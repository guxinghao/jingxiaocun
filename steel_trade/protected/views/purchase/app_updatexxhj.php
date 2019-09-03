<?php
$type= $_GET['type'];
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script type="text/javascript">
var array_brand=<?php echo $brands;?>;
</script>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="shop_select_box">
	<div class="shop_more_one">
	<input type="hidden" name="FrmPurchase[frm_contract_id]" value="<?php echo $purchase->frm_contract_id?>"  id="frmsales_id">
	<input type="hidden" name="FrmPurchase[purchase_type]" value="xxhj"/>
			<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
			<div id="" class="fa_droplist">
				<input type="text" id="" readonly value="<?php echo $purchase->supply->short_name?>" class="form-control"/>
				<input type='hidden' id='comboval'  value="<?php echo $purchase->supply_id?>"  name="FrmPurchase[supply_id]"/>
			</div>
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="" class="fa_droplist">
			<input type="text" id="" readonly value="<?php echo $purchase->title->short_name?>" class="form-control"/>
			<input type='hidden' id='comboval2' value="<?php echo $purchase->title_id;?>"  name="FrmPurchase[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购日期：</div>
			<input type="text"  name="CommonForms[form_time]" readonly value="<?php echo $baseform->form_time?>" class="form-control  " placeholder="选择日期"  >
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" readonly value="<?php //echo $purchase->invoice_cost;?>"class="form-control " placeholder=""  ><span class="danwei">元/吨</span>
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]" class='form-control chosen-select se_ywz' id="contact_id" disabled>
				<?php //foreach($contacts as $k=>$v){?>
	            	<!--  -><option <?php echo $purchase->contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>-->
	            <?php //}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" disabled onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            	<option <?php echo $baseform->owned_by==$k?'selected="selected"':"";?>  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	       <input type="hidden" name="CommonForms[owned_by]" value="<?php echo $baseform->owned_by?>">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		<select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option <?php echo $purchase->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	      </select>			
	      <input type="hidden" name="FrmPurchase[team_id]"   value="<?php echo $purchase->team_id?>">
<!-- 		<div id="teamselect" class="fa_droplist"> --
			<input type="text" id="combo4" value="<?php  //echo $purchase->team->name?>" />
			<input type='hidden' id='comboval4'  value="<?php //echo $purchase->team_id?>"  name="FrmPurchase[team_id]"/>
<!-- 		</div> -->
	</div>
	
		<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计到货日期：</div> 
			<input type="text" name="FrmPurchase[date_reach]" value="<?php echo ($purchase->date_reach>943891200)?date('Y-m-d',$purchase->date_reach):'';?>" class="form-control  date input_backimg" id="date_reach" placeholder="选择日期"  >
 	</div> 
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="<?php echo $purchase->contact->mobile;?>" class="form-control con_tel" placeholder=""  >
	</div>
 	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]" readonly value="<?php echo $purchase->transfer_number?>" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入库仓库：</div>
		<div id="" class="fa_droplist">
			<input type="text" id="" readonly value="<?php echo $purchase->warehouse->name;?>"  class="form-control"/>
			<input type='hidden' id='comboval3'  value="<?php echo $purchase->warehouse_id?>" class="wareinput" name="FrmPurchase[warehouse_id]"/>
		</div>
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]"  value="<?php echo $baseform->comment;?>" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one " >
		<div class="shop_more_one_l"><span class="bitian">*</span>运费单价：</div>
		<input type="text" readonly name="FrmPurchase[shipment]" value="<?php echo $purchase->shipment?>" id="shipment"  class="form-control " placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one" >
		<input class="check_box l" style="margin-left:90px;" type="checkbox"  disabled <?php echo $purchase->is_yidan==1?'checked="checked"':''?> name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<?php if(false&&$type!='dxcg'){?>
		<input class="check_box l" type="checkbox"  disabled <?php echo $purchase->purchase_type=="tpcg"?'checked="checked"':''?>  name="FrmPurchase[is_tp]" id="is_tp" value="1" /><div class="lab_check_box">托盘</div>
		<?php }?>
		<input class="" type="hidden"  name="FrmPurchase[contain_cash]" value="1" />
	</div>	
				
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="<?php echo count($details)?>">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>         		
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>规格</th>         		         		
         		<th class="text-center" style="width:5%;">长度</th>
    
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>单价</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>金额</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>开票成本</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	 <?php $i=1; foreach ($details as $each){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">
    		
    		<td class="text-center list_num"><?php echo $i;?></td>    		
    		<td class="">
    		<div id="<?php echo "bbbrandselect".$i;?>" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">
					<input type="text" readonly id="<?php echo "bbcombobrand".$i?>" style="width:130px;"  value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" class="form-control"/>
					<input type='hidden' id='<?php echo "bbcombovalbrand".$i?>' value="<?php echo $each->brand_id?>"   name="old_td_brands[]" class="td_brand"/>
				</div>
	    		<script type="text/javascript">
	    		//$('#bbcombobrand<?php echo $i?>').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"bbbrandselect<?php echo $i?>","bbcombovalbrand<?php echo $i?>",false);
	    		</script>	
    		</td>
    		<td class="">
    			<input type="hidden" name="old_td_id[]" value="<?php echo $each->id;?>" />
    			<select name='old_td_products[]' class='form-control chosen-select td_product' disabled>
    			<?php foreach($products as $k=>$v){?>
	            	<option <?php echo $each->product_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="old_td_textures[]" class='form-control chosen-select td_texture' disabled>
	            <?php foreach($textures as $k=>$v){?>
	            	<option <?php echo $each->texture_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="old_td_ranks[]" class='form-control chosen-select td_rank' disabled>
	            <?php foreach($ranks as $k=>$v){?>
	            	<option <?php echo $each->rank_id==$k?'selected="seleted"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>    		    		
    		<td class=""><input type="text" readonly name="old_td_length[]" style="" value="<?php echo $each->length;?>" class="form-control td_length" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name="old_td_amount[]" readonly style="" value="<?php echo $each->amount;?>" class="form-control td_num" placeholder=""  >
    			<input type="hidden"  name="" style="" value="<?php echo $each->salesDetail->saledetailCont->need_purchase_amount;?>" class="form-control td_max_num" >
    		</td>
    		<td class="">
    			<input type="text"  name="" readonly value="<?php echo round($each->weight,3);?>" style="" class="form-control  td_weight" placeholder=""  >
    			<input type="hidden"  name="old_td_weight[]"  value="<?php echo $each->weight;?>" style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class=""><input type="text"  readonly name="old_td_price[]" style="" value="<?php echo round($each->price,2);?>" class="form-control td_price" placeholder=""  ></td>
    		<td class=""><input type="text"  readonly name="old_td_totalMoney[]" value="<?php echo number_format($each->weight*$each->price,2);?>" style="" class="form-control td_money" placeholder=""  ></td>
    		<td><input type="text" readonly name="old_td_invoice[]" class="form-control td_invoice" value="<?php echo $each->invoice_price;?>"></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
     <tfoot>
   		<tr class="tablefoot">
			<td class="text-center"  colspan=2>合计：</td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td style=""></td>
			<td style=""><span class="tf_total_amount">0</span></td>
			<td style=""><span class="tf_total_weight">0</span></td>
			<td style=""></td>
			<td style=""><span class="tf_total_money">0</span></td>
			<td></td>
		</tr>
   </tfoot>
  </table>
</div>

 <div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('purchase/index')?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>

<script type="text/javascript">
var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
	var flag=true;
	//判断更新时间
	var lastupdate='<?php echo $_REQUEST['last_update']?>';
	var id='<?php echo $_REQUEST['id']?>';
	var fpage='<?php echo $_REQUEST['fpage']?>';
	var type='<?php echo $_REQUEST['type']?>';
	var url='/index.php/purchase/update/'+id+'?fpage='+fpage+'&&type='+type;
	$.ajaxSetup({async:false});
	$.get('/index.php/commonForms/lastUpdate/'+id,{
		'time':lastupdate,
	},function(data){
		if(data==='error')
		{
			confirmDialog('获取信息失败，请稍后再试');
		}else 	if(data!=='pass')
		{
			confirmDialog('您看到的信息不是最新的，请刷新后再试');
// 			setTimeout('',2300);
// 			window.location.href=url+'&&last_update='+data;
			flag=false;
			return false;
		}
	});		
	if(!flag)return false;		
		var str='';
		var gys = $("#comboval").val();
		var date_reach=$('#date_reach').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
			$('#date_reach').focus();
			return false;
		}	
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
	
	$('#contain_ship').click(function(){
		var check=$(this).attr('checked');
		if(check)
		{
			$('#ship').show();
		}else{
			$('#ship').hide();
		}
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
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });  
		//托盘
		$(function(){
			var check=$('#is_tp').attr('checked');
			if(check)
			{
				$('.tp_hidden').show();
			}else{
				$('.tp_hidden').hide();
			}
			changeCont();
			updateTotalAmount();
			updateTotalWeight();
			updateTotalMoney();		
		});  
	</script>
