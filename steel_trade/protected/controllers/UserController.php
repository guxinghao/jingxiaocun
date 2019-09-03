<?php

class UserController extends AdminBaseController
{
	
	public function actionCreate()
	{
		$model=new User();
        
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['User']))
		{
			$model->attributes=$_POST['User'];
			$model->created_at = time();
			$model->password = $model->password;
			$model->created_at = time();
			if (User::model()->find("loginname='{$model->loginname}'")){
				$msg="该用户名已存在！";
			}elseif($model->save()){
				//日志
				$base = new BaseForm();
				$base->operationLog('用户管理','新增');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'team',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				
				$auth = Yii::app()->authManager;
				$now_right_name = $_REQUEST['now_right_name'];
				$add_name =	$now_right_name;//需要添加的子权限
				if($add_name){
					foreach ($add_name as $add)//添加
					{
						//$auth->addItemChild($model->name,$add);
						//日志
						$base = new BaseForm();
						$dataArray = array("tableName"=>'authassignment',"newValue"=>'{"itemname":"'.$add.'","user_id":"'.$model->id.'"}',"oldValue"=>"");
						$base->dataLog($dataArray);
						$auth->assign($add,$model->id);
					}
				}
				$this->redirect($this->getBackListPageUrl());
			}
		}
		
		$auths_ = AuthItem::model()->findAll("type=2 order by priority");
		$auths = array();
		foreach ($auths_ as $a){
			$auths[$a->name] = $a->name;
		}
		$operation=AuthItem::model()->findAll("type=0 order by priority,name");
		
		$this->render('create',array(
			'model'=>$model,
			'backUrl' => $this->getBackListPageUrl(),
			'auths'=>$auths,
			'msg'=>$msg,
			"operation"=>$operation,
		));
	}

	
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		$auth = Yii::app()->authManager;
		$has_right = $auth->getAuthAssignments($id);
		if(isset($_POST['User']))
		{
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间

			if($canUpdate){
				$oldJson = $model->datatoJson();
				$model->attributes=$_POST['User'];
				if(User::model()->find("loginname='{$model->loginname}' and id<>$model->id")){
					$msg = "该用户名已存在！";
				}elseif($model->save()){
					//日志
					$base = new BaseForm();
					$base->operationLog('用户管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'team',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					if ($auth->getAuthAssignments($model->id)){
						
						$a=$auth->getAuthAssignments($model->id);
						foreach ($a as $name){
							$auth->revoke($name->itemname,$id);
							//日志
							$base = new BaseForm();
							$dataArray = array("tableName"=>'authassignment',"newValue"=>"","oldValue"=>'{"itemname":"'.$name->itemname.'","user_id":"'.$model->id.'"}');
							$base->dataLog($dataArray);
						}
					}
					$now_right_name = $_REQUEST['now_right_name'];
					$add_name =	$now_right_name;//需要添加的子权限
						
					if($add_name){
						foreach ($add_name as $add)//添加
						{
							//$auth->addItemChild($model->name,$add);
							//日志
							$base = new BaseForm();
							$dataArray = array("tableName"=>'authassignment',"newValue"=>'{"itemname":"'.$add.'","user_id":"'.$model->id.'"}',"oldValue"=>"");
							$base->dataLog($dataArray);
							$auth->assign($add,$id);
						}
					}	
					$this->redirect($this->getBackListPageUrl());
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$auths_ = AuthItem::model()->findAll("type=2 order by priority");
		$auths = array();
		foreach ($auths_ as $a){
			$auths[$a->name] = $a->name;
		}
		$operation=AuthItem::model()->findAll("type=0 order by priority,name");
		$ops=array();
		foreach ($operation as $op)
		{
			if ($this->isAssignRight($has_right, $op->name)) {
				$ops[$op->name] = 'checked="checked"';
				continue;
			}
			if ($auth->checkAccess($op->name,$id)){
				$ops[$op->name] = 'checked="checked" disabled="disabled"';
				continue;
			}
			$ops[$op->name] = '';
		}
		$this->render('update',array(
			'model'=>$model,
			'backUrl' => $this->getBackListPageUrl(),
			'auths'=>$auths,
			'msg'=>$msg,
			"ops"=>$ops,
			"has_right"=>$has_right,
		));
	}

	public function actionUpdateVoucher($id)
	{
		$model=$this->loadModel($id);
		$auth = Yii::app()->authManager;
		if(isset($_POST['User']))
		{
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$model->attributes=$_POST['User'];
				if(User::model()->find("loginname='{$model->loginname}' and id<>$model->id")){
					$msg = "该用户名已存在！";
				}elseif($model->save()){
					//日志
					$base = new BaseForm();
					$base->operationLog('用户管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'team',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					$this->redirect($this->getBackListPageUrl());
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		
		$this->render('updateVoucher',array(
				'model'=>$model,
				'backUrl' => $this->getBackListPageUrl(),
				'msg'=>$msg,
				"ops"=>$ops,
		));
	}
	
	private function isAssignRight($has_rights,$right_name){
		foreach ($has_rights as $_has_right){
			if ($_has_right->itemname == $right_name) return true;
		}
		return false;
	}
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$model = $this->loadModel($id);
		$mainJson = $model->datatoJson();
		$model->delete();
		$base = new BaseForm();
		$base->operationLog('用户管理','删除');
		
		$dataArray = array("tableName"=>'team',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * 用户列表
	 */
	public function actionIndex()
	{
	    $this->pageTitle = "用户管理";
	    $name=$_REQUEST['name'];
		list($model,$pages,$items) = User::getIndexList();
		$this->render('index',array('items'=>$items,'pages'=> $pages,'name'=>$name));
		
	}


	
	public function loadModel($id)
	{
		$model=User::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("user/index",array('page'=>$_REQUEST['page']));
	}

	public function actionGetTeam() 
	{
		$id = intval($_REQUEST['user_id']);
		echo User::getTeam($id)->name;
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='admin-user-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionCheckAuth()
	{
		$operation=$_REQUEST['operation'];
		if(checkOperation($operation))
		{
			echo 'yes';
		}else{
			echo 'no';
		}
	}
	

	
}
