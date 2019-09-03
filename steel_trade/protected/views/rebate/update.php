<?php 
echo $this->renderPartial('_form', array(
		'model' => $model, 
		'baseform' => $baseform,
		'customer_array' => $customer_array,
		'logistics_array' => $logistics_array,
		'gk_array' => $gk_array,
		'title_array' => $title_array,
		'team_array' => $team_array,
		'user_array' => $user_array,
		'bank_info_array' => $bank_info_array, //结算账户
		'dict_bank_info_array' => $dict_bank_info_array, //公司账户
		'details' => $details,
		'back_url' => $back_url,
		'msg' => $msg
));
?>
