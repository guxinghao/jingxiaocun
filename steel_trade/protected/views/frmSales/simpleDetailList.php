
<?php 
$i=1;
foreach ($data as $each)
{	if($each->need_purchase_amount-$each->purchased_amount<=0)continue;
?>
<tr class="<?php echo $i%2==0?"selected":""?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    	    <td class="">
    			<div id="<?php echo "bbbrandselect".$i;?>" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">
					<input type="text" id="<?php echo "bbcombobrand".$i?>" style="width:130px;"  value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" />
					<input type='hidden' id='<?php echo "bbcombovalbrand".$i?>' value="<?php echo $each->brand_id?>"   name="td_brands[]" class="td_brand"/>
				</div>
	    		<script type="text/javascript">
	    		var array_brand=<?php echo $brands;?>;
	    		$('#bbcombobrand<?php echo $i?>').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"bbbrandselect<?php echo $i?>","bbcombovalbrand<?php echo $i?>",false,'brandChange(obj)');
	    		</script>	
    		</td>
    		<td class="">
    			<select name='td_products[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<?php foreach($products as $k=>$v){?>
	            	<option <?php echo $each->product_id==$k?'selected="selected"':"";?>  value="<?php echo $k?>"><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="td_textures[]" class='form-control chosen-select td_texture' onchange="textureChange(this)">
	            <?php foreach($textures as $k=>$v){?>
	            	<option <?php echo $each->texture_id==$k?'selected="selected"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="td_ranks[]" class='form-control chosen-select td_rank' onchange="rankChange(this)">
	            <?php foreach($ranks as $k=>$v){?>
	            	<option <?php echo $each->rank_id==$k?'selected="selected"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class=""><input type="text"  name="td_length[]" style="" value="<?php echo $each->length;?>" class="form-control td_length" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $each->need_purchase_amount-$each->purchased_amount;?>" class="form-control td_num" placeholder=""  >
    			<input type="hidden"  name="" style="" value="<?php echo $each->need_purchase_amount-$each->purchased_amount;?>" class="form-control td_max_num" placeholder=""  >
    		</td>
    		<td class="">
    			<input type="text"  name=""  value="<?php echo round($each->weight-$each->purchased_weight,3)?>" style="" class="form-control  td_weight" placeholder=""  >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $each->weight-$each->purchased_weight?>" style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class="">
    			<input type="text"  name="td_price[]" style="" value="<?php echo round($each->price,2)?>" class="form-control td_price" placeholder=""  >
    			<input type="hidden" name="td_id[]" value="<?php echo $each->id?>"/>
    		</td>
    		<td class=""><input type="text"  name="td_totalMoney[]"  value="<?php echo number_format(($each->weight-$each->purchased_weight)*$each->price,2);?>" style="" class="form-control td_money" placeholder=""  ></td>
    		<td><input type="text" name="td_invoice[]" class="form-control td_invoice"></td>
    	</tr>
<?php $i++;}?>