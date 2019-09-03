<?php 
/**
 * 库存流水报表视图
 * @category I_Don't_Know.
 * @package  I_Don't_Know.
 * @author   yy_prince   <gengzicong@xun-ao.com>
 * @license  http://www.xun-ao.com YY_prince_license
 * @link     http://www.xun-ao.com
 */
?>

<style>
a{cursor:pointer}
.color_gray{background:#f4f4f4}
#isearch input{line-height:16px}
.datatable{border-right:0px}
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
    <div class="shop_more_one"  style="margin-top:8px;width:210px;">
		<div class="shop_more_one_l" style="width: 60px;">卡　号：</div>
		<div id="" class="fa_droplist">
			<input type="text" placeholder="请输入卡号" name="StorageTurnoverView[card_no]" class="form-control forreset" value="<?php echo $search->card_no;?>" />
		</div>
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" id="start_time" value="<?php echo $search->st?>" name="StorageTurnoverView[st]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" id="end_time" value="<?php echo $search->et?>" name="StorageTurnoverView[et]"  >
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">仓　库：</div>
		<div id="comselect_ck" class="fa_droplist">
			<input type="text" id="combo_ck" class="forreset" value="<?php echo Warehouse::getName($search->warehouse_id);?>" />
			<input type='hidden' id='comboval_ck' value="<?php echo $search->warehouse_id;?>"  class="forreset" name="StorageTurnoverView[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 80px;">销售公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combo_tt" class="forreset" value="<?php echo DictTitle::getName($search->title_id);?>" />
			<input type='hidden' id='comboval_tt' value="<?php echo $search->title_id;?>"  class="forreset" name="StorageTurnoverView[title_id]"/>
		</div>
	</div>
	
	<div class="more_select_box" style="top:90px;left:280px">
		<div class="more_one">
			<div class="more_one_l">产　　地：</div>
			<div id="comselect_b" class="fa_droplist">
				<input type="text" id="combo_brand" class="forreset" value="<?php echo DictGoodsProperty::getProName($search->brand_id);?>" />
				<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="StorageTurnoverView[brand_id]"/>
			</div>
		</div>
		<div class="more_one" >
			<div class="more_one_l">品　　名：</div>
			<div id="comselect_p" class="fa_droplist">
    			<input type="text" id="combo_product" class="forreset" value="<?php echo DictGoodsProperty::getProName($search->product_id);?>" />
    			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="StorageTurnoverView[product_id]"/>
    		</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">材　　质：</div>
			<div id="comselect_t" class="fa_droplist">
				<input type="text" id="combo_texture" class="forreset" value="<?php echo DictGoodsProperty::getProName($search->texture_id);?>" />
				<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="StorageTurnoverView[texture_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">规　　格：</div>
			<div id="comselect_r" class="fa_droplist">
				<input type="text" id="combo_rank" class="forreset" value="<?php echo DictGoodsProperty::getProName($search->rank_id);?>" />
				<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="StorageTurnoverView[rank_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">是否船舱：</div>
			<select name="is_ccrk" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>否</option>	             
       			<option <?php echo $_REQUEST['is_ccrk']=="1"?'selected="selected"':''?>  value="1">是</option>            	
		    </select>
		</div>
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="">
		<thead>
			<tr class="data">
				<th width="100" class='table_cell_first'>仓库</th>
				<th width="80" class='flex-col'>类型</th>
				<th width="110" class="flex-col" >公司抬头</th>
<!-- 				<th width="180" class="flex-col " >托盘公司</th> -->
				<th width="150" class="flex-col " >卡号</th>
				<th width="70" class="flex-col" >产地</th>
				<th width="70" class="flex-col" >品名</th>
				<th width="80" class="flex-col">材质</th>
				<th width="60" class="flex-col" >规格</th>
				<th width="50" class="flex-col rightAlign" >长度</th>
				<th width="50" class="flex-col rightAlign" >件数</th>
				<th width="80" class="flex-col rightAlign" >重量</th>
				<th width="100" class="flex-col" >时间</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
			?>
			<tr id="<?php echo $item->detail_id?>" class="data  <?php if($i%2==1){?>color_gray<?php }?>">
				<td class='table_cell_first'><?php echo $item->warehouse_name; ?></td>
				<td><?php switch ($item->type){
				    case "in":echo "入库";break;
				    case "out":echo "出库";break;
				    case "pypk":echo "盘盈盘亏";break;
				    case "zk":echo "转库";break;
				    default:;
				}?></td>
				<td ><?php echo $item->title_name; ?></td>
<!-- 				<td ><?php #echo $item->company_name;?></td> -->
				<td><?php echo $item->card_no; ?></td>
				<td ><?php echo $item->brand_name; ?></td>
				<td ><?php echo $item->product_name; ?></td>
				<td ><?php echo str_replace('E','<span class="red">E</span>',$item->texture_name); ?></td>
				<td ><?php echo $item->rank_name; ?></td>
				<td class="rightAlign"><?php echo $item->length; ?></td>
				<td class="rightAlign"><?php echo $item->amount; ?></td>
				<td class="rightAlign"><?php echo number_format($item->weight,3); ?></td>
				<td ><?php echo $item->created_at?date("Y-m-d",$item->created_at):""; ?></td>
			</tr>
			<?php $i++;  }?>
		</tbody>
	</table>
</div>	
	
<?php paginate($pages,"kcls")?>

  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  <script type="text/javascript">	
	$(function(){
// 		$('#datatable1').datatable({
// 	    	 fixedLeftWidth:100,
// 	    	 fixedRightWidth:0,
// 	      });
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
		$('#combo_tt').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","comboval_tt",false);
	})
</script>