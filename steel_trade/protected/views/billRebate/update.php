<?php $this->renderPartial('_form', array(
		'model' => $model, 
		'baseform' => $baseform, 
		'title_array' => $title_array,
		'company_array' => $company_array,
		'warehouse_array' => $warehouse_array, 
		'team_array' => $team_array,
		'user_array' => $user_array,
		'back_url' => $back_url,
		'msg' => $msg,
));