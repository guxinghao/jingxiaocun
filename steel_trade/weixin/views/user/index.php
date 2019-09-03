<body >
<style>
	.personal-ulaq{
		background: url(/weixin/skin/images/qq.png) no-repeat;
	}
	.personal-ulaa{
		background: url(/weixin/skin/images/aper12.png) no-repeat;
	}
</style>
<section class="personal-info">
	<article class="personal-header"><p><img src="<?php echo $userinfo['pic']?>"/><?php echo $userinfo['username']?></p></article>
	<article class="personal-content">
		<ul>
			<li class="personal-ula"><p>
					<label>联系电话</label>
					<span><a href="/wechat.php/user/edit?type=1"><font><?php echo $userinfo['phone']?></font><img src="/weixin/skin/images/aper_4.png"/></a></span>
				</p></li>
			<?php if($userinfo['company_id']){?>
			<li class="personal-ulb"><p>
					<label>公司抬头</label>
					<span><a href="/wechat.php/user/companyList?type=2&company_id=<?php echo $userinfo['company_id']?>"><font style="overflow: hidden;width: 166px;float: left;text-align: right;padding-right: 6px;"><?php echo $userinfo['company']?></font><img style="margin-left: 8px;" src="/weixin/skin/images/aper_4.png"/></a></span>
				</p></li>
			<?php }?>
			<li class="personal-ulc" style="<?php if(!$userinfo['userphone']) echo 'display:none';?>"><p>
					<label>我的采购专员</label>
					<span><a href="tel:<?php echo $userinfo['userphone']?>"><img src="/weixin/skin/images/aper_5.png"/></a></span>
				</p></li>
			<li class="personal-ulaq"><p>
					<label>qq</label>
					<span><a href="/wechat.php/user/editInfo"><font><?php echo $userinfo['qq']?></font><img src="/weixin/skin/images/aper_4.png"/></a></span>
				</p></li>
			<li class="personal-ulaa"><p>
					<label>传真</label>
					<span><a href="/wechat.php/user/editInfo?type=2"><font><?php echo $userinfo['fax']?></font><img src="/weixin/skin/images/aper_4.png"/></a></span>
				</p></li>
			<li class="personal-ule" style="display: none"><p>
					<label>消息通知</label>
					<span><a href="#"><i><?php echo $userinfo['news']?></i><img src="/weixin/skin/images/aper_4.png"/></a></span>
				</p></li>
		</ul>
	</article>
</section>

<script>
	$(function(){
		$(".getcode").click(function(){
			var phone = $(".phone").val();
			if(!phone){
				alert("请输入手机号码");
				return;
			}
			$.post("/wechat.php/site/getCode",{"phone":phone},function(data){
				if(data == 1){
					alert("验证码发送成功");
				}else{
					alert("验证码发送失败");
				}
			});
		});
		$(".sava").click(function(){
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
			if(!qq){
				alert("请输入qq号码");
				return;
			}
			var fax = $(".fax").val();
			if(!fax){
				alert("请输入传真");
				return;
			}
			var co_name = $(".co_name").val();
			if(!co_name){
				alert("请输入公司名称");
				return;
			}
			var co_address = $(".co_address").val();
			if(!co_address){
				alert("请输入公司地址");
				return;
			}
			var yq_code = $(".yq_code").val();
			$.post("/wechat.php/site/register",{"name":name,"phone":phone,"checkcode":checkcode,"yq_code":yq_code,"qq":qq,"fax":fax,"co_name":co_name,"co_address":co_address},function(data){
				data = eval('(' + data + ')');
				if(data["code"] == 0){
					alert("注册成功");
				}else{
					alert(data["info"]);
				}
			});
		});
	});
</script>
