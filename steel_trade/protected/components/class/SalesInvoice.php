<?php
/**
 * 销售开票
 * @author leitao
 *
 */
class SalesInvoice extends BaseForm 
{
	public $mainModel = 'FrmSalesInvoice';
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName = "销售开票";
	
	public function __construct($id) 
	{
		if (!$id) return ;
		$model = CommonForms::model()->with('salesInvoice')->findByPk($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->salesInvoice;
		$this->details = $model->salesInvoice->salesInvoiceDetails;
	}
	
	//-------------------------------------------------- 创建表单 --------------------------------------------------
	
	public function createForm($data)
	{
		$common = $data['common'];
		$main = $data['main'];
		$detail_array = $data['detail'];
		if ($common == null || $main == null)
			return false;	
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//保存基础信息
			$this->commonForm = $this->_saveCommonInfo($common);
			if ($this->commonForm == null) return false;				
			//主体信息
			$this->mainInfo = $this->saveMainInfo($main);				
			if ($this->mainInfo == null) return false;				
			//保存完主体，获取id，修改commonForm
			$sn = $this->_generateSN($this->commonForm->form_type,$this->mainInfo->id);
			$this->commonForm->form_sn = $sn;
			$this->commonForm->form_id = $this->mainInfo->id;
			$this->commonForm->update();				
			if ($this->has_detials){
				if (!is_array($detail_array)) return false;
	
				$this->details = $this->saveDetails($detail_array);
				if (!is_array($this->details)||count($this->details) == 0) return false;
			}
			$this->afterCreateForm();				
			$transaction->commit();
		}catch (Exception $e)
		{			
// 			echo "操作失败";
			$transaction->rollBack();//事务回滚
			if($e->message=='已开重量大于可开重量')return $e->message;
			else	return "操作失败";
		}
		//操作日志
		$operation = "新增";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		//发送消息
		return $this->commonForm->id;
	}
	
	
	/**
	 * 创建销售发票
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data) 
	{
		$mainInfo = new FrmSalesInvoice();
		$mainInfo->invoice_type = 'sales';
		$mainInfo->company_id = $data->company_id;
		$mainInfo->client_id = $data->client_id;
		$mainInfo->title_id = $data->title_id;
		$mainInfo->weight = $data->weight;
		$mainInfo->fee = $data->fee;
		$mainInfo->invoice_amount = $data->invoice_amount;
		$mainInfo->invoice_number = $data->invoice_number;
		$mainInfo->confirm_status = 0;
		if ($mainInfo->insert()) 
		{
			//新增日志
			$tableName = "FrmSalesInvoice";
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
			$detail = new SalesInvoiceDetail();
			$detail->sales_detail_id = $each->sales_detail_id;			
			$detail->fee = $each->fee;
			$detail->sales_invoice_id = $this->mainInfo->id;
			$detail->type = $detail->detailForInvoice->type;
			$detail->weight = $each->weight;
			
			$detail->frm_sales_id = $detail->detailForInvoice->relation_form->form_id;
			$detail->frm_sales_detail_id = $detail->detailForInvoice->detail_id;
			if ($detail->insert()) 
			{
				//日志
				$tableName = "SalesInvoiceDetail";
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
				if($detailForInvoice->checked_weight>$detailForInvoice->weight)
				{
					throw new CException('已开重量大于可开重量');
				}
				if ($detailForInvoice->update())
				{
					//日志
					$tableName = "DetailForInvoice";
					$oldValue = $dfi_oldValue;
					$newValue = $detailForInvoice->datatoJson();
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
				}
				//添加明细
				array_push($detail_array, $detail);
			}
		}
		return $detail_array;	
	}
	
	//-------------------------------------------------- 修改表单 --------------------------------------------------
	
	public function updateForm($data)
	{
		$common = $data['common'];
		$main = $data['main'];
		$detail_array = $data['detail'];
	
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//修改基础信息
			if ($common != null){
				$comResult = $this->_updateCommonInfo($common);
				if (!$comResult) return false;
			}
			//修改主体信息
			if ($main != null){
				$mainResult = $this->updateMainInfo($main);
				if (!$mainResult) return false;
			}
			//修改明细
			if ($this->has_detials && is_array($detail_array)){
				$detResult= $this->updateDetails($detail_array);
				if (!$detResult) return false;
			}
			$this->afterUpdateForm();
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();
			if($e->message=='已开重量大于可开重量')return $e->message;
			else	return "操作失败";
		}
		//发送消息
	
		//新增日志
		$operation = "修改";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 修改销售发票
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
			//修改销售发票
			$this->mainInfo->company_id = $data->company_id;
			$this->mainInfo->title_id = $data->title_id;
			$this->mainInfo->client_id = $data->client_id;
			$this->mainInfo->weight = $data->weight;
			$this->mainInfo->fee = $data->fee;
			$this->mainInfo->invoice_amount = $data->invoice_amount;
			$this->mainInfo->invoice_number = $data->invoice_number;
// 			$this->mainInfo->confirm_status = $data->confirm_status;
		}
		if ($this->mainInfo->update()) 
		{
			//新增修改日志
			$tableName = "FrmSalesInvoice";
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
			if ($item->id)
				array_push($id_array, $item->id);
		}
		
		foreach ($this->details as $each) 
		{
			//删除明细
			if (!in_array($each->id, $id_array)) 
			{
				//删除前信息
				$newValue = $each->datatoJson();
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
					$tableName = "SalesInvoiceDetail";
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
				$detail = SalesInvoiceDetail::model()->findByPk($data_each->id);
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
				if($detailForInvoice->checked_weight>$detailForInvoice->weight)
				{
					throw new CException('已开重量大于可开重量');
				}
				//修改明细信息
				$detail->weight = $data_each->weight;
				$detail->fee = $data_each->fee;
				if ($detail->update()) 
				{
					//日志
					$tableName = "SalesInvoiceDetail";
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
				$detail = new SalesInvoiceDetail();
				$detail->sales_detail_id = $data_each->sales_detail_id;				
				$detail->fee = $data_each->fee;
				$detail->sales_invoice_id = $this->mainInfo->id;
				
				$detailForInvoice = $detail->detailForInvoice;				
				if (!$detailForInvoice) continue;				
				$detail->type=$detailForInvoice->type;
			
				$detail->weight = $data_each->weight;
				if ($detail->insert()) 
				{
					//日志
					$tableName = "SalesInvoiceDetail";
					$oldValue = "";
					$newValue = $detail->datatoJson();
					$dataArray = compact('tableName', 'newValue', 'oldValue');
					$this->dataLog($dataArray);
					//修改关联信息
					
					//关联修改前信息
					$dfi_oldValue = $detailForInvoice->datatoJson();
					//修改已销票的重量、金额
					$detailForInvoice->checked_weight += $detail_data->weight;
					$detailForInvoice->checked_money += $detail_data->fee;
					if($detailForInvoice->checked_weight>$detailForInvoice->weight)
					{
						throw new CException('已开重量大于可开重量');
					}
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
		foreach ($this->mainInfo->salesInvoiceDetails as $detail_data) 
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
				$tableName = "SalesInvoiceDetail";
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
	
	//-------------------------------------------------- 开票 --------------------------------------------------
	/**
	 * 开票
	 */
	public function invoiceForm() 
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->mainInfo->confirm_status == 1) return;//表单状态为已开票
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->commonForm->form_status = 'invoice';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$this->mainInfo->confirm_status = 1;
			$this->mainInfo->update();
			$this->afterInvoiceForm();
			
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//发送消息
// 		if (Yii::app()->user->userid != $this->commonForm->owned_by) 
// 		{
// 			$this->sendMessage("已开票",
// 					"开票登记已开票，单号：".$this->commonForm->form_sn,
// 					$this->commonForm->form_type,
// 					$this->commonForm->owned_by,
// 					Yii::app()->createUrl('salesInvoice/view', array('id' => $this->commonForm->id))
// 			);
// 		}
		
		//新增日志
		$operation = "开票";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
	}
	
	/**
	 * 开票后操作
	 */
	public function afterInvoiceForm() 
	{
		
	}
	
	//-------------------------------------------------- 取消开票 --------------------------------------------------
	/**
	 * 取消开票
	 */
	public function cancelInvoiceForm() 
	{
		if ($this->commonForm == null) return false; //表单为空
		if ($this->mainInfo->confirm_status != 1) return ; //表单状态不是已开票
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$this->commonForm->form_status = 'submited';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$this->mainInfo->confirm_status = 0;
			$this->mainInfo->update();
			$this->afterCancelInvoiceForm();
			
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
// 		//发送消息
// 		if (Yii::app()->user->userid != $this->commonForm->owned_by) {
// 			$this->sendMessage("取消销票", 
// 					"开票登记已取消开票，单号：".$this->commonForm->form_sn, 
// 					$this->commonForm->form_type, 
// 					$this->commonForm->owned_by, 
// 					Yii::app()->createUrl('salesInvoice/view', array('id' => $this->commonForm->id))
// 			);
// 		}
		//新增日志
		$operation = "取消开票";
		$this->operationLog($this->busName, $operation, $this->commonForm->form_sn);
	}
	
	/**
	 * 取消开票后操作
	 */
	public function afterCancelInvoiceForm() 
	{
		
	}

}