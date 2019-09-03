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
<!-- <div class="con_tit_1">新增先销后进销售单</div> -->
<input type="hidden" value="<?php echo $_GET["type"];?>" name="FrmSales[sales_type]" id="salestype"/>
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<input type="hidden" value="<?php if(checkOperation("销售单价为零")){echo 1;}else{echo 0;}?>"  id="can_zero"/>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combo2" value="" />
			<input type='hidden' id='combval' value="" name="FrmSales[title_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="" />
			<input type='hidden' id='comboval' value="" name="FrmSales[client_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
			<select name='FrmSales[company_contact_id]' class='form-control chosen-select se_yw' id="contact_id">
	      	</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" id="phone" class="form-control con_tel" readonly>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>仓库：</div>
		<div id="warehouseselect" class="fa_droplist">
			<input type="text" id="combo3" value="" name="FrmSales[warehouse_name]"/>
			<input type='hidden' id='comboval3' value="" name="FrmSales[warehouse_id]" class="wareinput" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结算单位：</div>
		<div id="cusselect" class="fa_droplist">
			<input type="text" id="cuscombo" value="" />
			<input type='hidden' id='cuscomboval' value="" name="FrmSales[customer_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开单日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期" value="<?php echo date("Y-m-d");?>" >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">预计提货日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="FrmSales[date_extract]" class="form-control form-date date fs_date input_backimg"  placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name='CommonForms[owned_by]' class='form-control chosen-select se_yw' id="CommonForms_owned_by" onchange="changeOwnerTT()">
	       <?php foreach($users as $k=>$v){?>
	            <option <?php echo $user_dt==$k?'selected="selected"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	       <?php }?>
	      </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<input type="hidden" value="" name="FrmSales[team_id]" id="team_idnum"/>
		<select id="team_id" disabled class='form-control chosen-select se_yw'>
	 		<option selected="selected" value=''></option>
            <?php foreach($teams as $k=>$v){?>
            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
            <?php }?>
       </select>			
	</div>
	<div class="shop_more_one" id="gk_kh">
		<div class="shop_more_one_l">采购员：</div>
		<div id="gkselect" class="fa_droplist">
			<input type="text" id="gkcombo" value="" />
			<input type='hidden' id='gkcomboval' value="" name="gk_id" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_checkbox">
			 <label class='radio-inline'> <input class="check_box" type="checkbox" name="FrmSales[is_yidan]" value="1"> 乙单 </label>
		</div>
	<!--<div class="shop_more_checkbox">
			 <label class='radio-inline'> <input class="check_box" type="checkbox" name="FrmSales[has_bonus_price]" value="1" id="is_gk">高开 </label>
		</div> --> 
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
		<div id="supplyselect" class="fa_droplist">
			<input type="text" id="combosu" value="" />
			<input type='hidden' id='combovalsu' value="" name="FrmSales[supply_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text" class="form-control con_tel" id="traver" placeholder="多个用空格或逗号隔开" name="FrmSales[travel]" >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text" class="form-control tit_remark" placeholder="" name="FrmSales[comment]">
	</div>
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="5">
	<table class="table"  id="cght_tb">
    	<thead>
     		<tr>
         		<th class="text-center" style="width:4%;"></th>
         		<th class="text-center" style="width:6%;">操作</th>
         		<th class="text-center" style="width:13%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>规格</th>
         		<th class="text-center" style="width:5%;">长度</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>销售件数</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>销售重量</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>销售单价</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>销售金额</th>
         		<th class="text-center" style="width:7%;"><span class="gaokai_icon"><img alt="" src="/images/gaokai.png"></span></th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>采购单价</th>
         		<!--  <th class="text-center" style="width:17%;">备注</th>-->
      		</tr>
    	</thead>
    <tbody>
    	    <tr class="">
    		<td class="text-center list_num">1</td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
        		 <div id="brandselect" class="fa_droplist">
					<input type="text" id="combo_brand" value="" />
					<input type='hidden' id='comboval_brand' class="td_brand" value="" name="place[]" />
				</div>
    		</td>
    		<td class="">
    			<select name='product[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($product as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='material[]' class='form-control chosen-select td_texture' onchange="textureChange(this)">
    			<option></option>
                <?php foreach($material as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='type[]' class='form-control chosen-select td_rank' onchange="rankChange(this)">
    			<option></option>
                <?php foreach($type as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_length" placeholder=""  name="length[]">
    			<input type="hidden" id="td_weight" name="weight[]" value=""> 
    		</td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]"></td>
    		<td class=""><input type="text"  readonly  style="" class="form-control td_price" placeholder=""  name="price[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>
    		<td class=""><input type="text"  style="" class="form-control td_fix_price" placeholder="" name="fix_price[]" value=""></td>
    		<!--  <td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>-->
    	</tr>
    	 <tr class="selected">
    		<td class="text-center list_num">2</td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
        		 <div id="brandselect_2" class="fa_droplist">
					<input type="text" id="combo_brand_2" value="" />
					<input type='hidden' id='comboval_brand_2' class="td_brand" value="" name="place[]" />
				</div>
    		</td>
    		<td class="">
    			<select name='product[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($product as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='material[]' class='form-control chosen-select td_texture' onchange="textureChange(this)">
    			<option></option>
                <?php foreach($material as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='type[]' class='form-control chosen-select td_rank' onchange="rankChange(this)">
    			<option></option>
                <?php foreach($type as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_length" placeholder=""  name="length[]">
    			<input type="hidden" id="td_weight" name="weight[]" value=""> 
    		</td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]"></td>
    		<td class=""><input type="text" readonly   style="" class="form-control td_price" placeholder=""  name="price[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>
    		<td class=""><input type="text"  style="" class="form-control td_fix_price" placeholder="" name="fix_price[]" value=""></td>
    		<!--  <td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>-->
    	</tr>
    	 <tr class="">
    		<td class="text-center list_num">3</td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr" ></i></td>
    		<td class="">
        		 <div id="brandselect_3" class="fa_droplist">
					<input type="text" id="combo_brand_3" value="" />
					<input type='hidden' id='comboval_brand_3' class="td_brand" value="" name="place[]" />
				</div>
    		</td>
    		<td class="">
    			<select name='product[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($product as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='material[]' class='form-control chosen-select td_texture' onchange="textureChange(this)">
    			<option></option>
                <?php foreach($material as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='type[]' class='form-control chosen-select td_rank' onchange="rankChange(this)">
    			<option></option>
                <?php foreach($type as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_length" placeholder=""  name="length[]">
    			<input type="hidden" id="td_weight" name="weight[]" value=""> 
    		</td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]"></td>
    		<td class=""><input type="text"  readonly  style="" class="form-control td_price" placeholder=""  name="price[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>
    		<td class=""><input type="text"  style="" class="form-control td_fix_price" placeholder="" name="fix_price[]" value=""></td>
    		<!--  <td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>-->
    	</tr>
    	 <tr class="selected">
    		<td class="text-center list_num">4</td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
        		 <div id="brandselect_4" class="fa_droplist">
					<input type="text" id="combo_brand_4" value="" />
					<input type='hidden' id='comboval_brand_4' class="td_brand" value="" name="place[]" />
				</div>
    		</td>
    		<td class="">
    			<select name='product[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($product as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='material[]' class='form-control chosen-select td_texture' onchange="textureChange(this)">
    			<option></option>
                <?php foreach($material as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='type[]' class='form-control chosen-select td_rank' onchange="rankChange(this)">
    			<option></option>
                <?php foreach($type as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_length" placeholder=""  name="length[]">
    			<input type="hidden" id="td_weight" name="weight[]" value=""> 
    		</td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]"></td>
    		<td class=""><input type="text"  readonly  style="" class="form-control td_price" placeholder=""  name="price[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>
    		<td class=""><input type="text"  style="" class="form-control td_fix_price" placeholder="" name="fix_price[]" value=""></td>
    		<!--  <td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>-->
    	</tr>
    	 <tr class="">
    		<td class="text-center list_num">5</td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
        		 <div id="brandselect_5" class="fa_droplist">
					<input type="text" id="combo_brand_5" value="" />
					<input type='hidden' id='comboval_brand_5' class="td_brand" value="" name="place[]" />
				</div>
    		</td>
    		<td class="">
    			<select name='product[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($product as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='material[]' class='form-control chosen-select td_texture' onchange="textureChange(this)">
    			<option></option>
                <?php foreach($material as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<select name='type[]' class='form-control chosen-select td_rank' onchange="rankChange(this)">
    			<option></option>
                <?php foreach($type as $k=>$v){?>
                	<option value='<?php echo $k;?>'><?php echo $v;?></option>
                <?php }?>
        		</select>
    		</td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_length" placeholder=""  name="length[]">
    			<input type="hidden" id="td_weight" name="weight[]" value=""> 
    		</td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]"></td>
    		<td class=""><input type="text" readonly   style="" class="form-control td_price" placeholder=""  name="price[]"></td>
    		<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>
    		<td class=""><input type="text"  style="" class="form-control td_fix_price" placeholder="" name="fix_price[]" value=""></td>
    		<!--  <td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>-->
    	</tr>
    </tbody>
   <tfoot>
   		<tr class="tablefoot">
			<td class="text-center" style="width:4%;" colspan=2>合计：</td>
			<td style="width:13%;"></td>
			<td style="width:8%;"></td>
			<td style="width:9%;"></td>
			<td style="width:8%;"></td>
			<td style="width:5%;"></td>
			<td style="width:7%;"><span class="total_amount">0</span></td>
			<td style="width:8%;"><span class="total_weight">0</span></td>
			<td style="width:8%;"></td>
			<td style="width:9%;"><span class="total_price">0</span></td>
			<td style="width:7%;"></td>
			<td style="width:7%;"></td>
		</tr>
   </tfoot>
  </table>
</div>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm save_sub save" data-dismiss="modal" id="save_sub">保存提交</button>
	<button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo Yii::app()->createUrl('frmSales/index',array("view"=>$_COOKIE["view"]))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>

<script type="text/javascript">
var brand = <?php echo $brands?>;
	var sales = 0;
	var droplist_num = 1;
	var salestype = $("#salestype").val();
	if(salestype == "dxxs"){
		sales = 1;
	}
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
			getStorageData(1,sales);
			refreshTable("storagetable",60,0);
	})
	//每页显示发生变化
	$(document).on("change","#each_page",function(){
		var limit=$(this).val();
		getStorageData(1,sales);
		refreshTable("storagetable",60,0);
	})
	//页码点击事件
	$(document).on("click",".sauger_page_a",function(){
		var page = $(this).attr("page");
		getStorageData(page,sales);
		refreshTable("storagetable",60,0);
	})
	//复选框点击事件
	$(document).on("click",".checkit",function(){
		var card_no = $(this).val();
		if($(this).attr("checked")){
			setSalesDetial(card_no);
		}else{
			deleteSalesDetail(card_no);
		}
	})

	//删除销售单明细
	function deleteSalesDetail(card_no){
		$(".td_num").each(function(){
			var num = $(this).val();
			if(num == card_no){
				$(this).parent().parent().remove();
			}
		});
		refreshTableStyle();
		setTotalamount();
		setTotalweight();
		setTotalprice();
	}

	
	$("#add_list").click(function(){
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
		'<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>'+
		//'<td class="text-center"><input type="text"  style="" class="form-control td_num" placeholder="" name="card_id[]"></td>'+
		'<td class="">'+
		'<div id="brandselect'+droplist_num+'" class="fa_droplist">'+
			'<input type="text" id="combo_brand'+droplist_num+'" value="" />'+
			'<input type="hidden" id="comboval_brand'+droplist_num+'" class="td_brand" value="" name="place[]" />'+
		'</div>'+
		'</td>'+
		'<td class="">'+
			'<select name="product[]" class="form-control chosen-select td_product" onchange="productChange(this)">'+
			'<option></option>'+
			<?php foreach($product as $k=>$v){?>
            	'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
            <?php }?>
    		'</select>'+
		'</td>'+
		'<td class="">'+
		'<select name="material[]" class="form-control chosen-select td_texture" onchange="textureChange(this)">'+
		'<option></option>'+
		<?php foreach($material as $k=>$v){?>
    		'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
    	<?php }?>
		'</select>'+
		'</td>'+
		'<td class="">'+
			'<select name="type[]" class="form-control chosen-select td_rank" onchange="rankChange(this)">'+
			'<option></option>'+
			<?php foreach($type as $k=>$v){?>
        	'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
        	<?php }?>
    		'</select>'+
		'</td>'+
		'<td class=""><input type="text"  style="" class="form-control td_length" placeholder="" name="length[]">'+
			'<input type="hidden" id="td_weight" name="weight[]" value=""> </td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]"></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]"></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_money" placeholder="" name="money[]"></td>'+
    		'<td class=""><input type="text" readonly  style="" class="form-control td_price" placeholder=""  name="price[]"></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_fix_price" name="fix_price[]" value=""></td>'+
		//'<td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>'+
	'</tr>';
		$("#cght_tb tbody").append(newRow);
		$("#tr_num").val(count);
		$('#combo_brand'+droplist_num).combobox(brand, {},"brandselect"+droplist_num,"comboval_brand"+droplist_num,false,'brandChange(this)');
		droplist_num++;
	});

	$(".deleted_tr").live("click",function(){
		$(this).parent().parent().remove();
		var card_no = $(this).parent().parent().find(".td_num").val();
		$(".checkit").each(function(){
			var num = $(this).val();
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

	//保存
$(".save").click(function(){
		if($(this).hasClass("save_sub")){
			$("#submit_type").val(1);
		}else{
			$("#submit_type").val(0);
		}
		var str='';
		var gys = $("#comboval").val();
		if(gys==''){confirmDialog("请选择客户！");return false;}
		var cgsh = $("#cuscomboval").val();
		if(cgsh==''){confirmDialog("请选择结算单位！");return false;}
		var contact = $("#contact_id").val();
		if(contact=='' || contact==null){confirmDialog("请选择联系人！");return false;}
		var cggs = $("#combval").val();
		if(cggs==''){confirmDialog("请选择公司！");return false;}
		var warehouse =  $("#combo3").val();
		if(warehouse==''){confirmDialog("请选择仓库！");return false;}
		var start_time = $(".start_time").val();
		if(start_time==''){confirmDialog("请选择开单日期！");return false;}
		var traver = $("#traver").val();
		var result = checkTravel(traver);
		if(result != 1){confirmDialog(result);return false;}
		var supply = $("#combovalsu").val();
		if(supply==''){confirmDialog("请选择供应商！");return false;}
// 		var remark = $(".tit_remark").val();
// 		if(remark==''){confirmDialog("请输入备注！");return false;}
		var is_submit = true;
		var num = 0;
		var is_gk = false;
		var has_num = 0;
		$("#cght_tb tr").each(function(){
			if($(this).hasClass("tablefoot")){return true;}
			num++;
			if(num > 1){
				var place = $(this).find(".td_brand").text();
				var list_num = $(this).find(".list_num").text();
				var td_length = $(this).find(".td_length").val();
				var td_num = $(this).find(".td_shop_num").val();
				var td_price = $(this).find(".td_money").val();
				var gaok = $(this).find(".td_gaok").val();
				var brand=$(this).find(".td_brand").val();
				var product=$(this).find(".td_product").val();
				var texture=$(this).find(".td_texture").val();
				var rank=$(this).find(".td_rank").val();
				var fix_price=$(this).find(".td_fix_price").val();
				if(place=='' && td_length=='' && td_num=='' && td_price==''){
					return true;
				}
				if(gaok > 0 ){
					is_gk = true;
				}
				has_num ++;
				if(brand==''){confirmDialog("请选择编号为"+list_num+"的产地");is_submit=false;return false;}
				if(product==''){confirmDialog("请选择编号为"+list_num+"的品名");is_submit=false;return false;}
				if(texture==''){confirmDialog("请选择编号为"+list_num+"的材质");is_submit=false;return false;}
				if(rank==''){confirmDialog("请选择编号为"+list_num+"的规格");is_submit=false;return false;}	
				//if(td_length == ''){confirmDialog("请输入编号为"+list_num+"的长度");is_submit = false;return false;}
				if(td_num == ''){confirmDialog("请输入编号为"+list_num+"的件销售数");is_submit = false;return false;}
				if(td_price == ''){confirmDialog("请输入编号为"+list_num+"的单价");is_submit = false;return false;}
				if(gaok == ''){confirmDialog("请输入编号为"+list_num+"的高开价");is_submit = false;return false;}
				if(fix_price == ''){confirmDialog("请输入编号为"+list_num+"的采购价");is_submit = false;return false;}
			}
		})
		if(has_num >4){confirmDialog("销售单最多只能有4条明细");return false;}
		if(num > 1 && has_num > 0){
			if(is_submit){
				if(is_gk == true){
					var gkcomboval = $("#gkcomboval").val();
					if(gkcomboval==''){confirmDialog("您输入了高开金额，请选择采购员");return false;}
				}
				if(can_submit){
					can_submit = false;
					setSubmitStatus();
					$("#form_data").submit();
				}
			}
		}else{
			confirmDialog("请输入销售单明细");return false;
		}
	})
</script>
<script>
// 	//长度发生变化
 	$(document).on("change",".td_length",function(){
 		$(this).parent().parent().find('.td_shop_num').val('');
		$(this).parent().parent().find('.td_shop_total').val('');
		$(this).parent().parent().find('.td_money').val('');
		$(this).parent().parent().find('.td_price').val('');
		$(this).parent().parent().find('.td_gaok').val('0');
		//获取产品信息
		var that = $(this);
 		var length = $(this).val();
		if(length == ''){return false;}
		if(!/^[0-9]*$/.test(length))
		{
			confirmDialog('长度必须为大于等于0的整数');
			return false;
		}
 	});
	//销售件数发生变化
	$(document).on('change','.td_shop_num',function(){
			var that=$(this);
			var td_num=$(this).val();
			var total;
			var td_money=Number(numChange($(this).parent().parent().find('.td_money').val()));
			var gaokai=Number($(this).parent().parent().find('.td_gaok').val());
			if(td_num==''){return false;}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须是大于0的整数');
				return false;
			}
			
			var product=$(this).parent().parent().find('.td_product').val();
			var rank=$(this).parent().parent().find('.td_rank').val();
			var texture=$(this).parent().parent().find('.td_texture').val();
			var brand=$(this).parent().parent().find('.td_brand').val();
			var length=$(this).parent().parent().find('.td_length').val();
			if(brand == ''){
				confirmDialog('请选择产地');
				that.val('');
				return false;
			}
			//获取件重
			$.get('<?php echo Yii::app()->createUrl('contract/getUnitWeight')?>',{
					'product':product,
					'rank':rank,
					'texture':texture,
					'brand':brand,
					'length':length
		    		},function(data){
		    			if(data===false)
		    			{
			    			confirmDialog('没有找到商品，请重新选择');
			    			that.val('');
			    			return;
		    			}
		    			unit_weight=data;	    			
		    			that.parent().parent().find("#td_weight").val(unit_weight);
						//后续销售件数，销售金额等信息非空时同步发生改变
						var amount = (unit_weight*td_num).toFixed(3);
						that.parent().parent().find('.td_shop_total').val(amount);
						//后续销售金额信息非空时同时发生改变
						if(td_money > 0 ){
							that.parent().parent().find('.td_price').val(fmoney(td_money*amount,2));
						}
						if(td_money == 0){
							$.post('<?php echo Yii::app()->createUrl('quotedDetail/getGuideprice')?>',
									{"product_std":product,"rand_std":rank,
										"texture_std":texture,"brand_std":brand,"length":length
									},function(data){
										if(data != "false"){
											that.parent().parent().find('.td_money').val(data);
											var onePrice = Number(numChange(data));
											that.parent().parent().find('.td_price').val(fmoney((onePrice + gaokai)*amount,2));
										}
									})
						}
						setTotalamount();
						setTotalweight();
						setTotalprice();
		    	});
			
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
	$(function(){
		changeOwnerTT();
		var brand = <?php echo $brands?$brands:"[]"?>;
		var array=<?php echo $vendors?$vendors:'[]';?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		var array4=<?php echo $teams?$teams:'[]';?>;
		var gkvendor=<?php echo $gkvendor?$gkvendor:'[]';?>;
		var warehouse=<?php echo $warehouses?$warehouses:'[]';?>;
		var supply=<?php echo $supply?$supply:"[]";?>;
		$('#combo').combobox(array, {},"wareselect","comboval",false,'changeCont()');
		$('#cuscombo').combobox(array, {},"cusselect","cuscomboval");
		$('#combo2').combobox(coms, {},"ttselect","combval");
		$('#combo4').combobox(array4, {},"ywyselect","comboval4",false,'changeTeamU()');
		$('#combo_brand').combobox(brand, {},"brandselect","comboval_brand",false,'brandChange(this)');
		$('#combo_brand_2').combobox(brand, {},"brandselect_2","comboval_brand_2",false,'brandChange(this)');
		$('#combo_brand_3').combobox(brand, {},"brandselect_3","comboval_brand_3",false,'brandChange(this)');
		$('#combo_brand_4').combobox(brand, {},"brandselect_4","comboval_brand_4",false,'brandChange(this)');
		$('#combo_brand_5').combobox(brand, {},"brandselect_5","comboval_brand_5",false,'brandChange(this)');
		$('#gkcombo').combobox(gkvendor, {},"gkselect","gkcomboval",false);
		$('#combo3').combobox(warehouse, {},"warehouseselect","comboval3","","","",false);
		$('#combosu').combobox(supply, {},"supplyselect","combovalsu");
	})
	function brandChange(obj)
	{
		var brand=$(obj).attr('param');
		brand_name=$(obj).text();
		var product=$(obj).parent().parent().parent().parent().find('.td_product').val();
		var texture=$(obj).parent().parent().parent().parent().find('.td_texture').val();
		var rank=$(obj).parent().parent().parent().parent().find('.td_rank').val();
		$.post('/index.php/dictGoodsProperty/propertySelect',{
			'type':'brand',
			'id':brand,
			'product':product,
			'texture':texture,
			'rank':rank,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1@o'));
				var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
				var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
				var data4=data.substring(data.indexOf('o3@o')+4);
				$(obj).parent().parent().parent().parent().find('.td_product').html(data1);
				$(obj).parent().parent().parent().parent().find('.td_texture').html(data2);
				$(obj).parent().parent().parent().parent().find('.td_rank').html(data3);
				//$(obj).parent().parent().parent().parent().find('.td_length').val(data4);
				if(product!='')$(obj).parent().parent().parent().parent().find('.td_product').val(product);
				if(texture!='')$(obj).parent().parent().parent().parent().find('.td_texture').val(texture);
				if(rank!='')$(obj).parent().parent().parent().parent().find('.td_rank').val(rank);
		});
		$(obj).parent().parent().parent().parent().find('.td_shop_num').val('');
		$(obj).parent().parent().parent().parent().find('.td_shop_total').val('');
		$(obj).parent().parent().parent().parent().find('.td_money').val('');
		$(obj).parent().parent().parent().parent().find('.td_price').val('');
		$(obj).parent().parent().parent().parent().find('.td_fix_price').val('');
		$(obj).parent().parent().parent().parent().find('.td_gaok').val('');
		setTotalamount();
		setTotalweight();
		setTotalprice();
	}

	function productChange(obj)
	{
		var product=$(obj).val();
		var brand=$(obj).parent().parent().find('.td_brand').val();
		if(brand)
		{
			brand_name=getBrandName(brand);
		}
		var texture=$(obj).parent().parent().find('.td_texture').val();
		var rank=$(obj).parent().parent().find('.td_rank').val();
		$.post('/index.php/dictGoodsProperty/propertySelect',{
			'type':'product',
			'id':product,
			'brand':brand,
			'texture':texture,
			'rank':rank,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1@o'));
				var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
				var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
				var data4=data.substring(data.indexOf('o3@o')+4);
				$(obj).parent().parent().find('.td_texture').html(data2);
				$(obj).parent().parent().find('.td_rank').html(data3);
				//$(obj).parent().parent().find('.td_length').val(data4);
				var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
				var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
				var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
				var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:145px;margin-right:-23px;">'+
				'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
				'<input type="hidden" id="'+val_id+'" value=""  name="place[]" class="td_brand" />'+
				'</div>';
				$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
				$('#'+combo_id).combobox(data1, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(this)','',true);
				if(brand!=''){
					$(obj).parent().parent().find('.td_brand').val(brand);
					$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
				}				
				if(texture!='')$(obj).parent().parent().find('.td_texture').val(texture);
				if(rank!='')$(obj).parent().parent().find('.td_rank').val(rank);
		});
		$(obj).parent().parent().find('.td_shop_num').val('');
		$(obj).parent().parent().find('.td_shop_total').val('');
		$(obj).parent().parent().find('.td_money').val('');
		$(obj).parent().parent().find('.td_price').val('');
		$(obj).parent().parent().find('.td_fix_price').val('');
		$(obj).parent().parent().find('.td_gaok').val('');
		setTotalamount();
		setTotalweight();
		setTotalprice();
	}

	function textureChange(obj)
	{
		var texture=$(obj).val();
		var brand=$(obj).parent().parent().find('.td_brand').val();
		if(brand)
		{
			brand_name=getBrandName(brand);
		}
		var product=$(obj).parent().parent().find('.td_product').val();
		var rank=$(obj).parent().parent().find('.td_rank').val();
		$.post('/index.php/dictGoodsProperty/propertySelect',{
			'type':'texture',
			'id':texture,
			'brand':brand,
			'product':product,
			'rank':rank,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1@o'));
				var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
				var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
				var data4=data.substring(data.indexOf('o3@o')+4);
				$(obj).parent().parent().find('.td_product').html(data2);
				$(obj).parent().parent().find('.td_rank').html(data3);
				//$(obj).parent().parent().find('.td_length').val(data4);
				var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
				var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
				var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
				var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:145px;margin-right:-23px;">'+
				'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
				'<input type="hidden" id="'+val_id+'" value=""  name="place[]" class="td_brand" />'+
				'</div>';
				$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
				$('#'+combo_id).combobox(data1, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(this)','',true);
				if(brand!=''){
					$(obj).parent().parent().find('.td_brand').val(brand);
					$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
				}				
				if(product!='')$(obj).parent().parent().find('.td_product').val(product);
				if(rank!='')$(obj).parent().parent().find('.td_rank').val(rank);
		});
		$(obj).parent().parent().find('.td_shop_num').val('');
		$(obj).parent().parent().find('.td_shop_total').val('');
		$(obj).parent().parent().find('.td_money').val('');
		$(obj).parent().parent().find('.td_price').val('');
		$(obj).parent().parent().find('.td_fix_price').val('');
		$(obj).parent().parent().find('.td_gaok').val('');
		setTotalamount();
		setTotalweight();
		setTotalprice();
	}

	function rankChange(obj)
	{
		var rank=$(obj).val();
		var brand=$(obj).parent().parent().find('.td_brand').val();
		if(brand)
		{
			brand_name=getBrandName(brand);
		}
		var texture=$(obj).parent().parent().find('.td_texture').val();
		var product=$(obj).parent().parent().find('.td_product').val();
		$.post('/index.php/dictGoodsProperty/propertySelect',{
			'type':'rank',
			'id':rank,
			'brand':brand,
			'texture':texture,
			'product':product,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1@o'));
				var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
				var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
				var data4=data.substring(data.indexOf('o3@o')+4);
				$(obj).parent().parent().find('.td_product').html(data2);
				$(obj).parent().parent().find('.td_texture').html(data3);
				$(obj).parent().parent().find('.td_length').val(data4);
				var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
				var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
				var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
				var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:145px;margin-right:-23px;">'+
				'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
				'<input type="hidden" id="'+val_id+'" value=""  name="place[]" class="td_brand" />'+
				'</div>';
				$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
				$('#'+combo_id).combobox(data1, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(this)','',true);
				if(brand!=''){
					$(obj).parent().parent().find('.td_brand').val(brand);
					$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
				}				
				if(texture!='')$(obj).parent().parent().find('.td_texture').val(texture);
				if(product!='')$(obj).parent().parent().find('.td_product').val(product);
		});
		$(obj).parent().parent().find('.td_shop_num').val('');
		$(obj).parent().parent().find('.td_shop_total').val('');
		$(obj).parent().parent().find('.td_money').val('');
		$(obj).parent().parent().find('.td_price').val('');
		$(obj).parent().parent().find('.td_fix_price').val('');
		$(obj).parent().parent().find('.td_gaok').val('');
		setTotalamount();
		setTotalweight();
		setTotalprice();
	}
	function getBrandName(brand_id)
	{
		var result='';
		$.ajaxSetup({async:false});
		$.get('/index.php/dictGoodsProperty/getProName',{
			'id':brand_id,			
		},function(data){
			result=data;
		});
		return result;
	}
	</script>