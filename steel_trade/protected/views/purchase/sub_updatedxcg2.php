<?php
$type= $_GET['type'];
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
			<input type="hidden" name="FrmPurchase[purchase_type]" value="dxcg"/>
			<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
			<div id="" class="fa_droplist">
				<input type="text" id="" readonly value="<?php echo $purchase->supply->short_name?>" class="form-control"/>
				<input type='hidden' id='comboval'  value="<?php echo $purchase->supply_id?>"  name="FrmPurchase[supply_id]"/>
			</div>
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="" class="fa_droplist">
			<input type="text" id="" readonly value="<?php echo $purchase->title->short_name;?>" class="form-control"/>
			<input type='hidden' id='comboval2' value="<?php echo $purchase->title_id?>"  name="FrmPurchase[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购日期：</div>
			<input type="text"  name="CommonForms[form_time]"  readonly value="<?php echo $baseform->form_time;?>" class="form-control date " placeholder="选择日期"  >
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" readonly value="<?php //echo $purchase->invoice_cost;?>" class="form-control " placeholder=""  ><span class="danwei">元/吨</span>
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]"  class='form-control chosen-select se_ywz' id="contact_id" >
				<?php //if(!empty($contacts)){ foreach($contacts as $k=>$v){?>
	            	<!--  -><option <?php echo $purchase->contact_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>-->
	            <?php //}}?>
	     </select>	     
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购员：</div>
		 <select name="" id="CommonForms_owned_by" disabled onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            	<option <?php echo $baseform->owned_by==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	       <input type="hidden" name="CommonForms[owned_by]" value="<?php echo $baseform->owned_by?>">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		<select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php if(!empty($teams)){ foreach($teams as $k=>$v){?>
	            	<option <?php echo $purchase->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	      </select>		
	      <input type="hidden" name="FrmPurchase[team_id]"   value="<?php echo $purchase->team_id?>">
	</div>	
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计到货日期：</div> 
			<input type="text" value="<?php echo $purchase->date_reach?date("Y-m-d",$purchase->date_reach):'';?>" name="FrmPurchase[date_reach]" id="date_reach" class="form-control  date  input_backimg"  placeholder="选择日期"  >
 	</div> 
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="<?php echo  $purchase->contact->mobile;?>" class="form-control con_tel" placeholder=""  >
	</div>
 	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]" value="<?php echo $purchase->transfer_number?>" id="" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入库仓库：</div>
		<input type="text"  name="" readonly value="<?php echo $purchase->warehouse->name;?>"  class="form-control "  >
		<input type="hidden"  name="FrmPurchase[warehouse_id]"  class="form-control "  value="<?php echo $purchase->warehouse_id;?>"  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" value="<?php echo $baseform->comment;?>" class="form-control " placeholder=""  >
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l"><span class="bitian">*</span>运费单价：</div>
		<input type="text"  name="FrmPurchase[shipment]" readonly value="<?php echo $purchase->shipment?>" id="shipment"  class="form-control " placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one" > 
		<input class="check_box l" style="margin-left:90px;" disabled type="checkbox"  <?php echo $purchase->is_yidan==1?'checked="checked"':''?>  name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<input class="" type="hidden"  name="FrmPurchase[contain_cash]" value="1" />
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
    			<input type="hidden"  name="td_brands[]" value="<?php echo $each->brand_id;?>" class="form-control td_brand yes">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each['brand_id']);?>" class="form-control">
    		</td>
    		<td class="">    			
    			<input type="hidden"  name="td_products[]" value="<?php echo $each['product_id']?>" class="form-control td_product yes">
	    		<input type="text" readonly  value="<?php echo DictGoodsProperty::getProName($each['product_id']);?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $each['texture_id']?>" class="form-control td_texture yes">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each['texture_id']);?>" class="form-control">
    		</td>  
    		<td class="">
    			<input type="hidden"  name="td_ranks[]" value="<?php echo $each['rank_id']?>" class="form-control td_rank yes">
	    		<input type="text" readonly value="<?php echo DictGoodsProperty::getProName($each['rank_id']);?>" class="form-control">
    		</td>    		  		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $each['length'];?>" class="form-control td_length"  ></td>

    		<td ><input type="text"  readonly name="td_amount[]" style="" value="<?php echo $each['amount'];?>" class="form-control td_amount td_num"  ></td>
    		<td >
    			<input type="text" readonly  name=""  value="<?php echo round($each['weight'],3);?>" style="" class="form-control td_weight" >
    			<input type="hidden" readonly  name="td_weight[]"  value="<?php echo $each['weight'];?>" style="" class="form-control  td_total_weight" >
    		</td>
    		<td ><input type="text" readonly name="td_price[]" style="" value="<?php echo round($each['price'],2);?>" class="form-control td_price" ></td>
    		<td >
    		<input type="text"  name="td_totalMoney[]" readonly value="<?php echo number_format($each['price']*$each['weight'],2);?>"  class="form-control td_money" >    		    			
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
<?php $this->endWidget()?>

<div class="btn_list" style="margin-top:30px;">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#1c9fe1;float:right;margin-right:20px" id="submit_btn">保存</button>	 
	<a href="<?php echo Yii::app()->createUrl('purchase/index')?>">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#d5d5d5;color:#333;float:right" id="cancel">取消</button>
	</a>
</div>
<script type="text/javascript">
var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
	var flag=true;
	//判断更新时间
	var lastupdate='<?php echo $_REQUEST['last_update']?>';
	var id='<?php echo $_REQUEST['id']?>';
	var fpage='<?php echo $_REQUEST['fpage']?>';
	var type='<?php echo $_REQUEST['type']?>';
	var url='/index.php/purchase/updateDxcg/'+id+'?fpage='+fpage+'&&type='+type;
	$.ajaxSetup({async:false});
	$.get('/index.php/commonForms/lastUpdate/'+id,{
		'time':lastupdate,
	},function(data){
		if(data==='error')
		{
			confirmDialog('获取信息失败，请稍后再试');
		}else 	if(data!=='pass')
		{
			confirmDialog('您看到的信息不是最新的，请刷新后再试');
// 			setTimeout('',2300);
// 			window.location.href=url+'&&last_update='+data;
			flag=false;
			return false;
		}
	});		
	if(!flag)return false;		
		var traver = $("#transfer_number").val();
		if(traver=='')
		{
			confirmDialog('请输入车船号');return false;
		}	
// 		var result = checkTravel(traver);
// 		if(result != 1){confirmDialog(result);return false;}
		var date_reach=$('#date_reach').val();
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}	
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
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
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });

    	$(function(){
    		 if(changeCont())
    		 {
    			    var contact_id='<?php echo $purchase->contact_id;?>';
    			    var mobile='<?php echo $purchase->contact->mobile;?>';
    			    var ship2='<?php echo $purchase->shipment?>';
    			    $('#contact_id').val(contact_id);
    			    $('#phone').val(mobile);
    			    if(parseFloat(ship2)!=0){$('#shipment').val(ship2);}
    		 }
    		 changeCont();
    		 updateTotalAmount();
    		updateTotalWeight();
    		updateTotalMoney();		
        })
	</script>
