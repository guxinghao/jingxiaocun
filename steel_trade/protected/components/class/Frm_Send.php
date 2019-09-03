<?php
//配送单
class Frm_Send extends BaseForm
{
	public $mainModel = "FrmSend";
	public $has_detials = true;
	public $isAutoApprove = true;
	public $busName="配送单";
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('sales')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->send;
				$this->details=$model->send->sendDetails;
			}
		}
	}
	
	/**
	 * 
	 * 保存主体信息
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo = new FrmSend();
		
		$mainInfo->auth_type = $data->auth_type;
		$mainInfo->auth_text = $data->auth_text;
		$mainInfo->amount = $data->amount;
		$mainInfo->weight = $data->weight;
		$mainInfo->frm_sales_id = $data->frm_sales_id;
		$mainInfo->status = "unpush";
		$mainInfo->auth_code = FrmSend::setCode();
		$mainInfo->start_time = strtotime($data->start_time);
		$mainInfo->end_time = strtotime($data->end_time);
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmSend","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
		return null;
	}
	
	/**
	 * 
	 * 保存明细
	 * @see BaseForm::saveDetails()
	 */
	protected function saveDetails($data)
	{
		$details = array();
		$send = $this->mainInfo;
		if(!is_array($data) || count($data) == 0){
			return false;
		}
		foreach ($data as $each){
			$SendDetail = new FrmSendDetail();
			
			$SendDetail->frm_send_id = $send->id;
			$SendDetail->sales_detail_id = $each->sales_detail_id;
			$SendDetail->amount = $each->amount;
			$SendDetail->weight = $each->weight;
			$SendDetail->product_id = $each->product_id;
			$SendDetail->rank_id = $each->rank_id;
			$SendDetail->brand_id = $each->brand_id;
			$SendDetail->texture_id = $each->texture_id;
			$SendDetail->length = $each->length;
			if($SendDetail->insert()){
				//明细日志
				$detailJson = $SendDetail->datatoJson();
				$dataArray = array("tableName"=>"FrmSendDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($details,$SendDetail);
			}
		}
		return $details;
	}
	
	
	
	/**
	 * 
	 * after创建配送单
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm() 
	{
		//修改销售单已配送件数
		$details = $this->details;
		$send = $this->mainInfo;
		if($details){
			foreach ($details as $each){
				$salesDetails = $each->salesDetail;
				$oldJson=$salesDetails->datatoJson();
				$salesDetails->send_amount += $each->amount;
				$salesDetails->send_weight += $each->weight;
				$salesDetails->update();
				$mainJson = $salesDetails->datatoJson();
				$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
		$sales = $send->FrmSales;
		if($sales->can_push == 1 && $sales->sales_type != "xxhj" && Yii::app()->params['api_switch'] == 1){
			//推送配送单
			$result = FrmSend::BillPush($this->mainInfo->id,"deliveryform","Add");
		}
	}
	
	
	/**
	 * 推送配送单
	 */
	public function sendForm()
	{
		//调用接口中心数据推送配送单到接口中心
		
		return true;
	}
	
	
	/**
	 * 
	 * 修改主体信息
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data)
	{
		//查询配送单推送到接口中心的状态，如果是已审核则只能修改备注、车号、提货方式
		$checked = 0;
		$send = $this->mainInfo;
		$oldJson=$send->datatoJson();
		$send->auth_type = $data->auth_type;
		$send->auth_text = $data->auth_text;
		$send->start_time = strtotime($data->start_time);
		$send->end_time = strtotime($data->end_time);
		if($send->update()){
			$mainJson = $send->datatoJson();
			$dataArray = array("tableName"=>"FrmSend","newValue"=>$mainJson,"oldValue"=>$oldJson);
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
		//配送单不能修改明细
		//查询配送单推送到接口中心的状态，如果是已审核则只能修改备注、车号、提货方式

	}
	
	/**
	 * 
	 * after修改
	 * @see BaseForm::afterupdateForm()
	 */
	protected function afterupdateForm()
	{
		//修改销售单已配送件数
		$details = $this->details;
		$sales = $this->mainInfo->FrmSales;
		
		//推送修改后的配送单到接口中心
		if($sales->can_push == 1 && $sales->sales_type != "xxhj" && Yii::app()->params['api_switch'] == 1){
			FrmSend::BillPush($this->mainInfo->id,"deliveryform","Edit");
		}
	}
	
	/**
	 * 
	 * 不需要提交，可以直接作废，重写作废方法
	 * @see BaseForm::deleteForm()
	 */
	public function deleteForm($delete_reason="")
	{
		$details = $this->details;
		$transaction=Yii::app()->db->beginTransaction();
		try {
		//作废配送单
			$commonForm = $this->commonForm;
			$oldJson = $commonForm->datatoJson();
			$commonForm->form_status = "delete";
			$commonForm->is_deleted = 1;
			//$this->commonForm->delete_reason= $delete_reason;
			$commonForm->update();
			$mainJson = $commonForm->datatoJson();
			$dataArray = array("tableName"=>"CommonForm","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$send = $this->mainInfo;
			$sendJson = $send->datatoJson();
			$send->status = "deleted";
			$send->update();
			$mainJson = $send->datatoJson();
			$dataArray = array("tableName"=>"frmSend","newValue"=>$mainJson,"oldValue"=>$sendJson);
			$this->dataLog($dataArray);
			
			//修改销售单配送件数
			if($details){
				foreach ($details as $each){
					if($each->output_amount > 0){return false;}
					$salesDetails = $each->salesDetail;
					$oldJson=$salesDetails->datatoJson();
					$salesDetails->send_amount -= $each->amount;
					$salesDetails->send_weight -= $each->weight;
					$salesDetails->update();
					$mainJson = $salesDetails->datatoJson();
					$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}
			}
		$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return false;
		}
		return true;
	}
	
	/**
	 * 可以不要，直接写逻辑在deleteForm里面
	 * @see BaseForm::afterDeleteForm()
	 */
	protected function afterDeleteForm()
	{
		
	}
	
	
	/**
	 * 完成配送单
	 * 手动完成配送单
	 */
	public function completeSendForm()
	{
		//配送单完成
		$send=$this->mainInfo;
		$oldsend = $send->datatoJson();
		$send->status = finished;
		$send->is_complete = 1;
		
		//修改销售单已配送件数
		$total_amount = 0;
		$total_weight = 0;
		$details = $this->details;
		//更新销售单已配送件数
		if($details){
			foreach ($details as $each){
				$cha = $each->amount - $each->output_amount;
				//出库件数小于配送件数，数据异常
				if($cha < 0){
					return false;
				}
				//出库件数等于配送件数，销售单无需处理
				if($cha == 0){continue;}
				$salesDetails = $each->salesDetail;
				$oldJson=$salesDetails->datatoJson();
				$type['product'] = $salesDetails->product_id;
				$type['rank'] = $salesDetails->rank_id;
				$type['brand'] = $salesDetails->brand_id;
				$type['texture'] = $salesDetails->texture_id;
				$type['length'] = $salesDetails->length;
				$weight = DictGoods::getUnitWeight($type);
				$salesDetails->send_amount -=$cha;
				$salesDetails->send_weight -=$cha * $weight;
				$salesDetails->update();
				$mainJson = $salesDetails->datatoJson();
				$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
		
// 		//更新配送单主体
		$send->update();
		$mainJson = $send->datatoJson();
		$dataArray = array("tableName"=>"FrmSend","newValue"=>$mainJson,"oldValue"=>$oldsend);
		$this->dataLog($dataArray);
		return true;
	}
	
	
}