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
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l ">公司抬头：</div>
			<input type="text"  class="form-control" readonly value="<?php echo $salesReturn->title->short_name;?>" />
	</div>
	<div class="shop_more_one" >
		<div class="shop_more_one_l" >客户：</div>		
				<input type="text"  class="form-control" id="" readonly value="<?php echo $salesReturn->client->short_name;?>" />
				<input type="hidden" value="<?php echo $salesReturn->client_id;?>" id="comboval1">
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
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            <option <?php echo $baseform?($baseform->owned_by==$k?'selected="selected"':''):(Yii::app()->user->userid==$k?'selected="selected"':'');?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	</div>
	<div class="shop_more_one" >
		<div class="shop_more_one_l" >结算单位：</div>		
				<input type="text"  class="form-control" id="" readonly value="<?php echo $salesReturn->company->short_name;?>" />
				<input type="hidden" value="<?php echo $salesReturn->company_id;?>" id="comboval">
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
		<div class="shop_more_one_l">开单日期：</div>
		<div class="search_date_box" >
			<input type="text"  name="CommonForms[form_time]" readonly value="<?php echo $baseform->form_time;?>" class="form-control " placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">退货日期：</div> 
		<div class="search_date_box" >
			<input type="text"  name="FrmSalesReturn[return_date]" id="date_reach" value="<?php echo $salesReturn->return_date?date('Y-m-d',$salesReturn->return_date):'';?>" class="form-control  date  "  placeholder="选择日期"  >
 		</div> 
 	</div> 	
	<div class="shop_more_one">
		<div class="shop_more_one_l">退货仓库：</div>
			<input type="text" readonly class="form-control" value="<?php echo $salesReturn->warehouse->name?>" />
	</div>	
	<div class="shop_more_one">
		<div class="shop_more_one_l">退货方式：</div>
		<input type="radio"  name="FrmSalesReturn[return_type]" disabled <?php echo $salesReturn->return_type=='warehouse'?'checked':'';?>  value="warehouse"class=" check_box l return_type"  placeholder=""  ><div class="lab_check_box">仓库</div>
		<input type="radio" name="FrmSalesReturn[return_type]" disabled <?php echo $salesReturn->return_type=='supply'?'checked':'';?> value="supply" class="check_box l return_type" style="margin-left:10px;" /><div class="lab_check_box">供应商</div>
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l">运发责任：</div>
		<input type="radio"  name="FrmSalesReturn[tran_type]" disabled <?php echo $salesReturn->tran_type=='get'?'checked':'';?> value="get" class="check_box l" placeholder=""><div class="lab_check_box">自提</div>
		<input type="radio" name="FrmSalesReturn[tran_type]" disabled <?php echo $salesReturn->tran_type=='send'?'checked':'';?> value="send" class="check_box l" style="margin-left:10px;"><div class="lab_check_box">送货</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>车船号：</div>
		<input type="text"  name="FrmSalesReturn[travel]" class="form-control transfer"  value="<?php echo $salesReturn->travel;?>" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" class="form-control tit_remark" <?php echo $baseform->comment?> placeholder=""  >
	</div>
	<div class="shop_more_one"> 
		<input class="check_box l" style="margin-left:80px;" type="checkbox" <?php echo $salesReturn->is_yidan==1?'checked="checked"':''?> name="FrmSalesReturn[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
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
		<div class="shop_more_one_l" >退货供应商：</div>		
				<input type="text" id="combovendor" readonly class="form-control" value="<?php echo $salesReturn->supply->short_name;?>" />
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
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>规格</th>         		         		
         		<th class="text-center" style="width:4%;">长度</th>
         		<th class="text-center" style="width:9%;">卡号</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>退货件数</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>退货重量</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>退货单价</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>核定件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>核定重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>核定单价</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>金额</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    <?php if(!empty($details)){ $i=1; 	foreach ($details as $each)	{	?>
        <tr class="<?php echo $i%2==0?'selected':''?>">
        	<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="">
    			<input type="hidden"  value="<?php echo $each->brand_id?>" class="form-control yes td_brand">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $each->id;?>" />
    			<input type="hidden"   value="<?php echo $each->product_id?>" class="form-control yes td_product">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each->product_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  value="<?php echo $each->texture_id?>" class="form-control yes td_texture">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each->texture_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  value="<?php echo $each->rank_id?>" class="form-control yes td_rank">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each->rank_id)?>" class="form-control">
    		</td>    		
    		<td ><input type="text"  readonly style="" value="<?php echo $each->length;?>" class="form-control td_length"  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo $each->card_no;?>" class="form-control "  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo $each->return_amount;?>" class="form-control "  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo sprintf('%.3f',$each->return_weight);?>" class="form-control "  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo number_format($each->return_price,2);?>" class="form-control "  ></td>
    		<td ><input type="text"  name="td_amount[]"  style="" value="<?php echo $each->input_amount;?>" class="form-control td_amount td_num"  ></td>
    		<td >
    			<input type="text"  name=""    value="<?php echo  sprintf('%.3f',$each->input_weight);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $each->input_weight;?>" style="" class="form-control  td_total_weight" >
    		</td>
    		<td ><input type="text"  name="td_price[]"  style="" value="<?php echo round($each->return_price,2);?>" class="form-control td_price" ></td>
    		<td ><input type="text"  name="td_totalMoney[]" readonly value="<?php echo number_format($each->input_weight*$each->return_price,2);?>"  class="form-control td_money" ></td>
    	</tr>
    <?php $i++; } }?>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">核定</button>
	<a href="<?php echo Yii::app()->createUrl('SalesReturn/index',array("page"=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
    var date_now='<?php echo date('Y-m-d',time());?>';
</script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script>
<script>
// $('.return_type').click(function(){
// 	if($(this).val()=='warehouse')
// 	{
// 		$('.supply_id').hide();
// 	}else{
// 		$('.supply_id').show();
// 	}
// })
	$(document).on("blur","input",function(){
		$(this).removeClass("red-border");
	});
	var gaokai=<?php echo $salesReturn->gaokai_target?1:0?>;
	$(function(){	
		var msg = '<?php echo $msg;?>';
		if(msg)confirmDialog(msg);	
		var contact='<?php echo $salesReturn->contact_id?>';
		var mobile='<?php echo $salesReturn->contact->mobile;?>';
		changeCont();
		$('#contact_id').val(contact);
		$('#phone').val(mobile);
		if(gaokai){
			$('.gaokai_input').show();
		}
	})
	var selected_contract='';
	changeOwnerT();
	$('#submit_btn').unbind();
	var  can_submit = true;
	$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var str='';
		var contact=$('#contact_id').val();
		var owned_by=$('#CommonForms_owned_by').val();
		var transfer=$('.transfer').val();
		var date_reach=$('#date_reach').val();
		if(owned_by==''){alert("请选择输入业务员！");return false;}
		if(transfer==''){alert('请输入车船号');return false;}
		if(contact==''){alert("请选择输入联系人！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			alert('预计到货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数须为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价须为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
	changeOwnerT();
	</script>