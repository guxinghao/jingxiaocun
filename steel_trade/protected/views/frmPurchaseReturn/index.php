<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<style>
.shop_more_one1{width:200px;}
</style>
<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
	<?php 
		if(checkOperation("采购退货单:新增")){
	?>
	<a href="<?php echo Yii::app()->createUrl('frmPurchaseReturn/create')?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建退货</button>
	</a>
	<?php }?>
	</div>
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
	<div class="select_body">
	<div class="shop_more_one1">
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='combval' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="shop_more_one1">
	<div style="float:left;">供应商：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $search['custome_name'];?>" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>
	<div class="more_select_box" >
	<div class="more_one">
		<div class="more_one_l">审核状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['form_status']=="unsubmit"?'selected="selected"':''?>  value="unsubmit">未提交</option>
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已提交</option>
           		 <option <?php echo $search['form_status']=="approve"?'selected="selected"':''?>  value="approve">已审核</option>
           		 <option <?php echo $search['form_status']=="complete"?'selected="selected"':''?>  value="complete">已审单</option>
           		 <option <?php echo $search['form_status']=="delete"?'selected="selected"':''?>  value="delete">已作废</option>            	
	        </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l">产地：</div>
	     <div id="brandselect" class="fa_droplist">
			<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
			<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
		</div>
	</div>	
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?> value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rand]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
		<div class="more_one">
			<div class="more_one_l">长度：</div>
				<select name="search[length]" class='form-control chosen-select forreset form_status'  id="mlength">
		            <option value='-1' selected='selected'>-全部-</option>	             
	           		 <option <?php echo $search['length']==0 && isset($search['length'])?'selected="selected"':''?>  value="0">0</option>
	           		 <option <?php echo $search['length']==9?'selected="selected"':''?>  value="9">9</option>
	           		 <option <?php echo $search['length']==12?'selected="selected"':''?>  value="12">12</option>
		      </select>
		</div>
	<div class="more_one" >
		<div class="more_one_l">仓库：</div>
		 <select name="search[warehouse]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($warehouses as $k=>$v){?>
            	 <option <?php echo $k==$search['warehouse']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
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
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:150,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["money"],2);?></span></div>
	<div class="total_data_one">核定件数：<span><?php echo $totalData["c_amount"];?></span></div>
	<div class="total_data_one">核定重量：<span class="color_org"><?php echo number_format($totalData["c_weight"],3);?></span></div>
	<div class="total_data_one">核定金额：<span><?php echo number_format($totalData["c_money"],2);?></span></div>
</div>
<?php paginate($pages, "frmpurchasereturn_list")?>
	<script type="text/javascript">
	$(function(){
		 $('.submit_form').click(function(e){
				var title=$(this).attr("title");
				var href = $(this).attr('url');
				var num = $(this).parent().parent().find(".form_sn").val();
				var text = '确认要'+title+'退货单'+num+'吗';
				if(href != ''){
					confirmDialog2(text,href);
				}
			});
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
	<script>
	$(function(){
		var brand = <?php echo $brands?$brands:"[]"?>;
		var array=<?php echo $vendors?$vendors:"[]";?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(coms, {},"ywyselect","combval");
		$('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})
	</script>
