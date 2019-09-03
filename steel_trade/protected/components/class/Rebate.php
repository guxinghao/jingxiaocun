<?php
/**
 * 销售折让/高开折让
 * @author leitao
 *
 */
class Rebate extends BaseForm
{
	public $mainModel = 'FrmRebate'; 
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName = "";
	
	public function __construct($id, $type) 
	{
		switch ($type) 
		{
			case 'XSZR': 
				$this->busName = "销售折让";
				break;
			case 'GKZR': 
				$this->busName = "高开折让";
				break;
			case 'CGZR':
				$this->busName = "采购折让";
				break;
			default: 
				return false;
				break;
		}
		if (!$id) return ;
		$model = CommonForms::model()->with('rebate')->findByPk($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->rebate;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建折让
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new FrmRebate();
		$mainInfo->company_id = $data->company_id;
		$mainInfo->client_id = $data->client_id;
		$mainInfo->title_id = $data->title_id;
		$mainInfo->type = $data->type;
		$mainInfo->amount = $data->amount;
		$mainInfo->is_yidan = $data->is_yidan;
		$mainInfo->comment = $data->comment;
		$mainInfo->start_at = $data->start_at;
		$mainInfo->end_at = $data->end_at;
		$mainInfo->team_id = $data->team_id;
		if ($mainInfo->insert()) 
		{
			$relation = $data->relation;
			if (is_array($relation) && count($relation) > 0) 
			{
				foreach ($relation as $each) 
				{
					$rebate_relation = new RebateRelation();
					$rebate_relation->sales_id = $each->sales_id;
					$rebate_relation->rebate_id = $mainInfo->id;
					$rebate_relation->insert();
				}
			}
			
			//新增日志
			$tableName = "FrmRebate";
			$oldValue = "";
			$newValue = $mainInfo->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			return $mainInfo;
		}
	}
	
	/**
	 * 创建折让后操作
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm() 
	{	
		//创建折让往来
		$type = $this->commonForm->form_type; //XSZR | GKZR | CGZR
		$turnover_direction = "need_pay"; //应付
		$title_id = $this->mainInfo->title_id; //公司抬头
		$target_id = $this->mainInfo->company_id; // 供应商/客户
		$client_id= $this->mainInfo->client_id; // 供应商/客户
		$is_yidan = $this->mainInfo->is_yidan?1:0;
// 		$amount = 1;
		$fee = $this->mainInfo->amount; //金额
// 		$price = $fee;
		$common_forms_id = $this->commonForm->id; //对应单据id
		$created_by = $this->commonForm->created_by;
		$ownered_by = $this->commonForm->owned_by;
		$created_at = strtotime($this->commonForm->form_time);
		switch ($this->mainInfo->type) 
		{
			case 'shipment': 
				$description = "采购折让";
				$big_type="purchase";
				$turnover_direction="need_charge";
				break;
			case 'shipment_sale': 
				$description = "销售运费登记";
				$big_type = "freight";
				break;
			case 'high': 
				$turnover_direction = "need_charge"; //应收
				$description = "高开折让";
				$big_type = "gaokai";
				break;
			case 'sale': 
				$description = "销售折让";
				$big_type = "sales";
				break;
			default: 
				$big_type = "sales";
				break;
		}
		
		$turnarray = compact('type', 'turnover_direction', 'is_yidan','big_type', 'title_id', 'target_id','client_id','amount', 'price', 'fee', 'common_forms_id', 'created_by', 'ownered_by', 'description','created_at');
		$result = Turnover::createBill($turnarray);
		//新增日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = "";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	/**
	 * 提交折让后操作
	 * @see BaseForm::afterSubmitForm()
	 */
	protected function afterSubmitForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$message = array();
		switch ($baseform->form_type)
		{
			case 'XSZR': //销售折让
				$message['receivers'] = User::model()->getOperationList("销售折让:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了销售折让：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "销售折让通知";
				$message['type'] = "销售折让";
				$message['url'] = Yii::app()->createUrl('rebate/index', array('type' => $this->mainInfo->type));
				$message['big_type']='sale';
				$res = MessageContent::model()->addMessage($message);
				break;
			case 'GKZR': //高开折让
// 				$message['receivers'] = User::model()->getOperationList("高开折让:审核");
// 				$message['content'] = "业务员：".$baseform->belong->nickname."提交了高开折让：".$baseform->form_sn.",请尽快审核。";
// 				$message['title'] = "高开折让通知";
// 				$message['type'] = "高开折让";
				break;
			case 'CGZR'://采购折让
				$message['receivers'] = User::model()->getOperationList("采购折让:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了采购折让：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "采购折让通知";
				$message['type'] = "采购折让";
				$message['url'] = Yii::app()->createUrl('rebate/index', array('type' => $this->mainInfo->type));
				$message['big_type']='purchase';
				$res = MessageContent::model()->addMessage($message);
				break;
			default: break;
		}
		
		
		//修改销售折让往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "submited"));
	
		//新增提交日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		return true;
	}
	
	//-------------------------------------------------- 取消提交表单 --------------------------------------------------
	/**
	 * 取消提交折让后操作
	 * @see BaseForm::afterCancelSubmitForm()
	 */
	protected function afterCancelSubmitForm() 
	{
		$baseform = $this->commonForm;
		//修改折让往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "unsubmit"));
		
		//新增取消提交日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		return true;
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改折让
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		//修改前信息
		$oldValue = $this->mainInfo->datatoJson();
		//状态为“已提交”时，只能修改部分信息
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': //状态为“未提交”时，能修改所有信息
				$this->mainInfo->company_id = $data->company_id;
				$this->mainInfo->client_id = $data->client_id;
				$this->mainInfo->title_id = $data->title_id;
				$this->mainInfo->type = $data->type;
				$this->mainInfo->amount = $data->amount;
				$this->mainInfo->is_yidan = $data->is_yidan;
				$this->mainInfo->comment = $data->comment;
				$this->mainInfo->start_at = $data->start_at;
				$this->mainInfo->end_at = $data->end_at;
				$this->mainInfo->team_id = $data->team_id;
				break;
			case 'submited': //状态为“已提交”时，只能修改备注
				$this->mainInfo->comment = $data->comment;
				break;
			default: //状态为“已审核”时，无法修改
				break;
		}
		
		//修改折让
		if ($this->mainInfo->update()) 
		{
			$relation = $data->relation;
			if (is_array($this->mainInfo->rebateRelation) && count($this->mainInfo->rebateRelation) > 0) 
			{
				$id_array = array();
				foreach ($relation as $item) 
				{
					if ($item->id) 
						array_push($id_array, $item->id);
				}
				//删除关联
				foreach ($this->mainInfo->rebateRelation as $each) 
				{
					if (!in_array($each->id, $id_array))
						$each->delete();
				}
			}
			
			if (is_array($relation) && count($relation) > 0) 
			{
				foreach ($relation as $relation_each) 
				{
					if ($relation_each->id) continue;
					//新建
					$relation_data = new RebateRelation();
					$relation_data->sales_id = $relation_each->sales_id;
					$relation_data->rebate_id = $this->mainInfo->id;
					$relation_data->insert();
				}
			}
			
			//新增修改日志
			$tableName = "FrmRebate";
			$newValue = $this->mainInfo->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			return true;
		}
		return false;
	}
	
	/**
	 * 修改折让后操作
	 * @see BaseForm::afterupdateForm()
	 */
	protected function afterupdateForm()
	{
		//修改折让往来
		if ($this->commonForm->form_status == "unsubmit") 
		{
			$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
			if (!$turnover) 
				return ;
			
			//修改前信息
			$oldValue = $turnover->datatoJson();
			//修改往来
			$turnover_type = $this->commonForm->form_type; //XSZR /GKZR
			switch ($this->mainInfo->type) 
			{
				case 'high': 
					$turnover_direction = "need_charge"; //应收
					break;
				case 'shipment':
					$turnover_direction ='need_charge';
					break;
				default: 
					$turnover_direction = "need_pay"; //应付
					break;
			}
			$title_id = $this->mainInfo->title_id; //公司抬头
			$target_id = $this->mainInfo->company_id; // 供应商/客户
			$client_id = $this->mainInfo->client_id; // 供应商/客户
			$is_yidan=$this->mainInfo->is_yidan?1:0;
// 			$amount = 1;
			$fee = $this->mainInfo->amount; //金额
// 			$price = $fee;
			$common_forms_id = $this->commonForm->id; //对应单据id
			$created_by = $this->commonForm->created_by;
			$ownered_by = $this->commonForm->owned_by;
			$created_at = strtotime($this->commonForm->form_time);
			switch ($this->mainInfo->type)
			{
				case 'shipment':
					$description = "采购折让";
					$big_type="purchase";
					break;
				case 'shipment_sale':
					$description = "销售运费登记";
					$big_type = "freight";
					break;
				case 'high':
					$description = "高开折让";
					$big_type = "gaokai";
					break;
				case 'sale':
					$description = "销售折让";
					$big_type = "sales";
					break;
				default:
					$big_type = "sales";
					break;
			}
			$turnarray = compact('turnover_type', 'turnover_direction','is_yidan', 'big_type', 'title_id', 'target_id','client_id','amount', 'price', 'fee', 'common_forms_id', 'created_by', 'ownered_by', 'description','created_at');
			$result = Turnover::updateBill($turnover->id, $turnarray);
			//新增修改日志
			$tableName = "Turnover";
			$newValue = $result->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
		}
	}
	
	//-------------------------------------------------- 作废表单 --------------------------------------------------
	/**
	 * 作废折让后操作
	 * @see BaseForm::afterDeleteForm()
	 */
	protected function afterDeleteForm() 
	{
		if ($this->commonForm->is_deleted != 1) return ;
		
		//作废折让往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "delete"));
		
		//新增作废日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 审核通过 --------------------------------------------------
	public function approveForm()
	{
		if (!$this->commonForm) return false; //表单为空
	
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
			$message = array();
			if ($baseform->form_type == 'XSZR')
			{
				switch ($baseform->form_status)
				{
					case 'submited':
						$this->commonForm->form_status = 'approved_1';
						$description = '业务经理审核<span style="color: green;">通过</span>';
						//发送消息
						$message['receivers'] = User::model()->getOperationList("销售折让:财务主管审核");
						$message['content'] = "业务员：".$baseform->belong->nickname."的销售折让：".$baseform->form_sn."需要审核,请尽快审核。";
						break;
					case 'approved_1':
						$this->commonForm->form_status = 'approve';
						$description = '财务主管审核<span style="color: green;">通过</span>';
						$message['receivers'] = $baseform->owned_by;
						$message['content'] = "您的销售折让：".$baseform->form_sn."已经审核通过。";
						break;					
					default:
						return false;
						break;
				}
				//创建审核记录
				$approve_log = new BillApproveLog();
				$approve_log->form_id = $this->commonForm->id;
				$approve_log->description = $description;
				$approve_log->status = 'approve';
				$approve_log->created_by = currentUserId();
				$approve_log->created_at = time();
				$approve_log->insert();			
				$message['title'] = "折让通知";
				$message['url'] = Yii::app()->createUrl('rebate/index',array('type'=>"sale"));
				$message['type'] = "销售折让";
				$message['big_type']='sale';
				$res = MessageContent::model()->addMessage($message);				
			}else{
				$this->commonForm->form_status = 'approve';
			}
			$this->commonForm->approved_at = time();
			$this->commonForm->approved_by = currentUserId();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
				
			//日志
			$tableName = "commonForms";
			$newValue = $this->commonForm->datatoJson();
			$oldValue = $baseform->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);			
			$this->afterApproveForm();			
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack(); //事务回滚
			return "操作失败";
		}	
		//新增日志
		$operation = "审核";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	
	
	
	/**
	 * 审核销售折让通过后操作
	 * @see BaseForm::afterApproveForm()
	 */
	protected function afterApproveForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type)
			{
				case 'XSZR': //销售折让
// 					$message['content'] = "您的销售折让：".$baseform->form_sn."已经审核通过。";
// 					$message['type'] = "销售折让";
					break;
				case 'GKZR': //高开折让
					$message['content'] = "您的高开折让：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "高开折让";
// 					$res = MessageContent::model()->addMessage($message);
					break;
				case 'CGZR'://采购折让
					$message['content'] = "您的采购折让：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "采购折让";
					$message['big_type']='purchase';
					$res = MessageContent::model()->addMessage($message);
					break;
				default: break;
			}
			
		}
		
		FrmRebate::shareEqually($this->commonForm->id); //均摊
		$main = $this->mainInfo;
		//设置销售单均摊
		if($baseform->form_type == "XSZR"){
			$relation = $main->rebateRelation;
			$money = $main->amount;
			$weight = 0;
			if($relation){
				foreach ($relation as $li){
					$sales = $li->sales_form->sales;
					$weight += $sales->weight;
				}
				if($weight > 0){
					$price = $money/$weight;
					foreach ($relation as $li){
						$sales = $li->sales_form->sales;
						$sales->rebate_fee = $price;
						$sales->update();
					}
				}
			}
		}
// 		//添加可开票信息
// 		$model = new DetailForInvoice();
// 		$model->type = 'rebate';
// 		$model->form_id = $this->commonForm->id;
// 		$model->checked_weight = 0;
// 		$model->checked_money = 0;
// 		$model->weight = 0;
// 		$model->money = $this->mainInfo->amount;
// 		$model->title_id = $this->mainInfo->title_id;
// 		$model->company_id = $this->mainInfo->company_id;
// 		$model->insert();
		
		//修改销售折让往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$created_by = $this->commonForm->created_by;
		$ownered_by = $this->commonForm->owned_by;
		$confirmed=1;
		
		$turnarray = compact('created_by', 'ownered_by', 'account_by','confirmed');
		$result = Turnover::updateBill($turnover->id, $turnarray);

		//新增审核日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 审核拒绝 --------------------------------------------------
	
	public function refuseForm()
	{
		if (!$this->commonForm) return false;
	
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$message = array();
			$baseform = $this->commonForm;
			if ($baseform->form_type == 'XSZR')
			{
				switch ($baseform->form_status)
				{
					case 'submited':
						$description = '业务经理审核<span style="color: red;">拒绝</span>';
						$message['content'] = "您的销售折让：".$baseform->form_sn."被业务经理审核拒绝。";
						break;
					case 'approved_1':
						$description = '财务主管审核<span style="color: red;">拒绝</span>';
						$message['content'] = "您的销售折让：".$baseform->form_sn."被财务主管审核拒绝。";
						break;				
					default:
						return false;
						break;
				}
				$message['receivers'] = $baseform->owned_by;
				$message['title'] = "折让通知";
				$message['url'] = "";
				$message['type'] = "销售折让";
				$message['big_type']='sale';
				$res = MessageContent::model()->addMessage($message);
				//创建审核记录
				$approve_log = new BillApproveLog();
				$approve_log->form_id = $this->commonForm->id;
				$approve_log->description = $description;
				$approve_log->status = 'refuse';
				$approve_log->created_by = currentUserId();
				$approve_log->created_at = time();
				$approve_log->insert();
			}
				
			$this->commonForm->form_status = 'unsubmit';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
				
			//日志
			$tableName = "commonForms";
			$newValue = $this->commonForm->datatoJson();
			$oldValue = $baseform->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);			
			$this->afterRefuseForm();
				
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack(); //事务回滚
			return "操作失败";
		}
	
		//新增日志
		$operation = "拒绝";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	
	
	/**
	 * 拒绝销售折让后操作
	 * @see BaseForm::afterRefuseForm()
	 */
	protected function afterRefuseForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type)
			{
				case 'XSZR': //销售折让
// 					$message['content'] = "您的销售折让：".$baseform->form_sn."审核已被拒绝。";
// 					$message['type'] = "销售折让";
					break;
				case 'GKZR': //高开折让
					$message['content'] = "您的高开折让：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "高开折让";
// 					$res = MessageContent::model()->addMessage($message);
					break;
				case 'CGZR'://采购折让
					$message['content'] = "您的采购折让：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "采购折让";
					$message['big_type']='purchase';
					$res = MessageContent::model()->addMessage($message);
					break;
				default: break;
			}			
		}
		
		//修改销售折让往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "unsubmit"));
		
		//新增审核拒绝日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 取消审核 --------------------------------------------------
	/**
	 * 表单取消审核
	 */
	public function cancelApproveForm()
	{
		if ($this->commonForm == null) return false;//表单为空
// 		if ($this->commonForm->form_status != 'approve') return;//表单状态不是提交
		 
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//开票数据
			$model=DetailForInvoice::model()->findAll('form_id='.$this->commonForm->id);
			if($model)
			{
				$flag=false;
				foreach ($model as $each)
				{
					if($each->checked_weight>0){
						$flag=true;	break;
					}
				}
				if($flag){return -1;}else{
					foreach ($model as $e)
					{
						$e->delete();
					}
				}
			}
			$baseform=$this->commonForm;
			if ($baseform->form_type == 'XSZR')
			{
				$message = array();
				switch ($baseform->form_status)
				{
					case 'approved_1':
						$this->commonForm->form_status = 'submited';
						$description = '业务经理审核<span style="color: blue;">取消</span>';
						$message['content'] = "您的销售折让：".$baseform->form_sn."被业务经理取消审核。";
						break;					
					case 'approve':
						$this->commonForm->form_status = 'submited';
						$description = '财务主管审核<span style="color: blue;">取消</span>';
						$message['content'] = "您的销售折让：".$baseform->form_sn."被财务主管取消审核。";
						break;
					default:
						return false;
						break;
				}
				//创建审核记录
				$approve_log = new BillApproveLog();
				$approve_log->form_id = $this->commonForm->id;
				$approve_log->description = $description;
				$approve_log->status = 'cancle';
				$approve_log->created_by = currentUserId();
				$approve_log->created_at = time();
				$approve_log->insert();
				
				$message['receivers'] = $baseform->owned_by;
				$message['title'] = "折让通知";
				$message['url'] = "";
				$message['type'] = "销售折让";
				$message['big_type']='sale';
				$res = MessageContent::model()->addMessage($message);
				
			}else{
				$this->commonForm->form_status = 'submited';
			}		
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
// 			$this->commonForm->form_status = 'submited';
			$this->commonForm->approved_at = 0;
			$this->commonForm->approved_by = 0;
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
	
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
	
			$this->afterCancelApproveForm();
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return '操作失败';
		}
		//发送消息
		 
		//新增日志
		$operation = "取消审核";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 取消审核折让后操作
	 * @see BaseForm::afterCancelApproveForm()
	 */
	protected function afterCancelApproveForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type)
			{
				case 'XSZR': //销售折让
					$message['content'] = "您的销售折让：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "销售折让";
					$message['big_type']='sale';
					$res = MessageContent::model()->addMessage($message);
					break;
				case 'GKZR': //高开折让
					$message['content'] = "您的高开折让：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "高开折让";					
					break;
				case 'CGZR':
					$message['content'] = "您的采购折让：".$baseform->form_sn."审核已被取消审核。";
					$message['type'] = "采购折让";
					$message['big_type']='purchase';
					$res = MessageContent::model()->addMessage($message);
					break;
				default: break;
			}
						
			//往来
			$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
			$confirmed=0;			
			$turnarray = compact('confirmed');
			$result = Turnover::updateBill($turnover->id, $turnarray);
		}
		$main = $this->mainInfo;
		//设置销售单均摊
		if($baseform->form_type == "XSZR"){
			$relation = $main->rebateRelation;
			if($relation){
				foreach ($relation as $li){
					$sales = $li->sales_form->sales;
					$sales->rebate_fee = 0;
					$sales->update();
				}
			}
		}
	}
	
}