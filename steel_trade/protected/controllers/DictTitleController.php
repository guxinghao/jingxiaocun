<?php

class DictTitleController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "公司抬头管理";
		if(isset($_REQUEST['name']))
		{
			$_POST['DictTitle']['name']=$_REQUEST['name'];
		}
		list($model,$search,$pages,$items) = DictTitle::getIndexList();
		
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
		));
	}
	
	
	public function actionCreate()
	{
		$model = new DictTitle();
		$this->pageTitle = "公司抬头新建";
		if ($_POST['DictTitle']) {
			$success = $model->createTitle($_POST['DictTitle']);
			if ($success < 0) {
				$msg = "名称已存在";
			} else {
				//日志
				$base = new BaseForm();
				$base->operationLog('公司抬头管理','新建');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'dict_company',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				//推送信息
				if (Yii::app()->params['api_switch'] == 1) { 
					$result = DictTitle::synchronization($model, 'Add');
				}
				$this->returnListPage();
			}
		}
		
		$this->render('create',array(
				'model'=>$model,
				'msg'=>$msg,
				'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionUpdate()
	{
		$model = DictTitle::model()->findByPk($_REQUEST['id']);
		if($_POST['DictTitle']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间

			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateTitle($_POST['DictTitle']);
				if($success<0){
					$msg = "名称已存在";
				}else{
					//日志
					$base = new BaseForm();
					$base->operationLog('公司抬头管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'dict_title',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					//推送信息
					if (Yii::app()->params['api_switch'] == 1) {
						$result = DictTitle::synchronization($model, 'Edit');
					}
					$this->returnListPage();
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "公司抬头修改";
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = DictTitle::model()->findByPk($id);
		$main = $model;
		$mainJson = $main->datatoJson();
		$model->delete();
		//日志
		$base = new BaseForm();
		$base->operationLog('公司抬头管理','删除');
		
		$dataArray = array("tableName"=>'dict_title',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		
		//推送信息
		if (Yii::app()->params['api_switch'] == 1) {
			$result = DictTitle::synchronization($main, 'Delete');
		}
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("dictTitle/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	
	
}