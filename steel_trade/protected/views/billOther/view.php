<style>
<!--
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
			<td class="detail_table_backg">公司账户</td>
			<td><?php echo $model->dictBank ? '<span title="'.$model->dictBank->dict_name.'('.$model->dictBank->bank_number.')">'.$model->dictBank->dict_name.'('.$model->dictBank->bank_number.')</span>' : '';?></td>
			<td class="detail_table_backg">总金额</td><td><?php echo number_format($model->amount, 2);?>元</td>
		</tr>
		<tr>
			<td class="detail_table_backg">结算单位</td><td><span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span></td>
			<td class="detail_table_backg">结算账户</td>
			<td><?php echo $model->bank ? '<span title="'.$model->bank->company_name.'('.$model->bank->bank_number.')">'.$model->bank->company_name.'('.$model->bank->bank_number.')</span>' : '';?></td>
			<td class="detail_table_backg">状态</td><td class="red"><?php echo CommonForms::$formStatus[$baseform->form_status];?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
			<td class="detail_table_backg">负责人</td><td><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg">业务组</td><td><?php echo $baseform->belong->team->name;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">审核人</td><td><?php echo $baseform->approver->nickname;?></td>
			<td class="detail_table_backg">入账人</td><td><?php echo $model->account->nickname;?></td>
			<td class="detail_table_backg">入账时间</td><td><?php echo $model->account_at > 0 ? date('Y-m-d H:i:s', $model->account_at) : '';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">最后操作人</td><td><?php echo $baseform->lastupdate->nickname;?></td>
			<td class="detail_table_backg">最后更新时间</td><td><?php echo $baseform->last_update > 0 ? date('Y-m-d H:i:s', $baseform->last_update) : '';?></td>
			<td class="detail_table_backg">到账日期</td>
			<td><?php echo $model->reach_at > 0 ? date('Y-m-d', $model->reach_at) : '';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">备注</td>
			<td>
			<?php 
				$text =  htmlspecialchars($baseform->comment);
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
			<td class="detail_table_backg"></td><td></td>
		</tr>
	</tbody>
</table>

<div class="create_table">
	<table id="cght_tb" class="table" style="display: none;">
		<thead>
			<tr>
				<th style="width: 40px;"></th>
				<th class="flex-col" style="width: 150px;">费用类别</th>
				<th class="flex-col" style="width: 150px;"></th>
				<th class="flex-col" style="width: 150px;">金额</th>
			</tr>
		</thead>
		<tbody>
		<?php $tr_num = 1; 
		foreach ($model->details as $item) {?>
			<tr>
				<td class="text-center list_num" style="width: 40px;"><?php echo $tr_num;?></td>
				<td style="width: 150px;"><?php echo $item->recordType1->name;?></td>
				<td style="width: 150px;"><?php echo $item->recordType2->name;?></td>
				<td style="width: 150px;"><?php echo number_format($item->fee, 2);?></td>
			</tr>
		<?php $tr_num++; }?>
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
