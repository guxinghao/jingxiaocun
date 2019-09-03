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
		<div class="shop_more_one_l"><span class="bitian">*</span>角&nbsp;&nbsp;色&nbsp;&nbsp;名：</div>
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
<?php if($role_rights){?>
<div class=" create_table " style="width:99%"></div>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">拥有角色：</div>
	</div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<?php foreach($role_rights as $r_rights){?>			
		<div class="shop_more_one" >
			<div class="shop_more_one_l" style="width:230px"><?php echo $r_rights->name;?>：</div>
			<input type="checkbox" <?php if($has_right){
														foreach($has_right as $has){
															if($has->name == $r_rights->name)
																echo checked;
														}
													}?>  id="<?php echo $r_rights->name;?>" 
		<?php 
		$auth = Yii::app()->authManager;
		$disable = $auth->hasItemChild($r_rights->name,$model->name);
		if($disable){?>onclick="confirmDialog('当前编辑角色为此角色的子角色，不可选择！');return false;"<?php }?>																					
			name="now_right_name[]" style="width:50px;height:33px;margin:0" value="<?php echo $r_rights->name;?>" class=" right_check">
		</div>
	<?php }?>
</div>
<?php }?>
<?php if($all_right){?>
<div class=" create_table " style="width:99%"></div>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">拥有任务：</div>
	</div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<?php foreach($all_right as $right){?>			
		<div class="shop_more_one" >
			<div class="shop_more_one_l" style="width:230px"><?php echo $right->name;?>：</div>
			<input type="checkbox" <?php if($has_right){
														foreach($has_right as $has){
															if($has->name == $right->name)
																echo checked;
														}
													}?>  id="<?php echo $right->name;?>" name="now_right_name[]" style="width:50px;height:33px;margin:0" value="<?php echo $right->name;?>" class=" right_check">
		</div>
	<?php }?>
</div>
<?php }?>
<?php if($operation){?>
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
													}?> id="<?php echo $op->name;?>" name="now_right_name[]" style="width:50px;height:33px;margin:0" value="<?php echo $op->name;?>" class=" right_check">
		</div>
	<?php }?>
</div>
<?php }?>
<div class="btn_list create_table" style="width:99%;margin-bottom:30px">
	<button type="submit" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
	<button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
</div>	

	
<?php $this->endWidget (); ?>	
<script>
	$(function(){
		$(".cancel").click(function(){
			location.href="<?php echo Yii::app()->createUrl('role/index',array('page'=>$_REQUEST['page']))?>";
		});
	});

	$(".save").click(function(){
		if($.trim( $("#rolename").val() ) == "" || $.trim( $("#rolename").val() ) == null){
			confirmDialog("角色名不能为空！");
			$("#rolename").focus();
			return false;
		}
		$("form").submit();
	});
</script>

