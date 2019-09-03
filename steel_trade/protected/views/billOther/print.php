<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
		<style>
				body{margin:0;padding:0;}
				.print_body{margin-left:0;float:left;width:735px;}
				.title_name{flaot:left;width:100%;height:30px;line-height:30px;margin-top:30px;font-size:18px;text-align:center;}
				.bill_name{flaot:left;width:100%;height:30px;line-height:30px;font-size:17px;text-align:center;}
				.title_list{flaot:left;width:100%;height:30px;line-height:30px;font-size:15px;margin-top:15px;}
				.bumen_name{float:left;width:136px;}
				.bill_date{float:left;width:166px;}
				.fujian{float:left;width:120px;}
				.bill_no{float:right;width:310px;text-align:right;}
				table{float:left;width:735px;border:1px solid #666;margin:0;padding:0; border-collapse:collapse;font-size:15px;}
				table tbody{margin:0;padding:0;}
				table tbody tr{margin:0;padding:0;}
				table td{border:1px solid #666;margin:0;line-height:26px;height:26px;}
				.c{text-align:center;}
				.td1{width:135px;}
				.td2{width:165px;}
				.td3{width:119px;}
				.td4{width:310px;}
				.juhe1{padding-left:5px;}
				.juhe2{padding-left:15px;}
				.foot_list{flaot:left;width:100%;height:30px;line-height:30px;font-size:16px;}
		</style>
	</head>
	<body>
		<div class="print_body">
			<div class="title_name"><?php echo $model->title->name;?></div>
			<div class="bill_name">费用报销单</div>
			<div class="title_list">
					<div class="bumen_name">部门名称：<?php echo $baseform->belong->team->name;?></div>
					<div class="bill_date">报销日期：<?php echo $model->account_at > 0 ? date('Y-m-d', $model->account_at) : '';?></div>
					<div class="fujian">附件张数：0张</div>
					<div class="bill_no">No.<?php echo $baseform->form_sn;?></div>
			</div>
			<table>
				<tbody>
					<tr>
							<td class="td1 c">科目</td>
							<td class="td2 c">摘要</td>
							<td class="td3 c">金额</td>
							<td class="td4 c">备注</td>
					</tr>
					<?php 
							$fee = 0;
							for ($i = 0; $i < 5; $i++) { 
								$detail = $details[$i];
								$fee += $detail->fee;
					?>
					<tr>
							<td class="td1 c"><?php echo $detail->recordType1->name;?></td>
							<td class="td2 c"><?php echo $detail->recordType2->name;?></td>
							<td class="td3 c"><?php echo $detail->fee>0?number_format($detail->fee,2):"";?></td>
							<td class="td4 c"><?php echo $detail->fee>0?htmlspecialchars($baseform->comment):"";?></td>
					</tr>
					<?php }?>
					<tr>
							<td class="juhe1" colspan=2>人民币(大写)：<?php echo cny($fee);?></td>
							<td class="juhe2" colspan=2>￥<?php echo number_format($fee,2);?></td>
					</tr>
				</tbody>
			</table>
			<div class="foot_list">
					<div class="bumen_name">审批人：<?php //echo $model->account->nickname;?></div>
					<div class="bill_date">部门主管：</div>
					<div class="fujian">报销人：<?php //echo $baseform->belong->nickname;?></div>
			</div>
		</div>
	</body>
</html>