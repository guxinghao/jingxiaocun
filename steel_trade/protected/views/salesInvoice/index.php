<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('salesInvoice/export');?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php if (checkOperation("销售开票:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl("salesInvoice/create")?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">销售开票</button>
		</a>
	<?php }?>
	</div>
</div>

<form method="post" action="" url="">
	<div class="search_body">
		<div class="srarch_box">
			<img src="<?php echo imgUrl('search.png');?>">
			<input id="srarch" class="forreset" value="<?php echo $search['keywords']?>" placeholder="请输入单号" name="search[keywords]">
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
		<div class="select_body">
			<div class="shop_more_one1" style="width:215px;">
				<div style="float:left;">收票单位：</div>
				<div id="company_select" class="fa_droplist">
						<input type="text" id="company_combo" class="forreset" value="<?php echo $search['company_name'];?>" name="search[company_name]" />
						<input type="hidden" id="company_val" class="forreset" value="<?php echo $search['company_id'];?>" name="search[company_id]" />
				</div>
			</div>
			<div class="shop_more_one1" style="width:215px;">
				<div style="float:left;">开票单位：</div>
				<div id="title_select" class="fa_droplist">
					<input type="text" id="title_combo" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
					<input type="hidden" id="title_val" class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
				</div>
			</div>
		</div>
		<div class="more_select_box">
			<div class="more_one">
				<div class="more_one_l">客户：</div>
				<div id="company_select1" class="fa_droplist">
					<input type="text" id="company_combo1" class="forreset" value="<?php echo $search['client_name'];?>" name="search[client_name]" />
					<input type="hidden" id="company_val1" class="forreset" value="<?php echo $search['client_id'];?>" name="search[client_id]" />
				</div>
			</div>
			<div class="more_one">
				<div class="more_one_l">状态：</div>
				<select name="search[form_status]" class='form-control chosen-select forreset'>
					<option value="" selected='selected'>-全部-</option>
					<option<?php echo $search['form_status'] == "submited" ? ' selected="selected"' : ''?> value="submited">未开票</option>
					<option<?php echo $search['form_status'] == "invoice" ? ' selected="selected"' : ''?> value="invoice">已开票</option>
					<option<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?> value="delete">已作废</option>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l">业务员：</div>
				<select class='form-control chosen-select forreset' name="search[owned_by]">
					<option value="" selected='selected'>-全部-</option>
					<?php foreach ($user_array as $key => $value) {?>
					<option value="<?php echo $key;?>"<?php echo $search['owned_by'] == $key ? ' selected="selected"' : '';?>><?php echo $value;?></option>
					<?php }?>
				</select>
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
			'tableHeader' => $tableHeader, 
			'tableData' => $tableData, 
			'hide' => 1
	));
?>
<script type="text/javascript">
$(function(){
	$('#datatable1').datatable({
		fixedLeftWidth: 210,
		fixedRightWidth:0,
	});
});
</script>
</div>
<div class="total_data">
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"], 3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["price"], 2);?></span></div>
	<div class="total_data_one" style="display: none;">单数：<span class="color_org"><?php echo number_format($totalData["total_num"]);?></span></div>
</div>
<?php paginate($pages, "sales_invoice_list");?>
<div class="pop_background" style="display:none;"></div>
<div class="check_background" id="deleted" style="display:none;">
	<div class="deleted_div">
		<div class="pop_title">请输入作废理由
			<span class="pop_cancle"><i class="icon icon-times"></i></span>
		</div>
		<textarea class="pop_textarea"></textarea>
		<div class="pop_footer">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="submit">提交</button>
		</div>
	</div>
</div>

<script type="text/javascript">
//select数组 
var company_array = <?php echo $company_array ? $company_array : '[]';?>; //客户 
var title_array = <?php echo $title_array ? $title_array : '[]';?>; //公司抬头 

$(function() {
	var list_id, lastdate = 0;

	$('.submit_form').click(function(e){
		var title = $(this).attr("title");
		var href = $(this).attr('url');
		var num = $(this).parent().parent().find(".form_sn").val();
		var text = '确认要'+title+'<?php echo $this->pageTitle;?>'+num+'吗？';
//		var text = "确认" + title + "吗？";
        confirmDialog2(text, href);
    });

	//取消
	$(".pop_cancle").click(function() {
		$(".pop_background").hide();
		$("#deleted").hide();
		$("#check").hide();
	});

	//作废
	$(".delete_form").click(function() {
		list_id = $(this).attr("id");
		lastdate = $(this).attr("lastdate");
		$(".pop_background").show();
		$("#deleted").show();
	});
	//作废提交
	$("#submit").click(function() {
		var str = $(".pop_textarea").val();
		if (str == '') { confirmDialog("请输入作废原因"); return false; }
		$.post('/index.php/salesInvoice/deleteform', 
		{
			'id' : list_id,
			'last_update' : lastdate,
			'str' : str
		}, 
		function(e) {
			if (e == 'success') window.location.reload();
			else if (e) confirmDialog(e);
			else confirmDialog("作废失败");
		});
	});

	$("#company_combo").combobox(company_array, {}, "company_select", "company_val", false,'', 200);
	$("#company_combo1").combobox(company_array, {}, "company_select1", "company_val1", false,'', 200);
	$("#title_combo").combobox(title_array, {}, "title_select", "title_val", false);
});
</script>
