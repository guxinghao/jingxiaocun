<?php $this->renderPartial('_form', array(
		'model' => $model, 
		'baseform' => $baseform, 
		'vendor_array'=>$vendor_array,
		'customer_array' => $customer_array, 
		'logistics_array' => $logistics_array, 
		'gk_array' => $gk_array, 
		'title_array' => $title_array,
		'team_array' => $team_array,
		'user_array' => $user_array,
		'back_url' => $back_url
));?>