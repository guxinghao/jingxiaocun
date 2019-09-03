<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<?php
$_inputTime = array(6=>"00:00~06:00",12=>"06:00~12:00",18=>"12:00~18:00",24=>"18:00~24:00");
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<style>
.ss_tt_one{min-width:295px;}
.ss_tt_one_l{width:auto;}
</style>
<input type="hidden" name='CommonForms[owned_by]'  value="<?php echo $sales->baseform->owned_by;?>" >
<div class="con_tit" style="height:43px;background-color:#f5f5f5;padding-left:1%;">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">销售单号：</div>
		<span class="sales_sn"><?php echo $baseform->form_sn;?></span>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">公司：</div>
		<span class="dictTitle"><?php echo $sales->dictTitle->short_name;?></span>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">结算单位：</div>
		<span class="dictCompany"><?php echo $sales->dictCompany->short_name;?></span>
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">提货凭证：</div>
		<label class='radio-inline billclick'><input type="radio" name="send[auth_type]" value="bill">提货单</label>
        <label class='radio-inline carclick'><input type="radio" name="send[auth_type]" value="car" checked>车船号</label>
	</div>
	<div class="ss_tt_one">
		<div class="auth_text ss_tt_one_l"><span class="bitian">*</span>车船号：</div>
		<input type="text" class="form-control tit_remark" name="send[auth_text]" style="width:328px;" placeholder="多个用逗号或空格隔开">
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">备注：</div>
		<input type="text" class="form-control" placeholder="" name="CommonForms[comment]" style="width:210px;">
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l" ><span class="bitian">*</span>提货日期：</div>
		<input type="text"  name="send[start_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  value="<?php echo date("Y-m-d");?>">
		<div style="float:left;padding:0 5px">至</div>
		<input type="text"  name="send[end_time]" class="form-control form-date date end_time input_backimg"  placeholder="选择日期" value="<?php echo date("Y-m-d",time()+3600*24*180);?>">
	</div>
</div>
<input type="hidden" class="now_time" value="<?php echo date("Y-m-d");?>">
<input type="hidden" class="sales_id" value="<?php echo $sales->id;?>" name="send[frm_sales_id]">
<div class="create_table">
<?php if($id) {?>
	<table class="table" id="ps_tb">
		<thead>
			<tr>
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:25%;">产地/品名/材质/规格/长度</th>
         		<th class="text-center" style="width:15%;">总件数</th>
         		<th class="text-center" style="width:15%;">可配送件数</th>
         		<th class="text-center" style="width:15%;">配送件数</th>
         		<th class="text-center" style="width:25%;">预计到货日期</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$num = 0;
			foreach($salesDetails as $li){
			$num++;
			$t_amount += $li->amount;
			$t_can += ($li->amount-$li->send_amount);
			if($li->amount-$li->send_amount == 0){continue;}
			?>
			<tr>
				<input type="hidden" name="sales_detail_id[]" value="<?php echo $li->id;?>">
				<input type="hidden" name="product[]" value="<?php echo $li->product_id;?>"/>
				<input type="hidden" name="rank[]" value="<?php echo $li->rank_id;?>"/>
				<input type="hidden" name="brand[]" value="<?php echo $li->brand_id;?>"/>
				<input type="hidden" name="texture[]" value="<?php echo $li->texture_id;?>"/>
				<input type="hidden" name="length[]" value="<?php echo $li->length;?>"/>
				<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
				<td class="text-center">
				<span class="card_type">
				<?php echo $li->brand."/".$li->product."/".$li->texture."/".$li->rank."/".$li->length;?>
				</span>
				</td>
				<td><input type="text" class="form-control td_shop_num"  name="td_total[]" readonly value="<?php echo $li->amount;?>"></td>
				<td>
						<input type="hidden"  class=" td_can_num"  name="td_can[]"  value="<?php echo $li->amount-$li->send_amount;?>">
						<div class="click_can" style="width:100%;text-align:center;color:blue;font-weight:bold;"><?php echo $li->amount-$li->send_amount;?></div>
				</td>
				<td>
					<input type="text" class="form-control  td_amount"  name="amount[]" value="">
					<input type="hidden" class="one_weight" value="<?php echo $li->one_weight;?>">
					<input type="hidden" class="form-control td_shop_num td_weight"  name="weight[]">
				</td>
				<td class="text-center"><span class="td_date">
				<?php 
					if($li->mergestorage->is_transit == 1){
						echo date("Y-m-d",$li->mergestorage->pre_input_date);
						if($li->mergestorage->pre_input_time > 0){
							echo "&nbsp;&nbsp;".$_inputTime[$li->mergestorage->pre_input_time];
						}
					}
				?>
				</span></td>
			</tr>
			<?php }?>
			<tr>
				<td class="text-center">合计：</td><td></td>
				<td><span class="total_amount"><?php echo $t_amount;?></span></td>
				<td><span class="total_can"><?php echo $t_can;?></span></td>
				<td><span class="total-num"></span></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<?php }?>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo $backurl;?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<?php if(!$id){?>
<div class="caigou_body" style="margin-top:20px;">
	<div class="search_body" style="min-width:996px;position: relative;">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入销售单号" id="search" class="forreset" value="" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="开始日期"  value="" name="search[time_L]" id="mtime_l">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="结束日期" value="" name="search[time_H]" id="mtime_h">
		</div>
	</div>
	<div class="select_body" >
	<div class="shop_more_one1">
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="" name="search[title_name]" />
			<input type='hidden' id='combval' class="forreset" value="" name="search[title_id]" />
		</div>
	</div>
	<div class="more_select_box" style="left:180px;top:49px;width:750px;">
	<div class="more_one">
		<div class="more_one_l">客户：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="" name="search[customer_id]" />
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">业务组：</div>
		 <select name="search[team]" class='form-control chosen-select forreset' id="mteam">
	            <option value='0' selected='selected'>-全部-</option>
	            <?php foreach ($teams as $k=>$v){?>
           		<option value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l">仓库：</div>
		<div id="warehouseselect" class="fa_droplist">
			<input type="text" id="combo3" value="" class="forreset" />
			<input type='hidden' id='comboval3' value="" class="wareinput forreset" name="FrmSales[warehouse_id]"/>
		</div>
	</div>
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
	<div class="" id="kucun_table" style="float:left;width:100%;">

	</div>
</div>
<?php }?>
<script>
getsales(1);
//查询提交按钮
$(".btn_sub").on("click",function(){
		getsales(1);
})
//整行点击选中
$(document).on("click","#kucun_table table tbody tr",function(){
	$(this).find(".selected_contract").attr("checked","checked");
	findCheck();
})
//单选框变化时间
$(document).on("click",".selected_contract",function(){
	findCheck();
})
function findCheck(){
	$(".selected_contract").each(function(){
		if($(this).attr("checked")){
			var sales_sn = $(this).parent().parent().find(".form_sn").text();
			var title = $(this).parent().parent().find(".title_name").text();
			var custome = $(this).parent().parent().find(".custome_name").text();
			$(".sales_sn").text(sales_sn);
			$(".dictTitle").text(title);
			$(".dictCompany").text(custome);
			var id = $(this).val();
			$(".sales_id").val(id);
			getSalesDetail(id);
		}
	})
}
function getSalesDetail(id){
	$.post("/index.php/frmSales/detaillist",{"id":id},function(data){
		$(".create_table").html(data);
	})
}
//每页显示发生变化
$(document).on("change","#each_page",function(){
	limit=$(this).val();
	var url = $(this).attr('href');
	$.post("/index.php/site/writeCookie", {
	'name' : "sendsales_list",
	'limit':limit
	}, function(data){
		getsales(1);
		//refreshTable("storagetable",60,0);
		//setStorageCheck();	
	});
})
//页码点击事件
$(document).on("click",".sauger_page_a",function(){
	var page = $(this).attr("page");
	getsales(1);
	//refreshTable("storagetable",60,0);
})

$(document).on("change",".td_amount",function(){
	var amount = $(this).val();
	if(!/^[1-9][0-9]*$/.test(amount))
	{
		confirmDialog('件数必须是大于0的整数');
		return false;
	}
	var weight = Number($(this).next().val());
	t_weight = (amount*weight).toFixed(3);
	$(this).parent().parent().find(".td_weight").val(t_weight);
	var total = 0;
	var total_weight = 0;
	$(".td_amount").each(function(){
		var one = Number($(this).val());
		total = total +one;
	});
	$(".total-num").html(total);
	$(".td_weight").each(function(){
		var one = Number($(this).val());
		total_weight = total_weight +one;
	});
	$(".total_weight").html(total_weight.toFixed(3));
})

$(".deleted_tr").live("click",function(){
		$(this).parent().parent().remove();
		var num = 0;
		$(".deleted_tr").each(function(){
			num++;
		})
		if(num == 0){
			$("input[name=selected_contract]").attr("checked",false);
		}
		var total = 0;
		var total_weight = 0;
		var total_amount = 0;
		var total_can = 0;
		$(".td_amount").each(function(){
			var one = Number($(this).val());
			total = total +one;
		});
		$(".total-num").html(total);
		$(".td_weight").each(function(){
			var one = Number($(this).val());
			total_weight = total_weight +one;
		});
		$(".total_weight").html(total_weight.toFixed(3));
		$(".td_shop_num").each(function(){
			var one = Number($(this).val());
			total_amount = total_amount +one;
		});
		$(".total_amount").html(total_amount);
		$(".td_can_num").each(function(){
			var one = Number($(this).val());
			total_can = total_can +one;
		});
		$(".total_can").html(total_can);
	})
	
var can_submit = true;	
$("#save").click(function(){
	var text = $(".tit_remark").val();
	if(text==''){confirmDialog("请输入提货单号或者车船号！");return false;}
	var way = $('input:radio:checked').val();
	if(way == "car"){
		var result = checkTravel(text);
		if(result != 1){confirmDialog(result);return false;}
	}
	var now_time = $(".now_time").val();
	var start_time = $(".start_time").val();
	var end_time = $(".end_time").val();
	if(start_time < now_time){confirmDialog("提货日期小于当前时间");return false;}
	if(start_time == ""){confirmDialog("请输入提货日期");return false;}
	if(end_time == ""){confirmDialog("请输入提货截止日期");return false;}
	if(start_time > end_time){confirmDialog("提货日期不能早于到货时间");return false;}
	var is_submit = true;
	var num=1;
	$("#ps_tb tbody tr").each(function(){
		var card_id =$.trim($(this).find(".card_type").text());
		var amount = Number($(this).find(".td_amount").val());
		var td_shop_num = Number($(this).find(".td_can_num").val());
		var pre_input_date = $(this).find(".td_date").text();
		if(amount == ''){confirmDialog("请输入规格为"+card_id+"的件数");is_submit = false;return false;}
		if(amount > td_shop_num){confirmDialog("规格为"+card_id+"的件数大于可出库件数");is_submit = false;return false;}
		if(pre_input_date != ""){
			if(start_time < pre_input_date){confirmDialog("提货日期不能早于到货时间");is_submit = false;return false;}
		}
		num++;
	})
	if(is_submit){
		if(can_submit){
			can_submit = false;
			notAnymore("save");
			$("#form_data").submit();
		}
	}
});
$(function(){
	var array=<?php echo $vendor?$vendor:"[]";?>;
	var coms=<?php echo $com?$com:"[]";?>;
	var warehouse=<?php echo $warehouse?$warehouse:"[]";?>;
	$('#combo').combobox(array,{},"wareselect","comboval");
	$('#combo2').combobox(coms, {},"ywyselect","combval");
	$('#combo3').combobox(warehouse, {},"warehouseselect","comboval3");
	
	$(".billclick").click(function(){
		$(".auth_text").html('<span class="bitian">*</span>凭证单号：');
		$(".tit_remark").attr("placeholder","");
	});
	$(".carclick").click(function(){
		$(".auth_text").html('<span class="bitian">*</span>车船号：');
		$(".tit_remark").attr("placeholder","多个用逗号或空格隔开");
	});
	//可开件数点击事件
	$(".click_can").on("click",function(){
			var value = Number($(this).parent().find(".td_can_num").val());
			var one_weight = Number($(this).parent().parent().find(".one_weight").val());
			var weight = value * one_weight;
			$(this).parent().parent().find(".td_amount").val(value);
			$(this).parent().parent().find(".td_weight").val(weight);
	});
})
</script>