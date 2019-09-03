<style type="text/css">
	.status_btn{ color: blue;}
</style>

<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru  btn_export" url="<?php echo Yii::app()->createUrl('billOther/billExport',array("type"=>"FYBZ")); ?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php if ($type == 'FYBZ' && checkOperation("费用报支:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl('billOther/create', array('type' => $type));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">费用报支</button>
		</a>
	<?php } elseif ($type == 'QTSR' && checkOperation("其他收入:新增")) {?>
		<a href="<?php echo Yii::app()->createUrl('billOther/create', array('type' => $type));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">其他收入</button>
		</a>
	<?php }?>
	</div>
	
<?php if($type=='FYBZ'){?>
<div class="view_section">
	<?php 
	 $num = 0;
	 if(checkOperation("报支审核视图")){
	 	$yes=$_COOKIE['bz_view']=='all';
	 	$num ++;
	?>
		<a href="<?php echo Yii::app()->createUrl('billOther/index',array('type'=>'FYBZ','range'=>'all'))?>">
		<div class="view_button_right view_button  <?php  echo  $yes?'blue_back':''?>" range="all" title="查询视图">
			<img alt="" class="view_button_img" src="<?php echo $yes?'/images/right_blue1.png':'/images/right_white1.png'?>">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
	<?php }?>
	<?php if(checkOperation("报支普通视图") ){$num++;$yes1=$_COOKIE['bz_view']=='belong';?>
		<a href="<?php echo Yii::app()->createUrl('billOther/index',array('type'=>'FYBZ','range'=>'belong'))?>">
		<div class=" view_button_left  view_button   <?php  echo  $yes1?'blue_back':''?>"  range="belong" title="普通视图">
			<img alt="" class="view_button_img" src="<?php echo $yes1?'/images/left_blue1.png':'/images/left_white1.png'?>">
		</div>
		</a>
	<?php }?>
	</div>
	<script type="text/javascript">
		$('.view_button').click(function(){
			document.cookie="bz_view="+$(this).attr('range');
		})
	</script>
	<?php if($num <2) {?>
	<script>
		$(".view_section").hide();		
	</script>
<?php }}?>
	
	
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
				<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo !empty($search['time_L'])?$search['time_L']:date("Y-m-d");?>" name="search[time_L]">
			</div>
			<div style="float: left; margin: 0 3px;">至</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo !empty($search['time_H'])?$search['time_H']:date("Y-m-d");?>" name="search[time_H]"  >
			</div>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">公司抬头：</div>
			<div id="title_select" class="fa_droplist">
				<input type="text" id="title_combo" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
				<input type="hidden" id="title_val" class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]"/>
			</div>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">结算单位：</div>
			<div id="company_select" class="fa_droplist">
				<input type="text" id="company_combo" class="forreset" value="<?php echo $search['company_name'];?>" name="search[company_name]" />
				<input type="hidden" id="company_val" class="forreset" value="<?php echo $search['company_id'];?>" name="search[company_id]"/>
			</div>
		</div>
		
		<div class="more_select_box">
			<div class="more_one">
				<div class="more_one_l">状态：</div>
				<select name="search[form_status]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
					<option value="unsubmit"<?php echo $search['form_status'] == "unsubmit" ? ' selected="selected"' : ''?>>未提交</option>
					<option value="submited"<?php echo $search['form_status'] == "submited" ? ' selected="selected"' : ''?>>已提交</option>
					<option value="approving"<?php echo $search['form_status'] == "approving" ? ' selected="selected"' : ''?>>审核中</option>
					<option value="approve"<?php echo $search['form_status'] == "approve" ? ' selected="selected"' : ''?>>已审核</option>
					<option value="accounting"<?php echo $search['form_status'] == "accounting" ? ' selected="selected"' : ''?>>未入账</option>
					<option value="accounted"<?php echo $search['form_status'] == "accounted" ? ' selected="selected"' : '';?>>已入账</option>
					<option value="delete"<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?>>已作废</option>
				</select>
		</div>
		<div class="more_one">
			<div class="more_one_l">类型：</div>
			<select name="search[form_type1]" class='form-control chosen-select forreset'>
				<option value="0" selected="selected">-全部-</option>
				<?php foreach ($type_array as $k => $v) {?>
					<option value="<?php echo $k;?>"<?php echo $k == $search[form_type1] ? ' selected="selected"' : '';?>><?php echo $v;?></option>
				<?php }?>
				</select>
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
		<div class="more_one">
			<div class="more_one_l">入账人：</div>
			 <select name="search[account_by]" class='form-control chosen-select forreset owned'>
		            <option value='0' selected='selected'>-全部-</option>
		             <?php foreach ($users as $k=>$v){?>
	            <option <?php echo $k==$search['account_by']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
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
			'totalData'=>$totalData,
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

<?php paginate($pages, "bill_other_list")?>
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
//select数组
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

	//select 
	$("#company_combo").combobox(company_array, {}, "company_select", "company_val", false);
	$("#title_combo").combobox(title_array, {}, "title_select", "title_val", false);
});
</script>




