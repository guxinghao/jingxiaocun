<?php

class WarehouseController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "仓库管理";
		if(isset($_REQUEST['name']))
		{
			$_POST['Warehouse']['name']=$_REQUEST['name'];
		}		
		list($model,$search,$pages,$items) = Warehouse::getIndexList();
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,				
		));
	}
	
	
	public function actionCreate()
	{
		$model = new Warehouse();
		$this->pageTitle = "仓库信息新建";
		if($_POST['Warehouse']){
			$success = $model->createWarehouse($_POST['Warehouse']);
			if($success<-5){
				$msg = "标准码已存在";
			}elseif($success<0){
				$msg = "仓库名已存在";
			}else{
				//日志
				$base = new BaseForm();
				$base->operationLog('仓库管理','新建');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'warehouse',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				//推送信息
				if(Yii::app()->params['api_switch'] == 1){
					$result = Warehouse::synchronization($model, 'add');
				}
				$this->returnListPage();
			}
		}
		$areas=WareArea::getList();
		$this->render('create',array(
				'model'=>$model,
				'msg'=>$msg,
				'backUrl' => $this->getBackListPageUrl(),
				'areas'=>$areas,
		));
	}
	
	public function actionUpdate()
	{
		$model = Warehouse::model()->findByPk($_REQUEST['id']);
		if($_POST['Warehouse']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
			$_POST['Warehouse']['is_jxc'] = intval($_POST['Warehouse']['is_jxc']);
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateWarehouse($_POST['Warehouse']);
				if($success<-5){
					$msg = "标准码已存在";
				}elseif($success<0){
					$msg = "名称已存在";
				}else{
					//日志
					$base = new BaseForm();
					$base->operationLog('仓库管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'warehouse',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					//推送信息
					if(Yii::app()->params['api_switch'] == 1){
						$result = Warehouse::synchronization($model, 'edit');
					}
					$this->returnListPage();
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "仓库信息修改";
		$areas=WareArea::getList();
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
			'areas'=>$areas
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = Warehouse::model()->findByPk($id);
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
		$base->operationLog('仓库管理','删除');
		//推送信息
		if(Yii::app()->params['api_switch'] == 1){
			$result = Warehouse::synchronization($main, 'delete');
		}
		$dataArray = array("tableName"=>'warehouse',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("warehouse/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	
	
}