<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<?php
$_inputTime = array(6=>"00:00~06:00",12=>"06:00~12:00",18=>"12:00~18:00",24=>"18:00~24:00");
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<style>
.ss_tt_one{min-width:295px;}
.ss_tt_one_l{width:auto;}
</style>
<input type="hidden" value="<?php echo $baseform->last_update;?>" name="CommonForms[last_update]">
<input type="hidden" name="CommonForms[owned_by]"  value="<?php echo $baseform->owned_by;?>">
<div class="con_tit" style="height:43px;background-color:#f5f5f5;padding-left:1%;">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">销售单号：</div>
		<?php echo $sales->baseform->form_sn;?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">公司：</div>
		<?php echo $sales->dictTitle->short_name;?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">结算单位：</div>
		<?php echo $sales->dictCompany->short_name;?>
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">提货凭证：</div>
		<label class='radio-inline billclick'><input type="radio" name="send[auth_type]" value="bill" <?php if($model->auth_type == "bill"){echo "checked";} ?>>提货单</label>
        <label class='radio-inline carclick'><input type="radio" name="send[auth_type]" value="car" <?php if($model->auth_type == "car"){echo "checked";} ?>>车船号</label>
	</div>
	<div class="ss_tt_one">
		<div class="auth_text ss_tt_one_l"><span class="bitian">*</span><?php if($model->auth_type == "bill"){echo "凭证单号";}else{echo "车船号";}?>：</div>
		<input type="text" class="form-control tit_remark" name="send[auth_text]" value="<?php echo $model->auth_text;?>"  style="width:328px;" placeholder="<?php if($model->auth_type == "car"){echo '多个用逗号或空格隔开';}?>">
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">备注：</div>
		<input type="text" class="form-control" name="CommonForms[comment]"  value="<?php echo $baseform->comment;?>" style="width:210px;">
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l" ><span class="bitian">*</span>提货日期：</div>
		<input type="text"  name="send[start_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  value="<?php echo $model->start_time?date("Y-m-d",$model->start_time):date("Y-m-d");?>">
		<div style="float:left;padding:0 5px">至</div>
		<input type="text"  name="send[end_time]" class="form-control form-date date end_time input_backimg"  placeholder="选择日期" value="<?php echo $model->end_time?date("Y-m-d",$model->end_time):"";?>">
	</div>
</div>
<div class="create_table">
	<table class="table" id="ps_tb">
		<thead>
			<tr>
         		<th class="text-center" style="width:25%;">产地/品名/材质/规格/长度</th>
         		<th class="text-center" style="width:25%;">总件数</th>
         		<th class="text-center" style="width:25%;">配送件数</th>
         		<th class="text-center" style="width:25%;">预计到货日期</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = 0;
			$totalAmount = 0;
			foreach($detail as $li){
			$num++;
			$totalAmount += $li->amount;
			$t_amount += $li->salesDetail->amount;
			$t_weight += $li->weight;
			?>
			<tr>
				<input type="hidden" name="sales_detail_id[]" value="<?php echo $li->id;?>">
				<input type="hidden" name="send_id[]" value="<?php echo $li->id;?>">
				<td class="text-center">
				<?php echo $li["brand"]."/".$li["product"]."/".$li["texture"]."/".$li["rank"]."/".$li["length"];?>
				</td>
				<td><input type="text" class="form-control td_shop_num"  name="td_total[]" readonly value="<?php echo $li->salesDetail->amount;?>"></td>
				<td>
					<input type="text" class="form-control  td_amount"  name="amount[]" readonly value="<?php echo $li->amount;?>">
					<input type="hidden" class="form-control td_shop_num td_weight"  name="weight[]" readonly value="<?php echo number_format($li->weight,3);?>">
				</td>
				<td class="text-center">
				<span class="td_date">
				<?php
				if($li->salesDetail->mergestorage->is_transit == 1){
					echo date("Y-m-d",$li->salesDetail->mergestorage->pre_input_date);
					if($li->salesDetail->mergestorage->pre_input_time > 0){
						echo "&nbsp;&nbsp;".$_inputTime[$li->salesDetail->mergestorage->pre_input_time];
					}
				} 
				?>
				</span></td>
			</tr>
			<?php }?>
			<tr>
				<td class="text-center">合计：</td>
				<td><span class="total_amount"><?php echo $t_amount?></span></td>
				<td><span class="total-num"><?php echo $totalAmount;?></span></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo Yii::app()->createUrl('frmSend/index',array("id"=>$_GET["sid"],"page"=>$_GET["fpage"],"view"=>$_COOKIE["view"]));?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
$(document).on("change",".td_amount",function(){
	var amount = $(this).val();
	if(!/^[1-9][0-9]*$/.test(amount))
	{
		confirmDialog('件数必须是大于0的整数');
		return false;
	}
	var weight = Number($(this).next().val());
	t_weight = (amount*weight).toFixed(3);
	$(this).parent().parent().find(".td_weight").val(t_weight);
	var total = 0;
	$(".td_amount").each(function(){
		var one = Number($(this).val());
		total = total +one;
	});
	$(".total-num").html(total);
})
$(".deleted_tr").live("click",function(){
		$(this).parent().parent().remove();
		refreshTableStyle();
	})
var can_submit = true;	
$("#save").click(function(){
	var text = $(".tit_remark").val();
	if(text==''){confirmDialog("请输入提货单号或者行驶证号！");return false;}
	var way = $('input:radio:checked').val();
	if(way == "car"){
		var result = checkTravel(text);
		if(result != 1){confirmDialog(result);return false;}
	}
	var now_time = $(".now_time").val();
	var start_time = $(".start_time").val();
	var end_time = $(".end_time").val();
	if(start_time < now_time){confirmDialog("提货日期小于当前时间");return false;}
	if(start_time == ""){confirmDialog("请输入提货日期");return false;}
	if(end_time == ""){confirmDialog("请输入提货截止日期");return false;}
	if(start_time > end_time){confirmDialog("提货截止时间不能小于提货开始时间");return false;}
	var is_submit = true;
	var num=1;
	$("#ps_tb tbody tr").each(function(){
		var card_id = $(this).find(".card_id").text();
		var amount = Number($(this).find(".td_amount").val());
		var td_shop_num = Number($(this).find(".td_can_num").val());
		var pre_input_date = $(this).find(".td_date").text();
		
		if(amount == ''){confirmDialog("请输入卡号为"+card_id+"的件数");is_submit = false;return false;}
		if(amount > td_shop_num){confirmDialog("卡号为"+card_id+"的配送件数大于可出库件数");is_submit = false;return false;}
		if(pre_input_date != ""){
			if(start_time < pre_input_date){confirmDialog("提货日期不能早于到货时间");is_submit = false;return false;}
		}
		num++;
	})
	if(is_submit){
		if(can_submit){
			can_submit = false;
			setTimeout(function(){can_submit = true;},3000);
			$("#form_data").submit();
		}
	}
});
$(function(){
	$(".billclick").click(function(){
		$(".auth_text").html('<span class="bitian">*</span>凭证单号：');
		$(".tit_remark").attr("placeholder","");
	});
	$(".carclick").click(function(){
		$(".auth_text").html('<span class="bitian">*</span>车船号：');
		$(".tit_remark").attr("placeholder","多个用逗号或空格隔开");
	});
})
</script>