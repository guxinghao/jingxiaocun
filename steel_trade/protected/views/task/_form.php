<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array (
				'id' => 'output_fee' ,
				'enctype'=>'multipart/form-data',
		) 
) );
$this_priority = AuthItem::model()->findByPk($model->name)->priority;
$model->priority = $this_priority? $this_priority:100;
?>

<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>任&nbsp;&nbsp;务&nbsp;&nbsp;名：</div>
		<input type="text" id="rolename" name="AuthRoleForm[name]" style="width:150px;height:33px;" value="<?php echo $model->name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">描　　述：</div>
		<input type="text" id="pw" name="AuthRoleForm[description]" style="width:150px;height:33px;" value="<?php echo $model->description;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">优&nbsp;&nbsp;先&nbsp;&nbsp;级：</div>
		<input type="text"  id="Task" name="AuthRoleForm[priority]" style="width:150px;height:33px;" value="<?php echo $model->priority;?>" class="form-control "   >
	</div>
</div>
<?php if($all_right){?>
<div class=" create_table " style="width:99%"></div>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">拥有操作：</div>
	</div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<?php foreach($operation as $op){?>			
		<div class="shop_more_one" >
			<div class="shop_more_one_l" style="width:230px"><?php echo $op->name;?>：</div>
			<input type="checkbox" <?php if($has_right){
														foreach($has_right as $has){
															if($has->name == $op->name)
																echo checked;
														}
													}?>  id="<?php echo $op->name;?>" name="now_right_name[]" style="width:50px;height:33px;margin:0" value="<?php echo $op->name;?>" class=" right_check">
		</div>
	<?php }?>
</div>
<?php }?>
<div class="btn_list create_table" style="width:99%">
	<button type="submit" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
	<button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
</div>	

	
<?php $this->endWidget (); ?>	
<script>
	$(function(){
		$(".cancel").click(function(){
			location.href="<?php echo Yii::app()->createUrl('task/index',array('page'=>$_REQUEST['page']))?>";
		});
	});

	$(".save").click(function(){
		if($.trim( $("#rolename").val() ) == "" || $.trim( $("#rolename").val() ) == null){
			confirmDialog("任务名不能为空！");
			$("#rolename").focus();
			return false;
		}
		$("form").submit();
	});
</script>