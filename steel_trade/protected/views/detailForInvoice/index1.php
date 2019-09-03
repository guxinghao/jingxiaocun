<style>
a{cursor:pointer}
th{text-align:center;vertical-align:middle}
#isearch input{line-height:16px}
.qmye{background:#f4f4f4;cursor:pointer;}
.qcye{background:#f4f4f4;}
.color_gray{background:#f4f4f4}
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
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" id="start_time" value="<?php echo $search['start_time'];?>" name="search[start_time]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" id="end_time" value="<?php echo $search['end_time'];?>" name="search[end_time]"  >
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">公司抬头：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search['title']);?>" />
			<input type='hidden' id='combovaltt' value="<?php echo $search['title'];?>"  class="forreset" name="search[title]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">结算单位：</div>
		<div id="tgselect" class="fa_droplist">
			<input type="text" id="combotg" class="forreset" value="<?php echo DictCompany::getLongName($search['company']);?>" />
			<input type='hidden' id='combovaltg' value="<?php echo $search['company'];?>"  class="forreset" name="search[company]"/>
		</div>
	</div>
	<div class="more_one" style="width: 150px;">
		<div class="more_one_l">未销数量：</div>
		<select name="search[uncheck]" class="form-control" style="width: 80px;">
			<option value="0" selected="selected">--全部--</option>
			<option value="2" <?php echo $search['uncheck']==2?'selected="selected"':''?>>非0</option>
			<option value="1"  <?php echo $search['uncheck']==1?'selected="selected"':''?>>等于0</option>
		</select>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<!--	<div class="more_toggle" title="更多"></div>-->
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
    	 fixedLeftWidth:220,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>	

<script>
	$(function(){
		<?php if($msg){echo "confirmDialog('{$msg}');";}?>

		$(".del_user").click(function(){
			if (!window.confirm("确定要删除吗")) {
				return false;
			} else {
				$.post("<?php echo Yii::app()->createUrl('dictTitle/delete')?>", {
					'del_id' : $(this).attr('name')
				}, function(data) {
					window.location.reload();
				});
			}
		});
		
	});
</script>

<script type="text/javascript">

	$(function(){
		var array_tt=<?php echo $titles;?>;
		var array_tg=<?php echo $targets;?>;
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
		$('#combotg').combobox(array_tg, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"tgselect","combovaltg",false);
	});
</script>