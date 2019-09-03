
<?php 

$this->pageTitle = "新增角色";
$this->renderPartial(
		'_form',
		array(
			'model'=>$model,
			'all_right'=>$all_right,
			"role_rights"=>$role_rights,
			"operation"=>$operation,
)); ?>