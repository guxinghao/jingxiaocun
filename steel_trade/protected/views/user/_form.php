
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'admin-user-form',
	'enableAjaxValidation'=>false,
)); ?>

<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">登&nbsp;&nbsp;录&nbsp;&nbsp;名：</div>
		<input type="text" disabled  id="login_name_value" name="User[loginname]" style="width:150px;height:33px;" value="<?php echo $model->loginname;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">昵　　称：</div>
		<input type="text" disabled id="nick_name_value" name="User[nickname]" style="width:150px;height:33px;" value="<?php echo $model->nickname;?>" class="form-control "   >
	</div>
	<?php if(!$model->id){?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">密　　码：</div>
		<input type="text" disabled id="password_value" name="User[password]" style="width:150px;height:33px;" value="<?php echo $model->password;?>" class="form-control "   >
	</div>
	<?php }?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">手&nbsp;&nbsp;机&nbsp;&nbsp;号：</div>
		<input type="text"  id="phone_value" name="User[phone]" style="width:150px;height:33px;" value="<?php echo $model->phone;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">财务&nbsp;&nbsp;编码：</div>
		<input type="text"  id="number_value" name="User[number]" style="width:150px;height:33px;" value="<?php echo $model->number;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业&nbsp;&nbsp;务&nbsp;&nbsp;组：</div>
		<select name="User[team_id]" class="form-control chosen-select forreset">
	        <option value="" >--请选择--</option>
	        <?php foreach (Team::model()->getTeamList("array")  as $k=>$v){?>
	    	<option value="<?php echo $k?>" <?php if($model->team_id==$k){?>selected="selected"<?php }?>><?php echo $v?></option>
	    	<?php }?>    	            	 
        </select>
	</div>
	
	<?php if($model-id){?>
	<!-- 进入本页最后更新时间 -->
	<input type="hidden" name='lupt' value="<?php echo time();#$this->getUpdateTime($model->tableName(), $model->id)?>"/>
	<div class="shop_more_one">
		<div class="shop_more_one_l">创建时间：</div>
		<span><?php echo date("Y-m-d H:i:s",$model->created_at);?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">最后登录时间：</div>
		<span><?php echo date("Y-m-d H:i:s",$model->last_login_at);?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">最后登录ip：</div>
		<span><?php echo $model->last_login_ip;?></span>
	</div>
	<?php }?>
	
</div>
<div class=" create_table " style="width:99%"></div>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">系统角色：</div>
	</div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<?php foreach($auths as $key=>$val){?>			
		<div class="shop_more_one" >
			<div class="shop_more_one_l" style="width:230px"><?php echo $key;?>：</div>
			<input type="checkbox"  <?php if($this->getAuth($key,$model->id)) echo "checked=checked"; ?> id="<?php echo $key;?>" name="now_right_name[]" style="width:50px;height:33px;margin:0" value="<?php echo $key;?>" class=" right_check">
		</div>
	<?php }?>
</div>
<?php if($ops){?>
<div class=" create_table " style="width:99%"></div>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">拥有操作：</div>
	</div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<?php foreach($ops as $key=>$val){?>			
		<div class="shop_more_one" >
			<div class="shop_more_one_l" style="width:230px"><?php echo $key;?>：</div>
			<input type="checkbox"  <?php if($this->getAuth($key,$model->id)) echo "checked=checked"; ?> id="<?php echo $key;?>" name="now_right_name[]" style="width:50px;height:33px;margin:0" value="<?php echo $key;?>" class=" right_check">
		</div>
	<?php }?>
</div>
<?php }?>
<div class="btn_list create_table" style="width:99%">
	<button type="submit" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
	<button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
</div>		
		
	<?php
	$this->endWidget ();
	?>
</div>
<script>
	$("#admin-user-form").submit(function(){
		var login_name_value = $("#login_name_value").val();
		var password_value = $("#password_value").val();
		var nick_name = $("#nick_name_value").val();
		if(login_name_value==""){
			alert("登录名不能为空");
			return false;
		}else if(nick_name==""){
			alert("昵称不能为空");
			return false;
		}<?php if(!$model->id){?>else if(password_value==""){
			alert("密码不能为空");
			return false;
		}
		<?php }?>
	});

	$(".cancel").click(function(){
		location.href="<?php echo Yii::app()->createUrl('user/index',array('page'=>$_REQUEST['page']))?>";
	});

</script>
