<?php
class Contract extends BaseForm
{
	public $mainModel = "FrmPurchaseContract";
	public $detailModel = "PurchaseContractDetail";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="采购合同";
	

	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('contract')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->contract;
				$this->details=$model->contract->purchaseContractDetails;
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
		$mainInfo=new FrmPurchaseContract();
	
		$mainInfo->contract_no=trim($data->contract_no);
		$mainInfo->dict_company_id=$data->dict_company_id;
		$mainInfo->dict_title_id=$data->dict_title_id;
		$mainInfo->team_id=$data->team_id;
		$mainInfo->is_yidan=$data->is_yidan;//是否乙单
		$mainInfo->contact_id=$data->contact_id;
		$mainInfo->warehouse_id=$data->warehouse_id;//仓库
		$mainInfo->amount=$data->amount;
		$mainInfo->weight=$data->weight;
		$mainInfo->purchase_amount=0;
		$mainInfo->purchase_weight=0;
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
	}
	
	/**
	 * 基类方法重构
	 *
	 * 保存明细
	 */
	protected function saveDetails($data)
	{
		$detail=array();
		if (!is_array($data)||count($data)<=0)
			return;
		foreach ($data as $each)
		{
			$pcd=new PurchaseContractDetail();
			$pcd->product_id=$each->product_id;
			$pcd->rank_id =$each-> rank_id;
			$pcd->texture_id=$each->texture_id;
			$pcd->brand_id=$each->brand_id;
			$pcd->price=$each->price;			
			$pcd->amount=$each->amount;
			$pcd->weight=$each->weight;
			if(empty($each->length))
			{
				$pcd->length=0;
			}else{
				$pcd->length=$each->length;
			}
			
			$pcd->purchase_contract_id=$this->mainInfo->id;
			if($pcd->insert())
			{
				//明细日志
				$detailJson = $pcd->datatoJson();
				$dataArray = array("tableName"=>"PurchaseContractDetail","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				
				
				array_push($detail,$pcd);
			}
		}
		return $detail;
	}
	
	/**
	 * 基类方法重构
	 *
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
		//创建合同往来//不了
		$fee=0;
		$amount=0;
		foreach ($this->details as $each)
		{
			$fee += $each->price*$each->weight;
			$amount+=$each->weight;
		}
		$this->mainInfo->fee=$fee;
		$this->mainInfo->update();
		
// 		$da=array();
// 		$da['type']="CGHT";
// 		$da['turnover_direction']="need_pay";
// 		$da['title_id']= $this->mainInfo->dict_title_id;
// 		$da['target_id']=$this->mainInfo->dict_company_id;
// 		$da['proxy_company_id']='';
// 		$da['amount']=$amount;
// 		$da['price']=$fee/$amount;
// 		$da['fee']=$fee;
// 		$da['common_forms_id']=$this->commonForm->id;
// 		$da['form_detail_id']='';
// 		$da['ownered_by']=$this->commonForm->owned_by;
// 		$da['created_by']=$this->commonForm->created_by;
// 		$da['description']='单号'.$this->commonForm->form_sn."创建采购合同往来";
// 		$result=Turnover::createBill($da);
		
// 		//日志
// 		$mainJson = $result->datatoJson();
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
// 		$this->dataLog($dataArray);
	
		
	}
	
	/****------------------------基类方法重构之提交表单----------------****/
	
	/**
	 * 提交表单后的动作
	 */
	protected function afterSubmitForm()
	{
// 		//修改合同往来
// 		$id=$this->commonForm->cag->id;
// 		$oldturn=Turnover::getOne($id);
// 		$oldJson=$oldturn->datatoJson();
// 		$update=array('status'=>'submited');
// 		$result=Turnover::updateBill($id, $update);
// 		//新增提交日志
// 		$mainJson = $result->datatoJson();
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 		$this->dataLog($dataArray);
	
		//发送消息
		$baseform=$this->commonForm;
		$message = array();
		$message['receivers'] = User::model()->getOperationList("采购合同:审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."提交了采购合同：".$baseform->form_sn.",请尽快审核。";
		$message['title'] = "采购合同通知";
		$message['url'] = Yii::app()->createUrl('contract/index',array('card_no'=>$baseform->form_sn));
		$message['type'] = "采购合同";
		$message['big_type']='purchase';
		$res = MessageContent::model()->addMessage($message);
	}
	
	/****------------------------基类方法重构之取消提交表单----------------****/
	
	/**
	 * 取消提交表单后的动作
	 */
	protected function afterCancelSubmitForm()
	{
		//修改合同往来
// 		$id=$this->commonForm->cag->id;
// 		$oldturn=Turnover::getOne($id);
// 		$oldJson=$oldturn->datatoJson();
// 		$update=array('status'=>'unsubmit');
// 		$result=Turnover::updateBill($id, $update);
// 		//新增取消提交日志
// 		$mainJson = $result->datatoJson();
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 		$this->dataLog($dataArray);
	}
	
	
	/****------------------------基类方法重构之修改表单----------------****/
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		$contract=$this->mainInfo;
		$oldJson=$this->mainInfo->datatoJson();
		if($this->commonForm->form_status=='submited')
		{
			if($data->contact_id)
			{
				$contract->contact_id=$data->contact_id;
			}
		}elseif($this->commonForm->form_status=='unsubmit'){
			foreach ($data as $k=>$v)
			{
				$contract->$k=$v;
			}
		}
		if($contract->update())
		{
			//rizhi
			$mainJson = $contract->datatoJson();			
			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
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
						$each->delete();
						//日志
						$oldJson=$each->datatoJson();
						$dataArray = array("tableName"=>"purchaseContractDetail","newValue"=>"","oldValue"=>$oldJson);
						$this->dataLog($dataArray);
					}
				}else{
					foreach ($details as $each)
					{
						if(!in_array($each->id,$id_array))
						{
							$each->delete();
							$oldJson=$each->datatoJson();
							$dataArray = array("tableName"=>"purchaseContractDetail","newValue"=>"","oldValue"=>$oldJson);
							$this->dataLog($dataArray);
						}
					}
				}
				foreach ($data as $data_each)
				{	
					if($data_each->id)
					{
						//修改此条数据
						$detail_data=PurchaseContractDetail::getOne($data_each->id);
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
							//rizhi
							$mainJson = $detail_data->datatoJson();
							$dataArray = array("tableName"=>"PurchaseContractDetail","newValue"=>$mainJson,"oldValue"=>$oldJson);
							$this->dataLog($dataArray);
						}					
					}else{
						//新建
						$pcd=new PurchaseContractDetail();
						$pcd->product_id=$data_each->product_id;
						$pcd->rank_id =$data_each-> rank_id;
						$pcd->texture_id=$data_each->texture_id;
						$pcd->brand_id=$data_each->brand_id;
						$pcd->price=$data_each->price;
						$pcd->amount=$data_each->amount;
						$pcd->weight=$data_each->weight;
						if(empty($data_each->length))
						{
							$pcd->length=0;
						}else{
							$pcd->length=$data_each->length;
						}
						$pcd->purchase_contract_id=$this->mainInfo->id;
						$pcd->insert();		
						//rizhi
						$mainJson = $pcd->datatoJson();
						$dataArray = array("tableName"=>"PurchaseContractDetail","newValue"=>$mainJson,"oldValue"=>"");
						$this->dataLog($dataArray);
													
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
		if($this->commonForm->form_status=='submited')
		{
			return true;
		}
		if (!array($this->details) || count($this->details)<=0 )return;
		//修改合同往来
		$id=$this->commonForm->cag->id;
// 		$oldturn=Turnover::getOne($id);
// 		$oldJson=$oldturn->datatoJson();
		$amount=0;
		$fee=0;
		$detailss=PurchaseContractDetail::model()->findAllByAttributes(array('purchase_contract_id'=>$this->mainInfo->id));
		foreach ($detailss as $each)
		{
			$fee+=$each->price*$each->weight;
			$amount+=$each->weight;
		}
		
		$this->mainInfo->fee=$fee;
		$this->mainInfo->update();
		
// 		$price=$fee/$amount;
// 		$company_id=$this->mainInfo->dict_title_id;
// 		$vendor_id=$this->mainInfo->dict_company_id;
// 		$owned_by=$this->commonForm->owned_by;		
// 		$update=array('fee'=>$fee,'price'=>$price,'title_id'=>$company_id,'target_id'=>$vendor_id,'amount'=>$amount,'ownered_by'=>$owned_by);
// 		$result=Turnover::updateBill($id, $update);
// 		//新增日志
// 		$mainJson = $result->datatoJson();		
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 		$this->dataLog($dataArray);
		
	}
	
	
	/****------------------------基类方法重构之作废表单----------------****/
	
	/**
	 * 作废后的操作
	 */
	protected function afterDeleteForm()
	{
		//作废合同往来
// 		$id=$this->commonForm->cag->id;
// 		$oldturn=Turnover::getOne($id);
// 		$oldJson=$oldturn->datatoJson();
// 		$update=array('status'=>'delete');
// 		$result=Turnover::updateBill($id, $update);
// 		//新增作废日志
// 		$mainJson = $result->datatoJson();		
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 		$this->dataLog($dataArray);
		
	}
	
	
	/****------------------------基类方法重构之审核表单----------------****/
	/**
	 * 审核通过后续操作
	 */
	protected function afterApproveForm()
	{
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的采购合同：".$baseform->form_sn."已经审核通过。";
			$message['title'] = "审核通知";
			$message['type'] = "采购合同";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
	
		//新增审核通过日志
	}
	
	/**
	 * 表单拒绝后续操作
	 */
	public function afterRefuseForm()
	{
// 		//修改合同往来
// 		$id=$this->commonForm->cag->id;
// 		$oldturn=Turnover::getOne($id);
// 		$oldJson=$oldturn->datatoJson();
// 		$update=array('status'=>'unsubmit');
// 		$result=Turnover::updateBill($id, $update);
		
// 		$mainJson = $result->datatoJson();
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 		$this->dataLog($dataArray);
		
		//新增审核拒绝日志
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的采购合同：".$baseform->form_sn."审核已被拒绝。";
			$message['title'] = "审核通知";
			$message['type'] = "采购合同";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	/**
	 * 取消审核通过后续操作
	 */
	protected function afterCancelApproveForm()
	{
		//修改合同往来
// 		$id=$this->commonForm->cag->id;
// 		$update=array('status'=>'unsubmit');
// 		Turnover::updateBill($id, $update);
		//新增取消审核日志
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的采购合同：".$baseform->form_sn."已被取消审核。";
			$message['title'] = "审核通知";
			$message['type'] = "采购合同";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
	}
	
	/*
	 * 履约
	 */
	public function finished()
	{
		if ($this->commonForm == null) return false;
		//查询所有的采购单，如果有未审单的采购单则不能履约
 		$common = CommonForms::model()->with(array("purchase"=>array("condition"=>"purchase.frm_contract_id = ".$this->commonForm->id)))->findAll("t.form_status != 'delete' and t.form_type='CGD'");
 		if (!is_array($common) || count($common)<=0 ) return 'nolink';//没有关联
 		
		foreach ($common as $item){//如果有未审单的采购单，直接返回3
			if($item->form_status != "approve" ||$item->purchase->weight_confirm_status !=1 || $item->purchase->price_confirm_status !=1)
			{
				$result='3';
				return $result;				
			}
		}		
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$oldturn=$this->mainInfo;
			$oldJson=$oldturn->datatoJson();
			$this->mainInfo->is_finish = 1;
			$this->mainInfo->update();
			
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			
			//新增合同已付往来，补差值,相当新增采购记录
			$total=$this->commonForm->cag(array('condition'=>'turnover_type="CGHT"'))->fee;
			$total_bought=$this->mainInfo->purchase_fee;
			//查询所有的采购单总金额
// 			foreach ($common as $item){
// 				$total_bought += $item->purchase->price_amount;
// 			}
			$fee=$total-$total_bought;
			
// 			$da=array();
// 			$da['type']="HTBL";//合同补录采购单
// 			$da['turnover_direction']="payed";
// 			$da['title_id']= $this->mainInfo->dict_title_id;
// 			$da['target_id']=$this->mainInfo->dict_company_id;
// 			$da['proxy_company_id']='';
// 			$da['amount']='';
// 			$da['price']=$fee;
// 			$da['fee']=$fee;
// 			$da['status'] = 'submited';
// 			$da['common_forms_id']=$this->commonForm->id;
// 			$da['form_detail_id']='';
// 			$da['ownered_by']=$this->commonForm->owned_by;
// 			$da['created_by']=currentUserId();
// 			$da['description']='单号'.$this->commonForm->form_sn."创建合同补录往来";
// 			$result=Turnover::createBill($da);
			
// 			$mainJson = $result->datatoJson();
// 			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>"");
// 			$this->dataLog($dataArray);
			
			//操作日志
			$operation = "履约";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollback();
			return;
		}
		return true;
	}
	
	/**
	 * 取消履约
	 */
	public function cancelfinished()
	{
		if ($this->commonForm == null) return false;
		if ($this->mainInfo->is_finish != 1) return false;
		
		$transaction=Yii::app()->db->beginTransaction();
		try {
// 			//取消履约
			$olddata=$this->mainInfo;
			$oldJson=$olddata->datatoJson();
			$this->mainInfo->is_finish = 0;
			$this->mainInfo->update();
			
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPurchaseContract","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);			
			
// 			$turnover = Turnover::model()->find("common_forms_id = :cid and turnover_type = 'HTBL'",array(":cid"=>$this->commonForm->id));
// 			$olddata=$turnover;
// 			$oldJson=$olddata->datatoJson();
// 			if ($turnover && $turnover->status !="delete"){
// 				$turnover->status = "delete";
// 				$turnover->update();
				
// 				$mainJson = $turnover->datatoJson();
// 				$dataArray = array("tableName"=>"TurnOver","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 				$this->dataLog($dataArray);
// 			}
			
			//操作日志
			$operation = "取消履约";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollback();
			return;
		}
		return true;	
	}
	
	
	/**
	 * 查询
	 */
	public function getDetailByFormId($id)
	{
		$result = PurchaseContractDetail::model()->findAll("purchase_contract_id = :purchase_contract_id",array(":purchase_contract_id"=>$id));
		if (count($result)>0)
			return $result;
	}
}