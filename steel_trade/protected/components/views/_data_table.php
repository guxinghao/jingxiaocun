<table class="table datatable" id="<?php echo $data->id; ?>" style="<?php if($data->hide ==1) echo "display:none";?>">
    <thead>
      <tr>
      <?php
     	 $num = 1; 
        foreach ($data->tableHeader as $_header) { ?>
         <th class="<?php echo $_header['class'];?>" style="width:<?php echo $_header['width'];if($num ==1){echo ";border-left:1px solid #989898;";}?>"><?php echo $_header['name'];?></th>
        <?php
		$num++;
        }?>
      </tr>
    </thead>
    <tbody>
    	
    	<?php
    	$rowCss = true;//0:display for whit  1: display for on selected
    	 foreach ($data->tableData as $_data) {
    	 	$rowCss = $_data['group'] == $lastGroup ? $rowCss : !$rowCss;
    	 	$lastGroup = $_data['group'];
    	?>
    	<tr class="<?php echo $rowCss ? 'selected' : 'unselected';?>">
    		<?php 
    		for ($i=0;$i<count($_data['data']);$i++){?>
    		<td style="<?php echo 'width:'.$data->tableHeader[$i]['width'];if($i == 0){echo ";border-left:1px solid #989898;";}?>" class="<?php echo $data->tableHeader[$i]['class'];?>"><?php echo $_data['data'][$i]?></td>
    		<?php }?>
    	</tr>
    	<?php }
    		if($data->totalData){
    	?>
    	<tr class="">
    	<?php
    		$totalData=$data->totalData;
    		for($i=0;$i<count($data->totalData);$i++){
    	?>
    	<td style="<?php echo 'width:'.$data->tableHeader[$i]['width'];if($i == 0){echo ";border-left:1px solid #989898;";}?>" class="<?php echo $data->tableHeader[$i]['class'];?>"><?php echo $totalData[$i];?></td>
    	<?php }?>
    	</tr>
    	<?php } ?>
    </tbody>
  </table>