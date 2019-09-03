<?php 
$status = array("未出库","已出库","已作废");
?>
<table class="detail_table">
	<tbody>
		<tr>
			<?php if($model->is_return == 1){?>
			<td style="width:13%;" class="detail_table_backg">退货单号</td><td style="width:20.3%;"><?php echo $model->frmreturn->baseform->form_sn;?></td>
			<td style="width:13%;" class="detail_table_backg">公司</td><td style="width:20.3%;"><?php echo $model->frmreturn->dictTitle->short_name;?></td>
			<td style="width:13%;" class="detail_table_backg">供应商</td><td style="width:20.3%;"><span title="<?php echo $model->frmreturn->supply->name;?>"><?php echo $model->frmreturn->supply->short_name;?></span></td>
			<?php }else{?>
			<td style="width:13%;" class="detail_table_backg">销售单号</td><td style="width:20.3%;"><?php echo $sales->baseform->form_sn;?></td>
			<td style="width:13%;" class="detail_table_backg">公司</td><td style="width:20.3%;"><?php echo $sales->dictTitle->short_name;?></td>
			<td style="width:13%;" class="detail_table_backg">结算单位</td><td style="width:20.3%;"><span title="<?php echo $sales->dictCompany->name;?>"><?php echo $sales->dictCompany->short_name;?></span></td>
			<?php }?>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">出库单号</td><td style="width:20.3%;"><?php echo $baseform->form_sn;?></td>
			<td style="width:13%;" class="detail_table_backg">仓库</td><td style="width:20.3%;"><?php echo $sales->warehouse->name;?></td>
			<td style="width:13%;" class="detail_table_backg">创建时间</td><td style="width:20.3%;"><?php echo $baseform->form_time;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">状态</td><td style="width:20.3%;"><span class="red"><?php echo $status[$model->input_status];?></span></td>
			<td style="width:13%;" class="detail_table_backg">出库人</td><td style="width:20.3%;"><?php echo $model->outputby->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">出库时间</td><td style="width:20.3%;"><?php echo $model->output_at>0?date("Y-m-d",$model->output_at):"";?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">制单人</td><td style="width:20.3%;"><?php echo $baseform->operator->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">审核人</td><td style="width:20.3%;"><?php echo $baseform->approver->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">最后操作人</td><td style="width:20.3%;"><?php echo $baseform->lastupdate->nickname;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update > 0?date("Y-m-d",$baseform->last_update):"";?></td>
			<td style="width:13%;" class="detail_table_backg">备注</td>
			<td style="width:20.3%;">
			<?php 
				$text =  htmlspecialchars($baseform->comment);
				$length = mb_strlen($text,"UTF-8");
				if($length > 15){
					$text_sub = mb_substr($text,0,15,"UTF-8");
					echo '<div class="comment_text">'.$text_sub.'<img src="/images/nummore.png" style="margin:-2px 0 0 3px;">';
					echo '<div class="comment_text_close">x</div>';
					echo '<div class="comment_text_t">'.$text.'</div>';
					echo '</div>';
				}else{
					echo $text;
				}
			?>
			</td>
			<td style="width:13%;" class="detail_table_backg"></td><td style="width:20.3%;"></td>
		</tr>
	</tbody>
</table>
<div class="create_table">
	<table class="table" id="ckck_tb">
		<thead>
			<tr>
         		<th class="" style="width:15%;">卡号</th>
         		<th class="" style="width:15%;">产地</th>
         		<th class="" style="width:10%;">品名</th>
         		<th class="" style="width:10%;">材质</th>
         		<th class="" style="width:10%;">规格</th>
         		<th class="text-right" style="width:10%;">长度</th>
         		<th class="text-right" style="width:15%;">出库件数</th>
         		<th class="text-right" style="width:15%;">出库重量</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = 0;
			foreach($detail as $li){
			$num++;
			?>
			<tr>
				<td class="card_id"><?php echo $li->storage->card_no;?></td>
				<td class=""><?php echo $li->brand;?></td>
	    		<td class=""><?php echo $li->product;?></td>
	    		<td class=""><?php echo str_replace('E','<span class="red">E</span>',$li->texture);?></td>
	    		<td class=""><?php echo $li->rank;?></td>
	    		<td class="text-right"><?php echo $li->length;?></td>
				<td class="text-right"><?php echo $li->amount;?></td>
				<td class="text-right"><?php echo number_format($li->weight,3);?></td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<div class="btn_list">
	<a href="<?php echo Yii::app()->createUrl('frmOutput/index',array("id"=>$_GET["sid"],"page"=>$_GET["fpage"]));?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">返回</button>
	</a>
</div>
<script>
	$("body").bind("click",function(){
		$(".car_no_list_close").hide();
		$(".car_no_list").hide();
		$(".comment_text_close").hide();
		$(".comment_text_t").hide();
	})
	//点击车牌号
	$(document).on("click",".car_no",function(){
		$(".car_no_list_close").show();
		$(".car_no_list").show();
		$(".comment_text_close").hide();
		$(".comment_text_t").hide();
	});
//点击备注
	$(document).on("click",".comment_text",function(){
		$(".comment_text_close").show();
		$(".comment_text_t").show();
		$(".car_no_list_close").hide();
		$(".car_no_list").hide();
	});
	//点击关闭
	$(document).on("click",".car_no_list_close",function(e){
		$(this).hide();
		$(this).parent().find(".car_no_list").hide();
		e.stopPropagation();    //  阻止事件冒泡
	});
	//点击关闭
	$(document).on("click",".comment_text_close",function(e){
		$(this).hide();
		$(this).parent().find(".car_no_list").hide();
		e.stopPropagation();    //  阻止事件冒泡
	});
</script>