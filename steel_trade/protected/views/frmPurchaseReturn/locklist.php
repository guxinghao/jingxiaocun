<style>
.shop_more_one1{width:200px;}
</style>
<div class="con_tit">
	<div class="con_tit_daoru">
		<a href="<?php echo Yii::app()->createUrl('mergeStorage/locklist',array("page"=>$_GET['fpage']));?>">
		<img src="<?php echo imgUrl('back_url.png');?>">返回</a>
	</div>
</div>
<div class="con_tit" style="border-top:1px solid #fff;background:#fff;border-bottom:1px solid #ccc;margin-top:4px;">
	<a href="<?php echo Yii::app()->createUrl('FrmSales/locklist',array('storage_id'=>$storage_id,"fpage"=>$_GET['fpage']));?>">
		<div class="con_tit_one" style="border-bottom:1px solid #ccc;margin-left:12px;color:#666">销售单</div>
	</a>
		<div class="con_tit_one " style="border:1px solid #ccc ;border-bottom:none">退货单</div>
</div>
<form method="post" action="">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入退货单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="开始日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="结束日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<?php if($tableData){ ?>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'totalData' =>$totalData
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:180,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php 
		paginate($pages, "frmpurchasereturn_list");
	}else{
?>
	<div class="no_more">退货单没有锁定此库存，请查看销售单！</div>
<?php 		
	}
?>

	<script type="text/javascript">
	$(function(){
		//搜索条件重置按钮
		$(".reset").click(function(){
			$(".forreset").val('');
			$("#combo").val('');
			$("#comboval").val('');
			$("#combo2").val('');
			$("#combval").val('');
		});
	})
	</script>
