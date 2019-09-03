<?php

class TeamController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "业务组管理";
		$model = new Team();
		
		$cri = new CDbCriteria();
		$search =  new Team();
		
		if($_POST['Team']){
			
			$search->attributes = $_POST['Team'];
			if($search->name){
				$cri->params[':search'] = "%".$search->name."%";
				$cri->addCondition("name like :search");
			}
		}
		
		
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['ywz']?intval($_COOKIE['ywz']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		
		
		
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
		));
	}
	
	
	public function actionCreate()
	{
		$model = new Team();
		$this->pageTitle = "业务组新建";
		if($_POST['Team']){
			$success = $model->createTeam($_POST['Team']);
			if($success<0){
				$msg = "该名称已存在";
			}elseif($success){
				//日志
				$base = new BaseForm();
				$base->operationLog('业务组管理','新增');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'team',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
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
		$model = Team::model()->findByPk($_REQUEST['id']);
		if($_POST['Team']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间

			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateTeam($_POST['Team']);
				if($success<0){
					$msg = "该名称已存在";
				}elseif($success){
					//日志
					$base = new BaseForm();
					$base->operationLog('结算单位管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'team',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					$this->returnListPage();
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "业务组修改";
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = Team::model()->findByPk($id);
		$time = intval($_REQUEST['time']);
		$canDelete = $this->checkUpdateTime($model->tableName(), $time, $id);
		if(!$canDelete){
			echo "updated";
			return;
		}
		$mainJson = $model->datatoJson();
		$model->delete();
		//日志
		$base = new BaseForm();
		$base->operationLog('结算单位管理','删除');
		
		$dataArray = array("tableName"=>'team',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		
	}
	
	public function actionGetUsers() 
	{
		$id = intval($_REQUEST['team_id']);
		echo Team::getUsers($id);
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("team/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	
	
}