<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<div class="ss_tt_title" style="border-bottom:1px dotted #b1b0b0;">
	<div class="ss_tt_one">
		公司：<?php echo $output->dictTitle->short_name;?>
	</div>
	<div class="ss_tt_one">
		客户：<?php echo $output->dictCompany->short_name;?>
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		出库单号：<?php echo $output->output_no;?>
	</div>
	<div class="ss_tt_one">
		车船号：<?php echo $output->car_no;?>
	</div>
	<div class="ss_tt_one">
		<div style="float:left;">备注：</div>
		<input type="text" class="form-control" name="output[remark]" value="<?php echo $output->remark;?>">
	</div>
</div>
<div class="create_table">
	<table class="table" id="ps_tb">
		<thead>
			<tr>
         		<th class="text-center" style="width:30%;">出库卡号</th>
         		<th class="text-center" style="width:30%;">产地/品名/材质/规格/长度</th>
         		<th class="text-center" style="width:20%;">件数</th>
         		<th class="text-center" style="width:20%;">重量</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($detail as $li){
			?>
			<tr>
				<input type="hidden" name="output_detail_id[]" value="<?php echo $li->id;?>">
				<input type="hidden" name="product_id[]" value="<?php echo $li->product_id;?>">
				<input type="hidden" name="texture_id[]" value="<?php echo $li->texture_id;?>">
				<input type="hidden" name="rank_id[]" value="<?php echo $li->rank_id;?>">
				<input type="hidden" name="brand_id[]" value="<?php echo $li->brand_id;?>">
				<input type="hidden" name="length[]" value="<?php echo $li->length;?>">
				<td class="text-center"><input type="text" class="form-control td_card_no"  name="card_no[]" value="<?php echo $li->card_no;?>"></td>
				<td class="text-center">
				<?php echo $li->brand."/".$li->product."/".$li->texture."/".$li->rank."/".$li->length;?>
				</td>
				<td><input type="text" class="form-control td_amount"  name="amount[]" value="<?php echo $li->amount;?>"></td>
				<td><input type="text" class="form-control td_weight"  name="weight[]"  value="<?php echo number_format($li->weight,3);?>"></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm save_sub save" data-dismiss="modal" id="save_sub">出库保存</button>
	<button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo Yii::app()->createUrl('warehouseOutput/index',array("page"=>$_GET["fpage"]));?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
//输入框失去焦点
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
//保存提交
	$(".save").click(function(){
		if($(this).hasClass("save_sub")){
			$("#submit_type").val(1);
		}else{
			$("#submit_type").val(0);
		}
		var is_submit = true;
		var num = 0;
		$("#ps_tb tr").each(function(){
			num++;
			if(num >1){
				var card_id = $(this).find(".td_card_no").val();
				var amount = $(this).find(".td_amount").val();
				var weight = parseFloat($(this).find(".td_weight").val());
				if(card_id == ''){confirmDialog("请输入卡号");$(this).find(".td_card_no").addClass("red-border");is_submit = false;return false;}
				if(amount == ''){confirmDialog("请输入件数");$(this).find(".td_amount").addClass("red-border");is_submit = false;return false;}
				if(weight == ''){confirmDialog("请输入重量");$(this).find(".td_weight").addClass("red-border");is_submit = false;return false;}
			}
		})
		if(num >1){
			if(is_submit){
				$("#form_data").submit();
			}
		}else{
			alert("出库单没有明细");return false;
		}
	})
</script>