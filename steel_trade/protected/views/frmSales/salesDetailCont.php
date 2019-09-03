    	<tr class="">
    		<td class="text-center list_num"></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td><?php echo $data->FrmSales->baseform->form_sn;?><input type="hidden" name="td_form_sn[]" value="<?php echo $data->FrmSales->baseform->form_sn;?>"/></td>
    		<td >
    				<input type="text"  readonly style="" name="td_sell_name[]" value="<?php echo $frmSale->dictTitle->short_name;?>" class="form-control "  >
    				<input type="hidden"  readonly style="" name="td_sell[]" value="<?php echo $frmSale->title_id;?>" class="form-control td_sell"  >
    		</td>
    		<td >
    				<input type="text"  readonly style="" name="td_owner_name[]" value="<?php echo $frmSale->dictOwner->short_name;?>" class="form-control td_sell"  >
    				<input type="hidden"  readonly style="" name="td_owner[]" value="<?php echo $frmSale->owner_company_id;?>" class="form-control td_owner"  >
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $data->brand_id?>" class="form-control td_brand yes">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo DictGoodsProperty::getProName($data->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_id[]" value="<?php echo $data->id;?>"  class="detail_id"/>
    			<input type="hidden"  name="td_products[]" value="<?php echo $data->product_id?>" class="form-control td_product yes">
	    		<input type="text" readonly name="td_products_name[]" value="<?php echo DictGoodsProperty::getProName($data->product_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_textures[]" value="<?php echo $data->texture_id?>" class="form-control td_texture yes">
	    		<input type="text" readonly name="td_textures_name[]" value="<?php echo DictGoodsProperty::getProName($data->texture_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  name="td_ranks[]" value="<?php echo $data->rank_id?>" class="form-control td_rank yes">
	    		<input type="text" readonly name="td_ranks_name[]" value="<?php echo DictGoodsProperty::getProName($data->rank_id)?>" class="form-control">
    		</td>    		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $data->length;?>" class="form-control td_length"  ></td>

    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $data->amount-$data->purchased_amount;?>" class="form-control td_amount td_num"  >
    			<input type="hidden" name="td_max_amount[]" style="" value="<?php echo $data->amount-$data->purchased_amount;?>" class="form-control  td_max_num"  >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($data->weight-$data->purchased_weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $data->weight-$data->purchased_weight;?>" style="" class="form-control td_total_weight " >
    		</td>
    		<td ><input type="text"  name="td_price[]" style="" value="" class="form-control td_price" ></td>
    		<td >
    		<input type="text"  name="td_totalMoney[]" readonly  value=""  class="form-control td_money" >
    		<input type="hidden" name="good_id[]"  class="good_id" value="<?php echo $good_id;?>">    			
    		</td>
    		<td> <input type="text" name="td_invoice[]" class="form-control td_invoice" value="<?php echo FrmPurchase::getInvoice($data->brand_id);?>"></td>
    	</tr>