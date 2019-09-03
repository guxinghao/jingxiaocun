<?php

class LogController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "操作日志";
		$model = new Log();
		
		$cri = new CDbCriteria();
		$search =  new Log();
		if($_POST['Log']){
			$search->attributes = $_POST['Log'];
			if($search->comment){
				$cri->addCondition("comment like '%{$search->comment}%' or business_name like '%{$search->comment}%' or operation_type like '%{$search->comment}%'");
			}
			if( $_POST['Log']['start_time']){
				$st = strtotime($_POST['Log']['start_time']." 00:00:00");
				$cri->addCondition("created_at >= $st");
			}
			if($_POST['Log']['end_time']){
				$et = strtotime($_POST['Log']['end_time']." 23:59:59");
				$cri->addCondition("created_at <= $et");
			}
			if($_POST['Log']['created']){
				$cri->addCondition("created_by = {$_POST['Log']['created']}");
			}
		}
		
		$cri->order = "created_at desc";
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['log']? intval($_COOKIE['log']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		
		$user_array=User::getUserList();
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
				'users'=>$user_array,
		));
	}
	
	
	
}