

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
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $model->name?>" name="DictGoods[name]">
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">品　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $model->product->short_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $model->product_id;?>"  class="forreset" name="DictGoods[product_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">材　质：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_texture" class="forreset" value="<?php echo $model->texture->short_name;?>" />
			<input type='hidden' id='comboval_texture' value="<?php echo $model->texture_id;?>"  class="forreset" name="DictGoods[texture_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">规　格：</div>
		<div id="comselect_r" class="fa_droplist">
			<input type="text" id="combo_rank" class="forreset" value="<?php echo $model->rank->short_name;?>" />
			<input type='hidden' id='comboval_rank' value="<?php echo $model->rank_id;?>"  class="forreset" name="DictGoods[rank_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">产　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $model->brand1->short_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $model->brand_id;?>"  class="forreset" name="DictGoods[brand_id]"/>
		</div>
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="100" class="table_cell_first">商品名称</th>
				<th width="100" >简称</th>
				<th width="100" class="rightAlign">件重</th>
				<th width="50" class="rightAlign">长度</th>
				<th width="70" >品名</th>
				<th width="70" >产地</th>
				<th width="60" >材质</th>
				<th width="50" >规格</th>
				<th width="100" >更新时间</th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 0; 
		foreach ($items as $item) {?>
			<tr id="<?php echo $item->id;?>" class="data"<?php echo $i % 2 == 1 ? ' style="background-color: #f4f4f4;"' : '';?>>
				<td class="table_cell_first"><?php echo str_replace('E','<span class="red">E</span>', $item->name);?></td>
				<td><?php echo str_replace('E','<span class="red">E</span>',$item->short_name);?></td>
				<td class="rightAlign"><?php echo number_format($item->unit_weight, 3);?></td>
				<td class="rightAlign"><?php echo $item->length;?></td>
				<td><?php echo $item->product_name;?></td>
				<td><?php echo $item->brand_name;?></td>
				<td><?php echo $item->texture_name;?></td>
				<td><?php echo $item->rank_name;?></td>	
				<td><?php echo date("Y-m-d H:i:s", $item->last_update);?></td>		
			</tr>
		<?php $i++; }?>
		</tbody>
		
	</table>
	
</div>			
<?php paginate($pages,"d_goods")?>
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