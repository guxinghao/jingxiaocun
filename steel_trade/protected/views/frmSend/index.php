<style>
.ss_tt_one{font-size:14px;}
.ss_tt_one_l{width:auto;}
.check_str{height:auto;}
.pop_footer{height:60px;}
.check_div{height:auto;}
.send_one{width:400px;margin:0 auto;line-height:30px;height:auto;}
.send_one input{margin:0;padding:0;height:30px;line-height:30px;}
.send_one_l{width:100px;float:left;text-align:right;}
.send_one_r{width:300px;float:left;padding-left:5px;}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<div class="con_tit">
	<div class="con_tit_daoru">
		<a href="<?php echo $backUrl?>"><img src="<?php echo imgUrl('back_url.png');?>">返回</a>
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
	<?php 
	if(checkOperation("配送单:新增")  && $sales->confirm_status == 0){?>
	<a href="<?php echo Yii::app()->createUrl('frmSend/create',array("id"=>$id))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建配送单</button>
	</a>
	<?php }?>
	</div>
	<div class="con_tit_duanshu" style="margin-left:15px;display:none"></div>
	<div class="ss_tt_one" style="height:38px;line-height:35px;font-size:14px;margin-left:10px;display:none">
		<div class="ss_tt_one_l">销售单号：</div>
		<?php echo $sales->baseform->form_sn;?>
	</div>
</div>
<div class="ss_tt_title" style="border-bottom:1px dotted #b1b0b0;">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">销售单号：</div>
		<?php echo $sales->baseform->form_sn;?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">公司：</div>
		<?php echo $sales->dictTitle->short_name;?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">结算单位：</div>
		<span title="<?php echo $sales->dictCompany->name;?>">
		<?php echo $sales->dictCompany->short_name;?>
		</span>
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">销售件数：</div>
		<?php echo $send["amount"];?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">配送件数：</div>
		<?php echo $send["send_amount"];?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">出库件数：</div>
		<?php echo $send["output_amount"];?>
	</div>
</div>
<form method="post" action="">
<div class="search_body" style="background-color:#f5f5f5;border-top: 1px solid #989898;border-bottom:none;width:98%;margin-left:1%;">
<?php if(!$id){?>
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
<?php }?>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box" style="width:145px;">
			<input type="text"  class="form-control form-date forreset date" placeholder="开始日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box" style="width:145px;">
			<input type="text"  class="form-control form-date forreset date" placeholder="结束日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">
	<div class="shop_more_one1">
		<div style="float:left;">提货码：</div>
		 <input  class="form-control forreset" value="<?php echo $search['text']?>" name="search[text]" style="width:145px;">
	</div>
	<div class="shop_more_one1">
		<div style="float:left;">配送状态：</div>
		  <select name="search[status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['status']=="unpush"?'selected="selected"':''?>  value="unpush">未推送</option>
           		 <option <?php echo $search['status']=="pushed"?'selected="selected"':''?>  value="pushed">待出库</option>
           		 <option <?php echo $search['status']=="output"?'selected="selected"':''?>  value="output">已出库</option>
           		 <option <?php echo $search['status']=="finished"?'selected="selected"':''?>  value="finished">已完成</option>
           		 <option <?php echo $search['status']=="deleted"?'selected="selected"':''?>  value="deleted">已作废</option>     	
	        </select>
	</div>
	<div class="shop_more_one1">
		<div style="float:left;">提货凭证号：</div>
		 <input  class="form-control forreset" value="<?php echo $search['auth_text']?>" name="search[auth_text]" style="width:145px;">
	</div>
	<div class="more_select_box" style="left:260px;top:220px;">
	<div class="more_one" >
		<div class="more_one_l">产地：</div>
	     <div id="brandselect" class="fa_droplist">
			<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
			<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
		</div>
	</div>	
	<div class="more_one">
		<div class="more_one_l">品名:</div>
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
		<div class="more_one">
			<div class="more_one_l">长度：</div>
				<select name="search[length]" class='form-control chosen-select forreset form_status'  id="mlength">
		            <option value='-1' selected='selected'>-全部-</option>	             
	           		 <option <?php echo $search['length']==0 && isset($search['length'])?'selected="selected"':''?>  value="0">0</option>
	           		 <option <?php echo $search['length']==9?'selected="selected"':''?>  value="9">9</option>
	           		 <option <?php echo $search['length']==12?'selected="selected"':''?>  value="12">12</option>
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
			'id' => 'sendtable',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'totalData' =>"",
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#sendtable').datatable({
    	 fixedLeftWidth:240,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<?php paginate($pages,"frmsend_list")?>
<div class="pop_background" style="display:none;"></div>
<div class="check_background" id="check" style="display:none;">
	<div class="check_div">
		<div class="pop_title">审核
			<span class="pop_cancle"><i class="icon icon-times"></i></span>
		</div>
		<div class="check_str"></div>
		<div class="pop_footer">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="check_sub">同意</button>
			<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">拒绝</button>
		</div>
	</div>
</div>
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
var lastdate;
var list_id;
var deleted;
//点击作废按钮
	$(document).on("click",".delete_form",function(){
		list_id = $(this).attr("id");
		lastdate = $(this).attr("lastdate");
		deleted = $(this).attr("deleted");
		$(".pop_background").show();
		$("#deleted").show();
	});
	//点击取消按钮
	$(".pop_cancle").click(function(){
		$(".pop_background").hide();
		$("#deleted").hide();
		$("#check").hide();
	});
	//作废提交按钮
	$("#submit").on("click",function(){
	  	var str = $(".pop_textarea").val();
	  	if(str == ''){confirmDialog("请输入作废原因");return false;}
		$.post("/index.php/FrmSend/deleteform/"+list_id,{"last_update":lastdate,"str":str,'deleted':deleted},function(e){
			if(e=="success"){
				window.location.reload();
			}else{
				if(e) confirmDialog(e);
				else confirmDialog("作废失败");
			}
		})
	 })
	 
	$(function(){
		 $('.submit_form').click(function(e){
			var title=$(this).attr("title");
			var href = $(this).attr('url');
			var num = $(this).parent().parent().find(".form_sn").val();
			var text = '确认要'+title+'配送单'+num+'吗';
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
	//点击车牌号
	$(document).on("click",".car_no",function(){
		var car_list = $(this).attr("title");
		confirmCarNo(car_list);
	});
	//重发信息
	$(function(){
		$(".send_message").on("click",function(){
			var id  = $(this).attr("sendid");
			var tel = $(this).attr("tel");
			var str = $(this).attr("str");
			sendMessage(tel,str,id);
		});
	})
	</script>
<script>
	$(function(){
		var brand = <?php echo $brands?$brands:"[]"?>;
		var array=<?php echo $vendors?$vendors:"[]";?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(coms, {},"ywyselect","combval");
		$('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})
	</script>