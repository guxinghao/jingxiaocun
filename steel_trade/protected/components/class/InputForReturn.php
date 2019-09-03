<?php
class InputForReturn extends BaseForm
{
	public $mainModel = "FrmInput";
	public $detailModel = "InputDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="入库";
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('input','input.inputDetails')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->input;
				$this->details=$model->input->inputDetails;
			}
		}
	}
	
	/*****--------------------------基类方法重构---------------------------------******/
	
	/*
	 * 创建保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo=new FrmInput();
		$mainInfo->input_type=$data->input_type;//入库类型
		$mainInfo->purchase_id=$data->purchase_id;//采购单id
		$mainInfo->input_date=strtotime($data->input_date);//预计入库时间
		$mainInfo->input_time=$data->input_time;
		$mainInfo->warehouse_id=$data->warehouse_id;//仓库
		$mainInfo->input_status=$data->input_status;//入库状态
		$mainInfo->push_id=$data->push_id;//仓库推送信息
		$mainInfo->from=$data->from;//创建入库单来源	
		$mainInfo->plan_id=$data->plan_id;		
		if($mainInfo->insert()){
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmInput","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
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
			$input->remain_amount=0;
			$input->from='return';
			if($input->insert())
			{
				//日志
				$detailJson = $input->datatoJson();
				$dataArray = array("tableName"=>"InputDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($detail,$input);
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
			$this->mainInfo->purchase_id=$data->purchase_id;
			$this->mainInfo->input_date=strtotime($data->input_date);
			$this->mainInfo->input_time=$data->input_time;
			$this->mainInfo->warehouse_id=$data->warehouse_id;
		}elseif($this->commonForm->form_status=='approve'){
			$this->mainInfo->input_date=strtotime($data->input_date);
			$this->mainInfo->input_time=$data->input_time;
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
					$input->remain_amount=0;
					$input->from='return';
					$input->insert();
					
					$mainJson = $input->datatoJson();
					$dataArray = array("tableName"=>"InputDetail","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);
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
		//新增修改日志
	}
	
	/****------------------------基类方法重构之作废表单----------------****/
	/**
	 * 作废后的操作
	 */
	protected function afterDeleteForm()
	{
		
		//新增作废日志
	}
	
	/****------------------------基类方法重构之审核表单----------------****/
	/**
	 * 审核通过后续操作
	 */
	protected function afterApproveForm()
	{
		
		//插入修改库存记录//插入库存日志
			$totalA=0;
			$totalW=0;
			$arr=array();
			foreach ($this->details as $each)
			{
				$data['card_no']=$each->card_id;//卡号
				$data['input_detail_id']=$each->id;
				$data['card_status']='normal';  //状态
				$data['title_id']=$each->input->baseform_pur->salesReturn->title_id;//销售公司
				$data['redeem_company_id']=0;//托盘公司
				$data['input_amount']=$each->input_amount;
				$data['input_weight']=$each->input_weight;
				$data['left_amount']=$each->input_amount;
				$data['left_weight']=$each->input_weight;//剩余重量
				$data['retain_amount']=$each->remain_amount?$each->remain_amount:0;//保留件数
				$data['lock_amount']=0;//锁定件数
				$data['input_date']=$each->input->input_date;
				$data['pre_input_date']=$data['input_date'];//预计到货日期
				$data['input_time']=$this->mainInfo->input_time;
				
				$data['frm_input_id']=$this->mainInfo->id;
				$data['cost_price']=$each->cost_price;
				$data['is_price_confirmed']='';//是否采购单价已确定
				$data['invoice_price']=0;//采购发票成本
				$data['is_yidan']=$each->input->baseform_pur->salesReturn->is_yidan;
				$data['is_pledge']='0';
				$data['is_dx']='0';//是否代销
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
				//修改销售退货单已入库量
				$olddata=$each->returnDetail;
				$oldJson=$olddata->datatoJson();
				$each->returnDetail->input_amount+=$each->input_amount;
				$each->returnDetail->input_weight+=$each->input_weight;
				$each->returnDetail->update();

				/*
				$reD = $each->returnDetail;
				$std = DictGoodsProperty::getFullProName($reD->product_id);
				if($std != "螺纹钢" && $reD->salesReturn->is_yidan == 0){
					//设置可开票明细
					$price = 0 - $reD->return_price * $each->input_weight;
					$invoice = DetailForInvoice::setSalesInvoice($reD->salesReturn->baseform->id,$reD->id,$each->input_weight,$price,$reD->salesReturn->title_id,$reD->salesReturn->company_id,0,1);
					if(!$invoice){
						return -1;
					}
				}
				*/
				
				if($each->returnDetail->input_amount>=$each->returnDetail->return_amount)
				{
					array_push($arr,'yes');
				}else{
					array_push($arr, 'no');
				}				
				$mainJson = $each->returnDetail->datatoJson();
				$dataArray = array("tableName"=>"saleDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
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
					$merge->last_update=time();
					$merge->update();
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
					$merge->lock_amount=0;//锁定件数
					$merge->is_transit=0;//是否船舱
					$merge->pre_input_date=0;//船舱入库预计到货时间
					$merge->pre_input_time=0;
					$merge->storage_id=0;//船舱入库对应库存表
					$merge->warehouse_id=$this->mainInfo->warehouse_id;//仓库id
					$merge->invoice_price=$data['invoice_price'];//票价成本
					$merge->is_deleted=0;
					$merge->last_update=time();
					$merge->insert();
				}
				$totalA+=$each->input_amount;
				$totalW+=$each->input_weight;
			}
			$purchase=$this->mainInfo->baseform_pur->salesReturn;
			$olddata=$purchase;
			$oldJson=$olddata->datatoJson();
			$purchase->input_amount+=$totalA;
			$purchase->input_weight+=$totalW;
			if(!in_array('no', $arr))
			{
				$purchase->flag=0;
			}
			$purchase->update();
			
			$mainJson = $purchase->datatoJson();
			$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			$this->mainInfo->input_status=1;
			$this->mainInfo->input_at=time();
			$this->mainInfo->input_by=currentUserId();
			$this->mainInfo->update();
			
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
		$totalA=0;
		$totalW=0;
		$arr=array();
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
			$olddata=$storage->inputDetail->returnDetail;
			$oldJson=$olddata->datatoJson();
			$storage->inputDetail->returnDetail->input_amount-=$storage->input_amount;
			$storage->inputDetail->returnDetail->input_weight-=$storage->input_weight;
			$storage->inputDetail->returnDetail->update();
			if($storage->inputDetail->returnDetail->input_amount>=$storage->inputDetail->returnDetail->return_amount)
			{
				array_push($arr,'yes');
			}else{
				array_push($arr, 'no');
			}

			/*
			$reD = $storage->inputDetail->returnDetail;
			$std = DictGoodsProperty::getFullProName($reD->product_id);
			if($std != "螺纹钢" && $reD->salesReturn->is_yidan == 0){
				//设置可开票明细
				$price = $storage->input_weight * $reD->return_price;
				$invoice = DetailForInvoice::setSalesInvoice($reD->salesReturn->baseform->id,$reD->id,0-$storage->input_weight,$price,$reD->salesReturn->title_id,$reD->salesReturn->company_id,0,1);
				if(!$invoice){
					return "bill";
				}
			}
			*/
			$mainJson = $storage->inputDetail->returnDetail->datatoJson();
			$dataArray = array("tableName"=>"salesReturnDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			
			
			//回滚merge_storage
			$merge=MergeStorage::model()->findByAttributes(array('product_id'=>$detail->product_id,'brand_id'=>$detail->brand_id,'texture_id'=>$detail->texture_id,'rank_id'=>$detail->rank_id,'length'=>$detail->length,'title_id'=>$storage->title_id,'warehouse_id'=>$this->mainInfo->warehouse_id,'is_deleted'=>'0','is_transit'=>'0'));
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
				$merge->last_update=time();
				$merge->update();
			}
			$totalA+=$storage->input_amount;
			$totalW+=$storage->input_weight;
		}		
		$purchase=$this->mainInfo->baseform_pur->salesReturn;
		$olddata=$purchase;
		$oldJson=$olddata->datatoJson();
		$purchase->input_amount-=$totalA;
		$purchase->input_weight-=$totalW;
		if(!in_array('no', $arr))
		{
			$purchase->flag=0;
		}else{
			$purchase->flag=1;
		}
		$purchase->update();
		
		$mainJson = $purchase->datatoJson();
		$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
		$this->dataLog($dataArray);
		
		$this->mainInfo->input_status=0;
		$this->mainInfo->input_at=0;
		$this->mainInfo->input_by=0;
		$this->mainInfo->update();
		return true;
		//新增取消日志
	}
	
	
}