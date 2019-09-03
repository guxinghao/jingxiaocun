<?php
$_status=array('0'=>'未推送','1'=>"已推送",'2'=>'已完成',3=>'作废中',4=>'已作废'); 
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
			<td class="detail_table_backg" style="width:13%;">供应商</td><td style="width:20.3%;"><?php echo '<span title="'.$purchase->supply->name.'">'.$purchase->supply->short_name.'</span>';?></td>
			<td class="detail_table_backg" style="width:13%;">联系人</td><td style="width:20.3%;"><?php echo $purchase->contact->name;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">联系电话</td><td style="width:20.3%;"><?php echo $purchase->contact->mobile;?></td>
			<td class="detail_table_backg" style="width:13%;">采购单</td><td style="width:20.3%;"><?php echo $baseform_pur->form_sn?></td>			
			<td class="detail_table_backg" style="width:13%;">采购日期</td><td style="width:20.3%;"><?php echo $baseform_pur->form_time;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">业务员</td><td style="width:20.3%;"><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg" style="width:13%;">业务组</td><td style="width:20.3%;"><?php echo $purchase->team->name;?></td>
			<td class="detail_table_backg" style="width:13%;">预计入库时间</td><td style="width:20.3%;"><?php echo date('Y-m-d',$input->input_date).' '.$_time[$input->input_time];?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">车船号</td>
			<td style="width:20.3%;">
			<?php 
				$authtext =$purchase->transfer_number;
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
			<td class="detail_table_backg" style="width:13%;">状态</td><td style="width:20.3%;"><span class="red"><?php echo $input->input_status==0?'未入库':'已入库';?></span></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">入库类型</td><td style="width:20.3%;">船舱入库</td>
			<td class="detail_table_backg" style="width:13%;">制单人</td><td style="width:20.3%;"><?php echo $baseform->operator->nickname?></td>
			<td class="detail_table_backg" style="width:13%;">最后操作人</td><td style="width:20.3%;"><?php echo   $baseform->last_updated_by?$baseform->lastupdate->nickname:$baseform->operator->nickname;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update>0?date('Y-m-d',$baseform->last_update):date('Y-m-d',$baseform->created_at);?></td>
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
         		<th class="flex-col" style="width:9%;">卡号</th>   		         		
         		<th class="flex-col text-right" style="width:7%;">入库件数</th>
         		<th class="flex-col text-right" style="width:7%;">入库重量</th>
         		<th class="flex-col text-right" style="width:6%;">单价</th>
      		</tr>
    <tbody>
    <?php $i=1; foreach ($details as $each){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">    		
    		<td class="text-center list_num" style="width:3%"><?php echo $i;?></td>    		
    		<td class="" style="width:9%"><?php echo DictGoodsProperty::getProName($each->brand_id)?>	</td>
    		<td class="" style="width:9%"><?php echo DictGoodsProperty::getProName($each->product_id)?></td>
    		<td class="" style="width:9%"><?php echo str_replace('E', '<span class="red">E</span>',DictGoodsProperty::getProName($each->texture_id));?></td>
    		<td class="" style="width:9%"><?php echo  DictGoodsProperty::getProName($each->rank_id);?></td>
    		<td class="text-right" style="width:7%"><?php echo $each->length;?></td>
    		<td style="width:9%"><?php echo $each->card_id?></td>
    		<td class="text-right" style="width:7%"><?php echo $each->input_amount;?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($each->input_weight,3);?></td>
    		<td class="text-right" style="width:6%"><?php echo $each->cost_price;?></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<a href="<?php echo Yii::app()->createUrl('inputCcrk/index',array('page'=>$fpage,'input_type'=>'ccrk','search_url'=>$_REQUEST['search_url']))?>">
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
