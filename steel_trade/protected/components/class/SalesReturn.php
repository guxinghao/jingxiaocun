<?php

class SalesReturn extends BaseForm
{
	public $has_detials = true;//采购单有明细
	public $mainModel = "FrmSalesReturn";
	public $isAutoApprove = false;
	public $busName="销售退货";
	
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('salesReturn')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->salesReturn;
				$this->details=$model->salesReturn->salesReturnDetails;
			}
		}
	}
	
	//------------------------------------
	/**
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$salesReturn = new FrmSalesReturn();
		$salesReturn->company_id = $data->company_id;
		$salesReturn->client_id = $data->client_id;
		$salesReturn->title_id = $data->title_id;
		$salesReturn->return_date = strtotime($data->return_date);
		$salesReturn->team_id = $data->team_id;
		$salesReturn->travel=$data->travel;
		$salesReturn->is_yidan = $data->is_yidan?$data->is_yidan:0;
		$salesReturn->return_type =$data->return_type;
		$salesReturn->tran_type =$data->tran_type;		
		$salesReturn->warehouse_id = $data->warehouse_id;
		$salesReturn->amount=$data->amount;
		$salesReturn->weight=$data->weight;
		$salesReturn->back_reason_val=$data->back_reason;
		if($data->back_reason=='-1')
		{
			$salesReturn->back_reason=$data->other_reason;
		}else{
			$salesReturn->back_reason=FrmSales::$reasons[$data->back_reason];
		}
		if($data->return_type=='supply')
		{
			$salesReturn->supply_id = $data->supply_id;
		}		
		$salesReturn->contact_id = $data->contact_id;		
		
		if($data->is_gaokai)
		{
			$salesReturn->gaokai_money=$data->gaokai_money?$data->gaokai_money:0;
			$salesReturn->gaokai_target=$data->gaokai_target?$data->gaokai_target:0;
		}				
		if ($salesReturn->insert()){
			$mainJson = $salesReturn->datatoJson();
			$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $salesReturn;
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
		foreach ($data as $detail) {
			//获取明细 
			$salesReturn_detail = new SalesReturnDetail();
			$salesReturn_detail->return_amount = $detail->return_amount;
			$salesReturn_detail->return_weight = $detail->return_weight;
			$salesReturn_detail->return_price =$detail->return_price;
			$salesReturn_detail->sales_return_id =$this->mainInfo->id;
			$salesReturn_detail->product_id = $detail->product_id;//品名std
			$salesReturn_detail->brand_id = $detail->brand_id;//产地
			$salesReturn_detail->texture_id = $detail->texture_id;//材质
			$salesReturn_detail->rank_id = $detail->rank_id;//规格
			$salesReturn_detail->card_no=$detail->card_no;
			if(empty($detail->length))
			{
				$salesReturn_detail->length =0;
			}else{
				$salesReturn_detail->length = $detail->length;//长度
			}
			if ($salesReturn_detail->insert())
			{
				//日志
				$detailJson = $salesReturn_detail->datatoJson();
				$dataArray = array("tableName"=>"SalesReturnDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($detail_array, $salesReturn_detail);				
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
		
		//创建高开往来
		if($this->mainInfo->gaokai_target)
		{
			$type = "GKMX";//类型
			$turnover_direction = "need_charge";//应付
			$title_id = $this->mainInfo->title_id;//公司抬头
			$target_id = $this->mainInfo->gaokai_target;//往来对端公司
			$client_id = $this->mainInfo->client_id;
			$amount = 1;//重量
			$price = $this->mainInfo->gaokai_money;//单价
			$fee = $this->mainInfo->gaokai_money;
			$common_forms_id = $this->commonForm->id;
			$form_detail_id = 0;
			$ownered_by = $this->commonForm->owned_by;
			$created_by=$this->commonForm->created_by;
			$is_yidan=$this->mainInfo->is_yidan;
			$created_at=strtotime($this->commonForm->form_time);
			$big_type='gaokai';
			$description='单号：'.$this->commonForm->form_sn.',销售退货补高开往来';
			$turnarray = compact("type","turnover_direction","title_id","target_id","client_id","amount","price","fee",
					"common_forms_id",'big_type',"form_detail_id","ownered_by",'created_by','description','is_yidan','created_at'
			);
			$result = Turnover::createBill($turnarray);				
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			unset($result);
			unset($turnarray);
		}
		
		foreach ($this->details as $each) {
			$type = "XSTH";//类型
			$turnover_direction = "need_pay";//应付
			$title_id = $this->mainInfo->title_id;//公司抬头
			$target_id = $this->mainInfo->company_id;//往来对端公司
			$client_id = $this->mainInfo->client_id;//往来对端公司
			$amount = $each->return_weight;//重量
			$price = $each->return_price;//单价
			$fee = $price*$amount;
			$common_forms_id = $this->commonForm->id;
			$form_detail_id = $each->id;
			$ownered_by = $this->commonForm->owned_by;
			$created_by=$this->commonForm->created_by;
			$is_yidan=$this->mainInfo->is_yidan;
			$created_at=strtotime($this->commonForm->form_time);
			$big_type='sales';
			$description='单号：'.$this->commonForm->form_sn.','.DictGoodsProperty::getProName($each->brand_id).'|'.DictGoodsProperty::getProName($each->product_id).'|'.DictGoodsProperty::getProName($each->rank_id).'*'.$each->length.'*'.DictGoodsProperty::getProName($each->texture_id);
			$turnarray = compact("type","turnover_direction","title_id","target_id","client_id","amount","price","fee",
			"common_forms_id",'big_type',"form_detail_id","ownered_by",'created_by','description','is_yidan','created_at'
			);			
			$result = Turnover::createBill($turnarray);
			
			$mainJson = $result->datatoJson();
			$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			
		}
	}
	

	/**
	 * 采购单提交后动作
	 */
	protected function afterSubmitForm()
	{
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
		$message['receivers'] = User::model()->getOperationList("销售退货:审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."提交了销售退货单：".$baseform->form_sn.",请尽快审核。";
		$message['title'] = "销售退货通知";
		$message['url'] = Yii::app()->createUrl('salesReturn/index',array('card_no'=>$baseform->form_sn));
		$message['type'] = "销售退货单";
		$message['big_type']='sale';
		$res = MessageContent::model()->addMessage($message);
		return true;	
	} 
	
	/**
	 * 取消提交后动作
	 */
	protected function afterCancelSubmitForm()
	{
		//修改往来
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;
		//直接查找往来表，作废往来,
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
	
	//-----------------------------------------------------------修改表单--------------------------------------------------------
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
			$this->mainInfo->travel=$data->travel;
			$this->mainInfo->return_date=strtotime($data->return_date);
			if ($this->mainInfo->update()){					
				$mainJson = $this->mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);					
				return true;
			}
		}elseif($this->commonForm->form_status=='approve'){
			$oldmain=$this->mainInfo;
			$oldJson=$oldmain->datatoJson();
			$this->mainInfo->return_date=strtotime($data->return_date);
			if ($this->mainInfo->update()){
				$mainJson = $this->mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
				return true;
			}		
		}elseif($this->commonForm->form_status=='unsubmit'){
			//所有信息都能修改
			$oldmain=$this->mainInfo;
			$oldJson=$oldmain->datatoJson();
			$this->mainInfo->company_id = $data->company_id;
			$this->mainInfo->client_id = $data->client_id;
			$this->mainInfo->title_id = $data->title_id;
			$this->mainInfo->return_date = strtotime($data->return_date);
			$this->mainInfo->team_id = $data->team_id;
			$this->mainInfo->travel=$data->travel;
			$this->mainInfo->is_yidan = $data->is_yidan?$data->is_yidan:0;
			$this->mainInfo->return_type =$data->return_type;
			$this->mainInfo->tran_type =$data->tran_type;
			$this->mainInfo->warehouse_id = $data->warehouse_id;
			$this->mainInfo->amount=$data->amount;
			$this->mainInfo->weight=$data->weight;
			$this->mainInfo->back_reason_val=$data->back_reason;
			if($data->back_reason=='-1')
			{
				$this->mainInfo->back_reason=$data->other_reason;
			}else{
				$this->mainInfo->back_reason=FrmSales::$reasons[$data->back_reason];
			}
			if($data->return_type=='supply')
			{
				$this->mainInfo->supply_id = $data->supply_id;
			}else{
				$this->mainInfo->supply_id = 0;
			}
			$this->mainInfo->contact_id = $data->contact_id;		
			
			if($data->is_gaokai)
			{
				$this->mainInfo->gaokai_money=$data->gaokai_money?$data->gaokai_money:0;
				$this->mainInfo->gaokai_target=$data->gaokai_target?$data->gaokai_target:0;
			}else{
				$this->mainInfo->gaokai_money=0;
				$this->mainInfo->gaokai_target=0;
			}		
			
			if ($this->mainInfo->update()){
				$mainJson = $this->mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
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
					$dataArray = array("tableName"=>"SalesReturnDetail","newValue"=>"","oldValue"=>$mainJson);
					$this->dataLog($dataArray);
				}
			}else{
				foreach ($details as $each)
				{
					if(!in_array($each->id,$id_array))
					{
						$each->delete();
						$mainJson = $each->datatoJson();
						$dataArray = array("tableName"=>"SalesReturnDetail","newValue"=>"","oldValue"=>$mainJson);
						$this->dataLog($dataArray);
					}
				}
			}
			foreach ($data as $data_each)
			{
				if($data_each->id)
				{
					//修改此条数据
					$detail_data=SalesReturnDetail::model()->findByPk($data_each->id);
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
						$dataArray = array("tableName"=>"SalesReturnDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$this->dataLog($dataArray);
					}
				}else{
					//新建
					$pcd=new SalesReturnDetail();
					foreach ($data_each as $k=>$v)
					{
						if($key=='id')continue;
						$pcd->$k=$v;
					}
					$pcd->sales_return_id=$this->mainInfo->id;
					$pcd->insert();
					$mainJson = $pcd->datatoJson();
					$dataArray = array("tableName"=>"SalesReturnDetail","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);
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
			$details=SalesReturnDetail::model()->findAllByAttributes(array('sales_return_id'=>$this->mainInfo->id));
			foreach ($details as $detail) {
				$turnover = $detail->turnover(array("condition"=>"turnover_type='XSTH' and common_forms_id = ".$this->commonForm->id." and status!='delete'"));
				if (!$turnover){//如果新增的明细没有对应往来，需要创建一条
					$type = "XSTH";//类型
					$turnover_direction = "need_pay";//应付
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $this->mainInfo->company_id;//往来对端公司
					$client_id = $this->mainInfo->client_id;
					$amount = $detail->return_weight;//重量
					$price = $detail->return_price;//单价
					$fee = $price*$amount;
					$common_forms_id = $this->commonForm->id;
					$form_detail_id = $detail->id;
					$ownered_by = $this->commonForm->owned_by;
					$created_by =$this->commonForm->created_by;
					$is_yidan=$this->mainInfo->is_yidan;
					$created_at=strtotime($this->commonForm->form_time);
					$big_type='sales';
					$description='单号：'.$this->commonForm->form_sn.','.DictGoodsProperty::getProName($detail->brand_id).'|'.DictGoodsProperty::getProName($detail->product_id).'|'.DictGoodsProperty::getProName($detail->rank_id).'*'.$detail->length.'*'.DictGoodsProperty::getProName($detail->texture_id);					
					$turnarray = compact("type","turnover_direction","title_id","client_id",'big_type',"target_id","amount","price","fee",
						"common_forms_id","form_detail_id","ownered_by",'created_by','description','yi_yidan','created_at');					
					$result = Turnover::createBill($turnarray);
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);					
				}else{
					//
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $this->mainInfo->company_id;//往来对端公司
					$client_id = $this->mainInfo->client_id;
					$amount = $detail->return_weight;//重量
					$price = $detail->return_price;//单价
					$fee = $price*$amount;
					$common_forms_id = $this->commonForm->id;
					$form_detail_id = $detail->id;
					$ownered_by = $this->commonForm->owned_by;
					$is_yidan=$this->mainInfo->is_yidan;
					$created_at=strtotime($this->commonForm->form_time);
					$description='单号：'.$this->commonForm->form_sn.','.DictGoodsProperty::getProName($detail->brand_id).'|'.DictGoodsProperty::getProName($detail->product_id).'|'.DictGoodsProperty::getProName($detail->rank_id).'*'.$detail->length.'*'.DictGoodsProperty::getProName($detail->texture_id);					
					$turnarray = compact("title_id","target_id","client_id","amount","price","fee","common_forms_id","form_detail_id",
						"ownered_by",'description','is_yidan','created_at');					
					$oldturn=$turnover;
					$oldJson=$oldturn->datatoJson();
					$result=Turnover::updateBill($turnover->id, $turnarray);
					
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
				}
			}
			//高开往来
			$gaokaiturn=Turnover::model()->find('common_forms_id='.$this->commonForm->id.' and status!="delete" and big_type="gaokai"');
			if($this->mainInfo->gaokai_target)
			{
				if($gaokaiturn)
				{
					$gaokaiturn->title_id=$this->mainInfo->title_id;
					$gaokaiturn->ownered_by=$this->commonForm->owned_by;
					$gaokaiturn->is_yidan=$this->mainInfo->is_yidan;
					$gaokaiturn->price=$this->mainInfo->gaokai_money;//单价
					$gaokaiturn->fee=-$this->mainInfo->gaokai_money;
					$gaokaiturn->target_id=$this->mainInfo->gaokai_target;
					$gaokaiturn->update();
				}else{
					//创建高开往来				
					$type = "GKMX";//类型
					$turnover_direction = "need_charge";//应付
					$title_id = $this->mainInfo->title_id;//公司抬头
					$target_id = $this->mainInfo->gaokai_target;//往来对端公司
					$client_id = $this->mainInfo->client_id;
					$amount = 1;//重量
					$price = $this->mainInfo->gaokai_money;//单价
					$fee = $this->mainInfo->gaokai_money;
					$common_forms_id = $this->commonForm->id;
					$form_detail_id = 0;
					$ownered_by = $this->commonForm->owned_by;
					$created_by=$this->commonForm->created_by;
					$is_yidan=$this->mainInfo->is_yidan;
					$created_at=strtotime($this->commonForm->form_time);
					$big_type='gaokai';
					$description='单号：'.$this->commonForm->form_sn.',销售退货补高开往来';
					$turnarray = compact("type","turnover_direction","title_id","target_id","client_id","amount","price","fee",
							"common_forms_id",'big_type',"form_detail_id","ownered_by",'created_by','description','is_yidan','created_at'
					);
					$result = Turnover::createBill($turnarray);
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
					$this->dataLog($dataArray);				
				}
			}else{
				if($gaokaiturn)$gaokaiturn->delete();
			}			
			
			
			//删除明细已经删除对应的往来
			$turnovers=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status !="delete" and big_type!="gaokai"');
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
		}
		return true;
	}
	
	/**
	 * 作废
	 */
	protected function afterDeleteForm()
	{
		//作废往来
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
	}
	
	/**
	 * 审核通过
	 */
	protected function afterApproveForm()
	{
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的销售退货单：".$baseform->form_sn."已经审核通过。";
			$message['title'] = "审核通知";
			$message['type'] = "销售退货单";
			$message['big_type']='sale';
			$res = MessageContent::model()->addMessage($message);
		}
		//设置销售退货可开票信息
		/*
		$main = $this->mainInfo;
		$details = $main->salesReturnDetails;
		if($details){
			foreach($details as $each){
				$std = DictGoodsProperty::getFullProName($each->product_id);
				if($std == "螺纹钢" && $main->is_yidan == 0){
					//设置可开票明细
					$price = 0 - $each->return_weight * $each->return_price;
					$invoice = DetailForInvoice::setSalesInvoice($baseform->id,$each->id,$each->return_weight,$price,$main->title_id,$main->company_id,$main->client_id,1);
					if(!$invoice){
						return -1;
					}
				}
			}
		}
		*/
	}
	
	
	/**
	 * 审核拒绝
	 */
	protected function afterRefuseForm()
	{
		//往来改为未提交
		if (!is_array($this->details) || count($this->details)<=0) 
			return ;
		//直接查找往来表，
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
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的销售退货单：".$baseform->form_sn."审核已被拒绝。";
			$message['title'] = "审核通知";
			$message['type'] = "销售退货单";
			$message['big_type']='sale';
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
	 * 取消审核
	 */
	protected function afterCancelApproveForm()
	{
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的销售退货单：".$baseform->form_sn."已被取消审核。";
			$message['title'] = "审核通知";
			$message['type'] = "销售退货单";
			$message['big_type']='sale';
			$res = MessageContent::model()->addMessage($message);
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
		if ($main == null || count($detailArray)<=0) return;
		
		$transaction=Yii::app()->db->beginTransaction();
		try {
			//修改基本信息	
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
			$this->mainInfo->return_date=strtotime($main->return_date);
			$this->mainInfo->is_yidan=$main->is_yidan?$main->is_yidan:0;
			$this->mainInfo->travel=$main->travel;
			$this->mainInfo->confirm_amount = $main->confirm_amount;
			$this->mainInfo->confirm_weight = $main->confirm_weight;
			$this->mainInfo->confirm_cost = $main->confirm_cost;
			$this->mainInfo->confirm_amount = $main->confirm_cost;			
			
			if($this->mainInfo->gaokai_target){
				$gaokai_turn=Turnover::model()->find('common_forms_id='.$this->commonForm->id.' and status!="delete" and big_type="gaokai"');
				if($gaokai_turn){
					$gaokai_turn->is_yidan=$this->mainInfo->is_yidan;
					$gaokai_turn->update();
				}
			}			
			
			if ($main->confirm_amount && $main->confirm_weight)
				$this->mainInfo->weight_confirm_status = 1;
			$this->mainInfo->update();
			
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmSalesReturn","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
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
					$dataArray = array("tableName"=>"SalesReturnDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
					
					//更新采购单应付往来
					$turn=$val->turnover(array("condition"=>"turnover_type='XSTH' and common_forms_id = ".$this->commonForm->id." and status!='delete'"));
					if($turn)
					{
						$olddata=$turn;
						$oldJson=$olddata->datatoJson();
						$turn->amount=$item->fix_weight;
						$turn->price=$item->fix_price;
						$turn->fee=$item->fix_weight*$item->fix_price;
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

					//开票信息确认
 					if(!$this->mainInfo->is_yidan){
 						$invoice = DetailForInvoice::model()->find("form_id={$this->commonForm->id} and detail_id={$val->id}");
 						// $invoice = $each->detailsInvoice;
 						if(!$invoice){
 							$invoice = new DetailForInvoice();
 							$invoice->type = 'salesreturn';
 							$invoice->form_id = $this->commonForm->id;
 							$invoice->detail_id = $val->id;
 							$invoice->weight = $val->fix_weight;
 							$invoice->money = -$val->fix_weight*$val->fix_price;
 							$invoice->title_id = $this->mainInfo->title_id;
 							$invoice->company_id = $this->mainInfo->company_id;
 							$invoice->client_id = $this->mainInfo->client_id;
 							$invoice->insert();
 						}else{
 							$invoice->weight = $val->fix_weight;
 							$invoice->money = $val->fix_weight*$val->fix_price;
 							$invoice->title_id = $this->mainInfo->title_id;
 							$invoice->company_id = $this->mainInfo->company_id;
 							$invoice->client_id = $this->mainInfo->client_id;
 							if(floatval($invoice->checked_weight)>$invoice->weight){
 								throw new CException("已开票"); 								
 							}
 							$invoice->update();
 						}
 					}else{
 						$invoice = DetailForInvoice::model()->find("form_id={$this->commonForm->id} and detail_id={$val->id}");
 						// $invoice = $each->detailsInvoice;
 						if($invoice){
 							if(floatval($invoice->checked_weight))throw new CException("已开票"); 							
 							$invoice->delete();
 						}
 					}

				}
			}
			//审单日志
			$operation = "审单";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();
			if($e->message=='已开票'){
				return $e->message;
			}
			return;
		}
		return true;
	}
	/*
	 * 取消审单
	 */
	public function cancelConfirm()
	{
		$transaction = Yii::app()->db->beginTransaction();
		try{
			//更新采购单明细
			foreach ($this->details as $each)
			{
				//回滚采购应付往来
				$turn=$each->turnover(array("condition"=>"turnover_type='XSTH' and common_forms_id = ".$this->commonForm->id." and status !='delete'"));
				$olddata=$turn;
				$oldJson=$olddata->datatoJson();
				$turn->price=$each->return_price;
				$turn->amount=$each->return_weight;
				$turn->fee=$each->return_price*$each->return_weight;
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
				$each->fix_price=0;
				$each->update();
				
				$mainJson =$each->datatoJson();
				$dataArray = array("tableName"=>"PurchaseDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);

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
				if(!$this->mainInfo->is_yidan){
					$invoice = DetailForInvoice::model()->find("form_id={$this->commonForm->id} and detail_id={$each->id}");
					if($invoice){
						if(floatval($invoice->checked_weight))throw new CException("已开票"); 							
						$invoice->delete();
					}
				}
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
			$this->mainInfo->update();
			
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchase","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			//取消审单日志
			$operation = "取消审单";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
			$transaction->commit();

		}catch(Exception $e){
			$transaction->rollBack();
			if($e->message == '已开票'){
				return $e->messgae;
			}
			return;
		}
		return true;
	}
}
