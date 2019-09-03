<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/zui/css/zui.min.css" rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery_ui.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-timepicker-addon.css" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/zui/lib/datetimepicker/datetimepicker.min.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.combobox.css" />
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.0.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/index.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/export.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.cookie.js"></script>
<!-- ZUI Javascript组件 -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<style>
<!--
.icon{ cursor: pointer;}
#cght_tb tbody td{ line-height: 26px;}
#bill_list, #turnover_body{ float: left; width: 100%;}
.search_line{ height: 2px;}
i.deleted_tr{ float: none; line-height: 26px; margin: 0 auto;}
-->
</style>
<body style="min-height: 400px;width:900px;">
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
<div class="shop_select_box" style="width:890px;">
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

<div class="create_table" style="min-width: 890px;">
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

</body>
<script type="text/javascript">
var can_submit = true;

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

var form_sn='<?php echo $baseform->form_sn?>';

//查询条件
var type = "";
var company_id = 0;
var title_id = 0;
var is_yidan = '';
var pledge_company_id = 0;
var keywords = "";
var begin_time = "";
var end_time = "";


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


//保存
$("#submit_btn").click(function(e){

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

	if ($("#FrmFormBill_bill_type").val() == "DLFK" && $("#FrmFormBill_pledge_company_id").val() <= 0) 
	{
		confirmDialog("请选择托盘公司", function(){
			$("#pledge_combo").focus();
		});
		return false;
	}

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
			notAnymore('submit_btn');
			e.preventDefault();
			$("#form_data").submit();
		}
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
// 	getBillList(1);
<?php if (!$model->id) {?>
	if ($("#FrmFormBill_company_id").val() && $("#FrmFormBill_company_id").val() > 0) 
	{
		getBankList($("#FrmFormBill_company_id").val());
		$("#title_select").click();
	}
<?php }?>
	var formdata=$('#form_data');
	formdata.submit(function(e){
		e.preventDefault();
		$.post(formdata.attr('action'),formdata.serialize(),function(data){
			if(data=='done'){
				parent.$.colorbox.close();
				//更新表单按钮和状态
				var str='';
				$.ajaxSetup({async:false});
				$.post('/index.php/formBill/getCurrentButton',{'form_sn':form_sn},
				function(ret){
					str=ret;
				})
				var numm=1;
				parent.$('.cz_list_btn').each(function(){
					var thisform=$(this).find('.form_sn').val();					
					if(thisform===form_sn)
					{
						var ind=$(this).parent().parent().index();
						var sta=$(this).parent().parent().parent().parent().parent().parent().parent().children('.flexarea').children().children('table').children().children().eq(ind).find('.status_btn').parent();
						sta.html('已入账');
						var btn=$(this).parent();
						btn.html(str);
						
					}
										
				})
			}else if(data=='fail'){
				confirmDialog('入账失败');
			}
		});
	})

});
//取消，关闭
$('#cancel').click(function(){
	parent.$.colorbox.close();
});
</script>
