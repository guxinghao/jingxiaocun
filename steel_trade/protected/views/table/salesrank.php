<style>
.cz_list_btn_more{height:70px;}
.sales_export,thead .order_but{cursor: pointer;}
.order_img{margin-left:10px;margin-top:-3px;display: none; }
</style>
<div class="con_tit">
<?php if (false&&checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl(''); ?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
</div>
<form method="post" action="" url="">
<div class="search_body">
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="<?php echo $search['time_L']=="1970-01-01"?"":$search['time_L'];?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="<?php echo $search['time_H']?$search['time_H']:date('Y-m-d',time());?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='combval2' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="shop_more_one1">
		<div style="float:left;">结算单位：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $search['custome_name'];?>" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">分组统计：</div>
		<select name="search[group]" class='form-control chosen-select forreset '>
            <?php foreach (FrmSales::$groupby as $k=>$v){?>
        	<option <?php echo $k==$search['group']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
        </select>
	</div>
	<div class="more_select_box" >
	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="wareselect1" class="fa_droplist">
			<input type="text" id="combo1" value="<?php echo $search['client_name'];?>" class="forreset" name="search[client_name]"/>
			<input type='hidden' id='comboval1' class="forreset" value="<?php echo $search['client_id'];?>" name="search[client_id]" />
		</div>
	</div>
	
	<div class="more_one">
		<div class="more_one_l">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset is_yidan'>
	            <option value='-1' selected='selected'>-全部-</option>
	            <option <?php echo $search['is_yidan']=="1"?'selected="selected"':''?>  value="1">乙单</option>	             
           		<option <?php echo $search['is_yidan']=="0"?'selected="selected"':''?>  value="0">甲单</option>
	     </select>
	</div>
<!-- 	<div class="more_one">
		<div class="more_one_l">业务组：</div>
		 <select name="search[team]" class='form-control chosen-select forreset team'>
	            <option value='0' selected='selected'>-全部-</option>
	            <?php //foreach ($teams as $k=>$v){?>
           		<option <?php //echo $k==$search['team']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php //}?>
	        </select>
	</div> -->
	<div class="more_one">
		<div class="more_one_l">销售员：</div>
		 <select name="search[owned_by]" class='form-control chosen-select forreset owned'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned_by']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>

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
			'totalData' =>$totalData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:225,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<!-- <div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["price"],2);?></span></div>
</div> -->
<?php paginate($pages, "salerank_list")?>
<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
</script>
	<script type="text/javascript">
	$(function(){
		var order='<?php echo $_COOKIE["salesrank_order"]?>';
		var value=order.substr(0,3);
		// console.log(value);
		var img=order.substr(4);
		// console.log(img);
		$('.order_img').each(function(){
			if($(this).attr('value')==value)
			{
				$(this).show();
				if(img=='sha')
				{
					$(this).attr('src','/images/shang.png');
				}else{
					$(this).attr('src','/images/xia.png');
				}
			}else{
				$(this).hide();
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
		$("thead .order_but").click(function(){
			var order=$(this).find('.order_img').attr('value');
			if(order=='yue')
			{
				var can_order=$(this).find('.order_img').attr('can_order');
				if(can_order=='no')return false;
			}
			var src=$(this).find('.order_img').attr('src').substr(8,3);
			if(src=='sha')
			{src='xia';}else{src='sha';}
			document.cookie="salesrank_order="+order+'_'+src;
			window.location.reload();
		});
		$("thead .order_but").mouseover(function(){
			$(this).css('color','blue');
		});
		$("thead .order_but").mouseout(function(){
			$(this).css('color','black');
		});
	})
	</script>
	<script>
	$(function(){
		var titles = <?php echo $titles?$titles:'[]'?>;
		var targets=<?php echo $targets?$targets:"[]";?>;
		// var coms=<?php echo $coms?$coms:"[]";?>;
		$('#combo').combobox(targets,{},"wareselect","comboval","","",200);
		$('#combo1').combobox(targets,{},"wareselect1","comboval1","","",200);
		$('#combo2').combobox(titles, {},"ywyselect","combval2");
		// $('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})

	</script>