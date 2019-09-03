<?php if($date_type=="yes"){?>
<div class="con_tit">
<!--  	<div class="con_tit_jrbjd" onclick="window.open('<?php  echo Yii::app()->createUrl('purchasePrice/report',array('page'=>$_REQUEST['page']))?>');">
		<img src="<?php echo imgUrl('today_bjd.png');?>">今日报价单
	</div>
-->
	<div class="" style="float:right;line-height:40px;font-size:14px;margin-right:20px;">
	最后报价:<span style="color:#035eed"><?php echo $last_update_user,'&nbsp;&nbsp;',$update_time?date('Y-m-d H:i:s',$update_time):'';?></span> 
	</div>
</div>
<?php }?>
<style>
a{cursor:pointer}
#isearch input{line-height:16px}
</style>
<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<?php if($date_type=="yes"){?>
<div class="search_body">
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">产　　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="PurchasePrice[brand_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="PurchasePrice[product_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">材　　质：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
			<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="PurchasePrice[texture_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;" >规　　格：</div>
		<div id="comselect_r" class="fa_droplist">
			<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
			<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="PurchasePrice[rank_id]"/>
		</div>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<!-- 	<div class="more_toggle" title="更多"></div> -->
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php }else{?>
<div class="search_body">
	<div class="search_date" style="width:390px">
		<div style="float:left">报价日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $_POST['start_time']?>" name="start_time">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $_POST['end_time']?$_POST['end_time']:date('Y-m-d');?>" name="end_time"  >
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">产　　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="PurchasePrice[brand_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="PurchasePrice[product_id]"/>
		</div>
	</div>
	<div class="more_select_box" style="top:90px;left:580px;width:500px">
		<div class="more_one">
			<div class="more_one_l"  >材　　质：</div>
			<div id="comselect_t" class="fa_droplist">
				<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
				<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="PurchasePrice[texture_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">规　　格：</div>
			<div id="comselect_r" class="fa_droplist">
				<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
				<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="PurchasePrice[rank_id]"/>
			</div>
		</div>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php }?>
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
    	 fixedLeftWidth:0,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>	
<?php if($data_type!='yes')paginate($pages,"qd")?>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  
  <script type="text/javascript">	  
	$(function(){
		var array_product=<?php echo $products;?>;
		var array_texture=<?php echo $textures;?>;
		var array_rank=<?php echo $ranks;?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo_product').combobox(array_product, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_p","comboval_product",false);
		$('#combo_texture').combobox(array_texture, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_texture");
		$('#combo_rank').combobox(array_rank, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_r","comboval_rank",false);
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
	})
</script>