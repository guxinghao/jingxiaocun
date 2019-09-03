<style>
<!--
.icon{ cursor: pointer;}
.weight, .fee{ width: 90px; text-align: right;}
#cght_tb tbody td{ line-height: 30px;}
#cght_tb tbody td input{ height: 30px; line-height: 22px;}
#invoice_list{ float: left; width: 100%;}
.c_comment{ width: 555px;}
#CommonForms_comment{ width: 425px;}
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
		<span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span>
		<input type="hidden" id="FrmSalesInvoice_company_id" value="<?php echo $model->company_id;?>" name="FrmSalesInvoice[company_id]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开票单位：</div>
		<span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span>
		<input type="hidden" id="FrmSalesInvoice_title_id" value="<?php echo $model->title_id;?>" name="FrmSalesInvoice[title_id]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开票重量：</div>
		<input type="text" id="FrmSalesInvoice_weight" class="form-control" value="<?php echo number_format($model->weight, 3, ".", ",");?>" name="FrmSalesInvoice[weight]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开票金额：</div>
		<input type="text" id="FrmSalesInvoice_fee" class="form-control" value="<?php echo number_format($model->fee, 2, ".", ",");?>" name="FrmSalesInvoice[fee]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开票张数：</div>
		<input type="text" id="FrmSalesInvoice_invoice_amount" class="form-control" value="<?php echo $model->invoice_amount;?>" name="FrmSalesInvoice[invoice_amount]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>票号：</div>
		<input type="text" id="FrmSalesInvoice_invoice_number" class="form-control" value="<?php echo $model->invoice_number;?>" name="FrmSalesInvoice[invoice_number]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<select class="form-control chosen-select" disabled="disabled">
		<?php foreach ($user_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->owned_by == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
		<input id="CommonForms_owned_by" type="hidden" value="<?php echo $baseform->owned_by;?>" name="CommonForms[owned_by]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<select id="team_select" class="form-control chosen-select" disabled="disabled">
			<option value="">-请选择-</option>
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->belong->team_id == $key ? ' selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
		<input id="FrmSalesInvoice_team_id" type="hidden" value="<?php echo $baseform->belong->team_id;?>" name="FrmSalesInvoice[team_id]">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
		<span title="<?php echo $model->client->name;?>"><?php echo $model->client->name;?></span>
		<input type="hidden" id="FrmSalesInvoice_company_id" value="<?php echo $model->client_id;?>" name="FrmSalesInvoice[client_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开票时间：</div>
		<input id="CommonForms_form_time" type="text" class="form-control form-date date input_backimg" value="<?php echo date('Y-m-d');?>" name="CommonForms[form_time]" />
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
				<th class="text-center" ></th>
				<!-- <th class="text-center" >操作</th> -->
				<th class="text-center" >单号</th>
				<th class="text-center" >公司</th>
				<th class="text-center" >结算单位</th>
				<th class="text-center" >产地/品名/材质/规格/长度</th>
				<th class="text-center" >开票重量</th>
				<th class="text-center" >开票金额</th>
				<th class="text-center" >可开票重量</th>
				<th class="text-center" >可开票金额</th>
				<th class="text-center">业务员</th>
				<th class="text-center">客户</th>
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
					<input type="hidden" name="td_invoice_id[]" class="td_invoice_id" value="<?php echo $item->sales_detail_id;?>" />
				</td>
<!-- <td class="text-center"><i style="line-height:26px;" class="icon icon-trash deleted_tr"></i></td> -->
				<td class="text-center"><?php echo $item->form_sn;?></td>
				<td class="text-center"><span title="<?php echo $item->title->name;?>"><?php echo $item->title->short_name;?></span></td>
				<td class="text-center"><span title="<?php echo $item->company->name;?>"><?php echo $item->company->name;?></span></td>
				<td class="text-center"><?php echo $item->type != 'rebate' ? $item->brand.'/'.$item->product_name.'/'.str_replace('E', '<span class="red">E</span>', $item->texture).'/'.$item->rank.'/'.$item->length : '';?></td>
				<td class="text-center"><?php echo number_format($item->weight, 3, ".", ",");?>
					<input type="hidden" class="weight" value="<?php echo number_format($item->weight, 3, ".", ",");?>" name="weight[]" />
				</td>
				<td class="text-center"><?php echo number_format($item->fee, 2, ".", ",");?>
					<input type="hidden" class="fee" value="<?php echo number_format($item->fee, 2, ".", ",");?>" name="fee[]" />
				</td>
				<td class="text-center"><?php echo number_format($item->needWeight + $item->weight, 3, ".", ",");?></td>
				<td class="text-center"><?php echo number_format($item->needMoney + $item->fee, 2, ".", ",");?></td>
				<td class="text-center"><?php echo $item->belong->nickname;?></td>
				<td class="text-center">
					<span title="<?php echo $item->client->name;?>"><?php echo $item->client->name;?></span>
				</td>
			</tr>
		<?php } }?>
		</tbody>
	</table>
</div>
<input type="hidden" name="CommonForms[form_type]" value="XSKP">
<!-- <input type="hidden" name="CommonForms[form_time]" value="<?php echo date("Y-m-d H:i:s");?>"> -->
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />

<div class="btn_list">
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">开票</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget();?>

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
	$("#FrmSalesInvoice_team_id").val($("#team_select").val());
});

//保存
$("#submit_btn").click(function(){
// 	if (parseFloat(numChange($("#FrmSalesInvoice_fee").val())) <= 0) 
// 	{
// 		confirmDialog("开票总金额必须大于0");
// 		$("#FrmSalesInvoice_fee").focus();
// 		return false;
// 	}
	if (!$("#FrmSalesInvoice_invoice_amount").val() || isNaN(numChange($("#FrmSalesInvoice_invoice_amount").val())) || parseInt(numChange($("#FrmSalesInvoice_invoice_amount").val())) <= 0) 
	{
		confirmDialog("开票张数必须大于0", function(){
			$("#FrmSalesInvoice_invoice_amount").focus();
		});	
		return false;
	}
	if (!$("#FrmSalesInvoice_invoice_number").val()) 
	{
		confirmDialog("请填写票号", function(){
			$("#FrmSalesInvoice_invoice_number").focus();
		});
		return false;
	}
	if (!$("#CommonForms_owned_by").val()) 
	{
		confirmDialog("请选择业务员", function(){
			$("#CommonForms_owned_by").focus();
		});
		return false;
	}
	if (!$("#CommonForms_form_time").val()) 
	{
		confirmDialog("请选择开票时间", function(){
			$("#CommonForms_form_time").focus();
		});
		return false;
	}
	
	confirmDialog3("确认保存修改并开票？", function(){
		if (can_submit) 
		{
			can_submit = false;
			notAnymore('submit_btn');
			$("#form_data").submit();
		}
	});
});

$(function(){
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>
	
	//记录编辑前数据
	$("#cght_tb tbody tr").each(function(){
		id_array.push($(this).find(".td_id").val());
		invoice_id_array.push($(this).find(".td_invoice_id").val());
		_invoice_id_array.push($(this).find(".td_invoice_id").val());
		_weight_array.push(numChange($(this).find(".weight").val()));
		_fee_array.push(numChange($(this).find(".fee").val()));
	});
});
</script>
