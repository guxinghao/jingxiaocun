<?php 
$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
$_time=array('6'=>'00:00-06:00','12'=>'06:00-12:00','18'=>'12:00-18:00','24'=>'18:00-24:00');
?>
<style>
.table > thead > tr > th{border-bottom:none;}
.create_table thead th{border-bottom:none;}
</style>
<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg" style="width:13%;">公司</td><td style="width:20.3%;"><?php echo $purchase->title->short_name;?></td>
			<td class="detail_table_backg" style="width:13%;">供应商</td><td style="width:20.3%;"><?php echo '<span title="'.$purchase->supply->name.'">'.$purchase->supply->name.'</span>';?></td>
			<td class="detail_table_backg" style="width:13%;">联系人</td><td style="width:20.3%;"><?php echo $purchase->contact->name;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">联系电话</td><td style="width:20.3%;"><?php echo $purchase->contact->mobile;?></td>
			<td class="detail_table_backg" style="width:13%;">采购合同</td><td style="width:20.3%;"><?php echo $purchase->contract_baseform->contract->contract_no;?></td>
			<td class="detail_table_backg" style="width:13%;">采购日期</td><td style="width:20.3%;"><?php echo $baseform->form_time;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">业务员</td><td style="width:20.3%;"><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg" style="width:13%;">业务组</td><td style="width:20.3%;"><?php echo $purchase->team->name;?></td>
			<td class="detail_table_backg" style="width:13%;">预计到货时间</td><td style="width:20.3%;"><?php echo ($purchase->date_reach>943891200)?date('Y-m-d',$purchase->date_reach).' '.$_time[$purchase->reach_time]:'';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">车船号</td>
			<td style="width:20.3%;">
			<?php 
				$authtext = $purchase->transfer_number;
				$authtext = str_replace(" ",",",$authtext);
				$authtext = str_replace("，",",",$authtext);
				$autharr = explode(",",$authtext);
				$newArr = array();
				foreach($autharr as $k=>$v){
					if(!empty($v)){
						array_push($newArr,$v);
					}
				}
				$autharr=$newArr;
				if(count($autharr) > 2){
					echo '<div class="car_no">'.$autharr[0].','.$autharr[1].',<img src="/images/nummore.png" style="margin:-2px 0 0 3px;">';
					echo '<div class="car_no_list_close">x</div><div class="car_no_list">';
					for($i=0;$i<count($autharr);$i++){
						if($autharr[$i] == ""){continue;}
						echo '<div class="car_no_list_one">'.$autharr[$i].'</div>';
					}
					echo '</div></div>';
				}else{
					echo $purchase->transfer_number;
				}
			?>
			</td>			
			<td class="detail_table_backg" style="width:13%;">入库仓库</td><td style="width:20.3%;"><?php echo $purchase->warehouse->name;?></td>
			<td class="detail_table_backg" style="width:13%;">状态</td><td style="width:20.3%;"><span class="red"><?php echo $_status[$baseform->form_status];?></span></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">乙单</td><td style="width:20.3%;"><?php echo $purchase->is_yidan==1?'是':''?></td>			
			<td class="detail_table_backg" style="width:13%;">运费</td><td style="width:20.3%;"><?php echo  $purchase->contain_cash==1?$purchase->shipment.'元/吨':'';?></td>
			<td class="detail_table_backg" style="width:13%;">托盘</td><td style="width:20.3%;"><?php echo $purchase->purchase_type=="tpcg"?'是':''?></td>
		</tr>
		<?php if($purchase->purchase_type=="tpcg"){?>
		<tr>
			<td class="detail_table_backg" style="width:13%;">托盘公司</td><td style="width:20.3%;"><?php echo $purchase->pledge->pledgeCompany->short_name;?></td>
			<td class="detail_table_backg" style="width:13%;">托盘价格</td><td style="width:20.3%;"><?php echo round($purchase->pledge->unit_price);?></td>
			<td class="detail_table_backg" style="width:13%;">托盘总金额</td><td style="width:20.3%;"><?php echo number_format($purchase->pledge->fee,3);?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">托盘天数</td><td style="width:20.3%;"><?php echo $purchase->pledge->pledge_length;?></td>
			<td class="detail_table_backg" style="width:13%;">托盘利率</td><td style="width:20.3%;"><?php echo $purchase->pledge->pledge_rate;?></td>
			<td class="detail_table_backg" style="width:13%;">托盘赎回限制等级</td><td style="width:20.3%;"><?php echo $purchase->pledge->r_limit==1?'产地':'产地+品名';?></td>
		</tr>
		<tr>			
			<td class="detail_table_backg" style="width:13%;">托盘预付款</td><td style="width:20.3%;"><?php echo number_format($purchase->pledge->advance,3);?></td>
		<!-- ->	<td class="detail_table_backg" style="width:13%;">状态</td><td style="width:20.3%;"><span class="red"><?php echo $_status[$baseform->form_status];?></span></td>-->
			<td class="detail_table_backg" style="width:13%;">制单人</td><td style="width:20.3%;"><?php echo   $baseform->operator->nickname?></td>
			<td class="detail_table_backg" style="width:13%;">审核人</td><td style="width:20.3%;"><?php echo   $baseform->approver->nickname?></td>
		</tr>		
		<tr>									
			<td class="detail_table_backg" style="width:13%;">最后操作人</td><td style="width:20.3%;"><?php echo   $baseform->last_updated_by?$baseform->lastupdate->nickname:$baseform->operator->nickname;?></td>
			<td class="detail_table_backg" style="width:13%;">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update>0?date('Y-m-d H:i:s',$baseform->last_update):'';?></td>
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
		</tr>		
		<?php }else{?>
		<tr>			
			<td class="detail_table_backg" style="width:13%;">制单人</td><td style="width:20.3%;"><?php echo   $baseform->operator->nickname?></td>
			<td class="detail_table_backg" style="width:13%;">审核人</td><td style="width:20.3%;"><?php echo   $baseform->approver->nickname?></td>
			<td class="detail_table_backg" style="width:13%;">最后操作人</td><td style="width:20.3%;"><?php echo   $baseform->last_updated_by?$baseform->lastupdate->nickname:$baseform->operator->nickname;?></td>
		</tr>
		<tr>
			
			<td class="detail_table_backg" style="width:13%;">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update>0?date('Y-m-d H:i:s',$baseform->last_update):'';?></td>
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
			<td class="detail_table_backg" style="width:13%;"></td>
			<td style="width:20.3%;"></td>
		</tr>
		<?php }?>

	</tbody>
</table>
<div class="create_table">
	<table class="table"  id="cght_tb" style="display:none;" >
    	<thead>
    	<tr>
         		<th class="flex-col " style=""></th>         		
         		<th class="flex-col " style="">产地</th>
         		<th class="flex-col " style="">品名</th>
         		<th class="flex-col " style="">材质</th>
         		<th class="flex-col " style="">规格</th>      
         		<th class="flex-col text-right" style="">长度</th>   		         		
         		<th class="flex-col text-right" style="">件数</th>
         		<th class="flex-col text-right" style="">重量</th>
         		<th class="flex-col text-right" style="">单价</th>
         		<th class="flex-col text-right" style="">金额</th>
         		<th class="flex-col text-right" style="">核定件数</th>
         		<th class="flex-col text-right" style="">核定重量</th>
         		<th class="flex-col text-right" style="">核定单价</th>
         		<th class="flex-col text-right" style="">核定金额</th>
         		<th class="flex-col text-right" style="">开票成本</th>
      		</tr>
      	</thead>
    <tbody>
    <?php ?>
    <?php $i=1; foreach ($details as $each){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">    		
    		<td class="text-center list_num" style=""><?php echo $i;?></td>    		
    		<td class="" style=""><?php echo DictGoodsProperty::getProName($each->brand_id)?>	</td>
    		<td class="" style=""><?php echo DictGoodsProperty::getProName($each->product_id)?></td>
    		<td class="" style=""><?php echo str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id));?></td>
    		<td class="" style=""><?php echo  DictGoodsProperty::getProName($each->rank_id);?></td>
    		<td class="text-right" style=""><?php echo $each->length;?></td>
    		<td class="text-right" style=""><?php echo $each->amount;?></td>
    		<?php 
    			$amount+=$each->amount;
    			$weight+=$each->weight;
    			$money+=$each->weight*$each->price;
    			$fix_amount+=$each->fix_amount;
    			$fix_weight+=$each->fix_weight;
    			$fix_money+=$each->fix_weight*$each->fix_price;
    		?>
    		<td class="text-right" style=""><?php echo number_format($each->weight,3);?></td>
    		<td class="text-right" style=""><?php echo round($each->price);?></td>
    		<td class="text-right" style=""><?php echo number_format($each->weight*$each->price,2);?></td>
    		<td class="text-right" style=""><?php echo number_format($each->fix_amount);?></td>
    		<td class="text-right" style=""><?php echo number_format($each->fix_weight,3);?></td>
    		<td class="text-right" style=""><?php echo number_format($each->fix_price);?></td>
    		<td class="text-right" style=""><?php echo number_format($each->fix_weight*$each->fix_price,2);?></td>
    		<td class="text-right" style=""><?php echo number_format($each->invoice_price,2);?></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
     <tfoot>
   		<tr class="tablefoot">
			<td class="text-center" style="" colspan=2>合计：</td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td class="text-right" style=""><span class="tf_total_amount"><?php echo $amount;?></span></td>
			<td class="text-right" style=""><span class="tf_total_weight"><?php echo number_format($weight,3);?></span></td>
			<td class="text-right" style=""></td>
			<td class="text-right" style=""><span class="tf_total_money"><?php echo number_format($money,2);?></span></td>
			<td class="text-right" style=""><?php echo $fix_amount;?></td>
			<td class="text-right" style=""><?php echo number_format($fix_weight,3);?></td>
			<td class="text-right" style=""></td>
			<td class="text-right" style=""><?php echo number_format($fix_money,2);?></td>
			<td></td>
		</tr>
   </tfoot>
  </table>
</div>
<div class="btn_list">
 	<a href="<?php echo Yii::app()->createUrl($backUrl,array('page'=>$fpage,'search_url'=>$_REQUEST['search_url']))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">返回</button>
	</a>
</div>
 <script type="text/javascript">
//  $('#cancel').click(function(){
// // 	 alert(document.referrer);
// 	var referrer='<?php echo $_SERVER['HTTP_REFERER'];?>';
// //  	alert(referrer);
// 	 window.history.back();
// })
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
