<body >
<style>
	.fill-cont ul .fillc-ma span{
		font-size: 15px;
		width: 106px;
		padding: 8px;
		margin-top: 5px;
	}
	.fill-cont ul .fillc-ma span.getcode1{
		background: #ddd;
	}
</style>
<section class="edit-phone">
	<article class="edit-number"><input class="phone" value="<?php echo $userinfo['phone']?>"/><img src="/weixin/skin/images/dele.png"/></article>
	<section class="fill-cont">
		<ul>
			<li class="fillc-li fillc-ma"><p><label>短信验证码：</label><input class="checkcode"><span class="getcode">获取短信验证码</span></p></li>
		</ul>
	</section>
	<article class="edit-save"><a href="#">保 存</a></article>
</section>

<script>
	$(function(){
		var set = 60;
		$(".getcode").click(function(){
			getcode();
		});
		function getcode(){
			var phone = $(".phone").val();
			if(!phone){
				alert("请输入手机号码");
				return;
			}
			$.post("/wechat.php/site/getCode",{"phone":phone},function(data){
				if(data == 1){
					$(".getcode").html(set+"S后可重发");
					$(".getcode").addClass('getcode1');
					$(".getcode").unbind("click");
					var a = setInterval(function(){
						/*倒计时为1时*/
						if(set==1){
							clearInterval(a);
							$(".getcode").html("获取短信验证码");
							$(".getcode").removeClass('getcode1');
							$(".getcode").bind("click",function(){
								getcode();
							});
							set=60;
						}
						else{
							set-=1;
							$(".getcode").html(set+"S后可重发");
						}
					},1000);
					alert("验证码发送成功");
				}else{
					alert("验证码发送失败");
				}
			});
		}

		$(".edit-save a").click(function(){
			var phone = $(".phone").val();
			var checkcode = $(".checkcode").val();
			if(!phone){
				alert("请输入手机号码");
				return;
			}
			if(!checkcode){
				alert("请输入验证码");
				return;
			}
			$.post("/wechat.php/user/edit",{"phone":phone,"checkcode":checkcode,"type":1},function(data){
				data = eval('(' + data + ')');
				if(data["code"] == 0){
					alert("修改成功");
					window.location.href = "/wechat.php/user";
				}else{
					alert(data["info"]);
				}
			});
		});
	});
</script>
