<style>
<!--
.td_value{ width: 30%;}
-->
</style>

<div class="con_tit" style="display: none;">
	<div class="con_tit_cz"></div>
</div>

<?php 
$form = $this->beginWidget('CActiveForm', array(
		'htmlOptions' => array(
				'id' => "search_form", 
				'enctype' => "multipart/form-data",
		),
));
?>

<div class="search_body">
	<div class="srarch_box">
		<img alt="" src="<?php echo imgUrl('search.png');?>" />
		<input id="SysConfig_sys_name" type="text" class="forreset" value="<?php echo $model->sys_name;?>" placeholder="请输入配置名" name="SysConfig[sys_name]" />
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub" data-dismiss="modal" value="查询" />
	<img title="重置" src="<?php echo imgUrl('reset.png');?>" class="reset" />
</div>

<?php $this->endWidget();?>

<div class="div_table">
	<table id="datatable1" class="table datatable" cellspacing="1" align="center">
		<thead>
			<tr>
				<th width="100" class="table_cell_first">配置名</th>
				<th width="100">配置值</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($items as $item) {?>
			<tr id="<?php echo $item->id;?>">
				<td class="table_cell_first"><?php echo $item->sys_name;?></td>
				<td>
					<input type="text" class="td_value" value="<?php echo $item->value;?>" />
				</td>
			</tr>
		<?php }?>
		</tbody>
	</table>
	<div style="width:99%;margin-bottom:20px" class="btn_list ">
		<button id="save" type="button" data-dismiss="modal" class="btn btn-primary btn-sm  save">保存</button>
	</div>
</div>
<?php paginate($pages, 'sys_config');?>

<script type="text/javascript">
<!--

$("#save").click(function() {
	var ids = "";
	var values = "";
	$(".td_value").each(function() {
		var id = $(this).parent().parent().attr('id');
		var value = $(this).val();
		ids += ',' + id;
		values += ',' + value;
	});
	ids = ids.substring(1);
	values = values.substring(1);
	$.post('/index.php/sysConfig/postUpdate', 
	{
		'ids': ids,
		'values': values,
	}, 
	function(data) {
		if (data == 1) confirmDialog("修改成功");
		else confirmDialog("修改失败", function() { window.location.replace(); });
	}); 
});
//-->
</script>






