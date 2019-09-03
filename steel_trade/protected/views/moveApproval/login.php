<style>
input{width:78%;height:44px;margin:20px 10% 0;font-size:16px;padding-left:2%;}
.register_bottom_login{width:80%;height:44px;margin:20px 10% 0;line-height:44px;font-size:16px;text-align:center;background: rgb(40, 160, 222);}
</style>
<?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login_form',
            'enableClientValidation' => true,
            'clientOptions' => array(
            'validateOnSubmit' => true,
            ),
        ));
?>
<input placeholder="请输入用户名" class="input_name" id="username" name="LoginForm[username]" type="text">
<input class="loginpassword" placeholder="请输入密码" id="password" name="LoginForm[password]" type="password">
<div class="register_bottom_login" id="submit">登　录</div>
<?php $this->endWidget(); ?>
<script>
<?php if($msg){?>
alert("<?php echo $msg;?>");
<?php }?>
$("#submit").click(function(){
	var name = $("#username").val();
	var password = $("#password").val();
	if(name == ""){alert("请输入用户名");return false;}
	if(password == ""){alert("请输入密码");return false;}
	$("form").submit();
});

</script>