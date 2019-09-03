
<?php 
$this->pageTitle = '修改角色';

$this->renderPartial('_form', array(
		'model'=>$model,
		'all_right'=>$all_right,
		'has_right'=>$has_right,
		"role_rights"=>$role_rights,
		"operation"=>$operation,
		"authright"=>$authright,
)); ?>