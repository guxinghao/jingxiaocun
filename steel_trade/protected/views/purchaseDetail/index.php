<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入采购单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">
	
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">采购公司：</div>
		<div id="comselect" style="float:left; display:inline;position: relative;">
			<input type="text" id="combo2" class="forreset"  value="<?php echo DictTitle::getName($search['company'])?>" />
			<input type='hidden' id='comboval2' value="<?php echo $search['company'];?>"  class="forreset" name="search[company]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">供应商：</div>
		<div id="wareselect" style="float:left; display:inline;width:145px;position: relative;">
			<input type="text" style="width:145px;" id="combo" class="forreset"  value="<?php echo DictCompany::getName($search['vendor']);?>" />
			<input type='hidden' id='comboval'  class="forreset"  value="<?php echo $search['vendor'];?>"  name="search[vendor]"/>
		</div>
	</div>
	
	<div class="more_select_box" style="top:80px;">

	<div class="more_one">
		<div class="more_one_l">单据状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>             		 
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已提交</option>
           		 <option <?php echo $search['form_status']=="approve"?'selected="selected"':''?>  value="approve">已审核</option>
	        </select>
	</div>

	<div class="more_one">
		<div class="more_one_l">审单状态：</div>
		 <select name="search[confirm_status]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['confirm_status']=='1'?'selected="selected"':''?>  value="1">已审单</option>
            	<option <?php echo $search['confirm_status']=='0'?'selected="selected"':''?>  value="0">未审单</option>
	      </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l" >单据类型：</div>
		 <select name="search[purchase_type]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['purchase_type']=='normal'?'selected="selected"':''?>  value="normal">库存采购</option>
            	<option <?php echo $search['purchase_type']=='tpcg'?'selected="selected"':''?>  value="tpcg">托盘采购</option>
            	<option <?php echo $search['purchase_type']=='xxhj'?'selected="selected"':''?>  value="xxhj">直销采购</option>
            	<option <?php echo $search['purchase_type']=='dxcg'?'selected="selected"':''?>  value="dxcg">代销采购</option>
	      </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['is_yidan']=='1'?'selected="selected"':''?>  value="1">乙单</option>
            	<option <?php echo $search['is_yidan']=='0'?'selected="selected"':''?>  value="0">甲单</option>
	      </select>
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
<?php $this->endWidget ();?>					
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:210,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "purchase_list")?>
<script>
    $(function(){
		var brand = <?php echo $brands?>;
		var array=<?php echo $vendor?$vendor:json_encode(array());?>;
		var coms=<?php echo $coms;?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(coms, {},"comselect","comboval2");
		$('#combobrand').combobox(brand,{},"brandselect","combovalbrand");
	})
</script>