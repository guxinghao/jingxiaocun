<?php $_type=array('normal'=>'入库','transfer'=>'转库','tuopan'=>'托盘');
	$_status=array(0=>'未入库',1=>'已入库',-1=>'关联失败');
?>
<style>
.table > thead > tr > th{border-bottom:none;}
.create_table thead th{border-bottom:none;}
</style>
<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg" style="width:13%;">入库计划</td><td style="width:20.3%;"><?php echo $push->inputPlan->baseform->form_sn;?></td>
			<td class="detail_table_backg" style="width:13%;">货主单位</td><td style="width:20.3%;"><?php echo $push->inputCompany->short_name;?></td>
			<td class="detail_table_backg" style="width:13%;">货权单位</td><td style="width:20.3%;"><?php echo '<span title="'.$push->ownerCompany->name.'">'.$push->ownerCompany->short_name.'</span>';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">入库类型</td><td style="width:20.3%;"><?php echo $_type[$push->input_type];?></td>
			<td class="detail_table_backg" style="width:13%;">入库状态</td><td style="width:20.3%;"><span class="red"><?php echo $_status[$push->input_status];?></span></td>			
			<td class="detail_table_backg" style="width:13%;">车船号</td>
			<td style="width:20.3%;">
			<?php 
				$authtext =$push->ship_no;
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
					echo $push->ship_no;
				}
			?>
			</td>			
		</tr>
		<tr>
			<td class="detail_table_backg" style="width:13%;">创建时间</td><td style="width:20.3%;"><?php echo date('Y-m-d',$push->created_at)?></td>
			<td class="detail_table_backg" style="width:13%;"></td><td style="width:20.3%;"></td>
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
         		<th class="flex-col text-right" style="width:6%;">备注</th>
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
    		<td style="width:9%"><?php echo $each->card_no?></td>
    		<td class="text-right" style="width:7%"><?php echo $each->amount;?></td>
    		<td class="text-right" style="width:7%"><?php echo number_format($each->weight,3);?></td>
    		<td class="text-right" style="width:6%"><?php echo $each->content;?></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<a href="<?php echo Yii::app()->createUrl('input/pushedList',array('page'=>$fpage,'search_url'=>$_REQUEST['search_url']))?>">
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
