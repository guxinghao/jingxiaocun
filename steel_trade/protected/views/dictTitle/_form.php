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
		<div class="shop_more_one_l"><span class="bitian">*</span>公司抬头：</div>
		<input type="text"  id="name" name="DictTitle[name]" style="width:150px;height:33px;" value="<?php echo $model->name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司简称：</div>
		<input type="text"  id="short_name" name="DictTitle[short_name]" style="width:150px;height:33px;" value="<?php echo $model->short_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">拼　　音：</div>
		<input type="text"  id="code" name="DictTitle[code]" style="width:150px;height:33px;" value="<?php echo $model->code;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">财务编码：</div>
		<input type="text"  id="code" name="DictTitle[out_number]" style="width:150px;height:33px;" value="<?php echo $model->out_number;?>" class="form-control "   >
	</div>
</div>
<?php if($model->id){?>
<!-- 进入本页最后更新时间 -->
	<input type="hidden" name='lupt' value="<?php echo time();#$this->getUpdateTime($model->tableName(), $model->id)?>"/>
<?php }?>
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
			location.href="<?php echo Yii::app()->createUrl('dictTitle/index',array('page'=>$_REQUEST['page']))?>";
		});

		$(".save").click(function(){
			if($.trim( $("#name").val() ) == "" || $.trim( $("#name").val() ) == null){
				confirmDialog("抬头不能为空！");
				$("#name").focus();
				return false;
			}
			if($.trim( $("#short_name").val() ) == "" || $.trim( $("#short_name").val() ) == null){
				confirmDialog("简称不能为空！");
				$("#short_name").focus();
				return false;
			}
			$("form").submit();
		});
	});
</script>