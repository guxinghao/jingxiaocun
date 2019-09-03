<?php 
/**
 * 其他收入/费用报支 
 * @author leitao
 *
 */
class BillOther extends BaseForm
{
	public $mainModel = "FrmBillOther";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName = "";
	
	function __construct($type, $id)
	{
		$this->busName = $type == 'FYBZ' ? "费用报支" : "其他收入";
		if (!$id) return ;
		$model = CommonForms::model()->with('billOther')->findByPK($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->billOther;
		$this->details = $model->billOther->details;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建 其他收入/费用报支 
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new FrmBillOther();
		$mainInfo->title_id = $data->title_id; //公司抬头
		$mainInfo->company_id = $data->company_id; //结算单位
		$mainInfo->dict_bank_id = $data->dict_bank_id; //公司账户
		$mainInfo->bank_id = $data->bank_id; //结算账户
		$mainInfo->team_id = $data->team_id; //业务组
		$mainInfo->bill_type = $data->bill_type; //类型
		$mainInfo->amount = $data->amount; //金额
		$mainInfo->status = 0; //入账状态
		//$mainInfo->account_at = $data->account_at;
		$mainInfo->comment = $data->comment; //备注
		$result = $mainInfo->insert();
		//日志
		$tableName = "FrmBillOther";
		$oldValue = ""; 
		$newValue = $mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue'); 
		$this->dataLog($dataArray);
		
		return $result ? $mainInfo : false;
	}
	
	/**
	 * 创建 其他收入/费用报支 明细
	 * @see BaseForm::saveDetails()
	 */
	protected function saveDetails($data) 
	{
		$detail_array = array();
		foreach ($data as $each) 
		{
			$detail = new BillOtherDetail();
			$detail->type_1 = $each->type_1; //费用类别
			$detail->type_2 = $each->type_2; //费用类别
			$detail->fee = $each->fee; //金额
			$detail->bill_other_id = $this->mainInfo->id;
			if ($detail->insert()) array_push($detail_array, $detail);
			
			//日志
			$tableName = "BillOtherDetail";
			$oldValue = "";
			$newValue = $detail->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
		}
		return $detail_array;
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	/**
	 * 提交 其他收入/费用报支 后操作
	 * @see BaseForm::afterSubmitForm()
	 */
	protected function afterSubmitForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$message = array();
		switch ($baseform->form_type) 
		{
			case 'FYBZ': //费用报支
				$message['receivers'] = User::model()->getOperationList("费用报支:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了费用报支：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "费用报支通知";
				$message['type'] = "费用报支";
				break;
			case 'QTSR': //其他收入
				$message['receivers'] = User::model()->getOperationList("其他收入:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了其他收入：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "其他收入通知";
				$message['type'] = "其他收入";
				break;
			default: break;
		}
		$message['url'] = Yii::app()->createUrl('billOther/index', array('type' => $this->commonForm->form_type));
		$message['big_type']='money';
		$res = MessageContent::model()->addMessage($message);
		//创建提交记录
		$description = '费用报支提交';
		$approve_log = new BillApproveLog();
		$approve_log->form_id = $this->commonForm->id;
		$approve_log->description = $description;
		$approve_log->status = 'submit';
		$approve_log->created_by = currentUserId();
		$approve_log->created_at = time();
		$approve_log->insert();
		//发送消息
		$message = array();
		$message['receivers'] = User::model()->getOperationList("费用报支:业务经理审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."的费用报支：".$baseform->form_sn."需要审核,请尽快审核。";
		$message['title'] = "财务通知";
		$message['url'] = Yii::app()->createUrl('billOther/index',array('type'=>"FYBZ"));
		$message['type'] = "费用报支";
		$message['big_type']='money';
		$res = MessageContent::model()->addMessage($message);
	}
	
	/**
	 * 取消提交表单后的动作
	 */
	protected function afterCancelSubmitForm()
	{
		//创建提交记录
		$description = '费用报支取消提交';
		$approve_log = new BillApproveLog();
		$approve_log->form_id = $this->commonForm->id;
		$approve_log->description = $description;
		$approve_log->status = 'unsubmit';
		$approve_log->created_by = currentUserId();
		$approve_log->created_at = time();
		$approve_log->insert();
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改 其他收入/费用报支 
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		$mainInfo = $this->mainInfo;
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$this->mainInfo->title_id = $data->title_id; //公司抬头
				$this->mainInfo->company_id = $data->company_id; //结算单位
				$this->mainInfo->dict_bank_id = $data->dict_bank_id; //公司账户
				$this->mainInfo->bank_id = $data->bank_id; //结算账户
// 				$this->mainInfo->bill_type = $data->bill_type; //类型
				$this->mainInfo->amount = $data->amount; //金额
				$this->mainInfo->team_id = $data->team_id; //业务组
				$this->mainInfo->comment = $data->comment; //备注
				break;
			case 'submited': 
				$this->mainInfo->team_id = $data->team_id; //业务组
				$this->mainInfo->comment = $data->comment; //备注
				break;
			case 'approve': 
				$this->mainInfo->dict_bank_id = $data->dict_bank_id; //公司账户
				$this->mainInfo->bank_id = $data->bank_id; //结算账户
				$this->mainInfo->team_id = $data->team_id; //业务组
				$this->mainInfo->reach_at = $data->reach_at; //到账日期
				break;
			default: 
				break;
		}
		$result = $this->mainInfo->update();
		
		//日志
		$tableName = "FrmBillOther";
		$oldValue = $mainInfo->datatoJson();
		$newValue = $this->mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue'); 
		$this->dataLog($dataArray);
		
		return $result ? true : false;
	}
	
	/**
	 * 修改 其他收入/费用报支 明细
	 * @see BaseForm::updateDetails()
	 */
	protected function updateDetails($data) 
	{
		$detail_array = array();
		foreach ($data as $data_each) 
		{
			//获取已创建的明细
			if ($data_each->id) array_push($detail_array, $data_each->id);
		}
		//删除明细
		foreach ($this->details as $detail_each) 
		{
			if (!in_array($detail_each->id, $detail_array)) 
			{
				$oldValue = $detail_each->datatoJson();
				$detail_each->delete();
				//日志
				$tableName = "BillOtherDetail";
				$newValue = "";
				$dataArray = compact('tableName', 'newValue', 'oldValue');
				$this->dataLog($dataArray);
			}
		}
		
		$result_array = array();
		foreach ($data as $each) 
		{
			if ($each->id) //修改明细
			{
				$detail = BillOtherDetail::model()->findByPK($each->id);
				$oldValue = $detail->datatoJson();
				$detail->type_1 = $each->type_1;
				$detail->type_2 = $each->type_2;
				$detail->fee = $each->fee;
				$detail->update();
			}
			else //新增明细
			{
				$detail = new BillOtherDetail();
				$oldValue = "";
				$detail->type_1 = $each->type_1;
				$detail->type_2 = $each->type_2;
				$detail->fee = $each->fee;
				$detail->bill_other_id = $this->mainInfo->id;
				$detail->insert();
			}
			array_push($result_array, $detail);
			
			//日志
			$tableName = "BillOtherDetail";
			$newValue = $detail->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
		}
		return $result_array;
	}
	
	//-------------------------------------------------- 审核表单 --------------------------------------------------
	/**
	 * 审核 费用报支/其他收入 通过
	 * @see BaseForm::approveForm()
	 */
	 public function approveForm()
	{
		if (!$this->commonForm) return false; //表单为空
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
			$message = array();
			if ($baseform->form_type == 'FYBZ') 
			{
				switch ($baseform->form_status) 
				{
					case 'submited':
						$this->commonForm->form_status = 'approved_1';
						$description = '业务经理审核<span style="color: green;">通过</span>';
						//发送消息
						$message['receivers'] = User::model()->getOperationList("费用报支:财务主管审核");
						$message['content'] = "业务员：".$baseform->belong->nickname."的费用报支：".$baseform->form_sn."需要审核,请尽快审核。";
						break;
					case 'approved_1':
						$this->commonForm->form_status = 'approved_2';
						$description = '财务主管审核<span style="color: green;">通过</span>';
						$message['receivers'] = User::model()->getOperationList("费用报支:总经理审核");
						$message['content'] = "业务员：".$baseform->belong->nickname."的费用报支：".$baseform->form_sn."需要审核,请尽快审核。";
						break;
					case 'approved_2':
						$this->commonForm->form_status = 'approved_3';
						$description = '总经理审核<span style="color: green;">通过</span>';
						$message['receivers'] = User::model()->getOperationList("费用报支:出纳审核");
						$message['content'] = "业务员：".$baseform->belong->nickname."的费用报支：".$baseform->form_sn."需要审核,请尽快审核。";
						break;
					case 'approved_3':
						$this->commonForm->form_status = 'approve';
						$description = '出纳审核<span style="color: green;">通过</span>';
						$message['receivers'] = $baseform->owned_by;
						$message['content'] = "您的费用报支：".$baseform->form_sn."已经审核通过。";
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
				
				$message['title'] = "财务通知";
				$message['url'] = Yii::app()->createUrl('billOther/index',array('type'=>"FYBZ"));
				$message['type'] = "费用报支";
				$message['big_type']='money';
				$res = MessageContent::model()->addMessage($message);
			} 
			elseif ($baseform->form_type == 'QTSR') 
			{
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
	 * 审核 其他收入/费用报支 通过后操作
	 * @see BaseForm::afterApproveForm()
	 */
	protected function afterApproveForm() 
	{
		//发送消息
		
	}
	
	/**
	 * 审核 费用报支/其他收入 拒绝
	 * @see BaseForm::refuseForm()
	 */
	public function refuseForm() 
	{
		if (!$this->commonForm) return false;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$message = array();
			$baseform = $this->commonForm;
			if ($baseform->form_type == 'FYBZ') 
			{
				switch ($baseform->form_status) 
				{
					case 'submited': 
						$description = '业务经理审核<span style="color: red;">拒绝</span>';
						$message['content'] = "您的费用报支：".$baseform->form_sn."被业务经理审核拒绝。";
						break;
					case 'approved_1': 
						$description = '财务主管审核<span style="color: red;">拒绝</span>';
						$message['content'] = "您的费用报支：".$baseform->form_sn."被财务主管审核拒绝。";
						break;
					case 'approved_2': 
						$description = '总经理审核<span style="color: red;">拒绝</span>';
						$message['content'] = "您的费用报支：".$baseform->form_sn."被总经理审核拒绝。";
						break;
					case 'approved_3': 
						$description = '出纳审核<span style="color: red;">拒绝</span>';
						$message['content'] = "您的费用报支：".$baseform->form_sn."被出纳审核拒绝。";
						break;
					default: 
						return false; 
						break;
				}
				$message['receivers'] = $baseform->owned_by;
				$message['title'] = "财务通知";
				$message['url'] = "";
				$message['type'] = "费用报支";
				$message['big_type']='money';
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
	 * 审核 其他收入/费用报支 拒绝后操作
	 * @see BaseForm::afterRefuseForm()
	 */
	protected function afterRefuseForm() 
	{
		//发送消息
// 		$baseform = $this->commonForm;
// 		if (Yii::app()->user->userid != $baseform->owned_by) 
// 		{
// 			$message = array();
// 			$message['receivers'] = $baseform->owned_by;
// 			$message['title'] = "审核通知";
// 			switch ($baseform->form_type)
// 			{
// 				case 'FYBZ': //费用报支
// 					$message['content'] = "您的费用报支：".$baseform->form_sn."审核已被拒绝。";
// 					$message['type'] = "费用报支";
// 					break;
// 				case 'QTSR': //其他收入
// 					$message['content'] = "您的其他收入：".$baseform->form_sn."审核已被拒绝。";
// 					$message['type'] = "其他收入";
// 					break;
// 				default: break;
// 			}
// 			$res = MessageContent::model()->addMessage($message);
// 		}
	}
	
	/**
	 * 审核 费用报支/其他收入 取消
	 * @see BaseForm::cancelApproveForm()
	 */
	public function cancelApproveForm() 
	{
		if (!$this->commonForm) return false; //表单为空
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$baseform = $this->commonForm;
			if ($baseform->form_type == 'FYBZ') 
			{
				switch ($baseform->form_status) 
				{
					case 'approved_1': 
						$this->commonForm->form_status = 'unsubmit';
						$description = '业务经理审核<span style="color: blue;">取消</span>';
						break;
					case 'approved_2': 
						$this->commonForm->form_status = 'unsubmit';
						$description = '财务主管审核<span style="color: blue;">取消</span>';
						break;
					case 'approved_3': 
						$this->commonForm->form_status = 'unsubmit';
						$description = '总经理审核<span style="color: blue;">取消</span>';
						break;
					case 'approve': 
						$this->commonForm->form_status = 'unsubmit';
						$description = '出纳审核<span style="color: blue;">取消</span>';
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
			} 
			elseif ($baseform->form_type == 'QTSR') 
			{
				$this->commonForm->form_status = 'unsubmit';
			}
			$this->commonForm->approved_at = 0;
			$this->commonForm->approved_by = 0;
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
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
		//新增日志
		$operation = "取消审核";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 审核 其他收入/费用报支 取消后操作
	 * @see BaseForm::afterCancelApproveForm()
	 */
	protected function afterCancelApproveForm() 
	{
		//发送消息
// 		$baseform = $this->commonForm;
// 		if (Yii::app()->user->userid != $baseform->owned_by)
// 		{
// 			$message = array();
// 			$message['receivers'] = $baseform->owned_by;
// 			$message['title'] = "审核通知";
// 			switch ($baseform->form_type)
// 			{
// 				case 'FYBZ': //费用报支
// 					if ($baseform->form_status == 'approved_3') 
// 					{
// 						$message['content'] = "您的费用报支：".$baseform->form_sn."已被取消审核。";
// 						$message['type'] = "费用报支";
// 						$res = MessageContent::model()->addMessage($message);
// 					}
// 					break;
// 				case 'QTSR': //其他收入
// 					$message['content'] = "您的其他收入：".$baseform->form_sn."已被取消审核。";
// 					$message['type'] = "其他收入";
// 					$res = MessageContent::model()->addMessage($message);
// 					break;
// 				default: 
// 					return false;
// 					break;
// 			}
// 		}
	}
	
	//-------------------------------------------------- 入账 --------------------------------------------------
	/**
	 * 入账
	 */
	public function accountedForm() 
	{
		if (!$this->commonForm) return false; //表单为空
		if ($this->commonForm->form_status != 'approve') return ;
			
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->commonForm->form_status = 'accounted'; //已入账
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->mainInfo->status = 1;
			$this->mainInfo->account_at = time();
			$this->mainInfo->account_by = currentUserId();
			if ($this->commonForm->update() && $this->mainInfo->update()) 
			{
				//入账后操作
				$this->afterAccountedForm();
				$transaction->commit();
			}
		} catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		
		//日志
		$operation = "入账";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 入账后操作
	 */
	protected function afterAccountedForm() 
	{
		$details = $this->details;
		if($details){
			foreach ($details as $li){
				//入账日志
				$billLog = new FrmBillLog();
				$billLog->form_id = $this->commonForm->id;
				$billLog->form_sn = $this->commonForm->form_sn;
				$billLog->title_id = $this->mainInfo->title_id;
				$billLog->company_id = $this->mainInfo->company_id;
				$billLog->dict_bank_id = $this->mainInfo->dict_bank_id;
				$billLog->bank_id = $this->mainInfo->bank_id;
				switch ($this->commonForm->form_type)
				{
					case 'FYBZ':
						$billLog->account_type = "out"; //出账
						$billLog->bill_type = 4;
				
						//公司账户
						$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_id);
						$dictBankInfo->money -= $li->fee;
						$dictBankInfo->update();
						// 				//结算账户
						// 				$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_id);
						// 				$bankInfo->money += $this->mainInfo->amount;
						// 				$bankInfo->update();
						break;
					case 'QTSR':
						$billLog->account_type = "in"; //入账
						$billLog->bill_type = 3;
				
						// 				//结算账户
						// 				$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_id);
						// 				$bankInfo->money -= $this->mainInfo->amount;
						// 				$bankInfo->update();
						//公司账户
						$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_id);
						$dictBankInfo->money += $li->fee;
						$dictBankInfo->update();
						break;
					default: break;
				}
				$billLog->fee = $li->fee;
				// 		$billLog->pay_type = $this->mainInfo->pay_type;
				$billLog->account_by = $this->mainInfo->account_by;
				$billLog->created_at = time();
				$billLog->reach_at = $this->mainInfo->reach_at;
				$billLog->insert();
			}
		}
	}
	
	//-------------------------------------------------- 取消入账 --------------------------------------------------
	/**
	 * 取消入账
	 */
	public function cancelAccountedForm() 
	{
		if (!$this->commonForm) return false;
		if ($this->commonForm->form_status != 'accounted') return ;
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->commonForm->form_status = 'approve'; //已审核
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->mainInfo->status = 0;
			$this->mainInfo->account_by = 0;
			$this->mainInfo->account_at = 0;
			$this->mainInfo->reach_at = 0;
			if ($this->commonForm->update() && $this->mainInfo->update())
			{
				//取消入账后操作
				$this->afterCancelAccountedForm();
				$transaction->commit();
			}
		} catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		
		//日志
		$operation = "取消入账";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 取消入账后操作
	 */
	protected function afterCancelAccountedForm() 
	{
		if ($this->mainInfo->status > 0) return false;
		switch ($this->commonForm->form_type) 
		{
			case 'FYBZ': 
				$bill_type = 4;
// 				//结算账户
// 				$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_id);
// 				$bankInfo->money -= $this->mainInfo->amount;
// 				$bankInfo->update();
				//公司账户
				$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_id);
				$dictBankInfo->money += $this->mainInfo->amount;
				$dictBankInfo->update();
				break;
			case 'QTSR': 
				$bill_type = 3;
				//公司账户
				$dictBankInfo = DictBankInfo::model()->findByPK($this->mainInfo->dict_bank_id);
				$dictBankInfo->money -= $this->mainInfo->amount;
				$dictBankInfo->update();
// 				//结算账户
// 				$bankInfo = BankInfo::model()->findByPK($this->mainInfo->bank_id);
// 				$bankInfo->money += $this->mainInfo->amount;
// 				$bankInfo->update();
				break;
			default: break;
		}
		//删除入账日志
		$billLog = FrmBillLog::model()->find("bill_type = :bill_type AND form_id = :form_id", array(':bill_type' => $bill_type, ':form_id' => $this->commonForm->id));
		if ($billLog) $billLog->delete();
	}
}