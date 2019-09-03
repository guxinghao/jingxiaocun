$(function(){
	//初始化选中条数
	getCheckNum();
	$(document).on('click','.ignore_form',function(){
		var url = $(this).attr("url");
		ignoreData(url);
	});
		
	//点击选择凭证按钮
	$(document).on('click','.checkone',function(){
		toggleCheck($(this));
	})
	
	//点击全选按钮
	$(document).on('click','.checkAll',function(){
		var all = true;
		//判断此页所有单选按钮是否是全部选中状态
		$(".fixed-left .checkone").each(function(){
			if(!$(this).attr("checked")){
				all = false;
				return true;
			}
		})
		//全部选中，取消全部选中
		if(all){
			$(this).attr("checked",false);
			$(".fixed-left .checkone").each(function(){
				$(this).attr("checked",false);
				toggleCheck($(this));
			})
		}else{//非全部选中，设置全部选中
			$(this).attr("checked",true);
			$(".fixed-left .checkone").each(function(){
				if(!$(this).attr("checked")){
					$(this).attr("checked",true);
					toggleCheck($(this));
				}
			})
		}
	})
	
	//点击重置按钮
	$(".unset").click(function(){
		$.cookie("check_voucher_list",0,{path: '/'});
		$(".fixed-left .checkone").each(function(){
			$(this).attr("checked",false);
		})
		$(".checkAll").attr("checked",false);
		getCheckNum();
	});
	
	//点击返回按钮
	$(".goback").click(function(){
		$.cookie("check_voucher_list",0,{path: '/'});
	});
	//点击生成按钮
	$(".btn_submit").click(function(){
		var list = $.cookie("check_voucher_list");
		if(typeof list == "undefined" || list == 0){
			confirmDialog("请选择明细!");
			return false;
		}
		$.get("/index.php/voucher/ProcessBusinessData",{},function(data){
			if(data == "success"){
				$.cookie("check_voucher_list",0,{path: '/'});
				location.href = "/index.php/voucher";
			}else{
				confirmDialogMore(data);
			}
		});
		confirmLoading("数据正在处理中，请稍等！");
	});
	
	//忽略
	function ignoreData(url){
		var text='<div class="pop_background"></div>'+
				'<div class="check_background" id="deleted">'+
					'<div class="deleted_div"  style="height:180px;">'+
						'<div class="pop_title">忽略'+
							'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
						'</div>'+
						'<div class="check_str">您确定要忽略此条凭证吗？</div>'+
						'<div class="pop_footer">'+
							'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="submit">确定</button>'+
						'</div>'+
					'</div>'+
				'</div>';
		$("body").append(text);
		//作废提交按钮
	  	$("#submit").on("click",function(){
	  	  	$.get(url, {}, function(data){
		  	  	if (data == 'success') window.location.reload();
	  	  		else if (data) confirmDialog(data); 
	  	  		else confirmDialog("忽略失败");
	  	  	});
	  	 });
	  	$(".pop_cancle").on("click",function(){
	  		$(".pop_background").remove();
			$("#deleted").remove();
			$("#check").remove();
	  	});
	}

//点击单个复选框处理函数
	function toggleCheck(obj){
		var id = obj.val();
		var list = $.cookie("check_voucher_list");
		if(typeof list == "undefined"){
			list="0";
		}
		var arr = list.split(",");
		//选中
		if(obj.attr("checked")){
			//数组中不存在id，则增加
			if($.inArray(id, arr)<0){
				arr.push(id);
			}
		}else{
			//从数组中删除元素id
			arr.splice($.inArray(id,arr),1);
			$(".checkAll").attr("checked",false);
		}
		list = arr.join(",");
		$.cookie("check_voucher_list",list,{path: '/'});
		getCheckNum();
	}

})

//根据cookie的值，设置此页的选中情况
	function checkStatus(){
		var has = true;
		var list = $.cookie("check_voucher_list");
		if(typeof list == "undefined"){
			list="0";
		}
		var arr = list.split(",");
		$(".fixed-left .checkone").each(function(){
			var id=$(this).val();
			if($.inArray(id,arr)>0){
				$(this).attr("checked",true);
			}else{
				has = false;
				$(this).attr("checked",false);
			}
		});
		if(has){
			$(".checkAll").attr("checked",true);
		}
	}

//根据cookie的值设置选中条数
function getCheckNum(){
	var list = $.cookie("check_voucher_list");
	if(typeof list == "undefined"){
		list="0";
	}
	var arr = list.split(",");
	var length = arr.length - 1;
	$("#voucher_list_num").text(length);
	$(".voucher_num").show();
}