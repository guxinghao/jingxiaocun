$(function(){
	//一级导航栏点击展开二级菜单
	$(".first_bar li").hover(function(){
		var num = $(this).attr("num");
		$(".blue_line").hide();
		$(this).find(".blue_line").show();
		$(".first_bar li").removeClass("bar_selected");
		$(this).addClass("bar_selected");
		$(".bar .second_bar").hide().eq(num).show();
	},function(){
		$(".blue_line").hide();
		$(".first_bar li").removeClass("bar_selected");
		$(".bar .second_bar").hide();
	})
	
	$(".second_bar").hover(function(){
		$(this).show();
	},function(){
		$(this).hide();
		$(".first_bar li").removeClass("bar_selected");
		$(".blue_line").hide();
	})
	//二级菜单鼠标移动效果
	$(".second_bar_list").hover(function(){
		$(this).addClass("bar_hover");
	},function(){
		$(this).removeClass("bar_hover");
	})
	
	$(".more_toggle").bind("click",function(event){
		$(".cz_list_btn_more").hide();
		$(".cz_list_btn_more").attr("num",0);
		$(".car_no_list_close").hide();
		$(".car_no_list").hide();
		var status = $(".more_select_box").css("display");
		if(status == "none"){
			$(".more_select_box").show();
			$(this).css("background-image","url(/images/seopen.png)");
		}else{
			$(".more_select_box").hide();
			$(this).css("background-image","url(/images/seclose.png)");
		}
		event.stopPropagation();    //  阻止事件冒泡
	});
	
	$("body").bind("click",function(){
		$(".more_select_box").hide();
		$(".more_toggle").css("background-image","url(/images/seclose.png)");
		$(".cz_list_btn_more").hide();
		$(".cz_list_btn_more").attr("num",0);
	})
	$(".more_select_box").bind("click",function(e){
		e.stopPropagation();    //  阻止事件冒泡
	})
	
	 $(".more_but").live("click",function(e){
		$(".more_select_box").hide();
		$(".cz_list_btn_more").hide();
		var status = $(this).next().attr("num");
		if(status == 0){
			$(this).next().show();
			$(".cz_list_btn_more").attr("num",0);
			$(this).next().attr("num",1);
		}else{
			$(".cz_list_btn_more").hide();
			$(this).next().attr("num",0);
		}
		e.stopPropagation();    //  阻止事件冒泡
	})
	
	$(".cz_list_btn_more").live("click",function(e){
		e.stopPropagation();    //  阻止事件冒泡
	})
	
	//搜索条件重置按钮
	$(".reset").click(function(){
		$(".forreset").val('');
	});
	
	
	$("#set_home").click(function(){
		var url = $(this).attr('url');
		$.post("/index.php/site/setHome", {
			'url' : url
		}, function(data) {
			if(data=="updated"){
				confirmDialog("设置成功！");	
			}else{
				confirmDialog("设置失败！");	
			}
		});
	});
	dateTimePick();	
})


 function changeCont()
{
	var vendor_id=$('#comboval').val();
	$.get('/index.php/contract/getVendorCont',{
		'vendor_id':vendor_id,
	},function(data){
		var data1=data.substring(0,data.indexOf('o1o'));
		var data2=data.substring(data.indexOf('o1o')+3);
		$('#contact_id').html(data1);
		$('#phone').val(data2);
	});			
	
	//填充运费
//	if(vendor_id)
//	{
//		$.get('/index.php/purchase/getShipment/'+vendor_id,{},function(data){
//			if(data){$('#shipment').val(data);}
//		})
//	}	
}	   

function updateTotalAmount()
{
	  var total=0;
	  $("#cght_tb tbody tr").each(function(){
		  var num=numChange($(this).find('.td_num').val());
		  if(num)
		  {
			  total=total+parseInt(num);
		  }		  
		});
		$("#cght_tb tfoot tr .tf_total_amount").text(total);
}
function updateTotalWeight()
{
	var total=0;
	 $("#cght_tb tbody tr").each(function(){
		  var weight=numChange($(this).find('.td_weight').val());
		  if(weight)
		  {
			  total=total+parseFloat(weight);
		  }		  
		});
		$("#cght_tb tfoot tr .tf_total_weight").text(total.toFixed(3));
}
function updateTotalMoney()
{
	var total=0;
	 $("#cght_tb tbody tr").each(function(){
		 if($(this).find('.td_money').val())
		 {
			 var money=numChange($(this).find('.td_money').val()); 
		 }else{
			 var money='';
		 }		  
		  if(money)
		  {
			  total=total+parseFloat(money);
		  }		  
		});
		$("#cght_tb tfoot tr .tf_total_money").text(numberFormat(total.toFixed(2),2));
}

function checkApprove(id,type)
{
	var result=true;
	$.ajaxSetup({async:false});
	$.get('/index.php/commonForms/checked/'+id,{'type':type,},function(data){
		result=data;
	});
	return result;
}
function changeOwnerT()
{
	var owner=$('#CommonForms_owned_by').val();
	$.get('/index.php/contract/getUserTeam',{
		'owner':owner,
	},function(data){
		$('#team_id').val(data);
		$('#team_id').next().val(data);
	});
}
function CurrentTime(type)
{
    var now = new Date();   
    var year = now.getFullYear();       //年
    var month = now.getMonth() + 1;     //月
    var day = now.getDate();            //日   
    var clock = year + "-";   
    if(month < 10)clock += "0";   
    clock += month + "-";   
    if(day < 10)
        clock += "0";       
    clock += day;  
    if(type=='full')
    {
    	clock+=' ';
    	var hh = now.getHours();            //时
    	var mm = now.getMinutes();          //分   
    	if(hh < 10)
    	   clock += "0";       
    	clock += hh + ":";
    	if (mm < 10) clock += '0'; 
    	clock += mm; 
    } 
    return(clock); 
} 
function confirmDialogLink(text, callback,callback1){
	var confirm_return;
	var str='<div class="dialogbody">'+
				'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div">'+
						'<div class="pop_title">提示'+
							'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
						'</div>'+
						'<div class="check_str">'+text+'</div>'+
						'<div class="pop_footer">'+
							'<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal" id="cancelIt">关联</button>'+
							'<button type="button" class="btn btn-primary btn-sm gray"  style="color:#333;" data-dismiss="modal" id="IKnowIt">不关联</button>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
	$("body").append(str);
	$("#IKnowIt").on("click",function(e){
		$(".dialogbody").remove();
		callback();
	});
	
	$("#cancelIt").on("click",function(){
		$(".dialogbody").remove();
		callback1();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody").remove();
  	});
}


function notAnymore(select)
{
	$('.btn').css('background','#d5d5d5').css('color','#999');
	$('#'+select).html($('#'+select).text()+'中...');
}

function notAnymoreLoad(select)
{
	$('.btn').css('background','#d5d5d5').css('color','#999');
	$('#'+select).html($('#'+select).text()+'中...');
//	var str='<div class="loading" ><img  src="/images/ajaxL.gif" /></div>';
//	$("body").append(str);
	Loading();
}

function Loading()
{
	var str='<div class="loading" ><img  src="/images/ajaxL.gif" /></div>';
	$("body").append(str);	
}

function recoverSaveButton(select)
{
	$('.btn').css('background','#426ebb').css('color','#fff');
	$('#'+select).html($('#'+select).attr('title'));
}
function setSubmitStatus(){
	$('.save').css('background','#d5d5d5').css('color','#999');
	$('.save').html('保存中...');
}

function checkAuthority(operation)
{
	var result;
	$.ajaxSetup({async:false});
	$.get('/index.php/user/checkAuth',{
		'operation':operation,
	},function(data){
		 result=data
	});
	return result;
}

//车牌验证
function checkTravel(str)
{
	var travel = str.replace(/ /g,",");
	travel = travel.replace(/，/g,",");
	travel = travel.replace(/　/g,",");
	travel = travel.replace(/，/g,",");
	var travel_array = travel.split(",");
	

	if(travel_array.length <= 0) return "车船号不能为空";//格式不正确或车船号为空

	var car_noone=["京","津","冀","晋","蒙","辽","吉","黑","沪","苏","浙","皖","闽","赣","鲁","豫","鄂","湘","粤","桂","琼","渝","川","贵","云","藏","陕","甘","青","宁","新","黔"];
	
	var count = travel_array.length;
	var index = 0;
	for(var i=0;i<count;i++){
		var travel_name = travel_array[i];
		

		if(travel_name ==""){
			index++;
			continue;
		}
		//判断开头文字是否正确
		if($.inArray(travel_name.substr(0,1), car_noone) < 0){
			return "车船号："+travel_name+"不正确";
		}
		//判断格式是否正确
		var result = checkNo(travel_name);
		if(result<0) return "车船号："+travel_name+"不正确";
		
	}
	if(index == count && count!=1) return "车船号格式不正确"
	return 1;
}
function checkNo(str){
	var lastwz=str.substr(-1);
	var re=/^[\u4e00-\u9fa5]{1}[A-Z_a-z]{1}[A-Z_a-z_0-9]{5}$/;
	if(CheckChinese(lastwz))
	{
		if(lastwz!="挂"){return -1;}
		re=/^[\u4e00-\u9fa5]{1}[A-Z_a-z]{1}[A-Z_a-z_0-9]{4}[\u4e00-\u9fa5]{1}$/;
	}
	return str.search(re);
}

function CheckChinese(val){     
	var reg = new RegExp("[u4E00-u9FFF]+","g");
	if(/.*[\u4e00-\u9fa5]+.*$/.test(val)){     
       return true;         
	}
	return false;
}

function numberFormat(number, decimals, decimalpoint, separator) 
{	
	var format_num = "";
	decimals = decimals ? parseInt(decimals) : 0;
	decimalpoint = decimalpoint ? decimalpoint : ".";
	separator = separator ? separator : ",";
	number = number + "";
	//判断第一位是否是负号
	var pn = number.substr(0,1);
	if(pn != "-"){
		pn = "";
	}else{
		number = number.substr(1);
	}
	number = number.indexOf(".") > -1 ? number : parseFloat(number).toFixed(decimals) + "";
	number_array = number.split (".");
	
	var integer_num = number_array[0]; //整数部分
	var decimalpoint_num = number_array[1]; //小数部分
	if (decimals >= decimalpoint_num.length) 
	{ 
		var decimalpoint_num_length = decimals - decimalpoint_num.length;
		for (var i = 0; i < decimalpoint_num_length; i++) 
		{
			decimalpoint_num = decimalpoint_num + "0";
		}
	} 
	else 
	{
		decimalpoint_num = decimalpoint_num.substring(0, decimals);
	}	
	var format_integer_num = "";
	var integer_num_array = integer_num.split ("");
	for (var i = 0; i < integer_num_array.length; i++) 
	{
		if ((integer_num_array.length - i) % 3 == 0 && i > 0) format_integer_num = format_integer_num + separator;
		format_integer_num = format_integer_num + integer_num_array[i];
	}
	format_num = format_integer_num;
	format_num = decimalpoint_num ? format_num + decimalpoint : format_num;
	format_num = format_num + decimalpoint_num;
	format_num = pn + format_num;
	return format_num;
}

function numChange(str){
	var num = str;
	num = num.replace(/,/g,'');
	num = num.replace(/，/g,'');
	return num;
}

function date(format, timestamp) {
	var date_val = "";
	$.ajaxSetup({ async : false });
	$.get('/index.php/site/getDate', { 'format': format, 'timestamp': timestamp }, function(data) { date_val = data;});
	return date_val;
}

function strtotime(time, now) 
{
	var strtotime_val = 0;
	$.ajaxSetup({ async : false });
	$.get('/index.php/site/getStrtotime', { 'time': time, 'now': now }, function(data) { strtotime_val = data;});
	return strtotime_val;
}


//公共错误提示弹出方法
function confirmDialog(text, callback){
	var str='<div class="dialogbody">'+
			'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div_short">'+
						'<div class="check_str_short">'+text+'</div>'+
						'<div class="pop_footer_short">'+
							'<button type="button" class="btn btn-primary btn-sm pop_long_button IKnowIt" data-dismiss="modal" id="IKnowIt">知道了</button>'+
						'</div>'+
					'</div>'+
				'</div>'
			'</div>';
	$("body").append(str);
	//$(document).on("click","#check_sub",function(e){
	$(".IKnowIt").on("click",function(e){
		$(".dialogbody").remove();
		if (callback) callback();
	});
}

//公共错误提示弹出方法
function confirmLoading(text){
	var str='<div class="dialogbody">'+
			'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div_short">'+
						'<div class="check_str_short">'+text+'</div>'+
					'</div>'+
				'</div>'
			'</div>';
	$("body").append(str);
}

//多行公共错误提示弹出方法，会出现滚动条
function confirmDialogMore(text){
	$(".dialogbody").remove();
	var str='<div class="dialogbody">'+
				'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div" style="height:240px;">'+
						'<div class="pop_title">提醒：'+
							'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
						'</div>'+
						'<div class="check_str" style="height:120px;overflow:auto;line-height:25px;">'+
						text+
						'</div>'+
						'<div class="pop_footer">'+
							'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="color:#333;" id="cancelIt">关闭</button>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
	$("body").append(str);
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
	
}

//提示是否操作方法,取消或者关闭后刷新页面
function confirmDialogCancle(text,href){
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
		$.get(href,function(e){
			if(e=="success"||e=="1"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e,function(){window.location.reload();});
				else confirmDialog("更新失败",function(){window.location.reload();});
			}
		});
	});
	
	$("#cancelIt").on("click",function(){
		$(".dialogbody").remove();
		window.location.reload();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody").remove();
  		window.location.reload();
  	});
}


//公告提示是否操作方法
function confirmDialog2(text,href){
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
		$.get(href,function(e){
			if(e=="success"||e=="1"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("更新失败");
			}
		});
	});
	
	$("#cancelIt").on("click",function(){
		$(".dialogbody").remove();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody").remove();
  	});
}

function confirmDialog3(text, callback){
	var confirm_return;
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
							'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="IKnowIt">确定</button>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
	$("body").append(str);
	$("#IKnowIt").on("click",function(e){
		$(".dialogbody").remove();
		callback();
	});
	
	$("#cancelIt").on("click",function(){
		$(".dialogbody").remove();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody").remove();
  	});
}
//公共错误提示弹出方法,带确认后自动化刷新
function confirmDialogRefresh(text){
	var str='<div class="dialogbody">'+
			'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div_short">'+
						'<div class="check_str_short">'+text+'</div>'+
						'<div class="pop_footer_short">'+
							'<button type="button" class="btn btn-primary btn-sm pop_long_button" data-dismiss="modal" id="IKnowIt">知道了</button>'+
						'</div>'+
					'</div>'+
				'</div>'
			'</div>';
	$("body").append(str);
	//$(document).on("click","#check_sub",function(e){
	$("#IKnowIt").on("click",function(e){
		$(".dialogbody").remove();
		window.location.reload();
	});
}


//一般confirm，传入函数func1确认时动作，func2取消时动作
function confirmDialogWithCallBack(text,func1,func2){
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
							'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="IKnowIt">确定</button>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
	$("body").append(str);
	$("#IKnowIt").on("click",function(e){
		$(".dialogbody").remove();
		func1();
	});
	$("#cancelIt").on("click",function(){
		$(".dialogbody").remove();
		func2();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody").remove();
  		func2();
  	});
}
function setCheck(obj){
	var lastdate = $(obj).attr("lastdate");
	var id = $(obj).attr("id");
	var str = $(obj).attr("str");
	var text='<div class="pop_background"></div>'+
		'<div class="check_background" id="check">'+
			'<div class="check_div">'+
				'<div class="pop_title">审核'+
					'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
				'</div>'+
				'<div class="check_str">'+str+'</div>'+
				'<div class="pop_footer">'+
					'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancle">取消</button>'+
					'<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="color:#fff;" id="unpass">拒绝</button>'+
					'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="check_sub">同意</button>'+
				'</div>'+
			'</div>'+
		'</div>';
	$("body").append(text);
	 //审核通过
	$("#check_sub").on("click",function(){
		$.post(id,{"last_update":lastdate,"type":"pass"},function(e){
			if(e=="success"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("同意失败");
			}
		})
	 })
	 //审核拒绝
	 $("#unpass").on("click",function(){
		$.post(id,{"last_update":lastdate,"type":"deny"},function(e){
			if(e=="success"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("拒绝失败");
			}
		})
	 })
	//点击取消按钮
  	$("#cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
	$(".pop_cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
}

function setCheck_unrefresh(obj){
	var lastdate = $(obj).attr("lastdate");
	var id = $(obj).attr("id");
	var str = $(obj).attr("str");	
	var form_sn=$(obj).parent().find('.form_sn').val();
	var text='<div class="pop_background"></div>'+
		'<div class="check_background" id="check">'+
			'<div class="check_div">'+
				'<div class="pop_title">审核'+
					'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
				'</div>'+
				'<div class="check_str">'+str+'</div>'+
				'<div class="pop_footer">'+
					'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancle">取消</button>'+
					'<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="color:#fff;" id="unpass">拒绝</button>'+
					'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="check_sub">同意</button>'+
				'</div>'+
			'</div>'+
		'</div>';
	$("body").append(text);
	 //审核通过
	$("#check_sub").on("click",function(){
		//转圈圈
		$('.pop_background').remove();
		$('.check_background').remove();
		Loading();		
		$.post(id,{"last_update":lastdate,"type":"pass"},function(e){
			if(e=="success"){
				//设置已审核
				var operateStr=getOperateBut(form_sn,'sales');
				$(obj).parent().parent().html(operateStr);
				var can_push=operateStr.substring(operateStr.indexOf('canpush')+9,operateStr.indexOf('canpush')+10);
				$('.red').each(function(){
					if($(this).attr('form_sn')==form_sn){
						if(can_push==1)var status_str="待出库";						
						else var status_str="已审核";
						$(this).parent().html('<span class="status_sec" form_sn="'+form_sn+'">'+status_str+'</span>');
					}
				})
				$('.loading').remove();
//				window.location.reload();
			}else{
				$('.loading').remove();
				if(e) confirmDialog(e);
				else confirmDialog("同意失败");
			}
		})
	 })
	 //审核拒绝
	 $("#unpass").on("click",function(){
		 //转圈圈
		$('.pop_background').remove();
		$('.check_background').remove();
		Loading();		
		$.post(id,{"last_update":lastdate,"type":"deny"},function(e){
			if(e=="success"){
				//设置已拒绝
				var operateStr=getOperateBut(form_sn,'sales');
				$(obj).parent().parent().html(operateStr);
				$('.red').each(function(){
					if($(this).attr('form_sn')==form_sn){
						$(this).parent().html('<span class="red" form_sn="'+form_sn+'">未提交</span>');
					}
				})
				$('.loading').remove();
				//window.location.reload();
			}else{
				$('.loading').remove();
				if(e) confirmDialog(e);
				else confirmDialog("拒绝失败");
			}
		})
	 })
	//点击取消按钮
  	$("#cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
	$(".pop_cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
}


function getOperateBut(form_sn,type)
{
	var str='';
	var url='';
	$.ajaxSetup({async:false});
	switch(type)
	{
		case 'sales':
			url='/index.php/frmSales/getCurrentButton';
			break;
		case 'fsk':
			url='/index.php/formBill/getCurrentButton';
			break;
		case 'fbz':
			url='/index.php/billOther/getCurrentButton';
			break;
		case 'fdj':
			url='/index.php/shortLoan/getCurrentButton';
		default:
			break;
	}	
	$.post(url,{'form_sn':form_sn},function(data){
		str=data;
	});
	return str;
}

function setPayCheck_unrefresh(obj){
	var lastdate = $(obj).attr("lastdate");
	var frm=$(obj).attr("frm");
	var id = $(obj).attr("id");
	var str = $(obj).attr("str");
	var position=$(obj).attr('position');
	var form_sn=$(obj).parent().find('.form_sn').val();
	var text='<div class="pop_background"></div>'+
		'<div class="check_background" id="check">'+
			'<div class="check_div">'+
				'<div class="pop_title">审核'+
					'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
				'</div>'+
				'<div class="check_str">'+str+'</div>'+
				'<div class="pop_footer">'+
					'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancle">取消</button>'+
					'<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="color:#fff;" id="unpass">拒绝</button>'+
					'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="check_sub">同意</button>'+
				'</div>'+
			'</div>'+
		'</div>';
	$("body").append(text);
	 //审核通过
	$("#check_sub").on("click",function(){
		//转圈圈
		$('.pop_background').remove();
		$('.check_background').remove();
		Loading();		
		$.post(id,{"last_update":lastdate,"type":"pass"},function(e){
			if(e=="success"){
				//设置已审核
				var operateStr=getOperateBut(form_sn,frm);
//				alert(operateStr);
				$(obj).parent().parent().html(operateStr);
				$('.status_btn').each(function(){
					var sta_id=$(this).attr('id');
					if($(this).attr('form_sn')==form_sn){
						if(position=='chuna')
						{
							$(this).parent().html('<span id="'+sta_id+'" class="status_btn" form_sn="'+form_sn+'">已审核</span>');
						}else{
							$(this).parent().html('<span id="'+sta_id+'" class="status_btn" form_sn="'+form_sn+'">审核中</span>');
						}						
					}
				})
				$('.loading').remove();
//				window.location.reload();
			}else{
				$('.loading').remove();
				if(e) confirmDialog(e);
				else confirmDialog("同意失败");
			}
		})
	 })
	 //审核拒绝
	 $("#unpass").on("click",function(){
		 //转圈圈
		$('.pop_background').remove();
		$('.check_background').remove();
		Loading();		
		$.post(id,{"last_update":lastdate,"type":"deny"},function(e){
			if(e=="success"){
				//设置已拒绝
				var operateStr=getOperateBut(form_sn,frm);
				$(obj).parent().parent().html(operateStr);
				$('.status_btn').each(function(){
					if($(this).attr('form_sn')==form_sn){
						$(this).parent().html('未提交');
					}
				})
				$('.loading').remove();
				//window.location.reload();
			}else{
				$('.loading').remove();
				if(e) confirmDialog(e);
				else confirmDialog("拒绝失败");
			}
		})
	 })
	//点击取消按钮
  	$("#cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
	$(".pop_cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
}


function deleteIt(obj){
	 var lastdate = $(obj).attr("lastdate");
	 var id = $(obj).attr("id");
	 var text='<div class="pop_background"></div>'+
	            '<div class="check_background" id="deleted">'+
	                '<div class="deleted_div">'+
	                    '<div class="pop_title">请输入作废理由'+
	                        '<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
	                    '</div>'+
	                    '<textarea class="pop_textarea"></textarea>'+
	                    '<div class="pop_footer">'+
	                        '<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="submit">提交</button>'+
	                    '</div>'+
	                '</div>'+
	            '</div>';
	    $("body").append(text);
	    //作废提交按钮
	  	$("#submit").on("click",function(){
	  	  	var str = $(".pop_textarea").val();
	  	  	if (str == '') { confirmDialog("请输入作废原因"); return false; }
	  	  	$.post(id, { 'last_update': lastdate, 'str': str}, function(e) {
	  	  		if (e == 'success') window.location.reload();
	  	  		else if (e) confirmDialog(e); 
	  	  		else confirmDialog("作废失败");
	  	  	});
	  	 });
	  	$(".pop_cancle").on("click",function(){
	  		$(".pop_background").remove();
	        $("#deleted").remove();
	        $("#check").remove();
	  	});
}
//取消审核销售单输入取消原因
function cancleCheckSales(text,href){	
	var str='<div class="dialogbody1">'+
						'<div class="pop_background"></div>'+
						'<div class="check_background" id="check">'+
							'<div class="check_div" style="height:auto;">'+
							  '<div class="pop_title">提示'+
							 		'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
							  '</div>'+
							  '<div class="check_str">'+text+'</div>'+
							  '<div class="check_reason_contain" >'+
							  		'<div class="check_reason_select"  >'+
							  				'<div class="reason_title" >取消理由：</div>'+
							  				'<select class="form-control reason_val" style="width: 145px;float:left;font-size:14px;color:#666;">'+
							  					'<option value="1">修改客户抬头</option>'+
							  					'<option value="2">修改单价</option>'+
							  					'<option value="3">质量问题退货</option>'+
							  					'<option value="4">款未到退单</option>'+
							  					'<option value="5">甲乙单变动</option>'+
							  					'<option value="-1">其他</option>'+
							  				'</select>'+
							  		'</div>'+
							  		'<div class="check_reason_input" >'+
							  			'<div class="reason_title" >其他理由：</div>'+
							  			'<textarea  class="form-control reason_text"  style="float: left;width:490px;"></textarea>'+
							  		'</div>'+
							  '</div>'+	  
							  '<div class="pop_footer" style="clear: both;padding-bottom:10px;">'+
								 '<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancelIt">取消</button>'+
								 '<button type="button" class="btn btn-primary btn-sm IKnowIt" data-dismiss="modal" id="IKnowIt">确定</button>'+
								 '<div class="clear"></div>'+
							  '</div>'+
							'</div>'+
						'</div>'+
					'</div>';
	
	$("body").append(str);
	$('.reason_val').change(function(){
		if($(this).val()=='-1')
		{
			$('.check_reason_input').show();
		}else{
			$('.check_reason_input').hide();
		}
	})
	$(".IKnowIt").on("click",function(e){
		var reason_val=$('.reason_val').val();		
		var other_reason='';
		if(reason_val=='-1')
		{
			 other_reason=$('.reason_text').val();
			if(other_reason==''){
				confirmDialog('请输入取消审核原因!');
				return false;
			}
		}
		href+='&reason_val='+reason_val+'&other_reason='+other_reason;
		$(".dialogbody1").remove();
		$.get(href,function(e){
			if(e=="success"||e=="1"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("更新失败");
			}
		});
	});	
	$("#cancelIt").on("click",function(){
		$(".dialogbody1").remove();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody1").remove();
  	});
}

//销售单取消审核
function confirmDialog_uncheck(common_id,text,href){
	var reason='';
	var url='/index.php/frmSales/reasonView/'+common_id;
	$.ajaxSetup({async:false});
	$.get(url,{},function(data){
		if(data){			
			reason=data;
		}				
	});		
	var str='<div class="dialogbody">'+
				'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div" style="height:auto;">'+
						'<div class="pop_title">提示'+
							'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
						'</div>'+
						'<div class="check_str">'+text+'</div>'+
						 '<div class="check_reason_contain" style="padding:5px 14px;">'+reason+'</div>'+
						'<div class="pop_footer" style="padding-bottom:8px;">'+
							'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancelIt">取消</button>'+
							'<button type="button" class="btn btn-primary btn-sm IKnowIt" data-dismiss="modal" id="IKnowIt">确定</button>'+
							'<div class="clear"></div>'+
						'</div>'+
					'</div>'+
				'</div>'+
			'</div>';
	$("body").append(str);
	$(".IKnowIt").on("click",function(e){
		$(".dialogbody").remove();
		$.get(href,function(e){
			if(e=="success"||e=="1"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("更新失败");
			}
		});
	});
	
	$("#cancelIt").on("click",function(){
		$(".dialogbody").remove();
	})
	//点击取消按钮
  	$(".pop_cancle").on("click",function(){
  		$(".dialogbody").remove();
  	});
}




//作废销售提成
function deleteCom(obj){
	var id = $(obj).attr("url");
	var text='<div class="pop_background"></div>'+
			'<div class="check_background" id="deleted">'+
				'<div class="deleted_div"  style="height:180px;">'+
					'<div class="pop_title">作废'+
						'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
					'</div>'+
					'<div class="check_str">您确定要作废此条销售提成吗？</div>'+
					'<div class="pop_footer">'+
						'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="submit">提交</button>'+
					'</div>'+
				'</div>'+
			'</div>';
	$("body").append(text);
	//作废提交按钮
  	$("#submit").on("click",function(){
  	  	$.post(id, {}, function(e) {
  	  		if (e == 'success') window.location.reload();
  	  		else confirmDialog("作废失败");
  	  	});
  	 });
  	$(".pop_cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#deleted").remove();
		$("#check").remove();
  	});
}

function CheckCom(obj){
	var id = $(obj).attr("url");
	var str = "你确定要"+$(obj).attr("title")+"此条销售提成吗？";
	var text='<div class="pop_background"></div>'+
		'<div class="check_background" id="check">'+
			'<div class="check_div">'+
				'<div class="pop_title">审核'+
					'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
				'</div>'+
				'<div class="check_str">'+str+'</div>'+
				'<div class="pop_footer">'+
					'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancle">取消</button>'+
					'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="check_sub">同意</button>'+
				'</div>'+
			'</div>'+
		'</div>';
	$("body").append(text);
	 //审核通过
	$("#check_sub").on("click",function(){
		$.post(id,{},function(e){
			if(e=="success"){
				window.location.reload();
			}else{
				confirmDialog("同意失败");
			}
		})
	 })
	//点击取消按钮
  	$("#cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
	$(".pop_cancle").on("click",function(){
  		$(".pop_background").remove();
		$("#check").remove();
  	});
}

//格式化数字
function fmoney(s, n)
{
   n = n > 0 && n <= 20 ? n : 2;
   s = parseFloat((s + "").replace(/[^\d\.-]/g, "")).toFixed(n) + "";
   var l = s.split(".")[0].split("").reverse(),
   r = s.split(".")[1];
   t = "";
   for(i = 0; i < l.length; i ++ )
   {
      t += l[i] + ((i + 1) % 3 == 0 && (i + 1) != l.length ? "," : "");
   }
   if(typeof(r)=="undefined"){r="00";}
   return t.split("").reverse().join("") + "." + r;
}
//还原格式化数字
function rmoney(s)
{
   return parseFloat(s.replace(/[^\d\.-]/g, ""));
}

function dateTimePick()
{
	$.getScript('/zui/lib/datetimepicker/datetimepicker.min.js', function() {
	    if($.fn.datetimepicker)
	    {
		 	// 选择时间和日期
		    $(".date").datetimepicker(
		    {
		    	language:  "zh-CN",
	    	    weekStart: 1,
	    	    todayBtn:  1,
	    	    autoclose: 1,
	    	    todayHighlight: 1,
	    	    startView: 2,
	    	    minView: 2,
	    	    forceParse: 0,
	    	    format: "yyyy-mm-dd"
		    });
		    // 选择时间和日期
		    $(".date_end").datetimepicker(
		    {
		    	language:  "zh-CN",
	    	    weekStart: 1,
	    	    todayBtn:  1,
	    	    autoclose: 1,
	    	    todayHighlight: 1,
	    	    startView: 2,
	    	    minView: 2,
	    	    forceParse: 0,
	    	    format: "yyyy-mm-dd",
	    	    endDate: new Date()
		    });
		    $(".form-date").datetimepicker(
				    {
				    	language:  "zh-CN",
			    	    weekStart: 1,
			    	    todayBtn:  1,
			    	    autoclose: 1,
			    	    todayHighlight: 1,
			    	    startView: 2,
			    	    minView: 2,
			    	    forceParse: 0,
			    	    format: "yyyy-mm-dd"
				    });
		    $(".datetime").datetimepicker(
				    {
				    	language:  'zh-CN',
				        weekStart: 1,
				        todayBtn:  1,
				        autoclose: 1,
				        todayHighlight: 1,
				        startView: 2,
				        forceParse: 0,
				        showMeridian: 1,
				        format: "yyyy-mm-dd hh:ii:ss"
				    });
	    }
	});
}


//----------------------- 搜索条件checkbox -------------------------
function checkTitle() 
{
	var title_other = $("#title_other").attr("checked") == 'checked' ? $("#title_other").val() : '';
	var title_rl = $("#title_rl").attr("checked") == 'checked' ? $("#title_rl").val() : '';
	var title_rl_val = $("#title_rl").val();
	var title_cx = $("#title_cx").attr("checked") == 'checked' ? $("#title_cx").val() : '';
	var title_cx_val = $("#title_cx").val();

	if (title_other == '' && title_rl == '' && title_cx == '') return;

	var title_val = $("#title_val").val();
	if (title_other == '' && (title_val != title_rl_val && title_val != title_rl_val)) {
		$("#title_val").val("");
		$("#title_combo").val("");
		return;
	}
	if (title_rl == '' && (title_val == title_rl_val)) {
		$("#title_val").val("");
		$("#title_combo").val("");
		return;
	}
	if (title_cx == '' && (title_val == title_cx_val)) {
		$("#title_val").val("");
		$("#title_combo").val("");
		return;
	}
}

//根据销售重量获取提成价格
function getCommission(weight){
	weight = parseFloat(weight);
	if(weight >=0 && weight <= 10000){
		return weight*1;
	}
	if(weight >10000 && weight <= 20000){
		return weight*1.5;
	}
	if(weight >20000 && weight <= 30000){
		return weight*2;
	}
	if(weight >30000){
		return weight*2.5;
	}
}

Number.prototype.toFixed=function(len)
{
	var num=this;
	if(!isNaN(num))
	{
		var result=(Math.round(num*Math.pow(10,len))/Math.pow(10,len)).toString();
		if(result.indexOf('.')>-1)
			return result;
		else
			return result+'.'+'0'.repeat(len); 
	}else{
		return 'NaN';
	}	
}
String.prototype.repeat = function(n) {
    var _this = this;
    var result = '';
    for(var i=0;i<n;i++) {
        result += _this;
    }
    return result;
}


