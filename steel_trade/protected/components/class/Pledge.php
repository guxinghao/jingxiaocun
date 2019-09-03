<?php
class Pledge extends BaseForm
{
	public $mainModel = "FrmPledgeRedeem";
	public $detailModel = "";
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName="托盘赎回";

	public function __construct($id) 
	{
		if (!$id) return ;
		$model = CommonForms::model()->with('frmPledge')->findByPK($id);
		if (!$model) return ;
		$this->commonForm = $model;
		$this->mainInfo = $model->frmPledge;
	}
	
	/****------------------------基类方法重构之创建表单----------------****/
	/**
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo=new FrmPledgeRedeem();
	
		$mainInfo->title_id=$data->title_id;
		$mainInfo->company_id=$data->company_id;
		$mainInfo->pledge_info_id=$data->pledge_info_id;
		$mainInfo->purchase_id=$data->purchase_id;
		$mainInfo->total_fee=numChange($data->total_fee);
		$mainInfo->interest_fee=numChange($data->interest_fee);
		$mainInfo->brand_id=$data->brand_id;
		$mainInfo->product_id=$data->product_id;
		$mainInfo->weight=numChange($data->weight);
		if($mainInfo->insert()){
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPledgeRedeem","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
	}
	
	/**
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
// 		//付款往来
// 		$da=array();
// 		$da['type']="TPSH";
// 		$da['turnover_direction']="payed";
// 		$da['title_id']= $this->mainInfo->title_id;
// 		$da['target_id']=$this->mainInfo->company_id;
// 		$da['proxy_company_id']='';
// 		$da['amount']=$this->mainInfo->weight;
// 		$da['fee']=$this->mainInfo->total_fee;
// 		$da['price']=$da['fee']/$da['amount'];
// 		$da['common_forms_id']=$this->commonForm->id;
// 		$da['form_detail_id']='';
// 		$da['ownered_by']=$this->commonForm->owned_by;
// 		$da['created_by']=$this->commonForm->created_by;
// 		$da['description']='单号：'.$this->commonForm->form_sn."，".DictGoodsProperty::getProName($this->mainInfo->brand_id).'|'.DictGoodsProperty::getProName($this->mainInfo->product_id);
// 		$result=Turnover::createBill($da);
		
		//日志
// 		$mainJson = $result->datatoJson();
// 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
// 		$this->dataLog($dataArray);
		
		//利息应付往来
		if($this->mainInfo->interest_fee)
		{
			$da=array();
			$da['type']="TPSH";
			
			$da['turnover_direction']="need_pay";
			$da['title_id']= $this->mainInfo->title_id;
			$da['target_id']=$this->mainInfo->company_id;
			$da['big_type']='purchase';
			$da['proxy_company_id']='';
			$da['amount']=0;
			$da['fee']=$this->mainInfo->interest_fee;
			$da['price']=0;
			$da['common_forms_id']=$this->commonForm->id;
			$da['form_detail_id']='';
			$da['ownered_by']=$this->commonForm->owned_by;
			$da['created_by']=$this->commonForm->created_by;
			$da['created_at']=strtotime($this->commonForm->form_time);
			$da['description']='单号：'.$this->commonForm->form_sn."，对应采购单号：".$this->mainInfo->purchase->baseform->form_sn."，".DictGoodsProperty::getProName($this->mainInfo->brand_id).'|'.DictGoodsProperty::getProName($this->mainInfo->product_id);
			$result=Turnover::createBill($da);
		}		
	}
	
	/****------------------------基类方法重构之提交表单----------------****/
	
	/**
	 * 提交表单后的动作
	 */
	protected function afterSubmitForm()
	{
		//修改往来
		$turns=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status!="delete"');
		if($turns)
		{
			foreach ($turns as $each)
			{
				$id=$each->id;
				$oldturn=Turnover::getOne($id);
				$oldJson=$oldturn->datatoJson();
				$update=array('status'=>'submited');
				$result=Turnover::updateBill($id, $update);
				//新增提交日志
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
		//发送消息
		$baseform=$this->commonForm;
		$message = array();
		$message['receivers'] = User::model()->getOperationList("托盘赎回:审核");
		$message['content'] = "业务员：".$baseform->belong->nickname."提交了托盘赎回单：".$baseform->form_sn.",请尽快审核。";
		$message['title'] = "托盘赎回通知";
		$message['url'] = Yii::app()->createUrl('pledge/index',array('card_no'=>$baseform->form_sn));
		$message['type'] = "托盘赎回单";
		$message['big_type']='purchase';
		$res = MessageContent::model()->addMessage($message);
	}
	
	/****------------------------基类方法重构之取消提交表单----------------****/
	
	/**
	 * 取消提交表单后的动作
	 */
	protected function afterCancelSubmitForm()
	{
		//修改往来
		$turns=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status!="delete"');
		if($turns)
		{
			foreach ($turns as $each)
			{
				$id=$each->id;
				$oldturn=Turnover::getOne($id);
				$oldJson=$oldturn->datatoJson();
				$update=array('status'=>'unsubmit');
				$result=Turnover::updateBill($id, $update);
				//新增提交日志
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
	}
	
	
	/****------------------------基类方法重构之修改表单----------------****/
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		$oldJson=$this->mainInfo->datatoJson();
		if($this->commonForm->form_status=='unsubmit'){
			$this->mainInfo->title_id=$data->title_id;
			$this->mainInfo->company_id=$data->company_id;
			$this->mainInfo->pledge_info_id=$data->pledge_info_id;
			$this->mainInfo->purchase_id=$data->purchase_id;
			$this->mainInfo->total_fee=numChange($data->total_fee);
			$this->mainInfo->interest_fee=numChange($data->interest_fee);
			$this->mainInfo->brand_id=$data->brand_id;
			$this->mainInfo->product_id=$data->product_id;
			$this->mainInfo->weight=numChange($data->weight);
		}
		if($this->mainInfo->update())
		{
			//rizhi
			$mainJson = $this->mainInfo->datatoJson();			
			$dataArray = array("tableName"=>"FrmPledgeRedeem","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			return true;
		}
		return false;
	}
	/**
	 * 修改以后操作
	 */
	protected function afterupdateForm()
	{
		if($this->commonForm->form_status=='submited'){return true;}	
		$turns=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status!="delete"');
		if($turns)
		{
			foreach ($turns as $each)
			{
				$id=$each->id;
				$oldturn=Turnover::getOne($id);
				$oldJson=$oldturn->datatoJson();
				
				if($this->mainInfo->interest_fee)
				{
					$da['title_id']= $this->mainInfo->title_id;
					$da['target_id']=$this->mainInfo->company_id;
					if($each->turnover_direction=='need_pay'){
						$da['amount']=1;
						$da['fee']=$this->mainInfo->interest_fee;
						$da['price']=$da['fee'];
					}
					$da['created_at']=strtotime($this->commonForm->form_time);
					$da['description']='单号：'.$this->commonForm->form_sn."，".DictGoodsProperty::getProName($this->mainInfo->brand_id).'|'.DictGoodsProperty::getProName($this->mainInfo->product_id);
					$result=Turnover::updateBill($id, $da);
					
					//新增日志
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}else{
					$each->delete();
					$mainJson = $result->datatoJson();
					$dataArray = array("tableName"=>"Turnover","newValue"=>'',"oldValue"=>$oldJson);
					$this->dataLog($dataArray);
				}
			}
		}else{
			//利息应付往来
			if($this->mainInfo->interest_fee)
			{
				$da=array();
				$da['type']="TPSH";
				$da['turnover_direction']="need_pay";
				$da['title_id']= $this->mainInfo->title_id;
				$da['target_id']=$this->mainInfo->company_id;
				$da['big_type']='purchase';
				$da['proxy_company_id']='';
				$da['amount']=0;
				$da['fee']=$this->mainInfo->interest_fee;
				$da['price']=0;
				$da['common_forms_id']=$this->commonForm->id;
				$da['form_detail_id']='';
				$da['ownered_by']=$this->commonForm->owned_by;
				$da['created_by']=$this->commonForm->created_by;
				$da['created_at']=$this->commonForm->form_time;
				$da['description']='单号：'.$this->commonForm->form_sn."，对应采购单号：".$this->mainInfo->purchase->baseform->form_sn."，".DictGoodsProperty::getProName($this->mainInfo->brand_id).'|'.DictGoodsProperty::getProName($this->mainInfo->product_id);
				$result=Turnover::createBill($da);
			}
		}
	}
	
	/**
	 * 作废后的操作
	 */
	protected function afterDeleteForm()
	{
		//修改往来
		$turns=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status!="delete"');
		if($turns)
		{
			foreach ($turns as $each)
			{
				$id=$each->id;
				$oldturn=Turnover::getOne($id);
				$oldJson=$oldturn->datatoJson();
				$update=array('status'=>'delete');
				$result=Turnover::updateBill($id, $update);
				//新增日志
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
	}
	
	/**
	 * 审核通过后续操作
	 */
	protected function afterApproveForm()
	{
		FrmPledgeRedeem::shareEqually($this->commonForm->id); //均摊
		
		$pledgeInfo=$this->mainInfo->pledgeInfo;
		if($pledgeInfo->r_limit=='1')
		{
			$sql='select * from pledge_redeemed where purchase_id='.$this->mainInfo->purchase_id.' and brand_id='.$this->mainInfo->brand_id;
		}elseif ($pledgeInfo->r_limit=='2')
		{
			$sql='select * from pledge_redeemed where purchase_id='.$this->mainInfo->purchase_id.' and brand_id='.$this->mainInfo->brand_id.' and product_id='.$this->mainInfo->product_id;
		}
		$pledge=PledgeRedeemed::model()->findBySql($sql);
		if($pledge)
		{
			$pledge->weight+=$this->mainInfo->weight;
			$pledge->left_weight+=$this->mainInfo->weight;
			$pledge->update();	
		}else{
			//建立已赎回托盘数据
			$pledge=new PledgeRedeemed();
			$pledge->title_id=$this->mainInfo->title_id;
			$pledge->company_id=$this->mainInfo->company_id;
			$pledge->product_id=$this->mainInfo->product_id;
			$pledge->brand_id=$this->mainInfo->brand_id;
			$pledge->weight=$this->mainInfo->weight;
			$pledge->purchase_id=$this->mainInfo->purchase_id;
			$pledge->left_weight=$this->mainInfo->weight;
			$pledge->insert();
		}
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的托盘赎回单：".$baseform->form_sn."已经审核通过。";
			$message['title'] = "审核通知";
			$message['type'] = "托盘赎回单";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
		
	}
	
	/**
	 * 表单拒绝后续操作
	 */
	public function afterRefuseForm()
	{
		//修改往来
		$turns=Turnover::model()->findAll('common_forms_id='.$this->commonForm->id.' and status!="delete"');
		if($turns)
		{
			foreach ($turns as $each)
			{
				$id=$each->id;
				$oldturn=Turnover::getOne($id);
				$oldJson=$oldturn->datatoJson();
				$update=array('status'=>'unsubmit');
				$result=Turnover::updateBill($id, $update);
				//新增提交日志
				$mainJson = $result->datatoJson();
				$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$this->dataLog($dataArray);
			}
		}
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的托盘赎回单：".$baseform->form_sn."审核已被拒绝。";
			$message['title'] = "审核通知";
			$message['type'] = "托盘赎回单";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
		
	}
	
	/*
	 * 取消审核
	 */	
	public function cancelApproveForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'approve') return;//表单状态不是提交
	
		$transaction=Yii::app()->db->beginTransaction();
		try {
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
				
			$result=$this->afterCancelApproveForm();
			if($result!==true)
			{
				return $result;
			}
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
		$pledgeInfo=$this->mainInfo->pledgeInfo;
		if($pledgeInfo->r_limit==1)
		{
			$sql='select * from pledge_redeemed where purchase_id='.$this->mainInfo->purchase_id.' and brand_id='.$this->mainInfo->brand_id;
		}elseif ($pledgeInfo->r_limit==2)
		{
			$sql='select * from pledge_redeemed where purchase_id='.$this->mainInfo->purchase_id.' and brand_id='.$this->mainInfo->brand_id.' and product_id='.$this->mainInfo->product_id;
		}
		$pledge=PledgeRedeemed::model()->findBySql($sql);
		if($pledge)
		{
			$pledge->weight-=$this->mainInfo->weight;
			$pledge->left_weight-=$this->mainInfo->weight;
			if($pledge->left_weight<0)
			{
				return 'outed';
			}
			$pledge->update();
		}
		//发送消息
		$baseform=$this->commonForm;
		if(Yii::app()->user->userid != $baseform->owned_by){
			$message = array();
			$message['receivers'] = $baseform->owned_by;
			$message['content'] = "您的托盘赎回单：".$baseform->form_sn."已被取消审核。";
			$message['title'] = "审核通知";
			$message['type'] = "托盘赎回单";
			$message['big_type']='purchase';
			$res = MessageContent::model()->addMessage($message);
		}
		return true;
	}
}