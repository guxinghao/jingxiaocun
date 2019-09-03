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
			<td class="detail_table_backg" style="width:13%;">公司</td><td style="width:20.3%;"><?php echo $salesReturn->title->short_name;?></td>
			<td class="detail_table_backg" style="width:13%;">客户</td><td style="width:20.3%;"><?php echo '<span title="'.$salesReturn->client->name.'">'.$salesReturn->client->short_name.'</span>';?></td>
			<td class="detail_table_backg" style="width:13%;">结算单位</td><td style="width:20.3%;"><?php echo '<span title="'.$salesReturn->company->name.'">'.$salesReturn->company->short_name.'</span>';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">联系人</td><td style="width:20.3%;"><?php echo $salesReturn->contact->name;?></td>
			<td class="detail_table_backg" style="width:13%;">联系电话</td><td style="width:20.3%;"><?php echo $salesReturn->contact->mobile;?></td>
			<td class="detail_table_backg" style="width:13%;">开单日期</td><td style="width:20.3%;"><?php echo $baseform->form_time;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">业务员</td><td style="width:20.3%;"><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg" style="width:13%;">业务组</td><td style="width:20.3%;"><?php echo $salesReturn->team->name;?></td>
			<td class="detail_table_backg" style="width:13%;">预计退货时间</td><td style="width:20.3%;"><?php echo ($salesReturn->return_date>943891200)?date('Y-m-d',$salesReturn->return_date):'';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">车船号</td>
			<td style="width:20.3%;">
			<?php 
				$authtext = $salesReturn->travel;
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
					echo $salesReturn->travel;
				}
			?>
			</td>			
			<td class="detail_table_backg" style="width:13%;">入库仓库</td><td style="width:20.3%;"><?php echo $salesReturn->warehouse->name;?></td>
			<td class="detail_table_backg" style="width:13%;">退货方式</td><td style="width:20.3%;"><?php echo $salesReturn->return_type=='warehouse'?'仓库':'供应商';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">退货供应商</td><td style="width:20.3%;"><?php echo '<span title="'.$salesReturn->supply->name.'">'.$salesReturn->supply->short_name.'</span>'?></td>
			<td class="detail_table_backg" style="width:13%;">运发责任</td><td style="width:20.3%;"><?php echo $salesReturn->tran_type=='get'?'自提':'送货';?></td>
			<td class="detail_table_backg" style="width:13%;">审单状态</td><td style="width:20.3%;"><?php echo $salesReturn->weight_confirm_status==1?'是':'';?></td>
		</tr>
		<tr>		
			<td class="detail_table_backg" style="width:13%;">状态</td><td style="width:20.3%;"><span class="red"><?php echo $_status[$baseform->form_status];?></span></td>
			<td class="detail_table_backg" style="width:13%;">制单人</td><td style="width:20.3%;"><?php echo   $baseform->operator->nickname?></td>
			<td class="detail_table_backg" style="width:13%;">审核人</td><td style="width:20.3%;"><?php echo   $baseform->approver->nickname?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">是否乙单</td><td style="width:20.3%;"><?php echo $salesReturn->is_yidan=='1'?'是':'';?></td>
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
			<td class="detail_table_backg" style="width:13%;">退货原因</td><td><?php echo $salesReturn->back_reason;?></td>
			<td class="detail_table_backg" style="width:13%;"></td><td></td>
		</tr>
	</tbody>
</table>
<div class="create_table">
	<table class="table"  id="cght_tb" style="display:none;" >
    	<thead>
    	<tr>
         		<th class="flex-col " style="width:3%;"></th>         		
         		<th class="flex-col " style="width:9%;">产地</th>
         		<th class="flex-col " style="width:9%;">品名</th>
         		<th class="flex-col " style="width:9%;">材质</th>
         		<th class="flex-col " style="width:9%;">规格</th>      
         		<th class="flex-col text-right" style="width:7%;">长度</th>   		         		
         		<th class="flex-col text-right" style="width:7%;">件数</th>
         		<th class="flex-col text-right" style="width:7%;">重量</th>
         		<th class="flex-col text-right" style="width:7%;">单价</th>
         		<th class="flex-col text-right" style="width:7%;">金额</th>
      		</tr>
      	</thead>
    <tbody>
    <?php $i=1; foreach ($details as $each){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">    		
    		<td class="text-center list_num" style="width:3%"><?php echo $i;?></td>    		
    		<td class="" style="width:9%"><?php echo DictGoodsProperty::getProName($each->brand_id)?>	</td>
    		<td class="" style="width:9%"><?php echo DictGoodsProperty::getProName($each->product_id)?></td>
    		<td class="" style="width:9%"><?php echo str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id));?></td>
    		<td class="" style="width:9%"><?php echo  DictGoodsProperty::getProName($each->rank_id);?></td>
    		<td class="text-right" style="width:7%"><?php echo $each->length;?></td>
    		<td class="text-right" style="width:7%"><?php echo $each->return_amount;?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($each->return_weight,3);?></td>
    		<td class="text-right" style="width:7%"><?php echo round($each->return_price);?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($each->return_weight*$each->return_price,2);?></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<a href="<?php echo Yii::app()->createUrl($backUrl,array('page'=>$fpage,'search_url'=>$_REQUEST['search_url']))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">返回</button>
	</a>
</div>
 <script type="text/javascript">
//  $('#cancel').click(function(){
// 	 window.history.back(-1);
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
