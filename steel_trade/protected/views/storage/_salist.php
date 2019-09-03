<div class="div_table"  data-sortable='true'>
<?php
$this->widget('DataTableWdiget', array(
		'id' => 'storagetable',
		'tableHeader' =>$tableHeader,
		'tableData' =>$tableData,
		'totalData' =>$totalData
));
?>
</div>
<?php paginate1($pages,"storage_list");?>