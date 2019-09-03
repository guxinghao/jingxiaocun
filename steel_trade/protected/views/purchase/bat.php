<style>
a{cursor:pointer}
th{text-align:center;vertical-align:middle}
#isearch input{line-height:16px}
.qmye{background:#f4f4f4;cursor:pointer;}
.qcye{background:#f4f4f4;}
</style>

<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('purchase/BATExport');?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
</div>

<?php 
$form = $this->beginWidget('CActiveForm', array(
	'htmlOptions' => array(
		'id' => "user_search_form", 
		'enctype' => "multipart/form-data", 
		'url' => "",
	),
));
?>
<div class="search_body">
	<div class="more_one">
			<div class="more_one_l">采购公司：</div>
			<div id="ttselect" class="fa_droplist">
				<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search['company']);?>" />
				<input type='hidden' id='combovaltt' value="<?php  echo $search['company'];?>"  class="forreset" name="search[company]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">托盘公司：</div>
			<div id="tgselect" class="fa_droplist">
				<input type="text" id="combotg" class="forreset" value="<?php  echo DictCompany::getName($search['pledge']);?>" />
				<input type='hidden' id='combovaltg' value="<?php echo $search['pledge'];?>"  class="forreset" name="search[pledge]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">供应商：</div>
			<div id="venselect" class="fa_droplist">
				<input type="text" id="comboven" class="forreset" value="<?php  echo DictCompany::getName($search['vendor']);?>" />
				<input type='hidden' id='combovalven' value="<?php echo $search['vendor'];?>"  class="forreset" name="search[vendor]"/>
			</div>
		</div>
<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<!-- <div class="more_toggle" title="更多"></div> -->
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
    	 fixedLeftWidth:500,
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
<?php paginate($pages, "contract_simlist")?>
<script type="text/javascript">
	$(function(){
		var array_tt=<?php echo $coms;?>;
		var array_tg=<?php echo $pledges?$pledges:json_encode(array());?>;
		var array_ven=<?php echo $vendors?$vendors:json_encode(array());?>;
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
		$('#combotg').combobox(array_tg, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"tgselect","combovaltg",false);
		$('#comboven').combobox(array_ven, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","combovalven",false);
	});
</script>