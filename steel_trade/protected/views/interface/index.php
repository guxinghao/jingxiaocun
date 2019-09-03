<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
</div>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<form method="post" action="/index.php/interface/failList">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="推送单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
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
	<div class="shop_more_one" style="margin-top:8px;width:255px;">
		<div class="shop_more_one_l" style="width: 70px;">类型：</div>
		 <select name="search[type]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['type']=="inputformplan"?'selected="selected"':''?>  value="inputformplan">入库计划</option>
           		<option <?php echo $search['type']=="customer_company"?'selected="selected"':''?>  value="customer_company">结算单位</option>
           		 <option <?php echo $search['type']=="jxc_title"?'selected="selected"':''?>  value="jxc_title">公司</option>
           		 <option <?php echo $search['type']=="deliveryform"?'selected="selected"':''?>  value="deliveryform">配送单</option>
           		  <option <?php echo $search['type']=="jxc_warehouse"?'selected="selected"':''?>  value="jxc_warehouse">仓库</option>
           		 
           <!--  -		  <option <?php echo $search['type']=="inputform"?'selected="selected"':''?>  value="inputform">入库单</option>
           		 <option <?php echo $search['type']=="outputform"?'selected="selected"':''?>  value="outputform">出库单</option>
           		 <option <?php echo $search['type']=="textureform"?'selected="selected"':''?>  value="textureform">材质推送</option>
           		 <option <?php echo $search['type']=="goodsnameform"?'selected="selected"':''?>  value="goodsnameform">品名推送</option>
           		 <option <?php echo $search['type']=="rankform"?'selected="selected"':''?>  value="rankform">规格推送</option>
           		 <option <?php echo $search['type']=="goodscompanyform"?'selected="selected"':''?>  value="goodscompanyform">产地推送</option>
           		 <option <?php echo $search['type']=="goodsform"?'selected="selected"':''?>  value="goodsform">件重推送</option>
           		 <option <?php echo $search['type']=="usersform"?'selected="selected"':''?>  value="usersform">用户类型</option> -->           		 
	        </select>
	</div>	

	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<!-- 	<div class="more_toggle" title="更多"></div> -->
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
    	 fixedLeftWidth:280,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "push_list")?>
<script>
	    $('.reset').click(function(){
		    $('.forreset').val('');		    
		    });
	    $(function(){
		    $('.submit_form').click(function(e){
		    	 	var title=$(this).attr("str");
					var href = $(this).attr('url');
					var num = $(this).parent().parent().find(".form_sn").val();
					var text =title;
				    confirmDialog2(text,href);			  
			});
		  });
	</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/pub_index.js"></script>
