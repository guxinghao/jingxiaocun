<body >
<style>
	.highcharts-legend{display: none}
	.highcharts-yaxis-title{display: none}
</style>
<section class="latest-offer">
	<article class="latest-header">
		<ul>
			<li data="1" class="<?php if($quotedinfo['type'] == 1) echo 'latest-hula'?>">周走势</li>
			<li data="2" class="<?php if($quotedinfo['type'] == 2) echo 'latest-hula'?>">月走势</li>
		</ul>
	</article>
	<article class="latest-table">
		<ul class="latest-taul">
			<li style="color: #999;"><?php echo $quotedinfo['brand']?></li>
			<li><?php echo $quotedinfo['product']?></li>
			<li><samp><?php echo $quotedinfo['length']?$quotedinfo['rank']."*".$quotedinfo['length']:$quotedinfo['rank']?></samp></li>
			<li <?php if(substr($quotedinfo['texture'],-1) == "E"){echo 'style="color: #c84c12;"';}?>><font><?php echo $quotedinfo['texture']?></font></li>
<!--			<li><samp>--><?php //echo round($quotedinfo['weightinfo'],3)?><!--吨</samp></li>-->
			<li><?php //echo $quotedinfo['brand']?></li>
		</ul>
		<h3>涨幅走势图（单位：元）</h3>
		<div id="container"></div>
	</article>
	<article class="latest-title"><p><span>走势记录</span></p></article>
	<article class="latest-content">
		<ul>
			<li><span class="latest-licolor">价格</span><span class="latest-licolor">涨幅</span><span class="latest-licolor">时间</span></li>
			<?php if($quotedinfo['priceinfo']){
				foreach($quotedinfo['priceinfo'] as $va){
					echo '<li><span>'.$va['price'].'</span><span class="'.$va['class'].'">'.$va['up'].'<img src="'.$va['pic'].'"/></span><span>'.$va['date'].'</span></li>';
				}
			}?>
		</ul>
	</article>
</section>
<script src="/weixin/skin/js/highcharts.js"></script>
<script>
	$(function(){
		$('#container').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: ''
			},
			subtitle: {
				text: ''
			},
			xAxis: {
				categories:[<?php echo $quotedinfo['xtitle']?>]
			},
			yAxis: {
				title: {
					text: ''
				},
				gridLineColor:'#d1eefc',
				gridLineDashStyle:'Dash',
				labels: {
					formatter: function () {
						//return this.value;
					}
				}
			},
			tooltip: {
				pointFormat: '<b>{point.y:,.0f}</b>'
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: false
				}
			},
			series: [{
				data: [<?php echo $quotedinfo['content']?>]
			}]
		});
		$(".latest-header li").click(function(){
			$(".latest-hula").removeClass("latest-hula");
			$(this).addClass("latest-hula");
			var id = <?php echo $quotedinfo['id']?>;
			var area_id = <?php echo $_GET['area_id']?$_GET['area_id']:0?>;
			window.location.href = "/wechat.php/quoted/detail?quoted_id="+id+"&type="+$(this).attr("data")+"&area_id="+area_id;
		});
	});
</script>
