<html>
<head>
<style>.submit_form{cursor:pointer;}</style>
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/css/zui.min.css"	rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css"	rel="stylesheet" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery_ui.css" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-timepicker-addon.css" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/lib/datetimepicker/datetimepicker.min.css"	rel="stylesheet" />
<link rel="stylesheet"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.combobox.css" />
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.0.min.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/index.js"></script>
<!-- ZUI Javascript组件 -->
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
</head>
<body style="min-height: 500px;">
<form method="post" action="">
<div class="search_body search_background" style="min-width: 700px;">
		<div class="more_one" style="width:200px;">
		<div class="more_one_l" style="width:50px;">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand"  class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand_id']);?>" />
			<input type='hidden' id='combovalbrand' value="<?php echo $search['brand_id'];?>"  class="forreset" name="search[brand_id]"/>
		</div>		
	</div>	
	<div class="more_one" style="width:200px;">
		<div class="more_one_l" style="width:50px;">品名：</div>
		 <select name="search[product_id]" class='form-control chosen-select forreset product_id'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product_id']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one" style="width:200px;">
		<div class="more_one_l" style="width:50px;">规格：</div>
		 <select name="search[rank_id]" class='form-control chosen-select forreset rank_id'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rank_id']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one" style="width:200px;">
		<div class="more_one_l" style="width:50px;">材质：</div>
		 <select name="search[texture_id]" class='form-control chosen-select forreset texture_id'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture_id']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub search_btn1" data-dismiss="modal"  value="查询">
<!-- 	<div class="more_toggle" title="更多"></div> -->
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php
$this->widget('DataTableWdiget', array (
		'id' => 'datatable1',
		'tableHeader' =>$tableHeader,
		'tableData' =>$tableData
));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:70,
    	 fixedRightWidth:0,
      });
   });
  </script>  	
</div>
<?php paginate($pages, "d_goods")?>
<script>
  $(function(){	
		var ids=parent.new_ids;			
		$('.clickme').each(function(){
			var thisval= $(this).val();
			if(ids.indexOf(','+thisval+',')>=0)
			{
				$(this).attr('checked','checked');
			}
		})	  
})
  </script>
	<div class="btn_list"   style="padding:8px 16px 0;">
		<button type="button" style="float:right;" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
		<button type="submit" style="float:right;" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">添加</button>	
		</div>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
		<script type="text/javascript">
		var array_brand=<?php echo $brands;?>;
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);		
		var prefecture=<?php echo $prefecture;?>;

	//选择
	$(document).on('click','.clickme',function(){
		var check=$(this).attr('checked');
		var selected_sales= $(this).val();
		if(check)
		{
			parent.new_ids=parent.new_ids+selected_sales+',';
		}else{
			parent.new_ids=parent.new_ids.replace(','+selected_sales+',',',');
		}				
	});
	$(document).on('click',' .datatable-rows .flexarea .datatable-wrapper table tr',function(){
		var a=$(this).index();
		var input=$(' .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
		var selected_sales= $(input).val();
		var check=$(input).attr('checked');
		if(check)
		{
			$(input).removeAttr('checked');
			parent.new_ids=parent.new_ids.replace(','+selected_sales+',',',');
		}else{
			$(input).attr('checked','checked');		
			parent.new_ids=parent.new_ids+selected_sales+',';
		}	
	});
		 //确定
	   $('#submit_btn').click(function(){
		   if(parent.new_ids==''||parent.new_ids==',')
			{
			   parent.$.colorbox.close();
				return;
			}
			$.post('/index.php/prefecture/saveChange',{
				'new_ids':parent.new_ids,
				'prefecture':prefecture,
			},function(data){
				if(data=='1')
				{
					parent.refresh=true;
					parent.$.colorbox.close();
				}else{
					confirmDialog(data);
				}
			});		
		});
		//取消，关闭
		$('#cancel').click(function(){			
			parent.$.colorbox.close();
		});
</script>	

</body>
</html>