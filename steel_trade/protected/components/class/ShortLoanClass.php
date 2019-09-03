<?php

/**
 * 短期借贷Class
 * @author leitao
 *
 */
class ShortLoanClass extends BaseForm 
{
	public $mainModel = "ShortLoan";
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName = "短期借贷";
	
	function __construct($id) 
	{
		if (!$id) return ;
		$model = CommonForms::model()->findByPK($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->shortLoan;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建短期借贷
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new ShortLoan();
		$mainInfo->title_id = $data->title_id;
		$mainInfo->company_id = $data->company_id;
		$mainInfo->lending_direction = $data->lending_direction;
		$mainInfo->amount = $data->amount;
		$mainInfo->interest_rate = $data->interest_rate;
		$mainInfo->accounted_principal = $data->accounted_principal;
		$mainInfo->accounted_interest = $data->accounted_interest;
		$mainInfo->out_account_principal = $data->out_account_principal;
		$mainInfo->out_account_interest = $data->out_account_interest;
		$mainInfo->balance = $data->amount;
		$mainInfo->start_time = $data->start_time;
		$mainInfo->end_time = $data->end_time;
		$mainInfo->has_Ious = $data->has_Ious;
		$mainInfo->performance_status = $data->performance_status;
		if (!$mainInfo->insert()) return false;
		
		//日志
		$tableName = $this->mainModel;
		$oldValue = "";
		$newValue = $mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		return $mainInfo;
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	/**
	 * 提交短期借贷后操作
	 * @see BaseForm::afterSubmitForm()
	 */
	protected function afterSubmitForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$message = array();
		$message['receivers'] = User::model()->getOperationList("短期借贷:业务经理审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."提交了短期借贷：".$baseform->form_sn.",请尽快审核。";
		$message['title'] = "短期借贷通知";
		$message['type'] = "短期借贷";
		$message['url'] = Yii::app()->createUrl('shortLoan/index');
		$message['big_type']='money';
		$res = MessageContent::model()->addMessage($message);
	}
	
	/**
	 * 提交短期借贷取消后操作
	 * @see BaseForm::afterCancelSubmitForm()
	 */
	protected function afterCancelSubmitForm() 
	{
		//删除入账明细
		$loan = $this->mainInfo;
		$record = $loan->oneloanRecord;
		if($record){
			$id = $record->id;
			$record->delete();
			//删除入账日志
			$billLog = FrmBillLog::model()->find('bill_type = 6 AND form_id = '.$id);
			if ($billLog) $billLog->delete();
		}
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改短期借贷
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		$mainInfo = $this->mainInfo; //修改前数据
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$this->mainInfo->title_id = $data->title_id;
				$this->mainInfo->company_id = $data->company_id;
// 				$this->mainInfo->lending_direction = $data->lending_direction;
				$this->mainInfo->amount = $data->amount;
				$this->mainInfo->interest_rate = $data->interest_rate;
				$this->mainInfo->accounted_principal = $data->accounted_principal;
				$this->mainInfo->accounted_interest = $data->accounted_interest;
				$this->mainInfo->out_account_principal = $data->out_account_principal;
				$this->mainInfo->out_account_interest = $data->out_account_interest;
				$this->mainInfo->balance = $data->balance;
				$this->mainInfo->start_time = $data->start_time;
				$this->mainInfo->end_time = $data->end_time;
				$this->mainInfo->has_Ious = $data->has_Ious;
				$this->mainInfo->performance_status = $data->performance_status;
				break;
			case 'submited': 
				break;
			default: break;
		}
		if (!$this->mainInfo->update()) return false;
		
		//日志
		$tableName = $this->mainModel;
		$oldValue = $mainInfo->datatoJson();
		$newValue = $this->mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		
		return true;
	}
	
	//-------------------------------------------------- 审核表单 --------------------------------------------------
	//短期借贷审核
	public function approveForm()
	{
		if (!$this->commonForm) return false;
	
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata = $this->commonForm;
			$oldJson = $olddata->datatoJson();
			$baseform = $this->commonForm;
			switch ($baseform->form_status)
			{
				case 'submited':
					$this->commonForm->form_status = 'approved_1';
					//发送消息
					$contents="短期借贷单".$this->commonForm->form_sn."需要您审核。";
					$this->sendMassage("短期借贷:财务主管审核",$contents);
					$description = '业务经理审核<span style="color: green;">通过</span>';
					break;
				case 'approved_1':
					$this->commonForm->form_status = 'approved_2';
					//发送消息
					$contents="短期借贷单".$this->commonForm->form_sn."需要您审核。";
					$this->sendMassage("短期借贷:总经理审核",$contents);
					$description = '财务主管审核<span style="color: green;">通过</span>';
					break;
				case 'approved_2':
					$this->commonForm->form_status = 'approved_3';
					$description = '总经理审核<span style="color: green;">通过</span>';
					//发送消息
					$contents="短期借贷单".$this->commonForm->form_sn."需要您审核。";
					$this->sendMassage("短期借贷:出纳审核",$contents);
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
	 * 审核短期借贷通过后操作
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
			$message['content'] = "您的短期借贷：".$baseform->form_sn."已经审核通过。";
			$message['type'] = "短期借贷";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	//-------------------------------------------------- 审核拒绝 --------------------------------------------------
	/**
	 *短期借贷拒绝
	 * @see BaseForm::refuseForm()
	 */
	public function refuseForm()
	{
		if (!$this->commonForm) return false;
	
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
	 * 审核短期借贷拒绝后操作
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
			$message['content'] = "您的短期借贷：".$baseform->form_sn."审核已被拒绝。";
			$message['type'] = "短期借贷";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	/**
	 * 短期借贷取消
	 * @see BaseForm::cancelApproveForm()
	 */
	public function cancelApproveForm()
	{
		if (!$this->commonForm) return false;
	
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
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
	 * 审核短期借贷取消后操作
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
			$message['content'] = "您的短期借贷：".$baseform->form_sn."已被取消审核。";
			$message['type'] = "短期借贷";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	//-------------------------------------------------- 出入账 --------------------------------------------------
	public function accountedForm($data) 
	{
		if (!$this->commonForm) return false;
		$common = $data['common'];
		$data = $data["detail"];
		$transaction = Yii::app()->db->beginTransaction();
 		try {
			$baseform = $this->commonForm;
			$records = $this->mainInfo->loanRecord;

			//删除
			$data_array = array();
			foreach ($data as $record_each) {
				if ($record_each->id) array_push($data_array, $record_each->id);
			}
			foreach ($records as $each) {
				if (in_array($each->id, $data_array)) continue;
				$operation = LoanRecord::updateAccount($each->id, 'cancel');
				if (!$result || !$each->delete()) return false;
				//日志
				$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
			}

			foreach ($data as $data_each) {
				if ($data_each->id) {
					$record_model = LoanRecord::model()->findByPK($data_each->id); //修改
					$operation = LoanRecord::updateAccount($each->id, 'cancel');
					if (!$operation) return false;
					//日志
					$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
				} else {
					$record_model = new LoanRecord(); //新增
				}	
			}
			$record_model->account_direction = $data_each->account_direction;
			$record_model->dict_bank_id = $data_each->dict_bank_id;
			$record_model->bank_id = $data_each->bank_id;
			$record_model->created_at = $data_each->created_at;
			$record_model->created_by = $data_each->created_by;
			$record_model->amount_type = $data_each->amount_type;
			$record_model->amount = $data_each->amount;
			$record_model->has_Ious = $data_each->has_Ious;
			$record_model->comment = $data_each->comment;
			$record_model->reach_at = $data_each->reach_at;
			if ($data_each->id) 
				$model = LoanRecord::updateRecord($data_each->id, $record_model);
			else 
				$model = LoanRecord::createRecord($record_model, $this->mainInfo->id);
			
			$operation = LoanRecord::updateAccount($model->id);
			if (!$operation) return false;
			//日志
			$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
			$this->commonForm->form_time = $common->form_time;
			$this->commonForm->comment = $common->comment;
			$this->commonForm->form_status = "accounted";
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			if (!$this->commonForm->update()) return false;
			//日志
			$tableName = "commonForms";
			$newValue = $this->commonForm->datatoJson();
			$oldValue = $baseform->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			$this->afterAccountedForm();
			$transaction->commit();
		} 
		catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		return true;
	} 
	
	protected function afterAccountedForm() 
	{
		
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
		$message['type'] = "短期借贷";
		$message['big_type']='money';
		$res = MessageContent::model()->addMessage($message);
	
	}
	
	//取消出入账
	public function cancelAccountedForm(){
		$common = $this->commonForm;
		$main = $this->mainInfo;
		$transaction = Yii::app()->db->beginTransaction();
		try{
			$loanRecord = $main->oneloanRecord;
			$oldJson = $loanRecord->datatoJson();
			$loanRecord->delete();
			$dataArray = array("tableName"=>"loanRecord","newValue"=>"","oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$frmBill = FrmBillLog::model()->find("form_id=$common->id");
			$oldJson = $frmBill->datatoJson();
			$frmBill->delete();
			$dataArray = array("tableName"=>"FrmBillLog","newValue"=>"","oldValue"=>$oldJson);
			$this->dataLog($dataArray);	
			$transaction->commit();
			$oldJson = $common->datatoJson();
			if($main->lending_direction == "borrow"){
				$common->form_status = "submited";
			}else{
				$common->form_status = "approve";
			}
			$common->update();
			$newJson = $common->datatoJson();
			$dataArray = array("tableName"=>"CommonForms","newValue"=>$newJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
		}catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return false;
		}
		return true;
	}
	
	/**
	 * 履约
	 */
	public function performanceForm() 
	{
		if (!$this->commonForm) return false;
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->mainInfo->performance_status = 1;
			if (!$this->mainInfo->update() || !$this->commonForm->update()) return false;
			
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$result = $this->afterPerformanceForm();
			
			$transaction->commit();
		}
		catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		
		//新增日志
		$operation = "履约";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	protected function afterPerformanceForm() 
	{
		return true;
	}
	
	/**
	 * 取消履约
	 */
	public function cancelPerformanceForm()
	{
		if (!$this->commonForm) return false;
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->mainInfo->performance_status = 0;
			if (!$this->mainInfo->update() || !$this->commonForm->update()) return false;
				
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$result = $this->afterCancelPerformanceForm();
				
			$transaction->commit();
		}
		catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
	
		//新增日志
		$operation = "取消履约";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	protected function afterCancelPerformanceForm()
	{
		return true;
	}
}