<?php echo $this->renderPartial('_form', array(
		'model' => $model,
		'baseform' => $baseform,
		'title_output_array' => $title_output_array,
		'title_input_array' => $title_input_array,
		'output_bank_array' => $output_bank_array,
		'input_bank_array' => $input_bank_array,
		'team_array' => $team_array, //业务组
		'user_array' => $user_array, //业务员
		'back_url' => $back_url,
		'msg' => $msg,
		'bank_json' => $bank_json,
));