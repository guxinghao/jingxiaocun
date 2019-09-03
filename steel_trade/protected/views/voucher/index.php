<style>
.tishi{font-size:14px;line-height:30px;padding-left:1%;}
.num_click{cursor:pointer;}
</style>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('voucher/export'); ?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<a href="<?php echo Yii::app()->createUrl('voucher/createBusiness'); ?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">增加业务凭证</button>
	</a>
	<a href="<?php echo Yii::app()->createUrl('voucher/createPayment'); ?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">增加收付款凭证</button>
	</a>
	</div>
</div>
<?php if($no1 >0){?>
	<div class="tishi">二级科目共有<span class="num_click red" model=1><?php echo $no1;?></span>条没有编码</div>
<?php }
	if($no2 >0){?>
	<div class="tishi">核算项目共有<span class="num_click red" model=2><?php echo $no2;?></span>条没有编码</div>
<?php }?>
<script>
	$(".num_click").click(function(){
		var model = $(this).attr("model");
		$.post("/index.php/voucher/getNoList",{"model":model},function(data){
			$("body").append(data);
		})
	});
</script>
<form method="post" action="" url="">
	<div class="search_body">
		<div class="srarch_box">
			<img src="<?php echo imgUrl('search.png');?>">
			<input placeholder="请输入凭证字、摘要" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
		</div>
		<div class="search_date">
			<div style="float:left">日期：</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="<?php echo $search['time_L'];?>" name="search[time_L]">
			</div>
			<div style="float:left;margin:0 3px;">至</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="<?php echo $search['time_H'];?>" name="search[time_H]"  >
			</div>
		</div>
		
		<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:0px;">
		<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
	</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			//'totalData' =>$totalData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:100,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "voucher_list")?>
<script>
$(function(){
		$(".delete_form").on("click",function(){
			var href = $(this).attr('url');
			var text = '您确认要作废此条凭证吗';
			var is_export = $(this).attr('is_export');
			if(is_export == 1){
				confirmDialog("凭证已经导出，不能作废");
				return false;
			}
			confirmDialog2(text,href);
		});

		$('.submit_form').click(function(e){
			var title=$(this).attr("title");
			var href = $(this).attr('url');
			var text = '确认要'+title+'此条凭证吗';
			confirmDialog2(text,href);
		});
		
})
</script>