<?php 

/**
 * 钢厂返利/仓库返利/仓储费用
 * @author leitao
 *
 */
class BillRebateClass extends BaseForm
{
	public $mainModel = 'BillRebate'; 
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName = "返利";
	
	function __construct($id)
	{
		if (!$id) return ;
		$model = CommonForms::model()->with('billRebate')->findByPK($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->billRebate;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建返利
	 * @see BaseForm::saveMainInfo()
	 */
	protected  function saveMainInfo($data) 
	{
		$mainInfo = new BillRebate();
		$mainInfo->type = $data->type;
		$mainInfo->warehouse_id = $data->warehouse_id;
		$mainInfo->company_id = $data->company_id;
		$mainInfo->title_id = $data->title_id;
		$mainInfo->fee = $data->fee;
		$mainInfo->start_time = $data->start_time;
		$mainInfo->end_time = $data->end_time;
		if (!$mainInfo->insert()) return false;
		//日志
		$tableName = "BillRebate";
		$oldValue = "";
		$newValue = $mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return $mainInfo;
	}
	
	/**
	 * 创建返利后操作
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm() 
	{
		//创建往来
		$type = $this->commonForm->form_type; //GCFL
		switch ($this->mainInfo->type) 
		{
			case 'warehouse': //仓库返利
				$turnover_direction = "need_charge"; //应收
				$description = "仓库返利";
				$big_type = "warehouse";
				break;
			case 'supply': //钢厂返利
				$turnover_direction = "need_charge"; //应收
				$description = "钢厂返利";
				$big_type = "steelmill";
				break;
			case 'cost': //仓储费用
				$turnover_direction = "need_pay"; //应付
				$description = "仓储费用";
				$big_type = "warehouse";
				break;
			default: 
				break;
		}
		$title_id = $this->mainInfo->title_id; //公司抬头
		$target_id = $this->mainInfo->company_id; // 仓库结算单位/供应商
// 		$amount = 1;
		$fee = $this->mainInfo->fee; //金额
// 		$price = $fee;
		$common_forms_id = $this->commonForm->id; //对应单据id
		$created_by = $this->commonForm->created_by;
		$ownered_by = $this->commonForm->owned_by;
		$created_at = strtotime($this->commonForm->form_time);
		$turnarray = compact('type', 'turnover_direction', 'big_type', 'title_id', 'target_id', 'amount', 'fee', 'price', 'common_forms_id', 'created_by', 'ownered_by', 'description','created_at');
		$result = Turnover::createBill($turnarray);
		
		//往来日志
		$tableName = "Turnover";
		$oldValue = "";
		$newValue = $result->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 提交表单 --------------------------------------------------
	/**
	 * 返利提交后操作
	 * @see BaseForm::afterSubmitForm()
	 */
	protected function afterSubmitForm() 
	{
		//发送消息
		$baseform = $this->commonForm;
		$message = array();
		switch ($baseform->form_type) 
		{
			case 'GCFL': //钢厂返利
				$message['receivers'] = User::model()->getOperationList("钢厂返利:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了钢厂返利：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "钢厂返利通知";
				$message['type'] = "钢厂返利";
				break;
			case 'CKFL': //仓库返利
				$message['receivers'] = User::model()->getOperationList("仓库返利:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了仓库返利：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "仓库返利通知";
				$message['type'] = "仓库返利";
				break;
			case 'CCFY': //仓储费用
				$message['receivers'] = User::model()->getOperationList("仓储费用:审核");
				$message['content'] = "业务员：".$baseform->belong->nickname."提交了仓储费用：".$baseform->form_sn.",请尽快审核。";
				$message['title'] = "仓储费用通知";
				$message['type'] = "仓储费用";
				break;
			default: break;
		}
		$message['url'] = Yii::app()->createUrl('billRebate/index', array('type' => $this->mainInfo->type));
		$message['big_type']='ware';
		$res = MessageContent::model()->addMessage($message);
		
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "submited"));
		//日志
		$tableName = "Turnover";
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 取消提交表单 --------------------------------------------------
	/**
	 * 返利取消提交后操作
	 * @see BaseForm::afterCancelSubmitForm()
	 */
	protected function afterCancelSubmitForm()
	{
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "unsubmit"));
		//日志
		$tableName = "Turnover";
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改返利
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		$mainInfo = $this->mainInfo; //修改前信息
		switch ($this->commonForm->form_status) 
		{
			case 'unsubmit': 
				$this->mainInfo->type = $data->type;
				$this->mainInfo->warehouse_id = $data->warehouse_id;
				$this->mainInfo->company_id = $data->company_id;
				$this->mainInfo->title_id = $data->title_id;
				$this->mainInfo->fee = $data->fee;
				$this->mainInfo->start_time = $data->start_time;
				$this->mainInfo->end_time = $data->end_time;
				break;
			case 'submited': 
				break;
			default: //状态为“已审核”时，无法修改
				break;
		}
		if (!$this->mainInfo->update()) return false;
		//日志
		$tableName = "BillRebate";
		$oldValue = $mainInfo->datatoJson();
		$newValue = $this->mainInfo->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 修改返利后操作
	 * @see BaseForm::afterupdateForm()
	 */
	protected function afterupdateForm() 
	{
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		//修改往来
		switch ($this->mainInfo->type) 
		{
			case 'warehouse': //仓库返利
				$turnover_direction = "need_charge"; //应收
				$description = "仓库返利";
				$big_type = "warehouse";
				break;
			case 'supply': //钢厂返利
				$turnover_direction = "need_charge"; //应收
				$description = "钢厂返利";
				$big_type = "steelmill";
				break;
			case 'cost': //仓储费用
				$turnover_direction = "need_pay"; //应付
				$description = "仓储费用";
				$big_type = "warehouse";
				break;
			default: 
				break;
		}
		$title_id = $this->mainInfo->title_id; //公司抬头
		$target_id = $this->mainInfo->company_id; // 仓库结算单位/供应商
// 		$amount = 1;
		$fee = $this->mainInfo->fee; //金额
// 		$price = $fee;
		$common_forms_id = $this->commonForm->id; //对应单据id
		// $created_by = $this->commonForm->created_by;
		$ownered_by = $this->commonForm->owned_by;
		$created_at = strtotime($this->commonForm->form_time);
		$turnarray = compact('turnover_direction', 'big_type', 'title_id', 'target_id', 'amount', 'fee', 'price', 'common_forms_id', 'ownered_by', 'description','created_at');
		$result = Turnover::updateBill($turnover->id, $turnarray);
		//往来日志
		$tableName = "Turnover";
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 作废表单 --------------------------------------------------
	/**
	 * 作废返利后操作
	 * @see BaseForm::afterDeleteForm()
	 */
	protected function afterDeleteForm() 
	{
		if ($this->commonForm->is_deleted != 1) return ;
		//作废往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "delete"));
		//往来日志
		$tableName = "Turnover";
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
	//-------------------------------------------------- 审核通过 --------------------------------------------------
	/**
	 * 返利审核通过后操作
	 * @see BaseForm::afterApproveForm()
	 */
	protected function afterApproveForm() 
	{
		if ($this->commonForm->form_status != 'approve') return false;
		//发送消息
		$baseform = $this->commonForm;
		if (Yii::app()->user->userid != $baseform->owned_by) 
		{
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['title'] = "审核通知";
			switch ($baseform->form_type) 
			{
				case 'GCFL': //钢厂返利
					$message['content'] = "您的钢厂返利：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "钢厂返利";
					break;
				case 'CKFL': //仓库返利
					$message['content'] = "您的仓库返利：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "仓库返利";
					break;
				case 'CCFY': //仓储费用
					$message['content'] = "您的仓储费用：".$baseform->form_sn."已经审核通过。";
					$message['type'] = "仓储费用";
					break;
				default: break;
			}
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		BillRebate::shareEqually($this->commonForm->id,0); //均摊		
	}
	
	//-------------------------------------------------- 审核拒绝 --------------------------------------------------
	/**
	 * 返利审核拒绝后操作
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
				case 'GCFL': //钢厂返利
					$message['content'] = "您的钢厂返利：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "钢厂返利";
					break;
				case 'CKFL': //仓库返利
					$message['content'] = "您的仓库返利：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "仓库返利";
					break;
				case 'CCFY': //仓储费用
					$message['content'] = "您的仓储费用：".$baseform->form_sn."审核已被拒绝。";
					$message['type'] = "仓储费用";
					break;
				default: break;
			}
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		
		//修改往来
		$turnover = Turnover::model()->find("common_forms_id = :common_forms_id", array(':common_forms_id' => $this->commonForm->id));
		$result = Turnover::updateBill($turnover->id, array('status' => "unsubmit"));
		//往来日志
		$tableName = "Turnover";
		$oldValue = $turnover->datatoJson();
		$newValue = $result->datatoJson();
		$dataArray = compact('tableName', 'newValue', 'oldValue');
		$this->dataLog($dataArray);
	}
	
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
				case 'GCFL': //钢厂返利
					$message['content'] = "您的钢厂返利：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "钢厂返利";
					break;
				case 'CKFL': //仓库返利
					$message['content'] = "您的仓库返利：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "仓库返利";
					break;
				case 'CCFY': //仓储费用
					$message['content'] = "您的仓储费用：".$baseform->form_sn."已被取消审核。";
					$message['type'] = "仓储费用";
					break;
				default: break;
			}
			$message['big_type']='ware';
			$res = MessageContent::model()->addMessage($message);
		}
		BillRebate::shareEqually($this->commonForm->id,1); //均摊
	}
}
