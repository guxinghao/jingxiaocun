<style>
.shop_more_one{width:145px;margin-left:20px;}
</style>
<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<input type="text" value="<?php echo $year;?>年" readonly>
	</div>
	<div class="shop_more_one">
		<input type="text" value="<?php echo $month;?>月" readonly>
	</div>
</div>
<div class="create_table">
<table class="table"  id="cght_tb">
    	<thead>
     		<tr>
         		<th class="text-left" >业务员</th>
         		<th class="text-right" >预估销售重量</th>
         		<th class="text-right" >预估销售提成</th>
         		<th class="text-left" ><span class="bitian">*</span>提成</th>
      		</tr>
    	</thead>
    	<tbody>
    		<td class=""><span class="name"><?php echo $model->owned->nickname;?></span></td>
    		<td class="text-right">
    			<?php echo number_format($yg_weight,3);?>
    		</td>
    		<td class="text-right"><?php echo number_format($yg_money,2);?></td>
    		<td class=""><input type="text" class="form-control td_money" name="money" value="<?php echo $model->money;?>"></td>
    		</tr>
    	</tbody>
</table>
</div>
<div class="btn_list" >
	<button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal">保存</button>
	<a href="<?php echo Yii::app()->createUrl('salesCommission/index')?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>

$(function(){
	var can_submit	= true;
	$(".save").click(function(){
		var money = $(".td_money").val();
		if(money == ""){
			confirmDialog("请输入业务员的提成");
			return false;
		}
		if(can_submit){
			can_submit = false;
			setSubmitStatus();
			$("#form_data").submit();
		}
	});
})

//金额发生变化
$(document).on('change','.td_money',function(){
	var money = $(this).val();
	money = $.trim(money);
	money = numChange(money);
	if(!/^[0-9]+(.[0-9]{1,2})?$/.test(money))
	{
		confirmDialog('提成金额必须是大于等于0且小数点后只有2位的正数');
		$(this).val('');
		return false;
	}
})

</script>