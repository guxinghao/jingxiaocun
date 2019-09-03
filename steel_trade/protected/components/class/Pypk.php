<?php
class Pypk extends BaseForm
{
	public $mainModel = "FrmPypk";
	public $has_detials = false;
	public $isAutoApprove = false;
	public $busName="盘盈盘亏";

	public function __construct($id)
	{
		if(intval($id))
		{
			$model=CommonForms::model()->with('FrmPypk')->findByPk($id);
			if($model)
			{
				$this->commonForm=$model;
				$this->mainInfo=$model->FrmPypk;
			}
		}
	}
	
	/**
	 * 基类方法重构
	 *
	 * 保存主体信息
	 */
	protected function saveMainInfo($data)
	{
		$mainInfo=new FrmPypk();
	
		$mainInfo->type=$data->type;
		$mainInfo->storage_id=$data->storage_id;
		//$mainInfo->amount=$data->amount;
		$mainInfo->weight=$data->weight;
		$mainInfo->comment=$data->comment;
	
		if($mainInfo->insert()){
			//明细日志
			$mainJson = $mainInfo->datatoJson();
			$dataArray = array("tableName"=>"FrmPypk","newValue"=>$mainJson,"oldValue"=>"");
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
		$pypk = $this->mainInfo;
		$amount = $pypk->amount;
		$weight = $pypk->weight;
		$storage = Storage::model()->findByPk($pypk->storage_id);
		$oldJson=$storage->datatoJson();
		$left_weight=$storage->left_weight;
		$flag=false;
		if($storage){
			//$storage->left_amount -= $amount;
			$storage->left_weight -= $weight;
			if(abs($storage->left_weight)<0.001){
				$storage->left_weight=0;
				$flag=true;
			}
			if($storage->update()){
				$mainJson = $storage->datatoJson();
				$dataArray = array("tableName"=>"storage","newValue"=>$mainJson,"oldValue"=>$oldJson);
				$baseform = new BaseForm();
				$baseform->dataLog($dataArray);
			}
			//更新聚合表信息
			if($weight != 0){
				$model = new MergeStorage();
				$criteria=New CDbCriteria();
				$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
				$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
				$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
				$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
				$criteria->addCondition('length ='.$storage->inputDetail->length);
				$criteria->addCondition('title_id ='.$storage->title_id);
				$criteria->addCondition('warehouse_id ='.$storage->warehouse_id);
				$criteria->addCondition('is_transit = 0');
				$criteria->addCondition('is_deleted = 0');
				$merge = $model->find($criteria);
				if($merge){
					$oldJson=$merge->datatoJson();
					//$merge->left_amount -= $amount;
					if($flag)
						$merge->left_weight-=$left_weight;
					else
						$merge->left_weight -= $weight;
					if($merge->update()){
						$mainJson = $merge->datatoJson();
						$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
						$baseform = new BaseForm();
						$baseform->dataLog($dataArray);
						return true;
					}
				}
			}
			return true;
		}
	}
	
	/**
	 * 作废表单后的操作
	 */
	protected function afterDeleteForm()
	{
		$stock = $this->mainInfo;
		$storage = Storage::model()->findByPk($stock->storage_id);
		$oldJson = $storage->datatoJson();
		//$storage->left_amount += $stock->amount;
		$storage->left_weight += $stock->weight;
		$storage->update();
		$newJson = $storage->datatoJson();
		$dataArray = array("tableName"=>"Storage","newValue"=>$newJson,"oldValue"=>$oldJson);
		$baseform = new BaseForm();
		$baseform->dataLog($dataArray);
		if($stock->weight != 0){
			$model = new MergeStorage();
			$criteria=New CDbCriteria();
			$criteria->addCondition('product_id ='.$storage->inputDetail->product_id);
			$criteria->addCondition('brand_id ='.$storage->inputDetail->brand_id);
			$criteria->addCondition('texture_id ='.$storage->inputDetail->texture_id);
			$criteria->addCondition('rank_id ='.$storage->inputDetail->rank_id);
			$criteria->addCondition('length ='.$storage->inputDetail->length);
			$criteria->addCondition('title_id ='.$storage->title_id);
			$criteria->addCondition('warehouse_id ='.$storage->warehouse_id);
			$criteria->addCondition('is_transit = 0');
			$criteria->addCondition('is_deleted = 0');
			$merge = $model->find($criteria);
			if($merge){
				$oldJson=$merge->datatoJson();
				//$merge->left_amount += $stock->amount;
				$merge->left_weight += $stock->weight;
				if($merge->update()){
					$mainJson = $merge->datatoJson();
					$dataArray = array("tableName"=>"MergeStorage","newValue"=>$mainJson,"oldValue"=>$oldJson);
					$baseform = new BaseForm();
					$baseform->dataLog($dataArray);
					return true;
				}
			}
		}
		return true;
	}
}