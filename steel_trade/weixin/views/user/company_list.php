<body >
<section class="company-edit">
	<?php if($company){foreach($company as $va){?>
	<article class="company-ea">
		<p class="company-ep company-eab"><?php echo $va->company?></p>
		<ul class="company-eu company-eab">
			<li data="<?php echo $va->id?>" class="<?php if($va->is_default==1){ $company_de = $va->id;echo 'company-eul';}?> company-delete"><img src="/weixin/skin/images/zpic_5.png" class="company-imga"/><img src="/weixin/skin/images/zpic_2.png" class="company-imgb"/>删除</li>
			<li style="display:none" class="company-eul"><i></i></li>
			<li data="<?php echo $va->id?>" class="company-default <?php if($va->is_default==1){echo 'company-eul';}?>"><img src="/weixin/skin/images/zpic_6.png" class="company-imge"/><img src="/weixin/skin/images/zpic_3.png" class="company-imgf"/>设为默认</li>
			<!--<li data="<?php echo $va->id?>" class="company-eul company-edit1"><img src="/weixin/skin/images/zpic_4.png" class="company-imgc"/><img src="/weixin/skin/images/zpic_1.png" class="company-imgd"/>编辑</li>-->
			<!--<li data="<?php echo $va->id?>" class="company-default company-eul"><img style="display: <?php if($va->is_default==1){echo 'none';}else{echo 'inline';}?>" src="/weixin/skin/images/zpic_6.png" class="company-imge"/><img style="display: <?php if($va->is_default==1){echo 'inline';}else{echo 'none';}?>" src="/weixin/skin/images/zpic_3.png" class="company-imgf"/>设为默认</li>-->
		</ul>
	</article>
<?php }}?>
<input class="company_de" type="hidden" value="<?php echo $company_de?>"/>
	<!--<article class="company-ea">
		<p class="company-ep company-eab">上海大发公司</p>
		<ul class="company-eu company-eab">
			<li class=""><img src="images/zpic_5.png" class="company-imga"/><img src="images/zpic_2.png" class="company-imgb"/>删除</li>
			<li><i></i></li>
			<li class=""><img src="images/zpic_4.png" class="company-imgc"/><img src="images/zpic_1.png" class="company-imgd"/>编辑</li>
			<li class="company-default"><img src="images/zpic_6.png" class="company-imge"/><img src="images/zpic_3.png" class="company-imgf"/>设为默认</li>
		</ul>
	</article>-->
</section>
<!--<article class="edit-save company-add"><a href="#"><img src="/weixin/skin/images/zpic_7.png"/>新 增</a></article>-->

<script>
	$(function() {
		$(".company-eul img").click(function () {

		});
		$(".company-edit1").click(function () {
			var company_id = $(this).attr("data");
			window.location.href = "/wechat.php/user/edit?type=2&company_id=" + company_id;
		});
		$(".company-delete").click(function () {
			var company_id = $(this).attr("data");
			var _this = $(this);
			$.post("/wechat.php/user/companyDelete", {"company_id": company_id}, function (data) {
				data = eval('(' + data + ')');
				if (data["code"] == 0) {
					alert("删除成功");
					_this.parent().parent().remove();
				} else {
					alert(data["info"]);
				}
			});
		});
		$(".company-add").click(function () {
			window.location.href = "/wechat.php/user/edit?type=3";
		});
	})
</script>
