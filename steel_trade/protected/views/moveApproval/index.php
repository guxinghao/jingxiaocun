<section class="costc-c">
<header class="cost-top" >
	<article class="costa-mian">
		<ul>
			<li class="costa-li"><em>分类</em><img src="<?php echo imgUrl('pic_1.png');?>"/><img src="<?php echo imgUrl('pic_2.png');?>" /></li>
			<li class="costa-lib type">
				<span value="0" class="costa-sa"><img src="<?php echo imgUrl('pic_3.png');?>"/>全部</span>
				<span value="DQDK"><img src="<?php echo imgUrl('pic_3.png');?>" style="display: none;"/>短期借贷</span>
				<span value="FKDJ"><img src="<?php echo imgUrl('pic_3.png');?>" style="display: none;"/>付款</span>
				<span value="FYBZ"><img src="<?php echo imgUrl('pic_3.png');?>" style="display: none;"/>费用</span>
				<em><img src="<?php echo imgUrl('pic_4.png');?>"/></em>
			</li>
			<li class="costa-li"><em>待审核</em><img src="<?php echo imgUrl('pic_1.png');?>"/><img src="<?php echo imgUrl('pic_2.png');?>" /></li>
			<li class="costa-lib result">
				<span value="0"><img src="<?php echo imgUrl('pic_3.png');?>" style="display: none;"/>全部</span>
				<span value="1" class="costa-sa"><img src="<?php echo imgUrl('pic_3.png');?>"/>待审核</span>
				<span value="2"><img src="<?php echo imgUrl('pic_3.png');?>" style="display: none;"/>已通过</span>
				<span value="3"><img src="<?php echo imgUrl('pic_3.png');?>" style="display: none;"/>未通过</span>
				<em><img src="<?php echo imgUrl('pic_4.png');?>"/></em>
			</li>
		</ul>
	</article>
</header>
<section class="cost-bj"></section>
<div class="datalist">

</div>
<script type="text/javascript">
$(function(){
	var height = $(window).height() - 46;
	$(".datalist").css("min-height",height+"px");
	getDataList();
})
</script>