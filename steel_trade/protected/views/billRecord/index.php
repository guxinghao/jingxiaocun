<style>
<!--
.submit_form{ cursor: pointer;}
.pop_background_{ position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #000; z-index: 98; opacity: 0.6;}
-->
</style>

<div class="con_tit">
<?php if ($backUrl) {?>
	<div class="con_tit_daoru">
		<a href="<?php echo $backUrl?>"><img src="<?php echo imgUrl('back_url.png');?>">返回</a>
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_daoru">
		<a href="#"><img src="<?php echo imgUrl('daochu.png');?>">导出</a>
	</div>
	<div class="con_tit_duanshu"></div>
<?php if ($common_id && count($tableData) == 0) {?>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('billRecord/create', array('common_id' => $common_id));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新增运费</button>
		</a>
	</div>
<?php }?>
</div>

<form method="post" action="">
	<div class="search_body">
	<?php if (!$common_id) {?>
		<div class="srarch_box">
			<img src="<?php echo imgUrl('search.png');?>">
			<input placeholder="请输入单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
		</div>
	<?php }?>
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
	<?php if (!$common_id) {?>
		<div class="more_one">
			<div class="more_one_l">公司：</div>
			<div id="title_select" class="fa_droplist">
				<input id="title_combo" type="text" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
				<input id="title_val" type="hidden" class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
			</div>
		</div>
	<?php }?>
		<div class="more_one">
			<div class="more_one_l">收益单位：</div>
			<div id="logistic_select" class="fa_droplist">
				<input type="text" id="logistic_combo" class="forreset" value="<?php echo $search['company_name'];?>" name="search[company_name]" />
				<input type='hidden' id='logistic_val'  class="forreset"  value="<?php echo $search['company_id'];?>" name="search[company_id]" />
			</div>
		</div>
		
		<div class="more_select_box">
			<div class="more_one">
				<div class="more_one_l" style="width: 70px">状态：</div>
				<select name="search[form_status]" class='form-control chosen-select forreset'>
					<option value='0' selected='selected'>-全部-</option>
					<?php if ($common_id) {?><option <?php echo $search['form_status'] == "unsubmit" ? 'selected="selected"' : '';?> value="unsubmit">未提交</option><?php }?>
					<option <?php echo $search['form_status'] == "submited" ? 'selected="selected"' : '';?> value="submited">已提交</option>
					<option <?php echo $search['form_status'] == "approve" ? 'selected="selected"' : '';?> value="approve">已审核</option>
					<option <?php echo $search['form_status'] == "delete" ? 'selected="selected"' : '';?> value="delete">已作废</option>
				</select>
			</div>
		
		</div>
		
		<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
		<div title="更多" class="more_toggle" style="background-image: url(&quot;/images/seclose.png&quot;);"></div>
		<img title="重置"  src="<?php echo imgUrl('reset.png');?>" class="reset">
	</div>
</form>

<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData
	));
?>
<script type="text/javascript">
$(function(){
	$('#datatable1').datatable({
		fixedLeftWidth: 300, 
		fixedRightWidth:0,
	});
});
</script>
</div>
<div class="total_data">
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["price"], 2);?></span></div>
	<div class="total_data_one">单数：<span class="color_org"><?php echo number_format($totalData["total_num"]);?></span></div>
</div>
<?php paginate($pages, "bill_record_list")?>

<script type="text/javascript">
$.getScript("/zui/lib/datetimepicker/datetimepicker.min.js", function() {
	if ($.fn.datetimepicker) 
	{
		// 选择时间和日期
		$(".form-date").datetimepicker(
		{
	    	language:  "zh-CN",
    	    weekStart: 1,
    	    todayBtn:  1,
    	    autoclose: 1,
    	    todayHighlight: 1,
    	    startView: 2,
    	    minView: 2,
    	    forceParse: 0,
    	    format: "yyyy-mm-dd"
	    })
	}
});

$(function(){
	var list_id, lastdate = 0;
	
	$('.submit_form').click(function(e){
		var text = '';
		var title = $(this).attr("title");
		var href = $(this).attr('url');
		var num = $(this).parent().parent().find(".form_sn").val();
		if (title == '取消审核') 
		{
			var id = $(this).attr('id');
	        var is_selected = 0;
	        $.ajaxSetup({ async : false });
	        $.get('/index.php/billRecord/checkSelected', { 'id': id }, function(data){ is_selected = data; });
	        if (is_selected > 0) text += '该运费已关联付款，';
		}
		text += '确认要'+title+'<?php echo $this->pageTitle;?>'+num+'吗？';
        confirmDialog2(text, href);
    });

    $(".delete_form").click(function(){
        var id = $(this).attr('data-id');
        var is_selected = 0;
        $.ajaxSetup({ async : false });
        $.get('/index.php/billRecord/checkSelected', { 'id': id }, function(data){ is_selected = data; });
        if (is_selected > 0) 
        {
        	confirmDialog("该运费已关联付款，无法作废");
            return false;
        }
        deleteIt(this);
    });

	$(".reset").click(function(){
		$(".forreset").val("");
	});
});
</script>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script type="text/javascript">	
var title_array = <?php echo $title_array ? $title_array : '[]';?>;
var logistic_array = <?php echo $logistic_array ? $logistic_array : '[]';?>;

$(function(){
	$("#title_combo").combobox(title_array, {}, 'title_select', 'title_val');
	$("#logistic_combo").combobox(logistic_array, {}, "logistic_select", "logistic_val");
});
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/pub_index.js"></script>
