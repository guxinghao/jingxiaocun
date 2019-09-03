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
		<div class="shop_more_one_l"><span class="bitian">*</span>姓　　名：</div>
		<input type="text"  id="name" name="CompanyContact[name]" style="width:150px;height:33px;" value="<?php echo $model->name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">手　　机：</div>
		<input type="text"  id="dname" name="CompanyContact[mobile]" style="width:150px;height:33px;" value="<?php echo $model->mobile;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l" ><span class="bitian">*</span>结算单位：</div>
		<div id="comselect_c" class="fa_droplist" style="width:150px">
			<input type="text" id="combo_c" class="forreset" value="<?php echo $model->id?$model->company->short_name:DictCompany::getName(intval($_GET['dict_company_id']));?>" />
			<input type='hidden' id='comboval_c' value="<?php echo $model->id?$model->dict_company_id:$_GET['dict_company_id'];?>"  class="forreset" name="CompanyContact[dict_company_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">是否默认：</div>
		<input type="checkbox" style="margin-top:0px;" name="is_default"<?php if($model->is_default==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_default;?>" name="CompanyContact[is_default]" id="is_default"/>
	</div>
	<?php if($model->id){?>
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
	<button type="" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
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
			location.href="<?php echo Yii::app()->createUrl('companyContact/index',array('page'=>$_REQUEST['page'],'dict_company_id'=>$_GET['dict_company_id']))?>";
		});
		$("input[type=checkbox]").change(function(){
			var checked = $(this).attr("checked");
			var my_hidden_id = "#"+$(this).attr("name");
			if(checked=="checked"){
				$(my_hidden_id).val(1);
			}else{
				$(my_hidden_id).val(0);
			}

			if($(this).attr('id')=="supply_select"){
				if(checked=="checked"){
					$("#fee_div *").show();
				}else{
					$("#fee_div *").hide();
				}
			}
		});
		$(".save").click(function(){
			if($.trim( $("#name").val() ) == "" || $.trim( $("#name").val() ) == null){
				confirmDialog("姓名不能为空！");
				$("#name").focus();
				return false;
			}
			if(!$("#comboval_c").val()){
				confirmDialog("结算单位不能为空！");
				return false;
			}
			if($("#is_default").val()!=1){
				$("form").submit();
				return;
			}
			$.post("<?php echo Yii::app()->createUrl('companyContact/checkDefault')?>", {
				'id' : <?php echo intval($model->id)?>,
				'company':$("#comboval_c").val()
			}, function(data) {
				if(data){
					confirmDialogWithCallBack(data,function(){$("form").submit();},function(){return});	
					return;
				}else{
					$("form").submit();
				}
			});
			return false;
//			$("form").submit();
		});
		var array_c=<?php echo $coms;?>;
		$('#combo_c').combobox(array_c, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_c","comboval_c",false);
	});
</script>