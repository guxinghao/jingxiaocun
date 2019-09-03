<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="language" content="en" />
<meta name="keywords" content="进销存" />
<link rel="icon" type="image/x-icon" href="/images/titleicon.ico"/>
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
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/export.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.cookie.js"></script>
<!-- ZUI Javascript组件 -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<style>
	.message_line{
		line-height:30px;
		font-size:14px;
		background:#fff;
		min-width:98px;
		clear:both;
	}
	.message_line_name{float:left;text-align:center;color:#000;width:45%;}
	.message_line_num{float:left;text-align:left;min-width:30px;color:#fff;border-radius:15px;margin:6px 0 6px 3px;;line-height:18px;padding:0 4px;text-align:center;}
	.second_bar_6{top:-41px!important;}
</style>
<?php
	header("Cache-control: private");
	$all_menus = MenuItem::getValideMenus();
?>
</head>

<body>
	<div id="ibody">
		<div class="index_top">
			<div class="logo">
				<a href="<?php echo Yii::app()->createUrl('site/index'); ?>">
					<img src="<?php echo imgUrl('log.png');?>" />
				</a>
			</div>
			<div class="log_tit">进销存系统</div>
			<?php if ($this->pageTitle && $this->pageTitle!="进销存系统"){?><div class="pageTitle"><span>—</span><?php echo $this->pageTitle?></div><?php } ?>
			<div style="float:left;color:red;line-height:42px;font-size:18px;margin-left:20px;cursor:pointer" class="banjuan">切至板卷系统</div>
			<div class="user_quit">
				<a href="<?php echo Yii::app()->createUrl('site/logout');?>">
					<div class="user_quit_baby">
						<img src="<?php echo imgUrl('quit.png');?>">退出
					</div>
				</a>
			</div>
			<div class="message">
			     <img src="/images/msgRing.png"/>
			     <span>消息</span>
			     <?php $count=MessageContent::model()->getCount('all'); ?>
			     <div class="msg_count" style="<?php if(!$count) echo 'display:none;';?>">
			     	<?php if($count){echo $count;} ?>
			     </div>
			     <div class="message_b" style="position:absolute;top:42px;left:0px;line-height:40px;min-width:98px;display:none;z-index:1000;background:#fff;border: 1px solid #ccc;box-shadow: #ccc 3px 3px 3px;">
			     		<div class="message_line" message_type="ware">
			     			<div class="message_line_name" >仓库</div><div class="message_line_num lay_ware" style="background: #dc4949;">
			     			<?php $count=MessageContent::model()->getCount('ware'); echo $count;?>
			     			</div>
			     		</div>
			     		<div class="message_line" message_type="money">
			     			<div class="message_line_name">财务</div><div class="message_line_num lay_money" style="background: #3fa14d ;">
			     			<?php $count=MessageContent::model()->getCount('money');echo $count; ?>
			     			</div>
			     		</div>
			     		<div class="message_line" message_type="purchase">
			     			<div class="message_line_name">采购</div><div class="message_line_num lay_purchase" style="background: #e16643;">
			     			<?php $count=MessageContent::model()->getCount('purchase');echo $count ?>
			     			</div>
			     		</div>
			     		<div class="message_line" message_type="sale">
			     			<div class="message_line_name">销售</div><div class="message_line_num lay_sale" style="background: #46ab94;">
			     			<?php $count=MessageContent::model()->getCount('sale'); echo $count;?>
			     			</div>
			     		</div>
			     	</div>
			</div>
			<div class="user">
				您好，<?php echo Yii::app()->user->nickname;?>
				<div class="invit_code" style="position:absolute;top:42px;left:0px;line-height:40px;margin-left:-25px;padding:0 10px;background:#2d3953;min-width:140px;display:none;z-index:100;">我的邀请码:<?php echo Yii::app()->user->invit_code;?></div>
			</div>
			<?php if($this->setHome==1){?>
			<div class="set_home" id="set_home" url='<?php echo Yii::app()->request->getUrl();?>'>
				<img src="<?php echo imgUrl('set_home.png');?>">设为首页
			</div>
			<?php }?>
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
					$menus = $all_menus [$i]->sub_menus;//二级菜单
					$colum = count ( $menus );
					$more = 0;
					$isall = 0;
			?>
				<div class="second_bar second_bar_<?php echo $i+1;?>" style="z-index: 999;border: 1px solid #000;background: #1f283a;width:<?php echo $colum*100?>px;">
				<?php
						for($j = 0; $j < $colum; $j ++) {
				?>
						<div class="second_bar_li" style="float:left;border: none;">
				<?php
							if ($menus[$j]->name !="全部"){
								$isall = 1;
				?>

							<div title="<?php echo $menus[$j]->name;?>">
									<div class="second_bar_list_title"><img class="list_arrow" src="/images/arrow.png"><?php echo $menus[$j]->name;?></div>
							</div>
							<div class="bar_list_bd bar_list_bd_<?php echo $i+1;?>" <?php if($j == 0){echo 'style="border:none;"';}?>>
				<?php 		}
						$val = $menus[$j]->sub_menus;
						for($l = 0; $l < count($val); $l ++){
				?>
							<a<?php echo $val[$l]->name == '修改密码' ? ' target="_blank"' : '';?> href="<?php echo $val[$l]->url;?>">
								<div class="second_bar_list">
									<div class="second_bar_list_one"><?php echo $val[$l]->name;?></div>
								</div>
							</a>
				<?php }
					if($l > $more){$more = $l;}
					if ($menus[$j]->name !="全部"){
				?>
							</div>
					<?php } ?>
						</div>
				<?php } ?>
				</div>
				<script>
				<?php if($i >= 5){
					if($isall) {
						$more +=1;
						$height = $i*68-($more-2)*42-21;
					}else{
						$height = $i*68-($more-2)*42-18;
					}
				?>
				$(".second_bar_<?php echo $i+1;?>").css("top",<?php echo $height;?>+"px");
				<?php }?>
				<?php if($isall){
						if($i >= 5){
				?>
					$(".bar_list_bd_<?php echo $i+1;?>").css("min-height",<?php echo ($more-1)*42?>+"px");
				<?php }else{
				?>
					$(".bar_list_bd_<?php echo $i+1;?>").css("min-height",<?php echo $more*42?>+"px");
				<?php
						}
				 }else{?>
					$(".second_bar_<?php echo $i+1;?>").css("padding-bottom",0);
				<?php }?>
				</script>
				<?php } ?>
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
	<script type="text/javascript">
	 $('.user').mouseover(function(){
	 	$('.invit_code').show();
	 });
	 $('.user').mouseout(function(){
	 	$('.invit_code').hide();
	 });
	 var can_ask=true;
	 $('.message').mouseover(function(e){
		 e.stopPropagation();
		 $('.message_b').show();
		 if(can_ask)
		 {
			 $.post("/index.php/messageCenter/getCount/",{},function(data){
				   var json=eval('('+data+')');
			        $(".lay_ware").html(json.ware);
			        $(".lay_purchase").html(json.purchase);
			        $(".lay_money").html(json.money);
			        $(".lay_sale").html(json.sale);
			    });
			    can_ask=false;
			    setTimeout(function(){can_ask = true;},3000);
		 }
	})
	$('.message').mouseout(function(){
		 $('.message_b').hide();
	})
		$('.message_line').mouseover(function(){
			$(this).find('.message_line_name').css('color','#436ebb');
		})
		$('.message_line').mouseout(function(){
			$(this).find('.message_line_name').css('color','#000');
		})
		$('.message_line').click(function(e){
			e.preventDefault();
			e.stopPropagation();
			var type=$(this).attr('message_type');
			window.location.href="/index.php/messageCenter/index?big_type="+type;
		});
	 	var controller='<?php echo Yii::app()->getController()->id;?>';
	 	var url='<?php echo Yii::app()->request->url?>';
	 	var parallel_url='<?php echo Yii::app()->params['parallel_url'];?>';
		$('.banjuan').click(function(){
			window.location.href=parallel_url+url;
		});
	</script>
</body>
</html>

