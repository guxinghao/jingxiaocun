<style>
.detail_table_backg{ width: 13%;}
.detail_table_backg + td{ width: 20.3%;}
</style>

<?php
$_status = array('unsubmit' => "未提交", 'submited' => "已提交", 'approve' => "已审核", 'delete' => "已作废");
$_type = array('warehouse' => "仓库返利", 'supply' => "钢厂返利", 'cost' => "仓储费用");

$is_supply = 0;
$is_warehouse = 0;
switch ($model->type)
{
	case 'warehouse': //仓库返利
		$form_type = "CKFL";
		$is_warehouse = 1;
		$baseform = $model->baseformCKFL;
		break;
	case 'cost': //仓储费用
		$form_type = "CCFY";
		$is_warehouse = 1;
		$baseform = $model->baseformCCFY;
		break;
	case 'supply': //钢厂返利
		$form_type = "GCFL";
		$is_supply = 1;
		$baseform = $model->baseformGCFL;
		break;
	default:
		break;
}
?>
<table class="detail_table">
	<tbody>
		<tr>
		<?php if ($is_supply > 0) {?>
			<td class="detail_table_backg">供应商</td>
		<?php } elseif ($is_warehouse > 0) {?>
			<td class="detail_table_backg">仓库结算单位</td>
		<?php }?>
			<td><span title="<?php echo $model->company->name;?>"><?php echo $model->company->short_name;?></span></td>
			<td class="detail_table_backg">公司</td><td><span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span></td>
			<td class="detail_table_backg">总金额</td><td><?php echo number_format($model->fee, 2);?>元</td>
		</tr>
		<tr>
			<td class="detail_table_backg">开始时间</td><td><?php echo $model->start_time > 0 ? date('Y-m-d', $model->start_time) : '';?></td>
			<td class="detail_table_backg">结束时间</td><td><?php echo $model->end_time > 0 ? date('Y-m-d', $model->end_time) : '';?></td>
			<td class="detail_table_backg">业务员</td><td><?php echo $baseform->belong->nickname;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">状态</td><td class="red"><?php echo $_status[$baseform->form_status];?></td>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
			<td class="detail_table_backg">审核人</td><td><?php echo $baseform->approver->nickname;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">最后操作人</td><td><?php echo $baseform->lastupdate->nickname;?></td>
			<td class="detail_table_backg">最后更新时间</td><td><?php echo $baseform->last_update > 0 ? date('Y-m-d H:i:s', $baseform->last_update) : '';?></td>
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
