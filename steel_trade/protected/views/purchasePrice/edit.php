<div class="con_tit">
<!--  	<div class="con_tit_jrbjd" onclick="window.open('<?php  echo Yii::app()->createUrl('purchasePrice/report',array('page'=>$_REQUEST['page']))?>');">
		<img src="<?php echo imgUrl('today_bjd.png');?>">今日报价单
	</div>
-->
	<div class="" style="float:right;line-height:40px;font-size:14px;margin-right:20px;">
	最后报价:<span style="color:#035eed"><?php echo $last_update_user,'&nbsp;&nbsp;',$update_time?date('Y-m-d H:i:s',$update_time):'';?></span> 
	</div>
</div>
<style>
a{cursor:pointer}
.price{width:100%;text-align:right}
#isearch input{line-height:16px}
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
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
	<div class="shop_more_one_l" style="width: 90px;">日　　期：</div>			
			<input type="text"  class="form-control form-date forreset" class="time" placeholder="选择日期"  value="<?php echo $search->time?>" name="PurchasePrice[time]" />
			
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">产　　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="PurchasePrice[brand_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="PurchasePrice[product_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">材　　质：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
			<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="PurchasePrice[texture_id]"/>
		</div>
	</div>
	<div class="more_select_box" style="width:300px;left:800px;">
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;" >规　　格：</div>
		<div id="comselect_r" class="fa_droplist">
			<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
			<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="PurchasePrice[rank_id]"/>
		</div>
	</div>
	</div>	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php $this->endWidget ();?>					
<div id="div_table1"  class="div_table">
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>0
	));
?>
 <script type="text/javascript">
  $(function(){
//      $('#datatable1').datatable({
//     	 fixedLeftWidth:0,
//     	 fixedRightWidth:0,
//       });
   });
  </script>
	<div class="btn_list " style="width:99%;margin-bottom:20px">
	<button  class="btn btn-primary btn-sm  save" data-dismiss="modal" id="save">保存</button>		
	</div>
</div>			
<?php //paginate($pages,"qd")?>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>  
  <script type="text/javascript">	
  $(document).on("blur","input",function(){
		$(this).removeClass("red-border");
	});
  	var select_ids=',';
  	var push_range;
	$(function(){
		var array_product=<?php echo $products;?>;
		var array_texture=<?php echo $textures;?>;
		var array_rank=<?php echo $ranks;?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo_product').combobox(array_product, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_p","comboval_product",false);
		$('#combo_texture').combobox(array_texture, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_texture");
		$('#combo_rank').combobox(array_rank, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_r","comboval_rank",false);
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);

		var can_submit=true;
		$("#save").click(function(){
			if(!can_submit)return false;
			notAnymoreLoad('save');			
			var id="0";
			var data="0";
			var empty;
			var dd=0;	
			var price_time='<?php echo $search->time?>';
			$(".price").each(function(){
				id += ","+$(this).attr('name');
				var first=$(this).val();
				data += ","+first;
				if(first!=''&&!/^[0-9]+(.[0-9]{1,2})?$/.test(first)){
					empty=$(this);
					return;
				}
			});		
			if(empty){
				confirmDialog("价格不符合要求！");
				$(empty).addClass('red-border');
				return;
			}
// 			console.log(id);
// 			console.log(data);
// 			return;
			$.post("/index.php/purchasePrice/post_update",{
				'id':id,
				'data':data,				
				'time':<?php echo time();?>,
				'price_time':price_time
			},function(data){
				console.log(data);
				if(data=="updated"){
					confirmDialogRefresh("数据非最新，请刷新后操作！");
				}else	if(data==1){
					$('.loading').remove();
					confirmDialogRefresh("保存成功");
				}else{
					confirmDialog('保存失败');					
				}
			});
		});		
	})
</script>