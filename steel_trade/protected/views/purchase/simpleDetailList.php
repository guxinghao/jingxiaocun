<?php 
$i=1;
foreach ($data as $each)
{	if($each->amount-$each->input_amount<=0)continue;
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
    		<?php // if($from!='plan'&&$from!='ccrk'){?>
			<td style="display:<?php echo $from!='plan'&&$from!='ccrk'?'':'none'?>"><input type="text" name="td_card_id[]"  value="" class="form-control td_card"></td>
			<?php //}?>
			<td >
    			<input type="text" readonly name="" style="" value="<?php echo $each->amount-$each->input_amount;?>" class="form-control  " >
    		</td>
    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $each->amount-$each->input_amount;?>" class="form-control td_amount td_num"  >
    			<input type="hidden"  value="<?php echo $each->amount-$each->input_amount;?>" class="form-control  td_max_num" >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($each->weight-$each->input_weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $each->weight-$each->input_weight;?>" style="" class="form-control td_total_weight " >
    		</td>
    		<td style="display:<?php echo $_REQUEST['type']=='ccrk'||$from=='ccrk'?'':'none'?>">
    			<input type="text"  name="td_remain_amount[]" style="" value="" class="form-control  td_remain_num" >
    		</td>
    		<td style="display:<?php echo $_REQUEST['type']=='ccrk'||$from=='ccrk'?'':'none'?>">
    			<input type="text"  name="td_remain_weight[]" style="" value="" class="form-control  td_remain_weight" >
    		</td>
    		<?php // if($from!='plan'){?>
    		<td style="display:<?php echo $from!='plan'?'':'none'?>"><input type="text"  name="td_price[]" readonly style="" value="<?php echo $each->price;?>" class="form-control td_price" ></td>
    		<?php //}?>
    	</tr>
<?php $i++;}?>