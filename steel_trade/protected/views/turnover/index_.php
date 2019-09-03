<style type="text/css">
	a{ cursor: pointer; }
	#isearch input{ line-height: 16px; }
</style>

<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('turnover/indexExport');?>">
		<img src="/images/daochu.png">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
</div>

<?php 
$form = $this->beginWidget('CActiveForm', array(
	'htmlOptions' => array(
		'id' => "user_search_form", 
		'enctype' => "multipart/form-data", 
		'url' => "",
	),
));
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>" />
		<input id="srarch" type="text" class="forreset" value="<?php echo $model->description;?>" placeholder="请输入关键字" name="Turnover[description]" />
	</div>

	<div class="search_date">
		<div style="float: left;">日期：</div>
		<div class="search_date_box">
			<input type="text" class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $model->start_time;?>" name="Turnover[start_time]"></input>
		</div>
		<div style="float: left; margin: 0 3px;">至</div>
		<div class="search_date_box">
			<input type="text" class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $model->end_time;?>" name="Turnover[end_time]"></input>
		</div>
	</div>

	<div class="more_one">
		<div class="more_one_l">公司：</div>
		<div id="title_select" class="fa_droplist">
			<input id="title_combo" type="text" class="forreset" value="<?php echo $model->title->short_name;?>" />
			<input id="title_val" type="hidden" class="forreset" value="<?php echo $model->title_id;?>" name="Turnover[title_id]" />
		</div>
	</div>

	<div class="more_one">
		<div class="more_one_l">结算单位：</div>
		<div id="target_select" class="fa_droplist">
			<input id="target_combo" type="text" class="forreset" value="<?php echo $model->target->short_name;?>" />
			<input id="target_val" type="hidden" class="forreset" value="<?php echo $model->target_id;?>" name="Turnover[target_id]" />
		</div>
	</div>

	<div class="more_select_box" style="top: 90px; left: 280px;">
		<div class="more_one">
			<div class="more_one_l">托盘公司：</div>
			<div id="proxy_select" class="fa_droplist">
				<input id="proxy_combo" type="text" class="forreset" value="<?php echo $model->proxyCompany->short_name;?>" />
				<input id="proxy_val" type="hidden" class="forreset" value="<?php echo $model->proxy_company_id;?>" name="Turnover[proxy_company_id]" />
			</div>
		</div>

		<div class="more_one">
			<div class="more_one_l">类别：</div>
			<select class="form-control chosen-select forreset" name="Turnover[big_type]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach (Turnover::$bigType as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->big_type == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">业务类型：</div>
			<select class="form-control chosen-select forreset" name="Turnover[turnover_type]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach (Turnover::$turnover_type as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->turnover_type == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">往来类型：</div>
			<select class="form-control chosen-select forreset" name="Turnover[turnover_direction]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach (Turnover::$turnover_direction as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->turnover_direction == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">往来状态：</div>
			<select class="form-control chosen-select forreset" name="Turnover[status]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach (Turnover::$status as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->status == $k ? 'selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">乙单：</div>
			<select class="form-control chosen-select forreset" name="Turnover[is_yidan]">
				<option value="" selected="selected">-全部-</option>
				<option value="0"<?php echo $model->is_yidan == '0' ? ' selected="selected"' : '';?>>甲单</option>
				<option value="1"<?php echo $model->is_yidan == '1' ? ' selected="selected"' : '';?>>乙单</option>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">审单状态：</div>
			<select class="form-control chosen-select forreset" name="Turnover[confirmed]">
				<option value="" selected="selected">-全部-</option>
				<option value="0"<?php echo $model->confirmed == '0' ? ' selected="selected"' : '';?>>未审单</option>
				<option value="1"<?php echo $model->confirmed == '1' ? ' selected="selected"' : '';?>>已审单</option>
			</select>
		</div>

		<div class="more_one">
			<input id="title_rl" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('瑞亮物资');?>" name="title_rl"<?php echo $_REQUEST['title_rl'] ? ' checked="checked"' : '';?> />
			<label for="title_rl" class="lab_check_box">瑞亮物资</label>
			<input id="title_cx" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('乘翔实业');?>" name="title_cx"<?php echo $_REQUEST['title_cx'] ? ' checked="checked"' : '';?> />
			<label for="title_cx" class="lab_check_box">乘翔实业</label>
			<input id="title_other" type="checkbox" class="check_box" value="other" name="other"<?php echo $_REQUEST['other'] ? ' checked="checked"' : '';?> />
			<label for="title_other" class="lab_check_box">其他</label>
		</div>
	</div>

	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" />
	<div class="more_toggle" title="更多"></div>
	<img src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置" />
</div>
<?php $this->endWidget();?>

<div class="div_table">
	<table id="datatable1" cellspacing="1" align="center" class="table datatable" style="display: none;">
		<thead>
			<tr class="data">
				<th class="table_cell_first" style="width: 50px;"></th>
				
				<th style="width: 160px;">创建时间</th>
				<th style="width: 110px;">公司</th>
				<th style="width: 110px;">结算单位</th>
				<th class="flex-col" style="width: 140px;">往来业务类型</th>
				<th class="flex-col" style="width: 110px;">往来类型</th>
				
				<th class="flex-col" style="width: 110px;">代理付费公司</th>
				<th class="flex-col" style="width: 60px;">乙单</th>
				<th class="flex-col" style="width: 90px;">类别</th>
				<th class="flex-col" style="width: 460px;">往来描述</th>
				<th class="flex-col rightAlign" style="width: 120px;">数量</th>
				
				<th class="flex-col rightAlign" style="width: 120px;">单价</th>
				<th class="flex-col rightAlign" style="width: 150px;">总金额</th>
				<th class="flex-col rightAlign" style="width: 150px;">余额</th>
				<th class="flex-col" style="width: 110px;">往来状态</th>
				<th class="flex-col" style="width: 120px;">负责人</th>
				
				<th class="flex-col" style="width: 120px;">经办人</th>
				<th class="flex-col" style="width: 120px;">入账人</th>
			</tr>
		</thead>

		<tbody>
		<?php $i = 1; 
		foreach ($items as $item) {?>
			<tr id="<?php echo $item->id;?>" class="data">
				<td class="table_cell_first" style="width: 50px;"><?php echo $i;?></td>
				<td style="width: 160px;"><?php echo $item->created_at > 0 ? date('Y-m-d H:i:s', $item->created_at) : '';?></td>
				<td style="width: 110px;">
					<span title="<?php echo $item->title->name;?>">
						<?php echo $item->title->short_name;?>
					</span>
				</td>
				<td style="width: 110px;">
					<span title="<?php echo $item->target->name;?>">
						<?php echo $item->target->short_name;?>
					</span>
				</td>
				<td style="width: 140px;">
					<?php echo Turnover::$turnover_type[$item->turnover_type];?>
				</td>
				<td style="width: 110px;">
					<?php echo Turnover::$turnover_direction[$item->turnover_direction];?>
				</td>
				<td style="width: 110px;">
					<span title="<?php echo $item->proxyCompany ? $item->proxyCompany->name : '';?>">
						<?php echo $item->proxyCompany ? $item->proxyCompany->short_name : '';?>
					</span>
				</td>
				<td style="width: 60px;">
					<?php echo $item->is_yidan == 1 ? '乙单' : '';?>
				</td>
				<td style="width: 90px;">
					<?php echo Turnover::$bigType[$item->big_type];?>
				</td>
				<td style="width: 460px;">
					<?php echo $item->description;?>
				</td>
				<td class="rightAlign" style="width: 120px;">
					<?php echo number_format($item->amount, 3);?>
				</td>
				<td class="rightAlign" style="width: 120px;">
					<?php echo $item->turnover_type == 'GKMX' ? number_format($item->price * 0.83, 2).'('.number_format($item->price, 2).')' : number_format($item->price, 2);?>
				</td>
				<td class="rightAlign" style="width: 150px;">
					<?php echo number_format(abs($item->fee), 2);?>
				</td>
				<td class="rightAlign" style="width: 150px;">
					<?php echo number_format(Turnover::getBalance($item->id), 2);?>
				</td>
				<td style="width: 110px;">
					<?php echo Turnover::$status[$item->status];?>
				</td>
				<td style="width: 120px;">
					<?php echo $item->owner->nickname;?>
				</td>
				<td style="width: 120px;">
					<?php echo $item->creater->nickname;?>
				</td>
				<td style="width: 120px;">
					<?php echo $item->account->nickname;?>
				</td>
			</tr>
		<?php $i++;}?>
		</tbody>
	</table>
</div>
<div class="total_data">
	<div class="total_data_one">总重：<?php echo number_format($totaldata->total_weight, 3);?></div>
	<div class="total_data_one">总金额：<?php echo number_format($totaldata->total_fee, 2);?></div>
</div>
<?php paginate($pages, 'to');?>

<script type="text/javascript">
	var title_array = <?php echo $title_array ? $title_array : '[]';?>;
	var target_array = <?php echo $target_array ? $target_array : '[]';?>;
	var proxy_array = <?php echo $proxy_array ? $proxy_array : '[]';?>;

	$(function(){
		$("#title_combo").combobox(title_array, {}, 'title_select', 'title_val', false);
		$("#target_combo").combobox(target_array, {}, 'target_select', 'target_val', false);
		$("#proxy_combo").combobox(proxy_array, {}, 'proxy_select', 'proxy_val', false);

		$("#datatable1").datatable({
			fixedLeftWidth: 440, 
			fixedRightWidth: 0,
		});

		$(".reset").click(function(){
			$("#title_rl, #title_cx, #title_other").removeAttr("checked");
		});
		$("#title_rl, #title_cx, #title_other").bind('click', function() {
			checkTitle();
		});
	});
</script>