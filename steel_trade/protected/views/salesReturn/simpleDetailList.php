<?php 
$i=1;
foreach ($data as $each)
{	if($each->return_amount-$each->input_amount<=0)continue;
?>
<tr class="<?php echo $i%2==0?"selected":""?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $each->brand_id?>" class="form-control td_brand yes">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $each->id;?>"  class="detail_id"/>
    			<input type="hidden"  name="td_products[]" value="<?php echo $each->product_id?>" class="form-control td_product yes">
	    		<input type="text" readonly name="td_products_name[]" value="<?php echo DictGoodsProperty::getProName($each->product_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $each->texture_id?>" class="form-control td_texture yes">
	    		<input type="text" readonly name="td_textures_name[]" value="<?php echo DictGoodsProperty::getProName($each->texture_id)?>" class="form-control">
    		</td>   
    		<td class="">
    			<input type="hidden" name="td_ranks[]" value="<?php echo $each->rank_id?>" class="form-control td_rank yes">
	    		<input type="text" readonly name="td_ranks_name[]" value="<?php echo DictGoodsProperty::getProName($each->rank_id)?>" class="form-control">
    		</td>    		 		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $each->length;?>" class="form-control td_length"  ></td>
			<td><input type="text" name="td_card_id[]"  value="" class="form-control td_card"></td>
			<td >
    			<input type="text" readonly name="" style="" value="<?php echo $each->return_amount-$each->input_amount;?>" class="form-control  " >
    		</td>
    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $each->return_amount-$each->input_amount;?>" class="form-control td_amount td_num"  >
    			<input type="hidden"  value="<?php echo $each->return_amount-$each->input_amount;?>" class="form-control  td_max_num" >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($each->return_weight-$each->input_weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $each->return_weight-$each->input_weight;?>" style="" class="form-control td_total_weight " >
    		</td>
    		<td ><input type="text"  name="td_price[]"  style="" readonly    value="<?php echo $each->return_price;?>" class="form-control td_price" ></td>
    	</tr>
<?php $i++;}?>