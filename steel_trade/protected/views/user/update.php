<?php

echo $this->renderPartial('_form', array('model'=>$model,'auths'=>$auths,
			'msg'=>$msg,
			"ops"=>$ops,
			"has_right"=>$has_right,));