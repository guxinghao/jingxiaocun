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
		<div class="shop_more_one_l"><span class="bitian">*</span>仓库名称：</div>
		<input type="text"  id="name" name="Warehouse[name]" style="width:150px;height:33px;" value="<?php echo $model->name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">简　　称：</div>
		<input type="text"  id="short_name" name="Warehouse[short_name]" style="width:150px;height:33px;" value="<?php echo $model->short_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">公司抬头：</div>
		<input type="text"  id="title" name="Warehouse[title]" style="width:150px;height:33px;" value="<?php echo $model->title;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">拼　　音：</div>
		<input type="text"  id="code" name="Warehouse[code]" style="width:150px;height:33px;" value="<?php echo $model->code;?>" class="form-control "   >
	</div>
<!--	<div class="shop_more_one">
		<div class="shop_more_one_l">标&nbsp;&nbsp;准&nbsp;&nbsp;码：</div>
		<input type="text" placeholder="不超过15个字符" id="std" name="Warehouse[std]" style="width:150px;height:33px;" value="<?php echo $model->std;?>" class="form-control "   >
	</div>-->
	<div class="shop_more_one">
		<div class="shop_more_one_l">区　　域：</div>
		<select id="area" name="Warehouse[area]" class="form-control"  style="width:150px;height:33px;">
		<?php foreach ($areas as $key=>$value){?>
		<option value="<?php echo $key?>" <?php echo $model->area==$key?'selected="selected"':''?>><?php echo $value?></option>
		<?php }?>
		</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联&nbsp;&nbsp;系&nbsp;&nbsp;人：</div>
		<input type="text"  id="contact" name="Warehouse[contact]" style="width:150px;height:33px;" value="<?php echo $model->contact;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">传　　真：</div>
		<input type="text"  id="fax" name="Warehouse[fax]" style="width:150px;height:33px;" value="<?php echo $model->fax;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">电　　话：</div>
		<input type="text"  id="mobile" name="Warehouse[mobile]" style="width:150px;height:33px;" value="<?php echo $model->mobile;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">地　　址：</div>
		<input type="text"  id="address" name="Warehouse[address]" style="width:150px;height:33px;" value="<?php echo $model->address;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备　　注：</div>
		<input type="text"  id="common" name="Warehouse[common]" style="width:150px;height:33px;" value="<?php echo $model->common;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_checkbox" style="width:160px;">
			 <label class='radio-inline'> <input class="check_box" type="checkbox" name="Warehouse[is_jxc]" <?php echo $model->is_jxc?"checked":"";?> value="1"> 是否引入进销存系统 </label>
		</div> 
	</div>
	<?php if($model->id){?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">先销后进：</div>
		<span><?php echo $model->is_other?"是":"否";?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">创建时间：</div>
		<span><?php echo date("Y-m-d H:i:s",$model->created_at);?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">创&nbsp;&nbsp;建&nbsp;&nbsp;者：</div>
		<span><?php echo $model->creater->nickname;?></span>
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
			location.href="<?php echo Yii::app()->createUrl('warehouse/index',array('page'=>$_REQUEST['page']))?>";
		});

		$(".save").click(function(){
			if($.trim( $("#name").val() ) == "" || $.trim( $("#name").val() ) == null){
				confirmDialog("名称不能为空！");
				$("#name").focus();
				return false;
			}

// 			if($("#std").val().length>15){
// 				confirmDialog("标准码不能超过15个字符！");
// 				return false;
// 			}

			$("form").submit();
		});
	});
</script>