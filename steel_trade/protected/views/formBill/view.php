<style>
<!--
#cght_tb tbody td{ line-height: 26px;}
#cght_tb tbody td{ line-height: 26px;}
.detail_table_backg{ width: 13%;}
.detail_table_backg + td{ width: 20.3%;}
.detail_table_backg + td span{ display: block; width: 100%; height: 38px; padding: 0 2%; text-align: center; overflow: hidden;}
.table > thead > tr > th{ border-bottom: none;}
.create_table thead th{ border-bottom: none;}
-->
</style>
<?php
$type = $baseform->form_type;
$is_customer = in_array($model->bill_type, array('XSTH', 'XSZR', 'GKZR', 'XSSK'));
$is_gk = in_array($model->bill_type, array('GKFK'));
switch ($type) 
{
	case 'FKDJ': 
		$title = "付款";
		break;
	case 'SKDJ': 
		$title = "收款";
		break;
	default: break;
}

switch ($model->bill_type) 
{
	case 'CGFK': //采购付款
		$company_type = "supply"; //供应商
		$has_yidan = 1;
		$has_turnover = 1;
		break; 
	case 'XSSK': //销售收款
		$company_type = "customer"; //客户
		$has_yidan = 1;
		$has_ownedSales = 1;
		break; 
	case 'XSTH': //销售退货付款
		$company_type = "customer"; //客户
		$has_yidan = 1;
		$has_relation = 1;
		break; 
	case 'CGTH': //采购退货收款
		$company_type = "supply"; //供应商
		$has_yidan = 1;
		$has_relation = 1;
		break; 
	case 'XSZR': //销售折让
		$company_type = "customer"; //客户
		$has_relation = 1;
		break; 
	case 'XSZR': //销售折让
		$company_type = "customer"; //客户
		$has_relation = 1;
		break;
	case 'GKFK': //高开付款
		$company_type = "gk";
		$has_relation = 1;
		break; 
	case 'DLFK': //代理付款
		$company_type = "supply"; //供应商
		$has_relation = 1;
		break; 
	case 'TPSH': //托盘赎回
		$company_type = "supply"; //供应商
		$has_relation = 1;
		break; 
	case 'YF': //运费
		$company_type = "logistics"; //物流商
		$has_relation = 1;
		break; 
	case 'CKFL': //仓库返利
		$company_type = "warehouse"; //仓库结算单位
		break; 
	case 'GCFL': //钢厂返利
		$company_type = "supply"; //供应商
		break; 
	case 'CCFY': //仓储费用
		$company_type = "warehouse"; //仓库结算单位
		break; 
	case 'BZJ': //保证金
		$company_type = "supply"; //供应商
		$has_turnover = 1;
		break; 
	default: break;
}
?>

<table class="detail_table">
	<tbody>
		<tr>
			<td class="detail_table_backg">结算单位</td><td><span title="<?php echo $model->company->name;?>"><?php echo $model->company->name;?></span></td>
			<td class="detail_table_backg">结算账户</td>
			<td><?php echo $model->bankInfo ? '<span title="'.$model->bankInfo->company_name.'('.$model->bankInfo->bank_number.')">'.$model->bankInfo->company_name.'('.$model->bankInfo->bank_number.')</span>' : '';?></td>
			<td class="detail_table_backg"><?php echo $title;?>类型</td><td><?php echo FrmFormBill::$billTypes[$model->bill_type];?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">公司</td><td><span title="<?php echo $model->title->name;?>"><?php echo $model->title->short_name;?></span></td>
			<td class="detail_table_backg">公司账户</td>
			<td><?php echo $model->dictBankInfo ? '<span title="'.$model->dictBankInfo->dict_name.'('.$model->dictBankInfo->bank_number.')">'.$model->dictBankInfo->dict_name.'('.$model->dictBankInfo->bank_number.')</span>' : '';?></td>
			<td class="detail_table_backg"><?php echo $title;?>方式</td><td><?php echo FrmFormBill::$payTypes[$model->pay_type];?></td>
		</tr>
	<?php if ($model->bill_type == 'DLFK') {?>
		<tr>
			<td class="detail_table_backg">托盘公司</td><td><span title="<?php echo $model->pledgeCompany->name;?>"><?php echo $model->pledgeCompany->short_name;?></span></td>
			<td class="detail_table_backg">托盘账户</td>
			<td><?php echo $model->pledgeBankInfo ? '<span title="'.$model->pledgeBankInfo->company_name.'('.$model->pledgeBankInfo->bank_number.')">'.$model->pledgeBankInfo->company_name.'('.$model->pledgeBankInfo->bank_number.')</span>' : '';?></td>
			<td class="detail_table_backg"></td><td></td>
		</tr>
	<?php }?>
		<tr>
			<td class="detail_table_backg">业务员</td><td><?php echo $baseform->belong->nickname;?></td>
			<td class="detail_table_backg">业务组</td><td><?php echo $baseform->belong->team->name;?></td>
			<td class="detail_table_backg">总金额</td><td><?php echo number_format($model->fee, 2);?>元</td>
		</tr>
		<tr>
			<td class="detail_table_backg">乙单</td><td><?php echo $has_yidan ? ($model->is_yidan == 1 ? "是" : "否") : '';?></td>
			<td class="detail_table_backg">状态</td><td class="red">
			<?php echo $baseform->form_type == 'FKDJ' && $baseform->form_status == 'submited' ? "审核中" : CommonForms::$formStatus[$baseform->form_status];?>
			</td>
			<td class="detail_table_backg">到账日期</td><td><?php echo $model->reach_at > 0 ? date('Y-m-d', $model->reach_at) : '';?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">制单人</td><td><?php echo $baseform->operator->nickname;?></td>
			<td class="detail_table_backg">最后审核人</td><td><?php echo $baseform->approver->nickname;?></td>
			<td class="detail_table_backg">入账人</td><td><?php echo $model->account->nickname;?></td>
		</tr>
		<tr>
			<td class="detail_table_backg">最后操作人</td><td><?php echo $baseform->lastupdate->nickname;?></td>
			<td class="detail_table_backg">最后更新时间</td><td><?php echo $baseform->last_update > 0 ? date('Y-m-d H:i:s', $baseform->last_update) : '';?></td>
		<?php if ($baseform->form_type == 'FKDJ') {?>
			<td class="detail_table_backg">用途</td>
			<td>
			<?php $text =  htmlspecialchars($model->purpose); 
				$length = mb_strlen($text,"UTF-8");
				if ($length > 15) 
				{
					$text_sub = mb_substr($text,0,15,"UTF-8");
					echo '<div class="comment_text">'.$text_sub.'<img src="/images/nummore.png" style="margin:-2px 0 0 3px;">';
					echo '<div class="comment_text_close">x</div>';
					echo '<div class="comment_text_t">'.$text.'</div>';
					echo '</div>';
				} 
				else 
				{
					echo $text;
				}
			?>
			</td>
			<?php }else{?>
			<td class="detail_table_backg"></td><td></td>
			<?php }?>
		</tr>
		<tr>
			<?php if($is_customer || $is_gk){?>
			<td class="detail_table_backg">客户</td><td><span title="<?php echo $model->client->name;?>"><?php echo $model->client->short_name;?></span></td>
			<?php }?>
			<td class="detail_table_backg">备注</td>
			<td>
			<?php $text =  htmlspecialchars($baseform->comment); 
				$length = mb_strlen($text,"UTF-8");
				if ($length > 15) 
				{
					$text_sub = mb_substr($text,0,15,"UTF-8");
					echo '<div class="comment_text">'.$text_sub.'<img src="/images/nummore.png" style="margin:-2px 0 0 3px;">';
					echo '<div class="comment_text_close">x</div>';
					echo '<div class="comment_text_t">'.$text.'</div>';
					echo '</div>';
				} 
				else 
				{
					echo $text;
				}
			?>
			</td>
			<?php if(!$is_customer && !$is_gk){?>
			<td class="detail_table_backg"></td><td></td>
			<?php }?>
			<td class="detail_table_backg"></td><td></td>
		</tr>
	</tbody>
</table>

<div class="create_table"<?php echo $has_relation ? '' : ' style="display: none;"';?>>
	<table id="cght_tb" class="table" style="display: none;">
		<thead>
			<tr>
				<th style="width: 40px;"></th>
				<th class="flex-col" style="width: 150px;">单号</th>
				<th class="flex-col" style="width: 150px;">登记日期</th>
			<?php 
			switch ($model->bill_type) 
			{
				case 'CGFK': //采购付款
				case 'DLFK': //代理付款
			?>
				<th class="flex-col" style="width: 150px;">供应商</th>
				<th class="flex-col" style="width: 120px;">重量</th>
				<th class="flex-col" style="width: 120px;">金额</th>
				<th class="flex-col" style="width: 100px;">乙单</th>
				<th class="flex-col" style="width: 140px;">类型</th>
				<th class="flex-col" style="width: 140px;">业务组</th>
				<th class="flex-col" style="width: 140px;">业务员</th>
			<?php break;
				case 'TPSH': //托盘赎回?>
				<th class="flex-col" style="width: 140px;">托盘公司</th>
				<th class="flex-col" style="width: 120px;">产地</th>
				<th class="flex-col" style="width: 120px;">品名</th>
				<th class="flex-col" style="width: 130px;">托盘金额</th>
				<th class="flex-col" style="width: 130px;">利息</th>
				<th class="flex-col" style="width: 150px;">采购单号</th>
				<th class="flex-col" style="width: 140px;">业务员</th>
			<?php break;
				case 'CGTH': //采购退货收款?>
				
			<?php break;
				case 'XSSK': //销售收款?>
			
			<?php break;
				case 'XSTH': //销售退货付款?>
				<th class="flex-col" style="width: 150px;">客户</th>
				<th class="flex-col" style="width: 150px;">仓库</th>
				<th class="flex-col" style="width: 150px;">车船号</th>
				<th class="flex-col" style="width: 100px;">乙单</th>
				<th class="flex-col" style="width: 150px;">预计退货时间</th>
				<th class="flex-col" style="width: 140px;">业务组</th>
				<th class="flex-col" style="width: 140px;">业务员</th>
			<?php break;
				case 'XSZR': //销售折让?>
				<th class="flex-col" style="width: 150px;">结算单位</th>
				<th class="flex-col" style="width: 120px;">金额</th>
				<th class="flex-col" style="width: 100px;">乙单</th>
				<th class="flex-col" style="width: 140px;">折让类型</th>
				<th class="flex-col" style="width: 140px;">业务组</th>
				<th class="flex-col" style="width: 140px;">业务员</th>
			<?php break;
				case 'GKFK': //高开付款?>
				<th class="flex-col" style="width: 150px;">销售单号</th>
				<th class="flex-col" style="width: 260px;">产地/品名/材质/规格/长度</th>
				<th class="flex-col" style="width: 120px;">件数</th>
				<th class="flex-col" style="width: 120px;">重量</th>
				<th class="flex-col" style="width: 120px;">高开单价</th>
				<th class="flex-col" style="width: 120px;">高开金额</th>
				<th class="flex-col" style="width: 100px;">付款</th>
				<th class="flex-col" style="width: 140px;">业务员</th>
			<?php break;
				case 'YF': //运费?>
				<th class="flex-col" style="width: 150px;">公司</th>
				<th class="flex-col" style="width: 150px;">收益单位</th>
				<th class="flex-col" style="width: 120px;">重量</th>
				<th class="flex-col" style="width: 120px;">单价</th>
				<th class="flex-col" style="width: 120px;">金额</th>
				<th class="flex-col" style="width: 150px;">车船号</th>
				<th class="flex-col" style="width: 140px;">业务员</th>
			<?php break;
				default: break; 
			}?>
			</tr>
		</thead>
		
		<tbody>
		<?php if ($relations) { 
			$tr_num = 0; 
			foreach ($relations as $each) { 
				$tr_num++;
		?>
			<tr>
				<td class="text-center list_num" style="width: 40px;"><?php echo $tr_num;?></td>
				<td style="width: 150px;"><?php echo $each->common->form_sn;?></td>
				<td style="width: 150px;"><?php echo date('Y-m-d', $each->common->created_at);?></td>
		<?php 
		switch ($model->bill_type) 
		{
			case 'CGFK': //采购付款
			case 'DLFK': //代理付款
				$relation_data = $each->common->purchase;
				$_type = array('normal' => "库存采购", 'tpcg' => "托盘采购", 'xxhj' => "直销采购", 'dxcg' => "代销采购");
		?>
				<td style="width: 150px;"><span title="<?php echo $relation_data->supply->name;?>"><?php echo $relation_data->supply->short_name;?></span></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->weight, 3);?></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->price_amount, 2);?></td>
				<td style="width: 100px;"><?php echo $relation_data->is_yidan == 1 ? '是' : '否';?></td>
				<td style="width: 140px;"><?php echo $_type[$relation_data->purchase_type];?></td>
				<td style="width: 140px;"><?php echo $relation_data->team->name;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'TPSH': //托盘赎回
				$relation_data = $each->common->pledgeRedeem;
		?>
				<td style="width: 140px;"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->short_name;?></span></td>
				<td style="width: 120px;"><?php echo DictGoodsProperty::getProName($relation_data->brand_id);?></td>
				<td style="width: 120px;"><?php echo DictGoodsProperty::getProName($relation_data->product_id);?></td>
				<td style="width: 130px;"><?php echo number_format($relation_data->total_fee, 2);?></td>
				<td style="width: 130px;">	<?php echo number_format($relation_data->interest_fee, 2);?></td>
				<td style="width: 150px;">	<?php echo $relation_data->purchase->baseform->form_sn;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'CGTH': //采购退货收款
				$relation_data = $each->common->purchaseReturn;
		?>
				<td style="width: 150px;"><span title="<?php echo $relation_data->supply->name;?>"><?php echo $relation_data->supply->short_name;?></span></td>
				<td style="width: 150px;"><?php echo $relation_data->warehouse->name;?></td>
				<td style="width: 150px;"><?php echo $relation_data->travel;?></td>
				<td style="width: 150px;"><?php echo date('Y-m-d', $relation_data->return_data);?></td>
				<td style="width: 140px;"><?php echo $relation_data->team->name;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSSK': //销售收款
				$relation_data = $each->common->sales;
				$_type = array('normal' => "库存销售", 'xxhj' => "先销后进", 'dxxs' => "代销销售");
		?>
				<td style="width: 150px;"><span title="<?php echo $relation_data->dictCompany->name;?>"><?php echo $relation_data->dictCompany->short_name;?></span></td>
				<td style="width: 120px;">	<?php echo number_format($relation_data->weight, 3);?></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->amount);?></td>
				<td style="width: 100px;"><?php echo $relation_data->is_yidan == 1 ? '是' : '否';?></td>
				<td style="width: 140px;"><?php echo $_type[$relation_data->sales_type];?></td>
				<td style="width: 140px;"><?php echo $relation_data->team->name;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSTH': //销售退货付款
				$relation_data = $each->common->salesReturn;
		?>
				<td style="width: 150px;"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->short_name;?></span></td>
				<td style="width: 150px;"><span title="<?php echo $relation_data->warehouse->name;?>"><?php echo $relation_data->warehouse->name;?></span></td>
				<td style="width: 150px;"><?php echo $relation_data->travel;?>
				<td style="width: 100px;"><?php echo $relation_data->is_yidan == 1 ? '是' : '否';?></td>
				<td style="width: 150px;"><?php echo date('Y-m-d', $relation_data->return_date);?></td>
				<td style="width: 140px;"><?php echo $relation_data->team->name;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
		<?php 
				break;
			case 'XSZR': //销售折让
				$relation_data = $each->common->rebate;
				$_type = array('sale' => "销售折让", 'shipment' => "采购运费登记", 'shipment_sale' => "销售运费登记", 'high' => "高开折让");
		?>
				<td style="width: 150px;"><span title="<?php echo $relation_data->company->name?>"><?php echo $relation_data->company->short_name?></span></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->amount, 2);?></td>
				<td style="width: 100px;"><?php echo $relation_data->is_yidan == 1 ? "是" : "否";?></td>
				<td style="width: 140px;"><?php echo $_type[$relation_data->type];?></td>
				<td style="width: 140px;"><?php echo $relation_data->team->name;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
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
		?>
				<td style="width: 150px;"><?php echo $relation_data->sales->baseform->form_sn;?></td>
				<td style="width: 260px;"><?php echo $product_info;?></td>
				<td style="width: 120px;"><?php echo number_format($sales_detail->amount);?></td>
				<td style="width: 120px;"><?php echo number_format($sales_detail->weight, 3);?></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->price);?></td>
				<td style="width: 120px;">
					<span class="real_fee"><?php echo number_format($relation_data->real_fee, 2);?></span>
					<input class="discount" type="hidden" name="discount[]" value="<?php echo $relation_data->discount;?>">
				</td>
				<td style="width: 100px;"><?php echo $relation_data->is_pay == 1 ? "已付款" : "未付款";?></td>
				<td style="width: 140px;"><?php echo $baseform->belong->nickname;?></td>
		<?php 
				break;
			case 'YF': //运费
				$relation_data = $each->common->billRecord;
		?>
				<td style="width: 150px;"><span title="<?php echo $relation_data->title->name;?>"><?php echo $relation_data->title->short_name;?></span></td>
				<td style="width: 150px;"><span title="<?php echo $relation_data->company->name;?>"><?php echo $relation_data->company->short_name;?></span></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->weight, 3);?></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->price);?></td>
				<td style="width: 120px;"><?php echo number_format($relation_data->amount, 2);?></td>
				<td style="width: 150px;"><?php echo $relation_data->travel;?></td>
				<td style="width: 140px;"><?php echo $each->common->belong->nickname;?></td>
		<?php break;
			default: break;
		}?>
			</tr>
		<?php } }?>
		</tbody>
	</table>
</div>

<div class="btn_list">
	<a href="<?php echo $back_url;?>">
		<button id="cancel" type="button" class="btn btn-primary btn-sm" data-dismiss="modal">返回</button>
	</a>
</div>

<script type="text/javascript">
<!--
$(function(){
<?php if ($has_relation) {?>
	$("#cght_tb").datatable({
		fixedLeftWidth: 41, 
		fixedRightWidth: 0,
	});
<?php }?>
	$("body").bind("click", function(){
 		$(".comment_text_close").hide();
 		$(".comment_text_t").hide();
	});

	//点击备注
  	$(document).on("click", ".comment_text", function(){
  	  	$(".comment_text_close").show();
  		$(".comment_text_t").show();
  		$(".car_no_list_close").hide();
 		$(".car_no_list").hide();
  	});

  	//点击关闭
 	$(document).on("click", ".comment_text_close", function(e){
 	 	$(this).hide();
 	 	$(this).parent().find(".car_no_list").hide();
 	 	e.stopPropagation();    //  阻止事件冒泡
 	});
});

//-->
</script>

