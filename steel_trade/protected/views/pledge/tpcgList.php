<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('pledge/create',array('fpage'=>$_REQUEST['page']))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm create_btn" data-dismiss="modal">托盘赎回</button>
		</a>
	</div>
</div>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<form method="post" action="/index.php/pledge/pledgeSearch?page=1">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入采购单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset " placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
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
		<div class="shop_more_one_l" style="width: 70px;">托盘公司：</div>
		<div id="wareselect" style="float:left; display:inline;width:145px;position: relative;">
			<input type="text" style="width:145px;" id="combo" class="forreset"  value="<?php echo DictCompany::getName($search['vendor']);?>" />
			<input type='hidden' id='comboval'  class="forreset"  value="<?php echo $search['vendor'];?>"  name="search[vendor]"/>
		</div>
	</div>
	
	<div class="more_select_box" style="top:130px;left:700px;width:500px;">

	<div class="more_one">
		<div class="more_one_l">单据状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['form_status']=="unsubmit"?'selected="selected"':''?>  value="unsubmit">未提交</option>
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已提交</option>
           		 <option <?php echo $search['form_status']=="approve"?'selected="selected"':''?>  value="approve">已审核</option>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
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
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:240,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["money"],2);?></span></div>
	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totalData["total_num"];?></span></div>
</div>
<?php paginate($pages, "bill_list")?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<script>
	    $('.reset').click(function(){
		    $('.forreset').val('');		    
		    });
	    $(function(){
			$('.create_btn').click(function(e){
				var result=checkAuthority('托盘赎回:新增');
				if(result=='no')
				{
// 					alert('您没有权限执行此操作');
					e.preventDefault();
				}						
			});	
		  });
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script type="text/javascript">	
	$(function(){
		var array=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array2=<?php echo $coms;?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval",false);
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);

		$('.colorbox').click(function(e){
			e.preventDefault();
			var url = $(this).attr('url');
			$.colorbox({
				href:url,
				opacity:0.6,
				iframe:true,
				title:"",
				width: "700px",
				height: "250px",
				overlayClose: false,
				speed: 0,
			});
		});
		
	})	
</script>
