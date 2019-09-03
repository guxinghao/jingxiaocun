<?php
// var_dump($details_array);
// die;
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
			<input type="hidden" name="SalesDetail" value=""  class="frmsales_id">
			<input type="hidden" name="FrmPurchase[purchase_type]" value="dxcg"/>
			<input type="hidden" name="FrmPurchase[sales_details_array]" value='<?php echo $details_array;?>'/>
			<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
			<div id="venselect" class="fa_droplist">
				<input type="text" id="combo" value="<?php echo $purchase->supply->short_name?>" />
				<input type='hidden' id='comboval'  value="<?php echo $purchase->supply_id?>"  name="FrmPurchase[supply_id]"/>
			</div>
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" value="<?php echo $purchase->title->short_name;?>" />
			<input type='hidden' id='comboval2' value="<?php echo $purchase->title_id?>"  name="FrmPurchase[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购日期：</div>
			<input type="text"  name="CommonForms[form_time]"  value="<?php echo $baseform->form_time;?>" class="form-control date input_backimg" placeholder="选择日期"  >
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" value="<?php //echo $purchase->invoice_cost;?>" class="form-control invoice_cost" placeholder=""  ><span class="danwei">元/吨</span>
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]"  class='form-control chosen-select se_ywz' id="contact_id">
				<?php //foreach($contacts as $k=>$v){?>
	            	<!--  -><option <?php echo $purchase->contact_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>-->
	            <?php //}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php foreach($users as $k=>$v){?>
	            	<option <?php echo $baseform->owned_by==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		<select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php foreach($teams as $k=>$v){?>
	            	<option <?php echo $purchase->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	      </select>		
	      <input type="hidden" name="FrmPurchase[team_id]"   value="<?php echo $purchase->team_id?>">		
<!-- 		<div id="teamselect" class="fa_droplist"> --
			<input type="text" id="combo4" value="<?php  //echo  $purchase->team->name?>" />
			<input type='hidden' id='comboval4'  value="<?php  //echo $purchase->team_id?>"  name="FrmPurchase[team_id]"/>
<!-- 		</div> -->
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
		<input type="text"  name="FrmPurchase[transfer_number]" value="<?php echo $purchase->transfer_number?>" id="transfer_number" class="form-control tit_remark" placeholder=""  >
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>入库仓库：</div> -->
<!-- 		<div id="wareselect" class="fa_droplist"> --
			<input type="text" id="combo3" value="<?php echo $purchase->warehouse->name;?>" />
			<input type='hidden' id='comboval3'  value="<?php echo $purchase->warehouse_id?>" class="wareinput" name="FrmPurchase[warehouse_id]"/>
<!-- 		</div> -->
<!-- 	</div> -->
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
		<input type="text"  name="FrmPurchase[shipment]" value="<?php echo $purchase->shipment?>" id="shipment" class="form-control " placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one" > 
		<input class="check_box l" style="margin-left:90px;" type="checkbox"  <?php echo $purchase->is_yidan==1?'checked="checked"':''?>  name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<input class="" type="hidden" name="FrmPurchase[contain_cash]" value="1" />
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
    		<td ><input type="text" readonly name="td_price[]" style="" value="<?php echo round($each['price'],2);?>" class="form-control td_price" ></td>
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
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#1c9fe1;float:right;" id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('purchase/updateDxcg',array('id'=>$baseform->id,'details_array'=>$details_array,'detail_ids'=>$detail_ids,'last_update'=>$_REQUEST['last_update']))?>">
	<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal" style="background:#1c9fe1;float:right;" id="submit_btn1">上一步</button>
	</a> 
	<a href="<?php echo Yii::app()->createUrl('purchase/index')?>">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#d5d5d5;color:#333;float:right" id="cancel">取消</button>
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
			var td_price = numChange($(this).find(".td_price").val());
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
	$("#submit_btn1").click(function(){
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
			var td_price = numChange($(this).find(".td_price").val());
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
		})
		if(!flag){return false;}
		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
		$(this).parent().append(str);
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
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
	
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
// 			confirmDialog(contact_id);
			$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script>
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
	$(function(){
		var array=<?php echo $vendors;?>;
		var array2=<?php echo $coms;?>;
		var array3=<?php echo $warehouses?$warehouses:json_encode(array());?>;
		var array4=<?php echo $teams?$teams:json_encode(array());?>;
		var array5=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array6=<?php echo $vens?$vens:json_encode(array());?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
		$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"teamselect","comboval4",false,'changeTeamU()');
		$('#combo5').combobox(array5, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"saleselect","comboval5");
		$('#combo6').combobox(array6, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","comboval6");
		$('#combo7').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"team2select","comboval7",false);

		 if(changeCont())
		 {
			    var contact_id='<?php echo $purchase->contact_id;?>';
			    var mobile='<?php echo $purchase->contact->mobile;?>';
			    var ship2='<?php echo $purchase->shipment?>';
			    $('#contact_id').val(contact_id);
			    $('#phone').val(mobile);
			    if(parseFloat(ship2)!=0){$('#shipment').val(ship2);}
		 }
		 updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();		
	})
	</script>