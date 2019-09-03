<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
		<style>
				body{margin:0;padding:0;}
				p{float:left;margin:0;padding:0;line-height:25px;width:100%;}
				.l{float:left}
				.r{float:right}
				.print_body{margin-left:0;float:left;width:720px;font-family:"Open Sans","Microsoft YaHei",宋体,verdana,arial;font-size:15px;}
				.title_name{flaot:left;width:100%;height:30px;line-height:30px;margin-top:30px;font-size:22px;text-align:center;font-weight:700;}
				.bill_name{flaot:left;width:100%;height:30px;line-height:30px;text-align:center;}
				.form_sn{float:left;width:100%;text-align:right;}
				table{float:left;width:100%;border:1px solid #666;margin:0;padding:0; border-collapse:collapse;text-align:center;margin-bottom:5px;}
				table tbody{margin:0;padding:0;}
				table tbody tr{margin:0;padding:0;}
				table td{border:1px solid #666;margin:0;line-height:25px;height:25px;}
		</style>
	</head>
	<body>
		<div class="print_body">
			<div class="title_name"><?php echo $model->dictTitle->name;?></div>
			<div class="bill_name">采购订单</div>
			<div class="form_sn">确认单号：<?php echo $model->contract_no;?></div>
			<p>供方：<?php echo $model->dictCompany->name;?></p>
			<p style="margin-top:5px;">
					<span class="l">一、产品名称、产地、规格型号、材质、单位、件数、重量</span>
					<span class="r">签订时间：<?php echo $baseform->form_time;?></span>
			</p>
			<table>
				<tbody>
					<tr>
							<td>序号</td>
							<td>产品名称</td>
							<td>产地</td>
							<td>规格型号</td>
							<td>材质</td>
							<td>单位</td>
							<td>件数</td>
							<td>重量</td>
					</tr>
					<?php 
							$amount = 0;
							$weight = 0;
							$i = 0;
							foreach($details as $li) { 
								$amount += $li->amount;
								$weight += $li->weight;
								$i++;
					?>
					<tr>
							<td><?php  echo $i;?></td>
							<td><?php  echo DictGoodsProperty::getProName($li->product_id);?></td>
							<td><?php  echo DictGoodsProperty::getProName($li->brand_id);?></td>
							<td><?php echo $li->rank_id;?></td>
							<td><?php echo $li->texture_id;?></td>
							<td>吨</td>
							<td><?php echo $li->amount;?></td>
							<td><?php echo number_format($li->weight,3);?></td>
					</tr>
					<?php }?>
					<tr>
							<td  colspan=2>合计：</td>
							<td ></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo $amount;?></td>
							<td><?php echo number_format($weight,3);?></td>
					</tr>
				</tbody>
				<tfoot>
						<tr>
								<td colspan=8  style="text-align:left" >备注：<?php echo $baseform->comment;?></td>
						</tr>
				</tfoot>
			</table>
			<p>
				二、质量要求技术标准、供方对质量负责的条件和期限：<br/>
				1、提供质保书和生产许可证。根据中华人民共和国建筑规范，需方必须委托权威机构检测合格方能使用。<br/>
				2、螺纹钢、圆钢、型钢按理论计算，以每件出厂件重（每件保留小数点后三位）计算，拆件验收，缺重自理。<br/>
				3、高线、盘螺过磅计算，正常磅差为±3‰，如需复磅，请于货到时复磅，逾期本公司概不负责。如需调直，则按理论计算。<br/>
				4、以上重量均为计划单重量，实际以签收单为准。<br/>
				三、交货时间：要求在计划单申报日起七日内给予进场。<br/>
				四、交提货地点及联系人：_____________________<br/>
				五、收货人：_____________________<br/>
				六、业务员代表及联系方式: _____________________<br/>
				七、以上重量为计划单重量，实际以签收单为准。<br/>
				八、违约责任及解决合同纠纷的方式：双方严格按照此协议执行，如有未达事项，双方协商解决，经供方签字后有效，传真件亦具法律效力。<br/>
			</p>
			<p style="width:50%;">
					<span class="l">供方：</span><br/>
					<span class="l" style="text-indent: 2em;"><?php echo $model->dictCompany->name;?></span><br/>
					<span class="l" style="margin-top:20px;">代表（签字）：</span>
			</p>
			<p style="width:50%;">
					<span class="l">需方：</span><br/>
					<span class="l" style="text-indent: 2em;"><?php echo $model->dictTitle->name;?></span><br/>
					<span class="l" style="margin-top:20px;">代表（签字）：</span>
			</p>
		</div>
	</body>
</html>