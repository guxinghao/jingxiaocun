<?php
/**
 * 收付款登记
 * @author leitao
 *
 */
class FormBill extends BaseForm
{
	public $mainModel = "FrmFormBill";
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName = "";
	
	public function __construct($type, $id) 
	{
		if ($type == 'FKDJ') $this->busName = "付款登记"; 
		elseif ($type == 'SKDJ') $this->busName = "收款登记";
		if (!$id) return ;
		$model = CommonForms::model()->with('formBill')->findByPk($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->formBill;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建付款登记
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{	
		$mainInfo = new FrmFormBill();
		$mainInfo->bill_type = $data->bill_type;
		$mainInfo->is_yidan = $data->is_yidan;
		$mainInfo->pay_type = $data->pay_type;
		$mainInfo->company_id = $data->company_id;
		$mainInfo->client_id = $data->client_id;
		$mainInfo->bank_info_id = $data->bank_info_id;
		$mainInfo->pledge_company_id = $data->pledge_company_id;
		$mainInfo->pledge_bank_info_id = $data->pledge_bank_info_id;
		$mainInfo->title_id = $data->title_id;
		$mainInfo->dict_bank_info_id = $data->dict_bank_info_id;
		$mainInfo->weight = $data->weight;
		$mainInfo->fee = $data->fee;
		$mainInfo->reach_at = $data->reach_at;
		$mainInfo->rebate_form_id = $data->rebate_form_id;
		$mainInfo->purpose = $data->purpose;
		if ($mainInfo->insert()) //保存
		{
			//创建关联信息
			$relation = $data->relation;
			if (is_array($relation) && count($relation) > 0) 
			{
				foreach ($relation as $each) 
				{
					$fbr = new FormBillRelation();
					$fbr->bill_id = $mainInfo->id;
					$fbr->common_id = $each->common_id;
					if (!$fbr->insert()) continue;
					switch ($mainInfo->bill_type) 
					{
						case 'GKFK': //高开
							$highopen = $fbr->bill_form->highopen;
							$highopen->discount = $each->discount;
							$highopen->is_selected = 1;
							$highopen->update();
							break;
						case 'YF': //运费
							$billRecord = $fbr->bill_form->billRecord;
							$billRecord->is_selected = 1;
							$billRecord->update();
							break;
						default: 
							break;
					}
				}
			}
			
			//新增日志
			$tableName = "FrmFormBill";
			$newValue = $mainInfo->datatoJson();
			$oldValue = "";
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			return $mainInfo;
		}
	}
	
	/**
	 * 创建付款登记后操作
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm() 
	{
		//创建付款登记往来
		$type = $this->commonForm->form_type;
		switch ($type) 
		{
			case 'FKDJ': 
				$turnover_direction = "payed"; //付款
// 				$big_type = "purchase"; //分类
				break;
			case 'SKDJ': 
				$turnover_direction = "charged"; //收款
// 				$big_type = "sales"; //分类
				break;
			default: 
				break;
		}
		$created_at=strtotime($this->commonForm->form_time);
		$title_id = $this->mainInfo->title_id; //公司抬头
		$target_id = $this->mainInfo->company_id; //结算单位
		$client_id = $this->mainInfo->client_id; //结算单位
// 		$amount = $this->mainInfo->weight > 0 ? $this->mainInfo->weight : 1; //重量
		$fee = $this->mainInfo->fee; //总金额
// 		$price = $fee; //单价
		$is_yidan = $this->mainInfo->is_yidan; //乙单
		$common_forms_id = $this->commonForm->id; //对应单据id
		$created_by = $this->commonForm->created_by;
		$ownered_by = $this->commonForm->owned_by;
		$proxy_company_id = "";
		$description = "单号：".$this->commonForm->form_sn.",";
		switch ($this->mainInfo->bill_type) 
		{
			case 'CGFK': //采购付款
				$description .= "采购付款";
				$big_type = "purchase";
				break;
			case 'XSSK': //销售收款
				$description .= "销售收款";
				$big_type = "sales";
				break;
			case 'XSTH': //销售退货付款
				$description .= "销售退货付款";
				$big_type = 'sales';
				break;
			case 'CGTH': //采购退货收款
				$description = "采购退货收款";
				$big_type = "purchase";
				break;
			case 'XSZR': //销售折让
				$description .= "折让付款";
				$big_type = "sales";
				break;
			case 'GKZR': //高开折让
				$description .= "高开折让";
				$big_type = 'gaokai';
				break;
			case 'GKFK': //高开付款
				$description .= "高开付款";
				$big_type = 'gaokai';
				break;
			case 'DLFK': //代理付款
				$description .= "代理付款";
				$proxy_company_id = $this->mainInfo->pledge_company_id; //托盘公司：代理付费公司
				$big_type = "purchase";
				break;
			case 'DLSK': //代理收款
				$description .= "代理收款";
				$proxy_company_id = $this->mainInfo->pledge_company_id; //托盘公司：代理收费公司
				$big_type = "sales";
				break;
			case 'TPYF': //托盘预付
				$description .= "托盘预付";
				$big_type = "purchase";
				break;
			case 'TPSH': //托盘赎回
				$description .= "赎回付款";
				$proxy_company_id = $target_id;
				$big_type = "purchase";
				break;
			case 'YF': //运费
				$description .= "运费付款";
				$big_type = 'freight';
				break;
			case 'CKFL': //仓库返利
				$description .= "仓库返利";
				$big_type = 'warehouse';
				break;
			case 'GCFL': //钢厂返利
				$description .= "钢厂返利";
				$big_type = 'steelmill';
				break;
			case 'CCFY': //仓储费用
				$description .= "仓储费用";
				$big_type = 'warehouse';
				break;
			case 'BZJ': //保证金
				$description = "保证金"; 
				$big_type = 'purchase';
				break;
			default: 
				$description .= "";
				break;
		}
		//往来
		$turnarray = compact('type', 'turnover_direction', 'big_type', 'title_id', 'target_id','client_id','proxy_company_id', 'amount', 'price', 'fee', 'is_yidan', 'common_forms_id', 'created_by', 'ownered_by', 'description','created_at');
		$result = Turnover::createBill($turnarray);
		if (!$result) return false;
		
		//新增日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = "";
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return true;
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	/**
	 * 提交付款登记后操作
	 * @see BaseForm::afterSubmitForm()
	 */
	protected function afterSubmitForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$message = array();
		switch ($baseform->form_type) 
		{
			case 'FKDJ': //付款
				$message['receivers'] = User::model()->getOperationList("付款登记:业务经理审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了付款登记：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "付款登记通知";
				$message['type'] = "付款登记";
				break;
			case 'SKDJ': //收款
				$message['receivers'] = User::model()->getOperationList("收款登记:业务经理审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了收款登记：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "收款登记通知";
				$message['type'] = "收款登记";
				break;
			default: break;
		}
		$message['url'] = Yii::app()->createUrl('formBill/index', array('type' => $this->commonForm->form_type));
		$message['big_type']='money';
		$res = MessageContent::model()->addMessage($message);
		
		//修改付款登记往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status" => "submited"));
		if (!$result) return false;

		//新增提交日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		//创建审核记录
		$approve_log = new BillApproveLog();
		$approve_log->form_id = $this->commonForm->id;
		$approve_log->status = 'submited';
		$approve_log->created_by = currentUserId();
		$approve_log->created_at = time();
		$approve_log->description = Yii::app()->user->nickname.'<span style="color: green;">已提交</span>';
		$approve_log->insert();
		
		return true;
	}
	
	//-------------------------------------------------- 取消提交表单 --------------------------------------------------
	/**
	 * 取消提交付款登记后操作
	 * @see BaseForm::afterCancelSubmitForm()
	 */
	protected function afterCancelSubmitForm() 
	{
		$this->busName = $this->commonForm->form_type == "FKDJ" ? "付款登记" : "收款登记";
		//修改付款登记往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status" => "unsubmit"));
		
		//新增取消提交日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		//创建审核记录
		$approve_log = new BillApproveLog();
		$approve_log->form_id = $this->commonForm->id;
		$approve_log->status = 'unsubmit';
		$approve_log->created_by = currentUserId();
		$approve_log->created_at = time();
		$approve_log->description = Yii::app()->user->nickname.'<span style="color: blue;">已取消提交</span>';
		$approve_log->insert();
		
		return true;
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改付款登记
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		//修改前信息
		$mainInfo = $this->mainInfo;
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$this->mainInfo->bill_type = $data->bill_type;
				$this->mainInfo->is_yidan = $data->is_yidan;
				$this->mainInfo->pay_type = $data->pay_type;
				$this->mainInfo->company_id = $data->company_id;
				$this->mainInfo->client_id = $data->client_id;
				$this->mainInfo->bank_info_id = $data->bank_info_id;
				$this->mainInfo->pledge_company_id = $data->pledge_company_id;
				$this->mainInfo->pledge_bank_info_id = $data->pledge_bank_info_id;
				$this->mainInfo->title_id = $data->title_id;
				$this->mainInfo->dict_bank_info_id = $data->dict_bank_info_id;
				$this->mainInfo->weight = $data->weight;
				$this->mainInfo->fee = $data->fee;
				$this->mainInfo->reach_at = $data->reach_at;
				$this->mainInfo->rebate_form_id = $data->rebate_form_id;
				$this->mainInfo->purpose = $data->purpose;
				break;
			case 'submited': 
				$this->mainInfo->reach_at = $data->reach_at;
				$this->mainInfo->purpose = $data->purpose;
				break;
			case 'approve': 
				$this->mainInfo->pay_type = $data->pay_type;
				$this->mainInfo->fee = $data->fee;
				$this->mainInfo->dict_bank_info_id = $data->dict_bank_info_id;
				$this->mainInfo->pledge_bank_info_id = $data->pledge_bank_info_id;
				$this->mainInfo->reach_at = $data->reach_at;
				$this->mainInfo->purpose = $data->purpose;
				break;
			default: 
				break;
		}
		if (!$this->mainInfo->update()) return false;
		
		$data_relation = $data->relation;
		if (is_array($this->mainInfo->relation) && count($this->mainInfo->relation) > 0)
		{
			$id_array = array();
			foreach ($data_relation as $item)
			{
				if ($item->id) array_push($id_array, $item->id);
			}
			//删除关联
			$relation = $this->mainInfo->relation;
			foreach ($relation as $each) 
			{
				if (in_array($each->id, $id_array)) continue;
				if (!$each->delete()) continue;
				switch ($this->mainInfo->bill_type)
				{
					case 'GKFK':
						$highopen = $each->bill_form->highopen;
						$highopen->discount = 0;
						$highopen->is_selected = 0;
						$highopen->update();
						break;
					case 'YF':
						$billRecord = $each->bill_form->billRecord;
						$billRecord->is_selected = 0;
						$billRecord->update();
						break;
					default:
						break;
				}
			}
		}
		
		//修改关联
		if (is_array($data_relation) && count($data_relation) > 0) 
		{
			foreach ($data_relation as $relation_each) 
			{
				if ($relation_each->id) continue;
				//新建
				$relation_data = new FormBillRelation();
				$relation_data->common_id = $relation_each->common_id;
				$relation_data->bill_id = $this->mainInfo->id;
				if (!$relation_data->insert()) continue;
				switch ($this->mainInfo->bill_type)
				{
					case 'GKFK':
						$highopen = $relation_data->bill_form->highopen;
						$highopen->discount = $relation_each->discount;
						$highopen->is_selected = 1;
						$highopen->update();
						break;
					case 'YF':
						$billRecord = $relation_data->bill_form->billRecord;
						$billRecord->is_selected = 1;
						$billRecord->update();
						break;
					default:
						break;
				}
			}
		}
		
		//日志
		$tableName = "FrmFormBill";
		$newValue = $this->mainInfo->datatoJson();
		$oldValue = $mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 修改付款登记后操作
	 * @see BaseForm::afterupdateForm()
	 */
	protected function afterupdateForm()
	{
		//修改付款登记往来
		if ($this->commonForm->form_status == "unsubmit") //状态为“未提交”时，才能修改往来
		{
			$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
			if (!$turnover) return ;
			
			//修改前信息
			$oldValue = $turnover->datatoJson(); 
			//修改往来
			$turnover_type = $this->commonForm->form_type;
			switch ($turnover_type) 
			{
				case 'FKDJ': 
					$turnover_direction = "payed"; 
// 					$big_type = "purchase";
					break; //付款
				case 'SKDJ': 
					$turnover_direction = "charged"; 
// 					$big_type = "sales";
					break; //收款
				default: break;
			}
			$created_at=strtotime($this->commonForm->form_time);
			$title_id = $this->mainInfo->title_id;
			$target_id = $this->mainInfo->company_id; //结算单位
			$client_id = $this->mainInfo->client_id; //结算单位
// 			$amount = $this->mainInfo->weight > 0 ? $this->mainInfo->weight : 1; //重量
			$fee = $this->mainInfo->fee; //总金额
// 			$price = $fee;
			$is_yidan = $this->mainInfo->is_yidan; //乙单
			$common_forms_id = $this->commonForm->id; //对应单据id
			$ownered_by = $this->commonForm->owned_by;
			$description = "单号：".$this->commonForm->form_sn.",";
			switch ($this->mainInfo->bill_type) 
			{
				case 'CGFK': //采购付款
					$description .= "采购付款";
					$big_type = "purchase";
					break;
				case 'XSSK': //销售收款
					$description .= "销售收款";
					$big_type = "sales";
					break;
				case 'XSTH': //销售退货付款
					$description .= "销售退货付款";
					$big_type = 'sales';
					break;
				case 'CGTH': //采购退货收款
					$description .= "采购退货收款";
					$big_type = "purchase";
					break;
				case 'XSZR': //销售折让
					$description .= "折让付款";
					$big_type = "sales";
					break;
				case 'GKZR': //高开折让
					$description .= "高开折让";
					$big_type = 'gaokai';
					break;
				case 'GKFK': //高开付款
					$description .= "高开付款";
					$big_type = 'gaokai';
					break;
				case 'DLFK': //代理付款
					$description .= "代理付款";
					$proxy_company_id = $this->mainInfo->pledge_company_id; //托盘公司：代理付费公司
					$big_type = "purchase";
					break;
				case 'DLSK': //代理收款
					$description .= "代理收款";
					$proxy_company_id = $this->mainInfo->pledge_company_id; //托盘公司：代理收费公司
					$big_type = "sales";
					break;
				case 'TPYF': //托盘预付
					$description .= "托盘预付";
					$big_type = "purchase";
					break;
				case 'TPSH': //托盘赎回
					$description .= "赎回付款";
					$proxy_company_id = $target_id;
					$big_type = "purchase";
					break;
				case 'YF': //运费
					$description .= "运费付款";
					$big_type = 'freight';
					break;
				case 'CKFL': //仓库返利
					$description .= "仓库返利";
					$big_type = 'warehouse';
					break;
				case 'GCFL': //钢厂返利
					$description .= "钢厂返利";
					$big_type = 'steelmill';
					break;
				case 'CCFY': //仓储费用
					$description .= "仓储费用";
					$big_type = 'warehouse';
					break;
				case 'BZJ': //保证金
					$description .= "保证金"; 
					$big_type = 'purchase';
					break;
				default: 
					$description .= "";
					break;
			}
			$turnarray = compact('turnover_type', 'turnover_direction', 'big_type', 'title_id', 'target_id','client_id', 'proxy_company_id', 'amount', 'price', 'fee', 'common_forms_id', 'is_yidan', 'ownered_by', 'description','created_at');
			$result = Turnover::updateBill($turnover->id, $turnarray); //状态：未提交
			//新增修改日志
			$tableName = "Turnover";
			$newValue = $result->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
		}
	}
	
	//-------------------------------------------------- 作废表单 --------------------------------------------------
	/**
	 * 作废付款登记后操作
	 * @see BaseForm::afterDeleteForm()
	 */
	protected function afterDeleteForm() 
	{
		if ($this->commonForm == null) return false; //表单为空
		if ($this->commonForm->is_deleted != 1) return ;
		
		//删除关联信息
		$relation = $this->mainInfo->relation;
		foreach ($relation as $each) 
		{
			switch ($this->mainInfo->bill_type)
			{
				case 'GKFK':
					$highopen = $each->bill_form->highopen;
					$highopen->discount = 0;
					$highopen->is_selected = 0;
					$highopen->update();
					break;
				case 'YF':
					$billRecord = $each->bill_form->billRecord;
					$billRecord->is_selected = 0;
					$billRecord->update();
					break;
				default:
					break;
			}
			$each->delete();
		}
		
		//作废付款登记往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(":common_forms_id" => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status"=>"delete"));


		//新增通知，告诉删除的单据的所属人
		$user=currentUserId();
		if($user!=$this->commonForm->owned_by){
			//发送消息
			$baseform=$this->commonForm;
			$message = array();
			$message['receivers'] = $this->commonForm->owned_by;
			$message['content'] = "您的单据{$baseform->form_sn}被".Yii::app()->user->nickname."删除";
			$message['title'] = "收付款通知";
			$message['url'] = '';
			$message['type'] = "收付款";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}

		
		//新增作废日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		return true;
	}
	
	//-------------------------------------------------- 审核通过 --------------------------------------------------
	/**
	 * 审核付款登记通过
	 * @see BaseForm::approveForm()
	 */
	 public function approveForm()
	{
		if (!$this->commonForm || $this->commonForm->form_type != 'FKDJ') return false;
		
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata = $this->commonForm;
			$oldJson = $olddata->datatoJson();
			$baseform = $this->commonForm;
			switch ($baseform->form_status) 
			{
				case 'submited': 
					$this->commonForm->form_status = 'approved_1';
					if ($this->mainInfo->bill_type == 'DLFK') $this->commonForm->form_status = 'approve';
					else{
						//发送消息
						$contents="付款登记单".$this->commonForm->form_sn."需要您审核。";
						$this->sendMassage("付款登记:财务主管审核",$contents);
					}
					$description = '业务经理审核<span style="color: green;">通过</span>';
					break;
				case 'approved_1': 
					$this->commonForm->form_status = 'approved_2';
					if ($this->mainInfo->fee < 10000) {
							$this->commonForm->form_status = 'approved_3';
							$contents="付款登记单".$this->commonForm->form_sn."需要您审核。";
							$this->sendMassage("付款登记:出纳审核",$contents);							
					}else{
						//发送消息
						$contents="付款登记单".$this->commonForm->form_sn."需要您审核。";
						$this->sendMassage("付款登记:总经理审核",$contents);
					}
					$description = '财务主管审核<span style="color: green;">通过</span>';
					break;
				case 'approved_2': 
					$this->commonForm->form_status = 'approved_3';
					$description = '总经理审核<span style="color: green;">通过</span>';
					//发送消息
					$contents="付款登记单".$this->commonForm->form_sn."需要您审核。";
					$this->sendMassage("付款登记:出纳审核",$contents);
					break;
				case 'approved_3': 
					$this->commonForm->form_status = 'approve';
					$description = '出纳审核<span style="color: green;">通过</span>';
					break;
				default: 
					return false;
					break;
			}
			$this->commonForm->approved_at = time();
			$this->commonForm->approved_by = currentUserId();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			//创建审核记录
			$approve_log = new BillApproveLog();
			$approve_log->form_id = $this->commonForm->id;
			$approve_log->status = 'approve';
			$approve_log->created_by = currentUserId();
			$approve_log->created_at = time();
			$approve_log->description = $description;
			$approve_log->insert();
			
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
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//发送消息
		
		//新增日志
		$operation = "审核";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 审核付款登记通过后操作
	 * @see BaseForm::afterApproveForm()
	 */
	protected function afterApproveForm() 
	{
		if (!$this->commonForm || $this->commonForm->form_type != 'FKDJ') return false;
		//发送消息
		$baseform = $this->commonForm;
		if ($baseform->form_status == 'approve' && Yii::app()->user->userid != $baseform->owned_by) 
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type)
			{
				case 'FKDJ': //付款
					$message['content'] = "您的付款登记：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "付款登记";
					break;
				case 'SKDJ': //收款
					$message['content'] = "您的收款登记：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "收款登记";
					break;
				default: break;
			}
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
		//如果是出纳审核，发送通知
		if($baseform->form_status == 'approve'){
			$message = array();
			$message['receivers'] = User::model()->getOperationList("出纳审核通知");
			$message['content'] = "付款登记：".$baseform->form_sn."已被出纳审核。";
			$message['title'] = "出纳审核";
			$message['url'] = "";
			$message['type'] = "付款";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	/*
	发送消息函数
	*/
	public function sendMassage($operation,$contents)
	{
		$baseform=$this->commonForm;
		$message = array();
		$message['receivers'] = User::model()->getOperationList($operation);
		$message['content'] =$contents;
		$message['title'] = "审核通知";
		$message['url'] = Yii::app()->createUrl('formBill/index', array('type' => $this->commonForm->form_type));
		$message['type'] = "付款登记";
		$message['big_type']='money';
		$res = MessageContent::model()->addMessage($message);

	}

	//-------------------------------------------------- 审核拒绝 --------------------------------------------------
	/**
	 * 审核付款登记拒绝
	 * @see BaseForm::refuseForm()
	 */
	public function refuseForm() 
	{
		if (!$this->commonForm || $this->commonForm->form_type != 'FKDJ') return false;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
			switch ($baseform->form_status) 
			{
				case 'submited': 
					$description = '业务经理审核<span style="color: red;">拒绝</span>';
					break;
				case 'approved_1': 
					$description = '财务主管审核<span style="color: red;">拒绝</span>';
					break;
				case 'approved_2': 
					$description = '总经理审核<span style="color: red;">拒绝</span>';
					break;
				case 'approved_3': 
					$description = '出纳审核<span style="color: red;">拒绝</span>';
					break;
				default: 
					return false;
					break;
			}
			$this->commonForm->form_status = 'unsubmit';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			//创建审核记录
			$approve_log = new BillApproveLog();
			$approve_log->form_id = $this->commonForm->id;
			$approve_log->status = 'refuse';
			$approve_log->created_by = currentUserId();
			$approve_log->created_at = time();
			$approve_log->description = $description;
			$approve_log->insert();
			
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
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//新增日志
		$operation = "拒绝";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	
	/**
	 * 审核付款登记拒绝后操作
	 * @see BaseForm::afterRefuseForm()
	 */
	protected function afterRefuseForm() 
	{
		if (!$this->commonForm || $this->commonForm->form_type != 'FKDJ') return false;
		//发送消息
		$baseform = $this->commonForm;
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type)
			{
				case 'FKDJ': //付款
					$message['content'] = "您的付款登记：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "付款登记";
					break;
				case 'SKDJ': //收款
					$message['content'] = "您的收款登记：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "收款登记";
					break;
				default: break;
			}
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
		
		//修改付款登记往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array("status" => "unsubmit"));
		
		//新增审核拒绝日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 取消审核 --------------------------------------------------
	/**
	 * 审核付款登记取消
	 * @see BaseForm::cancelApproveForm()
	 */
	public function cancelApproveForm() 
	{
		if (!$this->commonForm || $this->commonForm->form_type != 'FKDJ') return false;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
// 			switch ($baseform->form_status) 
// 			{
// 				case 'approved_1': 
// // 					$this->commonForm->form_status = 'submited';
// 					$description = '业务经理审核<span style="color: blue;">取消</span>';
// 					break;
// 				case 'approved_2': 
// // 					$this->commonForm->form_status = 'approved_1';
// 					$description = '财务主管审核<span style="color: blue;">取消</span>';
// 					break;
// 				case 'approved_3': 
// // 					$this->commonForm->form_status = 'approved_2';
// 					$description = '总经理审核<span style="color: blue;">取消</span>';
// 					if (in_array($this->mainInfo->bill_type, array('CGFK', 'TPSH')) && $this->mainInfo->fee < 10000)
// 					{
// // 						$this->commonForm->form_status = 'approved_1';
// 						$description = '出纳审核<span style="color: blue;">取消</span>';
// 					}
// 					break;
// 				case 'approve': 
// // 					$this->commonForm->form_status = 'approved_3';
// 					$description = '出纳审核<span style="color: blue;">取消</span>';
// 					if ($this->mainInfo->bill_type == 'DLFK') 
// 					{
// // 						$this->commonForm->form_status = 'submited';
// 						$description = '业务经理审核<span style="color: blue;">取消</span>';
// 					}
// 					break;
// 				default: 
// 					return false;
// 					break;
// 			}
			$description = Yii::app()->user->nickname.'审核<span style="color: blue;">取消</span>';
			$this->commonForm->form_status = 'submited';
			$this->commonForm->approved_at = 0;
			$this->commonForm->approved_by = 0;
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();

			//创建审核记录
			$approve_log = new BillApproveLog();
			$approve_log->form_id = $this->commonForm->id;
			$approve_log->status = 'cancle';
			$approve_log->created_by = currentUserId();
			$approve_log->created_at = time();
			$approve_log->description = $description;
			$approve_log->insert();
			
			//日志
			$tableName = "commonForms";
			$newValue = $this->commonForm->datatoJson();
			$oldValue = $baseform->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			
			$this->afterCancelApproveForm();
			$transaction->commit();
		} 
		catch (Exception $e) 
		{
			$transaction->rollBack(); //事务回滚
			return '操作失败';
		}
		//发送消息
		
		//新增日志
		$operation = "取消审核";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 审核付款登记取消后操作
	 * @see BaseForm::afterCancelApproveForm()
	 */
	protected function afterCancelApproveForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
// 		if (Yii::app()->user->userid != $baseform->owned_by && ((in_array($this->mainInfo->bill_type, array('CGFK', 'TPSH')) && $this->commonForm->form_status == 'approved_1' && $this->mainInfo->fee < 10000) || ($this->commonForm->form_status == 'approved_2'))) 
		if (Yii::app()->user->userid != $baseform->owned_by)
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type)
			{
				case 'FKDJ': //付款
					$message['content'] = "您的付款登记：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "付款登记";
					break;
				case 'SKDJ': //收款
					$message['content'] = "您的收款登记：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "收款登记";
					break;
				default: break;
			}
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	//-------------------------------------------------- 入账 --------------------------------------------------
	/**
	 * 入账
	 */
	public function accountedForm($data) 
	{
		if (!$this->commonForm) return false;
		$transaction = Yii::app()->db->beginTransaction();
		try {
			//$this->commonForm->created_at = $data['common']->created_at;
			$this->commonForm->form_status = "accounted";
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->form_time=date('Y-m-d',$this->mainInfo->reach_at);
			$this->mainInfo->account_at = time();
			$this->mainInfo->account_by = currentUserId();
			if ($this->commonForm->update() && $this->mainInfo->update()) 
			{
				//入账后操作
				$this->afterAccountedForm();
			}
			$transaction->commit();
		} 
		catch (Exception $e) 
		{
			$transaction->rollBack();//事务回滚
			return ;
		}
		//新增日志
		$operation = "入账";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 入账后操作
	 */
	protected function afterAccountedForm() 
	{
		if ($this->mainInfo->account_by == 0) return false;
		if (!$this->mainInfo->dict_bank_info_id) return false;
		$main = $this->mainInfo;
		//入账日志
		$billLog = new FrmBillLog();
		$billLog->form_id = $this->commonForm->id;
		$billLog->form_sn = $this->commonForm->form_sn;
		$billLog->title_id = $this->mainInfo->title_id;
		$billLog->dict_bank_id = $this->mainInfo->dict_bank_info_id;
		$billLog->company_id = $this->mainInfo->company_id;
		$billLog->bank_id = $this->mainInfo->bank_info_id;
		switch ($this->commonForm->form_type)
		{
			case 'FKDJ': //付款
				$billLog->account_type = "out"; //出账
				if($main->bill_type == "CGFK"){
					$billLog->bill_type = 2;
				}else if($main->bill_type == "XSTH"){
					$billLog->bill_type = 8;
				}else if($main->bill_type == "XSZR"){
					$billLog->bill_type = 9;
				}else if($main->bill_type == "TPSH"){
					$billLog->bill_type = 11;
				}else{
					$billLog->bill_type = 2;
				}
				
				if ($this->mainInfo->pay_type == 'adjust') break;
				if (in_array($this->mainInfo->bill_type, array('DLFK', 'DLSK')) && $this->mainInfo->pledge_bank_info_id)
				{
					//托盘公司账户
					$dictBankInfo = BankInfo::model()->findByPK($this->mainInfo->pledge_bank_info_id);
					$dictBankInfo->money -= $this->mainInfo->fee;
					$dictBankInfo->update();
				}
				else
				{
					//公司账户
					$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_info_id);
					$dictBankInfo->money -= $this->mainInfo->fee;
					$dictBankInfo->update();
				}
// 				//结算账户
// 				$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_info_id);
// 				$bankInfo->money += $this->mainInfo->fee;
// 				$bankInfo->update();
				break;
			case 'SKDJ': //收款
				$billLog->account_type = "in"; //入账
				if($main->bill_type == "XSSK"){
					$billLog->bill_type = 1;
				}else if($main->bill_type == "CGTH"){
					$billLog->bill_type = 7;
				}else{
					$billLog->bill_type = 1;
				}
				if ($this->mainInfo->pay_type == 'adjust') break;
// 				//结算账户
// 				$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_info_id);
// 				$bankInfo->money -= $this->mainInfo->fee;
// 				$bankInfo->update();
				//公司账户
				$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_info_id);
				$dictBankInfo->money += $this->mainInfo->fee;
				$dictBankInfo->update();
				break;
			default: break;
		}
		$billLog->fee = $this->mainInfo->fee;
		$billLog->pay_type = $this->mainInfo->pay_type;
		$billLog->account_by = $this->mainInfo->account_by;
		$billLog->created_at = time();
		$billLog->reach_at = $this->mainInfo->reach_at;
		$billLog->insert();
		
		//修改付款登记往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$created_by = $this->commonForm->created_by;
		$ownered_by = $this->commonForm->owned_by;
		$account_by = $this->mainInfo->account_by;
		$status = "accounted";
		$created_at=strtotime($this->commonForm->form_time);
		$confirmed = 1;
		$turnarray = compact('created_by', 'ownered_by', 'account_by', 'status','created_at','confirmed');
		$result = Turnover::updateBill($turnover->id, $turnarray);
		
		//新增入账日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		//更新高开折让往来时间
		if($this->mainInfo->bill_type == 'GKFK'){
			FrmRebate::setRebateDate($this->mainInfo->rebate_form_id,$this->commonForm->form_time);
		}
	}
	
	//-------------------------------------------------- 取消入账 --------------------------------------------------
	/**
	 * 取消入账
	 */
	public function cancelAccountedForm() 
	{
		if ($this->commonForm == null) return false; //表单为空
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			switch ($this->commonForm->form_type) 
			{
				case 'FKDJ': 
					$this->commonForm->form_status = "approve";
					break;
				case 'SKDJ': 
					$this->commonForm->form_status = "submited";
					break;
				default: break;
			}
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->mainInfo->account_at = 0;
			$this->mainInfo->account_by = 0;
			$this->mainInfo->reach_at = 0;
			if ($this->commonForm->update() && $this->mainInfo->update()) 
			{
				$this->afterCancelAccountedForm();
			}
			$transaction->commit();
		} 
		catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		//新增取消入账日志
		$operation = "取消入账";
		$busName = $this->commonForm->form_type == "FKDJ" ? "付款登记" : "收款登记";
		$this->operationLog($busName, $operation, $this->commonForm->form_sn);
	}
	
	/**
	 * 取消入账后操作
	 */
	protected function afterCancelAccountedForm() 
	{
		if ($this->mainInfo->account_by > 0) return false;
		//修改付款登记往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		//账户金额
		if ($this->mainInfo->pay_type != 'adjust') 
		{
			if (!$this->mainInfo->dict_bank_info_id) return false;
			switch ($this->commonForm->form_type)
			{
				case 'FKDJ': //取消付款
					$bill_type = 2;
// 					//结算账户
// 					$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_info_id);
// 					$bankInfo->money -= $this->mainInfo->fee;
// 					$bankInfo->update();
			
					if (in_array($this->mainInfo->bill_type, array('DLFK', 'DLSK')) && $this->mainInfo->pledge_bank_info_id)
					{
						//托盘公司账户
						$dictBankInfo = BankInfo::model()->findByPK($this->mainInfo->pledge_bank_info_id);
						$dictBankInfo->money += $this->mainInfo->fee;
						$dictBankInfo->update();
					}
					else
					{
						//公司账户
						$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_info_id);
						$dictBankInfo->money += $this->mainInfo->fee;
						$dictBankInfo->update();
					}
					//修改付款登记往来
					$result = Turnover::updateBill($turnover->id, array("status" => "approve","confirmed"=>0));
					break;
				case 'SKDJ': //取消收款
					$bill_type = 1;
					//公司账户
					$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_info_id);
					$dictBankInfo->money -= $this->mainInfo->fee;
					$dictBankInfo->update();
// 					//结算账户
// 					$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_info_id);
// 					$bankInfo->money += $this->mainInfo->fee;
// 					$bankInfo->update();
					//修改付款登记往来
					$result = Turnover::updateBill($turnover->id, array("status" => "submited"));
					break;
				default: break;
			}
		}
		//删除入账日志
		$billLog = FrmBillLog::model()->find("form_id = :form_id", array(':form_id' => $this->commonForm->id));
		$billLog->delete();
		
		//往来日志
		$tableName = "Turnover";
		$newValue = $result->datatoJson();
		$oldValue = $turnover->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
}