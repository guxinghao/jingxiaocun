<style>
.cz_list_btn_more{height:70px;}
.sales_export{cursor: pointer;}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<form method="post" action="" url="">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入销售单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="<?php echo $search?$search['time_L']:date("Y-m-d");?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="<?php echo $search?$search['time_H']:date("Y-m-d");?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset owned'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($users as $k=>$v){?>
        	<option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
        	<?php }?>
        </select>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:0px;">
	<!-- <div class="more_toggle" title="更多"></div> -->
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
<script type="text/javascript">
$(function(){
	$('#datatable').datatable({
		fixedLeftWidth:180,
		fixedRightWidth:0,
	});
});
</script>
</div>
<?php paginate($pages, "sales_list")?>
<script type="text/javascript">
$(function(){
	//搜索条件重置按钮
	$(".reset").click(function(){
		$(".forreset").val('');
		$("#combo").val('');
		$("#comboval").val('');
		$("#combo2").val('');
		$("#combval").val('');
	});
});
</script>