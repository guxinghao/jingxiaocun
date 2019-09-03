<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl;?>/css/print.css" />
		<style type="text/css">
		.con_tit_daoru{float:left;width:85px;line-height:38px;text-align:center;font-size:14px;color:#333; cursor: pointer;}
		.con_tit_daoru img{width:24px;height:24px;display:block;float:left;margin-left:20px;margin-top:6px;}
		</style>
	</head>

	<body>
		<div class="print_body quoted">
			<div class="print_head">
				<h2 class="title">上海瑞亮物资有限公司</h2>
				<span class="sub_title">当日报价单：<?php echo $this->getDate($time);?></span>
			</div>

			<div class="print_main">
			<?php $i = 0; $last_prefecture = "未知专区"; 
			foreach ($items as $item) {
				if ($last_prefecture != $item->prefecture_name) {?>
				<ul class="print_row area_title">
					<li><?php echo $item->prefecture_name;?></li>
				</ul>
				<?php $last_prefecture = $item->prefecture_name; }?>
				<ul class="print_row area_item">
					<li><?php echo $item->product_name;?></li>
					<li><?php echo $item->rank_name.($item->length?"×".$item->length:'');?></li>
					<li><?php echo $item->texture_name;?></li>
					<li><?php echo DictGoods::getUnitWeightByStd($item->brand_std, $item->product_std, $item->texture_std, $item->rank_std, $item->length)."T";?></li>
					<li><?php echo number_format($item->rprice,0);?></li>
					<li><?php echo $item->brand_name;?></li>
					<li><?php echo $item->areaname;?></li>
				</ul>
			<?php $i++; }?>
			</div>

			<div class="print_foot">
				<h2 class="banner">
					<font style="color: red;">新三洲\东亚\贵航\亚新\锦兴\富鑫\华兴\三元\正大</font>--国标品质,我公司为其上海一级经销商
				</h2>
				<span class="prompt">因市场价格变动较大，敬请来电确认</span>
				<span class="address">公司总部：上海市杨浦区政益路28号405-406室（五角丰达）</span>
				<span class="contact_phone">联系电话：55392500 65887906(直线） 55389923（传真）</span>
				<span class="contacts">郑先生：18917065211/13022171222  QQ号:397552820</span>
			</div>
		</div>
	</body>
	<script type="text/javascript">
// 	$(document.body).css("-webkit-transform","scale(0.5)");
	</script>
</html>