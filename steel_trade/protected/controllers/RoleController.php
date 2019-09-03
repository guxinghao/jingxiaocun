<?php

class RoleController extends AdminBaseController
{
	public $layout='admin';
	
	
	public function actionIndex()
	{
		$model = new AuthItem();
		$cri = new CDbCriteria();
		$cri->condition = "type=2";
		$cri->order = "priority,name";
		if($_POST['AuthItem']){
			$model->attributes = $_POST['AuthItem'];
			if($model->name){
				$cri->addCondition("name like '%{$model->name}%'");
			}
			if($model->description){
				$cri->addCondition("description like '%{$model->description}%'");
			}
		}
		$pages = new CPagination();
		$pages->itemCount = count($model->findAll($cri));//查询结果总数
		$pages->pageSize = $_COOKIE['role']?$_COOKIE['role']:Yii::app()->params['pageCount'];//每页显示个数
		$cookie =Yii::app()->request->getCookies();
		if( $cookie['role_list']->value){
			$pages->pageSize = $cookie['role_list']->value;
		}
		$pages->applyLimit($cri);//应用分页
		$auths = $model->findAll($cri);
		$this->pageTitle="角色管理";
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
		$all_right = AuthItem::model()->findAll("type=1 order by priority,name");
		$role_right=AuthItem::model()->findAll("type=2 order by priority,name");
		$operation=AuthItem::model()->findAll("type=0 order by priority,name");
		if (Yii::app()->request->isPostRequest) {
			$model->attributes = $_POST['AuthRoleForm'];
			Yii::app()->authManager->createRole($model->name,$model->description);
		//**********	子权限修改
			$now_right_name = $_REQUEST['now_right_name'];
			$add_name =	$now_right_name;//需要添加的子权限
			
			if($add_name){
				foreach ($add_name as $add)//添加
				{
					$auth->addItemChild($model->name,$add); 
//					$auth->assign($add,$model->name);
				}
			}
		//**********
			$temp = AuthItem::model()->findByPk($model->name);
			$temp->priority = $model->priority;
			$temp->save();
			
			$this->redirect($this->getBackListPageUrl());
		}
		$this->render('create',array(
				'model'=>$model,
				'backUrl' => $this->getBackListPageUrl(),
				'all_right'=> $all_right,
				"role_rights"=>$role_right,
				"operation"=>$operation,
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
		
		//********授权
		$all_right = AuthItem::model()->findAll("type=1 order by priority,name");
		$role_right=AuthItem::model()->findAll("type=2 and name<>'{$model->name}' order by priority,name");
		$operation=AuthItem::model()->findAll("type=0 order by priority,name");
		$has_right = $auth->getItemChildren($model->name);//当前的子权限
		$authright=$auth->getAuthItems();
		if (!is_array($has_right)) $has_right = array();
		if (Yii::app()->request->isPostRequest) {
			//更新
			$model->attributes = $_POST['AuthRoleForm'];
			$authItem->name = $model->name;
			$authItem->description = $model->description;
			$auth->saveAuthItem($authItem,$name);
			//*********edit child
			if($_REQUEST['now_right_name'])
				$now_right_name = $_REQUEST['now_right_name'];
			else $now_right_name = array();
			$has_right_name = array();
			$delete_name = array();
			foreach ($has_right as $h)//取出has_right的name
			{
				$has_right_name[] = $h->name;
			}
			
			$delete_name = array_diff($has_right_name,$now_right_name);//需要删除的子权限
			$add_name = array_diff($now_right_name,$has_right_name);	//需要添加的子权限
			
			if($delete_name){
			foreach ($delete_name as $del)//删除
				{
					$auth->removeItemChild($model->name,$del);
					$auth->revoke($del,$model->name);
				}
			}
			if($add_name){
				foreach ($add_name as $add)//添加
				{
					$auth->addItemChild($model->name,$add); 
//					$auth->assign($add,$model->name);
				}
			}
			$temp = AuthItem::model()->findByPk($model->name);
			$temp->priority = $model->priority;
			$temp->save();
			
			$this->redirect($this->getBackListPageUrl());
		}
		
		
		$this->render('update',array(
			'model'=>$model,
			'backUrl' => $this->getBackListPageUrl(),
			'all_right'=> $all_right,
			'has_right'=> $has_right,
			"role_rights"=>$role_right,
			"operation"=>$operation,
			"authright"=>$authright,
		));
	}
	
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("role/index",array('page'=>$_REQUEST['page']));
		
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	private function _update($model)
	{
		$model->attributes = $_POST['AuthRoleForm'];
//		$this->redirect("admin?r=auth/index");

		if ($model->save()) {
			$this->returnListPage();
		}
	}
	
	public function actionDeleteAuth(){
		$del_name = $_REQUEST['del_name'];
		$auth = Yii::app()->authManager;
		
		//先查找一下
		$authItem = AuthItem::model()->find("name = '{$del_name}'");
		if ($authItem->type == 0)	$title = "操作";
		else if($authItem->type == 1)  $title = "任务";
		else if ($authItem->type == 2) $title = "角色";
		
		echo $auth->removeAuthItem($del_name);
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