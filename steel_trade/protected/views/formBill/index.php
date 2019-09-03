<style>
.submit_form, .reset{ cursor:pointer;}
#company_select{ position: relative; float: left; display: inline; width: 145px;}
.status_btn{ color: blue;}
</style>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('formBill/export', array('type' => $_REQUEST['type']));?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php if ( ($type == "FKDJ" && checkOperation("付款登记:新增")) || ($type == "SKDJ" && checkOperation("收款登记:新增")) ) {?>
		<a href="<?php echo Yii::app()->createUrl("formBill/create", array('type' => $type))?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal"><?php echo $type == "FKDJ" ? "付款" : "收款";?>登记</button>
		</a>
	<?php }?>
	</div>
	<?php 
		if($type=='FKDJ'||$type='SKDJ'){
			if($type=='FKDJ'){$normal_view='付款普通视图';$check_view='付款审核视图';$coo='bill_view';}else{
				$normal_view='收款普通视图';$check_view='收款审核视图';$coo='sbill_view';
			}
	?>
<div class="view_section">
	<?php 
	 $num = 0;
	 if(checkOperation($check_view)){
	 	$yes=$_COOKIE[$coo]=='all';
	 	$num ++;
	?>
		<a href="<?php echo Yii::app()->createUrl('formBill/index',array('type'=>$type,'range'=>'all'))?>">
		<div class="view_button_right view_button  <?php  echo  $yes?'blue_back':''?>" range="all" title="查询视图">
			<img alt="" class="view_button_img" src="<?php echo $yes?'/images/right_blue1.png':'/images/right_white1.png'?>">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
	<?php }?>
	<?php if(checkOperation($normal_view) ){$num++;$yes1=$_COOKIE[$coo]=='belong';?>
		<a href="<?php echo Yii::app()->createUrl('formBill/index',array('type'=>$type,'range'=>'belong'))?>">
		<div class=" view_button_left  view_button   <?php  echo  $yes1?'blue_back':''?>"  range="belong" title="普通视图">
			<img alt="" class="view_button_img" src="<?php echo $yes1?'/images/left_blue1.png':'/images/left_white1.png'?>">
		</div>
		</a>
	<?php }?>
	</div>
	<script type="text/javascript">
		var coo='<?php echo $coo;?>';
		$('.view_button').click(function(){
			document.cookie=coo+"="+$(this).attr('range');
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
				<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
			</div>
			<div style="float: left; margin: 0 3px;">至</div>
			<div class="search_date_box">
				<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
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
					<option value="all" >-全部-</option>
				<?php if ($type == "FKDJ") {?>
					<option value="unsubmit"<?php echo $search['form_status'] == "unsubmit" ? ' selected="selected"' : ''?>>未提交</option>
					<option value="submited"<?php echo $search['form_status'] == "submited" ? ' selected="selected"' : ''?>>已提交</option>
					<option value="approving"<?php echo $search['form_status'] == "approving" ? ' selected="selected"' : ''?>>审核中</option>
					<option value="approve"<?php echo $search['form_status'] == "approve" ? ' selected="selected"' : ''?>>已审核</option>
					<option  value="unaccount"<?php echo ($search['form_status'] == "unaccount"||!$search['form_status']) ? ' selected="selected"' : '';?>>未入账</option>
					<option value="accounted"<?php echo $search['form_status'] == "accounted" ? ' selected="selected"' : '';?>>已入账</option>
					<option value="delete"<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?>>已作废</option>
				<?php } elseif ($type == "SKDJ") {?>
					<option value="submited"<?php echo ($search['form_status'] == "submited"||!$search['form_status']) ? ' selected="selected"' : ''?>>已提交</option>
					<option value="accounted"<?php echo $search['form_status'] == "accounted" ? ' selected="selected"' : '';?>>已入账</option>
					<option value="delete"<?php echo $search['form_status'] == "delete" ? ' selected="selected"' : ''?>>已作废</option>
				<?php }?>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l"><?php echo $type == "FKDJ" ? "付款" : "收款";?>类型：</div>
				<select name="search[bill_type]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
				<?php if ($type == "FKDJ") {?>
					<option value="CGFK"<?php echo $search['bill_type'] == 'CGFK' ? ' selected="selected"' : '';?>>采购付款</option>
					<option value="TPYF"<?php echo $search['bill_type'] == 'TPYF' ? ' selected="selected"' : '';?>>托盘预付</option>
					<option value="TPSH"<?php echo $search['bill_type'] == 'TPSH' ? ' selected="selected"' : '';?>>托盘赎回</option>
					<option value="XSTH"<?php echo $search['bill_type'] == 'XSTH' ? ' selected="selected"' : '';?>>销售退货付款</option>
					<option value="DLFK"<?php echo $search['bill_type'] == 'DLFK' ? ' selected="selected"' : '';?>>代理付款</option>
					<option value="GKFK"<?php echo $search['bill_type'] == 'GKFK' ? ' selected="selected"' : '';?>>高开付款</option>
					<option value="XSZR"<?php echo $search['bill_type'] == 'XSZR' ? ' selected="selected"' : '';?>>销售折让</option>
					<!-- <option value="GKZR"<?php echo $search['bill_type'] == 'GKZR' ? ' selected="selected"' : '';?>>高开折让</option> -->
					<option value="CCFY"<?php echo $search['bill_type'] == 'CCFY' ? ' selected="selected"' : '';?>>仓储费用</option>
					<option value="YF"<?php echo $search['bill_type'] == 'YF' ? ' selected="selected"' : '';?>>运费</option>
					<option value="BZJ"<?php echo $search['bill_type'] == 'BZJ' ? ' selected="selected"' : '';?>>保证金</option>
				<?php } else {?>
					<option value="XSSK"<?php echo $search['bill_type'] == 'XSSK' ? ' selected="selected"' : '';?>>销售收款</option>
					<option value="CGTH"<?php echo $search['bill_type'] == 'CGTH' ? ' selected="selected"' : '';?>>采购退货收款</option>
					<option value="CKFL"<?php echo $search['bill_type'] == 'CKFL' ? ' selected="selected"' : '';?>>仓库返利</option>
					<option value="GCFL"<?php echo $search['bill_type'] == 'GCFL' ? ' selected="selected"' : '';?>>钢厂返利</option>
					<option value="DLSK"<?php echo $search['bill_type'] == 'DLSK' ? ' selected="selected"' : '';?>>代理收款</option>
				<?php }?>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l"><?php echo $type == "FKDJ" ? "付款" : "收款";?>方式：</div>
				<select name="search[pay_type]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
				<?php foreach (FrmFormBill::$payTypes as $k => $v) {?>
					<option value="<?php echo $k;?>"<?php echo $search['pay_type'] == $k ? ' selected="selected"' : '';?>><?php echo $v;?></option>
				<?php }?>
				</select>
			</div>
			
			<div class="more_one">
				<div class="more_one_l">乙单：</div>
				<select name="search[is_yidan]" class='form-control chosen-select forreset'>
					<option value="" selected="selected">-全部-</option>
					<option value="0"<?php echo $search['is_yidan'] != "0" ? '' : ' selected="selected"';?>>甲单</option>
					<option value="1"<?php echo $search['is_yidan'] != "1" ? '' : ' selected="selected"';?>>乙单</option>
				</select>
			</div>
			<div class="more_one">
				<div class="more_one_l">业务员：</div>
				 <select name="search[owned]" class='form-control chosen-select forreset'>
			            <option value='0' selected='selected'>-全部-</option>
			             <?php foreach ($users as $k=>$v){?>
		            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
		            <?php }?>
			        </select>
			</div>
			<div class="more_one">
				<div class="more_one_l">客户：</div>
				<div id="company_select1" class="fa_droplist">
					<input type="text" id="company_combo1" class="forreset" value="<?php echo $search['client_name'];?>" name="search[client_name]" />
					<input type="hidden" id="company_val1" class="forreset" value="<?php echo $search['client_id'];?>" name="search[client_id]"/>
				</div>
			</div>
			<!-- <div class="more_one" style="width: 270px;">
				<div class="more_one_l" style="width: 120px;">入账开始时间：</div>
				<div class="search_date_box">
					<input type="text"  class="form-control form-date forreset" placeholder="选择入账日期"  value="<?php echo $search['account_time_L']?>" name="search[account_time_L]">
				</div>
			</div> -->
			
			<!-- <div class="more_one" style="width: 270px;">
				<div class="more_one_l" style="width: 120px;">入账结束时间：</div>
				<div class="search_date_box">
					<input type="text"  class="form-control form-date forreset" placeholder="选择入账日期" value="<?php echo $search['account_time_H']?>" name="search[account_time_H]"  >
				</div>
			</div> -->
			
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
			'hide' => 0
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
<?php paginate($pages, "form_bill_list")?>
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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<script type="text/javascript">
//select数组
var company_array = <?php echo $company_array ? $company_array : '[]';?>;
var title_array = <?php echo $title_array ? $title_array : '[]';?>;

$(function(){
	var list_id, lastdate = 0;

	$(document).on("click",'.submit_form',function(e){
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
		$.post('/index.php/formBill/deleteform', 
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
	$(document).on('click','.status_btn',function(){
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
	$("#company_combo1").combobox(company_array, {}, "company_select1", "company_val1");
	$("#title_combo").combobox(title_array, {}, "title_select", "title_val", false);
});


/*
 * 弹出colorbox
 */
	$(document).on('click','.colorbox',function(e){
		e.preventDefault();
		var url = $(this).attr('url');
// 		var ven_id=$('#comboval').val();
// 		var title_id=$('#comboval2').val();
// 		if(ven_id==undefined){ven_id=$('.supply_id').val();}
// 		if(title_id==undefined){title_id=$('.title_id').val();}
// 		url=url+'?ven_id='+ven_id+'&title_id='+title_id;
		$.colorbox({
			href:url,
			opacity:0.6,
			iframe:true,
			title:"",
			width: "910px",
			height: "535px",
			overlayClose: false,
			speed: 0,
			onClosed: '',//function(){initSet();},
		});
	});



</script>
