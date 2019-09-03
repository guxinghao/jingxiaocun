<?php 
$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
?>
<table class="detail_table">
	<tbody>
		<tr>
			<td style="width:13%;" class="detail_table_backg">公司</td><td style="width:20.3%;"><?php echo $return->dictTitle->short_name;?></td>
			<td style="width:13%;" class="detail_table_backg">供应商</td><td style="width:20.3%;"><span title="<?php echo $return->supply->name;?>"><?php echo $return->supply->short_name;?></span></span></td>
			<td style="width:13%;" class="detail_table_backg">联系人</td><td style="width:20.3%;"><?php echo $return->contact->name;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">联系电话</td><td style="width:20.3%;"><?php echo $return->contact->mobile;?></td>
			<td style="width:13%;" class="detail_table_backg">仓库</td><td style="width:20.3%;"><?php echo $return->warehouse->name;?></td>
			<td style="width:13%;" class="detail_table_backg">开单日期</td><td style="width:20.3%;"><?php echo $baseform->form_time;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">业务员</td><td style="width:20.3%;"><?php echo $baseform->belong->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">业务组</td><td style="width:20.3%;"><?php echo $return->team->name;?></td>
			<td style="width:13%;" class="detail_table_backg">状态</td><td style="width:20.3%;"><span class="red"><?php echo $return->confirm_status==1?"已完成":$_status[$baseform->form_status];?></span></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">制单人</td><td style="width:20.3%;"><?php echo $baseform->operator->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">审核人</td><td style="width:20.3%;"><?php echo $baseform->approver->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">最后操作人</td><td style="width:20.3%;"><?php echo $baseform->lastupdate->nickname;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update > 0?date("Y-m-d",$baseform->last_update):"";?></td>
			<td style="width:13%;" class="detail_table_backg">乙单</td><td style="width:20.3%;"><?php echo $return->is_yidan==1?"是":'';?></td>
			<td style="width:13%;" class="detail_table_backg">备注</td>
			<td style="width:20.3%;">
			<?php 
				$text =  htmlspecialchars($return->comment);
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
		</tr>
	</tbody>
</table>
<div class="create_table">
	<table class="table" id="ckck_tb">
		<thead>
			<tr>
         		<th class="" style="width:40px;border-right:none;"></th>
         		<th class="flex-col" style="width:100px;border-left:none;">产地</th>
         		<th class="flex-col" style="width:70px;">品名</th>
         		<th class="flex-col" style="width:80px;">材质</th>
         		<th class="flex-col" style="width:60px;">规格</th>
         		<th class="flex-col text-right" style="width:50px;">长度</th>
         		<th class="flex-col text-right" style="width:80px;">退货件数</th>
         		<th class="flex-col text-right" style="width:100px;">退货重量</th>
         		<th class="flex-col text-right" style="width:100px;">退货单价</th>
         		<th class="flex-col text-right" style="width:100px;">退货金额</th>
         		<th class="flex-col text-right" style="width:80px;">出库件数</th>
         		<th class="flex-col text-right" style="width:80px;">出库重量</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = 0;
			foreach($details as $dt){
			$num++;
			$price = $dt->return_weight * $dt->return_price;
			$t_price += $price;
			$t_amount += $dt->return_amount;
			$t_weight += $dt->return_weight;
			$t_out_amount += $dt->output_amount;
			?>
			<tr>
				<td style="width:40px;" class="text-center list_num"><?php echo $num;?></td>
    			<td style="width:100px;" class=""><?php echo $dt->brand;?></td>
    			<td class="" style="width:70px;"><?php echo $dt->product;?></td>
	    		<td class="" style="width:80px;"><?php echo str_replace('E','<span class="red">E</span>',$dt->texture);?></td>
	    		<td class="" style="width:60px;"><?php echo $dt->rank;?></td>
	    		<td class="text-right" style="width:50px;"><?php echo intval($dt->length);?></td>
	    		<td class="text-right" style="width:80px;"><?php echo intval($dt->return_amount);?></td>
	    		<td class="text-right" style="width:100px;"><?php echo number_format($dt->return_weight,3);?></td>
	    		<td class="text-right" style="width:100px;"><?php echo number_format($dt->return_price);?></td>
	    		<td class="text-right" style="width:100px;"><?php echo number_format($price,2);?></td>
	    		<td class="text-right" style="width:100px;"><?php echo intval($dt->output_amount);?></td>
	    		<td class="text-right" style="width:100px;"><?php echo number_format($dt->output_weight,3);?></td>
			</tr>
			<?php } ?>
			<tr>
				<td style="width:40px;" class="text-center list_num">合计</td>
	    		<td style="width:100px;" class=""></td>
	    		<td class="" style="width:70px;"></td>
	    		<td class="" style="width:80px;"></td>
	    		<td class="" style="width:60px;"></td>
	    		<td class="" style="width:50px;"></td>
	    		<td class="text-right" style="width:80px;"><?php echo $t_amount;?></td>
	    		<td class="text-right" style="width:100px;"><?php echo $t_weight;?></td>
	    		<td class="text-right" style="width:100px;"></td>
	    		<td class="text-right" style="width:100px;"><?php echo $t_price;?></td>
	    		<td class="text-right" style="width:80px;"><?php echo $t_out_amount?></td>
	    		<td class="text-right" style="width:110px;"></td>
			</tr>
		</tbody>
	</table>
</div>

<div class="btn_list">
	<a href="<?php echo Yii::app()->createUrl('FrmPurchaseReturn/index',array("id"=>$_GET["sid"],"page"=>$_GET["fpage"]));?>">
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