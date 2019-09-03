<!-- <div class="con_tit">
<?php //if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php //echo Yii::app()->createUrl('mergeStorage/export');?>">
		<img src="<?php ///echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php //}?>
</div> -->

<form method="post" action="" url="">
<div class="search_body">
	<div class="select_body">
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">仓库：</div>
		<div id="warehouseselect" class="fa_droplist">
			<input type="text" id="combo3" value="<?php echo $search['warehouse_name'];?>" class="forreset" name="search[warehouse_name]"/>
			<input type='hidden' id='comboval3' value="<?php echo $search['warehouse_id'];?>" class="wareinput forreset" name="search[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
			<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
		</div>
	</div>
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">规格：</div>
		<select name="search[rank]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($ranks as $k=>$v){?>
            	 <option <?php echo $k==$search['rank']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	    </select>
	</div>
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">材质：</div>
			 <select name="search[texture]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_select_box" style="top:85px;">
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
			<div class="more_one_l">长度：</div>
			 <select name="search[length]" class='form-control chosen-select forreset form_status' >
		            <option value='-1' selected='selected'>-全部-</option>	             
	           		 <option <?php echo $search['length']==0 && isset($search['length'])?'selected="selected"':''?>  value="0">0</option>
	           		 <option <?php echo $search['length']==9?'selected="selected"':''?>  value="9">9</option>
	           		 <option <?php echo $search['length']==12?'selected="selected"':''?>  value="12">12</option>
		      </select>
		</div>
	<div class="more_one">
	<div class="more_one_l">公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">可售类型：</div>
	    <select name="search[type]"  class='form-control chosen-select forreset'>
		        <option value='0' selected='selected'>-全部-</option>
		        <option value='1' <?php if($search['type'] == 1) echo "selected";?>>在库</option>
		        <option value='2' <?php if($search['type'] == 2) echo "selected";?>>船舱</option>
		    </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">排序规则：</div>
	    <select name="search[order_by]"  class='form-control chosen-select forreset'>
		        <option value='1' selected='selected'>-按最后更新时间-</option>		        
		        <option value="0" <?php if($search['order_by'] == "0") echo "selected";?>>-不按最后更新时间-</option>
		    </select>
	</div>
<!-- 	<div class="more_one">
		<div class="more_one_l">是否锁定：</div>
		 <select name="search[lock]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
            	<option <?php //echo 1==$search['lock']?'selected="selected"':''?> value="1">是</option>
            	<option <?php //echo 2==$search['lock']?'selected="selected"':''?> value="2">否</option>
	     </select>
	</div> -->
<!-- 	<div class="more_one">
		<div class="more_one_l">是否保留：</div>
		 <select name="search[retain]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
            	<option <?php //echo 1==$search['retain']?'selected="selected"':''?> value="1">是</option>
            	<option <?php //echo 2==$search['retain']?'selected="selected"':''?> value="2">否</option>
	        </select>
	</div> -->
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:0px;">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php
$this->widget('DataTableWdiget', array(
		'id' => 'storagetable',
		'tableHeader' =>$tableHeader,
		'tableData' =>$tableData,
		// 'totalData' =>$totalData1,
		'hide'=>1
));
?>
</div>

<?php paginate($pages,"stoage_list");?>
<script>
$(function(){
	$('#storagetable').datatable({
   	 fixedLeftWidth:100,
   	 fixedRightWidth:0,
     });
	var brand = <?php echo $brands?>;
	var coms=<?php echo $coms;?>;
	var warehouse=<?php echo $warehouses;?>;
	$('#combo2').combobox(coms, {},"ttselect","comboval");
	$('#combo_brand').combobox(brand, {},"brandselect","comboval_brand");
	$('#combo3').combobox(warehouse, {},"warehouseselect","comboval3");
})
</script>