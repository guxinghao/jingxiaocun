<?php

class BillApproveLogController extends AdminBaseController 
{
	public function actionGetFormLog() 
	{
		$form_id = intval($_REQUEST['form_id']);
		$items = BillApproveLog::model()->findAll("form_id = :form_id ORDER BY created_at DESC", array(':form_id' => $form_id));
		
		echo $this->renderPartial('alertMain', array('items' => $items)); 
	}
}