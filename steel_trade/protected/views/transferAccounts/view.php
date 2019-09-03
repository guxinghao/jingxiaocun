<style>
<!--
.detail_table_backg{ width: 13%;}
.detail_table_backg + td{ width: 20.3%;}
-->
</style>

<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg">转出公司</td><td><span title="<?php echo $model->titleOutput->name;?>"><?php echo $model->titleOutput->short_name;?></span></td>
			<td class="detail_table_backg">转出账户</td><td><span title="<?php echo $model->outputBank->dict_name.'('.$model->outputBank->bank_number.')';?>"><?php echo $model->outputBank->dict_name.'('.$model->outputBank->bank_number.')';?></span></td>
			<td class="detail_table_backg">类型</td><td><?php echo TransferAccounts::$type[$model->type];?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">转入公司</td><td><span title="<?php echo $model->titleInput->name;?>"><?php echo $model->titleInput->short_name;?></span></td>
			<td class="detail_table_backg">转入账户</td><td><span title="<?php echo $model->inputBank->dict_name.'('.$model->inputBank->bank_number.')';?>"><?php echo $model->inputBank->dict_name.'('.$model->inputBank->bank_number.')';?></span></td>
			<td class="detail_table_backg">金额</td><td><?php echo number_format($model->amount, 2);?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">业务员</td><td><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg">业务组</td><td><?php echo $baseform->belong->team->name;?></td>
			<td class="detail_table_backg">状态</td><td class="red"><?php echo CommonForms::$formStatus[$baseform->form_status];?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
			<td class="detail_table_backg">最后审核人</td><td><?php echo $baseform->approver->nickname;?></td>
			<td class="detail_table_backg">到账日期</td>
			<td><?php echo $model->reach_at > 0 ? date('Y-m-d', $model->reach_at) : '';?></td>
		</tr>
		<tr>
		<td class="detail_table_backg">登记日期</td><td><?php echo $baseform->form_time;?></td>
			<td class="detail_table_backg">备注</td>
			<td><?php $text =  htmlspecialchars($baseform->comment); 
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

<div class="btn_list">
	<a href="<?php echo $back_url;?>">
		<button id="cancel" class="btn btn-primary btn-sm" data-dismiss="modal">返回</button>
	</a>
</div>

<script type="text/javascript">
<!--
$(function(){
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