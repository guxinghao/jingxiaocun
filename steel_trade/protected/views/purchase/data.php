<style>
a{cursor:pointer}
th{text-align:center;vertical-align:middle}
#isearch input{line-height:16px}
.qmye{background:#f4f4f4;cursor:pointer;}
.qcye{background:#f4f4f4;}
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
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" id="start_time" value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" id="end_time" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="more_one">
			<div class="more_one_l">公司：</div>
			<div id="ttselect" class="fa_droplist">
				<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search['company']);?>" />
				<input type='hidden' id='combovaltt' value="<?php  echo $search['company'];?>"  class="forreset" name="search[company]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">供应商：</div>
			<div id="tgselect" class="fa_droplist">
				<input type="text" id="combotg" class="forreset" value="<?php  echo DictCompany::getName($search['vendor']);?>" />
				<input type='hidden' id='combovaltg' value="<?php echo $search['vendor'];?>"  class="forreset" name="search[vendor]"/>
			</div>
		</div>
	<div class="select_body" style="position:relative">
	<div class="more_select_box" style="top:40px;left:-220px;width:500px;">
		<div class="more_one">
			<div class="more_one_l" >产地：</div>
			<div id="brandselect" class="fa_droplist">
				<input type="text" id="combo_brand" class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand'])?>"/>
				<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand']?>" name="search[brand]" />
			</div>
		</div>
		<div class="more_one">
		<div class="more_one_l">审单：</div>
		 <select name="search[confirm]" class="form-control chosen-select forreset" id="search_confirm">
	         <option value='2' selected='selected'>-全部-</option>
            <option <?php echo $search['confirm']=='1'?'selected="selected"':''?>  value="1">已审单</option>
            <option <?php echo $search['confirm']=='0'?'selected="selected"':''?>  value="0">未审单</option>
	      </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">甲乙单：</div>
		 <select name="search[yidan]" class="form-control chosen-select forreset" id="search_yidan">
	         <option value='2' selected='selected'>-全部-</option>
            <option <?php echo $search['yidan']=='1'?'selected="selected"':''?>  value="1">乙单</option>
            <option <?php echo $search['yidan']=='0'?'selected="selected"':''?>  value="0">甲单</option>
	      </select>
	</div>
	<?php if(checkOperation("采购汇总:查看全部")){?>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php if(!empty($users)){foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }}?>
	        </select>
	</div>	
	<?php }?>
	</div>
	</div>
	
<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<div class="more_toggle" title="更多"></div>
<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

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
    	 fixedLeftWidth:230,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<!-- <div class="total_data"> --
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["money"],2);?></span></div>
	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totalData["total_num"];?></span></div>
<!-- </div> -->
<?php paginate($pages, "purchase_simlist")?>

<script type="text/javascript">
	$(function(){
		var array_tt=<?php echo $coms;?>;
		var array_tg=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array_brand=<?php echo $brands;?>;
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
		$('#combotg').combobox(array_tg, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"tgselect","combovaltg",false);
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","comboval_brand",false);
	});
</script>