<?php 
$this->renderPartial('_form', array(
		'model' => $model, 
		'baseform' => $baseform,
		'company_array' => $company_array, 
		'back_url' => $back_url, 
		'msg' => $msg
));
?>