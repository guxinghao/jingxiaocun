<?php
class PurchaseReturn extends BaseForm
{
	public $mainModel = "FrmPurchaseReturn";
	public $detailModel = "PurchaseReturnDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="采购退货单";

	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('purchaseReturn')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->purchaseReturn;
				$this->details=$model->purchaseReturn->purchaseReturnDetails;
			}
		}
	}
	
	/****------------------------基类方法重构之创建表单----------------****/
	/**
	 * 基类方法重构
	 *
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo=new FrmPurchaseReturn();
	
		$mainInfo->supply_id=$data->supply_id;
		$mainInfo->title_id=$data->title_id;
		$mainInfo->warehouse_id=$data->warehouse_id;
		$mainInfo->team_id=$data->team_id;
		$mainInfo->comment=$data->comment;
		$mainInfo->travel=$data->travel;
		$mainInfo->is_yidan=intval($data->is_yidan);
		$mainInfo->return_data=strtotime($data->return_data);
		$mainInfo->company_contact_id=$data->company_contact_id;
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchaseReturn","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
		return null;
	}
	
	/**
	 * 基类方法重构
	 *
	 * 保存明细
	 *
	 */
	protected function saveDetails($data)
	{
		$detail=array();
		if (!is_array($data)||count($data)<=0)
			return;
		foreach ($data as $each)
		{
			$returnDetail=new PurchaseReturnDetail();
				
			$returnDetail->card_no = $each->card_no;
			$returnDetail->return_amount = $each->return_amount;
			$returnDetail->return_weight = $each->return_weight;
			$returnDetail->return_price = $each->return_price;
			$returnDetail->product_id = $each->product_id;
			$returnDetail->brand_id = $each->brand_id;
			$returnDetail->texture_id = $each->texture_id;
			$returnDetail->rank_id = $each->rank_id;
			$returnDetail->length = $each->length;
			$returnDetail->purchase_return_id = $this->mainInfo->id;
			$returnDetail->pre_amount = $each->return_amount;
	
			if($returnDetail->insert())
			{
				//明细日志
				$detailJson = $returnDetail->datatoJson();
				$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($detail,$returnDetail);
			}
		}
		return $detail;
	}
	
	/**
	 * 创建提交表单
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
			$result = $this->afterSubmitForm();
			if($result < 0){
				return $result;
			}
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
			echo "操作失败";
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//操作日志
		$operation = "新增";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		//发送消息
		return true;
	}
	/**
	 * 基类方法重构
	 *
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
		//创建往来
		foreach($this->details as $each){
			$price = $each->return_price;
			$amount=$each->return_weight;
			$fee = $price*$amount;
			$da=array();
			$da['type']="CGTH";
			$da['turnover_direction']="need_charge";
			$da['title_id']= $this->mainInfo->title_id;
			$da['target_id']=$this->mainInfo->supply_id;
			$da['is_yidan']=$this->mainInfo->is_yidan;
			$da['proxy_company_id']='';
			$da['amount']=$amount;
			$da['price']=$price;
			$da['fee']=$fee;
			$da['common_forms_id']=$this->commonForm->id;
			$da['form_detail_id']=$each->id;
			$da['ownered_by']=$this->commonForm->owned_by;
			$da['created_by'] = currentUserId();
			$da['created_at'] = strtotime($this->commonForm->form_time);
			$da['description'] = "采购退货:".$this->commonForm->form_sn;
			$da['big_type'] = "purchase";
			$result = Turnover::createBill($da);
			//日志
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
		}
	}

	/**
	 * 保存提交表单
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
			$result = $this->afterSubmitForm();
			if($result < 0){
				return $result;
			}
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
			$transaction->rollBack();
			return "操作失败";
		}
		//发送消息
		
		//新增日志
		$operation = "修改";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
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
			$result = $this->afterSubmitForm();
			if($result < 0){
				return $result;
			}
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
				$this->afterSubmitForm();
			}
	
	
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
		return true;
	}
	
	/**
	 * 退货单提交后动作
	 */
	protected function afterSubmitForm()
	{
		$main = $this->commonForm;
		$sales = $this->mainInfo;
		//修改库存,增加锁定库存
		$details=$this->details;
		if($details){
			foreach ($details as $each){
				if($each->card_no){
					$storage = Storage::model()->findByPk($each->card_no);
					$oldJson = $storage->datatoJson();
					$surplus = $storage->left_amount-$storage->lock_amount-$storage->retain_amount-$each->return_amount;
					if($surplus < 0){
						return -1;
					}
					$storage -> lock_amount += $each->return_amount;
					$storage -> lock_weight += $each->return_weight;
					$storage-> update();
					//日志
					$mainJson = $storage->datatoJson();
					$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					$model = new MergeStorage();
					$criteria=New CDbCriteria();
					$criteria->addCondition('warehouse_id ='.$sales->warehouse_id);
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
						$merge->lock_amount += $each->return_amount;
						$merge -> lock_weight += $each->return_weight;
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
		//修改往来
		$id=$this->commonForm->id;
		$thrnover = Turnover::findBill($id);
		$update=array('status'=>'submited');
		if(is_array($thrnover) && count($thrnover)>0){
			foreach($thrnover as $th){
				$thrnoverId = $th->id;
				$oldJson = $th->datatoJson();
				$result = Turnover::updateBill($thrnoverId, $update);
				//日志
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
		//发送消息
		$message = array();
		$message['receivers'] = User::model()->getOperationList("采购退货单:审核");
		$message['content'] = "业务员：".$main->belong->nickname."提交了采购退货单：".$main->form_sn.",请尽快审核。";
		$message['title'] = "退货通知";
		$message['url'] = Yii::app()->createUrl('frmPurchaseReturn/index',array('card_no'=>$main->form_sn));
		$message['type'] = "采购退货";
		$message['big_type']='purchase';
		$res = MessageContent::model()->addMessage($message);
		return true;
	}
	
	/**
	 * 取消提交表单后的动作
	 */
	protected function afterCancelSubmitForm()
	{
		$sales = $this->mainInfo;
		//修改库存,减少锁定库存
		$details=$this->details;
		if($details){
			foreach ($details as $each){
				if($each->card_no){		
					$storage = Storage::model()->findByPk($each->card_no);
					$oldJson = $storage->datatoJson();
					$storage -> lock_amount -= $each->return_amount;
					$storage -> lock_weight -= $each->return_weight;
					$storage-> update();
					$mainJson = $storage->datatoJson();
					$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					$model = new MergeStorage();
					$criteria=New CDbCriteria();
					$criteria->addCondition('warehouse_id ='.$sales->warehouse_id);
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
						$merge->lock_amount -= $each->return_amount;
						$merge->lock_weight -= $each->return_weight;
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
		//修改往来
		$id=$this->commonForm->id;
		$thrnover = Turnover::findBill($id);
		$update=array('status'=>'unsubmit');
		if($thrnover){
			foreach($thrnover as $th){
				$thrnoverId = $th->id;
				$oldJson = $th->datatoJson();
				$result = Turnover::updateBill($thrnoverId, $update);
				//日志
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
			//新增提交日志
	
		}
		return true;
	}
	
	//-----------------------------------------------------------修改表单--------------------------------------------------------
	/**
	 * 修改主体信息
	 */
	 protected function updateMainInfo($data)
	 {
	 	$return=$this->mainInfo;
	 	$oldJson=$return->datatoJson();
	 	if($this->commonForm->form_status!='unsubmit')
	 	{
// 	 		if($data->company_contact_id)
// 	 		{
	 			$return->company_contact_id=$data->company_contact_id;
	 			$return->return_data=strtotime($data->return_data);
//	 			$return->comment=$data->comment;
//	 		}
	 	}else{
	 		foreach ($data as $k=>$v)
	 		{
	 			if($k == "return_data"){
	 				$return->return_data=strtotime($v);
	 			}else{
	 				if($k == "warehouse_name"){continue;}
	 				$return->$k=$v;
	 			}
	 		}
	 	}
	 	if($return->update())
	 	{
	 		$mainJson = $return->datatoJson();
	 		$dataArray = array("tableName"=>"FrmPurchaseReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 		$this->dataLog($dataArray);
	 		return true;
	 	}
	 	return false;
	 }
	 /**
	  * 修改明细
	  */
	 protected function updateDetails($data)
	 {
	 	$amount = 0;
	 	$weight = 0;
	 	$total_amount = 0;
	 	if($this->commonForm->form_status=='unsubmit')
	 	{
	 		if($data && count($data)>0)
	 		{
	 			$id_array=array();
	 			foreach ($data as $e)
	 			{
	 				if($e->id)
	 				{
	 					array_push($id_array,$e->id);
	 				}
	 			}
	 			$details=$this->details;
	 			if(empty($id_array))
	 			{
	 				foreach ($details as $each)
	 				{
	 					$oldJson=$each->datatoJson();
	 					$each->delete();
	 					$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>"","oldValue"=>$oldJson);
	 					$this->dataLog($dataArray);
	 				}
	 			}else{
	 				foreach ($details as $each)
	 				{
	 					if(!in_array($each->id,$id_array))
	 					{
	 						$oldJson=$each->datatoJson();
	 						$each->delete();
	 						$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>"","oldValue"=>$oldJson);
	 						$this->dataLog($dataArray);
	 					}
	 				}
	 			}
	 			 
	 			foreach ($data as $data_each)
	 			{
	 				if($data_each->id)
	 				{
	 					//修改此条数据
	 					$detail_data=PurchaseReturnDetail::model()->findByPk($data_each->id);
	 					$oldJson=$detail_data->datatoJson();
	 					if($detail_data)
	 					{
	 						foreach ($data_each as $key =>$value)
	 						{
	 							if($key=='id')continue;
	 							if($key=='total_amount')continue;
	 							$detail_data->$key=$value;
	 						}
	 						$detail_data->update();
	 						$mainJson = $detail_data->datatoJson();
	 						$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 						$this->dataLog($dataArray);	
	 					}
	 				}else{
	 					//新建
		 				$returnDetail=new PurchaseReturnDetail();
						$returnDetail->card_no = $data_each->card_no;
						$returnDetail->return_amount = $data_each->return_amount;
						$returnDetail->return_weight = $data_each->return_weight;
						$returnDetail->return_price = $data_each->return_price;
						$returnDetail->product_id = $data_each->product_id;
						$returnDetail->brand_id = $data_each->brand_id;
						$returnDetail->texture_id = $data_each->texture_id;
						$returnDetail->rank_id = $data_each->rank_id;
						$returnDetail->length = $data_each->length;
						$returnDetail->purchase_return_id = $this->mainInfo->id;
						$returnDetail->pre_amount = $each->return_amount;
				
						if($returnDetail->insert())
						{
							//明细日志
							$detailJson = $returnDetail->datatoJson();
							$dataArray = array("tableName"=>"PurchaseReturnDetail","newValue"=>$detailJson,"oldValue"=>"");
							$this->dataLog($dataArray);
							array_push($this->details,$returnDetail);
						}
	 				}
	 			}
	 			return true;
	 		}
	 	}
	 	return true;
	 }
	
	 /**
	  * 修改以后操作
	  */
	 protected function afterupdateForm()
	 {
	 	//修改往来
	 	$id=$this->commonForm->id;
	 	$return = $this->mainInfo;
	 	if (!is_array($this->details) || count($this->details)<=0) return;
	 	$detail = PurchaseReturnDetail::model()->findAll("purchase_return_id=".$return->id);
	 	$details1=$this->details;
	 	$details_id = array();
	 	if($detail){
	 		foreach ($detail as $each){
	 			$detailId = $each->id;
	 			$thrnover = Turnover::findDetailBill($id,$detailId);
	 			if($thrnover){
	 				array_push($details_id,$thrnover->id);
	 			}
	 		}
	 	}
	 	//删除 已经删除的详情对应的往来信息
	 	$all = Turnover::findBill($id);
	 	if($all){
	 		foreach($all as $a){
	 			if(!in_array($a->id,$details_id))
	 			{
	 				$oldJson = $a->datatoJson();
	 				$a->delete();
	 				$dataArray = array("tableName"=>"Turnover","newValue"=>"","oldValue"=>$oldJson);
	 				$this->dataLog($dataArray);
	 			}
	 		}
	 	}
	 	if($detail)
	 	{
	 		foreach ($detail as $each)
	 		{
	 			$amount=0;
	 			$fee=0;
	 			$price = 0;
	 			$detailId = $each->id;
	 			$thrnover = Turnover::findDetailBill($id,$detailId);
	 			$owned_by=$this->commonForm->owned_by;
	 				
	 			if($thrnover){
	 				//修改往来信息
	 				$title_id=$this->mainInfo->title_id;
	 				$supply_id=$this->mainInfo->supply_id;
	 				$is_yidan = $this->mainInfo->is_yidan;
	 				$price = $each->return_price;
	 				$fee = $price*$each->return_weight;
	 				$amount = $each->return_weight;
	 				$created_at = strtotime($this->commonForm->form_time);
	 				//-----begin-----调整amount是数量，fee是金额，再看下是否需要修改往来的其他字段
	 				$update=array('fee'=>$fee,'amount'=>$amount,"price"=>$price,'created_at'=>$created_at,'title_id'=>$title_id,'target_id'=>$supply_id,"is_yidan"=>$is_yidan);
	 				//-----end----
	 				$oldJson=$thrnover->datatoJson();
	 				$result = Turnover::updateBill($thrnover->id, $update);
	
	 				$mainJson = $result->datatoJson();
	 				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 				$this->dataLog($dataArray);
	 				//新增修改日志
	 			}else{
	 				//新增往来信息
	 				$price = $each->return_price;
	 				$fee = $price*$each->return_weight;
	 				$amount = $each->return_weight;
	 				$da=array();
	 				$da['type']="CGTH";
	 				$da['turnover_direction']="need_charge";
	 				$da['title_id']= $this->mainInfo->title_id;
	 				$da['target_id']=$this->mainInfo->supply_id;
	 				$da['amount']=$amount;
	 				$da['price']=$price;
	 				$da['fee']=$fee;
	 				$da['common_forms_id']=$this->commonForm->id;
	 				$da['form_detail_id']=$each->id;
	 				$da['ownered_by']=$this->commonForm->owned_by;
	 				$da['created_by'] = currentUserId();
	 				$da['created_at'] = strtotime($this->commonForm->form_time);
	 				$da['description'] = "采购退货";
	 				$da['big_type'] = "purchase";
	 				$result = Turnover::createBill($da);
	 				//新增修改日志
	 				$mainJson = $result->datatoJson();
	 				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
	 				$this->dataLog($dataArray);
	 			}
	 		}
	
	 	}
	 }
	 
	 /**
	  * 作废表单后的操作
	  */
	 protected function afterDeleteForm()
	 {
	 	//修改往来
	 	$id=$this->commonForm->id;
	 	$thrnover = Turnover::findBill($id);
	 	$update=array('status'=>'delete');
	 	if($thrnover){
	 		foreach($thrnover as $th){
	 			$thrnoverId = $th->id;
	 			$oldJson=$th->datatoJson();
	 			$result = Turnover::updateBill($thrnoverId, $update);
	 			$mainJson = $result->datatoJson();
	 			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 			$this->dataLog($dataArray);
	 		}
	 		//新增提交日志
	 	}
	 }
	 
	 /**
	  * 审核通过后续操作
	  */
	 protected function afterApproveForm()
	 {
	 	$sales = $this->mainInfo;
	 	//修改往来(往来不需要修改)
	 	//修改高开信息'
	 	//HighOpen::checkHigh($sales->id);
	 	//$baseform = $this->commonForm;
	 	/*
	 	$details = $sales->purchaseReturnDetails;
	 	if($details){
	 		foreach ($details as $each_d)
	 		{
	 			$std = DictGoodsProperty::getFullProName($each_d->product_id);
	 			if($std == "螺纹钢"){
	 				$invoice=new DetailForInvoice();
	 				$invoice->type='purchase';
	 				$invoice->form_id=$this->commonForm->id;//
	 				$invoice->detail_id=$each_d->id;
	 				$invoice->checked_money=0;
	 				$invoice->checked_weight=0;
	 				$invoice->weight=$each_d->return_weight;
	 				$invoice->money=0-$each_d->return_weight*$each_d->return_price;
	 				$invoice->title_id=$this->mainInfo->title_id;
	 				$invoice->company_id=$this->mainInfo->supply_id;
	 				$invoice->pledge_id=0;
	 				$invoice->insert();
	 			}
	 		}
	 	}
	 	*/
	 	//发送消息
	 	if(Yii::app()->user->userid != $baseform->owned_by){
		 	$message = array();
		 	$message['receivers'] = $baseform->owned_by;
		 	$message['content'] = "您的采购退货单：".$baseform->form_sn."已审核通过。";
		 	$message['title'] = "审核通知";
		 	//$message['url'] = "";
		 	$message['type'] = "采购退货";
		 	$message['big_type']='purchase';
		 	$res = MessageContent::model()->addMessage($message);
	 	}
	 }
	 
	 /**
	 * 表单拒绝后续操作
	  */
	  protected function afterRefuseForm()
	  {
	  	  $baseform = $this->commonForm;
		  $sales = $this->mainInfo;
		  //修改库存,减少锁定库存
		  $details=$this->details;
		  if($details){
			  foreach ($details as $each){
				  if($each->card_no){
				  //库存销售减少聚合表锁定件数
				  	$storage = Storage::model()->findByPk($each->card_no);
				  	$oldJson = $storage->datatoJson();
				  	$storage -> lock_amount -= $each->return_amount;
				  	$storage -> lock_weight -= $each->return_weight;
				  	$storage-> update();
				  	$mainJson = $storage->datatoJson();
				  	$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				  	$this->dataLog($dataArray);
				  	$model = new MergeStorage();
				  	$criteria=New CDbCriteria();
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
				  		$merge->lock_amount -= $each->return_amount;
				  		$merge->lock_weight -= $each->return_weight;
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
		  //修改往来
		  $id=$this->commonForm->id;
		  $thrnover = Turnover::findBill($id);
		  $update=array('status'=>'unsubmit');
		  if($thrnover){
				foreach($thrnover as $th){
				  	$thrnoverId = $th->id;
				  	$oldJson=$th->datatoJson();
			  		$result = Turnover::updateBill($thrnoverId, $update);
			  		$mainJson = $result->datatoJson();
			  		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}
		  }
		  //发送消息
		  if(Yii::app()->user->userid != $baseform->owned_by){
			  $message = array();
			  $message['receivers'] = $baseform->owned_by;
			  $message['content'] = "您的采购退货单：".$baseform->form_sn."审核已被拒绝。";
			  $message['title'] = "审核通知";
			  //$message['url'] = "";
			  $message['type'] = "采购退货";
			  $message['big_type']='purchase';
			  $res = MessageContent::model()->addMessage($message);
		  }
	  }
	 
	  /**
	   * 表单取消审核
	   */
	  public function cancelApproveForm()
	  {
	  	if ($this->commonForm == null) return false;//表单为空
	  	if ($this->commonForm->form_status != 'approve') return;//表单状态不是提交
	  
	  	$transaction=Yii::app()->db->beginTransaction();
	  	try {
	  		//开票数据
	  		/*
	  		$model=DetailForInvoice::model()->findAll('form_id='.$this->commonForm->id);
	  		if($model)
	  		{
	  			$flag=false;
	  			foreach ($model as $each)
	  			{
	  				if($each->checked_weight>0){
	  					$flag=true;	break;
	  				}
	  			}
	  			if($flag){return false;}else{
	  				foreach ($model as $e)
	  				{
	  					$e->delete();
	  				}
	  			}
	  		}
	  		*/
	  		$olddata=$this->commonForm;
	  		$oldJson=$olddata->datatoJson();
	  		$this->commonForm->form_status = 'submited';
	  		$this->commonForm->approved_at = 0;
	  		$this->commonForm->approved_by = 0;
	  		$this->commonForm->last_update = time();
	  		$this->commonForm->last_updated_by = currentUserId();
	  		$this->commonForm->update();
	  			
	  		$commonJson = $this->commonForm->datatoJson();
	  		$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
	  		$this->dataLog($dataArray);
	  			
	  		$this->afterCancelApproveForm();
	  		$transaction->commit();
	  	}catch (Exception $e)
	  	{
	  		$transaction->rollBack();//事务回滚
	  		return '操作失败';
	  	}
	  	//发送消息
	  
	  	//新增日志
	  	$operation = "取消审核";
	  	$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
	  	return true;
	  }
	  
		/**
	  	* 取消审核通过后续操作
	  	*/
  		protected function afterCancelApproveForm()
  		{
  			$baseform = $this->commonForm;
  			//发送消息
  			if(Yii::app()->user->userid != $baseform->owned_by){
	  			$message = array();
	  			$message['receivers'] = $baseform->owned_by;
	  			$message['content'] = "您的采购退货单：".$baseform->form_sn."审核已被取消。";
	  			$message['title'] = "审核通知";
	  			//$message['url'] = "";
	  			$message['type'] = "采购退货";
	  			$message['big_type']='purchase';
	  			$res = MessageContent::model()->addMessage($message);
  			}
  		}
  		
  		/**
  		 * 销售单完成
  		*/
  		public function completeSales()
  		{
  			$id=$this->commonForm->id;
  			$return=$this->mainInfo;

  			$transaction = Yii::app()->db->beginTransaction();
  			try{
  				//作废所有未完成的出库单
	  			$frmOutputs = $return->frmOutputs;
	  			if($frmOutputs){
	  				foreach ($frmOutputs as $li){
	  					if($li->input_status != 0){continue;}
	  					$form=new Output($li->baseform->id);
	  					$form->deleteForm("完成退货单作废未出库的出库单");
	  				}
	  			}
	  			//查询获取销售单明细
	  			$details = $return->purchaseReturnDetails;
	  			 
	  			if($details){
	  				foreach ($details as $each){
	  					$oldDetail = $each->datatoJson();
	  					$old_amount = $each->return_amount;
	  					$cha_amount = $each->return_amount - $each->output_amount;
	  					$cha_weight = $each->return_weight - $each->output_weight;
	  					$each->return_amount = $each->output_amount;
	  					$each->return_weight = $each->output_weight;
	  					$each->update();
	  					$newDetail = $each->datatoJson();
	  					$dataArray = array("tableName"=>"SalesDetails","newValue"=>$newDetail,"oldValue"=>$oldDetail);
	  					$this->dataLog($dataArray);
	  					$thrnover = Turnover::findDetailBill($id,$each->id);
	  					$oldJson=$thrnover->datatoJson();
	  					//修改往来信息
	  					$price = $each->return_price;
	  					$amount = $each->output_weight;
	  					$fee = $price*$amount;
	  					//-----begin-----调整amount是数量，fee是金额，再看下是否需要修改往来的其他字段
	  					$update=array('fee'=>$fee,'amount'=>$amount,"price"=>$price,"confirmed"=>1);
	  					//-----end----
	  					$result = Turnover::updateBill($thrnover->id,$update);
	  					//新增修改日志
	  					$mainJson = $result->datatoJson();
	  					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  					$this->dataLog($dataArray);
	  					//未出库的产品 库存回滚
	  					$not_out = $old_amount - $each->output_amount;
	  					$storage = $each->storage;
	  					if($storage){
	  						$oldJson = $storage->datatoJson();
	  						$storage->lock_amount -=$not_out;
	  						$storage->lock_weight -=$cha_weight;
	  						$storage->update();
	  						$mainJson = $storage->datatoJson();
	  						$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  						$this->dataLog($dataArray);
	  						$model = new MergeStorage();
	  						$criteria=New CDbCriteria();
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
	  							$merge->lock_amount -= $not_out;
	  							$merge->lock_weight -=$cha_weight;
	  							if($merge->update()){
	  								$mainJson = $merge->datatoJson();
	  								$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  								$baseform = new BaseForm();
	  								$baseform->dataLog($dataArray);
	  							}
	  						}
	  					}
	  					//开票数据
			 			// $std = DictGoodsProperty::getFullProName($each_d->product_id);		 		
			 			$invoice = DetailForInvoice::model()->find('form_id='.$this->commonForm->id.' and detail_id='.$each->id);
			 			if($this->mainInfo->is_yidan){
			 				if($invoice){
			 					if(floatval($invoice->checked_weight))throw new CException("已销票");
			 					$invoice->delete();		 					
			 				}
			 			}else{
			 				if(!$invoice){
				 				$invoice=new DetailForInvoice();
				 				$invoice->type='purchase';
				 				$invoice->form_id=$this->commonForm->id;//
				 				$invoice->detail_id=$each->id;
				 				$invoice->checked_money=0;
				 				$invoice->checked_weight=0;
				 				$invoice->weight=$each->output_weight;
				 				$invoice->money=0-$each->output_weight*$each->return_price;
				 				$invoice->title_id=$this->mainInfo->title_id;
				 				$invoice->company_id=$this->mainInfo->supply_id;
				 				$invoice->pledge_id=0;
				 				$invoice->insert();
				 			}else{
				 				$invoice->weight=$each->output_weight;
				 				$invoice->money=0-$each->output_weight*$each->return_price;
				 				$invoice->title_id=$this->mainInfo->title_id;
				 				$invoice->company_id=$this->mainInfo->supply_id;
				 				$invoice->update();
				 			}
			 			}	  					
	  				}
	  			}
	  			//修改主体信息
	  			$return->confirm_status = 1;
	  			$oldJson=$return->datatoJson();
	  			if($return->update())
	  			{
	  				$mainJson = $return->datatoJson();
	  				$dataArray = array("tableName"=>"FrmPurchaseReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  				$this->dataLog($dataArray);
	  				// return true;
	  			}
	  			$transaction->commit();
  			}catch(Exception $e){
  				$transaction->rollBack();
  				if($e->message==='已销票')
  					return '已销票';
  				else
  					return false;
  			}
  			return true;
  		}
  		
  		/**
  		 * 取消销售单完成
  		 */
  		public function cancelcompleteSales()
  		{
  			$id=$this->commonForm->id;
  			$return=$this->mainInfo;

  			$transaction = Yii::app()->db->beginTransaction();
  			try{
  				$details = $return->purchaseReturnDetails;
	  			if($details){
	  				foreach ($details as $each){
	  					$weight = DictGoods::getWeightByStorage($each);
	  					$cha_weight = $weight*$each->pre_amount;
	  					$oldDetail = $each->datatoJson();
	  					$cha_amount = $each->pre_amount - $each->return_amount;
	  					$each->return_amount = $each->pre_amount;
	  					$each->return_weight = $cha_weight;
	  					//$each->need_purchase_amount += $cha_amount;
	  					$each->update();
	  					$newDetail = $each->datatoJson();
	  					$dataArray = array("tableName"=>"SalesDetails","newValue"=>$newDetail,"oldValue"=>$oldDetail);
	  					$this->dataLog($dataArray);
	  					$thrnover = Turnover::findDetailBill($id,$each->id);
	  					$oldJson=$thrnover->datatoJson();
	  					//修改往来信息
	  					$update=array("confirmed"=>0);
	  					//-----end----
	  					$result = Turnover::updateBill($thrnover->id,$update);

	  					//库存增加锁定件数
	  					$not_out = $each->pre_amount - $each->output_amount;
	  				  	$storage = $each->storage;
	  					if($storage){
	  						$oldJson = $storage->datatoJson();
	  						$storage->lock_amount +=$not_out;
	  						$storage->lock_weight +=$cha_weight - $each->output_weight;
	  						$storage->update();
	  						$mainJson = $storage->datatoJson();
	  						$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  						$this->dataLog($dataArray);
	  						$model = new MergeStorage();
	  						$criteria=New CDbCriteria();
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
	  							$merge->lock_amount += $not_out;
	  							$merge->lock_weight +=$cha_weight - $each->output_weight;
	  							if($merge->update()){
	  								$mainJson = $merge->datatoJson();
	  								$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  								$baseform = new BaseForm();
	  								$baseform->dataLog($dataArray);
	  							}
	  						}
	  					}

	  					//开票
	  					if(!$this->mainInfo->is_yidan){
	  						$invoice = DetailForInvoice::model()->find('form_id='.$this->commonForm->id.' and detail_id='.$each->id);
	  						if($invoice){
	  							if(floatval($invoice->checked_weight))throw new CException("已销票");
	  							$invoice->delete();
	  						}
	  					}
	  					// $invoice = DetailForInvoice::model()->find('form_id='.$this->commonForm->id.' and detail_id='.$each->id);
	  					// if($invoice){
	  					// 	if(floatval($invoice->checked_weight))throw new CException("已开票");
	  					// 	$invoice->delete();
	  					// }
	  					
	  				}
	  			}
	  			$return->confirm_status = 0;
	  			$oldJson=$return->datatoJson();
	  			if($return->update())
	  			{
	  				$mainJson = $return->datatoJson();
	  				$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
	  				$this->dataLog($dataArray);
	  			}
	  			$transaction->commit();
  			}catch(Exception $e){
  				$transaction->rollBack();
  				if($e->message==='已销票')
  					return '已销票';
  				else
  					return false;
  			}  			
  			return true;
  		}
}