<html>
<head>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/css/zui.min.css"	rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css"	rel="stylesheet" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery_ui.css" />
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.0.min.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<!-- ZUI Javascript组件 -->
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
</head>
<body style="min-height: 150px;">
<div class="pop_title" style="text-align: center">托盘详情</div>
<div class="div_table"  data-sortable='true' >
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData
	));
?>
 </div>
 <!--  --
<div class="btn_list"   style="padding:8px 16px 0;">
	<button type="submit" style="float:right;" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">确定</button>	
</div>-->

<script>
$(function(){
    $('#datatable1').datatable({
   	 fixedLeftWidth:0,
   	 fixedRightWidth:0,
     });
  });
    //确定，加载数据
    $('#submit_btn').click(function(){
		parent.$.colorbox.close();
	});
</script>
</body>
</html>
