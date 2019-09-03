<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array (
				'id' => 'output_fee' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>

<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>用户名：</div>
		<input type="text"  id="username" name="WxUser[username]" style="width:150px;height:33px;" value="<?php echo $model->username;?>" class="form-control "   >
	</div>
	
		<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>登陆名：</div>
		<input type="text"  id="loginname" name="WxUser[loginname]" style="width:150px;height:33px;" value="<?php echo $model->loginname;?>" class="form-control "   >
	</div>
	
		<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>电话：</div>
		<input type="text"  id="phone" name="WxUser[phone]" style="width:150px;height:33px;" value="<?php echo $model->phone;?>" class="form-control "   >
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">qq：</div>
		<input type="text"  id="qq" name="WxUser[qq]" style="width:150px;height:33px;" value="<?php echo $model->qq;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">传真：</div>
		<input type="text"  id="fax" name="WxUser[fax]" style="width:150px;height:33px;" value="<?php echo $model->fax;?>" class="form-control "   >
	</div>	
</div>
<?php if($model->id){?>
<!-- 进入本页最后更新时间 -->
	<input type="hidden" name='lupt' value="<?php echo time();#$this->getUpdateTime($model->tableName(), $model->id)?>"/>
<?php }?>
<div class="btn_list create_table" style="width:99%">
	<button type="submit" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
	<button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
</div>


<?php
$this->endWidget ();
?>	
<script>
	$(function(){	
		<?php if($msg){?>
		confirmDialog('<?php echo $msg?>');
			$("#name").focus().select();
		<?php }?>
		$(".cancel").click(function(){
			location.href="<?php echo Yii::app()->createUrl('wxUser/index')?>";
		});

		$(".save").click(function(){
			if($.trim( $("#loginname").val() ) == "" || $.trim( $("#loginname").val() ) == null){
				confirmDialog("登陆名不能为空！");
				$("#name").focus();
				return false;
			}
			if($.trim( $("#username").val() ) == "" || $.trim( $("#username").val() ) == null){
				confirmDialog("用户名不能为空！");
				$("#name").focus();
				return false;
			}
			if($.trim( $("#phone").val() ) == "" || $.trim( $("#phone").val() ) == null){
				confirmDialog("电话不能为空！");
				$("#name").focus();
				return false;
			}		
			$("form").submit();
		});
	});
</script>