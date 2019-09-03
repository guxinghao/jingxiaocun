<?php 
$form = $this->beginWidget('CActiveForm', array(
	'htmlOptions' => array(
		'id' => "user_search_form", 
		'enctype' => "multipart/form-data", 
	),
));
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>" />
		<input id="srarch" type="text" class="forreset" value="<?php echo $search['keywords'];?>" placeholder="请输入单号|备注" name="search[keywords]" />
	</div>

	<div class="search_date">
		<div style="float: left;">日期：</div>
		<div class="search_date_box">
			<input id="start_time" type="text" class="form-control form-date forreset" value="<?php echo $search['start_time'];?>" placeholder="开始日期" name="search[start_time]" />
		</div>
		<div style="float: left; margin: 0 3px;">至</div>
		<div class="search_date_box">
			<input id="end_time" type="text" class="form-control form-date forreset" value="<?php echo $search['end_time'];?>" placeholder="结束日期" name="search[end_time]" />
		</div>
	</div>

	<div class="more_one">
		<div class="more_one_l">公司：</div>
		<div id="title_select" class="fa_droplist">
			<input id="title_combo" type="text" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input id="title_val" type="hidden" class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]">
		</div>
	</div>

	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="customer_select" class="fa_droplist">
			<input id="customer_combo" type="text" class="forreset" value="<?php echo $search['customer_name'];?>" name="search[customer_name]" />
			<input id="customer_val" type="hidden" class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>

	<div class="more_select_box">
		<div class="more_one">
			<div class="more_one_l">业务员：</div>
			<select id="owned_by" class="form-control chosen-select forreset" name="search[owned_by]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach ($user_array as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $k == $search['owned_by'] ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">销售类型：</div>
			<select id="sales_type" class="form-control chosen-select forreset" name="search[sales_type]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach (FrmSales::$sales_type as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $k == $search['sales_type'] ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">产地：</div>
			<div id="brand_select" class="fa_droplist">
				<input id="brand_combo" type="text" class="forreset" value="<?php echo $search['brand_name'];?>" name="search[brand_name]" />
				<input id="brand_val" type="hidden" class="forreset" value="<?php echo $search['brand_id'];?>" name="search[brand_id]" />
			</div>
		</div>

		<div class="more_one">
			<div class="more_one_l">品名：</div>
			<select id="product_id" class="form-control chosen-select forreset" name="search[product_id]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach ($product_array as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $k == $search['product_id'] ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">规格：</div>
			<select id="texture_id" class="form-control chosen-select forreset" name="search[texture_id]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach ($texture_array as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $k == $search['texture_id'] ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">材质：</div>
			<select id="rank_id" class="form-control chosen-select forreset" name="search[rank_id]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach ($rank_array as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $k == $search['rank_id'] ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

	</div>

	<input type="submit" class="btn btn-primary btn-sm btn_sub" data-dismiss="modal" value="查询" />
	<div class="more_toggle" title="更多"></div>
	<img src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php $this->endWidget();?>

<div class="div_table" data-sortable="true">
<?php 
$this->widget('DataTableWdiget', array(
	'id' => "datatable1", 
	'tableHeader' => $tableHeader, 
	'tableData' => $tableData, 
	'hide' => 1,
));
?>

<script type="text/javascript">
	$(function() {
		$("#datatable1").datatable({
			fixedLeftWidth: 280, 
			fixedRightWidth: 0,
		});
	});
</script>
</div>
<?php paginate($pages, "variance_list");?>

<script type="text/javascript">
	var title_array = <?php echo $title_array ? $title_array : '[]';?>;
	var customer_array = <?php echo $customer_array ? $customer_array : '[]';?>;
	var user_array = <?php echo $user_array ? $user_array : '[]';?>;

	var brand_array = <?php echo $brand_array ? $brand_array : '[]';?>;
	var product_array = <?php echo $product_array ? $product_array : '[]';?>;

	$(function() {
		$("#title_combo").combobox(title_array, {}, 'title_select', 'title_val', false);
		$("#customer_combo").combobox(customer_array, {}, 'customer_select', 'customer_val', false);

		$("#brand_combo").combobox(brand_array, {}, 'brand_select', 'brand_val', false); //产地
	});
</script>