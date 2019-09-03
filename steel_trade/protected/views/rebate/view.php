<style>
#cght_tb tbody td{ line-height: 26px;}
.detail_table_backg{ width: 13%;}
.detail_table_backg + td{ width: 20.3%;}
.table > thead > tr > th{ border-bottom: none;}
.create_table thead th{ border-bottom: none;}
</style>

<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg">结算单位</td><td><span title="<?php echo $model->company->name;?>"><?php echo $model->company->short_name;?></span></td>
			<td class="detail_table_backg">公司</td><td><span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span></td>
			<td class="detail_table_backg">折让类型</td><td><?php echo FrmRebate::$type[$model->type];?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">折让金额</td><td><?php echo number_format($model->amount, 2);?>元</td>
			<td class="detail_table_backg">业务员</td><td><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg">业务组</td><td><?php echo $baseform->belong->team->name;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">状态</td><td class="red"><?php echo CommonForms::$formStatus[$baseform->form_status];?></td>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
			<td class="detail_table_backg">审核人</td><td><?php echo $baseform->approver->nickname;?></td>		
		</tr>
		<tr>
			<td class="detail_table_backg">最后操作人</td><td><?php echo $baseform->lastupdate->nickname;?></td>
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
		</tr>
		<tr>
			<td class="detail_table_backg">客户</td><td><span title="<?php echo $model->client->name;?>"><?php echo $model->client->short_name;?></span></td>
			<td class="detail_table_backg"></td><td></td>
			<td class="detail_table_backg"></td><td></td>
		</tr>
<!-- 
		<tr>
			<td class="detail_table_backg">起始时间</td><td><?php echo $model->start_at > 0 ? date('Y-m-d', $model->start_at) : '';?></td>
			<td class="detail_table_backg">结束时间</td><td><?php echo $model->start_at > 0 ? date('Y-m-d', $model->start_at) : '';?></td>
			<td class="detail_table_backg"></td><td></td>		
		</tr>
 -->
	</tbody>
</table>
<?php if ($model->type == 'sale') {?>
<div class="create_table">
	<table id="cght_tb" class="table" style="display: none;">
		<thead>
			<tr>
				<th style="width: 40px;"></th>
				<th class="flex-col" style="width: 150px;">销售单号</th>
				<th class="flex-col" style="width: 150px;">开单日期</th>
				<th class="flex-col" style="width: 150px;">结算单位</th>
				<th class="flex-col" style="width: 150px;">公司</th>
				<th class="flex-col text-right" style="width: 110px;">总重量</th>
				<th class="flex-col text-right" style="width: 110px;">总件数</th>
				<th class="flex-col text-right" style="width: 110px;">未完成重量</th>
				<th class="flex-col text-right" style="width: 110px;">未完成件数</th>
				<th class="flex-col" style="width: 100px;">销售类型</th>
				<th class="flex-col" style="width: 100px;">业务组</th>
				<th class="flex-col" style="width: 100px;">销售员</th>
				<th class="flex-col" style="width: 150px;">客户</th>
			</tr>
		</thead>
		
		<tbody>
		<?php if ($details) { 
			$tr_num = 0; 
			foreach ($details as $item) { 
				$tr_num++;?>
		<tr>
			<td class="text-center list_num" style="width: 40px;"><?php echo $tr_num;?></td>
			<td style="width: 150px;"><?php echo $item->form_sn;?></td>
			<td style="width: 150px;"><?php echo date('Y-m-d', $item->created_at);?></td>
			<td style="width: 150px;"><span title="<?php echo $item->company->name;?>"><?php echo $item->company->short_name;?></span></td>
			<td style="width: 150px;"><span title="<?php echo $item->title->name;?>"><?php echo $item->title->short_name;?></span></td>
			<td class="text-right" style="width: 110px;"><?php echo number_format($item->weight, 3);?></td>
			<td class="text-right" style="width: 110px;"><?php echo number_format($item->amount);?></td>
			<td class="text-right" style="width: 110px;"><?php echo number_format($item->need_weight, 3);?></td>
			<td class="text-right" style="width: 110px;"><?php echo number_format($item->need_amount);?></td>
			<td style="width: 100px;"><?php echo $item->sales_type;?></td>
			<td style="width: 100px;"><?php echo $item->team;?></td>
			<td style="width: 100px;"><?php echo $item->belong;?></td>
			<td style="width: 150px;"><span title="<?php echo $item->client->name;?>"><?php echo $item->client->short_name;?></span></td>
		</tr>
		<?php } }?>
		</tbody>
	</table>
</div>
<?php }?>

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
