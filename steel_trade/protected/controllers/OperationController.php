<?php

class OperationController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		
		$model = new AuthItem();
		$cri = new CDbCriteria();
		$cri->condition = "type=0";
		$cri->order = "priority,name";
		if($_POST['AuthItem']){
			$model->attributes = $_POST['AuthItem'];
			if($model->name){
				$cri->addCondition("name like '%{$model->name}%'");
			}
			if ($model->description)
				$cri->addCondition("description like '%{$model->description}%'");
		}
		$pages = new CPagination();
		$pages->itemCount = count($model->findAll($cri));//查询结果总数
		$pages->pageSize = $_COOKIE['operation']?$_COOKIE['operation']:Yii::app()->params['pageCount'];//每页显示个数
		/*$cookie =Yii::app()->request->getCookies();
		if( $cookie['operation_list']->value){
			$pages->pageSize = $cookie['operation_list']->value;
		}*/
		$pages->applyLimit($cri);//应用分页
		$auths = $model->findAll($cri);
		$this->pageTitle="操作管理";
		$this->render('index',array(
				'auths'=>$auths,
				'pages'=> $pages,
				'search'=>$model
		));
	}
	
	
	public function actionCreate()
	{
		$model = new AuthRoleForm();
		$auth = Yii::app()->authManager;
		
		
		if (Yii::app()->request->isPostRequest) {
			$model->attributes = $_POST['AuthRoleForm'];
			
			
			Yii::app()->authManager->createOperation($model->name,$model->description);
			$temp = AuthItem::model()->findByPk($model->name);
			$temp->priority = $model->priority;
			$temp->save();
			
			$this->redirect($this->getBackListPageUrl());
		}
		$this->render('create',array(
				'model'=>$model,
				'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionUpdate()
	{
		
		$name = $_REQUEST['name'];
		$auth = Yii::app()->authManager;
		$authItem = $auth->getAuthItem($name);
		$model = new AuthRoleForm();
		if ($authItem == null) {
			throw new CHttpException(404,'访问的页面不存在!');
		}
		$model->name = $authItem->name;
		$model->description = $authItem->description;
		
		if (Yii::app()->request->isPostRequest) {
			//更新
			$model->attributes = $_POST['AuthRoleForm'];
			$authItem->name = $model->name;
			$authItem->description = $model->description;
			$auth->saveAuthItem($authItem,$name);
			$temp = AuthItem::model()->findByPk($model->name);
			$temp->priority = $model->priority;
			$temp->save();
			$this->redirect($this->getBackListPageUrl());
		}
		
		
		$this->render('update',array(
			'model'=>$model,
			'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("operation/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	private function _update($model)
	{
		$model->attributes = $_POST['AuthRoleForm'];
		if ($model->save()) {
			$this->returnListPage();
		}
	}
	
	

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}