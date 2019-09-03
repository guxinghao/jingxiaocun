<style>
<!--
.icon{ cursor: pointer;}
#cght_tb tbody td{ line-height: 26px;}
#bill_list, #turnover_list{ float: left; width: 100%;}
i.deleted_tr{ float: none; line-height: 26px; margin: 0 auto;}
#ownedSales_list .sale_fee{ color: #145ccd;}
-->
</style>
<?php
switch ($baseform->form_type) 
{
	case 'FKDJ': 
		$title = "付款";
		break;
	case 'SKDJ': 
		$title = "收款";
		break;
	default: 
		break;
}
$is_supply = in_array($model->bill_type, array('CGFK', 'DLFK', 'CGTH', 'GCFL', 'BZJ'));
$is_logistics = in_array($model->bill_type, array('YF'));
$is_customer = in_array($model->bill_type, array('XSTH', 'XSZR', 'GKZR', 'XSSK'));
$is_gk = in_array($model->bill_type, array('GKFK'));
$has_warehouse = in_array($model->bill_type, array('CCFY', 'CKFL'));
$has_pledge = in_array($model->bill_type, array('TPYF', 'TPSH'));

$has_yidan = in_array($model->bill_type, array('CGFK', 'XSSK', 'GKFK', 'XSTH', 'CGTH'));
$is_relation = in_array($model->bill_type, array('CGFK', 'XSSK', 'TPYF', 'CCFY', 'CKFL', 'GCFL', 'BZJ', 'DLSK')) ? false : true;
$has_turnover = in_array($model->bill_type, array('CGFK', 'BZJ'));
$has_ownedSales = in_array($model->bill_type, array('XSSK'));
$has_billRebate = in_array($model->bill_type, array('GCFL', 'CKFL', 'CCFY'));
$has_salverPurchase = in_array($model->bill_type, array('TPYF'));
$has_dlfk = in_array($model->bill_type, array('DLSK'));

$search_title = "";
switch ($model->bill_type)
{
	case 'CGFK': //采购付款
		$company_type = "supply"; //供应商
		$has_yidan = 1;
		$has_turnover = 1;
		$search_title = "往来明细";
		break;
	case 'XSSK': //销售收款
		$company_type = "customer"; //客户
		$has_yidan = 1;
		$has_ownedSales = 1;
		$search_title = "销售明细";
		break;
	case 'XSTH': //销售退货付款
		$company_type = "customer"; //客户
		$has_yidan = 1;
		$has_relation = 1;
		$search_title = "销售退货信息";
		break;
	case 'CGTH': //采购退货收款
		$company_type = "supply"; //供应商
		$has_yidan = 1;
		$has_relation = 1;
		$search_title = "采购退货信息";
		break;
	case 'XSZR': //销售折让
		$company_type = "customer"; //客户
		$has_relation = 1;
		$search_title = "折让信息";
		break;
	case 'GKZR': //高开折让
		$company_type = "customer"; //客户
		$has_relation = 1;
		$search_title = "折让信息";
		break;
	case 'GKFK': //高开付款
		$company_type = "gk";
		$has_yidan = 1;
		$has_relation = 1;
		$search_title = "高开信息";
		$theory_fee = 0.0;
		break;
	case 'DLFK': //代理付款
		$company_type = "supply"; //供应商
		$has_relation = 1;
		$search_title = "托盘采购信息";
		break;
	case 'DLSK': //代理收款
		$company_type = "supply"; //供应商
		$has_dlfk = 1;
		$search_title = "代理付款信息";
		break;
	case 'TPYF': //托盘预付
		$company_type = "pledge"; //托盘公司
		$has_salverPurchase = 1;
		$search_title = "托盘采购信息";
		break;
	case 'TPSH': //托盘赎回
		$company_type = "pledge"; //托盘公司
		$has_relation = 1;
		$search_title = "托盘赎回信息";
		break;
	case 'YF': //运费
		$company_type = "logistics"; //物流商
		$has_relation = 1;
		$search_title = "运费信息";
		break;
	case 'CKFL': //仓库返利
		$company_type = "warehouse"; //仓库结算单位
		$search_title = "仓库返利信息";
		break;
	case 'GCFL': //钢厂返利
		$company_type = "supply"; //供应商
		$search_title = "钢厂返利信息";
		break;
	case 'CCFY': //仓储费用
		$company_type = "warehouse"; //仓库结算单位
		$search_title = "仓储费用信息";
		break;
	case 'BZJ': //保证金
		$company_type = "supply"; //供应商
		$has_turnover = 1;
		$search_title = "往来明细";
		break;
	default: break;
}

$form = $this->beginWidget('CActiveForm', array(
		'enableAjaxValidation' => true, 
		'htmlOptions' => array(
				'id' => 'form_data', 
				'enctype' => 'multipart/form-data'
		)
));
?>
<div class="shop_select_box">

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span><?php echo $title;?>类型：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<select id="FrmFormBill_bill_type" class="form-control chosen-select" name="FrmFormBill[bill_type]" disabled="disabled">
	<?php } else {?>
		<select id="FrmFormBill_bill_type" class="form-control chosen-select" name="FrmFormBill[bill_type]">
	<?php }?>
		<?php if ($baseform->form_type == "FKDJ") {?>
			<option value="CGFK"<?php echo $model->bill_type == 'CGFK' ? ' selected="selected"' : '';?>>采购付款</option>
			<option value="TPYF"<?php echo $model->bill_type == 'TPYF' ? ' selected="selected"' : '';?>>托盘预付</option>
			<option value="TPSH"<?php echo $model->bill_type == 'TPSH' ? ' selected="selected"' : '';?>>托盘赎回</option>
			<option value="XSTH"<?php echo $model->bill_type == 'XSTH' ? ' selected="selected"' : '';?>>销售退货付款</option>
			<option value="DLFK"<?php echo $model->bill_type == 'DLFK' ? ' selected="selected"' : '';?>>代理付款</option>
			<option value="GKFK"<?php echo $model->bill_type == 'GKFK' ? ' selected="selected"' : '';?>>高开付款</option>
			<option value="XSZR"<?php echo $model->bill_type == 'XSZR' ? ' selected="selected"' : '';?>>销售折让</option>
			<!-- <option value="GKZR"<?php echo $model->bill_type == 'GKZR' ? ' selected="selected"' : '';?>>高开折让</option> -->
			<option value="CCFY"<?php echo $model->bill_type == 'CCFY' ? ' selected="selected"' : '';?>>仓储费用</option>
			<option value="YF"<?php echo $model->bill_type == 'YF' ? ' selected="selected"' : '';?>>运费</option>
			<option value="BZJ"<?php echo $model->bill_type == 'BZJ' ? ' selected="selected"' : '';?>>保证金</option>
		<?php } elseif ($baseform->form_type == "SKDJ") {?>
			<option value="XSSK"<?php echo $model->bill_type == 'XSSK' ? ' selected="selected"' : '';?>>销售收款</option>
			<option value="CGTH"<?php echo $model->bill_type == 'CGTH' ? ' selected="selected"' : '';?>>采购退货收款</option>
			<option value="CKFL"<?php echo $model->bill_type == 'CKFL' ? ' selected="selected"' : '';?>>仓库返利</option>
			<option value="GCFL"<?php echo $model->bill_type == 'GCFL' ? ' selected="selected"' : '';?>>钢厂返利</option>
			<option value="DLSK"<?php echo $model->bill_type == 'DLSK' ? ' selected="selected"' : '';?>>代理收款</option>
		<?php }?>
		</select>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input id="FrmFormBill_bill_type" type="hidden" value="<?php echo $model->bill_type;?>" name="FrmFormBill[bill_type]" />
	<?php }?>
	</div>


	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结算单位：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" value="<?php echo $model->company->name;?>" readonly="readonly" />
	<?php } else {?>
		<div id="pledgeCompany_select" class="fa_droplist"<?php echo $has_pledge ? '' : ' style="display: none;"';?>>
			<input type="text" id="pledgeCompany_combo" value="<?php echo $has_pledge ? $model->company->name : '';?>" />
			<input type="hidden" id="pledgeCompany_val" value="<?php echo $has_pledge ? $model->company_id : '';?>" />
		</div>
		<div id="supply_select" class="fa_droplist"<?php echo $is_supply ? '' : ' style="display: none;"';?>>
			<input type="text" id="supply_combo" value="<?php echo $is_supply ? $model->company->name : '';?>" />
			<input type="hidden" id="supply_val" value="<?php echo $is_supply ? $model->company_id : '';?>" />
		</div>
		<div id="customer_select" class="fa_droplist"<?php echo $is_customer ? '' : ' style="display: none;"';?>>
			<input type="text" id="customer_combo" value="<?php echo $is_customer ? $model->company->name : '';?>" />
			<input type="hidden" id="customer_val" value="<?php echo $is_customer ? $model->company_id : '';?>" />
		</div>
		<div id="gk_select" class="fa_droplist"<?php echo $is_gk ? '' : ' style="display: none;"';?>>
			<input type="text" id="gk_combo" value="<?php echo $is_gk ? $model->company->name : '';?>" />
			<input type="hidden" id="gk_val"  value="<?php echo $is_gk ? $model->company_id : '';?>" />
		</div>
		<div id="logistics_select" class="fa_droplist"<?php echo $is_logistics ? '' : ' style="display: none;"';?>>
			<input type="text" id="logistics_combo" value="<?php echo $is_logistics ? $model->company->name : '';?>" />
			<input type="hidden" id="logistics_val" value="<?php echo $is_logistics ? $model->company_id : '';?>" />
		</div>
		<div id="warehouse_select" class="fa_droplist"<?php echo $has_warehouse ? '' : ' style="display: none;"';?>>
			<input type="text" id="warehouse_combo" value="<?php echo $has_warehouse ? $model->company->name : '';?>" />
			<input type="hidden" id="warehouse_val" value="<?php echo $has_warehouse ? $model->company_id : '';?>" />
		</div>
	<?php }?>
	</div>
	<input type="hidden" id="FrmFormBill_company_id" value="<?php echo $model->company_id;?>" name="FrmFormBill[company_id]">
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">结算账户：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" title="<?php echo $model->bankInfo ? $model->bankInfo->company_name.'('.$model->bankInfo->bank_number.')' : '';?>" value="<?php echo $model->bankInfo ? $model->bankInfo->company_name.'('.$model->bankInfo->bank_number.')' : '';?>" readonly="readonly" />
		<input type="hidden" id="FrmFormBill_bank_info_id" value="<?php echo $model->bank_info_id;?>" name="FrmFormBill[bank_info_id]">
	<?php } else {?>
		<div id="bank_info_select" class="fa_droplist">
			<input type="text" id="bank_info_combo" value="<?php echo $model->bankInfo ? $model->bankInfo->company_name.'('.$model->bankInfo->bank_number.')' : '';?>">
			<input type="hidden" id="FrmFormBill_bank_info_id" value="<?php echo $model->bank_info_id;?>" name="FrmFormBill[bank_info_id]">
		</div>
	<?php }?>
	</div>
	

	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span><?php echo $title;?>方式：</div>
	<?php //if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<!-- <select id="FrmFormBill_pay_type" class="form-control chosen-select" name="FrmFormBill[pay_type]" disabled="disabled"> -->
	<?php //} else {?>
		<select id="FrmFormBill_pay_type" class="form-control chosen-select" name="" <?php if(!checkOperation('收付款方式全部'))echo 'disabled="disabled"';?>>
	<?php //}?>
		<?php  $payTypes=FrmFormBill::getPayTypes();
		if (!$model->id) $model->pay_type = 'cyber';
		foreach ($payTypes as $k => $v) {
			if ($baseform->form_type != 'FKDJ' && $k == 'summary') continue; 			
		?>
			<option value="<?php echo $k;?>"<?php echo $model->pay_type == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
		<?php }?>
		</select>
	<?php //if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input id="FrmFormBill_pay_type_1" type="hidden" value="<?php echo $model->pay_type;?>" name="FrmFormBill[pay_type]" />
	<?php //}?>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" value="<?php echo $model->title->short_name;?>" readonly="readonly" />
		<input type="hidden" id="FrmFormBill_title_id"  value="<?php echo $model->title_id;?>" name="FrmFormBill[title_id]"/>
	<?php } else {?>
		<div id="title_select" class="fa_droplist">
			<input type="text" id="title_combo" value="<?php echo $model->title->short_name;?>" />
			<input type="hidden" id="FrmFormBill_title_id"  value="<?php echo $model->title_id;?>" name="FrmFormBill[title_id]"/>
		</div>
	<?php }?>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司账户：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" title="<?php echo $model->dictBankInfo ? $model->dictBankInfo->dict_name.'('.$model->dictBankInfo->bank_number.')' : '';?>" value="<?php echo $model->dictBankInfo ? $model->dictBankInfo->dict_name.'('.$model->dictBankInfo->bank_number.')' : '';?>" readonly="readonly" />
		<input type="hidden" id="FrmFormBill_dict_bank_info_id" value="<?php echo $model->dict_bank_info_id;?>" name="FrmFormBill[dict_bank_info_id]">
	<?php } else {?>
		<div id="dict_bank_info_select" class="fa_droplist">
			<input type="text" id="dict_bank_info_combo" value="<?php echo $model->dictBankInfo ? $model->dictBankInfo->dict_name.'('.$model->dictBankInfo->bank_number.')' : '';?>" />
			<input type="hidden" id="FrmFormBill_dict_bank_info_id" value="<?php echo $model->dict_bank_info_id;?>" name="FrmFormBill[dict_bank_info_id]">
		</div>
	<?php }?>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<select id="CommonForms_owned_by" class="form-control chosen-select" name="CommonForms[owned_by]">
		<?php foreach ($user_array as $key => $value) {?>
			<option value="<?php echo $key;?>"<?php echo $baseform->owned_by == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<select id="team_select" class="form-control chosen-select" disabled="disabled">
		<?php foreach ($team_array as $key => $value) {?>
			<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php }?>
		</select>
	</div>
	
	<div class="shop_more_one" id="pledge_company"<?php echo in_array($model->bill_type, array('DLFK', 'DLSK')) ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l"><span class="bitian">*</span>托盘公司：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" value="<?php echo $model->pledgeCompany->name;?>" readonly="readonly" />
		<input type="hidden" id="FrmFormBill_pledge_company_id"  value="<?php echo $model->pledge_company_id;?>" name="FrmFormBill[pledge_company_id]"/>
	<?php } else {?>
		<div id="pledge_select" class="fa_droplist">
			<input type="text" id="pledge_combo" value="<?php echo $model->pledgeCompany->name;?>" />
			<input type="hidden" id="FrmFormBill_pledge_company_id"  value="<?php echo $model->pledge_company_id;?>" name="FrmFormBill[pledge_company_id]"/>
		</div>
	<?php }?>
	</div>
	
	<div class="shop_more_one" id="pledge_bank_info"<?php echo in_array($model->bill_type, array('DLFK', 'DLSK')) ? '' : ' style="display: none;"';?>>
		<div class="shop_more_one_l">托盘账户：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" title="<?php echo $model->pledgeBankInfo ? $model->pledgeBankInfo->company_name.'('.$model->pledgeBankInfo->bank_number.')' : '';?>" value="<?php echo $model->pledgeBankInfo ? $model->pledgeBankInfo->company_name.'('.$model->pledgeBankInfo->bank_number.')' : '';?>" readonly="readonly" />
		<input type="hidden" id="FrmFormBill_pledge_bank_info_id" value="<?php echo $model->pledge_bank_info_id;?>" name="FrmFormBill[pledge_bank_info_id]" />
	<?php } else {?>
		<div id="pledge_bank_info_select" class="fa_droplist">
			<input type="text" id="pledge_bank_info_combo" value="<?php echo $model->pledgeBankInfo ? $model->pledgeBankInfo->company_name.'('.$model->pledgeBankInfo->bank_number.')' : '';?>" />
			<input type="hidden" id="FrmFormBill_pledge_bank_info_id" value="<?php echo $model->pledge_bank_info_id;?>" name="FrmFormBill[pledge_bank_info_id]" />
		</div>
	<?php }?>
	</div>

	<div class="shop_more_one" <?php echo $is_customer ? '' : ' style="display: none;"';?> id="FrmFormBill_client">
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" value="<?php echo $model->client->short_name;?>" readonly="readonly" />
		<input type="hidden" id="customer_val1"  value="<?php echo $model->client_id;?>"/>
	<?php } else {?>
		<div id="customer_select1" class="fa_droplist">
			<input type="text" id="customer_combo1" value="<?php echo $is_customer ? $model->client->name : '';?>" />
			<input type="hidden" id="customer_val1" value="<?php echo $is_customer ? $model->client_id : '';?>"/>
		</div>
	<?php }?>
	</div>
	<div class="shop_more_one" <?php echo $is_gk ? '' : ' style="display: none;"';?> id="FrmFormBill_gk_client">
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" class="form-control" value="<?php echo $model->client->short_name;?>" readonly="readonly" />
		<input type="hidden" id="customer_val_gk"  value="<?php echo $model->client_id;?>"/>
	<?php } else {?>
		<div id="customer_select_gk" class="fa_droplist">
			<input type="text" id="customer_combo_gk" value="<?php echo $is_gk ? $model->client->name : '';?>" />
			<input type="hidden" id="customer_val_gk" value="<?php echo $is_gk ? $model->client_id : '';?>" />
		</div>
	<?php }?>
	</div>
	<input type="hidden" id="FrmFormBill_client_id" value="<?php echo $model->client_id;?>" name="FrmFormBill[client_id]">
	<div class="shop_more_one" >
		<div class="shop_more_one_l">到账日期：</div>
		<input id="FrmFormBill_reach_at" type="text" class="form-control form-date forreset date input_backimg" name="FrmFormBill[reach_at]" value="<?php echo date('Y-m-d', $model->reach_at > 0 ? $model->reach_at : time());?>" placeholder="选择日期" />
	</div>
	<div class="shop_more_one" >
		<div class="shop_more_one_l">登记日期：</div>
		<input id="FrmFormBill_reach_at" type="text" class="form-control form-date forreset date input_backimg" name="CommonForms[form_time]" value="<?php echo $baseform->form_time? $baseform->form_time : date('Y-m-d',time());?>" placeholder="选择日期" />
	</div>
	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>总金额：</div>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="text" id="FrmFormBill_fee" class="form-control" value="<?php echo number_format($model->fee, 2);?>" name="FrmFormBill[fee]" readonly="readonly" />
	<?php } else {?>
		<input type="text" id="FrmFormBill_fee" class="form-control" value="<?php echo number_format($model->fee, 2);?>" name="FrmFormBill[fee]" />
	<?php }?>
		<input type="hidden" id="FrmFormBill_weight" class="form-control" value="<?php echo number_format($model->weight, 3);?>" name="FrmFormBill[weight]" />
		<input id="FrmFormBill_theory_fee" type="hidden" value="<?php echo number_format($theory_fee, 2);?>" name="FrmFormBill[theory_fee]">
	</div>
	
	<div class="shop_more_one"<?php echo $has_yidan ? '' : ' style="display: none;"';?>>
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		<input type="checkbox" id="yidan_val" class="check_box l" style="margin-left: 130px;" value="1" disabled="disabled"<?php echo $model->is_yidan == 1 ? ' checked="checked"' : '';?>>
	<?php } else {?>
		<input type="checkbox" id="yidan_val" class="check_box l" style="margin-left: 130px;" value="1" <?php echo $model->is_yidan == 1 ? ' checked="checked"' : '';?>>
	<?php }?>
		<div class="lab_check_box">乙单</div>
		<input id="FrmFormBill_is_yidan" type="hidden" value="<?php echo $has_yidan ? $model->is_yidan : '';?>" name="FrmFormBill[is_yidan]" />
	</div>
	<?php if ($baseform->form_type == 'FKDJ') {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">用途：</div>
		<input id="FrmFormBill_purpose" type="text" class="form-control" value="<?php echo $model->purpose;?>" name="FrmFormBill[purpose]" />
	</div>
<?php }?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="hidden" id="CommonForms_form_type" value="<?php echo $baseform->form_type;?>" name="CommonForms[form_type]">
		<!-- <input type="hidden" id="CommonForms_form_time" value="<?php echo date("Y-m-d H:i:s");?>" name="CommonForms[form_time]"> -->
		<input type="text" class="form-control" id="CommonForms_comment" value="<?php echo $baseform->comment;?>" name="CommonForms[comment]">
	</div>
</div>

<div class="create_table">
	<table id="cght_tb" class="table"<?php echo $is_relation ? '' : ' style="display: none;"';?>>
		<thead>
		
		</thead>
		
		<tbody>
		<?php if ($relations) { 
			$tr_num = 0; 
			foreach ($relations as $each) { 
				$tr_num++;
		?>
			<tr>
				<td class="text-center list_num"><?php echo $tr_num;?></td>
				<td class="text-center"<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') echo ' style="display: none;"'?>>
					<i class="icon icon-trash deleted_tr"></i>
					<input type="hidden" name="td_id[]" class="td_id" value="<?php echo $each->id;?>" />
					<input type="hidden" name="td_common_id[]" class="td_common_id" value="<?php echo $each->common_id;?>" />
				</td>
				<td class="text-center"><?php echo $each->common->form_sn;?></td>
				<td class="text-center"><?php echo date('Y-m-d', $each->common->created_at);?></td>
		<?php 
		switch ($model->bill_type) 
		{
			case 'CGFK': //采购付款
			case 'DLFK': //代理付款
				$relation_data = $each->common->purchase;
				$_type = array('normal' => "库存采购", 'tpcg' => "托盘采购", 'xxhj' => "直销采购", 'dxcg' => "代销采购");
		?>
				<td class="text-center"><span title="<?php echo $relation_data->supply->name;?>"><?php echo $relation_data->supply->name;?></span></td>
				<td class="text-center"><?php echo number_format($relation_data->weight, 3);?></td>
				<td class="text-center">
					<span class="real_fee"><?php echo number_format($relation_data->price_amount, 2);?></span>
				</td>
				<td class="text-center"><?php echo $has_yidan ? ($relation_data->is_yidan == 1 ? '是' : '否') : '';?></td>
				<td class="text-center"><span title="<?php echo $relation_data->pledge->pledgeCompany->name;?>" class="pledge_company"><?php echo $relation_data->pledge->pledgeCompany->name;?></span></td>
				<td class="text-center"><?php echo $_type[$relation_data->purchase_type];?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'TPSH': //托盘赎回
				$relation_data = $each->common->pledgeRedeem;
		?>
				<td class="text-center"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->name;?></span></td>
				<td class="text-center"><?php echo DictGoodsProperty::getProName($relation_data->brand_id);?></td>
				<td class="text-center"><?php echo DictGoodsProperty::getProName($relation_data->product_id);?></td>
				<td class="text-center">
					<span class="real_fee"><?php echo number_format($relation_data->total_fee, 2);?></span>
				</td>
				<td class="text-center">	<?php echo number_format($relation_data->interest_fee, 2);?></td>
				<td class="text-center">	<?php echo $relation_data->purchase->baseform->form_sn;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'CGTH': //采购退货收款
				$relation_data = $each->common->purchaseReturn;
		?>
				<td class="text-center"><span title="<?php echo $relation_data->supply->name;?>"><?php echo $relation_data->supply->name;?></span></td>
				<td class="text-center"><span title="<?php echo $relation_data->warehouse->name;?>"><?php echo $relation_data->warehouse->name;?></span></td>
				<td class="text-center"><?php echo $relation_data->travel;?></td>
				<td class="text-center"><?php echo date('Y-m-d', $relation_data->return_data);?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSSK': //销售收款
				$relation_data = $each->common->sales;
				$_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
		?>
				<td class="text-center"><span title="<?php echo $relation_data->dictCompany->name;?>"><?php echo $relation_data->dictCompany->name;?></span></td>
				<td class="text-center">	<?php echo number_format($relation_data->weight, 3);?></td>
				<td class="text-center"><?php echo number_format($relation_data->amount);?></td>
				<td class="text-center"><?php echo $relation_data->is_yidan == 1 ? '是' : '否';?></td>
				<td class="text-center"><?php echo $_type[$relation_data->sales_type];?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSTH': //销售退货付款
				$relation_data = $each->common->salesReturn;
				$details=$relation_data->salesReturnDetails;
				$total = 0;
				foreach ($details as $li)
				{
					$total+=$li->fix_weight*$li->fix_price;
				}
		?>
				<td class="text-center"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->name;?></span></td>
				<td class="text-center"><?php echo number_format($total,2);?>
				<td class="text-center"><span title="<?php echo $relation_data->warehouse->name;?>"><?php echo $relation_data->warehouse->name;?></span></td>
				<td class="text-center"><?php echo $relation_data->travel;?>
				<td class="text-center"><?php echo $relation_data->return_date>0?date('Y-m-d', $relation_data->return_date):"";?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
				<td class="text-center"><span title="<?php echo $relation_data->client->name;?>"><?php echo $relation_data->client->name;?></span></td>
		<?php 
				break;
			case 'XSZR': //销售折让
				$relation_data = $each->common->rebate;
				$_type = array('sale' => "销售折让", 'shipment' => "采购运费登记", 'shipment_sale' => "销售运费登记", 'high' => "高开折让");
		?>
				<td class="text-center"><span title="<?php echo $relation_data->company->name?>"><?php echo $relation_data->company->name?></span></td>
				<td class="text-center">
					<span class="real_fee"><?php echo number_format($relation_data->amount, 2);?></span>
				</td>
				<td class="text-center"><?php echo $relation_data->is_yidan == 1 ? "是" : "否";?></td>
				<td class="text-center"><?php echo $_type[$relation_data->type];?></td>
				<td class="text-center"><?php echo $relation_data->team->name;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'GKFK': //高开付款
				$relation_data = $each->common->highopen;
				$sales_detail = $relation_data->salesDetail;
				$product_info = DictGoodsProperty::getProName($sales_detail->brand_id) . "/" . 
						DictGoodsProperty::getProName($sales_detail->product_id) . "/" .
						str_replace('E', '<span class="red">E</span>', DictGoodsProperty::getProName($sales_detail->texture_id)) . "/" .
						DictGoodsProperty::getProName($sales_detail->rank_id) . "/" .
						$sales_detail->length;
				$theory_fee += $relation_data->real_fee;
		?>
				<td class="text-center"><?php echo $relation_data->sales->baseform->form_sn;?></td>
				<td class="text-center"><?php echo $product_info;?></td>
				<td class="text-center"><?php echo $sales_detail->amount;?></td>
				<td class="text-center"><?php echo number_format($sales_detail->weight, 3);?></td>
				<td class="text-center"><?php echo number_format($relation_data->price, 2);?></td>
				<td class="text-center">
					<span class="real_fee"><?php echo number_format($relation_data->real_fee, 2);?></span>
					<input class="discount" type="hidden" name="discount[]" value="<?php echo number_format($relation_data->discount, 2, '.', ',');?>">
				</td>
				<td class="text-center"><?php echo $relation_data->is_pay == 1 ? "已付款" : "未付款";?></td>
				<td class="text-center"><?php echo $baseform->belong->nickname;?></td>
				<td class="text-center"><span title="<?php echo $relation_data->client->name;?>"><?php echo $relation_data->client->short_name;?></span></td>
		<?php 
				break;
			case 'YF': //运费
				$relation_data = $each->common->billRecord;
				switch ($relation_data->bill_type) 
				{
					case 'purchase': 
						$relation_form = $relation_data->relationForm->purchase;
						break;
					case 'sales': 
						$relation_form = $relation_data->relationForm->sales;
						break;
					default: break;
				}
		?>
				<td class="text-center"><span title="<?php echo $relation_data->title->name;?>"><?php echo $relation_data->title->short_name;?></span></td>
				<td class="text-center"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->name;?></span></td>
				<td class="text-center"><?php echo number_format($relation_data->weight, 3);?></td>
				<td class="text-center"><?php echo number_format($relation_data->price, 2);?></td>
				<td class="text-center">
					<span class="real_fee"><?php echo number_format($relation_data->amount, 2);?></span>
				</td>
				<td class="text-center"><?php echo $relation_data->travel;?></td>
				<td class="text-center"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			default: 
				break;
		}
		?>
			</tr>
		<?php } }?>
		</tbody>
	</table>
</div>

<input id="FrmFormBill_rebate_form_id" type="hidden" value="<?php echo $model->rebate_form_id;?>" name="FrmFormBill[rebate_form_id]" />
<input id="last_update" type="hidden" name="last_update" value="<?php echo $_GET['last_update'];?>" />
<div class="btn_list">
<?php if ($baseform->form_type == 'FKDJ' && (!$model->id || $baseform->form_status == 'unsubmit')) {?>
	<button id="submit_btn1" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存提交</button>
<?php }?>
	<button id="submit_btn" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">保存</button>
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">取消</button>
	</a>
</div>
<?php $this->endWidget()?>

<div class="search_line"<?php echo $baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited' ? ' style="display: none;"' : '';?>></div>
<div class="search_title"<?php echo $baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited' ? ' style="display: none;"' : '';?>><?php echo $search_title;?></div>

<div class="search_body search_background"<?php echo !$is_relation || ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? ' style="display: none;"' : '';?>>
	<div class="srarch_box">
		<img src="/images/search.png">
		<input id="search_keywords" class="forreset" placeholder="请输入单号">
	</div>
	
	<div class="search_date">
		<div style="float: left;">日期：</div>
		<div class="search_date_box">
			<input type="text" id="search_begin" class="form-control form-date forreset date" value="" placeholder="开始日期">
		</div>
		<div style="float: left; margin: 0 3px;">至</div>
		<div class="search_date_box">
			<input type="text" id="search_end" class="form-control form-date forreset date" value="" placeholder="结束日期">
		</div>
	</div>
	<input type="button" data-dismiss="modal" class="btn btn-primary btn-sm btn_sub search_btn" value="查询" />
	<img class="reset" src="/images/reset.png">
</div>

<div id="bill_list"<?php echo !$is_relation || ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? ' style="display: none;"' : '';?>>

</div>

<div id="turnover_list"<?php echo !$has_turnover || ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? ' style="display: none;"' : '';?>>

</div>

<div id="ownedSales_list"<?php echo !$has_ownedSales || ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? ' style="display: none;"' : '';?>>

</div>
 
 <div id="billRebate_list"<?php echo !$has_billRebate || ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? ' style="display: none;"' : '';?>>
 
 </div>
 
 <div id="salverPurchase_list"<?php echo !$has_salverPurchase || ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') ? ' style="display: none;";' : '';?>>

 </div>
 
 <div id="dlfk_list"<?php echo !$has_dlfk ? ' style="display: none;"' : '';?>>
 
 </div>
 
<script type="text/javascript">
var can_submit = true;

$("#FrmFormBill_theory_fee").val("<?php echo $theory_fee;?>");

var tr_num = <?php echo $tr_num ? $tr_num : 0;?>; //序号
var id_array = new Array(); //明细id
var _common_id_array = new Array(); //关联单据基础id
var common_id_array = new Array();

var is_supply = <?php echo $is_supply ? 1 : 0;?>;
var is_logistics = <?php echo $is_logistics ? 1 : 0;?>;
var is_customer = <?php echo $is_customer ? 1 : 0;?>;
var is_gk = <?php echo $is_gk ? 1 : 0;?>;
var has_warehouse = <?php echo $has_warehouse ? 1 : 0;?>;
var has_pledge = <?php echo $has_pledge ? 1 : 0;?>;

var has_yidan = <?php echo $has_yidan ? 1 : 0;?>;
var is_relation = <?php echo $is_relation ? 1 : 0;?>;
var has_turnover = <?php echo $has_turnover ? 1 : 0;?>;
var has_ownedSales = <?php echo $has_ownedSales ? 1 : 0;?>;
var has_billRebate = <?php echo $has_billRebate ? 1 : 0;?>;
var has_salverPurchase = <?php echo $has_salverPurchase ? 1 : 0;?>;
var has_dlfk = <?php echo $has_dlfk ? 1 : 0;?>;

//查询条件
var type = "";
var company_id = 0;
var client_id = 0;
var title_id = 0;
var is_yidan = '';
var pledge_company_id = 0;
var keywords = "";
var begin_time = "";
var end_time = "";
var owned_by = 0;

//select
var supply_array = <?php echo $supply_array ? $supply_array : '[]';?>;
var logistics_array = <?php echo $logistics_array ? $logistics_array : '[]';?>;
var customer_array = <?php echo $customer_array ? $customer_array : '[]';?>;
var gk_array = <?php echo $gk_array ? $gk_array : '[]';?>;
var warehouse_array = <?php echo $warehouse_array ? $warehouse_array : '[]';?>;
var pledge_array = <?php echo $pledge_array ? $pledge_array : '[]';?>;
var title_array = <?php echo $title_array ? $title_array : "[]";?>;

var bank_info_array = <?php echo $bank_info_array ? $bank_info_array : "[]";?>;
var dict_bank_info_array = <?php echo $dict_bank_info_array ? $dict_bank_info_array : "[]";?>;
var pledge_bank_info_array = <?php echo $pledge_bank_info_array ? $pledge_bank_info_array : "[]";?>;

<?php if($model->bill_type == "GKFK") {?>
	$("#yidan_val").attr('checked', "checked");
	$("#FrmFormBill_pay_type").val("money");
	$("#FrmFormBill_is_yidan").val(1);
<?php } ?>

//查询
function getBillList(page) {
	company_id = $("#FrmFormBill_company_id").val();
	title_id = $("#FrmFormBill_title_id").val();
	type = $("#FrmFormBill_bill_type").val();
	is_yidan = $("#yidan_val").attr("checked") == 'checked' ? 1 : 0;
	pledge_company_id = $("#FrmFormBill_pledge_company_id").val();
	owned_by = $("#CommonForms_owned_by").val();
	keywords = $("#search_keywords").val();
	begin_time = $("#search_begin").val();
	end_time = $("#search_end").val();
	client_id = $("#FrmFormBill_client_id").val();
	if (is_relation == 0) return;
	$.get('/index.php/formBill/getBillSimpleList', 
	{
		'id': <?php echo $baseform->id ? $baseform->id : 0;?>,
		'type': type,
		'company_id': company_id,
		'client_id': client_id,
		'title_id': title_id,
		'is_yidan': is_yidan,
		'pledge_company_id': pledge_company_id,
		'owned_by': owned_by,
		'keywords': keywords,
		'time_L': begin_time,
		'time_H': end_time,
		'page': page
	}, 
	function(data){ 
		$('#bill_list').html(data);
		$("#cght_tb thead").html($("#bill_list thead").html());
		$("#cght_tb thead th").addClass("text-center");
		<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {?>
		$("#cght_tb thead th").eq(1).hide();
		<?php }?>
		
		//
		$("#bill_list tbody tr").each(function(){
			var common_id = $(this).find(".selected_bill").val();
			if (common_id_array.indexOf(common_id) > -1) $(this).find(".selected_bill").attr("checked", "checked");
		});
	});
}

//往来
function getTurnoverList(page) 
{
	company_id = $("#FrmFormBill_company_id").val();
	title_id = $("#FrmFormBill_title_id").val();
	
	if (has_turnover == 0) return;
	$.get('/index.php/formBill/getTurnoverList', 
	{
		'company_id': company_id,
		'title_id': title_id,
		'page': page
	}, 
	function(data){
		$('#turnover_list').html(data);
	});
	
}

//业务包含销售单
function getSalesList(page) 
{
	owned_by = $("#CommonForms_owned_by").val();

	if (has_ownedSales == 0) return;
	$.get('/index.php/formBill/getSalesList', 
	{
		'owned_by': owned_by,
		'page': page
	}, 
	function(data){
		$("#ownedSales_list").html(data);
	});
}

//获取返利信息
function getbillRebateList(page) 
{
	if (has_billRebate <= 0) return;
	$.get('/index.php/formBill/getBillRebateList', 
	{
		'type': $("#FrmFormBill_bill_type").val(),
		'page': page,
	}, 
	function(data) {
		$("#billRebate_list").html(data);
	});
}

//获取托盘采购信息
function getSalverPurchaseList(page) 
{
	owned_by = $("#CommonForms_owned_by").val();
	if (has_salverPurchase == 0) return;
	$.get('/index.php/formBill/getSalverPurchaseList', 
	{
		'owned_by': owned_by,
		'page': page,
	}, 
	function(data) {
		$("#salverPurchase_list").html(data);
	});
}

//获取代理付款信息
function getDlfkList(page) 
{
	owned_by = $("#CommonForms_owned_by").val();
	if (has_dlfk == 0) return;
	$.get('/index.php/formBill/getDlfkList', 
	{
		'owned_by': owned_by,
		'page': page,
	}, 
	function(data) {
		$("#dlfk_list").html(data);
	});
}



$("#salverPurchase_list tbody td").live('click', function() {
	var tr = $(this).parent();
	var supply_name = tr.find("td").eq(3).text();
	var advance = tr.find(".advance").text();
	var weight = tr.find(".weight").text();

	$("#supply_combo").val(supply_name);
	
	$("#FrmFormBill_fee").val(advance);
	$("#FrmFormBill_weight").val(weight);
	$("#FrmFormBill_theory_fee").val(advance);
});

//计算总和
function countTotal(){
	if (is_relation == 0) return;
	var total_fee = 0.0;
	$("#cght_tb tbody tr").each(function(){
		total_fee += $(this).find(".real_fee").text() ? parseFloat(numChange($(this).find(".real_fee").text())) : 0;
	});

	if ($("#cght_tb tbody tr").length > 0 && $("#cght_tb tbody tr .real_fee").length == 0) return;
	$("#FrmFormBill_fee").val(numberFormat(total_fee, 2, '.', ','));
	$("#FrmFormBill_theory_fee").val(numberFormat(total_fee, 2, '.', ','));
	countDiscount();
}

//均摊
function countDiscount(){
	var fee = parseFloat(numChange($("#FrmFormBill_fee").val())); //折让总金额
	var total_fee = 0.0; //总金额
	$("#cght_tb tbody tr").each(function(){
		total_fee += parseFloat(numChange($(this).find(".real_fee").text()));
	});
	
	$("#cght_tb tbody tr").each(function(){
		var real_fee = parseFloat(numChange($(this).find(".real_fee").text()));
		var discount = real_fee - fee * (real_fee / total_fee);
		$(this).find(".discount").val(discount.toFixed(2));
	});
}

//获取结算账户
function getBankList(id)
{
	if (is_supply > 0) id = $("#supply_val").val();
	if (is_customer > 0) {
		id = $("#customer_val").val();
		if(Number($('#customer_val1').val()) == 0){
			var vendor_id=$('#customer_val').val();
			var vendor_name=$('#customer_combo').val();
			$('#customer_val1').val(vendor_id);
			$('#customer_combo1').val(vendor_name);
			$("#FrmFormBill_client_id").val(id);
		}
	}
	if (is_gk > 0){
		id = $("#gk_val").val();
// 		var gk_id = $("#customer_val_gk").val();
// 		$("#FrmFormBill_client_id").val(gk_id);
	}
	if (has_warehouse > 0) id = $("#warehouse_val").val();
	if (has_pledge > 0) id = $("#pledgeCompany_val").val();
	if (is_logistics > 0) id = $("#logistics_val").val();
	var is_yidan=$('#FrmFormBill_is_yidan').val();
	$("#FrmFormBill_company_id").val(id);
	$.get("/index.php/bankInfo/getBankList", 
	{
		'id': id,
	}, 
	function(data) 
	{
		bank_info_array = data ? data : [];
		$("#bank_info_select").html('<input type="text" id="bank_info_combo" value=""><input type="hidden" id="FrmFormBill_bank_info_id" value="" name="FrmFormBill[bank_info_id]">');
		$("#bank_info_combo").combobox(bank_info_array, {}, "bank_info_select", "FrmFormBill_bank_info_id", false, '', 220);
	});
}

function getPledgeBankList() 
{
	var id = $("#FrmFormBill_pledge_company_id").val();
	$.get("/index.php/bankInfo/getBankList", 
	{
		'id': id,
	}, 
	function(data) 
	{
		pledge_bank_info_array = data ? data : [];
		$("#pledge_bank_info_select").html('<input type="text" id="pledge_bank_info_combo" value=""><input type="hidden" id="FrmFormBill_pledge_bank_info_id" value="" name="FrmFormBill[pledge_bank_info_id]">');
		$("#pledge_bank_info_combo").combobox(pledge_bank_info_array, {}, "pledge_bank_info_select", "FrmFormBill_pledge_bank_info_id", false, '', 220);
	});
}

function getDictBankList(id) 
{
	if (!id) { id = $("#FrmFormBill_title_id").val(); }
	if(!id)return;
	var is_yidan=$('#FrmFormBill_is_yidan').val();
	$.get("/index.php/dictBankInfo/getBankList", 
	{
		'id': id,
		'is_yidan':is_yidan
	}, 
	function(data) 
	{
		dict_bank_info_array = data ? data : [];
		$("#dict_bank_info_select").html('<input type="text" id="dict_bank_info_combo" value="" /><input type="hidden" id="FrmFormBill_dict_bank_info_id" value="" name="FrmFormBill[dict_bank_info_id]">');
		$("#dict_bank_info_combo").combobox(dict_bank_info_array, {}, "dict_bank_info_select", "FrmFormBill_dict_bank_info_id", false, '', 220);
	});
}

//选中
function selectedBill(checkItem) 
{
	var tr = checkItem.parent().parent();
	var tr_str = "";
	for (var i = 0; i < tr.find("td").length; i++) 
	{
		if (i == 0 || i == 1) continue;
		tr_str += '<td class="text-center">' + tr.find("td").eq(i).html() + '</td>';
	}
	var common_id = checkItem.val();
	var yidan = checkItem.attr("yidan");
	
	if (checkItem.attr("checked") == "checked") {
		for (var i = 0; i < pledge_array.length; i++) 
		{
			if (pledge_array[i].name == tr.find(".pledge_company").text()) 
			{
				$("#pledge_combo").val(tr.find(".pledge_company").text());
				$("#FrmFormBill_pledge_company_id").val(pledge_array[i].id);
				break;
			}
		}
/*
		//是否乙单
		if (has_yidan > 0 && $("#FrmFormBill_is_yidan").attr("checked") == "checked" && yidan == 0 && !confirm("该单为非乙单数据，确认选择？")) 
		{
			checkItem.removeAttr("checked");
			return ;
		} 
		else if (has_yidan > 0 && $("#FrmFormBill_is_yidan").attr("checked") != "checked" && yidan == 1 && !confirm("该单为乙单数据，确认选择？")) 
		{
			checkItem.removeAttr("checked");
			return ;
		}
*/	
		tr_num++;
		var id = "";
		var data = '<tr>' + 
			'<td class="text-center list_num">' + tr_num + '</td>' + 
			'<td class="text-center">' + 
				'<i class="icon icon-trash deleted_tr"></i>';
		if(_common_id_array.indexOf(common_id) > -1){
			id = id_array[_common_id_array.indexOf(common_id)];
		}
		data += '<input type="hidden" name="td_id[]" class="td_id" value="' + id + '" />' + 
				'<input type="hidden" name="td_common_id[]" class="td_common_id" value="' + common_id + '" />' +
			'</td>' + tr_str +
		'</tr>';
		common_id_array.push(common_id);
		$("#cght_tb tbody").append(data);
		if(is_gk > 0){
			var gk_client_id =  tr.find("td").eq(12).find("span").attr("value");
			var gk_client_name =  tr.find("td").eq(12).find("span").attr("title");
			$('#customer_val_gk').val(gk_client_id);
			$('#customer_combo_gk').val(gk_client_name);
			$("#FrmFormBill_client_id").val(gk_client_id);
			getBillList(1);
		}
		//金额加
		if(type=='XSTH')
		{
			var temp=numChange(tr.find('.pick_money').text());
			var pre_v=numChange($('#FrmFormBill_fee').val());
			var total=(parseFloat(temp)+parseFloat(pre_v)).toFixed(2);
			$('#FrmFormBill_fee').val(numberFormat(total,2));
		}
		
	}else{
		$("#cght_tb tbody tr").each(function(){
			if($(this).find(".td_common_id").val() == common_id){
				$(this).remove();
				tr_num = 0;
				$("#cght_tb tbody .list_num").each(function(){
					tr_num++;
					$(this).html(tr_num);
				});
			}
		});
		//金额减
		if(type=='XSTH')
		{
			var temp=numChange(tr.find('.pick_money').text());
			var pre_v=numChange($('#FrmFormBill_fee').val());
			var total=(parseFloat(pre_v)-parseFloat(temp)).toFixed(2);
			$('#FrmFormBill_fee').val(numberFormat(total,2));
		}
	}
	countTotal();
}


//单据 换页获取数据
$(document).on("click", "#bill_list .sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getBillList(page);
});

$(document).on("change", "#bill_list .paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getBillList(page);
});

$(document).on("change", "#bill_list #each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "form_bill_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('#bill_list .firstpage').attr("page");
		getBillList(page);
	});
});

//往来 换页获取数据 
$(document).on("click", "#turnover_list .sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getTurnoverList(page);
});

$(document).on("change", "#turnover_list .paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getTurnoverList(page);
});

$(document).on("change", "#turnover_list #each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "turnover_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('#turnover_list .firstpage').attr("page");
		getTurnoverList(page);
	});
});

//业务关联销售单
$(document).on("click", "#ownedSales_list .sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getSalesList(page);
});

$(document).on("change", "#ownedSales_list .paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getSalesList(page);
});

$(document).on("change", "#ownedSales_list #each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "ownedSales_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('#ownedSales_list .firstpage').attr("page");
		getSalesList(page);
	});
});

//返利列表
$(document).on("click", "#billRebate_list .sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getbillRebateList(page);
});

$(document).on("change", "#billRebate_list .paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getbillRebateList(page);
});

$(document).on("change", "#billRebate_list #each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "billRebate_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('#billRebate_list .firstpage').attr("page");
		getbillRebateList(page);
	});
});

//托盘采购列表
$(document).on("click", "#salverPurchase_list .sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getSalverPurchaseList(page);
});

$(document).on("change", "#salverPurchase_list .paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getSalverPurchaseList(page);
});

$(document).on("change", "#salverPurchase_list #each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "salverPurchase_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('#salverPurchase_list .firstpage').attr("page");
		getSalverPurchaseList(page);
	});
});

//代理付款列表
$(document).on("click", "#dlfk_list .sauger_page_a", function(e){
	e.preventDefault();
	
	var page = $(this).attr("page");
	getDlfkList(page);
});

$(document).on("change", "#dlfk_list .paginate_sel", function(){
	var page = getUrlParam($(this).val(), 'page');
	getDlfkList(page);
});

$(document).on("change", "#dlfk_list #each_page", function(){
	var limit = $(this).val();
	$.post("/index.php/site/writeCookie", 
	{
		'name' : "dlfk_list",
  		'limit': limit
	}, 
	function(data) {
		var page = $('#dlfk_list .firstpage').attr("page");
		getDlfkList(page);
	});
});

//---------------------------------------------------------------------------------------------
$("#billRebate_list tbody td").live('click', function() {
	var tr = $(this).parent();
	var title_name = tr.find("td").eq(3).text();
	var company_name = tr.find("td").eq(4).text();
	var money = tr.find("td").eq(5).text();
	//公司
	$("#title_combo").val(title_name);
	for (var i = 0; i < title_array.length; i++) 
	{
		if (title_array[i].name == title_name) 
		{
			$("#FrmFormBill_title_id").val(title_array[i].id);
			getDictBankList(title_array[i].id);
			break;
		}
	}
		
	//结算单位
	switch ($("#FrmFormBill_bill_type").val()) 
	{
		case 'GCFL': //钢厂返利
			$("#supply_combo").val(company_name);
			for (var i = 0; i < supply_array.length; i++) 
			{
				if (supply_array[i].name == company_name) 
				{
					$("#supply_val").val(supply_array[i].id);
					$("#FrmFormBill_company_id").val(supply_array[i].id);
				}
			}
			break;
		case 'CKFL': //仓库返利
			$("#warehouse_combo").val(company_name);
			for (var i = 0; i < warehouse_array.length; i++) 
			{
				if (warehouse_array[i].name == company_name) 
				{
					$("#warehouse_val").val(warehouse_array[i].id);
					$("#FrmFormBill_company_id").val(warehouse_array[i].id);
				}
			}
			break;
		case 'CCFY': //仓储费用
			$("#warehouse_combo").val(company_name);
			for (var i = 0; i < warehouse_array.length; i++) 
			{
				if (warehouse_array[i].name == company_name) 
				{
					$("#warehouse_val").val(warehouse_array[i].id);
					$("#FrmFormBill_company_id").val(warehouse_array[i].id);
				}
			}
			break;
		default: break;
	}
	getBankList($("#FrmFormBill_company_id").val());
	$("#FrmFormBill_fee").val(money);
});

//收付款类型
$("#FrmFormBill_bill_type").change(function(){
	if($(this).val() != type){
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	if ($.inArray($(this).val(), new Array('DLFK', 'DLSK')) > -1) 
	{
		$("#pledge_company").show();
		$("#pledge_bank_info").show();
	} else {
		$("#pledge_company").hide();
		$("#pledge_bank_info").hide();
	}
	
	is_supply = $.inArray($(this).val(), new Array('CGFK', 'DLFK', 'CGTH', 'GCFL', 'BZJ', 'DLSK')) > -1 ? 1 : 0;
	is_customer = $.inArray($(this).val(), new Array('XSTH', 'XSZR', 'GKZR', 'XSSK')) > -1 ? 1 : 0;
	is_gk = $.inArray($(this).val(), new Array('GKFK')) > -1 ? 1 : 0;
	has_warehouse = $.inArray($(this).val(), new Array('CCFY', 'CKFL')) > -1 ? 1 : 0;
	is_logistics = $.inArray($(this).val(), new Array('YF')) > -1 ? 1 : 0;
	has_pledge = $.inArray($(this).val(), new Array('TPYF', 'TPSH')) > -1 ? 1 : 0;
	
	has_yidan = $.inArray($(this).val(), new Array('CGFK', 'XSSK', 'GKFK', 'XSTH', 'CGTH')) > -1 ? 1 : 0;
	is_relation = $.inArray($(this).val(), new Array('TPYF', 'CGFK', 'XSSK', 'CCFY', 'CKFL', 'GCFL', 'BZJ', 'DLSK')) > -1 ? 0 : 1;
	has_turnover = $.inArray($(this).val(), new Array('CGFK', 'BZJ')) > -1 ? 1 : 0;
	has_ownedSales = $.inArray($(this).val(), new Array('XSSK')) > -1 ? 1 : 0;
	has_billRebate = $.inArray($(this).val(), new Array('GCFL', 'CKFL', 'CCFY')) > -1 ? 1 : 0;
	has_salverPurchase = $.inArray($(this).val(), new Array('TPYF')) > -1 ? 1 : 0;
	has_dlfk = $.inArray($(this).val(), new Array('DLSK')) > -1 ? 1 : 0;
	
	$("#pledgeCompany_select, #supply_select, #customer_select, #gk_select, #warehouse_select, #logistics_select").hide();
	$(".create_table #cght_tb, .search_body, #bill_list").hide();
	$(".search_title").html("");
	
	switch ($(this).val()) 
	{
		case 'XSTH': //销售退货付款
			company_type = "customer"; //客户
			$(".search_title").html("销售退货信息");
			break; 
		case 'CGTH': //采购退货收款
			company_type = "supply"; //供应商
			$(".search_title").html("采购退货信息");
			break; 
		case 'XSZR': //销售折让
			company_type = "customer"; //客户
			$(".search_title").html("折让信息");
			break; 
		case 'GKFK': //高开付款
			company_type = "gk";
			$(".search_title").html("高开信息");
			// $("#FrmFormBill_pay_type").val("money");
			break; 
		case 'DLFK': //代理付款
			company_type = "supply"; //供应商
			$(".search_title").html("托盘采购信息");
			break; 
		case 'TPYF': //托盘预付
			company_type = "pledge"; //
			$(".search_title").html("托盘采购信息");
			break;
		case 'TPSH': //托盘赎回
			company_type = "pledge"; //
			$(".search_title").html("托盘赎回信息");
			break; 
		case 'GCFL': //钢厂返利
			company_type = "supply"; //供应商
			$(".search_title").html("钢厂返利信息");
			break;
		case 'CKFL': //仓库返利
			company_type = "warehouse"; //仓库结算单位
			$(".search_title").html("仓库返利信息");
			break;
		case 'CCFY': //仓储费用
			company_type = "warehouse"; //仓库结算单位
			$(".search_title").html("仓储费用信息");
			break;
		case 'YF': //运费
			company_type = "logistics"; //物流商
			$(".search_title").html("运费信息");
			break; 
		default: break;
	}
	
	if (is_supply > 0) $("#supply_select").show();
	else if (is_customer > 0) $("#customer_select").show();
	else if (is_gk > 0) $("#gk_select").show();
	else if (has_warehouse > 0) $("#warehouse_select").show();
	else if (is_logistics > 0) $("#logistics_select").show();
	else if (has_pledge > 0) $("#pledgeCompany_select").show();
	
	$("#yidan_val").parent().hide();
	if (has_yidan > 0) 
	{
		$("#yidan_val").parent().show();
		if ($(this).val() == 'GKFK') {
			$("#yidan_val").attr('checked', "checked");
			$("#FrmFormBill_pay_type").val("money");
			$("#FrmFormBill_pay_type_1").val("money");
		}
		if ($("#yidan_val").attr('checked') == 'checked') 
			$("#FrmFormBill_is_yidan").val(1);
		else 
			$("#FrmFormBill_is_yidan").val(0);
	} else {
		$("#FrmFormBill_is_yidan").val('');
	}

	$("#turnover_list").hide();
	if (has_turnover > 0) 
	{
		$("#turnover_list").show();
		$(".search_title").html("往来明细");
		getTurnoverList(1);
	}
	
	$("#ownedSales_list").hide();
	if (has_ownedSales > 0) 
	{
		$("#ownedSales_list").show();
		$(".search_title").html("销售明细");
		getSalesList(1);
	}
	$("#FrmFormBill_client_id").val(0);
	$("#FrmFormBill_client").hide();
	if(is_customer > 0){
		$("#FrmFormBill_client").show();
		var gk_id = $("#customer_val1").val();
		$("#FrmFormBill_client_id").val(gk_id);
	}
	
	$("#FrmFormBill_gk_client").hide();
	if(is_gk > 0){
		$("#FrmFormBill_gk_client").show();
		var gk_id = $("#customer_val_gk").val();
		$("#FrmFormBill_client_id").val(gk_id);
	}
	$("#billRebate_list").hide();
	if (has_billRebate > 0) 
	{
		$("#billRebate_list").show();
		getbillRebateList(1);
	}

	$("#salverPurchase_list").hide();
	if (has_salverPurchase > 0) 
	{
		$("#salverPurchase_list").show();
		getSalverPurchaseList(1);
	}

	$("#dlfk_list").hide();
	if (has_dlfk > 0) 
	{
		$("#dlfk_list").show();
		getDlfkList(1);
	}
	
	if (is_relation > 0) {
		$(".create_table #cght_tb, .search_body, #bill_list").show();
		getBillList(1);
	}
});

$("#supply_select").click(function(){
	var supply_val = $("#supply_val").val();
	if (supply_val != company_id && is_supply > 0) 
	{
		$("#FrmFormBill_company_id").val(supply_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
		getTurnoverList(1);
		getBillList(1);
	}
});

$("#customer_select").click(function(){
	var customer_val = $("#customer_val").val();
	if (customer_val != company_id && is_customer > 0) 
	{
		$("#FrmFormBill_company_id").val(customer_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
		getTurnoverList(1);
		getBillList(1);
	}
});

$("#FrmFormBill_client").click(function(){
	var customer_val = $("#customer_val1").val();
	if (customer_val != client_id && is_customer > 0) 
	{
		$("#FrmFormBill_client_id").val(customer_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
		getTurnoverList(1);
		getBillList(1);
	}
});

$("#FrmFormBill_gk_client").click(function(){
	var customer_val = $("#customer_val_gk").val();
	if (customer_val != client_id && is_gk > 0) 
	{
		$("#FrmFormBill_client_id").val(customer_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
		getTurnoverList(1);
		getBillList(1);
	}
});

$("#gk_select").click(function(){
	var gk_val = $("#gk_val").val();
	if (gk_val != company_id && is_gk > 0) 
	{
		$("#FrmFormBill_company_id").val(gk_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getTurnoverList(1);
	getBillList(1);
});

$("#warehouse_select").click(function(){
	var warehouse_val = $("#warehouse_val").val();
	if (warehouse_val != company_id && has_warehouse > 0) 
	{
		$("#FrmFormBill_company_id").val(warehouse_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
		getBillList(1);
	}
});

$("#logistics_select").click(function(){
	var logistics_val = $("#logistics_val").val();
	if (logistics_val != company_id && is_logistics > 0) 
	{
		$("#FrmFormBill_company_id").val(logistics_val);
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
		getTurnoverList(1);
		getBillList(1);
	}
});

//托盘公司
$("#pledge_select").click(function(){
	var pledge_val = $("#FrmFormBill_pledge_company_id").val(); 
	if(pledge_val != pledge_company_id && type == 'DLFK'){
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBillList(1);
});

//公司抬头 
$("#title_select").click(function(){
	var title_val = $("#FrmFormBill_title_id").val(); 
	if (title_val != title_id && ['CGFK', 'CGTH', 'XSSK', 'XSTH', 'XSZR', 'GKZR', 'GKFK', 'YF'].indexOf(type) > -1) 
	{
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getDictBankList(title_val);
	getBillList(1);
	getTurnoverList(1);
});

//乙单
$("#yidan_val").click(function() {
	var bank_id=$('#FrmFormBill_dict_bank_info_id').val();	
	if ($(this).attr("checked") == 'checked') {
		$("#FrmFormBill_is_yidan").val(1);		
		getDictBankList();
		fillBankName(bank_id,1);
		$('#FrmFormBill_pay_type').val('money');
		$('#FrmFormBill_pay_type_1').val('money');
		if ($("#FrmFormBill_bill_type").val() == 'CGFK') $("#FrmFormBill_pay_type").val('money'); 
	} else {
		$("#FrmFormBill_is_yidan").val(0);
		getDictBankList();
		fillBankName(bank_id,0);
		$('#FrmFormBill_pay_type').val('cyber');
		$('#FrmFormBill_pay_type_1').val('cyber');
	}
});

function fillBankName(bank_id,is_yidan)
{
	if(!bank_id)return ;
	var title_id=$('#FrmFormBill_title_id').val();
	$.get("/index.php/dictBankInfo/getBankName",{
		'bank_id':bank_id,
		'title_id':title_id,
		'is_yidan':is_yidan
	},function(data){
		if(data)
		{
			$('#FrmFormBill_dict_bank_info_id').val(bank_id);
			$('#FrmFormBill_dict_bank_info_id').prev().children('input').val(data);
		}
	})

}
//业务组
/*
$("#team_select").change(function(){
	var team_val = $(this).val();
	$.get("/index.php/team/getUsers", 
	{
		'team_id': team_val
	}, 
	function(data) 
	{
		$("#CommonForms_owned_by").html(data);
	});
});
*/

//业务员
$("#CommonForms_owned_by").change(function(){
	var owned_by_val = $(this).val();
	$.get("/index.php/user/getTeam", 
	{
		'user_id': owned_by_val
	}, 
	function(data) 
	{
		if (data) $("#team_select").find("option:contains('" + data + "')").attr("selected", "selected");
		else $("#team_select").find("option[value='']").attr("selected", "selected");
		$("#team_select").change(function(){
			$("#CommonForms_owned_by").val(owned_by_val);
		});
	});
	getSalesList(1);
});

/*
//是否乙单
$("#FrmFormBill_is_yidan").change(function(){
	var is_yidan_val = $(this).val();
	if(['CGFK', 'XSSK', 'XSZR', 'GKZR', 'GKFK'].indexOf(type) > -1 && is_yidan_val != is_yidan){
		common_id_array = new Array();
		$("#cght_tb tbody").empty();
		countTotal();
	}
	getBillList(1);
});
*/
//搜索
$(".search_btn").click(function(){
	getBillList(1);
});

//重置搜索
$(".reset").click(function(){
	$(".search_body .forreset").val("");
});

//checkbox 点击不选中
$(".selected_bill").live("click", function(){
	if ($(this).attr("checked") == "checked") $(this).removeAttr("checked");
	else $(this).attr("checked", "checked");
});

//选中
$("#bill_list tbody td").live("click", function(){
	var checkItem = $(this).parent().find(".selected_bill");
	if (checkItem.attr("checked") == "checked") checkItem.removeAttr("checked");
	else checkItem.attr("checked", "checked");
	selectedBill(checkItem);
});

//删除
$(".deleted_tr").live("click", function(){
	var common_id = $(this).parent().parent().find(".td_common_id").val();
	$(this).parent().parent().remove();
	common_id_array.splice(common_id_array.indexOf(common_id), 1);
	$("#bill_list tbody tr").each(function(){
		if($(this).find(".selected_bill").val() == common_id){
			$(this).find(".selected_bill").removeAttr("checked");
		}
	});
	
	tr_num = 0;
	$("#cght_tb tbody .list_num").each(function(){
		tr_num++;
		$(this).html(tr_num);
	});
//	countTotal();
});

$("#FrmFormBill_fee").change(function(){
//	countTotal();
	countDiscount();
});

$("#ownedSales_list .sale_fee").live('click', function(){
	$("#FrmFormBill_fee").val($(this).text());
	$("#FrmFormBill_theory_fee").val($(this).text());

	//获取所有可用信息
	var sales_id=$(this).attr('frmsaleid');
	if(sales_id)
	{
		$.get('/index.php/frmSales/getSimIn',{
			'id':sales_id
		},function(data){
			if(data)
			{
				var ss=eval('('+data+')');
				$('#customer_combo').val(ss.customer_name);
				$('#customer_val').val(ss.customer_id);
				$('#customer_combo1').val(ss.client_name);
				$('#customer_val1').val(ss.client_id);
				$('#FrmFormBill_company_id').val(ss.customer_id);
				$('#FrmFormBill_client_id').val(ss.client_id);
				$('#title_combo').val(ss.title_name);
				$('#FrmFormBill_title_id').val(ss.title_id);
				getDictBankList(ss.title_id);				
			}			
		});
	}
	
});

//保存
$("#submit_btn, #submit_btn1").click(function(){
	if (is_supply > 0 && $("#supply_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#supply_combo").focus();
		});
		return false;
	} 
	if (is_customer > 0 && $("#customer_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#customer_combo").focus();
		});
		return false;
	}
	if (is_customer > 0 && $("#customer_val1").val() <= 0) 
	{
		confirmDialog("请选择客户", function(){
			$("#customer_combo1").focus();
		});
		return false;
	}
	if (is_gk > 0 && $("#customer_val_gk").val() <= 0) 
	{
		confirmDialog("请选择客户", function(){
			$("#customer_combo_gk").focus();
		});
		return false;
	}
	if (is_gk > 0 && $("#gk_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#gk_combo").focus();
		});
		return false;
	}
	if (has_warehouse > 0 && $("#warehouse_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#warehouse_combo").focus();
		});
		return false;
	}
	if (has_pledge > 0 && $("#pledgeCompany_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#pledgeCompany_combo").focus();
		});
	}
	if (is_logistics > 0 && $("#logistics_val").val() <= 0) 
	{
		confirmDialog("请选择结算单位", function(){
			$("#logistics_combo").focus();
		});
		return false;
	}
	if ($.inArray($("#FrmFormBill_bill_type").val(), new Array('DLFK', 'DLSK')) > -1 && $("#FrmFormBill_pledge_company_id").val() <= 0) 
	{
		confirmDialog("请选择托盘公司", function(){
			$("#pledge_combo").focus();
		});
		return false;
	}
	if ($("#FrmFormBill_title_id").val() <= 0) 
	{
		confirmDialog("请选择公司", function(){
			$("#title_combo").focus();
		});
		return false;
	}
	if (!$("#FrmFormBill_dict_bank_info_id").val() || $("#FrmFormBill_dict_bank_info_id").val() == "") 
	{
		confirmDialog("请选择公司公司账户", function() {
			$("#dict_bank_info_combo").focus();
		});
		return false;
	}
	if (!$("#FrmFormBill_fee").val() || $("#FrmFormBill_fee").val() == "") 
	{
		confirmDialog("请填写总金额", function(){
			$("#FrmFormBill_fee").focus();
		});
		return false;
	} 
	else if (isNaN(numChange($("#FrmFormBill_fee").val())) || parseFloat(numChange($("#FrmFormBill_fee").val())) <= 0) 
	{
		confirmDialog("总金额必须大于0", function(){
			$("#FrmFormBill_fee").focus();
		});
		return false;
	}
	if (!$("#CommonForms_owned_by").val()) 
	{
		confirmDialog("请选择业务员", function(){
			$("#CommonForms_owned_by").focus();
		});
		return false;
	}
	
	if ($(this).attr("id") == 'submit_btn1') $(this).parent().append('<input type="hidden" name="CommonForms[submit]" value="yes">');
	if (can_submit) 
	{
		can_submit = false;
		// setTimeout(function() { can_submit = true; }, 3000);
		notAnymore($(this).attr("id"));
		$("#form_data").submit();
	}
});

function SetClinetId(){
	var client_id = $("#customer_val1").val();
	$("#FrmFormBill_client_id").val(client_id);
}
function SetGkId(){
	var client_id = $("#customer_val_gk").val();
	$("#FrmFormBill_client_id").val(client_id);
}
$(function(){
<?php if ($msg) {?>
<?php if($msg=='您看到的信息不是最新的，请刷新后再试'){?>
	confirmDialog("<?php echo $msg;?>",function(){window.location.href="/index.php/formBill/index?type="+"<?php echo $baseform->form_type?>";});
<?php }else{?>
	confirmDialog("<?php echo $msg;?>");
<?php }}?>
	$("#pledgeCompany_combo").combobox(pledge_array, {}, "pledgeCompany_select", "pledgeCompany_val", false, "getBankList()");
	$("#supply_combo").combobox(supply_array, {}, "supply_select", "supply_val", false, "getBankList()");
	$("#logistics_combo").combobox(logistics_array, {}, "logistics_select", "logistics_val", false, "getBankList()");
	$("#customer_combo").combobox(customer_array, {}, "customer_select", "customer_val", false, "getBankList()");
	$("#customer_combo1").combobox(customer_array, {}, "customer_select1", "customer_val1",false,"SetClinetId()");
	$("#customer_combo_gk").combobox(customer_array, {}, "customer_select_gk", "customer_val_gk",false,"SetGkId()");
	$("#gk_combo").combobox(gk_array, {}, "gk_select", "gk_val", false, "getBankList()");
	$("#warehouse_combo").combobox(warehouse_array, {}, "warehouse_select", "warehouse_val", false, "getBankList()");
	$("#title_combo").combobox(title_array, {}, "title_select", "FrmFormBill_title_id", false, "getDictBankList()");
	$("#pledge_combo").combobox(pledge_array, {}, "pledge_select", "FrmFormBill_pledge_company_id", false, "getPledgeBankList()");
	
	$("#bank_info_combo").combobox(bank_info_array, {}, "bank_info_select", "FrmFormBill_bank_info_id", false, '', 220);
	$("#dict_bank_info_combo").combobox(dict_bank_info_array, {}, "dict_bank_info_select", "FrmFormBill_dict_bank_info_id", false, '', 220);
	$("#pledge_bank_info_combo").combobox(pledge_bank_info_array, {}, "pledge_bank_info_select", "FrmFormBill_pledge_bank_info_id", false, '', 220);

	//记录编辑前数据
	$("#cght_tb tbody tr").each(function(){
		id_array.push($(this).find(".td_id").val());
		_common_id_array.push($(this).find(".td_common_id").val());
		common_id_array.push($(this).find(".td_common_id").val());
	});
	$("#CommonForms_owned_by").change();	
	
	$("#turnover_list").hide();
	if (has_turnover > 0) 
	{
	<?php if ($baseform->form_type == 'FKDJ' && (!$model->id || $baseform->form_status == 'unsubmit')) {?>
		$("#turnover_list").show();
	<?php }?>
		$(".search_title").html("往来明细");
		getTurnoverList(1);
	}
	
	$("#ownedSales_list").hide();
	if (has_ownedSales > 0) 
	{
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {} else {?>
		$("#ownedSales_list").show();
	<?php }?>
		$(".search_title").html("销售明细");
		getSalesList(1);
	}

	$("#billRebate_list").hide();
	if (has_billRebate > 0) 
	{
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {} else {?>
		$("#billRebate_list").show();
	<?php }?>
		getbillRebateList(1);
	}

	$("#salverPurchase_list").hide();
	if (has_salverPurchase > 0) 
	{
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {} else {?>
		$("#salverPurchase_list").show();
	<?php }?>
		getSalverPurchaseList(1);
	}

	$("#dlfk_list").hide();
	if (has_dlfk > 0) 
	{
		$("#dlfk_list").show();
		getDlfkList(1);
	}

	$(".create_table #cght_tb, .search_body, #bill_list").hide();
	if (is_relation > 0) {
	<?php if ($baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited') {} else {?>
	$(".create_table #cght_tb, .search_body, #bill_list").show();
	<?php }?>
		getBillList(1);
	}
<?php if (!$model->id) {?>
	if ($("#FrmFormBill_company_id").val() && $("#FrmFormBill_company_id").val() > 0) 
	{
		getBankList($("#FrmFormBill_company_id").val());
		$("#title_select").click();
	}
<?php }?>
});

$('#FrmFormBill_pay_type').on('change',function(e){
	$('#FrmFormBill_pay_type_1').val($(this).val());
});
$(function(){
	$('yidan_val')
	$('#FrmFormBill_pay_type_1').val($('#FrmFormBill_pay_type').val());	
});

</script>
