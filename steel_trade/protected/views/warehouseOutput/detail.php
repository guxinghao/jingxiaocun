<div class="ss_tt_title" style="border-bottom:1px dotted #b1b0b0;">
	<div class="ss_tt_one">
		公司：<?php echo $output->dictTitle->short_name;?>
	</div>
	<div class="ss_tt_one">
		客户：<?php echo $output->dictCompany->short_name;?>
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		出库单号：<?php echo $output->output_no;?>
	</div>
	<div class="ss_tt_one">
		车船号：<?php echo $output->car_no;?>
	</div>
	<div class="ss_tt_one">
		<div style="float:left;">备注：</div>
		<?php echo $output->remark;?>
	</div>
</div>
<div class="create_table">
	<table class="table" id="ps_tb">
		<thead>
			<tr>
         		<th class="text-center" style="width:30%;">出库卡号</th>
         		<th class="text-center" style="width:30%;">品名/规格/材质/产地</th>
         		<th class="text-center" style="width:20%;">件数</th>
         		<th class="text-center" style="width:20%;">重量重量</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			foreach($detail as $li){
			?>
			<tr>
				<td class="text-center card_id"><?php echo $li->card_no;?></td>
				<td class="text-center">
				<?php echo $li->product."/".$li->rank."/".$li->texture."/".$li->brand;?>
				</td>
				<td><?php echo $li->amount;?></td>
				<td><?php echo number_format($li->weight,3);?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
<div class="btn_list">
	<a href="<?php echo Yii::app()->createUrl('warehouseOutput/index',array("page"=>$_GET["fpage"]));?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">返回</button>
	</a>
</div>