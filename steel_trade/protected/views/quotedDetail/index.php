<?php if($date_type=="yes"){?>
<div class="con_tit">
	<div class="con_tit_jrbjd" onclick="window.open('<?php  echo Yii::app()->createUrl('quotedDetail/report',array('page'=>$_REQUEST['page']))?>');">
		<img src="<?php echo imgUrl('today_bjd.png');?>">今日报价单
	</div>
	<div class="" style="float:right;line-height:40px;font-size:14px;margin-right:20px;">
	最后报价:<span style="color:#035eed"><?php echo $last_update_user,'&nbsp;&nbsp;',date('Y-m-d H:i:s',$update_time);?></span> 
	</div>
</div>
<?php }?>
<style>
a{cursor:pointer}
#isearch input{line-height:16px}
.order_img{margin-left:10px;margin-top:-3px;display: none; }
</style>
<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<?php if($date_type=="yes"){?>
<div class="search_body">
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">产　　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="QuotedDetail[brand_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="QuotedDetail[product_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">材　　质：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
			<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="QuotedDetail[texture_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;" >规　　格：</div>
		<div id="comselect_r" class="fa_droplist">
			<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
			<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="QuotedDetail[rank_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="width:0">
		<div class="more_select_box" style="top:128px;left:640px;width:500px">
			<div class="more_one">
				<div class="more_one_l">专　　区：</div>
				<select  name="QuotedDetail[prefecture]" class="forreset">
					<option value=''>全部</option>
					<?php if(!empty($prefectures)){ foreach($prefectures as $key=>$value){?>
					<option value="<?php echo $key?>" <?php echo  $search->prefecture==$key?'selected="selected"':''?>><?php  echo $value?></option>
					<?php }}?>
				</select>
				<!-- <input type="text" name="QuotedDetail[prefecture]" class="forreset" value="<?php echo $search->prefecture?>" /> -->
			</div>
			<div class="more_one">
				<div class="more_one_l">长　　度：</div>
				<select  name="QuotedDetail[length]" class="forreset">
					<option value=''>全部</option>					
					<option value="9" <?php echo  $search->length==9?'selected="selected"':''?>>9</option>
					<option value="12" <?php echo  $search->length==12?'selected="selected"':''?>>12</option>
					<option value="-1" <?php echo  $search->length==-1?'selected="selected"':''?>>其他</option>
				</select>
			</div>
		</div>
	</div>

<!-- 	<div class="shop_more_one" style="width:0">
		<div class="more_select_box" style="top:128px;left:640px;width:500px">
			<div class="more_one">
				<div class="more_one_l">专　　区：</div>
				<input type="text" name="QuotedDetail[prefecture]" class="forreset" value="<?php echo $search->prefecture?>"/>
			</div>
		</div>
	</div> -->
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php }else{?>
<div class="search_body">
	<div class="search_date" style="width:390px">
		<div style="float:left">报价日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $_POST['start_time']?>" name="start_time">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $_POST['end_time']?$_POST['end_time']:date('Y-m-d')?>" name="end_time"  >
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">产　　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="QuotedDetail[brand_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="QuotedDetail[product_id]"/>
		</div>
	</div>
	<div class="more_select_box" style="top:90px;left:580px;width:500px">
		<div class="more_one">
			<div class="more_one_l"  >材　　质：</div>
			<div id="comselect_t" class="fa_droplist">
				<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
				<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="QuotedDetail[texture_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">规　　格：</div>
			<div id="comselect_r" class="fa_droplist">
				<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
				<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="QuotedDetail[rank_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">长　　度：</div>
				<select  name="QuotedDetail[length]" class="forreset">
					<option value=''>全部</option>					
					<option value="9" <?php echo  $search->length==9?'selected="selected"':''?>>9</option>
					<option value="12" <?php echo  $search->length==12?'selected="selected"':''?>>12</option>
					<option value="-1" <?php echo  $search->length==-1?'selected="selected"':''?>>其他</option>
				</select>
			</div>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php }?>
<?php $this->endWidget ();?>					
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:0,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>	
<?php if($data_type!='yes')paginate($pages,"qd")?>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  
  <script type="text/javascript">	
	$(function(){
		//排序图标处理
		var order='<?php echo $_COOKIE["saleprice_order"]?>';
		var value=order.substr(0,3);
		var img=order.substr(4);
		$('.order_img').each(function(){
			if($(this).attr('value')==value)
			{
				$(this).show();
				if(img=='sha')
				{
					$(this).attr('src','/images/shang.png');
				}else{
					$(this).attr('src','/images/xia.png');
				}
			}else{
				$(this).hide();
			}
		});
		$("thead .order_but").click(function(){
			var order=$(this).find('.order_img').attr('value');			
			var src=$(this).find('.order_img').attr('src').substr(8,3);
			if(src=='sha'){src='xia';}else{src='sha';}
			document.cookie="saleprice_order="+order+'_'+src;
			window.location.reload();
		});
		$("thead .order_but").mouseover(function(){
			$(this).css('color','blue');
		});
		$("thead .order_but").mouseout(function(){
			$(this).css('color','black');
		});
		
		$('.prefecture_line').parent().parent().css('background','#dee9ff');
		$('.red').each(function(){
			var that=$(this);
			that.parent().parent().addClass('red_b');
// 			that.parent().parent().find('.price').each(function(){
// 				$(this).addClass('red_b');
// 			})					
		});
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