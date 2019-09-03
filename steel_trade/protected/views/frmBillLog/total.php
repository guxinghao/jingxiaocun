<style type="text/css">
	a{ cursor: pointer; }
	#isearch input{ line-height: 16px; }
	.color_gray{ background-color: #f4f4f4; }
</style>

<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('frmBillLog/totalExport');?>">
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
		'url' => ""
	),
));
?>
<div class="search_body">
	<div class="search_date">
		<div style="float: left;">日期：</div>
		<div class="search_date_box">
			<input type="text" class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $model->st;?>" name="FrmBillLog[st]"></input>
		</div>
		<div style="float: left; margin: 0 3px;">至</div>
		<div class="search_date_box">
			<input type="text" class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $model->et;?>" name="FrmBillLog[et]"></input>
		</div>
	</div>



	<div class="more_one">
		<div class="more_one_l">资金账户：</div>

		 <select name="FrmBillLog[dict_bank_id]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($dict_bank_array as $k=>$v){?>
        <option <?php echo $k==$model->dict_bank_id?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
        <?php }?>
        </select>


		<!-- <div id="dict_bank_select" class="fa_droplist">
			<input id="dict_bank_combo" type="text" class="forreset" value="<?php echo $model->title_bank->dict_name;?>"></input>
			<input id="dict_bank_val" type="hidden" class="forreset" value="<?php echo $model->dict_bank_id;?>" name="FrmBillLog[dict_bank_id]"></input>
		</div> -->
	</div>

	<div class="more_one">
		<div class="more_one_l">结算单位：</div>
		<div id="company_select" class="fa_droplist">
			<input id="company_combo" type="text" class="forreset" value="<?php echo $model->company->name;?>"></input>
			<input id="company_val" type="hidden" class="forreset" value="<?php echo $model->company_id;?>" name="FrmBillLog[company_id]"></input>
		</div>
	</div>

	<div class="more_one">
		<input id="title_rl" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('瑞亮物资');?>" name="title_rl"<?php echo $_REQUEST['title_rl']||$_COOKIE['search_totalbill_titlerl'] ? ' checked="checked"' : '';?> />
		<label for="title_rl" class="lab_check_box">瑞亮物资</label>
		<input id="title_cx" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('乘翔实业');?>" name="title_cx"<?php echo $_REQUEST['title_cx']||$_COOKIE['search_totalbill_titlecx'] ? ' checked="checked"' : '';?> />
		<label for="title_cx" class="lab_check_box">乘翔实业</label>
		<input id="title_other" type="checkbox" class="check_box" value="other" name="other"<?php echo $_REQUEST['other']||$_COOKIE['search_totalbill_other'] ? ' checked="checked"' : '';?> />
		<label for="title_other" class="lab_check_box">其他</label>
	</div>

    <input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" />
    <img src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置" />
</div>
<?php $this->endWidget ();?>					

<div class="div_table">
	<table id="datatable1" cellspacing="1" align="center" class="table datatable">
		<thead>
			<tr class="data">
				<th class="table_cell_first" style="width: 50px;">操作</th>
				<th style="width: 260px;">资金账户</th>
				<th class="flex-col rightAlign" style="width: 150px;">期末余额</th>
				<th class="flex-col rightAlign" style="width: 150px;">本期入账</th>
				<th class="flex-col rightAlign" style="width: 150px;">本期出账</th>
				<th class="flex-col rightAlign" style="width: 150px;">期初余额</th>
			</tr>
		</thead>
		
		<tbody>
		<?php $i = 0; 
		foreach ($items as $item) {
			$detail_url = Yii::app()->createUrl('frmBillLog/index', array(
				'account_id' => $item->id, 
				'company_id' => $model->company_id, 
				'st' => $model->st, 
				'et' => $model->et
			));
		?>
			<tr id="<?php echo $item->id;?>" class="data<?php echo $i % 2 != 0 ? 'color_gray' : '';?>">
				<td class="table_cell_first">
					<a href="<?php echo $detail_url;?>" class="update_b">
						<span title="查看详细"><img src="/images/detail.png"/></span>
					</a>
				</td>
				<td>
					<span title="<?php echo $item->dict_name.'('.$item->bank_number.')';?>">
						<?php echo $item->dict_name;?>
					</span>			
				</td>
				<td class="flex-col rightAlign"><?php echo number_format($item->final_balance, 2);?></td>
				<td class="flex-col rightAlign"><?php echo number_format($item->current_in, 2);?></td>
				<td class="flex-col rightAlign"><?php echo number_format($item->current_out, 2);?></td>
				<td class="flex-col rightAlign"><?php echo number_format($item->initial_balance, 2);?></td>
			</tr>
		<?php $i++; }?>
			<tr>
				<td class="table_cell_first" colspan="2">合计</td>
				<td class="flex-col rightAlign"><?php echo number_format($totaldata->final_balance, 2);?></td>
				<td class="flex-col rightAlign"><?php echo number_format($totaldata->current_in, 2);?></td>
				<td class="flex-col rightAlign"><?php echo number_format($totaldata->current_out, 2);?></td>
				<td class="flex-col rightAlign"><?php echo number_format($totaldata->initial_balance, 2);?></td>
			</tr>
		</tbody>
	</table>
</div>

<script type="text/javascript">
<?php if ($msg) {?>
	confirmDialog("<?php echo $msg;?>");
<?php }?>

	var company_array = <?php echo $company_array ? $company_array : '[]';?>;
	var dict_bank_array = <?php echo $dict_bank_array ? $dict_bank_array : '[]';?>;

	$(function(){
		$("#company_combo").combobox(company_array, {}, 'company_select', 'company_val', false);
		$("#dict_bank_combo").combobox(dict_bank_array, {}, 'dict_bank_select', 'dict_bank_val', false, '', 260);

		$(".reset").click(function(){
			$("#title_rl, #title_cx, #title_other").removeAttr("checked");
		});
		$("#title_rl, #title_cx, #title_other").bind('click', function() {
			checkTitle();
		});
	});
</script>
