<html>
<head>
<style>
body{min-height:300px;}
.submit_form{
	cursor:pointer;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/css/zui.min.css"	rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css"	rel="stylesheet" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery_ui.css" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-timepicker-addon.css" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/lib/datetimepicker/datetimepicker.min.css"	rel="stylesheet" />
<link rel="stylesheet"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.combobox.css" />
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.0.min.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/index.js"></script>
<!-- ZUI Javascript组件 -->
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
</head>
<body style="min-height:500px;">
<form method="post" action="<?php echo Yii::app()->createUrl('frmPurchaseReturn/returnlist') ?>">
<div class="search_body" style="min-width:996px;">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入销售单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
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
	<div class="select_body" >
	<div class="shop_more_one1">
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='combval' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="more_select_box" style="left:180px;top:49px;width:750px;">
	<div class="more_one">
		<div class="more_one_l">供应商：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $search['custome_name'];?>" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">业务组：</div>
		 <select name="search[team]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	            <?php foreach ($teams as $k=>$v){?>
           		<option <?php echo $k==$search['team']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l">仓库：</div>
		 <select name="search[warehouse]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($warehouse as $k=>$v){?>
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
			'totalData' =>$totalData
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:230,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages, "frmpurchasereturn_list")?>
<div class="btn_list" style="padding:5px 20px 0;">
	<button type="button" style="float:right;" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	<button type="submit" style="float:right;" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">确定</button>	
</div>
<script>
	$(function(){
		$("#submit_btn").click(function(){
			var check = $("input[type='radio']:checked");
			var selected_contract= check.val();
			var itsnum=check.parent().next().text();
			parent.$('#frm_sales_id').val(selected_contract);
			parent.$('#frmsales_no').val(itsnum);
			$.get("getReturnData",{"id":selected_contract},function(data){
				var data=eval('('+data+')');
				parent.$('#title_id').val(data.title_name);
				parent.$('#customer_id').val(data.company_name);
				parent.$('#warehouse_name').val(data.warehouse);
				parent.$('#warehouse').val(data.warehouse_id);
				parent.getOutdetail(selected_contract);
			})
			parent.$.colorbox.close();
		})
		$("#cancel").click(function(){
			parent.$.colorbox.close();
		});
		var array=<?php echo $vendor;?>;
		var coms=<?php echo $com;?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(coms, {},"ywyselect","combval");
		//点击整行
		$(document).on("click","table tbody tr",function(){
			var data_index = $(this).attr("data-index");
			$(".fixed-left tbody tr").eq(data_index).find(".selected_contract").attr("checked","checked");
		})
	})
	</script>
	</body>
</html>