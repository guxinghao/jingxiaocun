<?php
class InputPlan extends BaseForm
{
	public $mainModel = "FrmInputPlan";
	public $detailModel = "InputDetailPlan";
	public $has_detials = true;
	public $isAutoApprove = false;
	public $busName="入库计划";
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('inputplan','inputplan.inputDetailsPlan')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->inputplan;
				$this->details=$model->inputplan->inputDetailsPlan;
			}		
		}
	}
	
	/*****--------------------------基类方法重构---------------------------------******/
	
	/**
	 * 创建表单
	 */
	public function createForm($data)
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
			$this->afterCreateForm($main);
				
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
		//发送消息
		return $this->commonForm->id;
	}
	
	
	
	/*
	 * 创建保存主体信息
	 */
	protected function saveMainInfo($data)
	{
			$mainInfo=new FrmInputPlan();
			$mainInfo->input_type=$data->input_type;//入库类型
			$mainInfo->purchase_id=$data->purchase_id;//采购单baseform_id
			$mainInfo->input_date=strtotime($data->input_date);//预计入库时间
			$mainInfo->input_time=$data->input_time;
			$mainInfo->warehouse_id=$data->warehouse_id;//仓库
			$mainInfo->input_status=$data->input_status;//入库状态
			$mainInfo->input_company=$data->input_company;//入库单位
			$purchase=CommonForms::model()->findByPk($data->purchase_id)->purchase;
			if($purchase)
			{
				if($purchase->purchase_type=='tpcg')
				{
					$mainInfo->owner_company=$purchase->pledge->pledge_company_id;//货权单位
					$mainInfo->input_type='tprk';
				}else{
					$mainInfo->owner_company=$data->input_company;
				}
			}
			$mainInfo->ship_no=$data->ship_no;//车船号
			$mainInfo->form_sn=$data->form_sn;//原始单据唯一编号
// 			$mainInfo->purchase_type=$data->purchase_type;//采购类型			
			if($mainInfo->insert()){
				$mainJson = $mainInfo->datatoJson();
				$dataArray = array("tableName"=>"FrmInputPlan","newValue"=>$mainJson,"oldValue"=>"");
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
			$input=new InputDetailPlan();
			$input->input_id=$this->mainInfo->id;
			$input->input_amount=$each->input_amount;
			$input->input_weight=$each->input_weight;
			$input->purchase_detail_id=$each->purchase_detail_id;
			$input->product_id=$each->product_id;
			$input->rank_id =$each-> rank_id;
			$input->texture_id=$each->texture_id;
			$input->brand_id=$each->brand_id;
			$input->length=$each->length;
			$input->price=$each->price;
			$input->remain_amount=$each->remain_amount;
			$input->remain_weight=$each->remain_weight;
			if($input->insert())
			{
				//日志
				$detailJson = $input->datatoJson();
				$dataArray = array("tableName"=>"InputDetailPlan","newValue"=>$detailJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				array_push($detail,$input);
			}
		}		
		return $detail;
	}
	
	/**
	 * 创建表单后的动作
	 */
	protected function afterCreateForm($main)
	{
		//如果是船舱入库，创建一个入库单
		if($this->mainInfo->input_type=='ccrk')
		{
			//创建commonform
			$commonForm = new CommonForms();
			$commonForm->form_type = 'RKD';
			$commonForm->created_by = currentUserId();
			$commonForm->created_at = time();
			$commonForm->form_time = date('Y-m-d',time());
			$commonForm->form_status = 'unsubmit';
			$commonForm->owned_by = currentUserId();
			$commonForm->comment = '';
			
			$input=new FrmInput();
			$input->input_type=$this->mainInfo->input_type;//入库类型
			$input->purchase_id=$this->mainInfo->purchase_id;//采购单id
			$input->input_date=$this->mainInfo->input_date;//预计入库时间
			$input->input_time=$this->mainInfo->input_time;
			$input->warehouse_id=$this->mainInfo->warehouse_id;//仓库
			$input->input_status=$this->mainInfo->input_status;//入库状态
// 			$input->goods_status=$main->goods_status;
			$input->push_id=0;//仓库推送信息
			$input->from='';//创建入库单来源
			$input->plan_id=$this->mainInfo->id;
			if($input->insert()){
				$mainJson = $input->datatoJson();
				$dataArray = array("tableName"=>"FrmInput","newValue"=>$mainJson,"oldValue"=>"");
				$this->dataLog($dataArray);
				
				$sn = $this->_generateSN('RKD',$input->id);
				$commonForm->form_sn = $sn;
				$commonForm->form_id = $input->id;
				$commonForm->insert();
				
				//创建入库明细
				foreach ($this->details as $each)
				{
					$inputdetail=new InputDetail();
					$inputdetail->input_id=$input->id;
					$inputdetail->input_amount=$each->input_amount;
					$inputdetail->input_weight=$each->input_weight;
					$inputdetail->purchase_detail_id=$each->purchase_detail_id;
					$inputdetail->cost_price=0;
					$inputdetail->product_id=$each->product_id;
					$inputdetail->rank_id =$each-> rank_id;
					$inputdetail->texture_id=$each->texture_id;
					$inputdetail->brand_id=$each->brand_id;
					$inputdetail->length=$each->length;
					$inputdetail->cost_price=$each->price;
					$inputdetail->card_id='';
					$inputdetail->remain_amount=$each->remain_amount;
					$inputdetail->remain_weight=$each->remain_weight;
					if($inputdetail->insert())
					{
						$sn =  'SHIP'.date("ymd").str_pad($inputdetail->id,4,"0",STR_PAD_LEFT);
						$inputdetail->card_id=$sn;
						$inputdetail->update();
						//日志
						$detailJson = $inputdetail->datatoJson();
						$dataArray = array("tableName"=>"InputDetail","newValue"=>$detailJson,"oldValue"=>"");
						$this->dataLog($dataArray);						
					}
				}
				$this->operationLog("入库单", "新增",$commonForm->form_sn);				
			}
		}
		
		$purchase=$this->mainInfo->basepurchase->purchase;
		if($purchase->can_push==1&&Yii::app()->params['api_switch'])
		{
			$this->mainInfo->input_status=-1;
			$this->mainInfo->update();
			//获取信息，推送
			$jsonData=FrmInputPlan::getJsonData($this->mainInfo);
			//插入推送数据
			$data=array();
			$data['type']='inputformplan';
			$data['content']=$jsonData;
// 			$data['unid']= '00032gU86Q';
			$data['unid']= Yii::app()->user->unid;
			$data['operate']='Add';
			$data['form_id']=$this->mainInfo->id;
			$data['form_sn']=$this->commonForm->form_sn;
			PushList::createNew($data);
		}
		//新增日志	
	}
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		$con=$this->mainInfo;
		$oldJson=$con->datatoJson();
		if ($this->mainInfo->input_status!='0'&&$this->mainInfo->input_status!=1&&$this->mainInfo->input_status!=-2)return;
// 			$this->mainInfo->input_type=$data->input_type;
// 			$this->mainInfo->purchase_id=$data->purchase_id;
			$this->mainInfo->input_date=strtotime($data->input_date);
			$this->mainInfo->input_time=$data->input_time;
// 			$this->mainInfo->warehouse_id=$data->warehouse_id;
// 			$this->mainInfo->input_company=$data->input_company;//入库单位
// 			$purchase=CommonForms::model()->findByPk($data->purchase_id)->purchase;
// 			if($purchase)
// 			{
// 				if($purchase->purchase_type=='tpcg')
// 				{
// 					$this->mainInfo->owner_company=$purchase->pledge->pledge_company_id;//货权单位
// 				}else{
// 					$this->mainInfo->owner_company=$data->input_company;
// 				}
// 			}
			$this->mainInfo->ship_no=$data->ship_no;//车船号
// 			$mainInfo->form_sn=$data->form_sn;//原始单据唯一编号
		if($this->mainInfo->update()){
			$mainJson = $this->mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmInputPlan","newValue"=>$mainJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			return true;
		}
			
	}
	
	/**
	 * 修改明细
	 */
	protected function updateDetails($data)
	{
		return true;
// 		if (!is_array($data) || count($data)<=0 || !is_array($this->details) || count($this->details)<=0)
// 			return;
// 		if ($this->mainInfo->input_status=="0"){//如果不是未推送，明细不能修改
// 			$new_pur_details_id=array();
// 			foreach ($data as $e)
// 			{
// 				array_push(	$new_pur_details_id,$e->purchase_detail_id);
// 			}
// 			foreach ($this->details as $ea)
// 			{
// 				if(!in_array($ea->purchase_detail_id,$new_pur_details_id)){
// 					$ea->delete();
// 					//日志
// 					$oldJson=$ea->datatoJson();
// 					$dataArray = array("tableName"=>"InputDetailPlan","newValue"=>"","oldValue"=>$oldJson);
// 					$this->dataLog($dataArray);
// 					continue;
// 				}
// 				$old_pur_details_id[$ea->id]=$ea->purchase_detail_id;
// 			}
// 			foreach ($data as $data_e)
// 			{
// 				if(in_array($data_e->purchase_detail_id,$old_pur_details_id))
// 				{//还是原来那个
// 					$old_id=array_search($data_e->purchase_detail_id, $old_pur_details_id);
// 					$detail=InputDetailPlan::model()->findByPk($old_id);
// 					$olddata=$detail;
// 					$oldJson=$olddata->datatoJson();
// 					if($detail)
// 					{
// 						$detail->input_amount=$data_e->input_amount;
// 						$detail->input_weight=$data_e->input_weight;
// 						$detail->update();
// 						//日志
// 						$mainJson = $detail->datatoJson();
// 						$dataArray = array("tableName"=>"InputDetailPlan","newValue"=>$mainJson,"oldValue"=>$oldJson);
// 						$this->dataLog($dataArray);
// 					}
// 				}else{//要新建一个了
// 					$input=new InputDetailPlan();
// 					$input->input_id=$this->mainInfo->id;
// 					$input->input_amount=$data_e->input_amount;
// 					$input->input_weight=$data_e->input_weight;
// 					$input->purchase_detail_id=$data_e->purchase_detail_id;
// 					$input->product_id=$data_e->product_id;
// 					$input->rank_id =$data_e-> rank_id;
// 					$input->texture_id=$data_e->texture_id;
// 					$input->brand_id=$data_e->brand_id;
// 					$input->length=$data_e->length;
// 					$input->insert();

// 					$mainJson = $input->datatoJson();
// 					$dataArray = array("tableName"=>"InputDetailPlan","newValue"=>$mainJson,"oldValue"=>"");
// 					$this->dataLog($dataArray);
// 				}
// 			}
// 			return true;
// 		}
	}
	
	/*
	 * 作废
	 */
	public function beforeDeleteForm($reason)
	{
		//更改状态
		if($this->mainInfo->input_status==0)
		{
			$this->deleteForm($reason);
			$this->mainInfo->input_status=4;
			$this->mainInfo->update();			
			$this->commonForm->delete_reason=$reason;
			$this->commonForm->update();
		}else{
			$this->mainInfo->input_status=3;
			$this->mainInfo->update();
			$this->commonForm->delete_reason=$reason;
			$this->commonForm->update();
			//调用接口推送作废信息
			if(Yii::app()->params['api_switch'])
			{
				FrmInputPlan::deletePush($this->mainInfo);
			}			
		}
		return true;
	}
	
	
	/****------------------------基类方法重构之作废表单----------------****/
	/**
	 * 作废后的操作
	 */
	public function afterDeleteForm()
	{
		$this->mainInfo->input_status=4;
		$this->mainInfo->update();
		
		$this->commonForm->form_status='delete';
		$this->commonForm->update();
		
		//新增作废日志
		return true;
	}
	
	/*
	 * 推送
	 */
	public function push()
	{
		if(Yii::app()->params['api_switch'])
		{
			$this->mainInfo->input_status=-1;
			$this->mainInfo->update();
			FrmInputPlan::editPush($this->mainInfo);
		}		
	}
	
	/*
	 * 完成
	 */
	public function finish()
	{
		$total_A=0;
		$total_W=0;
		$total_a=0;
		$total_w=0;
		//修改计划量，采购明细，采购，计划明细，
		foreach ($this->details as $each)
		{
			$total_A+=$each->input_amount;
			$total_W+=$each->input_weight;
			$each->input_amount=$each->real_amount;
			$each->input_weight=$each->real_weight;
			$each->update();
			
			$pur_detail=$each->purchaseDetail;
			$pur_detail->plan_amount=$each->real_amount;
			$pur_detail->plan_weight=$each->real_weight;
			
			$total_a+=$each->real_amount;
			$total_w+=$each->real_weight;
		}
		$purchase=$this->mainInfo->basepurchase->purchase;
		$purchase->plan_amount+=($total_a-$total_A);
		$purchase->plan_weight+=($total_w-$total_W);
		$purchase->update();
		
		//修改状态
		$this->mainInfo->input_status=2;
		$this->mainInfo->update();
		return true;
	}

}