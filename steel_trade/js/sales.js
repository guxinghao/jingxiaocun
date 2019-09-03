function refreshTable(id,Leftwidth,RightWidth){
	$('#'+id).datatable({
   	 fixedLeftWidth:Leftwidth,
   	 fixedRightWidth:RightWidth,
     });
}
//销售单获取库存列表
function getStorageData(page,sales){
	var card_no = $("#mcard_id").val();
	var rand_std = $("#mrand").val();
	var product_std = $("#mproduct").val();
	var texture_std = $("#mtexture").val();
	var brand_std = $("#comboval_brand").val();
	var warehouse_id = Number($("#comboval3").val());
	$.get("/index.php/storage/salist",
			{"card_no":card_no,"page":page,"rand_std":rand_std,
			"product_std":product_std,"texture_std":texture_std,
			"brand_std":brand_std,"warehouse_id":warehouse_id,"sales":sales},
		function(data){
		$("#kucun_table").html(data);
		setStorageCheck();
		//refreshTable("storagetable",60,0);
	})
}

//销售单获取全部库存列表
function getAllStorageData(page){
	var type = $("#mtype").val();
	var title_id = $("#combval21").val();
	var warehouse_id = $("#comboval31").val();
	var rand_std = $("#mrand").val();
	var product_std = $("#mproduct").val();
	var texture_std = $("#mtexture").val();
	var brand_std = $("#comboval_brand").val();
	var length = $("#mlength").val();
	$.get("/index.php/mergeStorage/salist",
			{"page":page,"rand_std":rand_std,"type":type,"title_id":title_id,"warehouse_id":warehouse_id,
			"product_std":product_std,"texture_std":texture_std,
			"brand_std":brand_std,"length":length},
		function(data){
			$("#kucun_table").html(data);
			setStorageCheck();
			//refreshTable("storagetable",60,0);
			//设置导航栏高度
		 	var height = $(document).height() - 42;
		 	$(".bar").css("height",height+"px");
		})
}
//设置销售单库存单状态选择
function setStorageCheck(){
	var num_no = new Array();
	$(".td_card_id").each(function(){
		var num = $(this).val();
		num_no.push(num);
	});
	$(".checkit").each(function(){
		var card_no = $(this).val();
		for(i=0;i<num_no.length;i++){
			if(num_no[i] == card_no){
				$(this).attr("checked","true");
			}
		}
	})
}

//代销销售单获取库存列表
function getDxStorageData(page){
	var card_no = $("#mcard_id").val();
	var supply = $("#gyscomboval").val();
	var product_std = $("#mproduct").val();
	var warehouse = $("#comboval31").val();
	var brand_std = $("#comboval_brand").val();
	var texture_std = $("#mtexture").val();
	var rand_std = $("#mrand").val();
	var length = $("#mlength").val();
	$.get("/index.php/storage/dxlist",
			{"card_no":card_no,"page":page,"supply":supply,
			"product_std":product_std,"warehouse":warehouse,
			"brand_std":brand_std,"texture_std":texture_std,"rand_std":rand_std,"length":length},
		function(data){
		$("#kucun_table").html(data);
		setDxStorageCheck();
		//设置导航栏高度
	 	var height = $(document).height() - 42;
	 	$(".bar").css("height",height+"px");
	})
}
//设置代销销售单库存单状态选择
function setDxStorageCheck(){
	var num_no = new Array();
	$(".td_num").each(function(){
		var num = $(this).val();
		num_no.push(num);
	});
	$(".checkit").each(function(){
		var card_no = $(this).val();
		for(i=0;i<num_no.length;i++){
			if(num_no[i] == card_no){
				$(this).attr("checked","true");
			}
		}
	})
}

//出库单获取库存列表
function getCkStorageData(page,sales,sales_id){
	var card_no = $("#mcard_id").val();
	var rand_std = $("#mrand").val();
	var product_std = $("#mproduct").val();
	var texture_std = $("#mtexture").val();
	var brand_std = $("#comboval_brand").val();
	var warehouse_id = $("#warehouse").val();
	var ware_id = $("#ware_id").val();
	$.get("/index.php/storage/cklist",
			{"card_no":card_no,"rand_std":rand_std,"page":page,
			"product_std":product_std,"texture_std":texture_std,
			"brand_std":brand_std,"warehouse_id":warehouse_id,
			"sales":sales,"sales_id":sales_id,"ware_id":ware_id},
		function(data){
		$("#kucun_table").html(data);
		setCkStorageCheck();
	})
}

//转单获取库存列表
function getZkStorageData(page){
	var card_no = $("#mcard_id").val();
	var rand_std = $("#mrand").val();
	var product_std = $("#mproduct").val();
	var texture_std = $("#mtexture").val();
	var brand_std = $("#comboval_brand").val();
	var warehouse_id = $("#warehouse").val();
	var ware_id = $("#ware_id").val();
	$.get("/index.php/storage/zklist",
			{"card_no":card_no,"page":page,"rand_std":rand_std,
			"product_std":product_std,"texture_std":texture_std,
			"brand_std":brand_std,"warehouse_id":warehouse_id,
			"ware_id":ware_id},
		function(data){
		$("#kucun_table").html(data);
		setCkStorageCheck();
		
	})
}

//设置出库单库存状态选择
function setCkStorageCheck(){
	var num_no = new Array();
	$(".td_card_id").each(function(){
		var num = $(this).val();
		num_no.push(num);
	});
	$(".checkit").each(function(){
		var card_no = $(this).val();
		for(i=0;i<num_no.length;i++){
			if(num_no[i] == card_no){
				$(this).attr("checked","true");
			}
		}
	})
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
//业务员发生变化，修改业务组
function changeOwnerTT()
{
	var owner=$('#CommonForms_owned_by').val();
	$.get('/index.php/contract/getUserTeam',{
		'owner':owner,
	},function(data){
		$('#team_id').val(data);
		$("#team_idnum").val(data);
	});
}

//采购退货获取全部库存列表
function getPReturnStorageData(page){
	var title_id = $("#combval21").val();
	var warehouse_id = $("#comboval31").val();
	var rand_std = $("#mrand").val();
	var product_std = $("#mproduct").val();
	var texture_std = $("#mtexture").val();
	var brand_std = $("#comboval_brand").val();
	var length = $("#mlength").val();
	$.get("/index.php/storage/preturnlist",
			{"title_id":title_id,"warehouse_id":warehouse_id,
			"page":page,"rand_std":rand_std,
			"product_std":product_std,"texture_std":texture_std,
			"brand_std":brand_std,"length":length},
		function(data){
			$("#kucun_table").html(data);
			setPreturnStorageCheck();
			//refreshTable("storagetable",60,0);
		})
}

//采购退货设置出库单库存状态选择
function setPreturnStorageCheck(){
	var num_no = new Array();
	$(".td_card_id").each(function(){
		var num = $(this).val();
		num_no.push(num);
	});
	$(".checkit").each(function(){
		var card_no = $(this).attr("card_id");
		for(i=0;i<num_no.length;i++){
			if(num_no[i] == card_no){
				$(this).attr("checked","true");
			}
		}
	})
}

function getsales(page)
{
	var sales = $("#search").val();
	var mtime_l = $("#mtime_l").val();
	var mtime_h = $("#mtime_h").val();
	var title = $("#combval").val();
	var custome = $("#comboval").val();
	var team = $("#mteam").val();
	var warehouse = $("#comboval3").val();
	$.post("/index.php/frmSales/saleslist",{
		"sales":sales,"mtime_l":mtime_l,"mtime_h":mtime_h,"page":page,
		"title":title,"custome":custome,"team":team,"warehouse":warehouse,
	},function(data){
		$("#kucun_table").html(data);
	})
}

//设置件数合计
function setTotalamount(){
	var total_amount = 0;
	$(".td_shop_num").each(function(){
		var num = Number(numChange($(this).val()));
		total_amount += num;
	});
	$(".total_amount").text(total_amount);
}
//设置重量合计
function setTotalweight(){
	var total_weight = 0;
	$(".td_shop_total").each(function(){
		var num = Number(numChange($(this).val()));
		total_weight += num;
	});
	total_weight = fmoney(total_weight,3);
	$(".total_weight").text(total_weight);
}
//设置金额合计
function setTotalprice(){
	var total_price = 0;
	$(".td_price").each(function(){
		var num = Number(numChange($(this).val()));
		total_price += num;
	});
	total_price = fmoney(total_price,2);
	$(".total_price").text(total_price);
}

//销售重量发生变化
$(document).on('change','.td_shop_total',function(){
	var that=$(this);
	var td_shop_total=numChange($(this).val());
	if(!/^[0-9]+(.[0-9]{1,3})?$/.test(td_shop_total) || td_shop_total == 0)
	{
		confirmDialog('销售重量必须是大于0且小数点后只有3位的正数');
		$(this).val('');
		return false;
	}
	setTotalweight();
	td_shop_total=parseFloat(td_shop_total);
	var td_money=$(this).parent().parent().find('.td_money').val();
	var total;
	if(td_money==''){return false;}
	td_money = Number(numChange(td_money)); 
	$(this).parent().parent().find('.td_price').val(fmoney(td_money*td_shop_total,2));
	setTotalprice();
});

//销售单价发生变化
$(document).on('change','.td_money',function(){
		var zero = $("#can_zero").val();
		var that=$(this);
		var td_money=numChange($(this).val());
		var td_shop_total=parseFloat(numChange($(this).parent().parent().find('.td_shop_total').val()));
		var total;
		if(zero == 1){
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_money))
			{
				confirmDialog('销售单价必须是大于等于0且小数点后只有2位的正数');
				$(this).val('');
				return false;
			}
		}else{
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_money) || td_money == 0)
			{
				confirmDialog('销售单价必须是大于0且小数点后只有2位的正数');
				$(this).val('');
				return false;
			}
		}
		td_money=parseFloat(td_money);
		$(this).parent().parent().find('.td_price').val(fmoney(td_money*td_shop_total,2));
		setTotalprice();
	});
	//高开价发生变化
	$(document).on('change','.td_gaok',function(){
		var that=$(this);
		var gaokai= Number($(this).val());
		if(!/^[0-9]+(.[0-9]{1,2})?$/.test(gaokai) && gaokai != 0)
		{
			confirmDialog('高开价格必须是大于等于0且小数点后只有2位的正数');
			$(this).val('');
			return false;
		}
	});
	//金额发生变化
	$(document).on('change','.td_price',function(){
		var money = Number($(this).val());
		if(!/^[0-9]+(.[0-9]{1,2})?$/.test(money) && money != 0)
		{
			confirmDialog('金额必须是大于等于0且小数点后只有2位的正数');
			$(this).val('');
			return false;
		}
		setTotalprice();
	})
// $(function(){
// 	//销售单导出
// 	$(".sales_export").click(function(){
// 		$(".card_no1").val($("#srarch").val());
// 		$(".start_time1").val($(".start_time").val());
// 		$(".end_time1").val($(".end_time").val());
// 		$(".title_id1").val($("#combval").val());
// 		$(".customer_id1").val($("#comboval").val());
// 		$(".form_status1").val($(".form_status").val());
// 		$(".sales_type1").val($(".sales_type").val());
// 		$(".is_yidan1").val($(".is_yidan").val());
// 		$(".team1").val($(".team").val());
// 		$(".owned1").val($(".owned").val());
// 		$(".brand1").val($("#comboval_brand").val());
// 		$(".product1").val($(".product").val());
// 		$(".rank1").val($(".rank").val());
// 		$(".texture1").val($(".texture").val());
// 		$(".warehouse1").val($(".warehouse").val());
// 		$(".export_post").submit();
// 	})
	
// })

function sendMessage(tel,str,id){
	var html='<div class="dialogbody_send">'+
		'<div class="pop_background"></div>'+
		'<div class="check_background" id="check">'+
			'<div class="check_div">'+
				'<div class="pop_title l" style="background:#fff;">填写手机号'+
					'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
				'</div>'+
				'<div class="check_str l" style="background:#fff;">'+
					'<div class="send_one" style="margin-top:15px;">'+
						'<div class="send_one_l">手机号：</div><div class="send_one_r"><input type="text" class="send_phone" value="'+tel+'"></div>'+
					'</div>'+
					'<div class="send_one">'+
						'<div class="send_one_l">短信内容：</div><div class="send_one_r">'+str+'</div>'+
					'</div>'+
				'</div>'+
				'<div class="pop_footer l" style="background:#fff;width:100%;">'+
					'<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancelIt">取消</button>'+
					'<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="IKnowIt">确定</button>'+
				'</div>'+
			'</div>'+
		'</div>'+
	'</div>';
	$("body").append(html);
	$("#IKnowIt").on("click",function(e){
		var phone = $(".send_phone").val();
		if(phone == ""){confirmDialog("请输入手机号");}
		else if(isNaN(phone) || phone.length != 11){
			confirmDialog("您输入的不是正确的手机号");
		}else{
			//post到发送短信接口
			$.post("/index.php/frmSend/sendMsg",{"phone":phone,"str":str,"id":id},function(data){
				$(".dialogbody_send").remove();
				if(data == "success"){confirmDialog("发送成功");}
				else{confirmDialog("发送失败");}
			});
		}
	});
	
	$("#cancelIt").on("click",function(){
		$(".dialogbody_send").remove();
	})
	//点击取消按钮
	$(".pop_cancle").on("click",function(){
		$(".dialogbody_send").remove();
	});
}

function clickCheckbox1(dom,need_amount,need_weight){
	var card_id = dom.find(".checkit").val();
	var card_no = dom.find(".card_no").text();
	var cost = dom.find(".checkit").attr("cost");
	var product = dom.find(".product").text();
	var rand = dom.find(".rand").text();
	var texture = dom.find(".texture").text();
	var length = dom.find(".length").text();
	var brand = dom.find(".brand").text();
	var product_std = dom.find(".product_std").val();
	var rand_std = dom.find(".rand_std").val();
	var texture_std = dom.find(".texture_std").val();
	var brand_std = dom.find(".brand_std").val();
	var surplus = dom.find(".surplus").text();
	var weight = dom.find(".canweight").text();
	var one_weight = dom.find(".weight").val();
	
	
	var count=parseInt($("#tr_num").val()) + 1;
	var newRow = '';
	var yu = count % 2;
	if(yu == 0)
	{
		newRow = '<tr class="selected">';
	}else{
		newRow = '<tr class="">';
	}
	
	newRow = '<tr class="">';
	newRow +='<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>'+
		'<td class="text-center card_id"><input type="text" class="form-control td_card_no" value="'+card_no+'" readonly><input class="td_card_id" type="hidden" name="card_id[]" value="'+card_id+'"></td>'+
		'<td class=""><input type="text" class="form-control td_place" value="'+brand+'" readonly><input type="hidden" class="brand" name="brand[]" value="'+brand_std+'"></td>'+
		'<td class=""><input type="text" class="form-control td_product" value="'+product+'" readonly><input type="hidden" class="product" name="product[]" value="'+product_std+'"></td>'+
		'<td class=""><input type="text" class="form-control td_material" value="'+texture+'" readonly><input type="hidden" class="texture" name="texture[]" value="'+texture_std+'"></td>'+
		'<td class=""><input type="text" class="form-control td_type"  value="'+rand+'" readonly><input type="hidden" class="rank" name="rank[]" value="'+rand_std+'"></td>'+
		'<td class=""><input type="text" class="form-control td_length" name="length[]" value="'+length+'" readonly></td>'+
		'<td class=""><input type="text" class="form-control td_surplus" value="'+surplus+'" readonly></td>'+
		'<td><input type="text" class="form-control td_amount"  name="amount[]"  value="'+need_amount+'"><input type="hidden" class="td_one_weight" value="'+one_weight+'"></td>'+
		'<td><input type="text" class="form-control td_weight"  name="weight[]" value="'+need_weight+'"></td>'+
	'</tr>';
	$("#ckck_tb tbody").append(newRow);
	$("#tr_num").val(count);
	dom.find(".checkit").attr("checked","true");
	//setSalesDetial(card_no,product,rand,texture,length,brand,cost);
}

function setWareOut(){
	$("#storagetable tbody tr").each(function(){
		var product = $(this).find(".product_std").val();
		var rank = $(this).find(".rand_std").val();
		var texture = $(this).find(".texture_std").val();
		var brand = $(this).find(".brand_std").val();
		var length = Number($(this).find(".length").text());
		var surplus = Number($(this).find(".surplus").text());
		str =''+brand+product+texture+rank+length;
		if($("#"+str).length>0){
			var amount = Number($("#"+str).val());
			var weight = $("#"+str).attr("weight");
			if(surplus >= amount && amount>0){
				clickCheckbox1($(this),amount,weight);
				$("#"+str).val(0);
			}
		}
	
	})
}

//公告提示是否操作方法
function confirmCarNo(text){
	var str='<div class="dialogbody">'+
				'<div class="pop_background"></div>'+
				'<div class="check_background" id="check">'+
					'<div class="check_div">'+
						'<div class="pop_title">车牌号'+
							'<span class="pop_cancle"><i class="icon icon-times"></i></span>'+
						'</div>'+
						'<div class="check_str">'+text+'</div>'+
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
	})
}

//客户发生变化
function changeCont()
{
	var vendor_id=$('#comboval').val();
	var vendor_name=$('#combo').val();
	if(Number($('#cuscomboval').val())== 0){
		$('#cuscomboval').val(vendor_id);
		$('#cuscombo').val(vendor_name);
	}
	$.get('/index.php/contract/getVendorCont',{
		'vendor_id':vendor_id,
	},function(data){
		var data1=data.substring(0,data.indexOf('o1o'));
		var data2=data.substring(data.indexOf('o1o')+3);
		$('#contact_id').html(data1);
		$('#phone').val(data2);
	});
}
//业务员发生变化
function changeTeamU()
{
	var team_id= $('#comboval4').val();
	$.get('/index.php/contract/getTeamUser',{
		'team_id':team_id,
		},function(data){
			$('#CommonForms_owned_by').html(data);
		});	
}