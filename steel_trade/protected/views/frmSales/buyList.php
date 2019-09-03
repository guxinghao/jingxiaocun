
<div class="div_table"  data-sortable='true'>
<?php
$this->widget('DataTableWdiget', array (
		'id' => 'datatable1',
		'tableHeader' =>$tableHeader,
		'tableData' =>$tableData
));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:70,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "sales_list")?>

<script>
  $(function(){
	  $("#each_page").unbind();
	  $('.paginate_sel').unbind();
})


  </script>