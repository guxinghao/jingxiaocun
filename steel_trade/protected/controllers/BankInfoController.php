<?php

class BankInfoController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "结算账户管理";
		list($model,$search,$pages,$items) = BankInfo::getIndexList();
		$coms = DictCompany::getComs("json");
		
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
				'coms'=>$coms
		));
	}
	
	
	public function actionCreate()
	{
		$model = new BankInfo();
		$this->pageTitle = "结算账户新建";
		if($_POST['BankInfo']){
			$success = $model->createBankInfo($_POST['BankInfo']);
			if($success){
//				$msg = "名称已存在";
//			}else{
				//日志
				$base = new BaseForm();
				$base->operationLog('结算账户管理','新建');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'bank_info',"newValue"=>$mainJson,"oldValue"=>"");
				$base->dataLog($dataArray);
				
				
				$this->returnListPage();
			}else{
				$msg="保存失败";
			}
		}
		$coms = DictCompany::getComs("json");
		$this->render('create',array(
				'model'=>$model,
				'msg'=>$msg,
				'backUrl' => $this->getBackListPageUrl(),
				'coms'=>$coms
		));
	}
	
	public function actionUpdate()
	{
		$model = BankInfo::model()->findByPk($_REQUEST['id']);
		
		if($_POST['BankInfo']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
//			var_dump($canUpdate);
//			die;
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateBankInfo($_POST['BankInfo']);
				
				if($success){
//					$msg = "名称已存在";
//				}else{
					//日志
					$base = new BaseForm();
					$base->operationLog('结算账户管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'bank_info',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					
					$this->returnListPage();
				}else{
					$msg="保存失败";
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "结算账户修改";
		$coms = DictCompany::getComs("json");
		$this->render('update',array(
			'model'=>$model,
			'msg'=>$msg,
			'backUrl' => $this->getBackListPageUrl(),
			'coms'=>$coms
		));
	}
	
	public function actionDelete(){
		$id = $_REQUEST['del_id'];
		$model = BankInfo::model()->findByPk($id);
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
		$base->operationLog('结算账户管理','删除');
		
		$dataArray = array("tableName"=>'bank_info',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
	}
	
	public function getBackListPageUrl()
	{
		return Yii::app()->createUrl("bankInfo/index",array('page'=>$_REQUEST['page']));
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	//获取关联账户 json
	public function actionGetBankList() 
	{
		$id = intval($_REQUEST['id']);
		if (!$id) return ;
		$bank_json = BankInfo::getBankList("json", $id);
		echo $bank_json;
	}
	
}