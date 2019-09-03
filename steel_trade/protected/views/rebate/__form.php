<style>
.icon{ cursor: pointer;}
#cght_tb tbody td{ line-height: 26px;}
i.deleted_tr{ float: none; line-height: 26px; margin: 0 auto;}
</style>
<?php 
$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true, 
		'htmlOptions' => array(
				'id' => "form_data", 
				'enctype' => "multipart/form-data",
		),
));

$is_logistics = 0;
$is_gk = 0;
$is_customer = 0;

switch ($model->type) 
{
	case 'shipment': 
	case 'shipment_sale': $is_logistics = 1; break;
	case 'high': $is_gk = 1; break;
	case 'sale': $is_customer = 1; break;
	default: break;
}
?>
<div class="shop_select_box">
	<div class="shop_more_one"<?php echo $is_logistics > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>物流商：</div>
		<input type="text" id="logistics_combo" class="form-control" value="<?php echo $is_logistics > 0 ? $model->company->short_name : '';?>" readonly="readonly" />
		<input type="hidden" id="logistics_val" value="<?php echo $is_logistics > 0 ? $model->company_id : '';?>" />
	</div>
	
	<div class="shop_more_one"<?php echo $is_gk > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>高开结算单位：</div>
		<input type="text" id="gk_combo" class="form-control" value="<?php echo $is_gk > 0 ? $model->company->short_name : '';?>" readonly="readonly" />
		<input type="hidden" id="gk_val" value="<?php echo $is_gk > 0 ? $model->company_id : '';?>" />
	</div>
	
	<div class="shop_more_one"<?php echo $is_customer > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
		<div id="customer_select" class="fa_droplist">
			<input type="text" id="customer_combo" class="form-control" value="<?php echo $is_customer > 0 ? $model->company->short_name : '';?>" readonly="readonly" />
			<input type="hidden" id="customer_val" value="<?php echo $is_customer > 0 ? $model->company_id : '';?>" />
		</div>
	</div>
	<input id="FrmRebate_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="FrmRebate[company_id]">

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="title_select" class="fa_droplist">
			<input type="text" id="title_combo" class="form-control" value="<?php echo $model->title->short_name;?>" readonly="readonly" />
			<input type="hidden" id="FrmRebate_title_id" value="<?php echo $model->title_id;?>" name="FrmRebate[title_id]" />
		</div>
	</div>

	<div class="shop_more_one" style="display: none;">
		<div class="shop_more_one_l"><span class="bitian">*</span>折让类型：</div>
		<select id="FrmRebate_type" class="form-control chosen-select forreset" name="FrmRebate[type]" disabled="disabled">
			<option value="sale"<?php echo $model->type == 'sale' ? ' selected="selected"' : '';?>>销售折让</option>
			<option value="shipment"<?php echo $model->type == 'shipment' ? ' selected="selected"' : '';?>>采购运费登记</option>
			<option value="shipment_sale"<?php echo $model->type == 'shipment_sale' ? ' selected="selected"' : '';?>>销售运费登记</option>
			<option value="high"<?php echo $model->type == 'high' ? ' selected="selected"' : '';?>>高开折让</option>
		</select>
		<input id="FrmRebate_type" type="hidden" name="FrmRebate[type]" value="<?php echo $model->type;?>" />
	</div>
<?php if ($model->type != 'sale') {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>起始时间：</div>
		<input type="text" id="FrmRebate_start_at" class="form-control form-date forreset date" value="<?php echo $model->start_at ? date('Y-m-d', $model->start_at) : '';?>" name="FrmRebate[start_at]" placeholder="选择日期" readonly="readonly">
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结束时间：</div>
		<input type="text" id="FrmRebate_end_at" class="form-control form-date forreset date" value="<?php echo $model->end_at ? date('Y-m-d', $model->end_at) : '';?>" name="FrmRebate[end_at]" placeholder="选择日期" readonly="readonly">
	</div>
<?php }?>	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<select id="CommonForms_owned_by" class="form-control chosen-select" name="CommonForms[owned_by]" disabled="disabled">
		<?php foreach ($user_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->owned_by == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
		<input id="CommonForms_owned_by" type="hidden" name="CommonForms[owned_by]" value="<?php echo $baseform->owned_by;?>">
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<select id="team_select" class="form-control chosen-select" name="FrmRebate[team_id]" disabled="disabled">
			<option value="">-请选择-</option>
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>折让金额：</div>
		<input id="FrmRebate_amount" class="form-control" value="<?php echo number_format($model->amount, 2, ".", ",");?>" name="FrmRebate[amount]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="FrmRebate_comment" class="form-control" value="<?php echo $model->comment;?>" name="FrmRebate[comment]" />
	</div>
<!-- 	
	<div class="shop_more_one">
		<input type="checkbox" id="FrmRebate_is_yidan" class="check_box l" style="margin-left: 65px;"<?php #echo $model->is_yidan == 1 ? ' checked="checked"' : '';?> value="1" name="FrmRebate[is_yidan]" />
		<div class="lab_check_box">乙单</div>
	</div>
 -->
</div>

<div class="create_table">
	<table id="cght_tb" class="table"<?php echo $model->type != 'sale' ? ' style="display: none;"' : '';?>>
		<thead>
			<tr>
				<th class="text-center" style="width: 3%;"></th>
				<?php if ($baseform->form_status == 'unsubmit') {?>
				<th class="text-center" style="width: 4%;">操作</th>
				<?php }?>
				<th class="text-center" style="width: 12%;">销售单号</th>
				<th class="text-center" style="width: 7%;">状态</th>
				<th class="text-center" style="width: 10%;">开单日期</th>
				<th class="text-center" style="width: 8%;">销售公司</th>
				<th class="text-center" style="width: 8%;">客户</th>
				<th class="text-center" style="width: 7%;">总重量</th>
				<th class="text-center" style="width: 7%;">总件数</th>
				<th class="text-center" style="width: 9%;">未完成重量</th>
				<th class="text-center" style="width: 9%;">未完成件数</th>
				<th class="text-center" style="width: 9%;">销售类型</th>
				<th class="text-center" style="width: 7%;">销售员</th>
			</tr>
		</thead>
		
		<tbody>
		<?php if ($details) { 
			$tr_num = 0; 
			foreach ($details as $item) { 
				$tr_num++;?>
		<tr>
			<td class="text-center list_num"><?php echo $tr_num;?>
				<input type="hidden" name="td_id[]" class="td_id" value="<?php echo $item->id;?>" />
				<input type="hidden" name="td_sales_id[]" class="td_sales_id" value="<?php echo $item->sales_id;?>" />
			</td>
			<td class="text-center"><?php echo $item->form_sn;?></td>
			<td class="text-center"><?php echo CommonForms::$formStatus[$item->form_status];?></td>
			<td class="text-center"><?php echo date('Y-m-d', $item->created_at);?></td>
			<td class="text-center"><span title="<?php echo $item->company->name;?>"><?php echo $item->company->short_name;?></span></td>
			<td class="text-center"><span title="<?php echo $item->title->name;?>"><?php echo $item->title->short_name;?></span></td>
			<td class="text-center"><?php echo number_format($item->weight, 3);?></td>
			<td class="text-center"><?php echo number_format($item->amount);?></td>
			<td class="text-center"><?php echo number_format($item->need_weight, 3);?></td>
			<td class="text-center"><?php echo number_format($item->need_amount);?></td>
			<td class="text-center"><?php echo $item->sales_type;?></td>
			<td class="text-center"><?php echo $item->belong;?></td>
		</tr>
		<?php } }?>
		</tbody>
	</table>
</div>
<input type="hidden" name="CommonForms[form_type]" value="<?php echo $baseform->form_type;?>">
<input type="hidden" name="CommonForms[form_time]" value="<?php echo date("Y-m-d H:i:s");?>">
<input type="hidden" name="CommonForms[comment]" value="">
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />

<div class="btn_list">
	<button id="submit_btn" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget();?>

<script type="text/javascript">
var can_submit = true;

var tr_num = <?php echo $tr_num ? $tr_num : 0;?>;
var id_array = new Array(); //明细id
//关联单据基础id
var sales_id_array = new Array();
//记录原数据
var _sales_id_array = new Array(); 

//select数组
var customer_array = <?php echo $customer_array ? $customer_array : '[]';?>; //客户/is_customer
var logistics_array = <?php echo $logistics_array ? $logistics_array : '[]';?>; //物流商/is_logistics
var gk_array = <?php echo $gk_array ? $gk_array : '[]';?>; //高开结算单位is_gk
var title_array = <?php echo $title_array ? $title_array : '[]';?>; //公司抬头 

var bank_info_array = <?php echo $bank_info_array ? $bank_info_array : "[]";?>;
var dict_bank_info_array = <?php echo $dict_bank_info_array ? $dict_bank_info_array : "[]";?>;

//搜索条件
var type = "";
var company_id = 0;
var title_id = 0;
//	var is_yidan = 0;
var keywords = "";
var search_begin = "";
var search_end = "";
var owned_by = <?php echo $baseform->owned_by;?>;

//查询 
function getSimpleList(page) {
	type = $("#FrmRebate_type").val();
	if (type == 'sale') company_id = $("#customer_val").val();
	title_id = $("#FrmRebate_title_id").val();
//	is_yidan = $("#FrmRebate_is_yidan").attr("checked") == "checked" ? 1 : 0;
	keywords = $("#search_keywords").val();
	search_begin = $("#search_begin").val();
	search_end = $("#search_end").val();
//	owned_by = $("#CommonForms_owned_by").val();
	
	$.get("/index.php/rebate/getSimpleList", 
	{
		'type': type,
//		'title_id': title_id,
//		'company_id': company_id,
//		'is_yidan': is_yidan,
		'keywords': keywords, 
		'search_begin': search_begin,
		'search_end': search_end,
		'owned_by': owned_by,
		'page': page
	}, 
	function(data) {
		$("#sales_list").html(data);

		$("#sales_list table tbody .selected_sales").each(function(){
			var sales_id = $(this).val();
			if(sales_id_array.indexOf(sales_id) > -1){
				$(this).attr("checked", "checked");
			}
		});
	});
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
		'name' : "sales_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('.firstpage').attr("page");
		getSimpleList(page);
	});
}); 

//客户
$("#customer_select").click(function(){
	var customer_val = $("#customer_val").val();
	if (customer_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
	$("#FrmRebate_company_id").val(customer_val);
	getSimpleList(1);
});

//物流商
$("#logistics_select").click(function(){
	var logistics_val = $("#logistics_val").val();
	if (logistics_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
	$("#FrmRebate_company_id").val(logistics_val);
	getSimpleList(1);
});

//高开结算单位
$("#gk_select").click(function(){
	var gk_val = $("#gk_val").val();
	if (gk_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
	$("#FrmRebate_company_id").val(gk_val);
	getSimpleList(1);
});

//公司抬头
$("#title_select").click(function(){
	var title_val = $("#FrmRebate_title_id").val();
	if (title_val != title_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
	getSimpleList(1);
});

//折让类型
$("#FrmRebate_type").change(function(){
	$("#customer_select, #logistics_select, #gk_select").parent().hide();
	$(".create_table table, .search_body, #sales_list").hide();
	$("#sales_list").empty();

	sales_id_array = new Array();
	$("#cght_tb tbody").empty();
	
	switch ($(this).val()) 
	{
		case 'sale': 
			$("#customer_combo").val("");
			$("#customer_val").val("");
			$("#customer_select").parent().show();
			$(".create_table table, .search_body, #sales_list").show();
			getSimpleList(1);
			break;
		case 'shipment': 
			$("#logistics_combo").val("");
			$("#logistics_val").val("");
			$("#logistics_select").parent().show();
			break;
		case 'shipment_sale': 
			$("#logistics_combo").val("");
			$("#logistics_val").val("");
			$("#logistics_select").parent().show();
			break;
		case 'high': 
			$("#gk_combo").val("");
			$("#gk_val").val("");
			$("#gk_select").parent().show();
			break;
	}
});

//业务组
/*
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
/*
	sales_id_array = new Array();
	$("#cght_tb tbody").empty();
*/	
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
//	getSimpleList(1);
});

//搜索
$(".search_btn").click(function(){
	getSimpleList(1);
});

//重置搜索
$(".reset").click(function(){
	$(".search_body .forreset").val("");
});

//保存
$("#submit_btn").click(function(){
	if ($("#FrmRebate_type").val() == 'sale' && (!$("#customer_val").val() || $("#customer_val").val() <= 0)) 
	{
		confirmDialog("请选择客户", function(){
			$("#customer_combo").focus();
		});
		return false;
	}
	if (($("#FrmRebate_type").val() == 'shipment' || $("#FrmRebate_type").val() == 'shipment_sale') && (!$("#logistics_val").val() || $("#logistics_val").val() <= 0)) 
	{
		confirmDialog("请选择物流商", function(){
			$("#logistics_combo").focus();
		});
		return false;
	}
	if ($("#FrmRebate_type").val() == 'high' && (!$("#gk_val").val() || $("#gk_val").val() <= 0)) 
	{
		confirmDialog("请选择高开结算单位", function(){
			$("#logistics_combo").focus();
		});
		return false;
	}
	if (!$("#FrmRebate_title_id").val() || $("#FrmRebate_title_id").val() <= 0) 
	{
		confirmDialog("请选择公司", function(){
			$("#title_combo").focus();
		});
		return false;
	}
	if (!$("#FrmRebate_type").val()) 
	{
		confirmDialog("请选择折让类型", function(){
			$("#FrmRebate_type").focus();
		});
		return false;
	}
	if(!$("#FrmRebate_amount").val() || isNaN(numChange($("#FrmRebate_amount").val())) || parseFloat(numChange($("#FrmRebate_amount").val())) <= 0) 
	{
		confirmDialog("折让金额必须大于0", function(){
			$("#FrmRebate_amount").focus();
		});
		return false;
	}
	if ($("#cght_tb tbody tr").length <= 0) 
	{
		confirmDialog("请选择明细");
		return false;
	}
	if (!$("#CommonForms_owned_by").val()) 
	{
		confirmDialog("请选择业务员", function(){
			$("#CommonForms_owned_by").focus();
		});
		return false;
	};
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

<?php if ($baseform->form_status == 'unsubmit') {?>
	//select 
	$("#customer_combo").combobox(customer_array, {}, "customer_select", "customer_val", false); //客户
	$("#logistics_combo").combobox(logistics_array, {}, "logistics_select", "logistics_val", false,''); //物流商
	$("#gk_combo").combobox(gk_array, {}, "gk_select", "gk_val", false,''); //高开结算单位
	$("#title_combo").combobox(title_array, {}, "title_select", "FrmRebate_title_id", false);

	$("#bank_info_combo").combobox(bank_info_array, {}, "bank_info_select", "FrmFormBill_bank_info_id", false);
	$("#dict_bank_info_combo").combobox(dict_bank_info_array, {}, "dict_bank_info_select", "FrmFormBill_dict_bank_info_id", false);
<?php }?>
	//记录编辑前数据
	$("#cght_tb tbody tr").each(function(){
		id_array.push($(this).find(".td_id").val());
		_sales_id_array.push($(this).find(".td_sales_id").val());
		sales_id_array.push($(this).find(".td_sales_id").val());
	});

	$("#CommonForms_owned_by").change();
	getSimpleList(1);
});
</script>