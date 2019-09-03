<?php
class Output extends BaseForm
{
	public $mainModel = "FrmOutput";
	public $detailModel = "OutputDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="出库单";
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('output')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->output;
				$this->details=$model->output->outputDetails;
			}
		}
	}
	
	/**
	 * 出库单创建并提交表单
	 */
	public function createSubmitOutForm($data)
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
				if($this->mainInfo->push_id && $this->mainInfo->warehouseOutputs->output_type == "transfer"){
					$result = $this->afterZkSubmitForm();
				}else{
					$sales = $this->mainInfo->frmsales;
					if($sales->sales_type == "normal"){	
						$result = $this->afterSalesSubmitForm();
					}else{
						$result = $this->afterSubmitForm();
					}
				}
			}
			if($result == false){
				return -2;
			}else if($result < 0){
				return $result;
			}
			if($result === 1){$needwc = 1;}
			$transaction->commit();
		}catch (Exception $e)
		{
			//echo "操作失败";
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//操作日志
		$operation = "新增";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		//完成销售单
		if($needwc == 1){
			$form=new Sales($this->mainInfo->frmsales->baseform->id);
			$result = $form->completeSales();
		}
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
		$mainInfo = new FrmOutput();

		$mainInfo->frm_sales_id = $data->frm_sales_id;
		$mainInfo->push_id = $data->push_id;
		$mainInfo->output_amount = $data->amount;
		$mainInfo->output_weight = $data->weight;
		$mainInfo->is_return = $data->is_return;
		$mainInfo->from = $data->from;
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
		$warehouse_id = $output->frmsales->warehouse_id;
		foreach ($data as $each){
			$outputDetail = new OutputDetail();
			$outputDetail->frm_output_id = $output->id;
			$outputDetail->amount = $each->amount;
			$outputDetail->weight = $each->weight;
			$outputDetail->storage_id = $each->storage_id;
			$outputDetail->product_id = $each->product_id;
			$outputDetail->rank_id = $each->rank_id;
			$outputDetail->brand_id = $each->brand_id;
			$outputDetail->texture_id = $each->texture_id;
			$outputDetail->length = $each->length;
			$outputDetail->sales_detail_id = $each->sales_detail_id;
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
	 *
	 * after创建表单
	 * @see BaseForm::afterCreateForm()
	 */
	protected function afterCreateForm()
	{
		
	}
	
	/**
	 * 修改并提交表单表单
	 */
	public function updateSubmitForm($data)
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
				if($this->mainInfo->push_id && $this->mainInfo->warehouseOutputs->output_type == "transfer"){
					$result = $this->afterZkSubmitForm();
				}else{
					$sales = $this->mainInfo->frmsales;
					if($sales->sales_type == "normal"){
						$result = $this->afterSalesSubmitForm();
					}else{
						$result = $this->afterSubmitForm();
					}
				}	
			}
			if($result == false){
				return -2;
			}else if($result < 0){
				return $result;
			}
			if($result === 1){$needwc = 1;}
			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();
			return "操作失败";
		}
		//发送消息
	
		//新增日志
		$operation = "修改";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		//完成销售单
		if($needwc == 1){
			$form=new Sales($this->mainInfo->frmsales->baseform->id);
			$result = $form->completeSales();
		}
		return true;
	}
	
	/**
	 *
	 * 修改主体信息
	 * @see BaseForm::updateMainInfo()
	 */
	protected function updateMainInfo($data)
	{
		$mainInfo = $this->mainInfo;
		$oldJson = $mainInfo->datatoJson();
		$mainInfo->output_amount = $data->amount;
		$mainInfo->output_weight = $data->weight;
		if($mainInfo->update()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
		return  true;
	}
	
	/**
	 * 修改明细
	 * @see BaseForm::updateDetails()
	 */
	protected function updateDetails($data)
	{
		$output = $this->mainInfo;
		
		$details=$this->details;
		if($data && count($data) >0){
			$id_array=array();
			foreach ($data as $e)
			{
				if($e->id)
				{
					array_push($id_array,$e->id);
				}
			}
		}
		//处理被删除的数据
		if(empty($id_array))
		{
			foreach ($details as $each)
			{
				$oldJson=$each->datatoJson();
				$each->delete();
				$dataArray = array("tableName"=>"OutputDetail","newValue"=>"","oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}else{
			foreach ($details as $each)
			{
				if(!in_array($each->id,$id_array))
				{
					$oldJson=$each->datatoJson();
					$each->delete();
					$dataArray = array("tableName"=>"OutputDetail","newValue"=>"","oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}
			}
		}
		
		$warehouse_id = $output->frmsales->warehouse_id;
		foreach ($data as $each){
			if($each->id){
				$outputDetail = OutputDetail::model()->findByPk($each->id);
				$oldJson=$outputDetail->datatoJson();
				$outputDetail->amount = $each->amount;
				$outputDetail->weight = $each->weight;
				$outputDetail->storage_id = $each->storage_id;
				$outputDetail->product_id = $each->product_id;
				$outputDetail->rank_id = $each->rank_id;
				$outputDetail->brand_id = $each->brand_id;
				$outputDetail->texture_id = $each->texture_id;
				$outputDetail->length = $each->length;
				$outputDetail->sales_detail_id = $each->sales_detail_id;
				$outputDetail->update();
				$mainJson = $outputDetail->datatoJson();
				$dataArray = array("tableName"=>"OutputDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}else{				
				$outputDetail = new OutputDetail();
				$outputDetail->frm_output_id = $output->id;
				$outputDetail->amount = $each->amount;
				$outputDetail->weight = $each->weight;
				$outputDetail->storage_id = $each->storage_id;
				$outputDetail->product_id = $each->product_id;
				$outputDetail->rank_id = $each->rank_id;
				$outputDetail->brand_id = $each->brand_id;
				$outputDetail->texture_id = $each->texture_id;
				$outputDetail->length = $each->length;
				$outputDetail->sales_detail_id = $each->sales_detail_id;
				
				if($outputDetail->insert()){
					$mainJson = $outputDetail->datatoJson();
					$dataArray = array("tableName"=>"OutputDetail","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);
					array_push($this->details,$outputDetail);
				}

			}
		}
		return true;
	}
	
	/**
	 *
	 * after修改
	 * @see BaseForm::afterupdateForm()
	 */
	protected function afterupdateForm()
	{
		$details = $this->details;
	
		
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
	
	/**
	 * 库存销售单出库表单提交
	 */
	public function salesSubmitForm(){
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
			$result = $this->afterSalesSubmitForm();
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
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		//完成销售单
		if($needwc == 1){
			$form=new Sales($this->mainInfo->frmsales->baseform->id);
			$result = $form->completeSales();
		}
		return true;
	}
	
	/**
	 * 库存销售出库提交后动作
	 */
	protected function afterSalesSubmitForm(){
		//修改销售单已出库件数
		$output = $this->mainInfo;
		$output = FrmOutput::model()->findByPk($output->id);
		$details = $output->outputDetails;
		$oldOutput = $output->datatoJson();
		$sum_amount = 0;
		$sum_weight = 0;
		$sales = $output->frmsales;
		$oldSales=$sales->datatoJson();
		if($details){
			foreach ($details as $each){
				$sum_amount += $each->amount;
				$sum_weight += $each->weight;
				$send_amount = $each->amount;
				$send_weight = $each->weight;
				$type['product'] = $each->product_id;
				$type['rank'] = $each->rank_id;
				$type['brand'] = $each->brand_id;
				$type['texture'] = $each->texture_id;
				$type['length'] = intval($each->length);
				$weight = DictGoods::getUnitWeight($type);
				//如果是仓库推送的数组 修改配送单明细
				if($output->push_id){
					$frm_send_id = $output->warehouseOutputs->frm_send_id;
					if($frm_send_id){
						$send = FrmSend::model()->findByPk($frm_send_id);
						$oldSend = $send-> datatoJson();
						$sendDetail = new FrmSendDetail();
						$c = New CDbCriteria();
						$c->compare('frm_send_id',$frm_send_id,false);
						$c->compare('product_id',$each->product_id,false);
						$c->compare('brand_id',$each->brand_id,false);
						$c->compare('texture_id',$each->texture_id,false);
						$c->compare('rank_id',$each->rank_id,false);
						$c->compare('length',intval($each->length),false);
						$sendDetail=$sendDetail->findAll($c);
						
						if($sendDetail){
							$cc_amount = $each->amount;
							$cc_weight = $each->weight;
							foreach ($sendDetail as $sd){
								$oldJson=$sd->datatoJson();
								//获取此条明细可以增加的最大数量
								$can_amount = $sd->amount - $sd->output_amount;
								$can_weight = $sd->weight - $sd->output_weight;
								$m_amount = $m_weight = 0;
								//配送明细可以完全增加出库明细数量
								if($can_amount >=$cc_amount){
									$sd->output_amount +=$cc_amount;
									$sd->output_weight +=$cc_weight;
									$cc_amount = 0;
									$cc_weight = 0;
									//配送明细不足以吃掉出库出库件数
								}else{
									$sd->output_amount +=$can_amount;
									$sd->output_weight +=$can_weight;
									$cc_amount -= $can_amount;
									$cc_weight -= $can_weight;
								}
								$sd->update();
								$mainJson = $sd->datatoJson();
								$dataArray = array("tableName"=>"FrmSendDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
								$this->dataLog($dataArray);
							}
							if($cc_amount > 0){ return -2;}
						}else{
							//配送单内找不到对应规格的信息，数据有误，抛出异常
							return  false;
						}
					}
				}
			}
		}
		
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
				//修改销售明细件数
				$model = new SalesDetail();
				$criteria=New CDbCriteria();
				$criteria->compare('frm_sales_id',$output->frm_sales_id,false);
				$criteria->compare('product_id',$each->product_id,false);
				$criteria->compare('brand_id',$each->brand_id,false);
				$criteria->compare('texture_id',$each->texture_id,false);
				$criteria->compare('rank_id',$each->rank_id,false);
				$criteria->compare('length',intval($each->length),false);
				$criteria->addCondition('output_amount<>amount');
				$salesDetails=$model->findAll($criteria);
				if($salesDetails){
						$cc_amount = $each->amount;
						$cc_weight = $each->weight;
						foreach ($salesDetails as $sd){
								$oldJson=$sd->datatoJson();
								//获取此条明细可以增加的最大数量
								$can_amount = $sd->amount - $sd->warehouse_output_amount;
								$can_weight = $sd->weight - $sd->warehouse_output_weight;
								$m_amount = $m_weight = 0;
								//销售明细可以完全增加出库明细数量
								if($can_amount >=$cc_amount){
									$sd->warehouse_output_amount +=$cc_amount;
									$sd->warehouse_output_weight +=$cc_weight;
									if(!$output->push_id){
										$sd->warehouse_amount +=$cc_amount;
										$sd->warehouse_weight +=$cc_weight;
									}
									$sd->output_amount +=$cc_amount;
									$sd->output_weight +=$cc_weight;
									$m_amount = $cc_amount;
									$m_weight = $cc_weight;
									$cc_amount = 0;
									$cc_weight = 0;
								//销售明细不足以吃掉出库出库件数
								}else{
									$sd->warehouse_output_amount +=$can_amount;
									$sd->warehouse_output_weight +=$can_weight;
									if(!$output->push_id){
										$sd->warehouse_amount +=$can_amount;
										$sd->warehouse_weight +=$can_weight;
									}
									$sd->output_amount +=$can_amount;
									$sd->output_weight +=$can_weight;
									$cc_amount -= $can_amount;
									$cc_weight -= $can_weight;
									$m_amount = $can_amount;
									$m_weight = $can_weight;
								}
								$sd->update();
								$mainJson = $sd->datatoJson();
								$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
								$this->dataLog($dataArray);
								//更新聚合库存信息
								$mestaroge = $sd->mergestorage;
								if($mestaroge){
									$oldSt = $mestaroge->datatoJson();
									$mestaroge->left_amount -= $m_amount;
									$mestaroge->left_weight -= $m_weight;
									$mestaroge->lock_amount -= $m_amount;
									$mestaroge->lock_weight -= $m_weight;
									$connection=Yii::app()->db;
									$sql="update merge_storage set lock_amount=lock_amount-".$m_amount.",lock_weight=lock_weight-".$m_weight.",left_amount=left_amount-".$m_amount.",left_weight=left_weight-".$m_weight."  where id=".$sd->card_id;
									$connection->createCommand($sql)->execute();
// 									$mestaroge->update();
									$mestaroge=MergeStorage::model()->findByPk($sd->card_id);
									$mainJson = $mestaroge->datatoJson();
									$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldSt);
									$this->dataLog($dataArray);
								}

							//设置可开票明细
							// $std = DictGoodsProperty::getFullProName($each->product_id);
							// if($std != "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
							// 	$t_weight = $m_weight;
							// 	$price = $m_weight*$sd->price;
							// 	$invoice = DetailForInvoice::setSalesInvoice($sales->baseform->id,$sd->id,$t_weight,$price,$sales->title_id,$sales->customer_id,$sales->client_id);
							// 	if(!$invoice){
							// 		return false;
							// 	}
							// }

						}
						if($cc_amount > 0){ return -2;}
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
				}else{
					return false;
				}
			}
		}
		if($output->push_id){
			if($send){
				$send->output_amount += $sum_amount;
				$send->output_weight += $sum_weight;
				$send->update();
				$newsend = $send->datatoJson();
				$dataArray = array("tableName"=>"FrmSend","newValue"=>$newsend,"oldValue"=>$oldSend);
				$this->dataLog($dataArray);
			}
		}
		$output->input_status = 1;
		$output->output_at = time();
		$output->output_by = currentUserId();
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		//销售单主表已出库件数
		
		$sales->output_amount += $sum_amount;
		$sales->output_weight += $sum_weight;
		if(!$output->push_id){
			$sales->warehouse_amount += $sum_amount;
			$sales->warehouse_weight += $sum_weight;
		}
		$sales->update();
		$mainJson = $sales->datatoJson();
		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldSales);
		$this->dataLog($dataArray);
		if($sales->amount == $sales->output_amount){
			return 1;	
		}
		return true;
	}
	
	//转库单提交后的动作
	protected function afterZkSubmitForm(){
		$output = $this->mainInfo;
		$output = FrmOutput::model()->findByPk($output->id);
		$details = $output->outputDetails;
		$oldOutput = $output->datatoJson();
		$warehosue = $output->warehouseOutputs;
		
		if($details){
			foreach ($details as $each){
				$total_amount = $each->amount;
				$total_weight = $each->weight;
				$warehouse_id = $each->storage_id;
				//更新聚合库存表
				$model = new MergeStorage();
				$criteria=New CDbCriteria();
				$criteria->addCondition('warehouse_id ='.$warehosue->warehouse_id);
				$criteria->addCondition('product_id ='.$each->product_id);
				$criteria->addCondition('brand_id ='.$each->brand_id);
				$criteria->addCondition('texture_id ='.$each->texture_id);
				$criteria->addCondition('rank_id ='.$each->rank_id);
				$criteria->addCondition('length ='.$each->length);
				$criteria->addCondition('title_id ='.$warehosue->title_id);
				$criteria->addCondition('is_transit = 0');
				$criteria->addCondition('is_deleted = 0');
				$merge = $model->find($criteria);
				if($merge){
					$oldJson=$merge->datatoJson();
					$merge->left_amount -= $each->amount;
					$merge->left_weight -= $each->weight;
					if($merge->update()){
						$mainJson = $merge->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
					}
				}
				//更新库存
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
			}
		}

		$output->input_status = 1;
		$output->output_at = time();
		$output->output_by = currentUserId();
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		$oldJson = $warehosue->datatoJson();
		$warehosue->status = 1;
		if($warehosue->update()){
			$mainJson = $warehosue->datatoJson();
			$dataArray = array("tableName"=>"warehosueOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
			$this->dataLog($dataArray);
		}
		return true;
	}
	
	/**
	 * 提交表单
	 */
	public function submitForm()
	{
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
			if($this->mainInfo->is_return == 1){
				$result = $this->afterReturnSubmitForm();
			}else{
				if($this->mainInfo->from == "storage"){
					//$result = $this->afterZkSubmitForm();
				}else{
					$sales = $this->mainInfo->frmsales;
					if($sales->sales_type == "normal"){
						$result = $this->afterSalesSubmitForm();
					}else{
						$result = $this->afterSubmitForm();
					}
				}
			}
			if($result < 0){
				return $result;
			}
			if($result === 1){$needwc = 1;}
			//判断表单是否自动审核
			if ($this->isAutoApprove){
				$olddata=$this->commonForm;
				$oldJson=$olddata->datatoJson();
				$this->commonForm->form_status = 'approve';
				$this->commonForm->approved_at = time();
				$this->commonForm->approved_by = -1;
				$this->commonForm->update();
	
				$commonJson = $this->commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				$this->afterApproveForm();
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//发送消息
	
		//新增日志
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		//完成销售单
		if($needwc == 1){
			$form=new Sales($this->mainInfo->frmsales->baseform->id);
			$result = $form->completeSales();
		}
		return true;
	}
	
	/**
	 * 表单提交后动作
	 */
	protected function afterSubmitForm()
	{
		//修改销售单已出库件数
		$output = $this->mainInfo;
		$output = FrmOutput::model()->with('outputDetails')->findByPk($output->id);
		$details = $output->outputDetails;
		//var_dump($details);die;
		$oldOutput = $output->datatoJson();
		$sum_amount = 0;
		$sum_weight = 0;
		$sales = $output->frmsales;
		$oldSales=$sales->datatoJson();
				
		if($details){
			foreach ($details as $each){
				$sum_amount += $each->amount;
				$sum_weight += $each->weight;
				//修改销售明细件数
				$salesDetails=$each->salesDetails;
				if($salesDetails){
					$oldJson=$salesDetails->datatoJson();
					$salesDetails->warehouse_output_amount +=$each->amount;
					$salesDetails->warehouse_output_weight +=$each->weight;
					if(!$output->push_id){
						$salesDetails->warehouse_amount +=$each->amount;
						$salesDetails->warehouse_weight +=$each->weight;
					}
					$salesDetails->output_amount +=$each->amount;
					$salesDetails->output_weight +=$each->weight;
					if($sales->sales_type == "dxxs"){
						$salesDetails->need_purchase_amount += $each->amount;
					}
					if($salesDetails->output_amount > $salesDetails->amount){return false;}
					$salesDetails->update();
					$mainJson = $salesDetails->datatoJson();
					$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					$oldJson=$salesDetails->datatoJson();
					//设置可开票明细
					// $std = DictGoodsProperty::getFullProName($each->product_id);
					// if($std != "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
					// 	$t_weight = $each->weight;
					// 	$price = $salesDetails->price * $t_weight;
					// 	$invoice = DetailForInvoice::setSalesInvoice($salesDetails->FrmSales->baseform->id,$salesDetails->id,$t_weight,$price,$sales->title_id,$sales->customer_id,$sales->client_id);
					// 	if(!$invoice){
					// 		return -5;
					// 	}
					// }
					if($each->storage > 0){
						$storage = $each->storage;
						$oldSt = $storage->datatoJson();
						$storage->left_amount -= $each->amount;
						$storage->left_weight -= $each->weight;
						$storage->lock_amount -= $each->amount;
						if($storage->left_amount < 0){return -1;}
						$storage->lock_weight -= $each->weight;
						$storage->update();
						$mainJson = $storage->datatoJson();
						$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldSt);
						$this->dataLog($dataArray);
					}
				}
			}
		}
		
		$output->input_status = 1;
		$output->output_at = time();
		$output->output_by = currentUserId();
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		//销售单主表已出库件数
		
		$sales->output_amount += $sum_amount;
		$sales->output_weight += $sum_weight;
		if(!$output->push_id){
			$sales->warehouse_amount += $sum_amount;
			$sales->warehouse_weight += $sum_weight;
		}
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
	 * 表单单提交后动作
	 */
	protected function afterReturnSubmitForm()
	{
		//修改销售单已出库件数
		$output = $this->mainInfo;
		$output = FrmOutput::model()->with('outputDetails')->findByPk($output->id);
		$details = $output->outputDetails;
		//var_dump($details);die;
		$oldOutput = $output->datatoJson();
		$sum_amount = 0;
		$sum_weight = 0;
		$return = $output->frmreturn;
		$oldSales=$return->datatoJson();
	
		if($details){
			foreach ($details as $each){
				$sum_amount += $each->amount;
				$sum_weight += $each->weight;
				//修改销售明细件数
				$returnDetails=$each->returnDetails;
				if($returnDetails){
					$oldJson=$returnDetails->datatoJson();
					$returnDetails->output_amount +=$each->amount;
					$returnDetails->output_weight +=$each->weight;
					if($returnDetails->output_amount > $returnDetails->return_amount){return -2;}
					$returnDetails->update();
					$mainJson = $returnDetails->datatoJson();
					$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}
				//设置可开票明细
				// $std = DictGoodsProperty::getFullProName($each->product_id);
				// if($std != "螺纹钢"){
				// 	$t_weight = $each->weight;
				// 	$price = 0 - $t_weight*($returnDetails->return_price);
				// 	$invoice = DetailForInvoice::setPurReInvoice($return->baseform->id,$returnDetails->id,$t_weight,$price,$return->title_id,$return->supply_id);
				// 	if(!$invoice){
				// 		return false;
				// 	}
				// }
				if($each->storage_id > 0){
					$storage = $each->storage;
					$oldSt = $storage->datatoJson();
					$storage->left_amount -= $each->amount;
					$storage->left_weight -= $each->weight;
					$storage->lock_amount -= $each->amount;
					if($storage->left_amount < 0){return -1;}
					$storage->lock_weight -= $each->weight;
					$storage->update();
					$mainJson = $storage->datatoJson();
					$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldSt);
					$this->dataLog($dataArray);
					$model = new MergeStorage();
					$criteria=New CDbCriteria();
					$criteria->addCondition('warehouse_id ='.$output->frmreturn->warehouse_id);
					$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
					$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
					$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
					$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
					$criteria->addCondition('length ='.$storage->inputDetail->length);
					$criteria->addCondition('title_id ='.$storage->title_id);
					$criteria->addCondition('is_transit = 0');
					$criteria->addCondition('is_deleted = 0');
					$merge = $model->find($criteria);
					if($merge){
						$oldJson=$merge->datatoJson();
						$merge->left_amount -= $each->amount;
						$merge->left_weight -= $each->weight;
						$merge->lock_amount -= $each->amount;
						$merge->lock_weight -= $each->weight;
						if($merge->update()){
							$mainJson = $merge->datatoJson();
							$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
						}
					}
				}
			}
		}
		$output->input_status = 1;
		$output->output_at = time();
		$output->output_by = currentUserId();
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		return true;
	}
		
	/**
	 * 取消提交表单
	 */
	public function cancelSubmitForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'submited') return;//只有提交未审核才可以取消提交
	
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//判断表单是否是自动审核
			if ($this->isAutoApprove){
				$olddata=$this->commonForm;
				$oldJson=$olddata->datatoJson();
				$this->commonForm->form_status = 'submited';
				$this->commonForm->update();
				$commonJson = $this->commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				$this->afterCancelApproveForm();
			}
			$oldJson=$this->commonForm->datatoJson();
			$this->commonForm->form_status = 'unsubmit';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
				
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			if($this->mainInfo->is_return == 1){
				$result = $this->afterReturnCancelSubmitForm();
			}else{
				$result = $this->afterCancelSubmitForm();
			}
			
			//如果afterSubmitForm保存失败，返回错误码
			if($result < 0){
				return $result;
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		//发送消息
	
		//新增日志
		$operation = "取消提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 取消提交表单后的动作
	 */
	protected function afterCancelSubmitForm()
	{
		//修改销售单已出库件数
		$details = $this->details;
		$output = $this->mainInfo;
		$oldOutput = $output->datatoJson();
		$sum_amount = 0;
		$sum_weight = 0;
		$sales = $output->frmsales;
		$oldSales=$sales->datatoJson();
		
		if($details){
			foreach ($details as $each){
				$sum_amount += $each->amount;
				$sum_weight += $each->weight;
				$total_amount = $each->amount;
				$total_weight = $each->weight;
				$send_amount = $each->amount;
				$send_weight = $each->weight;
// 				$type['product'] = $each->product_id;
// 				$type['rank'] = $each->rank_id;
// 				$type['brand'] = $each->brand_id;
// 				$type['texture'] = $each->texture_id;
// 				$type['length'] = $each->length;
// 				$weight = DictGoods::getUnitWeight($type);
				//修改库存信息
				if($each->storage > 0){
					$storage = $each->storage;
					if($storage->card_status == 'clear'){
						return -3;
					}
					$oldSt = $storage->datatoJson();
					$storage->left_amount += $each->amount;
					$storage->left_weight += $each->weight;
					$storage->lock_amount += $each->amount;
					$storage->lock_weight += $each->weight;
					$storage->update();
					$mainJson = $storage->datatoJson();
					$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldSt);
					$this->dataLog($dataArray);
				}
				
				//修改销售明细件数
				$salesDetails=$each->salesDetails;
				if($salesDetails){
					$oldJson=$salesDetails->datatoJson();
					$salesDetails->warehouse_output_amount -=$each->amount;
					$salesDetails->warehouse_output_weight -=$each->weight;
					if(!$output->push_id){
						$salesDetails->warehouse_amount -=$each->amount;
						$salesDetails->warehouse_weight -=$each->weight;
					}
					$salesDetails->output_amount -=$each->amount;
					$salesDetails->output_weight -=$each->weight;
					if($sales->sales_type == "dxxs"){
						$salesDetails->need_purchase_amount -= $each->amount;
						if($salesDetails->need_purchase_amount < $salesDetails->purchased_amount){return -2;}
					}
					$salesDetails->update();
					$mainJson = $salesDetails->datatoJson();
					$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					$oldJson=$salesDetails->datatoJson();
					//设置可开票明细
					// $std = DictGoodsProperty::getFullProName($each->product_id);
					// if($std != "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
					// 	$price = 0 - $salesDetails->price * $each->weight;
					// 	$invoice = DetailForInvoice::setSalesInvoice($salesDetails->FrmSales->baseform->id,$salesDetails->id,0-$each->weight,$price,$sales->title_id,$sales->customer_id,$sales->client_id);
					// 	if(!$invoice){
					// 		return false;
					// 	}
					// }
				}
			}
		}
		
// 		if($output->push_id){
// 			$send->output_amount -= $sum_amount;
// 			$send->output_weight -= $sum_weight;
				
// 			$send->update();
// 			$newsend = $send->datatoJson();
// 			$dataArray = array("tableName"=>"FrmSend","newValue"=>$newsend,"oldValue"=>$oldSend);
// 			$this->dataLog($dataArray);
// 		}
		$output->input_status = 0;
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		//销售单主表已出库件数
		$sales->output_amount -= $sum_amount;
		$sales->output_weight -= $sum_weight;
		if(!$output->push_id){
			$sales->warehouse_amount -= $sum_amount;
			$sales->warehouse_weight -= $sum_weight;
		}
		$sales->update();
		$mainJson = $sales->datatoJson();
		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldSales);
		$this->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 退货单取消提交表单后的动作
	 */
	protected function afterReturnCancelSubmitForm()
	{
		//修改销售单已出库件数
		$details = $this->details;
		$output = $this->mainInfo;
		$oldOutput = $output->datatoJson();
		$return = $output->frmreturn;
		$oldSales=$return->datatoJson();
	
		if($details){
			foreach ($details as $each){
				//修改库存信息
				if($each->storage_id > 0){
					$storage = $each->storage;
					if($storage->card_status == 'clear'){
						return -3;
					}
					$oldSt = $storage->datatoJson();
					$storage->left_amount += $each->amount;
					$storage->left_weight += $each->weight;
					$storage->lock_amount += $each->amount;
					$storage->lock_weight += $each->weight;
					$storage->update();
					$mainJson = $storage->datatoJson();
					$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldSt);
					$this->dataLog($dataArray);
					$model = new MergeStorage();
					$criteria=New CDbCriteria();
					$criteria->addCondition('warehouse_id ='.$output->frmreturn->warehouse_id);
					$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
					$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
					$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
					$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
					$criteria->addCondition('length ='.$storage->inputDetail->length);
					$criteria->addCondition('title_id ='.$storage->title_id);
					$criteria->addCondition('is_transit = 0');
					$criteria->addCondition('is_deleted = 0');
					$merge = $model->find($criteria);
					if($merge){
						$oldJson=$merge->datatoJson();
						$merge->left_amount += $each->amount;
						$merge->left_weight += $each->weight;
						$merge->lock_amount += $each->amount;
						$merge->lock_weight += $each->weight;
						if($merge->update()){
							$mainJson = $merge->datatoJson();
							$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$baseform = new BaseForm();
							$baseform->dataLog($dataArray);
						}
					}
				}
				//修改销售明细件数
				$returnDetails=$each->returnDetails;
				//设置可开票明细
				// $std = DictGoodsProperty::getFullProName($each->product_id);
				// if($std != "螺纹钢"){
				// 	$t_weight = 0 - $each->weight;
				// 	$price = 0-$t_weight*($returnDetails->return_price);
				// 	$invoice = DetailForInvoice::setPurReInvoice($return->baseform->id,$returnDetails->id,$t_weight,$price);
				// 	if(!$invoice){
				// 		return -1;
				// 	}
				// }
				if($returnDetails){
					$oldJson=$returnDetails->datatoJson();
					$returnDetails->output_amount -=$each->amount;
					$returnDetails->output_weight -=$each->weight;
					$returnDetails->update();
					$mainJson = $returnDetails->datatoJson();
					$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					//设置可开票明细
				}
			}
		}
		$output->input_status = 0;
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 库存销售单取消提交表单
	 */
	public function salesCancelSubmitForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'submited') return false;//只有提交未审核才可以取消提交
	
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//判断表单是否是自动审核
			if ($this->isAutoApprove){
				$olddata=$this->commonForm;
				$oldJson=$olddata->datatoJson();
				$this->commonForm->form_status = 'submited';
				$this->commonForm->update();
				$commonJson = $this->commonForm->datatoJson();
				$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				$this->afterCancelApproveForm();
			}
			$oldJson=$this->commonForm->datatoJson();
			$this->commonForm->form_status = 'unsubmit';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
	
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$result = $this->afterSalesCancelSubmitForm();
			//如果afterSubmitForm保存失败，返回错误码
			if($result < 0){
				return $result;
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		//发送消息
	
		//新增日志
		$operation = "取消提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 库存销售单取消提交表单后的动作
	 */
	protected function afterSalesCancelSubmitForm()
	{
		//修改销售单已出库件数
		$details = $this->details;
		$output = $this->mainInfo;
		$oldOutput = $output->datatoJson();
		$sum_amount = 0;
		$sum_weight = 0;
		$sales = $output->frmsales;
		$oldSales=$sales->datatoJson();
	
		if($details){
			foreach ($details as $each){
				$sum_amount += $each->amount;
				$sum_weight += $each->weight;
				$total_amount = $each->amount;
				$total_weight = $each->weight;
				$send_amount = $each->amount;
				$send_weight = $each->weight;
				$type['product'] = $each->product_id;
				$type['rank'] = $each->rank_id;
				$type['brand'] = $each->brand_id;
				$type['texture'] = $each->texture_id;
				$type['length'] = $each->length;
				$weight = DictGoods::getUnitWeight($type);
	
				//如果是仓库推送的数组 修改配送单明细
				if($output->push_id){
					$frm_send_id = $output->warehouseOutputs->frm_send_id;
					$send = FrmSend::model()->findByPk($frm_send_id);
					$oldSend = $send-> datatoJson();
					$sendDetail = new FrmSendDetail();
					$c = New CDbCriteria();
					$c->compare('frm_send_id',$frm_send_id,false);
					$c->compare('product_id',$each->product_id,false);
					$c->compare('brand_id',$each->brand_id,false);
					$c->compare('texture_id',$each->texture_id,false);
					$c->compare('rank_id',$each->rank_id,false);
					$c->compare('length',intval($each->length),false);
					$sendDetail=$sendDetail->findAll($c);
					if($sendDetail){
						$cc_amount = $each->amount;
						$cc_weight = $each->weight;
						foreach ($sendDetail as $sd){
							$oldJson=$sd->datatoJson();
							//获取此条明细可以减少的最大数量
							$can_amount =  $sd->output_amount;
							$can_weight =  $sd->output_weight;
							//销售明细可以完全减少出库明细数量
							if($can_amount >=$cc_amount){
								$sd->output_amount -=$cc_amount;
								$sd->output_weight -=$cc_weight;
								//销售明细不足以吃掉出库出库件数
							}else{
								$sd->output_amount -=$can_amount;
								$sd->output_weight -=$can_weight;
								$cc_weight -= $can_amount;
								$cc_weight -= $can_weight;
							}
							$sd->update();
							$mainJson = $sd->datatoJson();
							$dataArray = array("tableName"=>"FrmSendDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$this->dataLog($dataArray);
						}
// 						//取消出库件数大于剩余出库件数，数据有误，抛出异常
// 						if($sendDetail->output_amount - $each->amount < 0){
// 							return -1;
// 						}
// 						$oldsendD = $sendDetail->datatoJson();
// 						$sendDetail->output_amount -= $each->amount;
// 						$sendDetail->output_weight -= $each->weight;
// 						$sendDetail->update();
// 						$mainJson = $sendDetail->datatoJson();
// 						$dataArray = array("tableName"=>"FrmSendDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 						$this->dataLog($dataArray);
					}
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
				$salesDetails=$model->findAll($criteria);
				if($salesDetails){
					$cc_amount = $each->amount;
					$cc_weight = $each->weight;
					foreach ($salesDetails as $sd){
						$oldJson=$sd->datatoJson();
						//获取此条明细可以减少的最大数量
						$can_amount =  $sd->warehouse_output_amount;
						$can_weight =  $sd->warehouse_output_weight;
						$m_amount = $m_weight = 0;
						//销售明细可以完全减少出库明细数量
						if($can_amount >=$cc_amount){
							$sd->warehouse_output_amount -=$cc_amount;
							$sd->warehouse_output_weight -=$cc_weight;
							if(!$output->push_id){
								$sd->warehouse_amount -=$cc_amount;
								$sd->warehouse_weight -=$cc_weight;
							}
							$sd->output_amount -=$cc_amount;
							$sd->output_weight -=$cc_weight;
							$m_amount = $cc_amount;
							$m_weight = $cc_weight;
							//销售明细不足以吃掉出库出库件数
						}else{
							$sd->warehouse_output_amount -=$can_amount;
							$sd->warehouse_output_weight -=$can_weight;
							if(!$output->push_id){
								$sd->warehouse_amount -=$can_amount;
								$sd->warehouse_weight -=$can_weight;
							}
							$sd->output_amount -=$can_amount;
							$sd->output_weight -=$can_weight;
							$cc_weight -= $can_amount;
							$cc_weight -= $can_weight;
							$m_amount = $can_amount;
							$m_weight = $can_weight;
						}
						$sd->update();
						$mainJson = $sd->datatoJson();
						$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
						//更新聚合库存信息
						$mestaroge = $sd->mergestorage;
						if($mestaroge){
							$oldSt = $mestaroge->datatoJson();
							$mestaroge->left_amount += $m_amount;
							$mestaroge->left_weight += $m_weight;
							$mestaroge->lock_amount += $m_amount;
							$mestaroge->lock_weight += $m_weight;
							
							$connection=Yii::app()->db;
							$sql="update merge_storage set lock_amount=lock_amount+".$m_amount.",lock_weight=lock_weight+".$m_weight.",left_amount=left_amount+".$m_amount.",left_weight=left_weight+".$m_weight." where id=".$sd->card_id;
							$connection->createCommand($sql)->execute();
							$mestaroge = MergeStorage::model()->findByPk($sd->card_id);							
// 							$mestaroge->update();
							$mainJson = $mestaroge->datatoJson();
							$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldSt);
							$this->dataLog($dataArray);
						}

						//设置可开票明细
						// $std = DictGoodsProperty::getFullProName($each->product_id);
						// if($std != "螺纹钢" && $sales->is_yidan == 0 && $sales->is_import == 0){
						// 	$price = 0 - $m_weight*$sd->price;
						// 	$invoice = DetailForInvoice::setSalesInvoice($sales->baseform->id,$sd->id,0-$m_weight,$price,$sales->title_id,$sales->customer_id,$sales->client_id);
						// 	if(!$invoice){
						// 		return -1;
						// 	}
						// }
					}
				}else{
					return -2;
				}
				//更新库存信息
				$storage = $each->storage;
				if($storage->card_status == 'clear'){
					return -3;
				}
				$oldSt = $storage->datatoJson();
				$storage->left_amount += $each->amount;
				$storage->left_weight += $each->weight;
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
					$oldpl = $pledge->datatoJson();
					if($pledge){
						$pledge->left_weight += $each->weight;
						$pledge->update();
						$mainJson = $pledge->datatoJson();
						$dataArray = array("tableName"=>"PledgeRedeemed","newValue"=>$mainJson,"oldValue"=>$oldpl);
						$this->dataLog($dataArray);
					}
				}
			}
		}
	
		if($output->push_id){
			$send->output_amount -= $sum_amount;
			$send->output_weight -= $sum_weight;
	
			$send->update();
			$newsend = $send->datatoJson();
			$dataArray = array("tableName"=>"FrmSend","newValue"=>$newsend,"oldValue"=>$oldSend);
			$this->dataLog($dataArray);
		}
		$output->input_status = 0;
		$output->update();
		$mainJson = $output->datatoJson();
		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
		$this->dataLog($dataArray);
		//销售单主表已出库件数
		$sales->output_amount -= $sum_amount;
		$sales->output_weight -= $sum_weight;
		if(!$output->push_id){
			$sales->warehouse_amount -= $sum_amount;
			$sales->warehouse_weight -= $sum_weight;
		}
		$sales->update();
		$mainJson = $sales->datatoJson();
		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldSales);
		$this->dataLog($dataArray);
		return true;
	}
	
	/**
	 * 审核通过后续操作
	 */
	protected function afterApproveForm()
	{
		
	}
	
	/**
	 * 表单拒绝后续操作
	 */
	protected function afterRefuseForm()
	{
		
	}
	
	/**
	 * 取消审核通过后续操作
	 */
	protected function afterCancelApproveForm()
	{
// 		//修改销售单已出库件数
// 		$output = $this->mainInfo;
// 		$oldOutput = $output->datatoJson();
// 		$details = $this->details;
// 		$total_amount = 0;
// 		$total_weight = 0;
// 		if($details){
// 			foreach ($details as $each){
// 				$total_amount += $each->amount;
// 				$total_weight += $each->weight;
// 				$salesDetails = SalesDetail::model()->findByPk($each->sales_detail_id);
// 				$oldJson=$salesDetails->datatoJson();
// 				$salesDetails->output_amount -=$each->amount;
// 				$salesDetails->output_weight -=$each->weight;
// 				$salesDetails->update();
// 				$mainJson = $salesDetails->datatoJson();
// 				$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 				$this->dataLog($dataArray);
// 			}
// 		}
// 		//出库单主体已出库数量
// 		$output->output_amount -= $total_amount;
// 		$output->output_weight -= $total_weight;
// 		$output->update();
// 		$mainJson = $output->datatoJson();
// 		$dataArray = array("tableName"=>"FrmOutput","newValue"=>$mainJson,"oldValue"=>$oldOutput);
// 		$this->dataLog($dataArray);
// 		//销售单主表已出库件数
// 		$sales = $output->frmsales;
// 		$oldJson=$sales->datatoJson();
// 		$sales->output_amount -= $total_amount;
// 		$sales->output_weight -= $total_weight;
// 		$sales->update();
// 		$mainJson = $sales->datatoJson();
// 		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 		$this->dataLog($dataArray);
 	}
}