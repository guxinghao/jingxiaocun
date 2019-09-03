<?php
class BaseForm
{
	public $commonForm;
	public $mainModel;//主体model
	public $detailModel;//明细model
	public $mainInfo;//表单主体
	public $details;//明细主体
	public $has_detials;//是否有明细
	public $isAutoApprove;//是否自动审核
	public $busName;
	
	//-----------------------------------------------------------创建表单--------------------------------------------------------
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
			$this->afterCreateForm();
			
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

	/**
	 * 保存基础信息
	 * 暂时写死状态未提交，应根据系统参数判断
	 */
	protected  function _saveCommonInfo($data)
	{
		//var_dump($data);die;
		$commonForm = new CommonForms();
		$commonForm->form_type = $data->form_type;
		$commonForm->created_by = currentUserId();
		$commonForm->created_at = time();
		$commonForm->form_time = $data->form_time?$data->form_time:date('Y-m-d',time());
		$commonForm->form_status = 'unsubmit';
		$commonForm->owned_by = $data->owned_by?$data->owned_by:currentUserId();
		$commonForm->comment = $data->comment;
		if (!$data->form_type)
			return false;
		
		if($commonForm->insert()){
			//明细日志
			$commonJson = $commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>"");
			$this->dataLog($dataArray);
			return $commonForm;
		}		
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
	
	/**
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		
		return null;
	}

	/**
	 * 保存明细
	 */
	protected function saveDetails($data)
	{
		return array();
	}
	
	/**
	 * 创建表单后的动作
	 */
	protected function afterCreateForm()
	{
		
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
			return "操作失败";
		}
		//发送消息
		
		//新增日志
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 提交表单后的动作
	 */
	protected function afterSubmitForm()
	{
		
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
			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//发送消息
		
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
		
	}
	
	//-----------------------------------------------------------修改表单--------------------------------------------------------
	/**
	 * 修改表单
	 */
	public function updateForm($data)
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
			$transaction->commit();
		}catch (Exception $e)
		{	
			$transaction->rollBack();
			if(isset($e->message)&&$e->message=='已开票,不能更改为乙单'){
				return "已开票,不能更改为乙单";
			}
			return "操作失败";
		}
		//发送消息
		
		//新增日志
			$operation = "修改";
			$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
			return true;
	}
	
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
			$transaction->rollBack();
			if(isset($e->message)&&$e->message=='已开票,不能更改为乙单'){
				return "已开票,不能更改为乙单";
			}
			return "操作失败";
		}
		//发送消息
	
		//新增日志
		$operation = "修改";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		$operation = "提交";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return $this->commonForm->id;
	}
	
	/**
	 * 修改基本信息
	 */
	protected function _updateCommonInfo($data)
	{
 		if ($this->commonForm == null) return false;
 		
		if($this->commonForm->form_status=="is_finish" || $this->commonForm->form_status=="delete")
		{
			return false;
		}		
		$olddata=$this->commonForm;
		$oldJson=$olddata->datatoJson();
 		$this->commonForm->comment = $data->comment;
 		$this->commonForm->last_update = time();
		$this->commonForm->last_updated_by = currentUserId();
		
		$this->commonForm->form_time = $data->form_time?$data->form_time:date('Y-m-d',time());
 		$this->commonForm->owned_by = $data->owned_by;
 		if($this->commonForm->update()){
 			$commonJson = $this->commonForm->datatoJson();
 			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
 			$this->dataLog($dataArray);
 			return true;
 		}
 		
		
	}
	
	/**
	 * 修改主体信息
	 */
	protected function updateMainInfo($data)
	{
		return false;
	}
	
	/**
	 * 修改明细
	 */
	protected function updateDetails($data)
	{
		return false;
	}
	
	/**
	 * 修改以后操作
	 */
	protected function afterupdateForm()
	{
		
	} 
	
	//-----------------------------------------------------------作废表单--------------------------------------------------------
	/**
	 * 作废表单
	 */
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
			$this->afterDeleteForm();			
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return;
		}
		
		//发送消息
		
		//新增日志
		$operation = "删除";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 作废后的操作
	 */
	protected function afterDeleteForm()
	{
		
	}
	
	
	//-----------------------------------------------------------审核表单--------------------------------------------------------
	/**
	 * 表单审核通过
	 */
	public function approveForm()
	{
		if ($this->commonForm == null) return false;//表单为空
		if ($this->commonForm->form_status != 'submited') return;//表单状态不是提交
		
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$olddata=$this->commonForm;
			$oldJson=$olddata->datatoJson();
			$this->commonForm->form_status = 'approve';
			$this->commonForm->approved_at = time();
			$this->commonForm->approved_by = currentUserId();
			$this->commonForm->last_update = time();
			$this->commonForm->last_updated_by = currentUserId();
			$this->commonForm->update();
			
			$commonJson = $this->commonForm->datatoJson();
			$dataArray = array("tableName"=>"commonForms","newValue"=>$commonJson,"oldValue"=>$oldJson);
			$this->dataLog($dataArray);
			$this->afterApproveForm();
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			return "操作失败";
		}
		//发送消息
		
		//新增日志
		$operation = "审核";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 审核通过后续操作
	 */
	protected function afterApproveForm()
	{
		
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
		
		//新增日志
		$operation = "拒绝";
		$this->operationLog($this->busName, $operation,$this->commonForm->form_sn);
		return true;
	}
	
	/**
	 * 表单拒绝后续操作
	 */
	protected  function afterRefuseForm()
	{
		
	}

	//-----------------------------------------------------------取消审核表单--------------------------------------------------------
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
			
	}
	
	
	
	//-----------------------------------------------------------查询表单--------------------------------------------------------
	/**
	 * 根据common的id查询表单信息
	 */
	public function getFormByCommonId($id)
	{
		$this->commonForm = CommonForms::model()->findByPk($id);
		
		if ($this->commonForm == null) return false;
		$main = new $this->mainModel;
// 		$cir_main = new CDbCriteria();
// 		$cir_main->addCondition("id = :id",array(":id"=>$this->commonForm->form_id));
		
		$this->mainInfo = $main->findByPk($this->commonForm->form_id);
		
		if ($this->has_detials){
			$this->details = $this->getDetailByFormId($this->mainInfo->id);
		}
		
		return $this;
	}
	
	
	/**
	 * 根据sn查询表单信息
	 */
	public function getFormByCommonSN($sn)
	{
		$this->commonForm = CommonForms::model()->find("form_sn = :sn",array(":sn"=>$sn));
		
		if ($this->commonForm == null) return false;
		
		$main = new $this->mainModel;
		$cir_main = new CDbCriteria();
		$cir_main->addCondition("id = :id",array(":id"=>$this->commonForm->form_id));
		
		$this->mainInfo = $main->find($cir_main);
		
		if ($this->has_detials){
			$this->details = $this->getDetailByFormId($this->mainInfo->id);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * 根据form表id获取明细，子类重写
	 */
	protected function getDetailByFormId($id)
	{
		
	}
	
	
	/**
	 * 生成统一的序列号
	 */
	public function _generateSN($form_type,$id)
	{
		if (!$form_type || !$id) return;
		switch ($form_type){
			//采购模块
			case "CGD"://采购单
				$type = "CD";
				break;
			case "CGHT"://采购合同
				$type = "CHT";
				break;	
			case "CGTH"://采购退货
				$type = "CTD";
				break;	
			case "TPSH"://托盘赎回
				$type = "CSH";
				break;
			//销售模块	
			case "XSD"://销售单
				$type = "XD";
				break;
			case "XSTH"://销售退货
				$type = "XTD";
				break;	
			case "XSZR"://销售折让
				$type = "XZR";
				break;	
			case 'GKZR': //高开折让
				$type = "GZR";
				break;
			case 'CGZR':
				$type = 'CZR';
				break;
			case "FYDJ"://费用登记
				$type = "FY";
				break;
			//财务模块	
			case "FKDJ"://付款登记
				$type = "FFK";
				break;
			case "SKDJ"://收款登记
				$type = "FSK";
				break;
			case "CGXP"://采购销票
				$type = "FCXP";
				break;
			case "XSKP"://销售开票
				$type = "FSKP";
				break;
			case "FYBZ"://费用报支
				$type = "FBZ";
				break;
			case "QTSR"://其他收入
				$type = "FQT";
				break;
			case "YHHZ"://银行互转
				$type = "FHZ";
				break;
			case "DQJK"://短期借款
				$type = "FJK";
				break;
			case "DQDK"://短期贷款
				$type = "FDK";
				break;
			//库存模块
			case "RKD"://入库单
				$type = "KRK";
				break;
			case "DXRK"://代销入库单
					$type = "KDRK";
					break;
			case "XSPS"://销售配送单
				$sn=$this->sendSnGeneration();
				goto end;
				// $type = "KPS";
				break;
			case "CKD"://出库单
				$type = "KCK";
				break;
			case "XSZK"://销售转库单
				$type = "KZK";
				break;
			case "DXDB"://代销调拨单
				$type = "KDB";
				break;
			case "PYPK"://盘盈盘亏记录
				$type = "KYK";
				break;
			case "BLKC"://保留库存
				$type = "KBL";
				break;
			case "RKP":
				$type = "RKP";
				break;
			case "GK":
				$type = "GK";
				break;
			case 'CKFL': //仓库返利
				$type = "CKFL";
				break;
			case 'GCFL': //钢厂返利
				$type = "GCFL";
				break;
			case 'CCFY': //仓储费用
				$type = "CCFY";
				break;
			default:
				$type = "unknow";
				break;
		}
		
		
		$id = $id%10000 == 0 ? 0 : $id%10000;
		
		$sn =  $type.date("ymd").str_pad($id,4,"0",STR_PAD_LEFT);
		end:
		if (!$sn) return false;
		return $sn;
	}
	
	
	/**
	 * 业务日志
	 *  @param string $busName 业务名
	 * 	@param string $operation 操作名
	 * 	@param string $comment 描述
	 */
	public function operationLog($busName,$operation,$comment="")
	{
		$log = New Log();
		$log->business_name = $busName;
		$log->operation_type = $operation;
		$log->created_by = currentUserId();
		$log->created_at = time();
		$log->comment = $comment;
		return $log->insert();
	}
	
	
	/**
	 * 明细日志
	 * @param array  $dataArray 内容数组,包括tableName,oldValue,newValue,owner
	 */
	public function dataLog($dataArray)
	{
		if (!is_array($dataArray) || count($dataArray)<=0) return false;
		
		$logDetail = new LogDetail();
		$logDetail->table_name = $dataArray['tableName'];
		$logDetail->oldValue = $dataArray['oldValue'];
		$logDetail->newValue = $dataArray['newValue'];
		$logDetail->created_at = time();
		$logDetail->created_by = $dataArray['owner'] ? $dataArray['owner'] : currentUserId();
		return $logDetail->insert();
	}
	
	/**
	 * 发送消息
	 * @param string $title 消息标题
	 * @param string $content 消息内容
	 * @param string $type 类型
	 * @param int $reciever 接收人id
	 * @param string $url 跳转地址
	 * @return  返回save()结果
	 */
	public function sendMessage($title, $content, $type, $reciever, $url)
	{
		$msg = new MessageContent();	
		$msg->title = $title;
		$msg->content = $content;
		$msg->type = $type;
		$msg->created_at = time();
		$msg->created_by = Yii::app()->user->userid;
		$msg->url = $url;
		if (!$msg->insert()) return false;
		
		$box = new MessageBox();
		$box->message_id = $msg->id;
		$box->receiver_id = $reciever;
		$box->send_time = $msg->created_at;
		$box->status = 1;
		$bool = $box->insert();
		return $bool;
	}
	
	/**
	 * 消息状态改变
	 * @param int $msg_id 消息id
	 * @param int $status 0表示发送失败，1为已发送且未读，2为已读，默认进行已读操作
	 * @return 返回save()结果
	 */
	public function readMessage($msg_id,$status=2){
		$box = MessageBox::model()->find("messge_id = $msg_id");
		$box->status = $status;
		return $box->save();
	}
	
	public function sendSnGeneration()
	{
		$sql="select count(*)  from frm_send where frm_sales_id={$this->mainInfo->frm_sales_id}";
		$count=FrmSend::model()->countBySql($sql);
		$base=$this->mainInfo->FrmSales->baseform;		
		$sn=$base->form_sn.'-'.$count;
		return $sn;
	}


}

