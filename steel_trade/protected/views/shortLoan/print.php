<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
		<style>
				body{margin:0;padding:0;}
				.print_body{margin-left:0;float:left;width:735px;}
				.title_name{flaot:left;width:100%;height:30px;line-height:30px;margin-top:30px;font-size:20px;text-align:center;}
				.bill_name{flaot:left;width:100%;height:30px;line-height:30px;font-size:17px;text-align:center;}
				.title_list{flaot:left;width:100%;line-height:20px;font-size:15px;margin-top:15px;}
				.bumen_name{float:left;width:25%;}
				.bill_date{float:left;width:25%;}
				.fujian{float:left;width:25%;}
				.bill_no{float:right;width:100%;text-align:right;}
				table{float:left;width:735px;border:1px solid #666;margin:0;padding:0; border-collapse:collapse;font-size:15px;}
				table tbody{margin:0;padding:0;}
				table tbody tr{margin:0;padding:0;}
				table td{border:1px solid #666;margin:0;line-height:30px;height:30px;}
				.c{text-align:center;}
				.td1{width:20%;}
				.td2{width:80%;padding-left:15px;}
				.juhe1{padding-left:5px;}
				.juhe2{padding-left:15px;}
				.foot_list{flaot:left;width:100%;height:30px;line-height:30px;font-size:16px;}
		</style>
	</head>
	<body>
		<div class="print_body">
			<div class="title_name"><?php echo $model->lending_direction=="borrow"?"入款":"出款";?>申请单</div>
			<div class="title_list">
					<div class="bill_no">编号：<?php echo $baseform->form_sn;?></div>
					<div class="bill_no">日期：<?php echo $model->oneloanRecord->reach_at>0?date("Y-m-d",$model->oneloanRecord->reach_at):"";?></div>
			</div>
			<table>
				<tbody>
					<tr>
							<td class="td1 c">用途</td>
							<td class="td2 "><?php echo $baseform->comment;?></td>
					</tr>
					<tr>
							<td class="td1 c"><?php echo $model->lending_direction=="borrow"?"入款":"出款";?>名称</td>
							<td class="td2 "><?php echo $model->company->name;?></td>
					</tr><tr>
							<td class="td1 c">金额</td>
							<td class="td2 ">
							<div style="float:left;">大写：<?php echo cny($model->oneloanRecord->amount);?></div>
							<div style="float:right;text-decoration:underline;">￥<?php echo number_format($model->oneloanRecord->amount,2);?></div>
							</td>
					</tr><tr>
							<td class="td1 c">账户及开户行</td>
							<td class="td2 ">(<?php echo $model->oneloanRecord->dictBank->bank_number;?>)<?php echo $model->oneloanRecord->dictBank->dict_name;?></td>
					</tr>
				</tbody>
			</table>
			<div class="foot_list">
					<div class="bumen_name">财务主管：</div>
					<div class="bill_date">部门主管：</div>
					<div class="fujian">出纳：</div>
					<div class="fujian" style="width:24%;">经办人：<?php echo $baseform->operator->nickname;?></div>
			</div>
		</div>
	</body>
</html>