<?php



echo $this->renderPartial('_form', array('model'=>$model,'auths'=>$auths,
			'msg'=>$msg,
			"operation"=>$operation,)); ?>
