<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/payment.js"></script>
<form method="post" action="" url="">
<div class="search_body">
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
	<div class="select_body">
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='combval' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="shop_more_one1 short_shop_more_one">
	<div style="float:left;">客户：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $search['custome_name'];?>" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">类别：</div>
		 <select name="search[type]" class='form-control chosen-select forreset form_status'>
	         <option value='0' selected='selected'>-全部-</option>	             
           	 <option <?php echo $search['type']=="SKDJ"?'selected="selected"':''?>  value="SKDJ">收款</option>
           	 <option <?php echo $search['type']=="FKDJ"?'selected="selected"':''?>  value="FKDJ">付款</option>
           	 <option <?php echo $search['type']=="GKFK"?'selected="selected"':''?>  value="GKFK">高开付款</option>
           	 <option <?php echo $search['type']=="YHHZ"?'selected="selected"':''?>  value="YHHZ">银行互转</option>
           	 <option <?php echo $search['type']=="DQJK"?'selected="selected"':''?>  value="DQJK">短期借贷</option>
           	 <option <?php echo $search['type']=="FYBZ"?'selected="selected"':''?>  value="FYBZ">费用报支</option>
	     </select>
	</div>
	<div class="more_select_box" style="top:90px;">
	<div class="srarch_box" style="margin-left:10px;">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="more_one">
		<div class="more_one_l">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset is_yidan'>
	            <option value='-1' selected='selected'>-全部-</option>
	            <option <?php echo $search['is_yidan']=="1"?'selected="selected"':''?>  value="1">乙单</option>	             
           		<option <?php echo $search['is_yidan']=="0"?'selected="selected"':''?>  value="0">甲单</option>
	     </select>
	</div>
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:0px;">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="con_tit" style="background-color:#fff;">
	<div class="con_tit_cz">
		<button type="button" class="btn btn-primary btn-sm btn_submit" data-dismiss="modal">生成凭证</button>
		<button type="button" class="btn btn-primary btn-sm unset" data-dismiss="modal">重置</button>
		<a href="<?php echo Yii::app()->createUrl('voucher/index')?>" style="text-decoration: none;">
			<button type="button" class="btn btn-primary btn-sm goback" data-dismiss="modal">返回</button>
		</a>
	</div>
	<div class="voucher_num">本次共选中<span id="voucher_list_num">0</span>条数据</div>
</div>
<script>
	$(function(){
		var array=<?php echo $vendors?$vendors:"[]";?>;
		var title=<?php echo $title?$title:"[]";?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(title, {},"ywyselect","combval");
	})
	</script>
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
<?php paginate($pages, "payment_list")?>
<script>
	$(function(){
		checkStatus();
	})
</script>