<style>
<!--
.icon{ cursor: pointer;}
#cght_tb tbody td{ line-height: 30px;}
#cght_tb tbody td input{ height: 30px; line-height: 22px;}
.weight, .fee{ width: 90px; text-align: right;}
#invoice_list{ float: left; width: 100%;}
.more_one{ width: 210px;}
.more_one_l{ width: auto;}
i.deleted_tr{ float: none; line-height: 30px; margin: 0 auto;}
/* .c_comment{ width: 555px;} */
/* #CommonForms_comment{ width: 425px;} */
-->
</style>

<?php 
$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true, 
		'htmlOptions' => array(
				'id' => "form_data", 
				'enctype' => "multipart/form-data",
		),
));
?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>收票单位：</div>
		<input id="title_name" type="text" class="form-control" value="<?php echo $model->title->short_name;?>" readonly="readonly" />
		<input type="hidden" id="FrmPurchaseInvoice_title_id" value="<?php echo $model->title_id;?>" name="FrmPurchaseInvoice[title_id]" />
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开票单位：</div>
		<input type="text" id="company_name" class="form-control" value="<?php echo $model->company->name;?>" readonly="readonly" />
		<input type="hidden" id="FrmPurchaseInvoice_company_id" value="<?php echo $model->company_id;?>" name="FrmPurchaseInvoice[company_id]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>销票重量：</div>
		<input type="text" id="FrmPurchaseInvoice_weight" class="form-control" value="<?php echo number_format($model->weight, 3, ".", ",");?>" name="FrmPurchaseInvoice[weight]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>销票金额：</div>
		<input type="text" id="FrmPurchaseInvoice_fee" class="form-control" value="<?php echo number_format($model->fee, 2, ".", ",");?>" name="FrmPurchaseInvoice[fee]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<select id="CommonForms_owned_by" class="form-control chosen-select" name="CommonForms[owned_by]">
		<?php foreach ($user_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->owned_by == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<select id="team_select" class="form-control chosen-select" disabled="disabled">
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->belong->team_id == $key ? ' selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
		<input id="FrmPurchaseInvoice_team_id" type="hidden" name="FrmPurchaseInvoice[team_id]" />
	</div>
	<div class="shop_more_one ">
		<div class="shop_more_one_l"><span class="bitian">*</span>登记日期：</div>
		<input id="CommonForms_form_time" type="text" class="form-control date" name="CommonForms[form_time]" value="<?php echo $model->baseform->form_time?$model->baseform->form_time:date('Y-m-d',time());?>">
	</div>
	<div class="shop_more_one c_comment">
		<div class="shop_more_one_l">备注：</div>
		<input id="CommonForms_comment" type="text" class="form-control" name="CommonForms[comment]" value="<?php echo $model->baseform->comment;?>">
	</div>
</div>

<div class="create_table">
	<table id="cght_tb" class="table">
		<thead>
			<tr>
				<th class="text-center" style="width: 3%;"></th>
				<th class="text-center" style="width: 4%;">操作</th>
				<th class="text-center" style="width: 9%;">单号</th>
				<th class="text-center" style="width: 8%;">公司</th>
				<th class="text-center" style="width: 8%;">供应商</th>
				<th class="text-center" style="width: 8%;">托盘公司</th>
				<th class="text-center" style="width: 16%;">产地/品名/材质/规格/长度</th>
				<th class="text-center" style="width: 9%;">销票重量</th>
				<th class="text-center" style="width: 9%;">销票金额</th>
				<th class="text-center" style="width: 9%;">可销票重量</th>
				<th class="text-center" style="width: 9%;">可销票金额</th>
				<th class="text-center" style="width: 8%;">业务员</th>
			</tr>
		</thead>
		
		<tbody>
		<?php if ($details) { 
			$tr_num = 0; 
			foreach ($details as $item) { 
				$tr_num++;?>
			<tr>
				<td class="text-center list_num"><?php echo $tr_num;?></td>
				<td class="text-center">
					<i class="icon icon-trash deleted_tr"></i>
					<input type="hidden" name="td_id[]" class="td_id" value="<?php echo $item->id;?>" />
					<input type="hidden" name="td_invoice_id[]" class="td_invoice_id" value="<?php echo $item->purchase_detail_id;?>" />
				</td>
				<td class="text-center"><?php echo $item->form_sn;?></td>
				<td class="text-center"><span title="<?php echo $item->title->name;?>"><?php echo $item->title->short_name;?></span></td>
				<td class="text-center"><span title="<?php echo $item->company->name;?>"><?php echo $item->company->name;?></span></td>
				<td class="text-center"><span title="<?php echo $item->pledgeCompany->name;?>"><?php echo $item->pledgeCompany->name;?></span></td>
				<td class="text-center"><?php echo $item->brand.'/'.$item->product_name.'/'.str_replace('E', '<span class="red">E</span>', $item->texture).'/'.$item->rank.'/'.$item->length;?></td>
				<td class="text-center"><input type="text" class="form-control weight" value="<?php echo number_format($item->weight, 3, ".", ",");?>" name="weight[]" /></td>
				<td class="text-center"><input type="text" class="form-control fee" value="<?php echo number_format($item->fee, 2, ".", ",");?>" name="fee[]" /></td>
				<td class="text-center"><?php echo number_format($item->needWeight + $item->weight, 3, ".", ",");?></td>
				<td class="text-center"><?php echo number_format($item->needMoney + $item->fee, 2, ".", ",");?></td>
				<td class="text-center"><?php echo $item->belong->nickname;?></td>
			</tr>
		<?php } }?>
		</tbody>
	</table>
</div>
<input type="hidden" name="CommonForms[form_type]" value="CGXP">
<!--  <input type="hidden" name="CommonForms[form_time]" value="<?php echo date("Y-m-d H:i:s");?>"> -->
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />

<div class="btn_list">
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget();?>
<div class="search_line"></div>
<div class="search_title">可销票信息</div>

<div class="search_body search_background">
	<div class="srarch_box">
		<img src="/images/search.png">
		<input id="search_keywords" class="forreset" placeholder="请输入单号">
	</div>
<!-- 	
	<div class="search_date">
		<div style="float: left;">日期：</div>
		<div class="search_date_box">
			<input type="text" id="search_begin" class="form-control form-date forreset date" value="" placeholder="开始日期">
		</div>
		<div style="float: left; margin: 0 3px;">至</div>
		<div class="search_date_box">
			<input type="text" id="search_end" class="form-control form-date forreset date" value="" placeholder="结束日期">
		</div>
	</div>
 -->
 	<div class="more_one">
 		<div class="more_one_l">公司：</div>
 		<div id="title_select" class="fa_droplist">
			<input type="text" id="title_combo" class="forreset" value="<?php echo $model->title->short_name;?>" />
			<input type="hidden" id="title_val" class="forreset" value="<?php echo $model->title_id;?>" />
		</div>
 	</div>
 	
 	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="company_select" class="fa_droplist">
			<input type="text" id="company_combo" class="forreset" value="<?php echo $model->company->name;?>" />
			<input type="hidden" id="company_val" class="forreset" value="<?php echo $model->company_id;?>" />
		</div>
	</div>
	
	<input type="button" data-dismiss="modal" class="btn btn-primary btn-sm btn_sub search_btn" value="查询" />
	<img class="reset" src="/images/reset.png">
</div>

<div id="invoice_list">
	<h3 style="text-align: center; color: #999;">请选择收票单位和开票单位</h3>
</div>

<script type="text/javascript">
var can_submit = true;

var tr_num = <?php echo $tr_num ? $tr_num : 0;?>;
var id_array = new Array(); //明细id 
//关联单据基础id
var invoice_id_array = new Array();
//记录原数据
var _invoice_id_array = new Array();
var _weight_array = new Array();
var _fee_array = new Array();

//select数组 
var company_array = <?php echo $company_array ? $company_array : '[]';?>; //客户 
var title_array = <?php echo $title_array ? $title_array : '[]';?>; //公司抬头 

//查询条件 
var company_id = 0;
var title_id = 0;
var keywords = "";
var search_begin = "";
var search_end = "";
var type = "";

//计算总重、总金额 
function getTotal() 
{
	var total_weight = 0;
	var total_fee = 0;
	$("#cght_tb tbody tr").each(function(){
		total_weight += $(this).find(".weight").val() ? (parseFloat(numChange($(this).find(".weight").val()))*1000) : 0;
		total_fee += $(this).find(".fee").val() ? (parseFloat(numChange($(this).find(".fee").val()))*1000) : 0;
	});
	$("#FrmPurchaseInvoice_weight").val(numberFormat(total_weight/1000, 3, '.', ','));
	$("#FrmPurchaseInvoice_fee").val(numberFormat(total_fee/1000, 2, '.', ','));
}

//查询 
function getSimpleList(page) 
{
	company_id = $("#company_val").val();
	title_id = $("#title_val").val();
	keywords = $("#search_keywords").val();
	search_begin = $("#search_begin").val();
	search_end = $("#search_end").val();
	type = $("#search_type").val();
	owned_by = $("#CommonForms_owned_by").val();

//	if (company_id == 0 || title_id == 0) return ;
	$.get("/index.php/purchaseInvoice/getSimpleList", 
	{
		'id': <?php echo $baseform->id ? $baseform->id : 0?>,
		'company_id': company_id,
		'title_id': title_id,
		'keywords': keywords, 
		'search_begin': search_begin,
		'search_end': search_end,
		'type': type, 
		'page': page,
		'owned_by': owned_by,
	}, 
	function(data){
		$("#invoice_list").html(data);
		$("#invoice_list table tbody .selected_invoice").each(function(){
			var invoice_id = $(this).val();
			if (invoice_id_array.indexOf(invoice_id) > -1) 
				$(this).attr("checked", "checked");
		});
	});
}

//选中 
function selectedBill(checkItem) 
{
	var tr = checkItem.parent().parent();
	var invoice_id = checkItem.val();
	var first_one=false;
	if (checkItem.attr("checked") == "checked") 
	{
		//自动带出公司抬头 
		for (var i = 0; i < title_array.length; i++) 
		{
			if (title_array[i].name == tr.find("td").eq(3).text()) 
			{
				if ($("#FrmPurchaseInvoice_company_id").val() == 0 || $("#FrmPurchaseInvoice_title_id").val() == 0) /* || invoice_id_array.length == 0)*/ 
				{
					first_one=true;
					$("#title_name").val(title_array[i].name);
					$("#FrmPurchaseInvoice_title_id").val(title_array[i].id);
				} 
				$("#title_combo").val(title_array[i].name);
				$("#title_val").val(title_array[i].id);
				break;
			}
		}
		for (var i = 0; i<company_array.length; i++) 
		{
			var company_txt = "";
			if (tr.find("td").eq(5).text() != '') company_txt = tr.find("td").eq(5).text();
			else company_txt = tr.find("td").eq(4).text();
			if (company_array[i].name == company_txt) 
			{
				if ($("#FrmPurchaseInvoice_company_id").val() == 0 || $("#FrmPurchaseInvoice_title_id").val() == 0 || invoice_id_array.length == 0) 
				{
					$("#company_name").val(company_array[i].name);
					$("#FrmPurchaseInvoice_company_id").val(company_array[i].id);
				}
				$("#company_combo").val(company_array[i].name);
				$("#company_val").val(company_array[i].id);
				break;
			}
		}
		var owned_name = tr.find("td:last").text();
		$("#CommonForms_owned_by option").each(function() {
			if ($(this).text() == owned_name) $(this).attr("selected", "selected");
		});
		invoice_id_array.push(invoice_id);
		
		tr_num++;
		var id = "";
		var weight = (parseFloat( numChange(tr.find("td").eq(9).text()) )*1000 - parseFloat( numChange(tr.find("td").eq(7).text()) )*1000)/1000;
		var fee = (parseFloat( numChange(tr.find("td").eq(10).text()) )*1000 - parseFloat( numChange(tr.find("td").eq(8).text()) )*1000)/1000;
		var data = '<tr>' + 
			'<td class="text-center list_num">' + tr_num + '</td>' + 
			'<td class="text-center">' + 
				'<i class="icon icon-trash deleted_tr"></i>'; 
				var id_index = _invoice_id_array.indexOf(invoice_id);
				if(id_index > -1){
					id = id_array[id_index];
					weight += parseFloat(_weight_array[id_index]);
					fee += parseFloat(_fee_array[id_index]);
				}
				data += '<input type="hidden" name="td_id[]" class="td_id" value="' + id + '" />' + 
				'<input type="hidden" name="td_invoice_id[]" class="td_invoice_id" value="' + invoice_id + '" />' +
			'</td>' + 
			'<td class="text-center">' + tr.find("td").eq(2).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(3).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(4).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(5).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(6).html() + '</td>' + 
			'<td class="text-center">' + '<input type="text" name="weight[]" class="form-control weight" value="' + numberFormat(weight, 3, '.', ',') + '" />' + '</td>' + 
			'<td class="text-center">' + '<input type="text" name="fee[]" class="form-control fee" value="' + numberFormat(fee, 2, '.', ',') + '" />' + '</td>' + 
			'<td class="text-center">' + numberFormat(weight, 3, '.', ',') + '</td>' + 
			'<td class="text-center">' + numberFormat(fee, 2, '.', ',') + '</td>' +
			'<td class="text-center">' + tr.find("td:last").html() + '</td>' +
		'</tr>';
		$("#cght_tb tbody").append(data);
		if(first_one)getSimpleList(1);
	} 
	else 
	{
		$("#cght_tb tbody tr").each(function(){
			if ($(this).find(".td_invoice_id").val() == invoice_id) 
			{
				$(this).remove();
				tr_num = 0;
				$("#cght_tb tbody .list_num").each(function(){
					tr_num++;
					$(this).html(tr_num);
				});
			}
		});
		delete invoice_id_array[invoice_id_array.indexOf(invoice_id)];
	}
	getTotal();
}

//换页获取数据
$(document).on("click", ".sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getSimpleList(page);
});

$(document).on("change", ".paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getSimpleList(page);
});

$(document).on("change", "#each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "invoice_list",
		'limit': limit
	}, 
	function(data) {
		var page = $('.firstpage').attr("page");
		getSimpleList(page);
	});
});

$("#company_select").click(function(){
	var company_val = $("#company_val").val();
	if (company_val != company_id && company_val != $("#FrmPurchaseInvoice_company_id").val()) 
	{
		invoice_id_array = new Array();
		$("#cght_tb tbody").empty();
		$("#FrmPurchaseInvoice_company_id").val(company_val);
		$("#company_name").val($("#company_combo").val());
		getSimpleList(1);
	}
});

$("#title_select").click(function(){
	var title_val = $("#title_val").val();
	if (title_val != title_id && title_val != $("#FrmPurchaseInvoice_title_id").val()) 
	{
		invoice_id_array = new Array();
		$("#cght_tb tbody").empty();
		$("#FrmPurchaseInvoice_title_id").val(title_val);
		$("#title_name").val($("#title_combo").val());
		getSimpleList(1);
	}
});
/*
//业务组
$("#team_select").change(function(){
	var team_val = $(this).val();
	$.get("/index.php/team/getUsers", 
	{
		'team_id': team_val
	}, 
	function(data) 
	{
		$("#CommonForms_owned_by").html(data);
	});
});
*/
//业务员
$("#CommonForms_owned_by").change(function(){
	var owned_by_val = $(this).val();
	$.get("/index.php/user/getTeam", 
	{
		'user_id': owned_by_val
	}, 
	function(data) 
	{
		if (data) $("#team_select").find("option:contains('" + data + "')").attr("selected", "selected");
		else $("#team_select").find("option[value='']").attr("selected", "selected");
		$("#team_select").change(function(){
			$("#CommonForms_owned_by").val(owned_by_val);
		});
	});
	$("#FrmPurchaseInvoice_team_id").val($("#team_select").val());

	invoice_id_array = new Array();
	$("#cght_tb tbody").empty();
	getSimpleList(1);
});

//搜索
$(".search_btn").click(function(){
	invoice_id_array = new Array();
	$("#cght_tb tbody").empty();
	getTotal();
	getSimpleList(1);
});

//重置搜索
$(".reset").click(function(){
	$(".search_body .forreset").val("");
});

//checkbox 点击不选中
$(".selected_invoice").live("click", function(){
	if ($(this).attr("checked") == "checked") $(this).removeAttr("checked");
	else $(this).attr("checked", "checked");
});

//选中 
$("#invoice_list tbody td").live("click", function(){
	var checkItem = $(this).parent().find(".selected_invoice");
	if (checkItem.attr("checked") == "checked") checkItem.removeAttr("checked");
	else checkItem.attr("checked", "checked");
	selectedBill(checkItem);
});

//删除
$(".deleted_tr").live("click", function(){
	var invoice_id = $(this).parent().parent().find(".td_invoice_id").val();
	$(this).parent().parent().remove();
	invoice_id_array.splice(invoice_id_array.indexOf(invoice_id), 1);
	$("#invoice_list table tbody tr").each(function(){
		if($(this).find(".selected_invoice").val() == invoice_id){
			$(this).find(".selected_invoice").removeAttr("checked");
		}
	});
	tr_num = 0;
	$("#cght_tb tbody .list_num").each(function(){
		tr_num++;
		$(this).html(tr_num);
	});
	getTotal();
});

$("#cght_tb .weight").live("change", function(){
	var weight = $(this).val() && !isNaN(numChange($(this).val())) && parseFloat(numChange($(this).val())) >= 0 ? parseFloat(numChange($(this).val())) : 0;
	var max_weight = parseFloat(numChange($(this).parent().next().next().text()));
	var max_fee = parseFloat(numChange($(this).parent().next().next().next().text()));
	
	if (weight > max_weight) 
	{
		confirmDialog("销票重量不能大于可销票重量");
		weight = max_weight;
	}
	$(this).val(numberFormat(weight, 3, '.', ','));
	
	var price = max_weight > 0 ? (max_fee / max_weight) : max_fee;
	var fee = weight > 0 ? weight * price : price;
	$(this).parent().parent().find(".fee").val(numberFormat(fee, 2, '.', ','));
	getTotal();
});

$("#cght_tb .fee").live("change", function(){
	var change = false;
	var fee = $(this).val() && !isNaN(numChange($(this).val())) && parseFloat(numChange($(this).val()));
	var max_fee = parseFloat( numChange($(this).parent().next().next().text()) );
	//采购退货
	if(max_fee < 0){
		if(fee > 0){
			confirmDialog("可销票金额必须是负数");
			fee = max_fee;
		}else{
			if (fee < max_fee) 
			{
				confirmDialog("销票金额不能大于可销票金额");
				fee = max_fee;
			}
		}
	}
	//采购单
	if(max_fee >= 0){
		if(fee<0){
			confirmDialog("可销票金额必须是正数");
			fee = max_fee;
		}else{
			if (fee > max_fee) 
			{
				confirmDialog("销票金额不能大于可销票金额");
				fee = max_fee;
			}
		}
	}
	$(this).val(numberFormat(fee, 2, '.', ','));
	getTotal();
});

//保存
$("#submit_btn").click(function(){
/*
	if (!$("#FrmPurchaseInvoice_title_id").val() || $("#FrmPurchaseInvoice_title_id").val() == 0) 
	{
		confirmDialog("请选择收票单位", function(){
			$("#title_combo").focus();
		});
		return false;
	}
	if (!$("#FrmPurchaseInvoice_company_id").val() || $("#FrmPurchaseInvoice_company_id").val() == 0) 
	{
		confirmDialog("请选择开票单位", function(){
			$("#company_combo").focus();
		});
		return false;
	}
*/
	if ($("#cght_tb tbody .td_invoice_id").length == 0) 
	{
		confirmDialog("请选择单据");
		return false;
	}

	var check_company = true;
	var check_title = true;
	var company_name = $("#company_name").val();
	var title_name = $("#title_name").val();
	$("#cght_tb tbody tr").each(function(){
		if (title_name != $(this).find("td").eq(3).text()) check_title = false;
		if ($(this).find("td").eq(5).text()) company_txt = $(this).find("td").eq(5).text();
		else company_txt = $(this).find("td").eq(4).text();
		if (company_name != company_txt) check_company = false;
	});
	if (!check_title) 
	{
		confirmDialog("选中单据中收票单位不一致");
		return false;
	}
	if (!check_company) 
	{
		confirmDialog("选中单据中开票单位不一致");
		return false;
	}
	
	var is_submit = true;
	$("#cght_tb tbody tr").each(function(){
		var weight = numChange($(this).find(".weight").val());
		var fee = numChange($(this).find(".fee").val());
		if(!weight || isNaN(weight)) 
		{
			confirmDialog("请填写重量");
			$(this).find(".weight").focus();
			is_submit = false;
			return false;
		}
		else if(!fee || isNaN(fee)) 
		{
			confirmDialog("请填写金额");
			$(this).find(".fee").focus();
			is_submit = false;
			return false;
		}
	});
	if (!is_submit) return false;
	/*
	if (parseFloat( numChange($("#FrmPurchaseInvoice_fee").val()) ) <= 0) 
	{
		confirmDialog("销票总金额必须大于0");
		$("#FrmPurchaseInvoice_fee").focus();
		return false;
	}
	*/
	if (!$("#CommonForms_owned_by").val()) 
	{
		confirmDialog("请选择业务员");
		$("#CommonForms_owned_by").focus();
		return false;
	}
	if (!$("#CommonForms_form_time").val()) 
	{
		confirmDialog("请选择登记时间");
		return false;
	}

	if (can_submit) 
	{
		can_submit = false;
		notAnymore('submit_btn');
		$("#form_data").submit();
	}
});

$(function(){
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>

	//select 
	$("#company_combo").combobox(company_array, {}, "company_select", "company_val", false,'');
	$("#title_combo").combobox(title_array, {}, "title_select", "title_val", false);

	//记录编辑前数据
	$("#cght_tb tbody tr").each(function(){
		id_array.push($(this).find(".td_id").val());
		invoice_id_array.push($(this).find(".td_invoice_id").val());
		_invoice_id_array.push($(this).find(".td_invoice_id").val());
		_weight_array.push(numChange($(this).find(".weight").val()));
		_fee_array.push(numChange($(this).find(".fee").val()));
	});

//	$("#CommonForms_owned_by").change();
	getSimpleList(1);
});

</script>
