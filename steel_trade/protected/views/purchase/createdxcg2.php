<?php
$type= $_GET['type'];
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
$mainInfo=json_decode($mainInfo);
?>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="shop_select_box">
	<div class="shop_more_one">
			<input type="hidden" name="SalesDetail" value=""  class="frmsales_id">
			<input type="hidden" name="FrmPurchase[purchase_type]" value="dxcg"/>
			<input type="hidden" name="FrmPurchase[sales_details_array]" value='<?php echo $details_array;?>'/>
			<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
			<div id="venselect" class="fa_droplist">
				<input type="text" id="combo" value="<?php echo $mainInfo->supply_name;?>" />
				<input type='hidden' id='comboval'  value="<?php echo $mainInfo->supply_id;?>"  name="FrmPurchase[supply_id]"/>
			</div>
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" value="<?php echo $mainInfo->title_name;?>" />
			<input type='hidden' id='comboval2' value="<?php echo $mainInfo->title_id;?>"  name="FrmPurchase[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购日期：</div>
			<input type="text"  name="CommonForms[form_time]" value="<?php echo date('Y-m-d',time());?>" class="form-control date input_backimg" placeholder="选择日期"  >
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" class="form-control  invoice_cost"  value="<?php //echo $invoice_cost?>"  placeholder=""  ><span style="float:right;margin:-33px -25px 0 0;">元/吨</span>
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]" style="width: 145px;" class='form-control chosen-select se_ywz' id="contact_id">
				<?php //if(!empty($contacts)){foreach($contacts as $k=>$v){?>
	            	<!--  -><option value='<?php echo $k;?>'><?php echo $v;?></option>-->
	            <?php //}}?>
	     </select>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()"   class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            	<option <?php echo $k==Yii::app()->user->userid?'selected="selected"':''?>  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>	
		<select name="" id="team_id" disabled class="form-control chosen-select se_yw">
		 		<option selected="selected" value=''></option>
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option <?php echo $mainInfo->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	    </select>		
		<input type="hidden" name="FrmPurchase[team_id]"   value="<?php echo $mainInfo->team_id;?>">
	</div>
		<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计到货日期：</div> 
			<input type="text"   name="FrmPurchase[date_reach]" id="date_reach" class="form-control form-date date input_backimg"  placeholder="选择日期"  >
 	</div> 
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone"  class="form-control con_tel" placeholder=""  >
	</div>
 	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]"  class="form-control tit_remark" id="transfer_number" placeholder=""  >
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l">入库仓库：</div>
		<input type="text"  name="" readonly value="<?php echo $mainInfo->warehouse_name;?>"  class="form-control "  >
		<input type="hidden"  name="FrmPurchase[warehouse_id]"  class="form-control "  value="<?php echo $mainInfo->warehouse_id;?>"  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]"  class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l"><span class="bitian">*</span>运费单价：</div>
		<input type="text"  name="FrmPurchase[shipment]" class="form-control " id="shipment"  placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one" > 
		<input class="check_box l" style="margin-left: 90px;" type="checkbox" name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<input class=""  type="hidden" name="FrmPurchase[contain_cash]" id="contain_ship" value="1" />	
	</div>	
</div>

<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:9%;">产地</th>
         		<th class="text-center" style="width:9%;">品名</th>
         		<th class="text-center" style="width:9%;">材质</th>
         		<th class="text-center" style="width:5%;">规格</th>         		         		
         		<th class="text-center" style="width:5%;">长度</th>
    
         		<th class="text-center" style="width:7%;">件数</th>
         		<th class="text-center" style="width:7%;">重量</th>
         		<th class="text-center" style="width:7%;">单价</th>
         		<th class="text-center" style="width:7%;">金额</th>
         		<th class="text-center" style="width:7%;">开票成本</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	<?php $i=1; foreach ($zipped_array as $each){?>
    	<tr class="">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $each['brand_id']?>" class="form-control td_brand yes">
	    		<input type="text" readonly value="<?php echo $each['brand_name']?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $each['detail_id'];?>"  class="detail_id"/>
    			<input type="hidden"  name="td_products[]" value="<?php echo $each['product_id']?>" class="form-control td_product yes">
	    		<input type="text" readonly  value="<?php echo $each['product_name'];?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $each['texture_id']?>" class="form-control td_texture yes">
	    		<input type="text" readonly value="<?php echo $each['texture_name']?>" class="form-control">
    		</td>  
    		<td class="">
    			<input type="hidden"  name="td_ranks[]" value="<?php echo $each['rank_id']?>" class="form-control td_rank yes">
	    		<input type="text" readonly value="<?php echo $each['rand_name']?>" class="form-control">
    		</td>    		  		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $each['length'];?>" class="form-control td_length"  ></td>
    		<td ><input type="text"  readonly name="td_amount[]" style="" value="<?php echo $each['amount'];?>" class="form-control td_amount td_num"  ></td>
    		<td >
    			<input type="text" readonly  name=""  value="<?php echo round($each['weight'],3);?>" style="" class="form-control td_weight" >
    			<input type="hidden" readonly  name="td_weight[]"  value="<?php echo $each['weight'];?>" style="" class="form-control  td_total_weight" >
    		</td>
    		<td ><input type="text" readonly name="td_price[]" style="" value="<?php echo $each['price'];?>" class="form-control td_price" ></td>
    		<td >
    		<input type="text"  name="td_totalMoney[]" readonly value="<?php echo number_format($each['totalMoney'],2);?>"  class="form-control td_money" >
    		<input type="hidden" name="good_id[]"  class="good_id" value="<?php echo $each['good_id'];?>">    			
    		</td>
    		<td><input type="text" name="td_invoice[]" readonly class="form-control td_invoice"  value="<?php echo $each['invoice_price'];?>"></td>
    	</tr>
    	<?php $i++; }?>
    </tbody>
     <tfoot>
   		<tr class="tablefoot">
			<td class="text-center"  colspan=2>合计：</td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td style=""><span class="tf_total_amount">0</span></td>
			<td style=""><span class="tf_total_weight">0</span></td>
			<td style=""></td>
			<td style=""><span class="tf_total_money">0</span></td>
			<td></td>
		</tr>
   </tfoot>
  </table>
</div>
<div class="btn_list" style="margin-top:30px;">
	<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal" style="background:#426ebb;float:right;margin-right:20px;" id="submit_btn1">保存提交</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="float:right;" id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('purchase/createDxcgStepOne',array('details_array'=>$details_array,'detail_ids'=>$detail_ids))?>">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="float:right;" id="submit_btn1">上一步</button>
	</a> 
	<a href="<?php echo Yii::app()->createUrl('purchase/index',array("page"=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;float:right" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>


<script type="text/javascript">
	$(".deleted_tr").live("click",function(){
		var row_num=0;
		$(this).parent().parent().remove();
		$("#cght_tb tbody tr").each(function(){
			row_num++;
			$(this).find(".list_num").html(row_num);
			if(row_num%2 == 0){
				$(this).addClass("selected");
			}else{
				$(this).removeClass("selected");
			}
		});
		$("#tr_num").val(row_num);
	})
var  can_submit = true;
$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var contact=$('#contact_id').val();
		var date_reach=$('#date_reach').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(contact==''||!contact){confirmDialog("请选择输入联系人！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}
		var traver = $("#transfer_number").val();
		var cost_price=$('.invoice_cost').val();
		if(cost_price==''){confirmDialog('请输入开票成本');return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}	
		var shipment=$('#shipment').val();
		if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = $(this).find(".td_price").val();
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        //setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
	
	$("#submit_btn1").click(function(){
		if(!can_submit){return false;}
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var date_reach=$('#date_reach').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}
		var traver = $("#transfer_number").val();
		var cost_price=$('.invoice_cost').val();
		if(cost_price==''){confirmDialog('请输入开票成本');return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}	
		var shipment=$('#shipment').val();
		if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = $(this).find(".td_price").val();
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
		})
		if(!flag){return false;}
		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
		$(this).parent().append(str);
		if(can_submit){
	        can_submit = false;
	        //setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn1');
	        $("#form_data").submit();
	    }
	})
	
		$('#contain_ship').click(function(){
		var check=$(this).attr('checked');
		if(check)
		{
			$('#ship').show();
		}else{
			$('#ship').hide();
		}
	});
</script>
<script>
	    changeCont();
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
// 			confirmDialog(contact_id);
			$.get('getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script>
	$(function(){
		var array=<?php echo $vendors;?>;
		var array2=<?php echo $coms;?>;
		var array3=<?php echo $warehouses;?>;		
		var array5=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array6=<?php echo $vens?$vens:json_encode(array());?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");		
		$('#combo5').combobox(array5, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"saleselect","comboval5");
		$('#combo6').combobox(array6, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","comboval6");		
		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();		
	})
	</script>