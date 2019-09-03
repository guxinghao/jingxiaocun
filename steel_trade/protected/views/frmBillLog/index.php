<style type="text/css">
	a{ cursor: pointer; }
	#isearch input{ line-height: 16px; }
</style>

<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('frmBillLog/indexExport');?>">
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
		'action'=>array('frmBillLog/index'),
		'url' => Yii::app()->createUrl('frmBillLog/index'),
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
		<div class="more_one_l">公司简称：</div>
		<div id="title_select" class="fa_droplist">
			<input id="title_combo" type="text" class="forreset" value="<?php echo $model->title->short_name;?>"></input>
			<input id="title_val" type="hidden" class="forreset" value="<?php echo $model->title_id;?>" name="FrmBillLog[title_id]"></input>
		</div>
	</div>

	<div class="more_one">
		<div class="more_one_l">资金账户：</div>
		<div id="dict_bank_select" class="fa_droplist">
			<input id="dict_bank_combo" type="text" class="forreset" value="<?php echo $model->title_bank->dict_name;?>"></input>
			<input id="dict_bank_val" type="hidden" class="forreset" value="<?php echo $model->dict_bank_id;?>" name="FrmBillLog[dict_bank_id]"></input>
		</div>
	</div>

	<div class="more_select_box" style="top: 125px; left: 280px;">
		<div class="more_one">
			<div class="more_one_l">结算单位：</div>
			<div id="company_select" class="fa_droplist">
				<input id="company_combo" type="text" class="forreset" value="<?php echo $model->company->name;?>"></input>
				<input id="company_val" type="hidden" class="forreset" value="<?php echo $model->company_id;?>" name="FrmBillLog[company_id]"></input>
			</div>
		</div>

		<div class="more_one">
			<div class="more_one_l">入账人：</div>
			 <select name="FrmBillLog[account_by]" class='form-control chosen-select forreset owned'>
		        <option value='0' selected='selected'>-全部-</option>
		        <?php foreach ($users as $k=>$v){?>
	            <option <?php echo $k==$model->account_by?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
	            <?php }?>
		        </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">负责人：</div>
			 <select name="search[owned_by]" class='form-control chosen-select forreset owned'>
		        <option value='0' selected='selected'>-全部-</option>
		        <?php foreach ($users as $k=>$v){?>
	            <option <?php echo $k==$search['owned_by']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
	            <?php }?>
		     </select>
		</div>
		<div class="more_one">
			<div class="more_one_l" style="width: 85px;">出入账类型：</div>
			<select id="FrmBillLog_account_type" class="form-control chosen-select forreset" name="FrmBillLog[account_type]" style="width:135px;">
				<option value="" selected="selected">-全部-</option>
				<option value="out"<?php echo $model->account_type == 'out' ? ' selected="selected"' : '';?>>出账</option>
				<option value="in"<?php echo $model->account_type == 'in' ? ' selected="selected"' : '';?>>入账</option>
			</select>
		</div>

		<div class="more_one" >
			<div class="more_one_l">收付类型：</div>
			<select name="FrmBillLog[bill_type]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>
	        <?php foreach (FrmBillLog::$billType as $k => $v) {?>
	        	<option value="<?php echo $k;?>"<?php echo $model->bill_type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
	        <?php }?>
		    </select>
			
		</div>

		<div class="more_one" style="width:240px">
			<div class="more_one_l" style="width:90px">收付款方式：</div>
			<select name="Turnover[turnover_direction]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	  
	            <?php foreach (FrmFormBill::$payTypes as $k=>$v){?>             
       			<option <?php echo $model->pay_type==$k?' selected="selected"':''?>  value="<?php echo $k;?>"><?php echo $v;?></option>
       			<?php }?>
		    </select>
		</div>

		<div class="more_one">
			<input id="title_rl" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('瑞亮物资');?>" name="title_rl"<?php echo $_REQUEST['title_rl']||$_COOKIE['search_detailbill_titlerl'] ? ' checked="checked"' : '';?> />
			<label for="title_rl" class="lab_check_box">瑞亮物资</label>
			<input id="title_cx" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('乘翔实业');?>" name="title_cx"<?php echo $_REQUEST['title_cx']||$_COOKIE['search_detailbill_titlecx'] ? ' checked="checked"' : '';?> />
			<label for="title_cx" class="lab_check_box">乘翔实业</label>
			<input id="title_other" type="checkbox" class="check_box" value="other" name="other"<?php echo $_REQUEST['other']||$_COOKIE['search_detailbill_other'] ? ' checked="checked"' : '';?> />
			<label for="title_other" class="lab_check_box">其他</label>
		</div>
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub" data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置" />
</div>
<?php $this->endWidget();?>

<div class="div_table">
	<table id="datatable1" cellspacing="1" align="center" class="table datatable" style="display: none;">
		<thead>
			<tr class="data">
				<th class="table_cell_first" style="width: 80px;">日期</th>
				<th style="width: 100px;">单据号</th>
				<th style="width: 200px;">公司资金账户</th>
				<th style="width: 80px;">结算单位</th>
				<th class="flex-col rightAlign" style="width: 110px;">入帐金额</th>
				<th class="flex-col rightAlign" style="width: 110px;">出帐金额</th>
				<th class="flex-col rightAlign" style="width: 110px;">余额</th>
				<th class="flex-col" style="width: 90px;">收付类型</th>
				<th class="flex-col" style="width: 90px;">收付款方式</th>
				<th class="flex-col" style="width: 60px;">公司简称</th>
				<th class="flex-col" style="width: 70px;">负责人</th>
				<th class="flex-col" style="width: 70px;">入账人</th>
				<th class="flex-col" style="width: 210px;">备注</th>
			</tr>
		</thead>

		<tbody>
		<?php $i = 0; foreach ($items as $item) {
			$company = $item->company;
			switch ($item->bill_type) {
				case '1': //销售收款
					$view_url = Yii::app()->createUrl('formBill/view', array(
						'id' => $item->form_id, 
						'back_url' => "frmBillLog", 
						'fpage' => $_REQUEST['page'],
					));
					break;
				case '2': //采购付款
					$view_url = Yii::app()->createUrl('formBill/view', array(
						'id' => $item->form_id,
						'back_url' => "frmBillLog", 
						'fpage' => $_REQUEST['page'],
					));
					break;
				case '3': //其他收入
					$view_url = Yii::app()->createUrl('billOther/view', array(
						'id' => $item->form_id,
						'back_url' => "frmBillLog", 
						'fpage' => $_REQUEST['page'],
					));
					break;
				case '4': //费用报支
					$view_url = Yii::app()->createUrl('billOther/view', array(
						'id' => $item->form_id,
						'back_url' => "frmBillLog", 
						'fpage' => $_REQUEST['page'],
					));
					break;
				case '5': //银行互转
					$company = $item->opposite_title;
					$view_url = Yii::app()->createUrl('transferAccounts/view', array(
						'id' => $item->form_id,
						'back_url' => "frmBillLog", 
						'fpage' => $_REQUEST['page'],
					));
					break;
				case '6': //短期借贷
					$view_url = Yii::app()->createUrl('shortLoan/view', array(
						'id' => $item->loanRecord->shortLoan->baseform->id,
						'back_url' => "frmBillLog", 
						'fpage' => $_REQUEST['page'],
					));
					break;
				default: 
					continue;
					break;
			}
		?>
			<?php if(!$item->id){?>
			<tr id="" class="data">
				<td class="table_cell_first" style="width: 80px;"></td>
				<td style="width: 100px;"></td>
				<td style="width: 200px;"><?php  echo $item->dict_bank_id;?></td>
				<td style="width: 80px;"></td>
				<td class="rightAlign" style="width: 110px;"></td>
				<td class="rightAlign" style="width: 110px;"></td>
				<td class="rightAlign" style="width: 110px;"><?php echo number_format($qichu,2);?></td>
				<td style="width: 90px;"></td>
				<td style="width: 90px;"></td>
				<td style="width: 60px;"></td>
				<td style="width: 70px;"></td>
				<td style="width: 70px;"></td>
				<td style="width: 210px;"></td>
			</tr>
			<?php }else{?>
			<tr id="<?php echo $item->id;?>" class="data">
				<td class="table_cell_first" style="width: 80px;">
					<?php echo $item->reach_at > 0 ? date("Y-m-d", $item->reach_at) : '';?>
				</td>
				<td style="width: 100px;">
					<a href="<?php echo $view_url;?>"><?php echo $item->form_sn;?></a>
				</td>
				<td style="width: 200px;">

					<span title="<?php echo $item->title_bank->dict_name.'('.$item->title_bank->bank_number.')';?>"><?php echo $item->title_bank->dict_name;?></span>
				</td>
				<td style="width: 80px;">
					<span title="<?php echo $company->name;?>" ><?php echo $company->short_name;?></span>
				</td>
				<td class="rightAlign" style="width: 110px;">
					<?php echo $item->account_type == 'in' ? number_format($item->fee, 2) : '';?>
				</td>
				<td class="rightAlign" style="width: 110px;">
					<?php echo $item->account_type == 'out' ? number_format($item->fee, 2) : '';?>
				</td>

				<td class="rightAlign" style="width: 110px;">
					<?php $qichu=$qichu+($item->account_type=='in'?$item->fee:(-$item->fee)); echo number_format($qichu,2);?>
				</td>

				<td style="width: 90px;">
					<?php echo FrmBillLog::$billType[$item->bill_type];?>
				</td>
				<td style="width: 90px;">
					<?php echo FrmFormBill::$payTypes[$item->pay_type];?>
				</td>
				<td style="width: 60px;">
					<span title="<?php echo $item->title->name?>"><?php echo $item->title->short_name;?></span>
				</td>
				<td style="width: 70px;">
					<?php echo $item->baseform->belong->nickname;?>
				</td>
				<td style="width: 70px;"><?php echo $item->accounter->nickname;?></td>
				<td style="width: 210px;">
					<span title="<?php echo htmlspecialchars($item->comment);?>"><?php echo mb_substr($item->comment, 0,15,"utf-8");?></span>
				</td>
			</tr>
		<?php }$i++; }?>
			<tr id="" class="data">
				<td class="table_cell_first" style="width: 80px;">合计</td>
				<td style="width: 100px;"></td>
				<td style="width: 200px;"></td>
				<td style="width: 80px;"></td>
				<td class="rightAlign" style="width: 110px;"><?php echo number_format($totaldata['in_price'], 2);?></td>
				<td class="rightAlign" style="width: 110px;"><?php echo number_format($totaldata['out_price'], 2);?></td>
				<td class="rightAlign" style="width: 110px;"><?php #echo number_format($totaldata['final'],2);?></td>
				<td style="width: 90px;"></td>
				<td style="width: 90px;"></td>
				<td style="width: 60px;"></td>
				<td style="width: 70px;"></td>
				<td style="width: 70px;"></td>
				<td style="width: 210px;"></td>
			</tr>
		</tbody>
	</table>
</div>
<!-- <div class="total_data"> --
	<div class="total_data_one">入账总金额：<?php #echo number_format($totaldata['in_price'], 2);?></div>
	<div class="total_data_one">出账总金额：<?php #Eecho number_format($totaldata['out_price'], 2);?></div>
<!-- </div> -->
<?php paginate($pages,"zcmx");?>

<script type="text/javascript">
	var title_array = <?php echo $title_array ? $title_array : '[]';?>;
	var company_array = <?php echo $company_array ? $company_array : '[]';?>;
	var dict_bank_array = <?php echo $dict_bank_array ? $dict_bank_array : '[]';?>;

	$(function(){
		//select
		$("#title_combo").combobox(title_array, {}, 'title_select', 'title_val', false);
		$("#company_combo").combobox(company_array, {}, 'company_select', 'company_val', false);
		$("#dict_bank_combo").combobox(dict_bank_array, {}, 'dict_bank_select', 'dict_bank_val', false, '', 260);

		//列表
		$("#datatable1").datatable({
			fixedLeftWidth: 590, 
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
