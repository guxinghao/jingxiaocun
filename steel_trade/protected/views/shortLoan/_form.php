<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/edit.css" />

<?php 
$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true, 
		'htmlOptions' => array(
				'id' => "form_data", 
				'enctype' => "multipart/form-data",
		),
));
?>
<div class="edit_body">
	<div class="main_body">
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
			<div id="title_select" class="fa_droplist">
			<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
				<input id="title_combo" type="text" value="<?php echo $model->title->short_name;?>" />
			<?php } else {?>
				<input id="title_combo" type="text" class="form-control" value="<?php echo $model->title->short_name;?>" readonly="readonly" />
			<?php }?>			
				<input id="ShortLoan_title_id" type="hidden" value="<?php echo $model->title_id;?>" name="ShortLoan[title_id]" />
			</div>
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>借/贷公司：</div>
			<div id="company_select" class="fa_droplist">
			<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
				<input id="company_combo" type="text" value="<?php echo $model->company->name;?>" />
			<?php } else {?>
				<input id="company_combo" type="text" class="form-control" value="<?php echo $model->company->name;?>" readonly="readonly" />
			<?php }?>
				<input id="ShortLoan_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="ShortLoan[company_id]" />
			</div>
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>合约金额：</div>
			<input id="ShortLoan_amount" type="text" class="form-control" value="<?php echo $model->amount>0?number_format($model->amount, 2):"";?>" name="ShortLoan[amount]"<?php echo $model->id && $baseform->form_status != 'unsubmit' ? ' readonly="readonly"' : '';?> />
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>利率(‰)：</div>
			<input id="ShortLoan_interest_rate" type="text" class="form-control" value="<?php  if($model->id){ echo number_format($model->interest_rate, 4);}else{ echo "";}?>" name="ShortLoan[interest_rate]"<?php echo $model->id && $baseform->form_status != 'unsubmit' ? ' readonly="readonly"' : '';?> />
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>借贷方向：</div>
			<select id="lending_direction_select" class="form-control chosen-select"  <?php echo ($model->id||($model->id&&$baseform->form_status != 'unsubmit' ))? ' disabled="disabled"' : '';?>>
			<?php foreach (ShortLoan::$lendingDirection as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->lending_direction == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
			<input id="ShortLoan_lending_direction" type="hidden" class="form-control" value="<?php echo $model->lending_direction;?>" name="ShortLoan[lending_direction]" />
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>有无借据：</div>
			<select id="has_Ious_select" class="form-control chosen-select"<?php echo $model->id && $baseform->form_status != 'unsubmit' ? ' disabled="disabled"' : '';?>>
			<?php foreach (ShortLoan::$hasIous as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->has_Ious == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
			<input id="ShortLoan_has_Ious" type="hidden" class="form-control" value="<?php echo $model->has_Ious;?>" name="ShortLoan[has_Ious]" />
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>负责人：</div>
			<select id="CommonForms_owned_by" class="form-control chosen-select" name="CommonForms[owned_by]">
			<?php foreach ($user_array as $key => $value) {?>
				<option value="<?php echo $key;?>"<?php echo $key == $baseform->owned_by ? ' selected="selected"' : '';?>><?php echo $value;?></option>
			<?php }?>
			</select>
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
						<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  value="<?php echo $baseform->form_time?$baseform->form_time:date("Y-m-d");?>">
				</div>
		</div>
		<div class="shop_more_one">
			<div class="shop_more_one_l">开始日期：</div>
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<input id="ShortLoan_start_time" type="text" class="form-control form-date input_backimg" value="<?php echo $model->start_time > 0 ? date('Y-m-d', $model->start_time) : '';?>" name="ShortLoan[start_time]" placeholder="选择日期" />
		<?php } else {?>
			<input id="ShortLoan_start_time" type="text" class="form-control" value="<?php echo $model->start_time > 0 ? date('Y-m-d', $model->start_time) : '';?>" name="ShortLoan[start_time]" readonly="readonly" />
		<?php }?>
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l">结束日期：</div>
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<input id="ShortLoan_end_time" type="text" class="form-control form-date input_backimg" value="<?php echo $model->end_time > 0 ? date('Y-m-d', $model->end_time) : '';?>" name="ShortLoan[end_time]" placeholder="选择日期" />
		<?php } else {?>
			<input id="ShortLoan_end_time" type="text" class="form-control" value="<?php  echo $model->end_time > 0 ? date('Y-m-d', $model->end_time) : '';?>" name="ShortLoan[end_time]" readonly="readonly" />
		<?php }?>
		</div>
		<div class="shop_more_one">
			<div class="shop_more_one_l">备注：</div>
			<input id="CommonForms_comment" type="text" class="form-control" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]" />
		</div>
		
	</div>
	
	<div class="detail_body">
		<div class="btn_list">
		<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
			<button id="save_submit" type="button" class="btn btn-primary btn-sm first" data-dismiss="modal">保存提交</button>
			<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
		<?php } else {?>
			<button id="submit_btn" type="button" class="btn btn-primary btn-sm first" data-dismiss="modal">保存</button>
		<?php }?>
			<a href="<?php echo $back_url;?>">
				<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
			</a>
		</div>
	</div>
</div>

<input id="CommonForms_form_type" type="hidden" value="<?php echo $baseform->form_type;?>" name="CommonForms[form_type]" />
<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />
<?php $this->endWidget();?>

<script type="text/javascript">
var can_submit = true;

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

//保存 
$("#save_submit, #submit_btn").click(function() {
	var title_id = $("#ShortLoan_title_id").val();
	var company_id = $("#ShortLoan_company_id").val();
	var amount = numChange($("#ShortLoan_amount").val());
	var interest_rate = numChange($("#ShortLoan_interest_rate").val());
	var start_time = $("#ShortLoan_start_time").val();
	var end_time = $("#ShortLoan_end_time").val();

	if (!title_id || title_id <= 0) 
	{
		confirmDialog("请选择公司", function() {
			$("#title_combo").focus();
		});
		return false;
	} 
	if (!company_id || company_id <= 0) 
	{
		confirmDialog("请选择借/贷公司", function() {
			$("#company_combo").focus();
		});
		return false;
	}
	if (!amount || isNaN(amount) || parseFloat(amount) <= 0) 
	{
		confirmDialog("请输入合约金额", function() {
			$("#ShortLoan_amount").focus();
		});
		return false;
	}
	if (!interest_rate || isNaN(interest_rate)) 
	{
		confirmDialog("请输入利率", function() {
			$("#ShortLoan_interest_rate").focus();
		});
		return false;
	}
// 	if (!start_time) 
// 	{
// 		confirmDialog("请选择开始日期", function() {
// 			$("#ShortLoan_start_time").focus();
// 		});
// 		return false;
// 	} 
// 	if (!end_time) 
// 	{
// 		confirmDialog("请选择结束日期", function() {
// 			$("#ShortLoan_end_time").focus();
// 		});
// 		return false;
// 	}
	if ($(this).attr("id") == "save_submit") 
		$(this).parent().append('<input type="hidden" name="CommonForms[submit]" value="yes">');	

	if (can_submit) 
	{
		can_submit = false;
		notAnymore('save_submit');
		notAnymore('submit_btn');
		$("#form_data").submit();
	}
});

//select 
var title_array = <?php echo $title_array ? $title_array : '[]';?>;
var company_array = <?php echo $company_array ? $company_array : '[]';?>;

$(function() {
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	$("#title_combo").combobox(title_array, {}, 'title_select', 'ShortLoan_title_id', false);
	$("#company_combo").combobox(company_array, {}, 'company_select', 'ShortLoan_company_id', false);
<?php }?>

	$("#lending_direction_select").change();
	$("#has_Ious_select").change();
});

</script>
