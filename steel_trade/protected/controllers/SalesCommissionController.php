<?php
class SalesCommissionController extends AdminBaseController
{
	//新建销售提成
	public function actionCreate(){
		$this->pageTitle = "登记销售提成";
		if($_POST){
			$result = SalesCommission::createForm($_POST);
			if($result == 1){
				//$msg = "保存成功";
				$this->redirect(yii::app()->createUrl("salesCommission/index"));
			}else{
				$msg = "保存失败";
			}
		}
		//业务员
		$users=User::getUserList();
		$this->render('create',array(
			"model"=>$model,
			"users"=>$users,
			"msg"=>$msg,
		));
	}
	
	//销售提成列表
	public function actionIndex(){
		$this->pageTitle = "销售提成";
		if(isset($_REQUEST['search']))
		{
			$search=$_REQUEST['search'];
		}
		//获取表单列表
		list($details,$pages,$totaldata)=SalesCommission::getFormList($search);
		//业务员
		$users=User::getUserList();
		$this->render('index',array(
			"model"=>$model,
			"users"=>$users,
			"msg"=>$msg,
			"details"=>$details,
			'pages'=>$pages,
			'search'=>$search,
			"totalData"=>$totaldata,
		));
	}
	
	//编辑销售提成
	public function actionUpdate($id){
		$this->pageTitle = "编辑销售提成";
		if($_POST){
			$result = SalesCommission::updateForm($_POST,$id);
			if($result == 1){
				//$msg = "保存成功";
				$this->redirect(yii::app()->createUrl("salesCommission/index"));
			}else{
				$msg = "保存失败";
			}
		}
		$model = SalesCommission::model()->findByPk($id);
		$year = date("Y",strtotime($model->date));
		$month = date("m",strtotime($model->date));
		$userId = $model->owned_by;
		list($yg_weight,$yg_money) = FrmSales::GetUserWeight($year,$month,$userId);
		$this->render('update',array(
				"model"=>$model,
				"yg_weight"=>$yg_weight,
				"yg_money"=>$yg_money,
				'year'=>$year,
				'month'=>$month,
				"msg"=>$msg,
		));
	}
	
	//作废销售提成
	public function actionDelete($id){
		$model = SalesCommission::model()->findByPk($id);
		$model->status = -1;
		if($model->update()){
			echo "success";
		}else{
			echo "fail";
		}
	}
	
	//审核销售提成
	public function actionCheck($id){
		$type = $_REQUEST['type'];
		$transaction=Yii::app()->db->beginTransaction();
		try {
			$model = SalesCommission::model()->findByPk($id);
			if($type == "pass"){
				$model->status = 1;
				$status = 1;
			}else if($type == "unpass"){
				$model->status = 0;
				$status = 0;
			}
			if($model->update()){
				SalesCommission::subsidy($model,$status);
			}
			$transaction->commit();
		}catch (Exception $e)
		{
			$transaction->rollBack();//事务回滚
			echo "fail";
		}
		echo "success";
	}
	
	//获取业务员提成列表
	public function actionGetUserData(){
		$year = $_POST["year"];
		$month = $_POST['month'];
		if($month < 10){
			$month = "0".$month;
		}
		$date = $year."-".$month;
		$users=User::getUserList();
		$data = array();
		foreach ($users as $k=>$v){
			if(!Yii::app()->authManager->checkAccess('业务员',$k)){continue;}
			list($yg_weight,$yg_money) = FrmSales::GetUserWeight($year,$month,$k);
			$temp = array();
			$temp["id"] = $k;
			$temp["name"] = $v;
			$temp["yg_weight"] = $yg_weight;
			$temp["yg_money"] = $yg_money;
			$has_com = SalesCommission::model()->find("date like '%{$date}%' and status<>-1 and owned_by={$k}");
			if($has_com){
				$temp["money"] = $has_com->money;
			}else{
				$temp["money"] = "";
			}
			array_push($data,$temp);
		}
		$this->renderPartial('_list', array(
			"data"=>$data
		));
	}
}