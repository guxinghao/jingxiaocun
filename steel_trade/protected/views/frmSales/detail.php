<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<?php 
$_status=array('unsubmit'=>'未提交','submited'=>'已提交','approve'=>'已审核','delete'=>'已作废');
$sales_type = array("normal"=>"库存销售","xxhj"=>"先销后进","dxxs"=>"代销销售");
$_inputTime = array(6=>"00:00~06:00",12=>"06:00~12:00",18=>"12:00~18:00",24=>"18:00~24:00");
?>
<style>
.table > thead > tr > th{border-bottom:none;}
.create_table thead th{border-bottom:none;}
</style>
<input type="hidden" value="<?php echo $baseform->last_update;?>" name="CommonForms[last_update]">
<input type="hidden" value="<?php echo $sales->sales_type;?>" name="FrmSales[sales_type]" id="salestype"/>
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<table class="detail_table">
	<tbody>
		<tr>
			<td style="width:13%;" class="detail_table_backg">公司</td><td style="width:20.3%;"><?php echo $sales->dictTitle->short_name;?></td>
			<td style="width:13%;" class="detail_table_backg">客户</td><td style="width:20.3%;"><span title="<?php echo $sales->client->name;?>"><?php echo $sales->client->name;?></span></span></td>
			<td style="width:13%;" class="detail_table_backg">结算单位</td><td style="width:20.3%;"><span title="<?php echo $sales->dictCompany->name;?>"><?php echo $sales->dictCompany->name;?></span></span></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">联系人</td><td style="width:20.3%;"><?php echo $sales->companycontact->name;?></td>
			<td style="width:13%;" class="detail_table_backg">联系电话</td><td style="width:20.3%;"><?php echo $sales->contact->mobile;?></td>
			<td style="width:13%;" class="detail_table_backg">仓库</td><td style="width:20.3%;"><?php echo $sales->warehouse->name;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">开单日期</td><td style="width:20.3%;"><?php echo $baseform->form_time;?></td>
			<td style="width:13%;" class="detail_table_backg">预计提货日期</td><td style="width:20.3%;"><?php echo $sales->date_extract>0?date("Y-m-d",$sales->date_extract):"";?></td>
			<td style="width:13%;" class="detail_table_backg">车船号</td>
			<td style="width:20.3%;">
			<?php 
				$authtext = $sales->travel;
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
					echo $sales->travel;
				}
			?>
			</td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">业务员</td><td style="width:20.3%;"><?php echo $baseform->belong->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">业务组</td><td style="width:20.3%;"><?php echo $sales->team->name;?></td>
			<td style="width:13%;" class="detail_table_backg">乙单</td><td style="width:20.3%;"><?php echo $sales->is_yidan==1?"是":"否";?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">状态</td><td style="width:20.3%;"><span class="red"><?php echo $sales->confirm_status==1?"已完成":$_status[$baseform->form_status];?></span></td>
			<td style="width:13%;" class="detail_table_backg">类型</td><td style="width:20.3%;"><?php echo $sales_type[$sales->sales_type];?></td>
			<td style="width:13%;" class="detail_table_backg">采购员</td><td style="width:20.3%;"><?php echo $sales->highopenone->company->short_name;?></td>
		</tr>
		<tr style="display:none;">
			<td style="width:13%;" class="detail_table_backg">制单人</td><td style="width:20.3%;"><?php echo $baseform->operator->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">审核人</td><td style="width:20.3%;"><?php echo $baseform->approver->nickname;?></td>
			<td style="width:13%;" class="detail_table_backg">最后操作人</td><td style="width:20.3%;"><?php echo $baseform->lastupdate->nickname;?></td>
		</tr>
		<tr>
			<td style="width:13%;" class="detail_table_backg">最后更新时间</td><td style="width:20.3%;"><?php echo $baseform->last_update > 0?date("Y-m-d",$baseform->last_update):"";?></td>
			<td style="width:13%;" class="detail_table_backg">备注</td>
			<td style="width:20.3%;">
			<?php 
				$text =  htmlspecialchars($sales->comment);
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
			<td style="width:13%;" class="detail_table_backg"></td><td style="width:20.3%;"></td>
		</tr>
	</tbody>
</table>
<div class="create_table">
	<table class="table"  id="cght_tb" style="display:none;" >
    	<thead>
     		<tr>
         		<th class="" style="width:40px;border-right:none;"></th>
         		<th class="flex-col" style="width:60px;border-left:none;">产地</th>
         		<th class="flex-col" style="width:50px;">品名</th>
         		<th class="flex-col" style="width:60px;">材质</th>
         		<th class="flex-col" style="width:60px;">规格</th>
         		<th class="flex-col text-right" style="width:40px;">长度</th>
         		<th class="flex-col text-right" style="width:65px;">销售件数</th>
         		<th class="flex-col text-right" style="width:65px;">销售重量</th>
         		<th class="flex-col text-right" style="width:65px;">销售单价</th>
         		<th class="flex-col text-right" style="width:80px;">销售金额</th>
         		<th class="flex-col text-right" style="width:80px;">配送件数</th>
         		<th class="flex-col text-right" style="width:80px;">出库件数</th>
         		<th class="flex-col text-right" style="width:85px;">仓库出库件数</th>
         		<th class="flex-col text-right" style="width:80px;">核定重量</th>
         		<th class="flex-col text-right" style="width:80px;">核定金额</th>
         		<th class="flex-col text-right" style="width:80px;">差额</th>
         		<th class="flex-col text-right" style="width:85px;">实时出库件数</th>
         		<th class="flex-col text-right" style="width:85px;">实时出库重量</th>
         		<th class="flex-col " style="width:40px;">类型</th>
         		<th class="flex-col text-right" style="width:50px;"><span class="gaokai_icon"><img alt="" src="/images/gaokai.png"></span></th>
      		</tr>
    	</thead>
    <tbody>
    <?php 
    	$num = 1;
    	foreach($details as $dt){
    		$price = $dt->fee;
    		$hd_price = $dt->output_weight*($dt->price);
    		if($sales->confirm_status==1){
    			$cha = $price - $hd_price;
    		}else{
    			$cha = 0;
    		}
    		$t_amount += $dt->amount;
    		$t_weight += $dt->weight;
    		$t_price += $price;
    		$t_send_amount += $dt->send_amount;
    		$t_out_amount += $dt->output_amount;
    		$t_out_weight += $dt->output_weight;
    		$t_wout_amount += $dt->warehouse_output_amount;
    		$t_hd_price += $hd_price;
    		$t_cha += $cha;
    ?>
    	<tr class="">
    		<td style="width:40px;" class="text-center list_num"><?php echo $num;?></td>
    		<td style="width:60px;" class=""><?php echo $dt->brand;?></td>
    		<td class="" style="width:50px;"><?php echo $dt->product;?></td>
    		<td class="" style="width:60px;"><?php echo str_replace('E','<span class="red">E</span>',$dt->texture);?></td>
    		<td class="" style="width:60px;"><?php echo $dt->rank;?></td>
    		<td class="text-right" style="width:40px;"><?php echo intval($dt->length);?></td>
    		<td class="text-right" style="width:65px;"><?php echo $dt->amount;?></td>
    		<td class="text-right" style="width:65px;"><?php echo number_format($dt->weight,3);?></td>
    		<td class="text-right" style="width:65px;"><?php echo number_format($dt->price);?></td>
    		<td class="text-right" style="width:80px;"><?php echo number_format($price,2);?></td>
    		<td class="text-right" style="width:80px;">
    		<?php if($baseform->form_status == "approve"){
    				if($dt->send_amount == 0 && $sales->confirm_status==0){
    		?>
    			<a class="a_view" href="<?php echo yii::app()->createUrl("FrmSend/create",array("id"=>$sales->id))?>"><?php echo $dt->send_amount;?></a>
    		<?php 		
    				}else{
    		?>
    			
    			<a class="a_view" href="<?php echo yii::app()->createUrl("FrmSend/index",array("id"=>$sales->id))?>"><?php echo $dt->send_amount;?></a>
    		<?php }
    			}else{
    			echo $dt->send_amount;
    		}?>
    		</td>
    		<td class="text-right" style="width:80px;">
    		<?php if($baseform->form_status == "approve"){
    			if($dt->output_amount == 0 && $sales->confirm_status==0){
    				if($sales->sales_type == "normal"){
    		?>
    			<a class="a_view" href="<?php echo yii::app()->createUrl("frmOutput/create",array("id"=>$sales->id))?>"><?php echo $dt->output_amount;?></a>
    			<?php }else if($sales->sales_type == "xxhj"){
    			?>
    			<a class="a_view" href="<?php echo yii::app()->createUrl("frmOutput/xscreate",array("id"=>$sales->id))?>"><?php echo $dt->output_amount;?></a>
    			<?php 	
    			}else if($sales->sales_type == "dxxs"){
    			?>
    				  <a class="a_view" href="<?php echo yii::app()->createUrl("frmOutput/dxcreate",array("id"=>$sales->id))?>"><?php echo $dt->output_amount;?></a>
    			<?php 	
    			}
    			}else{
    			?> 
    			<a class="a_view" href="<?php echo yii::app()->createUrl("frmOutput/index",array("id"=>$sales->id))?>"><?php echo $dt->output_amount;?></a>
    			<?php	
    			}?>
    		<?php }else{
    			echo $dt->output_amount;
    		}?>
    		</td>
    		<td class="text-right" style="width:85px;"><?php echo $dt->warehouse_output_amount;?></td>
    		<td class="text-right" style="width:80px;"><?php echo number_format($dt->output_weight,3);?></td>
    		<td class="text-right" style="width:80px;"><?php echo number_format($hd_price,2);?></td>
    		<td class="text-right" style="width:80px;"><?php echo number_format($cha,2);?></td>
    		<td class="text-right" style="width:85px;"><?php echo $dt->warehouse_amount;?></td>
    		<td class="text-right" style="width:85px;"><?php echo number_format($dt->warehouse_weight,3);?></td>
    		<td class="" style="width:40px;"><span title="<?php
    			if($dt->mergestorage->is_transit == 1)
    			{
    				if($dt->mergestorage->pre_input_time > 0)
    				{
    					echo date("Y-m-d",$dt->mergestorage->pre_input_date)."&nbsp;&nbsp;".$_inputTime[$dt->mergestorage->pre_input_time];
    				}else{
    					echo date("Y-m-d",$dt->mergestorage->pre_input_date);
    				}
    			}
    		?>"><?php echo $dt->mergestorage->is_transit == 1?"在途":"库存";?></span></td>
    		<td class="text-right" style="width:50px;"><?php echo number_format($dt->bonus_price,2);?></td>
    	</tr>
    	<?php 
    	$num++;
    	}
    	?>
    	<tr class="">
    		<td  class="text-center list_num">合计</td>
    		<td class=""></td>
    		<td class="" ></td>
    		<td class="" ></td>
    		<td class="" ></td>
    		<td class="" ></td>
    		<td class="text-right red" ><?php echo $t_amount;?></td>
    		<td class="text-right red" ><?php echo number_format($t_weight,3);?></td>
    		<td class="text-right" ></td>
    		<td class="text-right red" ><?php echo number_format($t_price,2);?></td>
    		<td class="text-right red" ><?php echo $t_send_amount;?></td>
    		<td class="text-right red" ><?php echo $t_out_amount?></td>
    		<td class="text-right red" ><?php echo $t_wout_amount;?></td>
    		<td class="text-right red" ><?php echo number_format($t_out_weight,3)?></td>
    		<td class="text-right red" ><?php echo number_format($t_hd_price,2);?></td>
    		<td class="text-right red"><?php echo number_format($t_cha,2);?></td>
    		<td class="text-right red" ></td>
    		<td class="text-right red" ></td>
    		<td class="text-right" ></td>
    		<td class="text-right" ></td>
    	</tr>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<a href="<?php echo $backUrl;?>">
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
