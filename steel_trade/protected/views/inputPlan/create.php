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
		<input type="hidden"  name="FrmInput[input_status]" value="0" id="input_status">
		<input type="hidden"  name="FrmInput[input_type]" value="purchase" id="input_type">
		<input type="hidden" name="FrmInput[input_company]" value="<?php echo $purchase->title_id?>" id="input_company">
		<input type="text"  name="" id="title" readonly  value="<?php echo $purchase->title->short_name?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">供应商：</div>
		<input type="text"  name="" id="supply" readonly  value="<?php echo $purchase->supply->short_name;?>" class="form-control " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系人：</div>
		<input type="text"  name="" id="contact" readonly  value="<?php echo $purchase->contact->name?>" class="form-control " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone"  value="<?php echo $purchase->contact->mobile;?>" class="form-control con_tel"  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购单：</div>
			<input type="text"  name="FrmInput[form_sn]" readonly id="form_sn" value="<?php echo $baseform->form_sn?>"   class="form-control con_tel"   >
			<input type="hidden"  name="FrmInput[purchase_id]" id="purchase_id" value="<?php echo $baseform->id?>"   class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购日期：</div>
		<div class="search_date_box" style="margin-top:0px;width:150px;background-position:155px 8px;">
			<input type="text"  name=""  value="<?php echo $baseform->form_time?>" readonly id="form_time" value=""  class="form-control  " placeholder=""  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l maybe_bitian"> <?php echo $_REQUEST['type']=='ccrk'?'<span class="bitian">*</span>':'';?>预计到货日期：</div>
		<div class="search_date_box" style="margin-top:0px;width:150px;background-position:155px 8px;">
			<input type="text"   name="FrmInput[input_date]"  value="<?php echo $purchase->date_reach?date('Y-m-d',$purchase->date_reach):date('Y-m-d',time())?>" id="input_date"  class="form-control form-date date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l maybe_bitian1"> <?php echo $_REQUEST['type']=='ccrk'?'<span class="bitian">*</span>':'';?>预计到货时段：</div>
		 <select name="FrmInput[input_time]" class='form-control input_time' id="reach_time">
	            <option value='0' selected='selected'></option>	             
           		 <option <?php echo $purchase->reach_time=='6'?'selected="selected"':''?>  value="6">00:00-06:00</option>
           		 <option <?php echo $purchase->reach_time=='12'?'selected="selected"':''?>  value="12">06:00-12:00</option>
           		 <option <?php echo $purchase->reach_time=='18'?'selected="selected"':''?>  value="18">12:00-18:00</option>
           		 <option <?php echo $purchase->reach_time=='24'?'selected="selected"':''?>  value="24">18:00-24:00</option>            	
	        </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<input type="text" readonly name="" id="team" value="<?php echo $purchase->team->name?>"   class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务员：</div>
		<input type="text"  name="" readonly id="owned" value="<?php echo $baseform->belong->nickname;?>"   class="form-control con_tel"   >
		<input type="hidden"  name="CommonForms[owned_by]" id="owned_by" value="<?php echo $baseform->owned_by?>"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入库仓库：</div>
		<input type="text"  name="" id="warehouse" readonly value="<?php echo $purchase->warehouse->name;?>"   class="form-control con_tel"   >
		<input type="hidden"  name="FrmInput[warehouse_id]" id="warehouse_id" value="<?php echo $purchase->warehouse_id?>"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmInput[ship_no]" readonly id="transfer_number" value="<?php echo $purchase->transfer_number?>"  class="form-control tit_remark" placeholder=""  >
	</div>
	<?php if(false&& $_REQUEST['type']=='ccrk'){?>
	<div class="shop_more_one">
		<div class="shop_more_one_l maybe_bitian1"> 货物状态：</div>
		 <select name="FrmInput[goods_status]" class="form-control"  id="goods_status">
	            <option value="1" selected="selected">在厂里等装货</option>	             
           		 <option  value="2">在厂里在装货</option>          		             	
	        </select>
	</div>
	<?php }?>
	<div class="shop_more_one remark_cont">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]"  class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one" style="display:none">
		<div class="shop_more_one_l">创建时间：</div>
		<input type="text"  name="CommonForms[form_time]" id="" value="<?php echo date('Y-m-d',time());?>"  class="form-control date create_time"  placeholder="请选择日期"  >
	</div>
	<div class="shop_more_one" style="display: <?php echo $_REQUEST['type']=='ccrk'?'none':'block';?>"> 
		<div class="shop_more_one_l" style="padding-top:2px;">是否船舱：</div>
		<input class="check_box l is_cc"  type="checkbox"  <?php echo $_REQUEST['type']=='ccrk'?'checked="checked"':'';?> name="FrmInput[is_cc]" value="1" /><div class="lab_check_box">船舱入库</div>
	</div>
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:30px;"></th>
         		<th class="text-center" style="width:40px;">操作</th>
         		<th class="text-center" style="">产地</th>
         		<th class="text-center" style="">品名</th>
         		<th class="text-center" style="">材质</th>
         		<th class="text-center" style="">规格</th>         		         		
         		<th class="text-center" style="">长度</th>
         		<th class="text-center" style="">可入库件数</th>
         		<th class="text-center" style=""><span class="bitian">*</span>入库件数</th>
         		<th class="text-center" style=""><span class="bitian">*</span>入库重量</th>
         		<th class="text-center"  style="display:<?php echo $_REQUEST['type']=='ccrk'?'':'none'?>">保留件数</th>
         		<th class="text-center"  style="display:<?php echo $_REQUEST['type']=='ccrk'?'':'none'?>">保留重量</th>
         		<th class="text-center" style="display:<?php echo $_REQUEST['type']=='ccrk'?'':'none'?>"><span class="bitian">*</span>价格</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	<?php
    	 if(!empty($details)){ $i=1;foreach ($details as $data){
    	 	if($data->amount-$data->input_amount<=0)continue;
    	?>
    	<tr class="<?php echo $i%2==0?"selected":""?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $data->brand_id?>" class="form-control yes td_brand">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo DictGoodsProperty::getProName($data->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $data->id;?>"  class="detail_id"/>
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
    		<td >
    			<input type="text" readonly name="" style="" value="<?php echo $data->amount-$data->input_amount;?>" class="form-control  " >
    		</td>
    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $data->amount-$data->input_amount;?>" class="form-control td_amount td_num" >
    			<input type="hidden"  name="" style="" value="<?php echo $data->amount-$data->input_amount;?>" class="form-control  td_max_num" >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($data->weight-$data->input_weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $data->weight-$data->input_weight;?>" style="" class="form-control td_total_weight" >
    		</td>
    		<td style="display:<?php echo $_REQUEST['type']=='ccrk'?'':'none'?>">
    			<input type="text"  name="td_remain_amount[]" style="" value="" class="form-control  td_remain_num" >
    		</td>
    		<td style="display:<?php echo $_REQUEST['type']=='ccrk'?'':'none'?>">
    			<input type="text"  name="td_remain_weight[]" style="" value="" class="form-control  td_remain_weight" >
    		</td>
    		 <td style="display:<?php echo $_REQUEST['type']=='ccrk'?'':'none'?>">
    			<input type="text"  name="td_price[]"  readonly value="<?php echo round($data->price,2);?>" style="" class="form-control td_price" >
    		</td>
    	</tr>
    	<?php $i++;}}?>
    	
    </tbody>
  </table>
</div>
<div class="btn_list">
<!--     <button type="button" class="btn btn-primary btn-sm " data-dismiss="modal"  id="submit_btn1">保存推送</button> -->
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
</div>
<?php $this->endWidget()?>
<div style="clear: both;">
 <div class="search_line"></div>
 <div class="search_title">选择采购单</div>
<form method="post" action="">
<div class="search_body search_background">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入采购单号" id="search_keywords" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
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
		<div style="float:left;margin-left:10px;">销售公司：</div>
		<div id="saleselect" class="fa_droplist">
			<input type="text" id="combo5" class="forreset" value="<?php echo DictCompany::getName($search['title_id'])?>" />
			<input type='hidden' id="comboval5" class="forreset" value="<?php echo $search['title_id']?>" name="search[title_id]" />
		</div>
	</div>
	
	<div class="select_body" style="position:relative">
	<div class="more_select_box" style="top:40px;left:-220px;width:500px;">
		<div class="more_one">
		<div class="shop_more_one">
			<div class="shop_more_one_l" style="width: 90px;">采购公司：</div>
			<div id="cusselect" class="fa_droplist">
				<input type="text" id="combo6" class="forreset" value="<?php echo DictCompany::getName($search['customer_id'])?>"/>
				<input type='hidden' id='comboval6' class="forreset" value="<?php echo $search['customer_id']?>" name="search[customer_id]" />
			</div>
		</div>
		</div>
		
		<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class="form-control chosen-select forreset" id="search_owned_by">
	         <option value='0' selected='selected'>-全部-</option>
	         <?php if(!empty($users)){ foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }}?>
	      </select>
	</div>
		
	</div>
	</div>
	<input type="button" class="btn btn-primary btn-sm btn_sub search_btn" data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
</div>
<div style="clear: both;" id="sales_list">

</div>
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
	//采购列表的选择与否
	if(row_num==0)
	{
		selected_sales=0;
		$('#sales_list').find('input[type=radio]').each(function(){				
			$(this).removeAttr('checked');					
		})
	}
})

$('#cancel').click(function(){
	window.history.back(-1);
});
var type='<?php echo $_REQUEST['type']?'ccrk':'plan'?>';
$('.is_cc').click(function(){
	var is_cc=$(this).attr('checked');	
	if(is_cc=='checked')
	{
		type='ccrk';
		$('.maybe_bitian').html('<span class="bitian">*</span>预计到货日期：');
		$('.maybe_bitian1').html('<span class="bitian">*</span>预计到货时段：');
		$('#cght_tb th').eq(10).show();
		$('#cght_tb th').eq(11).show();
		$('#cght_tb th').eq(12).show();
		$('#cght_tb .td_weight').parent().next().show();
		//货物状态
// 		var strrr='<div class="shop_more_one goods_sta"><div class="shop_more_one_l maybe_bitian1"> 货物状态：</div>'+
// 								'<select name="FrmInput[goods_status]" class="form-control"  id="goods_status">'+
// 									'<option value="1" selected="selected">在厂里等装货</option>'+
// 									'<option  value="2">在厂里在装货</option>'+
// 								'</select>'+
// 							'</div>';
// 		$('.remark_cont').before(strrr);		
		$('#cght_tb .td_weight').parent().next().next().show();
		$('#cght_tb .td_weight').parent().next().next().next().show();
	}else{
		type='plan';
		$('.maybe_bitian').html('预计到货日期：');
		$('.maybe_bitian1').html('预计到货时段：');
		$('#cght_tb th').eq(10).hide();
		$('#cght_tb th').eq(11).hide();
		$('#cght_tb th').eq(12).hide();
		$('#cght_tb .td_weight').parent().next().hide();
// 		$('.goods_sta').remove();
		$('#cght_tb .td_weight').parent().next().next().hide();
		$('#cght_tb .td_weight').parent().next().next().next().hide();
	}
});
var  can_submit = true;
$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var is_cc=$('.is_cc').attr('checked');
		var input_date=$('#input_date').val();
		var warehouse_id=$('#warehouse_id').val();
		// if(!warehouse_id){
		// 	confirmDialog('请填写仓库信息！');
		// 	return ;
		// }
		if(is_cc=='checked')
		{
			if(input_date=='')
			{
				confirmDialog('请选择输入预计到货日期');
				return false;
			}
			var datenow=CurrentTime();
			if(input_date<datenow)
			{
				confirmDialog('预计到货日期须大于当前日期');
				return false;
			}
			var input_time=$('.input_time').val();
			if(input_time=='0')
			{
				confirmDialog('请输入预计到货时段');
				return false;
			}
		}else{
			var create_time=$('.create_time').val();
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
			}
		}
		var flag=true;
		var detailLength=parseInt($("#cght_tb tbody tr").length);
		if(!detailLength){confirmDialog('您没有选择明细信息');return false;}
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_remain_amount=$(this).find(".td_remain_num").val();
			var td_remain_weight=$(this).find(".td_remain_weight").val();
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
			if(td_remain_amount!=undefined&&td_remain_amount!=''&&(!/^[1-9][0-9]*$/.test(td_remain_amount))){confirmDialog("请修改编号为"+list_num+"的保留件数为整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_remain_amount!=undefined&&parseInt(td_remain_amount)>parseInt(td_amount)){confirmDialog('保留件数不得大于入库件数');flag=false;return false;}
			if(td_remain_weight!=undefined&&Number(td_remain_weight)>Number(td_weight)){confirmDialog('保留重量不得大于入库重量');flag=false;return false;}
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
// 	$('#submit_btn1').click(function(){
// 		if(!can_submit){return false;}
// 		var is_cc=$('.is_cc').attr('checked');
// 		var input_date=$('#input_date').val();
// 		if(is_cc=='checked')
// 		{
// 			if(input_date=='')
// 			{
// 				confirmDialog('请选择输入预计到货日期');
// 				return false;
// 			}
// 			var datenow=CurrentTime();
// 			if(input_date<datenow)
// 			{
// 				confirmDialog('预计到货日期须大于当前日期');
// 				return false;
// 			}	
// 			var input_time=$('.input_time').val();
// 			if(input_time=='0')
// 			{
// 				confirmDialog('请输入预计到货时段');
// 				return false;
// 			}
// 		}else{
// 			var create_time=$('.create_time').val();
// 			if(input_date!='')
// 			{
// 				if(create_time!='')
// 				{
// 					if(input_date<create_time)
// 					{
// 						confirmDialog('预计到货日期须大于创建日期');
// 						return false;
// 					}
// 				}else{
// 					var datenow=CurrentTime();
// 					if(input_date<datenow)
// 					{
// 						confirmDialog('预计到货日期须大于当前日期');
// 						return false;
// 					}
// 				}
// 			}
// 		}
// 		var flag=true;
// 		var detailLength=parseInt($("#cght_tb tbody tr").length);
// 		if(!detailLength){confirmDialog('您没有选择明细信息');return false;}
// 		$("#cght_tb tbody tr").each(function(){
// 			var list_num = $(this).find(".list_num").text();
// 			var td_amount = $(this).find(".td_num").val();
// 			var td_weight = $(this).find(".td_weight").val();
// 			var td_remain_amount=$(this).find(".td_remain_amount").val();
// 			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
// 			if(td_remain_amount!=undefined&&(!/^[1-9][0-9]*$/.test(td_remain_amount))){confirmDialog("请修改编号为"+list_num+"的保留件数为整数");flag= false;return false;}
// 			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
// 			if(td_remain_amount!=undefined&&td_remain_amount>td_amount){confirmDialog('保留件数不得大于入库件数');flag=false;return false;}
// 		})
// 		if(!flag){return false;}
// 		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
// 		$(this).parent().append(str);
// 		if(can_submit){
// 	        can_submit = false;
// 	        setTimeout(function(){can_submit = true;},3000);
// 	        $("#form_data").submit();
// 	    }
// 	})
</script>
<script>
	     $.ajaxSetup({ async: false });
	  	    var unit_weight=0;
	  		$(document).on('change','.td_num',function(){
	  			//)$('.td_num').change(function(){			
	  			var that=$(this);
	  			var td_num=$(this).val();
	  			var td_max_num=$(this).next().val();
	  			if(!/^[1-9][0-9]*$/.test(td_num))
	  			{
	  				confirmDialog('件数必须为大于0的整数');
	  				return;
	  			}
	  			if(parseInt(td_num)>parseInt(td_max_num))
	  			{
		  			confirmDialog('件数不可大于最大可输件数'+td_max_num);
		  			$(this).val('');
		  			return ;
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
	  		});
		//重量改变
		$(document).on('change','.td_weight',function(){
			//改变金额
			var td_weight=$(this).val();
			if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
			{						
				confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
				return;
			}
			$(this).next().val(td_weight);
		});

		//保留重量改变
		$(document).on('change','.td_remain_weight',function(){
			//改变金额
			var td_weight=$(this).val();
			if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight))
			{						
				confirmDialog('保留重量必须是大于等于0的整数或小数点后6位的小数');
				return;
			}
		});
		//保留件数发生变化
		$(document).on('change','.td_remain_num',function(){		
  			var that=$(this);
  			var td_num=$(this).val();
  			var td_max_num=$(this).parent().parent().find(".td_max_num").val();
  			if(!/^[1-9][0-9]*$/.test(td_num))
  			{
  				confirmDialog('保留件数必须为大于0的整数');
  				$(this).val('');
  				return;
  			}
  			if(parseInt(td_num)>parseInt(td_max_num))
  			{
	  			confirmDialog('保留件数不可大于最大可输件数'+td_max_num);
	  			$(this).val('');
	  			return ;
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
  	    			if(unit_weight!=0)
  	    			{
	  	    			that.parent().parent().find('.td_remain_weight').val((td_num*unit_weight).toFixed(3));
  	    			}	  	    			
  	    	});				
  		});
  		
		/**----------------库存采购列表》》-------------***/
		var selected_sales='<?php echo $_REQUEST['purchase_common_id'];?>';
		$(function(){
			$('.reset').click(function(){
			    $('.forreset').val('');		    
			});
			$.get('/index.php/purchase/getSimpleList',{
				'type':'plan',
				},function(data){
					$('#sales_list').html(data);
					$('#sales_list').find('input[type=radio]').each(function(){
						var id = $(this).val();
						if(id==selected_sales&&selected_sales!='')
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
			var owned_by=$('#search_owned_by').val();
			var url="/index.php/purchase/getSimpleList?&page=1";
			$.get(url,{
				'type':'plan',
				'keywords':keywords ,
				'time_L':time_L ,
				'time_H':time_H ,
				'title_id':title_id , //销售公司
				'customer_id':customer_id ,//采购公司
				'owned_by':owned_by ,
			},function(data){
				$('#sales_list').html(data);
				$('input[type=radio]').each(function(){
					var id = $(this).val();
					if(id==selected_sales)
					{
						$(this).attr('checked','checked');					
					}
				});
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
			var owned_by=$('#search_owned_by').val();
			var url=$(this).attr('href');
			$.get(url,{
				'type':'plan',
				'keywords':keywords ,
				'time_L':time_L ,
				'time_H':time_H ,
				'title_id':title_id , //销售公司
				'customer_id':customer_id ,//采购公司
				'owned_by':owned_by ,
			},function(data){
				$('#sales_list').html(data);
				$('input[type=radio]').each(function(){
					var id = $(this).val();
					if(id==selected_sales)
					{
						$(this).attr('checked','checked');					
					}
				});
			});
		});
		  $(document).on('change','#each_page',function(){
			  	limit=$(this).val();
			  	$.post("/index.php/site/writeCookie", {
			  		'name' : "purchase_list",
			  		'limit':limit
			  	}, function(data) {
			  		if(data){
			  			var keywords=$('#search_keywords').val();
			  			var time_L=$('#search_begin').val();
			  			var time_H=$('#search_end').val();
			  			var title_id=$('#comboval5').val();
			  			var customer_id=$('#comboval6').val();
			  			var owned_by=$('#search_owned_by').val();
			  			var url=$('.firstpage').attr('href');
			  			$.get(url,{
			  				'type':'plan',
			  				'keywords':keywords ,
			  				'time_L':time_L ,
			  				'time_H':time_H ,
			  				'title_id':title_id , //销售公司
			  				'customer_id':customer_id ,//采购公司
			  				'owned_by':owned_by ,
			  			},function(data){
			  				$('#sales_list').html(data);
			  				$('input[type=radio]').each(function(){
								var id = $(this).val();
								if(id==selected_sales)
								{
									$(this).attr('checked','checked');					
								}
							});
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
				var owned_by=$('#search_owned_by').val();
				$.get(url,{
					'type':'plan',
					'keywords':keywords ,
					'time_L':time_L ,
					'time_H':time_H ,
					'title_id':title_id , //销售公司
					'customer_id':customer_id ,//采购公司
					'owned_by':owned_by ,
				},function(data){
					$('#sales_list').html(data);
					$('input[type=radio]').each(function(){
						var id = $(this).val();
						if(id==selected_sales)
						{
							$(this).attr('checked','checked');					
						}
					});
				});
			});	
		//选择，填充数据
		$(document).on('click','.selected_sales',function(){
				selected_sales= $(this).val();
				$.get('/index.php/purchase/getMainInfo',{
					'id':selected_sales,
				},function(data){
					var json=eval('('+data+')');
					$('#title').val(json.title_name);
					$('#input_company').val(json.title);
					$('#supply').val(json.supply_name);
					$('#contact').val(json.contact_name);
					$('#phone').val(json.mobile);
					$('#form_sn').val(json.form_sn);
					$('#form_time').val(json.form_time);
					$('#team').val(json.team);
					$('#owned').val(json.owned);
					$('#owned_by').val(json.owned_by);
					$('#warehouse').val(json.warehouse);
					$('#warehouse_id').val(json.warehouse_id);
					$('#transfer_number').val(json.transfer_number);
					$('#input_date').val(json.date_reach);
					$('#reach_time').val(json.reach_time);
				});
				$.get('/index.php/purchase/getPurchaseDetail',{
					'id':selected_sales,
					'from':type,
				},function(data){
					$('.forinsert').html(data);
					$('#purchase_id').val(selected_sales);
					var is_cc=$('.is_cc').attr('checked');
					if(is_cc=='checked')
					{
// 						$("#cght_tb .td_weight").each(function(){
// 							$(this).parent().after('<td><input type="text"  name="td_remain_weight[]" style="" value="" class="form-control  td_remain_weight"></td>');
// 							$(this).parent().after('<td><input type="text"  name="td_remain_amount[]" style="" value="" class="form-control  td_remain_num"></td>');
// 							$(this).parent().after('<td><input type="text"  name="td_price[]" style="" value="" class="form-control  td_price"></td>');
// 						})
					}				
				});
			});
		$(document).on('click','#datatable-datatable1 .datatable-rows .flexarea .datatable-wrapper table tr',function(){
			var a=$(this).index();
			var input=$('#datatable-datatable1 .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
			selected_sales= $(input).val();
			$(input).trigger('click');		
		});
		/****----------------------《《----------------------------****/
	</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script>
var array5=<?php echo $vendors?$vendors:json_encode(array());?>;
var array6=<?php echo $vens?$vens:json_encode(array());?>;
$('#combo5').combobox(array5, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"saleselect","comboval5");
$('#combo6').combobox(array6, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","comboval6",false,'',200);
</script>
