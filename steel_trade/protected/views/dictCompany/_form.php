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
		<div class="shop_more_one_l"><span class="bitian">*</span>公司名称：</div>
		<input type="text"  id="name" name="DictCompany[name]" style="width:150px;height:33px;" value="<?php echo $model->name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>简　　称：</div>
		<input type="text"  id="short_name" name="DictCompany[short_name]" style="width:150px;height:33px;" value="<?php echo $model->short_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">拼　　音：</div>
		<input type="text"  id="code" name="DictCompany[code]" style="width:150px;height:33px;" value="<?php echo $model->code;?>" class="form-control "   >
	</div>
	<div class="shop_more_one"></div>
	<?php 
		if(checkOperation("凭证列表")){
	?>
		<div class="shop_more_one">
			<div class="shop_more_one_l">客户编码：</div>
				<input type="text"  id="name" name="DictCompany[cus_number]" style="width:150px;height:33px;" value="<?php echo $model->cus_number;?>" class="form-control "   >
		</div>
		<div class="shop_more_one">
			<div class="shop_more_one_l">供应商编码：</div>
				<input type="text"  id="name" name="DictCompany[sup_number]" style="width:150px;height:33px;" value="<?php echo $model->sup_number;?>" class="form-control "   >
		</div>
		<div class="shop_more_one">
			<div class="shop_more_one_l">短借 编码：</div>
				<input type="text"  id="name" name="DictCompany[dj_number]" style="width:150px;height:33px;" value="<?php echo $model->dj_number;?>" class="form-control "   >
		</div>
		<div class="shop_more_one"></div>
	<?php 
		}
	$bool = checkOperation("结算单位管理");
	if($bool){?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采&nbsp;&nbsp;购&nbsp;&nbsp;商：</div>
		<input type="checkbox" style="margin-top:0px;" name="is_customer"<?php if($model->is_customer==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_customer;?>" name="DictCompany[is_customer]" id="is_customer"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">物流供应商：</div>
		<input type="checkbox" style="margin-top:0px;" name="is_logistics"<?php if($model->is_logistics==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_logistics;?>" name="DictCompany[is_logistics]" id="is_logistics"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">是否仓库：</div>
		<input type="checkbox" style="margin-top:0px;" name="is_warehouse"<?php if($model->is_warehouse==1){?>checked="checked"<?php }?> id="check_warehouse"/>
		<input type="hidden" value="<?php echo $model->is_warehouse;?>" name="DictCompany[is_warehouse]" id="is_warehouse"/>
	</div>
	<div class="shop_more_one">
		<div class="show_warehouse" style="<?php if($model->is_warehouse != 1){echo "display:none;";}?>">
			<div class="shop_more_one_l">仓库：</div>
			<select name="DictCompany[warehouse_id]" class='form-control chosen-select forreset select_warehouse'>
	            <option value='0' selected='selected'>-请选择仓库-</option>
	             <?php foreach ($warehouse as $k=>$v){?>
            	 <option <?php echo $k==$model->warehouse_id?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
		</div>
	</div>
	<?php }?>
	
	<?php if(!$model->id&&!$bool) $model->is_gk=1;?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">高　　开：</div>
		<input type="checkbox" style="margin-top:0px;" name="is_gk"<?php if($model->is_gk==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_gk;?>" name="DictCompany[is_gk]" id="is_gk"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">代　　销：</div>
		<input type="checkbox" style="margin-top:0px;" name="is_dx"<?php if($model->is_dx==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_dx;?>" name="DictCompany[is_dx]" id="is_dx"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">供&nbsp;&nbsp;应&nbsp;&nbsp;商：</div>
		<input type="checkbox" style="margin-top:0px;" id='supply_select' name="is_supply"<?php if($model->is_supply==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_supply;?>" name="DictCompany[is_supply]" id="is_supply"/>
	</div>
	<?php if(!$model->id||$model->is_supply!=1) $hide=true;?>
	<div class="shop_more_one" id='fee_div'>
		<div class="shop_more_one_l" <?php if($hide){?>style="display:none"<?php }?>>运　　费：</div>
		<input type="text" onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" id="name" name="DictCompany[fee]" style="width:150px;height:33px;<?php if($hide){?>display:none<?php }?>" value="<?php echo $model->fee;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">托盘公司：</div>
		<input type="checkbox" style="margin-top:0px;" id="pledge_select" name="is_pledge"<?php if($model->is_pledge==1){?>checked="checked"<?php }?>/>
		<input type="hidden" value="<?php echo $model->is_pledge;?>" name="DictCompany[is_pledge]" id="is_pledge"/>
	</div>
	<div class="shop_more_one tp_" id="level_div">
		<div class="shop_more_one_l"  <?php if($model->is_pledge!=1){?>style="display:none"<?php }?>>赎回限制等级：</div>
		<select class='form-control chosen-select forreset' style="width:150px;<?php if($model->is_pledge!=1){?>display:none<?php }?>" name="DictCompany[level]">
			<option value="1" <?php if($model->level==1) echo "selected=selected"?>>根据产地</option>
			<option value="2" <?php if($model->level==2) echo "selected=selected"?>>根据产地、品名</option>
		</select>
	</div>
<!-- 	<div class="shop_more_one tp_"> --
		<div class="shop_more_one_l" <?php //if($model->is_pledge!=1){?>style="display:none"<?php //}?>>托盘天数：</div>
		<input type="text" onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" id="name" name="DictCompany[pledge_length]" style="width:150px;height:33px;<?php if($model->is_pledge!=1){?>display:none<?php }?>" value="<?php echo $model->pledge_length;?>" class="form-control "   >
<!-- 	</div> --
	<div class="shop_more_one tp_" style="position:relative">
		<div class="shop_more_one_l" <?php //if($model->is_pledge!=1){?>style="display:none"<?php //}?>>托盘利息利率：</div>
		<input type="text" onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" id="name" name="DictCompany[pledge_rate]" style="width:150px;height:33px;<?php if($model->is_pledge!=1){?>display:none<?php }?>" value="<?php echo number_format($model->pledge_rate,4);?>" class="form-control "   >
		<span style="<?php if($model->is_pledge!=1){?>display:none<?php }?>;position:absolute;right:-40px;top:0">‰/天</span>
<!-- 	</div> -->
	<?php if($model->id){?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">加入时间：</div>
		<span><?php echo $model->created_at?date("Y-m-d H:i:s",$model->created_at):" - ";?></span>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">操&nbsp;&nbsp;作&nbsp;&nbsp;人：</div>
		<span><?php echo $model->creater->nickname?></span>
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
		confirmDialog("<?php echo $msg?>");
		<?php }?>
		
		$(".cancel").click(function(){
			location.href="<?php echo Yii::app()->createUrl('dictCompany/index',array('page'=>$_REQUEST['page']))?>";
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
			if($(this).attr('id')=="pledge_select"){
				if(checked=="checked"){
					$(".tp_ *").show();
				}else{
					$(".tp_ *").hide();
				}
			}
			if($(this).attr('id')=="check_warehouse"){
				if(checked=="checked"){
					$(".show_warehouse").show();
				}else{
					$(".show_warehouse").hide();
					$(".select_warehouse").val(0)
				}
			}
		});
		$(".save").click(function(){
			if($.trim( $("#name").val() ) == "" || $.trim( $("#name").val() ) == null){
				confirmDialog("公司名不能为空！");
				$("#name").focus();
				return false;
			}
			if($.trim( $("#short_name").val() ) == "" || $.trim( $("#short_name").val() ) == null){
				confirmDialog("简称不能为空！");
				$("#short_name").focus();
				return false;
			}
			var is_warehouse = $("#is_warehouse").val();
			var warehouse_id = $(".select_warehouse").val();
			if(is_warehouse == 1 && warehouse_id == 0){
				confirmDialog("结算单位为仓库，请选择对应仓库");
				return false;
			} 
			$("form").submit();
		});
	});
</script>