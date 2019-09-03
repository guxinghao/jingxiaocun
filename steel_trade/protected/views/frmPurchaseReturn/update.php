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
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<input type="hidden" value="<?php echo $baseform->last_update;?>" name="CommonForms[last_update]">
<?php if($baseform->form_status == "unsubmit") {?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combo2" value="<?php echo $return->dictTitle->short_name;?>" />
			<input type='hidden' id='combval' value="<?php echo $return->title_id;?>" name="PurchaseReturn[title_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $return->supply->short_name;?>" />
			<input type='hidden' id='comboval' value="<?php echo $return->supply_id;?>" name="PurchaseReturn[supply_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
			<select name='PurchaseReturn[company_contact_id]' class='form-control chosen-select se_yw' id="contact_id">
			<?php foreach($contacts as $k=>$v){?>
	        	<option <?php echo $return->company_contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	        <?php }?>
	      	</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" id="phone" class="form-control con_tel" readonly value="<?php echo $return->contact->mobile;?>">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>仓库：</div>
		<div id="warehouseselect" class="fa_droplist">
			<input type="text" id="combo3" value="<?php echo $return->warehouse->name;?>" />
			<input type='hidden' id='comboval3' value="<?php echo $return->warehouse_id;?>" class="wareinput" name="PurchaseReturn[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开单日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time" placeholder="选择日期"  value="<?php echo $baseform->form_time;?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">预计退货日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="PurchaseReturn[return_data]" class="form-control form-date date fs_date"  placeholder="选择日期" value="<?php echo $return->return_data>0?date("Y-m-d",$return->return_data):"";?>" >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车牌号：</div>
		<input type="text"  class="form-control con_tel" name="PurchaseReturn[travel]" id="traver" placeholder="多个用空格或逗号隔开" value="<?php echo $return->travel;?>">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name='CommonForms[owned_by]' class='form-control chosen-select se_yw' id="CommonForms_owned_by" onchange="changeOwnerTT()">
	       <?php foreach($users as $k=>$v){?>
	            <option <?php echo $baseform->owned_by==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	       <?php }?>
	      </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<input type="hidden" value="" name="PurchaseReturn[team_id]" id="team_idnum"/>
		<select id="team_id" disabled class='form-control chosen-select se_yw'>
	 		<option selected="selected" value=''></option>
            <?php foreach($teams as $k=>$v){?>
            	<option <?php echo $return->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
            <?php }?>
       </select>			
	</div>
	<div class="shop_more_one">
		<div class="shop_more_checkbox">
			 <label class='radio-inline'> <input class="check_box" type="checkbox" name="PurchaseReturn[is_yidan]" <?php echo $return->is_yidan?"checked":"";?> value="1"> 乙单 </label>
		</div> 
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text" class="form-control tit_remark" placeholder="" name="CommonForms[comment]" value="<?php echo $baseform->comment;?>">
	</div>
</div>
<?php }else{?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<input type="text" readonly value="<?php echo $return->dictTitle->short_name;?>" class="form-control" />
		<input type='hidden' id='combval' value="<?php echo $return->title_id;?>" name="PurchaseReturn[title_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
		<input type="text" readonly value="<?php echo $return->supply->short_name;?>" class="form-control"  />
		<input type='hidden' value="<?php echo $return->supply_id;?>" name="PurchaseReturn[customer_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
			<select name='PurchaseReturn[company_contact_id]' class='form-control chosen-select se_yw' id="contact_id">
			<?php foreach($contacts as $k=>$v){?>
	            	<option <?php echo $return->company_contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	      	</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" id="phone" class="form-control con_tel" value="<?php echo $return->contact->mobile;?>" readonly>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">仓库：</div>
		<input type="text" class="form-control con_tel" readonly value="<?php echo $return->warehouse->name;?>">
		<input type='hidden' id='comboval3' value="<?php echo $return->warehouse_id;?>"  name="PurchaseReturn[warehouse_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开单日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" class="form-control form-date start_time" placeholder="选择日期" readonly value="<?php echo $baseform->form_time;?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">预计提货日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="PurchaseReturn[return_data]" class="form-control form-date date fs_date"  placeholder="选择日期" value="<?php echo $return->return_data>0?date("Y-m-d",$return->return_data):"";?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车牌号：</div>
		<input type="text" class="form-control con_tel" name="PurchaseReturn[travel]" readonly value="<?php echo $return->travel;?>" id="traver" placeholder="多个用空格或逗号隔开">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<input type="text" class="form-control con_tel" readonly value="<?php echo $baseform->belong->nickname;?>">
		<input type='hidden' value="<?php echo $baseform->owned_by;?>"  name="CommonForms[owned_by]"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		<input type="text" value="<?php echo $return->team->name;?>" readonly class="form-control" />
		<input type='hidden' value="<?php echo $return->team_id;?>"  name="PurchaseReturn[team_id]"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_checkbox">
			 <label class='radio-inline'><input class="check_box" disabled type="checkbox" name="PurchaseReturn[is_yidan]" <?php echo $return->is_yidan?"checked":"";?> value="1"> 乙单 </label>
		</div> 
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  class="form-control tit_remark" name="CommonForms[comment]" value="<?php echo $baseform->comment;?>">
	</div>
</div>
<?php }?>
<div class="create_table">
<input type="hidden" id="tr_num" value="0">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:4%;">操作</th>
         		<th class="text-center" style="width:10%;">卡号</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>规格</th>
         		<th class="text-center" style="width:5%;">长度</th>
         		<th class="text-center" style="width:8%;">可退件数</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>退货件数</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>退货重量</th>
         		<th class="text-center" style="width:10%;"><span class="bitian">*</span>退货单价</th>
         		<th class="text-center" style="width:10%;"><span class="bitian">*</span>退货金额</th>
      		</tr>
    	</thead>
    <tbody>
    	<?php
    		$num =1; 
    		foreach($details as $dt){
    			$toatl_amount += $dt->return_amount;
    			$toatl_weight += $dt->return_weight;
    			$toatl_price += $dt->return_weight*$dt->return_price;
    	?>
    	<tr class="">
    		<td class="text-center list_num"><?php echo $num;?></td>
    		<input type="hidden" class="td_detail_id" value="<?php echo $dt->id;?>" name="detail_id[]">
			<input type="hidden" class="td_warehouse_id" value="<?php echo $return->warehouse_id;?>">
			<input type="hidden" class="td_title_id" value="<?php echo $return->title_id;?>">
			<input type="hidden" class="td_supply_id" value="<?php echo $return->supply_id;?>">
			<input type="hidden" name="card_id[]" class="td_card_id" value="<?php echo $dt->card_no;?>">
			<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>
			<td class="text-center"><input type="text" class="form-control td_num" readonly value="<?php echo $dt->storage->card_no;?>"></td>
			<td class=""><input type="text" class="form-control td_place" value="<?php echo $dt->brand;?>" readonly><input type="hidden"  name="place[]" value="<?php echo $dt->brand_id;?>"></td>
			<td class=""><input type="text" class="form-control td_product" value="<?php echo $dt->product;?>" readonly><input type="hidden"  name="product[]" value="<?php echo $dt->product_id;?>"></td>
			<td class=""><input type="text" class="form-control td_material" value="<?php echo $dt->texture;?>" readonly><input type="hidden"  name="material[]" value="<?php echo $dt->texture_id;?>"></td>
			<td class=""><input type="text" class="form-control td_type"  value="<?php echo $dt->rank;?>" readonly><input type="hidden"  name="type[]" value="<?php echo $dt->rank_id;?>"></td>
			<td class=""><input type="text" class="form-control td_length" name="length[]" value="<?php echo $dt->length;?>" readonly>
				<input type="hidden" class="td_weight" name="weight[]" value="<?php echo $dt->weight;?>">
				<input type="hidden" class="td_surplus"  value="<?php echo $dt->surplus;?>">
			</td>
			<td class=""><input type="text" class="form-control" readonly value="<?php echo $dt->surplus;?>"></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" name="td_num[]" value="<?php echo $dt->return_amount;?>" <?php if($baseform->form_status != "unsubmit") echo "readonly";?>></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" name="td_total[]" value="<?php echo number_format($dt->return_weight,3);?>" <?php if($baseform->form_status != "unsubmit") echo "readonly";?>></td>
    		<td class=""><input type="text"  style="" class="form-control td_money" name="money[]" value="<?php echo number_format($dt->return_price);?>" <?php if($baseform->form_status != "unsubmit") echo "readonly";?>>
    		<td class=""><input type="text"  readonly style="" class="form-control td_price"  name="price[]" value="<?php echo number_format($dt->return_weight*$dt->return_price,2);?>" <?php if($baseform->form_status != "unsubmit") echo "readonly";?>></td>
		</tr>
    	<?php
    		$num++; 
    		}    	
    	?>
    </tbody>
    	<tfoot>
	    	<tr class="tablefoot">
		    	<td class="text-center" style="width:3%;" colspan=2>合计：</td>
				<td style="width:10%;"></td>
				<td style="width:8%;"></td>
				<td style="width:6%;"></td>
				<td style="width:7%;"></td>
				<td style="width:5%;"></td>
				<td style="width:5%;"></td>
				<td style="width:8%;"></td>
				<td style="width:8%;"><span class="total_amount"><?php echo $toatl_amount;?></span></td>
				<td style="width:9%;"><span class="total_weight"><?php echo $toatl_weight;?></span></td>
				<td style="width:10%;"></td>
				<td style="width:10%;"><span class="total_price"><?php echo number_format($toatl_price,2);?></span></td>
			</tr>
    	</tfoot>
  </table>
  <input type="hidden" id="tr_num" value="<?php echo $num -1;?>">
</div>
<div class="btn_list">
	<?php if($baseform->form_status == "unsubmit") {?>
	<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="save_sub">保存提交</button>
	<?php }?>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo Yii::app()->createUrl('frmPurchaseReturn/index')?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<?php if($baseform->form_status == "unsubmit") {?>
<div class="caigou_body">
	<div class="search_line"></div>
	<div class="search_title">选择退货产品</div>
	<div class="search_body search_background" style="position:relative;">
		<div class="cg_search_one" style="display:none;">
			<div class="cg_search_one_l">卡号：</div>
			<input type="text"  class="form-control con_tel forreset" id="mcard_id">
		</div>
		<div class="select_body" style="color:#426ebb;">
			<div class="more_one" style="margin-top:8px;width:193px;">
				<div style="float:left;">仓库：</div>
				<div id="warehouseselect1" class="fa_droplist">
					<input type="text" id="combo31" value="" class="forreset"/>
					<input type='hidden' id='comboval31' value="" class="wareinput forreset" name="search[warehouse_id]"/>
				</div>
			</div>
			<div class="more_one" style="margin-top:8px;width:193px;">
				<div style="float:left;">公司：</div>
				<div id="ttselect1" class="fa_droplist">
					<input type="text" id="combo21" value="" class="forreset"/>
					<input type='hidden' id='combval21' value="" name="search[title_id]" class="forreset"/>
				</div>
			</div>
			<div class="more_one" style="margin-top:8px;width:193px;">
				<div style="float:left;">产地：</div>
		    	<div id="brandselect" class="fa_droplist">
					<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
					<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
				</div>
			</div>
		</div>
		<div class="more_select_box" style="top:50px;">
			<div class="more_one">
				<div class="more_one_l">品名：</div>
				<select name="search[product]"  class='form-control chosen-select forreset' id="mproduct">
		            <option value='0' selected='selected'>-全部-</option>
		            <?php foreach ($product as $k=>$v){?>
		            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
		            <?php }?>
		        </select>
			</div>
			<div class="more_one">
				<div class="more_one_l">材质：</div>
				<select name="search[texture]" class='form-control chosen-select forreset' id="mtexture">
	        		<option value='0' selected='selected'>-全部-</option>
	            	<?php foreach ($material as $k=>$v){?>
            		<option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            		<?php }?>
		       	</select>
			</div>
			<div class="more_one">
				<div class="more_one_l">规格：</div>
				<select name="search[rand]" class='form-control chosen-select forreset' id="mrand">
		            <option value='0' selected='selected'>-全部-</option>
		            <?php foreach ($type as $k=>$v){?>
		            <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
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
		<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
		<div class="more_toggle" title="更多"></div>
		<img  src="<?php echo imgUrl('reset.png');?>" class="reset">
	</div>
	<div class="" id="kucun_table" style="float:left;width:100%;">

	</div>
</div>
<?php }?>
<script type="text/javascript">
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
$("#mproduct").change(function(){
	getPReturnStorageData(1);
});
$("#mtexture").change(function(){
	getPReturnStorageData(1);
});
$("#mrand").change(function(){
	getPReturnStorageData(1);
});
$("#mlength").change(function(){
	getPReturnStorageData(1);
});
//输入框失去焦点
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
	//初始化仓库数据
	$(function(){
		changeOwnerTT();
		getPReturnStorageData(1);
	})
	
	//表格样式初始化
	function refreshTableStyle(){
		var row_num=0;
		$("#cght_tb tbody tr").each(function(){
			row_num++;
			$(this).find(".list_num").html(row_num);
			//confirmDialog(row_num);
			if(row_num%2 == 0){
				$(this).addClass("selected");
			}else{
				$(this).removeClass("selected");
			}
		});
		$("#tr_num").val(row_num);
	}
	
	//查询提交按钮
	$(".btn_sub").on("click",function(){
			//confirmDialog(1);
			getPReturnStorageData(1);
			//refreshTable("storagetable",60,0);
	})
	//每页显示发生变化
	$(document).on("change","#each_page",function(){
		limit=$(this).val();
		var url = $(this).attr('href');
		$.post("/index.php/site/writeCookie", {
		'name' : "preturnstorage_list",
		'limit':limit
		}, function(data){
			getPReturnStorageData(1);
			//refreshTable("storagetable",60,0);
			//setStorageCheck();	
		});
	})
	//页码点击事件
	$(document).on("click",".sauger_page_a",function(){
		var page = $(this).attr("page");
		getPReturnStorageData(page);
		//refreshTable("storagetable",60,0);
	})
	
		//新增信息点击事件
	$(document).on("click","#storagetable tbody tr",function(e){
		if($(this).find(".checkit").attr("checked")){
			$(this).find(".checkit").attr("checked",false);
		}else{
			$(this).find(".checkit").attr("checked",true);
		}
		var dom = $(this).find(".checkit");
		clickCheckbox(dom);
	});
	//复选框点击事件
	$(document).on("click",".checkit",function(e){
		e.stopPropagation();    //  阻止事件冒泡
		clickCheckbox($(this));
	})
	
	//复选框点击事件
	function clickCheckbox(dom){
		var row_num=parseInt($("#tr_num").val());
		var card_no = dom.val();
		var product = dom.parent().parent().find(".product").text();
		var rand = dom.parent().parent().find(".rand").text();
		var texture = dom.parent().parent().find(".texture").text();
		var length = dom.parent().parent().find(".length").text();
		var brand = dom.parent().parent().find(".brand").text();
		var card_id = dom.attr("card_id");
		var product_std = dom.parent().find(".product_std").val();
		var rand_std = dom.parent().find(".rand_std").val();
		var texture_std = dom.parent().find(".texture_std").val();
		var brand_std = dom.parent().find(".brand_std").val();
		var surplus = dom.parent().parent().find(".surplus").text();
		var weight = dom.parent().find(".weight").val();
		var warehouse_id = dom.parent().find(".warehouse_id").val();
		var warehouse_name = dom.parent().parent().find(".warehouse_name").text();
		var ware_id = $("#comboval3").val();
		var title_id = dom.parent().find(".title_id").val();
		var title_name = dom.parent().parent().find(".title_name").text();
		var supply_id = dom.parent().find(".supply_id").val();
		var supply_name = dom.parent().parent().find(".supply_name").text();
		if(row_num == 0){
			$("#comboval3").val(warehouse_id);
			$("#combo3").val(warehouse_name);
			$("#combo2").val(title_name);
			$("#combval").val(title_id);
			$("#comboval").val(supply_id);
			$("#combo").val(supply_name);
			$.get('/index.php/contract/getVendorCont',{
				'vendor_id':supply_id,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1o'));
				var data2=data.substring(data.indexOf('o1o')+3);
				$('#contact_id').html(data1);
				$('#phone').val(data2);
			});
		}
		
		if(dom.attr("checked")){
			row_num++;
			var count=parseInt($("#tr_num").val()) + 1;
			var newRow = '';
			var yu = count % 2;
			if(yu == 0)
			{
				newRow = '<tr class="selected">';
			}else{
				newRow = '<tr class="">';
			}
			
			newRow +='<td class="text-center list_num">'+count+'</td>'+
			'<input type="hidden" class="td_warehouse_id" value="'+warehouse_id+'">'+
			'<input type="hidden" class="td_title_id" value="'+title_id+'">'+
			'<input type="hidden" class="td_supply_id" value="'+supply_id+'">'+
			'<input type="hidden" name="card_id[]" class="td_card_id" value="'+card_id+'">'+
			'<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:26px;"></i></td>'+
			'<td class="text-center"><input type="text" class="form-control td_num" readonly  value="'+card_no+'"></td>'+
			'<td class=""><input type="text" class="form-control td_place" value="'+brand+'" num="'+brand_std+'" readonly><input type="hidden"  name="place[]" value="'+brand_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_product" value="'+product+'" num="'+product_std+'" readonly><input type="hidden"  name="product[]" value="'+product_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_material" value="'+texture+'" num="'+texture_std+'" readonly><input type="hidden"  name="material[]" value="'+texture_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_type"  value="'+rand+'" num="'+rand_std+'" readonly><input type="hidden"  name="type[]" value="'+rand_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_length" name="length[]" value="'+length+'" readonly>'+
			'<input type="hidden" class="td_weight" name="weight[]" value="'+weight+'">'+
			'<input type="hidden" class="td_surplus"  value="'+surplus+'">'+
			'</td>'+
			'<td class=""><input type="text" class="form-control" readonly value="'+surplus+'"></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]" value=""></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]" value=""></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]">'+
    		'<td class=""><input type="text" readonly style="" class="form-control td_price" placeholder=""  name="price[]"></td>'+
		'</tr>';
			$("#cght_tb tbody").append(newRow);
			$("#tr_num").val(count);
			//setSalesDetial(card_no,product,rand,texture,length,brand,cost);
		}else{
			deleteSalesDetail(card_id);
		}
	}
	//删除销售单明细
	function deleteSalesDetail(card_no){
		$(".td_card_id").each(function(){
			var num = $(this).val();
			if(num == card_no){
				$(this).parent().remove();
			}
		});
		refreshTableStyle();
		setTotalamount();
		setTotalweight();
		setTotalprice();
	}


	$(".deleted_tr").live("click",function(){
		$(this).parent().parent().remove();
		var card_no = $(this).parent().parent().find(".td_card_id").val();
		$(".checkit").each(function(){
			var num = $(this).attr("card_id");
			if(num == card_no){
				$(this).removeAttr("checked");
			}
		});
		refreshTableStyle();
		setTotalamount();
		setTotalweight();
		setTotalprice();
	})
var can_submit = true;	
//保存提交
$("#save_sub").click(function(){
		$("#submit_type").val(1);
		var str='';
		var gys = $("#comboval").val();
		var supply_name = $("#combo").val();
		if(gys==''){confirmDialog("请选择客户！");return false;}
		var cggs = $("#combval").val();
		var title_name = $("#combo2").val();
		if(cggs==''){confirmDialog("请选择公司！");return false;}
		var warehouse =  $("#comboval3").val();
		var warehouse_name = $("#combo3").val();
		if(warehouse==''){confirmDialog("请选择仓库！");return false;}
		var start_time = $(".start_time").val();
		if(start_time==''){confirmDialog("请选择开单日期！");return false;}
		var is_submit = true;
		var num = 0;
		var traver = $("#traver").val();
		var result = checkTravel(traver);
		if(result != 1){confirmDialog(result);return false;}
		$("#cght_tb tr").each(function(){
			if($(this).hasClass("tablefoot")){return true;}
			num++;
			if(num >1){
				var list_num = $(this).find(".list_num").text();
				//var td_length = $(this).find(".td_length").val();
				var td_num = $(this).find(".td_shop_num").val();
				var td_price = $(this).find(".td_money").val();
				var td_surplus = $(this).find(".td_surplus").val();
				var cost = parseFloat($(this).find(".td_cost").val());
				var warehouse_id = $(this).find(".td_warehouse_id").val();
				var title_id = $(this).find(".td_title_id").val();
				var supply_id = $(this).find(".td_supply_id").val();
				if(td_num == ''){confirmDialog("请输入销售件数");$(this).find(".td_shop_num").addClass("red-border");is_submit = false;return false;}
				if(parseInt(td_num) > parseInt(td_surplus)){confirmDialog("销售件数大于可售件数");$(this).find(".td_shop_num").addClass("red-border");is_submit = false;return false;}
				if(td_price == ''){confirmDialog("请输入单价");$(this).find(".td_money").addClass("red-border");is_submit = false;return false;}
				if(title_id != cggs){confirmDialog("产品不属于"+title_name);$(this).addClass("delete_background");is_submit = false;return false;}
				if(warehouse_id != warehouse){confirmDialog("产品不在"+warehouse_name+"里");$(this).addClass("delete_background");is_submit = false;return false;}
			}
		})
		if(num >1){
			if(is_submit){
				if(can_submit){
					can_submit = false;
					notAnymore('save_sub');
					$("#form_data").submit();
				}
			}
		}else{
			confirmDialog("请输入退货单明细");return false;
		}
	})
	//保存
	$("#save").click(function(){
		var str='';
		var gys = $("#comboval").val();
		var supply_name = $("#combo").val();
		if(gys==''){confirmDialog("请选择供应商！");return false;}
		var cggs = $("#combval").val();
		var title_name = $("#combo2").val();
		if(cggs==''){confirmDialog("请选择公司！");return false;}
		var warehouse =  $("#comboval3").val();
		var warehouse_name = $("#combo3").val();
		if(warehouse==''){confirmDialog("请选择仓库！");return false;}
		var start_time = $(".start_time").val();
		if(start_time==''){confirmDialog("请选择开单日期！");return false;}
		var traver = $("#traver").val();
		var result = checkTravel(traver);
		if(result != 1){confirmDialog(result);return false;}
		var is_submit = true;
		var num = 0;
		$("#cght_tb tr").each(function(){
			if($(this).hasClass("tablefoot")){return true;}
			num++;
			if(num >1){
				var list_num = $(this).find(".list_num").text();
				//var td_length = $(this).find(".td_length").val();
				var td_num = $(this).find(".td_shop_num").val();
				var td_price = $(this).find(".td_money").val();
				var td_surplus = $(this).find(".td_surplus").val();
				var cost = parseFloat($(this).find(".td_cost").val());
				var warehouse_id = $(this).find(".td_warehouse_id").val();
				var title_id = $(this).find(".td_title_id").val();
				var supply_id = $(this).find(".td_supply_id").val();
				if(td_num == ''){confirmDialog("请输入销售件数");$(this).find(".td_shop_num").addClass("red-border");is_submit = false;return false;}
				if(parseInt(td_num) > parseInt(td_surplus)){confirmDialog("销售件数大于可售件数");$(this).find(".td_shop_num").addClass("red-border");is_submit = false;return false;}
				if(td_price == ''){confirmDialog("请输入单价");$(this).find(".td_money").addClass("red-border");is_submit = false;return false;}
				if(title_id != cggs){confirmDialog("产品不属于"+title_name);$(this).addClass("delete_background");is_submit = false;return false;}
				if(warehouse_id != warehouse){confirmDialog("产品不在"+warehouse_name+"里");$(this).addClass("delete_background");is_submit = false;return false;}
			}
		})
		if(num >1){
			if(is_submit){
				if(can_submit){
					can_submit = false;
					notAnymore('save');
					$("#form_data").submit();
				}
			}
		}else{
			confirmDialog("请输入退货单明细");return false;
		}
	})
</script>
<script>
	//销售件数发生变化
	$(document).on('change','.td_shop_num',function(){
			var td_num=$(this).val();
			var weight=numChange($(this).parent().parent().find('.td_weight').val());
			var total;
			var td_money=numChange($(this).parent().parent().find('.td_money').val());
			if(td_num==''){return false;}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须是大于0的整数');
				return false;
			}
			amount = weight*td_num;
			$(this).parent().parent().find('.td_shop_total').val(fmoney(amount,3));
			if(td_money > 0){
				$(this).parent().parent().find('.td_price').val(fmoney(td_money*amount,2));
			}
			setTotalamount();
			setTotalweight();
			setTotalprice();
		});
	//销售单价发生变化
	$(document).on('change','.td_money',function(){
			var that=$(this);
			var td_money=numChange($(this).val());
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_money) || td_money == 0)
			{
				confirmDialog('退货单价必须是大于0且小数点后只有2位的正数');
				return false;
			}
			td_money=parseFloat(td_money);
			var td_shop_total=numChange($(this).parent().parent().find('.td_shop_total').val());
			var total;
			if(td_money==''){return false;}	
			$(this).parent().parent().find('.td_price').val(fmoney(td_money*td_shop_total));
			setTotalprice();
		});
	//联系人发生变化
	$('#contact_id').change(function(){
		var contact_id=$(this).val();
		$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
			$('#phone').val(data);
		});
    });
</script>
	<script>
	 function changeCont()
	    {
			var vendor_id=$('#comboval').val();
			$.get('/index.php/contract/getVendorCont',{
				'vendor_id':vendor_id,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1o'));
				var data2=data.substring(data.indexOf('o1o')+3);
				$('#contact_id').html(data1);
				$('#phone').val(data2);
			});
		}	   
		function changeTeamU()
		{
			var team_id= $('#comboval4').val();
//	     	confirmDialog(team_id);	
	    	$.get('/index.php/contract/getTeamUser',{
	    		'team_id':team_id,
	    		},function(data){
	    			$('#CommonForms_owned_by').html(data);
	    		});	
		}
	
	$(function(){
		var brand = <?php echo $brand?$brand:"[]"?>;
		var array=<?php echo $vendors?$vendors:"[]";?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		var array4=<?php echo $teams?$teams:"[]";?>;
		var warehouse=<?php echo $warehouses?$warehouses:"[]";?>;
		$('#combo').combobox(array, {},"wareselect","comboval",false,'changeCont()');
		$('#combo2').combobox(coms, {},"ttselect","combval");
		$('#combo21').combobox(coms, {},"ttselect1","combval21",false,'getPReturnStorageData(1)');
		$('#combo4').combobox(array4, {},"ywyselect","comboval4",false,'changeTeamU()');
		$('#combo_brand').combobox(brand, {},"brandselect","comboval_brand",false,'getPReturnStorageData(1)');
		$('#combo3').combobox(warehouse, {},"warehouseselect","comboval3");
		$('#combo31').combobox(warehouse, {},"warehouseselect1","comboval31",false,'getPReturnStorageData(1)');
	})
</script>