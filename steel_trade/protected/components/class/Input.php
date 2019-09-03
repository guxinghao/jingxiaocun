<?php
class Input extends BaseForm
{
	public $mainModel = "FrmInput";
	public $detailModel = "InputDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="入库";
	
	public function __construct($id,$type="")
	{
		if(intval($id))
		{
			if($type=="dxrk")
			{
				$model=CommonForms::model()->with('inputdx','inputdx.inputDetailsDx')->findByPk($id);
				if($model)
				{
					$this->commonForm=$model;
					$this->mainInfo=$model->inputdx;
					$this->details=$model->inputdx->inputDetailsDx;
				}
			}else{
				$model=CommonForms::model()->with('input','input.inputDetails')->findByPk($id);
				if($model)
				{
					$this->commonForm=$model;
					$this->mainInfo=$model->input;
					$this->details=$model->input->inputDetails;
				}
			}
		}
	}
	
	/*****--------------------------基类方法重构---------------------------------******/
	
	/*
	 * 创建保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		if($data->input_type=="dxrk")
		{
			$mainInfo=new FrmInputDx();	
			$mainInfo->supply_id=$data->supply_id;//
			$mainInfo->title_id=$data->title_id;//
			$mainInfo->warehouse_id=$data->warehouse_id;//仓库
			$mainInfo->team_id=$data->team_id;
			$mainInfo->contact_id=$data->contact_id;
			$mainInfo->amount=$data->amount;
			$mainInfo->weight=$data->weight;
			if($mainInfo->insert())
				$mainJson = $mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmInputDx","newValue"=>$mainJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				return $mainInfo;
		}else{
			$mainInfo=new FrmInput();
			$mainInfo->input_type=$data->input_type;//入库类型
			$mainInfo->purchase_id=$data->purchase_id;//采购单id
			$mainInfo->input_date=strtotime($data->input_date);//预计入库时间
			$mainInfo->input_time=$data->input_time;
			$mainInfo->warehouse_id=$data->warehouse_id;//仓库
			$mainInfo->input_status=$data->input_status;//入库状态
			$mainInfo->push_id=$data->push_id?$data->push_id:0;//仓库推送信息			
			$mainInfo->from=$data->from;//创建入库单来源	
			$mainInfo->plan_id=$data->plan_id;		
			if($mainInfo->insert()){
				$mainJson = $mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmInput","newValue"=>$mainJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				
				return $mainInfo;
			}
		}
		
		return null;
	}
	
	/*
	 * 创建保存明细
	 */
	protected function saveDetails($data)
	{
		$detail=array();
		if (!is_array($data)||count($data)<=0)
			return;
		if(isset($this->mainInfo->input_type))
		{
			if($this->mainInfo->push_id==0)
			{
				foreach ($data as $each)
				{
					$input=new InputDetail();
					$input->input_id=$this->mainInfo->id;
					$input->input_amount=$each->input_amount;
					$input->input_weight=$each->input_weight;
					$input->purchase_detail_id=$each->purchase_detail_id;
					$input->cost_price=$each->cost_price;
					$input->product_id=$each->product_id;
					$input->rank_id =$each-> rank_id;
					$input->texture_id=$each->texture_id;
					$input->brand_id=$each->brand_id;
					$input->length=$each->length;
					$input->card_id=$each->card_id;
					$input->remain_amount=$each->remain_amount;
					if($input->insert())
					{
						//日志
						$detailJson = $input->datatoJson();
						$dataArray = array("tableName"=>"InputDetail","newValue"=>$detailJson,"oldValue"=>"");
						$this->dataLog($dataArray);
						array_push($detail,$input);
					}
				}
			}else{
				foreach ($data as $each)
				{
					$input=new InputDetail();
					$input->input_id=$this->mainInfo->id;
					$input->input_amount=$each->input_amount;
					$input->input_weight=$each->input_weight;					
					$input->cost_price=$each->cost_price;
					$input->product_id=$each->product_id;
					$input->rank_id =$each-> rank_id;
					$input->texture_id=$each->texture_id;
					$input->brand_id=$each->brand_id;
					$input->length=$each->length;
					$input->card_id=$each->card_id;
					$input->push_detail_id=$each->push_detail_id;//
					$plandetail=InputDetailPlan::model()->findByPk($each->original_detail_id);
					$input->purchase_detail_id=$plandetail->purchase_detail_id;
					if($input->insert())
					{
						//日志
						$detailJson = $input->datatoJson();
						$dataArray = array("tableName"=>"InputDetail","newValue"=>$detailJson,"oldValue"=>"");
						$this->dataLog($dataArray);
						array_push($detail,$input);
					}
				}
			}
			
		}else{//代销
			foreach ($data as $each)
			{
				$input=new InputDetailDx();
				$input->input_id=$this->mainInfo->id;
				$input->input_amount=$each->input_amount;
				$input->input_weight=$each->input_weight;
				$input->product_id=$each->product_id;
				$input->rank_id =$each-> rank_id;
				$input->texture_id=$each->texture_id;
				$input->brand_id=$each->brand_id;
				$input->length=$each->length;
				$input->card_id=$each->card_id;
				if($input->insert())
				{
					//日志
					$detailJson = $input->datatoJson();
					$dataArray = array("tableName"=>"InputDetailDx","newValue"=>$detailJson,"oldValue"=>"");
					$this->dataLog($dataArray);
					array_push($detail,$input);
				}
			}
		}
		
		return $detail;
	}
	
	/**
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
		//新增日志	
		if(isset($this->mainInfo->input_type))
		{
			if($this->mainInfo->push)
			{
				$this->mainInfo->push->input_status=1;
				$this->mainInfo->push->update();
			}
		}		
	}
	
	
	/****------------------------基类方法重构之提交表单----------------****/
	/**
	 * 提交表单后的动作
	 */
	protected function afterSubmitForm()
	{
		//发送消息通知
		
		//新增提交日志
	
	}
	
	/**
	 * 取消提交表单后的动作
	 */
	protected function afterCancelSubmitForm()
	{
		//发送消息通知
	
		//新增取消提交日志
	}
	
	
	/****------------------------基类方法重构之修改表单----------------****/
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		$con=$this->mainInfo;
		$oldJson=$con->datatoJson();
		
		if ($this->commonForm->form_status=='unsubmit')
		{
			if($data->input_type!='dxrk')
			{
				$this->mainInfo->input_type=$data->input_type;
				$this->mainInfo->purchase_id=$data->purchase_id;
				$this->mainInfo->input_date=strtotime($data->input_date);
				$this->mainInfo->input_time=$data->input_time;
				$this->mainInfo->warehouse_id=$data->warehouse_id;
// 				if($data->input_type=='ccrk')$this->mainInfo->goods_status=$data->goods_status;					
			}else{
				$this->mainInfo->supply_id=$data->supply_id;//
				$this->mainInfo->title_id=$data->title_id;//
				$this->mainInfo->warehouse_id=$data->warehouse_id;//仓库
				$this->mainInfo->team_id=$data->team_id;
				$this->mainInfo->contact_id=$data->contact_id;
				$this->mainInfo->amount=$data->amount;
				$this->mainInfo->weight=$data->weight;
			}
		}elseif($this->commonForm->form_status=='approve'){
			$this->mainInfo->input_date=strtotime($data->input_date);
			$this->mainInfo->input_time=$data->input_time;
// 			if($this->mainInfo->input_type=='ccrk')$this->mainInfo->goods_status=$data->goods_status;
		}
		
		if($this->mainInfo->update()){
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmInput","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			return true;
		}
			
	}
	
	/**
	 * 修改明细
	 */
	protected function updateDetails($data)
	{
		if($this->commonForm->form_status=='approve')return true;
		if (!is_array($data) || count($data)<=0 || !is_array($this->details) || count($this->details)<=0)
			return;
		if ($this->commonForm->form_status=="unsubmit"){//如果不是未提交，明细不能修改
			if(isset($this->mainInfo->input_type))
			{//非代销入库
				$new_pur_details_id=array();
				foreach ($data as $e)
				{
					array_push(	$new_pur_details_id,$e->purchase_detail_id);
				}
				foreach ($this->details as $ea)
				{
					if(!in_array($ea->purchase_detail_id,$new_pur_details_id)){
						$ea->delete();
						//日志
						$oldJson=$ea->datatoJson();
						$dataArray = array("tableName"=>"InputDetail","newValue"=>"","oldValue"=>$oldJson);
						$this->dataLog($dataArray);
						continue;
					}
					$old_pur_details_id[$ea->id]=$ea->purchase_detail_id;
				}
				foreach ($data as $data_e)
				{
					if(in_array($data_e->purchase_detail_id,$old_pur_details_id))
					{//还是原来那个
						$old_id=array_search($data_e->purchase_detail_id, $old_pur_details_id);
						$detail=InputDetail::model()->findByPk($old_id);
						$olddata=$detail;
						$oldJson=$olddata->datatoJson();
						if($detail)
						{
							$detail->input_amount=$data_e->input_amount;
							$detail->input_weight=$data_e->input_weight;
							$detail->cost_price=$data_e->cost_price;
							$detail->card_id=$data_e->card_id;
							$detail->remain_amount=$data_e->remain_amount;
							$detail->remain_weight=$data_e->remain_weight;
							$detail->update();
							
							//日志
							$mainJson = $detail->datatoJson();
							$dataArray = array("tableName"=>"InputDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$this->dataLog($dataArray);
						}
					}else{//要新建一个了
						$input=new InputDetail();
						$input->input_id=$this->mainInfo->id;
						$input->input_amount=$data_e->input_amount;
						$input->input_weight=$data_e->input_weight;
						$input->purchase_detail_id=$data_e->purchase_detail_id;
						$input->cost_price=$data_e->cost_price;
						$input->product_id=$data_e->product_id;
						$input->rank_id =$data_e-> rank_id;
						$input->texture_id=$data_e->texture_id;
						$input->brand_id=$data_e->brand_id;
						$input->length=$data_e->length;
						$input->card_id=$data_e->card_id;
						$input->remain_amount=$data_e->remain_amount;
						$input->remain_weight=$data_e->remain_weight;
						$input->insert();
						
						$mainJson = $input->datatoJson();
						$dataArray = array("tableName"=>"InputDetail","newValue"=>$mainJson,"oldValue"=>"");
						$this->dataLog($dataArray);
					}
				}
			}else{
				//代销入库
				$ids=array();
				foreach ($data as $each)
				{
					if($each->id)array_push($ids, $each->id);
				}
				foreach ($this->details as $each)
				{
					if(!in_array($each->id, $ids))
					{
						$each->delete();
						
						//日志
						$oldJson=$each->datatoJson();
						$dataArray = array("tableName"=>"InputDetailDx","newValue"=>"","oldValue"=>$oldJson);
						$this->dataLog($dataArray);
					}
				}
				foreach ($data as $each)
				{
					if($each->id)
					{//更新
						$detail=InputDetailDx::model()->findByPk($each->id);
						$olddata=$detail;
						$oldJson=$olddata->datatoJson();
						foreach ($each as $k=>$v)
						{
							$detail->$k=$v;
						}
						$detail->update();
						
						$mainJson = $detail->datatoJson();
						$dataArray = array("tableName"=>"InputDetailDx","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
						
					}else{//新建
						$detail=new InputDetailDx();
						foreach ($each as $k=>$v)
						{
							$detail->$k=$v;
						}
						$detail->input_id=$this->mainInfo->id;
						$detail->insert();
						
						$mainJson = $detail->datatoJson();
						$dataArray = array("tableName"=>"InputDetailDx","newValue"=>$mainJson,"oldValue"=>"");
						$this->dataLog($dataArray);
					}
				}
				
			}
			return true;
		}
	}
	
	/**
	 * 修改以后操作
	 */
	protected function afterupdateForm()
	{
		//船舱入库修改库存预计到货时间
		if(isset($this->mainInfo->input_type)&&$this->mainInfo->input_type=='ccrk')
		{
			$input_details=$this->mainInfo->inputDetails;
			foreach ($input_details as $each)
			{
				$storage=$each->storage;
				if($storage)
				{
					$storage->pre_input_date=$this->mainInfo->input_date;
					$storage->input_time=$this->mainInfo->input_time;
					$merge=$storage->mergeStorage;
					$merge->pre_input_date=$this->mainInfo->input_date;
					$merge->pre_input_time=$this->mainInfo->input_time;
					$storage->update();
					$merge->update();
				}
			}
		}
		
		//新增修改日志
	}
	
	/****------------------------基类方法重构之作废表单----------------****/
	/**
	 * 作废后的操作
	 */
	protected function afterDeleteForm()
	{
		//更改push状态
		if(isset($this->mainInfo->input_type)){
			if($this->mainInfo->push_id)
			{
				$this->mainInfo->push->input_status=0;
				$this->mainInfo->push->update();
			}
		}
		
		
		//新增作废日志
	}
	
	/*******子类独有方法*********/
	/*
	 * 船舱入库真实入库
	 */
	public function relStore($data)
	{
		$transaction=Yii::app()->db->beginTransaction();
		try{
		//修改真实库存(与船舱记录比较)
		//修改关联采购单的已入库量
		$olddata=$this->mainInfo;
		$oldJson=$olddata->datatoJson();
		$this->mainInfo->input_type="purchase";
		$this->mainInfo->input_date=strtotime($data['input_date']);
		$this->mainInfo->update();
		
		$mainJson = $this->mainInfo->datatoJson();
		$dataArray = array("tableName"=>"FrmInput","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$this->dataLog($dataArray);
		
		$totalA=0;
		$totalW=0;
		foreach ($data['data'] as $each)
		{
			$inputdetail=InputDetail::model()->with('storage')->findByPk($each->id);
			//修改采购明细
			$purchaseDetail=$inputdetail->purchaseDetail;
			$olddata=$purchaseDetail;
			$oldJson=$olddata->datatoJson();
			$purchaseDetail->input_amount+=($each->input_amount-$inputdetail->input_amount);
			$purchaseDetail->input_weight+=($each->input_weight-$inputdetail->input_weight);
			$purchaseDetail->update();

			//修改入库计划已入库量
			$plandetail=$inputdetail->pushdetail->plandetail;//关联关系
			if($plandetail)
			{
				$plandetail->real_amount+=($each->input_amount-$inputdetail->input_amount);
				$plandetail->real_weight+=($each->input_weight-$inputdetail->input_weight);
				$plandetail->update();
			}
			//rizhi
			$mainJson = $purchaseDetail->datatoJson();
			$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			$olddata=$inputdetail;
			$oldJson=$olddata->datatoJson();
			$totalA+=($each->input_amount-$inputdetail->input_amount);
			$totalW+=($each->input_weight-$inputdetail->input_weight);
			//修改入库明细			
			$inputdetail->input_amount=$each->input_amount;
			$inputdetail->input_weight=$each->input_weight;
			$inputdetail->card_id=$each->card_id;
			$inputdetail->cost_price=$each->cost_price;
			$inputdetail->update();
			
			$mainJson = $inputdetail->datatoJson();
			$dataArray = array("tableName"=>"InputDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			//修改库存
			$storage=$inputdetail->storage;
			$olddata=$storage;
			$oldJson=$olddata->datatoJson();
			$storage->left_amount=$each->input_amount-($storage->input_amount-$storage->left_amount);
			$storage->left_weight=$each->input_weight-($storage->input_weight-$storage->left_weight);
			$storage->input_amount=$each->input_amount;
			$storage->input_weight=$each->input_weight;
			$storage->cost_price=$each->cost_price;
			$storage->card_no=$each->card_id;
			$storage->input_date=strtotime($data['input_date']);
			$storage->pre_input_date=0;
			$storage->update();
			
			$mainJson = $storage->datatoJson();
			$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			//修改库存日志
			$da['storage_id']=$storage->id;
			$da['change_type']='船舱转入库';
			$da['change_amount']=$each->input_amount;
			$da['change_weight']=$each->input_weight;
			$da['order_id']=$this->mainInfo->id;
			StorageChangeLog::createNew($da);
			
			
			//查找merge_storage原船舱
			$merge_cc=MergeStorage::model()->findByAttributes(array('product_id'=>$inputdetail->product_id,'brand_id'=>$inputdetail->brand_id,'texture_id'=>$inputdetail->texture_id,'rank_id'=>$inputdetail->rank_id,'length'=>$inputdetail->length,'title_id'=>$storage->title_id,'warehouse_id'=>$this->mainInfo->warehouse_id,'is_transit'=>'1','storage_id'=>$storage->id,'is_deleted'=>'0'));
						
			//复制库存到merge_storage
			$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$inputdetail->product_id,'brand_id'=>$inputdetail->brand_id,'texture_id'=>$inputdetail->texture_id,'rank_id'=>$inputdetail->rank_id,'length'=>$inputdetail->length,'title_id'=>$merge_cc->title_id,'warehouse_id'=>$this->mainInfo->warehouse_id,'is_deleted'=>'0','is_transit'=>'0'));
			if($merge)
			{//累加
				$merge->input_amount+=$each->input_amount;
				$merge->input_weight+=$each->input_weight;
				$merge->left_amount+=$each->input_amount-($merge_cc->input_amount-$merge_cc->left_amount);
				$merge->left_weight+=$each->input_weight-($merge_cc->input_weight-$merge_cc->left_weight);
				$merge->retain_amount+=$merge_cc->retain_amount;
				$merge->retain_weight+=$merge_cc->retain_weight;
				$merge->lock_amount+=$merge_cc->lock_amount;
				$merge->lock_weight+=$merge_cc->lock_weight;
				$merge->last_update=time();
// 				$merge->update();
				
				$sql="update merge_storage set input_amount=input_amount+{$each->input_amount},input_weight=input_weight+{$each->input_weight},
							left_amount=left_amount+({$each->input_amount}+{$merge_cc->left_amount}-{$merge_cc->input_amount}),
							left_weight=left_weight+({$each->input_weight}+{$merge_cc->left_weight}-{$merge_cc->input_weight}),
							retain_amount=retain_amount+{$merge_cc->retain_amount},retain_weight=retain_weight+{$merge_cc->retain_weight},
							lock_amount=lock_amount+{$merge_cc->lock_amount},lock_weight=lock_weight+{$merge_cc->lock_weight},
							last_update=".time()." where id=".$merge->id;
				$connection=Yii::app()->db;
				$connection->createCommand($sql)->execute();				
			}else{
				//新建一条
				$merge=new MergeStorage();
				$merge->unsetAttributes();
				$merge->product_id=$merge_cc->product_id;
				$merge->brand_id=$merge_cc->brand_id;
				$merge->texture_id=$merge_cc->texture_id;
				$merge->rank_id=$merge_cc->rank_id;
				$merge->length=$merge_cc->length;
				$merge->status='normal';
				$merge->cost_price=$merge_cc->cost_price;
				$merge->title_id=$merge_cc->title_id;
				$merge->redeem_company_id=$merge_cc->redeem_company_id;//托盘公司
				$merge->input_amount=$each->input_amount;
				$merge->input_weight=$each->input_weight;
				$merge->left_amount=$each->input_amount-($merge_cc->input_amount-$merge_cc->left_amount);
				$merge->left_weight=$each->input_weight-($merge_cc->input_weight-$merge_cc->left_weight);
				$merge->retain_amount=$merge_cc->retain_amount;//保留件数
				$merge->retain_weight=$merge_cc->retain_weight;
				$merge->lock_amount=$merge_cc->lock_amount;//锁定件数
				$merge->lock_weight=$merge_cc->lock_weight?$merge_cc->lock_weight:0;
				$merge->is_transit=0;//是否船舱
				$merge->pre_input_date=0;//船舱入库预计到货时间
				$merge->storage_id=0;//船舱入库对应库存表
				$merge->warehouse_id=$merge_cc->warehouse_id;//仓库id
				$merge->invoice_price=$merge_cc->invoice_price;//票价成本
				$merge->is_deleted=0;
				$merge->last_update=time();
				$merge->insert();
			}
			$merge_cc->is_deleted='1';
			$merge_cc->update();			
			
			FrmSales::setSSDetails($merge_cc->id,$merge->id);
		}
		//修改采购单
		$purchase=$this->mainInfo->baseform_pur->purchase;
		$olddata=$purchase;
		$oldJson=$olddata->datatoJson();
		$purchase->input_amount+=$totalA;
		$purchase->input_weight+=$totalW;
		$purchase->update();
		
		$mainJson = $purchase->datatoJson();
		$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$this->dataLog($dataArray);
		
		//操作日志
		$operation = "船舱入库真实入库";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);

		$transaction->commit();
		}
		catch(Exception $e){
			$transaction->rollBack();
			return '数据库错误';
		}
		
	}
	
	
	/*
	 * 通过仓库推送信息进行船舱入库真实入库
	 */
	public static function relStoreByPush($plan_id,$push_id)
	{
		
		$that=new baseform();
		$plan=FrmInputPlan::model()->with('inputDetailsPlan','frmInput','frmInput.inputDetails')->findByPk($plan_id);
		$push=PushedStorage::model()->with('pushedStorageDetails')->findByPk($push_id);
		if($plan->frmInput->input_status==0)
		{			
			return '请先入库船舱入库单';
		}
		if($plan&&$push)
		{
			$transaction=Yii::app()->db->beginTransaction();
			try{
			//修改真实库存(与船舱记录比较)
			//修改关联采购单的已入库量
			$olddata=$plan->frmInput;
			$oldJson=$olddata->datatoJson();
			$plan->frmInput->input_type="purchase";
			$plan->frmInput->input_date=$push->created_at;
			$plan->frmInput->update();
				
			$mainJson = $plan->frmInput->datatoJson();
			$dataArray = array("tableName"=>"FrmInput","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$that->dataLog($dataArray);
				
			$totalA=0;
			$totalW=0;
			foreach ($push->pushedStorageDetails as $each)
			{
				
				$inputDetailss=$plan->frmInput->inputDetails;
				foreach ($inputDetailss as $input_de) {
					if($input_de->purchase_detail_id==$each->plandetail->purchase_detail_id)
					{
						$inputdetail=$input_de;
						break;
					}
					
				}
				//$inputdetail=InputDetail::model()->with('storage')->findByPk($each->plandetail->purchase_detail_id);
				//修改采购明细
				$purchaseDetail=$inputdetail->purchaseDetail;
				$olddata=$purchaseDetail;
				$oldJson=$olddata->datatoJson();
				$purchaseDetail->input_amount+=($each->amount-$inputdetail->input_amount);
				$purchaseDetail->input_weight+=($each->weight-$inputdetail->input_weight);
				$purchaseDetail->update();
					
				//修改入库计划已入库量
				$plandetail=$each->plandetail;//关联关系
				if($plandetail)
				{
					$plandetail->real_amount+=($each->amount-$inputdetail->input_amount);
					$plandetail->real_weight+=($each->weight-$inputdetail->input_weight);
					$plandetail->update();
				}
					
				//rizhi
				$mainJson = $purchaseDetail->datatoJson();
				$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$that->dataLog($dataArray);
	
				$olddata=$inputdetail;
				$oldJson=$olddata->datatoJson();
				$totalA+=($each->amount-$inputdetail->input_amount);
				$totalW+=($each->weight-$inputdetail->input_weight);
				//修改入库明细
				$inputdetail->input_amount=$each->amount;
				$inputdetail->input_weight=$each->weight;
				$inputdetail->card_id=$each->card_no;
				$inputdetail->push_detail_id=$each->id;
				$inputdetail->update();
					
				$mainJson = $inputdetail->datatoJson();
				$dataArray = array("tableName"=>"InputDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$that->dataLog($dataArray);
					
				//修改库存
				$storage=$inputdetail->storage;
				
				$olddata=$storage;
				$oldJson=$olddata->datatoJson();
				$storage->left_amount=$each->amount-($storage->input_amount-$storage->left_amount);
				$storage->left_weight=$each->weight-($storage->input_weight-$storage->left_weight);
				$storage->input_amount=$each->amount;
				$storage->input_weight=$each->weight;
				$storage->card_no=$each->card_no;
				$storage->input_date=$push->created_at;
				$storage->pre_input_date=0;
// 				$storage->cost_price=$each->cost_price;
				$storage->update();
					
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$that->dataLog($dataArray);
					
				//修改库存日志
				$da['storage_id']=$storage->id;
				$da['change_type']='船舱转入库';
				$da['change_amount']=$each->amount;
				$da['change_weight']=$each->weight;
				$da['order_id']=$plan->frmInput->id;
				StorageChangeLog::createNew($da);
				
				//查找merge_storage原船舱
				$merge_cc=MergeStorage::model()->findByAttributes(array('product_id'=>$inputdetail->product_id,'brand_id'=>$inputdetail->brand_id,'texture_id'=>$inputdetail->texture_id,'rank_id'=>$inputdetail->rank_id,'length'=>$inputdetail->length,'title_id'=>$storage->title_id,'warehouse_id'=>$plan->frmInput->warehouse_id,'is_transit'=>'1','storage_id'=>$storage->id,'is_deleted'=>'0'));
				if(!$merge_cc)throw new CException('已找不到原船舱入库信息');
				//复制库存到merge_storage
				$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$inputdetail->product_id,'brand_id'=>$inputdetail->brand_id,'texture_id'=>$inputdetail->texture_id,'rank_id'=>$inputdetail->rank_id,'length'=>$inputdetail->length,'title_id'=>$merge_cc->title_id,'warehouse_id'=>$plan->frmInput->warehouse_id,'is_deleted'=>'0','is_transit'=>'0'));
				if($merge)
				{//累加
					$merge->input_amount+=$each->amount;
					$merge->input_weight+=$each->weight;
					$merge->left_amount+=$each->amount-($merge_cc->input_amount-$merge_cc->left_amount);
					$merge->left_weight+=$each->weight-($merge_cc->input_weight-$merge_cc->left_weight);
					$merge->retain_amount+=$merge_cc->retain_amount;
					$merge->retain_weight+=$merge_cc->retain_weight;
					$merge->lock_amount+=$merge_cc->lock_amount;
					$merge->lock_weight+=$merge_cc->lock_weight;
					$merge->last_update=time();
// 					$merge->update();
					
					$sql="update merge_storage set input_amount=input_amount+{$each->amount},input_weight=input_weight+{$each->weight},
					left_amount=left_amount+({$each->amount}+{$merge_cc->left_amount}-{$merge_cc->input_amount}),
					left_weight=left_weight+({$each->weight}+{$merge_cc->left_weight}-{$merge_cc->input_weight}),
					retain_amount=retain_amount+{$merge_cc->retain_amount},retain_weight=retain_weight+{$merge_cc->retain_weight},
					lock_amount=lock_amount+{$merge_cc->lock_amount},lock_weight=lock_weight+{$merge_cc->lock_weight},
					last_update=".time()." where id=".$merge->id;
					$connection=Yii::app()->db;
					$connection->createCommand($sql)->execute();
					
				}else{
					//新建一条
					$merge=new MergeStorage();
					$merge->unsetAttributes();
					$merge->product_id=$merge_cc->product_id;
					$merge->brand_id=$merge_cc->brand_id;
					$merge->texture_id=$merge_cc->texture_id;
					$merge->rank_id=$merge_cc->rank_id;
					$merge->length=$merge_cc->length;
					$merge->status='normal';
					$merge->cost_price=$merge_cc->cost_price;
					$merge->title_id=$merge_cc->title_id;
					$merge->redeem_company_id=$merge_cc->redeem_company_id;//托盘公司
					$merge->input_amount=$each->amount;
					$merge->input_weight=$each->weight;
					$merge->left_amount=$each->amount-($merge_cc->input_amount-$merge_cc->left_amount);
					$merge->left_weight=$each->weight-($merge_cc->input_weight-$merge_cc->left_weight);
					$merge->retain_amount=$merge_cc->retain_amount;//保留件数
					$merge->retain_weight=$merge_cc->retain_weight;
					$merge->lock_amount=$merge_cc->lock_amount;//锁定件数
					$merge->lock_weight=$merge_cc->lock_weight;
					$merge->is_transit=0;//是否船舱
					$merge->pre_input_date=0;//船舱入库预计到货时间
					$merge->storage_id=0;//船舱入库对应库存表
					$merge->warehouse_id=$merge_cc->warehouse_id;//仓库id
					$merge->invoice_price=$merge_cc->invoice_price;//票价成本
					$merge->is_deleted=0;
					$merge->last_update=time();
					$merge->insert();
				}
				$merge_cc->is_deleted='1';
				$merge_cc->update();	
				
				FrmSales::setSSDetails($merge_cc->id,$merge->id);


				//修改入库计划已入库量
				$plandetail=$each->plandetail;//关联关系
				if($plandetail)
				{
					$plandetail->real_amount+=$each->amount;
					$plandetail->real_weight+=$each->weight;
					$plandetail->update();
				}
				
				
			}
			//修改采购单
			$purchase=$plan->basepurchase->purchase;
			$olddata=$purchase;
			$oldJson=$olddata->datatoJson();
			$purchase->input_amount+=$totalA;
			$purchase->input_weight+=$totalW;
			$purchase->update();
				
			$mainJson = $purchase->datatoJson();
			$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$that->dataLog($dataArray);
			
			//修改推送数据状态
			$push->input_status=1;			
			$push->update();
	
			//操作日志
			$operation = "船舱入库真实入库";
			$that->operationLog($that->busName, $operation,$plan->frmInput->baseform->form_sn);			
			$transaction->commit();
			}
			catch(Exception $e){
				$transaction->rollBack();			
				if($e->message=="已找不到原船舱入库信息")	return $e->message;
				else	return '数据库错误';
			}
			return true;
		}
		return '未找到相关信息，入库失败';
	}
// 	/*
// 	 * 船舱入库取消真实入库
// 	 */
// 	public function cancelRelStore()
// 	{
// 		//变更入库类型
// 		$this->mainInfo->input_type="ccrk";
// 		$this->mainInfo->update();
// 		$total_w=0;
// 		$total_a=0;
// 		foreach ($this->details as $each)
// 		{
// 			$storage=$each->storage;
// 			$storageChangeLog=StorageChangeLog::model()->findByAttributes(array('storage_id'=>$storage->id,'change_type'=>'入库','order_id'=>$each->input_id));
			
// 			//修改采购明细
// 			$purchaseDetail=$each->purchaseDetail;
// 			$purchaseDetail->input_amount-=($each->input_amount-$storageChangeLog->change_amount);
// 			$purchaseDetail->input_weight-=($each->input_weight-$storageChangeLog->change_weight);
// 			$purchaseDetail->update();			
// 			$tatal_a+=($each->input_amount-$storageChangeLog->change_amount);
// 			$total_w+=($each->input_weight-$storageChangeLog->change_weight);
			
// 			//修改库存
// 			$left_a=$storage->left_amount-$each->input_amount+$storageChangeLog->change_amount;
// 			if($left_a<0)	return 0;
// 			$left_w=$storage->left_weight-$each->input_weight+$storageChangeLog->change_weight;
// 			$storage->left_amount=$left_a;
// 			$storage->left_weight=$left_w;
// 			$storage->input_amount=$storageChangeLog->change_amount;
// 			$storage->input_weight=$storageChangeLog->change_weight;
// // 			$storage->cost_price=$each->cost_price;
// 			$storage->update();
// 			//修改库存日志
// 			$da['storage_id']=$storage->id;
// 			$da['change_type']='取消真实入库';
// 			$da['change_amount']=$each->input_amount;
// 			$da['change_weight']=$each->input_weight;
// 			$da['order_id']=$this->mainInfo->id;
// 			StorageChangeLog::createNew($da);
			
// 			//开票明细
// 			$invoice=DetailForInvoice::model()->findByAttributes(array('detail_id'=>$each->purchase_detail_id,'form_id'=>$each->purchaseDetail->frmPurchase->id));
// 			if($invoice)
// 			{
// 				$invoice->weight=0;
// 				$invoice->money=0;
// 			}
// 			//入库明细
// 			$each->input_amount=$storageChangeLog->change_amount;
// 			$each->input_weight=$storageChangeLog->change_weight;
// 			// 			$each->card_id=$each->card_id;//卡号无法变更
// 			// 			$each->cost_price=$each->cost_price;//价格无法变更
// 			$each->update();
// 		}
// 		//修改采购单
// 		$purchase=$this->mainInfo->baseform_pur->purchase;
// 		$purchase->input_amount-=$total_a;
// 		$purchase->input_weight-=$total_w;
// 		$purchase->update();
// 		//操作日志
// 		$operation = "取消船舱入库真实入库";
// 		$this->operationLog($this->busName, $operation);
// 	}
	
	/****------------------------基类方法重构之审核表单----------------****/
	/**
	 * 审核通过后续操作
	 */
	protected function afterApproveForm()
	{
		//插入修改库存记录//插入库存日志
		if(isset($this->mainInfo->input_type))
		{//非代销
			$totalA=0;
			$totalW=0;
			$purchase=$this->mainInfo->baseform_pur->purchase;
			foreach ($this->details as $each)
			{
				
				$data=array();
				$data['card_no']=$each->card_id;//卡号
				$data['input_detail_id']=$each->id;
				$data['card_status']='normal';  //状态
				$data['title_id']=$purchase->title_id;//销售公司
				$data['redeem_company_id']=$purchase->purchase_type=='tpcg'?$purchase->pledge->pledge_company_id:0;//托盘公司
				$data['input_amount']=$each->input_amount;
				$data['input_weight']=$each->input_weight;
				$data['left_amount']=$each->input_amount;
				$data['left_weight']=$each->input_weight;//剩余重量
				$data['retain_amount']=$each->remain_amount?$each->remain_amount:0;//保留件数
				$data['retain_weight']=$each->remain_weight?$each->remain_weight:0;//保留重量
				$data['lock_amount']=0;//锁定件数
				$data['input_date']=$each->input->input_date;
				$data['pre_input_date']=$data['input_date'];//预计到货日期
				$data['input_time']=$this->mainInfo->input_time;
				
				$data['frm_input_id']=$this->mainInfo->id;
				$data['cost_price']=$each->cost_price;
				$data['is_price_confirmed']='';//是否采购单价已确定
				$data['invoice_price']=$each->purchaseDetail->invoice_price;//采购发票成本
				$data['is_yidan']=$purchase->is_yidan;
				$data['is_pledge']=$purchase->purchase_type=='tpcg'?'1':'0';
				$data['purchase_id']=$purchase->id;//采购单主体信息id
				$data['is_dx']=$purchase->purchase_type=='dxcg'?'1':'0';//是否代销
				$data['warehouse_id']=$this->mainInfo->warehouse_id;
				$storage=Storage::createNew($data);
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				if($storage)
				{//日志
					$da['storage_id']=$storage->id;
					$da['change_type']='入库';
					$da['change_amount']=$each->input_amount;
					$da['change_weight']=$each->input_weight;
					$da['order_id']=$each->input->id;
					StorageChangeLog::createNew($da);
				}
				//修改采购单或销售退货单已入库量
				$olddata=$each->purchaseDetail;
				$oldJson=$olddata->datatoJson();
				$each->purchaseDetail->input_amount+=$each->input_amount;
				$each->purchaseDetail->input_weight+=$each->input_weight;
				$each->purchaseDetail->update();
				$mainJson = $each->purchaseDetail->datatoJson();
				$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				//复制库存到merge_storage
				$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$each->product_id,'brand_id'=>$each->brand_id,'texture_id'=>$each->texture_id,'rank_id'=>$each->rank_id,'length'=>$each->length,'title_id'=>$data['title_id'],'warehouse_id'=>$this->mainInfo->warehouse_id,'is_deleted'=>'0','is_transit'=>'0'));
				if($merge&&$this->mainInfo->input_type!='ccrk')
				{//累加
					$merge->input_amount+=$each->input_amount;
					$merge->input_weight+=$each->input_weight;
					$merge->left_amount+=$each->input_amount;
					$merge->left_weight+=$each->input_weight;
					$merge->retain_amount+=$each->remain_amount;
					$merge->retain_weight+=$each->remain_weight;
					$merge->last_update=time();
// 					$merge->update();
					
					$sql="update merge_storage set input_amount=input_amount+{$each->input_amount},input_weight=input_weight+{$each->input_weight},
					left_amount=left_amount+{$each->input_amount},left_weight=left_weight+{$each->input_weight},
					retain_amount=retain_amount+".intval($each->remain_amount).",retain_weight=retain_weight+{$each->remain_weight},					
					last_update=".time()." where id=".$merge->id;
					$connection=Yii::app()->db;
					$connection->createCommand($sql)->execute();
					
				}else{
					//新建一条
					$merge=new MergeStorage();
					$merge->unsetAttributes();
					$merge->product_id=$each->product_id;
					$merge->brand_id=$each->brand_id;
					$merge->texture_id=$each->texture_id;
					$merge->rank_id=$each->rank_id;
					$merge->length=$each->length;
					$merge->status='normal';
					$merge->cost_price=$each->cost_price;
					$merge->title_id=$data['title_id'];
					$merge->redeem_company_id=$data['redeem_company_id'];//托盘公司
					$merge->input_amount=$each->input_amount;
					$merge->input_weight=$each->input_weight;
					$merge->left_amount=$each->input_amount;
					$merge->left_weight=$each->input_weight;
					$merge->retain_amount=$each->remain_amount?$each->remain_amount:0;//保留件数
					$merge->retain_weight=$each->remain_weight?$each->remain_weight:0;//保留重量
					$merge->lock_amount=0;//锁定件数
					$merge->lock_weight=0;//锁定重量
					if($this->mainInfo->input_type=='ccrk')
					{
						$merge->is_transit=1;//是否船舱
						$merge->pre_input_date=$this->mainInfo->input_date;//船舱入库预计到货时间
						$merge->pre_input_time=$this->mainInfo->input_time;
						$merge->storage_id=$storage->id;//船舱入库对应库存表
					}else{
						$merge->is_transit=0;//是否船舱
						$merge->pre_input_date=0;//船舱入库预计到货时间
						$merge->pre_input_time=0;
						$merge->storage_id=0;//船舱入库对应库存表
					}					
					$merge->warehouse_id=$this->mainInfo->warehouse_id;//仓库id
					$merge->invoice_price=$data['invoice_price'];//票价成本
					$merge->is_deleted=0;
					$merge->last_update=time();
					$merge->insert();
				}
				//修改入库计划已入库量
				$plandetail=$each->pushdetail->plandetail;//关联关系
				if($plandetail)
				{
					$plandetail->real_amount+=$each->input_amount;
					$plandetail->real_weight+=$each->input_weight;
					$plandetail->update();
				}
				$totalA+=$each->input_amount;
				$totalW+=$each->input_weight;
			}
// 			$purchase=$this->mainInfo->baseform_pur->purchase;
			$olddata=$purchase;
			$oldJson=$olddata->datatoJson();
			$purchase->input_amount+=$totalA;
			$purchase->input_weight+=$totalW;
			$purchase->update();
			
			$mainJson = $purchase->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			$this->mainInfo->input_status=1;
			$this->mainInfo->input_at=time();
			$this->mainInfo->input_by=currentUserId();
			$this->mainInfo->update();
// 			if($this->mainInfo->push)
// 			{
// 				$this->mainInfo->push->input_status=1;
// 				$this->mainInfo->push->update();
// 			}
			
		}else{
			//代销
			foreach ($this->details as $each)
			{
				$data['card_no']=$each->card_id;//卡号
				$data['input_detail_id']=$each->id;
				$data['card_status']='normal';  //状态
				$data['title_id']=$each->input->title_id;//销售公司
				$data['redeem_company_id']='';//托盘公司
				$data['input_amount']=$each->input_amount;
				$data['input_weight']=$each->input_weight;
				$data['left_amount']=$each->input_amount;
				$data['left_weight']=$each->input_weight;//剩余重量
				$data['retain_amount']=0;//保留件数
				$data['lock_amount']=0;//锁定件数
				$data['input_date']=strtotime($each->input->baseform->form_time);
				$data['pre_input_date']=$data['input_date'];//预计到货日期
				$data['frm_input_id']=$this->mainInfo->id;
				$data['cost_price']=0;
				$data['is_price_confirmed']='';//是否采购单价已确定
				$data['invoice_price']=0;//采购发票成本
				$data['is_yidan']=0;
				$data['is_pledge']=0;
				$data['is_dx']=1;//是否代销
				$data['warehouse_id']=$this->mainInfo->warehouse_id;
				$storage=Storage::createNew($data);
				
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				
				if($storage)
				{//日志
					$da['storage_id']=$storage->id;
					$da['change_type']='入库';
					$da['change_amount']=$each->input_amount;
					$da['change_weight']=$each->input_weight;
					$da['order_id']=$this->mainInfo->id;
					StorageChangeLog::createNew($da);
				}
			}
		}
	
		//发送消息通知
		
		//新增审核通过日志
	}
	
	/**
	 * 表单拒绝后续操作
	 */
	public function afterRefuseForm()
	{
		//发送消息通知
		
		//新增审核拒绝日志
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
			$olddata=$this->commonForm;
			$this->commonForm->form_status = 'submited';
			$this->commonForm->approved_at = 0;
			$this->commonForm->approved_by = 0;
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
				
			$commonJson = $this->commonForm->datatoJson();
			$oldJson=$olddata->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
				
			$result=$this->afterCancelApproveForm();
			if($result==='sale'||$result==='bill')
			{
				return $result;
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();
			return;
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
		//库存回滚
		
		if(isset($this->mainInfo->input_type))
		{
			$totalA=0;
			$totalW=0;
			foreach ($this->details as $detail)
			{
				$storage=$detail->storage;
				if($storage->lock_amount>0||$storage->input_amount!=$storage->left_amount)
				{
					return 'sale';
				}				
				$olddata=$storage;
				$oldJson=$olddata->datatoJson();
				$storage->card_status='deleted';
				$storage->is_deleted=1;
				$storage->update();
				
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				
				//日志
				$da['storage_id']=$storage->id;
				$da['change_type']='入库';
				$da['change_amount']=-$storage->input_amount;
				$da['change_weight']=-$storage->input_weight;
				$da['order_id']=$storage->frm_input_id;
				StorageChangeLog::createNew($da);
					
				//修改采购单或销售退货单的已入库数量
				$olddata=$detail->purchaseDetail;
				$oldJson=$olddata->datatoJson();
				$detail->purchaseDetail->input_amount-=$storage->input_amount;
				$detail->purchaseDetail->input_weight-=$storage->input_weight;
				$detail->purchaseDetail->update();
				
				$mainJson = $detail->purchaseDetail->datatoJson();
				$dataArray = array("tableName"=>"purchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				
				
				
				//回滚merge_storage
				if($this->mainInfo->input_type=='ccrk')
				{
					$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$detail->product_id,'brand_id'=>$detail->brand_id,'texture_id'=>$detail->texture_id,'rank_id'=>$detail->rank_id,'length'=>$detail->length,'title_id'=>$storage->title_id,'warehouse_id'=>$this->mainInfo->warehouse_id,'is_transit'=>'1','storage_id'=>$storage->id,'is_deleted'=>'0'));
					if($merge)
					{//减
						$merge->input_amount-=$detail->input_amount;
						$merge->input_weight-=$detail->input_weight;
						$merge->left_amount-=$detail->input_amount;
						$merge->left_weight-=$detail->input_weight;
						$merge->retain_amount-=$detail->remain_amount;
						if($merge->left_amount<($merge->retain_amount+$merge->lock_amount))
						{
							return 'sale';
						}
						$merge->is_deleted=1;
						$merge->update();
					}
				}else{
					$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$detail->product_id,'brand_id'=>$detail->brand_id,'texture_id'=>$detail->texture_id,'rank_id'=>$detail->rank_id,'length'=>$detail->length,'title_id'=>$storage->title_id,'warehouse_id'=>$this->mainInfo->warehouse_id,'is_deleted'=>'0','is_transit'=>'0'));
					if($merge)
					{//减
						$merge->input_amount-=$detail->input_amount;
						$merge->input_weight-=$detail->input_weight;
						$merge->left_amount-=$detail->input_amount;
						$merge->left_weight-=$detail->input_weight;
						$merge->retain_amount-=$detail->remain_amount;
						$merge->retain_weight-=$detail->remain_weight;
						if($merge->left_amount<($merge->retain_amount+$merge->lock_amount))
						{
							return 'sale';
						}
						$merge->last_update=time();
// 						$merge->update();
						
						$sql="update merge_storage set input_amount=input_amount-{$detail->input_amount},input_weight=input_weight-{$detail->input_weight},
						left_amount=left_amount-{$detail->input_amount},left_weight=left_weight-{$detail->input_weight},
						retain_amount=retain_amount-".intval($detail->remain_amount).",retain_weight=retain_weight-{$detail->remain_weight},						
						last_update=".time()." where id=".$merge->id;
						$connection=Yii::app()->db;
						$connection->createCommand($sql)->execute();
						
					}
				}			
				
				//修改入库计划已入库量
				$plandetail=$storage->inputDetail->pushdetail->plandetail;//关联关系
				if($plandetail)
				{
					$plandetail->real_amount-=$storage->input_amount;
					$plandetail->real_weight-=$storage->input_weight;
					$plandetail->update();
				}
				$totalA+=$storage->input_amount;
				$totalW+=$storage->input_weight;
			}
			$purchase=$this->mainInfo->baseform_pur->purchase;
			$olddata=$purchase;
			$oldJson=$olddata->datatoJson();
			$purchase->input_amount-=$totalA;
			$purchase->input_weight-=$totalW;
			$purchase->update();
			
			$mainJson = $purchase->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			$this->mainInfo->input_status=0;
			$this->mainInfo->input_at=0;
			$this->mainInfo->input_by=0;
			$this->mainInfo->update();
			//修改推送信息状态
// 			if($this->mainInfo->push)
// 			{
// 				$this->mainInfo->push->frm_input_id=0;
// 				$this->mainInfo->push->input_status=0;
// 				$this->mainInfo->push->update();
// 			}
		}else{
			//代销
			$storages=$this->mainInfo->storages;
			foreach ($storages as $each)
			{
				$olddata=$each;
				$oldJson=$olddata->datatoJson();
				$each->card_status='deleted';
				$each->is_deleted=1;
				$each->update();
				
				$mainJson = $each->datatoJson();
				$dataArray = array("tableName"=>"Storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				
				//日志
				$da['storage_id']=$each->id;
				$da['change_type']='入库';
				$da['change_amount']=-$each->input_amount;
				$da['change_weight']=-$each->input_weight;
				$da['order_id']=$each->frm_input_id;
				StorageChangeLog::createNew($da);
			}
		}		
		return true;
		//新增取消日志
	}
	
	
}