<table class="table"  id="cght_tb">
    	<thead>
     		<tr>
         		<th class="text-center" ></th>
         		<th class="text-center" ><input type="checkbox" class="checkAll"></th>
         		<th class="text-left" >业务员</th>
         		<th class="text-right" >预估销售重量</th>
         		<th class="text-right" >预估销售提成</th>
         		<th class="text-left" ><span class="bitian">*</span>提成</th>
      		</tr>
    	</thead>
    	<tbody>
    	<?php 
    		$num = 0;
    		foreach ($data as $li){
    			$num ++;
    	?>
    		<tr class="<?php if($num%2 == 0) echo "selected"?>">
    		<input type="hidden" name="userid[]" value="<?php echo $li["id"];?>">
    		<td class="text-center list_num"><?php echo $num;?></td>
    		<td class="text-center">
    			<input type="checkbox" class="checkone" value="1" <?php if($li['money']>0){echo "checked";}?>>
    			<input type="hidden" class="check" name="check[]" value="<?php if($li['money']>0){echo 1;}else{echo 0;}?>">
    		</td>
    		<td class=""><span class="name"><?php echo $li["name"];?></span></td>
    		<td class="text-right">
    			<?php echo number_format($li["yg_weight"],3);?>
    			<input type="hidden" class="form-control" name="weight[]" value="<?php echo $li["yg_weight"];?>">
    		</td>
    		<td class="text-right"><?php echo number_format($li["yg_money"],2);?></td>
    		<td class=""><input type="text" class="form-control td_money" name="money[]" value="<?php echo $li['money']>0?number_format($li['money'],2):"";?>"></td>
    		</tr>
    	<?php }?>
    	</tbody>
</table>
<input type="hidden" id="tr_num" value="<?php echo $num;?>">