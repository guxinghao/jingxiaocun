<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/zui/css/zui.min.css" rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery_ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-timepicker-addon.css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/zui/lib/datetimepicker/datetimepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.combobox.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/index.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<!-- ZUI Javascript组件 -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<?php $all_menus = MenuItem::getValideMenus();?>
</head>

<body>
	<div id="ibody">
		<div class="index_top">
			<div class="logo">
				<img src="<?php echo imgUrl('log.png');?>" />
			</div>
			<div class="log_tit">进销存系统</div>
			<?php if ($this->pageTitle && $this->pageTitle!="进销存系统"){?><div class="pageTitle"><span>—</span><?php echo $this->pageTitle?></div><?php } ?>
			<div class="user_quit">
				<a href="<?php echo Yii::app()->createUrl('site/logout');?>">
					<div class="user_quit_baby">
						<img src="<?php echo imgUrl('quit.png');?>">退出
					</div>
				</a>
			</div>
			<div class="user">
				<img src="<?php echo imgUrl('head.png');?>">您好，<?php echo Yii::app()->user->nickname;?>
			</div>
		</div>
		<div class="table_body">
			<div class="bar">
				<ul class="first_bar">
				<?php
				if (! is_array ( $all_menus )) {
					$all_menus = array ();
				}
				$k = 0;
				foreach ( $all_menus as $val ) {
					$name = $val->name;
					switch ($name) {
						case "采购" :
							$src = imgUrl ( 'buy.png' );
							break;
						case "销售" :
							$src = imgUrl ( 'shop.png' );
							break;
						case "库存" :
							$src = imgUrl ( 'stock.png' );
							break;
						case "仓库" :
							$src = imgUrl ( 'warehouse.png' );
							break;
						case "财务" :
							$src = imgUrl ( 'finance.png' );
							break;
						case "报表" :
							$src = imgUrl ( 'form.png' );
							break;
						case "系统" :
							$src = imgUrl ( 'system.png' );
							break;
						case "价格" :
							$src = imgUrl ( 'priceing.png' );
							break;
						case "设置" :
							$src = imgUrl ( 'seting.png' );
							break;
					}
					?>
					<li num=<?php echo $k;?>><img src="<?php echo $src;?>"><br /><?php echo $name;?>
						<div class="blue_line"></div></li>
				<?php
					$k ++;
				}
				?>
				</ul>
			<?php
			for($i = 0; $i < count ( $all_menus ); $i ++) {
				$menus = $all_menus [$i]->sub_menus;
				if (! is_array ( $menus )) {
					$menus = array ();
				}
				if($all_menus[$i]->name == "采购"){
				?>
				<div class="second_bar second_bar_<?php echo $i+1;?>" style="z-index: 999;width:298px;">
					<div class="second_bar_li" style="float:left;">
				<?php
				$j = 0;
				$num = count ( $menus );
				foreach ( $menus as $val ) {
					if ($j >3) continue;
					?>
				<a href="<?php echo $val->url;?>">
					<div class="second_bar_list">
						<div class="second_bar_list_one"
						<?php if($j == 0){echo 'style="border-top: none"';}elseif($j ==3){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?></div>
					</div>
				</a>
				<?php
					$j ++;
				}
				?>
				</div>
				<div class="second_bar_li" style="float:left;">
				<?php
				$j = 0;
				//$num = count ( $menus );
				foreach ( $menus as $val ) {
					$j ++;
					if ($j < 5 || $j > 8) continue;
					?>
				<a href="<?php echo $val->url;?>">
					<div class="second_bar_list">
						<div class="second_bar_list_one"
						<?php if($j == 4){echo 'style="border-top: none"';}elseif($j == 7){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?></div>
					</div>
				</a>
				<?php
				}
				?>
				</div>
				<div class="second_bar_li" style="float:left;">
				<?php
				$j = 0;
				$num = count ( $menus );
				foreach ( $menus as $val ) {
					$j ++;
					if ($j < 9) continue;
					?>
				<a href="<?php echo $val->url;?>">
					<div class="second_bar_list">
						<div class="second_bar_list_one"
						<?php if($j == 8){echo 'style="border-top: none"';}elseif($j == 11){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?></div>
					</div>
				</a>
				<?php
				}
				?>
				</div>
				</div>
				<?php
				}else if($all_menus[$i]->name == "报表"){
					$num = count ( $menus );
					if($num > 8){
						$height = $i*68-6*42-20;
					}else if($num == 1){
						$height = $i*68;
					}else{
						$height = $i*68-($num-2)*42-20;
					}
					if($height < 0) $height = 0;
				?>
				<div class="second_bar second_bar_<?php echo $i+1;?>" style="z-index: 999;width:200px;background-color: #1f283a;top:<?php echo $height;?>px">
					<div class="second_bar_li">
				<?php
					$j = 0;
					$num = count($menus);
					foreach ($menus as $val) {
						if ($j >7) continue;
						?>
						<a href="<?php echo $val->url;?>">
							<div class="second_bar_list">
								<div class="second_bar_list_one"
								<?php if($j == 0){echo 'style="border-top: none"';}elseif($j==8){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?>
								</div>
							</div>
						</a>
				<?php
					$j++;
					}
					?>
					</div>
					<div class="second_bar_li second" style="float: right;">
				<?php
					$k = 0;
					foreach ($menus as $val) {
						$k++;
						if ($k < 9) continue;
						?>
						<a href="<?php echo $val->url;?>">
							<div class="second_bar_list">
								<div class="second_bar_list_one"
								<?php if($k==9){echo 'style="border-top: none"';}elseif($k == $num ){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?>
								</div>
							</div>
						</a>
				<?php
					}
					?>
					</div>
				</div>
				<?php
				}else if($all_menus[$i]->name == "设置" || $all_menus[$i]->name == "价格" || $all_menus[$i]->name == "系统"){
					$num = count ( $menus );
					if($num == 1){
						$height = $i*68;
					}else{
						$height = $i*68-($num-2)*42-20;
					}
					if($height < 0) $height = 0;
				?>
				<div class="second_bar second_bar_<?php echo $i+1;?>" style="z-index: 999;top:<?php echo $height;?>px">
					<div class="second_bar_li">
				<?php
				$j = 0;
				foreach ( $menus as $val ) {
					?>
				<a href="<?php echo $val->url;?>">
					<div class="second_bar_list">
						<div class="second_bar_list_one"
						<?php if($j == 0){echo 'style="border-top: none"';}elseif($j == $num - 1){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?></div>
					</div>
				</a>
				<?php
					$j ++;
				}
				?>
					</div>
				</div>
				<?php
				}else{
					?>
				<div class="second_bar second_bar_<?php echo $i+1;?>" style="z-index: 999;">
					<div class="second_bar_li">
				<?php
				$j = 0;
				$num = count ( $menus );
				foreach ( $menus as $val ) {
					?>
				<a href="<?php echo $val->url;?>">
					<div class="second_bar_list">
						<div class="second_bar_list_one"
						<?php if($j == 0){echo 'style="border-top: none"';}elseif($j == $num - 1){echo 'style="border-bottom: none"';}?>><?php echo $val->name;?></div>
					</div>
				</a>
				<?php
					$j ++;
				}
				?>
					</div>
				</div>
			<?php }
			}
			?>

		</div>
			<div class="table_content">
				<script>
					var width = $(window).width();
					$(".table_content").width(width-61);
				</script>
			<?php echo $content; ?>
			</div>
		</div>
	</div>
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/foot.js"></script>
</body>
</html>
