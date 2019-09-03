	//点击作废按钮
	var href='';
  	$(document).on("click",".delete_form",function(){
		$(".pop_background").show();
		$("#deleted").show();
		href=$(this).attr('url');
  	});
  	//点击取消按钮
  	$(".pop_cancle").click(function(){
  		$(".pop_background").hide();
		$("#deleted").hide();
		$("#check").hide();
  	});
  	//作废提交按钮
  	$("#submit").on("click",function(){
  	  	var str = $(".pop_textarea").val();
  	  	if(str == ''){confirmDialog("请输入作废原因");return false;}	
		href=href+'&str='+str;		
		$.get(href,function(e){
			if(e==1){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("作废失败");
			}
		});
  	 })
  	 //审核点击按钮
  	 var pass_url='';
 	 var deny_url='';
  	 	$(document).on("click",".check_form",function(){			
			var str=$(this).attr("str");
			pass_url=$(this).attr('url');
			deny_url=$(this).attr('url_deny');
			$(".check_str").html(str);
			$(".pop_background").show();
			$("#check").show();
  	});
  	 //审核通过
  	 $("#check_sub").click(function(){
  		$.get(pass_url,function(e){
			if(e==1){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("作废失败");
			}
		});
  	 })
  	 //审核拒绝
  	  $("#unpass").click(function(){
  		$.get(deny_url,function(e){
			if(e==1){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("作废失败");
			}
		});
  	 })