<?php
$_status=array('delete'=>'已删除','unsubmit'=>"未提交",'submited'=>'已提交','approve'=>'已审核'); 
?>
<style>
.table > thead > tr > th{border-bottom:none;}
.create_table thead th{border-bottom:none;}
</style>
<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg" style="width:13%;">采购公司</td><td style="width:20.3%;"><?php echo $frmPledge->title->short_name;?></td>
			<td class="detail_table_backg" style="width:13%;">托盘公司</td><td style="width:20.3%;"><?php echo '<span title="'.$frmPledge->company->name.'">'.$frmPledge->company->short_name.'</span>';?></td>
			<td class="detail_table_backg" style="width:13%;">采购单</td><td style="width:20.3%;"><?php echo $frmPledge->purchase->baseform->form_sn?></td>			
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">托盘赎回等级</td><td style="width:20.3%;"><?php echo $frmPledge->pledgeInfo->r_limit=='1'?'产地':'产地+品名';?></td>
			<td class="detail_table_backg" style="width:13%;">托盘金额</td><td style="width:20.3%;"><?php echo $frmPledge->pledgeInfo->fee;?></td>
			<td class="detail_table_backg" style="width:13%;">托盘价格</td><td style="width:20.3%;"><?php echo $frmPledge->pledgeInfo->unit_price;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">托盘预付款</td><td style="width:20.3%;"><?php echo $frmPledge->pledgeInfo->advance;?></td>
			<td class="detail_table_backg" style="width:13%;">业务员</td><td style="width:20.3%;"><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg" style="width:13%;">状态</td><td style="width:20.3%;"><span class="red"><?php echo $_status[$baseform->form_status];?></span></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">制单人</td><td style="width:20.3%;"><?php echo   $baseform->operator->nickname?></td>
			<td class="detail_table_backg" style="width:13%;">最后操作人</td><td style="width:20.3%;"><?php echo   $baseform->last_updated_by?$baseform->lastupdate->nickname:$baseform->operator->nickname;?></td>
			<td class="detail_table_backg" style="width:13%;">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update>0?date('Y-m-d H:i:s',$baseform->last_update):'';?></td>
		</tr>
		<tr>		
			<td class="detail_table_backg" style="width:13%;">备注</td>
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
			<td class="detail_table_backg" style="width:13%;"></td><td style="width:20.3%;"></td>
			<td class="detail_table_backg" style="width:13%;"></td><td style="width:20.3%;"></td>
		</tr>
	</tbody>
</table>
<div class="create_table">
	<table class="table"  id="cght_tb" style="display:none;" >
    	<thead>
    	<tr>
         		<th class="flex-col " style="width:9%;">产地</th>
         		<th class="flex-col " style="width:9%;">品名</th>
         		<th class="flex-col text-right" style="width:7%;">赎回金额</th>   		         		
         		<th class="flex-col text-right" style="width:7%;">赎回重量</th>
         		<th class="flex-col text-right" style="width:7%;">利息</th>
      		</tr>
    <tbody>
    	<tr >    		
    		<td class="" style="width:9%"><?php echo DictGoodsProperty::getProName($frmPledge->brand_id)?>	</td>
    		<td class="" style="width:9%"><?php echo DictGoodsProperty::getProName($frmPledge->product_id)?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($frmPledge->total_fee,2);?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($frmPledge->weight,3);?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($frmPledge->interest_fee,2);?></td>
    	</tr>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<?php
	if(isset($_REQUEST['search_dan'])){
		$url=Yii::app()->createUrl('pledge/index',array('page'=>$fpage,'search_dan'=>$_REQUEST['search_dan']));
	} else{
		$url=Yii::app()->createUrl('pledge/index',array('page'=>$fpage,'search_url'=>$_REQUEST['search_url']));
	}
	?>
	<a href="<?php echo $url?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">返回</button>
	</a>
</div>
 <script type="text/javascript">
  $(function(){
     $('#cght_tb').datatable({
    	 fixedLeftWidth:41,
    	 fixedRightWidth:0,
      });
     
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
   });
  </script>
