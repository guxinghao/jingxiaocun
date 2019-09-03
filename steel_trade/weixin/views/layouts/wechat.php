<?php
  $name = Yii::app()->controller->id;
  $actionname = $this->getAction()->getId();
if($name == "site"){
	$title = "填写信息";
}
if($name == "user"){
	if($actionname == "index"){
		$title = "个人信息";
	}else{
		$title = "信息编辑";
	}
}
if($name == "quoted"){
	if($actionname == "index"){
		$title = "报价列表";
	}
	if($actionname == "detail"){
		$title = "报价详情";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
	<meta content="yes" name="apple-mobile-web-app-capable">
	<meta content="black" name="apple-mobile-web-app-status-bar-style">
	<meta content="telephone=no" name="format-detection">
	<meta content="email=no" name="format-detection">
	<title><?php echo $title?></title>
	<link rel="stylesheet" href="/weixin/skin/css/main.css" />
	<link rel="stylesheet" href="/weixin/skin/css/demo.css">
	<script type="text/javascript" src="/weixin/skin/js/jquery.min.js" ></script>
	<script type="text/javascript" src="/weixin/skin/js/comment.js" ></script>
</head>
<?php echo $content; ?>
</body>
</html>
