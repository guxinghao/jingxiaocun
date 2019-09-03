<body >
<section class="edit-phone">
	<article class="edit-number"><input class="co_name" value="<?php echo $userinfo['fax']?>"/><img src="/weixin/skin/images/dele.png"/></article>
	<input class="company_id" type="hidden" value="<?php echo $userinfo['company_id']?>"/>
	<input class="company_type" type="hidden" value="2"/>
	<article class="edit-save"><a href="#">保 存</a></article>
</section>

<script>
	$(function(){
		$(".edit-save a").click(function(){
			var co_name = $(".co_name").val();
			if(!co_name){
				alert("请输入传真");
				return;
			}
//			var co_address = $(".co_address").val();
//			if(!co_address){
//				alert("请输入公司地址");
//				return;
//			}
			var company_id = $(".company_id").val();
			var type = $(".company_type").val();

			$.post("/wechat.php/user/editInfo",{"fax":co_name,"co_name":co_name,"co_address":1,"type":type},function(data){
				data = eval('(' + data + ')');
				if(data["code"] == 0){
					alert(data["info"]);
					window.location.href = "/wechat.php/user";
				}else{
					alert(data["info"]);
				}
			});
		});
	});
</script>
