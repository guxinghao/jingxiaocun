<style>
<!--
.no_more{ width: 100%; height: 70px; line-height: 70px; font-size: 16px; font-weight: bold; text-align: center; color: #757575; border-width: 0 1px 1px 1px; border-style: solid; border-color: #d8d8d8; background-color: #fff;}
-->
</style>

<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1', 
			'tableHeader' => $tableHeader, 
			'tableData' => $tableData
	));
?>
<script type="text/javascript">
$(function(){
	$('#datatable1').datatable({
		fixedLeftWidth:160,
		fixedRightWidth:0,
	});

<?php if (count($tableData) == 0) {?>
	$(".datatable-rows, .scroll-wrapper").hide();
	$(".datatable").append('<div class="no_more">暂时没有内容</div>');
<?php }?>
});
</script>
</div>
<?php paginate($pages, "bill_list");?>

<script type="text/javascript">
<!--
$(function(){
	$("#each_page").unbind();
	$('.paginate_sel').unbind();
});
//-->
</script>