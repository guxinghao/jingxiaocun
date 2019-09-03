<style>
.check_div{height:360px;}
.check_str{height:240px;overflow:auto;line-height:25px;}
.list{padding-left:20px;}
</style>
<div class="dialogbody">
	<div class="pop_background"></div>
	<div class="check_background" id="check">
		<div class="check_div">
			<div class="pop_title">无编码信息列表
				<span class="pop_cancle"><i class="icon icon-times"></i></span>
			</div>
			<div class="check_str">
			
<?php
if($model == 1){
	if(count($user) > 0){
		echo '<div class="list_title">业务员：</div>';
		foreach ($user as $li){
			echo '<div class="list">'.$li->nickname.'</div>';
		}
	}
	if(count($bank) > 0){
		echo '<div class="list_title">银行账户：</div>';
		foreach ($bank as $li){
			echo '<div class="list">'.$li->dict_name.'</div>';
		}
	}
	if(count($title) > 0){
		echo '<div class="list_title">公司抬头：</div>';
		foreach ($title as $li){
			echo '<div class="list">'.$li->name.'</div>';
		}
	}
}
if($model == 2){
	if(count($company) > 0){
		echo '<div class="list_title">采购公司：</div>';
		foreach ($company as $li){
			echo '<div class="list">'.$li->name.'</div>';
		}
	}
	if(count($supply) > 0){
		echo '<div class="list_title">供应商：</div>';
		foreach ($supply as $li){
			echo '<div class="list">'.$li->name.'</div>';
		}
	}
}
?>	
			</div>
			<div class="pop_footer">
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="color:#333;" id="cancelIt">关闭</button>
			</div>
		</div>
	</div>
</div>
<script>
		$("#cancelIt").on("click",function(){
			$(".dialogbody").remove();
		})
		//点击取消按钮
	  	$(".pop_cancle").on("click",function(){
	  		$(".dialogbody").remove();
	  	});
		$(".pop_background,.check_background").on("click",function(){
	  		$(".dialogbody").remove();
	  	});
		$(".check_div").on("click",function(event){
			event.stopPropagation();    //  阻止事件冒泡
		});
</script>