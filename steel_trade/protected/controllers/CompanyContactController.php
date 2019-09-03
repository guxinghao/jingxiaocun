<?php

class CompanyContactController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "结算单位联系人管理";
		list($model,$search,$pages,$items) = CompanyContact::getIndexList();
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
		$model = new CompanyContact();
		$this->pageTitle = "结算单位联系人新建";
		if($_POST['CompanyContact']){
			$success = $model->createContact($_POST['CompanyContact']);
			if($success){
//				$msg = "名称已存在";
//			}else{
				//日志
				$base = new BaseForm();
				$base->operationLog('结算单位联系人管理','新建');
				$mainJson = $model->datatoJson();
				$dataArray = array("tableName"=>'company_contact',"newValue"=>$mainJson,"oldValue"=>"");
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
		$model = CompanyContact::model()->findByPk($_REQUEST['id']);
		
		if($_POST['CompanyContact']){
			$canUpdate = $this->checkUpdateTime($model->tableName(), $_POST['lupt'], $model->id);//检查最后更新时间
//			var_dump($canUpdate);
//			die;
			if($canUpdate){
				$oldJson = $model->datatoJson();
				$success = $model->updateContact($_POST['CompanyContact']);
				
				if($success){
//					$msg = "名称已存在";
//				}else{
					//日志
					$base = new BaseForm();
					$base->operationLog('结算单位联系人管理','修改');
					$mainJson = $model->datatoJson();
					$dataArray = array("tableName"=>'company_contact',"newValue"=>$mainJson,"oldValue"=>$oldJson);
					$base->dataLog($dataArray);
					
					$this->returnListPage();
				}else{
					$msg="保存失败";
				}
			}else{
				$msg="数据已不是最新，请重新编辑后提交！";
			}
		}
		$this->pageTitle = "结算单位联系人修改";
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
		$model = CompanyContact::model()->findByPk($id);
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
		$base->operationLog('结算单位联系人管理','删除');
		
		$dataArray = array("tableName"=>'company_contact',"newValue"=>"","oldValue"=>$mainJson);
		$base->dataLog($dataArray);
	}
	
	public function getBackListPageUrl()
	{
	    if(!$_GET['dict_company_id']){
		    return Yii::app()->createUrl("companyContact/index",array('page'=>$_REQUEST['page']));
	    }else{
	    return Yii::app()->createUrl("companyContact/index",array('page'=>$_REQUEST['page'],'dict_company_id'=>$_GET['dict_company_id']));
	    }
	}
	
	public function returnListPage()
	{
		$this->redirect($this->getBackListPageUrl());
	}

	public function actionCheckDefault(){
		$id = intval($_REQUEST['id']);
		$cid = intval($_REQUEST['company']);
		$now = CompanyContact::model()->find("dict_company_id=$cid and is_default=1 and id<>$id");
		if($now){
			echo "当前公司默认联系人为".$now->name.",是否确认更改？";
		}
	}
	
	//获取关联账户 json
//	public function actionGetBankList() 
//	{
//		$id = intval($_REQUEST['id']);
//		if (!$id) return ;
//		$bank_json = BankInfo::getBankList("json", $id);
//		echo $bank_json;
//	}
	
}