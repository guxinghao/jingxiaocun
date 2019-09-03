<style type="text/css">
	a{ cursor: pointer; }
	#isearch input{ line-height: 16px; }
</style>
<?php $yidan_auto=!checkOperation('不看甲乙单');?>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('turnover/totalExport');?>">
		<img src="/images/daochu.png">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
</div>

<?php 
$form = $this->beginWidget('CActiveForm', array(
	'htmlOptions' => array(
		'id' => 'user_search_form', 
		'enctype' => 'multipart/form-data',
		'url' => "",
	),
));
?>
<div class="search_body">
	<div class="search_date">
		<div style="float: left;">日期：</div>
		<div class="search_date_box">
			<input type="text" class="form-control form-date forreset" value="<?php echo substr($model->start_time, 0, 10);?>" placeholder="选择日期" name="Turnover[start_time]" />
		</div>
		<div style="float: left; margin: 0 3px;">至</div>
		<div class="search_date_box">
			<input type="text" class="form-control form-date forreset" value="<?php echo $model->end_time;?>" placeholder="选择日期" name="Turnover[end_time]" />
		</div>
	</div>

	<div class="more_one">
		<div class="more_one_l">公司抬头：</div>
		<div id="title_select" class="fa_droplist">
			<input id="title_combo" type="text" class="forreset" value="<?php echo $model->title->short_name;?>" />
			<input id="title_val" type="hidden" class="forreset" value="<?php echo $model->title_id;?>" name="Turnover[title_id]" />
		</div>
	</div>
	
	<div class="more_one">
		<div class="more_one_l">结算单位：</div>
		<div id="target_select" class="fa_droplist">
			<input id="target_combo" type="text" class="forreset" value="<?php echo $model->target->name;?>" />
			<input id="target_val" type="hidden" class="forreset" value="<?php echo $model->target_id;?>" name="Turnover[target_id]" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="target_select1" class="fa_droplist">
			<input id="target_combo1" type="text" class="forreset" value="<?php echo $model->client->name;?>" />
			<input id="target_val1" type="hidden" class="forreset" value="<?php echo $model->client_id;?>" name="Turnover[client_id]" />
		</div>
	</div>
	<div class="more_select_box" style="top: 125px; left: 280px;">
		<div class="more_one">
			<div class="more_one_l">类别：</div>
			<select class="form-control chosen-select forreset" name="Turnover[big_type]">
				<option value="" selected="selected">-全部-</option>
			<?php foreach (Turnover::$bigType as $k => $v) {?>
				<option value="<?php echo $k;?>"<?php echo $model->big_type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
			<?php }?>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">期末余额：</div>
			<select class="form-control chosen-select forreset" name="final_balance">
				<option value="all"<?php echo $_REQUEST['final_balance'] == 'all' ? ' selected="selected"' : ''?>>-全部-</option>
				<option value="wrong"<?php echo $_REQUEST['final_balance'] == 'wrong' ? ' selected="selected"' : ''?>>非0</option>
				<option value="positive"<?php echo $_REQUEST['final_balance'] == 'positive' ? ' selected="selected"' : ''?>>大于0</option>
				<option value="negative"<?php echo $_REQUEST['final_balance'] == 'negative' ? ' selected="selected"' : ''?>>小于0</option>
			</select>
		</div>

		<div class="more_one">
			<div class="more_one_l">业务员：</div>
				<select name="Turnover[ownered_by]" class='form-control chosen-select forreset owned'>
				<?php if(checkOperation('全部往来汇总')){?>
		            <option value='0' selected='selected'>-全部-</option>
		             <?php foreach ($user_array as $k=>$v){?>
	            <option <?php echo $k==$model->ownered_by?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
	            <?php }}else{?>
	            <option value='<?php echo currentUserId()?>'><?php echo Yii::app()->user->nickname?></option>
	            <?php }?>
		        </select>
		</div>
	<?php if($yidan_auto){?>
		<div class="more_one">
			<div class="more_one_l">乙单：</div>
			<select class="form-control chosen-select forreset" name="Turnover[is_yidan]">
				<option value="" selected="selected">-全部-</option>
				<option value="0"<?php echo $model->is_yidan == '0' ? ' selected="selected"' : '';?>>甲单</option>
				<option value="1"<?php echo $model->is_yidan == '1' ? ' selected="selected"' : '';?>>乙单</option>
			</select>
		</div>
<?php }?>
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
	<table id="datatable1" cellspacing="1" align="center" class="table datatable" style="display: none">
		<thead>
			<tr class="data">
				<th width="20" class='table_cell_first'></th>
				<th width="60" class="leftAlign">公司抬头</th>
				<th width="110" class="leftAlign">结算单位</th>
				<th width="110" class="leftAlign">客户</th>
				<th width="130" class="rightAlign">期末余额</th>
				
				<th width="110" class="flex-col rightAlign">采购明细</th>
				<th width="100" class="flex-col rightAlign">运费</th>
				<th width="100" class="flex-col rightAlign">采购折让</th>
				<th width="110" class="flex-col rightAlign">采购退货</th>
				<th width="110" class="flex-col rightAlign">托盘采购</th>
				<th width="100" class="flex-col rightAlign">托盘赎回计息</th>
				<th width="110" class="flex-col rightAlign">付款登记</th>
				<th width="110" class="flex-col rightAlign">销售明细</th>
				<th width="100" class="flex-col rightAlign">销售折让</th>
				<th width="100" class="flex-col rightAlign">销售退货</th>
				<th width="100" class="flex-col rightAlign">净销售重量</th>
				<th width="110" class="flex-col rightAlign">收款登记</th>
				<th width="100" class="flex-col rightAlign">仓库返利</th>
				<th width="100" class="flex-col rightAlign">钢厂返利</th>
				<th width="100" class="flex-col rightAlign">仓储费用</th>
				<th width="100" class="flex-col rightAlign">高开明细</th>
				
				<!-- <th width="120" class="flex-col rightAlign">已收款</th>
				<th width="120" class="flex-col rightAlign">已付款</th> -->
				<th width="130" class="flex-col rightAlign">期初余额</th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 1; foreach($items as $item) {
			$detail_url = Yii::app()->createUrl('turnover/index', array(
				'title_id' => $item->title_id, 
				'target_id' => $item->target_id,
				'client_id' => $item->client_id,
				'start_time' => $model->start_time, 
				'end_time' => $model->end_time,
				'is_yidan' =>  $model->is_yidan,
				'big_type'=>$model->big_type,
				'ownered_by'=>$model->ownered_by,
				'title_rl'=>$_REQUEST['title_rl']?'checked':'',
				'title_cx'=>$_REQUEST['title_cx']?'checked':'',
				'other'=>$_REQUEST['other']?'checked':'',
				'confirmed'=>$model->confirmed,
			));
			?>
			<tr class="data">
				<td class="table_cell_first"><?php echo $i;?></td>
				<td>
					<span title="<?php echo $item->title->name;?>">
						<?php echo $item->title->short_name;?>
					</span>
				</td>
				<td>
					<span title="<?php echo $item->target->name;?>">
						<?php echo $item->target->short_name;?>
					</span>
				</td>
				<td>
					<span title="<?php echo $item->client->name;?>">
						<?php echo $item->client->short_name;?>
					</span>
				</td>
				<td class="rightAlign">
					<a href="<?php echo $detail_url; ?>"><?php echo number_format($item->final_balance, 2);?></a>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->purchase_detail, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->freight, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->purchase_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->purchase_return, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->tray_purchase, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->pallet_redemption, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->payment, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->sales_detail, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->sales_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->sales_return, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->sales_amount-$item->sales_return_amount, 3);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->receivables, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->warehouse_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->mills_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->storage_charge, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->gk_detail, 2);?>
				</td>
		<!-- 		<td class="rightAlign">
					<?php echo number_format($item->already_collection, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($item->already_paid, 2);?>
				</td> -->
				<td class="rightAlign">
					<?php echo number_format($item->initial_balance, 2);?>
				</td>
			</tr>
		<?php $i++; }?>
			<tr class="data">
				<td class="table_cell_first" colspan="4">合计</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->final_balance, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->purchase_detail, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->freight, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->purchase_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->purchase_return, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->tray_purchase, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->pallet_redemption, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->payment, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->sales_detail, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->sales_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->sales_return, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->sales_amount-$totaldata->sales_return_amount, 3);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->receivables, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->warehouse_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->mills_rebate, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->storage_charge, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->gk_detail, 2);?>
				</td>
				<!-- <td class="rightAlign">
					<?php echo number_format($totaldata->already_collection, 2);?>
				</td>
				<td class="rightAlign">
					<?php echo number_format($totaldata->already_paid, 2);?>
				</td> -->
				<td class="rightAlign">
					<?php echo number_format($totaldata->initial_balance, 2);?>
				</td>
			</tr>
		</tbody>
	</table>
</div>			
<?php paginate($pages, "to");?>


<script type="text/javascript">
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>

	var title_array = <?php echo $title_array ? $title_array : '[]';?>;
	var target_array = <?php echo $target_array ? $target_array : '[]';?>;
//	var user_array = <?php echo $user_array ? $user_array : '[]';?>;

	$(function(){
		$("#title_combo").combobox(title_array, {}, 'title_select', 'title_val', false);
		$("#target_combo").combobox(target_array, {}, 'target_select', 'target_val', false);
		$("#target_combo1").combobox(target_array, {}, 'target_select1', 'target_val1', false);
//		$("#user_combo").combobox(user_array, {}, 'user_select', 'user_val', false);

		$("#datatable1").datatable({
			fixedLeftWidth: 500, 
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