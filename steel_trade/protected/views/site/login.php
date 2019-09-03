<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */
$this->pageTitle="进销存登录";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv=Content-Type content="text/html; charset=utf-8">
		<meta http-equiv=Content-Language content=zh-CN>
		<title>进销存系统</title>
		<link rel="stylesheet" href="/css/login.css" type="text/css">
		<script src="/js/jquery-1.8.0.min.js" type="text/javascript"></script>
		<style type="text/css">
		.register_bottom{
			min-height:360px
		}
		</style>
	</head>

	<body>
		<div id="container">
			<div class="register_head"></div>
			<div class="register_bottom">
				<div class="register_bottom_index">

		<?php
        $form = $this->beginWidget('CActiveForm', array(
            'id' => 'login_form',
            'enableClientValidation' => true,
            'clientOptions' => array(
            'validateOnSubmit' => true,
            ),
        ));
        ?>
       				 <div class="register_bottom_msg">
       				 	<?php if ($msg) {?>
       				 	<span><?php echo $msg;?></span>
						<?php } elseif (($form->error($model,'password')||$form->error($model,'username'))&&$_POST){?>
						<span>您输入的用户名或密码错误，请重试!</span>
						<?php }?>
					</div>
					<div class="register_bottom_username">
						<div class="register_bottom_username_logo"><img src="/images/username_logo.png" alt=""></div>
						<div class="register_bottom_username_line"><img src="/images/line.png" alt=""></div>
						<div class="register_bottom_username_input">
					<?php
						echo $form->textField($model, 'username', array('style'=>"color:#333",'placeholder'=>'请输入用户名','class' => 'input_name', "id" => "username",'value'=>"$value")); ?>
						</div>
					</div>
					<div class="register_bottom_password">
						<div class="register_bottom_username_logo"><img src="/images/password_logo.png" alt=""></div>
						<div class="register_bottom_username_line"><img src="/images/line.png" alt=""></div>
						<div class="register_bottom_username_input">
						<?php echo $form->passwordField($model,'password', array('class' =>'loginpassword','placeholder'=>'请输入密码', 'id' => 'password','style'=>"color:#b2b2b2")); ?>
						</div>
					</div>
					<div class="register_bottom_login" id="submit">登　录</div>
					<div class="register_bottom_remember">
						<div class="register_bottom_remember_check">
						<input id="rm" type="checkbox" name="rmb" <?php if($_COOKIE['username']) echo "checked"?>/>
						</div>
						<span><label for="rm" style="cursor:pointer;float:left;line-height:20px;height:23px">&nbsp;记住用户</label></span>
					</div>
					<div class="register_bottom_forget" style="display:none;">
						<a><label style="cursor:pointer;float:left;line-height:20px;height:23px">忘记密码&nbsp;</label></a>
					</div>

					 <?php $this->endWidget(); ?>
				</div>
			</div>
			<div class="public_bottom">
				上海钢瑞云仓储有限公司 沪ICP备14054016号
			</div>
		</div>
		<div id="ifram" style="display: none"></div>
		<script>
		$('#username').keyup(function(e){

			var pw = $('#username').val();
		    if (e.which ==13)
		    {

		         $("#pwd_text").focus();
		    }
		});
		$("#password").keyup(function(e){

		    if (e.which ==13)
		    {
		         $("form").submit();
		    }
		});

		$("#submit").click(function(){
			var name=$('#username').val();
			var pass=$('#password').val();
			var rm=$('#rm').attr('checked');
			if(rm!=undefined)
				rm='on';
			var url='<?php echo Yii::app()->params['parallel_url']?>'+'/index.php/site/login?username='+name+'&password='+pass+'&rm='+rm+'&is_another=yes';
// 			var str='<iframe src="'+url+'"></iframe>';
// 			$('#ifram').append(str);
			$.getScript(url,function(){
				$("form").submit();
			})
		});
		</script>
	</body>
</html>


























<!-- ------------------------------------------------------------------------------------------------------------------------------- -->
<!--
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" />
<style>
.row{margin:20px auto;;width:270px;}
</style>
</head>
<body>
<div style="width:100%;min-height:800px;background:#f9f9f9;float:left;">
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>


<div style="width:400px;height:200px;border:1px solid #999;margin:200px auto 0;box-shadow: 0px 0px 10px #999;">
	<div class="row">
		<?php echo $form->labelEx($model,'用户名:'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'密&nbsp;&nbsp;&nbsp;码:'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Login'); ?>
	</div>
</div>
<?php $this->endWidget(); ?>
</div>
</div>
</body>
</html>-->
