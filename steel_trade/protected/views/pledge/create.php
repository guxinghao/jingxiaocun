<?php
$form = $this->beginWidget ('CActiveForm', array('enableAjaxValidation'=>true,'htmlOptions'=>array('id' =>'form_data' ,'enctype'=>'multipart/form-data',)));
?>
<script type="text/javascript">
</script>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l supply_div">托盘公司：</div>
		 <select name="" id="pledge_company" disabled class='form-control chosen-select se_yw'>
		 	<option></option>
			 <?php if(!empty($tpcompanys)){foreach($tpcompanys as $k=>$v){?>
			   	<option value='<?php echo $k;?>'><?php echo $v;?></option>
			  <?php }}?>
	   	 </select>
		<input type="hidden" name="FrmPledgeRedeem[company_id]" class="pledge_company">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l supply_div">采购公司：</div>
		<select name="" id="title_id" disabled class='form-control chosen-select se_yw'>
		 	<option></option>
			 <?php if(!empty($titles)){foreach($titles as $k=>$v){?>
			   	<option value='<?php echo $k;?>'><?php echo $v;?></option>
			  <?php }}?>
	   	 </select>
	   	 <input type="hidden" name="FrmPledgeRedeem[title_id]" class="title_id">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购单：</div>
		<input type="text"  readonly id="form_sn"  name="" class="form-control " placeholder=""  >
		<input type="hidden" name="FrmPledgeRedeem[purchase_id]" value="">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">托盘赎回等级：</div>
		<input type="text"  name="" readonly class="form-control " id="r_limit_name" placeholder="" >
		<input type="hidden" name="" id="r_limit" value="">
		<input type="hidden" name="FrmPledgeRedeem[pledge_info_id]" value="">
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">托盘金额：</div>
		<input type="text"  name="" readonly id="tp_money" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">托盘价格：</div>
		<input type="text"  name="" readonly id="tp_price" class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">托盘预付款：</div>
		<input type="text"  name="" readonly id="tp_prepay" class="form-control tit_remark" placeholder=""  >
	</div>	
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="" class='form-control chosen-select se_yw'>
	            <?php if(!empty($users)){foreach($users as $k=>$v){?>
	            <option <?php echo $baseform?($baseform->owned_by==$k?'selected="selected"':''):(Yii::app()->user->userid==$k?'selected="selected"':'');?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>赎回日期：</div>
		<div class="search_date_box">
			<input type="text"  name="CommonForms[form_time]" id="date_now" value="<?php echo date('Y-m-d',time());?>" class="form-control form-date date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" class="form-control tit_remark" placeholder=""  >
	</div>
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:9%;">品名</th>
         		<th class="text-center" style="width:9%;">托盘重量</th>
         		<th class="text-center" style="width:6%;">未赎回重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>赎回重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>赎回金额</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>赎回利息</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	<tr >
    		<td class="">
    		<select name="FrmPledgeRedeem[brand_id]" class="form-control td_brand" >
    		</select>
    		</td>
    		<td class="">
    		<select name="FrmPledgeRedeem[product_id]" class="form-control td_product">
    		</select>
    		</td>
    		<td class=""><input type="text"  name="FrmPledgeRedeem[total_weight]" readonly style="" class="form-control td_tpweight" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  readonly name="" class="form-control td_unpay_weight" placeholder=""  >
    		</td>
    		<td class=""><input type="text"  name="FrmPledgeRedeem[weight]"  class="form-control td_weight" placeholder=""  ></td>
    		<td class=""><input type="text"  name="FrmPledgeRedeem[total_fee]"   class="form-control td_money" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name="FrmPledgeRedeem[interest_fee]" readonly style="" class="form-control td_interest_fee" placeholder=""  >
    			<input type="hidden" name="FrmPledgeRedeem[buy_price]" value="" class="buy_price" >
    		</td>    			
    	</tr>
    </tbody>
  </table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm " data-dismiss="modal"  id="submit_btn1">保存提交</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
</div>
<?php $this->endWidget()?>
<div style="clear: both;">
 <div class="search_line"></div>
 <div class="search_title">选择托盘采购单</div>
<form method="post" action="">
<div class="search_body search_background">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入采购单号" id="search_keywords" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
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
		<div style="float:left;">采购公司：</div>
		
		 <select name="search[title_id]" id="comboval5"  class='form-control chosen-select forreset'>
		 	<option value="0">全部</option>
			 <?php foreach($titles as $k=>$v){?>
			   	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
			  <?php }?>
	   	 </select>
<!-- 		<div id="saleselect" class="fa_droplist"> --
			<input type="text" id="combo5" class="forreset" value="<?php echo DictCompany::getName($search['title_id'])?>" />
			<input type='hidden' id="comboval5" class="forreset" value="<?php echo $search['title_id']?>" name="search[title_id]" />
<!-- 		</div> -->
	</div>
	<div class="select_body" style="position:relative">
	<div class="more_select_box" style="top:40px;left:-220px;width:500px;">
		<div class="more_one">
		<div class="shop_more_one">
			<div class="shop_more_one_l" style="width: 90px;">托盘公司：</div>
			 <select name="search[customer_id]" id="comboval6"  class='form-control chosen-select forreset'>
		 	<option value="0">全部</option>
			 <?php if(!empty($tpcompanys)){foreach($tpcompanys as $k=>$v){?>
			   	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
			  <?php }}?>
	   	 </select>
<!-- 			<div id="cusselect" class="fa_droplist"> --
				<input type="text" id="combo6" class="forreset" value="<?php echo DictCompany::getName($search['_id'])?>"/>
				<input type='hidden' id='comboval6' class="forreset" value="<?php echo $search['customer_id']?>" name="search[customer_id]" />
<!-- 			</div> -->
		</div>
		</div>
		<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class="form-control chosen-select forreset" id="search_owned_by">
	         <option value='0' selected='selected'>-全部-</option>
	         <?php if(!empty($users)){foreach ($users as $k=>$v){?>
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
<script type="text/javascript">
$('#cancel').click(function(){
	window.history.back(-1);
});
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
$(document).on("blur","select",function(){
	$(this).removeClass("red-border");
});
var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
	if(!selected_sales)
	{
		confirmDialog('请选择托盘采购单');
		return false;
	}else{
		//检查采购单是否符合要求
			var result=checkApprove(selected_sales,'pledge');
			if(!result)
			{
				confirmDialog('您选择的采购信息已变更，请重新选择');
				return false;
			}
	}
	var form_time=$('#date_now').val();
	if(!form_time){
		confirmDialog("请选择赎回日期");
		return false;
	}
	var td_weight = $(".td_weight").val();
	var td_money=numChange($(".td_money").val());
	if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为整数或6位小数点的小数");$('.td_weight').addClass('red-border');return false;}
	if(td_money==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_money)||td_money==0){confirmDialog('金额需为大于0的整数或2位小数点内的小数');$('.td_money').addClass('red-border');return false;}
	if(can_submit){
        can_submit = false;
        // setTimeout(function(){can_submit = true;},3000);
        notAnymore('submit_btn');
        $("#form_data").submit();
    }
})
$("#submit_btn1").click(function(){
	if(!can_submit){return false;}
	if(!selected_sales)
	{
		confirmDialog('请选择托盘采购单');
		return false;
	}else{
		//检查采购单是否符合要求
		var result=checkApprove(selected_sales,'pledge');
		if(!result)
		{
			confirmDialog('您选择的采购信息已变更，请重新选择');
			return false;
		}
}
	var form_time=$('#date_now').val();
	if(!form_time){
		confirmDialog("请选择赎回日期");
		return false;
	}
	var td_weight = $(".td_weight").val();
	var td_money=numChange($(".td_money").val());
	if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为整数或6位小数点的小数");$('.td_weight').addClass('red-border');return false;}
	if(td_money==''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_money)||td_money==0){confirmDialog('金额需为大于0的整数或2位小数点内的小数');$('.td_money').addClass('red-border');return false;}
	var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
	$(this).parent().append(str);
	if(can_submit){
        can_submit = false;
        // setTimeout(function(){can_submit = true;},3000);
        notAnymore('submit_btn1');
        $("#form_data").submit();
    }
})

$('.td_weight').change(function(){
	var td_weight=$(this).val();
	if(td_weight)
	{
		td_weight=numChange(td_weight);
	}
	if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
	{						
		confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
		return;
	}
	var td_unpay_weight=$('.td_unpay_weight').val();
	if(td_unpay_weight==''){return ;}else{
		td_unpay_weight=numChange(td_unpay_weight);
	}
	if(parseFloat(td_weight)>parseFloat(td_unpay_weight))
	{
		confirmDialog('赎回重量不可大于未赎回重量');
		$('.td_weight').val('');		 
		return false;
	}
	var tp_price=$('#tp_price').val();
	if(tp_price=='')return ;
	$('.td_money').val(formatNum((tp_price*td_weight).toString()));
	$('.td_interest_fee').val(0);
});
$('.td_money').change(function(){
	if($(this).val())
	{
		var td_money=numChange($(this).val());
	}else{
		var td_money=$(this).val();
	}	
	if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_money)||td_money==0)
	{
		if(td_money=='')return;								
		confirmDialog('金额必须是大于0的整数或小数点后2位的小数');
		return;
	}
	var td_tpweight=$('.td_tpweight').val();
	var buy_price=$('.buy_price').val();
	var td_weight=$('.td_weight').val();
	if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
	{						
		confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
		return;
	}
// 	var interest=td_money-buy_price*(td_weight/td_tpweight);
// 	$('.td_interest_fee').val(formatNum(interest.toString()));
	var tp_price=$('#tp_price').val();
	if(tp_price=='')return ;
	var interest=td_money-tp_price*td_weight;
	if(interest<0){
		confirmDialog('金额必须大于等于托盘单价乘以赎回重量');
		$(this).val('');
		return ;
	}
	$('.td_interest_fee').val(formatNum((interest).toFixed(2).toString()));
});

/**----------------托盘采购列表》》-------------***/
 var selected_sales='<?php echo $_REQUEST['purchase_id']?>';
$(function(){
	$('.reset').click(function(){
	    $('.forreset').val('');		    
	});
	$.get('/index.php/purchase/getTpcgList',{
		},function(data){
			$('#sales_list').html(data);
			if(selected_sales!='')
			{
				$('#sales_list').find('input[type=radio]').each(function(){
					var id = $(this).val();
					if(id==selected_sales)
					{
						$(this).trigger('click');
						$(this).attr('checked','checked');	
					}
				});
			}
				
		});
});

$('.search_btn').click(function(){
	var keywords=$('#search_keywords').val();
	var time_L=$('#search_begin').val();
	var time_H=$('#search_end').val();
	var title_id=$('#comboval5').val();
	var customer_id=$('#comboval6').val();
	var owned_by=$('#search_owned_by').val();
	var url="/index.php/purchase/getTpcgList?&page=1";
	$.get(url,{
		'keywords':keywords ,
		'time_L':time_L ,
		'time_H':time_H ,
		'title_id':title_id , //销售公司
		'customer_id':customer_id ,//采购公司
		'owned_by':owned_by ,
	},function(data){
		$('#sales_list').html(data);
		$('#sales_list').find('input[type=radio]').each(function(){
			var id = $(this).val();
			if(id==selected_sales)
			{
				$(this).attr('checked','checked');					
			}
		});
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
	var owned_by=$('#search_owned_by').val();
	var url=$(this).attr('href');
	$.get(url,{
		'keywords':keywords ,
		'time_L':time_L ,
		'time_H':time_H ,
		'title_id':title_id , //销售公司
		'customer_id':customer_id ,//采购公司
		'owned_by':owned_by ,
	},function(data){
		$('#sales_list').html(data);
		$('#sales_list').find('input[type=radio]').each(function(){
			var id = $(this).val();
			if(id==selected_sales&&selected_sales!='')
			{
				$(this).attr('checked','checked');					
			}
		});
	});
});
  $(document).on('change','#each_page',function(){
	  	limit=$(this).val();
	  	$.post("/index.php/site/writeCookie", {
	  		'name' : "purchase_list",
	  		'limit':limit
	  	}, function(data) {
	  		if(data){
	  			var keywords=$('#search_keywords').val();
	  			var time_L=$('#search_begin').val();
	  			var time_H=$('#search_end').val();
	  			var title_id=$('#comboval5').val();
	  			var customer_id=$('#comboval6').val();
	  			var owned_by=$('#search_owned_by').val();
	  			var url=$('.firstpage').attr('href');
	  			$.get(url,{
	  				'keywords':keywords ,
	  				'time_L':time_L ,
	  				'time_H':time_H ,
	  				'title_id':title_id , //销售公司
	  				'customer_id':customer_id ,//采购公司
	  				'owned_by':owned_by ,
	  			},function(data){
	  				$('#sales_list').html(data);
	  				$('#sales_list').find('input[type=radio]').each(function(){
						var id = $(this).val();
						if(id==selected_sales&&selected_sales!='')
						{
							$(this).attr('checked','checked');					
						}
					});
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
		var owned_by=$('#search_owned_by').val();
		$.get(url,{
			'keywords':keywords ,
			'time_L':time_L ,
			'time_H':time_H ,
			'title_id':title_id , //销售公司
			'customer_id':customer_id ,//采购公司
			'owned_by':owned_by ,
		},function(data){
			$('#sales_list').html(data);
			$('#sales_list').find('input[type=radio]').each(function(){
				var id = $(this).val();
				if(id==selected_sales&&selected_sales!='')
				{
					$(this).attr('checked','checked');					
				}
			});
		});
	});	
//选择，填充数据
$(document).on('click','.selected_sales',function(){
		selected_sales= $(this).val();
		$.ajaxSetup({async:false});
		$.get('/index.php/purchase/getMainInfo',{
			'id':selected_sales,
		},function(data){
			var json=eval('('+data+')');
			$('#title_id').val(json.title);
			$('.title_id').val(json.title);
			$('#pledge_company').val(json.pledge_company_id);
			$('.pledge_company').val(json.pledge_company_id);
			$('#form_sn').val(json.form_sn);
			$('#form_sn').next().val(json.purchase_id);
			$('#r_limit').val(json.r_limit);
			$('#r_limit').next().val(json.pledge_info_id);
			$('#r_limit_name').val(json.r_limit_name);
			$('#tp_money').val(json.fee);
			$('#tp_price').val(json.unit_price);
			$('#tp_prepay').val(json.advance);
			$('.td_brand').html(json.brand_str);

			//是否初始化品名
			if(json.r_limit=='2')
			{
				$('#cght_tb thead> tr').children().eq(1).html('<span class="bitian">*</span>品名');
				$('.td_product').removeAttr('disabled');
				initProduct();				
			}else{
				$('#cght_tb thead> tr').children().eq(1).html('品名');
				$('.td_product').html('');
				$('.td_product').attr('disabled','disabled');
			}
			fillPledge();
		});
	});
$(document).on('click','#datatable-datatable1 .datatable-rows .flexarea .datatable-wrapper table tr',function(){
	var a=$(this).index();
	var input=$('#datatable-datatable1 .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
	selected_sales= $(input).val();
	$(input).trigger('click');		
});

function initProduct()
{
	var brand_id=$('.td_brand').val();
	$.ajaxSetup({async:false});
	$.get('/index.php/purchase/getProductL',{
		'brand_id':brand_id,
		'form_id':selected_sales,
	},function(data){
		$('.td_product').html(data);
	})
}
var tp_weight;
function fillPledge()
{
	var brand_id=$('.td_brand').val();
	var product_id=$('.td_product').val();
	$.get('/index.php/purchase/getPledgeInfo',{
		'brand_id':brand_id,
		'product_id':product_id,
		'form_id':selected_sales,
	},function(data){
		var json=eval('('+data+')');
		tp_weight=json.weight;
		$('.td_tpweight').val(json.weight);
		$('.buy_price').val(json.fee);
		$('.td_unpay_weight').val(json.unweight);
		$('.td_weight').val('');
		$('.td_money').val('');
		$('.td_interest_fee').val('');
	});
}
//根据产地获取后面的信息
$('.td_brand').change(function(){
	var r_limit=$('#r_limit').val();
	if(r_limit=='2')initProduct();
	fillPledge();
});
$('.td_product').change(function(){
	fillPledge();
});
</script>

