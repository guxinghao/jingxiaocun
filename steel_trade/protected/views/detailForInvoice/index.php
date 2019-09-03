<style>
a{cursor:pointer}
th{text-align:center;vertical-align:middle}
#isearch input{line-height:16px}
.qmye{background:#f4f4f4;cursor:pointer;}
.qcye{background:#f4f4f4;}
.color_gray{background:#f4f4f4}
.div_table table{border-right:0}
</style>
<div class="con_tit">
<?php if($view=="caiwu") {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('detailForInvoice/indexExport');?>">
		<img src="/images/daochu.png">导出
	</div>
<?php }?>
	<div class="view_section">
	<?php 
	 if($caiwu_view && $yewu_view){
	?>
		<a href="<?php echo Yii::app()->createUrl('detailForInvoice/index',array("view"=>"caiwu"))?>">
		<div class="view_button_right view_button <?php if($view=="caiwu"){echo "blue_back";}?>" title="财务视图">
			<img alt="" class="view_button_img" src="/images/right_<?php if($view=="caiwu"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
		<a href="<?php echo Yii::app()->createUrl('detailForInvoice/index',array("view"=>"yewu"))?>">
		<div class=" view_button_left view_button <?php if($view=="yewu"){echo "blue_back";}?>" title="业务视图">
			<img alt="" class="view_button_img" src="/images/left_<?php if($view=="yewu"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
	<?php }?>
	</div>
</div>
<form method="post" action="/index.php/detailForInvoice/index?page=1" url="/index.php/detailForInvoice/index?page=1">
<div class="search_body">
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期" id="start_time" value="<?php echo $st_date;?>" name="start_time">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期" id="end_time" value="<?php echo $et_date;?>" name="end_time"  >
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">公司抬头：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combott" class="forreset" value="<?php echo $search->title->short_name;?>" />
			<input type='hidden' id='combovaltt' value="<?php echo $search->title_id;?>"  class="forreset" name="DetailForInvoice[title_id]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">结算单位：</div>
		<div id="tgselect" class="fa_droplist">
			<input type="text" id="combotg" class="forreset" value="<?php echo $search->company->name;?>" />
			<input type='hidden' id='combovaltg' value="<?php echo $search->company_id;?>"  class="forreset" name="DetailForInvoice[company_id]"/>
		</div>
	</div>
	<?php if($view == "yewu"){?>
	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="tgselect1" class="fa_droplist">
			<input type="text" id="combotg1" class="forreset" value="<?php echo $search->client->name;?>" />
			<input type='hidden' id='combovaltg1' value="<?php echo $search->client_id;?>"  class="forreset" name="DetailForInvoice[client_id]"/>
		</div>
	</div>
	<?php }?>
	<div class="shop_more_one" style="width:0;">
		<div class="more_select_box" style="top:128px;left:800px;width:400px;">
	<div class="more_one">
		<div class="more_one_l">业&nbsp;务&nbsp;员：</div>
		<select name="DetailForInvoice[owned_by]" class='form-control chosen-select forreset owned'>				
        <option value='0' selected='selected'>-全部-</option>
         <?php foreach ($user_array as $k=>$v){?>
        <option <?php echo $k==$search->owned_by?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
        <?php }?>
        </select>
	</div>
	<div class="more_one" style="width: 150px;">
		<div class="more_one_l">未销数量：</div>
		<select name="DetailForInvoice[uncheck]" class="form-control" style="width: 80px;">
			<option value="0" selected="selected">--全部--</option>
			<option value="2" <?php echo $search->uncheck==2?'selected="selected"':''?>>非0</option>
			<option value="1"  <?php echo $search->uncheck==1?'selected="selected"':''?>>等于0</option>
		</select>
	</div>
	</div>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>	
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none">
		<thead>
			<tr class="data">
<!--				<th width="50" class='table_cell_first'>序号</th>-->
				<th width="110"	class="leftAlign table_cell_first">公司抬头</th>
				<th width="110" class="leftAlign">结算单位</th>
				<?php if($view == "yewu"){?>
				<th width="110" class="leftAlign">客户</th>
				<?php }?>
<!--				<th width="120" class="rightAlign">期末余额</th>-->
				
				<th width="120" class="flex-col rightAlign">应开票重量</th>
				<th width="120" class="flex-col rightAlign">应开票金额</th>
				<th width="120" class="flex-col rightAlign">已开票重量</th>
				<th width="120" class="flex-col rightAlign">已开票金额</th>
				<th width="120" class="flex-col rightAlign">未开票重量</th>
				<th width="120" class="flex-col rightAlign">未开票金额</th>
				
<!-- 				<th width="120" class="flex-col rightAlign">应销票重量</th> -->
<!-- 				<th width="120" class="flex-col rightAlign">应销票金额</th> -->
<!-- 				<th width="120" class="flex-col rightAlign">已销票重量</th> -->
<!-- 				<th width="120" class="flex-col rightAlign">已销票金额</th> -->
<!-- 				<th width="120" class="flex-col rightAlign">未销票重量</th> -->
<!-- 				<th width="120" class="flex-col rightAlign">未销票金额</th> -->
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($array_result as $item){
			?>
			<tr id="<?php echo $item->id?>" class="data <?php if($i%2==1){?>color_gray<?php }?>">
<!--				<td class='table_cell_first'><?php echo $i+1?></td>-->
				<td class="leftAlign table_cell_first"><?php echo $item->title?></td>
				<td class="leftAlign"><span title="<?php echo $item->company_full?>"><?php echo $item->company?></span></td>
				<?php if($view == "yewu"){?>
				<td class="leftAlign"><span title="<?php echo $item->client_full?>"><?php echo $item->client?></span></td>
				<?php }?>
<!--				<td class="rightAlign qmye"><?php echo $item->qmye?number_format($item->qmye,2):"0.00" ?></td>-->
				
				<td class="rightAlign"><?php echo number_format($item->should_kp_weight,3)?></td>
				<td class="rightAlign"><?php echo number_format($item->should_kp_money,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->already_kp_weight,3)?></td>
				<td class="rightAlign"><?php echo number_format($item->already_kp_money,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->not_kp_weight,3)?></td>
				<td class="rightAlign"><?php echo number_format($item->not_kp_money,2)?></td>
				
<!--				<td class="rightAlign"><?php echo number_format($item->should_xp_weight,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->should_xp_money,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->already_xp_weight,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->already_xp_money,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->not_xp_weight,2)?></td>
				<td class="rightAlign"><?php echo number_format($item->not_xp_money,2)?></td>-->
			</tr>
			<?php $i++;  }?>
			<tr>
				<td class="leftAlign table_cell_first">合计</td>
				<td class="leftAlign"></td>
				<?php if($view == "yewu"){?>
				<td class="leftAlign"></td>
				<?php }?>
				<td class="rightAlign"><?php echo number_format($totalData[0],3);?></td>
				<td class="rightAlign"><?php echo number_format($totalData[1],2);?></td>
				<td class="rightAlign"><?php echo number_format($totalData[2],3);?></td>
				<td class="rightAlign"><?php echo number_format($totalData[3],2);?></td>
				<td class="rightAlign"><?php echo number_format($totalData[4],3);?></td>
				<td class="rightAlign"><?php echo number_format($totalData[5],2);?></td>
			</tr>
		</tbody>
	</table>
</div>		
<?php paginate($pages,"wlhz")?>
<script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:<?php echo $view == "yewu"?"330":"220";?>,
    	 fixedRightWidth:0,
      });
   });
</script>
<script>
	$(function(){
		<?php if($msg){echo "confirmDialog('{$msg}');";}?>

		$(".del_user").click(function(){
			if (!window.confirm("确定要删除吗")) {
				return false;
			} else {
				$.post("<?php echo Yii::app()->createUrl('dictTitle/delete')?>", {
					'del_id' : $(this).attr('name')
				}, function(data) {
					window.location.reload();
				});
			}
		});
		
	});
</script>

<script type="text/javascript">

	$(function(){
		var array_tt=<?php echo $titles;?>;
		var array_tg=<?php echo $targets;?>;
		$('#combott').combobox(array_tt, {},"ttselect","combovaltt",false);
		$('#combotg').combobox(array_tg, {},"tgselect","combovaltg",false);
		$('#combotg1').combobox(array_tg, {},"tgselect1","combovaltg1",false);
	});
</script>