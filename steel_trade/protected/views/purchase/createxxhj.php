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
<script type="text/javascript">
var array=<?php echo $vendors;?>;
var array2=<?php echo $coms;?>;
var array3=<?php echo $warehouses;?>;		
var array5=<?php echo $vendors;?>;
var array6=<?php echo $vens?$vens:json_encode(array());?>;
var array_brand=<?php echo $brands?$brands:json_encode(array());?>;
</script>
<div class="shop_select_box">
	<div class="shop_more_one">
			<input type="hidden" name="FrmPurchase[frm_contract_id]" value=""  id="frmsales_id">
			<input type="hidden" name="FrmPurchase[purchase_type]" value="xxhj"/>
			<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
			<div id="venselect" class="fa_droplist">
				<input type="text" id="combo" value="" />
				<input type='hidden' id='comboval'  value=""  name="FrmPurchase[supply_id]"/>
			</div>
		</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" value="" />
			<input type='hidden' id='comboval2' value=""  name="FrmPurchase[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购日期：</div>
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]"  value="<?php echo date('Y-m-d',time());?>" class="form-control form-date date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
<!-- 	<div class="shop_more_one"> -->
<!-- 		<div class="shop_more_one_l"><span class="bitian">*</span>开票成本：</div> --
		<input type="text"  name="FrmPurchase[invoice_cost]" class="form-control  invoice_cost" value="<?php echo $invoice_cost?>"  placeholder=""  ><span class="danwei">元/吨</span>
<!-- 	</div> -->
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchase[contact_id]"  class='form-control chosen-select se_ywz' id="contact_id">
				<option value=""></option>
				<?php //if(!empty($contacts)){foreach($contacts as $k=>$v){?>
	            	<!--  <option value='<?php echo $k;?>'><?php echo $v;?></option>-->
	            <?php //}}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            	<option <?php echo $k==Yii::app()->user->userid?'selected="selected"':''?>  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		<select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	     </select>
		<input type="hidden" name="FrmPurchase[team_id]"  value="" >
<!-- 		<div id="teamselect" class="fa_droplist"> -->
<!-- 			<input type="text" id="combo4" value="" /> -->
<!-- 			<input type='hidden' id='comboval4'  value=""  name="FrmPurchase[team_id]"/> -->
<!-- 		</div> -->
	</div>
	<div class="shop_more_one"> 
 		<div class="shop_more_one_l">预计到货日期：</div> 
		<div class="search_date_box" style="margin-top:0px;background-position:155px 8px;">
			<input type="text"  name="FrmPurchase[date_reach]" id="date_reach" class="form-control  date  input_backimg"  placeholder="选择日期"  >
 		</div> 
 	</div> 
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone"  class="form-control con_tel" placeholder=""  >
	</div>
 	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]" id="transfer_number" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入库仓库：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo3" value="" />
			<input type='hidden' id='comboval3'  value="" class="wareinput"  name="FrmPurchase[warehouse_id]"/>
		</div>
	</div>
	<?php if($comm_id){?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">销售单号：</div>
		<input type="text" readonly  name="" value="<?php echo $comm_sn;?>" class="form-control " placeholder=""  >
	</div>
	<?php }?>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one ">
		<div class="shop_more_one_l"><span class="bitian">*</span>运费单价：</div>
		<input type="text"  name="FrmPurchase[shipment]" id="shipment" style="width:145px;height:33px;" class="form-control " placeholder=""  >
		<span class="danwei">元/吨</span>
	</div>
	<div class="shop_more_one" > 
		<input class="check_box l" style="margin-left:90px;" type="checkbox" name="FrmPurchase[is_yidan]" value="1" /><div class="lab_check_box">乙单</div>
		<?php if(false&&$type!='dxcg'){?>
		<input class="check_box l" type="checkbox" name="FrmPurchase[is_tp]" id="is_tp" value="1" /><div class="lab_check_box">托盘</div>
		<?php }?>
		<input  type="hidden" name="FrmPurchase[contain_cash]" id="" value="1" />	
	</div>	
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>规格</th>         		         		
         		<th class="text-center" style="width:9%;">长度</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>单价</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>金额</th>
         		<th class="text-center" style="width:5%;"><span class="bitian">*</span>开票成本</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">

    </tbody>
    <tfoot>
   		<tr class="tablefoot">
			<td class="text-center"  colspan=2>合计：</td>
			<td style=""></td>
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
<!-- 
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
 -->
 <div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal" style="background:#426ebb;" id="submit_btn1">保存提交</button> 
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="" id="submit_btn">保存</button>
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
</div>
<?php $this->endWidget()?>

<?php if(!$comm_id){?>
<div style="clear: both;">
 <div class="search_line"></div>
 <div class="search_title">选择先销后进销售单</div>
<form method="post" action="">
<div class="search_body search_background">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入销售单号" id="search_keywords" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" id="search_begin" placeholder="开始日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" id="search_end" placeholder="结束日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="shop_more_one1">
		<div style="float:left;">销售公司：</div>
		<div id="saleselect" class="fa_droplist">
			<input type="text" id="combo5" class="forreset" value="<?php echo DictCompany::getName($search['title_id'])?>" />
			<input type='hidden' id="comboval5" class="forreset" value="<?php echo $search['title_id']?>" name="search[title_id]" />
		</div>
	</div>
	<div class="select_body" style="position:relative">
	<div class="more_select_box" style="top:40px;left:-220px;width:500px;">
		<div class="more_one">
		<div class="shop_more_one">
			<div class="shop_more_one_l" style="width: 90px;">客户：</div>
			<div id="cusselect" class="fa_droplist">
				<input type="text" id="combo6" class="forreset" value="<?php echo DictCompany::getName($search['customer_id'])?>"/>
				<input type='hidden' id='comboval6' class="forreset" value="<?php echo $search['customer_id']?>" name="search[customer_id]" />
			</div>
		</div>
		</div>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class="form-control chosen-select forreset" id="owned_by">
	         <option value='0' selected='selected'>-全部-</option>
	         <?php  if(!empty($users)){foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }}?>
	      </select>
	</div>
	</div>
	</div>
	<input type="button" class="btn btn-primary btn-sm btn_sub search_btn" data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
</div>
<div style="clear: both;" id="sales_list">
</div>
<?php }?>
<script type="text/javascript">
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
$('#cancel').click(function(){
	window.history.back(-1);
});
	$(".deleted_tr").live("click",function(){
		var row_num=0;
		$(this).parent().parent().remove();
		$("#cght_tb tbody tr").each(function(){
			row_num++;
			$(this).find(".list_num").html(row_num);
			if(row_num%2 == 0){
				$(this).addClass("selected");
			}else{
				$(this).removeClass("selected");
			}
		});
		$("#tr_num").val(row_num);
		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();

		//销售列表的选择与否
		if(row_num==0)
		{
			selected_sales=0;
			$('#sales_list').find('input[type=radio]').each(function(){
				$(this).removeAttr('checked');					
			})
		}
	})
var  can_submit = true;
$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var contact=$('#contact_id').val();
		var invoice_cost=$('.invoice_cost').val();
		var date_reach=$('#date_reach').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(contact==''||!contact){confirmDialog("请选择输入联系人！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}
		if(invoice_cost==''||/^\s+$/g.test(invoice_cost)){confirmDialog("请选择输入开票成本！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
// 			$('#date_reach').focus();
			return false;
		}	
		var shipment=$('#shipment').val();
		if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数须为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价须为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
	
	$("#submit_btn1").click(function(){
		if(!can_submit){return false;}
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var contact=$('#contact_id').val();
		var invoice_cost=$('.invoice_cost').val();
		var date_reach=$('#date_reach').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(contact==''||!contact){confirmDialog("请选择输入联系人！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}
		if(invoice_cost==''||/^\s+$/g.test(invoice_cost)){confirmDialog("请选择输入开票成本！");return false;}
		var datenow=CurrentTime();
		if(date_reach!=''&&date_reach<datenow)
		{
			confirmDialog('预计到货日期须大于当前日期');
// 			$('#date_reach').focus();
			return false;
		}	
		var shipment=$('#shipment').val();
		if(shipment==''||!/^[0-9]+(.[0-9]{1,3})?$/.test(shipment)){confirmDialog('运费单价需为数字');$('#shipment').addClass('red-border');return false;}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数须为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价须为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
		})
		if(!flag){return false;}
		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
		$(this).parent().append(str);
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn1');
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
	    changeOwnerT();
		function changeTitle()
		{
			var title_name=$('#combo2').val();
			var title_id=$('#comboval2').val();
			$('#combo5').val(title_name);
			$('#comboval5').val(title_id);
			$('.search_btn').trigger('click');
		}
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });

    	  //件数改变
	    $.ajaxSetup({ async: false });
	    var unit_weight=0;
		$(document).on('change','.td_num',function(){
			//)$('.td_num').change(function(){			
			var that=$(this);
			var td_num=$(this).val();
			var td_max_num=$(this).next().val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须为大于0的整数');
				$(this).val();
				$(this).focus();
				return;
			}
			if(parseInt(td_num)>parseInt(td_max_num))
			{
				confirmDialog('采购件数不能大于销售单未补单件数'+td_max_num);
				$(this).val('');
				$(this).focus();
				return;
			}
			//获取件重			
			var product=$(this).parent().parent().find('.td_product').val();
			var rank=$(this).parent().parent().find('.td_rank').val();
			var texture=$(this).parent().parent().find('.td_texture').val();
			var brand=$(this).parent().parent().find('.td_brand').val();
			var length=$(this).parent().parent().find('.td_length').val();
			$.get('/index.php/contract/getUnitWeight',{
				'product':product,
				'rank':rank,
				'texture':texture,
				'brand':brand,
				'length':length,
	    		},function(data){
	    			if(data===false||data==='')
	    			{
		    			confirmDialog('根据品名/规格/材质/产地未找到对应商品，请重新选择');
		    			that.val('');
		    			return;
	    			}
	    			unit_weight=data;	    	
	    			if(!/^[1-9][0-9]*$/.test(td_num))
	    			{
	    				confirmDialog('件数必须为大于0的整数');
	    				return;
	    			}	
	    			if(unit_weight!=0)
	    			{
	    				that.parent().parent().find('.td_total_weight').val(td_num*unit_weight);
		    			that.parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));
	    			}	    			
	    	});				
			updateTotalAmount();
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();			
		});

		//重量改变
		$(document).on('change','.td_weight',function(){
			//改变金额
			var td_weight=$(this).val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
			{						
				confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
				return;
			}
			$(this).next().val(td_weight);
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;								
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();			
		});
		
	    //单价改变
		$(document).on('change','.td_price',function(){
			var td_price=numChange($(this).val());
			var td_num=$(this).parent().parent().find('.td_num').val();		
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();		
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price))
			{						
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				$(this).parent().parent().find('.td_money').val('');
				updateTotalMoney();
				return;
			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;		
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();
		});	    

/****------------------------------销售列表有关》》---------------*****/
 	var selected_sales='<?php echo $comm_id;?>';
	$('.reset').click(function(){
	    $('.forreset').val('');		    
	});
	$(function(){
			if(selected_sales)
			{
				//获取主体信息
				$.get('/index.php/frmSales/getXxhjMainData',{
					'id':selected_sales,
				},function(data){
					var json=eval('('+data+')');
					$('#combo2').val(json.title_name);
					$('#comboval2').val(json.title_id);
					$('#team_id').val(json.team_id);
					$('#CommonForms_owned_by').val(json.owned_by);
					$('#combo3').val(json.warehouse);
					$('#comboval3').val(json.warehouse_id);
				});
				$.get('/index.php/frmSales/getXxhjDetail',{
					'id':selected_sales,
				},function(data){
					$('.forinsert').html(data);
					$('#frmsales_id').val(selected_sales);
				});
			}else{
				$.get('/index.php/frmSales/getSimpleList',{
					'type':'xxhj',
					},function(data){
						$('#sales_list').html(data);
					});
			}
			
		});
	$('.search_btn').click(function(){
		var keywords=$('#search_keywords').val();
		var time_L=$('#search_begin').val();
		var time_H=$('#search_end').val();
		var title_id=$('#comboval5').val();
		var customer_id=$('#comboval6').val();
		var owned_by=$('#owned_by').val();
		var url="/index.php/frmSales/getSimpleList?&page=1";
		$.get(url,{
			'type':'xxhj',
			'keywords':keywords ,
			'time_L':time_L ,
			'time_H':time_H ,
			'title_id':title_id , //销售公司
			'customer_id':customer_id ,//采购公司
			'owned_by':owned_by ,
		},function(data){
			$('#sales_list').html(data);
			$('#sales_list').find('input[type=radio]').each(function(){
				var id=$(this).val();
				if(id==selected_sales)
				{
					$(this).attr('checked','checked');					
				}
			})
		});
	});
	
	//换页获取数据
	$(document).on('click','.sauger_page_a',function(e){
		e.preventDefault();
		var keywords=$('#search_keywords').val();
		var time_L=$('#search_begin').val();
		var time_H=$('#search_end').val();
		var title_id=$('#comboval5').val();
		var customer_id=$('#comboval6').val();
		var owned_by=$('#owned_by').val();
		var url=$(this).attr('href');
		$.get(url,{
			'type':'xxhj',
			'keywords':keywords ,
			'time_L':time_L ,
			'time_H':time_H ,
			'title_id':title_id , //销售公司
			'customer_id':customer_id ,//采购公司
			'owned_by':owned_by ,
		},function(data){
			$('#sales_list').html(data);
			$('#sales_list').find('input[type=radio]').each(function(){
				var id=$(this).val();
				if(id==selected_sales)
				{
					$(this).attr('checked','checked');					
				}
			})
		});
	});
	  $(document).on('change','#each_page',function(){
		  	limit=$(this).val();
		  	$.post("/index.php/site/writeCookie", {
		  		'name' : "sales_list",
		  		'limit':limit
		  	}, function(data) {
		  		if(data){
		  			var keywords=$('#search_keywords').val();
		  			var time_L=$('#search_begin').val();
		  			var time_H=$('#search_end').val();
		  			var title_id=$('#comboval5').val();
		  			var customer_id=$('#comboval6').val();
		  			var owned_by=$('#owned_by').val();
		  			var url=$('.firstpage').attr('href');
		  			$.get(url,{
		  				'type':'xxhj',
		  				'keywords':keywords ,
		  				'time_L':time_L ,
		  				'time_H':time_H ,
		  				'title_id':title_id , //销售公司
		  				'customer_id':customer_id ,//采购公司
		  				'owned_by':owned_by ,
		  			},function(data){
		  				$('#sales_list').html(data);
		  				$('#sales_list').find('input[type=radio]').each(function(){
							var id=$(this).val();
							if(id==selected_sales)
							{
								$(this).attr('checked','checked');					
							}
						})
		  			});
		  		}
		  	});			
		  });
	  $(document).on('change','.paginate_sel',function(){
		    var url = $(this).val();
		    var keywords=$('#search_keywords').val();
			var time_L=$('#search_begin').val();
			var time_H=$('#search_end').val();
			var title_id=$('#comboval5').val();
			var customer_id=$('#comboval6').val();
			var owned_by=$('#owned_by').val();
			$.get(url,{
				'type':'xxhj',
				'keywords':keywords ,
				'time_L':time_L ,
				'time_H':time_H ,
				'title_id':title_id , //销售公司
				'customer_id':customer_id ,//采购公司
				'owned_by':owned_by ,
			},function(data){
				$('#sales_list').html(data);
				$('#sales_list').find('input[type=radio]').each(function(){
					var id=$(this).val();
					if(id==selected_sales)
					{
						$(this).attr('checked','checked');					
					}
				})
			});
		});	
	//选择，填充数据
	$(document).on('click','.selected_sales',function(){
			selected_sales= $(this).val();
			//获取主体信息
			$.get('/index.php/frmSales/getXxhjMainData',{
				'id':selected_sales,
			},function(data){
				var json=eval('('+data+')');
				$('#combo2').val(json.title_name);
				$('#comboval2').val(json.title_id);
// 				$('#contact_id').val(json.contact_id);
				$('#team_id').val(json.team_id);
				$('#CommonForms_owned_by').val(json.owned_by);
// 				$('#phone').val(json.mobile);
				$('#combo3').val(json.warehouse);
				$('#comboval3').val(json.warehouse_id);
			});
			$.get('/index.php/frmSales/getXxhjDetail',{
				'id':selected_sales,
			},function(data){
				$('.forinsert').html(data);
				$('#frmsales_id').val(selected_sales);
				initSet('new');
			});
		});
	$(document).on('click','#datatable-datatable1 .datatable-rows .flexarea .datatable-wrapper table tr',function(){
		var a=$(this).index();
		var input=$('#datatable-datatable1 .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
		selected_sales= $(input).val();
		$(input).trigger('click');		
	});
	/******-----------------《《销售列表有关------------------*******/
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/changeFunction.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script>
	$(function(){
		
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval2",false,'changeTitle()');
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");		
		$('#combo5').combobox(array5, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"saleselect","comboval5");
		$('#combo6').combobox(array6, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","comboval6");
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);		
	})
	</script>