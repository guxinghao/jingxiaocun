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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script type="text/javascript">
var array_brand=<?php echo $brands;?>;
var array=<?php echo $vendors?$vendors:json_encode(array());?>;
var array2=<?php echo $coms?$coms:json_encode(array());?>;
var array3=<?php echo $warehouses?$warehouses:json_encode(array());?>;
var array4=<?php echo $teams?$teams:json_encode(array());?>;
</script>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="shop_select_box">
	<div class="shop_more_one">
			<input type="hidden" name="FrmPurchase[purchase_type]" id="purchase_type" value="<?php echo $type;?>"/>
			<div class="shop_more_one_l supply_div"><span class="bitian">*</span>供应商：</div>
	<?php if($purchase->frm_contract_id){?>
			<div class="search_date_box">
				<input type="text"  readonly value="<?php echo $purchase->supply->short_name?>"  class="form-control"/>
				<input type="hidden"  id="comboval" name="FrmPurchase[supply_id]" value="<?php echo $purchase->supply_id;?>" class="form-control  supply_id" placeholder=""  >
			</div>
	<?php }else{?>
			<div id="venselect" class="fa_droplist">
				<input type="text" id="combo" value="<?php echo $purchase->supply->short_name?>" />
				<input type='hidden' id='comboval'  value="<?php echo $purchase->supply_id?>"  name="FrmPurchase[supply_id]"/>
			</div>
	<?php }?>			
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l title_div"><span class="bitian">*</span>公司：</div>		
		<?php if($purchase->frm_contract_id){?>
		<div class="search_date_box" >
				<input type="text"  readonly value="<?php echo $purchase->title->short_name?>" class="form-control"/>
				<input type="hidden" id="comboval2" name="FrmPurchase[title_id]" value="<?php echo $purchase->title_id;?>" class="form-control title_id" placeholder=""  >
		</div>
		<?php }else{?>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" value="<?php echo $purchase->title->short_name?>" />
			<input type='hidden' id='comboval2' value="<?php echo $purchase->title_id;?>"  name="FrmPurchase[title_id]"/>
		</div>
		<?php }?>
	</div>
	<div class="shop_more_one" style="position:relative">
		<div class="shop_more_one_l" >采购合同：</div>
		<div id="close_section">
			<input type="hidden" name="FrmPurchase[frm_contract_id]" id="frmpurchase_contract_hidden" value="<?php echo $purchase->frm_contract_id?>">
			<input type="text"   id="frmpurchase_contract" value="<?php echo $purchase->contract_baseform->contract->contract_no;?>"  style="width:110px;height:33px;" class="form-control con_tel"   >
			<span style="float: right;margin-top:-31px;width:36px;font-size:13px;cursor:pointer;color:#fff;text-align:center;border:none;background:#1ca1e4;height:29px;line-height:29px;border-radius:4px;" class="contract_link colorbox" url="<?php echo Yii::app()->createUrl('contract/listForSelect')?>">关联</span>
			<img style="position:absolute;display:none;top:8px;right:50px;width:18px;z-index:10;background:#fff;" class="clear_contract"  src="/images/close11.png"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购日期：</div>
		<div class="search_date_box" >
			<input type="text"  name="CommonForms[form_time]"  value="<?php echo $baseform->form_time?>" id="form_time" class="form-control form-date date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]" class='form-control chosen-select se_ywz' id="contact_id">
				<?php //if(!empty($contacts)){ foreach($contacts as $k=>$v){?>
	            	<!--  ><option <?php echo $purchase->contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>-->
	            <?php //}}?>
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
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option <?php echo $purchase->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	      </select>		
	      <input type="hidden" name="FrmPurchase[team_id]"   value="<?php echo $purchase->team_id?>">	
<!-- 		<div id="teamselect" class="fa_droplist"> --
			<input type="text" id="combo4" value="<?php  //echo $purchase->team->name?>" />
			<input type='hidden' id='comboval4'  value="<?php //echo $purchase->team_id?>"  name="FrmPurchase[team_id]"/>
<!-- 		</div> -->
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计到货日期：</div> 
		<div class="search_date_box" >
			<input type="text"  name="FrmPurchase[date_reach]" id="date_reach" value="<?php echo ($purchase->date_reach>943891200)?date('Y-m-d',$purchase->date_reach):'';?>" class="form-control form-date date input_backimg"  placeholder="选择日期"  >
 		</div> 
 	</div> 
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="<?php echo $purchase->contact->mobile;?>" class="form-control con_tel" placeholder=""  >
	</div>
 	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]" value="<?php echo $purchase->transfer_number?>" class="form-control transfer" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入库仓库：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo3" value="<?php echo $purchase->warehouse->name;?>" />
			<input type='hidden' id='comboval3'  value="<?php echo $purchase->warehouse_id?>"  class="wareinput" name="FrmPurchase[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l maybe_bitian1">预计到货时段：</div>
		 <select name="FrmPurchase[reach_time]" class='form-control reach_time'>
	            <option value='0' selected='selected'></option>	             
           		 <option <?php echo $purchase->reach_time==6?'selected="selected"':''?> value="6">00:00-06:00</option>
           		 <option <?php echo $purchase->reach_time==12?'selected="selected"':''?> value="12">06:00-12:00</option>
           		 <option <?php echo $purchase->reach_time==18?'selected="selected"':''?>  value="18">12:00-18:00</option>
           		 <option <?php echo $purchase->reach_time==24?'selected="selected"':''?>  value="24">18:00-24:00</option>            	
	        </select>
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" value="<?php //echo $purchase->invoice_cost;?>" class="form-control invoice_cost" placeholder=""  ><span class="danwei">元/吨</span>
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" value="<?php echo $baseform->comment;?>" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l"><span class="bitian">*</span>运费单价：</div>
		<input type="text"  name="FrmPurchase[shipment]" class="form-control "  value="<?php echo $purchase->shipment?>" id="shipment" style="width:145px;height:33px;" placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one" >
		<input class="check_box l" style="margin-left:90px;" type="checkbox"  <?php echo $purchase->is_yidan==1?'checked="checked"':''?> name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<?php if($type!='dxcg'){?>
		<input class="check_box l" type="checkbox"  <?php echo $purchase->purchase_type=="tpcg"?'checked="checked"':''?>  name="FrmPurchase[is_tp]" id="is_tp" value="1" /><div class="lab_check_box">托盘</div>
		<?php }?>
		<input  type="hidden"   name="FrmPurchase[contain_cash]" value="1" />
	</div>			
	
</div>
<div class="tp_hidden" style="clear:both;width:99%;margin:0 auto;margin-bottom:10px;display:none;position:relative;">
	<div style="position:absolute;width:0; height:0; border-width:10px; border-color:transparent transparent #c8c8c8 transparent; border-style:dashed dashed solid dashed; overflow:hidden;  top:-20px;left:975px;"></div>
	<div style="position:absolute;width:0; height:0; border-width:10px; border-color:transparent transparent #fff transparent; border-style:dashed dashed solid dashed; overflow:hidden; top:-19px; left:975px;"></div>
	<!-- <div style="margin-left:40%;width: 0; height: 0; border-left: 10px solid #ccc; border-right: 10px solid transparent;border-bottom: 10px solid red;"></div> -->
	<div style="border:1px solid #ccc;border-radius:5px;width:100%;margin:0 auto;padding-top:10px;">
		<div class="shop_more_one">
			<div class="shop_more_one_l"><span class="bitian">*</span>托盘公司：</div>
			 <select name="FrmPurchase[pledge_company_id]" id="pledge_company"  class='form-control chosen-select se_yw'>
			 <?php if(!empty($tpcompanys)){foreach($tpcompanys as $k=>$v){?>
			   	<option <?php echo $purchase->pledge->pledge_company_id==$k?'selected="selected"':"";?>  value='<?php echo $k;?>'><?php echo $v;?></option>
			  <?php }}?>
	   		 </select>
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘价格：</div>
				 <input type="text"  name="FrmPurchase[unit_price]" value="<?php echo $purchase->pledge->unit_price;?>" class="form-control unit_price" placeholder=""  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘总金额：</div>
				 <input type="text"  name="FrmPurchase[fee]" value="<?php echo $purchase->pledge->fee;?>" class="form-control fee" placeholder=""  >
			</div>
			<div class="shop_more_one" style="width:275px;">
				<div class="shop_more_one_l" style="width:130px;">托盘赎回限制等级：</div>
				 <select name="" id="r_limit" disabled class='form-control chosen-select se_yw'>
				   	<option <?php echo $purchase->pledge->r_limit==1?'selected="selected"':"";?> value="1">产地</option>
				   	<option <?php echo $purchase->pledge->r_limit==2?'selected="selected"':"";?>  value="2">产地+品名</option>
	       		 </select>
	       		 <input type="hidden" name="FrmPurchase[r_limit]" value="">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘预付款：</div>
				<input type="text"  name="FrmPurchase[advance]" value="<?php echo $purchase->pledge->advance;?>" class="form-control  advance" placeholder=""  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘天数：</div>
				 <input type="text"  name="FrmPurchase[pledge_length]" value="<?php echo $purchase->pledge->pledge_length?>" class="form-control pledge_length" placeholder=""  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>违约天数：</div>
				 <input type="text"  name="FrmPurchase[violation_date]" value="<?php echo $purchase->pledge->violation_date?>" class="form-control violation_date" placeholder=""  >
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>最小利率：</div>
				 <input type="text"  name="FrmPurchase[min_rate]" value="<?php echo $purchase->pledge->min_rate?>" class="form-control min_rate" placeholder=""  >
				 <span class="danwei">‰/天</span>
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>托盘利率：</div>
				 <input type="text"  name="FrmPurchase[pledge_rate]" value="<?php echo $purchase->pledge->pledge_rate?>" class="form-control pledge_rate" placeholder=""  >
				 <span class="danwei">‰/天</span>
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l"><span class="bitian">*</span>违约利率：</div>
				 <input type="text"  name="FrmPurchase[over_rate]" value="<?php echo $purchase->pledge->over_rate?>" class="form-control over_rate" placeholder=""  >
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
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>规格</th>         		         		
         		<th class="text-center" style="width:4%;">长度</th>
    			<th class="text-center" style="width:6%;">件重</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>单价</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>金额</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>开票成本</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	 <?php $i=1; foreach ($details as $each){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">
    		
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
    			<div id="<?php echo "bbbrandselect".$i;?>" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">
					<input type="text" id="<?php echo "bbcombobrand".$i?>" style="width:130px;"  value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" />
					<input type='hidden' id='<?php echo "bbcombovalbrand".$i?>' value="<?php echo $each->brand_id?>"   name="old_td_brands[]" class="td_brand"/>
				</div>
	    		
	    		<input type="hidden" class="old_value" value="<?php echo $each->brand_id;?>">
    		</td>
    		<td class="">
    			<input type="hidden" name="old_td_id[]" value="<?php echo $each->id;?>" />
    			<select name='old_td_products[]' class="form-control chosen-select td_product" onchange="productChange(this)">
    			<?php foreach($products as $k=>$v){?>
	            	<option <?php echo $each->product_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
	    		<input type="hidden" class="old_value" value="<?php echo $each->product_id;?>">
    		</td>
    		<td class="">
    			<select name="old_td_textures[]" class="form-control chosen-select td_texture" onchange="textureChange(this)">
	            <?php foreach($textures as $k=>$v){?>
	            	<option <?php echo $each->texture_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
	    		<input type="hidden" class="old_value" value="<?php echo $each->texture_id;?>">
    		</td>    	
    		<td class="">
    			<select name="old_td_ranks[]" class="form-control chosen-select td_rank" onchange="rankChange(this)">
	            <?php foreach($ranks as $k=>$v){?>
	            	<option <?php echo $each->rank_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
	    		<input type="hidden" class="old_value" value="<?php echo $each->rank_id;?>">
    		</td>    			
    		<td class=""><input type="text"  name="old_td_length[]" style="" value="<?php echo $each->length;?>" onchange="lengthChange(this)"  class="form-control td_length" placeholder=""  ></td>
    		<td class=""><input type="text"  readonly name="" style="" value="" class="form-control td_unit_weight" placeholder=""  ></td>
    		<td class=""><input type="text"  name="old_td_amount[]" style="" value="<?php echo $each->amount;?>" class="form-control td_num" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name=""  value="<?php echo round($each->weight,3);?>" style="" class="form-control td_total_weight td_weight" placeholder=""  >
    			<input type="hidden"  name="old_td_weight[]"  value="<?php echo $each->weight;?>" style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class=""><input type="text"  name="old_td_price[]" style="" value="<?php echo round($each->price,2);?>" class="form-control td_price" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name="old_td_totalMoney[]"  readonly value="<?php echo number_format($each->weight*$each->price,2);?>" style="" class="form-control td_money" placeholder=""  >
    			<script type="text/javascript">
	    		$('#bbcombobrand<?php echo $i?>').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"bbbrandselect<?php echo $i?>","bbcombovalbrand<?php echo $i?>",false,'brandChange(obj)');
	    		</script>	
    		</td>
    		<td ><input type="text" name="old_td_invoice[]"  class="form-control td_invoice" value="<?php echo $each->invoice_price;?>"></td>
    	</tr>    	
    	<?php $i++;}?>
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
			<td style=""><span class="tf_total_amount">0</span></td>
			<td style=""><span class="tf_total_weight">0</span></td>
			<td style=""></td>
			<td style=""><span class="tf_total_money">0</span></td>
			<td></td>
		</tr>
   </tfoot>
  </table>
</div>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal" style="background:#426ebb;" id="submit_btn1">保存提交</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="submit_btn">保存</button>
	<a href="<?php  echo Yii::app()->createUrl($backUrl,array('search_url'=>$_REQUEST['search_url']))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script>
<script type="text/javascript">
// $('#cancel').click(function(){
// 	 window.history.back(-1);
// })
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
var invoice=$('.invoice_cost').val();
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
$(document).on("blur","select",function(){
	$(this).removeClass("red-border");
});
	 var purchase_type='normal';
	 var date_now='<?php echo date('Y-m-d',time());?>';
	//新增采购合同明细
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
		'<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>'+
		'<td class="">'+
		'<div id="brandselect'+num+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
			'<input type="text" id="combobrand'+num+'" style="width:130px;"  value="" />'+
			'<input type="hidden" id="combovalbrand'+num+'" value=""   name="td_brands[]" class="td_brand"/>'+
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
		'<td class=""><input type="text" name="td_length[]" style="" onchange="lengthChange(this)"  class="form-control td_length" placeholder=""  ></td>'+
		'<td class=""><input type="text"  readonly name="" style="" value="" class="form-control td_unit_weight" placeholder=""  ></td>'+
		'<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>'+
		'<td class="">'+
			'<input type="text"  name=""  style="" class="form-control td_total_weight td_weight" placeholder=""  >'+
			'<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >'+
		'</td>'+
		'<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>'+
		'<td class=""><input type="text" readonly name="td_totalMoney[]"  style="" class="form-control td_money" placeholder=""  ></td>'+
		'<td><input type="text" name="td_invoice[]" class="form-control td_invoice"></td>'+
	'</tr>';

		//获取最后一条的值
		var nextBrand= $("#cght_tb tbody tr:last").find('.td_brand').val();
		var nextProduct= $("#cght_tb tbody tr:last").find('.td_product').val();
		var nextTexture= $("#cght_tb tbody tr:last").find('.td_texture').val();
		var nextRank= $("#cght_tb tbody tr:last").find('.td_rank').val();
		var nextLength= $("#cght_tb tbody tr:last").find('.td_length').val();
		var nextNum= $("#cght_tb tbody tr:last").find('.td_num').val();
		var nextWeight= $("#cght_tb tbody tr:last").find('.td_weight').val();
		var nextTotalWeight= $("#cght_tb tbody tr:last").find('.td_total_weight').val();
		var nextPrice= $("#cght_tb tbody tr:last").find('.td_price').val();
		var nextMoney= $("#cght_tb tbody tr:last").find('.td_money').val();	
		var nextInvoice= $('#cght_tb tbody tr:last').find('.td_invoice').val();	
		$("#cght_tb tbody ").append(newRow);
		$('#combobrand'+num).combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect"+num,"combovalbrand"+num,false,'brandChange(obj)');
		$("#tr_num").val(count);
		num++;

		$("#cght_tb tbody tr:last").find('.td_brand').val(nextBrand);
		$("#cght_tb tbody tr:last").find('.td_product').val(nextProduct);
		$("#cght_tb tbody tr:last").find('.td_texture').val(nextTexture);
		$("#cght_tb tbody tr:last").find('.td_rank').val(nextRank);
		$("#cght_tb tbody tr:last").find('.td_length').val(nextLength);
		$("#cght_tb tbody tr:last").find('.td_num').val(nextNum);
		$("#cght_tb tbody tr:last").find('.td_weight').val(nextWeight);
		$("#cght_tb tbody tr:last").find('.td_total_weight').val(nextTotalWeight);
		$("#cght_tb tbody tr:last").find('.td_price').val(nextPrice);
		$("#cght_tb tbody tr:last").find('.td_money').val(nextMoney);
		$("#cght_tb tbody tr:last").find('.td_invoice').val(nextInvoice);	

		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();
		
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
		$("#cght_tb tbody tr:last").find('.td_length').val(nextLength);
		
	});
</script>
<script>		

   	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
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
    	$('#pledge_company').change(function(){
    		var sel=$(this).val();
    		$.ajaxSetup({async:false});
    		$.get('/index.php/dictCompany/tpLevel/'+sel,{},function(data){
    			if(data!='error'){
    				$('#r_limit').val(data);
    				$('#r_limit').next().val(data);
    			}else{
    				confirmDialog('数据错误');
    			}			
    		})
    	});
		//托盘
		$(function(){
			var check=$('#is_tp').attr('checked');
			if(check)
			{
				$('.tp_hidden').show();
				$('#pledge_company').trigger('change');
			}else{
				$('.tp_hidden').hide();
			}
			$("#submit_btn").unbind();
			$("#submit_btn1").unbind();
			var  can_submit = true;
			$("#submit_btn").click(function(){
				if(!can_submit){return false;}
				var flag=true;
				//判断更新时间
				var lastupdate='<?php echo $_REQUEST['last_update']?>';
				var id='<?php echo $_REQUEST['id']?>';
				var fpage='<?php echo $_REQUEST['fpage']?>';
				var type='<?php echo $_REQUEST['type']?>';
				var url='/index.php/purchase/update/'+id+'?fpage='+fpage+'&&type='+type;
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
// 						setTimeout('',2300);
// 						window.location.href=url+'&&last_update='+data;
						flag=false;
						return false;
					}
				});		
				if(!flag)return false;		
				
				var str='';
				var gys = $("#comboval").val();		
				var contact=$('#contact_id').val();
				var form_time=$('#form_time').val();
				var owned_by=$('#CommonForms_owned_by').val();
				var ware=$('.wareinput').val();
				var transfer=$('.transfer').val();
				var cost_price=$('.invoice_cost').val();
				var date_reach=$('#date_reach').val();
				if(gys==''){confirmDialog("请选择输入供应商！");return false;}
				var cggs = $("#comboval2").val();
				if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}		
				if(ware==''){confirmDialog('请选择输入仓库');return false;}
				if(contact==''||!contact){confirmDialog("请选择输入联系人！");return false;}
				if(form_time==''){confirmDialog("请选择输入采购日期！");return false;}
				if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
				if(transfer=='')
				{
					confirmDialog('请输入车船号');return false;
				}
				if(cost_price==''){confirmDialog('请输入开票成本');return false;}
				var datenow=CurrentTime();
				if(date_reach!=''&&date_reach<datenow)
				{
					confirmDialog('预计到货日期须大于当前日期');
					$('#date_reach').focus();
					return false;
				}	
				if(date_reach=='')
				{
					var reach_time=$('.reach_time').val();
					if(reach_time!='0')
					{
						confirmDialog('请选择输入预计到货日期');
						return false;
					}
				}
				var shipment=$('#shipment').val();
				if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
				//tuopan
				var is_tp=$('#is_tp').attr('checked');
				if(is_tp=='checked')
				{
					var tp_price=numChange($('.unit_price').val());
					var tp_money=numChange($('.fee').val());
					var tp_prepay=numChange($('.advance').val());
					var tp_pledge_length=$('.pledge_length').val();
					var tp_pledge_rate=$('.pledge_rate').val();
					if(tp_price==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(tp_price)||tp_price==0){confirmDialog('托盘价格需为大于0的整数或2位小数点内的小数');$('.unit_price').addClass('red-border');return false;}
					if(tp_money==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(tp_money)||tp_money==0){confirmDialog('托盘总金额需为大于0的整数或2位小数点内的小数');$('.fee').addClass('red-border');return false;}
					if(tp_prepay==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(tp_prepay)||tp_prepay==0){confirmDialog('请输入托盘预付款需为大于0的整数或2位小数点内的小数');$('.advance').addClass('red-border');return false;}
					if(tp_pledge_length==''||!/^[1-9][0-9]*$/.test(tp_pledge_length)){confirmDialog('托盘天数需为大于0的整数');$('.pledge_length').addClass('red-border');return false;}
					if(tp_pledge_rate==''||!/^[0-9]+(.[0-9]{1,4})?$/.test(tp_pledge_rate)||tp_pledge_rate==0){confirmDialog('托盘利率需为大于0的4位小数点内的小数');$('.pledge_rate').addClass('red-border');return false;}
				}		
				var havedetail=false;
				goods_arr=new Array();
				$("#cght_tb tbody tr").each(function(){
					var brand=$(this).find(".td_brand").val();
					var product=$(this).find(".td_product").val();
					var texture=$(this).find(".td_texture").val();
					var rank=$(this).find(".td_rank").val();
					var length=$(this).find(".td_length").val();
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
						if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量为整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
						//暂时注释--2016-10-21重新放开
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
		var flag=true;
		//判断更新时间
		var lastupdate='<?php echo $_REQUEST['last_update']?>';
		var id='<?php echo $_REQUEST['id']?>';
		var fpage='<?php echo $_REQUEST['fpage']?>';
		var type='<?php echo $_REQUEST['type']?>';
		var url='/index.php/purchase/update/'+id+'?fpage='+fpage+'&&type='+type;
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
// 				setTimeout('',2300);
// 				window.location.href=url+'&&last_update='+data;
				flag=false;
				return false;
			}
		});		
		if(!flag)return false;		
		var str='';
		var gys = $("#comboval").val();		
		var contact=$('#contact_id').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		var ware=$('.wareinput').val();
		var transfer=$('.transfer').val();
		var cost_price=$('.invoice_cost').val();
		var date_reach=$('#date_reach').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}		
		if(ware==''){confirmDialog('请选择输入仓库');return false;}
		if(contact==''||!contact){confirmDialog("请选择输入联系人！");return false;}
		if(form_time==''){confirmDialog("请选择输入采购日期！");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		if(transfer=='')
		{
			confirmDialog('请输入车船号');return false;
		}
		if(cost_price==''){confirmDialog('请输入开票成本');return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}	
		if(date_reach=='')
		{
			var reach_time=$('.reach_time').val();
			if(reach_time!='0')
			{
				confirmDialog('请选择输入预计到货日期');
				return false;
			}
		}
		var shipment=$('#shipment').val();
		if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
		//tuopan
		var is_tp=$('#is_tp').attr('checked');
		if(is_tp=='checked')
		{
			var tp_price=numChange($('.unit_price').val());
			var tp_money=numChange($('.fee').val());
			var tp_prepay=numChange($('.advance').val());
			var tp_pledge_length=$('.pledge_length').val();
			var tp_pledge_rate=$('.pledge_rate').val();
			if(tp_price==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(tp_price)||tp_price==0){confirmDialog('托盘价格需为大于0的整数或2位小数点内的小数');$('.unit_price').addClass('red-border');return false;}
			if(tp_money==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(tp_money)||tp_money==0){confirmDialog('托盘总金额需为大于0的整数或2位小数点内的小数');$('.fee').addClass('red-border');return false;}
			if(tp_prepay==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(tp_prepay)||tp_prepay==0){confirmDialog('请输入托盘预付款需为大于0的整数或2位小数点内的小数');$('.advance').addClass('red-border');return false;}
			if(tp_pledge_length==''||!/^[1-9][0-9]*$/.test(tp_pledge_length)){confirmDialog('托盘天数需为大于0的整数');$('.pledge_length').addClass('red-border');return false;}
			if(tp_pledge_rate==''||!/^[0-9]+(.[0-9]{1,4})?$/.test(tp_pledge_rate)||tp_pledge_rate==0){confirmDialog('托盘利率需为大于0的4位小数点内的小数');$('.pledge_rate').addClass('red-border');return false;}
		}		
		var havedetail=false;
		goods_arr=new Array();
		$("#cght_tb tbody tr").each(function(){
			var brand=$(this).find(".td_brand").val();
			var product=$(this).find(".td_product").val();
			var texture=$(this).find(".td_texture").val();
			var rank=$(this).find(".td_rank").val();
			var length=$(this).find(".td_length").val();
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
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量为整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
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
});  
$('#is_tp').click(function(){
	var check=$(this).attr('checked');
	if(check)
	{
		$('.tp_hidden').show();
		$('#pledge_company').trigger('change');
	}else{
		$('.tp_hidden').hide();
	}
});
$('.clear_contract').click(function(){
	 selected_contract='';
	$('#frmpurchase_contract').val(' ');
	$('#frmpurchase_contract_hidden').val('');
	$('input').each(function(){$(this).val('')});
	//更改供应商
	$('.supply_div').next().remove();
	var supply_str='<div id="venselect" class="fa_droplist">'
		+'<input type="text" id="combo" value="" />'
		+'<input type="hidden" id="comboval"  value=""  name="FrmPurchase[supply_id]"/>'
		+'</div>';
	$('.supply_div').after(supply_str);
	$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
	//更改抬头
	$('.title_div').next().remove();		
	var title_str='<div id="ywyselect" class="fa_droplist">'
		+'<input type="text" id="combo2" value="" />'
		+'<input type="hidden" id="comboval2" value=""  name="FrmPurchase[title_id]"/>'
		+'</div>';
	$('.title_div').after(title_str);
	$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
	$('#purchase_type').val(purchase_type);
	$('#form_time').val(date_now);
	$('.invoice_cost').val(invoice);
	$('#contact_id').html('<option value=""></option>');
});
		
$('#close_section').mouseenter(function(e){
	$('.clear_contract').show();
});
$('#close_section').mouseleave(function(e){
	$('.clear_contract').hide();
});
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/changeFunction.js"></script>	
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<script>
$(function(){
	$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
	$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2");
	$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
	$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"teamselect","comboval4",false,'changeTeamU()');
	$('.colorbox').click(function(e){
		e.preventDefault();
		var url = $(this).attr('url');
		$.colorbox({
			href:url,
			opacity:0.6,
			iframe:true,
			title:"",
			width: "1000px",
			height: "635px",
			overlayClose: false,
			speed: 0,
			onClosed:function(){initSet();},
		});
	});

	var contact='<?php echo $purchase->contact_id?>';
	var mobile='<?php echo $purchase->contact->mobile;?>';
	var ship2='<?php echo $purchase->shipment?>';
	changeCont();
	$('#contact_id').val(contact);
	$('#phone').val(mobile);
	if(parseFloat(ship2)!=0){$('#shipment').val(ship2);}
})
	var selected_contract='<?php echo $purchase->frm_contract_id?>';
	var template_select='';	
	changeOwnerT();
	//产地品名，材质规格变换函数	
	var from='<?php echo !empty($details)?'contract':''?>';
	if(from=='contract')
	{
		initSet('old');
	}
	var brand_name='';	
	</script>
	