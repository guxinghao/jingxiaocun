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
	<div class="shop_more_one" style="">
		<div class="shop_more_one_l"><span class="bitian">*</span>转出公司：</div>
		<div id="title_output_select" class="fa_droplist">
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<input id="title_output_combo" type="text" value="<?php echo $model->titleOutput->short_name;?>" />
		<?php } else {?>
			<input id="title_output_combo" type="text" class="form-control" value="<?php echo $model->titleOutput->short_name;?>" readonly="readonly" />
		<?php }?>
			<input id="TransferAccounts_title_output_id" type="hidden" value="<?php echo $model->title_output_id;?>" name="TransferAccounts[title_output_id]" />
		</div>
	</div>
	
	<div class="shop_more_one" >
		<div class="shop_more_one_l"><span class="bitian">*</span>转出账户：</div>
		<div id="output_bank_select" class="fa_droplist">
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<select id="output_banks" class="form-control chosen-select" name="TransferAccounts[output_bank_id]">
				<?php foreach($bank_json as $k=>$v){?>
				<option value="<?php echo $k?>" <?php  echo $model->output_bank_id == $k ? ' selected="selected"' : ''; ?>><?php echo $v;?></option>
				<?php }?>
			</select>
		<?php } else {?>
		<input id="output_bank_combo" type="text" value="<?php echo $model->outputBank ? $model->outputBank->dict_name.'('.$model->outputBank->bank_number.')' : '';?>" readonly />
		<input id="TransferAccounts_output_bank_id" type="hidden" value="<?php echo $model->output_bank_id;?>" name="TransferAccounts[output_bank_id]" />
		<?php }?>
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>金额：</div>
		<input id="TransferAccounts_amount" type="text" class="form-control" value="<?php echo $model->amount>0?number_format($model->amount, 2):"";?>" name="TransferAccounts[amount]"<?php echo !$model->id || $baseform->form_status == 'unsubmit' ? '' : ' readonly="readonly"';?> />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>类型：</div>
	<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
		<select id="TransferAccounts_type" class="form-control chosen-select" name="TransferAccounts[type]">
	<?php } else {?>
		<select id="TransferAccounts_type" class="form-control chosen-select" disabled="disabled">
	<?php }?>
		<?php foreach (TransferAccounts::$type as $k => $v) {?>
			<option value="<?php echo $k;?>"<?php echo $model->type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	<?php } else {?>
		<input type="hidden" value="<?php echo $model->type;?>" name="TransferAccounts[type]" />
	<?php }?>
	</div>
	
	<div class="shop_more_one" style="">
		<div class="shop_more_one_l"><span class="bitian">*</span>转入公司：</div>
		<div id="title_input_select" class="fa_droplist">
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<input id="title_input_combo" type="text" value="<?php echo $model->titleInput->short_name;?>" />
		<?php } else {?>
			<input id="title_input_combo" type="text" class="form-control" value="<?php echo $model->titleInput->short_name;?>" readonly="readonly" />
		<?php }?>
			<input id="TransferAccounts_title_input_id" type="hidden" value="<?php echo $model->title_input_id;?>" name="TransferAccounts[title_input_id]" />
		</div>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>转入账户：</div>
		<div id="input_bank_select" class="fa_droplist">
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<select id="input_banks" class="form-control chosen-select" name="TransferAccounts[input_bank_id]">
				<?php foreach($bank_json as $k=>$v){?>
				<option value="<?php echo $k?>" <?php  echo $model->input_bank_id == $k ? ' selected="selected"' : ''; ?>><?php echo $v;?></option>
				<?php }?>
			</select>
		<?php } else {?>
			<input id="input_bank_combo" type="text" value="<?php echo $model->inputBank ? $model->inputBank->dict_name.'('.$model->inputBank->bank_number.')' : '';?>" />
			<input id="TransferAccounts_input_bank_id" type="hidden" value="<?php echo $model->input_bank_id;?>" name="TransferAccounts[input_bank_id]" />
		<?php }?>
			
		</div>
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>负责人：</div>
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
				<div class="shop_more_one_l"><span class="bitian">*</span>登记时间：</div>
				<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
						<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  value="<?php echo $baseform->form_time>0?$baseform->form_time:date("Y-m-d");?>">
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
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	<button id="save_submit" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存入账</button>
<?php }?>
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
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

//转出账户
function getOutputBank() {
	title_output_id = $("#TransferAccounts_title_output_id").val();
	if(title_output_id==0)return;
	var bank_now=$('#output_banks').val();
	$.get("/index.php/dictBankInfo/getBankList", 
	{
		'id': title_output_id
	}, 
	function(data) 
	{
		var json=eval('('+data+')');
		var options='';
		for(var i=0;i<json.length;i++){
			options+='<option value="'+json[i].id+'">'+json[i].name+'</option>';
		}
		$('#output_banks').html(options);
		$('#output_banks').val(bank_now);
		// output_bank_array = data ? data : [];
		// $("#output_bank_select").html('<input id="output_bank_combo" type="text" value="" /><input id="TransferAccounts_output_bank_id" type="hidden" value="" name="TransferAccounts[output_bank_id]" />');
		// $("#output_bank_combo").combobox(output_bank_array, {}, "output_bank_select", "TransferAccounts_output_bank_id", false, '', 260);
	});
}

//转入账户
function getInputBank() {
	title_input_id = $("#TransferAccounts_title_input_id").val();
	if(!title_input_id)return;
	var bank_now=$('#input_banks').val();
	$.get("/index.php/dictBankInfo/getBankList", 
	{
		'id': title_input_id
	}, 
	function(data) 
	{
		var json=eval('('+data+')');
		var options='';
		for(var i=0;i<json.length;i++){
			options+='<option value="'+json[i].id+'">'+json[i].name+'</option>';
		}
		$('#input_banks').html(options);	
		$('#input_banks').val(bank_now);
		// input_bank_array = data ? data : [];
		// $("#input_bank_select").html('<input id="input_bank_combo" type="text" value="" /><input id="TransferAccounts_input_bank_id" type="hidden" value="" name="TransferAccounts[input_bank_id]" />');
		// $("#input_bank_combo").combobox(input_bank_array, {}, "input_bank_select", "TransferAccounts_input_bank_id", false, '', 260);
	});
}

//业务员
$("#CommonForms_owned_by").change(function(){
	var owned_by_val = $(this).val();
	$.get("/index.php/user/getTeam", 
	{
		'user_id': owned_by_val
	}, 
	function(data) 
	{
		if (data) 
			$("#team_select").find("option:contains('" + data + "')").attr("selected", "selected");
		else 
			$("#team_select").find("option[value='']").attr("selected", "selected");
		
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

	if (title_output_id <= 0) 
	{
		confirmDialog("请选择转出公司", function() {
			$("#title_output_combo").focus();
		});
		return false;
	}
	if (output_bank_id <= 0) 
	{
		confirmDialog("请选择转出账户", function() {
			$("#output_bank_combo").focus();
		});
		return false;
	}
	if (title_input_id <= 0) 
	{
		confirmDialog("请选择转入公司", function() {
			$("#title_input_combo").focus();
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
	if (amount == '' || isNaN(amount) || parseFloat(amount) <= 0) 
	{
		confirmDialog("请填写金额", function(){
			$("#TransferAccounts_amount").focus();
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
	if ($(this).attr("id") == 'save_submit') 
		$(this).parent().append('<input type="hidden" name="CommonForms[submit]" value="yes">');
	
	if (can_submit) 
	{
		can_submit = false;
		notAnymore('save_submit');
		notAnymore('submit_btn');
		$("#form_data").submit();
	}
});


var title_output_array = <?php echo $title_output_array ? $title_output_array : '[]';?>;
var title_input_array = <?php echo $title_input_array ? $title_input_array : '[]';?>;
// var output_bank_array = <?php echo $bank_json ? $bank_json : '[]';?>;
// var input_bank_array = <?php echo $bank_json ? $bank_json : '[]';?>;

$(function() {
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>

<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	$("#title_output_combo").combobox(title_output_array, {}, "title_output_select", "TransferAccounts_title_output_id", false, 'getOutputBank()');
	$("#title_input_combo").combobox(title_input_array, {}, "title_input_select", "TransferAccounts_title_input_id", false, 'getInputBank()');
	// $("#output_bank_combo").combobox(output_bank_array, {}, "output_bank_select", "TransferAccounts_output_bank_id", false, '', 260);
	// $("#input_bank_combo").combobox(input_bank_array, {}, "input_bank_select", "TransferAccounts_input_bank_id", false, '', 260);
	getOutputBank();
	getInputBank();
<?php }?>
	
});
</script>