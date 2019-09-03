<?php
/**
 * 运费
 * @author leitao
 *
 */
class BillRecordClass extends BaseForm 
{
	public $mainModel = "BillRecord";
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName = "运费";
	
	public function __construct($id) 
	{
		if (!$id) return ;
		$model = CommonForms::model()->findByPK($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->billRecord;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建运费
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new BillRecord();
		$mainInfo->title_id = $data->title_id;
		$mainInfo->company_id = $data->company_id;
		$mainInfo->frm_common_id = $data->frm_common_id;
		$mainInfo->bill_type = $data->bill_type;
		$mainInfo->price = $data->price;
		$mainInfo->weight = $data->weight;
		$mainInfo->amount = $data->amount;
		$mainInfo->is_yidan = $data->is_yidan;
		$mainInfo->travel = $data->travel;
		$mainInfo->discount = $data->discount;
		$mainInfo->is_selected = $data->is_selected;
		if (!$mainInfo->insert()) return false;
		
		//日志
		$oldValue = "";
		$newValue = $mainInfo->datatoJson();
		$tableName = $this->mainModel;
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return $mainInfo;
	}
	
	/**
	 * 创建运费后操作
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm() 
	{
		//创建往来
		$type = "FYDJ"; //往来类型
		$turnover_direction = "need_pay"; //应付
		$big_type = "freight"; //分类
		$title_id = $this->mainInfo->title_id; //公司抬头
		$target_id = $this->mainInfo->company_id; //往来对端公司
		$amount = $this->mainInfo->weight; //重量
		$price = $this->mainInfo->price; //单价
		$fee = $this->mainInfo->amount; //总金额
		$common_forms_id = $this->commonForm->id; //对应单据id
		$ownered_by = $this->commonForm->owned_by;
		$description = "单号".$this->commonForm->form_sn."登记运费应付往来";
		
		$turnArray = compact('type', 'turnover_direction', 'big_type', 'title_id', 'target_id', 'amount', 'price', 'fee', 'common_forms_id', 'ownered_by', 'description');
		$result = Turnover::createBill($turnArray);
		
		//日志
		$oldValue = "";
		$newValue = $result->datatoJson();
		$tableName = "Turnover";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	/**
	 * 提交运费后操作
	 * @see BaseForm::afterSubmitForm()
	 */
	protected function afterSubmitForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$main = $this->mainInfo;
		$message = array();
		switch ($this->mainInfo->bill_type) 
		{
			case 'purchase': //采购运费
				$message['receivers'] = User::model()->getOperationList("采购运费:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了采购运费：".$baseform->form_sn.",请尽快审核。";
				break;
			case 'sales': //销售运费
				$message['receivers'] = User::model()->getOperationList("销售运费:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了销售运费：".$baseform->form_sn.",请尽快审核。";
				break;
			default: break;
		}
		$message['title'] = "运费通知";
		$message['type'] = "运费";
		$message['url'] = Yii::app()->createUrl('billRecord/index');
		$message['big_type']='ware';
		$res = MessageContent::model()->addMessage($message);
		
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status" => "submited"));
		
		//日志
		$oldValue = $turnover->datatoJson();;
		$newValue = $result->datatoJson();
		$tableName = "Turnover";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	/**
	 * 取消提交运费后操作
	 * @see BaseForm::afterCancelSubmitForm()
	 */
	protected function afterCancelSubmitForm() 
	{
		$main = $this->mainInfo;
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status" => "unsubmit"));
		//修改销售单运费金额
		if($main->bill_type == "sales"){
			$sales = $main->relationForm->sales;
			$sales->shipment = 0;
			$sales->update();
		}
		
		//日志
		$oldValue = $turnover->datatoJson();;
		$newValue = $result->datatoJson();
		$tableName = "Turnover";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改运费
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		$mainInfo = $this->mainInfo;
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$this->mainInfo->title_id = $data->title_id;
				$this->mainInfo->company_id = $data->company_id;
				$this->mainInfo->frm_common_id = $data->frm_common_id;
				$this->mainInfo->price = $data->price;
				$this->mainInfo->weight = $data->weight;
				$this->mainInfo->amount = $data->amount;
				$this->mainInfo->is_yidan = $data->is_yidan;
				$this->mainInfo->travel = $data->travel;
				$this->mainInfo->discount = $data->discount;
				$this->mainInfo->is_selected = $data->is_selected;
				break;
			case 'submited': 
				$this->mainInfo->travel = $data->travel;
				break;
			default: break;
		}
		if (!$this->mainInfo->update()) return false;
		
		//日志
		$oldValue = $mainInfo->datatoJson();
		$newValue = $this->mainInfo->datatoJson();
		$tableName = $this->mainModel;
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 修改运费后操作
	 * @see BaseForm::afterupdateForm()
	 */
	protected function afterupdateForm() 
	{
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		if (!$turnover) return ;
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$title_id = $this->mainInfo->title_id; //公司抬头
				$target_id = $this->mainInfo->company_id; //往来对端公司
				$amount = $this->mainInfo->weight; //重量
				$price = $this->mainInfo->price; //单价
				$fee = $this->mainInfo->amount; //总金额
				$ownered_by = $this->commonForm->owned_by;
				$turnArray = compact('title_id', 'target_id', 'amount', 'price', 'fee', 'ownered_by');
				break;
			case 'submited': 
				$ownered_by = $this->commonForm->owned_by;
				$turnArray = compact('ownered_by');
				break;
			default: break;
		}
		$result = Turnover::updateBill($turnover->id, $turnArray);
		
		//日志
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$tableName = "Turnover";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 作废表单 --------------------------------------------------
	/**
	 * 作废运费后操作
	 * @see BaseForm::afterDeleteForm()
	 */
	protected function afterDeleteForm() 
	{
		//作废往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		if (!$turnover) return ;
		$result = Turnover::updateBill($turnover->id, array('status' => "delete"));
		
		//日志
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$tableName = "Turnover";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return true;
	}
	
	//-------------------------------------------------- 审核表单 --------------------------------------------------
	/**
	 * 审核运费通过后操作
	 * @see BaseForm::afterApproveForm()
	 */
	protected function afterApproveForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$main = $this->mainInfo;
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($this->mainInfo->bill_type)
			{
				case 'purchase': //采购运费
					$message['content'] = "您的采购运费：".$baseform->form_sn."已经审核通过。";
					break;
				case 'sales': //销售运费
					$message['content'] = "您的销售运费：".$baseform->form_sn."已经审核通过。";
					break;
				default: break;
			}
			$message['type'] = "运费";
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		
		$relationForm = $this->mainInfo->relationForm;
		//修改销售单运费金额
		if($main->bill_type == "sales"){
			$sales = $main->relationForm->sales;
			$sales->shipment = $main->amount/$sales->weight;
			$sales->update();
		}
		switch ($relationForm->form_type) 
		{
			case 'CGD':
				$purchase=$relationForm->purchase;
				$purchase->shipment= $this->mainInfo->price;
				$purchase->update();
				ProfitChange::createNew('purchase',$this->mainInfo->frm_common_id,1);
// 				$form = new Purchase($relationForm->id);
// 				$details = $purchase->details;
// 				if (!is_array($details) || count($details) <= 0) return ;
// 				foreach ($details as $each) 
// 				{
// 					//日志
// 					$oldValue = $each->datatoJson();
// 					$form->caclulateItemCost($each->id); //计算采购成本
// 					$newValue = $each->datatoJson();
// 					$tableName = $form->mainModel;
// 					$dataArray = compact('tableName', 'newValue', 'oldValue');
// 					$this->dataLog($dataArray);
// 				}
				break;
			case 'XSD': 
				$sales=$relationForm->sales;
				$sales->shipment=$this->mainInfo->price;
				$sales->update();
				ProfitChange::createNew('sale',$this->mainInfo->frm_common_id,0);
				break;
			default: break;
		}
	}
	
	/**
	 * 审核运费拒绝后操作
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
			switch ($this->mainInfo->bill_type)
			{
				case 'purchase': //采购运费
					$message['content'] = "您的采购运费：".$baseform->form_sn."审核已被拒绝。";
					break;
				case 'sales': //销售运费
					$message['content'] = "您的销售运费：".$baseform->form_sn."审核已被拒绝。";
					break;
				default: break;
			}
			$message['type'] = "运费";
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status" => "unsubmit"));
		
		//日志
		$oldValue = $turnover->datatoJson();;
		$newValue = $result->datatoJson();
		$tableName = "Turnover";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}	
	
	/**
	 * 取消审核运费后操作
	 * @see BaseForm::afterCancelApproveForm()
	 */
	protected function afterCancelApproveForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$main = $this->mainInfo;
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($this->mainInfo->bill_type)
			{
				case 'purchase': //采购运费
					$message['content'] = "您的采购运费：".$baseform->form_sn."已被取消审核。";
					break;
				case 'sales': //销售运费
					$message['content'] = "您的销售运费：".$baseform->form_sn."已被取消审核。";
					break;
				default: break;
			}
			$message['type'] = "运费";
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		
		//修改销售单运费金额
		if($main->bill_type == "sales"){
			$sales = $main->relationForm->sales;
			$sales->shipment = 0;
			$sales->update();
		}
		$relationForm = $this->mainInfo->relationForm;
		switch ($relationForm->form_type)
		{
			case 'CGD':
				$purchase=$relationForm->purchase;
				$purchase->shipment=0;
				$purchase->update();
				ProfitChange::createNew('purchase',$this->mainInfo->frm_common_id,1);
				break;
			case 'XSD':
				$sales=$relationForm->sales;
				$sales->shipment=0;
				$sales->update();
				ProfitChange::createNew('sale',$this->mainInfo->frm_common_id,0);
				break;
			default: break;
		}
		
	}
}
