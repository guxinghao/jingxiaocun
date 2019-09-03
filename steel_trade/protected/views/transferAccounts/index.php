<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru  btn_export" url="<?php echo Yii::app()->createUrl('transferAccounts/tranExport',array("type"=>"FYBZ")); ?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php if (checkOperation("银行互转:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl('transferAccounts/create');?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">银行互转</button>
		</a>
	<?php }?>
	</div>
</div>

<form method="post" action="" url="">
	<div class="search_body">
		<div class="srarch_box">
			<img src="<?php echo imgUrl('search.png');?>" />
			<input id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]" placeholder="请输入单号" />
		</div>
		
		<div class="search_date">
			<div style="float: left;">日期：</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
			</div>
			<div style="float: left; margin: 0 3px;">至</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">负责人：</div>
			 <select name="search[owned]" class='form-control chosen-select forreset owned'>
			        <option value='0' selected='selected'>-全部-</option>
			             <?php foreach ($users as $k=>$v){?>
		            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
		            <?php }?>
			 </select>
		</div>
		<div class="more_one" style="display:none;">
			<div class="more_one_l">转出公司：</div>
			<div id="title_output_select" class="fa_droplist">
				<input type="text" id="title_output_combo" class="forreset" value="<?php echo $search['title_output_name'];?>" name="search[title_output_name]" />
				<input type="hidden" id="title_output_val" class="forreset" value="<?php echo $search['title_output_id'];?>" name="search[title_output_id]"/>
			</div>
		</div>
		
		<div class="more_one" style="display:none;">
			<div class="more_one_l">转入公司：</div>
			<div id="title_input_select" class="fa_droplist">
				<input type="text" id="title_input_combo" class="forreset" value="<?php echo $search['title_input_name'];?>" name="search[title_input_name]" />
				<input type="hidden" id="title_input_val" class="forreset" value="<?php echo $search['title_input_id'];?>" name="search[title_input_id]"/>
			</div>
		</div>
		
		<div class="more_select_box" style="left:460px;width:535px;">
			<div class="more_one">
				<div class="more_one_l">状态：</div>
				<select name="search[form_status]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
					<option value="unsubmit"<?php echo $search['form_status'] == "unsubmit" ? ' selected="selected"' : ''?>>未提交</option>
					<option value="submited"<?php echo $search['form_status'] == "submited" ? ' selected="selected"' : ''?>>已提交</option>
					<option value="approve"<?php echo $search['form_status'] == "approve" ? ' selected="selected"' : ''?>>已审核</option>
					<option value="accounted"<?php echo $search['form_status'] == "accounted" ? ' selected="selected"' : ''?>>已入账</option>
					<option value="delete"<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?>>已作废</option>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l">类型：</div>
				<select name="search[type]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
				<?php foreach (TransferAccounts::$type as $k => $v) {?>
					<option value="<?php echo $k;?>"<?php echo $search['type'] == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
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
		fixedLeftWidth: 240,
		fixedRightWidth:0,
	});
});
</script>
</div>

<div class="total_data">
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["price"], 2);?></span></div>
	<div class="total_data_one" style="display: none;">单数：<span class="color_org"><?php echo number_format($totalData["total_num"]);?></span></div>
</div>
<?php paginate($pages, "transfer_accounts");?>
<div class="pop_background" style="display: none;"></div>
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
<!--
var title_output_array = <?php echo $title_output_array ? $title_output_array : '[]';?>;
var title_input_array = <?php echo $title_input_array ? $title_input_array : '[]';?>;

$(function() {
	var list_id, lastdate = 0;

	$('.submit_form').click(function(e){
		var title = $(this).attr("title");
		var href = $(this).attr('url');
		var num = $(this).parent().parent().find(".form_sn").val();
		var text = '确认要'+title+'<?php echo $this->pageTitle;?>'+num+'吗？';
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
		$.post('/index.php/transferAccounts/deleteform', 
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
	
	$(".reset").click(function() {
		$(".forreset").val("");
	});

	$("#title_output_combo").combobox(title_output_array, {}, "title_output_select", "title_output_val", false);
	$("#title_input_combo").combobox(title_input_array, {}, "title_input_select", "title_input_val", false);
});
//-->
</script>