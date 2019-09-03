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
	.fillc-none{
		background: none;
	}
</style>
<section class="fill-cont">
	<ul>
		<li class="fillc-li"><p><label>姓名：</label><input class="name"/></p></li>
		<li class="fillc-li"><p><label>电话：</label><input class="phone"/></p></li>
		<li class="fillc-li fillc-ma"><p><label>短信验证码：</label><input class="checkcode"/><span class="getcode">获取短信验证码</span></p></li>
		<li class="fillc-ld"><p><label>邀请码：</label><input class="yq_code" placeholder="填写瑞亮提供的邀请码"/></p></li>
		<li class="fillc-li fillc-none"><p><label>qq：</label><input class="qq"/></p></li>
		<li class="fillc-li fillc-none"><p><label>传真：</label><input class="fax"/></p></li>
		<li class="fillc-li fillc-lm fillc-none"><p><label>公司名称：</label><input class="co_name"/></p></li>
		<li class="fillc-li fillc-lm fillc-lb fillc-none"><p><label>公司地址：</label><input class="co_address"/></p></li>
	</ul>
	<article class="edit-save fill-save"><a class="sava" href="#">保 存</a></article>
</section>
<div class="bga" style="display: none"></div>
<div class="bga-cont2" style="display: none">
	<p>
		<span>正在提交信息</span>
	</p>
</div>
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
		$('.fillc-li input').keyup(function () {
			var name = $(".name").val();
			var phone = $(".phone").val();
			var checkcode = $(".checkcode").val();
			var qq = $(".qq").val();
			var fax = $(".fax").val();
			var co_name = $(".co_name").val();
			var co_address = $(".co_address").val();
			if(name&&phone&&checkcode){
				$(".fill-save").removeClass("fill-save");
			}else{
				$(".edit-save").addClass("fill-save");
			}

		});
		$(".sava").click(function(){
			if($(this).parent().hasClass("fill-save")){
				return false;
			}
			var name = $(".name").val();
			if(!name){
				alert("请输入姓名");
				return;
			}
			var phone = $(".phone").val();
			if(!phone){
				alert("请输入手机号码");
				return;
			}
			var checkcode = $(".checkcode").val();
			if(!checkcode){
				alert("请输入验证码");
				return;
			}
			var qq = $(".qq").val();
//			if(!qq){
//				alert("请输入qq号码");
//				return;
//			}
			var fax = $(".fax").val();
//			if(!fax){
//				alert("请输入传真");
//				return;
//			}
			var co_name = $(".co_name").val();
//			if(!co_name){
//				alert("请输入公司名称");
//				return;
//			}
			var co_address = $(".co_address").val();
//			if(!co_address){
//				alert("请输入公司地址");
//				return;
//			}
			var yq_code = $(".yq_code").val();
//			$.post("/wechat.php/site/register",{"name":name,"phone":phone,"checkcode":checkcode,"yq_code":yq_code,"qq":qq,"fax":fax,"co_name":co_name,"co_address":co_address},function(data){
//				data = eval('(' + data + ')');
//				if(data["code"] == 0){
//					alert("注册成功");
//					window.location.href = "/wechat.php/user";
//				}else{
//					alert(data["info"]);
//				}
//			});
			$.ajax({
				type: "POST",
				url: "/wechat.php/site/register",
				data: {"name":name,"phone":phone,"checkcode":checkcode,"yq_code":yq_code,"qq":qq,"fax":fax,"co_name":co_name,"co_address":co_address},
				beforeSend: function () {
					// 禁用按钮防止重复提交
					$(".edit-save").addClass("fill-save");
					$(".edit-save a").html("正在提交。。。");
					//$(".bga").show();
					//$(".bga-cont2").show();
				},
				success: function(data){
					$(".fill-save").removeClass("fill-save");
					$(".edit-save a").html("保存");
					//$(".bga").hide();
					//$(".bga-cont2").hide();
					data = eval('(' + data + ')');
					if(data["code"] == 0){
						//alert("注册成功");
						var url = "/wechat.php/user";
						if(data["info"]){
							url = "/wechat.php/quoted/detail?quoted_id="+data["info"];
						}
						window.location.href = url;
					}else{
						alert(data["info"]);
					}
				}
			});
			
		});
	});
</script>
