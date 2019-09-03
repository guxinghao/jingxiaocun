<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title></title>
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" />
	</head>
	
	<body>
		<div class="print_body">
			<div class="print_head">
			<div style="width:100%;height:60px;line-height:60px;text-align:center;font-size:32px;color:#000">
					<?php echo $model->dictTitle->name;?>
			</div>
				<span class="form_no"><?php echo $baseform->form_sn;?></span>
				<div class="print_row">
					<span class="created_date"><?php echo $baseform->form_time;?></span>
					<span class="valid">3</span>
				</div>
			</div>
			
			<div class="print_main">
				<ul class="print_row">
					<li style="width: 95px;"></li>
					<li style="width: 265px;"><?php echo $model->company->name;?></li>
					<li style="width: 60px;"></li>
					<li style="width: 355px;"></li>
				</ul>
				<ul class="print_row">
					<li style="width: 95px;"></li>
					<li style="width: 265px;"><?php echo $model->contact->mobile;?></li>
					<li style="width: 60px;"></li>
					<li style="width: 355px;"></li>
				</ul>
				
				<ul class="print_row list title">
					<li style="width: 126px;"></li>
					<li style="width: 83px;"></li>
					<li style="width: 64px;"></li>
					<li style="width: 64px;"></li>
					<li style="width: 68px;"></li>
	                <li style="width: 76px;"></li>
	                <li style="width: 113px;"></li>
					<li style="width: 181px;"></li>
				</ul>
				<?php $total_amount = 0.0; $t_weight=0;
				for ($i = 0; $i < 4; $i++) { 
					$detail = $details[$i];
				?>
				<ul class="print_row list">
					<li style="width: 126px;white-space:nowrap;"><?php echo $detail ? DictGoodsProperty::getProName($detail->product_id).DictGoodsProperty::getProName($detail->texture_id)."".DictGoodsProperty::getProName($detail->rank_id).($detail->length >0?"*".$detail->length:"") : '';?></li>
					<li style="width: 83px;"><?php echo $detail ? DictGoodsProperty::getProName($detail->brand_id) : '';?></li>
					<li style="width: 64px;">
					<?php 
					$detail_data = array(
						'product' => $detail->product_id, 
						'texture' => $detail->texture_id, 
						'rank' => $detail->rank_id, 
						'brand' => $detail->brand_id, 
						'length' => $detail->length
					);
					echo $detail ? number_format(DictGoods::getUnitWeight($detail_data), 3) : '';
					?>
					</li>
					<li style="width: 64px;"><?php echo $detail ? $detail->amount : '';?></li>
					<li style="width: 68px;"><?php echo $detail ? number_format($detail->weight, 3) : '';?></li>
					<li style="width: 76px;"><?php echo $detail ? number_format($detail->price, 2) : '';?></li>
					<li style="width: 113px;"><?php echo $detail ? number_format($detail->fee, 2) : '';?></li>
					<li style="width: 181px;"><?php echo $detail ? $model->travel : '';?></li>
				</ul>
				<?php 
					$total_amount += $detail->fee; 
					$t_weight += $detail->weight;
				}
				?>
				<ul class="print_row list">
					<li style="width: 126px;white-space:nowrap;"></li>
					<li style="width: 83px;"></li>
					<li style="width: 64px;"></li>
					<li style="width: 64px;"></li>
					<li style="width: 68px;"><?php echo number_format($t_weight,3);?></li>
					<li style="width: 76px;"></li>
					<li style="width: 113px;"><?php echo number_format($total_amount,2);?></li>
					<li style="width: 181px;"></li>
				</ul>
				<ul class="print_row">
					<li style="width: 775px; text-indent: 55px;"><?php echo htmlspecialchars($model->comment);?></li>
				</ul>
            
				<ul class="print_row">
					<li style="width: 360px; text-indent: 100px;"><?php echo cny($total_amount);?></li>
					<li style="width: 415px; text-indent: 80px;"><?php echo ch_num($t_weight)."吨";?></li>
				</ul>

				<ul class="print_row">
					<li style="width: 360px; text-indent: 75px;">
						<?php 
							echo $model->warehouse->name;
							if($model->warehouse->address){
								echo ":".$model->warehouse->address;
							}
						?>
					</li>
				</ul>

				<ul class="print_row">
					<li style="width: 775px; height: 95px;"></li>
				</ul>
			</div>
			
			<div class="print_foot" style="margin-top:-3px;">
				<ul class="print_row">
					<li style="width: 415px; text-indent: 55px;"><?php echo $baseform->belong->nickname;?></li>
					<li style="width: 300px; text-align:right;font-size:30px;"><?php echo $model->is_yidan==1?"***":"";?></li>
				</ul>
			</div>
		</div>
	</body>
</html>