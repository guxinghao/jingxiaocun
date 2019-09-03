<?php 
$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true,
		'htmlOptions' => array(
				'id' => 'form_data',
				'enctype' => 'multipart/form-data'
		)
));
?>
<div class="shop_select_box">
	<div class="shop_more_one" style="display:none;">
		<div class="shop_more_one_l"><span class="bitian">*</span>转出公司：</div>
		<span title="<?php echo $model->titleOutput->name;?>"><?php echo $model->titleOutput->short_name;?></span>
		<input id="TransferAccounts_title_output_id" type="hidden" value="<?php echo $model->title_output_id;?>" name="TransferAccounts[title_output_id]" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>转出账户：</div>
		<div id="output_bank_select" class="fa_droplist">
			<input id="output_bank_combo" type="text" value="<?php echo $model->outputBank ? $model->outputBank->dict_name.'('.$model->outputBank->bank_number.')' : '';?>"  readonly/>
			<input id="TransferAccounts_output_bank_id" type="hidden" value="<?php echo $model->output_bank_id;?>" name="TransferAccounts[output_bank_id]" />
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>转入账户：</div>
		<div id="input_bank_select" class="fa_droplist">
			<input id="input_bank_combo" type="text" value="<?php echo $model->inputBank ? $model->inputBank->dict_name.'('.$model->inputBank->bank_number.')' : '';?>"  readonly/>
			<input id="TransferAccounts_input_bank_id" type="hidden" value="<?php echo $model->input_bank_id;?>" name="TransferAccounts[input_bank_id]" />
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>金额：</div>
		<input id="TransferAccounts_amount" type="text" class="form-control" value="<?php echo number_format($model->amount, 2);?>" name="TransferAccounts[amount]"<?php echo !$model->id || $baseform->form_status == 'unsubmit' ? '' : ' readonly="readonly"';?> />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>类型：</div>
		<select id="TransferAccounts_type" class="form-control chosen-select" name="TransferAccounts[type]">
		<?php foreach (TransferAccounts::$type as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $model->type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="shop_more_one" style="display:none;">
		<div class="shop_more_one_l"><span class="bitian">*</span>转入公司：</div>
		<span title="<?php echo $model->titleInput->name;?>"><?php echo $model->titleInput->short_name;?></span>
		<input id="TransferAccounts_title_input_id" type="hidden" value="<?php echo $model->title_input_id;?>" name="TransferAccounts[title_input_id]" />
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
			<option value="<?php echo $key;?>"<?php echo $baseform->belong->team->id == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入账日期：</div>
		<input id="TransferAccounts_reach_at" type="text" class="form-control form-date forreset date input_backimg" value="<?php echo $model->reach_at > 0 ? date('Y-m-d', $model->reach_at):$baseform->form_time;?>" placeholder="选择日期" name="TransferAccounts[reach_at]" />
	</div>
	<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>登记日期：</div>
				<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
						<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  value="<?php echo $baseform->form_time?$baseform->form_time:date("Y-m-d");?>" readonly>
				</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="TransferAccounts_comment" type="text" class="form-control" value="<?php echo $model->comment;?>" name="TransferAccounts[comment]" />
	</div>
</div>

<input id="CommonForms_form_type" type="hidden" value="<?php echo $baseform->form_type;?>" name="CommonForms[form_type]">
<input id="CommonForms_comment" type="hidden" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]" />
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />
<div class="btn_list">
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">入账</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget();?>


<script type="text/javascript">
var can_submit = true;
var title_output_id = <?php echo $model->title_output_id ? $model->title_output_id : 0;?>;
var title_input_id = <?php echo $model->title_input_id ? $model->title_input_id : 0;?>;
var output_bank_id = <?php echo $model->output_bank_id ? $model->output_bank_id : 0;?>;
var input_bank_id = <?php echo $model->input_bank_id ? $model->input_bank_id : 0;?>;

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
$("#save_submit, #submit_btn").click(function() {
	var title_output_id = $("#TransferAccounts_title_output_id").val(); 
	var title_input_id = $("#TransferAccounts_title_input_id").val();
	var output_bank_id = $("#TransferAccounts_output_bank_id").val();
	var input_bank_id = $("#TransferAccounts_input_bank_id").val();
	var amount = numChange($("#TransferAccounts_amount").val());
	
	if (output_bank_id <= 0) 
	{
		confirmDialog("请选择转出账户", function() {
			$("#output_bank_combo").focus();
		});
		return false;
	}
	
	if (input_bank_id <= 0) 
	{
		confirmDialog("请选择转入账户", function() {
			$("#input_bank_combo").focus();
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

	confirmDialog3("确认保存修改并入账？", function() {
		if (can_submit) 
		{
			can_submit = false;
			notAnymore('save_submit');
			notAnymore('submit_btn');
			$("#form_data").submit();
		}
	});
});


//var title_output_array = <?php echo $title_output_array ? $title_output_array : '[]';?>;
//var title_input_array = <?php echo $title_input_array ? $title_input_array : '[]';?>;
//var output_bank_array = <?php echo $output_bank_array ? $output_bank_array : '[]';?>;
//var input_bank_array = <?php echo $input_bank_array ? $input_bank_array : '[]';?>;

$(function() {
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>

//	$("#title_output_combo").combobox(title_output_array, {}, "title_output_select", "TransferAccounts_title_output_id", false);
//	$("#title_input_combo").combobox(title_input_array, {}, "title_input_select", "TransferAccounts_title_input_id", false);
//	$("#output_bank_combo").combobox(output_bank_array, {}, "output_bank_select", "TransferAccounts_output_bank_id", false, '', 260);
//	$("#input_bank_combo").combobox(input_bank_array, {}, "input_bank_select", "TransferAccounts_input_bank_id", false, '', 260);

});
$(function(){
	$("#TransferAccounts_reach_at").on("change",function(){
		$(".start_time").val($(this).val());
	});
})
</script>