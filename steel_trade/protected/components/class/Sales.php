<?php
class Sales extends BaseForm
{
	public $mainModel = "FrmSales";
	public $detailModel = "SalesDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="销售单";
	public $yidan_change=false;
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('sales')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->sales;
				$this->details=$model->sales->salesDetails;
			}
		}
	}
	

	/****------------------------基类方法重构之创建表单----------------****/
	
	/**
	 * 创建并提交表单
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
			if(!$result){
				throw new CException("提交失败");
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
			$transaction->rollBack();//事务回滚
			return -1;
		}

		//操作日志
		$operation = "新增";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return $this->commonForm->id;
		//发送消息
	}
	
	/**
	 * 基类方法重构
	 *
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo=new FrmSales();
	
		$mainInfo->sales_type=$data->sales_type;
		$mainInfo->title_id=$data->title_id;
		$mainInfo->customer_id=$data->customer_id;
		$mainInfo->client_id=$data->client_id;
		$mainInfo->supply_id=$data->supply_id;
		$mainInfo->owner_company_id=$data->owner_company_id;
		$mainInfo->team_id=$data->team_id;
		$mainInfo->is_yidan=intval($data->is_yidan);
		$mainInfo->has_bonus_price=intval($data->has_bonus_price);
		$mainInfo->company_contact_id=$data->company_contact_id;
		$mainInfo->warehouse_id=$data->warehouse_id;
		$mainInfo->amount=$data->amount;
		$mainInfo->weight=$data->weight;
		$mainInfo->output_amount=$data->output_amount;
		$mainInfo->output_weight=$data->output_weight;
		$mainInfo->comment=$data->comment;
		$mainInfo->travel=$data->travel;
		$mainInfo->date_extract=strtotime($data->date_extract);
		$mainInfo->is_import=intval($data->is_import);
		
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>"");
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
		$gk['CommonForms']['form_type']='GK';
		$gk['CommonForms']['form_time']=$this->commonForm->form_time;
		$gk['CommonForms']['owned_by']=$this->commonForm->owned_by;
		$gkdata['common']=(Object)$gk['CommonForms'];
		$baseform = $this->commonForm;
		$sales=$this->mainInfo;
		$amount = 0;
		$weight = 0;
		$total_money = 0;
		$total_amount = 0;		
		$detail=array();
		if (!is_array($data)||count($data)<=0)
			return;
		foreach ($data as $each)
		{
			$old = $each->old;
			$salesDetail=new SalesDetail();
			
			$salesDetail->price = $each->price;
			$salesDetail->fix_price = $each->fix_price;
			$salesDetail->bonus_price = floatval($each->bonus_price);
			$salesDetail->frm_sales_id = $this->mainInfo->id;
			$salesDetail->amount = $each->amount;
			$salesDetail->pre_amount = $each->amount;
			$salesDetail->weight = $each->weight;
			$salesDetail->pre_weight = $each->weight;
			$salesDetail->product_id = $each->product_id;
			$salesDetail->brand_id = $each->brand_id;
			$salesDetail->texture_id = $each->texture_id;
			$salesDetail->rank_id = $each->rank_id;
			$salesDetail->length = intval($each->length);
			$salesDetail->card_id = $each->card_id;
			$salesDetail->fee = $each->total_amount;
			if($sales->sales_type == "xxhj"){
				$salesDetail->need_purchase_amount = $each->amount;
			}
			$amount +=$each->amount;
			$weight +=$each->weight;
			$total_amount += $each->total_amount;
			//$total_money += $each->weight*($salesDetail->bonus_price);
			$total_money += $each->total_amount;
			if($salesDetail->insert())
			{
				//明细日志
				$detailJson = $salesDetail->datatoJson();
				$dataArray = array("tableName"=>"SalesDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($detail,$salesDetail);
			}
			//如果设置了高开价格
			if($salesDetail->bonus_price > 0){
				$gk["main"]["sales_id"] = $sales->id;
				$gk["main"]["sales_detail_id"] = $salesDetail->id;
				$gk["main"]["price"] = $salesDetail->bonus_price;
				$gk["main"]["fee"] = $salesDetail->bonus_price*$salesDetail->weight;
				$gk["main"]["title_id"] = $sales->title_id;
				$gk["main"]["target_id"] = $each->gk_id;
				$gk["main"]["client_id"] = $sales->client_id;
				$gk["main"]["weight"] = $salesDetail->weight;
				$gk["main"]["is_yidan"] = $sales->is_yidan;
				$gkdata['main']=(Object)$gk['main'];
				HighOpen::createHigh($gkdata);
			}
		}
		//修改主体信息
		$sales->amount = $amount;
		$sales->weight = $weight;
		$sales->pre_amount = $amount;
		$sales->pre_weight = $weight;
		$sales->fee = $total_money;
		$sales->update();
		//主体日志
		$mainJson = $sales->datatoJson();
		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>"");
		$this->dataLog($dataArray);
		
		return $detail;
	}
	
	/**
	 * 基类方法重构
	 *
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
		//创建往来
		$dg_jm=$this->isDgJmSales();
		$dg_jm?$remark='抬头为登钢爵淼的销售单往来为0':$remark='';
		foreach($this->details as $each){			
			$price = $each->price;
			if($dg_jm)$fee=0;else $fee = $each->fee;
			$amount=$each->weight;
			$std="单号：".$this->commonForm->form_sn.",".DictGoodsProperty::getProName($each->brand_id)."|".DictGoodsProperty::getProName($each->product_id).
				"|".DictGoodsProperty::getProName($each->rank_id)."*".intval($each->length)."*".DictGoodsProperty::getProName($each->texture_id).$remark;
			$da=array();
			$da['type']="XSMX";
			$da['turnover_direction']="need_charge";
			$da['title_id']= $this->mainInfo->title_id;
			$da['target_id']=$this->mainInfo->customer_id;
			$da['client_id']=$this->mainInfo->client_id;
			$da['proxy_company_id']='';
			$da['amount']=$amount;
			$da['price']=$price;
			$da['fee']=$fee;
			$da['common_forms_id']=$this->commonForm->id;
			$da['form_detail_id']=$each->id;
			$da['ownered_by']=$this->commonForm->owned_by;
			$da['created_by'] = currentUserId();
			$da['description'] = $std;
			$da['is_yidan']=$this->mainInfo->is_yidan;
			$da['big_type'] = "sales";
			$da['created_at'] = strtotime($this->commonForm->form_time);
			$result = Turnover::createBill($da);
			//日志
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
		}
	}
	
	/**
	 * 提交表单
	 */
	public function submitForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'unsubmit') return false;//表单状态不是未提交
		$this->commonForm->form_status = 'submited';
		$this->commonForm->update();
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
// 			$this->commonForm->form_status = 'submited';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
				
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$result = $this->afterSubmitForm();
			if(!$result){
				return -1;
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
			$transaction->rollBack();//事务回滚
			$this->commonForm->form_status='unsubmit';
			$this->commonForm->update();
			return;
		}
		//发送消息
	
		//新增日志
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 销售单提交后动作
	 */
	protected function afterSubmitForm()
	{
		$baseform = $this->commonForm;
		$sales = $this->mainInfo;
		//重新查询内容，防止锁定库存时使用原来的老数据
		$sales = FrmSales::model()->findByPk($sales->id);
		//修改库存,增加锁定库存
		$details=$sales->salesDetails;
		if($details){
			foreach ($details as $each){
				if($each->card_id){
					if($sales->sales_type == "normal"){
						$storage = MergeStorage::model()->findByPk($each->card_id);
						if($storage->is_deleted == 1){
							return false;
						}
						$oldJson = $storage->datatoJson();
						$surplus = $storage->left_amount-$storage->lock_amount-$storage->retain_amount-$each->amount;
						if($surplus < 0){
							return false;
						}
						$storage -> lock_amount += $each->amount;
						$storage -> lock_weight += $each->weight;
						$connection=Yii::app()->db;
						$sql="update merge_storage set lock_amount=lock_amount+".$each->amount.",lock_weight=lock_weight+".$each->weight." where id=".$each->card_id;
						$connection->createCommand($sql)->execute();
// 						$storage-> update();
						//日志
						$storage = MergeStorage::model()->findByPk($each->card_id);
						$mainJson = $storage->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
					}else{
						$storage = Storage::model()->findByPk($each->card_id);
						if($storage->is_deleted == 1){
							return false;
						}
						$oldJson = $storage->datatoJson();
						$surplus = $storage->left_amount-$storage->lock_amount-$storage->retain_amount-$each->amount;
						if($surplus < 0){
							return false;
						}
						$storage -> lock_amount += $each->amount;
						$storage -> lock_weight += $each->weight;
						$storage-> update();
						//日志
						$mainJson = $storage->datatoJson();
						$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
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
		//修改高开信息'
		HighOpen::submitHigh($sales->id);
		//发送消息
		$message = array();
		$message['receivers'] = User::model()->getOperationList("销售单:审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."提交了销售单：".$baseform->form_sn.",请尽快审核。";
		$message['title'] = "销售通知";
		$message['url'] = Yii::app()->createUrl('FrmSales/index',array('card_no'=>$baseform->form_sn));
		$message['type'] = "销售单";
		$message['big_type']='sale';
		$res = MessageContent::model()->addMessage($message);

		//新增标记
		ProfitChange::createNew('sale',$this->commonForm->id,0);


		return true;
	}
	
	/**
	 * 取消提交表单
	 */
	public function cancelSubmitForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'submited') return;//只有提交未审核才可以取消提交
		$this->commonForm->form_status='unsubmit';
		$this->commonForm->update();
		//发送消息
		$sales = $this->mainInfo;
		//作废所有配送单
		$send = $sales->frmSends;
		if($send){
			foreach($send as $li){
				if($li->status == "finished" || $li->baseform->is_deleted == 1 || empty($li->baseform)){
					continue;
				}else{
					$this->commonForm->form_status='submited';
					$this->commonForm->update();
					return -2;
				}
			}
		}
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$oldJson=$this->commonForm->datatoJson();
			$this->commonForm->form_status = 'unsubmit';
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
				
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$result = $this->afterCancelSubmitForm();
				
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			$this->commonForm->form_status='submited';
			$this->commonForm->update();
			return "操作失败";
		}
		
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
		$sales = $this->mainInfo;
		//修改库存,减少锁定库存
		$details=$this->details;
		if($details){
			foreach ($details as $each){
				if($each->card_id){
					if($sales->sales_type == "normal"){
						$storage = MergeStorage::model()->findByPk($each->card_id);
						$oldJson = $storage->datatoJson();
						$storage -> lock_amount -= $each->amount;
						$storage -> lock_weight -= $each->weight;
						$connection=Yii::app()->db;
						$sql="update merge_storage set lock_amount=lock_amount-".$each->amount.",lock_weight=lock_weight-".$each->weight."  where id=".$each->card_id;
						$connection->createCommand($sql)->execute();
// 						$storage-> update();
						$storage = MergeStorage::model()->findByPk($each->card_id);
						$mainJson = $storage->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
					}else{
						$storage = Storage::model()->findByPk($each->card_id);
						$oldJson = $storage->datatoJson();
						$storage -> lock_amount -= $each->amount;
						$storage -> lock_weight -= $each->weight;
						$storage-> update();
						$mainJson = $storage->datatoJson();
						$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
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
	
		//修改高开信息'
		HighOpen::unsubmitHigh($sales->id);

		ProfitChange::createNew('sale',$this->commonForm->id,0);
		return true;
	}
	
	//-----------------------------------------------------------修改表单--------------------------------------------------------
	/**
	 * 修改主体信息
	 */
	 protected function updateMainInfo($data)
	 {
// 	 	$sales=$this->commonForm->sales;
	 	$old_yidan=$this->mainInfo->is_yidan;
	 	$sales=$this->mainInfo;	 	
	 	$oldJson=$sales->datatoJson();
	 	if($this->commonForm->form_status!='unsubmit')
	 	{
	 		if($data->company_contact_id)
	 		{
	 			$sales->company_contact_id=$data->company_contact_id;
	 			$sales->date_extract=strtotime($data->date_extract);
	 			$sales->comment=$data->comment;
	 			$sales->travel=$data->travel;
	 			if(checkOperation("销售单超级权限")){
	 				$sales->is_yidan=$data->is_yidan;
	 				$sales->has_bonus_price=$data->has_bonus_price;
	 			}
	 		}
	 	}else{
	 		foreach ($data as $k=>$v)
	 		{
	 			if($k == "date_extract"){
	 				$sales->date_extract=strtotime($v);
	 			}else{
	 				if($k == "warehouse_name"){continue;}
	 				$sales->$k=$v;
	 			}
	 		}
	 	}
	 	$new_yidan=$sales->is_yidan;
	 	if($new_yidan!=$old_yidan)$this->yidan_change=true;	 	
	 	if($sales->update())
	 	{
	 		$mainJson = $sales->datatoJson();
	 		$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
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
	 	$gk['CommonForms']['form_type']='GK';
	 	$gk['CommonForms']['form_time']=$this->commonForm->form_time;
	 	$gk['CommonForms']['owned_by']=$this->commonForm->owned_by;
	 	$gkdata['common']=(Object)$gk['CommonForms'];
	 	$baseform = $this->commonForm;
	 	$amount = 0;
	 	$weight = 0;
	 	$total_money = 0;
	 	$total_amount = 0;
	 	if($this->commonForm->form_status=='unsubmit' || checkOperation("销售单超级权限"))
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
	 				$amount += $e->amount;
	 				$weight += $e->weight;
	 				$total_amount += $e->total_amount;
	 				$total_money = $e->total_amount;
	 			}
	 			
	 			//更新主体表信息
	 			$sales=$this->mainInfo;
	 			$oldJson=$sales->datatoJson();
	 			$sales->amount = $amount;
	 			$sales->pre_amount = $amount;
	 			$sales->weight = $weight;
	 			$sales->pre_weight = $weight;
	 			$sales->fee = $total_money;
	 			
	 			$sales->update();
	 			$mainJson = $sales->datatoJson();
	 			$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 			$this->dataLog($dataArray);

	 			$details=$this->details;
	 			if(empty($id_array))
	 			{
	 				foreach ($details as $each)
	 				{
	 					$oldJson=$each->datatoJson();
	 					$hasHigh = HighOpen::getHighOpen($sales->id,$each->id);
	 					if($hasHigh){
	 						HighOpen::deleteLine($sales->id,$each->id);
	 					}
	 					$each->delete();
	 					$dataArray = array("tableName"=>"SalesDetail","newValue"=>"","oldValue"=>$oldJson);
	 					$this->dataLog($dataArray);
	 				}
	 			}else{
	 				foreach ($details as $each)
	 				{
	 					if(!in_array($each->id,$id_array))
	 					{
	 						$oldJson=$each->datatoJson();
	 						$hasHigh = HighOpen::getHighOpen($sales->id,$each->id);
	 						if($hasHigh){
	 							HighOpen::deleteLine($sales->id,$each->id);
	 						}
	 						$each->delete();
	 						$dataArray = array("tableName"=>"SalesDetail","newValue"=>"","oldValue"=>$oldJson);
	 						$this->dataLog($dataArray);
	 					}
	 				}
	 			}
	 	
	 			foreach ($data as $data_each)
	 			{
	 				if($data_each->id)
	 				{
	 					//修改此条数据
	 					$detail_data=SalesDetail::getOne($data_each->id);
	 					$oldJson=$detail_data->datatoJson();
	 					if($detail_data)
	 					{
	 						$old= $data_each->old;
	 						$gk_price = $detail_data->bonus_price;
	 						foreach ($data_each as $key =>$value)
	 						{
	 							if($key=='id')continue;
	 							if($key=='old')continue;
	 							//if($key=='total_amount')continue;
	 							if($key=='total_amount'){$detail_data->fee = $value;}
	 							if($key=='gk_id')continue;
	 							$detail_data->$key=$value;
	 						}
	 						if($sales->sales_type == "xxhj"){
	 							$detail_data->need_purchase_amount = $detail_data->amount;
	 						}
	 						$detail_data->update();
	 						$mainJson = $detail_data->datatoJson();
	 						$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 						$this->dataLog($dataArray);
 							//如果原来的高开价大于0，修改高开表
	 						if($gk_price > 0){
	 							$gk["main"]["price"] = $data_each->bonus_price;
	 							$gk["main"]["fee"] = $data_each->bonus_price*$data_each->weight;
	 							$gk["main"]["title_id"] = $sales->title_id;
	 							$gk["main"]["target_id"] = $data_each->gk_id;
	 							$gk["main"]["client_id"] = $sales->client_id;
	 							$gk["main"]["weight"] = $data_each->weight;
	 							$gkdata['main']=(Object)$gk['main'];
	 							HighOpen::updateLine($sales->id,$detail_data->id,$gkdata);
	 						 }else if($data_each->bonus_price > 0){
	 							//如果原来的高开价为0，修改后大于0.查询是否有过高开记录
	 							$hasHigh = HighOpen::getHighOpen($sales->id,$detail_data->id);
	 							if($hasHigh){
	 								$gk["main"]["price"] = $data_each->bonus_price;
	 								$gk["main"]["fee"] = $data_each->bonus_price*$data_each->weight;
	 								$gk["main"]["title_id"] = $sales->title_id;
	 								$gk["main"]["target_id"] = $data_each->gk_id;
	 								$gk["main"]["client_id"] = $sales->client_id;
	 								$gk["main"]["weight"] = $data_each->weight;
	 								$gkdata['main']=(Object)$gk['main'];
	 								HighOpen::updateLine($sales->id,$detail_data->id,$gkdata);
	 							}else{
		 							$gk["main"]["sales_id"] = $sales->id;
		 							$gk["main"]["sales_detail_id"] = $data_each->id;
		 							$gk["main"]["price"] = $data_each->bonus_price;
		 							$gk["main"]["fee"] = $data_each->bonus_price*$data_each->weight;
		 							$gk["main"]["title_id"] = $sales->title_id;
		 							$gk["main"]["target_id"] = $data_each->gk_id;
		 							$gk["main"]["client_id"] = $sales->client_id;
		 							$gk["main"]["weight"] = $data_each->weight;
		 							$gkdata['main']=(Object)$gk['main'];
		 							HighOpen::createHigh($gkdata,$this->commonForm->form_status);
	 							}
	 						}
	 						//如果已经有可开票信息，更新可开票信息	 	
	 						/*					 							
 							if($baseform->form_status == "approve"&&$this->mainInfo->is_import==0)
 							{
 								//甲乙单变化
 								$oldJson='';
 								$mainJson='';
 								if($this->yidan_change){
 									//变了
 									if($this->mainInfo->is_yidan)
 									{
 										//乙单，清掉开票信息----20161116xing
 										$invoice= DetailForInvoice::model()->find("form_id={$baseform->id} and detail_id={$detail_data->id}");
 										if($invoice)
 										{
 											$oldJson=$invoice->datatoJson();
 											if($invoice->checked_weight>0)throw new CException("已开票,不能更改为乙单") ;
 											$invoice->weight=0;
 											$invoice->money=0;
 											$invoice->update();
 											$mainJson=$invoice->datatoJson();
 										}
 									}else{
 										//甲单，生成该有的开票信息
 										$name=DictGoodsProperty::model()->findByPk($detail_data->product_id)->name;
 										$invoice= DetailForInvoice::model()->find("form_id={$baseform->id} and detail_id={$detail_data->id}");
 										if($invoice)
 										{
 											$oldJson=$invoice->datatoJson();
 											if($name=='螺纹钢')
 											{
 												$invoice->weight+=$detail_data->weight;
 												$invoice->money+=$detail_data->weight*$detail_data->price;
 											}else{
 												$invoice->weight+=$detail_data->output_weight;
 												$invoice->money+=$detail_data->output_weight*$detail_data->price;
 											}
 											$invoice->update();
 										}else{
 											$invoice=new DetailForInvoice();
 											$invoice->type='sales';
 											$invoice->form_id=$this->commonForm->id;
 											$invoice->detail_id=$detail_data->id;
 											if($name=="螺纹钢"){
 												$invoice->weight=$detail_data->weight;
 												$invoice->money=$detail_data->weight*$detail_data->price;
 											}else{
 												$invoice->weight=$detail_data->output_weight;
 												$invoice->money=$detail_data->output_weight*$detail_data->price;
 											}
 											$invoice->insert();
 										}
 										$mainJson=$invoice->datatoJson();
 									} 									
 								}else{
 									//没变
 									if($old != $detail_data->price&& !$this->mainInfo->is_yidan){
 										$detailfor = DetailForInvoice::model()->find("form_id={$baseform->id} and detail_id={$detail_data->id}");
 										if($detailfor){
 											$oldJson =  $detailfor->datatoJson();
 											$detailfor->money = $detailfor->weight * $detail_data->price;
 											$detailfor->update();
 											$mainJson = $detailfor->datatoJson(); 											
 										}
 									}	 									
 								}	 	
 								$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>$oldJson);
 								$this->dataLog($dataArray);
 							}
 							*/	 	
	 					}
	 				}else{
	 					//新建
	 					$salesDetail=new SalesDetail();
						$salesDetail->price = $data_each->price;
						$salesDetail->fix_price = $each->fix_price;
						$salesDetail->bonus_price = $data_each->bonus_price;
						$salesDetail->frm_sales_id = $this->mainInfo->id;
						$salesDetail->amount = $data_each->amount;
						$salesDetail->pre_amount = $data_each->amount;
						$salesDetail->weight = $data_each->weight;
						$salesDetail->pre_weight = $data_each->weight;
						$salesDetail->product_id = $data_each->product_id;
						$salesDetail->brand_id = $data_each->brand_id;
						$salesDetail->texture_id = $data_each->texture_id;
						$salesDetail->rank_id = $data_each->rank_id;
						$salesDetail->length = $data_each->length;
						$salesDetail->card_id = $data_each->card_id;
						$salesDetail->fee = $data_each->total_amount;
						if($sales->sales_type == "xxhj"){
							$salesDetail->need_purchase_amount = $data_each->amount;
						}
						$salesDetail->insert();
						$mainJson = $salesDetail->datatoJson();
						$dataArray = array("tableName"=>"SalesDetail","newValue"=>$mainJson,"oldValue"=>"");
						$this->dataLog($dataArray);
						array_push($this->details, $salesDetail);
						if($data_each->bonus_price > 0){
							//如果高开价为0，新增高开表信息
							$gk["main"]["sales_id"] = $sales->id;
							$gk["main"]["sales_detail_id"] = $salesDetail->id;
							$gk["main"]["price"] = $salesDetail->bonus_price;
							$gk["main"]["fee"] = $salesDetail->bonus_price*$salesDetail->weight;
							$gk["main"]["title_id"] = $sales->title_id;
							$gk["main"]["target_id"] = $data_each->gk_id;
							$gkdata['main']=(Object)$gk['main'];
							HighOpen::createHigh($gkdata);
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
	 	$dg_jm=$this->isDgJmSales();
	 	$dg_jm?$remark='抬头为登钢爵淼的销售单往来为0':$remark='';

	 	$id=$this->commonForm->id;
	 	$sales = $this->mainInfo;
	 	if (!is_array($this->details) || count($this->details)<=0) return;
	 	$detail = SalesDetail::model()->findAll("frm_sales_id=".$sales->id);
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
	 			$fee=$each->fee;
	 			$price = 0;
	 			$detailId = $each->id;
	 			$thrnover = Turnover::findDetailBill($id,$detailId);
	 			$owned_by=$this->commonForm->owned_by;
	 			
	 			if($thrnover){
	 				//修改往来信息
	 				$price = $each->price;
	 				if($dg_jm)$fee=0;else $fee = $each->fee;
// 	 				$fee = $each->fee;
	 				$amount = $each->weight;
	 				$company_id=$this->mainInfo->title_id;
	 				$vendor_id=$this->mainInfo->customer_id;
	 				$is_yidan = $this->mainInfo->is_yidan;
	 				$client_id=$this->mainInfo->client_id;
	 				$description="单号：".$this->commonForm->form_sn.",".DictGoodsProperty::getProName($each->brand_id)."|".DictGoodsProperty::getProName($each->product_id).
	 				"|".DictGoodsProperty::getProName($each->rank_id)."*".intval($each->length)."*".DictGoodsProperty::getProName($each->texture_id).$remark;
	 				//$description = $company_id."同".$vendor_id."产生一条need_charge往来,金额为:".$fee;
	 				//-----begin-----调整amount是数量，fee是金额，再看下是否需要修改往来的其他字段
	 				$created_at = strtotime($this->commonForm->form_time);
	 				$update=array('fee'=>$fee,'title_id'=>$company_id,'target_id'=>$vendor_id,'amount'=>$amount,"price"=>$price,'ownered_by'=>$owned_by,'description'=>$description,'is_yidan'=>$is_yidan,'created_at'=>$created_at,'client_id'=>$client_id);
	 				//-----end----
	 				$oldJson=$thrnover->datatoJson();
	 				$result = Turnover::updateBill($thrnover->id, $update);
	 				
	 				$mainJson = $result->datatoJson();
	 				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 				$this->dataLog($dataArray);
	 				//新增修改日志
	 			}else{
	 				//新增往来信息
	 				$price = $each->price + $each->bonus_price;
	 				if($dg_jm)$fee=0;else $fee = $each->fee;
// 	 				$fee = $each->fee;
	 				$amount = $each->weight;
	 				$description="单号：".$this->commonForm->form_sn.",".DictGoodsProperty::getProName($each->brand_id)."|".DictGoodsProperty::getProName($each->product_id).
	 				"|".DictGoodsProperty::getProName($each->rank_id)."*".intval($each->length)."*".DictGoodsProperty::getProName($each->texture_id).$remark;
	 				$da=array();
	 				$da['type']="XSMX";
	 				$da['turnover_direction']="need_charge";
	 				$da['title_id']= $this->mainInfo->title_id;
	 				$da['target_id']=$this->mainInfo->customer_id;
	 				$da['client_id']=$this->mainInfo->client_id;
	 				$da['proxy_company_id']='';
	 				$da['amount']=$amount;
	 				$da['price']=$price;
	 				$da['fee']=$fee;
	 				$da['common_forms_id']=$this->commonForm->id;
	 				$da['form_detail_id']=$each->id;
	 				$da['ownered_by']=$this->commonForm->owned_by;
	 				$da['created_by'] = currentUserId();
	 				$da['description'] = $description;
	 				$da['is_yidan'] = $this->mainInfo->is_yidan;
	 				$da['big_type'] = "sales";
	 				$da['created_at'] = strtotime($this->commonForm->form_time);
	 				$result = Turnover::createBill($da);
	 				//新增修改日志
	 				$mainJson = $result->datatoJson();
	 				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
	 				$this->dataLog($dataArray);
	 			}
	 		}

	 	}
	 	ProfitChange::createNew('sale',$this->commonForm->id,0);
	 }
	 
	 
	 
	 //***********作废表单*****************//	 
	 public function deleteForm($delete_reason)
	 {
	 	if ($this->commonForm == null) return false;//表单为空
	 	if ($this->commonForm->form_status != 'unsubmit') return;//表单状态不是未提交
	 
	 	$transaction=Yii::app()->db->beginTransaction();
	 	try {
	 		$olddata=$this->commonForm;
	 		$oldJson=$olddata->datatoJson();
	 		$this->commonForm->form_status = 'delete';
	 		$this->commonForm->is_deleted = 1;
	 		$this->commonForm->last_update = time();
	 		$this->commonForm->last_updated_by = currentUserId();
	 		$this->commonForm->delete_reason= $delete_reason;
	 		$this->commonForm->update();
	 			
	 		$commonJson = $this->commonForm->datatoJson();
	 		$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
	 		$this->dataLog($dataArray);
	 		$result=$this->afterDeleteForm();
	 		if($result!==true)throw new CException('失败');
	 		$transaction->commit();
	 	}catch (Exception $e)
	 	{	 		
	 		$transaction->rollBack();//事务回滚
	 		if($result=='purchased')return '已补采购单，不能删除';
	 		else	return '操作失败';
	 	}
	 
	 	//发送消息
	 
	 	//新增日志
	 	$operation = "删除";
	 	$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
	 	return true;
	 }
	 
	 /**
	  * 作废表单后的操作
	  */
	 protected function afterDeleteForm()
	 {
	 	//代销和先销后进检查
	 	$res=false;
	 	if($this->mainInfo->sales_type!='normal')
	 	{
	 		foreach ($this->details as $each)
	 		{
	 			$res=SaledetailPurchase::model()->exists('sales_detail_id='.$each->id);
	 			if($res)
	 				break;
	 		}
	 	}
	 	if($res)return 'purchased';
	 	
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
	 	//作废高开
	 	$sales = $this->mainInfo;
	 	$detail = $this->details;
	 	if($detail){
	 		foreach ($detail as $each){
	 			$hasHigh = HighOpen::getHighOpen($sales->id,$each->id);
	 			if($hasHigh){
	 				HighOpen::deleteLine($sales->id,$each->id);
	 			}
	 		}
	 	}
	 	return true;
	 }
	 
	 /**
	  * 审核通过后续操作
	  */
	 protected function afterApproveForm()
	 {
	 	$baseform = $this->commonForm;
	 	$sales = $this->mainInfo;
	 	//修改往来(往来不需要修改)
	 	//修改高开信息'
	 	HighOpen::checkHigh($sales->id);
	 	//如果是螺纹钢，设置可开票明细
// 	 	$details = $sales->salesDetails;
// 	 	if($details){
// 	 		foreach($details as $each){
// 	 			$std = DictGoodsProperty::getStd($each->product_id);
// 	 			if($std == "lwg" && $sales->is_yidan == 0){
// 	 				//设置可开票明细
// 	 				$price = $each->weight*($each->price+$each->bonus_price);
// 	 				$invoice = DetailForInvoice::setSalesInvoice($each->FrmSales->baseform->id,$each->id,$each->weight,$price,$each->FrmSales->title_id,$each->FrmSales->customer_id);
// 	 				if(!$invoice){
// 	 					return false;
// 	 				}
// 	 			}
// 	 		}
// 	 	}
	 	//发送消息
	 	if(Yii::app()->user->userid != $baseform->owned_by){
		 	$message = array();
		 	$message['receivers'] = $baseform->owned_by;
		 	$message['content'] = "您的销售单：".$baseform->form_sn."已经审核通过。";
		 	$message['title'] = "审核通知";
		 	//$message['url'] = "";
		 	$message['type'] = "销售单";
		 	$message['big_type']='sale';
		 	$res = MessageContent::model()->addMessage($message);
	 	}	 	

	 }
	 
	 /**
	  * 表单审核拒绝
	  */
	 public function refuseForm()
	 {
	 	if ($this->commonForm == null) return false;//表单为空
	 	if ($this->commonForm->form_status != 'submited') return;//表单状态不是提交
	 
	 	$transaction=Yii::app()->db->beginTransaction();
	 	try {
	 		$olddata=$this->commonForm;
	 		$oldJson=$olddata->datatoJson();
	 		$this->commonForm->form_status = 'unsubmit';
	 		$this->commonForm->last_update = time();
	 		$this->commonForm->last_updated_by = currentUserId();
	 		$this->commonForm->update();
	 			
	 		$commonJson = $this->commonForm->datatoJson();
	 		$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
	 		$this->dataLog($dataArray);
	 		$this->afterRefuseForm();
	 			
	 			
	 		$transaction->commit();
	 	}catch (Exception $e)
	 	{
	 		$transaction->rollBack();//事务回滚
	 		return "操作失败";
	 	}
	 	//发送消息
	 	$sales = $this->mainInfo;
	 	//作废所有配送单
	 	$send = $sales->frmSends;
	 	if($send){
	 		foreach($send as $li){
	 			if($li->status == "finished" || $li->baseform->is_deleted == 1 || empty($li->baseform)){
	 				continue;
	 			}
	 			$form=new Frm_Send($li->baseform->id);
	 			$form->deleteForm("取消提交销售单作废配送单");
	 		}
	 	}
	 	//新增日志
	 	$operation = "拒绝";
	 	$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
	 	return true;
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
	 			if($each->card_id){
	 				//库存销售减少聚合表锁定件数
	 				if($sales->sales_type == "normal"){
	 					$storage = MergeStorage::model()->findByPk($each->card_id);
	 					$oldJson = $storage->datatoJson();
	 					$storage -> lock_amount -= $each->amount;
	 					$storage -> lock_weight -= $each->weight;
	 					$connection=Yii::app()->db;
	 					$sql="update merge_storage set lock_amount=lock_amount-".$each->amount.",lock_weight=lock_weight-".$each->weight."  where id=".$storage->id;
	 					$connection->createCommand($sql)->execute();
	 					$storage=MergeStorage::model()->findByPk($storage->id);	 					
// 	 					$storage-> update();
	 					$mainJson = $storage->datatoJson();
	 					$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 					$this->dataLog($dataArray);
	 				//代销销售减少库存件数
	 				}else{
		 				$storage = Storage::model()->findByPk($each->card_id);
		 				$oldJson = $storage->datatoJson();
		 				$storage -> lock_amount -= $each->amount;
		 				$storage -> lock_weight -= $each->weight;
		 				$storage-> update();
		 				$mainJson = $storage->datatoJson();
		 				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
		 				$this->dataLog($dataArray);
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
	
	 	//修改高开信息
	 	HighOpen::unsubmitHigh($sales->id);
	 	//发送消息
	 	if(Yii::app()->user->userid != $baseform->owned_by){
		 	$message = array();
		 	$message['receivers'] = $baseform->owned_by;
		 	$message['content'] = "您的销售单：".$baseform->form_sn."审核已被拒绝。";
		 	$message['title'] = "审核通知";
		 	//$message['url'] = "";
		 	$message['type'] = "销售单";
		 	$message['big_type']='sale';
		 	$res = MessageContent::model()->addMessage($message);
	 	}
	 	ProfitChange::createNew('sale',$this->commonForm->id,0);
	 }
	 
	 /**
	  * 取消审核通过后续操作
	  */
	 protected function afterCancelApproveForm()
	 {
	 	$baseform = $this->commonForm;
	 	$sales = $this->mainInfo;
	 	HighOpen::uncheckHigh($sales->id);
	 	//如果是螺纹钢，删除可开票明细
	 	$details = $sales->salesDetails;
	 	if($details){
// 	 		foreach($details as $each){
// 	 			$std = DictGoodsProperty::getStd($each->product_id);
// 	 			if($std == "lwg" && $sales->is_yidan == 0){
// 	 				//设置可开票明细
// 	 				$invoice = DetailForInvoice::model()->find("form_id=".$each->FrmSales->baseform->id." and detail_id=".$each->id);
// 	 				if($invoice){
// 	 					$oldJson = $invoice->datatoJson();
// 	 					if($invoice->checked_weight > 0){
// 	 						return -1;
// 	 					}else{
// 	 						$invoice->delete();
// 	 						$dataArray = array("tableName"=>"DetailForInvoice","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 	 						$this->dataLog($dataArray);
// 	 					}
// 	 				}
// 	 			}
// 	 		}
	 		//发送消息
	 		if(Yii::app()->user->userid != $baseform->owned_by){
		 		$message = array();
		 		$message['receivers'] = $baseform->owned_by;
		 		$message['content'] = "您的销售单：".$baseform->form_sn."已被取消审核。";
		 		$message['title'] = "审核通知";
		 		//$message['url'] = "";
		 		$message['type'] = "销售单";
		 		$message['big_type']='sale';
		 		$res = MessageContent::model()->addMessage($message);
	 		}
	 	}
	 	
	 	//清除取消审核申请
	 	$conn=Yii::app()->db;
	 	$sql="update canclecheck_record set status=1 where common_id=".$this->commonForm->id." and status=0";
	 	$conn->createCommand($sql)->execute();
	 }
	  
	 /**
	  * 销售单完成
	  */
	 public function completeSales()
	 {
	 	$dg_jm=$this->isDgJmSales();
	 	$dg_jm?$remark='抬头为登钢爵淼的销售单往来为0':$remark='';
	 	
	 	$id=$this->commonForm->id;
	 	$sales=$this->mainInfo;
	 	$confirm_amount = 0;
	 	$confirm_weight = 0;
	 	$confirm_fee = 0;
	 	$lwg_confirm_fee=$lwg_confirm_weight=$lwg_old_fee=$lwg_old_weight=0;
	 	$lwg_details='';
	 	//完成配送单
	 	$send = $sales->frmSends;
	 	if($sales->output_amount <= 0){ return false;}	
	 	if($send){
	 		foreach($send as $li){
	 			if($li->status == "finished" || $li->baseform->is_deleted == 1 || empty($li->baseform)){
	 				continue;
	 			}
	 			$form=new Frm_Send($li->baseform->id);
	 			$form->completeSendForm();
	 		}
	 	}
	 	//作废所有未完成的出库单
	 	$frmOutputs = $sales->frmOutputs;
	 	if($frmOutputs){
	 		foreach ($frmOutputs as $li){
	 			if($li->input_status != 0){continue;}
	 			$form=new Output($li->baseform->id);
	 			$form->deleteForm("完成销售单作废未出库的出库单");
	 		}
	 	}
	 	//完成配送单时，销售主体已经发生变化，需重新查询一遍，再进行处理
	 	$sales = FrmSales::model()->with("salesDetails")->findByPk($sales->id);
	 	//查询获取销售单明细
	 	$details = $sales->salesDetails;
	 	$transaction=Yii::app()->db->beginTransaction();
	 	try {
	 		if($details){
	 			foreach ($details as $each){
	 				$confirm_amount += $each->output_amount;
	 				$confirm_weight += $each->output_weight;
	 				// $product_name=DictGoodsProperty::model()->findByPk($each->product_id)->short_name;
	 				// if($product_name=='螺纹钢'){
	 				// 	$lwg_confirm_fee+=$each->output_weight *  $each->price;
	 				// 	$lwg_confirm_weight+=$each->output_weight;
	 				// 	$lwg_old_fee+=$each->fee;
	 				// 	$lwg_old_weight+=$each->weight;
	 				// 	$lwg_details.=($each->id.',');
	 				// }

	 				//开票信息确认
 					if(!$this->mainInfo->is_yidan){
 						$invoice = $each->detailsInvoice;
 						if(!$invoice){
 							$invoice = new DetailForInvoice();
 							$invoice->type = 'sales';
 							$invoice->form_id = $this->commonForm->id;
 							$invoice->detail_id = $each->id;
 							$invoice->weight = $each->output_weight;
 							$invoice->money = $each->output_weight*$each->price;
 							$invoice->title_id = $this->mainInfo->title_id;
 							$invoice->company_id = $this->mainInfo->customer_id;
 							$invoice->client_id = $this->mainInfo->client_id;
 							$invoice->insert();
 						}else{
 							$invoice->weight = $each->output_weight;
 							$invoice->money = $each->output_weight*$each->price;
 							$invoice->title_id = $this->mainInfo->title_id;
 							$invoice->company_id = $this->mainInfo->customer_id;
 							$invoice->client_id = $this->mainInfo->client_id;
 							if(floatval($invoice->checked_weight)>$invoice->weight){
 								throw new CException("已开票"); 								
 							}
 							$invoice->update();
 						}
 					}else{
 						$invoice = $each->detailsInvoice;
 						if($invoice){
 							if(floatval($invoice->checked_weight))throw new CException("已开票"); 							
 							$invoice->delete();
 						}
 					}
	 				$oldDetail = $each->datatoJson();
	 				$old_amount = $each->amount;
	 				$cha_amount = $each->amount - $each->output_amount;
	 				$cha_weight = $each->weight - $each->output_weight;
	 				$each->amount = $each->output_amount;
	 				if(empty($each->pre_weight) || $each->pre_weight == 0){
	 					$each->pre_weight = $each->weight;
	 				}
	 				$each->weight = $each->output_weight;
	 				if($each->fee == 1){
	 					$each->fee = 1;
	 				}else{
	 					$each->fee = $each->output_weight *  $each->price;
	 				}
	 				// 	 			$each->send_amount = $each->output_amount;
	 				// 	 			$each->send_weight = $each->output_weight;
	 				if($sales->sales_type == "xxhj"){
	 					$each->need_purchase_amount -= $cha_amount;
	 				}
	 				$each->update();
	 				$newDetail = $each->datatoJson();
	 				$dataArray = array("tableName"=>"SalesDetails","newValue"=>$newDetail,"oldValue"=>$oldDetail);
	 				$this->dataLog($dataArray);
	 				$thrnover = Turnover::findDetailBill($id,$each->id);
	 				$oldJson=$thrnover->datatoJson();
	 				//修改往来信息
	 				$price = $each->price;
	 				$amount = $each->output_weight;
	 				//$fee = $price*$amount;
	 				if($dg_jm)$fee=0;else $fee = $each->fee;
	 				// 	 			$fee = $each->fee;
	 				$confirm_fee += $fee;
	 				//-----begin-----调整amount是数量，fee是金额，再看下是否需要修改往来的其他字段
	 				$update=array('fee'=>$fee,'amount'=>$amount,"price"=>$price,"confirmed"=>1);
	 				//-----end----
	 				$result = Turnover::updateBill($thrnover->id,$update);
	 				//新增修改日志
	 				$mainJson = $result->datatoJson();
	 				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 				$this->dataLog($dataArray);
	 				//修改高开信息
	 				if($each->bonus_price > 0){
	 					$gk["main"]["price"] = $each->bonus_price;
	 					$gk["main"]["fee"] = $each->bonus_price*$each->weight;
	 					$gk["main"]["weight"] = $each->weight;
	 					$gkdata['main']=(Object)$gk['main'];
	 					HighOpen::updateLine($sales->id,$each->id,$gkdata);
	 				}
	 				//未出库的产品 库存回滚
	 				$not_out = $old_amount - $each->output_amount;
	 				if($sales->sales_type == "normal"){
	 					$storage = $each->mergestorage;
	 					if($storage){
	 						$oldJson = $storage->datatoJson();
	 						$storage->lock_amount -=$not_out;
	 						$storage->lock_weight -=$cha_weight;
	 						 
	 						$connection=Yii::app()->db;
	 						$sql="update merge_storage set lock_amount=lock_amount-".$not_out.",lock_weight=lock_weight-".$cha_weight."  where id=".$storage->id;
	 						$connection->createCommand($sql)->execute();
	 						$storage=MergeStorage::model()->findByPk($storage->id);
	 						// 			 			$storage->update();
	 						$mainJson = $storage->datatoJson();
	 						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 						$this->dataLog($dataArray);
	 					}
	 				}else if($sales->sales_type == "dxxs"){
	 					$storage = $each->storage;
	 					if($storage){
	 						$oldJson = $storage->datatoJson();
	 						$storage->lock_amount -=$not_out;
	 						$storage->lock_weight -=$cha_weight;
	 						$storage->update();
	 						$mainJson = $storage->datatoJson();
	 						$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 						$this->dataLog($dataArray);
	 					}
	 				}
	 		
	 				if ($each->output_amount != $each->pre_amount || $each->output_weight != $each->pre_weight) {
	 					//记录差异
	 					$variance = new VarianceReport();
	 					$variance->type = 'sales';
	 					$variance->detail_id = $each->id;
	 					$variance->variance_amount = $each->pre_amount - $each->output_amount;
	 					$variance->variance_weight = $each->pre_weight - $each->output_weight;
	 					$variance->insert();
	 				}
	 		
	 			}
	 		}	 		
	 		// if(($lwg_confirm_fee!=$lwg_old_fee||$lwg_old_weight!=$lwg_confirm_weight)&&$lwg_details){	 			
	 		// 	$lwg_details=substr($lwg_details, 0,strlen($lwg_details)-1);
	 		// 	$sql="select sum(checked_weight) as checked_weight,sum(checked_money) as checked_money 
	 		// 	from detail_for_invoice where form_id={$this->commonForm->id} and detail_id in ({$lwg_details}) ";
	 		// 	$invoice=DetailForInvoice::model()->findBySql($sql);
	 		// 	if(floatval($invoice->checked_weight)!=0)
	 		// 	{
	 		// 		if(floatval($invoice->checked_weight)!=floatval($lwg_confirm_weight)||floatval($invoice->checked_weight)!=floatval($lwg_confirm_weight)){
	 		// 			throw new CException("已开票");
	 		// 		}
	 		// 	}
	 		// }	 		
	 		
	 		//修改主体信息
	 		$sales->confirm_amount = $confirm_amount;
	 		$sales->confirm_weight = $confirm_weight;
	 		$sales->amount = $confirm_amount;
	 		$sales->weight = $confirm_weight;
	 		$sales->fix_fee = $confirm_fee;
	 		$sales->confirm_status = 1;
	 		$oldJson=$sales->datatoJson();
	 		if($sales->update())
	 		{
	 			$mainJson = $sales->datatoJson();
	 			$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 			$this->dataLog($dataArray);
	 			$operation = "审单";
	 			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
	 			ProfitChange::createNew('sale',$this->commonForm->id,0);	 			
	 		}
	 		$transaction->commit();
	 	}catch (Exception $e){	 		
	 		$transaction->rollback();
	 		if($e->message=="已开票")return "已开票";
	 		return false;
	 	}
	 	return true;
	 }

	 /**
	  * 取消销售单完成
	  */
	public function cancelcompleteSales()
	{
		$dg_jm=$this->isDgJmSales();
// 		$dg_jm?$remark='抬头为登钢爵淼的销售单往来为0':$remark='';
		$id=$this->commonForm->id;
		$sales=$this->mainInfo;
		$details = $sales->salesDetails;
		$total_amount = 0;
		$total_weight = 0;
		$tran = Yii::app()->db->beginTransaction();
		try{
			if($details){
				foreach ($details as $each){
					$total_amount += $each->pre_amount;
					$weight = DictGoods::getWeightByStorage($each);
					$cha_weight = $weight*$each->pre_amount;
					$total_weight += $cha_weight;
					$oldDetail = $each->datatoJson();
					$cha_amount = $each->pre_amount - $each->amount;
					$each->amount = $each->pre_amount;
					$each->weight = $cha_weight;
					if($each->fee != 1){
						$each->fee = $cha_weight * $each->price;
					}
					if($sales->sales_type == "xxhj"){
						$each->need_purchase_amount += $cha_amount;
					}
					$each->update();
					$newDetail = $each->datatoJson();
					$dataArray = array("tableName"=>"SalesDetails","newValue"=>$newDetail,"oldValue"=>$oldDetail);
					$this->dataLog($dataArray);

					$thrnover = Turnover::findDetailBill($id,$each->id);
					$oldJson=$thrnover->datatoJson();
					//修改往来信息
					if($dg_jm)$fee=0;else $fee = $each->fee;
					$update=array('amount'=>$each->weight,"fee"=>$fee,"confirmed"=>0);
					//-----end----
					$result = Turnover::updateBill($thrnover->id,$update);
					//新增修改日志
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
					//库存增加锁定件数
					$not_out = $each->pre_amount - $each->output_amount;
					if($sales->sales_type == "normal"){
						$storage = $each->mergestorage;
						if($storage){
							$oldJson = $storage->datatoJson();
							$storage->lock_amount +=$not_out;
							$storage->lock_weight +=$cha_weight - $each->output_weight;
							$connection=Yii::app()->db;
							$sql="update merge_storage set lock_amount=lock_amount+".$not_out.",lock_weight=lock_weight+".($cha_weight-$each->output_weight)."  where id=".$storage->id;
							$connection->createCommand($sql)->execute();
							$storage=MergeStorage::model()->findByPk($storage->id);						
	// 						$storage->update();
							$mainJson = $storage->datatoJson();
							$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$this->dataLog($dataArray);
						}
					}else if($sales->sales_type == "dxxs"){
						$storage = $each->storage;
						if($storage){
							$oldJson = $storage->datatoJson();
							$storage->lock_amount +=$not_out;
							$storage->lock_weight +=$cha_weight - $each->output_weight;
							$storage->update();
							$mainJson = $storage->datatoJson();
							$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$this->dataLog($dataArray);
						}
					}
					//删除差异记录
					$variance = VarianceReport::model()->find("detail_id = :detail_id", array(':detail_id' => $each->id));
					if ($variance) $variance->delete();

					//删除可开票信息
					$invoice = $each->detailsInvoice;
					if($invoice){
						if(floatval($invoice->checked_weight)){
							throw new CException("已开票");						
						}
						$invoice->delete();
					}

				}
			}
			$sales->confirm_status = 0;
			$sales->amount = $sales->pre_amount;
			$sales->weight = $total_weight;
			$oldJson=$sales->datatoJson();
			if($sales->update())
			{
				$mainJson = $sales->datatoJson();
				$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				$operation = "取消审单";
				$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
				ProfitChange::createNew('sale',$this->commonForm->id,0);
				
			}
			$tran->commit();
		}catch(Exception $e){
			$tran->rollback();
			if($e->message=='已开票'){
				return "已开票";
			}
			return false;
		}		
		return true;
	}




	protected function getDetailByFormId($id)
	{
		$result = SalesDetail::model()->findAll("frm_sales_id = :frm_sales_id",array(":frm_sales_id"=>$id));
		if (count($result)>0)
			return $result;
	}
	
	public function isDgJmSales()
	{
		return ($this->mainInfo->title->short_name=='登钢商贸'||$this->mainInfo->title->short_name=='爵淼实业');
	}
	
}