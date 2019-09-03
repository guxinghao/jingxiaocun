<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable3', 
			'tableHeader' => $tableHeader, 
			'tableData' => $tableData
	));
?>
<script type="text/javascript">
<!--
$(function(){
	$('#datatable3').datatable({
		fixedLeftWidth: 400, 
		fixedRightWidth: 0
	});

<?php if (count($tableData) == 0) {?>
	$(".datatable-rows, .scroll-wrapper").hide();
	$(".datatable").append('<div class="no_more">暂时没有内容</div>');
<?php }?>
});
//-->
</script>
</div>
<?php paginate($pages, "ownedSales_list");?>

<script type="text/javascript">
<!--
$(function(){
	$("#each_page").unbind();
	$('.paginate_sel').unbind();
});
//-->
</script>