<style>
<!--
.icon{ cursor: pointer;}
#cght_tb tbody td{ line-height: 26px;}
#bill_list, #turnover_body{ float: left; width: 100%;}
.search_line{ height: 2px;}
i.deleted_tr{ float: none; line-height: 26px; margin: 0 auto;}
-->
</style>
<?php
switch ($type) 
{
	case 'FKDJ': 
		$title = "付款";
		break;
	case 'SKDJ': 
		$title = "收款";
		break;
	default: 
		break;
}

$is_supply = in_array($model->bill_type, array('CGFK', 'TPSH', 'DLFK', 'CGTH', 'GCFL', 'BZJ'));
$is_logistics = in_array($model->bill_type, array('YF'));
$is_customer = in_array($model->bill_type, array('XSTH', 'XSZR', 'GKZR', 'XSSK'));
$is_gk = in_array($model->bill_type, array('GKFK'));
$has_warehouse = in_array($model->bill_type, array('CCFY', 'CKFL'));

$has_yidan = in_array($model->bill_type, array('CGFK', 'XSSK', 'XSTH', 'CGTH'));
$is_relation = in_array($model->bill_type, array('CGFK', 'XSSK', 'CCFY', 'CKFL', 'GCFL', 'BZJ')) ? false : true;
$has_turnover = in_array($model->bill_type, array('CGFK', 'BZJ'));
$has_ownedSales = in_array($model->bill_type, array('XSSK'));

$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true, 
		'htmlOptions' => array(
				'id' => 'form_data', 
				'enctype' => 'multipart/form-data'
		)
));
?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结算单位：</div>
		<span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span>
	</div>
	<input type="hidden" id="FrmFormBill_company_id" value="<?php echo $model->company_id;?>" name="FrmFormBill[company_id]">
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">结算账户：</div>
		<div id="bank_info_select" class="fa_droplist">
			<input type="text" id="bank_info_combo" value="<?php echo $model->bankInfo ? $model->bankInfo->company_name.'('.$model->bankInfo->bank_number.')' : '';?>">
			<input type="hidden" id="FrmFormBill_bank_info_id" value="<?php echo $model->bank_info_id;?>" name="FrmFormBill[bank_info_id]">
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span><?php echo $title;?>类型：</div>
 		<span><?php echo FrmFormBill::$billTypes[$model->bill_type];?></span>
 		<input id="FrmFormBill_bill_type" type="hidden" value="<?php echo $model->bill_type;?>" name="FrmFormBill[bill_type]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span><?php echo $title;?>方式：</div>
		<select id="FrmFormBill_pay_type" class="form-control chosen-select" name="FrmFormBill[pay_type]" >
		<?php foreach (FrmFormBill::$payTypes as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $model->pay_type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span>
		<input type="hidden" id="FrmFormBill_title_id"  value="<?php echo $model->title_id;?>" name="FrmFormBill[title_id]"/>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司账户：</div>
		<div id="dict_bank_info_select" class="fa_droplist">
			<input type="text" id="dict_bank_info_combo" value="<?php echo $model->dictBankInfo ? $model->dictBankInfo->dict_name.'('.$model->dictBankInfo->bank_number.')' : '';?>" />
			<input type="hidden" id="FrmFormBill_dict_bank_info_id" value="<?php echo $model->dict_bank_info_id;?>" name="FrmFormBill[dict_bank_info_id]">
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<select id="CommonForms_owned_by" class="form-control chosen-select" name="CommonForms[owned_by]" disabled="disabled">
		<?php foreach ($user_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->owned_by == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
		<input type="hidden" value="<?php echo $baseform->owned_by;?>" name="CommonForms[owned_by]">
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<select id="team_select" class="form-control chosen-select" disabled="disabled">
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="shop_more_one" id="pledge_company"<?php echo in_array($model->bill_type, array('DLFK', 'DLSK')) ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>托盘公司：</div>
		<span title="<?php echo $model->pledgeCompany->name;?>"><?php echo $model->pledgeCompany->short_name;?></span>
		<input type="hidden" id="FrmFormBill_pledge_company_id"  value="<?php echo $model->pledge_company_id;?>" name="FrmFormBill[pledge_company_id]"/>
	</div>
	
	<div class="shop_more_one" id="pledge_bank_info"<?php echo in_array($model->bill_type, array('DLFK', 'DLSK')) ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l">托盘账户：</div>
		<div id="pledge_bank_info_select" class="fa_droplist">
			<input type="text" id="pledge_bank_info_combo" value="<?php echo in_array($model->bill_type, array('DLFK', 'DLSK')) ? ($model->pledgeBankInfo ? $model->pledgeBankInfo->company_name.'('.$model->pledgeBankInfo->bank_number.')' : '') : '';?>" />
			<input type="hidden" id="FrmFormBill_pledge_bank_info_id" value="<?php echo in_array($model->bill_type, array('DLFK', 'DLSK')) ? $model->pledge_bank_info_id : '';?>" name="FrmFormBill[pledge_bank_info_id]" />
		</div>
	</div>
	<?php if($is_customer || $is_gk){?>
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
				<span title="<?php echo $model->client->name;?>"><?php echo $model->client->short_name;?></span>
			</div>
		<input type="hidden" id="FrmFormBill_client_id" value="<?php echo $model->client_id;?>" name="FrmFormBill[client_id]">
	
	<?php }?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>登记日期：</div>
		<input id="CommonForms_created_at" type="text" class="form-control form-date forreset date input_backimg" name="CommonForms[form_time]" value="<?php echo $baseform->form_time?$baseform->form_time:'';?>" placeholder="登记日期" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">到账日期：</div>
		<input id="FrmFormBill_reach_at" type="text" class="form-control form-date forreset date input_backimg" name="FrmFormBill[reach_at]" value="<?php echo date('Y-m-d', $model->reach_at > 0 ? $model->reach_at : time());?>" placeholder="选择日期" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>总金额：</div>
		<input type="text" id="FrmFormBill_fee" class="form-control" value="<?php echo number_format($model->fee, 2, ".", ",");?>" name="FrmFormBill[fee]" readonly="readonly" />
		<input type="hidden" id="FrmFormBill_weight" class="form-control" value="<?php echo number_format($model->weight, 3, ".", ",");?>" name="FrmFormBill[weight]" />
	</div>
	
<?php if ($baseform->form_type == 'FKDJ') {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">用途：</div>
		<input id="FrmFormBill_purpose" type="text" class="form-control" value="<?php echo $model->purpose;?>" name="FrmFormBill[purpose]" />
	</div>
<?php }?>

	<div class="shop_more_one"<?php echo $has_yidan ? '' : ' style="display: none;"';?>>
		<input type="checkbox" id="FrmFormBill_is_yidan" class="check_box l" style="margin-left: 130px;" value="1" disabled="disabled"<?php echo $model->is_yidan == 1 ? ' checked="checked"' : '';?>>
		<input type="hidden" value="<?php echo $model->is_yidan;?>" name="FrmFormBill[is_yidan]" />
		<div class="lab_check_box">乙单</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="hidden" id="CommonForms_form_type" value="<?php echo $type;?>" name="CommonForms[form_type]">
		<!-- <input type="hidden" id="CommonForms_form_time" value="<?php echo date("Y-m-d H:i:s");?>" name="CommonForms[form_time]"> -->
		<input type="text" class="form-control" id="CommonForms_comment" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]">
	</div>
</div>

<div class="create_table">
	<table id="cght_tb" class="table"<?php echo $is_relation ? '' : ' style="display: none;"';?>>
		<thead>
		
		</thead>
		
		<tbody>
		<?php if ($relations) { 
			$tr_num = 0; 
			foreach ($relations as $each) { 
				$tr_num++;
		?>
			<tr>
				<td class="text-center list_num"><?php echo $tr_num;?></td>
				<td class="text-center" style="display: none;">
					<i class="icon icon-trash deleted_tr"></i>
					<input type="hidden" name="td_id[]" class="td_id" value="<?php echo $each->id;?>" />
					<input type="hidden" name="td_common_id[]" class="td_common_id" value="<?php echo $each->common_id;?>" />
				</td>
				<td class="text-center"><?php echo $each->common->form_sn;?></td>
				<td class="text-center"><?php echo $each->common->created_at ? date('Y-m-d', $each->common->created_at) : '';?></td>
		<?php 
		switch ($model->bill_type) 
		{
			case 'CGFK': //采购付款
			case 'DLFK': //代理付款
				$relation_data = $each->common->purchase;
				$_type = array('normal' => "库存采购", 'tpcg' => "托盘采购", 'xxhj' => "直销采购", 'dxcg' => "代销采购");
		?>
				<td class="text-center"><span title="<?php echo $relation_data->supply->name;?>"><?php echo $relation_data->supply->short_name;?></span></td>
				<td class="text-center"><?php echo number_format($relation_data->weight, 3, ".", ",");?></td>
				<td class="text-center"><?php echo number_format($relation_data->price_amount, 2, ".", ",");?></td>
				<td class="text-center"><?php echo $has_yidan ? ($relation_data->is_yidan == 1 ? '是' : '否') : '';?></td>
				<td class="text-center"><span title="<?php echo $relation_data->pledge->pledgeCompany->name;?>" class="pledge_company"><?php echo $relation_data->pledge->pledgeCompany->short_name;?></span></td>
				<td class="text-center"><?php echo $_type[$relation_data->purchase_type];?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'TPSH': //托盘赎回
				$relation_data = $each->common->pledgeRedeem;
		?>
				<td class="text-center"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->short_name;?></span></td>
				<td class="text-center"><?php echo DictGoodsProperty::getProName($relation_data->brand_id);?></td>
				<td class="text-center"><?php echo DictGoodsProperty::getProName($relation_data->product_id);?></td>
				<td class="text-center"><?php echo number_format($relation_data->total_fee, 2, ".", ",");?></td>
				<td class="text-center">	<?php echo number_format($relation_data->interest_fee, 2, ".", ",");?></td>
				<td class="text-center">	<?php echo $relation_data->purchase->baseform->form_sn;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'CGTH': //采购退货收款
				$relation_data = $each->common->purchaseReturn;
		?>
				<td class="text-center"><span title="<?php echo $relation_data->supply->name;?>"><?php echo $relation_data->supply->short_name;?></span></td>
				<td class="text-center"><span title="<?php echo $relation_data->warehouse->name;?>"><?php echo $relation_data->warehouse->name;?></span></td>
				<td class="text-center"><?php echo $relation_data->travel;?></td>
				<td class="text-center"><?php echo date('Y-m-d', $relation_data->return_data);?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSSK': //销售收款
				$relation_data = $each->common->sales;
				$_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
		?>
				<td class="text-center"><span title="<?php echo $relation_data->dictCompany->name;?>"><?php echo $relation_data->dictCompany->short_name;?></span></td>
				<td class="text-center">	<?php echo number_format($relation_data->weight, 3);?></td>
				<td class="text-center"><?php echo $relation_data->amount;?></td>
				<td class="text-center"><?php echo $relation_data->is_yidan == 1 ? '是' : '否';?></td>
				<td class="text-center"><?php echo $_type[$relation_data->sales_type];?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSTH': //销售退货付款
				$relation_data = $each->common->salesReturn;
				$details=$relation_data->salesReturnDetails;
				foreach ($details as $li)
				{
					$total+=$li->fix_weight*$li->fix_price;
				}
		?>
				<td class="text-center"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->short_name;?></span></td>
				<td class="text-center"><?php echo number_format($total,2);?>
				<td class="text-center"><span title="<?php echo $relation_data->warehouse->name;?>"><?php echo $relation_data->warehouse->name;?></span></td>
				<td class="text-center"><?php echo $relation_data->travel;?>
				<td class="text-center"><?php echo $relation_data->return_date?date('Y-m-d', $relation_data->return_date):'';?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
				<td class="text-center"><span title="<?php echo $relation_data->client->name;?>"><?php echo $relation_data->client->short_name;?></span></td>
		<?php 
				break;
			case 'XSZR': //销售折让
				$relation_data = $each->common->rebate;
				$_type = array('sale' => "销售折让", 'shipment' => "采购运费登记", 'shipment_sale' => "销售运费登记", 'high' => "高开折让");
		?>
				<td class="text-center"><span title="<?php echo $relation_data->company->name?>"><?php echo $relation_data->company->short_name?></span></td>
				<td class="text-center"><?php echo number_format($relation_data->amount, 2);?></td>
				<td class="text-center"><?php echo $relation_data->is_yidan == 1 ? "是" : "否";?></td>
				<td class="text-center"><?php echo $_type[$relation_data->type];?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'GKFK': //高开付款
				$relation_data = $each->common->highopen;
				$sales_detail = $relation_data->salesDetail;
				$product_info = DictGoodsProperty::getProName($sales_detail->brand_id) . "/" . 
						DictGoodsProperty::getProName($sales_detail->product_id) . "/" .
						str_replace('E', '<span class="red">E</span>', DictGoodsProperty::getProName($sales_detail->texture_id)) . "/" .
						DictGoodsProperty::getProName($sales_detail->rank_id) . "/" .
						$sales_detail->length;
		?>
				<td class="text-center"><?php echo $relation_data->sales->baseform->form_sn;?></td>
				<td class="text-center"><?php echo $product_info;?></td>
				<td class="text-center"><?php echo $sales_detail->amount;?></td>
				<td class="text-center"><?php echo number_format($sales_detail->weight, 3);?></td>
				<td class="text-center"><?php echo number_format($relation_data->price);?></td>
				<td class="text-center">
					<span class="real_fee"><?php echo number_format($relation_data->real_fee, 2);?></span>
					<input class="discount" type="hidden" name="discount[]" value="<?php echo $relation_data->discount;?>">
				</td>
				<td class="text-center"><?php echo $relation_data->is_pay == 1 ? "已付款" : "未付款";?></td>
				<td class="text-center"><?php echo $baseform->belong->nickname;?></td>
				<td class="text-center"><span title="<?php echo $relation_data->client->name;?>"><?php echo $relation_data->client->short_name;?></span></td>
		<?php 
				break;
			case 'YF': 
				$relation_data = $each->common->billRecord;
		?>
				<td class="text-center"><span title="<?php echo $relation_data->title->name;?>"><?php echo $relation_data->title->short_name;?></span></td>
				<td class="text-center"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->short_name;?></span></td>
				<td class="text-center"><?php echo number_format($relation_data->weight, 3);?></td>
				<td class="text-center"><?php echo number_format($relation_data->price);?></td>
				<td class="text-center"><?php echo number_format($relation_data->amount, 2);?></td>
				<td class="text-center"><?php echo $relation_data->travel;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			default: 
				break;
		}
		?>
			</tr>
		<?php } }?>
		</tbody>
	</table>
</div>

<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />
<div class="btn_list">
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">入账</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<div class="search_line" style="display: none;"></div>
<div id="bill_list" style="display: none;">
	<h3 style="text-align: center; color: #999">请选择结算单位和公司</h3>
</div>

<script type="text/javascript">
<!--
var can_submit = true;

var tr_num = <?php echo $tr_num ? $tr_num : 0;?>; //序号
var id_array = new Array(); //明细id
var _common_id_array = new Array(); //关联单据基础id
var common_id_array = new Array();

var is_supply = <?php echo $is_supply ? 1 : 0;?>;
var is_logistics = <?php echo $is_logistics ? 1 : 0;?>;
var is_customer = <?php echo $is_customer ? 1 : 0;?>;
var is_gk = <?php echo $is_gk ? 1 : 0;?>;
var has_warehouse = <?php echo $has_warehouse ? 1 : 0;?>;

var has_yidan = <?php echo $has_yidan ? 1 : 0;?>;
var is_relation = <?php echo $is_relation ? 1 : 0;?>;
var has_turnover = <?php echo $has_turnover ? 1 : 0;?>;
var has_ownedSales = <?php echo $has_ownedSales ? 1 : 0;?>;

//查询条件
var type = "";
var company_id = 0;
var title_id = 0;
var is_yidan = '';
var pledge_company_id = 0;
var keywords = "";
var begin_time = "";
var end_time = "";

//查询
function getBillList(page) {
	company_id = $("#FrmFormBill_company_id").val();
	title_id = $("#FrmFormBill_title_id").val();
	type = $("#FrmFormBill_bill_type").val();
	is_yidan = $("#FrmFormBill_is_yidan").attr("checked") == 'checked' ? 1 : 0;
	pledge_company_id = $("#FrmFormBill_pledge_company_id").val();
	keywords = $("#search_keywords").val();
	begin_time = $("#search_begin").val();
	end_time = $("#search_end").val();
	
	if (is_relation == 0 || company_id == 0 || title_id == 0) return;
	$.get('/index.php/formBill/getBillSimpleList', 
	{
		'id': <?php echo $baseform ? $baseform->id : 0;?>,
		'type': type,
		'company_id': company_id,
		'title_id': title_id,
		'is_yidan': is_yidan,
		'pledge_company_id': pledge_company_id,
		'keywords': keywords,
		'time_L': begin_time,
		'time_H': end_time,
		'page': page
	}, 
	function(data){ 
		$('#bill_list').html(data);
		$("#cght_tb thead").html($("#bill_list thead").html());
		$("#cght_tb thead th").addClass("text-center");
		$("#cght_tb thead th").eq(1).hide();
		//
		$("#bill_list tbody .selected_bill").each(function(){
			var common_id = $(this).val();
			if (common_id_array.indexOf(common_id) > -1) $(this).attr("checked", "checked");
		});
	});
}
//获取结算账户
function getBankList(id) 
{
	$.get("/index.php/bankInfo/getBankList", 
	{
		'id': id
	}, 
	function(data) 
	{
		bank_info_array = data ? data : [];
		$("#bank_info_select").html('<input type="text" id="bank_info_combo" value=""><input type="hidden" id="FrmFormBill_bank_info_id" value="" name="FrmFormBill[bank_info_id]">');
		$("#bank_info_combo").combobox(bank_info_array, {}, "bank_info_select", "FrmFormBill_bank_info_id", false, '', 220, '', 220);
	});
}

//计算总和
function countTotal(){
	if (is_relation == 0) return;
	
	var fee = parseFloat(numChange($("#FrmFormBill_fee").val()));
	var total_fee = 0.0;
	$("#cght_tb tbody tr").each(function(){
		total_fee += parseFloat(numChange($(this).find(".real_fee").text()));
	});

	$("#cght_tb tbody tr").each(function(){
		var real_fee = parseFloat(numChange($(this).find(".real_fee").text()));
		$(this).find(".discount").val((fee * (real_fee / total_fee)).toFixed(2));
	});
}

//换页获取数据
$(document).on("click", ".sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getBillList(page);
});

$(document).on("change", ".paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getBillList(page);
});

$(document).on("change", "#each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "form_bill_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('.firstpage').attr("page");
		getBillList(page);
	});
});

$("#FrmFormBill_bill_type").change(function(){
	if($(this).val() != type){
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}

	if($(this).val() == "DLFK"){
		$("#pledge_company").show();
		$("#pledge_bank_info").show();
	}else{
		$("#pledge_company").hide();
		$("#pledge_bank_info").hide();
	}
	
	is_supply = $.inArray($(this).val(), new Array('CGFK', 'TPSH', 'DLFK', 'CGTH', 'GCFL', 'BZJ')) > -1 ? 1 : 0;
	is_customer = $.inArray($(this).val(), new Array('XSTH', 'XSZR', 'GKZR', 'XSSK')) > -1 ? 1 : 0;
	is_gk = $.inArray($(this).val(), new Array('GKFK')) > -1 ? 1 : 0;
	has_warehouse = $.inArray($(this).val(), new Array('CCFY', 'CKFL')) > -1 ? 1 : 0;
	is_logistics = $.inArray($(this).val(), new Array('YF')) > -1 ? 1 : 0;
	
	has_yidan = $.inArray($(this).val(), new Array('CGFK', 'XSSK', 'XSTH', 'CGTH')) > -1 ? 1 : 0;
	is_relation = $.inArray($(this).val(), new Array('CGFK', 'XSSK', 'CCFY', 'CKFL', 'GCFL', 'BZJ')) > -1 ? 0 : 1;
	has_turnover = $.inArray($(this).val(), new Array('CGFK', 'BZJ')) > -1 ? 1 : 0;
	has_ownedSales = $.inArray($(this).val(), new Array('XSSK')) > -1 ? 1 : 0;
	
	$("#supply_select, #customer_select, #gk_select, #logistics_select").hide();
	$(".create_table #cght_tb, .search_body, #bill_list").hide();
	if (is_relation > 0) $(".create_table #cght_tb, .search_body, #bill_list").show();

	$("#FrmFormBill_is_yidan").parent().hide();
	if (has_yidan > 0) $("#FrmFormBill_is_yidan").parent().show();
	
	if (is_supply > 0) 
	{
		$("#supply_select").parent().show();
		$("#bill_list").html('<h3 style="text-align: center; color: #999">请选择供应商和公司抬头</h3>');
	} 
	else if (is_customer > 0) 
	{
		$("#customer_select").parent().show();
		$("#bill_list").html('<h3 style="text-align: center; color: #999">请选择客户和公司抬头</h3>');
	} 
	else if (is_gk > 0) 
	{
		$("#gk_select").parent().show();
		$("#bill_list").html('<h3 style="text-align: center; color: #999">请选择高开结算单位和公司抬头</h3>');
	} 
	else if (is_logistics > 0) 
	{
		$("#logistics_select").parent().show();
		$("#bill_list").html('<h3 style="text-align: center; color: #999">请选择物流商和公司抬头</h3>');
	}
	getBillList(1);
});

$("#supply_select").click(function(){
	var supply_val = $("#supply_val").val();
	if (supply_val != company_id && is_supply > 0) 
	{
		$("#FrmFormBill_company_id").val(supply_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBankList(supply_val);
	getBillList(1);
});

$("#customer_select").click(function(){
	var customer_val = $("#customer_val").val();
	if (customer_val != company_id && is_customer > 0) 
	{
		$("#FrmFormBill_company_id").val(customer_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBankList(customer_val);
	getBillList(1);
});

$("#gk_select").click(function(){
	var gk_val = $("#gk_val").val();
	if (gk_val != company_id && is_gk > 0) 
	{
		$("#FrmFormBill_company_id").val(gk_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBankList(gk_val);
	getBillList(1);
});

$("#logistics_select").click(function(){
	var logistics_val = $("#logistics_val").val();
	if (logistics_val != company_id && is_logistics > 0) 
	{
		$("#FrmFormBill_company_id").val(logistics_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBankList(logistics_val);
	getBillList(1);
});

$("#pledge_select").click(function(){
	var pledge_val = $("#FrmFormBill_pledge_company_id").val(); 
	if(pledge_val != pledge_company_id && type == 'DLFK'){
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	$.get("/index.php/bankInfo/getBankList", 
	{
		'id': pledge_val
	}, 
	function(data) 
	{
		pledge_bank_info_array = data ? data : [];
		$("#pledge_bank_info_select").html('<input type="text" id="pledge_bank_info_combo" value=""><input type="hidden" id="FrmFormBill_pledge_bank_info_id" value="" name="FrmFormBill[pledge_bank_info_id]">');
		$("#pledge_bank_info_combo").combobox(pledge_bank_info_array, {}, "pledge_bank_info_select", "FrmFormBill_pledge_bank_info_id", false, '', 220);
	});
	getBillList(1);
});

$("#title_select").click(function(){
	var title_val = $("#FrmFormBill_title_id").val(); 	
	if (title_val != title_id && ['CGFK', 'CGTH', 'XSSK', 'XSTH', 'XSZR', 'GKZR', 'GKFK'].indexOf(type) > -1) 
	{
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	$.get("/index.php/dictBankInfo/getBankList", 
	{
		'id': title_val
	}, 
	function(data) 
	{
		dict_bank_info_array = data ? data : [];
		$("#dict_bank_info_select").html('<input type="text" id="dict_bank_info_combo" value="" /><input type="hidden" id="FrmFormBill_dict_bank_info_id" value="" name="FrmFormBill[dict_bank_info_id]">');
		$("#dict_bank_info_combo").combobox(dict_bank_info_array, {}, "dict_bank_info_select", "FrmFormBill_dict_bank_info_id", false, '', 220);
	});
	getBillList(1);
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
		user_options = '<option value="">-请选择-</option>' + data;
		$("#CommonForms_owned_by").html(user_options);
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
});

/*
//是否乙单
$("#FrmFormBill_is_yidan").change(function(){
	var is_yidan_val = $(this).val();
	if(['CGFK', 'XSSK', 'XSZR', 'GKZR', 'GKFK'].indexOf(type) > -1 && is_yidan_val != is_yidan){
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBillList(1);
});
*/
//搜索
$(".search_btn").click(function(){
	getBillList(1);
});

//重置搜索
$(".reset").click(function(){
	$(".search_body .forreset").val("");
});

//选中
$(".selected_bill").live("click", function(){
	var tr = $(this).parent().parent();
	var tr_str = "";
	for (var i = 0; i < tr.find("td").length; i++) 
	{
		if (i == 0 || i == 1) continue;
		tr_str += '<td class="text-center">' + tr.find("td").eq(i).html() + '</td>';
	}
	var common_id = $(this).val();
	var yidan = $(this).attr("yidan");
	
	if ($(this).attr("checked") == "checked") {
/*
		//是否乙单
		if (has_yidan > 0 && $("#FrmFormBill_is_yidan").attr("checked") == "checked" && yidan == 0 && !confirm("该单为非乙单数据，确认选择？")) 
		{
			checkItem.removeAttr("checked");
			return ;
		} 
		else if (has_yidan > 0 && $("#FrmFormBill_is_yidan").attr("checked") != "checked" && yidan == 1 && !confirm("该单为乙单数据，确认选择？")) 
		{
			checkItem.removeAttr("checked");
			return ;
		}
*/
		tr_num++;
		var id = "";
		var data = '<tr>' + 
			'<td class="text-center list_num">' + tr_num + '</td>' + 
			'<td class="text-center">' + 
				'<i class="icon icon-trash deleted_tr"></i>';
		if(_common_id_array.indexOf(common_id) > -1){
			id = id_array[_common_id_array.indexOf(common_id)];
		}
		data += '<input type="hidden" name="td_id[]" class="td_id" value="' + id + '" />' + 
				'<input type="hidden" name="td_common_id[]" class="td_common_id" value="' + common_id + '" />' +
			'</td>' + tr_str +
		'</tr>';
		common_id_array.push(common_id);
		$("#cght_tb tbody").append(data);
	}else{
		$("#cght_tb tbody tr").each(function(){
			if($(this).find(".td_common_id").val() == common_id){
				$(this).remove();
				tr_num = 0;
				$("#cght_tb tbody .list_num").each(function(){
					tr_num++;
					$(this).html(tr_num);
				});
			}
		});
	}
	countTotal();
});

//删除
$(".deleted_tr").live("click", function(){
	var common_id = $(this).parent().parent().find(".td_common_id").val();
	$(this).parent().parent().remove();
	common_id_array.splice(common_id_array.indexOf(common_id), 1);
	$("#bill_table tbody tr").each(function(){
		if($(this).find(".selected_bill").val() == common_id){
			$(this).find(".selected_bill").removeAttr("checked");
		}
	});
	tr_num = 0;
	$("#cght_tb tbody .list_num").each(function(){
		tr_num++;
		$(this).html(tr_num);
	});
	countTotal();
});

$("#FrmFormBill_fee").change(function(){
	countTotal();
});

//保存
$("#submit_btn").click(function(){
/*
	if (is_supply > 0 && $("#supply_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位");
		$("#supply_combo").focus();
		return false;
	} 
*/
	if (is_customer > 0 && $("#customer_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位");
		$("#customer_combo").focus();
		return false;
	}
	if (is_gk > 0 && $("#gk_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位");
		$("#gk_combo").focus();
		return false;
	}
	if (has_warehouse > 0 && $("#warehouse_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#warehouse_combo").focus();
		});
		return false;
	}
	if (is_logistics > 0 && $("#logistics_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#logistics_combo").focus();
		});
		return false;
	}
/*
	if ($("#FrmFormBill_bank_info_id").val() <= 0) 
	{
		confirmDialog("请选择结算账户", function(){
			$("#bank_info_combo").focus();
		});
		return false;
	}
 */
	if ($("#FrmFormBill_bill_type").val() == "DLFK" && $("#FrmFormBill_pledge_company_id").val() <= 0) 
	{
		confirmDialog("请选择托盘公司", function(){
			$("#pledge_combo").focus();
		});
		return false;
	}
/*
	if ($("#FrmFormBill_bill_type").val() == "DLFK" && $("#FrmFormBill_pledge_bank_info_id").val() <= 0) 
	{
		confirmDialog("请选择托盘账户", function(){
			$("#pledge_bank_info_combo").focus();
		});
		return false;
	}
*/
	if ($("#FrmFormBill_title_id").val() <= 0) 
	{
		confirmDialog("请选择公司", function(){
			$("#title_combo").focus();
		});
		return false;
	}
	if ($("#FrmFormBill_dict_bank_info_id").val() <= 0) 
	{
		confirmDialog("请选择公司账户", function(){
			$("#dict_bank_info_combo").focus();
		});
		return false;
	}
	if (!$("#FrmFormBill_fee").val() || $("#FrmFormBill_fee").val() == "") 
	{
		confirmDialog("请填写总金额", function() {
			$("#FrmFormBill_fee").focus();
		});
		return false;
	} 
	else if (isNaN(numChange($("#FrmFormBill_fee").val())) || parseFloat(numChange($("#FrmFormBill_fee").val())) <= 0) 
	{
		confirmDialog("总金额必须大于0", function() {
			$("#FrmFormBill_fee").focus();
		});
		return false;
	}
	if (!$("#CommonForms_owned_by").val()) 
	{
		confirmDialog("请选择业务员", function() {
			$("#CommonForms_owned_by").focus();
		});
		return false;
	}
	if (!$("#CommonForms_created_at").val()) 
	{
		confirmDialog("请选择创建日期", function() {
			$("#CommonForms_created_at").focus();
		});
		return false;
	}
	confirmDialog3("确认保存修改并入账？", function() {
		if (can_submit) 
		{
			can_submit = false;
			// setTimeout(function() { can_submit = true; }, 3000);
			notAnymore('submit_btn');
			$("#form_data").submit();
		}
		$("#form_data").submit();
	});
});

//select
var supply_array = <?php echo $supply_array ? $supply_array : '[]';?>;
var logistics_array = <?php echo $logistics_array ? $logistics_array : '[]';?>;
var customer_array = <?php echo $customer_array ? $customer_array : '[]';?>;
var gk_array = <?php echo $gk_array ? $gk_array : "[]";?>;
var pledge_array = <?php echo $pledge_array ? $pledge_array : '[]'?>;
var title_array = <?php echo $title_array ? $title_array : "[]";?>;

var bank_info_array = <?php echo $bank_info_array ? $bank_info_array : "[]";?>;
var dict_bank_info_array = <?php echo $dict_bank_info_array ? $dict_bank_info_array : "[]";?>;
var pledge_bank_info_array = <?php echo $pledge_bank_info_array ? $pledge_bank_info_array : "[]";?>;

$(function(){
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>

	$("#supply_combo").combobox(supply_array, {}, "supply_select", "supply_val", false);
	$("#logistics_combo").combobox(logistics_array, {}, "logistics_select", "logistics_val", false);
	$("#customer_combo").combobox(customer_array, {}, "customer_select", "customer_val", false);
	$("#gk_combo").combobox(gk_array, {}, "gk_select", "gk_val", false);
	$("#title_combo").combobox(title_array, {}, "title_select", "FrmFormBill_title_id", false);
	$("#pledge_combo").combobox(pledge_array, {}, "pledge_select", "FrmFormBill_pledge_company_id", false);

	$("#bank_info_combo").combobox(bank_info_array, {}, "bank_info_select", "FrmFormBill_bank_info_id", false, '', 220);
	$("#dict_bank_info_combo").combobox(dict_bank_info_array, {}, "dict_bank_info_select", "FrmFormBill_dict_bank_info_id", false, '', 220);
	$("#pledge_bank_info_combo").combobox(pledge_bank_info_array, {}, "pledge_bank_info_select", "FrmFormBill_pledge_bank_info_id", false, '', 220);

	//记录编辑前数据
	$("#cght_tb tbody tr").each(function(){
		id_array.push($(this).find(".td_id").val());
		_common_id_array.push($(this).find(".td_common_id").val());
		common_id_array.push($(this).find(".td_common_id").val());
	});
	
	$("#CommonForms_owned_by").change();
	getBillList(1);
<?php if (!$model->id) {?>
	if ($("#FrmFormBill_company_id").val() && $("#FrmFormBill_company_id").val() > 0) 
	{
		getBankList($("#FrmFormBill_company_id").val());
		$("#title_select").click();
	}
<?php }?>
});
//-->
</script>
