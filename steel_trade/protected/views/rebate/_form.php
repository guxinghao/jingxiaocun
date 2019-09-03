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
$is_vendor=0;

switch ($model->type) 
{
	case 'shipment': $is_vendor=1;break;
	case 'shipment_sale': $is_logistics = 1; break;
	case 'high': $is_gk = 1; break;
	case 'sale': $is_customer = 1; break;
	default: break;
}

?>
<div class="shop_select_box">
	<div class="shop_more_one"<?php echo $is_vendor > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
		<div id="vendors_select" class="fa_droplist">
			<input type="text" id="vendors_combo" value="<?php echo $is_vendor > 0 ? $model->company->short_name : '';?>" />
			<input type="hidden" id="vendors_val" value="<?php echo $is_vendor > 0 ? $model->company_id : '';?>" />
		</div>
	</div>

	<div class="shop_more_one"<?php echo $is_logistics >0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>物流商：</div>
		<div id="logistics_select" class="fa_droplist">
			<input type="text" id="logistics_combo" value="<?php echo $is_logistics > 0 ? $model->company->short_name : '';?>" />
			<input type="hidden" id="logistics_val" value="<?php echo $is_logistics > 0 ? $model->company_id : '';?>" />
		</div>
	</div>
	
	<div class="shop_more_one"<?php echo $is_gk > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>高开结算单位：</div>
		<div id="gk_select" class="fa_droplist">
			<input type="text" id="gk_combo" value="<?php echo $is_gk > 0 ? $model->company->short_name : '';?>" />
			<input type="hidden" id="gk_val" value="<?php echo $is_gk > 0 ? $model->company_id : '';?>" />
		</div>
	</div>
	
	<div class="shop_more_one"<?php echo $is_customer > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>结算单位：</div>
		<div id="customer_select" class="fa_droplist">
			<input type="text" id="customer_combo" value="<?php echo $is_customer > 0 ? $model->company->short_name : '';?>" />
			<input type="hidden" id="customer_val" value="<?php echo $is_customer > 0 ? $model->company_id : '';?>" />
		</div>
	</div>
	<input id="FrmRebate_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="FrmRebate[company_id]">

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="title_select" class="fa_droplist">
			<input type="text" id="title_combo" value="<?php echo $model->title->short_name;?>" />
			<input type="hidden" id="FrmRebate_title_id" value="<?php echo $model->title_id;?>" name="FrmRebate[title_id]" />
		</div>
	</div>

	<div class="shop_more_one" style="display: none;">
		<div class="shop_more_one_l"><span class="bitian">*</span>折让类型：</div>
		<select id="FrmRebate_type" class="form-control chosen-select forreset" name="FrmRebate[type]">
			<option value="sale"<?php echo $model->type == 'sale' ? ' selected="selected"' : '';?>>销售折让</option>
			<option value="shipment"<?php echo $model->type == 'shipment' ? ' selected="selected"' : '';?>>采购运费登记</option>
			<option value="shipment_sale"<?php echo $model->type == 'shipment_sale' ? ' selected="selected"' : '';?>>销售运费登记</option>
			<option value="high"<?php echo $model->type == 'high' ? ' selected="selected"' : '';?>>高开折让</option>
		</select>
	</div>
<!-- 
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>起始时间：</div>
		<input type="text" id="FrmRebate_start_at" class="form-control form-date forreset date input_backimg" value="<?php echo $model->start_at ? date('Y-m-d', $model->start_at) : '';?>" name="FrmRebate[start_at]" placeholder="选择日期">
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结束时间：</div>
		<input type="text" id="FrmRebate_end_at" class="form-control form-date forreset date input_backimg" value="<?php echo $model->end_at ? date('Y-m-d', $model->end_at) : '';?>" name="FrmRebate[end_at]" placeholder="选择日期">
	</div>
 -->
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
		<select id="team_select" class="form-control chosen-select" name="FrmRebate[team_id]" disabled="disabled">
			<option value="">-请选择-</option>
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>登记时间：</div>
		<input id="form_time" type="text" class="form-control form-date input_backimg" value="<?php echo $baseform->form_time?$baseform->form_time:date("Y-m-d");?>" placeholder="选择日期" name="CommonForms[form_time]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>折让金额：</div>
		<input id="FrmRebate_amount" class="form-control" value="<?php echo number_format($model->amount, 2, ".", ",");?>" name="FrmRebate[amount]" />
	</div>
	<div class="shop_more_one">
	<input class="check_box l" style="margin-left:90px;" type="checkbox" <?php echo $model->is_yidan==1?'checked="checked"':''?> name="FrmRebate[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<!-- <div class="shop_more_one_l">乙单：</div>
		<input id="FrmRebate_yidan" class="form-control" value="<?php echo number_format($model->amount, 2, ".", ",");?>" name="FrmRebate[amount]" />-->
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="FrmRebate_comment" class="form-control" value="<?php echo $model->comment;?>" name="FrmRebate[comment]" />
	</div>
	<div class="shop_more_one"<?php echo $is_customer > 0 ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
		<div id="customer_select9" class="fa_droplist">
			<input type="text" id="customer_combo9" value="<?php echo $is_customer > 0 ? $model->client->short_name : '';?>" />
			<input type="hidden" id="customer_val9" value="<?php echo $is_customer > 0 ? $model->client_id : '';?>" name="FrmRebate[client_id]" />
		</div>
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
				<th class="text-center" style="width: 4%;">操作</th>
				<th class="text-center" style="width: 10%;">销售单号</th>
				<th class="text-center" style="width: 7%;">状态</th>
				<th class="text-center" style="width: 8%;">开单日期</th>
				<th class="text-center" style="width: 8%;">销售公司</th>
				<th class="text-center" style="width: 8%;">结算单位</th>
				<th class="text-center" style="width: 7%;">总重量</th>
				<th class="text-center" style="width: 6%;">总件数</th>
				<th class="text-center" style="width: 9%;">未完成重量</th>
				<th class="text-center" style="width: 9%;">未完成件数</th>
				<th class="text-center" style="width: 7%;">销售类型</th>
				<th class="text-center" style="width: 6%;">销售员</th>
				<th class="text-center" style="width: 8%;">客户</th>
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
				<input type="hidden" name="td_sales_id[]" class="td_sales_id" value="<?php echo $item->sales_id;?>" />
			</td>
			<td class="text-center"><?php echo $item->form_sn;?></td>
			<td class="text-center"><?php echo CommonForms::$formStatus[$item->form_status];?></td>
			<td class="text-center"><?php echo date('Y-m-d', $item->created_at);?></td>
			<td class="text-center"><span title="<?php echo $item->title->name;?>"><?php echo $item->title->short_name;?></span></td>
			<td class="text-center"><span value="<?php echo $item->company_id;?>" title="<?php echo $item->company->name;?>"><?php echo $item->company->short_name;?></span></td>
			<td class="text-center"><?php echo number_format($item->weight, 3);?></td>
			<td class="text-center"><?php echo number_format($item->amount);?></td>
			<td class="text-center"><?php echo number_format($item->need_weight, 3);?></td>
			<td class="text-center"><?php echo number_format($item->need_amount);?></td>
			<td class="text-center"><?php echo $item->sales_type;?></td>
			<td class="text-center"><?php echo $item->belong;?></td>
			<td class="text-center"><span value="<?php echo $item->client_id;?>" title="<?php echo $item->client->name;?>"><?php echo $item->client->short_name;?></span></td>
		</tr>
		<?php } }?>
		</tbody>
	</table>
</div>
<input type="hidden" name="CommonForms[form_type]" value="<?php echo $baseform->form_type;?>">
<input type="hidden" name="CommonForms[comment]" value="">
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />

<div class="btn_list">
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	<button id="submit_btn1" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存提交</button>
<?php }?>
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget();?>
<div class="search_line"></div>

<div class="search_body search_background"<?php echo $model->type != 'sale' ? ' style="display: none;"' : '';?> style="position:relative;" >
	<div class="srarch_box">
		<img src="/images/search.png">
		<input id="search_keywords" class="forreset" placeholder="请输入单号">
	</div>
	<div class="more_one">
		<div class="more_one_l">公司抬头：</div>
		<div id="title_select1" class="fa_droplist">
			<input type="text" id="title_combo1" class="forreset" value="" />
			<input type="hidden" id="title_val1" class="forreset" value="" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">结算单位：</div>
		<div id="company_select1" class="fa_droplist">
			<input type="text" id="company_combo1" class="forreset" value="" />
			<input type="hidden" id="company_val1" class="forreset" value="" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="company_select2" class="fa_droplist">
			<input type="text" id="company_combo2" class="forreset" value="" />
			<input type="hidden" id="company_val2" class="forreset" value="" />
		</div>
	</div>
	<div class="more_select_box" style="top:<?php echo $is_customer>0?40:280;?>px;">
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
		<?php if(checkOperation('销售折让:全部')){?>
		<div class="more_one">
			<div class="more_one_l">业务员：</div>
		 	<select name="search[owned]" class='form-control chosen-select forreset owned'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($user_array as $k=>$v){?>
            	<option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<?php }?>		
	</div>
	<input type="button" data-dismiss="modal" class="btn btn-primary btn-sm btn_sub search_btn" value="查询" />
	<div class="more_toggle" title="更多"></div>
	<img class="reset" src="/images/reset.png">
</div>

<div id="sales_list"<?php echo $model->type != 'sale' ? ' style="display: none;"' : '';?>>

</div>

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
var vendor_array = <?php echo $vendor_array?$vendor_array : '[]';?>;//供应商
var gk_array = <?php echo $gk_array ? $gk_array : '[]';?>; //高开结算单位is_gk
var title_array = <?php echo $title_array ? $title_array : '[]';?>; //公司抬头 

var bank_info_array = <?php echo $bank_info_array ? $bank_info_array : "[]";?>;
var dict_bank_info_array = <?php echo $dict_bank_info_array ? $dict_bank_info_array : "[]";?>;

//搜索条件
var id = <?php echo $model->id ? $model->id : 0;?>;
var type = "";
var company_id = 0;
var title_id = 0;
//	var is_yidan = 0;
var keywords = "";
var search_begin = "";
var search_end = "";
//var owned_by = <?php echo $baseform->owned_by ? $baseform->owned_by : 0;?>;
var client_id = 0;
var customer_id = 0;
var sales_title = 0;
//查询 
function getSimpleList(page) {
	type = $("#FrmRebate_type").val();
	if (type == 'sale') company_id = $("#customer_val").val();
//	title_id = $("#FrmRebate_title_id").val();
//	is_yidan = $("#FrmRebate_is_yidan").attr("checked") == "checked" ? 1 : 0;
	keywords = $("#search_keywords").val();
	search_begin = $("#search_begin").val();
	search_end = $("#search_end").val();
	sales_title = $("#title_val1").val();
	customer_id = $("#company_val1").val();
	client_id = $("#company_val2").val();
	owned_by = $(".owned").val();	
	$.get("/index.php/rebate/getSimpleList", 
	{
		'type': type,
		'id': id,
		'sales_title': sales_title, 
		'customer_id': customer_id, 
		'client_id': client_id, 
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

//选中 
function selectedBill(checkItem) 
{
	var tr = checkItem.parent().parent();
	var sales_id = checkItem.val();
	
	if (checkItem.attr("checked") == "checked") 
	{
		switch ($("#FrmRebate_type").val()) 
		{
			case 'sale': 
				var title_name = tr.find("td").eq(5).text();
				var customer_name = tr.find("td").eq(6).children('span').attr('title');
				var customer_id=tr.find("td").eq(6).children('span').attr('value');
				var client_name = tr.find("td").eq(14).children('span').attr('title');
				var client_id=tr.find("td").eq(14).children('span').attr('value');
				var owned_by=tr.find("td").eq(13).children('span').attr('value');
				if ($("#FrmRebate_title_id").val() == 0 || $("#customer_val").val() == 0) 
				{
					for (var i = 0; i < title_array.length; i++) 
					{
						if (title_array[i].name == title_name) 
						{
							$("#title_combo").val(title_array[i].name);
							$("#FrmRebate_title_id").val(title_array[i].id);
							break;
						}
					}
					for (var i = 0; i < customer_array.length; i++) 
					{
						if (customer_array[i].name == customer_name) 
						{
							$("#customer_combo").val(customer_array[i].name);
							$("#customer_val").val(customer_array[i].id);
							$("#FrmRebate_company_id").val(customer_array[i].id);
							break;
						}
					}
					for (var i = 0; i < customer_array.length; i++) 
					{
						if (customer_array[i].id == client_id) 
						{
							$("#customer_combo9").val(customer_array[i].name);
							$("#customer_val9").val(customer_array[i].id);
							break;
						}
					}
					
				} 
				else 
				{
					if (customer_id != $("#customer_val").val() || title_name != $("#title_combo").val() || client_id != $("#customer_val9").val()) 
					{
						confirmDialog("该销售单结算单位、公司或客户与单据不一致");
						checkItem.removeAttr("checked");
						return ;
					}
					var old_owned=$('#cght_tb tbody tr:first').find('td').eq(12).children('span').attr('value');
// 					console.log(old_owned);
// 					console.log(owned_by);
					if(old_owned&&old_owned!=owned_by)
					{
						confirmDialog("该销售单业务员与单据不一致");
						checkItem.removeAttr("checked");
						return;
					}			
				}
				$('#CommonForms_owned_by').val(owned_by);
				break;
			default: 
				break;
		}
		var yidan = $.trim(tr.find("td").eq(11).html());
		//if(yidan !="是"){confirmDialog("您选择了甲单");}
		tr_num++;
		var id = "";
		var data = '<tr>' + 
			'<td class="text-center list_num">' + tr_num + '</td>' + 
			'<td class="text-center">' + 
				'<i class="icon icon-trash deleted_tr"></i>'; 
				if(_sales_id_array.indexOf(sales_id) > -1){
					id = id_array[_sales_id_array.indexOf(sales_id)];
				}
				data += '<input type="hidden" name="td_id[]" class="td_id" value="' + id + '" />' + 
				'<input type="hidden" name="td_sales_id[]" class="td_sales_id" value="' + sales_id + '" />' +
			'</td>' + 
			'<td class="text-center">' + tr.find("td").eq(2).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(3).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(4).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(5).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(6).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(7).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(8).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(9).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(10).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(12).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(13).html() + '</td>' + 
			'<td class="text-center">' + tr.find("td").eq(14).html() + '</td>' + 
		'</tr>';
		sales_id_array.push(sales_id);
		$("#cght_tb tbody").append(data);
	}
	else 
	{
		$("#cght_tb tbody tr").each(function(){
			if($(this).find(".td_sales_id").val() == sales_id){
				$(this).remove();
				tr_num = 0;
				$("#cght_tb tbody .list_num").each(function(){
					tr_num++;
					$(this).html(tr_num);
				});
				return sales_id;
			}
		});
	}
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

function vendorSelect()
{
	var vendor_val = $("#vendors_val").val();
	$("#FrmRebate_company_id").val(vendor_val);
}
//供应商
$("#vendors_select").click(function(){
	var vendor_val = $("#vendors_val").val();
/*
	if (vendor_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
*/
	$("#FrmRebate_company_id").val(vendor_val);
});


//客户
$("#customer_select").click(function(){
	var customer_val = $("#customer_val").val();
/*
	if (customer_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
*/
	$("#FrmRebate_company_id").val(customer_val);
//	getSimpleList(1);
});

//物流商
$("#logistics_select").click(function(){
	var logistics_val = $("#logistics_val").val();
/*
	if (logistics_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
*/
	$("#FrmRebate_company_id").val(logistics_val);
//	getSimpleList(1);
});

//高开结算单位
$("#gk_select").click(function(){
	var gk_val = $("#gk_val").val();
/*
	if (gk_val != company_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
*/
	$("#FrmRebate_company_id").val(gk_val);
//	getSimpleList(1);
});

//公司抬头
$("#title_select").click(function(){
	var title_val = $("#FrmRebate_title_id").val();
/*
	if (title_val != title_id) 
	{
		sales_id_array = new Array();
		$("#cght_tb tbody").empty();
	}
*/
//	getSimpleList(1);
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
			$("#vendors_combo").val("");
			$("#vendors_val").val("");
			$("#vendors_select").parent().show();
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

//checkbox 点击不选中
$(".selected_sales").live("click", function(){
	if ($(this).attr("checked") == "checked") $(this).removeAttr("checked");
	else $(this).attr("checked", "checked");
});

//选中
$("#sales_list tbody td").live("click", function(){
	var checkItem = $(this).parent().find(".selected_sales");
	if (checkItem.attr("checked") == "checked") checkItem.removeAttr("checked");
	else checkItem.attr("checked", "checked");
	selectedBill(checkItem);
});

//删除
$(".deleted_tr").live("click", function(){
	var sales_id = $(this).parent().parent().find(".td_sales_id").val();
	$(this).parent().parent().remove();
	sales_id_array.splice(sales_id_array.indexOf(sales_id), 1);
	$("#sales_list table tbody tr").each(function(){
		if($(this).find(".selected_sales").val() == sales_id){
			$(this).find(".selected_sales").removeAttr("checked");
		}
	});
	tr_num = 0;
	$("#cght_tb tbody .list_num").each(function(){
		tr_num++;
		$(this).html(tr_num);
	});
});

//保存
$("#submit_btn, #submit_btn1").click(function(){
	var type = $("#FrmRebate_type").val();

	if (!$("#FrmRebate_company_id").val() || $("#FrmRebate_company_id").val() <= 0) 
	{
		switch (type) 
		{
			case 'sale': 
				confirmDialog("结算单位", function(){
					$("#customer_combo").focus();
				});
				return false;
				break;
			case 'shipment': 
				confirmDialog("请选择供应商", function(){
					$("#vendors_combo").focus();
				});
				return false;
				break;
			case 'shipment_sale': 
				confirmDialog("请选择物流商", function(){
					$("#logistics_combo").focus();
				});
				return false;
				break;
			case 'high': 
				confirmDialog("请选择高开结算单位", function(){
					$("#logistics_combo").focus();
				});
				return false;
				break;
			default: 
				break;
		}
	}

	if (!$("#FrmRebate_title_id").val() || $("#FrmRebate_title_id").val() <= 0) 
	{
		confirmDialog("请选择公司", function(){
			$("#title_combo").focus();
		});
		return false;
	}

	if(type == 'sale' && $("#customer_val9").val() <= 0){
		confirmDialog("请选择客户", function(){
			$("#customer_combo9").focus();
		});
		return false;
	}
	
	switch (type) 
	{
		case 'sale':
			var customer_name = $("#customer_combo").val();
			var customer_id=$("#customer_val").val();
			var client_id=$("#customer_val9").val();
			var title_name = $("#title_combo").val();
			if ($("#cght_tb tbody tr").length > 0) 
			{
				var check_customer = false;
				var check_title = false;
				var check_client = false;
				$("#cght_tb tbody tr").each(function(){
					var thisid=$(this).find("td").eq(6).children('span').attr('value');
					if (customer_id == thisid) {
						check_customer = true;
					}else{
						check_customer=false;
						return false;
					}
					if (title_name == $(this).find("td").eq(5).text()) {
						check_title = true;
					}else{
						check_title=false;
						return false;
					}
					if (client_id == $(this).find("td").eq(13).children('span').attr('value')) {
						check_client = true;
					}else{
						check_client=false;
						return false;
					}
				});
				if (!check_customer) 
				{
					confirmDialog("选中销售单中结算单位与单据不一致");
					return false;
				} 
				if (!check_client) 
				{
					confirmDialog("选中销售单中客户与单据不一致");
					return false;
				} 
				else if (!check_title) 
				{
					confirmDialog("选中销售单中公司与单据不一致");
					return false;
				}
			}
			break;
		default: 
			break;
	}
/*
	if (!$("#FrmRebate_type").val()) 
	{
		confirmDialog("请选择折让类型", function(){
			$("#FrmRebate_type").focus();
		});
		return false;
	}
*/
	if(!$("#FrmRebate_amount").val() || isNaN(numChange($("#FrmRebate_amount").val()))) 
	{
		confirmDialog("折让金额必须是数字", function(){
			$("#FrmRebate_amount").focus();
		});
		return false;
	}
/*
	if (!$("#FrmRebate_start_at").val()) 
	{
		confirmDialog("请选择起始时间", function(){
			$("#FrmRebate_start_at").focus();
		});
		return false;
	}
	if (!$("#FrmRebate_end_at").val()) 
	{
		confirmDialog("请选择结束时间", function(){
			$("#FrmRebate_end_at").focus();
		});
		return false;
	}
*/
	
	if (type!='shipment'&&$("#cght_tb tbody tr").length <= 0) 
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

	if ($(this).attr("id") == 'submit_btn1') $(this).parent().append('<input type="hidden" name="CommonForms[submit]" value="yes">');
	if (can_submit) 
	{
		can_submit = false;
		notAnymore('submit_btn');
		notAnymore('submit_btn1');
		$("#form_data").submit();
	}
});

$(function(){
<?php if ($msg) {?>
confirmDialog("<?php echo $msg;?>");
<?php }?>

	//select 
	$("#customer_combo").combobox(customer_array, {}, "customer_select", "customer_val", false); //客户
	$("#customer_combo9").combobox(customer_array, {}, "customer_select9", "customer_val9", false); //客户
	$("#logistics_combo").combobox(logistics_array, {}, "logistics_select", "logistics_val", false,''); //物流商
	$("#vendors_combo").combobox(vendor_array, {}, "vendors_select", "vendors_val", false,'vendorSelect()'); //供应商
	$("#gk_combo").combobox(gk_array, {}, "gk_select", "gk_val", false,''); //高开结算单位
	$("#title_combo").combobox(title_array, {}, "title_select", "FrmRebate_title_id", false);

	$("#bank_info_combo").combobox(bank_info_array, {}, "bank_info_select", "FrmFormBill_bank_info_id", false);
	$("#dict_bank_info_combo").combobox(dict_bank_info_array, {}, "dict_bank_info_select", "FrmFormBill_dict_bank_info_id", false);
	$("#title_combo1").combobox(title_array, {}, "title_select1", "title_val1", false);
	$("#company_combo1").combobox(customer_array, {}, "company_select1", "company_val1", false,'');
	$("#company_combo2").combobox(customer_array, {}, "company_select2", "company_val2", false,'');
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
