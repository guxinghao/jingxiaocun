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
<!-- <div class="con_tit_1">修改销售单</div> -->
<input type="hidden" value="<?php echo $baseform->last_update;?>" name="CommonForms[last_update]">
<input type="hidden" value="<?php echo $sales->sales_type;?>" name="FrmSales[sales_type]" id="salestype"/>
<input type="hidden" value="0" name="submit_type" id="submit_type"/>
<input type='hidden' id='owner_company_id' value="" name="FrmSales[owner_company_id]" />
<input type="hidden" value="<?php if(checkOperation("销售单价为零")){echo 1;}else{echo 0;}?>"  id="can_zero"/>
<div class="shop_select_box">
<?php if($baseform->form_status == "unsubmit") {?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combo2" value="<?php echo $sales->dictTitle->short_name;?>" />
			<input type='hidden' id='combval' value="<?php echo $sales->title_id;?>" name="FrmSales[title_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $sales->client->name;?>" />
			<input type='hidden' id='comboval' value="<?php echo $sales->client_id;?>" name="FrmSales[client_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
			<select name='FrmSales[company_contact_id]' class='form-control chosen-select se_yw' id="contact_id">
			<?php foreach($contacts as $k=>$v){?>
	            	<option <?php echo $sales->company_contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	      	</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" id="phone" class="form-control con_tel" value="<?php echo $sales->contact->mobile;?>" readonly>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>仓库：</div>
		<div id="warehouseselect" class="fa_droplist">
			<input type="text" id="combo3" value="<?php echo $sales->warehouse->name;?>" />
			<input type='hidden' id='comboval3' value="<?php echo $sales->warehouse_id;?>" class="wareinput" name="FrmSales[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结算单位：</div>
		<div id="cusselect" class="fa_droplist">
			<input type="text" id="cuscombo" value="<?php echo $sales->dictCompany->name;?>" />
			<input type='hidden' id='cuscomboval' value="<?php echo $sales->customer_id;?>" name="FrmSales[customer_id]" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开单日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" class="form-control form-date date start_time input_backimg" placeholder="选择日期" value="<?php echo $baseform->form_time;?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">预计提货日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="FrmSales[date_extract]" class="form-control form-date date fs_date input_backimg"  placeholder="选择日期" value="<?php echo $sales->date_extract>0?date("Y-m-d",$sales->date_extract):"";?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name='CommonForms[owned_by]' class='form-control chosen-select se_yw' id="CommonForms_owned_by" onchange="changeOwnerTT()">
	        <?php foreach($users as $k=>$val){?>
	           <option <?php echo $baseform->owned_by==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $val;?></option>
	        <?php }?>
	      </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<input type="hidden" value="<?php echo $sales->team_id;?>" name="FrmSales[team_id]" id="team_idnum"/>		
		<select id="team_id" disabled class='form-control chosen-select se_yw'>
	 		<option selected="selected" value=''></option>
            <?php foreach($teams as $k=>$v){?>
            	<option <?php echo $sales->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
            <?php }?>
       </select>	
	</div>
	<div class="shop_more_one" id="gk_kh">
		<div class="shop_more_one_l">采购员：</div>
		<div id="gkselect" class="fa_droplist">
			<input type="text" id="gkcombo" value="<?php if($sales->has_bonus_price){echo $sales->highopenone->company->short_name;}?>"/>
			<input type='hidden' id='gkcomboval' value="<?php if($sales->has_bonus_price){echo $sales->highopenone->target_id;}?>" name="gk_id" />
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_checkbox">
			 <label class='radio-inline'> <input class="check_box" type="checkbox" name="FrmSales[is_yidan]" <?php echo $sales->is_yidan?"checked":"";?> value="1"> 乙单 </label>
		</div> 
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text" class="form-control con_tel" name="FrmSales[travel]" value="<?php echo $sales->travel;?>" id="traver" placeholder="多个用空格或逗号隔开">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  class="form-control tit_remark" name="FrmSales[comment]" value="<?php echo $sales->comment;?>">
	</div>
	<?php }else{?>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<input type="text" readonly class="form-control con_tel" value="<?php echo $sales->dictTitle->short_name;?>" />
		<input type='hidden' id='combval' value="<?php echo $sales->title_id;?>" name="FrmSales[title_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>客户：</div>
		<input type="text" readonly class="form-control con_tel" value="<?php echo $sales->client->name;?>" />
		<input type='hidden' value="<?php echo $sales->client_id;?>" name="FrmSales[client_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
			<select name='FrmSales[company_contact_id]' class='form-control chosen-select se_yw' id="contact_id">
			<?php foreach($contacts as $k=>$v){?>
	            	<option <?php echo $sales->company_contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	      	</select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" id="phone" class="form-control con_tel" value="<?php echo $sales->contact->mobile;?>" readonly>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">仓库：</div>
		<input type="text" class="form-control con_tel" readonly value="<?php echo $sales->warehouse->name;?>">
		<input type='hidden' id='comboval3' value="<?php echo $sales->warehouse_id;?>"  name="FrmSales[warehouse_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>结算单位：</div>
		<input type="text" readonly class="form-control con_tel" value="<?php echo $sales->dictCompany->name;?>" />
		<input type='hidden' value="<?php echo $sales->customer_id;?>" name="FrmSales[customer_id]" />
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>开单日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" class="form-control form-date start_time" placeholder="选择日期"  <?php echo $super?'':"readonly"?> value="<?php echo $baseform->form_time;?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">预计提货日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="FrmSales[date_extract]" class="form-control form-date date fs_date input_backimg"  placeholder="选择日期" value="<?php echo $sales->date_extract>0?date("Y-m-d",$sales->date_extract):"";?>">
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		<input type="text" class="form-control con_tel" readonly value="<?php echo $baseform->belong->nickname;?>">
		<input type='hidden' value="<?php echo $baseform->owned_by;?>"  name="CommonForms[owned_by]"/>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		<input type="text" class="form-control con_tel" value="<?php echo $sales->team->name;?>" readonly/>
		<input type='hidden' value="<?php echo $sales->team_id;?>"  name="FrmSales[team_id]"/>
	</div>
	<div class="shop_more_one" id="gk_kh">
		<div class="shop_more_one_l">采购员：</div>
		<?php if($super){?>
		<div id="gkselect" class="fa_droplist">
			<input type="text" id="gkcombo" value="<?php if($sales->has_bonus_price){echo $sales->highopenone->company->name;}?>"/>
			<input type='hidden' id='gkcomboval' value="<?php if($sales->has_bonus_price){echo $sales->highopenone->target_id;}?>" name="gk_id" />
		</div>
	<?php }else{?>
			<input type="text" class="form-control con_tel" readonly value="<?php if($sales->has_bonus_price){echo $sales->highopenone->company->name;}?>"/>
			<input type='hidden' value="<?php if($sales->has_bonus_price){echo $sales->highopenone->target_id;}?>" name="gk_id" />
	<?php }?>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_checkbox">
			 <label class='radio-inline'><input class="check_box" <?php echo $super?'':"disabled"?> type="checkbox" name="FrmSales[is_yidan]" <?php echo $sales->is_yidan?"checked":"";?> value="1"> 乙单 </label>
		</div> 
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text" class="form-control con_tel" name="FrmSales[travel]"  value="<?php echo $sales->travel;?>" id="traver" placeholder="多个用空格或逗号隔开">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  class="form-control tit_remark" name="FrmSales[comment]" value="<?php echo $sales->comment;?>">
	</div>
	<?php }?>
</div>
<div class="create_table">
	<table class="table"  id="cght_tb">
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<?php if($baseform->form_status == "unsubmit") {?>
         		<th class="text-center" style="width:5%;">操作</th>
         		<?php }?>
         		<th class="text-center" style="width:10%;">卡号</th>
         		<th class="text-center" style="width:6%;">产地</th>
         		<th class="text-center" style="width:6%;">品名</th>
         		<th class="text-center" style="width:7%;">材质</th>
         		<th class="text-center" style="width:6%;">规格</th>
         		<th class="text-center" style="width:6%;">长度</th>
         	  	<th class="text-center" style="width:7%;">可售件数</th>
         	<!--	<th class="text-center" style="width:7%;">可供重量</th>-->
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>销售件数</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>销售重量</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>销售单价</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>销售金额</th>
         		<th class="text-center" style="width:8%;"><span class="gaokai_icon"><img alt="" src="/images/gaokai.png"></span></th>
         		<!--  <th class="text-center" style="width:17%;">备注</th>-->
      		</tr>
    	</thead>
    <tbody>
    <?php 
    	$num = 1;
    	foreach($details as $dt){
    ?>
    	<tr class="">
    		<input type="hidden" name="details_id[]" value="<?php echo $dt->id;?>">
    		<input type="hidden" class="td_warehouse_id" value="<?php echo $dt->storage->warehouse_id;?>">
    		<input type="hidden" class="td_title_id" value="<?php echo $sales->title_id;?>">
    		<input type="hidden" class="td_supply_id" value="<?php echo $dt->storage->frmInputDx->supply_id;?>">
    		<td class="text-center list_num"><?php echo $num;?></td>
    		<?php if($baseform->form_status == "unsubmit") {?>
    		<td class="text-center">
    			<i class="icon icon-trash deleted_tr"></i>
    		</td>
    		<?php }?>
    		<td class="text-center">
    			<input type="text"  style="" class="form-control td_num"  value="<?php echo $dt->storage->card_no;?>" readonly>
    			<input type="hidden" name="card_id[]" class="td_card_id" value="<?php echo $dt->card_id;?>">
    		</td>
    		<td class="">
	    		<input type="text" class="form-control td_place" value="<?php echo $dt->brand;?>" num="<?php echo $dt->brand_id;?>" readonly>
	    		<input type="hidden"  name="place[]" value="<?php echo $dt->brand_id;?>">
    		</td>
    		<td class="">
	    		<input type="text" class="form-control td_product" value="<?php echo $dt->product;?>" num="<?php echo $dt->product_id;?>" readonly>
	    		<input type="hidden"  name="product[]" value="<?php echo $dt->product_id;?>">
    		</td>
    		<td class="">
	    		<input type="text" class="form-control td_material"  value="<?php echo $dt->texture;?>" num="<?php echo $dt->texture_id;?>" readonly>
	    		<input type="hidden"  name="material[]" value="<?php echo $dt->texture_id;?>">
    		</td>
    		<td class="">
	    		<input type="text" class="form-control td_type"  value="<?php echo $dt->rank;?>" num="<?php echo $dt->rank_id;?>" readonly>
	    		<input type="hidden"  name="type[]" value="<?php echo $dt->rank_id;?>">
    		</td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_length" name="length[]" value="<?php echo $dt->length;?>" readonly/>
    			<input type="hidden" class="td_weight" name="weight[]" value="">
    			<input type="hidden" class="td_surplus"  value="<?php echo $dt->can_surplus;?>"> 
    		</td>
    		<td class=""><input type="text" class="form-control" readonly value="<?php echo $dt->surplus;?>"></td>
    	<!--<td class=""><input type="text"  style="" class="form-control can_num" placeholder="" name="can_num[]" readonly></td>
    		<td class=""><input type="text"  style="" class="form-control can_total_num" placeholder="" name="can_total[]" readonly></td>-->
    		<td class=""><input type="text"  style="" class="form-control td_shop_num" name="td_num[]" value="<?php echo $dt->amount;?>" <?php if($baseform->form_status != "unsubmit") echo "readonly";?>></td>
    		<td class=""><input type="text"  style="" class="form-control td_shop_total" name="td_total[]" value="<?php echo number_format($dt->weight,3);?>" <?php if($baseform->form_status != "unsubmit") echo "readonly";?>></td>
    		<td class="">
    			<input type="text"  style="" class="form-control td_money" placeholder="" name="money[]" value="<?php echo number_format($dt->price);?>" <?php if($baseform->form_status != "unsubmit" && !$super) echo "readonly";?>>
    			<input type="hidden"  class="form-control " placeholder="" name="cost[]" value="<?php echo $dt->cost_price;?>">
    			<input type="hidden"  class="form-control " placeholder="" name="old[]" value="<?php echo $dt->price;?>">
    		</td>
    		<td class=""><input type="text"  readonly  style="" class="form-control td_price"  name="price[]" value="<?php echo number_format($dt->fee,2);?>" <?php if($baseform->form_status != "unsubmit"&& !$super) echo "readonly";?>></td>
    		<td class=""><input type="text"  style="" class="form-control td_gaok" name="gaok[]" value="<?php echo number_format($dt->bonus_price);?>" <?php if($baseform->form_status != "unsubmit" && !$super) echo "readonly";?>></td>
    		<!--  <td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>-->
    	</tr>
    	<?php 
    	$num++;
    	}
    	?>
    </tbody>
    <tfoot>
    	<tr class="tablefoot">
    		<?php if($baseform->form_status == "unsubmit") {?>
			<td class="text-center" style="width:3%;" colspan=2>合计：</td>
			<?php }else{?>
			<td class="text-center" style="width:8%;">合计：</td>
			<?php }?>
			<td style="width:10%;"></td>
			<td style="width:6%;"></td>
			<td style="width:6%;"></td>
			<td style="width:7%;"></td>
			<td style="width:6%;"></td>
			<td style="width:6%;"></td>
			<td style="width:7%;"></td>
			<td style="width:7%;"><span class="total_amount">0</span></td>
			<td style="width:8%;"><span class="total_weight">0</span></td>
			<td style="width:8%;"></td>
			<td style="width:8%;"><span class="total_price">0</span></td>
			<td style="width:8%;"></td>
		</tr>
    </tfoot>
  </table>
</div>
<input type="hidden" id="tr_num" value="<?php echo $num -1;?>">
<div class="ht_add_list" id="add_list" style="display:none;">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list">
	<?php if($baseform->form_status == "unsubmit") {?>
	<button type="button" class="btn btn-primary btn-sm save_sub save" data-dismiss="modal" id="save_sub">保存提交</button>
	<?php }?>
	<button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal" id="save">保存</button>
	<a href="<?php echo Yii::app()->createUrl('frmSales/index',array("page"=>$_GET["fpage"],"view"=>$_COOKIE["view"]));?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<div class="caigou_body" <?php if($baseform->form_status != "unsubmit") echo 'style="display:none;"';?>>
	<div class="search_line"></div>
	<div class="search_title">添加销售货物</div>
	<div class="search_body search_background" style="position:relative;">
		<div class="cg_search_one" style="display:none;">
			<div class="cg_search_one_l">卡号：</div>
			<input type="text"  class="form-control con_tel forreset" id="mcard_id">
		</div>
		<div class="select_body">
			<div class="more_one" style="margin-top:7px;width:205px;">
				<div style="float:left;">供应商：</div>
				<div id="gysselect" class="fa_droplist">
					<input type="text" id="gyscombo" value="" class="forreset"/>
					<input type='hidden' id='gyscomboval' value="" class="forreset" name="search[is_supply]"/>
				</div>
			</div>
			<div class="more_one" style="margin-top:7px;width:193px;">
				<div style="float:left;">仓库：</div>
				<div id="warehouseselect1" class="fa_droplist">
					<input type="text" id="combo31" value="" class="forreset"/>
					<input type='hidden' id='comboval31' value="" class="forreset" name="search[warehouse_id]"/>
				</div>
			</div>
			<div class="more_one" style="margin-top:7px;width:193px;">
				<div style="float:left;">产地：</div>
		    	<div id="brandselect" class="fa_droplist">
					<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
					<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
				</div>
			</div>
			<div class="select_body_one">
				<div style="float:left;">品名：</div>
				<div class='col-md-4'>
		        <select name="search[product]"  class='form-control chosen-select forreset' id="mproduct">
		            <option value='0' selected='selected'>-全部-</option>
		            <?php foreach ($product as $k=>$v){?>
		            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
		            <?php }?>
		        </select>
		        </div>
			</div>
		</div>
		<div class="more_select_box" style="top:50px;width:740px;">
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
			 <select name="search[length]" class='form-control chosen-select forreset form_status' id="mlength">
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
	<div class="" id="kucun_table">
	
	</div>
</div>
<script type="text/javascript">
$("#mproduct").change(function(){
	getDxStorageData(1);
});
$("#mtexture").change(function(){
	getDxStorageData(1);
});
$("#mrand").change(function(){
	getDxStorageData(1);
});
$("#mlength").change(function(){
	getDxStorageData(1);
});
var line = 0;
setTotalamount();
setTotalweight();
setTotalprice();
// var sales = 0;
// var salestype = $("#salestype").val();
// if(salestype == "dxxs"){
// 	sales = 1;
// }
$(function(){
	<?php if($msg){?>
	confirmDialog('<?php echo $msg?>');
	<?php }?>
	//初始化搜索条件，设置仓库为选中仓库，提交保存
	//$(".forreset").val('');
	getDxStorageData(1);
	//refreshTable("storagetable",60,0);
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
		getDxStorageData(1);
		//refreshTable("storagetable",60,0);
		//setStorageCheck();
})
//每页显示发生变化
$(document).on("change","#each_page",function(){
	limit=$(this).val();
	var url = $(this).attr('href');
	$.post("/index.php/site/writeCookie", {
	'name' : "storage_list",
	'limit':limit
	}, function(data){
		getDxStorageData(1);
		//refreshTable("storagetable",60,0);
		//setStorageCheck();	
	});
})
//页数下拉框发生变化
	$(document).on("change",".paginate_sel",function(){
		page = $(this).val();
		getDxStorageData(page);
	});
//页码点击事件
$(document).on("click",".sauger_page_a",function(){
	var page = $(this).attr("page");
	getDxStorageData(page);
	//refreshTable("storagetable",60,0);
	//setStorageCheck();
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
		var height = $(document).height() - 42;
	 	$(".bar").css("height",height+"px");
	});
	
	//复选框点击事件
	$(document).on("click",".checkit",function(e){
		e.stopPropagation();    //  阻止事件冒泡
		clickCheckbox($(this));
	})
	function clickCheckbox(dom){
		line++;
		var row_num=parseInt($("#tr_num").val());
		var card_no = dom.val();
		var cost = dom.attr("cost");
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
		var can_surplus = dom.parent().find(".can_surplus").val();
		var surplus = dom.parent().parent().find(".surplus").text();
		var weight = dom.parent().parent().find(".canweight").text();
		var warehouse_id = dom.parent().find(".warehouse_id").val();
		var warehouse_name = dom.parent().parent().find(".warehouse_name").text();
		var ware_id = $("#comboval3").val();
		var title_id = dom.parent().find(".title_id").val();
		var title_name = dom.parent().parent().find(".title_name").text();
		var supply_id = dom.parent().find(".supply_id").val();
		if(row_num == 0){
			$("#comboval3").val(warehouse_id);
			$("#combo3").val(warehouse_name);
			$("#combo2").val(title_name);
			$("#combval").val(title_id);
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
			'<td class="text-center"><i class="icon icon-trash deleted_tr" style="line-height:32px;"></i></td>'+
			'<td class="text-center"><input type="text" class="form-control td_num" readonly  value="'+card_no+'"><input type="hidden" name="card_id[]" class="td_card_id" value="'+card_id+'"></td>'+
			'<td class=""><input type="text" class="form-control td_place" value="'+brand+'" num="'+brand_std+'" readonly><input type="hidden"  name="place[]" value="'+brand_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_product" value="'+product+'" num="'+product_std+'" readonly><input type="hidden"  name="product[]" value="'+product_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_material" value="'+texture+'" num="'+texture_std+'" readonly><input type="hidden"  name="material[]" value="'+texture_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_type"  value="'+rand+'" num="'+rand_std+'" readonly><input type="hidden"  name="type[]" value="'+rand_std+'"></td>'+
			'<td class=""><input type="text" class="form-control td_length" name="length[]" value="'+length+'" readonly>'+
			'<input type="hidden" class="td_weight" name="weight[]" value="">'+
			'<input type="hidden" class="td_surplus"  value="'+can_surplus+'">'+
			'</td>'+
			'<td class=""><input type="text" class="form-control" readonly value="'+surplus+'"></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_shop_num" placeholder="" name="td_num[]" value=""></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_shop_total" placeholder=""  name="td_total[]" value=""></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_money td_p_'+line+'" placeholder="" name="money[]">'+
    		'<input type="hidden"  style="" class="form-control td_cost" placeholder="" name="cost[]" value="'+cost+'"></td>'+
    		'<td class=""><input type="text" readonly   style="" class="form-control td_price" placeholder=""  name="price[]"></td>'+
    		'<td class=""><input type="text"  style="" class="form-control td_gaok" placeholder="" name="gaok[]" value=""></td>'+
			//'<td style="border-right:none;" class=""><input type="text"  style="" class="form-control remark" placeholder="" name="remark[]"></td>'+
		'</tr>';
			$("#cght_tb tbody").append(newRow);
			$("#tr_num").val(count);
			$.post('<?php echo Yii::app()->createUrl('quotedDetail/getGuideprice')?>',
					{"product_std":product_std,"rand_std":rand_std,
						"texture_std":texture_std,"brand_std":brand_std,"length":length
					},function(data){
						if(data != "false"){
							$(".td_p_"+line).val(data);
						}
					})
			//setSalesDetial(card_no,product,rand,texture,length,brand,cost);
		}else{
			deleteSalesDetail(card_no);
		}
	}
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

//输入框失去焦点
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
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
		var remark = $(".tit_remark").val();
		//if(remark==''){confirmDialog("请输入备注！");return false;}
		var is_submit = true;
		var num = 0;
		var is_gk = false;
		var is_supply = false;
		var supply_id;
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
				var gaok = $(this).find(".td_gaok").val();
				var warehouse_id = $(this).find(".td_warehouse_id").val();
				var title_id = $(this).find(".td_title_id").val();
				var supply = $(this).find(".td_supply_id").val();
				if(num >2 && supply_id != supply){
					is_supply = true;
				}
				supply_id = supply;
				if(gaok > 0 ){
					is_gk = true;
				}
				//if(td_length == ''){confirmDialog("请输入编号为"+list_num+"的长度");return false;}
				if(td_num == ''){confirmDialog("请输入销售件数");$(this).find(".td_shop_num").addClass("red-border");is_submit = false;return false;}
				if(parseInt(td_num) > parseInt(td_surplus)){confirmDialog("销售件数大于可售件数");$(this).find(".td_shop_num").addClass("red-border");is_submit = false;return false;}
				if(td_price == ''){confirmDialog("请输入单价");$(this).find(".td_money").addClass("red-border");is_submit = false;return false;}
				if(gaok == ''){confirmDialog("请输入高开价");$(this).find(".td_gaok").addClass("red-border");is_submit = false;return false;}
				//if(title_id != cggs){confirmDialog("产品不属于"+title_name);$(this).addClass("delete_background");is_submit = false;return false;}
				if(warehouse_id != warehouse){confirmDialog("产品不在"+warehouse_name+"里");$(this).addClass("delete_background");is_submit = false;return false;}
			}
		})
		if(num >5){confirmDialog("销售单最多只能有4条明细");return false;}
		if(num >1){
			if(is_supply){
				confirmDialog("您选择的销售单明细所属公司不一致");return false;
			}else{
				$("#owner_company_id").val(supply_id);
			}
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
	//销售件数发生变化
	$(document).on('change','.td_shop_num',function(){
			var that=$(this);
			var td_num=$(this).val();
			var weight=$(this).parent().parent().find('.td_weight').val();
			var total;
			var td_money=Number(numChange($(this).parent().parent().find('.td_money').val()));
			var gaokai=Number($(this).parent().parent().find('.td_gaok').val());
			if(td_num==''){return false;}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须是大于0的整数');
				return false;
			}
			var product=$(this).parent().parent().find('.td_product').attr("num");
			var rand=$(this).parent().parent().find('.td_type').attr("num");
			var texture=$(this).parent().parent().find('.td_material').attr("num");
			var brand=$(this).parent().parent().find('.td_place').attr("num");
			var length=$(this).parent().parent().find('.td_length').val();
			
			//获取件重
			$.get('<?php echo Yii::app()->createUrl('contract/getUnitWeight')?>',{
					'product':product,
					'rank':rand,
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
		    			that.parent().parent().find(".td_weight").val(unit_weight);
						//后续销售件数，销售金额等信息非空时同步发生改变
						var amount = (unit_weight*td_num).toFixed(3);
						that.parent().parent().find('.td_shop_total').val(amount);
						//后续销售金额信息非空时同时发生改变
						if(td_money > 0){
							that.parent().parent().find('.td_price').val(fmoney(td_money*amount,2));
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
// 	//仓库点击提示
// 	$(".se_house").click(function(){
// 		var num = $(this).attr("num");
// 		if(num == 0){
// 			confirmDialog("注意：仓库改变后，所有的明细信息将会被清空！")
// 			var num = $(this).attr("num",1);
// 		}
// 	});
	//仓库发生变化
	function warehouseChange(){
		var house = Number($("#comboval3").val());
		var title = Number($("#combval").val());
		if(house > 0 && title > 0){
			//清除所有的明细信息
			$("#cght_tb tbody tr").remove();
			$("#tr_num").val(0);
			//初始化搜索条件，设置仓库为选中仓库，提交保存
			$(".forreset").val('');
			//$("#mwarehouse").val(house);
			$("#kucun_table h3").hide();
			getDxStorageData(1);
			refreshTable("storagetable",60,0);
		}else{
			$("#kucun_table table").remove();
			$("#kucun_table h3").show();
		}
	}
</script>
<script>
	$(function(){
		var brand = <?php echo $brand?$brand:"[]"?>;
		var array=<?php echo $vendors?$vendors:'[]';?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		var array4=<?php echo $teams?$teams:'[]';?>;
		var gkvendor=<?php echo $gkvendor?$gkvendor:'[]';?>;
		var warehouse=<?php echo $warehouses?$warehouses:'[]';?>;
		var gys=<?php echo $gys?$gys:"[]";?>;
		$('#combo').combobox(array, {},"wareselect","comboval",false,'changeCont()');
		$('#cuscombo').combobox(array, {},"cusselect","cuscomboval");
		$('#combo2').combobox(coms, {},"ttselect","combval");
		$('#combo4').combobox(array4, {},"ywzselect","comboval4",false,'changeTeamU()');
		$('#combo_brand').combobox(brand, {},"brandselect","comboval_brand",false,'getDxStorageData(1)');
		$('#gkcombo').combobox(gkvendor, {},"gkselect","gkcomboval",false);
		$('#combo3').combobox(warehouse, {},"warehouseselect","comboval3");
		$('#combo31').combobox(warehouse, {},"warehouseselect1","comboval31",false,'getDxStorageData(1)');
		$('#gyscombo').combobox(gys, {},"gysselect","gyscomboval",false,'getDxStorageData(1)');
	})
</script>