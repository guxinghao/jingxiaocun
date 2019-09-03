<?php
class AdminBaseController extends Controller {
	
// 	protected function beforeAction($action) {
// 		if (Yii::app ()->user->isGuest && !in_array($action->id, array('login','test','error','captcha'))) {
// 			$this->redirect(Yii::app()->createUrl('site/login'));
// 			return false;
// 		} else {
// 			return true;
// 		}
// 	}
	
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
						'actions'=>array('login', 'logout', 'login_api', 'autoHistory', 'shareEqually', 'messages','print','push','test','runToGetHer',"RandSales","RandSend",
								"RandOutput","RandInvoice","RandSalesInvoice","SmsSend","initBsprofit","resetStorageProfit","updateShareMain"),
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
	
	/**
	 * 
	 * 获取某表最后更新时间
	 * @param string $table 表名
	 * @param int $id 修改记录id
	 * @return int 最后更新时间
	 */
	public function getUpdateTime($table,$id){
		$cri = new CDbCriteria();
		$cri->params[':table'] =$table; 
		$cri->addCondition("table_name=:table");
		$cri->params[':idstr'] ="%".'"id":"'.$id.'"'."%"; 
		$cri->addCondition("oldValue like :idstr or newValue like :idstr");
		$cri->order = "created_at desc";
		$log = LogDetail::model()->find($cri);
		return $log->created_at?$log->created_at:"0";
	}
	
	/**
	 * 
	 * 核对最后更新前是否有改动
	 * @param string $table 表名
	 * @param int $time 上次修改时间
	 * @param int $id 修改记录id
	 * @return bool false表示该表被人更改过,不允许修改。true表示正常可更改
	 */
	public function checkUpdateTime($table,$time,$id){
		$last_update = $this->getUpdateTime($table,$id);
		return $time>=$last_update;
		
	}
}

?>