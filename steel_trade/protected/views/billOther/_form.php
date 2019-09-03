<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/edit.css" />
<style>
td select{float:left;margin-right:20px;}
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
				<input id="FrmBillOther_title_id" type="hidden" value="<?php echo $model->title_id;?>" name="FrmBillOther[title_id]" />
			</div>
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l">公司账户：</div>
			<div id="dict_bank_select" class="fa_droplist">
			<?php  if (!$model->id || $baseform->form_status == 'unsubmit') {?>
				<input id="dict_bank_combo" type="text" value="<?php echo $model->dictBank ? $model->dictBank->dict_name.'('.$model->dictBank->bank_number.')' : '';?>" />
			<?php } else {?>
 				<input id="dict_bank_combo" type="text" class="form-control" value="<?php echo $model->dictBank ? $model->dictBank->dict_name.'('.$model->dictBank->bank_number.')' : '';?>" readonly="readonly" />
			<?php }?>
				<input id="FrmBillOther_dict_bank_id" type="hidden" value="<?php echo $model->dict_bank_id;?>" name="FrmBillOther[dict_bank_id]" />
			</div>
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l">结算单位：</div>
			<div id="company_select" class="fa_droplist">
			<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
				<input id="company_combo" type="text" value="<?php echo $model->company->name;?>" />
			<?php } else {?>
				<input id="company_combo" type="text" class="form-control" value="<?php echo $model->company->name;?>" readonly="readonly" />
			<?php }?>
				<input id="FrmBillOther_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="FrmBillOther[company_id]" />
			</div>
		</div>
	
		<div class="shop_more_one">
			<div class="shop_more_one_l">结算账户：</div>
			<div id="bank_select" class="fa_droplist">
			<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
				<input id="bank_combo" type="text" value="<?php echo $model->bank ? $model->bank->company_name.'('.$model->bank->bank_number.')' : '';?>" />
			<?php } else {?>
				<input id="bank_combo" type="text" class="form-control" value="<?php echo $model->bank ? $model->bank->company_name.'('.$model->bank->bank_number.')' : '';?>" readonly="readonly" />
			<?php }?>
				<input id="FrmBillOther_bank_id" type="hidden" value="<?php echo $model->bank_id;?>" name="FrmBillOther[bank_id]" />
			</div>
		</div>
		
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>总金额：</div>
			<input id="FrmBillOther_amount" type="text" class="form-control" value="<?php echo number_format($model->amount, 2);?>" name="FrmBillOther[amount]" readonly="readonly" />
		</div>

		<div class="shop_more_one">
			<div class="shop_more_one_l">备注：</div>
			<input id="FrmBillOther_comment" type="text" class="form-control" value="<?php echo $model->comment;?>" name="FrmBillOther[comment]" />
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
			<input id="FrmBillOther_team_id" type="hidden" value="<?php echo $baseform->belong->team->id;?>" name="FrmBillOther[team_id]" />
		</div>
		<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>登记日期：</div>
				<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
						<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  value="<?php echo $baseform->form_time?$baseform->form_time:date("Y-m-d");?>">
				</div>
		</div>
	</div>
	
	<div class="detail_body">
	<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
		<button id="add_list" type="button" class="btn_add"></button>
	<?php }?>
		<div class="detail_table">
			<table id="cght_tb">
				<thead>
					<tr>
						<th style="width: 8%;">序号</th>
						<th style="width: 8%;<?php echo (!$model->id || $baseform->form_status == 'unsubmit') ? '' : ' display: none;';?>">操作</th>
						<th style="width: 60%;">费用类别</th>
						<th style="width: 24%;">金额</th>
					</tr>
				</thead>
				
				<tbody>
				<?php $tr_num = 1; 
				if (is_array($model->details) && count($model->details) > 0) {
					foreach ($model->details as $item) {
				?>
					<tr>
						<td style="text-indent: 10px;"><?php echo $tr_num;?></td>
						<td<?php echo (!$model->id || $baseform->form_status == 'unsubmit') ? '' : ' style="display: none;"';?>>
							<i class="icon icon-trash deleted_tr"></i>
							<input type="hidden" class="td_id" value="<?php echo $item->id;?>" name="td_id[]" />
						</td>
						<td>
							<select class="form-control chosen-select td_type"<?php echo (!$model->id || $baseform->form_status == 'unsubmit') ? '' : ' disabled="disabled"';?> style="margin-right:20px;">
							<option value="0" selected="selected">请选择类型</option>
							<?php foreach ($type_array as $k => $v) {?>
								<option value="<?php echo $k;?>"<?php echo $k == $item->type_1 ? ' selected="selected"' : '';?>><?php echo $v;?></option>
							<?php }?>
							</select>
							<input type="hidden" class="td_type_val" value="<?php echo $item->type_1;?>" name="td_type1[]" />
							<div class="second_type">
								<select class="form-control chosen-select td_type2" style="margin-right:20px;" <?php echo (!$model->id||$baseform->form_status=='unsubmit')?'':'disabled="disabled"';?>>
								<?php 
								if($item->type_2>0){
									$type_arr2 = DictRecordType::getSecTypeList($item->type_1);
									foreach ($type_arr2 as $k=>$v){
								?>
								<option value="<?php echo $k;?>"<?php echo $k == $item->type_2 ? ' selected="selected"' : '';?>><?php echo $v;?></option>
								<?php
									}
								}
								?>
								</select>
								<input type="hidden" class="td_type_val2" value="<?php echo $item->type_2;?>" name="td_type2[]" />
							</div>
							
						</td>
						<td>
							<input type="text" class="form-control td_fee" value="<?php echo number_format($item->fee, 2);?>" name="td_fee[]"<?php echo (!$model->id || $baseform->form_status == 'unsubmit') ? '' : ' readonly="readonly"';?> />
						</td>
					</tr>
				<?php $tr_num++; } } else {?>
					<tr>
						<td style="text-indent: 10px;"><?php echo $tr_num;?></td>
						<td>
							<i class="icon icon-trash deleted_tr"></i>
							<input type="hidden" class="td_id" value="" name="td_id[]" />
						</td>
						<td>
							<select class="form-control chosen-select td_type" style="margin-right:20px;">
							<option value="0" selected="selected">请选择类型</option>
							<?php foreach ($type_array as $k => $v) {?>
								<option value="<?php echo $k;?>"><?php echo $v;?></option>
							<?php }?>
							</select>
							<input type="hidden" class="td_type_val" value="0" name="td_type1[]" />
							<div class="second_type">
								<select class="form-control chosen-select td_type2" style="margin-right:20px;">
								</select>
								<input type="hidden" class="td_type_val2" value="0" name="td_type2[]" />
							</div>
						</td>
						<td><input type="text" class="form-control td_fee" name="td_fee[]" /></td>
					</tr>
				<?php $tr_num++; }?>
				</tbody>
			</table>
		</div>
		
		<input id="CommonForms_form_type" type="hidden" value="<?php echo $baseform->form_type;?>" name="CommonForms[form_type]" />
		<input id="CommonForms_comment" type="hidden" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]" />
		<input id="last_update" type="hidden" name="last_update" value="<?php echo $baseform->last_update;?>" />
		
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
<?php $this->endWidget();?>

<script type="text/javascript">
var can_submit = true;
var tr_num = <?php echo $tr_num;?>;

<?php if ($msg) {?>
confirmDialog("<?php echo $msg;?>");
<?php }?>

//类型 
$(".td_type").live("change", function() {
	var that = $(this);
	var id = $(this).val();
	$(this).next().val(id);
	$.post("/index.php/billOther/getType",{"id":id},function(data){
		if(data == "fail"){
			that.next().next().find(".td_type2").html('');
			that.next().next().find(".td_type_val2").val(0);
		}else{
			that.next().next().html(data);
		}
	});
});

$(".td_type2").live("change", function() {
	var id = $(this).val();
	$(this).next().val(id);
});

//获取公司账户 
function getDictBank(id) 
{
	id = id ? id : $("#FrmBillOther_title_id").val();
	$.get('/index.php/dictBankInfo/getBankList', 
	{
		'id': id
	}, 
	function(data)
	{
		dict_bank_array = data ? data : [];
		$("#dict_bank_select").html('<input id="dict_bank_combo" type="text" value="" /><input id="FrmBillOther_dict_bank_id" type="hidden" value="" name="FrmBillOther[dict_bank_id]" />');
		$("#dict_bank_combo").combobox(dict_bank_array, {}, 'dict_bank_select', 'FrmBillOther_dict_bank_id', false, '', 250);
	});
}

//获取结算账户 
function getBank(id) 
{
	id = id ? id : $("#FrmBillOther_company_id").val();
	$.get('/index.php/bankInfo/getBankList', 
	{
		'id': id
	}, 
	function(data) 
	{
		dict_bank_array = data ? data : [];
		$("#bank_select").html('<input id="bank_combo" type="text" value="" /><input id="FrmBillOther_bank_id" type="hidden" value="" name="FrmBillOther[bank_id]" />');
		$("#bank_combo").combobox(dict_bank_array, {}, 'bank_select', 'FrmBillOther_bank_id', false, '', 250);
	});
}

//计算总和 
function getTotal() 
{
	var total_fee = 0.0;
	$("#cght_tb tbody tr").each(function(){
		var td_fee = $(this).find(".td_fee");
		var fee = td_fee.val() ? numChange(td_fee.val()) : 0;
		if (fee && isNaN(fee)) 
		{
			confirmDialog("金额格式不正确", function(){
				td_fee.val(numberFormat(0, 2, '.', ','));
				td_fee.focus();
			})
			return false;
		}
		total_fee += parseFloat(fee);
	});
	$("#FrmBillOther_amount").val(numberFormat(total_fee, 2, '.', ','));
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
		if (!data) return false; 
		$("#team_select").find("option:contains('" + data + "')").attr("selected", "selected");
		$("#FrmBillOther_team_id").val($("#team_select").val());
	});
});

//备注 
$("#FrmBillOther_comment").change(function(){
	$("#CommonForms_comment").val($(this).val());
});

//金额 
$(".td_fee").live("change", function(){
	getTotal();
});

//新增 
$("#add_list").click(function(){
	var str = 
		'<tr>' + 
			'<td style="text-indent: 10px;">' + tr_num + '</td>' + 
			'<td>' + 
				'<i class="icon icon-trash deleted_tr"></i>' + 
				'<input type="hidden" class="td_id" value="" name="td_id[]" />' + 
			'</td>' + 
			'<td>' + 
				'<select class="form-control chosen-select td_type" style="margin-right:20px;">' + 
					'<option value="0" selected="selected">请选择类型</option>'+
				<?php foreach ($type_array as $k => $v) {?>
					'<option value="<?php echo $k;?>"><?php echo $v;?></option>' + 
				<?php }?>
				'</select>' +
				'<input type="hidden" class="td_type_val" value="0" name="td_type1[]" />'+ 
				'<div class="second_type">'+
					'<select class="form-control chosen-select td_type2">'+
					'</select>'+
					'<input type="hidden" class="td_type_val2" value="0" name="td_type2[]" />'+
				'</div>'+
			'</td>' + 
			'<td><input type="text" class="form-control td_fee" name="td_fee[]" /></td>' + 
		'</tr>'
		$("#cght_tb tbody").append(str);
		tr_num++;
});

//删除 
$(".deleted_tr").live('click', function(){
	$(this).parent().parent().remove();
	tr_num = 1;
	$("#cght_tb tbody tr").each(function(){
		$(this).find("td").eq(0).html(tr_num);
		tr_num++;
	});
	getTotal();
});

//保存 
$("#submit_btn, #save_submit").click(function(){
	var title_id = $("#FrmBillOther_title_id").val(); //公司 
	var company_id = $("#FrmBillOther_company_id").val(); //结算单位 
	var dict_bank_id = $("#FrmBillOther_dict_bank_id").val(); //公司账户 

	if (!title_id || title_id <= 0) 
	{
		confirmDialog("请选择公司", function(){
			$("#title_combo").focus();
		});
		return false;
	}
// 	if (!dict_bank_id || dict_bank_id <= 0) 
// 	{
// 		confirmDialog("请选择公司账户", function(){
// 			$("#dict_bank_combo").focus();
// 		});
// 		return false;
// 	}
// 	if (!company_id || company_id <= 0) 
// 	{
// 		confirmDialog("请选择结算单位", function(){
// 			$("#company_combo").focus();
// 		});
// 		return false;
// 	}
	if ($("#cght_tb tbody tr").length == 0) 
	{
		confirmDialog("请至少添加一条明细");
		return false;
	}
	
	is_submit = true;
	$("#cght_tb tbody tr").each(function(){
		var td_fee = $(this).find(".td_fee");
		var fee = numChange(td_fee.val());
		if (!fee || isNaN(fee)) //|| parseFloat(fee) <= 0 
		{
			confirmDialog("金额必须为数字", function(){
				td_fee.focus();
			})
			is_submit = false;
			return false;
		}
	});
	if (!is_submit) return false;
	
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
var dict_bank_array = <?php echo $dict_bank_array ? $dict_bank_array : '[]';?>;
var bank_array = <?php echo $bank_array ? $bank_array : '[]';?>;

$(function(){
<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
	$("#title_combo").combobox(title_array, {}, 'title_select', 'FrmBillOther_title_id', false, 'getDictBank()');
	$("#company_combo").combobox(company_array, {}, 'company_select', 'FrmBillOther_company_id', false, 'getBank()',160);
	$("#dict_bank_combo").combobox(dict_bank_array, {}, 'dict_bank_select', 'FrmBillOther_dict_bank_id', false, '', 250);
	$("#bank_combo").combobox(bank_array, {}, 'bank_select', 'FrmBillOther_bank_id', false, '', 250);
<?php }?>
});

</script>