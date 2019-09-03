function initSet(param)
{
	$('#forinsert tr').each(function(){
		var obj=$(this).find('.td_brand');
		var brand=$(obj).val();
		var tr=$(obj).parent().parent().parent();
		var product=$(tr).find('.td_product').val();
		var texture=$(tr).find('.td_texture').val();
		var rank=$(tr).find('.td_rank').val();
		var length=$(tr).find('.td_length').val();		
		var brandOld=$(obj).parent().siblings('.old_value').val();
		var productOld=$(tr).find('.td_product').siblings('.old_value').val();
		var textureOld=$(tr).find('.td_texture').siblings('.old_value').val();
		var rankOld=$(tr).find('.td_rank').siblings('.old_value').val();	
		$.ajaxSetup({async:false});	
		$.post('/index.php/dictGoodsProperty/propertySelect',{
			'type':'brand',
			'id':brand,
			'product':product,
			'texture':texture,
			'rank':rank,
			'brandOld':brandOld,
			'productOld':productOld,
			'textureOld':textureOld,
			'rankOld':rankOld,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1@o'));
				var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
				var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
				var data4=data.substring(data.indexOf('o3@o')+4);
				$(tr).find('.td_product').html(data1);
				$(tr).find('.td_texture').html(data2);
				$(tr).find('.td_rank').html(data3);
				$(tr).find('.td_length').val(data4);
				if(product!='')$(tr).find('.td_product').val(product);
				if(texture!='')$(tr).find('.td_texture').val(texture);
				if(rank!='')$(tr).find('.td_rank').val(rank);
				if(length!='')$(tr).find('.td_length').val(length);
		});
		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();
		if(param=='old')
		{
			productChangeForInit($(tr).find('.td_product'));
		}else{
			//获取开票成本
			$.post('/index.php/purchase/getInvoice/'+brand,{},function(data){
				if(data)$(tr).find('.td_invoice').val(data);
			})
			productChange($(tr).find('.td_product'));			
		}		
	});
}

function productChangeForInit(obj)
{
	var product=$(obj).val();
	var brand=$(obj).parent().parent().find('.td_brand').val();
	if(brand)
	{
		brand_name=getBrandName(brand);
	}
	var oldd=$(obj).attr('name').substring(0,3);
	var plus='';
	if(oldd=='old')
	{
		 plus='old_';
		var  oldBrandValue=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	}
	var texture=$(obj).parent().parent().find('.td_texture').val();
	var rank=$(obj).parent().parent().find('.td_rank').val();
	var length=$(obj).parent().parent().find('.td_length').val();
	var brandOld=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	var productOld=$(obj).siblings('.old_value').val();
	var textureOld=$(obj).parent().parent().find('.td_texture').siblings('.old_value').val();
	var rankOld=$(obj).parent().parent().find('.td_rank').siblings('.old_value').val();	
	$.post('/index.php/dictGoodsProperty/propertySelect',{
		'type':'product',
		'id':product,
		'brand':brand,
		'texture':texture,
		'rank':rank,
		'brandOld':brandOld,
		'productOld':productOld,
		'textureOld':textureOld,
		'rankOld':rankOld,
		},function(data){
			var data1=data.substring(0,data.indexOf('o1@o'));
			var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
			var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
			var data4=data.substring(data.indexOf('o3@o')+4);
			$(obj).parent().parent().find('.td_texture').html(data2);
			$(obj).parent().parent().find('.td_rank').html(data3);
			$(obj).parent().parent().find('.td_length').val(data4);
			var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
			var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
			var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
			var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
								'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
								'<input type="hidden" id="'+val_id+'" value=""  name="'+plus+'td_brands[]" class="td_brand" />'+
							'</div>';
			if(oldd=='old')
			{
				str=str+'<input type="hidden" class="old_value" value="'+oldBrandValue+'">';
			}							
			$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
			$('#'+combo_id).combobox(data1, {imageUrl : "/images/dropdown.png"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(obj)','',true);
			if(brand!=''){
				$(obj).parent().parent().find('.td_brand').val(brand);
				$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
			}				
			if(texture!='')$(obj).parent().parent().find('.td_texture').val(texture);
			if(rank!='')$(obj).parent().parent().find('.td_rank').val(rank);
			if(length!='')$(obj).parent().parent().find('.td_length').val(length);
	});
	//获取件重
	var length=$(obj).parent().parent().find('.td_length').val();
	var td_num=$(obj).parent().parent().find('.td_num').val();
	if($(obj).parent().parent().find('.td_price').val())
	{
		var td_price=numChange($(obj).parent().parent().find('.td_price').val());
	}else{
		var td_price='';
	}
	$.get('/index.php/contract/getUnitWeight',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		},function(data){
			if(data===false||data=='')
			{
    			return;
			}
			unit_weight=data;	    	
			$(obj).parent().parent().find('.td_unit_weight').val((parseFloat(unit_weight)).toFixed(3));
	});				
}

function brandChange(obj)
{
	var brand=$(obj).attr('param');
	brand_name=$(obj).text();
	var product=$(obj).parent().parent().parent().parent().find('.td_product').val();
	var texture=$(obj).parent().parent().parent().parent().find('.td_texture').val();
	var rank=$(obj).parent().parent().parent().parent().find('.td_rank').val();
	var brandOld=$(obj).parent().siblings('.old_value').val();
	var productOld=$(obj).parent().parent().parent().find('.td_product').siblings('.old_value').val();
	var textureOld=$(obj).parent().parent().parent().find('.td_texture').siblings('.old_value').val();
	var rankOld=$(obj).parent().parent().parent().find('.td_rank').siblings('.old_value').val();
	var ware=$('.wareinput').val();
	
	//获取开票成本
	$.ajaxSetup({async:false});	
	$.post('/index.php/purchase/getInvoice/'+brand,{},function(data){
		var invoice=$(obj).parent().parent().parent().parent().find('.td_invoice')
		if(data)invoice.val(data);
		else invoice.val('');
	})
	$.post('/index.php/dictGoodsProperty/propertySelect',{
		'type':'brand',
		'id':brand,
		'product':product,
		'texture':texture,
		'rank':rank,
		'brandOld':brandOld,
		'productOld':productOld,
		'textureOld':textureOld,
		'rankOld':rankOld,
		},function(data){
			var data1=data.substring(0,data.indexOf('o1@o'));
			var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
			var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
			var data4=data.substring(data.indexOf('o3@o')+4);
			$(obj).parent().parent().parent().parent().find('.td_product').html(data1);
			$(obj).parent().parent().parent().parent().find('.td_texture').html(data2);
			$(obj).parent().parent().parent().parent().find('.td_rank').html(data3);
			$(obj).parent().parent().parent().parent().find('.td_length').val(data4);
			if(product!='')$(obj).parent().parent().parent().parent().find('.td_product').val(product);
			if(texture!='')$(obj).parent().parent().parent().parent().find('.td_texture').val(texture);
			if(rank!='')$(obj).parent().parent().parent().parent().find('.td_rank').val(rank);
	});
	
	//获取件重
	var length=$(obj).parent().parent().parent().parent().find('.td_length').val();
	var td_num=$(obj).parent().parent().parent().parent().find('.td_num').val();
	if($(obj).parent().parent().parent().parent().find('.td_price').val())
	{
		var td_price=numChange($(obj).parent().parent().parent().parent().find('.td_price').val());
	}else{
		var td_price='';
	}
	
	$.get('/index.php/contract/getUnitWeight',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		},function(data){
			if(data===false||data=='')
			{
    			return;
			}
			unit_weight=data;	   			
			$(obj).parent().parent().parent().parent().find('.td_unit_weight').val((parseFloat(unit_weight)).toFixed(3));
//			if(ware)	{
				//获取当日价格
				price=getGoodsPrice(ware,product,rank,texture,brand,length);
				if(price){
					td_price=price;
					$(obj).parent().parent().parent().parent().find('.td_price').val(formatNum(td_price));
				}
					
//			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;
				confirmDialog('件数必须为大于0的整数');
				return;
			}	    			
			$(obj).parent().parent().parent().parent().find('.td_total_weight').val(td_num*unit_weight);
			$(obj).parent().parent().parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));
			updateTotalAmount();
			updateTotalWeight();					
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(obj).parent().parent().parent().parent().find('.td_total_weight').val();
			$(obj).parent().parent().parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();
	});				
}

function productChange(obj)
{
	var product=$(obj).val();
	var brand=$(obj).parent().parent().find('.td_brand').val();
	if(brand)
	{
		brand_name=getBrandName(brand);
	}
	var oldd=$(obj).attr('name').substring(0,3);
	var plus='';
	if(oldd=='old')
	{
		 plus='old_';
		 var oldBrandValue=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	}
	var texture=$(obj).parent().parent().find('.td_texture').val();
	var rank=$(obj).parent().parent().find('.td_rank').val();
	var brandOld=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	var productOld=$(obj).siblings('.old_value').val();
	var textureOld=$(obj).parent().parent().find('.td_texture').siblings('.old_value').val();
	var rankOld=$(obj).parent().parent().find('.td_rank').siblings('.old_value').val();
	var ware=$('.wareinput').val();
	$.post('/index.php/dictGoodsProperty/propertySelect',{
		'type':'product',
		'id':product,
		'brand':brand,
		'texture':texture,
		'rank':rank,
		'brandOld':brandOld,
		'productOld':productOld,
		'textureOld':textureOld,
		'rankOld':rankOld,
		},function(data){
			var data1=data.substring(0,data.indexOf('o1@o'));
			var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
			var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
			var data4=data.substring(data.indexOf('o3@o')+4);
			$(obj).parent().parent().find('.td_texture').html(data2);
			$(obj).parent().parent().find('.td_rank').html(data3);
			$(obj).parent().parent().find('.td_length').val(data4);
			var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
			var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
			var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
			var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
			'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
			'<input type="hidden" id="'+val_id+'" value=""  name="'+plus+'td_brands[]" class="td_brand" />'+
			'</div>';
			if(oldd=='old')
			{
				str=str+'<input type="hidden" class="old_value" value="'+oldBrandValue+'">';
			}				
			$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
			$('#'+combo_id).combobox(data1, {imageUrl : "/images/dropdown.png"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(obj)','',true);
			if(brand!=''){
				$(obj).parent().parent().find('.td_brand').val(brand);
				$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
			}				
			if(texture!='')$(obj).parent().parent().find('.td_texture').val(texture);
			if(rank!='')$(obj).parent().parent().find('.td_rank').val(rank);
	});
	
	
	//获取件重
	var length=$(obj).parent().parent().find('.td_length').val();
	var td_num=$(obj).parent().parent().find('.td_num').val();
	if($(obj).parent().parent().find('.td_price').val())
	{
		var td_price=numChange($(obj).parent().parent().find('.td_price').val());
	}else{
		var td_price='';
	}
	$.get('/index.php/contract/getUnitWeight',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		},function(data){
			if(data===false||data=='')
			{
    			return;
			}
			unit_weight=data;	    	
			$(obj).parent().parent().find('.td_unit_weight').val((parseFloat(unit_weight)).toFixed(3));
//			if(ware)	{
				//获取当日价格
				price=getGoodsPrice(ware,product,rank,texture,brand,length);
				if(price){
					td_price=price;
					$(obj).parent().parent().find('.td_price').val(formatNum(td_price));
				}
					
//			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;
				confirmDialog('件数必须为大于0的整数');
				return;
			}			    			
			$(obj).parent().parent().find('.td_total_weight').val(td_num*unit_weight);
			$(obj).parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));
			updateTotalAmount();
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(obj).parent().parent().find('.td_total_weight').val();
			$(obj).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();
	});				
	
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
	var oldd=$(obj).attr('name').substring(0,3);
	var plus='';
	if(oldd=='old')
	{
		 plus='old_';
		 var oldBrandValue=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	}
	var brandOld=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	var productOld=$(obj).parent().parent().find('.td_product').siblings('.old_value').val();
	var textureOld=$(obj).siblings('.old_value').val();
	var rankOld=$(obj).parent().parent().find('.td_rank').siblings('.old_value').val();
	var ware=$('.wareinput').val();
	$.post('/index.php/dictGoodsProperty/propertySelect',{
		'type':'texture',
		'id':texture,
		'brand':brand,
		'product':product,
		'rank':rank,
		'brandOld':brandOld,
		'productOld':productOld,
		'textureOld':textureOld,
		'rankOld':rankOld,
		},function(data){
			var data1=data.substring(0,data.indexOf('o1@o'));
			var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
			var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
			var data4=data.substring(data.indexOf('o3@o')+4);
			$(obj).parent().parent().find('.td_product').html(data2);
			$(obj).parent().parent().find('.td_rank').html(data3);
			$(obj).parent().parent().find('.td_length').val(data4);
			var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
			var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
			var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
			var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
			'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
			'<input type="hidden" id="'+val_id+'" value=""  name="'+plus+'td_brands[]" class="td_brand" />'+
			'</div>';
			if(oldd=='old')
			{
				str=str+'<input type="hidden" class="old_value" value="'+oldBrandValue+'">';
			}				
			$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
			$('#'+combo_id).combobox(data1, {imageUrl : "/images/dropdown.png"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(obj)','',true);
			if(brand!=''){
				$(obj).parent().parent().find('.td_brand').val(brand);
				$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
			}				
			if(product!='')$(obj).parent().parent().find('.td_product').val(product);
			if(rank!='')$(obj).parent().parent().find('.td_rank').val(rank);
	});
	
	//获取件重
	var length=$(obj).parent().parent().find('.td_length').val();
	var td_num=$(obj).parent().parent().find('.td_num').val();
	if($(obj).parent().parent().find('.td_price').val())
	{
		var td_price=numChange($(obj).parent().parent().find('.td_price').val());
	}else{
		var td_price='';
	}
	$.get('/index.php/contract/getUnitWeight',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		},function(data){
			if(data===false||data=='')
			{
    			return;
			}
			unit_weight=data;	    
			$(obj).parent().parent().find('.td_unit_weight').val((parseFloat(unit_weight)).toFixed(3));
//			if(ware)	{
				//获取当日价格
				price=getGoodsPrice(ware,product,rank,texture,brand,length);
				if(price){
					td_price=price;
					$(obj).parent().parent().find('.td_price').val(formatNum(td_price));
				}
					
//			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;
				confirmDialog('件数必须为大于0的整数');
				return;
			}			    			
			$(obj).parent().parent().find('.td_total_weight').val(td_num*unit_weight);
			$(obj).parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));
			updateTotalAmount();
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(obj).parent().parent().find('.td_total_weight').val();
			$(obj).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();
	});				
	
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
	var oldd=$(obj).attr('name').substring(0,3);
	var plus='';
	if(oldd=='old')
	{
		 plus='old_';
		 var oldBrandValue=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	}
	var brandOld=$(obj).parent().parent().find('.td_brand').parent().siblings('.old_value').val();
	var productOld=$(obj).parent().parent().find('.td_product').siblings('.old_value').val();
	var textureOld=$(obj).parent().parent().find('.td_texture').siblings('.old_value').val();
	var rankOld=$(obj).siblings('.old_value').val();
	var ware=$('.wareinput').val();
	$.post('/index.php/dictGoodsProperty/propertySelect',{
		'type':'rank',
		'id':rank,
		'brand':brand,
		'texture':texture,
		'product':product,
		'brandOld':brandOld,
		'productOld':productOld,
		'textureOld':textureOld,
		'rankOld':rankOld,
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
			var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
			'<input type="text" id="'+combo_id+'" style="width:130px;"  value="" />'+
			'<input type="hidden" id="'+val_id+'" value=""  name="'+plus+'td_brands[]" class="td_brand" />'+
			'</div>';
			if(oldd=='old')
			{
				str=str+'<input type="hidden" class="old_value" value="'+oldBrandValue+'">';
			}				
			$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
			$('#'+combo_id).combobox(data1, {imageUrl : "/images/dropdown.png"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(obj)','',true);
			if(brand!=''){
				$(obj).parent().parent().find('.td_brand').val(brand);
				$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
			}				
			if(texture!='')$(obj).parent().parent().find('.td_texture').val(texture);
			if(product!='')$(obj).parent().parent().find('.td_product').val(product);
	});
	
	//获取件重
	var length=$(obj).parent().parent().find('.td_length').val();
	var td_num=$(obj).parent().parent().find('.td_num').val();
	if($(obj).parent().parent().find('.td_price').val())
	{
		var td_price=numChange($(obj).parent().parent().find('.td_price').val());
	}else{
		var td_price='';
	}
	$.get('/index.php/contract/getUnitWeight',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		},function(data){
			if(data===false||data=='')
			{
    			return;
			}
			unit_weight=data;	    
			$(obj).parent().parent().find('.td_unit_weight').val((parseFloat(unit_weight)).toFixed(3));
//			if(ware)	{
				//获取当日价格
				price=getGoodsPrice(ware,product,rank,texture,brand,length);
				if(price){
					td_price=price;
					$(obj).parent().parent().find('.td_price').val(formatNum(td_price));
				}
//			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;
				confirmDialog('件数必须为大于0的整数');
				return;
			}			    			
			$(obj).parent().parent().find('.td_total_weight').val(td_num*unit_weight);
			$(obj).parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));
			updateTotalAmount();
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				alert(td_price);
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(obj).parent().parent().find('.td_total_weight').val();
			$(obj).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();
	});				
	
}

function lengthChange(obj)
{
	var length=$(obj).val();
	var brand=$(obj).parent().parent().find('.td_brand').val();
	var texture=$(obj).parent().parent().find('.td_texture').val();
	var product=$(obj).parent().parent().find('.td_product').val();
	var rank=$(obj).parent().parent().find('.td_rank').val();
	var ware=$('.wareinput').val();
	if(brand==''||texture==''||product==''||rank=='')
	{
		return;
	}
	
	var td_num=$(obj).parent().parent().find('.td_num').val();
	if($(obj).parent().parent().find('.td_price').val())
	{
		var td_price=numChange($(obj).parent().parent().find('.td_price').val());
	}else{
		var td_price='';
	}
	$.get('/index.php/contract/getUnitWeight',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		},function(data){
			if(data===false||data=='')
			{
    			return;
			}
			unit_weight=data;	    
			$(obj).parent().parent().find('.td_unit_weight').val((parseFloat(unit_weight)).toFixed(3));
//			if(ware)	{
				//获取当日价格
				price=getGoodsPrice(ware,product,rank,texture,brand,length);
				if(price)
				{
					td_price=price;
					$(obj).parent().parent().find('.td_price').val(formatNum(td_price));					
				}
//			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;
				confirmDialog('件数必须为大于0的整数');
				return;
			}			    			
			$(obj).parent().parent().find('.td_total_weight').val(td_num*unit_weight);
			$(obj).parent().parent().find('.td_weight').val((td_num*unit_weight).toFixed(3));
			updateTotalAmount();
			updateTotalWeight();
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(obj).parent().parent().find('.td_total_weight').val();
			$(obj).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
			updateTotalMoney();
	});				
	
	
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

function getGoodsPrice(ware,product,rank,texture,brand,length)
{
	var td_price='';
	//获取当日价格
	$.ajaxSetup({async:false});
	$.get('/index.php/dictGoods/getGoodsPrice',{
		'product':product,
		'rank':rank,
		'texture':texture,
		'brand':brand,
		'length':length,
		'ware':ware,
		'type':'net',
	},function(price){
		if(price!=='')	td_price=price;
	});
	return td_price;
}


