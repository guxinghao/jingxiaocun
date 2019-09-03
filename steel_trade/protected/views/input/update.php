<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">公司：</div>
		<input type="hidden"  name="FrmInput[input_type]" value="<?php echo $type;?>" id="input_type">
		<input type="text"  name="" id="title"  readonly value="<?php echo $purchase->title->short_name;?>" class="form-control "   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">供应商：</div>
		<input type="text"  name="" id="supply"  readonly value="<?php echo $purchase->supply->short_name;?>" class="form-control " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系人：</div>
		<input type="text"  name="" id="contact"  readonly  value="<?php echo $purchase->contact->name;?>" class="form-control " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone"  value="<?php echo $purchase->contact->mobile?>" class="form-control con_tel"  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购单：</div>
			<input type="text"  name="" id="form_sn" readonly  value="<?php echo $baseform_pur->form_sn?>"   class="form-control con_tel"   >
			<input type="hidden"  name="FrmInput[purchase_id]" id="purchase_id" value="<?php echo $baseform_pur->id;?>"   class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">采购日期：</div>
			<input type="text"  name="CommonForms[form_time]" readonly  id="form_time" value="<?php echo $baseform_pur->form_time;?>"  class="form-control  " >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入库日期：</div>
		<div class="search_date_box" >
			<input type="text"  name="FrmInput[input_date]"  value="<?php echo ($input->input_date>943891200)?date('Y-m-d',$input->input_date):date('Y-m-d',time());?>" id="input_date"  class="form-control form-date date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		<input type="text"  name="" id="team" readonly value="<?php echo $purchase->team->name;?>"   class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务员：</div>
		<input type="text"  name="" id="owned" readonly value="<?php echo $baseform->belong->nickname;?>"   class="form-control con_tel"   >
		<input type="hidden"  name="CommonForms[owned_by]" id="" value="<?php echo $baseform->owned_by;?>"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">入库仓库：</div>
		<input type="text"  name="" id="warehouse" readonly value="<?php echo $purchase->warehouse->name;?>"   class="form-control con_tel"   >
		<input type="hidden"  name="FrmInput[warehouse_id]"  class="wareinput"  id="warehouse_id" value="<?php echo $purchase->warehouse_id;?>"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">车船号：</div>
		<input type="text"  name="FrmPurchase[transfer_number]" readonly id="transfer_number" value="<?php echo $purchase->transfer_number;?>"  class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]" value="<?php echo $baseform->comment;?>"  class="form-control tit_remark" placeholder=""  >
	</div>
	<div class="shop_more_one" style="display:none">
		<div class="shop_more_one_l">创建时间：</div>
		<input type="text"  name="CommonForms[form_time]" value="<?php echo $baseform->form_time;?>"  class="form-control date create_time" placeholder="请选择日期"  >
	</div>
<!-- 	<div class="shop_more_one">  -->
<!-- 		<div class="shop_more_one_l">是否船舱：</div> --
		<input class="check_box l"  type="checkbox" <?php //echo $input->input_type=="ccrk"?'checked="checked"':''?> name="FrmInput[is_cc]" value="1" /><div class="lab_check_box">船舱入库</div>
<!-- 	</div> -->
</div>
<div class="create_table">
<input type="hidden" id="tr_num" value="1">
	<table class="table"  id="cght_tb" >
    	<thead>
     		<tr>
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:9%;">产地</th>
         		<th class="text-center" style="width:9%;">品名</th>
         		<th class="text-center" style="width:5%;">材质</th>
         		<th class="text-center" style="width:5%;">规格</th>         		         		
         		<th class="text-center" style="width:4%;">长度</th>
         		
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>卡号</th>
         		<th class="text-center" style="width:7%;">可入库件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>入库件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>入库重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>单价</th>
      		</tr>
    	</thead>
    <tbody class="forinsert" id="forinsert">
    	<?php if(!empty($details)){ $i=1;foreach ($details as $data){?>
    	<tr class="<?php echo $i%2==0?"selected":""?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
    			<input type="hidden"  name="td_brands[]" value="<?php echo $data->brand_id?>" class="form-control td_brand yes">
	    		<input type="text" readonly name="td_brands_name[]" value="<?php echo DictGoodsProperty::getProName($data->brand_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden"  value="<?php echo $data->id;?>" class="td_id" name="td_real[]">
    			<input type="hidden" name="td_id[]" value="<?php echo $data->purchase_detail_id;?>"  class="detail_id"/>
    			<input type="hidden" name="td_products[]" value="<?php echo $data->product_id?>" class="form-control td_product yes">
	    		<input type="text" readonly name="td_products_name[]" value="<?php echo DictGoodsProperty::getProName($data->product_id)?>" class="form-control">
    		</td>
    		<td class="">
    			<input type="hidden" name="td_textures[]" value="<?php echo $data->texture_id?>" class="form-control td_texture yes">
	    		<input type="text" readonly name="td_textures_name[]" value="<?php echo DictGoodsProperty::getProName($data->texture_id)?>" class="form-control">
    		</td>  
    		<td class="">
    			<input type="hidden"name="td_ranks[]" value="<?php echo $data->rank_id?>" class="form-control td_rank yes">
	    		<input type="text" readonly name="td_ranks_name[]" value="<?php echo DictGoodsProperty::getProName($data->rank_id)?>" class="form-control">
    		</td>    		  		
    		<td ><input type="text"  readonly style="" name="td_length[]" value="<?php echo $data->length;?>" class="form-control td_length"  ></td>
			<td><input type="text" name="td_card_id[]"  value="<?php echo $data->card_id?>" class="form-control td_card"></td>
			<td >
    			<input type="text" readonly name="" style="" value="<?php echo $data->purchaseDetail->amount-$data->purchaseDetail->input_amount;?>" class="form-control  " >
    		</td>
    		<td >
    			<input type="text"  name="td_amount[]" style="" value="<?php echo $data->input_amount;?>" class="form-control td_amount td_num"  >
    			<input type="hidden"  style="" value="<?php echo $data->purchaseDetail->amount-$data->purchaseDetail->input_amount;?>" class="form-control td_max_num"  >
    		</td>
    		<td >
    			<input type="text"  name=""  value="<?php echo round($data->input_weight,3);?>" style="" class="form-control td_weight" >
    			<input type="hidden"  name="td_weight[]"  value="<?php echo $data->input_weight;?>" style="" class="form-control  td_total_weight" >
    		</td>
    		<td ><input type="text"  name="td_price[]" style="" value="<?php echo $data->cost_price;?>" class="form-control td_price" ></td>
    	</tr>
    	<?php $i++;}}?>
    	
    </tbody>
  </table>
</div>
<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('input/index',array('page'=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<div style="clear: both;">
 <div class="search_line"></div>
 <div class="search_title">选择采购单</div>
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
			<div class="shop_more_one_l" style="width: 90px;">采购公司：</div>
			<div id="cusselect" class="fa_droplist">
				<input type="text" id="combo6" class="forreset" value="<?php echo DictCompany::getName($search['customer_id'])?>"/>
				<input type='hidden' id='comboval6' class="forreset" value="<?php echo $search['customer_id']?>" name="search[customer_id]" />
			</div>
		</div>
		</div>
		<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class="form-control chosen-select forreset" id="search_owned_by">
	         <option value='0' selected='selected'>-全部-</option>
	         <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
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
<script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script>
<script>

$("#submit_btn").unbind();
$("#submit_btn1").unbind();
var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
	var flag=true;
	//判断更新时间
	var lastupdate='<?php echo $_REQUEST['last_update']?>';
	var id='<?php echo $_REQUEST['id']?>';
	var fpage='<?php echo $_REQUEST['fpage']?>';
	var type='<?php echo $_REQUEST['type']?>';
	var url='/index.php/input/update/'+id+'?fpage='+fpage+'&&type='+type;
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
	var card_array=new Array();
	var str='';
	var gys = $("#comboval").val();
	var ware=$('#warehouse_id').val();
	if(gys==''){confirmDialog("请选择输入供应商！");return false;}
	var cggs = $("#comboval2").val();
	if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
	if(ware==''){confirmDialog("请选择输入仓库！");return false;}
	var detailLength=parseInt($("#cght_tb tbody tr").length);
	if(!detailLength){confirmDialog('您没有选择明细信息');return false;}
	$("#cght_tb tbody tr").each(function(){
		var list_num = $(this).find(".list_num").text();
		var td_amount = $(this).find(".td_num").val();
		var td_weight = $(this).find(".td_weight").val();
		var td_price = numChange($(this).find(".td_price").val());
		var td_card = $(this).find('.td_card').val();
		var detail_id=$(this).find('.td_id').val();
		if(td_card==''){
			confirmDialog('请输入编号为'+list_num+'的卡号');flag=false;return false;
		}else if(td_card!=undefined){
			if(card_array.indexOf(td_card)>-1)
			{
				confirmDialog('您填写的卡号之间不能重复');flag=false;return false;
			}else{
				card_array.push(td_card);
			}
			//查询仓库			
			$.ajaxSetup({async:false});
			$.get('/index.php/storage/haveOrNot',{
				'warehouse_id':ware,
				'card_no':td_card,
				'detail_id':detail_id,
				},function(data){
					if(data)
					{
						confirmDialog('仓库中已经有卡号为'+td_card+'的商品，请输入其他的卡号');
						flag= false;
						return false;
					}
				});			
		}		
		if(!flag){return false;}
		if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
		if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
		if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
	})
	if(!flag){return false;}
	if(can_submit){
        can_submit = false;
        // setTimeout(function(){can_submit = true;},3000);
        notAnymore('submit_btn');
        $("#form_data").submit();
    }
})

		/**----------------库存采购列表》》-------------***/
		var selected_sales='<?php echo $input->purchase_id;?>';
		$(function(){
			$('.reset').click(function(){
			    $('.forreset').val('');		    
			});
			$.get('/index.php/purchase/getSimpleList',{
// 				'type':'kcrk',
				},function(data){
					$('#sales_list').html(data);
					$('#sales_list').find('input').each(function(){
						var id=$(this).val();
						if(id==selected_sales)
						{
							$(this).attr('checked','checked');
						}
					});
				});
		});
		$('.search_btn').click(function(){
			var keywords=$('#search_keywords').val();
			var time_L=$('#search_begin').val();
			var time_H=$('#search_end').val();
			var title_id=$('#comboval5').val();
			var customer_id=$('#comboval6').val();
			var owned_by=$('#search_owned_by').val();
			var url="/index.php/purchase/getSimpleList?&page=1";
			$.get(url,{
				'type':'kcrk',
				'keywords':keywords ,
				'time_L':time_L ,
				'time_H':time_H ,
				'title_id':title_id , //销售公司
				'customer_id':customer_id ,//采购公司
				'owned_by':owned_by ,
			},function(data){
				$('#sales_list').html(data);
				$('#sales_list').find('input').each(function(){
					var id=$(this).val();
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
				'type':'kcrk',
				'keywords':keywords ,
				'time_L':time_L ,
				'time_H':time_H ,
				'title_id':title_id , //销售公司
				'customer_id':customer_id ,//采购公司
				'owned_by':owned_by ,
			},function(data){
				$('#sales_list').html(data);
				$('#sales_list').find('input').each(function(){
					var id=$(this).val();
					if(id==selected_sales)
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
			  				'type':'kcrk',
			  				'keywords':keywords ,
			  				'time_L':time_L ,
			  				'time_H':time_H ,
			  				'title_id':title_id , //销售公司
			  				'customer_id':customer_id ,//采购公司
			  				'owned_by':owned_by ,
			  			},function(data){
			  				$('#sales_list').html(data);
			  				$('#sales_list').find('input').each(function(){
								var id=$(this).val();
								if(id==selected_sales)
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
					'type':'xxhj',
					'keywords':keywords ,
					'time_L':time_L ,
					'time_H':time_H ,
					'title_id':title_id , //销售公司
					'customer_id':customer_id ,//采购公司
					'owned_by':owned_by ,
				},function(data){
					$('#sales_list').html(data);
					$('#sales_list').find('input').each(function(){
						var id=$(this).val();
						if(id==selected_sales)
						{
							$(this).attr('checked','checked');
						}
					});
				});
			});	
		//选择，填充数据
		$(document).on('click','.selected_sales',function(){
				selected_sales= $(this).val();
				$.get('/index.php/purchase/getMainInfo',{
					'id':selected_sales,
				},function(data){
					var json=eval('('+data+')');
					$('#title').val(json.title_name);
					$('#supply').val(json.supply_name);
					$('#contact').val(json.contact_name);
					$('#phone').val(json.mobile);
					$('#form_sn').val(json.form_sn);
					$('#form_time').val(json.form_time);
					$('#team').val(json.team);
					$('#owned').val(json.owned);
					$('#owned_by').val(json.owned_by);
					$('#warehouse').val(json.warehouse);
					$('#transfer_number').val(json.transfer_number);
				});
				$.get('/index.php/purchase/getPurchaseDetail',{
					'id':selected_sales,
				},function(data){
					$('.forinsert').html(data);
					$('#purchase_id').val(selected_sales);
				});
			});
		$(document).on('click','#datatable-datatable1 .datatable-rows .flexarea .datatable-wrapper table tr',function(){
			var a=$(this).index();
			var input=$('#datatable-datatable1 .datatable-rows .fixed-left .datatable-wrapper table tr').eq(a).find('input');		
			selected_sales= $(input).val();
			$(input).trigger('click');		
		});
		/****----------------------《《----------------------------****/
	</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script>
var array5=<?php echo $vendors?$vendors:json_encode(array());?>;
var array6=<?php echo $vens?$vens:json_encode(array());?>;
$('#combo5').combobox(array5, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"saleselect","comboval5");
$('#combo6').combobox(array6, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","comboval6",false,'',200);
</script>
