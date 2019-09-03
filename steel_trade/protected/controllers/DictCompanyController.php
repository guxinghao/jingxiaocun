<?php

class DictCompanyController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "结算单位管理";
		if(isset($_REQUEST['name']))
		{
			$_POST['DictCompany']['name']=$_REQUEST['name'];
		}
		list($model,$search,$pages,$items) = DictCompany::getIndexList();
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
		));
	}
	
	
	public function actionCreate()
	{
		$this->pageTitle = "结算单位新建";
		$model = new DictCompany();
		
		if ($_POST['DictCompany']) 
		{
			$success = $model->createCompany($_POST['DictCompany']);
			if ($success < -2) {
				$msg = "简称已存在";
			} elseif ($success < 0) {
				$msg = "公司名已存在";
			} else {
				//日志
				$base = new BaseForm();
				$base->operationLog('结算单位管理','新增');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'dict_company',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				//推送信息
				if (Yii::app()->params['api_switch'] == 1) {
					$result = DictCompany::synchronization($model, 'Add');
				}
				$this->returnListPage();
			}
		}
		$warehouse=Warehouse::getWareList("array");
		$this->render('create',array(
				'model'=>$model,
				'msg'=>$msg,
				'warehouse'=>$warehouse,
				'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionUpdate()
	{
		$model = DictCompany::model()->findByPk($_REQUEST['id']);
		if ($_POST['DictCompany']) {
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
			if (!$canUpdate) 
			{
				$msg = "数据已不是最新，请重新编辑后提交！";
			} else {
				$oldJson = $model->datatoJson();
				$success = $model->updateCompany($_POST['DictCompany']);
				if (!$success) {
					$msg = "保存失败";
				} elseif ($success < -2) {
					$msg = "简称已存在";
				} elseif ($success < 0) {
					$msg = "公司名已存在";
				} else {
					//日志
					$base = new BaseForm();
					$base->operationLog('结算单位管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'dict_company',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					//推送信息
					if (Yii::app()->params['api_switch'] == 1) {
						$result = DictCompany::synchronization($model, 'Edit');
					}
					
					$this->returnListPage();
				}
			}
		}
		$warehouse=Warehouse::getWareList("array");
		$this->pageTitle = "结算单位修改";
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'warehouse'=>$warehouse,
			'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionDelete(){
		$id = intval($_REQUEST['del_id']);
		$model = DictCompany::model()->findByPk($id);
		$time = intval($_REQUEST['time']);
		$canDelete = $this->checkUpdateTime($model->tableName(), $time, $id);
		if(!$canDelete){
			echo "updated";
			return;
		}
		$main = $model;
		$mainJson = $main->datatoJson();
		$model->delete();
		//日志
		$base = new BaseForm();
		$base->operationLog('结算单位管理','删除');
		$dataArray = array("tableName"=>'dict_company',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		//推送信息
		if (Yii::app()->params['api_switch'] == 1) {
			$result = DictCompany::synchronization($main, 'Delete');
		}
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("dictCompany/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}
	
	
	public function actionTpLevel($id)
	{
		$model=DictCompany::model()->findByPk($id);
		if($model)
		{
			echo $model->level;
			return;
		}
		echo 'error';
		
	}

}