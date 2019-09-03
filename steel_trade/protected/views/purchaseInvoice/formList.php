<div class="div_table"  data-sortable="true">
<?php
$this->widget('DataTableWdiget', array (
		'id' => 'datatable1',
		'tableHeader' =>$tableHeader,
		'tableData' =>$tableData
));
?>
 <script type="text/javascript">
<!--
$(function(){
	$(".datatable1").datatable({
		fixedLeftWidth: 60, 
		fixedRightWidth: 0,
	});
});
//-->
</script>
</div>
<div class="total_data">
	<div class="total_data_one">总重量：<span><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">总金额：<span class="color_org"><?php echo number_format($totalData["money"],2);?></span></div>
	<div class="total_data_one">未销重量：<span><?php echo number_format($totalData["uncheck_weight"],3);?></span></div>
	<div class="total_data_one" >未销金额：<span class="color_org"><?php echo number_format($totalData["uncheck_money"],2);?></span></div>
</div>
<?php paginate($pages, "invoice_list")?>

<script type="text/javascript">
<!--
$(function(){
	$("#each_page").unbind();
	$(".paginate_sel").unbind();
});
//-->
</script>