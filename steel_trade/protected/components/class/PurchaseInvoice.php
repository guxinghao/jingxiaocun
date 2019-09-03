<?php
/**
 * 采购开票
 * @author leitao
 *
 */
class PurchaseInvoice extends BaseForm 
{
	public $mainModel = "FrmPurchaseInvoice";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName = "采购销票";
	
	public function __construct($id) 
	{
		if (!$id) return ;
		$model = CommonForms::model()->with('purchaseInvoice')->findByPk($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->purchaseInvoice;
		$this->details = $model->purchaseInvoice->purchaseInvoiceDetails;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	/**
	 * 创建采购发票
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new FrmPurchaseInvoice();
		$mainInfo->invoice_type = 'purchase';
		$mainInfo->company_id = $data->company_id;
		$mainInfo->title_id = $data->title_id;
		$mainInfo->weight = $data->weight;
		$mainInfo->fee = $data->fee;
		$mainInfo->capias_amount = $data->capias_amount;
		$mainInfo->capias_number = $data->capias_number;
		$mainInfo->confirm_status = 0;
		if ($mainInfo->insert()) 
		{
			//新增日志
			$tableName = "FrmPurchaseInvoice";
			$newValue = $mainInfo->datatoJson();
			$oldValue = "";
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			return $mainInfo;
		}
	}
	
	/**
	 * 创建明细
	 * @see BaseForm::saveDetails()
	 */
	protected function saveDetails($data) 
	{
		if (!is_array($data) || count($data) <= 0)
			return ;
		
		$detail_array = array();
		foreach ($data as $each)
		{
			//添加明细
			$detail = new PurchaseInvoiceDetail();
			$detail->purchase_detail_id = $each->purchase_detail_id;
			$detail->weight = $each->weight;
			$detail->fee = $each->fee;
			$detail->purchase_invoice_id = $this->mainInfo->id;
			$detail->type = $detail->detailForInvoice->type;
			$detail->frm_purchase_id = $detail->detailForInvoice->relation_form->form_id;
			$detail->frm_purchase_detail_id = $detail->detailForInvoice->detail_id;
			if ($detail->insert()) 
			{
				//日志
				$tableName = "PurchaseInvoiceDetail";
				$oldValue = "";
				$newValue = $detail->datatoJson();
				$dataArray = compact('tableName', 'newValue', 'oldValue');
				$this->dataLog($dataArray);
				//修改关联信息
				$detailForInvoice = $detail->detailForInvoice;
				if (!$detailForInvoice) continue;
				//关联修改前信息
				$dfi_oldValue = $detailForInvoice->datatoJson();
				//修改已销票的重量、金额
				$detailForInvoice->checked_weight += $detail->weight;
				$detailForInvoice->checked_money += $detail->fee;
				if ($detailForInvoice->update())
				{
					//日志
					$tableName = "DetailForInvoice";
					$oldValue = $dfi_oldValue;
					$newValue = $detailForInvoice->datatoJson();
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
				}
				array_push($detail_array, $detail);
			}
		}
		return $detail_array;
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	/**
	 * 修改采购发票
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data) 
	{
		//修改前信息
		$oldValue = $this->mainInfo->datatoJson();
		if ($this->commonForm->form_status != "unsubmit")
		{
		
		}
		else //状态为“未提交”时，能修改所有信息
		{
			//修改采购发票
			$this->mainInfo->company_id = $data->company_id;
			$this->mainInfo->title_id = $data->title_id;
			$this->mainInfo->weight = $data->weight;
			$this->mainInfo->fee = $data->fee;
			$this->mainInfo->capias_amount = $data->capias_amount;
			$this->mainInfo->capias_number = $data->capias_number;
// 			$this->mainInfo->confirm_status = $data->confirm_status;
		}
		if ($this->mainInfo->update()) 
		{
			//新增修改日志
			$tableName = "FrmPurchaseInvoice";
			$newValue = $this->mainInfo->datatoJson();
			$dataArray = compact('tableName', 'newValue', 'oldValue');
			$this->dataLog($dataArray);
			return true;
		}
		return false;
	}
	
	/**
	 * 修改明细
	 * @see BaseForm::updateDetails()
	 */
	protected function updateDetails($data) 
	{
		if (!is_array($data) || count($data) <= 0 || !is_array($this->details)) return ; 
		if ($this->commonForm->form_status != "unsubmit") return ;
		
		$id_array = array();
		foreach ($data as $item)
		{
			if ($item->id) array_push($id_array, $item->id);
		}
		
		foreach ($this->details as $each)
		{
			//删除明细
			if (!in_array($each->id, $id_array)) 
			{
				//删除前信息
				$oldValue = $each->datatoJson();
				$detailForInvoice = $each->detailForInvoice;
				if ($detailForInvoice) 
				{
					//关联修改前信息
					$dfi_oldValue = $detailForInvoice->datatoJson();
					//修改已销票的重量、金额
					$detailForInvoice->checked_weight -= $each->weight;
					$detailForInvoice->checked_money -= $each->fee;
				}
				if ($each->delete()) 
				{
					//日志
					$tableName = "PurchaseInvoiceDetail";
					$newValue = "";
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
					//修改关联
					if (!$detailForInvoice) continue;
					if ($detailForInvoice->update()) 
					{
						//日志
						$tableName = "DetailForInvoice";
						$oldValue = $dfi_oldValue;
						$newValue = $detailForInvoice->datatoJson();
						$dataArray = compact('tableName', 'newValue', 'oldValue');
						$this->dataLog($dataArray);
					}
				}
			}
		}
		
		foreach ($data as $data_each)
		{
			if ($data_each->id) //修改此条数据
			{
				$detail = PurchaseInvoiceDetail::model()->findByPk($data_each->id);
				if (!$detail) continue;
				//修改前数据
				$oldValue = $detail->datatoJson();
				$detailForInvoice = $detail->detailForInvoice;
				if (!$detailForInvoice) continue;
				//关联修改前信息
				$dfi_oldValue = $detailForInvoice->datatoJson();
				//修改已销票的重量、金额
				$detailForInvoice->checked_weight += ($data_each->weight - $detail->weight);
				$detailForInvoice->checked_money += ($data_each->fee - $detail->fee);
				//修改明细信息
				$detail->weight = $data_each->weight;
				$detail->fee = $data_each->fee;
				if ($detail->update()) 
				{
					//日志
					$tableName = "PurchaseInvoiceDetail";
					$newValue = $detail->datatoJson();
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
					//修改关联信息
					if ($detailForInvoice->update()) 
					{
						//日志
						$tableName = "DetailForInvoice";
						$oldValue = $dfi_oldValue;
						$newValue = $detailForInvoice->datatoJson();
						$dataArray = compact('tableName', 'newValue', 'oldValue');
						$this->dataLog($dataArray);
					}
				}
			}
			else //新建
			{
				$detail = new PurchaseInvoiceDetail();
				$detail->purchase_detail_id = $data_each->purchase_detail_id;
				$detail->weight = $data_each->weight;
				$detail->fee = $data_each->fee;
				$detail->purchase_invoice_id = $this->mainInfo->id;
				$detail->frm_purchase_id = $detail->detailForInvoice->relation_form->form_id;
				$detail->frm_purchase_detail_id = $detail->detailForInvoice->detail_id;
				$detail->type = $detail->detailForInvoice->type;
				if ($detail->insert()) 
				{
					//日志
					$tableName = "PurchaseInvoiceDetail";
					$oldValue = "";
					$newValue = $detail->datatoJson();
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
					//修改关联信息
					$detailForInvoice = $detail->detailForInvoice;
					if (!$detailForInvoice) continue;
					//关联修改前信息
					$dfi_oldValue = $detailForInvoice->datatoJson();
					//修改已销票的重量、金额
					$detailForInvoice->checked_weight += $detail->weight;
					$detailForInvoice->checked_money += $detail->fee;
					if ($detailForInvoice->update())
					{
						//日志
						$tableName = "DetailForInvoice";
						$oldValue = $dfi_oldValue;
						$newValue = $detailForInvoice->datatoJson();
						$dataArray = compact('tableName', 'newValue', 'oldValue');
						$this->dataLog($dataArray);
					}
				}
			}
		}
		return true;
	}
	
	//-------------------------------------------------- 作废表单 --------------------------------------------------
	/**
	 * 作废
	 * @see BaseForm::afterDeleteForm()
	 */
	protected function afterDeleteForm() 
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'delete') return ; //表单状态不是已作废
		//删除所有明细
		foreach ($this->mainInfo->purchaseInvoiceDetails as $detail_data) 
		{
			//修改前信息
			$oldValue = $detail_data->datatoJson();
			$detailForInvoice = $detail_data->detailForInvoice;
			//关联修改前信息
			$dfi_oldValue = $detailForInvoice->datatoJson();
			//修改已销票的重量、金额
			$detailForInvoice->checked_weight -= $detail_data->weight;
			$detailForInvoice->checked_money -= $detail_data->fee;
			if ($detail_data->delete()) 
			{
				//日志
				$tableName = "PurchaseInvoiceDetail";
				$newValue = "";
				$dataArray = compact('tableName', 'newValue', 'oldValue');
				$this->dataLog($dataArray);
				//修改关联信息
				if ($detailForInvoice->update()) 
				{
					//日志
					$tableName = "DetailForInvoice";
					$oldValue = $dfi_oldValue;
					$newValue = $detailForInvoice->datatoJson();
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
				}
			}
		}
	}
	
	//-------------------------------------------------- 审核表单 --------------------------------------------------
	/**
	 * 审核采购发票通过后操作
	 * @see BaseForm::afterApproveForm()
	 */
	protected function afterApproveForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'approve') return;//表单状态不是已审核
		//
		foreach ($this->details as $detail)
		{
			$detailForInvoice = $detail->detailForInvoice;
			//修改前的信息
			$oldValue = $detailForInvoice->datatoJson();
			//修改已开票的重量、金额
			$detailForInvoice->checked_weight += $detail->weight;
			$detailForInvoice->checked_money += $detail->fee;
			if ($detailForInvoice->update())
			{
				//日志
				$tableName = "DetailForInvoice";
				$newValue = $detailForInvoice->datatoJson();
				$dataArray = compact('tableName', 'newValue', 'oldValue');
				$this->dataLog($dataArray);
			}
		}
	}
	
	//-------------------------------------------------- 取消审核 --------------------------------------------------
	/**
	 * 取消审核采购发票后操作
	 * @see BaseForm::afterCancelApproveForm()
	 */
	protected function afterCancelApproveForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'submited') return;//表单状态不是提交
		//
		foreach ($this->details as $detail)
		{
			$detailForInvoice = $detail->detailForInvoice;
			//修改前的信息
			$oldValue = $detailForInvoice->datatoJson();
			//修改已开票的重量、金额
			$detailForInvoice->checked_weight -= $detail->weight;
			$detailForInvoice->checked_money -= $detail->fee;
			if ($detailForInvoice->update())
			{
				//日志
				$tableName = "DetailForInvoice";
				$newValue = $detailForInvoice->datatoJson();
				$dataArray = compact('tableName', 'newValue', 'oldValue');
				$this->dataLog($dataArray);
			}
		}
	}
	
	//-------------------------------------------------- 销票 --------------------------------------------------
	/**
	 * 销票
	 */
	public function capiasForm() 
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->mainInfo->confirm_status == 1) return ;//表单状态为已销票
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->commonForm->form_status = 'capias';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$this->mainInfo->confirm_status = 1;
			$this->mainInfo->update();
			$this->afterCapiasForm();
			
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
// 		//发送消息
// 		$this->sendMessage("已销票",
// 				"销票登记已销票，单号：".$this->commonForm->form_sn,
// 				$this->commonForm->form_type,
// 				$this->commonForm->owned_by,
// 				Yii::app()->createUrl('purchaseInvoice/view', array('id' => $this->commonForm->id))
// 		);
		
		//新增日志
		$operation = "销票";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
	}
	
	/**
	 * 销票后操作
	 */
	public function afterCapiasForm() 
	{
		//采购单已销票
		foreach ($this->mainInfo->purchaseInvoiceDetails as $detail) 
		{			
			$detailForInvoice=$detail->detailForInvoice;
			$base=$detailForInvoice->relation_form;
			if($base->form_type=='CGTH')continue;			
			$purchaseDetail = $detail->purchaseDetail;
			$purchaseDetail->bill_done = 1;
			$purchaseDetail->update();
			//采购单是否已销票
			FrmPurchase::isBillDone($detail->frm_purchase_id);
		}
	}
	
	//-------------------------------------------------- 取消销票 --------------------------------------------------
	/**
	 * 取消销票
	 */
	public function cancelCapiasForm() 
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->mainInfo->confirm_status != 1) return;//表单状态不是已销票
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->commonForm->form_status = 'submited';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$this->mainInfo->confirm_status = 0;
			$this->mainInfo->update();
			$this->afterCancelCapiasForm();
				
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
// 		//发送消息
// 		$this->sendMessage("取消销票",
// 				"销票登记已取消销票，单号：".$this->commonForm->form_sn,
// 				$this->commonForm->form_type,
// 				$this->commonForm->owned_by,
// 				Yii::app()->createUrl('purchaseInvoice/view', array('id' => $this->commonForm->id))
// 		);
		
		//新增日志
		$operation = "取消销票";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
	}
	
	/**
	 * 取消销票后操作
	 */
	public function afterCancelCapiasForm() 
	{
		//采购单未销票
		foreach ($this->mainInfo->purchaseInvoiceDetails as $detail) 
		{
			$detailForInvoice=$detail->detailForInvoice;
		    $base=$detailForInvoice->relation_form;
			if($base->form_type=='CGTH')continue;
			$purchaseDetail = $detail->purchaseDetail;
			$purchaseDetail->bill_done = 0;
			$purchaseDetail->update();
			//采购单是否已销票
			FrmPurchase::isBillDone($detail->frm_purchase_id);
		}
	}

}