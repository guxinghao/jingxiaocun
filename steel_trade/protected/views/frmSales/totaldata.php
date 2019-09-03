<form method="post" action="">
<div class="search_body">
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
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
	<div class="shop_more_one1">
		<div style="float:left;">审单状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset form_status'>
	            <option value='0' selected='selected'>-未完成-</option>
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已提交</option>
           		 <option <?php echo $search['form_status']=="approve"?'selected="selected"':''?>  value="approve">已审核</option>
           		 <option <?php echo $search['form_status']=="complete"?'selected="selected"':''?>  value="complete">已完成</option>
           		 <!--  <option <?php echo $search['form_status']=="delete"?'selected="selected"':''?>  value="delete">已作废</option>  -->          	
	        </select>
	</div>
	<div class="more_select_box" style="top:90px;">
	<div class="more_one">
		<div class="more_one_l">销售类型：</div>
		 <select name="search[sales_type]" class='form-control chosen-select forreset sales_type'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['sales_type']=="normal"?'selected="selected"':''?>  value="normal">库存销售</option>
           		 <option <?php echo $search['sales_type']=="xxhj"?'selected="selected"':''?>  value="xxhj">先销后进</option>
           		 <option <?php echo $search['sales_type']=="dxxs"?'selected="selected"':''?>  value="dxxs">代销销售</option>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset yidan'>
	            <option value='-1' selected='selected'>-全部-</option>
	            <option <?php echo $search['is_yidan']=="1"?'selected="selected"':''?>  value="1">乙单</option>	             
           		<option <?php echo $search['is_yidan']=="0"?'selected="selected"':''?>  value="0">甲单</option>
	     </select>
	</div>
	<?php 
	if(checkOperation("销售汇总:查看全部")){
	?>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<?php }?>
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
    	 fixedLeftWidth:280,
    	 fixedRightWidth:0,
      });
     $(".salesview").on("click",function(){
		var id = $(this).attr("id");
		var cid = $(this).attr("cid");
		var start = $(".start_time").val();
		var end = $(".end_time").val();
		var form_status = $(".form_status").val();
		var sales_type = $(".sales_type").val();
		var yidan = $(".yidan").val();
		location.href = "<?php echo Yii::app()->request->hostInfo?>/index.php/frmSales?id="+id
			+"&cid="+cid+"&start="+start+"&end="+end+"&form_status="+form_status+"&sales_type="+sales_type+"&yidan="+yidan+"&view=index&total=1";		
     })
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totaldata["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totaldata["amount"],2);?></span></div>
</div>
<?php paginate($pages, "salestotal_list")?>
<script>
    $(function(){
		var brand = <?php echo $brands?$brands:"[]"?>;
		var array=<?php echo $vendor?$vendor:"[]";?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(coms, {},"ywyselect","combval");
		$('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})
</script>
	