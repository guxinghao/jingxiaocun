<style>
a{cursor:pointer}
#isearch input{line-height:16px}
</style>
<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',) 
) );
?>
<div class="search_body">
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['start_time']?>" name="search[start_time]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['end_time']?>" name="search[end_time]"  >
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">仓　库：</div>
		<div id="comselect_ck" class="fa_droplist">
			<input type="text" id="combo_ck" class="forreset" value="<?php echo Warehouse::getName($search['warehouse_id']);?>" />
			<input type='hidden' id='comboval_ck' value="<?php echo $search['warehouse_id'];?>"  class="forreset" name="search[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">品　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo DictGoodsProperty::getProName($search['product_id']);?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search['product_id'];?>"  class="forreset" name="search[product_id]"/>
		</div>
	</div>
	
<div class="more_select_box" style="top:80px;left:600px;width:500px;">
	<div class="more_one">
		<div class="more_one_l">甲乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset'>
	            <option value='-1' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['is_yidan']=="1"?'selected="selected"':''?>  value="1">乙单</option>
           		 <option <?php echo $search['is_yidan']=="0"?'selected="selected"':''?>  value="0">甲单</option>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">入库类型：</div>
		 <select name="search[type]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['type']=="purchase"?'selected="selected"':''?>  value="purchase">采购入库</option>
           		 <option <?php echo $search['type']=="thrk"?'selected="selected"':''?>  value="thrk">销售退货</option>
	        </select>
	</div>	
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php $this->endWidget ();?>					
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:350,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>			
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  <script type="text/javascript">	
	$(function(){
		var array_product=<?php echo $products;?>;
		//var array_texture=<?php echo $textures;?>;
		//var array_rank=<?php echo $ranks;?>;
		//var array_brand=<?php echo $brands;?>;
		var array_ck = <?php echo $warehouse;?>;
		$('#combo_product').combobox(array_product, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_p","comboval_product",false);
		//$('#combo_texture').combobox(array_texture, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_texture");
		//$('#combo_rank').combobox(array_rank, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_r","comboval_rank",false);
		//$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
		$('#combo_ck').combobox(array_ck, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_ck","comboval_ck",false);
	})
</script>