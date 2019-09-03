<style>
.status_btn{ color: blue;}
</style>

<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
	<?php if ($type == 'sale' && checkOperation("销售折让:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl("rebate/create", array('type' => "sale"));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">销售折让</button>
		</a>
	<?php }?>
	<?php if ($type == 'shipment' && checkOperation("采购折让:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl('rebate/create', array('type' => 'shipment'));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">采购折让</button>
		</a>
	<?php }?>
<!-- 
	<?php if ($type == 'high' && checkOperation("高开折让:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl("rebate/create", array('type' => "high"));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">高开折让</button>
		</a>
	<?php }?>
 -->
	</div>
</div>

<form method="post" action="">
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
		
		<div class="more_one">
			<div class="more_one_l">公司抬头：</div>
			<div id="title_select" class="fa_droplist">
				<input type="text" id="title_combo" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
				<input type="hidden" id="title_val" class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
			</div>
		</div>
		
		<div class="more_select_box">
			<div class="more_one">
				<div class="more_one_l">结算单位：</div>
				<div id="company_select" class="fa_droplist">
					<input type="text" id="company_combo" class="forreset" value="<?php echo $search['company_name'];?>" name="search[company_name]" />
					<input type="hidden" id="company_val" class="forreset" value="<?php echo $search['company_id'];?>" name="search[company_id]" />
				</div>
			</div>
			<div class="more_one">
				<div class="more_one_l">客户：</div>
				<div id="company_select1" class="fa_droplist">
					<input type="text" id="company_combo1" class="forreset" value="<?php echo $search['client_name'];?>" name="search[client_name]" />
					<input type="hidden" id="company_val1" class="forreset" value="<?php echo $search['client_id'];?>" name="search[client_id]" />
				</div>
			</div>
<!-- 			
			<div class="more_one">
				<div class="more_one_l">乙单：</div>
				<select name="search[is_yidan]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
					<option value="0"<?php #echo $search['is_yidan'] != "0" ? '' : ' selected="selected"';?>>否</option>
					<option value="1"<?php #echo $search['is_yidan'] != "1" ? '' : ' selected="selected"';?>>是</option>
				</select>
			</div>
 -->
<!-- 
			<div class="more_one">
				<div class="more_one_l">业务组：</div>
				<div id="team_select" class="fa_droplist">
					<input type="text" id="team_combo" class="forreset" value="<?php echo $search['team_name'];?>" name="search[team_name]" />
					<input type="hidden" id="team_val" class="forreset" value="<?php echo $search['team_id'];?>" name="search[team_id]" />
				</div>
			</div>
 -->
			<div class="more_one">
				<div class="more_one_l">状态：</div>
				<select name="search[form_status]" class='form-control chosen-select forreset'>
					<option value="" selected='selected'>-全部-</option>
					<option<?php echo $search['form_status'] == "unsubmit" ? ' selected="selected"' : '';?> value="unsubmit">未提交</option>
					<option<?php echo $search['form_status'] == "submited" ? ' selected="selected"' : ''?> value="submited">已提交</option>
					<option<?php echo $search['form_status'] == "approve" ? ' selected="selected"' : ''?> value="approve">已审核</option>
					<option<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?> value="delete">已作废</option>            	
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
	<?php $fixedLeftWidth = $type == 'high' ? 180 : 250;?>
	$('#datatable1').datatable({
		fixedLeftWidth: <?php echo $fixedLeftWidth;?>,
		fixedRightWidth: 0,
	});
});
</script>
</div>
<div class="total_data">
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["price"], 2);?></span></div>
	<div class="total_data_one" style="display: none;">单数：<span class="color_org"><?php echo number_format($totalData["total_num"]);?></span></div>
</div>
<?php paginate($pages, "rebate_list");?>
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
var team_array = <?php echo $team_array ? $team_array : '[]';?>; //业务组 
var title_array = <?php echo $title_array ? $title_array : '[]';?>; //公司抬头 
var company_array = <?php echo $company_array ? $company_array : '[]';?>; //客户 
var user_array = <?php echo $user_array ? $user_array : '[]';?>; //销售员

$(function(){
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
		$.post('/index.php/rebate/deleteform', 
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

	//查看审核记录
	$(".status_btn").click(function(){
		var form_id = $(this).attr("id");
		$.get('/index.php/billApproveLog/getFormLog', 
		{
			'form_id': form_id
		}, 
		function(data) 
		{
			confirmDialog(data);
		});
	});
	
	//select 
	$("#team_combo").combobox(team_array, {}, "team_select", "team_val", false);
	$("#title_combo").combobox(title_array, {}, "title_select", "title_val", false);
	$("#company_combo").combobox(company_array, {}, "company_select", "company_val", false,'');
	$("#company_combo1").combobox(company_array, {}, "company_select1", "company_val1", false,'');
	$("#user_combo").combobox(user_array, {}, "user_select", "user_val", false, "getSimpleList()");
});
//-->
</script>
