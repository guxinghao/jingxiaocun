
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
		if(row_num==0)
		{
			selected_sales=0;
			$('#sales_list').find('input[type=radio]').each(function(){
				$(this).removeAttr('checked');					
			})
		}
		updateTotalAmount();
		updateTotalWeight();
		updateTotalMoney();
	})
$("#submit_btn").click(function(){
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var transfer = $('.transfer').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}		
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			var td_card = $(this).find('.td_card').val();
			if(td_card==''){
				confirmDialog('请输入编号为'+list_num+'的卡号');flag=false;return false;	
			}else if(td_card!=undefined){
				//查询仓库			
				$.ajaxSetup({async:false});
				$.get('/index.php/storage/haveOrNot',{
					'warehouse_id':ware,
					'card_no':td_card,
					},function(data){
						if(data)
						{
							confirmDialog('仓库中已经有编号为'+list_num+'的卡号的商品，请输入其他的卡号');
							flag=false;
							return false;
						}
					});			
			}
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为大于0的整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
		})
		if(!flag){return false;}
	})
	$('#submit_btn1').click(function(){
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}
		var flag=true;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			var td_card = $(this).find('.td_card').val();
			if(td_card==''){
				confirmDialog('请输入编号为'+list_num+'的卡号');flag=false;return false;
			}else if(td_card!=undefined){
				//查询仓库			
				$.ajaxSetup({async:false});
				$.get('/index.php/storage/haveOrNot',{
					'warehouse_id':ware,
					'card_no':td_card,
					},function(data){
						if(data)
						{
							confirmDialog('仓库中已经有编号为'+list_num+'的卡号的商品，请输入其他的卡号');
							flag=false;
							return false;
						}
					});			
			}
			if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为大于0的整数");flag= false;return false;}
			if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
		})
		if(!flag){return false;}
		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
		$(this).parent().append(str);
	})
		function changeTeamU()
		{
			var team_id= $('#comboval4').val();
	    	$.get('/index.php/contract/getTeamUser',{
	    		'team_id':team_id,
	    		},function(data){
	    			$('#CommonForms_owned_by').html(data);
	    		});	
		}

    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });
	    
	    //件数改变
	    $.ajaxSetup({ async: false });
	    var unit_weight=0;
		$(document).on('change','.td_num',function(){
			var that=$(this);
			var td_num=$(this).val();
			var td_max_num=$(this).parent().find('.td_max_num').val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			if(td_max_num!=undefined)
			{
				if(parseInt(td_num)>parseInt(td_max_num))
				{
					confirmDialog('您输入的件数大于最大可输件数'+td_max_num);
					$(this).val('');
					return;
				}
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
			//获取价格			
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