<?php
//修改密码
class ChangePwdController extends AdminBaseController
{
	public function actionIndex()
	{
		$this->pageTitle = "修改密码";
		$this->render('index',array('msg' => $msg));
	}
	// 是否修改密码页面
	public function actionIsChangePwd()
	{
		$this->pageTitle = "修改密码";
		$this->render('isChangePwd');
	}
	// 修改密码
	public function actionUpdatePwd()
	{
		if ($_POST) {
			$userName = $_POST['userName'];
			$oldPwd = md5($_POST['oldPwd']);
			$newPwd = md5($_POST['newPwd']);
			$model = User::model()->find('loginname="'.$userName.'" and password="'.$oldPwd.'"');
			$member_id = $model->id;
			$model->password = $newPwd;
			if (!$model->update()){
                $msg = '修改失败';
            } else {
	            $msg = '修改成功';
	            $this->render('index',array('msg' => $msg));
            }
		}

	}


	// 判断用户是否存在
	public function actionCheckUser()
	{
		$userName = $_GET['userName'];
		$info = User::model()->find("loginname='{$userName}'");
		if ($info) {
			echo '1';
		}else{
			echo '0';
		}
	}
	// 判断用户原始密码是否正确
	public function actionCheckOldPwd()
	{
		$oldPwd = md5($_GET['oldPwd']);
		$userName = $_GET['userName'];
		$info = User::model()->find('loginname="'.$userName.'" and password="'.$oldPwd.'"');
		if ($info) {
			echo '1';
		}else{
			echo '0';
		}
	}


}
