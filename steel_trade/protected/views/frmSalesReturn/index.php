<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('frmSalesReturn/create',array('fpage'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建退货单</button>
		</a>
	</div>
</div>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<form method="post" action="">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入入库单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">

	<div class="shop_more_one" style="margin-top:8px;width:230px;">
		<div class="shop_more_one_l" style="width: 70px;">供应商：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" class="forreset" value="<?php echo DictCompany::getName($search['vendor']);?>" />
			<input type='hidden' id='comboval' value="<?php echo $search['vendor'];?>"  class="forreset" name="search[vendor]"/>
		</div>
	</div>	
	<div class="more_select_box" style="top:130px;">
	
	<div class="more_one">
		<div class="more_one_l">入库状态：</div>
		 <select name="search[input_status]" class='form-control chosen-select forreset'>
	            <option value='-1' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['input_status']=="1"?'selected="selected"':''?>  value="1">已入库</option>
           		 <option <?php echo $search['input_status']=="0"?'selected="selected"':''?>  value="0">未入库</option>
	        </select>
	</div>
	<?php if($type!="dxrk"){?>
	<div class="more_one">
		<div class="more_one_l">入库类型：</div>
		 <select name="search[input_type]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['input_type']=="purchase"?'selected="selected"':''?>  value="purchase">采购入库</option>
           		 <option <?php echo $search['input_type']=="thrk"?'selected="selected"':''?>  value="thrk">销售退货</option>
           		 <option <?php echo $search['input_type']=="ccrk"?'selected="selected"':''?>  value="ccrk">船舱入库</option>
	        </select>
	</div>
	<?php }?>
	<div class="more_one">
		<div class="more_one_l">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand" class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
			<input type='hidden' id='combovalbrand' value="<?php echo $search['brand'];?>"  class="forreset" name="search[brand]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rand]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
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
    	 fixedLeftWidth:160,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "input_list")?>

<script type="text/javascript">
<!--
var url_chPC = '<?php echo Yii::app()->createUrl('frmSalesReturn/index', array('search' => $search))?>';
var array = <?php echo $vendors ? $vendors : '[]';?>;
var array2 = <?php echo $coms ? $coms : '[]';?>;
var array_brand = <?php echo $brands ? $brands : '[]';?>;

$(".reset").click(function() {
	$(".forreset").val("");
});

$("select[name='page_records']").change(function() {
	var pageCount = $(this).val();
	window.location.href = url_chPC + '&pageCount' + pageCount;
});

$(function() {
	$(".submit_form").click(function(e) {
		if (!confirm("确认操作吗")) return false;
		e.preventDefault();
		var href = $(this).attr('url');
		$.get(href, function(data) {
			if (data == 1) window.location.reload();
			else if (data) confirmDialog(data);
			else confirmDialog("更新失败");
		});
	});
	
	$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval",false);
	$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
	$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);
});
//-->
</script>
