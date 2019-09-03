<?php ?>
<style>
.information_all{clear:both;font-size:14px;line-height:30px;margin-left:30px;padding-top:20px;}
.information_detail{font-size: 14px;line-height:30px;margin-left:30px;}
.fail_list{font-size: 14px;line-height:30px;margin-left:30px;}
</style>
<div class="search_body">
<div class="search_date">
	<div style="float:left">日期：</div>
	<div class="search_date_box">
		<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="" name="time_L">
	</div>
	<div style="float:left;margin:0 3px;">至</div>
	<div class="search_date_box">
		<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="" name="time_H"  >
	</div>
</div>
<input type="button" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="自动出库" style="margin-left:20px;">
</div>
<div class="information_all"  >
</div>
<div class="information_detail" >
</div>
<div class="fail_list">
</div>
<script>
	var can_submit=true;
	$('.btn_sub').click(function(){
		//判断满足条件否
		if(!can_submit)	return false;
		var time_L=$('.start_time').val();
		var time_H=$('.end_time').val();
		if(!time_L||!time_H){
			confirmDialog('请输入开始时间或结束时间');
			return false;
		}
		if(time_L>time_H){
			confirmDialog('结束时间必须大于等于开始时间');
			return;			
		}
		var time_l=new Date(time_L);
		var time_h=new Date(time_H);
		if(time_h-time_l>1296000000){
			confirmDialog('时间间隔不得超过15天');return false;
		}
		can_submit=false;				
		var text='确认要自动出库吗';
		var href="/index.php/frmOutput/autoOutput?time_L="+time_L+"&time_H="+time_H;
		confirmDialog9(text,href);		
	});
	function confirmDialog9(text,href){
		var str='<div class="dialogbody">'+
					'<div class="pop_background"></div>'+
					'<div class="check_background" id="check">'+
						'<div class="check_div">'+
							'<div class="pop_title">提示'+
								'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
							'</div>'+
							'<div class="check_str">'+text+'</div>'+
							'<div class="pop_footer">'+
								'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancelIt">取消</button>'+
								'<button type="button" class="btn btn-primary btn-sm IKnowIt" data-dismiss="modal" id="IKnowIt">确定</button>'+
							'</div>'+
						'</div>'+
					'</div>'+
				'</div>';
		$("body").append(str);
		$(".IKnowIt").on("click",function(e){
			$(".dialogbody").remove();
			//ajax处理输出
			
			var info_all=$('.information_all');
			var info_detail=$('.information_detail');		
			var fail_list=$('.fail_list');
			fail_list.html('');
			var xhr = new window.XMLHttpRequest(); 
			if(!window.XMLHttpRequest){
				try { 
					xhr = new window.ActiveXObject("Microsoft.XMLHTTP"); 
				} catch(e) {} 
			}
			xhr.open("post",href); 
			var oldSize=0; 
			xhr.onreadystatechange = function(){
				if(xhr.readyState >2){
					var tmpText = xhr.responseText.substring(oldSize); 
					if(oldSize==0)
					{
						if(tmpText=='0')
						{
							info_all.html('此时间段没有要处理的销售单');
							can_submit=true;
						}else{
							info_all.html('总共有<span class="count_all red">'+tmpText+'</span>条销售单');
							info_detail.html('正在处理第<span class="count_now " style="color: green">1</span>条销售单');
						}
					}else{					
						if(tmpText.length > 0 ){
							if(isNaN(tmpText))
							{
								fail_list.append(tmpText);
							} else{
								// 设置文本 
								info_detail.html('正在处理第<span class="count_now " style="color: green">'+tmpText+'</span>条销售单');
							}									 
						}
					}
					oldSize = xhr.responseText.length; 				 
				} 
				if(xhr.readyState == 4){
					// 请求执行完毕 
					info_detail.html( "执行完毕");	
					can_submit=true;			 
				} 
			} 
			xhr.send(null);
		});	
		$("#cancelIt").on("click",function(){
			$(".dialogbody").remove();
		})
		//点击取消按钮
	  	$(".pop_cancle").on("click",function(){
	  		$(".dialogbody").remove();
	  	});
	}
</script>