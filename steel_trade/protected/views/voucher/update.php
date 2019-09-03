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
			<div class="shop_more_one_l">凭证字：</div>
			<input type="text" class="form-control name"  name="Voucher[voucher_name]" value="<?php echo $model->voucher_name;?>">
		</div>
		<div class="shop_more_one">
			<div class="shop_more_one_l">凭证号：</div>
			<input type="text" class="form-control number" name="Voucher[voucher_number]" value="<?php echo $model->voucher_number;?>">
		</div>
		<div class="shop_more_one">
			<div class="shop_more_one_l">附件数：</div>
			<input type="text" class="form-control attachment" name="Voucher[attachment]" value="<?php echo $model->attachment;?>">
		</div>
		<div class="shop_more_one">
		<div class="shop_more_one_l">业务日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="Voucher[form_at]" class="form-control form-date date form_at input_backimg"  value="<?php echo date("Y-m-d",$model->form_at);?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">凭证日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="Voucher[created_at]" class="form-control form-date date created_at input_backimg"  value="<?php echo date("Y-m-d",$model->created_at);;?>">
		</div>
	</div>
</div>
<div class="btn_list" style="margin-bottom: 15px;">
	<button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo Yii::app()->createUrl('voucher/index',array("page"=>$_GET["fpage"]));?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
	<div class="create_table">
		<table class="table"  id="cght_tb">
	    	<thead>
	    		<th class="text-center" ></th>
         		<th class="text-center" >摘要</th>
         		<th class="text-center" >科目编码</th>
         		<th class="text-center" >科目名称</th>
         		<th class="text-center" >结算单位</th>
         		<th class="text-center" >借款金额</th>
         		<th class="text-center" >贷款金额</th>
         		<th class="text-center" >重量</th>
	    	</thead>
	    	<tbody>
	    	<?php 
	    		$num = 0;
	    		foreach ($detail as $li){
	    			$num ++;
	    	?>
	    	<tr>
		    	<td class=""><?php  echo $num;?></td>
		    	<td class=""><?php  echo $li->comment;?></td>
		    	<td class=""><?php  echo $li->account_code;?></td>
		    	<td class=""><?php  echo $li->account_name;?></td>
		    	<td class=""><?php  echo $li->company->name;?></td>
		    	<td class=""><?php  echo number_format($li->debit,2);?></td>
		    	<td class=""><?php  echo number_format($li->credit,2);?></td>
		    	<td class=""><?php  echo number_format($li->amount,3);?></td>
	    	</tr>
	    	<?php }?>
	    	</tbody>
    	</table>
	</div>
	<script>
	$(function(){
		<?php if($msg){?>
		confirmDialog('<?php echo $msg?>');
		<?php }?>

		//保存
		$(".save").click(function(){
			var name = $(".name").val();
			var number = $(".number").val();
			var num=$(".attachment").val();
			var created_at = $(".created_at").val();
			var form_at = $(".form_at").val();
			if(name==''){confirmDialog("请输入凭证字！");return false;}
			if(!/^[1-9][0-9]*$/.test(number))
			{
				confirmDialog('凭证号必须是大于0的整数');
				return false;
			}
			if(!/^[0-9]*$/.test(num))
			{
				confirmDialog('附件数必须是大于等于0的整数');
				return false;
			}
			if(form_at==''){confirmDialog("请选择业务日期！");return false;}
			if(created_at==''){confirmDialog("请选择凭证日期！");return false;}
			setSubmitStatus();
			$("#form_data").submit();
		})
		
	})
	</script>