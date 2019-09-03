<?php
$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true, 
		'htmlOptions' => array(
				'id' => 'form_data', 
				'enctype' => 'multipart/form-data',
		),
));
?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<input id="title_combo" type="text" class="form-control" value="<?php echo $model->title->short_name;?>" readonly="readonly" />
		<input id="BillRecord_title_id" type="hidden" value="<?php echo $model->title_id;?>" name="BillRecord[title_id]" />
	</div>
	
	
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>重量：</div>
		<input id="BillRecord_weight" type="text" class="form-control" value="<?php echo number_format($model->weight, 3);?>" name="BillRecord[weight]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>单价：</div>
		<input id="BillRecord_price" type="text" class="form-control" value="<?php echo number_format($model->price, 2);?>" name="BillRecord[price]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input id="BillRecord_travel" type="text" class="form-control" value="<?php echo $model->travel;?>" name="BillRecord[travel]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>收益单位：</div>
		<div id="company_select" class="fa_droplist">
			<input id="company_combo" type="text" value="<?php echo $model->company->short_name;?>" />
			<input id="BillRecord_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="BillRecord[company_id]" />
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>金额：</div>
		<input id="BillRecord_amount" type="text" class="form-control" value="<?php echo number_format($model->amount, 2);?>" name="BillRecord[amount]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="CommonForms_comment" type="text" class="form-control" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]" />
	</div>
	
	<input type="checkbox" id="BillRecord_is_yidan" class="check_box l" style="margin: 6px 0 0 130px;" value="1" name="BillRecord[is_yidan]"<?php echo $model->is_yidan == 1 ? ' checked="checked"' : '';?>>
	<div class="lab_check_box">乙单</div>
<?php } else {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>重量：</div>
		<input id="BillRecord_weight" type="text" class="form-control" value="<?php echo number_format($model->weight, 3);?>" name="BillRecord[weight]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>单价：</div>
		<input id="BillRecord_price" type="text" class="form-control" value="<?php echo number_format($model->price, 2);?>" name="BillRecord[price]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input id="BillRecord_travel" type="text" class="form-control" value="<?php echo $model->travel;?>" name="BillRecord[travel]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>收益单位：</div>
		<div id="company_select" class="fa_droplist">
			<input id="company_combo" type="text" class="form-control" value="<?php echo $model->company->short_name;?>" readonly="readonly" />
			<input id="BillRecord_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="BillRecord[company_id]" />
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>金额：</div>
		<input id="BillRecord_amount" type="text" class="form-control" value="<?php echo number_format($model->amount, 2);?>" name="BillRecord[amount]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="CommonForms_comment" type="text" class="form-control" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]" />
	</div>

	<input type="checkbox" class="check_box l" style="margin-left: 130px;" value="1"<?php echo $model->is_yidan == 1 ? ' checked="checked"' : '';?> disabled="disabled">
	<div class="lab_check_box">乙单</div>
	<input id="BillRecord_is_yidan" type="hidden" value="<?php echo $model->is_yidan;?>" name="BillRecord[is_yidan]" />
<?php }?>
</div>
<div class="create_table"></div>

<input id="BillRecord_frm_common_id" type="hidden" value="<?php echo $model->frm_common_id;?>" name="BillRecord[frm_common_id]" />
<input id="BillRecord_bill_type" type="hidden" value="<?php echo $model->bill_type;?>" name="BillRecord[bill_type]" />
<input id="BillRecord_discount" type="hidden" value="<?php echo $model->discount;?>" name="BillRecord[discount]" />
<input id="BillRecord_is_selected" type="hidden" value="<?php echo $model->is_selected;?>" name="BillRecord[is_selected]" />

<input id="CommonForms_form_type" type="hidden" name="CommonForms[form_type]" value="FYDJ" />
<input id="CommonForms_form_time" type="hidden" name="CommonForms[form_time]" value="<?php echo date("Y-m-d H:i:s");?>" />
<input id="CommonForms_owned_by" type="hidden" name="CommonForms[owned_by]" value="<?php echo $baseform->owned_by;?>" />
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />

<div class="btn_list">
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	<button id="save_submit" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存提交</button>
<?php }?>
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget();?>

<script type="text/javascript">

var can_submit = true;

//计算单价 
function getPrice() 
{
	var weight = $("#BillRecord_weight").val() ? parseFloat(numChange($("#BillRecord_weight").val())) : 0;
	var amount = $("#BillRecord_amount").val() ? parseFloat(numChange($("#BillRecord_amount").val())) : 0;
	var price = amount > 0 ? amount / weight : 0;
	$("#BillRecord_price").val(numberFormat(price, 2, '.', ','));
}

//重量 
$("#BillRecord_weight").change(function() {
	if (isNaN($(this).val())) 
	{
		confirmDialog("重量必须为数字", function() {
			$("#BillRecord_weight").focus();
		});
		return false;
	} 
	else if (parseFloat($("#BillRecord_weight").val()) <= 0) 
	{
		confirmDialog("重量必须大于0", function() {
			$("#BillRecord_weight").focus();
		});
		return false;
	}
	getPrice();
});

//总金额 
$("#BillRecord_amount").change(function() {
	if (isNaN($(this).val())) 
	{
		confirmDialog("金额必须为数字", function() {
			$("#BillRecord_amount").focus();
		});
		return false;
	} 
	else if (parseFloat($("#BillRecord_amount").val()) <= 0) 
	{
		confirmDialog("金额必须大于0", function() {
			$("#BillRecord_amount").focus();
		});
		return false;
	}
	getPrice();
});

//保存 
$("#save_submit, #submit_btn").click(function() {
	if ($("#BillRecord_company_id").val() == 0) 
	{
		confirmDialog("请选择收益单位", function() {
			$("#company_combo").focus();
		});
		return false;
	}
	
	if ($("#BillRecord_weight").val() == "") 
	{
		confirmDialog("请填写重量", function() {
			$("#BillRecord_weight").focus();
		});
		return false;
	} 
	else if (parseFloat($("#BillRecord_weight").val()) <= 0) 
	{
		confirmDialog("重量必须大于0", function() {
			$("#BillRecord_weight").focus();
		});
		return false;
	}

	if ($("#BillRecord_amount").val() == "") 
	{
		confirmDialog("请填写金额", function() {
			$("#BillRecord_amount").focus();
		});
		return false;
	}
	else if (parseFloat($("#BillRecord_amount").val()) <= 0) 
	{
		confirmDialog("金额必须大于0", function() {
			$("#BillRecord_amount").focus();
		});
		return false;
	}

	if ($(this).attr("id") == 'save_submit') $(this).parent().append('<input type="hidden" name="CommonForms[submit]" value="yes">');
	if (can_submit) 
	{
		can_submit = false;
		notAnymore('save_submit');
		notAnymore('submit_btn');
		$("#form_data").submit();
	}
});

//select 
var company_array = <?php echo $company_array ? $company_array : '[]';?>;

$(function() {
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	$("#company_combo").combobox(company_array, {}, 'company_select', 'BillRecord_company_id', false);
<?php }?>

<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>", function() {
//		window.location.href = "<?php echo $back_url;?>";
	});
<?php }?>

});

</script>








