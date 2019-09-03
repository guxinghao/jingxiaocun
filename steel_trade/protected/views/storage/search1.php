<style>
a{cursor:pointer}
.color_gray{background:#f4f4f4}
#isearch input{line-height:16px}
.table th, .table td{padding:3px 8px;}
</style>

<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入卡号" class="forreset" value="<?php echo $search->card_no?>" name="Storage[card_no]" id="Storage_card_no">
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">销售公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search->title_id);?>" />
			<input type='hidden' id='combovaltt' value="<?php echo $search->title_id;?>"  class="forreset" name="Storage[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">仓　库：</div>
		<div id="comselect_ck" class="fa_droplist">
			<input type="text" id="combo_ck" class="forreset" value="<?php echo Warehouse::getName($search->warehouse_id);?>" />
			<input type='hidden' id='comboval_ck' value="<?php echo $search->warehouse_id;?>"  class="forreset" name="Storage[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">产　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="Storage[brand_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">规　格：</div>
		<div id="comselect_r" class="fa_droplist">
			<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
			<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="Storage[rank_id]"/>
		</div>
	</div>
	<div class="more_select_box" style="top:90px;left:280px">
	<div class="more_one">
		<div class="more_one_l">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="Storage[product_id]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">材　　质：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
			<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="Storage[texture_id]"/>
		</div>
	</div>
	<div class="more_one">
			<div class="more_one_l">销售公司：</div>
			<div id="ttselect" class="fa_droplist">
				<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search->title_id);?>" />
				<input type='hidden' id='combovaltt' value="<?php echo $search->title_id;?>"  class="forreset" name="Storage[title_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">卡号状态：</div>
			<select name="Storage[card_status]" class='form-control chosen-select forreset'>
       			<option <?php echo $search->card_status=="normal"?'selected="selected"':''?>  value="normal">正常</option>
	           	<option <?php echo $search->card_status=="clear"?'selected="selected"':''?>  value="clear">清卡</option>
	           	<option <?php echo $search->card_status=="deleted"?'selected="selected"':''?>  value="deleted">删除</option>            	
		    </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">入库类型：</div>
			<select name="Storage[input_type]" class='form-control chosen-select forreset'>
				<option value="">-全部-</option>
       			<option <?php echo $search->input_type=="purchase"?'selected="selected"':''?>  value="purchase">采购</option>
	           	<option <?php echo $search->input_type=="thrk"?'selected="selected"':''?>  value="thrk">销售退货</option>
	           	<option <?php echo $search->input_type=="ccrk"?'selected="selected"':''?>  value="ccrk">船舱入库</option> 
	           	<option <?php echo $search->input_type=="qt"?'selected="selected"':''?>  value="qt">其他</option>           	
		    </select>
		</div>
		
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none">
		<thead>
			<tr class="data">
				<th width="150" class='table_cell_first'>卡号</th>
				<th width="100" class="flex-col" >仓库</th>
				<th width="80" class="flex-col" >卡号状态</th>
				<th width="80" class="flex-col" >产地</th>
				<th width="70" class="flex-col" >品名</th>
				<th width="80" class="flex-col">材质</th>
				<th width="60" class="flex-col" >规格</th>
				<th width="60" class="flex-col rightAlign" >长度</th>
				<th width="70" class="flex-col rightAlign" >件重</th>
				<th width="80" class="flex-col rightAlign" >可用件数</th>
				<th width="80" class="flex-col rightAlign" >可用重量</th>
				<th width="100" class="flex-col" >入库时间</th>
				<th width="120" class="flex-col" >预计到货日期</th>
				<th width="100" class="flex-col" >入库类型</th>
				<th width="120" class="flex-col rightAlign" >采购发票成本</th>
				<th width="130" class="flex-col" >采购单价已确定</th>
				<th width="120" class="flex-col" >采购是否乙单</th>
				<th width="120" class="flex-col" >是否托盘库存</th>
				<th width="110" class="flex-col" >销售公司</th>
				<th width="110" class="flex-col" >托盘公司</th>
<!--				<th width="100" class="flex-col" >是否代销</th>-->
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
			?>
			<tr id="<?php echo $item->id?>" class="data  <?php if($i%2==1){?>color_gray<?php }?>">
				<td class='table_cell_first'><?php echo $item->card_no; ?></td>
				<td ><?php echo Warehouse::getName($item->warehouse_id); ?></td>	
				<td ><?php switch ($item->card_status){
					case "normal":echo "正常";break;
					case "clear":echo "清卡";break;	
					case "deleted":echo "删除";break;
					default:echo "未知";
				}
				?></td>
				<td ><?php echo DictGoodsProperty::getProName($item->brand_id); ?></td>
				<td ><?php echo DictGoodsProperty::getProName($item->product_id); ?></td>
				<td ><?php echo str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($item->texture_id)); ?></td>
				<td ><?php echo DictGoodsProperty::getProName($item->rank_id); ?></td>
				<td class="rightAlign"><?php echo $item->length; ?></td>
				<td class="rightAlign"><?php echo $item->weight;?></td>
				<td class="rightAlign"><?php echo $item->available_amount; ?></td>
				<td class="rightAlign"><?php echo number_format($item->available_weight,3); ?></td>
				<td ><?php echo $item->input_date?date("Y-m-d",$item->input_date):""; ?></td>
				<?php if($item->input_type=="ccrk"){?>
				<td style="color:red"><?php echo $item->pre_input_date?date("Y-m-d",$item->pre_input_date):""; ?></td>
				<?php }else{?>
				<td></td>
				<?php }?>
				<td ><?php switch($item->input_type){
							case "purchase":echo "采购";break;
							case "thrk":echo "销售退货";break;
							case "ccrk":echo "船舱入库";break;
							case "qt":echo "其他";
							default:echo "未知";}; ?></td>
				<td class="rightAlign"><?php echo number_format($item->invoice_price,0); ?></td>
				<td ><?php echo $item->is_price_confirmed==1?"是":""; ?></td>
				<td ><?php echo $item->is_yidan==1?"是":""; ?></td>	
				<td ><?php echo $item->is_pledge==1?"是":""; ?></td>				
				<td ><?php echo $item->title->short_name; ?></td>
				<td ><span title="<?php echo $item->redeemCompany->name;?>"><?php echo $item->redeemCompany->short_name;?></span></td>
<!--				<td ><?php #echo $item->is_dx==1?"是":""; ?></td>-->
			</tr>
			<?php $i++;  }?>
		</tbody>
	</table>
</div>	
<?php if($access){ ?>
<div class="total_data">
	<div class="total_data_one">剩余件数：<span><?php echo $totaldata["amount"];?></span></div>
	<div class="total_data_one">剩余重量：<span class="color_org"><?php echo number_format($totaldata["weight"],3);?></span></div>
	<div class="total_data_one" style="display:none;">金额：<span><?php echo number_format($totaldata["price"],2);?></span></div>
	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totaldata["total_num"];?></span></div>
</div>	
<?php }?>	
<?php paginate($pages,"kc")?>

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  
  <script type="text/javascript">	
	$(function(){
		$('#datatable1').datatable({
	    	 fixedLeftWidth:150,
	    	 fixedRightWidth:0,
	      });
		var array_product=<?php echo $products;?>;
		var array_texture=<?php echo $textures;?>;
		var array_rank=<?php echo $ranks;?>;
		var array_brand=<?php echo $brands;?>;
		var array_ck = <?php echo $warehouse;?>;
		var array_tt=<?php echo $titles;?>;
		$('#combo_product').combobox(array_product, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_p","comboval_product",false);
		$('#combo_texture').combobox(array_texture, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_texture");
		$('#combo_rank').combobox(array_rank, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_r","comboval_rank",false);
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
		$('#combo_ck').combobox(array_ck, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_ck","comboval_ck",false);
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
	})
</script>