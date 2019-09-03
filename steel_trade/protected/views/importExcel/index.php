<?php 
$form = $this->beginWidget('CActiveForm', array(
	'htmlOptions' => array(
		'id' => "import_form", 
		'enctype' => "multipart/form-data", 
	),
));
?>
<div class="search_body">
	<div class="more_one">
		<div class="more_one_l">收款file：</div>
		<input type="file" class="form-control" name="import"></input>
	</div>

	<input type="submit" class="btn btn-primary btn-sm btn_sub" data-dismiss="modal" value="导入" />
</div>
<?php $this->endWidget();?>

<script type="text/javascript">
<?php if ($msg) {?>
	alert("<?php echo $msg;?>");
<?php }?>
</script>