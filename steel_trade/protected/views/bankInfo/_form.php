<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array (
				'id' => 'output_fee' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>

<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开户银行：</div>
		<input type="text"  id="name" name="BankInfo[bank_name]" style="width:150px;height:33px;" value="<?php echo $model->bank_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>账户名称：</div>
		<input type="text"  id="dname" name="BankInfo[company_name]" style="width:150px;height:33px;" value="<?php echo $model->company_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>账　　号：</div>
		<input type="text"  id="account" name="BankInfo[bank_number]" style="width:150px;height:33px;" value="<?php echo $model->bank_number;?>" class="form-control "   >
	</div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one">
		<div class="shop_more_one_l" ><span class="bitian">*</span>结算单位：</div>
		<div id="comselect_c" class="fa_droplist" style="width:150px">
			<input type="text" id="combo_c" class="forreset" value="<?php echo DictCompany::getShortName($model->dict_company_id);?>" />
			<input type='hidden' id='comboval_c' value="<?php echo $model->dict_company_id;?>"  class="forreset" name="BankInfo[dict_company_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">拼　　音：</div>
		<input type="text"  id="code" name="BankInfo[code]" style="width:150px;height:33px;" value="<?php echo $model->code;?>" class="form-control "   >
	</div>
<!--	<div class="shop_more_one">-->
<!--		<div class="shop_more_one_l">期初金额：</div>-->
<!--		<input type="text" onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" id="money" name="BankInfo[money]" style="width:150px;height:33px;" value="<?php echo $model->money;?>" class="form-control "   >-->
<!--	</div>-->
	<?php if($model->id){?>
	<div class="shop_more_one"></div>
	<div class="shop_more_one"></div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">创建时间：</div>
		<span><?php echo $model->created_at?date("Y-m-d H:i:s",$model->created_at):"-";?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">创&nbsp;&nbsp;建&nbsp;&nbsp;者：</div>
		<span><?php echo $model->created_by?$model->creater->nickname:"-";?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">最后修改：</div>
		<span><?php echo $model->last_update_at?date("Y-m-d H:i:s",$model->last_update_at):"-";?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">最后修改人：</div>
		<span><?php echo $model->last_update_by?$model->updater->nickname:"-";?></span>
	</div>
	<!-- 进入本页最后更新时间 -->
	<input type="hidden" name='lupt' value="<?php echo time();#$this->getUpdateTime($model->tableName(), $model->id)?>"/>
	<?php }?>
</div>
<div class="btn_list create_table" style="width:99%">
	<button type="submit" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
	<button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
</div>

<?php
$this->endWidget ();
?>	
<script>
	$(function(){	
		<?php if($msg){?>
		confirmDialog('<?php echo $msg?>');
		<?php }?>
		$(".cancel").click(function(){
			location.href="<?php echo Yii::app()->createUrl('bankInfo/index',array('page'=>$_REQUEST['page']))?>";
		});

		$(".save").click(function(){
			if($.trim( $("#name").val() ) == "" || $.trim( $("#name").val() ) == null){
				confirmDialog("开户银行不能为空！");
				$("#name").focus();
				return false;
			}
			if($.trim( $("#dname").val() ) == "" || $.trim( $("#dname").val() ) == null){
				confirmDialog("账户名称不能为空！");
				$("#dname").focus();
				return false;
			}
			if($.trim( $("#account").val() ) == "" || $.trim( $("#account").val() ) == null){
				confirmDialog("账号不能为空！");
				$("#account").focus();
				return false;
			}
			if($("#comboval_c").val()==null||$("#comboval_c").val()==""||$("#comboval_c").val()==0){
				confirmDialog("结算单位不能为空！");
				return false;
			}
			
			if($("#money").val()>99999999.99){
				confirmDialog("金额过大，金额不能超过1亿元(不含1亿)！");
				$("#money").focus().select();
				return false;
			}
			$("form").submit();
		});
		var array_c=<?php echo $coms;?>;
		$('#combo_c').combobox(array_c, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_c","comboval_c",false);
	});
</script>