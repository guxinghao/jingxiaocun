<style>
<!--
.detail_table tbody select.LoanRecord_account_direction{ width: 65px;}
.detail_table tbody select.LoanRecord_amount_type{ width: 65px;}
.detail_table tbody input.LoanRecord_amount{ float: right; width: 70px; text-align: right;}
.detail_table tbody select.LoanRecord_has_Ious{ width: 70px;}
.detail_table tbody input.LoanRecord_created_at{ width: 80px;}
.detail_table tbody select.LoanRecord_created_by{ width: 70px;}
.detail_table tbody input.LoanRecord_comment{ width: 110px;}
.create_table .input_backimg{ background-position: 88px center;}
.deleted_tr{ cursor: pointer;}
.LoanRecord_reach_at{width:115px;}
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
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span>
		<input id="ShortLoan_title_id" type="hidden" value="<?php echo $model->title_id;?>" name="ShortLoan[title_id]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>借/贷公司：</div>
		<span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span>
		<input id="ShortLoan_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="ShortLoan[company_id]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>合约金额：</div>
		<input id="ShortLoan_amount" type="text" class="form-control" value="<?php echo number_format($model->amount, 2);?>" name="ShortLoan[amount]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>利率(‰)：</div>
		<input id="ShortLoan_interest_rate" type="text" class="form-control" value="<?php echo number_format($model->interest_rate, 4);?>" name="ShortLoan[interest_rate]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>借贷方向：</div>
		<select id="lending_direction_select" class="form-control chosen-select" name="ShortLoan[lending_direction]" disabled="disabled">
		<?php foreach (ShortLoan::$lendingDirection as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $model->lending_direction == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
		<input id="ShortLoan_lending_direction" type="hidden" class="form-control" value="<?php echo $model->lending_direction;?>" name="ShortLoan[lending_direction]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>有无借据：</div>
		<select id="has_Ious_select" class="form-control chosen-select" disabled="disabled">
		<?php foreach (ShortLoan::$hasIous as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $model->has_Ious == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
		<input id="ShortLoan_has_Ious" type="hidden" class="form-control" value="<?php echo $model->has_Ious;?>" name="ShortLoan[has_Ious]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>负责人：</div>
		<select id="CommonForms_owned_by" class="form-control chosen-select" disabled="disabled">
		<?php foreach ($user_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $key == $baseform->owned_by ? ' selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
		<input id="CommonForms_owned_by" type="hidden" value="<?php echo $baseform->owned_by;?>" name="CommonForms[owned_by]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务组：</div>
		<select id="team_select" class="form-control chosen-select" disabled="disabled">
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $key == $baseform->belong->team->id ? ' selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
		<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>登记日期：</div>
				<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
						<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg form_time" placeholder="选择日期"  value="<?php echo $baseform->form_time;?>" readonly>
				</div>
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">开始日期：</div>
		<input id="ShortLoan_start_time" type="text" class="form-control" value="<?php echo $model->start_time>0?date('Y-m-d', $model->start_time):"";?>" name="ShortLoan[start_time]" readonly="readonly" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">结束日期：</div>
		<input id="ShortLoan_end_time" type="text" class="form-control" value="<?php echo $model->end_time>0?date('Y-m-d', $model->end_time):"";?>" name="ShortLoan[end_time]" readonly="readonly" />
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="CommonForms_comment" type="text" class="form-control" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]"/>
	</div>
	<div class="recordData">
	<?php
		$tr_num = 1;
		if(is_array($loanRecord) && count($loanRecord) > 0){
			foreach ($loanRecord as $item) {
	?>
	<input type="hidden" class="LoanRecord_id"  value="<?php echo $item->id;?>" name="LoanRecord[id][]" />
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入账日期：</div>
		<input type="text" class="form-control form-date input_backimg LoanRecord_reach_at" value="<?php echo  $item->reach_at > 0 ?date('Y-m-d', $item->reach_at ): $baseform->form_time;;?>" placeholder="选择日期" name="LoanRecord[reach_at][]" />
		<input type="hidden" class="LoanRecord_created_at" value="<?php echo date('Y-m-d', $item->created_at > 0 ? $item->created_at : time());?>" name="LoanRecord[created_at][]" />
		<select class="form-control chosen-select LoanRecord_amount_type" name="LoanRecord[amount_type][]" style="display:none;">
			<?php foreach (LoanRecord::$amountType as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $item->amount_type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
		</select>
		<select class="form-control chosen-select LoanRecord_account_direction" name="LoanRecord[account_direction][]" style="display: none;">	
				<option value="accounted" <?php echo $model->lending_direction == "borrow" ? ' selected="selected"' : '';?>>入账</option>
				<option value="out_account" <?php echo $model->lending_direction == "lend" ? ' selected="selected"' : '';?>>出账</option>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司账号：</div>
		<div id="dict_bank_select_<?php echo $tr_num;?>" class="fa_droplist">
			<input id="dict_bank_combo_<?php echo $tr_num;?>" type="text" class="dict_bank_combo" value="<?php echo $item->dictBank ? $item->dictBank->dict_name.'('.$item->dictBank->bank_number.')' : '';?>" />
			<input id="dict_bank_val_<?php echo $tr_num;?>" type="hidden" class="LoanRecord_dict_bank_id" value="<?php echo $item->dict_bank_id;?>" name="LoanRecord[dict_bank_id][]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">对方账号：</div>
		<div id="bank_select_<?php echo $tr_num;?>" class="fa_droplist">
			<input id="bank_combo_<?php echo $tr_num;?>" type="text" class="bank_combo" value="<?php echo $item->bank ? $item->bank->company_name.'('.$item->bank->bank_number.')' : '';?>" />
			<input id="bank_val_<?php echo $tr_num;?>" type="hidden" class="LoanRecord_bank_id" value="<?php echo $item->bank_id;?>" name="LoanRecord[bank_id][]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>金额：</div>
		<input type="text" class="form-control LoanRecord_amount" value="<?php echo number_format($item->amount, 2);?>" name="LoanRecord[amount][]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>借据：</div>
		<select class="form-control chosen-select LoanRecord_has_Ious" name="LoanRecord[has_Ious][]">
		<?php foreach (LoanRecord::$hasIous as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $item->has_Ious == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>操作人：</div>
		<select class="form-control chosen-select LoanRecord_created_by" name="LoanRecord[created_by][]">
		<?php foreach ($user_array as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $item->created_by == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text" class="form-control LoanRecord_comment" value="<?php echo $item->comment;?>" name="LoanRecord[comment][]" />
	</div>
	<script type="text/javascript">
		$(function() {
			$("#dict_bank_combo_<?php echo $tr_num;?>").combobox(dict_bank_array, {}, 'dict_bank_select_<?php echo $tr_num;?>', 'dict_bank_val_<?php echo $tr_num;?>', false, '', 260);
			$("#bank_combo_<?php echo $tr_num;?>").combobox(bank_array, {}, 'bank_select_<?php echo $tr_num;?>', 'bank_val_<?php echo $tr_num;?>', false, '', 260);
			dateTimePick();
		});
	</script>
	<?php $tr_num++;}
		}else{
	?>
	<input type="hidden" class="LoanRecord_id" value="" name="LoanRecord[id][]" />
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入账日期：</div>
		<input type="text" class="form-control form-date input_backimg LoanRecord_reach_at" value="<?php echo  $baseform->form_time;;?>" placeholder="选择日期" name="LoanRecord[reach_at][]" />
		<input type="hidden" class="LoanRecord_created_at" value="<?php echo date('Y-m-d');?>" name="LoanRecord[created_at][]" />
		<select class="form-control chosen-select LoanRecord_amount_type" name="LoanRecord[amount_type][]" style="display:none;">
			<?php foreach (LoanRecord::$amountType as $k => $v) {?>
				<option value="<?php echo $k;?>" ><?php echo $v;?></option>
			<?php }?>
		</select>
			<select class="form-control chosen-select LoanRecord_account_direction" name="LoanRecord[account_direction][]" style="display: none;">	
				<option value="accounted" <?php echo $model->lending_direction == "borrow" ? ' selected="selected"' : '';?>>入账</option>
				<option value="out_account" <?php echo $model->lending_direction == "lend" ? ' selected="selected"' : '';?>>出账</option>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司账号：</div>
		<div id="dict_bank_select_1" class="fa_droplist">
			<input id="dict_bank_combo_1" type="text" class="dict_bank_combo" value="" />
			<input id="dict_bank_val_1" type="hidden" class="LoanRecord_dict_bank_id" value="" name="LoanRecord[dict_bank_id][]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">对方账号：</div>
		<div id="bank_select_1" class="fa_droplist">
			<input id="bank_combo_1" type="text" class="bank_combo" value="" />
			<input id="bank_val_1" type="hidden" class="LoanRecord_bank_id" value="" name="LoanRecord[bank_id][]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>金额：</div>
		<input type="text" class="form-control LoanRecord_amount" value="<?php echo number_format($model->amount, 2);?>" name="LoanRecord[amount][]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>借据：</div>
		<select class="form-control chosen-select LoanRecord_has_Ious" name="LoanRecord[has_Ious][]">
		<?php foreach (LoanRecord::$hasIous as $k => $v) {?>
			<option value="<?php echo $k;?>" <?php echo $model->has_Ious == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>操作人：</div>
		<select class="form-control chosen-select LoanRecord_created_by" name="LoanRecord[created_by][]">
		<?php foreach ($user_array as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo Yii::app()->user->userid == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text" class="form-control LoanRecord_comment" value="" name="LoanRecord[comment][]" />
	</div>
	<script type="text/javascript">
		$(function() {
			$("#dict_bank_combo_1").combobox(dict_bank_array, {}, 'dict_bank_select_1', 'dict_bank_val_1', false, '', 260);
			$("#bank_combo_1").combobox(bank_array, {}, 'bank_select_1', 'bank_val_1', false, '', 260);
			dateTimePick();
		});
	</script>
	<?php 
		}
	?>
	</div>
</div>

<div class="detail_body">
	<div id="add_list" class="ht_add_list" style="display:none;"><img src="/images/add.png">新增</div>
	<div class="btn_list">
		<button id="submit_btn" type="button" class="btn btn-primary btn-sm first" data-dismiss="modal">入账</button>
		<a href="<?php echo $back_url;?>">
			<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
		</a>
	</div>
</div>

<input id="CommonForms_form_type" type="hidden" value="<?php echo $baseform->form_type;?>" name="CommonForms[form_type]" />
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />
<?php $this->endWidget();?>

<script type="text/javascript">

var can_submit = true;
var tr_num = <?php echo $tr_num;?>;

<?php if ($msg) {?>
confirmDialog("<?php echo $msg;?>");
<?php }?>

//借贷方向 
$("#lending_direction_select").change(function() {
	$("#ShortLoan_lending_direction").val($(this).val());
	switch ($(this).val()) 
	{
		case 'borrow': //借入 
			$("#CommonForms_form_type").val("DQJK"); //短期借款 
			break;
		case 'lend': //借出 
			$("#CommonForms_form_type").val("DQDK"); //短期贷款 
			break;
		default: break;
	}
});

//有无借据 
$("#has_Ious_select").change(function() {
	$("#ShortLoan_has_Ious").val($(this).val());
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
		if (!data) return false; 
		$("#team_select").find("option:contains('" + data + "')").attr("selected", "selected");
	});
});

//新增
$("#add_list").click(function() {
	addRow();
});

//删除
$(".deleted_tr").live('click', function() {
	var tr = $(this).parent().parent();
	confirmDialog3("确认删除？", function() {
		tr.remove();
	});
});

//保存 
$("#save_submit, #submit_btn").click(function() {
	var is_submit = true;
	$(".LoanRecord_dict_bank_id").each(function() {
		var t = $(this);
		if (t.val() <= 0) {
			confirmDialog("请选择公司账号", function() {
				t.parent().find(".dict_bank_combo").focus();
			});
			is_submit = false;
			return false;
		}
	});
	if (!is_submit) return false;
/*
	$(".LoanRecord_bank_id").each(function() {
		var t = $(this);
		if (t.val() <= 0) {
			confirmDialog("请选择对方账号", function() {
				t.parent().find(".bank_combo").focus();
			});
			is_submit = false;
			return false;
		}
	});
	if (!is_submit) return false;
*/
	$(".LoanRecord_amount").each(function() {
		var t = $(this);
		if (t.val() == '' || isNaN(numChange(t.val())) || parseFloat(numChange(t.val())) <= 0) {
			confirmDialog("请输入金额", function() {
				t.focus();
			});
			is_submit = false;
			return false;
		}
	});
	if (!is_submit) return false;

	$(".LoanRecord_created_at").each(function() {
		var t = $(this);
		if (t.val() == '') {
			confirmDialog("请选择操作日期", function() {
				t.focus();
			});
			is_submit = false;
			return false;
		}
	});
	if (!is_submit) return false;
	
	if (can_submit) 
	{
		can_submit = false;
		notAnymore('submit_btn');
		$("#form_data").submit();
	}
});

//select 
//var title_array = <?php echo $title_array ? $title_array : '[]';?>;
//var company_array = <?php echo $company_array ? $company_array : '[]';?>;
var dict_bank_array = <?php echo $dict_bank_array ? $dict_bank_array : '[]';?>;
var bank_array = <?php echo $bank_array ? $bank_array : '[]';?>;

$(function() {
	$("#lending_direction_select").change();
	$("#has_Ious_select").change();
});

$(function(){
	$(".LoanRecord_reach_at").on("change",function(){
		$(".form_time").val($(this).val());
	});
})

</script>
