<style>
<!--
#cght_tb tbody td{ line-height: 26px;}
#cght_tb tbody td{ line-height: 26px;}
.detail_table_backg{ width: 13%;}
.detail_table_backg + td{ width: 20.3%;}
.detail_table_backg + td span{ display: block; width: 100%; height: 38px; padding: 0 2%; text-align: center; overflow: hidden;}
.table > thead > tr > th{ border-bottom: none;}
.create_table thead th{ border-bottom: none;}
-->
</style>

<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg">公司</td><td><span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span></td>
			<td class="detail_table_backg">借/贷公司</td><td><span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span></td>
			<td class="detail_table_backg">借贷方向</td><td><?php echo ShortLoan::$lendingDirection[$model->lending_direction];?></td>
		</tr>
		
		<tr>
			<td class="detail_table_backg">合约金额</td><td><?php echo number_format($model->amount, 2);?></td>
			<td class="detail_table_backg">利率</td><td><?php echo number_format($model->interest_rate, 4);?>‰</td>
			<td class="detail_table_backg">本金余额</td><td><?php echo number_format($model->balance, 2);?></td>
		</tr>

		<tr>
			<td class="detail_table_backg">登记时间</td><td><?php echo $baseform->form_time;?></td>
			<td class="detail_table_backg">开始日期</td><td><?php echo $model->start_time>0?date('Y-m-d', $model->start_time):"";?></td>
			<td class="detail_table_backg">结束日期</td><td><?php echo $model->end_time>0?date('Y-m-d', $model->end_time):"";?></td>
		</tr>
		
		<tr>
			<td class="detail_table_backg">借据</td><td><?php echo ShortLoan::$hasIous[$model->has_Ious];?></td>
			<td class="detail_table_backg">状态</td><td><?php echo CommonForms::$formStatus[$baseform->form_status];?></td>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
		</tr>
		
		<tr>
			<td class="detail_table_backg">负责人</td><td><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg">业务组</td><td><?php echo $baseform->belong->team->name;?></td>
			<td class="detail_table_backg">最后审核人</td><td><?php echo $baseform->approver->nickname;?></td>
		</tr>
				
		<tr>
			<td class="detail_table_backg">最后操作人</td><td><?php echo $baseform->lastupdate->nickname;?></td>
			<td class="detail_table_backg">最后更新时间</td><td><?php echo $baseform->last_update > 0 ? date('Y-m-d', $baseform->last_update) : '';?></td>
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
	</tbody>
</table>

<div class="create_table">
	<table id="cght_tb" class="table" style="display: none;">
		<thead>
			<tr>
				<th class="flex-col" style="width: 100px;">入账日期</th>
				<th class="flex-col" style="width: 150px;">公司账号</th>
				<th class="flex-col" style="width: 150px;">对方账号</th>
				<th class="flex-col text-right" style="width: 120px;">金额</th>
				<th class="flex-col" style="width: 60px;">借据</th>
				<th class="flex-col" style="width: 100px;">操作人</th>
				<th class="flex-col" style="width: 150px;">备注</th>
			</tr>
		</thead>
		
		<tbody>
		<?php $tr_num = 1;
		foreach ($loanRecord as $key => $item) {
		?>
			<tr>
				<td style="width: 100px;"><?php echo $item->reach_at > 0 ? date('Y-m-d', $item->reach_at) : '';?></td>
				<td style="width: 150px;"><span title="<?php echo $item->dictBank ? $item->dictBank->dict_name.'('.$item->dictBank->bank_number.')' : '';?>"><?php echo $item->dictBank ? $item->dictBank->bank_number : '';?></span></td>
				<td style="width: 150px;"><span title="<?php echo $item->bank ? $item->bank->company_name.'('.$item->bank->bank_number.')' : '';?>"><?php echo $item->bank ? $item->bank->bank_number : '';?></span></td>
				<td class="text-right" style="width: 120px;"><?php echo number_format($item->amount, 2);?></td>
				<td style="width: 60px;"><?php echo LoanRecord::$hasIous[$item->has_Ious];?></td>
				<td style="width: 100px;"><?php echo $item->operator->nickname;?></td>
				<td style="width: 150px;"><span title="<?php echo htmlspecialchars($item->comment);?>"><?php echo mb_substr($item->comment, 0, 11,"utf-8");?></span></td>
			</tr>
		<?php $tr_num++; }?>
		</tbody>
	</table>
</div>

<div class="btn_list">
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">返回</button>
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