<?php
//区域管理
class AreaController extends AdminBaseController
{
	public $layout='admin';	
	public function actionIndex()
	{
		$this->pageTitle = "区域管理";
		$model = new WareArea();		
		$cri = new CDbCriteria();
		$search =  new WareArea();		
		if($_POST['search']){			
			$search->attributes = $_POST['search'];
			if($search->name){
				$cri->params[':search'] = "%".$search->name."%";
				$cri->addCondition("name like :search");
			}
		}		
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
		$model = new WareArea();
		$this->pageTitle = "新建区域";
		if($_POST['WareArea']){
			$success = $model->createArea($_POST['WareArea']);
			if($success<0){
				$msg = "该名称已存在";
			}elseif($success){
				//日志
				$base = new BaseForm();
				$base->operationLog('区域管理','新增');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'warearea',"newValue"=>$mainJson,"oldValue"=>"");
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
		$model = WareArea::model()->findByPk($_REQUEST['id']);
		if($_POST['WareArea']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateArea($_POST['WareArea']);
				if($success<0){
					$msg = "该名称已存在";
				}elseif($success){
					//日志
					$base = new BaseForm();
					$base->operationLog('区域管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'warearea',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					$this->returnListPage();
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "区域修改";
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = WareArea::model()->findByPk($id);
		$time = intval($_REQUEST['time']);
		$canDelete = $this->checkUpdateTime($model->tableName(), $time, $id);
		if(!$canDelete){
			echo "updated";
			return;
		}
		//make sure that there is no warehouse belongs to this area
		if($this->checkRelation($id))
		{
			echo 'deny';
			return;
		}
		
		$mainJson = $model->datatoJson();
		$model->delete();
		//日志
		$base = new BaseForm();
		$base->operationLog('区域管理','删除');
		
		$dataArray = array("tableName"=>'warearea',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("area/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}
	
	public function checkRelation($id)
	{
		$bool=Warehouse::model()->exists("area=".$id);
		return $bool;
	}
	
}

?>