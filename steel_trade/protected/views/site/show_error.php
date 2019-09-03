<style>
.this_page{float:left;width:100%;height:1000px;background:#f7f7f7}
.info_box{border:1px solid #e4e4e4;width:94%;height:300px;float:left;margin-top:30px;margin-left:3%;background:#fff;}
.info_title{width:100%;height:60px;float:left;text-align:center;margin-top:85px}
.info_title .title{height:100%;width:350px;margin:0 auto;}
.title_text{font-size:22px;line-height:60px;margin-left:15px;color:#bd0000;font-family:MicrosoftYaHei;text-shadow: 1px 1px#e4e4e4;}
.title *{float:left}
.info_button{text-align:center;margin-top:5px;float:left;width:100%}
.info_button button{width:140px;height:30px;color:#fff;background:#28a0de;border:none;font-size:14px;border-radius:3px;}
.info_timer{text-align:center;width:100%;height:30px;float:left}
.timer_box{height:30px;width:240px;margin:0 auto;}
.timer_second{color:red;float:left;font-size:14px;line-height:30px}
.timer_text{float:left;font-size:14px;line-height:30px;margin-left:5px}
</style>

<div class="this_page">
	<div class="info_box">
		<div class="info_title">
			<div class="title">
				<img src="/images/alert.png"><div class="title_text">对不起，您的输入有错误</div>
			</div>
		</div>
		<div class="info_timer">
			<div class="timer_box">
				<div class="timer_second">3</div>
				<div class="timer_text">秒后自动返回，或直接点击按钮返回</div>
			</div>
		</div>
		<div class="info_button">
			<button id="backbtn">返回上一界面</button>
		</div>
	</div>

</div>
<script>
var left = 3;
function onTimer(){
	if(left>0){
		left--;
	}
		$('.timer_second').html(left);
		if (left >0) return;
		<?php if ($_REQUEST['url']){?>
		location.href='<?php echo $_REQUEST['url'];?>';
		<?php }else{?>
		window.history.go(-1);
		<?php }?>
}
$(function(){
	setInterval(onTimer,1000);
	$("#backbtn").click(function(){
		<?php if ($_REQUEST['url']){?>
		location.href='<?php echo $_REQUEST['url'];?>';
		<?php }else{?>
		window.history.go(-1);
		<?php }?>
	});
	
});
</script>
