<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<form method="post" action="">
<div class="search_body search_background">
<div class="more_one" style="width:50px;margin-right:-25px;">
		<button  class="btn btn-primary btn-sm colorbox"  url="/index.php/quotedDetail/simpleList?prefecture=<?php echo $_REQUEST['id']?>&type=edit"  id="add_new">新增</button>
</div>
		<div class="more_one">
		<div class="more_one_l">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand"  class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
			<input type='hidden' id='combovalbrand' value="<?php echo $search['brand'];?>"  class="forreset" name="search[brand]"/>
		</div>		
	</div>	
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset product_id'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rand]" class='form-control chosen-select forreset rank_id'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset texture_id'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<input type="button" class="btn btn-primary btn-sm btn_sub search_btn" data-dismiss="modal" value="查询">
<!-- 	<div class="more_toggle" title="更多"></div> -->
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div style="clear: both;" id="sales_list">
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<script type="text/javascript">
var array_brand=<?php echo $brands;?>;
$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);
var prefecture=<?php echo $_REQUEST['id']?>;
var old_ids=<?php echo $old_ids;?>;
var deleted_ids=',';
var new_ids=',';
var refresh=false;
$(function(){
	$('.reset').click(function(){
	    $('.forreset').val('');		    
	});
	$.get('/index.php/quotedDetail/simpleList',{
		'prefecture':prefecture,
		},function(data){
			$('#sales_list').html(data);
		});
});
$('.search_btn').click(function(){
	var product_id=$('.product_id').val();
	var brand_id=$('#combovalbrand').val();
	var rank_id=$('.rank_id').val();
	var texture_id=$('.texture_id').val();
	var choosed=$('.choosed').val();
	var url="/index.php/quotedDetail/simpleList?&page=1";
	$.get(url,{
		'choosed':choosed,
		'product_id':product_id,
		'brand_id':brand_id,
		'rank_id':rank_id,
		'texture_id':texture_id,
		'prefecture':prefecture,
	},function(data){
		$('#sales_list').html(data);
	});
});

//换页获取数据
$(document).on('click','.sauger_page_a',function(e){
	e.preventDefault();
	var product_id=$('.product_id').val();
	var brand_id=$('#combovalbrand').val();
	var rank_id=$('.rank_id').val();
	var texture_id=$('.texture_id').val();
	var choosed=$('.choosed').val();
	var url=$(this).attr('href');
	$.get(url,{
		'choosed':choosed,
		'product_id':product_id,
		'brand_id':brand_id,
		'rank_id':rank_id,
		'texture_id':texture_id,
		'prefecture':prefecture,
	},function(data){
		$('#sales_list').html(data);
	});
});
  $(document).on('change','#each_page',function(){
	  	limit=$(this).val();
	  	$.post("/index.php/site/writeCookie", {
	  		'name' : "d_goods",
	  		'limit':limit
	  	}, function(data) {
	  		if(data){
	  			var product_id=$('.product_id').val();
	  			var brand_id=$('#combovalbrand').val();
	  			var rank_id=$('.rank_id').val();
	  			var texture_id=$('.texture_id').val();
	  			var url=$('.firstpage').attr('href');
	  			var choosed=$('.choosed').val();
	  			$.get(url,{
	  				'choosed':choosed,
	  				'product_id':product_id,
	  				'brand_id':brand_id,
	  				'rank_id':rank_id,
	  				'texture_id':texture_id,
	  				'prefecture':prefecture,
	  			},function(data){
	  				$('#sales_list').html(data);
	  			});
	  		}
	  	});			
	  });
  $(document).on('change','.paginate_sel',function(){
	    var url = $(this).val();
	    var product_id=$('.product_id').val();
		var brand_id=$('#combovalbrand').val();
		var rank_id=$('.rank_id').val();
		var choosed=$('.choosed').val();
		var texture_id=$('.texture_id').val();
		$.get(url,{
			'choosed':choosed,
			'product_id':product_id,
			'brand_id':brand_id,
			'rank_id':rank_id,
			'texture_id':texture_id,
			'prefecture':prefecture,
		},function(data){
			$('#sales_list').html(data);
		});
	});	
// //选择
// $(document).on('click','.clickme',function(){
// 	var check=$(this).attr('checked');
// 	var selected_sales= $(this).val();
// 	if(check)
// 	{
// 		new_ids=new_ids+selected_sales+',';
// 		deleted_ids=deleted_ids.replace(','+selected_sales+',',',');
// 	}else{
// 		deleted_ids=deleted_ids+selected_sales+',';
// 		new_ids=new_ids.replace(','+selected_sales+',',',');
// 	}	
		
// });
// $(document).on('click',' .datatable-rows .flexarea .datatable-wrapper table tr',function(){
// 	var a=$(this).index();
// 	var input=$(' .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
// 	var selected_sales= $(input).val();
// 	var check=$(input).attr('checked');
// 	if(check)
// 	{
// 		$(input).removeAttr('checked');
// 		deleted_ids=deleted_ids+selected_sales+',';
// 		new_ids=new_ids.replace(','+selected_sales+',',',');
// 	}else{
// 		$(input).attr('checked','checked');		
// 		new_ids=new_ids+selected_sales+',';
// 		deleted_ids=deleted_ids.replace(','+selected_sales+',',',');
// 	}	
// });

// $(document).on('click','.save',function(){
// 	$.post('/index.php/prefecture/saveChange',{
// 		'deleted_ids':deleted_ids,
// 		'new_ids':new_ids,
// 		'prefecture':prefecture,
// 	},function(data){
// 		if(data==1)
// 		{
// 			confirmDialog("保存成功",function(){window.location.reload();	});
// 		}else{
// 			confirmDialog(data);
// 		}
// 	});
// })


///////////////////////////////////
	$(document).on('click','.del_but',function(){		
		var item = this;
		confirmDialogWithCallBack("确定要删除吗",function(){
			var url="<?php echo Yii::app()->createUrl('prefecture/saveChange')?>";		
			var del_id=$(item).attr('name');
			$.post(url, {
				'deleted_ids' : del_id,
				'prefecture':prefecture
			}, function(data) {
				if(data){
					confirmDialogRefresh('删除成功');				
				}else{
					confirmDialog('删除失败');
				}				
			});
		},function(){return false;});
	});
		$('.colorbox').click(function(e){
			e.preventDefault();
			var url = $(this).attr('url');
			$.colorbox({
				href:url,
				opacity:0.6,
				iframe:true,
				title:"",
				width: "1020px",
				height: "635px",
				overlayClose: false,
				speed: 0,
				onClosed:function(){if(refresh){window.location.reload()};},
			});
		});




</script>
