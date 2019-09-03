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
		<div class="shop_more_one_l">公司：</div>
		<input type="hidden" name="FrmInput[input_company]" value="<?php ?>" id="input_company">
		<input type="text"  name="" readonly id="title"   value="<?php echo $purchase->title->short_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">供应商：</div>
		<input type="text"  name="" readonly id="supply"  value="<?php echo $purchase->supply->short_name;?>" class="form-control " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系人：</div>
		<input type="text"  name="" readonly id="contact"  value="<?php echo $purchase->contact->name;?>" class="form-control " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone"  value="<?php echo $purchase->contact->mobile?>" class="form-control con_tel"  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购单：</div>
			<input type="text"  readonly name="FrmInput[form_sn]" id="form_sn" value="<?php echo $basePurchase->form_sn?>"   class="form-control con_tel"   >
			<input type="hidden"  name="FrmInput[purchase_id]" id="purchase_id" value="<?php echo $basePurchase->id;?>"   class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购日期：</div>
		<div class="search_date_box" style="margin-top:0px;width:150px;background-position:155px 8px;">
			<input type="text" readonly name="CommonForms[form_time]"  id="form_time" value="<?php echo $basePurchase->form_time;?>"  class="form-control   input_backimg" placeholder=""  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>预计到货日期：</div>
		<div class="search_date_box" style="margin-top:0px;width:150px;background-position:155px 8px;">
			<input type="text"  name="FrmInput[input_date]" value="<?php echo ($inputPlan->input_date>943891200)?date('Y-m-d',$inputPlan->input_date):'';?>"  id="input_date"  class="form-control form-date date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l maybe_bitian1"><span class="bitian">*</span>预计到货时段：</div>
		 <select name="FrmInput[input_time]" class='form-control input_time'>
	            <option value='0' selected='selected'></option>	             
           		 <option <?php echo $inputPlan->input_time=='6'?'selected="selected"':''?>  value="6">00:00-06:00</option>
           		 <option  <?php echo $inputPlan->input_time=='12'?'selected="selected"':''?> value="12">06:00-12:00</option>
           		 <option  <?php echo $inputPlan->input_time=='18'?'selected="selected"':''?> value="18">12:00-18:00</option>
           		 <option  <?php echo $inputPlan->input_time=='24'?'selected="selected"':''?>  value="24">18:00-24:00</option>            	
	        </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<input type="text" readonly name="" id="team" value="<?php echo $purchase->team->name;?>"   class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务员：</div>
		<input type="text" readonly name="" id="owned" value="<?php echo $basePurchase->belong->nickname;?>"   class="form-control con_tel"   >
		<input type="hidden"  name="CommonForms[owned_by]" id="owned_by" value="<?php echo $basePurchase->owned_by;?>"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入库仓库：</div>
		<input type="text"  name="" readonly id="warehouse" value="<?php echo $purchase->warehouse->name;?>"   class="form-control con_tel"   >
		<input type="hidden"  name="FrmInput[warehouse_id]" id="warehouse_id" value="<?php echo $purchase->warehouse_id?>"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmInput[ship_no]" id="transfer_number" value="<?php echo $inputPlan->ship_no;?>"  class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" value="<?php echo $baseform->comment?>"  class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">创建时间：</div>
		<input type="text"  name="CommonForms[form_time]" value="<?php echo $baseform->form_time;?>"  class="form-control date input_backimg" placeholder="请选择日期"  >
	</div>
	<div class="shop_more_one"> 
		<div class="shop_more_one_l">是否船舱：</div>
		<input class="check_box l" disabled type="checkbox" <?php echo $inputPlan->input_type=="ccrk"?'checked="checked"':'';?> name="FrmInput[is_cc]" value="1" /><div class="lab_check_box">船舱入库</div>
	</div>
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<!--  --<th class="text-center" style="width:5%;">操作</th>-->
         		<th class="text-center" style="width:9%;">产地</th>
         		<th class="text-center" style="width:9%;">品名</th>
         		<th class="text-center" style="width:9%;">材质</th>
         		<th class="text-center" style="width:9%;">规格</th>        		
         		<th class="text-center" style="width:9%;">长度</th>
         		
         		<th class="text-center" style="width:6%;">入库件数</th>
         		<th class="text-center" style="width:6%;">入库重量</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	<?php if(!empty($details)){ $i=1;foreach ($details as $data){?>
    	<tr class="<?php echo $i%2==0?"selected":""?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    	<!--  ->	<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>-->
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $data->brand_id?>" class="form-control yes td_brand">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo DictGoodsProperty::getProName($data->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $data->purchase_detail_id;?>"  class="detail_id"/>
    			<input type="hidden"  name="td_products[]" value="<?php echo $data->product_id?>" class="form-control yes td_product">
	    		<input type="text" readonly name="td_products_name[]" value="<?php echo DictGoodsProperty::getProName($data->product_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $data->texture_id?>" class="form-control yes td_texture">
	    		<input type="text" readonly name="td_textures_name[]" value="<?php echo DictGoodsProperty::getProName($data->texture_id)?>" class="form-control">
    		</td>  
    		<td class="">
    			<input type="hidden"  name="td_ranks[]" value="<?php echo $data->rank_id?>" class="form-control yes td_rank">
	    		<input type="text" readonly name="td_ranks_name[]" value="<?php echo DictGoodsProperty::getProName($data->rank_id)?>" class="form-control">
    		</td>   		  		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $data->length;?>" class="form-control td_length"  ></td>
    		<td ><input type="text"  readonly name="td_amount[]" style="" value="<?php echo $data->input_amount;?>" class="form-control td_amount td_num"  ></td>
    		<td >
    			<input type="text"  name=""  readonly value="<?php echo round($data->input_weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $data->input_weight;?>" style="" class="form-control  td_total_weight" >
    		</td>
    	</tr>
    	<?php $i++;}}?>
    	
    </tbody>
  </table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('input/index')?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<div style="clear: both;" id="sales_list">


</div>

<script type="text/javascript">
var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
	var create_time=$('.create_time').val();
	var input_date=$('#input_date').val();
	if(input_date!='')
	{
		if(create_time!='')
		{
			if(input_date<create_time)
			{
				confirmDialog('预计到货日期须大于创建日期');
				return false;
			}
		}else{
			var datenow=CurrentTime();
			if(input_date<datenow)
			{
				confirmDialog('预计到货日期须大于当前日期');
				return false;
			}
		}
	}else{
		confirmDialog('请选择输入预计到货日期');
		return false;
	}
	var input_time=$('.input_time').val();
	if(input_time=='0')
	{
		confirmDialog('请输入预计到货时段');
		return false;
	}
	
	var traver = $("#transfer_number").val();
	if(traver!='')
	{
		var result = checkTravel(traver);
		if(result != 1){confirmDialog(result);return false;}
	}
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
</script>
