<style>
body{color:black;font-family: "Open Sans","Microsoft YaHei",宋体,verdana,arial;margin:0;padding:0;}
.info{float: left;margin: 3px auto 0 130px;width:350px;height: 30px;}
.productinfo{float:left;height:30px;text-align:center;}
.blue{border:1px solid #1c9fe1;color:#fff;border-radius:3px;}
</style>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" />
	<title></title>
</head>
<body>
	<div class="index_top">
		<div class="logo">
			<img src="<?php echo imgUrl('log.png');?>" />
		</div>
		<div class="log_tit">进销存系统</div>
		<?php if ($this->pageTitle && $this->pageTitle!="进销存系统"){?><div class="pageTitle"><span>—</span><?php echo $this->pageTitle?></div><?php } ?>
		<div class="user_quit">
			<a href="<?php echo Yii::app()->createUrl('site/logout');?>">
				<div class="user_quit_baby">
					<img src="<?php echo imgUrl('quit.png');?>">退出
				</div>
			</a>
		</div>
		<div class="message">
		     <img src="/images/msgRing.png"/>
		     <span>消息</span>
		     <?php $count=MessageContent::model()->getCount(); ?>
		     <div class="msg_count" style="<?php if(!$count) echo 'display:none;';?>">
		     	<?php if($count){echo $count;} ?>
		     </div>
		</div>
		<div class="user">
			您好，<?php echo Yii::app()->user->nickname;?>
		</div>
		<?php if($this->setHome==1){?>
		<div class="set_home" id="set_home" url='<?php echo Yii::app()->request->getUrl();?>'>
			<img src="<?php echo imgUrl('set_home.png');?>">设为首页
		</div>
		<?php }?>
	</div>
<div id="main" style="width:100%;font-size:12pt;font-family:sans-serif;color:black;float:left;">
	<div style="margin:20px auto 0;width:1200px;height:30px;">
		<a target="_blank" href="<?php echo Yii::app()->createUrl('print/print', array('id'=>$baseform->id));?>">
			<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="float:right;">打印</button>
		</a>
	</div>
	<div id="main1" style="margin:20px auto;width:1200px;height:800px;position:relative">
		<img src="<?php echo imgUrl('TX_SellBill.jpg');?>" id="printImg" class="printImg" style="width:1200px;vertical-align:top">
		<div id="Allcontent" style="position:absolute;left:75px;top:0;width:1020px;height:700px;">
			<table style="float:left;width:100%;height:80px;margin-top:20px;">
				<tbody>
					<tr>
						<td valign="middle" align="center">
							<span col="FullCompanyName" style="font-size:40px;font-weight:100;letter-spacing:1mm">
							<?php echo $model->dictTitle->name;?>
							</span>
							<!-- <div id="PrintNum" style="float: left;position: absolute;right: 150px;top: 20px;font-weight: bold; font-size: 16pt; border: solid 2px #ff0000; padding: 1mm; color: #ff0000; ">
								<span col="PrintNum" style="color: #ff0000">
								已打印1次
								</span>
							</div> -->
						</td>
					</tr>
				</tbody>
			</table>
			<div id="SellBillNumber" style="float:left;margin: 13px auto 0px 800px;font-weight:bold;font-size:16pt;height:26px;line-height:30px;">
				<span col="SellBillNumber"><?php echo $baseform->form_sn?></span>
			</div>
			<div id="datedays" style="float:left;margin:10px auto 0;width:1020px;height:25px;line-height:28px;">
				<div id="BilledDate" style="float:left;margin-left:100px;" >
					<span col="BilledDate"><?php echo $baseform->form_time?></span>
				</div>
				<div id="EffectiveDays" style="float:right;margin-right:25px;" >
					<span>3</span>
				</div>
			</div>
			<div style="float:left;margin:0 auto 0;width:1020px;height:60px;line-height:30px;">
				<div class="info" id="CustomerName">
					<span col="CustomerName"><?php echo $model->company->name;?></span>
				</div>
				<div class="info" id="taxnum">
					<span col="taxnum"></span>
				</div>
				<div class="info" id="addressphone">
					<span col="addressphone"><?php echo $model->contact->mobile;?></span>
				</div>
				<div class="info" id="bank">
					<span col="bank"></span>
				</div>
			</div>
			<div id="someproduct" style="float:left;margin-top:33px;width:1020px;height:150px;">
			<!-- 下面的这个div可以循环至多4次 -->
			<?php
			$t_weight = 0;
			$t_fee = 0;
			for ($i = 0; $i < 4; $i++) {
				$detail = $details[$i];
				$t_weight += $detail->weight;
				$t_fee += $detail->fee;
			?>
				<div style="float:left;width:1020px;height:31px;line-height:30px;">
					<div class="productinfo" id="ProductName" style="width:170px;">
						<span><?php echo $detail ? DictGoodsProperty::getProName($detail->product_id).DictGoodsProperty::getProName($detail->texture_id)."".DictGoodsProperty::getProName($detail->rank_id).($detail->length >0?"*".$detail->length:"") : '';?></span>
					</div>
					<div class="productinfo" id="OriginName" style="width:113px;">
						<span><?php echo $detail ? DictGoodsProperty::getProName($detail->brand_id) : '';?></span>
					</div>
					<div class="productinfo" id="UnitWeight" style="width:86px;">
						<span>
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
						</span>
					</div>
					<div class="productinfo" id="UnitNumber" style="width:86px;">
						<span><?php echo $detail ? $detail->amount : '';?></span>
					</div>
					<div class="productinfo" id="Quantity" style="width:90px;">
						<span><?php echo $detail ? number_format($detail->weight, 3) : '';?></span>
					</div>
					<div class="productinfo" id="UnitPrice" style="width:100px;">
						<span><?php echo $detail ? number_format($detail->price) : '';?></span>
					</div>
					<div class="productinfo" id="Amount" style="width:150px;">
						<span><?php echo $detail ? number_format($detail->fee,2) : '';?></span>
					</div>
					<div class="productinfo" id="StockNumber" style="width:225px;">
						<span style="margin-bottom:0;"><?php echo $detail ? $model->travel : '';?></span>
					</div>
				</div>
				<?php }?>
				<div style="float:left;width:1020px;height:30px;line-height:30px;">
					<div class="productinfo" id="ProductName" style="width:170px;"></div>
					<div class="productinfo" id="OriginName" style="width:113px;"></div>
					<div class="productinfo" id="UnitWeight" style="width:86px;"></div>
					<div class="productinfo" id="UnitNumber" style="width:86px;"></div>
					<div class="productinfo" id="Quantity" style="width:90px;">
						<span><?php echo number_format($t_weight,3);?></span>
					</div>
					<div class="productinfo" id="UnitPrice" style="width:100px;"></div>
					<div class="productinfo" id="Amount" style="width:150px;">
						<span><?php echo number_format($t_fee,2);?></span>
					</div>
					<div class="productinfo" id="StockNumber" style="width:225px;"></div>
				</div>
			</div>
			<div style="float:left;width:950px;height:30px;margin:2px auto 0 70px;line-height:36px;">
				<span><?php echo htmlspecialchars($model->comment);?></span>
			</div>
			<div style="float:left;width:1020px;height:30px;line-height:30px;">
				<div id="money" style="float:left;margin:5px auto 0 150px;width:350px;height:25px">
					<span><?php echo cny($t_fee);?></span>
				</div>
				<div id="total" style="float:left;margin:5px auto 0 100px;width:350px;height:25px">
					<span><?php echo ch_num($t_weight)."吨";?></span>
				</div>
			</div>
			<div style="float:left;margin:5px auto 0 100px;width:920px;height:25px;line-height:30px;">
				<span><?php
							echo $model->warehouse->name;
							if($model->warehouse->address){
								echo ":".$model->warehouse->address;
							}
						?></span>
			</div>
			<div style="float:left;margin:0 auto 0 492px;width:155px;height:120px;">
				<!-- <img src="beijing.jpg" style="width:120px;"> -->
			</div>
			<div style="float:left;margin:5px auto 0 80px;width:920px;height:25px;line-height:30px;">
				<span><?php echo $baseform->belong->nickname;?></span>
			</div>
		</div>
	</div>
</div>


</body>
</html>
