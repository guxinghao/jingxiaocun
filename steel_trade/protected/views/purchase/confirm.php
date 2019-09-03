<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="shop_select_box">
	<div class="shop_more_one">
			<div class="shop_more_one_l">供应商：</div>
			<input type="text"  value="<?php echo $purchase->supply->short_name?>" readonly   class="form-control con_tel"/>
			<input type="hidden" value="<?php echo $purchase->supply_id;?>" id="comboval">
	</div>
	<div class="shop_more_one" style="position:relative">
		<div class="shop_more_one_l" >采购合同：</div>
			<input type="hidden" id="frmpurchase_contract_hidden" value="<?php echo $purchase->frm_contract_id?>">
			<input type="text" readonly="readonly" value="<?php echo $purchase->contract_baseform->contract->contract_no;?>"   class="form-control con_tel"   >
	</div>
		
	<div class="shop_more_one">
		<div class="shop_more_one_l">公司：</div>
		<div id="ywyselect" style="float:left; display:inline;position:relative">
			<input type="text" readonly value="<?php echo $purchase->title->short_name?>"    class="form-control con_tel" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购日期：</div>
			<input type="text"   readonly value="<?php echo $baseform->form_time?>"  class="form-control form-date date " placeholder="选择日期"  >
	</div>
	
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]" class='form-control chosen-select se_ywz' id="contact_id" >
				<?php if(!empty($contacts)){foreach($contacts as $k=>$v){?>
	            	<option <?php echo $purchase->contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            	<option <?php echo $baseform->owned_by==$k?'selected="selected"':"";?>  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>			
		<select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php  if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option <?php echo $purchase->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	    </select>			
	    <input type="hidden" name="FrmPurchase[team_id]"   value="<?php echo $purchase->team_id?>">
<!-- 		<div id="teamselect" class="fa_droplist"> --
			<input type="text" id="combo4" value="<?php  //echo $purchase->team->name?>" />
			<input type='hidden' id='comboval4'  value="<?php   //echo $purchase->team_id?>"  name="FrmPurchase[team_id]"/>
<!-- 		</div> -->
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">到货时间：</div> 
			<input type="text"    name="FrmPurchase[date_reach]" value="<?php echo $date_reach?$date_reach:($purchase->date_reach?date('Y-m-d',$purchase->date_reach):date('Y-m-d',time()));?>" class="form-control form-date date input_backimg"   placeholder="请选择日期">
 	</div> 
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="<?php echo $purchase->contact->mobile;?>" class="form-control con_tel"  >
	</div>
 	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]"  id="transfer_number" value="<?php echo $purchase->transfer_number?>"  class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入库仓库：</div>
			<input type="text"  readonly value="<?php echo $purchase->warehouse->name;?>"   class="form-control con_tel"/>
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" value="<?php //echo $purchase->invoice_cost;?>" style="margin-right:30px;" class="form-control " >
<!-- 		<span class="danwei">元/吨</span> -->
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" value="<?php echo $baseform->comment;?>"  class="form-control "  >
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l"><span class="bitian">*</span>运费单价：</div>
		<input type="text"  name="FrmPurchase[shipment]"  class="form-control " value="<?php echo $purchase->shipment?>"  id="shipment"  placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one">
		<input style="margin-left: 90px;" class="check_box l" type="checkbox"  <?php echo $purchase->is_yidan==1?'checked="checked"':''?> name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<input class="check_box l" type="checkbox" disabled <?php echo $purchase->purchase_type=="tpcg"?'checked="checked"':''?>  name="FrmPurchase[is_tp]" id="is_tp" value="1" /><div class="lab_check_box">托盘</div>
		<input type="hidden"  name="FrmPurchase[contain_cash]" value="1" />		
	</div>
	
</div>
<div class="tp_hidden" style="clear:both;width:99%;margin:0 auto;margin-bottom:10px;display:none;position:relative;">
	<div style="position:absolute;width:0; height:0; border-width:10px; border-color:transparent transparent #c8c8c8 transparent; border-style:dashed dashed solid dashed; overflow:hidden;  top:-20px;left:705px;"></div>
	<div style="position:absolute;width:0; height:0; border-width:10px; border-color:transparent transparent #fff transparent; border-style:dashed dashed solid dashed; overflow:hidden; top:-19px; left:705px;"></div>
	<!-- <div style="margin-left:40%;width: 0; height: 0; border-left: 10px solid #ccc; border-right: 10px solid transparent;border-bottom: 10px solid red;"></div> -->
	<div style="border:1px solid #ccc;border-radius:5px;width:100%;margin:0 auto;padding-top:10px;">
		<div class="shop_more_one">
			<div class="shop_more_one_l">托盘公司：</div>
	   		 <input type="text"  readonly value="<?php echo $purchase->pledge->pledgeCompany->short_name;?>" class="form-control "/>
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">托盘价格：</div>
				 <input type="text"  name="FrmPurchase[unit_price]" id="unit_price" readonly value="<?php echo $purchase->pledge->unit_price;?>"  class="form-control "   >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">托盘总金额：</div>
				 <input type="text"  name="FrmPurchase[fee]" readonly id="fee" value="<?php echo $purchase->pledge->fee;?>"  class="form-control tit_remark"  >
			</div>
			<div class="shop_more_one" style="width:280px;">
				<div class="shop_more_one_l" style="width:130px;">托盘赎回限制等级：</div>
	       		 <input type="text"  readonly value="<?php echo $purchase->pledge->r_limit==1?'产地':'产地+品名'?>"  class="form-control " >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">托盘预付款：</div>
				<input type="text"  name="FrmPurchase[advance]" readonly value="<?php echo $purchase->pledge->advance;?>"  class="form-control tit_remark"  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘天数：</div>
				 <input type="text"  name="FrmPurchase[pledge_length]" readonly value="<?php echo $purchase->pledge->pledge_length?>" class="form-control pledge_length" placeholder=""  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>违约天数：</div>
				 <input type="text"  name="FrmPurchase[violation_date]" readonly value="<?php echo $purchase->pledge->violation_date?>" class="form-control violation_date" placeholder=""  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>最小利率：</div>
				 <input type="text"  name="FrmPurchase[min_rate]" readonly value="<?php echo $purchase->pledge->min_rate?>" class="form-control min_rate" placeholder=""  >
				 <span class="danwei">‰/天</span>
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘利率：</div>
				 <input type="text"  name="FrmPurchase[pledge_rate]" readonly value="<?php echo $purchase->pledge->pledge_rate?>" class="form-control pledge_rate" placeholder=""  >
				 <span class="danwei">‰/天</span>
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>违约利率：</div>
				 <input type="text"  name="FrmPurchase[over_rate]" readonly value="<?php echo $purchase->pledge->over_rate?>" class="form-control over_rate" placeholder=""  >
				 <span class="danwei">‰/天</span>
			</div>
			<div class="clear"></div>

		</div>
	</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="<?php echo count($details)?>">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:8%;">产地</th>
         		<th class="text-center" style="width:5%;">品名</th>
         		<th class="text-center" style="width:6%;">材质</th>
         		<th class="text-center" style="width:5%;">规格</th>         		         		
         		<th class="text-center" style="width:4%;">长度</th>
    			<th class="text-center" style="width:5%;">件数</th>
         		<th class="text-center" style="width:6%;">重量</th>
         		<th class="text-center" style="width:5%;">单价</th>
         		<?php if($purchase->purchase_type!='xxhj'){?>
         		<th class="text-center" style="width:7%;">已入库件数</th>
         		<th class="text-center" style="width:7%;">已入库重量</th>
         		<?php }?>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>核定件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>核定重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>核定单价</th>
         		<th class="text-center" style="width:7%;">金额</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>开票成本</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	 <?php $i=1; foreach ($details as $each){?>
    	<tr class="">
    		
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
    		<td ><input type="text"  readonly style="" value="<?php echo $each->amount;?>" class="form-control "  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo sprintf('%.3f',$each->weight);?>" class="form-control "  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo number_format($each->price,2);?>" class="form-control "  ></td>
    		<?php if($purchase->purchase_type!='xxhj'){?>
    		<td ><input type="text"  readonly style="" value="<?php echo $each->input_amount;?>" class="form-control "  ></td>
    		<td ><input type="text"  readonly style="" value="<?php echo sprintf('%.3f',$each->input_weight);?>" class="form-control "  ></td>
    		<?php }?>
    		<td ><input type="text"  name="td_amount[]" <?php echo $purchase->purchase_type=='xxhj'?'':'readonly'?> style="" value="<?php echo $purchase->purchase_type=='dxcg'?$each->amount:$each->input_amount;?>" class="form-control td_amount td_num"  ></td>
    		<td >
    			<input type="text"  name=""  <?php echo $purchase->purchase_type=='xxhj'?'':'readonly'?>  value="<?php echo  $purchase->purchase_type=='dxcg'?sprintf('%.3f',$each->weight):sprintf('%.3f',$each->input_weight);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $purchase->purchase_type=='dxcg'?round($each->weight,3):round($each->input_weight,3);?>" style="" class="form-control  td_total_weight" >
    		</td>
    		<td ><input type="text"  name="td_price[]"  style="" value="<?php echo round($each->price,2);?>" class="form-control td_price" ></td>
    		<td ><input type="text"  name="td_totalMoney[]" readonly value="<?php echo $purchase->purchase_type=='dxcg'?number_format(round($each->weight,3)*$each->price,2):number_format(round($each->input_weight,3)*$each->price,2);?>"  class="form-control td_money" ></td>
    		<td ><input type="text" name="old_td_invoice[]"  readonly class="form-control td_invoice" value="<?php echo $each->invoice_price;?>"></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
  </table>
</div>

<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#1c9fe1;" id="submit_btn">核定</button>
	<a href="<?php echo Yii::app()->createUrl('purchase/index')?>">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#d5d5d5;color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
<?php if($msg){?>
confirmDialog('<?php echo $msg;?>');
<?php }?>

var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
// 	var traver = $("#transfer_number").val();
// 	if(traver=='')
// 	{
// 		confirmDialog('请输入车船号');return false;
// // 		var result = checkTravel(traver);
// // 		if(result != 1){confirmDialog(result);return false;}
// 	}
		var shipment=$('#shipment').val();
		if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_amount").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数须为大于0的整数");$(this).find('.td_amount').addClass('red-border');flag= false;return false;}
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
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script>
var array4=<?php echo $teams;?>;
$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"teamselect","comboval4",false,'changeTeamU()');
		//托盘
		$(function(){
			var check=$('#is_tp').attr('checked');
			if(check)
			{
				$('.tp_hidden').show();
			}else{
				$('.tp_hidden').hide();
			}
		}); 
		$('#contain_ship').click(function(){
			var check=$(this).attr('checked');
			if(check)
			{
				$('#ship').show();
			}else{
				$('#ship').hide();
			}
		}); 
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });
		
	    function changeCont()
	    {
	    	$.ajaxSetup({ async: false });
			var vendor_id=$('#comboval').val();
			$.get('/index.php/contract/getVendorCont',{
				'vendor_id':vendor_id,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1o'));
				var data2=data.substring(data.indexOf('o1o')+3);
				$('#contact_id').html(data1);
				$('#phone').val(data2);
				
			});
			//填充运费
			$.get('/index.php/purchase/getShipment/'+vendor_id,{},function(data){
				if(data){$('#shipment').val(data);}
			})
			return true;			
		}	   

	    //件数改变
	    $.ajaxSetup({ async: false });
	    var unit_weight=0;
		$(document).on('change','.td_num',function(){
			//)$('.td_num').change(function(){			
			var that=$(this);
			var td_num=$(this).val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			//获取件重			
			var product=$(this).parent().parent().find('.td_product').val();
			var rank=$(this).parent().parent().find('.td_rank').val();
			var texture=$(this).parent().parent().find('.td_texture').val();
			var brand=$(this).parent().parent().find('.td_brand').val();
			var length=$(this).parent().parent().find('.td_length').val();
			$.get('/index.php/contract/getUnitWeight',{
				'product':product,
				'rank':rank,
				'texture':texture,
				'brand':brand,
				'length':length,
	    		},function(data){
	    			if(data===false||data==='')
	    			{
		    			confirmDialog('根据品名/规格/材质/产地未找到对应商品，请重新选择');
		    			that.val('');
		    			return;
	    			}
	    			unit_weight=data;	    	
	    			if(!/^[1-9][0-9]*$/.test(td_num))
	    			{
	    				confirmDialog('件数必须为大于0的整数');
	    				return;
	    			}
	    			if(unit_weight!=0)
	    			{
	    				that.parent().parent().find('.td_total_weight').val(td_num*unit_weight);
		    			that.parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));	
	    			}	    			
	    	});				
			
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			
		});

		//重量改变
		$(document).on('change','.td_weight',function(){
			//改变金额
			var td_weight=$(this).val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
			{						
				confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
				return;
			}
			$(this).next().val(td_weight);
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;								
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
		});
		
	    //单价改变
		$(document).on('change','.td_price',function(){
			var td_price=numChange($(this).val());
			var td_num=$(this).parent().parent().find('.td_num').val();		
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();		
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price))
			{						
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;		
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(numberFormat((td_price*td_weight).toFixed(2),2));
		});	

	    if(changeCont())
	    {
		    var contact_id='<?php echo $purchase->contact_id;?>';
		    var mobile='<?php echo $purchase->contact->mobile;?>';
		    var ship2='<?php echo $purchase->shipment?>';
		    $('#contact_id').val(contact_id);
		    $('#phone').val(mobile);
		    if(parseFloat(ship2)!=0){$('#shipment').val(ship2);}
	    }
	</script>
