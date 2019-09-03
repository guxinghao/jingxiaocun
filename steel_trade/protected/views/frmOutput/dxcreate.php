<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<input type="hidden" value="<?php if($sales) echo $sales->sales_type;?>" id="salestype"/>
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<div class="ss_tt_title" style="border-bottom:1px dotted #b1b0b0;">
	<div class="shop_more_one" style="position:relative;margin:6px 40px 0 0;width:250px;">
		<div class="shop_more_one_l" style="width:75px;text-align:right;">销售单：</div>
			<input type="hidden" value="<?php if($sales) echo $sales->id;?>" name="out[frm_sales_id]" id="frm_sales_id">
			<input type="text"   id="frmsales_no" value="<?php if($sales) echo $baseform->form_sn;?>"  style="width:145px;height:30px;" readonly class="form-control con_tel">
			<?php if(!$sales){?>
			<span style="float: right;margin:-30px -18px 0 0;width:40px;font-size:15px;color:#fff;text-align:center;border:none;background:#1ca1e4;height:29px;line-height:29px;border-radius:4px;" class="contract_link colorbox" url="<?php echo Yii::app()->createUrl('frmSales/dxlist')?>">关联</span>
			<img style="position:absolute;top:6px;right:40px;width:18px;z-index:10;" class="clear_contract"  src="/images/close11.png"/>
			<?php }?>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">公司：</div>
		<input type="text" id="title_id" value="<?php if($sales) echo $sales->dictTitle->short_name;?>" class="form-control" readonly>
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">结算单位：</div>
		<input type="text" id="customer_id" value="<?php if($sales) echo $sales->dictCompany->short_name;?>" class="form-control" readonly>
	</div>
</div>
<div class="ss_tt_title">
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">仓库：</div>
		<input type="text" id="warehouse_name" value="<?php if($sales) echo $sales->warehouse->name;?>" class="form-control" readonly>
		<input type="hidden" id="warehouse" value="<?php if($sales) echo $sales->warehouse_id;?>">
	</div>
	<div class="ss_tt_one">
		<div class="ss_tt_one_l">备注：</div>
		<input type="text"  class="form-control" placeholder="" name="CommonForms[comment]">
	</div>
</div>
<div class="create_table">
<?php if($id){?>
	<table class="table" id="ckck_tb">
		<thead>
			<tr>
				<th class="text-center" style="width:15%;">卡号</th>
         		<th class="text-center" style="width:10%;">产地</th>
         		<th class="text-center" style="width:10%;">品名</th>
         		<th class="text-center" style="width:10%;">材质</th>
         		<th class="text-center" style="width:10%;">规格</th>
         		<th class="text-center" style="width:10%;">长度</th>
         		<th class="text-center" style="width:10%;">可出库件数</th>
         		<th class="text-center" style="width:10%;"><span class="bitian">*</span>出库件数</th>
         		<th class="text-center" style="width:15%;"><span class="bitian">*</span>出库重量</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($detail as $li) {?>
		<tr class="">
			<input type="hidden" name="sales_detail_id[]" value="<?php echo $li->id;?>" />
			<td class="text-center">
				<?php echo $li->storage->card_no;?>
				<input type="hidden" name="card_id[]" value="<?php echo $li->card_id;?>">		
			</td>
			<td class="">
				<input type="text" class="form-control td_place" value="<?php echo $li->brand;?>" readonly>
				<input type="hidden" class="brand" name="brand[]" value="<?php echo $li->brand_id;?>">
			</td>
			<td class="">
				<input type="text" class="form-control td_product" value="<?php echo $li->product;?>" readonly>
				<input type="hidden" class="product" name="product[]" value="<?php echo $li->product_id;?>">
			</td>
			<td class="">
				<input type="text" class="form-control td_material" value="<?php echo $li->texture;?>" readonly>
				<input type="hidden" class="texture" name="texture[]" value="<?php echo $li->texture_id;?>">
			</td>
			<td class="">
				<input type="text" class="form-control td_type"  value="<?php echo $li->rank;?>" readonly>
				<input type="hidden" class="rank" name="rank[]" value="<?php echo $li->rank_id;?>">
			</td>
			<td class=""><input type="text" class="form-control td_length" name="length[]" value="<?php echo $li->length;?>" readonly></td>
			<td class=""><input type="text" class="form-control td_surplus" value="<?php echo $li->amount-$li->output_amount;?>" readonly></td>
			<td>
				<input type="text" class="form-control td_amount"  name="amount[]"  value="">
				<input type="hidden" class="td_one_weight" value="<?php echo $li->one_weight;?>">
			</td>
			<td><input type="text" class="form-control td_weight"  name="weight[]" value=""></td>
		</tr>
		<?php }?>
		</tbody>
	</table>
	<?php }?>
</div>

<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="save_sub">保存出库</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo $backurl;?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget();?>
<script>
function getOutdetail(id){
	$.post("/index.php/frmOutput/dxdetaillist",{"id":id},function(data){
		$(".create_table").html(data);
	})
}
//出库件数发生变化
$(document).on('change','.td_amount',function(){
	var amount = $(this).val();
	if(!/^[1-9][0-9]*$/.test(amount) && amount!=0)
	{
		confirmDialog('件数必须是大于等于0的整数');
		$(this).val('');
		return false;
	}
	var weight = $(this).parent().find(".td_one_weight").val();
	var total_weight =(amount*weight).toFixed(3);
	$(this).parent().parent().find(".td_weight").val(total_weight);
});
var can_submit = true;
$("#save").click(function(){
	$("#submit_type").val(0);
	var is_submit = true;
	var num = 0;
	var card_no = 0
	var warehouse = $("#warehouse").val();
	var json = "[";
	var card = "[";
	var amount_arr = "[";
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id == ""){
		confirmDialog("请选择关联销售单");return false;
	}
	var has_amount = false;
	$("#ckck_tb tr").each(function(){
		num++;
		if(num >1 ){
			card_no ++;
			var product = $(this).find(".product").val();
			var rank = $(this).find(".rank").val();
			var texture = $(this).find(".texture").val();
			var brand = $(this).find(".brand").val();
			var length = Number($(this).find(".td_length").val());
			var amount = Number($(this).find(".td_amount").val());
			var weight = Number($(this).find(".td_weight").val());
			var surplus = Number($(this).find(".td_surplus").val());
			if(amount > 0){
				has_amount=true;
				if(amount > surplus){confirmDialog("出库件数大于销售单累积出库件数");is_submit = false;return false;}
			}
// 			if(amount == ''){confirmDialog("请输入第"+(num-1)+"行的出库件数");is_submit = false;return false;}
// 			if(weight == ''){confirmDialog("请输入第"+(num-1)+"行的出库重量");is_submit = false;return false;}
			str =''+product+rank+texture+brand+length;
			amount_arr += '"'+amount+'",'
			json += '"'+str+'",';
		}
	})
	json=json.substring(0,json.length-1);
	amount_arr = amount_arr.substring(0,amount_arr.length-1);
	card += ']';
	json += ']';
	amount_arr += ']'; 
	if(card_no > 0 && has_amount){
		if(is_submit){
			$.post("/index.php/frmOutput/checkInputXX",{"id":frm_sales_id,"json":json,"card":card,"amount":amount_arr,"warehouse":warehouse},function(data){
				//confirmDialog(data);
				if(data == "success"){
					if(can_submit){
						can_submit = false;
						notAnymore('save');
						$("#form_data").submit();
					}
				}else{
					confirmDialog(data);
				}
			});
		}
	}else{
		confirmDialog("请输入出库单明细");return false;
	}
});

$("#save_sub").click(function(){
	$("#submit_type").val(1);
	var is_submit = true;
	var num = 0;
	var card_no = 0
	var warehouse = $("#warehouse").val();
	var json = "[";
	var card = "[";
	var amount_arr = "[";
	var frm_sales_id = $("#frm_sales_id").val();
	if(frm_sales_id == ""){
		confirmDialog("请选择关联销售单");return false;
	}
	var has_amount = false;
	$("#ckck_tb tr").each(function(){
		num++;
		if(num >1 ){
			card_no ++;
			var product = $(this).find(".product").val();
			var rank = $(this).find(".rank").val();
			var texture = $(this).find(".texture").val();
			var brand = $(this).find(".brand").val();
			var length = Number($(this).find(".td_length").val());
			var amount = Number($(this).find(".td_amount").val());
			var weight = Number($(this).find(".td_weight").val());
			var surplus = Number($(this).find(".td_surplus").val());
			if(amount > 0){
				has_amount=true;
				if(amount > surplus){confirmDialog("出库件数大于销售单累积出库件数");is_submit = false;return false;}
			}
// 			if(amount == ''){confirmDialog("请输入第"+(num-1)+"行的出库件数");is_submit = false;return false;}
// 			if(weight == ''){confirmDialog("请输入第"+(num-1)+"行的出库重量");is_submit = false;return false;}
			str =''+product+rank+texture+brand+length;
			amount_arr += '"'+amount+'",'
			json += '"'+str+'",';
		}
	})
	json=json.substring(0,json.length-1);
	amount_arr = amount_arr.substring(0,amount_arr.length-1);
	card += ']';
	json += ']';
	amount_arr += ']'; 
	if(card_no > 0 && has_amount){
		if(is_submit){
			$.post("/index.php/frmOutput/checkInputXX",{"id":frm_sales_id,"json":json,"card":card,"amount":amount_arr,"warehouse":warehouse},function(data){
				//confirmDialog(data);
				if(data == "success"){
					if(can_submit){
						can_submit = false;
						notAnymore('save_sub');
						$("#form_data").submit();
					}
				}else{
					confirmDialog(data);
				}
			});
		}
	}else{
		confirmDialog("请输入出库单明细");return false;
	}
});

$('.colorbox').click(function(e){
	e.preventDefault();
	var url = $(this).attr('url');
	$.colorbox({
		href:url,
		opacity:0.6,
		iframe:true,
		title:"",
		width: "1020px",
		height: "635px",
		overlayClose: false,
		speed: 0,
	});
});
</script>