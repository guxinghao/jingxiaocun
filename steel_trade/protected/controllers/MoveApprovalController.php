<?php
class MoveApprovalController extends MoveApproveController{
	public $layout='move';
	
	public function actionLogin(){
		$this->pageTitle="登陆";
		$model = new LoginForm;
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			setcookie('moveusername',$model->username,time()+3600*24*30*100,"/");
			setcookie('movepassword',$model->password,time()+3600*24*30*100,"/");
			if($model->validate()&&$model->login()){
				$api_center = new api_center();
				$result = $api_center->loginAuthorization($model->username, $model->password);
				$result = json_decode($result);
				if ($result->result == 'error'){
					$msg = $result->message;
				}else{
					$user = User::model()->findByPk(Yii::app()->user->userid);
					$user->last_login_at = time();
					$user->last_login_ip = Yii::app()->request->userHostAddress;
					$user->update();
					$this->redirect(Yii::app()->createUrl('moveApproval/index'));
				}
			}else{
				$msg = "用户名或密码错误";
			}
		}else if($_REQUEST['is_another']=='yes'){
			$model->attributes=$_GET;		
			setcookie('moveusername',$model->username,time()+3600*24*30*100,"/");
			setcookie('movepassword',$model->password,time()+3600*24*30*100,"/");
			if($model->validate() && $model->login()){
				$api_center = new api_center();
				$result = $api_center->loginAuthorization($model->username, $model->password);
				$result = json_decode($result);
				if ($result->result == 'error') {
					$msg = $result->message;
				} else {						
					$user = User::model()->findByPk(Yii::app()->user->userid);
					$user->last_login_at = time();
					$user->last_login_ip = Yii::app()->request->userHostAddress;
					$user->update();				
				}
			}
			return;
		}
		$username = $_COOKIE["moveusername"];
		$password = $_COOKIE["movepassword"];
		if($username && $password){
			$_POST['LoginForm']["username"] = $username;
			$_POST['LoginForm']["password"] = $password;
			$model->attributes=$_POST['LoginForm'];
			if($model->validate()&&$model->login()){
				$api_center = new api_center();
				$result = $api_center->loginAuthorization($model->username, $model->password);
				$result = json_decode($result);
				if ($result->result == 'error'){
					$msg = $result->message;
				}else{
					$user = User::model()->findByPk(Yii::app()->user->userid);
					$user->last_login_at = time();
					$user->last_login_ip = Yii::app()->request->userHostAddress;
					$user->update();
					$this->redirect(Yii::app()->createUrl('moveApproval/index'));
				}
			}
		}
		$this->render('login1', array(
				'model' => $model,
				'msg' => $msg,
		));
	}
	
	public function actionIndex(){
		$this->pageTitle="费用审批";
		$this->render('index', array(
				'model' => $model,
		));
	}
	
	//根据类型获取对应数据
	public function actionGetDataList(){
		$type = $_POST['type'];
		$result = $_POST['result'];
		$model = new MoveView();
		$criteria=New CDbCriteria();
		if($type){
			$criteria->addCondition("form_type='{$type}'");
			$criteria->order='last_update desc';
		}else{
			$criteria->order="last_update desc,locate(form_type,'FKDJ,DQDK,FYBZ')";
		}
		$model = $model->findAll($criteria);
		
		$this->renderPartial('list', array(
				'model' => $model,
				'type' =>$type,
				'result' =>$result,
		));
	}
	
	//获取表单审核记录
	public function actionGetCheckList(){
		$id = intval($_POST["id"]);
		$items = BillApproveLog::model()->findAll("form_id = :form_id ORDER BY created_at DESC", array(':form_id' => $id));
		$this->renderPartial('checklist', array('items' => $items));
	}
	
	//信息详情页
	public function actionDetail($id){
		$this->pageTitle='审批详情';
		$user_id = $_COOKIE["move_user_id"];
		$baseform = MoveView::model()->find("common_id={$id}");
		if($baseform->form_type == "FYBZ"){
			$detail = BillOtherDetail::model()->findAll("bill_other_id={$baseform->main_id}");
		}
		$bank = DictBankInfo::model()->findByPk($baseform->bank_id);
		$this->render('detail', array(
				'baseform' => $baseform,
				'detail' => $detail,
				'bank'=>$bank,
		));
	}
	
	//审核通过
	public function actionPass(){
		$last = $_POST["last"];
		$id = intval($_POST["id"]);
		$baseform = CommonForms::model()->findByPk($id);
		if($baseform->last_update != $last){
			echo "单据已不是最新";
			die;
		}
		if($baseform->form_type == "FKDJ"){
			$form = new FormBill($baseform->form_type, $id);
			$result = $form->approveForm();
		}else if($baseform->form_type == "DQDK"){
			$form = new ShortLoanClass($id);
			$result = $form->approveForm();
		}else if($baseform->form_type == "FYBZ"){
			$form = new BillOther($baseform->form_type, $id);
			$result = $form->approveForm();
		}
		if($result){
			echo "success";
		}else{
			echo "操作失败";
		}
	}
	//审核拒绝
	public function actionUnPass(){
		$last = $_POST["last"];
		$id = intval($_POST["id"]);
		$baseform = CommonForms::model()->findByPk($id);
		if($baseform->last_update != $last){
			echo "单据已不是最新";
			die;
		}
		if($baseform->form_type == "FKDJ"){
			$form = new FormBill($baseform->form_type, $id);
			$result = $form->refuseForm();
		}else if($baseform->form_type == "DQDK"){
			$form = new ShortLoanClass($id);
			$result = $form->refuseForm();
		}else if($baseform->form_type == "FYBZ"){
			$form = new BillOther($baseform->form_type, $id);
			$result = $form->refuseForm();
		}
		
		if($result){
			echo "success";
		}else{
			echo "操作失败";
		}
	}
	
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
		setcookie('moveusername');
		setcookie('movepassword');
		$this->redirect(Yii::app()->createUrl('moveApproval/login'));
	}
	
	
}