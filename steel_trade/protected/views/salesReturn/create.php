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
var array2=<?php echo $coms?$coms:json_encode(array());?>;
var array3=<?php echo $warehouses;?>;
var array4=<?php echo $teams?$teams:json_encode(array());?>;
var array_vendor=<?php echo $vendors?$vendors:json_encode(array())?>;
</script>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l title_div"><span class="bitian">*</span>公司抬头：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" value="" />
			<input type='hidden' id='comboval2' value=""  name="FrmSalesReturn[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="position:relative">
		<div class="shop_more_one_l" ><span class="bitian">*</span>客户：</div>		
		<div id="venselect1" class="fa_droplist">
				<input type="text" id="combo1" value="" />
				<input type='hidden' id='comboval1'  value=""  name="FrmSalesReturn[client_id]"/>
			</div>		
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmSalesReturn[contact_id]" class='form-control chosen-select se_ywz' id="contact_id">
				<option value=""></option>
				<?php //foreach($contacts as $k=>$v){?>
	         <!--    	<option <?php echo $contract->contact_id==$k?'selected="selected"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option> -->
	            <?php //}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="<?php echo $contract->contact->mobile;?>" class="form-control con_tel" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php foreach($users as $k=>$v){?>
	            <option <?php echo $baseform?($baseform->owned_by==$k?'selected="selected"':''):(Yii::app()->user->userid==$k?'selected="selected"':'');?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
	</div>
	<div class="shop_more_one" style="position:relative">
		<div class="shop_more_one_l" ><span class="bitian">*</span>结算单位：</div>		
		<div id="venselect" class="fa_droplist">
				<input type="text" id="combo" value="" />
				<input type='hidden' id='comboval'  value=""  name="FrmSalesReturn[company_id]"/>
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
		<div class="search_date_box" style="margin-top:0px;width:145px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" id="form_time" value="<?php echo date('Y-m-d',time());?>" class="form-control form-date date" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计退货日期：</div> 
		<div class="search_date_box" style="margin-top:0px;width:145px;background-position:155px 8px;">
			<input type="text"  name="FrmSalesReturn[return_date]" id="date_reach" class="form-control  date  "  placeholder="选择日期"  >
 		</div> 
 	</div> 	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>退货仓库：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo3" value="" />
			<input type='hidden' id='comboval3'  value="" class="wareinput" name="FrmSalesReturn[warehouse_id]"/>
		</div>
	</div>	
	<div class="shop_more_one">
		<div class="shop_more_one_l">退货方式：</div>
		<input type="radio"  name="FrmSalesReturn[return_type]" checked  id="return_type_ware" value="warehouse"class=" check_box l return_type" style="margin-top: 8px;"  placeholder=""  ><div class="lab_check_box"  style="margin-top:0px;"><label for="return_type_ware">仓库</label></div>
		<input type="radio" name="FrmSalesReturn[return_type]" value="supply"   id="return_type_supply"  class="check_box l return_type" style="margin-left:10px;margin-top:8px;" /><div class="lab_check_box" style="margin-top:0px;"><label for="return_type_supply">供应商</label></div>
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l">运发责任：</div>
		<input type="radio"  name="FrmSalesReturn[tran_type]" checked value="get" class="check_box l"  style="margin-top: 8px;" id="tran_type_get"  placeholder=""><div class="lab_check_box" style="margin-top:0px;"><label for="tran_type_get" >自提</label></div>
		<input type="radio" name="FrmSalesReturn[tran_type]" value="send"  id="tran_type_send"  class="check_box l" style="margin-left:10px;margin-top:8px;"><div class="lab_check_box" style="margin-top:0px;"><label for="tran_type_send">送货</label></div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>车船号：</div>
		<input type="text"  name="FrmSalesReturn[travel]" class="form-control transfer" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one"> 
		<input class="check_box l" style="margin-left:80px;" type="checkbox"  name="FrmSalesReturn[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		
		<input class="check_box l gaokai" style="margin-left:80px;" type="checkbox"  name="FrmSalesReturn[is_gaokai]" value="1" /><div class="lab_check_box">高开</div>
	</div>

	
		<div class="shop_more_one gaokai_input">
		<div class="shop_more_one_l">高开金额：</div>
		<input type="text"  name="FrmSalesReturn[gaokai_money]" class="form-control " placeholder=""  >
	</div>
	<div class="shop_more_one gaokai_input">
		<div class="shop_more_one_l">采购员：</div>
			<div id="gkselect" class="fa_droplist">
			<input type="text" id="gkcombo" value="" />
			<input type='hidden' name="FrmSalesReturn[gaokai_target]"  id='gkcomboval' value="" name="gk_id" />
		</div>
<!-- 		<input type="text"  name="CommonForms[gaokai_target]" class="form-control " placeholder=""  > -->
	</div>	
	<div class="shop_more_one supply_id" style="position:relative;display:none" >
		<div class="shop_more_one_l" ><span class="bitian">*</span>退货供应商：</div>		
		<div id="vendorselect" class="fa_droplist">
				<input type="text" id="combovendor" value="" />
				<input type='hidden' id='combovalvendor'  value=""  name="FrmSalesReturn[supply_id]"/>
			</div>		
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">退货理由：</div> 
		<div class="search_date_box" style="margin-top:0px;width:145px;background-position:155px 8px;">
			<select name="FrmSalesReturn[back_reason]" class="form-control back_reason" >
			  <?php foreach(FrmSales::$reasons as $k=>$v){?>
	            	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
			</select>
 		</div> 
 	</div> 		
 	<div class="shop_more_one other_reason_div" style="width:450px;display:none"> 
 		<div class="shop_more_one_l" >其他理由：</div> 
 		<input type="text"  style="width:300px;" name="FrmSalesReturn[other_reason]" class="form-control  other_reason" placeholder="请输入其他理由"  >
 	</div>
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:4%;">操作</th>
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
    <?php for($i=1;$i<=5;$i++){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>
    		<td class="">
    			<div id="brandselect<?php echo $i?>" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">
					<input type="text" id="combobrand<?php echo $i?>" style="width:130px;"  value="" />
					<input type='hidden' id='combovalbrand<?php echo $i?>' value=""   name="td_brands[]" class="td_brand"/>
				</div>
			<script type="text/javascript">				
    			$('#combobrand<?php echo $i?>').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect<?php echo $i?>","combovalbrand<?php echo $i?>",false,'brandChange(obj)');
    		</script>	
    		</td>
    		<td class="">
    			<select name='td_products[]' class="form-control chosen-select td_product" onchange="productChange(this)">
    			<option></option>
    			<?php foreach($products as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="td_textures[]" class="form-control chosen-select td_texture" onchange="textureChange(this)">
    			<option></option>
	            <?php foreach($textures as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>   
    		<td class="">
    			<select name="td_ranks[]" class="form-control chosen-select td_rank" onchange="rankChange(this)">
    			<option></option>
	            <?php foreach($ranks as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>    		 		
    		<td class=""><input type="text"  name="td_length[]" style="" onchange="lengthChange(this)" class="form-control td_length" placeholder=""  ></td>
    		<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name=""   style="" class="form-control  td_weight" placeholder=""  >
    			<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>
    		<td class=""><input type="text" readonly  name="td_totalMoney[]"  style="" class="form-control td_money" placeholder=""  ></td>
    	</tr>
    	<?php }?>
    </tbody>
  </table>
</div>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal"  id="submit_btn1">保存提交</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('SalesReturn/index',array("page"=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
	$(document).on("blur","input",function(){
		$(this).removeClass("red-border");
	});
	$(document).on("blur","select",function(){
		$(this).removeClass("red-border");
	});
	$('.gaokai').click(function(){
// 		alert($(this).attr('checked'))
		if($(this).attr('checked')=='checked')
		{
			$('.gaokai_input').show();
		}else{
			$('.gaokai_input').hide();
		}
		
	});
    var date_now='<?php echo date('Y-m-d',time());?>';
	var array_brand=<?php echo $brands;?>;
	var num=1;
	$("#add_list").click(function(){
		var count=parseInt($('#forinsert').children('tr').length)+1;
		var newRow = '';
		var yu = count % 2;
		if(yu == 0)
		{
			newRow = '<tr class="selected">';
		}else{
			newRow = '<tr class="">';
		}
		newRow +='<td class="text-center list_num">'+count+'</td>'+
		'<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>'+
		'<td class="">'+
			'<div id="bbrandselect'+num+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
				'<input type="text" id="bcombobrand'+num+'" style="width:130px;"  value="" />'+
				'<input type="hidden" id="bcombovalbrand'+num+'" value=""   name="td_brands[]" class="td_brand"/>'+
			'</div>'+
		'</td>'+
		'<td class="">'+
			'<select name="td_products[]" class="form-control chosen-select td_product" onchange="productChange(this)">'+
			'<option></option>'+
			<?php foreach($products as $k=>$v){?>
            	'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
            <?php }?>
    		'</select>'+
		'</td>'+
		'<td class="">'+
		'<select name="td_textures[]" class="form-control chosen-select td_texture" onchange="textureChange(this)">'+
		'<option></option>'+
		<?php foreach($textures as $k=>$v){?>
    		'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
    	<?php }?>
		'</select>'+
		'</td>'+		
		'<td class="">'+
			'<select name="td_ranks[]" class="form-control chosen-select td_rank" onchange="rankChange(this)">'+
			'<option></option>'+
			<?php foreach($ranks as $k=>$v){?>
        	'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
        	<?php }?>
    		'</select>'+
		'</td>'+		
		'<td class=""><input type="text" name="td_length[]" style="" onchange="lengthChange(this)" class="form-control td_length" placeholder=""  ></td>'+
// 		'<td class=""><input type="text"  name="td_card[]" style="" class="form-control td_card" placeholder=""  ></td>'+
		'<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>'+
		'<td class="">'+
			'<input type="text"  name=""  style="" class="form-control  td_weight" placeholder=""  >'+
			'<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >'+
		'</td>'+
		'<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>'+
		'<td class=""><input type="text" readonly name="td_totalMoney[]"  style="" class="form-control td_money" placeholder=""  ></td>'+
	'</tr>';

		//获取最后一条的值
		var nextBrand= $("#cght_tb tr:last").find('.td_brand').val();
		var nextProduct= $("#cght_tb tr:last").find('.td_product').val();
		var nextTexture= $("#cght_tb tr:last").find('.td_texture').val();
		var nextRank= $("#cght_tb tr:last").find('.td_rank').val();
		var nextLength= $("#cght_tb tr:last").find('.td_length').val();
		var nextUnitWeight = $("#cght_tb tr:last").find('.td_unit_weight').val();
		var nextNum= $("#cght_tb tr:last").find('.td_num').val();
		var nextWeight= $("#cght_tb tr:last").find('.td_weight').val();
		var nextTotalWeight= $("#cght_tb tr:last").find('.td_total_weight').val();
		var nextPrice= $("#cght_tb tr:last").find('.td_price').val();
		var nextMoney= $("#cght_tb tr:last").find('.td_money').val();	
	
		$("#cght_tb tbody ").append(newRow);
		$('#bcombobrand'+num).combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"bbrandselect"+num,"bcombovalbrand"+num,false);
		$("#tr_num").val(count);
		num++;

		$("#cght_tb tr:last").find('.td_brand').val(nextBrand);
		$("#cght_tb tr:last").find('.td_product').val(nextProduct);
		$("#cght_tb tr:last").find('.td_texture').val(nextTexture);
		$("#cght_tb tr:last").find('.td_rank').val(nextRank);
		$("#cght_tb tr:last").find('.td_length').val(nextLength);
		$("#cght_tb tr:last").find('.td_unit_weight').val(nextUnitWeight);
		$("#cght_tb tr:last").find('.td_num').val(nextNum);
		$("#cght_tb tr:last").find('.td_weight').val(nextWeight);
		$("#cght_tb tr:last").find('.td_total_weight').val(nextTotalWeight);
		$("#cght_tb tr:last").find('.td_price').val(nextPrice);
		$("#cght_tb tr:last").find('.td_money').val(nextMoney);

		//初始化下拉框
		$('#forinsert tr:last').each(function(){
			var obj=$(this).find('.td_brand');
			var brand=$(obj).val();
			var product=$(obj).parent().parent().parent().find('.td_product').val();
			var texture=$(obj).parent().parent().parent().find('.td_texture').val();
			var rank=$(obj).parent().parent().parent().find('.td_rank').val();		
			$.ajaxSetup({async:false});	
			$.post('/index.php/dictGoodsProperty/propertySelect',{
				'type':'brand',
				'id':brand,
				'product':product,
				'texture':texture,
				'rank':rank,
				},function(data){
					var data1=data.substring(0,data.indexOf('o1@o'));
					var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
					var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
					var data4=data.substring(data.indexOf('o3@o')+4);
					$(obj).parent().parent().parent().find('.td_product').html(data1);
					$(obj).parent().parent().parent().find('.td_texture').html(data2);
					$(obj).parent().parent().parent().find('.td_rank').html(data3);
					$(obj).parent().parent().parent().find('.td_length').val(data4);
					if(product!='')$(obj).parent().parent().parent().find('.td_product').val(product);
					if(texture!='')$(obj).parent().parent().parent().find('.td_texture').val(texture);
					if(rank!='')$(obj).parent().parent().parent().find('.td_rank').val(rank);
			});
			productChange($(obj).parent().parent().parent().find('.td_product'));			
		});
		$("#cght_tb tr:last").find('.td_length').val(nextLength);
	});
</script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/changeFunction.js"></script>
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
$('.back_reason').change(function(){
	if($(this).val()=='-1')
	{
		$('.other_reason_div').show();
	}else{
		$('.other_reason_div').hide();
	}
})
function changeCont(){
 	var vendor_id=$('#comboval1').val();
 	var vendor_name=$('#combo1').val();
 	if(Number($('#comboval').val()) == 0){
	 	$('#comboval').val(vendor_id);
	 	$('#combo').val(vendor_name);
 	}
 	$.get('/index.php/contract/getVendorCont',{
 		'vendor_id':vendor_id,
 	},function(data){
 		var data1=data.substring(0,data.indexOf('o1o'));
 		var data2=data.substring(data.indexOf('o1o')+3);
 		$('#contact_id').html(data1);
 		$('#phone').val(data2);
 	});			
 	
 	//填充运费
 	if(vendor_id)
 	{
 		$.get('/index.php/purchase/getShipment/'+vendor_id,{},function(data){
 			if(data){$('#shipment').val(data);}
 		})
 	}	
}	
		 
	$(function(){
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false);
		$('#combo1').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect1","comboval1",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
		$('#combovendor').combobox(array_vendor, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"vendorselect","combovalvendor");
	})
	changeOwnerT();
	var goods_arr=new Array();
	$('#submit_btn').unbind();
	$('#submit_btn1').unbind();
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
		if(cggs==''){confirmDialog("请选择输入公司抬头！");return false;}
		if(client==''){confirmDialog("请选择输入客户");return false;}
		if(customer==''){confirmDialog("请选择输入结算单位");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		if(form_time==''){confirmDialog("请选择输入开单日期！");return false;}
		if(transfer==''){confirmDialog('请输入车船号');return false;}
		if(ware==''){confirmDialog('请选择输入退货仓库');return false;}
		if(!contact){confirmDialog("请选择输入联系人！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
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
		var flag=true;
		var havedetail=false;
		goods_arr=new Array();
		$("#cght_tb tbody tr").each(function(){
			var brand=$(this).find(".td_brand").val();
			var product=$(this).find(".td_product").val();
			var texture=$(this).find(".td_texture").val();
			var rank=$(this).find(".td_rank").val();
			var length=$(this).find(".td_length").val();
// 			var td_card=$(this).find(".td_card").val();
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(brand==''&&product==''&&texture==''&&rank==''&&length==''&&td_amount==''&&td_weight==''&&td_price=='')
			{					
			}else{
				havedetail=true;
				if(brand==''){confirmDialog("请选择产地");flag=false;return false;}
				if(product==''){confirmDialog("请选择品名");$(this).find('.td_product').addClass('red-border');flag=false;return false;}
				if(texture==''){confirmDialog("请选择材质");$(this).find('.td_texture').addClass('red-border');flag=false;return false;}
				if(rank==''){confirmDialog("请选择规格");$(this).find('.td_rank').addClass('red-border');flag=false;return false;}				
				if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
				if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
				var result=checkRepeat(brand,product,texture,rank,$.trim(length));
				if(!result){confirmDialog("选择的产地,品名,材质,规格,长度不能重复");flag= false;return false;}
				var rr=checkGood(brand,product,texture,rank,length);
				if(!rr){confirmDialog('第'+list_num+'条数据长度有误，请重新输入');$(this).find('.td_length').addClass('red-border');flag=false;return false;}
			}
		})
		if(!flag){return false;}
		if(!havedetail){confirmDialog('请选择输入明细信息');return false;}
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
	function checkGood(brand,product,texture,rank,length)
	{
		var res=true;
		$.ajaxSetup({async:false});
		$.get('/index.php/dictGoods/getGoodId',{
			'product_id':product,
			'rank_id':rank,
			'texture_id':texture,
			'brand_id':brand,
			'length':length,
    		},function(data){
    			if(!data)
    			{
	    			res=false;
    			}
    	});			
    	return res;
	}


	$('#submit_btn1').click(function(){
		if(!can_submit){return false;}
		var str='';
		var contact=$('#contact_id').val();
		var customer=$('#comboval').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		var ware=$('.wareinput').val();
		var transfer=$('.transfer').val();
		var date_reach=$('#date_reach').val();
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入公司抬头！");return false;}		
		if(customer==''){confirmDialog("请选择输入客户");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		if(form_time==''){confirmDialog("请选择输入开单日期！");return false;}
		if(transfer==''){confirmDialog('请输入车船号');return false;}
		if(ware==''){confirmDialog('请选择输入仓库');return false;}
		if(!contact){confirmDialog("请选择输入联系人！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
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
		var flag=true;
		var havedetail=false;
		goods_arr=new Array();
		$("#cght_tb tbody tr").each(function(){
			var brand=$(this).find(".td_brand").val();
			var product=$(this).find(".td_product").val();
			var texture=$(this).find(".td_texture").val();
			var rank=$(this).find(".td_rank").val();
			var length=$(this).find(".td_length").val();
// 			var td_card=$(this).find(".td_card").val();
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(brand==''&&product==''&&texture==''&&rank==''&&length==''&&td_amount==''&&td_weight==''&&td_price=='')
			{
			}else{
				havedetail=true;
				if(brand==''){confirmDialog("请选择产地");flag=false;return false;}
				if(product==''){confirmDialog("请选择品名");$(this).find('.td_product').addClass('red-border');flag=false;return false;}
				if(texture==''){confirmDialog("请选择材质");$(this).find('.td_texture').addClass('red-border');flag=false;return false;}
				if(rank==''){confirmDialog("请选择规格");$(this).find('.td_rank').addClass('red-border');flag=false;return false;}				
				if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
				if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
				var result=checkRepeat(brand,product,texture,rank,$.trim(length));
				if(!result){confirmDialog("选择的产地,品名,材质,规格,长度不能重复");flag= false;return false;}
				var rr=checkGood(brand,product,texture,rank,length);
				if(!rr){confirmDialog('第'+list_num+'条数据长度有误，请重新输入');$(this).find('.td_length').addClass('red-border');flag=false;return false;}
			}
		})
		if(!flag){return false;}
		if(!havedetail){confirmDialog('请选择输入明细信息');return false;}
		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
		$(this).parent().append(str);
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn1');
	        $("#form_data").submit();
	    }
	})		
		var gkvendor=<?php echo $gkvendor?$gkvendor:'[]';?>;
		$('#gkcombo').combobox(gkvendor, {},"gkselect","gkcomboval",false);
	</script>