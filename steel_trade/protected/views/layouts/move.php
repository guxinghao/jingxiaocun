<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<meta content="email=no" name="format-detection">
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/demo.css">
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/comment.js" ></script>
</head>
<body style="background: #f5f5f5;">
<?php if($this->getAction()->id!='login'){?>
<div class="" style="width:100%;line-height:36px;background:rgba(0,0,0,0.9);z-index:50;position:fixed;top:0;left:0;text-align:center;color:#fff;font-size:17px;">
<?php if($this->getAction()->id=='detail'){?>
<img onclick="history.back();" src="/images/goback.png" style="width:18px;position:fixed;top:10px;left:10px;"/>
<?php }elseif($this->getAction()->id=='index'){?>
<img alt="" class="changeSys" src="/images/log.png" style="width:17px;height:17px;float:left;position:fixed;top:10px;left:10px;background-color:#1eabf2">
<?php }?>
<?php echo $this->pageTitle;?><img  class="logout"  src="/images/logout.png" style="width:17px;position:fixed;top:10px;right:10px;"/>
</div>
<?php }?>
<?php echo $content; ?>
<section class="logout_back" style="display: none;"></section>
<section class="cost-widw logout_main" style="display: none;">
	<h3>温馨提示</h3>
	<p>您是否确认退出审批系统？</p>
	<ul>
		<li class="logout_cancel">取消</li>
		<li class="cs-ok" onclick="logout()">确定</li>
	</ul>
</section>
</body>
<script>
$('.logout').click(function(){
	$('.logout_back').fadeIn();
	$('.logout_main').fadeIn();
});
var parallel_url='<?php echo Yii::app()->params['parallel_url'];?>';
$('.changeSys').click(function(){	
	window.location.href=parallel_url+'/index.php/moveApproval';
});
function logout()
{
	window.location.href="/index.php/moveApproval/logout";
}
$('.logout_cancel').on('click',function(){
	$('.logout_back').fadeOut();
	$('.logout_main').fadeOut();
});
</script>
</html>