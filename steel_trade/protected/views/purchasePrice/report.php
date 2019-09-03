<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php if (checkOperation("打印")) {?>
	<a target="_blank" href="<?php echo Yii::app()->createUrl('print/quotedPrint');?>">
		<div class="con_tit_daoru">
			<img src="<?php echo imgUrl('print_icon.png');?>">打印
		</div>
	</a>
<?php }?>
</div>

<style>
a{cursor:pointer}
.div_table table td, th{border:1px solid #989898;}
#isearch input{line-height:16px}
td{text-align:center}
</style>
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			
		</thead>
		<tbody>
			<tr>
				<td colspan='9' style="padding:0;height:50px;text-align:center;line-height:50px;font-size:28px"><b>上海瑞亮物资有限公司</b></td>
			</tr>
			<tr>
				<td colspan='9' style="padding:0;height:40px;text-align:center;line-height:40px;font-size:18px">当日报价单：<?php echo $this->getDate($time);?></td>
			</tr>
			<?php $i=0;foreach ($items as $item){	?>					
			<tr id="<?php echo $item->id?>" class="data">
				<td width="100"><?php echo $item->brand_name;?></td>				
				<td width="100"><?php echo $item->product_name;?></td>
				<td width="100"><?php echo $item->texture_name;?></td>
				<td width="100"><?php echo $item->rank_range;?></td>	 								
				<td width="100"><?php echo number_format($item->price,0);?></td>						
			</tr>
			<?php $i++;  }?>
			<tr>
				<td colspan='9' style="padding:0;height:auto;text-align:center;height:35px;line-height:35px"><b style="font-size:16px"><span style="color:red">新三洲\东亚\贵航\亚新\锦兴\富鑫\华兴\三元\正大</span>--国标品质,我公司为其上海一级经销商</b></td>
			</tr>
			<tr>
				<td colspan='9' style="padding:0;height:auto;text-align:center;height:40px;line-height:40px"><b style="font-size:20px">因市场价格变动较大，敬请来电确认</b></td>
			</tr>
			<tr>
				<td colspan='9' style="padding:0;height:auto;text-align:center;height:35px;line-height:35px"><b style="font-size:18px">公司总部：上海市杨浦区政益路28号405-406室（五角丰达）</b></td>
			</tr>
			<tr>
				<td colspan='9' style="padding:0;height:auto;text-align:center;height:35px;line-height:35px"><b style="font-size:18px">联系电话：55392500 65887906(直线） 55389923（传真）</b></td>
			</tr>
			<tr>
				<td colspan='9' style="padding:0;height:auto;text-align:center;height:35px;line-height:35px"><b style="font-size:18px">郑先生：18917065211/13022171222  QQ号:397552820</b></td>
			</tr>
		</tbody>
		
	</table>
	
</div>			

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  
  <script type="text/javascript">	
	$(function(){
		var array_product=<?php echo $products;?>;
		var array_texture=<?php echo $textures;?>;
		var array_rank=<?php echo $ranks;?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo_product').combobox(array_product, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_p","comboval_product",false);
		$('#combo_texture').combobox(array_texture, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_texture");
		$('#combo_rank').combobox(array_rank, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_r","comboval_rank",false);
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
	})
</script>