<div class="con_tit">
	<div class="con_tit_jrbjd" onclick="window.open('<?php  echo Yii::app()->createUrl('quotedDetail/report',array('page'=>$_REQUEST['page']))?>');">
		<img src="<?php echo imgUrl('today_bjd.png');?>">今日报价单
	</div>
	<div class="" style="float:right;line-height:40px;font-size:14px;margin-right:20px;">
	最后报价:<span style="color:#035eed"><?php echo $last_update_user,'&nbsp;&nbsp;',$update_time?date('Y-m-d H:i:s',$update_time):'';?></span> 
	</div>
</div>

<style>
a{cursor:pointer}
.price{width:100%;text-align:right}
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
				<select  name="QuotedDetail[prefecture]" class='forreset'>
					<option value=''>全部</option>
					<?php if(!empty($prefectures)){ foreach($prefectures as $key=>$value){?>
					<option value="<?php echo $key?>" <?php echo  $search->prefecture==$key?'selected="selected"':''?>><?php  echo $value?></option>
					<?php }}?>
				</select>
				<!-- <input type="text" name="QuotedDetail[prefecture]" class="forreset" value="<?php echo $search->prefecture?>" /> -->
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
	<?php if($type=='guidance'){?>
	 <button  class="btn btn-primary btn-sm  save" style="width:120px;" title="保存并推送到微信" data-dismiss="modal" id="push_all">保存并推送到微信</button>
	<!--  <button  class="btn btn-primary btn-sm  save" style="width:140px;" data-dismiss="modal" id="push_selected">推送选中部分到微信</button>-->
	<?php  }?>
	<button  class="btn btn-primary btn-sm  save" data-dismiss="modal" title="保存" id="save">保存</button>		
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
			that.parent().parent().find('.price').each(function(){
				$(this).addClass('red_b');
			})					
		});
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
			can_submit=false;
			notAnymoreLoad('save');			
			var ret=getData('save');
			if(!ret)return;			
			var save_res=postSave(ret);
			if(save_res=="updated"){
				$('.loading').remove();
				confirmDialogRefresh("数据非最新，请刷新后操作！");
			}else	if(save_res==1){
				$('.loading').remove();
				confirmDialogRefresh("保存成功");
			}else{
				can_submit=true;		
				$('.loading').remove();		
				confirmDialog('保存失败');
				recoverSaveButton('save');					
			}
		});

		function postSave(ret)
		{
			var type='<?php echo $_REQUEST['type']?>';
			var da;
			$.ajaxSetup({async:false});
			$.post("/index.php/quotedDetail/post_update",{
				'id':ret[0],
				'data':ret[1],
				'type':type,
				'time':<?php echo time();?>
			},function(data){
				console.log(data);
				da=data;				
			});
			return da;
		}		
		
		function getData(str)
		{
			var id="0";
			var data="0";
			var empty;			
			var type='<?php echo $_REQUEST['type']?>';
			if(type=='spread')
			{
				$(".spread").each(function(){
					id += ","+$(this).attr('name');
					var first=$(this).val();
					data += ","+first;
					if(first!=''&&!/^-?([0-9])+(.[0-9]{1,2})?$/.test(first)){
						empty=$(this);
						return;
					}
				});
			}else{
				$("#datatable1 tbody tr").each(function(){
					var ea_id=$(this).find('.price:first').attr('name');
					id += ","+ea_id;					
					data+=","
					var that=$(this);
					$(this).find('input[type=text]').each(function(){
						var area_id=$(this).attr('area_id');
						var val=$(this).val();
						if(val!=''&&!/^[0-9]+(.[0-9]{1,2})?$/.test(val))
						{
							empty=$(this);
							return;
						}
						data+=ea_id+"/"+area_id+"/"+val+",";
					})
					if(empty)return;
				});
			}		
			if(empty){
				$('.loading').remove();
				can_submit=true;
				confirmDialog("价格不符合要求！");
				$(empty).addClass('red-border');
				recoverSaveButton(str);					
				return;
			}
			return [id,data];
		}

		
		function pushToWei()
		{			
			//保存
			if(!can_submit)return false;
			can_submit=false;
			notAnymoreLoad('push_all');				
			var ret=getData('push_all');
			if(!ret)return;			
			var save_res=postSave(ret);			
			if(save_res=="updated"){
				$('.loading').remove();
				confirmDialogRefresh("数据非最新，请刷新后操作！");
				return;
			}else	if(save_res==1){
// 				$('.loading').remove();
// 				confirmDialogRefresh("保存成功");
			}else{
				$('.loading').remove();
				can_submit=true;
				confirmDialog('保存失败');		
				recoverSaveButton('push_all');					
				return;		
			}
			
			//推送
			var ids=',';			
			if(push_range=='select'){
				$('.checkbox').each(function(){
					var check=$(this).attr('checked');
					if(check)
					{
						var thisid=$(this).val();
						ids=ids+thisid+',';
					}					
				});
			}				
			$.post('/index.php/quotedDetail/pushToWeichat',{
				'ids':ids,
				'type':push_range,
			},function(data){
				if(data==1){
					$('.loading').remove();
					confirmDialogRefresh('保存推送成功');
				}else{
					can_submit=true;
					$('.loading').remove();
					confirmDialog(data);
					recoverSaveButton('push_all');				
				}
			});
		}

		$('#push_all').click(function(){
			push_range='all';
			confirmDialog3('确定推送吗?',pushToWei);
		})

		$('#push_selected').click(function(){
			push_range='select';
			confirmDialog3('确定推送吗?',pushToWei);
		})		
		$('.select_all').click(function(){
			var select=$(this).attr('checked');
			$('.checkbox').each(function(){
				var check=$(this).attr('checked');
				var thisid=$(this).val();
				if(select)
				{
					if(!check){
						select_ids=select_ids+thisid+',';
						$(this).attr('checked','checked');	
					}
				}else{
					if(check){
						select_ids=select_ids.replace(','+thisid+',',',');
						$(this).removeAttr('checked');
					}
				}				
			});
			// console.log(select_ids);
		});
		$('.checkbox').click(function(){
			var thisid=$(this).val();
			var check=$(this).attr('checked');
			if(check)
			{
				select_ids=select_ids+thisid+',';
			}else{
				select_ids=select_ids.replace(','+thisid+',',',');
			}
			
		});
		
	})
</script>