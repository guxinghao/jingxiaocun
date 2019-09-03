<?php 
echo $this->renderPartial('_form', array(
		'model' => $model,
		'baseform' => $baseform,
		'company_array' => $company_array,
		'title_array' => $title_array,
		'team_array' => $team_array, //业务组
		'user_array' => $user_array, //业务员
		'details' => $details,
		'back_url' => $back_url,
		'msg' => $msg
));	
?>