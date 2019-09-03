<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入卡号" class="forreset" value="<?php echo $search["card_no"]?>" name="search[card_no]" id="search_card_no">
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">销售公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search["title_id"]);?>" />
			<input type='hidden' id='combovaltt' value="<?php echo $search["title_id"];?>"  class="forreset" name="search[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">仓　库：</div>
		<div id="comselect_ck" class="fa_droplist">
			<input type="text" id="combo_ck" class="forreset" value="<?php echo Warehouse::getName($search["warehouse_id"]);?>" />
			<input type='hidden' id='comboval_ck' value="<?php echo $search["warehouse_id"];?>"  class="forreset" name="search[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">产　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search["brand_name"];?>" name="search[brand_name]"/>
			<input type='hidden' id='comboval_brand' value="<?php echo $search["brand"];?>"  class="forreset" name="search[brand]"/>
		</div>
	</div>
	
	<div class="more_select_box" style="top:90px;left:280px">
	<div class="more_one">
		<div class="more_one_l">品　　名：</div>
		<select name="search[product]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
       </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材　　质：</div>
		<select name="search[texture]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规　　格：</div>
		<select name="search[rank]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($ranks as $k=>$v){?>
            	 <option <?php echo $k==$search['rank']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">状　　态：</div>
		<select name="search[status]" class='form-control chosen-select forreset'>
            <option <?php echo $search['status']==1?'selected="selected"':''?>  value="1">正常</option>
            <option <?php echo $search['status']==2?'selected="selected"':''?>  value="2">已删除</option>
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
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			//'totalData' =>$totalData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:90,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "stock_list")?>
 <script type="text/javascript">	
	$(function(){
		var array_brand=<?php echo $brands;?>;
		var array_ck = <?php echo $warehouse;?>;
		var array_tt=<?php echo $titles;?>;
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
		$('#combo_ck').combobox(array_ck, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_ck","comboval_ck",false);
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
	})
</script>
 