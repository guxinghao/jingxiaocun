<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<?php //echo $msg;?>
<div class="con_tit">
	<?php if($from != "menu"){?>
	<div class="con_tit_daoru">
		<a href="<?php echo $backUrl?>"><img src="<?php echo imgUrl('back_url.png');?>">返回</a>
	</div>
	<?php }?>
	<div class="con_tit_duanshu"></div>
	<?php if($id){?>
	<div class="con_tit_cz">
	<?php if(checkOperation("出库单:新增") && $sales->confirm_status == 0){?>
	<a href="<?php echo $createUrl;?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建出库单</button>
	</a>
	<?php }?>
	</div>
	<?php }else{?>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('frmOutput/create',array('from'=>$from));?>" style="text-decoration: none;">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建库存出库</button>
		</a>
	</div>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('frmOutput/xscreate',array('from'=>$from));?>" style="text-decoration: none;">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">先销后进出库</button>
		</a>
	</div>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('frmOutput/dxcreate',array('from'=>$from));?>" style="text-decoration: none;">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建代销出库</button>
		</a>
	</div>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('frmOutput/rtcreate',array('from'=>$from));?>" style="text-decoration: none;">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">采购退货出库</button>
		</a>
	</div>
	<?php }?>
</div>
<form method="post" action="">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入单号或卡号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
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
		<div style="float:left;">销售类型：</div>
		 <select name="search[sales_status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['sales_status']=="normal"?'selected="selected"':''?>  value="normal">库存销售</option>
           		 <option <?php echo $search['sales_status']=="xxhj"?'selected="selected"':''?>  value="xxhj">先销后进</option>
           		 <option <?php echo $search['sales_status']=="dxxs"?'selected="selected"':''?>  value="dxxs">代销销售</option>            	
	        </select>
	</div>
	<div class="shop_more_one1">
		 <div style="float:left;">出库状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['form_status']=="unsubmit"?'selected="selected"':''?>  value="unsubmit">未出库</option>
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已出库</option>
           		 <option <?php echo $search['form_status']=="delete"?'selected="selected"':''?>  value="delete">已作废</option>            	
	        </select>
	</div>
	<div class="more_select_box" style="left:260px;top:128px;">
	<div class="more_one">
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
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
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
			'id' => 'outputtable',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'totalData' =>"",
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#outputtable').datatable({
    	 fixedLeftWidth:240,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totaldata["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totaldata["weight"],3);?></span></div>
	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totaldata["total_num"];?></span></div>
</div>
<?php paginate($pages,"frmOutput_list")?>
<script type="text/javascript">
	$(function(){
		 $('.submit_form').click(function(e){
			var title=$(this).attr("title");
			var href = $(this).attr('url');
			var num = $(this).parent().parent().find(".form_sn").val();
			var text = '确认要'+title+'出库单'+num+'吗';
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
		var brand = <?php echo $brands?$brands:"[]";?>;
		$('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})
	</script>