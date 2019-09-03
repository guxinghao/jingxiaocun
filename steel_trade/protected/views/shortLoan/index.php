<style>
.status_btn{ color: blue;}
</style>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru  btn_export" url="<?php echo Yii::app()->createUrl('shortLoan/loanExport'); ?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php if (checkOperation("短期借贷:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl('shortLoan/create');?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">短期借贷</button>
		</a>
	<?php }?>
	</div>
</div>
<form method="post" action="" url="">
	<div class="search_body">
		<div class="srarch_box" style="width: 210px;">
			<img src="<?php echo imgUrl('search.png');?>">
			<input id="srarch" class="forreset" style="width: 178px;" value="<?php echo $search['keywords']?>" name="search[keywords]" placeholder="请输入单号">
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
			<div class="more_one_l">公司：</div>
			<div id="title_select" class="fa_droplist">
				<input id="title_combo" type="text" class="forreset"  value="<?php echo $search['title_name'];?>" name="search[title_name]" />
				<input id="title_val" type="hidden" class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
			</div>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">借贷公司：</div>
			<div id="company_select" class="fa_droplist">
				<input id="company_combo" type="text" class="forreset"  value="<?php echo $search['company_name'];?>" name="search[company_name]" />
				<input id="company_val" type="hidden" class="forreset"  value="<?php echo $search['company_id'];?>" name="search[company_id]" />
			</div>
		</div>
		
		<div class="more_select_box">
			<div class="more_one" >
				<div class="more_one_l">状态：</div>
				<select name="search[form_status]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
					<option value="unsubmit"<?php echo $search['form_status'] == "unsubmit" ? ' selected="selected"' : ''?>>未提交</option>
					<option value="submited"<?php echo $search['form_status'] == "submited" ? ' selected="selected"' : ''?>>已提交</option>
					<option value="approving"<?php echo $search['form_status'] == "approving" ? ' selected="selected"' : ''?>>审核中</option>
					<option value="approve"<?php echo $search['form_status'] == "approve" ? ' selected="selected"' : ''?>>已审核</option>
					<option value="accounted"<?php echo $search['form_status'] == "accounted" ? ' selected="selected"' : ''?>>已入账</option>
					<option value="delete"<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?>>已作废</option>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l">借贷方向：</div>
				<select id="lending_direction_select" class="form-control chosen-select forreset" name="search[lending_direction]">
				<option value="0" selected="selected">-全部-</option>
				<?php foreach (ShortLoan::$lendingDirection as $k => $v) {?>
					<option value="<?php echo $k;?>"<?php echo $search['lending_direction'] == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
				<?php }?>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l">借据：</div>
				<select id="has_Ious_select" class="form-control chosen-select forreset" name="search[has_Ious]">
				<option value="-1" selected="selected">-全部-</option>
				<?php foreach (ShortLoan::$hasIous as $k => $v) {?>
					<option value="<?php echo $k;?>"<?php echo $search['has_Ious'] === $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
				<?php }?>
				</select>
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
			'totalData' =>$totalData,
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

<?php paginate($pages, "short_loan_list");?>
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
//select 
var company_array = <?php echo $company_array ? $company_array : '[]';?>;
var title_array = <?php echo $title_array ? $title_array : '[]';?>;

$(function() {
	var list_id, lastdate = 0;
	
	$(document).on('click','.submit_form',function(e){
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
		$.post('/index.php/billOther/deleteform', 
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
	$(document).on('click',".status_btn",function(){
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
	
	$("#title_combo").combobox(title_array, {}, 'title_select', 'title_val', false);
	$("#company_combo").combobox(company_array, {}, 'company_select', 'company_val', false);
});
</script>




