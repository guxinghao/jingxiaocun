<style>
<!--
.icon{ cursor: pointer;}
#add_list{ cursor: pointer;}
-->
</style>
<?php 
$is_arr = array('0' => "否", '1' => "是");

$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<div class="create_table">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width: 3%;"></th>
         		<th class="text-center" style="width: 5%;">操作</th>
         		<th class="text-center" style="width: 9%;">收益单位</th>
         		<th class="text-center" style="width: 8%;">费用大类</th>
         		<th class="text-center" style="width: 7%;">费用类别</th>
         		<th class="text-center" style="width: 15%;">产地/品名/材质/规格/长度</th>

         		<th class="text-center" style="width: 6%;">重量(吨)</th>
         		<th class="text-center" style="width: 5%;">单价</th>
         		<th class="text-center" style="width: 5%;">金额</th>
         		<th class="text-center" style="width: 5%;">乙单</th>
         		<th class="text-center" style="width: 7%;">起始日期</th>
         		<th class="text-center" style="width: 7%;">结束日期</th>
         		<th class="text-center" style="width: 6%;">车船号</th>
         		<th class="text-center" style="width: 6%;">备注</th>
      		</tr>
    	</thead>
		
		<tbody>
		<?php if ($billRecord) { 
			$tr_num = 0;
			$bill = is_array($billRecord) ? $billRecord : array($billRecord);
			foreach ($bill as $item) { 
				$tr_num ++;?>
			<tr class="<?php echo $tr_num % 2 == 0 ? 'selected' : '';?>">
				<td class="text-center list_num"><?php echo $tr_num;?></td>
				<td class="text-center">
					<input type="hidden"  name="td_bill_id[]" style="" class="td_bill_id" value="<?php echo $item->baseform->id;?>" />
					<i class="icon icon-trash deleted_tr" style="line-height:26px;"></i>
				</td>
				<td class="">
					<select name="td_company_id[]" class='form-control chosen-select td_company_id'>
					<?php foreach ($companys as $k => $v) {?>
	    				<option value="<?php echo $k;?>"<?php echo $item->company_id == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
	    			<?php }?>
					</select>
				</td>
				<td class="">
	    			<select name="td_type[]" class='form-control chosen-select td_type'>
	    			<?php foreach ($types as $k => $v) {?>
	    				<option value="<?php echo $k;?>"<?php echo $item->type == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class="">
	    			<select name="td_type_detail[]" class='form-control chosen-select td_type_detail'>
	    			<?php foreach ($sub_types[$item->type] as $k => $v) {?>
	    				<option value="<?php echo $k;?>"<?php echo $item->type_detail == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class="">
	    			<select name="td_frm_detail_id[]" class='form-control chosen-select td_frm_detail_id'>
	    			<?php foreach ($infos as $k => $v) {?>
	    				<option value="<?php echo $k;?>"<?php echo $item->frm_detail_id == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class=""><input type="text"  name="td_weight[]" style="" class="form-control td_weight" value="<?php echo number_format($item->weight, 4);?>" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" value="<?php echo number_format($item->price, 2);?>" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_amount" value="<?php echo number_format($item->amount, 2);?>" placeholder=""  ></td>
	    		<td class="">
	    			<select name="td_is_yidan[]" class="form-control chosen-select td_is_yidan">
	    			<?php foreach ($is_arr as $k => $v) {?>
	    				<option value="<?php echo $k;?>"<?php echo $item->is_yidan == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class=""><input type="text"  name="td_start_at[]" style="" class="form-control form-date date td_start_at" value="<?php echo date('Y-m-d', $item->start_at);?>" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_end_at[]"  style="" class="form-control form-date date td_end_at" value="<?php echo date('Y-m-d', $item->end_at);?>" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_travel[]"  style="" class="form-control td_travel" value="<?php echo $item->travel;?>" placeholder=""></td>
	    		<td class=""><input type="text"  name="td_remarks[]"  style="" class="form-control td_remarks" value="<?php echo $item->baseform->comment;?>" placeholder=""></td>
			</tr>
		<?php } } else { 
			$tr_num = 1;?>
	    	<tr class="">
	    		<td class="text-center list_num"><?php echo $tr_num;?></td>
	    		<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>
	    		<td class="">
	    			<select name="td_company_id[]" class='form-control chosen-select td_company_id'>
	    			<?php foreach ($companys as $k => $v) {?>
	    				<option value="<?php echo $k;?>"><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class="">
	    			<select name="td_type[]" class='form-control chosen-select td_type'>
	    			<?php foreach ($types as $k => $v) {?>
	    				<option value="<?php echo $k;?>"><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class="">
	    			<select name="td_type_detail[]" class='form-control chosen-select td_type_detail'>
	    			<?php foreach (reset($sub_types) as $k => $v) {?>
	    				<option value="<?php echo $k;?>"><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class="">
	    			<select name="td_frm_detail_id[]" class='form-control chosen-select td_frm_detail_id'>
	    			<?php foreach ($infos as $k => $v) {?>
	    				<option value="<?php echo $k;?>"><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class=""><input type="text"  name="td_weight[]" style="" class="form-control td_weight" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_amount" placeholder=""  ></td>
	    		<td class="">
	    			<select name="td_is_yidan[]" class="form-control chosen-select td_is_yidan">
	    			<?php foreach ($is_arr as $k => $v) {?>
	    				<option value="<?php echo $k;?>"><?php echo $v;?></option>
	    			<?php }?>
	    			</select>
	    		</td>
	    		<td class=""><input type="text"  name="td_start_at[]" style="" class="form-control form-date date td_start_at" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_end_at[]"  style="" class="form-control form-date date td_end_at" placeholder=""  ></td>
	    		<td class=""><input type="text"  name="td_travel[]"  style="" class="form-control td_travel" placeholder=""></td>
	    		<td class=""><input type="text"  name="td_remarks[]"  style="" class="form-control td_remarks" placeholder=""></td>
	    	</tr>
    	<?php }?>
    	</tbody>
	</table>
	<input type="hidden" id="tr_num" value="<?php echo $tr_num;?>" />
</div>
<?php if (is_array($billRecord)) {?>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<?php }?>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#1c9fe1;" id="submit_btn">保存</button>
	<a href="#">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#d5d5d5;color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>

<script type="text/javascript">
<!--
var back_url = '<?php echo is_array($billRecord) ? Yii::app()->createUrl("purchase/index") : Yii::app()->createUrl("billRecord/index");?>';
var type_details_arr = [];
<?php foreach ($sub_types as $k => $v) {?>
var type_option = '';
	<?php foreach ($v as $v_k => $v_v) {?>
	type_option += '<option value="<?php echo $v_k;?>"><?php echo $v_v;?></option>';
	<?php }?>
	type_details_arr[<?php echo $k;?>] = type_option;
<?php }?>

$("#add_list").click(function(){
	var count = parseInt($("#tr_num").val()) + 1;
	var newRow = count % 2 == 0 ? '<tr class="selected">' : '<tr class="">';
	newRow += '<td class="text-center list_num">' + count + '</td>' + 
	'<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>' + 
	'<td class="">' + 
		'<select name="td_company_id[]" class="form-control chosen-select td_company_id">' + 
	    <?php foreach ($companys as $k => $v) {?>
	    	'<option value="<?php echo $k;?>"><?php echo $v;?></option>' + 
	    <?php }?>
	    '</select>' + 
	'</td>' + 
	'<td class="">' + 
		'<select name="td_type[]" class="form-control chosen-select td_type">' + 
		<?php foreach ($types as $k => $v) {?>
			'<option value="<?php echo $k;?>"><?php echo $v;?></option>' + 
    	<?php }?>
		'</select>' + 
	'</td>' + 
	'<td class="">' + 
		'<select name="td_type_detail[]" class="form-control chosen-select td_type_detail">' + 
		<?php foreach (reset($sub_types) as $k => $v) {?>
			'<option value="<?php echo $k;?>"><?php echo $v;?></option>' + 
		<?php }?>
	    '</select>' +
	'</td>' + 
	'<td class="">' + 
		'<select name="td_frm_detail_id[]" class="form-control chosen-select td_frm_detail_id">' + 
	    <?php foreach ($infos as $k => $v) {?>
	    	'<option value="<?php echo $k;?>"><?php echo $v;?></option>' + 
	    <?php }?>
	    '</select>' + 
	'</td>' + 
	'<td class=""><input type="text"  name="td_weight[]" style="" class="form-control td_weight" placeholder=""  ></td>' + 
	'<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>' + 
	'<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_amount" placeholder=""  ></td>' + 
	'<td class="">' + 
		'<select name="td_is_yidan[]" class="form-control chosen-select td_is_yidan">' + 
		<?php foreach ($is_arr as $k => $v) {?>
    		'<option value="<?php echo $k;?>"><?php echo $v;?></option>' + 
		<?php }?>
	    '</select>' + 
	'</td>' + 
	'<td class=""><input type="text"  name="td_start_at[]" style="" class="form-control form-date date td_start_at" placeholder=""></td>' + 
	'<td class=""><input type="text"  name="td_end_at[]" style="" class="form-control form-date date td_end_at" placeholder=""></td>' + 
	'<td class=""><input type="text"  name="td_travel[]"  style="" class="form-control td_travel" placeholder=""></td>' + 
	'<td class=""><input type="text"  name="td_remarks[]"  style="" class="form-control td_remarks" placeholder=""></td>';
	newRow += '</tr>';

	if($("#cght_tb tbody tr").length > 0){
		$("#cght_tb tbody tr:last").after(newRow);
	}else{
		$("#cght_tb tbody").html(newRow);
	}
	$("#tr_num").val(count);
	//加载时间控件
	dateTimePick();
});

$(".td_type").live("change", function(){
	$(this).parent().parent().find(".td_type_detail").html(type_details_arr[$(this).val()]);
});

var td_del_id = new Array();

$(".deleted_tr").live("click", function(){
	if($(this).parent().parent().find(".td_bill_id").length == 1){
		if (confirm("确认删除？")){
			td_del_id.push($(this).parent().parent().find(".td_bill_id").val());
		}else{
			return false;
		}
	}
	var row_num = 0;
	$(this).parent().parent().remove();
	$("#cght_tb tbody tr").each(function(){
		row_num++;
		$(this).find(".list_num").html(row_num);
		if(row_num%2 == 0){
			$(this).addClass("selected");
		}else{
			$(this).removeClass("selected");
		}
	});
	$("#tr_num").val(row_num);
});

$("#cancel").click(function(){
	window.location.href = back_url;
});

$("#submit_btn").click(function(){
	var res = true;
	
	var td_bill_id = new Array();
	$(".td_bill_id").each(function(){
		td_bill_id.push($(this).val());
	});
	//收益单位
	var td_company_id = new Array();
	$(".td_company_id").each(function(){
		td_company_id.push($(this).val());
	});
	//费用大类别
	var td_type = new Array();
	$(".td_type").each(function(){
		td_type.push($(this).val());
	});
	//类别
	var td_type_detail = new Array();
	$(".td_type_detail").each(function(){
		td_type_detail.push($(this).val());
	});
	//明细
	var td_frm_detail_id = new Array();
	$(".td_frm_detail_id").each(function(){
		td_frm_detail_id.push($(this).val());
	});
	//重量
	var td_weight = new Array();
	$(".td_weight").each(function(){
		if($(this).val() == ""){
			confirmDialog("请输入重量");
			$(this).focus();
			res = false;
			return false;
		}
		td_weight.push($(this).val());
	});
	if(!res){ return false;}
	//单价
	var td_price = new Array();
	$(".td_price").each(function(){
		if($(this).val() == ""){
			confirmDialog("请输入单价");
			$(this).focus();
			res = false;
			return false;
		}
		td_price.push($(this).val());
	});
	if(!res){ return false;}
	//金额
	var td_amount = new Array();
	$(".td_amount").each(function(){
		if($(this).val() == ""){
			confirmDialog("请输入金额");
			$(this).focus();
			res = false;
			return false;
		}
		td_amount.push($(this).val());
	});
	if(!res){ return false;}
	//乙单状态
	var td_is_yidan = new Array();
	$(".td_is_yidan").each(function(){
		td_is_yidan.push($(this).val());
	});
	//起始时间
	var td_start_at = new Array();
	$(".td_start_at").each(function(){
		if($(this).val() == ""){
			confirmDialog("请输入起始时间");
			$(this).focus();
			res = false;
			return false;
		}
		td_start_at.push($(this).val());
	});
	if(!res){ return false;}
	//结束时间
	var td_end_at = new Array();
	$(".td_end_at").each(function(){
		if($(this).val() == ""){
			confirmDialog("请输入结束时间");
			$(this).focus();
			res = false;
			return false;
		}
		td_end_at.push($(this).val());
	});
	if(!res){ return false;}
	//车船号
	var td_travel = new Array();
	$(".td_travel").each(function(){
		td_travel.push($(this).val());
	});
	//备注
	var td_remarks = new Array();
	$(".td_remarks").each(function(){
		td_remarks.push($(this).val());
	});
	
	$.post("<?php echo Yii::app()->createUrl('billRecord/submitRecord')?>", 
	{
		'owned_by': <?php echo $owned_by;?>,
		'bill_type': "<?php echo $bill_type;?>",
		'frm_common_id': <?php echo $frm_common_id;?>,
		'td_bill_id': td_bill_id,
		'td_company_id': td_company_id,
		'td_type': td_type,
		'td_type_detail': td_type_detail,
		'td_frm_detail_id': td_frm_detail_id,
		'td_weight': td_weight,
		'td_price': td_price,
		'td_amount': td_amount,
		'td_is_yidan': td_is_yidan,
		'td_start_at': td_start_at,
		'td_end_at': td_end_at,
		'td_travel': td_travel,
		'td_remarks': td_remarks,
		'td_del_id': td_del_id
	}, function(data){
		window.location.href = back_url;
	});
});

//-->
</script>
