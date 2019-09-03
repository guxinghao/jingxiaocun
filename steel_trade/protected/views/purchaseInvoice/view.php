<style>
<!--
#cght_tb tbody td{ line-height: 30px;}
.detail_table_backg{ width: 13%;}
.detail_table_backg + td{ width: 20.3%;}
.table > thead > tr > th{ border-bottom: none;}
.create_table thead th{ border-bottom: none;}
-->
</style>
<?php $baseform = $model->baseform;?>
<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg">收票单位</td><td><span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span></td>
			<td class="detail_table_backg">开票单位</td><td><span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span></td>
			<td class="detail_table_backg">销票重量</td><td><?php echo number_format($model->weight, 3);?>吨</td>
		</tr>
		<tr>
			<td class="detail_table_backg">业务员</td><td><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg">业务组</td><td><?php echo $baseform->belong->team->name;?></td>
			<td class="detail_table_backg">销票金额</td><td><?php echo number_format($model->fee, 2);?>元</td>
		</tr>
		<tr>
			<td class="detail_table_backg">状态</td><td class="red"><?php echo CommonForms::$formStatus[$baseform->form_status];?></td>
			<td class="detail_table_backg">销票张数</td><td><?php echo number_format($model->capias_amount > 0 ? $model->capias_amount : 1);?></td>
			<td class="detail_table_backg">票号</td><td><?php echo $model->capias_number;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
			<td class="detail_table_backg">审核人</td><td><?php echo $baseform->approver->nickname;?></td>
			<td class="detail_table_backg">最后操作人</td><td><?php echo $baseform->lastupdate->nickname;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">最后更新时间</td><td><?php echo $baseform->last_update > 0 ? date('Y-m-d H:i:s', $baseform->last_update) : '';?></td>
			<td class="detail_table_backg">备注</td>
			<td>
			<?php $text =  htmlspecialchars($baseform->comment);
				$length = mb_strlen($text,"UTF-8");
				if ($length > 15) 
				{
					$text_sub = mb_substr($text,0,15,"UTF-8");
					echo '<div class="comment_text">'.$text_sub.'<img src="/images/nummore.png" style="margin:-2px 0 0 3px;">';
					echo '<div class="comment_text_close">x</div>';
					echo '<div class="comment_text_t">'.$text.'</div>';
					echo '</div>';
				} 
				else 
				{
					echo $text;
				}
			?>
			</td>
			<td class="detail_table_backg"></td><td></td>
		</tr>
	</tbody>
</table>

<div class="create_table">
	<table id="cght_tb" class="table" style="display: none;">
		<thead>
			<tr>
				<th style="width: 40px;"></th>
				<th class="flex-col" style="width: 150px;">单号</th>
				<th class="flex-col" style="width: 150px;">公司</th>
				<th class="flex-col" style="width: 150px;">客户</th>
				<th class="flex-col" style="width: 260px;">产地/品名/材质/规格/长度</th>
				<th class="flex-col text-right" style="width: 120px;">销票重量</th>
				<th class="flex-col text-right" style="width: 120px;">销票金额</th>
				<th class="flex-col text-right" style="width: 120px;">可销票重量</th>
				<th class="flex-col text-right" style="width: 120px;">可销票金额</th>
				<!-- <th class="flex-col" style="width: 120px;">单据类型</th> -->
			</tr>
		</thead>
		
		<tbody>
		<?php if ($details) { 
			$tr_num = 0; 
			foreach ($details as $item) { 
				$tr_num++;?>
			<tr>
				<td class="text-center list_num" style="width: 40px;"><?php echo $tr_num;?></td>
				<td class="flex-col" style="width: 150px;"><?php echo $item->form_sn;?></td>
				<td class="flex-col" style="width: 150px;"><span title="<?php echo $item->title->name;?>"><?php echo $item->title->short_name;?></span></td>
				<td class="flex-col" style="width: 150px;"><span title="<?php echo $item->company->name;?>"><?php echo $item->company->name;?></span></td>
				<td class="flex-col" style="width: 260px;"><?php echo $item->brand.'/'.$item->product_name.'/'.str_replace('E', '<span class="red">E</span>', $item->texture).'/'.$item->rank.'/'.$item->length;?></td>
				<td class="flex-col text-right" style="width: 120px;"><?php echo number_format($item->weight, 3);?></td>
				<td class="flex-col text-right" style="width: 120px;"><?php echo number_format($item->fee, 2);?></td>
				<td class="flex-col text-right" style="width: 120px;"><?php echo number_format($item->needWeight + $item->weight, 3);?></td>
				<td class="flex-col text-right" style="width: 120px;"><?php echo number_format($item->needMoney + $item->fee, 2);?></td>
				<!-- <td class="flex-col" style="width: 120px;"><?php #echo $item->type;?></td> -->
			</tr>
		<?php } }?>
		</tbody>
	</table>
</div>

<div class="btn_list">
	<a href="<?php echo $back_url;?>">
		<button id="cancel" class="btn btn-primary btn-sm" data-dismiss="modal">返回</button>
	</a>
</div>

<script type="text/javascript">
<!--
$(function(){
	$("#cght_tb").datatable({
		fixedLeftWidth: 41, 
		fixedRightWidth: 0,
	});

	$("body").bind("click", function(){
 		$(".comment_text_close").hide();
 		$(".comment_text_t").hide();
	});

	//点击备注
  	$(document).on("click", ".comment_text", function(){
  	  	$(".comment_text_close").show();
  		$(".comment_text_t").show();
  		$(".car_no_list_close").hide();
 		$(".car_no_list").hide();
  	});

  	//点击关闭
 	$(document).on("click", ".comment_text_close", function(e){
 	 	$(this).hide();
 	 	$(this).parent().find(".car_no_list").hide();
 	 	e.stopPropagation();    //  阻止事件冒泡
 	});
});
//-->
</script>
