<form method="post" action="" url="">
	<div class="search_body">
		<div class="srarch_box">
			<img src="<?php echo imgUrl('search.png');?>">
			<input placeholder="请输入单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
		</div>
		<div class="search_date">
			<div style="float:left">日期：</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="<?php echo $search['time_L'];?>" name="search[time_L]">
			</div>
			<div style="float:left;margin:0 3px;">至</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="<?php echo $search['time_H'];?>" name="search[time_H]"  >
			</div>
		</div>
		<div class="shop_more_one1 short_shop_more_one" style="margin-left:15px;">
		<div style="float:left;">类别：</div>
		 <select name="search[form_type]" class='form-control chosen-select forreset form_status'>
	         <option value='0' selected='selected'>-全部-</option>
           	 <option <?php echo $search['form_type']=="XSD"?'selected="selected"':''?>  value="XSD">销售</option>
           	 <option <?php echo $search['form_type']=="XSTH"?'selected="selected"':''?>  value="XSTH">销售退货</option>
           	 <option <?php echo $search['form_type']=="CGD"?'selected="selected"':''?>  value="CGD">采购</option>
           	 <option <?php echo $search['form_type']=="CGTH"?'selected="selected"':''?>  value="CGTH">采购退货</option>
           	 <option <?php echo $search['form_type']=="YHHZ"?'selected="selected"':''?>  value=YHHZ>银行互转</option>
           	 <option <?php echo $search['form_type']=="SKDJ"?'selected="selected"':''?>  value="SKDJ">收款</option>
           	 <option <?php echo $search['form_type']=="FKDJ"?'selected="selected"':''?>  value="FKDJ">付款</option>
           	 <option <?php echo $search['form_type']=="DQJK"?'selected="selected"':''?>  value="DQJK">短期借贷</option>
	     </select>
	</div>
		<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:0px;">
		<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
	</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			//'totalData' =>$totalData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:0,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "ignore_list")?>
<script>
$(function(){
		$(".cancel_form").on("click",function(){
			var href = $(this).attr('url');
			var text = '您确认要恢复此条凭证吗';
			confirmDialog2(text,href);
		});
})
</script>