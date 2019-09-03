<?php
$nu = ceil((count($quotedinfo['brand'])+1)/2);
$weekarray=array("日","一","二","三","四","五","六");
?>
<style>
	.bga {
		position: fixed;
		width: 100%;
		height: 100%;
		left: 0;
		top: 0;
		z-index: 998;
		background: rgba(0, 0, 0, .4);
	}
	.bga-cont2 {
		position: fixed;
		width: 295px;
		height: 150px;
		left: 50%;
		margin-left: -147.5px;
		top: 50%;
		margin-top: -120px;
		overflow: hidden;
		background: #fff;
		z-index: 999;
	}
	.bga-cont2 p {
		width: 100%;
		height: 100px;
		overflow: hidden;
		font-size: 14px;
		color: #02a29a;
	}
	.bga-cont2 p span {
		display: block;
		line-height: 20px;
		overflow: hidden;
	}
	.bga-cont2 p span:first-child {
		padding-top: 65px;
		text-align: center;
	}

</style>
<body >
<section class="offer">
	<article class="latest-header offer-header">
		<ul>
			<!--lastest-hd--><li class="lastest-hc"><?php echo $quotedinfo['search']['brandname']?><img src="/weixin/skin/images/znew_1.png" class="lastest-himga"/><img src="/weixin/skin/images/pic_2.png" class="lastest-himgb"/></li>
			<li class="lastest-hz" style="display: none;">
				<dl class="lastest-hdla">
					<?php
					$show = (!$quotedinfo['search']['brand'])?"lastest-hsok":"";
					$show1 = ($quotedinfo['search']['brand']==$quotedinfo['brand'][0]['brand_std'])?"lastest-hsok":"";
					echo '<dt><span data = "0" class="'.$show.'"><img src="/weixin/skin/images/pic_3.png" />全部</span><span data="'.$quotedinfo['brand'][0]['brand_std'].'" class="'.$show1.'"><img src="/weixin/skin/images/pic_3.png" />'.$quotedinfo['brand'][0]['name'].'</span></dt>';
					for($i=1;$i<$nu;$i++) {
						if ($i == 1) {
							$show = ($quotedinfo['search']['brand']==$quotedinfo['brand'][$i]['brand_std'])?"lastest-hsok":"";
							$show1 = ($quotedinfo['search']['brand']==$quotedinfo['brand'][$i+1]['brand_std'])?"lastest-hsok":"";
							echo '<dt><span data="'.$quotedinfo['brand'][$i]['brand_std'].'" class="'.$show.'"><img src="/weixin/skin/images/pic_3.png" />'.$quotedinfo['brand'][$i]['name'].'</span><span data="'.$quotedinfo['brand'][$i+1]['brand_std'].'" class="'.$show1.'"><img src="/weixin/skin/images/pic_3.png" />'.$quotedinfo['brand'][$i+1]['name'].'</span></dt>';
						}else{
							$show = ($quotedinfo['search']['brand']==$quotedinfo['brand'][$i*2-1]['brand_std'])?"lastest-hsok":"";
							$show1 = ($quotedinfo['search']['brand']==$quotedinfo['brand'][$i*2]['brand_std'])?"lastest-hsok":"";
							echo '<dt><span data="'.$quotedinfo['brand'][$i*2-1]['brand_std'].'" class="'.$show.'"><img src="/weixin/skin/images/pic_3.png" />'.$quotedinfo['brand'][$i*2-1]['name'].'</span>';if ($quotedinfo['brand'][$i*2]['brand_std']) echo '<span data="'.$quotedinfo['brand'][$i*2]['brand_std'].'" class="'.$show1.'"><img src="/weixin/skin/images/pic_3.png" />'.$quotedinfo['brand'][$i*2]['name'].'</span></dt>';
						}
					}
					?>
				</dl>
				<p class="lastest-hdlbp"><img src="/weixin/skin/images/pic_4.png"/></p>
			</li>
			<li class="lastest-hc" style="background:none;padding-right: .5%;"><?php echo $quotedinfo['search']['productname']?><img src="/weixin/skin/images/znew_1.png" class="lastest-himga"/><img src="/weixin/skin/images/pic_2.png" class="lastest-himgb"/></li>
			<li class="lastest-hz" style="display: none;">
				<dl class="lastest-hzlc">
					<dt data="0" class="<?php if(!$quotedinfo['search']['product']) echo 'lastest-hlccolor'?>"><img src="/weixin/skin/images/pic_3.png"/>全部</dt>
					<?php foreach($quotedinfo['product'] as $va){?>
					<dt data="<?php echo $va['product_std']?>" class="<?php if($quotedinfo['search']['product']==$va['product_std']) echo 'lastest-hlccolor'?>"><img src="/weixin/skin/images/pic_3.png"/><?php echo $va['name']?></dt>
					<?php }?>
				</dl>
				<p class="lastest-hdlbp"><img src="/weixin/skin/images/pic_4.png"/></p>
			</li>
			<li class="lastest-hc" ><?php echo $quotedinfo['search']['productall']?><img src="/weixin/skin/images/znew_1.png" class="lastest-himga"/><img src="/weixin/skin/images/pic_2.png" class="lastest-himgb"/></li>
			<li class="lastest-hz" style="display: none;">
				<dl class="lastest-hdlb">
					<dt>
						<span style="display: none" class="lastest-hscolor">全部</span>
						<span class="">材质<font style="letter-spacing: -1px;"><?php echo $quotedinfo['search']['texturename']?></font></span>
						<span>规格<font><?php echo $quotedinfo['search']['rankname']?></font></span>
						<span>长度<font><?php echo $quotedinfo['search']['lengthname']?></font></span>
					</dt>
					<dd style="display: none;">
						<p><span data="0" class="<?php if(!$quotedinfo['search']['texture']) echo 'lastest-hdlbcolor'?>"><img src="/weixin/skin/images/pic_3.png"/>全部</span></p>
						<?php foreach($quotedinfo['texture'] as $va){?>
							<p><span class="<?php if($quotedinfo['search']['texture']==$va['texture_std']) echo 'lastest-hdlbcolor'?>" data="<?php echo $va['texture_std']?>"><img src="/weixin/skin/images/pic_3.png"/><?php echo $va['name']?></span></p>
						<?php }?>
					</dd>
					<dd style="display: none;">
						<p><span data="0" class="<?php if(!$quotedinfo['search']['rank']) echo 'lastest-hdlbcolor'?>"><img src="/weixin/skin/images/pic_3.png"/>全部</span></p>
						<?php foreach($quotedinfo['rank'] as $va){?>
							<p><span class="<?php if($quotedinfo['search']['rank']==$va['rank_std']) echo 'lastest-hdlbcolor'?>" data="<?php echo $va['rank_std']?>"><img src="/weixin/skin/images/pic_3.png"/><?php echo $va['name']?></span></p>
						<?php }?>
					</dd>
					<dd style="display: none;">
						<p><span data="-1" class="<?php if($quotedinfo['search']['length'] == -1) echo 'lastest-hdlbcolor'?>"><img src="/weixin/skin/images/pic_3.png"/>全部</span></p>
						<?php foreach($quotedinfo['length'] as $va){?>
							<p><span class="<?php if($quotedinfo['search']['length']==$va) echo 'lastest-hdlbcolor'?>" data="<?php echo $va?>"><img src="/weixin/skin/images/pic_3.png"/><?php echo $va?$va:"其他"?></span></p>
						<?php }?>
					</dd>
				</dl>
				<p class="lastest-hdlbp"><img src="/weixin/skin/images/pic_4.png"/></p>
			</li>
		</ul>
	</article>
	<article class="offer-time">
		<dl>
			<dt>当前报价时间：<font><?php echo date("Y.m.d H:i",time())?></font>（星期<?php echo $weekarray[date("w")]?>）</dt>
			<dd><a href="javascript:window.location.href='/wechat.php/quoted';"><img src="/weixin/skin/images/znew_2.png"/></a></dd>
		</dl>
	</article>
	<?php
	if($quotedinfo['info']){
		foreach($quotedinfo['info'] as $va){
	?>
	<article class="latest-title"><p><span><?php echo $va[0]->prefecture_name?></span></p></article>
	<?php
	foreach($va as $v){
	?>
	<article class="latest-table offer-content">
		<ul class="latest-taul">
			<li style="color: #1d2637;"><?php echo $quotedinfo['proinfo']['brand'][$v->id]?></li>
			<li><?php echo $quotedinfo['proinfo']['product'][$v->id]?></li>
			<li><samp><?php echo $quotedinfo['proinfo']['rank'][$v->id]?><?php echo $v->length?"*".$v->length:""?></samp></li>
			<li <?php if(substr($quotedinfo['proinfo']['texture'][$v->id],-1) == "E"){echo 'style="color: #c84c12;"';}?>><font><?php echo $quotedinfo['proinfo']['texture'][$v->id]?></font></li>
<!--			<li><samp>--><?php //echo round($quotedinfo['weightinfo'][$v->id],3)?><!--吨</samp></li>-->
			<li><?php //echo $v->areaname?></li>
		</ul>
		<ul class="offer-tabul">
			<li><font>¥</font><?php echo $quotedinfo['priceinfo'][$v->id][$v->area]?></li>
			<li <?php echo $quotedinfo['changeinfo'][$v->id][$v->area]['show'];if(!$quotedinfo['is_spread'] || !$quotedinfo['changeinfo'][$v->id][$v->area]['up']) echo 'style="display:none"';?>><?php echo $quotedinfo['changeinfo'][$v->id][$v->area]['up']?><img src="<?php echo $quotedinfo['changeinfo'][$v->id][$v->area]['pic']?>"/></li>
			<li><?php echo $v->areaname?></li>
			<!--<li><?php //echo $v->brand?></li>-->
<!--			<li><a href="#"><img src="/weixin/skin/images/znew_4.png"/></a></li>-->
<!--			<li><img src="/weixin/skin/images/line_2.jpg"/></li>-->
			<li><a href="/wechat.php/quoted/detail?quoted_id=<?php echo $v->id?>&type=1&area_id=<?php echo $v->area?>"><img src="/weixin/skin/images/znew_3.png"/></a></li>
		</ul>
	</article>
	<?php }}}?>

</section>
<section class="tel-fiexd" style=""><a href="tel:<?php echo $quotedinfo['userphone']?>"><img src="/weixin/skin/images/znew_5.png"/></a></section>
<section class="cost-bj2" style="display: none;"></section>
<div class="bga" style="display: none"></div>
<div class="bga-cont2" style="display: none">
	<p>
		<span>正在提交信息</span>
	</p>
	<dl>
		<dd>确定</dd>
	</dl>
</div>
<input type="hidden" class="brand" value="<?php echo $quotedinfo['search']['brand']?>"/>
<input type="hidden" class="product" value="<?php echo $quotedinfo['search']['product']?>"/>
<input type="hidden" class="texture" value="<?php echo $quotedinfo['search']['texture']?>"/>
<input type="hidden" class="rank" value="<?php echo $quotedinfo['search']['rank']?>"/>
<input type="hidden" class="length" value="<?php echo $quotedinfo['search']['length']?>"/>
<input type="hidden" class="prefecture" value="<?php echo $quotedinfo['search']['prefecture']?>"/>
<script>
	$(function(){
		var show = <?php echo $quotedinfo['search']['show']?>;
		$('.lastest-hdlb dt').children('span').eq(show).trigger("click");

		$('.offer-content').click(function(){
			var url = $(this).children('ul').eq(1).children('li').eq(3).children('a').attr('href');
			window.location.href = url;
		});
	});
</script>
