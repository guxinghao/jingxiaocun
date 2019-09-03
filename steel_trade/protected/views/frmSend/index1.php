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
	<div class="con_tit_cz">
	<?php 
	if(checkOperation("配送单:新增")  && $sales->confirm_status == 0){?>
	<a href="<?php echo Yii::app()->createUrl('frmSend/create',array("id"=>$id))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建配送单</button>
	</a>
	<?php }?>
	</div>
		<div class="view_section">
	<?php 
	 if(checkOperation("配送审核视图")){
	?>
		<a href="<?php echo Yii::app()->createUrl('frmSend/index',array("view"=>"checkview"))?>">
		<div class="view_button_right view_button <?php if($view=="checkview"){echo "blue_back";}?>" title="审核视图">
			<img alt="" class="view_button_img" src="/images/right_<?php if($view=="checkview"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
		<a href="<?php echo Yii::app()->createUrl('frmSend/index',array("view"=>"index"))?>">
		<div class=" view_button_left view_button <?php if($view=="index"){echo "blue_back";}?>" title="普通视图">
			<img alt="" class="view_button_img" src="/images/left_<?php if($view=="index"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
	<?php }?>
	</div>
</div>
<form method="post" action="">
<div class="search_body">
<?php if(!$id){?>
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
<?php }?>
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
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='combval' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="shop_more_one1">
	<div style="float:left;">结算单位：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $search['custome_name'];?>" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>
	<div class="more_select_box" style="left:260px;top:128px;">
	<div class="more_one">
		<div class="more_one_l">配送状态：</div>
		 <select name="search[status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['status']=="unpush"?'selected="selected"':''?>  value="unpush">未推送</option>
           		 <option <?php echo $search['status']=="pushed"?'selected="selected"':''?>  value="pushed">待出库</option>
           		 <option <?php echo $search['status']=="output"?'selected="selected"':''?>  value="output">已出库</option>
           		 <option <?php echo $search['status']=="finished"?'selected="selected"':''?>  value="finished">已完成</option>
           		 <option <?php echo $search['status']=="deleted"?'selected="selected"':''?>  value="deleted">已作废</option>     	
	        </select>
	</div>
	<?php if($view=="checkview"){?>
	<div class="more_one">
		<div class="more_one_l">制单人：</div>
		 <select name="search[owned_by]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <?php foreach ($users as $k=>$v){?>
            	 <option <?php echo $k==$search['owned_by']?'selected="selected"':''?>  value="<?php echo $k;?>"><?php echo $v;?></option>
            	<?php }?>  	
	     </select>
	</div>
	<?php }?>
	<div class="more_one">
		<div class="more_one_l">提货凭证：</div>
		 <input  class="forreset" value="<?php echo $search['text']?>" name="search[text]">
	</div>
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
	//重发信息
	$(function(){
		$(".send_message").on("click",function(){
			var id  = $(this).attr("sendid");
			var tel = $(this).attr("tel");
			var str = $(this).attr("str");
			sendMessage(tel,str,id);
		});
	})
	
	//点击车牌号
	$(document).on("click",".car_no",function(){
		var car_list = $(this).attr("title");
		confirmCarNo(car_list);
	});
	</script>
<script>
	$(function(){
		var brand = <?php echo $brands?>;
		var array=<?php echo $vendors;?>;
		var coms=<?php echo $coms;?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#combo2').combobox(coms, {},"ywyselect","combval");
		$('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})
	</script>