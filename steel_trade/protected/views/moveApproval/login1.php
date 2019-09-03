<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<meta content="email=no" name="format-detection">
<title>标题</title>
</head>
	<body>
		<section class="n-header"><img src="/images/zl_1.jpg"/></section>
		<section class="n-main">
		<form method="post">
			<article class="n-m-a">
				<label><img src="/images/zl_2.png"/></label>
				<input placeholder="请输入用户名" id="username" name="LoginForm[username]"/>
			</article>
			<article class="n-m-a">
				<label><img src="/images/zl_3.png"/></label>
				<input placeholder="请输入密码" id="password" name="LoginForm[password]"  type="password"/>
			</article>
			<article class="n-m-b" id="submit">
				<a href="#" >登录</a>
			</article>
			</form>
		</section>
	</body>
	<script>
<?php if($msg){?>
alert("<?php echo $msg;?>");
<?php }?>
$("#submit").click(function(){
	var name = $("#username").val();
	var password = $("#password").val();
	if(name == ""){alert("请输入用户名");return false;}
	if(password == ""){alert("请输入密码");return false;}
	var url='<?php echo Yii::app()->params['parallel_url']?>'+'/index.php/moveApproval/login?username='+name+'&password='+password+'&is_another=yes';
	$.getScript(url,function(){
		$("form").submit();
	});
	
});

</script>
</html>
