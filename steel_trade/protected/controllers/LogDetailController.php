<?php

class LogDetailController extends AdminBaseController
{
	public $layout='admin';
	
	public function actionIndex()
	{
		$this->pageTitle = "数据日志";
		$model = new LogDetail();
		
		$cri = new CDbCriteria();
		$search =  new LogDetail();
		if($_POST['LogDetail']){
			$search->attributes = $_POST['LogDetail'];
			if($search->table_name){
				$cri->addCondition("table_name like '%{$search->table_name}%' or oldValue like '%{$search->table_name}%' or newValue like '%{$search->table_name}%'");
			}
			if( $_POST['LogDetail']['start_time']){
				$st = strtotime($_POST['LogDetail']['start_time']." 00:00:00");
				$cri->addCondition("created_at >= $st");
			}
			if($_POST['LogDetail']['end_time']){
				$et = strtotime($_POST['LogDetail']['end_time']." 23:59:59");
				$cri->addCondition("created_at <= $et");
			}
		}
		
		$cri->order = "created_at desc,id desc";
		$pages = new CPagination();
		$pages->itemCount = $model->count($cri);
		$pages->pageSize = $_COOKIE['log_d']? intval($_COOKIE['log_d']):Yii::app()->params['pageCount'];
		$pages->applyLimit($cri);
		$items = $model->findAll($cri);
		
		
		
		$this->render('index',array(
				'search'=>$search,
				'pages'=> $pages,
				'items'=>$items,
				'model'=>$model,
		));
	}
	
	
	
}