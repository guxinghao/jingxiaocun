<table class="table" id="ckck_tb">
		<thead>
			<tr>
				<th class="text-center" style="width:15%;">卡号</th>
         		<th class="text-center" style="width:10%;">产地</th>
         		<th class="text-center" style="width:10%;">品名</th>
         		<th class="text-center" style="width:10%;">材质</th>
         		<th class="text-center" style="width:10%;">规格</th>
         		<th class="text-center" style="width:10%;">长度</th>
         		<th class="text-center" style="width:10%;">可出库件数</th>
         		<th class="text-center" style="width:10%;"><span class="bitian">*</span>出库件数</th>
         		<th class="text-center" style="width:15%;"><span class="bitian">*</span>出库重量</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($detail as $li) {?>
		<tr class="">
			<input type="hidden" name="sales_detail_id[]" value="<?php echo $li->id;?>" />
			<td class="text-center">
				<?php echo $li->storage->card_no;?>
				<input type="hidden" name="card_id[]" value="<?php echo $li->card_no;?>">		
			</td>
			<td class="">
				<input type="text" class="form-control td_place" value="<?php echo $li->brand;?>" readonly>
				<input type="hidden" class="brand" name="brand[]" value="<?php echo $li->brand_id;?>">
			</td>
			<td class="">
				<input type="text" class="form-control td_product" value="<?php echo $li->product;?>" readonly>
				<input type="hidden" class="product" name="product[]" value="<?php echo $li->product_id;?>">
			</td>
			<td class="">
				<input type="text" class="form-control td_material" value="<?php echo $li->texture;?>" readonly>
				<input type="hidden" class="texture" name="texture[]" value="<?php echo $li->texture_id;?>">
			</td>
			<td class="">
				<input type="text" class="form-control td_type"  value="<?php echo $li->rank;?>" readonly>
				<input type="hidden" class="rank" name="rank[]" value="<?php echo $li->rank_id;?>">
			</td>
			<td class=""><input type="text" class="form-control td_length" name="length[]" value="<?php echo $li->length;?>" readonly></td>
			<td class=""><input type="text" class="form-control td_surplus" value="<?php echo $li->return_amount-$li->output_amount;?>" readonly></td>
			<td>
				<input type="text" class="form-control td_amount"  name="amount[]"  value="">
				<input type="hidden" class="td_one_weight" value="<?php echo $li->one_weight;?>">
			</td>
			<td><input type="text" class="form-control td_weight"  name="weight[]" value=""></td>
		</tr>
		<?php }?>
		</tbody>
	</table>