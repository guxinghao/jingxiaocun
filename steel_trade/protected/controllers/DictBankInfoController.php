<?php

class DictBankInfoController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "公司账户管理";
		list($model, $items, $pages) = DictBankInfo::getIndexList();
		
		$titles = DictTitle::getComs("json"); //公司
		
		$this->render('index', array(
				'model' => $model, 
				'items' => $items, 
				'pages' => $pages, 
				'titles' => $titles
		));
	}
	
	
	public function actionCreate()
	{
		$titlelist = array();
		$model = new DictBankInfo();
		$title_id = intval($_REQUEST['title_id']);
		if($title_id){
			$model->dict_title_id = $title_id;
			array_push($titlelist,$title_id);
		}
		$this->pageTitle = "公司账户新建";
		if($_POST['DictBankInfo']){
			$success = $model->createBankInfo($_POST['DictBankInfo']);
			if($success==1){
				//日志
				$base = new BaseForm();
				$base->operationLog('公司账户管理','新建');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'dict_bank_info',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				$this->returnListPage();
			}elseif($success==0){
				$msg="保存失败";
			}else if($success==-1){
				$msg="帐号已存在";
			}
		}
		$titles = DictTitle::getComs("array");
		$this->render('create',array(
				'model'=>$model,
				'msg'=>$msg,
				'backUrl' => $this->getBackListPageUrl(),
				'titles'=>$titles,
				'titlelist'=>$titlelist
		));
	}
	
	public function actionUpdate()
	{
		$id = $_REQUEST['id'];
		$model = DictBankInfo::model()->findByPk($id);
		if($_POST['DictBankInfo']){
			
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间

			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateBankInfo($_POST['DictBankInfo']);
				if($success){
					$this->returnListPage();
				}else{
					$msg="保存失败";
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$titlelist = array();
		$relation = DictTitleBankRelation::model()->findAll("bank_id=".$id);
		if($relation){
			foreach ($relation as $li){
				array_push($titlelist,$li->title_id);
			}
		}
		$this->pageTitle = "公司账户修改";
		$titles = DictTitle::getComs("array");
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
			'titles'=>$titles,
			'titlelist'=>$titlelist
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = DictBankInfo::model()->findByPk($id);
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
		$base->operationLog('公司账户管理','删除');
		
		$dataArray = array("tableName"=>'dict_bank_info',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
		$relation = DictTitleBankRelation::model()->findAll("bank_id=".$id);
		foreach ($relation as $li){
				$oldJson = $li->datatoJson();
				$li->delete();
				$base = new BaseForm();
				$dataArray = array("tableName"=>'dict_title_bank_relation',"newValue"=>'',"oldValue"=>$oldJson);
				$base->dataLog($dataArray);
			}
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("dictBankInfo/index",array('page'=>$_REQUEST['page'],'title_id'=>$_REQUEST['title_id']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	//获取关联账户 json
	public function actionGetBankList() 
	{
		$id = intval($_REQUEST['id']);
		if(isset($_REQUEST['is_yidan'])){
			$is_yidan=intval($_REQUEST['is_yidan']);
		}else{
			$is_yidan=100;
		}		
		if (!$id) return ;
		$bank_json = DictBankInfo::getBankList('json', $id,$is_yidan);
		echo $bank_json;
	}
	public function actionGetBankName()
	{
		$id=$_REQUEST['bank_id'];
		$is_yidan=$_REQUEST['is_yidan'];		
		$model=DictBankInfo::model()->findByPk($id);
		if($model)
		{
			if($is_yidan)
			{
				if($model->bank_level==0||$model->bank_level==-1)
				{
					echo  $model->dict_name;
				}
			}else{
				if($model->bank_level==0||$model->bank_level==1)
				{
					echo  $model->dict_name;
				}
			}
			
		}
	}
}