<?php
class High extends BaseForm
{
	public $mainModel = "HighOpen";
//	public $detailModel = "SalesDetail";
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName="高开";
	
	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('highopen')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->highopen;
			}
		}
	}
	
	/**
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo=new HighOpen();
		$mainInfo->sales_id=$data->sales_id;
		$mainInfo->sales_detail_id=$data->sales_detail_id;
		$mainInfo->price=$data->price;
		$mainInfo->fee=$data->fee;
		$mainInfo->real_fee=$data->fee*0.83;
		$mainInfo->title_id=$data->title_id;
		$mainInfo->target_id=$data->target_id;
		
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"HighOpen","newValue"=>$mainJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $mainInfo;
		}
		return null;
	}
	
	/**
	 * 基类方法重构
	 *
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
		$main = $this->mainInfo;
		
		$price = $each->price;
		$fee = $price*$each->weight;
		$amount=$each->weight;
		$da=array();
		$da['type']="GKMX";
		$da['turnover_direction']="need_charge";
		$da['title_id']= $main->title_id;
		$da['target_id']=$main->target_id;
		$da['proxy_company_id']='';
		$da['amount']=$main->fee;
		$da['price']=$main->price;
		$da['fee']=$main->real_fee;
		$da['common_forms_id']=$this->commonForm->id;
		$da['ownered_by']=$this->commonForm->owned_by;
			
		$result = Turnover::createBill($da);
		//日志
		$mainJson = $result->datatoJson();
		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>"");
		$this->dataLog($dataArray);
	
	}
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		$mainInfo=$this->mainInfo;
		$oldJson=$mainInfo->datatoJson();
		$mainInfo->price=$data->price;
		$mainInfo->fee=$data->fee;
		$mainInfo->real_fee=$data->fee*0.83;
		$mainInfo->title_id=$data->title_id;
		$mainInfo->target_id=$data->target_id;
		
		if($mainInfo->update())
		{
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"HighOpen","newValue"=>$mainJson,"oldValue"=>$oldJson);
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
		$baseform = $this->commonForm;
		$main = $this->mainInfo;
		$id=$baseform->id;
		$thrnover = Turnover::findOneBill($id);
		if($thrnover){
			//修改往来信息
	 		$price = $main->price;
	 		$fee = $main->real_fee;
	 		$amount = $main->fee;
	 		$company_id=$main->title_id;
	 		$vendor_id=$main->target_id;
	 		$description = $company_id."同".$vendor_id."产生一条need_charge往来,金额为:".$fee;
	 		//-----begin-----调整amount是数量，fee是金额，再看下是否需要修改往来的其他字段
	 		$update=array('fee'=>$fee,'title_id'=>$company_id,'target_id'=>$vendor_id,'amount'=>$amount,"price"=>$price,'ownered_by'=>$owned_by,'description'=>$description);
	 		//-----end----
	 		//var_dump($thrnover);die;
	 		$oldJson=$thrnover->datatoJson();
	 		$result = Turnover::updateBill($thrnover->id, $update);
	 				
	 		$mainJson = $result->datatoJson();
	 		$dataArray = array("tableName"=>"Turnover","newValue"=>$mainJson,"oldValue"=>$oldJson);
	 		$this->dataLog($dataArray);
		}else{
			//新增往来信息
			//高开没有明细，不会新增往来
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
	
}