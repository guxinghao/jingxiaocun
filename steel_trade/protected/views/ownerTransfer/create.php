<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<input type="hidden" value="<?php if($sales) echo $sales->sales_type;?>" id="salestype"/>
<input type="hidden" value="<?php if($sales) echo $sales->team_id;?>" id="salesteam" name="out[team_id]"/>
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<div class="ss_tt_title" style="border-bottom:1px dotted #b1b0b0;">
	<div class="shop_more_one" style="position:relative;margin:6px 40px 0 0;width:250px;">
		<div class="shop_more_one_l" style="width:75px;text-align:right;">销售单：</div>
			<input type="hidden" value="<?php if($sales) echo $sales->id;?>" name="out[frm_sales_id]" id="frm_sales_id">
			<input type="text"   id="frmsales_no" value="<?php if($sales) echo $baseform->form_sn;?>"  style="width:145px;height:30px;" readonly class="form-control con_tel">
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">公司：</div>
		<input type="text" id="title_id" value="<?php if($sales) echo $sales->dictTitle->short_name;?>" class="form-control" readonly>
		<input type="hidden" id="title" value="<?php if($sales) echo $sales->title_id;?>" class="form-control" name="out[title_id]">
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">客户：</div>
		<input type="text" id="customer_id" value="<?php if($sales) echo $sales->dictCompany->short_name;?>" class="form-control" readonly name="out[company_name]">
		<input type="hidden" id="customer" value="<?php if($sales) echo $sales->customer_id;?>" class="form-control" name="out[company_id]">
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">仓库：</div>
		<input type="text" id="warehouse_name" value="<?php if($sales) echo $sales->warehouse->name;?>" class="form-control" readonly>
		<input type="hidden" id="warehouse" value="<?php if($sales) echo $sales->warehouse_id;?>" name="out[warehouse_id]">
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">备注：</div>
		<input type="text"  class="form-control" placeholder="" name="CommonForms[comment]">
	</div>
</div>
<div class="create_table">
	<table class="table" id="ckck_tb">
		<thead>
			<tr>
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:15%;">卡号</th>
         		<th class="text-center" style="width:10%;">产地</th>
         		<th class="text-center" style="width:10%;">品名</th>
         		<th class="text-center" style="width:9%;">材质</th>
         		<th class="text-center" style="width:8%;">规格</th>
         		<th class="text-center" style="width:8%;">长度</th>
         		<th class="text-center" style="width:10%;">可转库件数</th>
         		<th class="text-center" style="width:10%;"><span class="bitian">*</span>转库件数</th>
         		<th class="text-center" style="width:15%;"><span class="bitian">*</span>转库重量</th>
			</tr>
		</thead>
		<tbody>
	
		</tbody>
	</table>
</div>

<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="save_sub">保存转库</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo $backurl;?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget();?>
<div class="caigou_body">
	<div class="search_line"></div>
	<div class="search_title">选择出库产品</div>
	<div class="search_body search_background" style="position:relative;">
		<div class="cg_search_one">
			<div class="cg_search_one_l">卡号：</div>
			<input type="text" class="form-control con_tel forreset" id="mcard_id">
		</div>
		<div class="select_body">
			<div class="shop_more_one1 short_shop_more_one">
				<div style="float:left;">产地：</div>
				<div id="brandselect" class="fa_droplist">
					<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
					<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
				</div>
			</div>
			<div class="select_body_one" >
				<div style="float:left;">品名：</div>
				<div class='col-md-4'>
		        <select name="search[product]"  class='form-control chosen-select forreset' id="mproduct">
		            <option value='0' selected='selected'>-全部-</option>
		            <?php foreach ($product as $k=>$v){?>
		            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
		            <?php }?>
		        </select>
		        </div>
			</div>
		<div class="more_select_box" style="color:#426ebb;top:48px;width:780px;">
		<div class="more_one">
			<div class="more_one_l">材质：</div>
			 <select name="search[texture]" class='form-control chosen-select forreset'  id="mtexture">
		        <option value='0' selected='selected'>-全部-</option>
		             <?php foreach ($texture as $k=>$v){?>
	            	<option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
	            	<?php }?>
		        </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">规格：</div>
			  <select name="search[rand]" class='form-control chosen-select forreset'  id="mrand">
		            <option value='0' selected='selected'>-全部-</option>
		            <?php foreach ($rank as $k=>$v){?>
		            <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
		            <?php }?>
		      </select>
		</div>
		</div>
	</div>
		<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
		<div class="more_toggle"></div>
		<img  src="<?php echo imgUrl('reset.png');?>" class="reset">
	</div>
	<div class="" id="kucun_table">
	
	</div>
</div>

<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
var droplist_num = 1;
var sales = 0;
var salestype = $("#salestype").val();
if(salestype == "dxxs"){
	sales = 1;
}
var brand = <?php echo $brand?>;
function setSales(){
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id > 0){
		getCkStorageData(1,sales,frm_sales_id);
		refreshTable("storagetable",60,0);
	}
}
$(function(){
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id > 0){
		getCkStorageData(1,sales,frm_sales_id);
		refreshTable("storagetable",60,0);
	}
})
$(document).on("change",".td_amount",function(){
	var amount = $(this).val();
	if(!/^[1-9][0-9]*$/.test(amount))
	{
		confirmDialog('件数必须是大于0的整数');
		$(this).val('');
		return false;
	}
	var weight = $(this).parent().find(".td_one_weight").val();
	var total_weight =(amount*weight).toFixed(3);
	$(this).parent().parent().find(".td_weight").val(total_weight);
})

//删除明细
$(".deleted_tr").live("click",function(){
	$(this).parent().parent().remove();
	var card_no = $(this).parent().parent().find(".td_card_id").val();
	$(".checkit").each(function(){
		var num = $(this).val();
		if(num == card_no){
			$(this).removeAttr("checked");
		}
	});
	//refreshTableStyle();
})

//每页显示发生变化
$(document).on("change","#each_page",function(){
	var limit=$(this).val();
	var frm_sales_id = $("#frm_sales_id").val();
	var url = $(this).attr('href');
	if(frm_sales_id > 0){
		$.post("/index.php/site/writeCookie", {
		'name' : "storage_list",
		'limit':limit
		}, function(data){
			getCkStorageData(1,sales,frm_sales_id);
			refreshTable("storagetable",60,0);
			setCkStorageCheck();	
		});	
	}
})
//页码点击事件
$(document).on("click",".sauger_page_a",function(){
	var page = $(this).attr("page");
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id > 0){
		getCkStorageData(page,sales,frm_sales_id);
		refreshTable("storagetable",60,0);
		setCkStorageCheck();
	}
})
//查询提交按钮
$(".btn_sub").on("click",function(){
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id > 0){
		getCkStorageData(1,sales,frm_sales_id);
		refreshTable("storagetable",60,0);
		setStorageCheck();
	}else{
		 confirmDialog("请先关联销售单");
	}
})	
$('.clear_contract').click(function(){
	$('#frm_sales_id').val(' ');
	$('#frmsales_no').val('');
	$('#title_id').html("");
	$('#customer_id').html("");
	$('#warehouse_name').html("");
	$('#warehouse').val("");
});
var can_submit = true;
$("#save").click(function(){
	var is_submit = true;
	var num = 0;
	var card_no = 0
	var warehouse = $("#warehouse").val();
	var json = "[";
	var card = "[";
	var amount_arr = "[";
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id == ""){
		confirmDialog("请选择关联销售单");return false;
	}
	$("#ckck_tb tr").each(function(){
		num++;
		if(num >1 ){
			var card_id = $.trim($(this).find(".td_card_no").val());
			if(card_id == ""){return true;}
			card_no ++;
			var product = $(this).find(".product").val();
			var rank = $(this).find(".rank").val();
			var texture = $(this).find(".texture").val();
			var brand = $(this).find(".brand").val();
			var length = Number($(this).find(".td_length").val());
			var amount = Number($(this).find(".td_amount").val());
			var weight = Number($(this).find(".td_weight").val());
			var surplus = Number($(this).find(".td_surplus").val());
			if(card_id == ''){confirmDialog("请输入第"+(num-1)+"行的卡号");is_submit = false;return false;}
			if(brand == ''){confirmDialog("请选择卡号为"+card_id+"的产地");is_submit = false;return false;}
			if(amount == ''){confirmDialog("请输入卡号为"+card_id+"的转库件数");is_submit = false;return false;}
			if(weight == ''){confirmDialog("请输入卡号为"+card_id+"的转库重量");is_submit = false;return false;}
			if(amount > surplus){confirmDialog("卡号为"+card_id+"的转库件数大于可转库件数");is_submit = false;return false;}
			str =''+product+rank+texture+brand+length;
			amount_arr += '"'+amount+'",'
			card += '"'+card_id+'",';
			json += '"'+str+'",';
		}
	})
	card=card.substring(0,card.length-1);
	json=json.substring(0,json.length-1);
	amount_arr = amount_arr.substring(0,amount_arr.length-1);
	card += ']';
	json += ']';
	amount_arr += ']';
	if(card_no > 0){
		if(is_submit){
			$.post("/index.php/frmOutput/checkInput",{"id":frm_sales_id,"json":json,"card":card,"amount":amount_arr,"warehouse":warehouse,"isTr":1},function(data){
				//confirmDialog(data);
				if(data == "success"){
					if(can_submit){
						can_submit = false;
						setTimeout(function(){can_submit = true;},3000);
						$("#form_data").submit();
					}
				}else{
					confirmDialog(data);
				}
			});
		}
	}else{
		confirmDialog("请输入出库单明细");return false;
	}
});

//保存提交
$("#save_sub").click(function(){
	$("#submit_type").val(1);
	var is_submit = true;
	var num = 0;
	var card_no = 0
	var warehouse = $("#warehouse").val();
	var json = "[";
	var card = "[";
	var amount_arr = "[";
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id == ""){
		confirmDialog("请选择关联销售单");return false;
	}
	$("#ckck_tb tr").each(function(){
		num++;
		if(num >1 ){
			var card_id = $.trim($(this).find(".td_card_no").val());
			if(card_id == ""){return true;}
			card_no ++;
			var product = $(this).find(".product").val();
			var rank = $(this).find(".rank").val();
			var texture = $(this).find(".texture").val();
			var brand = $(this).find(".brand").val();
			var length = Number($(this).find(".td_length").val());
			var amount = Number($(this).find(".td_amount").val());
			var weight = Number($(this).find(".td_weight").val());
			var surplus = Number($(this).find(".td_surplus").val());
			if(card_id == ''){confirmDialog("请输入第"+(num-1)+"行的卡号");is_submit = false;return false;}
			if(brand == ''){confirmDialog("请选择卡号为"+card_id+"的产地");is_submit = false;return false;}
			if(amount == ''){confirmDialog("请输入卡号为"+card_id+"的出库件数");is_submit = false;return false;}
			if(weight == ''){confirmDialog("请输入卡号为"+card_id+"的出库重量");is_submit = false;return false;}
			if(amount > surplus){confirmDialog("卡号为"+card_id+"的出库件数大于可出库件数");is_submit = false;return false;}
			str =''+product+rank+texture+brand+length;
			amount_arr += '"'+amount+'",'
			card += '"'+card_id+'",';
			json += '"'+str+'",';
		}
	})
	card=card.substring(0,card.length-1);
	json=json.substring(0,json.length-1);
	amount_arr = amount_arr.substring(0,amount_arr.length-1);
	card += ']';
	json += ']';
	amount_arr += ']';
	if(card_no > 0){
		if(is_submit){
			$.post("/index.php/frmOutput/checkInput",{"id":frm_sales_id,"json":json,"card":card,"amount":amount_arr,"warehouse":warehouse},function(data){
				//confirmDialog(data);
				if(data == "success"){
					if(can_submit){
						can_submit = false;
						setTimeout(function(){can_submit = true;},3000);
						$("#form_data").submit();
					}
				}else{
					confirmDialog(data);
				}
			});
		}
	}else{
		confirmDialog("请输入出库单明细");return false;
	}
});

//新增信息点击事件
$(document).on("click","#storagetable tbody tr",function(e){
	if($(this).find(".checkit").attr("checked")){
		$(this).find(".checkit").attr("checked",false);
	}else{
		$(this).find(".checkit").attr("checked",true);
	}
	var dom = $(this).find(".checkit");
	clickCheckbox(dom);
});
//复选框点击事件
$(document).on("click",".checkit",function(e){
	e.stopPropagation();    //  阻止事件冒泡
	clickCheckbox($(this));
})
function clickCheckbox(dom){
	var card_id = dom.val();
	var card_no = dom.parent().parent().find(".card_no").text();
	var cost = dom.attr("cost");
	var product = dom.parent().parent().find(".product").text();
	var rand = dom.parent().parent().find(".rand").text();
	var texture = dom.parent().parent().find(".texture").text();
	var length = dom.parent().parent().find(".length").text();
	var brand = dom.parent().parent().find(".brand").text();
	var product_std = dom.parent().find(".product_std").val();
	var rand_std = dom.parent().find(".rand_std").val();
	var texture_std = dom.parent().find(".texture_std").val();
	var brand_std = dom.parent().find(".brand_std").val();
	var surplus = dom.parent().parent().find(".surplus").text();
	var weight = dom.parent().parent().find(".canweight").text();
	var one_weight = dom.parent().find(".weight").val();
	if(dom.attr("checked")){
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
		'<td><input type="text" class="form-control td_amount"  name="amount[]"  value=""><input type="hidden" class="td_one_weight" value="'+one_weight+'"></td>'+
		'<td><input type="text" class="form-control td_weight"  name="weight[]" value=""></td>'+
	'</tr>';
		$("#ckck_tb tbody").append(newRow);
		$("#tr_num").val(count);
		//setSalesDetial(card_no,product,rand,texture,length,brand,cost);
	}else{
		deleteCkDetail(card_no);
	}
}

//删除出库单明细
function deleteCkDetail(card_no){
	$(".td_card_no").each(function(){
		var num = $(this).val();
		if(num == card_no){
			$(this).parent().parent().remove();
		}
	});
	//refreshTableStyle();
}
</script>
<script>
$(function(){
	$('#combo_brand').combobox(brand, {},"brandselect","comboval_brand");
	$('.colorbox').click(function(e){
		e.preventDefault();
		var url = $(this).attr('url');
		$.colorbox({
			href:url,
			opacity:0.6,
			iframe:true,
			title:"",
			width: "1020px",
			height: "635px",
			overlayClose: false,
			speed: 0,
		});
	});
})
</script>