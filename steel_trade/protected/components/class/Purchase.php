<?php

class Purchase extends BaseForm
{
	public $has_detials = true;//采购单有明细
	public $mainModel = "FrmPurchase";
	public $isAutoApprove = false;
	public $busName="采购单";
	
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('purchase')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->purchase;
				$this->details=$model->purchase->purchaseDetails;
			}
		}
	}
	
	//------------------------------------
	/**
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$purchase = new FrmPurchase();
		$purchase->purchase_type = $data->purchase_type;
		$purchase->supply_id = $data->supply_id;
		$purchase->title_id = $data->title_id;
		$purchase->team_id = $data->team_id;
		$purchase->is_yidan = $data->is_yidan?$data->is_yidan:0;
		$purchase->contact_id = $data->contact_id;
		$purchase->warehouse_id = $data->warehouse_id;
		$purchase->amount = $data->amount;
		$purchase->weight = $data->weight;
		$purchase->input_amount=0;
		$purchase->input_weight=0;
		$purchase->frm_contract_id = $data->frm_contract_id;
		$purchase->date_reach = strtotime($data->date_reach);
		$purchase->reach_time = $data->reach_time;
		$purchase->transfer_number=$data->transfer_number;
		$purchase->invoice_cost=$data->invoice_cost;
		$purchase->contain_cash=$data->contain_cash;
		$purchase->price_amount=$data->price_amount;
		if($data->contain_cash)
		{
			$purchase->shipment=$data->shipment ? $data->shipment : 0;
		}else{
			$purchase->shipment=0;
		}		
		if ($purchase->insert()){
			$mainJson = $purchase->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			if ($data->purchase_type != "tpcg")
			{
				return $purchase;
			}
			//保存托盘信息
			$pledgeInfo = new PledgeInfo();
			$pledgeInfo->frm_purchase_id = $purchase->id;
			$pledgeInfo->fee = numChange($data->fee);
			$pledgeInfo->begin_date = $data->begin_date;
			$pledgeInfo->pledge_company_id = $data->pledge_company_id;
			$pledgeInfo->advance = numChange( $data->advance);
			$pledgeInfo->unit_price =  numChange($data->unit_price);
			$pledgeInfo->r_limit = $data->r_limit?$data->r_limit:1;
			$pledgeInfo->pledge_length=$data->pledge_length;
			$pledgeInfo->pledge_rate=$data->pledge_rate;
			$pledgeInfo->min_rate=$data->min_rate;
			$pledgeInfo->over_rate=$data->over_rate;
			$pledgeInfo->violation_date=$data->violation_date;
			if ($pledgeInfo->insert()){
				$mainJson = $pledgeInfo->datatoJson();
				$dataArray = array("tableName"=>"PledgeInfo","newValue"=>$mainJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				
				return $purchase;
			}
		}
	} 
	
	/**
	 * 保存明细
	 */
	protected function saveDetails($data)
	{
		if (!is_array($data)&&count($data)<=0)
			return;
		$detail_array = array();
		$xml=readConfig();
		foreach ($data as $detail) {
			//获取明细 
			$purchase_detail = new PurchaseDetail();
			$purchase_detail->price = $detail->price;
			$purchase_detail->amount = $detail->amount;
			$purchase_detail->weight = $detail->weight;
			$purchase_detail->input_amount = 0;
			$purchase_detail->input_weight = 0;
			$purchase_detail->purchase_id = $this->mainInfo->id;
			$purchase_detail->fix_amount = 0;
			$purchase_detail->fix_weight = 0;
			$purchase_detail->fix_price = 0;
			$purchase_detail->cost_price =  $detail->price;
			$purchase_detail->invoice_price = $detail->invoice_price;//发票成本
			$purchase_detail->product_id = $detail->product_id;//品名std
			$purchase_detail->brand_id = $detail->brand_id;//产地
			$purchase_detail->texture_id = $detail->texture_id;//材质
			$purchase_detail->rank_id = $detail->rank_id;//规格			
			if(empty($detail->length))$purchase_detail->length =0;
			else	$purchase_detail->length = $detail->length;//长度
			
			
			if($this->mainInfo->purchase_type!='xxhj')
			{
				//修改配置
				$brand=DictGoodsProperty::model()->findByPk($detail->brand_id);
				if($brand){
					$path=Yii::app()->basePath.'/../public/config.xml';
					foreach ($xml->invoice->cost as $each)
					{
						if($each->brand==$brand->short_name)
						{
							$price=floatval($each->price);
							if($price!=$detail->invoice_price)
							{
								$each->price=$detail->invoice_price;
								$xml->asXML($path);
								//加日志
								$base=new BaseForm();
								$base->operationLog($this->busName,'修改配置','产地'.$brand->short_name.'开票成本由'.$price.'改为'.$detail->invoice_price );
							}
							break;
						}
					}
				}
			}		
			
			
			if ($purchase_detail->insert())
			{
				//日志
				$detailJson = $purchase_detail->datatoJson();
				$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($detail_array, $purchase_detail);
				//创建关联
				if($this->mainInfo->purchase_type=='dxcg')
				{
					$sales_details=json_decode($detail->sales_details_array);
					$ids=explode(',', $detail->salesdetail_ids);
					foreach ($ids as $e)
					{
						foreach ($sales_details as $each)
						{
							if($each->detail_id==$e)
							{
								$relation=new SaledetailPurchase();
								$relation->sales_detail_id=$each->detail_id;
								$relation->purchase_id=$this->mainInfo->id;
								$relation->purchase_detail_id=$purchase_detail->id;
								$relation->amount=$each->amount;
								$relation->weight=$each->weight;
								$relation->form_sn=$each->form_sn;
								$relation->good_id=$each->good_id;
								$relation->insert();
								$detailJson = $relation->datatoJson();
								$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$detailJson,"oldValue"=>"");
								$this->dataLog($dataArray);
								break;
							}
						}
					}
				}elseif($this->mainInfo->purchase_type=='xxhj'){
					//更新销售单详细的补单数量
					$relation=new SaledetailPurchase();
					$relation->sales_detail_id=$detail->id;
					$relation->purchase_id=$this->mainInfo->id;
					$relation->purchase_detail_id=$purchase_detail->id;
					$relation->amount=$detail->amount;
					$relation->weight=$detail->weight;
					$relation->insert();
					$detailJson = $relation->datatoJson();
					$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$detailJson,"oldValue"=>"");
					$this->dataLog($dataArray);
				}
			}
		}
		return $detail_array;
	}
	/**
	 * 创建表单后动作
	 */
	protected function afterCreateForm()
	{
		//采购单创建完以后需要创建对应的采购往来
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;
		
		$dg_jm_normal=$this->isDgJmPurchase();
		
		foreach ($this->details as $detail) {
			$remark='';
			$type = "CGMX";//类型
			$turnover_direction = "need_pay";//应付
			$title_id = $this->mainInfo->title_id;//公司抬头
			$target_id = $this->mainInfo->supply_id;//往来对端公司
			$client_id=$target_id;
			$proxy_company_id=$this->mainInfo->pledge->pledge_company_id;
			$amount = $detail->weight;//重量
			$price = $detail->price;//单价
			
			$brand_name=DictGoodsProperty::getProName($detail->brand_id);
			//抬头为登钢爵淼产地为贵航的采购单往来为0--2016/10/20
			if($dg_jm_normal&&($brand_name=='贵航'))
			{
				$fee=0;
				$remark='抬头为登钢爵淼产地为贵航的采购单往来为0';
			}else{
				$fee = $price*$amount;
			}			
			$common_forms_id = $this->commonForm->id;
			$form_detail_id = $detail->id;
			$ownered_by = $this->commonForm->owned_by;
			$created_by=$this->commonForm->created_by;
			$is_yidan=$this->mainInfo->is_yidan;
			$big_type='purchase';
			$created_at=strtotime($this->commonForm->form_time);
			$description='单号：'.$this->commonForm->form_sn.','.$brand_name.'|'.DictGoodsProperty::getProName($detail->product_id).'|'.DictGoodsProperty::getProName($detail->rank_id).'*'.$detail->length.'*'.DictGoodsProperty::getProName($detail->texture_id).$remark;
			$turnarray = compact("type","turnover_direction","title_id",'big_type',"target_id","client_id",
					'proxy_company_id',"amount","price","fee","common_forms_id","form_detail_id","ownered_by",
					'created_by','description','is_yidan','created_at'		
			);
			
			$result = Turnover::createBill($turnarray);
			
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			
		}
		//如果是托盘采购，需要多创建一条往来，代理付款创建另外一条对应明细，托盘赎回创建一条对应下面的托盘往来
		if ($this->mainInfo->purchase_type == "tpcg"){
			$pledgeInfo = $this->mainInfo->pledge;
			
			$type = "TPCG";//类型
			$turnover_direction = "need_pay";//应付
			$title_id = $this->mainInfo->title_id;//公司抬头
			$target_id = $pledgeInfo->pledge_company_id;//往来对端公司		
			$client_id=$target_id;
			$proxy_company_id=$target_id;
			$amount = $this->mainInfo->weight;//总重量
			$price = $pledgeInfo->unit_price;//托盘单价
			$fee = $pledgeInfo->fee;//托盘金额
			$common_forms_id = $this->commonForm->id;
			$ownered_by = $this->commonForm->owned_by;
			$created_by=$this->commonForm->created_by;
			$description='单号：'.$this->commonForm->form_sn.'创建托盘往来';	
			$is_yidan=$this->mainInfo->is_yidan;
			$turnarray = compact("type","turnover_direction","title_id",'big_type','proxy_company_id',
					"target_id","amount","price","fee","common_forms_id","client_id",
					"form_detail_id","ownered_by",'created_by','description','is_yidan','created_at');
						
			$result = Turnover::createBill($turnarray);
			
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			
		}		
	}
	
	
	public function canPush()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status == 'unsubmit') return false;//表单状态不是未提交
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
		
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$this->mainInfo->can_push=1;	
			$this->mainInfo->update();		
			
			//推送所有入库计划
			if(Yii::app()->params['api_switch'])
			{
				FrmInputPlan::pushAll($this->commonForm->id);
			}						
			//开票
			/*
			if($this->commonForm->form_status=='approve')
			{
				if($this->mainInfo->is_yidan==0)
				{
					foreach ($this->details as $each_d)
					{
						$invoice=$each_d->invoice(array('condition'=>"form_id=".$this->commonForm->id));
						if(!$invoice)
						{
							$invoice=new DetailForInvoice();
							$invoice->type='purchase';
							$invoice->form_id=$this->commonForm->id;//
							$invoice->detail_id=$each_d->id;
							$invoice->checked_money=0;
							$invoice->checked_weight=0;
							if($this->mainInfo->weight_confirm_status)
							{
								$invoice->weight=$each_d->fix_weight;
								$invoice->money=$each_d->fix_weight*$each_d->fix_price;
							}else{
								$invoice->weight=$each_d->weight;
								$invoice->money=$each_d->weight*$each_d->price;
							}							
							$invoice->title_id=$this->mainInfo->title_id;
							$invoice->company_id=$this->mainInfo->supply_id;
							if($this->mainInfo->purchase_type=='tpcg')
							{
								$invoice->pledge_id=$this->mainInfo->pledge->pledge_company_id;
							}else{
								$invoice->pledge_id=0;
							}
							$invoice->insert();
						}
					}
				}
			}
			*/
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		return true;
	}
	
	public function cannotPush()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status == 'unsubmit') return false;//表单状态不是未提交
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
	
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$this->mainInfo->can_push=0;
			$this->mainInfo->update();
			
			//开票数据
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
				if($flag){echo '已开票，不能取消';return;	}else{
					foreach ($model as $e)
					{
						$e->delete();
					}
				}
			}			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		return true;
	}
	
	//-----------------------------------------------------------提交表单--------------------------------------------------------
	
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
			if($result!==true)
			{
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
	 * 采购单提交后动作
	 */
	protected function afterSubmitForm()
	{
		//修改代销销售单的未补单的数量
		if($this->mainInfo->purchase_type=="dxcg"||$this->mainInfo->purchase_type=="xxhj")
		{			
// 			$sales=$this->mainInfo->salesdetail_pur;
			$sales=SaledetailPurchase::model()->findAll('purchase_id='.$this->mainInfo->id);
			$result=SalesDetail::updatePurchased($sales,'submit');			
			if($result!==true)
			{
				return $result;
			}
		}
		//修改往来
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;

		//直接查找往来表，作废往来,托盘采购往来(如果有)也在这里
		$turnArray = Turnover::model()->findAll("common_forms_id = :common_forms_id and status!='delete'",array(":common_forms_id"=>$this->commonForm->id));
		foreach ($turnArray as $turnover) {
			$olddata=Turnover::getOne($turnover->id);
			$oldJson=$olddata->datatoJson();
			$result=Turnover::updateBill($turnover->id, array("status"=>"submited"));
			
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
		}
		//发送消息
		$baseform=$this->commonForm;
		$message = array();
		$message['receivers'] = User::model()->getOperationList("采购单:审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."提交了采购单：".$baseform->form_sn.",请尽快审核。";
		$message['title'] = "采购通知";
		$message['url'] = Yii::app()->createUrl('purchase/index',array('card_no'=>$baseform->form_sn));
		$message['type'] = "采购单";
		$message['big_type']='purchase';
		$res = MessageContent::model()->addMessage($message);
		return true;	
	} 
	
	//-----------------------------------------------------------取消提交表单--------------------------------------------------------

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
			$result = $this->afterCancelSubmitForm();
			if($result!==true)
			{
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
	 * 采购单取消提交后动作
	 */
	protected function afterCancelSubmitForm()
	{
		//修改代销销售单的未补单的数量
		if($this->mainInfo->purchase_type=="dxcg"||$this->mainInfo->purchase_type=="xxhj")
		{
			$sales=$this->mainInfo->salesdetail_pur;
			$result=SalesDetail::updatePurchased($sales,'unsubmit');
			if($result!==true)
			{
				return $result;
			}
		}
		//修改往来
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;
			
		//直接查找往来表，作废往来,托盘采购往来也在这里
		$turnarray = Turnover::model()->findAll("common_forms_id = :common_forms_id and status!='delete'",array(":common_forms_id"=>$this->commonForm->id));
		foreach ($turnarray as $turnover) {
			$olddata=Turnover::getOne($turnover->id);
			$oldJson=$olddata->datatoJson();
			$result=Turnover::updateBill($turnover->id, array("status"=>"unsubmit"));
			
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
		}	
	return true;		
	}
	
	
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
			if($result!==true)
			{
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
		return true;
		//发送消息
	}
		
	//-----------------------------------------------------------修改表单--------------------------------------------------------
	
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
			$result = $this->afterSubmitForm();
			if($result!==true)
			{
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
		//新增日志
		$operation = "修改";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		//只能修改部分信息
		if ($this->commonForm->form_status=='submited'){
			$oldmain=$this->mainInfo;
			$oldJson=$oldmain->datatoJson();
			$this->mainInfo->contact_id=$data->contact_id;
			$this->mainInfo->transfer_number=$data->transfer_number;
			$this->mainInfo->date_reach=strtotime($data->date_reach);
			$this->mainInfo->reach_time=$data->reach_time;
			if ($this->mainInfo->update()){					
				$mainJson = $this->mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);					
				return true;
			}
		}elseif($this->commonForm->form_status=='approve'){
			$oldmain=$this->mainInfo;
			$oldJson=$oldmain->datatoJson();
			$this->mainInfo->date_reach=strtotime($data->date_reach);
			$this->mainInfo->reach_time=$data->reach_time;
			if ($this->mainInfo->update()){
				$mainJson = $this->mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				return true;
			}		
		}elseif($this->commonForm->form_status=='unsubmit') {
			//所有信息都能修改
			$oldmain=$this->mainInfo;
			$oldJson=$oldmain->datatoJson();
			$old_id=$this->mainInfo->frm_contract_id;
			$this->mainInfo->title_id = $data->title_id;
			$this->mainInfo->supply_id=$data->supply_id;
			$this->mainInfo->purchase_type=$data->purchase_type;
			$this->mainInfo->is_yidan = $data->is_yidan?$data->is_yidan:0;
			$this->mainInfo->contact_id = $data->contact_id;
			$this->mainInfo->warehouse_id = $data->warehouse_id;
			$this->mainInfo->amount = $data->amount;
			$this->mainInfo->weight = $data->weight;
			$this->mainInfo->input_amount = 0;
			$this->mainInfo->input_weight = 0;
			$this->mainInfo->invoice_cost = $data->invoice_cost;
			$this->mainInfo->frm_contract_id = $data->frm_contract_id;
			$this->mainInfo->transfer_number=$data->transfer_number;
			$this->mainInfo->invoice_cost=$data->invoice_cost;
			$this->mainInfo->date_reach=strtotime($data->date_reach);
			$this->mainInfo->reach_time=$data->reach_time;
			$this->mainInfo->contain_cash=$data->contain_cash;
			$this->mainInfo->price_amount=$data->price_amount;
			if($data->contain_cash)
			{
				$this->mainInfo->shipment=$data->shipment?$data->shipment:0;
			}else{
				$this->mainInfo->shipment=0;
			}			
			if ($this->mainInfo->purchase_type == "tpcg"){
				$pledgeInfo = $this->mainInfo->pledge;		
				$oldJson='';
				//找到托盘信息，修改
				if($pledgeInfo)
				{
					$olddata=$pledgeInfo;
					$oldJson=$olddata->datatoJson();
					$pledgeInfo->fee =  numChange($data->fee);
					$pledgeInfo->begin_date = $data->begin_date;
					$pledgeInfo->pledge_company_id = $data->pledge_company_id;
					$pledgeInfo->advance =  numChange($data->advance);
					$pledgeInfo->unit_price =  numChange($data->unit_price);
					$pledgeInfo->r_limit = $data->r_limit?$data->r_limit:1;
					$pledgeInfo->pledge_length=$data->pledge_length;
					$pledgeInfo->pledge_rate=$data->pledge_rate;
					$pledgeInfo->min_rate=$data->min_rate;
					$pledgeInfo->over_rate=$data->over_rate;
					$pledgeInfo->violation_date=$data->violation_date;
					if (!$pledgeInfo->update()) return ;
					
					$mainJson = $pledgeInfo->datatoJson();
					$dataArray = array("tableName"=>"pledge_info","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}else{
					$pledgeInfo=new PledgeInfo();
					$pledgeInfo->frm_purchase_id = $this->mainInfo->id;
					$pledgeInfo->fee =  numChange($data->fee);
					$pledgeInfo->begin_date = $data->begin_date;
					$pledgeInfo->pledge_company_id = $data->pledge_company_id;
					$pledgeInfo->advance =  numChange($data->advance);
					$pledgeInfo->unit_price =  numChange($data->unit_price);
					$pledgeInfo->r_limit = $data->r_limit?$data->r_limit:1;
					$pledgeInfo->pledge_length=$data->pledge_length;
					$pledgeInfo->pledge_rate=$data->pledge_rate;
					$pledgeInfo->min_rate=$data->min_rate;
					$pledgeInfo->over_rate=$data->over_rate;
					$pledgeInfo->violation_date=$data->violation_date;
					if ($pledgeInfo->insert()){
						$mainJson = $pledgeInfo->datatoJson();
						$dataArray = array("tableName"=>"PledgeInfo","newValue"=>$mainJson,"oldValue"=>"");
						$this->dataLog($dataArray);
					}
				}
			}else{
				$pledgeInfo = $this->mainInfo->pledge;
				if($pledgeInfo)$pledgeInfo->delete();
			}
			if ($this->mainInfo->update()){
				$mainJson = $this->mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				return true;
			}
		}
	}
	
	/**
	 * 修改明细
	 */
	protected function updateDetails($data)
	{
		if($this->commonForm->form_status=='submited'||$this->commonForm->form_status=='approve')
		{
			return true;
		}
		if (!is_array($data) || count($data)<=0 || !is_array($this->details) || count($this->details)<=0)
			return ;
		if ($this->commonForm->form_status=="unsubmit"){//如果不是未提交，明细不能修改		
			/******-----------代销采购》》-------******/
			if($this->mainInfo->purchase_type=="dxcg")
			{
// 				//更新后的商品id
// 				$new_good_array=array();
// 				$new_sales_arr=array();
// 				$sales=json_decode($data[0]->sales_details_array);
// 				foreach ($sales as $sale)
// 				{
// 					$new_good_array[$sale->detail_id]= $sale->good_id;
// 					array_push($new_sales_arr, $sale->detail_id);
// 				}
// 				//之前的数据
// 				$relate=$this->mainInfo->salesdetail_pur;
// 				$old_good_array=array();
// 				$old_sales_arr=array();
// 				foreach ($relate as $eachrel)
// 				{
// 					if(!in_array($eachrel->good_id, $old_good_array))
// 					{
// 						$old_good_array[$eachrel->purchase_detail_id]=$eachrel->good_id;
// 					}
// 					$old_sales_arr[$eachrel->id]=$eachrel->sales_detail_id;
// 					if(!in_array($eachrel->sales_detail_id, $new_sales_arr))
// 					{
// 						$eachrel->delete();
// 						$oldJson=$eachrel->datatoJson();
// 						$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>"","oldValue"=>$oldJson);
// 						$this->dataLog($dataArray);
// 					}
// 				}
// 				//删除旧数据
// 				foreach ($this->details as $e)
// 				{
// 					if(!in_array($old_good_array[$e->id], $new_good_array))
// 					{
// 						$e->delete();
// 						$oldJson=$e->datatoJson();
// 						$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>"","oldValue"=>$oldJson);
// 						$this->dataLog($dataArray);
// 					}
// 				}
// 				//更新与创建
// 				foreach ($data as $eachdata)
// 				{
// 					$ids=explode(',', $eachdata->salesdetail_ids);
					
// 					if(in_array($new_good_array[$ids[0]],$old_good_array))
// 					{//采购单详细之前有，更新
// 						$old_detail_id=array_search($new_good_array[$ids[0]], $old_good_array);
// 						$purchase_detail=PurchaseDetail::model()->findByPk($old_detail_id);
// 						$olddata=$purchase_detail;
// 						$oldJson=$olddata->datatoJson();
// 						$purchase_detail->price=$eachdata->price;
// 						$purchase_detail->cost_price=$eachdata->price;
// 						$purchase_detail->amount=$eachdata->amount;
// 						$purchase_detail->weight=$eachdata->weight;
// 						$purchase_detail->length=$eachdata->length;
// 						$purchase_detail->update();
						
// 						$mainJson = $purchase_detail->datatoJson();
// 						$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 						$this->dataLog($dataArray);
// 						foreach ($ids as $eachid)
// 						{
// 							if(in_array($eachid, $old_sales_arr))
// 							{//代销销售明细单有,更新
// 								$saledetailP=SaledetailPurchase::model()->findByPk(array_search($eachid,$old_sales_arr));
// 								foreach ($sales as $each)
// 								{
// 									if($each->detail_id==$eachid)
// 									{
// 										$olddata=$saledetailP;
// 										$oldJson=$olddata->datatoJson();
// 										$saledetailP->amount=$each->amount;
// 										$saledetailP->weight=$each->weight;
// 										$saledetailP->update();
										
// 										$mainJson = $saledetailP->datatoJson();
// 										$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 										$this->dataLog($dataArray);
// 										break;
// 									}
// 								}
// 							}else{
// 								//新建
// 								foreach ($sales as $each)
// 								{
// 									if($each->detail_id==$eachid)
// 									{
// 										$relation=new SaledetailPurchase();
// 										$relation->sales_detail_id=$each->detail_id;
// 										$relation->purchase_id=$this->mainInfo->id;
// 										$relation->purchase_detail_id=$old_detail_id;
// 										$relation->amount=$each->amount;
// 										$relation->weight=$each->weight;
// 										$relation->form_sn=$each->form_sn;
// 										$relation->good_id=$each->good_id;
// 										$relation->insert();
										
// 										$mainJson = $relation->datatoJson();
// 										$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$mainJson,"oldValue"=>"");
// 										$this->dataLog($dataArray);
										
// 										break;
// 									}
// 								}
// 							}
// 						}
						
// 					}else{
// 						//之前没有采购明细，新建
// 						$purchase_detail = new PurchaseDetail();
// 						$purchase_detail->price = $eachdata->price;
// 						$purchase_detail->cost_price=$eachdata->price;
// 						$purchase_detail->amount = $eachdata->amount;
// 						$purchase_detail->weight = $eachdata->weight;
// 						$purchase_detail->input_amount = 0;
// 						$purchase_detail->input_weight = 0;
// 						$purchase_detail->purchase_id = $this->mainInfo->id;
// 						$purchase_detail->fix_amount = 0;
// 						$purchase_detail->fix_weight = 0;
// 						$purchase_detail->fix_price=0;
// 						$purchase_detail->invoice_price = $this->mainInfo->invoice_cost;
// 						$purchase_detail->product_id = $eachdata->product_id;//品名std
// 						$purchase_detail->brand_id = $eachdata->brand_id;//产地
// 						$purchase_detail->texture_id = $eachdata->texture_id;//材质
// 						$purchase_detail->rank_id = $eachdata->rank_id;//规格
// 						if(empty($eachdata->length))
// 						{
// 							$purchase_detail->length=0;
// 						}else{
// 							$purchase_detail->length = $eachdata->length;//长度
// 						}
// 						if ($purchase_detail->insert())
// 						{
// 							$mainJson = $purchase_detail->datatoJson();
// 							$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>"");
// 							$this->dataLog($dataArray);
// 							//创建关联
// 							foreach ($ids as $eachid)
// 							{
// 								foreach ($sales as $each)
// 								{
// 									if($each->detail_id==$eachid)
// 									{
// 										$relation=new SaledetailPurchase();
// 										$relation->sales_detail_id=$each->detail_id;
// 										$relation->purchase_id=$this->mainInfo->id;
// 										$relation->purchase_detail_id=$purchase_detail->id;
// 										$relation->amount=$each->amount;
// 										$relation->weight=$each->weight;
// 										$relation->form_sn=$each->form_sn;
// 										$relation->good_id=$each->good_id;
// 										$relation->insert();
										
// 										$mainJson = $relation->datatoJson();
// 										$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$mainJson,"oldValue"=>"");
// 										$this->dataLog($dataArray);
// 										break;
// 									}
// 								}
// 							}
// 						}
// 					}
// 				}
				//删除旧数据
				$relate=$this->mainInfo->salesdetail_pur;
				foreach ($relate as $ea){$ea->delete();}
				foreach ($this->details as $e)	{$e->delete();}			
				foreach ($data as $detail) {
					//获取明细
					$purchase_detail = new PurchaseDetail();
					$purchase_detail->price = $detail->price;
					$purchase_detail->amount = $detail->amount;
					$purchase_detail->weight = $detail->weight;
					$purchase_detail->input_amount = 0;
					$purchase_detail->input_weight = 0;
					$purchase_detail->purchase_id = $this->mainInfo->id;
					$purchase_detail->fix_amount = 0;
					$purchase_detail->fix_weight = 0;
					$purchase_detail->fix_price = 0;
					$purchase_detail->cost_price =  $detail->price;
					$purchase_detail->invoice_price = $detail->invoice_price;
					$purchase_detail->product_id = $detail->product_id;//品名std
					$purchase_detail->brand_id = $detail->brand_id;//产地
					$purchase_detail->texture_id = $detail->texture_id;//材质
					$purchase_detail->rank_id = $detail->rank_id;//规格
					if(empty($detail->length))
					{
						$purchase_detail->length =0;
					}else{
						$purchase_detail->length = $detail->length;//长度
					}
					if($purchase_detail->insert())
					{
						//日志
						$detailJson = $purchase_detail->datatoJson();
						$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$detailJson,"oldValue"=>"");
						$this->dataLog($dataArray);
						//创建关联
						$sales_details=json_decode($detail->sales_details_array);
						$ids=explode(',', $detail->salesdetail_ids);
						foreach ($ids as $e)
						{
							foreach ($sales_details as $each)
							{
								if($each->detail_id==$e)
								{
									$relation=new SaledetailPurchase();
									$relation->sales_detail_id=$each->detail_id;
									$relation->purchase_id=$this->mainInfo->id;
									$relation->purchase_detail_id=$purchase_detail->id;
									$relation->amount=$each->amount;
									$relation->weight=$each->weight;
									$relation->form_sn=$each->form_sn;
									$relation->good_id=$each->good_id;
									$relation->insert();
									$detailJson = $relation->datatoJson();
									$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$detailJson,"oldValue"=>"");
									$this->dataLog($dataArray);
									break;
								}
							}
						}
					}
				}
				/***-----------------《《----------先销后进》》---------***/
			}elseif($this->mainInfo->purchase_type=="xxhj"){//先销后进
				$flag=false;
				$ids=array();
				foreach ($data as $each)
				{
					if($each->old_id)
					{
						$flag=true;
						array_push($ids,$each->old_id);						
					}
				}
				foreach ($this->details as $det)
				{
					if(!in_array($det->id, $ids)){
						$rel=SaledetailPurchase::model()->findByAttributes(array('purchase_detail_id'=>$det->id));
						$rel->delete();
						
						$oldJson = $rel->datatoJson();
						$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>"","oldValue"=>$oldJson);
						$this->dataLog($dataArray);
											
						$det->delete();
						$oldJson = $det->datatoJson();
						$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>"","oldValue"=>$oldJson);
						$this->dataLog($dataArray);
					}
				}
				if($flag)
				{//原有数据
					foreach ($data as $each)
					{
						foreach ($this->details as $detail)
						{
							if($detail->id==$each->old_id)
							{
								$olddata=$detail;
								$oldJson=$olddata->datatoJson();
								$detail->price = $each->price;
								$detail->amount = $each->amount;
								$detail->weight = $each->weight;
// 								$detail->fix_price = $each->price;
								$detail->cost_price =  $each->price;
								$detail->invoice_price = $each->invoice_price;
								$detail->product_id = $each->product_id;//品名std
								$detail->brand_id = $each->brand_id;//产地
								$detail->texture_id = $each->texture_id;//材质
								$detail->rank_id = $each->rank_id;//规格
								$detail->length = $each->length;//长度
								$detail->update();
								
								$mainJson = $detail->datatoJson();
								$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
								$this->dataLog($dataArray);
								
								//改变相应关联的值
								$rel=SaledetailPurchase::model()->findByAttributes(array('purchase_detail_id'=>$detail->id));
								$olddata=$rel;
								$oldJson=$olddata->datatoJson();
								$rel->amount=$each->amount;
								$rel->weight=$each->weight;
								$rel->update();
								
								$mainJson = $rel->datatoJson();
								$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
								$this->dataLog($dataArray);
								
								break;
							}
						}
					}
				}else{
					//新切换数据
					foreach ($data as $detail) {
						//获取明细
						$purchase_detail = new PurchaseDetail();
						$purchase_detail->price = $detail->price;
						$purchase_detail->amount = $detail->amount;
						$purchase_detail->weight = $detail->weight;
						$purchase_detail->input_amount = 0;
						$purchase_detail->input_weight = 0;
						$purchase_detail->purchase_id = $this->mainInfo->id;
						$purchase_detail->fix_amount = 0;
						$purchase_detail->fix_weight = 0;
						$purchase_detail->fix_price = 0;
						$purchase_detail->cost_price =  $detail->price;
						$purchase_detail->invoice_price = $detail->invoice_price;
						$purchase_detail->product_id = $detail->product_id;//品名std
						$purchase_detail->brand_id = $detail->brand_id;//产地
						$purchase_detail->texture_id = $detail->texture_id;//材质
						$purchase_detail->rank_id = $detail->rank_id;//规格
						$purchase_detail->length = $detail->length;//长度
							
						if ($purchase_detail->insert())
						{
							$mainJson = $purchase_detail->datatoJson();
							$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>"");
							$this->dataLog($dataArray);
								//更新销售单详细的补单数量
								$relation=new SaledetailPurchase();
								$relation->sales_detail_id=$detail->id;
								$relation->purchase_id=$this->mainInfo->id;
								$relation->purchase_detail_id=$purchase_detail->id;
								$relation->amount=$detail->amount;
								$relation->weight=$detail->weight;
								$relation->insert();		
								
								$mainJson = $relation->datatoJson();
								$dataArray = array("tableName"=>"SaledetailPurchase","newValue"=>$mainJson,"oldValue"=>"");
								$this->dataLog($dataArray);
								
						}
					}
				}
				
				/***--------------《《----- ----------其他》》   ----------***/
			}else{//其他
				$id_array = array();
				foreach ($data as $item) {
					if ($item->id)
						array_push($id_array,$item->id);
				}
				$details=$this->details;
				if(empty($id_array))
				{
					foreach ($details as $each)
					{
						$each->delete();
						$mainJson = $each->datatoJson();
						$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>"","oldValue"=>$mainJson);
						$this->dataLog($dataArray);
						
					}
				}else{
					foreach ($details as $each)
					{
						if(!in_array($each->id,$id_array))
						{
							$each->delete();
							$mainJson = $each->datatoJson();
							$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>"","oldValue"=>$mainJson);
							$this->dataLog($dataArray);
						}
					}
				}
				foreach ($data as $data_each)
				{
					if($data_each->id)
					{
						//修改此条数据
						$detail_data=PurchaseDetail::getOne($data_each->id);
						$olddata=$detail_data;
						$oldJson=$olddata->datatoJson();
						if($detail_data)
						{
							foreach ($data_each as $key =>$value)
							{
								if($key=='id')continue;
								$detail_data->$key=$value;
							}
							$detail_data->update();
							
							$mainJson = $detail_data->datatoJson();
							$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$this->dataLog($dataArray);
							
						}
					}else{
						//新建
						$pcd=new PurchaseDetail();
						foreach ($data_each as $k=>$v)
						{
							$pcd->$k=$v;
						}
						$pcd->purchase_id=$this->mainInfo->id;
						$pcd->insert();
						
						$mainJson = $pcd->datatoJson();
						$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>"");
						$this->dataLog($dataArray);
					}
				}
			}
		}
		return true;
	}
	
	/**
	 * 修改以后操作
	 */
	protected function afterupdateForm()
	{
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;
		
		//如果是未提交状态才可以修改明细信息，修改往来，否者直接跳过
		if ($this->commonForm->form_status == "unsubmit"){
			//1.修改往来
			//2.重新计算采购成本价
			$dg_jm_normal=$this->isDgJmPurchase();
			
			$details=PurchaseDetail::model()->findAllByAttributes(array('purchase_id'=>$this->mainInfo->id));
			foreach ($details as $detail) {
				$remark='';
				$turnover = $detail->turnover(array("condition"=>"turnover_type='CGMX' and common_forms_id = ".$this->commonForm->id." and status!='delete'"));
				$brand_name=DictGoodsProperty::getProName($detail->brand_id);
				if (!$turnover){//如果新增的明细没有对应往来，需要创建一条
					$type = "CGMX";//类型
					$turnover_direction = "need_pay";//应付
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $this->mainInfo->supply_id;//往来对端公司
					$client_id=$target_id;
					$proxy_company_id=$this->mainInfo->pledge->pledge_company_id;
					$amount = $detail->weight;//重量
					$price = $detail->price;//单价					
				
					//抬头为登钢爵淼产地为贵航的采购单往来为0--2016/10/20
					if($dg_jm_normal&&($brand_name=='贵航'))
					{
						$fee=0;
						$remark='抬头为登钢爵淼产地为贵航的采购单往来为0';
					}else{
						$fee = $price*$amount;
					}					
			
					$common_forms_id = $this->commonForm->id;
					$form_detail_id = $detail->id;
					$ownered_by = $this->commonForm->owned_by;
					$created_by =$this->commonForm->created_by;
					$is_yidan=$this->mainInfo->is_yidan;
					$big_type='purchase';
					$created_at=strtotime($this->commonForm->form_time);
					$description='单号：'.$this->commonForm->form_sn.','.$brand_name.'|'.DictGoodsProperty::getProName($detail->product_id).'|'.DictGoodsProperty::getProName($detail->rank_id).'*'.$detail->length.'*'.DictGoodsProperty::getProName($detail->texture_id).$remark;					
					$turnarray = compact("type","turnover_direction","title_id",'big_type',"target_id","client_id",
						'proxy_company_id',"amount","price","fee","common_forms_id","form_detail_id",
						"ownered_by",'created_by','description','is_yidan','created_at');					
					$result = Turnover::createBill($turnarray);
					
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);					
				}else{
					
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $this->mainInfo->supply_id;//往来对端公司
					$client_id=$target_id;
					$proxy_company_id=$this->mainInfo->pledge->pledge_company_id;
					$amount = $detail->weight;//重量
					$price = $detail->price;//单价
					
					//抬头为登钢爵淼产地为贵航的采购单往来为0--2016/10/20
					if($dg_jm_normal&&($brand_name=='贵航'))
					{
						$fee=0;
						$remark='抬头为登钢爵淼产地为贵航的采购单往来为0';
					}else{
						$fee = $price*$amount;
					}			
					
					$common_forms_id = $this->commonForm->id;
					$form_detail_id = $detail->id;
					$ownered_by = $this->commonForm->owned_by;
					$is_yidan=$this->mainInfo->is_yidan;
					$big_type='purchase';
					$created_at=strtotime($this->commonForm->form_time);
					$description='单号：'.$this->commonForm->form_sn.','.DictGoodsProperty::getProName($detail->brand_id).'|'.DictGoodsProperty::getProName($detail->product_id).'|'.DictGoodsProperty::getProName($detail->rank_id).'*'.$detail->length.'*'.DictGoodsProperty::getProName($detail->texture_id).$remark;					
					$turnarray = compact("title_id","target_id",'proxy_company_id',"amount","price",'big_type',"fee","client_id",
						"common_forms_id","form_detail_id","ownered_by",'description','is_yidan','created_at');					
					//状态是未提交，价格从新计算
					$oldturn=$turnover;
					$oldJson=$oldturn->datatoJson();
					$result=Turnover::updateBill($turnover->id, $turnarray);
					
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
				}
				//计算采购成本价
				$this->caclulateItemCost($detail->id);
			}
			
			//删除明细已经删除对应的往来
			$turnovers=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status !="delete"');
			foreach ($turnovers as $each)
			{
				$flag=false;
				foreach ($details as $detail)
				{
					if($detail->id==$each->form_detail_id)
					{
						$flag=true;
						break;
					}
				}
				if(!$flag){
					$each->status='delete';
					$each->update();
				}
			}
			
			//如果是托盘采购
			if ($this->mainInfo->purchase_type == "tpcg")
			{
				//托盘往来
				$turnover = Turnover::model()->find("turnover_type = :type and common_forms_id = :form_id and status!= 'delete'",array(":type"=>"TPCG",":form_id"=>$this->commonForm->id));
				
				if($turnover)
				{
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $this->mainInfo->pledge->pledge_company_id;//往来对端公司
					$client_id=$target_id;
					$proxy_company_id=$target_id;
					$amount = $this->mainInfo->weight;//总重量
					$price = $this->mainInfo->pledge->unit_price;//托盘单价
					$fee = $this->mainInfo->pledge->fee;
					$common_forms_id = $this->commonForm->id;
					$ownered_by = $this->commonForm->owned_by;
					$is_yidan=$this->mainInfo->is_yidan;
					$created_at=strtotime($this->commonForm->form_time);
					$turnarray = compact("title_id","target_id","client_id",'proxy_company_id',"amount","price","fee","common_forms_id","ownered_by",'is_yidan','created_at');
					
					$oldturn=$turnover;
					$oldJson=$oldturn->datatoJson();
					$result=Turnover::updateBill($turnover->id, $turnarray);
					
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}else{
					$pledgeInfo=PledgeInfo::model()->findByAttributes(array('frm_purchase_id'=>$this->mainInfo->id));
					$type = "TPCG";//类型
					$turnover_direction = "need_pay";//应付
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $pledgeInfo->pledge_company_id;//往来对端公司
					$client_id=$target_id;
					$proxy_company_id=$target_id;
					$amount = $this->mainInfo->weight;//总重量
					$price = $pledgeInfo->unit_price;//托盘单价
					$fee = $pledgeInfo->fee;//托盘金额
					$common_forms_id = $this->commonForm->id;
					$ownered_by = $this->commonForm->owned_by;
					$created_by=$this->commonForm->created_by;
					$description="单号：".$this->commonForm->form_sn."创建托盘往来";
					$is_yidan=$this->mainInfo->is_yidan;	
					$created_at=strtotime($this->commonForm->form_time);					
					$big_type='purchase';
					$turnarray = compact("type","turnover_direction","title_id","target_id","amount","client_id",
							"price","fee","common_forms_id","form_detail_id","ownered_by",'created_at',
							'created_by','description','is_yidan','big_type','proxy_company_id');					
					$result = Turnover::createBill($turnarray);
						
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);
				}
				
			}else{				
				$turnover = Turnover::model()->find("turnover_type = :type and common_forms_id = :form_id",array(":type"=>"TPCG",":form_id"=>$this->commonForm->id));
				if($turnover)$turnover->delete();
			}
		}

		return true;
		
	}
	
	/**
	 * 作废
	 */
	protected function afterDeleteForm()
	{
		//作废采购往来
		if ($this->commonForm->is_deleted != 1) 
			return ;
		
		//直接查找往来表，作废往来,托盘采购往来也在这里
		$turnArray = Turnover::model()->findAll("common_forms_id = :common_forms_id",array(":common_forms_id"=>$this->commonForm->id));
		foreach ($turnArray as $turnover) {
			$oldturn=Turnover::getOne($turnover->id);
			$oldJson=$oldturn->datatoJson();
			$result=Turnover::updateBill($turnover->id, array("status"=>"delete"));
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
		}
		//代销和先销后进删除
		if($this->purchase_type!='normal')
		{
			SaledetailPurchase::model()->deleteAll('purchase_id='.$this->mainInfo->id);			
		}
		
	}
	
	/**
	 * 审核通过
	 */
	protected function afterApproveForm()
	{
		//创建可以开票明细
		/*
		if($this->mainInfo->is_yidan==0&&$this->mainInfo->can_push)
		{
			foreach ($this->details as $each_d)
			{
				$invoice=new DetailForInvoice();
				$invoice->type='purchase';
				$invoice->form_id=$this->commonForm->id;//
				$invoice->detail_id=$each_d->id;
				$invoice->checked_money=0;
				$invoice->checked_weight=0;
				$invoice->weight=$each_d->weight;
				$invoice->money=$each_d->weight*$each_d->price;
				$invoice->title_id=$this->mainInfo->title_id;
				$invoice->company_id=$this->mainInfo->supply_id;
				if($this->mainInfo->purchase_type=='tpcg')
				{
					$invoice->pledge_id=$this->mainInfo->pledge->pledge_company_id;
				}else{
					$invoice->pledge_id=0;
				}				
				$invoice->insert();
			}
		}
		*/
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的采购单：".$baseform->form_sn."已经审核通过。";
			$message['title'] = "审核通知";
			//$message['url'] = "";
			$message['type'] = "采购单";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
		
		//判断是否关联合同，如果关联，修改合同执行记录
		if (!$this->mainInfo->frm_contract_id){return;}
		if($this->mainInfo->purchase_type!='xxhj')
		{
			
			//修改采购合同已执行记录
			$contract = new Contract($this->mainInfo->frm_contract_id);
			
			if (!is_array($contract->details) || count($contract->details)<=0) return ;
			$amount_price = 0;

			foreach($this->details as $each)
			{
				$amount_price+=$each->price*$each->weight;
			}
			$olddata=$contract->mainInfo;
			$oldJson=$olddata->datatoJson();
			$contract->mainInfo->purchase_amount += $this->mainInfo->amount;
			$contract->mainInfo->purchase_weight += $this->mainInfo->weight;
			$contract->mainInfo->purchase_fee+=$amount_price;
			$contract->mainInfo->update();
			
			$mainJson = $contract->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
		}else {
			$sale_form=CommonForms::model()->with('sales','sales.salesDetails')->findByPk($this->mainInfo->frm_contract_id);
			if($sale_form)
			{
				$frmsale=$sale_form->sales;

				if($this->mainInfo->amount>=$frmsale->amount)
				{
					$olddata=$frmsale;
					$oldJson=$olddata->datatoJson();
					$frmsale->is_related=1;
					$frmsale->update();
					
					$mainJson =$frmsale ->datatoJson();
					$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);					
				}
			}
		}	
	}
	
	
	/**
	 * 审核拒绝
	 */
	protected function afterRefuseForm()
	{
		//往来改为未提交
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;
			

		//修改代销销售单的未补单的数量
		if($this->mainInfo->purchase_type=="dxcg"||$this->mainInfo->purchase_type=="xxhj")
		{
			$sales=$this->mainInfo->salesdetail_pur;
			$result=SalesDetail::updatePurchased($sales,'unsubmit');
			if($result!==true)
			{
				return $result;
			}
		}



		//直接查找往来表，作废往来,托盘采购往来也在这里
		$turnArray = Turnover::model()->findAll("common_forms_id = :common_forms_id and status!='delete'",array(":common_forms_id"=>$this->commonForm->id));
		foreach ($turnArray as $turnover) {
			$oldturn=Turnover::getOne($turnover->id);
			$oldJson=$oldturn->datatoJson();
			$result=Turnover::updateBill($turnover->id, array("status"=>"unsubmit"));
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
		}
		//发送消息
		$baseform = $this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的采购单：".$baseform->form_sn."审核已被拒绝。";
			$message['title'] = "审核通知";
			//$message['url'] = "";
			$message['type'] = "采购单";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	/**
	 * 取消审核
	 */
	protected function afterCancelApproveForm()
	{
		//删除可以开票明细数据
		/*
		if($this->mainInfo->is_yidan==0)
		{
			foreach ($this->details as $each_d)
			{
				$invoice=$each_d->invoice(array('condition'=>"form_id=".$this->commonForm->id));
				if($invoice)$invoice->delete();
			}
		}
		*/		
		//发送消息
		$baseform = $this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的采购单：".$baseform->form_sn."已被取消审核。";
			$message['title'] = "审核通知";
			$message['type'] = "采购单";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
		
		//判断是否关联合同，如果关联，作废合同执行记录
		if (!$this->mainInfo->frm_contract_id){return;}
		
		if($this->mainInfo->purchase_type!='xxhj')
		{
			//修改采购合同已执行记录
			$contract = new Contract($this->mainInfo->frm_contract_id);
			$contract->getFormByCommonId($id);
			
			if (!is_array($contract->details) || count($contract->details)<=0) return ;
			foreach($this->details as $each)
			{
				$amount_price+=$each->price*$each->weight;
			}
			$olddata=$contract->mainInfo;
			$oldJson=$olddata->datatoJson();
			$contract->mainInfo->purchase_amount -= $this->mainInfo->amount;
			$contract->mainInfo->purchase_weight -= $this->mainInfo->weight;
			$contract->mainInfo->purchase_fee-=$amount_price;
			$contract->mainInfo->update();
			
			$mainJson = $contract->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
		}else{
			$sale_form=CommonForms::model()->with('sales','sales.salesDetails')->findByPk($this->mainInfo->frm_contract_id);
			if($sale_form)
			{
				$frmsale=$sale_form->sales;
				if($this->mainInfo->amount>=$frmsale->amount)
				{
					$olddata=$frmsale;
					$oldJson=$olddata->datatoJson();
					$frmsale->is_related=1;
					$frmsale->update();
					$mainJson =$frmsale ->datatoJson();
					$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
				}else{
					$olddata=$frmsale;
					$oldJson=$olddata->datatoJson();
					$frmsale->is_related=0;
					$frmsale->update();
					
					$mainJson =$frmsale ->datatoJson();
					$dataArray = array("tableName"=>"FrmSales","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
				}
			}
		}
	}
	
	
	/**
	 * 审单、定价
	 */
	public function confirmFormInfo($data)
	{
		$common = $data['common'];
		$main = $data['main'];
		$detailArray = $data['detail'];
		if ($main == null || count($detailArray)<=0) return "数据错误";
		
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//修改基本信息	
// 			$this->commonForm->form_time = $common->form_time;
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->owned_by=$common->owned_by;
			$this->commonForm->comment=$common->comment;
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			//修改主体信息
			$olddata=$this->mainInfo;
			$oldJson=$olddata->datatoJson();
			$this->mainInfo->contact_id=$main->contact_id;
			$this->mainInfo->team_id=$main->team_id;
			$this->mainInfo->date_reach=strtotime($main->date_reach);
			$this->mainInfo->invoice_cost=$main->invoice_cost;
			$this->mainInfo->is_yidan=$main->is_yidan?$main->is_yidan:0;
			$this->mainInfo->contain_cash=$main->contain_cash;
			$this->mainInfo->shipment=$main->shipment;
			$this->mainInfo->transfer_number=$main->transfer_number;
			$this->mainInfo->confirm_amount = $main->confirm_amount;
			$this->mainInfo->confirm_weight = $main->confirm_weight;
			$this->mainInfo->confirm_cost = $main->confirm_cost;
			$this->mainInfo->price_amount = $main->confirm_cost;
			if ($main->confirm_amount && $main->confirm_weight)
				$this->mainInfo->weight_confirm_status = 1;
			if ($main->confirm_cost)
				$this->mainInfo->price_confirm_status = 1;
			$this->mainInfo->update();
			
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			
			$contract=$this->mainInfo->contract_baseform(array("condition"=>"form_type='CGHT'"))->contract;
			$totalF=0;
			$totalOriginalF=0;
			
			$dg_jm_normal=$this->isDgJmPurchase();
			//修改单据明细
			foreach ($detailArray as $item){
				foreach ($this->details as $val){
					if ($item->id !=$val->id) continue;
					$olddata=$val;
					$oldJson=$olddata->datatoJson();
					$val->fix_amount = $item->fix_amount;
					$val->fix_weight = $item->fix_weight;
					$val->fix_price = $item->fix_price;
					$val->update();
					
					$mainJson =$val->datatoJson();
					$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
					
					//更新采购单应付往来
					$turn=$val->turnover(array("condition"=>"turnover_type='CGMX' and common_forms_id = ".$this->commonForm->id." and status!='delete'"));
					if($turn)
					{
						$olddata=$turn;
						$oldJson=$olddata->datatoJson();
						$turn->amount=$item->fix_weight;
						$turn->price=$item->fix_price;
						
						$brand_name=DictGoodsProperty::getProName($val->brand_id);
						//抬头为登钢爵淼产地为贵航的采购单往来为0--2016/10/20
						if($dg_jm_normal&&($brand_name=='贵航')){}else{
							$turn->fee=$item->fix_weight*$item->fix_price;
						}						
						
						$turn->ownered_by=$this->commonForm->owned_by;
						$turn->is_yidan=$this->mainInfo->is_yidan;
						$turn->confirmed=1;
						$turn->update();						
						$mainJson = $turn->datatoJson();
						$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);						
					}
					
					//修改库存采购价确认
					$inputDetails=$val->inputDetails;
					if(is_array($inputDetails))
					{
						foreach ($inputDetails as $inputDetail)
						{
							$storage=$inputDetail->storage;
							if($storage)
							{
								$storage->is_price_confirmed=1;
								$storage->cost_price=$item->fix_price;
								$storage->update();
							}
						}
					}
					//更新可销票明细
					$invoice=$val->invoice(array('condition'=>"form_id=".$this->commonForm->id));
					if($invoice)
					{
						if($this->mainInfo->is_yidan){
							$invoice->delete();
						}else{
							$invoice->weight=$val->fix_weight;
							$invoice->money=$val->fix_price*$val->fix_weight;
							if($invoice->weight<$invoice->checked_weight||$invoice->money<$invoice->checked_money)
							{
								throw new CException("已销票");
							}
							$invoice->update();
						}						
					}else if(!$this->mainInfo->is_yidan){
						$invoice=new DetailForInvoice();
						$invoice->type='purchase';
						$invoice->form_id=$this->commonForm->id;//
						$invoice->detail_id=$val->id;
						$invoice->checked_money=0;
						$invoice->checked_weight=0;
						$invoice->weight=$val->fix_weight;
						$invoice->money=$val->fix_weight*$val->fix_price;
					
						$invoice->title_id=$this->mainInfo->title_id;
						$invoice->company_id=$this->mainInfo->supply_id;
						if($this->mainInfo->purchase_type=='tpcg')
						{
							$invoice->pledge_id=$this->mainInfo->pledge->pledge_company_id;
						}else{
							$invoice->pledge_id=0;
						}
						$invoice->insert();
					}
					
					$totalF+=$turn->fee;
					$totalOriginalF+=$val->weight*$val->price;
					//重新计算成本价  //更新采购成本价
					$this->caclulateItemCost($val->id);
				}
			}
			//更新对应合同执行往来
			if($contract)
			{
				$olddata=$contract;
				$oldJson=$olddata->datatoJson();
				$contract->purchase_amount+=($this->mainInfo->confirm_amount-$this->mainInfo->amount);
				$contract->purchase_weight+=($this->mainInfo->confirm_weight-$this->mainInfo->weight);
				$contract->purchase_fee+=($totalF-$totalOriginalF);
				$contract->update();
				
				$mainJson = $contract->datatoJson();
				$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				
			}
			//审单日志
			$operation = "审单";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
			ProfitChange::createNew('purchase',$this->commonForm->id,1);//
			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();
			if($e->message=='已销票')
				return '已销票';
			return "操作失败";
		}
		return true;
	}
	/*
	 * 取消审单
	 */
	public function cancelConfirm()
	{
		$contract=$this->mainInfo->contract_baseform(array("condition"=>"form_type='CGHT'"))->contract;
		$totalF=0;
		$total_of=0;
		$dg_jm_normal=$this->isDgJmPurchase();
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//更新采购单明细
			foreach ($this->details as $each)
			{
				//回滚采购应付往来
				$turn=$each->turnover(array("condition"=>"turnover_type='CGMX' and common_forms_id = ".$this->commonForm->id." and status !='delete'"));
				$olddata=$turn;
				$oldJson=$olddata->datatoJson();
				$turn->price=$each->price;
				$turn->amount=$each->weight;
					
				$brand_name=DictGoodsProperty::getProName($each->brand_id);
				//抬头为登钢爵淼产地为贵航的采购单往来为0--2016/10/20
				if($dg_jm_normal&&($brand_name=='贵航')){}else{
					$turn->fee=$each->price*$each->weight;
				}
				$turn->confirmed=0;
				$turn->update();
					
				$total_of+=$each->fix_price*$each->fix_weight;
					
				$mainJson = $turn->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			
				//更新采购明细
				$olddata=$each;
				$oldJson=$olddata->datatoJson();
				$each->fix_amount=0;
				$each->fix_weight=0;
				$each->update();
					
				$mainJson =$each->datatoJson();
				$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
					
				$totalF+=$turn->fee;
			
				//修改库存采购价确认
				$inputDetails=$each->inputDetails;
				if(is_array($inputDetails))
				{
					foreach ($inputDetails as $inputDetail)
					{
						$storage=$inputDetail->storage;
						if($storage)
						{
							$storage->is_price_confirmed=0;
							$storage->cost_price=$inputDetail->cost_price;
							$storage->update();
						}
					}
				}
				//删除可销票明细
				if($this->mainInfo->is_yidan==0)
				{
					foreach ($this->details as $each_d)
					{
						$invoice=$each_d->invoice(array('condition'=>"form_id=".$this->commonForm->id));
						if(floatval($invoice->checked_weight))
						{
							throw new CException("已销票");
						}
						if($invoice)$invoice->delete();
					}
				}
				/*
				$invoice=$each->invoice(array('condition'=>"form_id=".$this->commonForm->id));
				if($invoice)
				{
					$invoice->weight=$each->weight;
					$invoice->money=$each->price*$each->weight;
					if($invoice->weight<$invoice->checked_weight||$invoice->money<$invoice->checked_money)
					{
						throw new CException("已销票");
					}
					$invoice->update();
				}
				*/
				//重新计算成本价  //更新采购成本价
				$this->caclulateItemCost($each->id);
			}
			//若关联合同，回滚合同执行记录和对应合同执行往来
			if($contract)
			{
				$olddata=$contract;
				$oldJson=$olddata->datatoJson();
				$contract->purchase_amount-=($this->mainInfo->confirm_amount-$this->mainInfo->amount);
				$contract->purchase_weight-=($this->mainInfo->confirm_weight-$this->mainInfo->weight);
				$contract->purchase_fee-=($total_of-$totalF);
				$contract->update();
					
				$mainJson = $contract->datatoJson();
				$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
					
			}
			//
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			//修改主体信息
			$olddata=$this->mainInfo;
			$oldJson=$olddata->datatoJson();
			$this->mainInfo->confirm_amount = 0;
			$this->mainInfo->confirm_weight = 0;
			$this->mainInfo->confirm_cost = 0;
			$this->mainInfo->weight_confirm_status = 0;
			$this->mainInfo->price_confirm_status = 0;
			$this->mainInfo->price_amount=$totalF;
			$this->mainInfo->update();
			
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);			
				
			//取消审单日志
			$operation = "取消审单";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
			ProfitChange::createNew('purchase',$this->commonForm->id,1);//			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollback();
			if($e->message=='已销票')
				return "已销票";
			return "操作失败";
		}		
		return true;
	}
	
	
	/**
	 * 计算采购成本价
	 */
	public function caclulateItemCost($item_id)
	{
		//获取采购单价格+票价+费用登记，根据采购的吨数平均一个采购成本价，如果费用登记没有关联明细，则价格要平均所有的吨数来计算
		//采购成本单价=采购单价+采购单费用【均摊】+仓库费用【均摊】-采购返利【均摊】- 仓库收入【均摊】- 仓库返利【均摊】+ 发票成本		
		
	}
	
	
	/*
	 * 查看是否是登钢爵淼的库存采购单
	 */
	public function isDgJmPurchase()
	{
		return ($this->mainInfo->title->short_name=='登钢商贸'||$this->mainInfo->title->short_name=='爵淼实业')&&$this->mainInfo->purchase_type=='normal';
	}
	
	
	/*
	 * 重写获取明细方法
	 */
	protected function getDetailByFormId($id)
	{
		$result = PurchaseDetail::model()->findAll("purchase_id = :purchase_id",array(":purchase_id"=>$id));
		if (count($result)>0)
			return $result;
	}
}