<?php 
class Transfer extends BaseForm
{
	public $mainModel = "OwnerTransfer";
	public $detailModel = "OwnerTransferDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="出库单";
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('OwnerTransfer')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->OwnerTransfer;
				$this->details=$model->OwnerTransfer->ownerTransferDetails;
			}
		}
	}
	
	/**
	 * 出库单创建并提交表单
	 */
	public function createSubmitForm($data)
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
			//提交表单
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->form_status = 'submited';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
	
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			if($this->mainInfo->is_return == 1){
				$result = $this->afterReturnSubmitForm();
			}else{
				$result = $this->afterSubmitForm();
			}
			if($result == false){
				return -2;
			}else if($result < 0){
				return $result;
			}
			//if($result === 1){$needwc = 1;}
			$transaction->commit();
		}catch (Exception $e)
		{
			echo "操作失败";
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//操作日志
		$operation = "新增";
		$this->operationLog($this->busName, $operation);
		$operation = "提交";
		$this->operationLog($this->busName, $operation);
		//完成销售单
// 		if($needwc == 1){
// 			$form=new Sales($this->mainInfo->frmsales->baseform->id);
// 			$result = $form->completeSales();
// 		}
		return true;
		//发送消息
	}
	
	/**
	 *
	 * 保存主体信息
	 * @see BaseForm::saveMainInfo()
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo = new OwnerTransfer();
		$mainInfo->frm_sales_id = $data->frm_sales_id;
		$mainInfo->title_id = $data->title_id;
		$mainInfo->company_id = $data->company_id;
		$mainInfo->company_name = $data->company_name;
		$mainInfo->warehouse_id = $data->warehouse_id;
		$mainInfo->team_id = $data->team_id;
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
		return null;
	}
	
	/**
	 * 保存明细
	 * @see BaseForm::saveDetails()
	 */
	protected function saveDetails($data)
	{
		$details = array();
		$output = $this->mainInfo;
		if(!is_array($data) || count($data) == 0){
			return false;
		}
		
		foreach ($data as $each){
			$outputDetail = new OwnerTransferDetail();
			$outputDetail->owner_transfer_id = $output->id;
			$outputDetail->amount = $each->amount;
			$outputDetail->weight = $each->weight;
			$outputDetail->storage_id = $each->storage_id;
			$outputDetail->product_id = $each->product_id;
			$outputDetail->rank_id = $each->rank_id;
			$outputDetail->brand_id = $each->brand_id;
			$outputDetail->texture_id = $each->texture_id;
			$outputDetail->length = $each->length;
			if($outputDetail->insert()){
				//明细日志
				$detailJson = $outputDetail->datatoJson();
				$dataArray = array("tableName"=>"OutputDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($details,$outputDetail);
			}
		}
		return $details;
	}
	
	/**
	 * 库存销售单出库表单提交
	 */
	public function SubmitForm(){
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'unsubmit') return false;//表单状态不是未提交
	
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->form_status = 'submited';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
	
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$result = $this->afterSubmitForm();
			if($result == false){
				return -2;
			}else if($result < 0){
				return $result;
			}
			if($result === 1){$needwc = 1;}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		//发送消息
	
		//新增日志
		$operation = "提交";
		$this->operationLog($this->busName, $operation);
		//完成销售单
		if($needwc == 1){
			$form=new Sales($this->mainInfo->frmsales->baseform->id);
			$result = $form->completeSales();
		}
		return true;
	}
	
	/**
	 * 转库单提交后动作
	 */
	protected function afterSubmitForm(){
		//修改销售单已出库件数
		$output = $this->mainInfo;
		$output = OwnerTransfer::model()->findByPk($output->id);
		$details = $output->ownerTransferDetails;
		$oldOutput = $output->datatoJson();
		$sales = $output->frmsales;
		$oldSales=$sales->datatoJson();
		
		if($details){
			foreach ($details as $each){
				$total_amount = $each->amount;
				$total_weight = $each->weight;
				$warehouse_id = $each->storage_id;
				$type['product'] = $each->product_id;
				$type['rank'] = $each->rank_id;
				$type['brand'] = $each->brand_id;
				$type['texture'] = $each->texture_id;
				$type['length'] = intval($each->length);
				$weight = DictGoods::getUnitWeight($type);
				if($weight == 0){
					$weight = $each->weight/$each->amount;
				}
				//修改销售明细件数
				$model = new SalesDetail();
				$criteria=New CDbCriteria();
				$criteria->compare('frm_sales_id',$output->frm_sales_id,false);
				$criteria->compare('product_id',$each->product_id,false);
				$criteria->compare('brand_id',$each->brand_id,false);
				$criteria->compare('texture_id',$each->texture_id,false);
				$criteria->compare('rank_id',$each->rank_id,false);
				$criteria->compare('length',intval($each->length),false);
				//$criteria->compare('card_id',$warehouse_id,false);
				$salesDetails=$model->find($criteria);
				if($salesDetails){
					$oldJson=$salesDetails->datatoJson();
					$salesDetails->warehouse_output_amount +=$each->amount;
					$salesDetails->warehouse_output_weight +=$each->weight;
					$salesDetails->output_amount +=$each->amount;
					if($salesDetails->output_amount > $salesDetails->amount){return false;}
					$salesDetails->output_weight +=$each->weight;
					$salesDetails->update();
					$mainJson = $salesDetails->datatoJson();
					$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					//更新聚合库存信息
					$mestaroge = $salesDetails->mergestorage;
					$oldSt = $mestaroge->datatoJson();
					$mestaroge->left_amount -= $each->amount;
					$mestaroge->left_weight -= $each->weight;
					$mestaroge->lock_amount -= $each->amount;
					$mestaroge->update();
					$mainJson = $mestaroge->datatoJson();
					$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldSt);
					$this->dataLog($dataArray);
					//设置可开票明细
					$std = DictGoodsProperty::getFullProName($each->product_id);
					if($std != "螺纹钢" && $sales->is_yidan == 0){
						$t_weight = $each->weight;
						$price = $t_weight*($salesDetails->price);
						$invoice = DetailForInvoice::setSalesInvoice($salesDetails->FrmSales->baseform->id,$salesDetails->id,$t_weight,$price,$sales->title_id,$sales->customer_id,$sales->client_id);
						if(!$invoice){
							return false;
						}
					}
				}
				//更新库存信息
				$storage = $each->storage;
				$oldSt = $storage->datatoJson();
				$storage->left_amount -= $each->amount;
				$storage->left_weight -= $each->weight;
				if($storage->left_amount < 0){return -1;}
				//$storage->lock_amount += $each->amount;
				$storage->update();
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldSt);
				$this->dataLog($dataArray);
				//托盘库存，更新托盘赎回信息
				if($storage->is_pledge == 1){
					$limit = $storage->purchase->pledge->r_limit;
					$pledge = new PledgeRedeemed();
					$criteria=New CDbCriteria();
					$criteria->addCondition('purchase_id='.$storage->purchase_id);
					$criteria->addCondition('brand_id='.$storage->inputDetail->brand_id);
					if($limit == 2){
						$criteria->addCondition('product_id='.$storage->inputDetail->product_id);
					}
					$pledge = $pledge->find($criteria);
					if($pledge){
						$oldpl = $pledge->datatoJson();
						$pledge->left_weight -= $each->weight;
						if($pledge->left_weight < 0){return -5;}
						$pledge->update();
						$mainJson = $pledge->datatoJson();
						$dataArray = array("tableName"=>"PledgeRedeemed","newValue"=>$mainJson,"oldValue"=>$oldpl);
						$this->dataLog($dataArray);
					}else{
						return -5;
					}
				}
			}
		}
		
		$output->input_status = 1;
// 		$output->output_at = time();
// 		$output->output_by = currentUserId();
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		//销售单主表已出库件数
	
		$sales->output_amount += $sum_amount;
		$sales->output_weight += $sum_weight;
		$sales->update();
		$mainJson = $sales->datatoJson();
		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldSales);
		$this->dataLog($dataArray);
		if($sales->amount == $sales->output_amount){
			return 1;
		}
		return true;
	}
	
	/**
	 *
	 * 作废表单
	 * @see BaseForm::deleteForm()
	 */
	public function deleteForm($delete_reason)
	{
		$details = $this->details;
		$output = $this->mainInfo;
		//作废配送单
		$commonForm = $this->commonForm;
		$oldJson = $commonForm->datatoJson();
		$commonForm->form_status = "delete";
		$commonForm->is_deleted = 1;
		$this->commonForm->delete_reason= $delete_reason;
		$commonForm->update();
		$mainJson = $commonForm->datatoJson();
		$dataArray = array("tableName"=>"CommonForm","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$this->dataLog($dataArray);
		$oldOut = $output->datatoJson();
		$output->input_status = 2;
		$output->update();
		$newJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$newJson,"oldValue"=>$oldOut);
		$this->dataLog($dataArray);
	}
}