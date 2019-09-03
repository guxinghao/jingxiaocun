<?php
/**
 * 银行互转Class
 * @author leitao
 * 
 */
class TransferAccountsClass extends BaseForm 
{
	public $mainModel = 'TransferAccounts'; 
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName = "银行互转";
	
	public function __construct($id) 
	{
		if (!$id) return ;
		$model = CommonForms::model()->findByPK($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->transferAccounts;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建银行互转信息
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new TransferAccounts();
		$mainInfo->title_output_id = $data->title_output_id; //转出公司
		$mainInfo->output_bank_id = $data->output_bank_id; //转出账号
		$mainInfo->title_input_id = $data->title_input_id; //转入公司
		$mainInfo->input_bank_id = $data->input_bank_id; //转入账号
		$mainInfo->type = $data->type; //类型
		$mainInfo->amount = $data->amount; //金额
		$mainInfo->comment = $data->comment; //备注
		$mainInfo->reach_at = $data->reach_at; //备注
		if (!$mainInfo->insert()) return false; 
		//日志
		$tableName = $this->mainModel;
		$oldValue = "";
		$newValue = $mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return $mainInfo;
	}
	
	/**
	 * 创建银行互转后操作
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm() 
	{
		
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	protected function updateMainInfo($data) 
	{
		$mainInfo = $this->mainInfo; //修改前信息
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$this->mainInfo->title_output_id = $data->title_output_id; //转出公司
				$this->mainInfo->output_bank_id = $data->output_bank_id; //转出账号
				$this->mainInfo->title_input_id = $data->title_input_id; //转入公司
				$this->mainInfo->input_bank_id = $data->input_bank_id; //转入账号
				$this->mainInfo->type = $data->type; //类型
				$this->mainInfo->amount = $data->amount; //金额
				$this->mainInfo->comment = $data->comment; //备注
				$this->mainInfo->comment = $data->comment; //备注
				$this->mainInfo->reach_at = $data->reach_at; //备注
				break;
			case 'submited': 
				$this->mainInfo->comment = $data->comment; //备注
				$this->mainInfo->reach_at = $data->reach_at; //备注
				break;
			case 'approve': 
				$this->mainInfo->output_bank_id = $data->output_bank_id; //转出账号
				$this->mainInfo->input_bank_id = $data->input_bank_id; //转入账号
				$this->mainInfo->type = $data->type; //类型
				$this->mainInfo->reach_at = $data->reach_at; //到账日期
				$this->mainInfo->comment = $data->comment; //备注
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
	
	/**
	 * 银行互转审核通过后操作
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
			$message['content'] = "您的银行互转：".$baseform->form_sn."已经审核通过。";
			$message['type'] = "银行互转";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	//-------------------------------------------------- 审核拒绝 --------------------------------------------------
	/**
	 * 银行互转审核拒绝后操作
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
			$message['content'] = "您的银行互转：".$baseform->form_sn."审核已被拒绝。";
			$message['type'] = "银行互转";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	//-------------------------------------------------- 取消审核 --------------------------------------------------	
	/**
	 * 银行互转取消审核后操作
	 * @see BaseForm::afterCancelApproveForm()
	 */
	protected function afterCancelApproveForm() 
	{
		if (Yii::app()->user->userid != $baseform->owned_by) 
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			$message['content'] = "您的银行互转：".$baseform->form_sn."已被取消审核。";
			$message['type'] = "银行互转";
			$message['big_type']='money';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	//-------------------------------------------------- 入账 --------------------------------------------------
	/**
	 * 入账
	 */
	public function accountedForm() 
	{
		//if (!$this->commonForm || $this->commonForm->form_status != 'approve') return false;
		if (!$this->commonForm) return false;
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
			
			$this->commonForm->form_status = 'accounted';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			if (!$this->commonForm->update()) return false;

		//金额互转
			//转出账户
			$output_bank = DictBankInfo::model()->findByPK($this->mainInfo->output_bank_id); 
			$output_bank->money -= $this->mainInfo->amount; //转出金额
			if ($output_bank->update()) {
				//出账日志
				$out_bill_log = new FrmBillLog();
				$out_bill_log->form_id = $this->commonForm->id;
				$out_bill_log->form_sn = $this->commonForm->form_sn;
				$out_bill_log->title_id = $this->mainInfo->title_output_id;
				$out_bill_log->dict_bank_id = $this->mainInfo->output_bank_id;
				$out_bill_log->company_id = $this->mainInfo->title_input_id;
				$out_bill_log->bank_id = $this->mainInfo->input_bank_id;
				$out_bill_log->account_type = 'out';
				$out_bill_log->fee = $this->mainInfo->amount;
				$out_bill_log->bill_type = 5;
				$out_bill_log->account_by = Yii::app()->user->userid;
				$out_bill_log->created_at = time();
				$out_bill_log->reach_at = $this->mainInfo->reach_at;
				$out_bill_log->insert();
			}

			//转入账户
			$input_bank = DictBankInfo::model()->findByPK($this->mainInfo->input_bank_id); 
			$input_bank->money += $this->mainInfo->amount; //转入金额
			if ($input_bank->update()) {
				//入账日志
				$in_bill_log = new FrmBillLog();
				$in_bill_log->form_id = $this->commonForm->id;
				$in_bill_log->form_sn = $this->commonForm->form_sn;
				$in_bill_log->title_id = $this->mainInfo->title_input_id;
				$in_bill_log->dict_bank_id = $this->mainInfo->input_bank_id;
				$in_bill_log->company_id = $this->mainInfo->title_output_id;
				$in_bill_log->bank_id = $this->mainInfo->output_bank_id;
				$in_bill_log->account_type = 'in';
				$in_bill_log->fee = $this->mainInfo->amount;
				$in_bill_log->bill_type = 5;
				$in_bill_log->account_by = Yii::app()->user->userid;
				$in_bill_log->created_at = time();
				$in_bill_log->reach_at = $this->mainInfo->reach_at;
				$in_bill_log->insert();
			}
				
			//日志
			$tableName = "commonForms";
			$oldValue = $baseform->datatoJson();
			$newValue = $this->commonForm->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
				
			$this->afterAccountedForm();
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return "操作失败";
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
		
	}
	
	/**
	 * 取消入账
	 */
	public function cancelAccountedForm()
	{
		if (!$this->commonForm || $this->commonForm->form_status != 'accounted') return false;
	
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
				
			$this->commonForm->form_status = 'unsubmit';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->mainInfo->reach_at = 0;
			if (!$this->commonForm->update() || !$this->mainInfo->update()) return false;
				
		//金额互转（反向）
			//转出账户
			$output_bank = DictBankInfo::model()->findByPK($this->mainInfo->output_bank_id); 
			$output_bank->money += $this->mainInfo->amount; //转入金额
			//删除出账日志
			if ($output_bank->update()) {
				$out_bill_log = FrmBillLog::model()->find('form_id = :form_id AND dict_bank_id = :dict_bank_id', array(':form_id' => $this->commonForm->id, ':dict_bank_id' => $this->mainInfo->output_bank_id));
				$out_bill_log->delete();
			}

			//转入账户
			$input_bank = DictBankInfo::model()->findByPK($this->mainInfo->input_bank_id); 
			$input_bank->money -= $this->mainInfo->amount; //转出金额
			if ($input_bank->update()) {
				//删除入账日志
				$in_bill_log = FrmBillLog::model()->find('bill_type = 5 AND form_id = :form_id AND dict_bank_id = :dict_bank_id', array(':form_id' => $this->commonForm->id, ':dict_bank_id' => $this->mainInfo->input_bank_id));
				$in_bill_log->delete();
			}
				
			//日志
			$tableName = "commonForms";
			$oldValue = $baseform->datatoJson();
			$newValue = $this->commonForm->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
				
			$this->afterCancelAccountedForm();
			$transaction->commit();
		}
		catch (Exception $e)
		{
			$transaction->rollBack(); //事务回滚
			return '操作失败';
		}
	
		//新增日志
		$operation = "取消入账";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 取消入账后操作
	 */
	protected function afterCancelAccountedForm() 
	{
		
	}
	
}