<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<table class="table" id="ps_tb">
		<thead>
			<tr>
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:20%;">产地/品名/材质/规格/长度</th>
         		<th class="text-center" style="width:15%;">总件数</th>
         		<th class="text-center" style="width:15%;">可配送件数</th>
         		<th class="text-center" style="width:15%;">配送件数</th>
         		<th class="text-center" style="width:15%;">预计到货日期</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = 0;
			foreach($salesDetails as $li){
			$num++;
			$t_amount += $li->amount;
			$t_can += ($li->amount-$li->send_amount);
			?>
			<tr>
				<input type="hidden" name="sales_detail_id[]" value="<?php echo $li->id;?>">
				<input type="hidden" name="product[]" value="<?php echo $li->product_id;?>"/>
				<input type="hidden" name="rank[]" value="<?php echo $li->rank_id;?>"/>
				<input type="hidden" name="brand[]" value="<?php echo $li->brand_id;?>"/>
				<input type="hidden" name="texture[]" value="<?php echo $li->texture_id;?>"/>
				<input type="hidden" name="length[]" value="<?php echo $li->length;?>"/>
				<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
				<td class="text-center">
				<span class="card_type">
				<?php echo $li->brand."/".$li->product."/".$li->texture."/".$li->rank."/".$li->length;?>
				</span>
				</td>
				<td><input type="text" class="form-control td_shop_num"  name="td_total[]" readonly value="<?php echo $li->amount;?>"></td>
			<td>
						<input type="hidden"  class=" td_can_num"  name="td_can[]"  value="<?php echo $li->amount-$li->send_amount;?>">
						<div class="click_can" style="width:100%;text-align:center;color:blue;font-weight:bold;"><?php echo $li->amount-$li->send_amount;?></div>
				</td>
				<td>
					<input type="text" class="form-control  td_amount"  name="amount[]" value="">
					<input type="hidden" class="one_weight" value="<?php echo $li->one_weight;?>">
					<input type="hidden" class="form-control td_shop_num td_weight"  name="weight[]">
				</td>
				<td class="text-center"><span class="td_date"><?php if($li->mergestorage->is_transit == 1){echo date("Y-m-d",$li->mergestorage->pre_input_date);}?></span></td>
			</tr>
			<?php }?>
			<tr>
				<td class="text-center">合计：</td><td></td>
				<td><span class="total_amount"><?php echo $t_amount;?></span></td>
				<td><span class="total_can"><?php echo $t_can;?></span></td>
				<td><span class="total-num"></span></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<script>
		$(function(){
			//可开件数点击事件
			//可开件数点击事件
			$(".click_can").on("click",function(){
					var value = $(this).parent().find(".td_can_num").val();
					$(this).parent().parent().find(".td_amount").val(value);
			});
		})
	</script>