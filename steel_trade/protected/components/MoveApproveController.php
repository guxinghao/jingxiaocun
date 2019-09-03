<?php
class MoveApproveController extends Controller {
	
	protected function beforeAction($action) {
		if (Yii::app()->user->isGuest && !in_array($action->id, array('login','test','error','captcha'))) {
			$this->redirect(Yii::app()->createUrl('moveApproval/login'));
			return false;
		} else {
			return true;
		}
	}
	
	public function filters()
	{
		return array(
				'accessControl',
		);
	}
	
	public function accessRules()
	{
		return array(
				array('allow',
						'actions'=>array('login','index',"getDataList","getCheckList","detail","pass","unPass"),
						'users'=>array('*'),
				),
				array('allow',
						'users'=>array('@'),
				),
				array('deny',
						'users'=>array('*'),
				),				
		);
	}
	
	public function actions() {
		return array (
				// captcha action renders the CAPTCHA image displayed on the contact page
				'captcha'=>array(
					'class'=>'CCaptchaAction',
					'height'=>38,
					'width'=>83,
					'minLength'=>4,
					'maxLength'=>4,
					'backColor'=>0xFFFFFF, 
					'transparent'=>false,
					'testLimit'=>999, 
				),
				// page action renders "static" pages stored under 'protected/views/site/pages'
				// They can be accessed via: index.php?r=site/page&view=FileName
				'page' => array (
						'class' => 'CViewAction' 
				) 
		);
	}
	public function getAuth($authitem,$id=null){
		$auth=Yii::app()->authManager;
		if(!$id){
			$id = Yii::app()->user->userid;
		}
		$bool=$auth->checkAccess($authitem,$id);
		return $bool;
	}
	

	
}

?>