<style>
<!--
.gaokai_input{display:none;}
-->
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
<script type="text/javascript">
var array_brand=<?php echo $brands;?>;
var array=<?php echo $vens?$vens:json_encode(array());?>;
var array2=<?php echo $coms;?>;
var array3=<?php echo $warehouses;?>;
var array4=<?php echo $teams?$teams:json_encode(array());?>;
var array_vendor=<?php echo $vendors?$vendors:json_encode(array())?>;
</script>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l title_div"><span class="bitian">*</span>公司抬头：</div>
		<div id="" class="fa_droplist">
			<input type="text" id="" readonly value="<?php echo $salesReturn->title->short_name;?>" class="form-control"/>
			<input type='hidden' id='comboval2' value="<?php echo $salesReturn->title_id;?>"  name="FrmSalesReturn[title_id]" class="form-control"/>
		</div>
	</div>
	<div class="shop_more_one" style="position:relative">
		<div class="shop_more_one_l" ><span class="bitian">*</span>客户：</div>		
		<div id="" class="fa_droplist">
				<input type="text" id="" readonly value="<?php echo $salesReturn->client->short_name;?>" class="form-control" />
				<input type='hidden' id='comboval1'  value="<?php echo $salesReturn->client_id?>"  name="FrmSalesReturn[client_id]" class="form-control"/>
			</div>		
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmSalesReturn[contact_id]" class='form-control chosen-select se_ywz' id="contact_id">
				<option value=""></option>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="" class="form-control con_tel" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name="CommonForms[owned_by]" disabled id="CommonForms_owned_by" onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php foreach($users as $k=>$v){?>
	            <option <?php echo $baseform?($baseform->owned_by==$k?'selected="selected"':''):(Yii::app()->user->userid==$k?'selected="selected"':'');?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
	       <input type="hidden" name="CommonForms[owned_by]"   value="<?php echo $baseform->owned_by?>">	
	</div>
	<div class="shop_more_one" style="position:relative">
		<div class="shop_more_one_l" ><span class="bitian">*</span>结算单位：</div>		
		<div id="" class="fa_droplist">
				<input type="text" id="" readonly value="<?php echo $salesReturn->company->short_name;?>" class="form-control" />
				<input type='hidden' id='comboval'  value="<?php echo $salesReturn->company_id?>"  name="FrmSalesReturn[company_id]" class="form-control"/>
			</div>		
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
			<select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>		
	       <input type="hidden" name="FrmSalesReturn[team_id]"   value="">
	</div>	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开单日期：</div>
		<div class="search_date_box" >
			<input type="text"  name="CommonForms[form_time]" readonly id="form_time" value="<?php echo $baseform->form_time;?>" class="form-control " placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计退货日期：</div> 
		<div class="search_date_box" >
			<input type="text"  name="FrmSalesReturn[return_date]" id="date_reach" value="<?php echo $salesReturn->return_date>0?date('Y-m-d',$salesReturn->return_date):''?>" class="form-control  date  "  placeholder="选择日期"  >
 		</div> 
 	</div> 	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>退货仓库：</div>
		<div id="" class="fa_droplist">
			<input type="text" id="" readonly class="form-control" value="<?php echo $salesReturn->warehouse->name?>" />
			<input type='hidden' id='comboval3'  value="<?php echo $salesReturn->warehouse_id?>" class="wareinput" name="FrmSalesReturn[warehouse_id]"/>
		</div>
	</div>	
	<div class="shop_more_one">
		<div class="shop_more_one_l">退货方式：</div>
		<input type="radio"  name="FrmSalesReturn[return_type]" <?php echo $salesReturn->return_type=='warehouse'?'checked':'';?>  value="warehouse" id="return_type_ware" disabled style="margin-top: 8px;" class=" check_box l return_type"   placeholder=""  ><div class="lab_check_box"  style="margin-top:0px;"><label for="return_type_ware">仓库</label></div>
		<input type="radio" name="FrmSalesReturn[return_type]" <?php echo $salesReturn->return_type=='supply'?'checked':'';?> value="supply" id="return_type_supply" disabled  class="check_box l return_type" style="margin-left:10px;margin-top:8px;" /><div class="lab_check_box"  style="margin-top:0px;"><label for="return_type_supply">供应商</label></div>
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l">运发责任：</div>
		<input type="radio"  name="FrmSalesReturn[tran_type]" <?php echo $salesReturn->tran_type=='get'?'checked':'';?> value="get" id="tran_type_get" style="margin-top: 8px;" class="check_box l" disabled placeholder=""><div class="lab_check_box"  style="margin-top:0px;"><label for="tran_type_get">自提</label></div>
		<input type="radio" name="FrmSalesReturn[tran_type]" <?php echo $salesReturn->tran_type=='send'?'checked':'';?> value="send" id="tran_type_send" class="check_box l" disabled style="margin-left:10px;margin-top:8px;"><div class="lab_check_box"  style="margin-top:0px;"><label for="tran_type_send">送货</label></div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>车船号：</div>
		<input type="text"  name="FrmSalesReturn[travel]" class="form-control transfer"  value="<?php echo $salesReturn->travel;?>" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" class="form-control tit_remark"  value="<?php echo $baseform->comment?>" placeholder=""  >
	</div>
	<div class="shop_more_one"> 
		<input class="check_box l" style="margin-left:80px;" disabled type="checkbox" <?php echo $salesReturn->is_yidan==1?'checked="checked"':''?> name="FrmSalesReturn[is_yidan]" id="is_yidan" value="1" /><div class="lab_check_box"><label for="is_yidan">乙单</label></div>
		<input class="check_box l gaokai" style="margin-left:80px;" type="checkbox"  disabled <?php echo $salesReturn->gaokai_target?'checked="checked"':''?>  name="FrmSalesReturn[is_gaokai]" value="1" /><div class="lab_check_box">高开</div>
	</div>
		<div class="shop_more_one gaokai_input">
		<div class="shop_more_one_l">高开金额：</div>
		<input type="text"  name="FrmSalesReturn[gaokai_money]" disabled value="<?php echo $salesReturn->gaokai_money?>" class="form-control " placeholder=""  >
	</div>
	<div class="shop_more_one gaokai_input">
		<div class="shop_more_one_l">采购员：</div>
			<input type="text" id="" class="form-control" disabled value="<?php echo DictCompany::getName($salesReturn->gaokai_target)?>" />			
	</div>	
	<div class="shop_more_one supply_id" style="position:relative;display:<?php echo $salesReturn->return_type=='supply'?'block':'none'?>" >
		<div class="shop_more_one_l" ><span class="bitian">*</span>退货供应商：</div>		
		<div id="" class="fa_droplist">
				<input type="text" id="" readonly class="form-control" value="<?php echo $salesReturn->supply->short_name;?>" />
				<input type='hidden' id='combovalvendor'  value="<?php echo $salesReturn->supply_id?>"  name="FrmSalesReturn[supply_id]"/>
			</div>		
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">退货理由：</div> 
		<div class="search_date_box" style="margin-top:0px;width:145px;background-position:155px 8px;">
			<select name="FrmSalesReturn[back_reason]" disabled class="form-control back_reason" >
			  <?php foreach(FrmSales::$reasons as $k=>$v){?>
	            	<option  value='<?php echo $k;?>' <?php echo $salesReturn->back_reason_val==$k?'selected="selected"':''?>><?php echo $v;?></option>
	            <?php }?>
			</select>
 		</div> 
 	</div> 		
 	<div class="shop_more_one other_reason_div" style="width:450px;display:<?php echo $salesReturn->back_reason_val==-1?'block':'none'?>"> 
 		<div class="shop_more_one_l" >其他理由：</div> 
 		<input type="text"  style="width:300px;" readonly name="FrmSalesReturn[other_reason]" value="<?php echo $salesReturn->back_reason_val==-1?$salesReturn->back_reason:'';?>" class="form-control  other_reason" placeholder="请输入其他理由"  >
 	</div>
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>规格</th>         		         		
         		<th class="text-center" style="width:4%;">长度</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>退货件数</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>退货重量</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>退货单价</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>金额</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    <?php if(!empty($details)){ $i=1; 	foreach ($details as $each)	{	?>
        <tr class="<?php echo $i%2==0?'selected':''?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="">
					<input type="text" class="form-control" readonly   value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" />
					<input type='hidden' id='<?php echo "bbcombovalbrand".$i?>' value="<?php echo $each->brand_id?>"   name="td_brands[]" class="td_brand"/>
    		</td>
    		<td class="">
    			<select name='td_products[]'  class='form-control chosen-select td_product' disabled>
    			<?php foreach($products as $k=>$v){?>
	            	<option <?php echo $each->product_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="td_textures[]" class="form-control chosen-select td_texture" disabled>
	            <?php foreach($textures as $k=>$v){?>
	            	<option <?php echo $each->texture_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>    		
    		<td class="">
    			<select name="td_ranks[]" class="form-control chosen-select td_rank"  disabled>
	            <?php foreach($ranks as $k=>$v){?>
	            	<option <?php echo $each->rank_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>    		
    		<td class=""><input type="text" readonly name="td_length[]" style="" value="<?php echo $each->length;?>" class="form-control td_length" placeholder=""  ></td>
    			
    		<td class="">
    			<input type="text"  readonly name="td_amount[]" style="" value="<?php echo $each->return_amount;?>" class="form-control td_num" placeholder=""  >
    			<input type="hidden" name="td_id[]" value="<?php echo $each->id;?>">
    		</td>
    		<td class="">
    			<input type="text"  name="" readonly value="<?php echo round($each->return_weight,3);?>" style="" class="form-control td_total_weight td_weight" placeholder=""  >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $each->return_weight;?>" style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class=""><input type="text"  readonly name="td_price[]" style="" value="<?php echo round($each->return_price,2);?>" class="form-control td_price" placeholder=""  ></td>
    		<td class=""><input type="text"  readonly name="td_totalMoney[]" value="<?php echo number_format($each->return_weight*$each->return_price,2);?>" style="" class="form-control td_money" placeholder=""  ></td>
    	</tr>
    
    <?php $i++; } }?>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('SalesReturn/index',array("page"=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
    var date_now='<?php echo date('Y-m-d',time());?>';
	var array_brand=<?php echo $brands;?>;
</script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script>
$('.return_type').click(function(){
	if($(this).val()=='warehouse')
	{
		$('.supply_id').hide();
	}else{
		$('.supply_id').show();
	}
})
var gaokai=<?php echo $salesReturn->gaokai_target?1:0?>;
	$(function(){
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
		$('#combovendor').combobox(array_vendor, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"vendorselect","combovalvendor");

		var contact='<?php echo $salesReturn->contact_id?>';
		var mobile='<?php echo $salesReturn->contact->mobile;?>';
		changeCont();
		$('#contact_id').val(contact);
		$('#phone').val(mobile);
		if(gaokai){
			$('.gaokai_input').show();
		}
	})
	$('#submit_btn').unbind();
	var  can_submit = true;
	$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var str='';
		var contact=$('#contact_id').val();
		var customer=$('#comboval').val();
		var client=$('#comboval1').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		var ware=$('.wareinput').val();
		var transfer=$('.transfer').val();
		var date_reach=$('#date_reach').val();
		var cggs = $("#comboval2").val();
		if(cggs==''){alert("请选择输入公司抬头！");return false;}		
		if(client==''){confirmDialog("请选择输入客户");return false;}
		if(customer==''){confirmDialog("请选择输入结算单位");return false;}
		if(owned_by==''){alert("请选择输入业务员！");return false;}
		if(form_time==''){alert("请选择输入开单日期！");return false;}
		if(transfer==''){alert('请输入车船号');return false;}
		if(ware==''){alert('请选择输入仓库');return false;}
		if(contact==''){alert("请选择输入联系人！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			alert('预计退货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}	
		var return_type=$('.return_type').val();
		if(return_type=='supply')
		{
			var supply_id=$('#combovalvendor').val();
			if(supply_id=='')
			{
				confirmDialog("请选择输入退货供应商");
				return false;
			}
		}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
	function checkRepeat(brand,product,texture,rank,length)
	{
		var temp=[brand,product,texture,rank,length];
		if(goods_arr.indexOf(temp.toString())>-1)
		{
			return false;
		}else{
			goods_arr.push(temp.toString());
			return true;
		}
	}
	changeOwnerT();
	var brand_name='';
	</script>