<?php 
$status = array("unpush"=>"未推送","pushed"=>"待出库","output"=>"已出库","finished"=>"已完成","deleted"=>"已作废");
$_inputTime = array(6=>"00:00~06:00",12=>"06:00~12:00",18=>"12:00~18:00",24=>"18:00~24:00");
?>
<table class="detail_table">
	<tbody>
		<tr>
			<td style="width:13%;" class="detail_table_backg">销售单号</td><td style="width:20.3%;"><?php echo $sales->baseform->form_sn;?></td>
			<td style="width:13%;" class="detail_table_backg">公司</td><td style="width:20.3%;"><?php echo $sales->dictTitle->short_name;?></td>
			<td style="width:13%;" class="detail_table_backg">结算单位</td><td style="width:20.3%;"><span title="<?php echo $sales->dictCompany->name;?>"><?php echo $sales->dictCompany->short_name;?></span></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">提货凭证</td><td style="width:20.3%;"><?php if($model->auth_type == "bill"){echo "提货单";}else{echo "车船号";}?></td>
			<td style="width:13%;" class="detail_table_backg"><?php if($model->auth_type == "bill"){echo "凭证单号";}else{echo "车船号";}?></td>
			<td style="width:20.3%;">
			<?php
			if($model->auth_type == "car"){ 
				$authtext = $model->auth_text;
				$authtext = str_replace(" ",",",$authtext);
				$authtext = str_replace("，",",",$authtext);
				$autharr = explode(",",$authtext);
				$newArr = array();
				foreach($autharr as $k=>$v){
					if(!empty($v)){
						array_push($newArr,$v);
					}
				}
				if(count($newArr) > 2){
					echo '<div class="car_no">'.$newArr[0].','.$newArr[1].',<img src="/images/nummore.png" style="margin:-2px 0 0 3px;">';
					echo '<div class="car_no_list_close">x</div><div class="car_no_list">';
					for($i=0;$i<count($newArr);$i++){
						echo '<div class="car_no_list_one">'.$newArr[$i].'</div>';
					}
					echo '</div></div>';
				}else{
					echo $model->auth_text;
				}
			}else{
				echo $model->auth_text;
			}
			?>
			</td>
			<td style="width:13%;" class="detail_table_backg">提货码</td><td style="width:20.3%;"><?php echo $model->auth_code;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">状态</td><td style="width:20.3%;"><span class="red"><?php echo $status[$model->status];?></span></td>
			<td style="width:13%;" class="detail_table_backg">仓库</td><td style="width:20.3%;"><?php echo $sales->warehouse->name;?></td>
			<td style="width:13%;" class="detail_table_backg">开单时间</td><td style="width:20.3%;"><?php echo $baseform->form_time;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">提货时间</td><td style="width:20.3%;"><?php echo $model->start_time > 0?date("Y-m-d",$model->start_time):"";?></td>
			<td style="width:13%;" class="detail_table_backg">截止时间</td><td style="width:20.3%;"><?php echo $model->end_time > 0?date("Y-m-d",$model->end_time):"";?></td>
			<td style="width:13%;" class="detail_table_backg">制单人</td><td style="width:20.3%;"><?php echo $baseform->operator->nickname;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">审核人</td><td style="width:20.3%;"><?php echo $baseform->approver->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">最后修改人</td><td style="width:20.3%;"><?php echo $baseform->lastupdate->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">备注</td>
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
		<tr>
			<td style="width:13%;" class="detail_table_backg">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update > 0?date("Y-m-d",$baseform->last_update):"";?></td>
			<td style="width:13%;" class="detail_table_backg"></td><td style="width:20.3%;"></td>
			<td style="width:13%;" class="detail_table_backg"></td><td style="width:20.3%;"></td>
		</tr>
	</tbody>
</table>
<div class="create_table">
	<table class="table" id="ps_tb">
		<thead>
			<tr>
         		<th class="" style="width:20%;">产地/品名/材质/规格/长度</th>
         		<th class="text-right" style="width:15%;">总件数</th>
         		<th class="text-right" style="width:20%;">配送件数</th>
         		<th class="text-right" style="width:15%;">实时出库件数</th>
         		<th class="text-right" style="width:20%;">实时出库重量</th>
         		<th class="" style="width:10%;">类型</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = 0;
			$totalAmount = 0;
			foreach($detail as $li){
			$num++;
			$totalAmount += $li->amount;
			$t_amount += $li->salesDetail->amount;
			$t_weight += $li->weight;
			$is_ship = 0;
			$title = "";
			if($sales->sales_type == "normal"){
				$is_ship = $li->salesDetail->mergestorage->is_transit;
				$inputdate = $li->salesDetail->mergestorage->pre_input_date;
				$inputtime = $li->salesDetail->mergestorage->pre_input_time;
				if($is_ship > 0)
				{
					$title = date("Y-m-d",$inputdate)."&nbsp;&nbsp;".$_inputTime[$inputtime];
				}
			}
			?>
			<tr>
				<td class="">
				<?php
					$str = $li["brand"]."/".$li["product"]."/".$li["texture"]."/".$li["rank"]."/".$li["length"];
					echo str_replace('E','<span class="red">E</span>',$str)
				?>
				</td>
				<td class="text-right"><?php echo $li->salesDetail->amount;?></td>
				<td class="text-right"><?php echo $li->amount;?></td>
				<td class="text-right"><?php echo $li->warehouse_amount;?></td>
				<td class="text-right"><?php echo number_format($li->warehouse_weight,3);?></td>
				<td><span title="<?php echo $title;?>"><?php echo $is_ship == 1?"在途":"库存"?></span></td>
			</tr>
			<?php }?>
			<tr>
				<td class="">合计：</td><td class="text-right"><?php echo $t_amount?></td>
				<td class="text-right"><span class="total-num"><?php echo $totalAmount;?></span></td>
				<td></td><td></td>
				<td></td>
			</tr>
		</tbody>
	</table>
</div>
<div class="btn_list">
	<?php 
		 if(isset($_REQUEST['search_url'])){
		 	$back_url=Yii::app()->createUrl($_REQUEST['backUrl'],array('page'=>$_REQUEST['fpage'],'search_url'=>$_REQUEST['search_url']));
		 }else{
		 	$back_url=Yii::app()->createUrl('frmSend/index',array("id"=>$_GET["sid"],"page"=>$_GET["fpage"],"view"=>$_COOKIE["view"]));
		 }
	?>
	<a href="<?php echo $back_url;?>">
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