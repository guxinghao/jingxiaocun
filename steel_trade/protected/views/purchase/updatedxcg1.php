<?php
$type= $_GET['type'];
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',)
) );
?>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:2%;"></th>
         		<th class="text-center" style="width:4%;">操作</th>
         		<th class="text-center" style="width:5%;">销售单号</th>
         		<th class="text-center" style="width:8%;">销售公司</th>
         		<th class="text-center" style="width:8%;">货主公司</th>
         		<th class="text-center" style="width:8%;">产地</th>
         		<th class="text-center" style="width:5%;">品名</th>
         		<th class="text-center" style="width:6%;">材质</th>
         		<th class="text-center" style="width:4%;">规格</th>         		
         		
         		<th class="text-center" style="width:4%;">长度</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>单价</th>
         		<th class="text-center" style="width:7%;">金额</th>
         		<th class="text-center" style="width:7%;">开票成本</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	<?php 
    		$ii=1;
    		if(isset($details)&&!isset($_REQUEST['details_array']))
    		{
    			foreach ($details as $eachPurD)
    			{
    				$sales=$eachPurD->salesDetail;
    				foreach ($sales as $eachSales)
    				{
    	?>
    	<tr class="">
    		<td class="text-center list_num"><?php echo $ii;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td><?php echo $eachSales->form_sn;?><input type="hidden" name="td_form_sn[]" value="<?php echo $eachSales->form_sn;?>"/></td>
    		<td >
    				<input type="text"  readonly style="" name="td_sell_name[]" value="<?php echo $purchase->title->short_name;?>" class="form-control "  >
    				<input type="hidden"  readonly style="" name="td_sell[]" value="<?php echo $purchase->title_id;?>" class="form-control td_sell"  >
    		</td>
    		<td >
    				<input type="text"  readonly style="" name="td_owner_name[]" value="<?php echo $purchase->supply->short_name;?>" class="form-control td_sell"  >
    				<input type="hidden"  readonly style="" name="td_owner[]" value="<?php echo $purchase->supply_id;?>" class="form-control td_owner"  >
    		</td>    		
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $eachPurD->brand_id?>" class="form-control td_brand yes">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo DictGoodsProperty::getProName($eachPurD->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $eachSales->sales_detail_id;?>"  class="detail_id"/>
    			<input type="hidden" name="td_purchaseDetail_id[]" value="<?php echo $eachSales->sales_detail_id;?>"  class="detail_id"/>
    			<input type="hidden"  name="td_products[]" value="<?php echo $eachPurD->product_id?>" class="form-control td_product yes">
	    		<input type="text" readonly name="td_products_name[]" value="<?php echo DictGoodsProperty::getProName($eachPurD->product_id);?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $eachPurD->texture_id?>" class="form-control td_texture yes">
	    		<input type="text" readonly name="td_textures_name[]" value="<?php echo DictGoodsProperty::getProName($eachPurD->texture_id)?>" class="form-control">
    		</td> 
    		<td class="">
    			<input type="hidden"  name="td_ranks[]" value="<?php echo $eachPurD->rank_id?>" class="form-control td_rank yes">
	    		<input type="text" readonly name="td_ranks_name[]" value="<?php echo DictGoodsProperty::getProName($eachPurD->rank_id);?>" class="form-control">
    		</td>    		   		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $eachPurD->length;?>" class="form-control td_length"  ></td>
    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $eachSales->amount;?>" class="form-control td_amount td_num"  >
    			<input type="hidden" name="td_max_amount[]" style="" value="<?php echo $eachSales->saledetailCont->need_purchase_amount;?>" class="form-control  td_max_num"  >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($eachSales->weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $eachSales->weight;?>" style="" class="form-control td_total_weight " >
    		</td>
    		<td ><input type="text"  name="td_price[]" style="" value="<?php echo round($eachPurD->price,2);?>" class="form-control td_price" ></td>
    		<td >
    		<input type="text"  name="td_totalMoney[]" readonly  value="<?php echo number_format($eachSales->weight*$eachPurD->price,2)?>"  class="form-control td_money" >
    		<input type="hidden" name="good_id[]"  class="good_id" value="<?php echo $eachSales->good_id;?>">    			
    		</td>
    		<td><input type="text" name="td_invoice[]" class="form-control td_invoice" value="<?php echo $eachPurD->invoice_price;?>"></td>
    	</tr>
    	<?php $ii++;	}}}?>
    	<?php 
    		if(isset($_REQUEST['details_array'])){
    			$i=1;
    			$details_array=json_decode($_REQUEST['details_array']);
    			foreach ($details_array as $each){
    	?>
    	<tr class="">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td><?php echo $each->form_sn;?><input type="hidden" name="td_form_sn[]" value="<?php echo $each->form_sn;?>"/></td>
    		<td >
    				<input type="text"  readonly style="" name="td_sell_name[]" value="<?php echo $each->title_name;?>" class="form-control "  >
    				<input type="hidden"  readonly style="" name="td_sell[]" value="<?php echo $each->title_id;?>" class="form-control td_sell"  >
    		</td>
    		<td >
    				<input type="text"  readonly style="" name="td_owner_name[]" value="<?php echo $each->owner_name;?>" class="form-control td_sell"  >
    				<input type="hidden"  readonly style="" name="td_owner[]" value="<?php echo $each->owner_company_id;?>" class="form-control td_owner"  >
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $each->detail_id;?>"  class="detail_id"/>
    			<input type="hidden"  name="td_products[]" value="<?php echo $each->product_id?>" class="form-control td_product yes">
	    		<input type="text" readonly name="td_products_name[]" value="<?php echo $each->product_name;?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $each->texture_id?>" class="form-control td_texture yes">
	    		<input type="text" readonly name="td_textures_name[]" value="<?php echo $each->texture_name?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_ranks[]" value="<?php echo $each->rank_id?>" class="form-control td_rank yes">
	    		<input type="text" readonly name="td_ranks_name[]" value="<?php echo $each->rand_name?>" class="form-control">
    		</td>    		
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $each->brand_id?>" class="form-control td_brand yes">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo $each->brand_name?>" class="form-control">
    		</td>
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $each->length;?>" class="form-control td_length"  ></td>
    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $each->amount;?>" class="form-control td_amount td_num"  >
    			<input type="hidden" name="td_max_amount[]" style="" value="<?php echo $each->max_amount;?>" class="form-control  td_max_num"  >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($each->weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $each->weight;?>" style="" class="form-control  td_total_weight" >
    		</td>
    		<td ><input type="text"  name="td_price[]" style="" value="<?php echo round($each->price,2);?>" class="form-control td_price" ></td>
    		<td >
    		<input type="text"  name="td_totalMoney[]" readonly  value="<?php echo number_format($each->totalMoney,2);?>"  class="form-control td_money" >
    		<input type="hidden" name="good_id[]"  class="good_id" value="<?php echo $each->good_id;?>">    			
    		</td>
    		<td><input type="text" name="td_invoice[]" class="form-control td_invoice" value="<?php echo $each->invoice_price;?>"></td>
    	</tr>
    	<?php }}?>
    </tbody>
     <tfoot>
   		<tr class="tablefoot">
			<td class="text-center"  colspan=2>合计：</td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
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
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#1c9fe1;float:right;margin-right:20px" id="submit_btn">下一步</button>
	<a href="<?php echo Yii::app()->createUrl('purchase/index')?>">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" style="background:#d5d5d5;color:#333;float:right" id="cancel">取消</button>
	</a>
</div>
<div style="clear: both;">
 <div class="search_line"></div>
 <div class="search_title">选择代销销售单</div>
<form method="post" action="">
<div class="search_body search_background">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入销售单号" id="search_keywords" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" id="search_begin" placeholder="开始日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" id="search_end" placeholder="结束日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="shop_more_one1">
		<div style="float:left;">销售公司：</div>
		<div id="saleselect" class="fa_droplist">
			<input type="text" id="combo5" class="forreset" value="<?php echo DictCompany::getName($search['title_id'])?>" />
			<input type='hidden' id="comboval5" class="forreset" value="<?php echo $search['title_id']?>" name="search[title_id]" />
		</div>
	</div>
	<div class="select_body" style="position:relative">
	<div class="more_select_box" style="top:40px;left:-500px;">
		<div class="more_one">
		<div class="shop_more_one">
			<div class="more_one_l" >采购公司：</div>
			<div id="cusselect" class="fa_droplist">
				<input type="text" id="combo6" class="forreset" value="<?php echo DictCompany::getName($search['customer_id']);?>" style="width:145px;"/>
				<input type='hidden' id='comboval6' class="forreset" value="<?php echo $search['customer_id']?>" name="search[customer_id]" />
			</div>
		</div>
		</div>
		<div class="more_one">
		<div class="more_one_l">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand"  class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
			<input type='hidden' id='combovalbrand' value="<?php echo $search['brand'];?>"  class="forreset" name="search[brand]"/>
		</div>
	</div>	
	<div class="shop_more_one" style="width: 240px;">
		<div class="shop_more_one_l" style="width:90px;line-height:45px;">业务组：</div>		
		<div id="team2select" class="fa_droplist" style="margin-top:5px;">
			<input type="text" id="combo7"  class="forreset"  value="<?php echo Team::getName($search['team']);?>" />
			<input type='hidden' id='comboval7'   class="forreset" value="<?php echo $search['team'];?>"  name="search[team]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset' id="owned_by">
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset' id="product">
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rand]" class='form-control chosen-select forreset' id="rand">
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($ranks as $k=>$v){?>
            	 <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset' id="texture">
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
		<div class="more_one">
		<div class="more_one_l">仓库：</div>
		 <select name="search[warehouse]" class='form-control chosen-select forreset' id="warehouse">
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($warehouses1 as $k=>$v){?>
            	 <option <?php echo $k==$search['warehouse']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
			<div class="more_one">
		<div class="more_one_l">审单状态：</div>
		 <select name="search[confirm_status]" class='form-control chosen-select forreset' id="confirm_status">
	            <option value='0' selected='selected'>-全部-</option>
	             <?php $_status=array(0=>'未审单',1=>'已审单'); foreach ($_status as $k=>$v){?>
            	 <option <?php echo $k==$search['confirm_status']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	</div>
	</div>
	<input type="button" class="btn btn-primary btn-sm btn_sub search_btn" data-dismiss="modal" value="查询">
	<div class="more_toggle"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset">
</div>
</form>
</div>

<div style="clear: both;" id="sales_list">

</div>
<script type="text/javascript">
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
	var detail_id_array=new Array();
	<?php if(isset($_REQUEST['detail_ids'])&&$_REQUEST['detail_ids']!=''){?>
	var temp_arr="<?php echo $_REQUEST['detail_ids'];?>";
	 detail_id_array=temp_arr.split(',');
	 detail_id_array.splice(0,1);
	<?php }elseif($detail_ids){?>
	var temp_arr="<?php echo$detail_ids;?>";
	 detail_id_array=temp_arr.split(',');
	 detail_id_array.splice(0,1);
	<?php }?>
	$(".deleted_tr").live("click",function(){
		//现有的改变勾选状态
		var de_id=$(this).parent().parent().find('.detail_id').val();
		detail_id_array.splice(detail_id_array.indexOf(de_id),1);
		$('#datatable-datatable1 .datatable-rows:first .datatable-wrapper table tr').each(function(){
			var detail_id=$(this).children().find('input[type=checkbox]').val();
			if(de_id==detail_id)
			{
				$(this).children().find('input[type=checkbox]').removeAttr('checked');
				return;
			}
		})
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
		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();
	})
	var  can_submit = true;
$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var flag=true;
		var owner='';
		var title_id='';
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			var td_owner=$(this).find('.td_owner').val();
			var td_title=$(this).find('.td_sell').val();			
			if(owner=='')
			{
				owner=td_owner;
				title_id=td_title;
			}else{
				if(td_title!=title_id)
				{
					confirmDialog('请选择同一个销售公司的销售单');
					flag=false;
					return;
				}
				if(td_owner!=owner)
				{
					confirmDialog('请选择同一个货主公司的销售单');
					flag=false;
					return;
				}
			}
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
</script>
<script>
	    function changeCont()
	    {
			var vendor_id=$('#comboval').val();
			$.get('/index.php/contract/getVendorCont',{
				'vendor_id':vendor_id,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1o'));
				var data2=data.substring(data.indexOf('o1o')+3);
				$('#contact_id').html(data1);
				$('#phone').val(data2);
			});
		}	   
		function changeTeamU()
		{
			var team_id= $('#comboval4').val();
// 	     	confirmDialog(team_id);	
	    	$.get('/index.php/contract/getTeamUser',{
	    		'team_id':team_id,
	    		},function(data){
	    			$('#CommonForms_owned_by').html(data);
	    		});	
		}
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
// 			confirmDialog(contact_id);
			$.get('getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });

  	  //件数改变
	    $.ajaxSetup({ async: false });
	    var unit_weight=0;
		$(document).on('change','.td_num',function(){
			//)$('.td_num').change(function(){			
			var that=$(this);
			var td_num=$(this).val();
			var td_max_num=$(this).next().val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			if(parseInt(td_num)>parseInt(td_max_num))
			{
				confirmDialog('购买的件数必须小于最大可购买件数'+td_max_num);
				$(this).val('');
				$(this).focus();
				return false;
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
			updateTotalAmount();
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();		
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
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;								
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();		
		});
		
	    //单价改变
		$(document).on('change','.td_price',function(){
			var td_price=numChange($(this).val());
			var td_num=$(this).parent().parent().find('.td_num').val();		
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();		
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price))
			{						
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				$(this).parent().parent().find('.td_money').val('');
				updateTotalMoney();
				return;
			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;		
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();		
		});	    

/****------------------------------销售列表有关》》---------------*****/
	$('.reset').click(function(){
	    $('.forreset').val('');		    
	});
	$(function(){
		$.get('/index.php/frmSales/getSimpleList',{
			'type':'dxcg',
			},function(data){
				$('#sales_list').html(data);
				$('input[type=checkbox]').each(function(){
					var id = $(this).val();
					if(detail_id_array.indexOf(id)>-1)
					{
						$(this).attr('checked','checked');					
					}
				});
			});
		
	});	
	$('.search_btn').click(function(){
		var keywords=$('#search_keywords').val();
		var time_L=$('#search_begin').val();
		var time_H=$('#search_end').val();
		var title_id=$('#comboval5').val();
		var customer_id=$('#comboval6').val();
		var owned_by=$('#owned_by').val();
		var product=$('#product').val();
		var rand=$('#rand').val();
		var team=$('#comboval7').val();
		var brand=$('#brand').val();
		var texture=$('#texture').val();
		var warehouse=$('#warehouse').val();
		var confirm_status=$('#confirm_status').val();
		var url="/index.php/frmSales/getSimpleList?&page=1";
		$.get(url,{
			'type':'dxcg',
			'keywords':keywords ,
			'time_L':time_L ,
			'time_H':time_H ,
			'title_id':title_id , //销售公司
			'customer_id':customer_id ,//采购公司
			'owned_by':owned_by ,
			'product':product ,
			'rand':rand ,
			'team': team,
			'brand': brand,
			'texture':texture ,
			'warehouse': warehouse,
			'confirm_status':confirm_status ,
		},function(data){
			$('#sales_list').html(data);
			//查看是否存在以选中
			$('#datatable-datatable1 .datatable-rows:first .datatable-wrapper table tr').each(function(){
				var detail_id=$(this).children().find('input[type=checkbox]').val();
				if(detail_id_array.indexOf(detail_id)>-1)
				{
					$(this).children().find('input[type=checkbox]').attr('checked','checked');					
				}
			})
		});
	});
	
	//换页获取数据
	$(document).on('click','.sauger_page_a',function(e){
		e.preventDefault();
		var keywords=$('#search_keywords').val();
		var time_L=$('#search_begin').val();
		var time_H=$('#search_end').val();
		var title_id=$('#comboval5').val();
		var customer_id=$('#comboval6').val();
		var owned_by=$('#owned_by').val();
		var product=$('#product').val();
		var rand=$('#rand').val();
		var team=$('#comboval7').val();
		var brand=$('#brand').val();
		var texture=$('#texture').val();
		var warehouse=$('#warehouse').val();
		var confirm_status=$('#confirm_status').val();
		var url=$(this).attr('href');
		$.get(url,{
			'type':'dxcg',
			'keywords':keywords ,
			'time_L':time_L ,
			'time_H':time_H ,
			'title_id':title_id , //销售公司
			'customer_id':customer_id ,//采购公司
			'owned_by':owned_by ,
			'product':product ,
			'rand':rand ,
			'team': team,
			'brand': brand,
			'texture':texture ,
			'warehouse': warehouse,
			'confirm_status':confirm_status ,
		},function(data){
			$('#sales_list').html(data);
			$('#datatable-datatable1 .datatable-rows:first .datatable-wrapper table tr').each(function(){
				var detail_id=$(this).children().find('input[type=checkbox]').val();
				if(detail_id_array.indexOf(detail_id)>-1)
				{
					$(this).children().find('input[type=checkbox]').attr('checked','checked');					
				}
			})
		});
	});
	  $(document).on('change','#each_page',function(){
		  	limit=$(this).val();
		  	$.post("/index.php/site/writeCookie", {
		  		'name' : "sales_list",
		  		'limit':limit
		  	}, function(data) {
		  		if(data){
		  			var keywords=$('#search_keywords').val();
		  			var time_L=$('#search_begin').val();
		  			var time_H=$('#search_end').val();
		  			var title_id=$('#comboval5').val();
		  			var customer_id=$('#comboval6').val();
		  			var owned_by=$('#owned_by').val();
		  			var product=$('#product').val();
		  			var rand=$('#rand').val();
		  			var team=$('#comboval7').val();
		  			var brand=$('#brand').val();
		  			var texture=$('#texture').val();
		  			var warehouse=$('#warehouse').val();
		  			var confirm_status=$('#confirm_status').val();
		  			var url=$('.firstpage').attr('href');
		  			$.get(url,{
		  				'type':'dxcg',
		  				'keywords':keywords ,
		  				'time_L':time_L ,
		  				'time_H':time_H ,
		  				'title_id':title_id , //销售公司
		  				'customer_id':customer_id ,//采购公司
		  				'owned_by':owned_by ,
		  				'product':product ,
		  				'rand':rand ,
		  				'team': team,
		  				'brand': brand,
		  				'texture':texture ,
		  				'warehouse': warehouse,
		  				'confirm_status':confirm_status ,
		  			},function(data){
		  				$('#sales_list').html(data);
		  				$('#datatable-datatable1 .datatable-rows:first .datatable-wrapper table tr').each(function(){
		  					var detail_id=$(this).children().find('input[type=checkbox]').val();
		  					if(detail_id_array.indexOf(detail_id)>-1)
		  					{
		  						$(this).children().find('input[type=checkbox]').attr('checked','checked');					
		  					}
		  				})
		  			});
		  		}
		  	});			
		  });
	  $(document).on('change','.paginate_sel',function(){
		    var url = $(this).val();
		    var keywords=$('#search_keywords').val();
			var time_L=$('#search_begin').val();
			var time_H=$('#search_end').val();
			var title_id=$('#comboval5').val();
			var customer_id=$('#comboval6').val();
			var owned_by=$('#owned_by').val();
			var product=$('#product').val();
			var rand=$('#rand').val();
			var team=$('#comboval7').val();
			var brand=$('#brand').val();
			var texture=$('#texture').val();
			var warehouse=$('#warehouse').val();
			var confirm_status=$('#confirm_status').val();
			$.get(url,{
				'type':'dxcg',
				'keywords':keywords ,
				'time_L':time_L ,
				'time_H':time_H ,
				'title_id':title_id , //销售公司
				'customer_id':customer_id ,//采购公司
				'owned_by':owned_by ,
				'product':product ,
				'rand':rand ,
				'team': team,
				'brand': brand,
				'texture':texture ,
				'warehouse': warehouse,
				'confirm_status':confirm_status ,
			},function(data){
				$('#sales_list').html(data);
				$('#datatable-datatable1 .datatable-rows:first .datatable-wrapper table tr').each(function(){
					var detail_id=$(this).children().find('input[type=checkbox]').val();
					if(detail_id_array.indexOf(detail_id)>-1)
					{
						$(this).children().find('input[type=checkbox]').attr('checked','checked');					
					}
				})
			});
		});	
	//选择，填充数据，撤销，恢复数据
	$(document).on('click','.selected_sales',function(){
			var that=$(this);
			var check=$(this).attr('checked');
			var selected_sales= $(this).val();
			if(check)
			{//向上加载数据
				$.get('/index.php/frmSales/getDxxsDetailCont',{
						'id':selected_sales,
				},function(data){
						$('.forinsert').append(data);					
						//序号
						var count=parseInt($('#forinsert').children('tr').length);
						$('#forinsert tr:last td:first').text(count);	
						detail_id_array.push(selected_sales);					
				});
			}else{
				//向下撤销数据
				$("#cght_tb tbody tr").each(function(){
					var detail_id=$(this).find('.detail_id').val();
					if(detail_id==selected_sales)
					{
						$(this).remove();
						detail_id_array.splice(detail_id_array.indexOf(detail_id),1);
					}
				})
				var row_num=0;
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
			}
		});
	$(document).on('click','#datatable-datatable1 .datatable-rows .flexarea .datatable-wrapper table tr',function(){
		var a=$(this).index();
		var input=$('#datatable-datatable1 .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
		var check=$(input).attr('checked');
		var selected_sales= $(input).val();
		if(check==undefined)
		{
			$(input).attr('checked','checked');
			//向上加载数据
			$.get('/index.php/frmSales/getDxxsDetailCont',{
					'id':selected_sales,
			},function(data){
					$('.forinsert').append(data);					
					//序号
					var count=parseInt($('#forinsert').children('tr').length);
					$('#forinsert tr:last td:first').text(count);	
					detail_id_array.push(selected_sales);					
			});
		}else{
			$(input).removeAttr('checked');
			//向下撤销数据
			$("#cght_tb tbody tr").each(function(){
				var detail_id=$(this).find('.detail_id').val();
				if(detail_id==selected_sales)
				{
					$(this).remove();
					detail_id_array.splice(detail_id_array.indexOf(detail_id),1);
				}					
			})			
			var row_num=0;
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
		}
		
	});

	/******-----------------《《销售列表有关------------------*******/
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script>
	$(function(){
		var array=<?php echo $vendors;?>;
		var array2=<?php echo $coms;?>;
		var array3=<?php echo $warehouses?$warehouses:json_encode(array());?>;
		var array4=<?php echo $teams?$teams:json_encode(array());?>;
		var array5=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array6=<?php echo $vens?$vens:json_encode(array());?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
		$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"teamselect","comboval4",false,'changeTeamU()');
		$('#combo5').combobox(array5, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"saleselect","comboval5");
		$('#combo6').combobox(array6, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","comboval6");
		$('#combo7').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"team2select","comboval7",false);
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);
		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();		
	})
	</script>