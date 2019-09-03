<style>
<!--
.icon{ cursor: pointer;}
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

$is_supply = 0;
$is_warehouse = 0;
switch ($model->type) 
{
	case 'warehouse': //仓库返利
		$is_warehouse = 1;
		break;
	case 'cost': //仓储费用
		$is_warehouse = 1; 
		break;
	case 'supply': //钢厂返利
		$is_supply = 1; 
		break;
	default: 
		break;
}
?>
<div class="shop_select_box">
	<input type="hidden" id="BillRebate_type" value="<?php echo $model->type;?>" name="BillRebate[type]" />

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="title_select" class="fa_droplist">
		<?php if (!$model->id) {?>
			<input type="text" id="title_combo" value="<?php echo $model->title->short_name;?>" />
		<?php } else {?>
			<input type="text" id="title_combo" class="form-control" value="<?php echo $model->title->short_name;?>" readonly="readonly" />
		<?php }?>
			<input type="hidden" id="BillRebate_title_id" value="<?php echo $model->title_id;?>" name="BillRebate[title_id]" />
		</div>
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l">
			<span class="bitian">*</span>
			<?php 
				if ($is_supply > 0) echo "供应商："; 
				elseif ($is_warehouse > 0) echo "仓库结算单位：";
			?>
		</div>
		<div id="company_select" class="fa_droplist">
		<?php if (!$model->id) {?>
			<input id="company_combo" type="text" value="<?php echo $model->company->short_name;?>" />
		<?php } else {?>
			<input id="company_combo" type="text" class="form-control" value="<?php echo $model->company->short_name;?>" readonly="readonly" />
		<?php }?>
			<input id="BillRebate_company_id" type="hidden" value="<?php echo $model->company_id;?>" name="BillRebate[company_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开始时间：</div>
	<?php if (!$model->id) {?>
		<input id="BillRebate_start_time" type="text" class="form-control form-date input_backimg" value="<?php echo $model->start_time > 0 ? date('Y-m-d', $model->start_time) : '';?>" placeholder="选择日期" name="BillRebate[start_time]" />
	<?php } else {?>
		<input type="text" id="BillRebate_start_time" class="form-control form-date" value="<?php echo $model->start_time > 0 ? date('Y-m-d', $model->start_time) : '';?>" placeholder="选择日期" name="BillRebate[start_time]" readonly="readonly" />
	<?php }?>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结束时间：</div>
	<?php if (!$model->id) {?>
		<input id="BillRebate_end_time" type="text" class="form-control form-date input_backimg" value="<?php echo $model->end_time > 0 ? date('Y-m-d', $model->end_time) : '';?>" placeholder="选择日期" name="BillRebate[end_time]" />
	<?php } else {?>
		<input type="text" id="BillRebate_end_time" class="form-control form-date" value="<?php echo $model->end_time > 0 ? date('Y-m-d', $model->end_time) : '';?>" placeholder="选择日期" name="BillRebate[end_time]" readonly="readonly" />
	<?php }?>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>登记时间：</div>
		<input id="form_time" type="text" class="form-control form-date input_backimg" value="<?php echo $baseform->form_time?$baseform->form_time:date("Y-m-d");?>" placeholder="选择日期" name="CommonForms[form_time]" />
	</div>
	<?php if ($is_warehouse > 0) {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>仓库：</div>
		<div id="warehouse_select" class="fa_droplist">
			<input type="text" id="warehouse_combo" class="form-control" value="<?php echo $model->warehouse->name;?>" readonly="readonly" />
			<input type="hidden" id="BillRebate_warehouse_id" value="<?php echo $model->warehouse_id;?>" name="BillRebate[warehouse_id]" />
		</div>
	</div>
	<?php }?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>总金额：</div>
	<?php if (!$model->id || $baseform->form_status == 'unsubmit') {?>
		<input type="text" id="BillRebate_fee" class="form-control" value="<?php echo number_format($model->fee, 2, '.', ',');?>" name="BillRebate[fee]" />
	<?php } else {?>
		<input type="text" id="BillRebate_fee" class="form-control" value="<?php echo number_format($model->fee, 2, '.', ',');?>" name="BillRebate[fee]" readonly="readonly" />
	<?php }?>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input id="CommonForms_comment" class="form-control" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]" />
	</div>
</div>

<div class="create_table"></div>

<input type="hidden" name="CommonForms[form_type]" value="<?php echo $baseform->form_type;?>">
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
<div class="search_line"></div>

<script type="text/javascript">
var can_submit = true;

<?php if ($msg) {?>
confirmDialog("<?php echo $msg;?>");
<?php }?>

var min_time, start_time, end_time;
var fee = 0.0;
var owned_by = <?php echo $baseform->owned_by;?>;

//获取开始时间,更新仓库信息
function getStartTime() 
{
	var title_id = $("#BillRebate_title_id").val();
	var company_id = $("#BillRebate_company_id").val();
	<?php if ($is_warehouse > 0) {?>
	if(company_id){
		$.get("/index.php/billRebate/getWareData",{'company_id': company_id},function(e){
			var arr = e.split(",");
			$("#warehouse_combo").val(arr[0]);
			$("#BillRebate_warehouse_id").val(arr[1]);
		})
	}
	<?php }?>
	if (!title_id || !company_id) return;
	$.get('/index.php/billRebate/getMaxTime', 
	{
		'type': "<?php echo $model->type;?>", 
		'title_id': title_id, 
		'company_id': company_id
	}, 
	function(data) {
		if (data) {
			min_time = data;
			$("#BillRebate_start_time").val(data);
		}
	});
}

//开始时间
$("#BillRebate_start_time").change(function() {
	if ($("#BillRebate_end_time").val() && strtotime($(this).val().substring(0, 10), '') > strtotime($("#BillRebate_end_time").val().substring(0, 10), '')) 
	{
		confirmDialog("开始时间不能大于结束时间", function() {
			$("#BillRebate_start_time").val("");
			$("#BillRebate_start_time").focus();
		});
		return false;
	}
	if (min_time && strtotime($(this).val().substring(0, 10), '') < strtotime(min_time, '')) 
	{
		confirmDialog("开始时间不能小于" + min_time, function() {
			$("#BillRebate_start_time").val(min_time);
			$("#BillRebate_start_time").focus();
		});
		return false;
	}
	start_time = $(this).val().substring(0, 10);
});

//结束时间
$("#BillRebate_end_time").change(function() {
	if ($("#BillRebate_start_time").val() && strtotime($("#BillRebate_start_time").val().substring(0, 10), '') > strtotime($(this).val().substring(0, 10), '')) 
	{
		confirmDialog("结束时间不能小于开始时间", function() {
			$("#BillRebate_end_time").val("");
			$("#BillRebate_end_time").focus();
		});
		return false;
	}
	
	if ($(this).val().substring(0, 10) == date('Y-m-d', '')) 
	{
		confirmDialog("结束时间不能选择今天", function() {
			$("#BillRebate_end_time").val("");
			$("#BillRebate_end_time").focus();
		});
		return false;
	}

	if (strtotime($(this).val().substring(0, 10), '') > strtotime(date('Y-m-d', ''), '')) 
	{
		confirmDialog("结束时间不能大于今天", function() {
			$("#BillRebate_end_time").val("");
			$("#BillRebate_end_time").focus();
		});
		return false;
	}
	end_time = $(this).val().substring(0, 10);
});

//业务员 
$("#CommonForms_owned_by").change(function(){
	var owned_by = $(this).val();
	$.get("/index.php/user/getTeam", 
	{
		'user_id': owned_by
	}, 
	function(data) 
	{
		if (data) $("#team_select").find("option:contains('" + data + "')").attr("selected", "selected");
		else $("#team_select").find("option[value='']").attr("selected", "selected");
	});
});

//保存 
$("#submit_btn, #save_submit").click(function() {
	var obj = $(this);
	var id = obj.attr("id");
	var title_id = $("#BillRebate_title_id").val();
	var company_id = $("#BillRebate_company_id").val();
	var warehouse_id = $("#BillRebate_warehouse_id").val();
	var start_time = $("#BillRebate_start_time").val().substring(0, 10);
	var end_time = $("#BillRebate_end_time").val().substring(0, 10);
	var fee = numChange($("#BillRebate_fee").val());
	//var owned_by = $("#CommonForms_owned_by").val();
	
	if (!title_id || title_id <= 0) {
		confirmDialog("请选择公司", function(){
			$("#title_combo").focus();
		});
		return false;
	}
	if (!company_id || company_id <= 0) {
		confirmDialog("请选择" + $("#company_select").prev().text().replace('：', '').replace('*', ''), function(){
			$("#company_combo").focus();
		});
		return false;
	}
	if (!start_time) {
		confirmDialog("请选择开始时间", function(){
			$("#BillRebate_start_time").focus();
		});
		return false;
	}
	if (!end_time) {
		confirmDialog("请选择结束时间", function(){
			$("#BillRebate_end_time").focus();
		});
		return false;
	}
	if (!fee || isNaN(fee) || parseFloat(fee) <= 0) 
	{
		confirmDialog("金额必须大于0", function(){
			$("#BillRebate_fee").focus();
		});
		return false;
	}
<?php if ($is_warehouse > 0) {?>
	if (!warehouse_id || warehouse_id <= 0) {
		confirmDialog("请选择仓库", function(){
			$("#warehouse_combo").focus();
		});
		return false;
	}
<?php }?>
// 	if (!owned_by || owned_by <= 0) 
// 	{
// 		confirmDialog("请选择业务员", function(){
// 			$("#CommonForms_owned_by").focus();
// 		});
// 		return false;
// 	}
	if (id == 'save_submit') 
		obj.parent().append('<input type="hidden" name="CommonForms[submit]" value="yes">');
	if (can_submit) 
	{
		can_submit = false;
		notAnymore('save_submit');
		notAnymore('submit_btn');
		$("#form_data").submit();
	}
});

//select数组 
var company_array = <?php echo $company_array ? $company_array : '[]';?>; //仓库结算单位|供应商 
var title_array = <?php echo $title_array ? $title_array : '[]';?>; //公司
var warehouse_array = <?php echo $warehouse_array ? $warehouse_array : '[]';?>; //仓库 

$(function() {
<?php if (!$model->id) {?>
	//select 
	$("#title_combo").combobox(title_array, {}, "title_select", "BillRebate_title_id", false, 'getStartTime()');
	$("#company_combo").combobox(company_array, {}, "company_select", "BillRebate_company_id", false, 'getStartTime()');
//	$("#warehouse_combo").combobox(warehouse_array, {}, "warehouse_select", "BillRebate_warehouse_id", false);
<?php }?>
});
</script>






















