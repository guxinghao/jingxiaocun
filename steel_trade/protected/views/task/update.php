
<?php 
$this->pageTitle = "任务编辑";
$this->renderPartial('_form', array('model'=>$model,'all_right'=>$all_right,'has_right'=>$has_right,"operation"=>$operation,)); ?>