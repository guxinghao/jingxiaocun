<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<script type="text/javascript">
var array_brand=<?php echo $brands;?>;
var array=<?php echo $vendors;?>;
var array2=<?php echo $coms;?>;
var array3=<?php echo $warehouses;?>;
</script>
<div class="shop_select_box">
	<div class="shop_more_one">
	<input type="hidden" name="FrmInput[input_type]" value="dxrk">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购公司：</div>
		<div id="comselect" class="fa_droplist">
			<input type="text" id="combo2" value="" />
			<input type='hidden' id='comboval2' value=""  name="FrmInput[title_id]"/>
		</div>
	</div>
<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>供应商：</div>
		<div id="supplyselect" class="fa_droplist">
			<input type="text" id="combo" value="" />
			<input type='hidden' id='comboval'  value=""  name="FrmInput[supply_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmInput[contact_id]" class='form-control chosen-select se_ywz' id="contact_id">
				<option value=""></option>
				<?php foreach($contacts as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone"  class="form-control con_tel" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入库日期：</div>
		<div class="search_date_box" style="margin-top:0px;width:150px;background-position:155px 8px;">
			<input type="text"  name="CommonForms[form_time]" value="<?php echo date('Y-m-d',time());?>" id="form_time"  class="form-control  date input_backimg" placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by" onchange="changeOwnerT()"  class='form-control chosen-select se_yw'>
	            <?php foreach($users as $k=>$v){?>
	            <option <?php echo Yii::app()->user->userid==$k?'selected="selected"':'';?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>
		 <select name="FrmInput[team_id]" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php foreach($teams as $k=>$v){?>
	            	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
			<input type="hidden" name="FrmInput[team_id]"   value="">
		<!-- -><div id="ywyselect" style="float:left; display:inline;position: relative;width:150px;">
<!-- 			<input type="text" id="combo4" value="" /> -->
<!-- 			<input type='hidden' id='comboval4'  value=""  name="FrmInput[team_id]"/> -->
<!-- 		</div> -->
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>入库仓库：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo3" value="" />
			<input type='hidden' id='comboval3'  value=""  class="wareinput" name="FrmInput[warehouse_id]"/>
		</div>
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  name="CommonForms[comment]"  class="form-control tit_remark" placeholder=""  >
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
         		<th class="text-center" style="width:5%;">长度</th>
    			<th class="text-center" style="width:9%;"><span class="bitian">*</span>卡号</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>重量</th>
      		</tr>
    	</thead>
    <tbody>
        <?php for($i=1;$i<=5;$i++){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>
    		<td class="">
    			<div id="brandselect<?php echo $i?>" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">
					<input type="text" id="combobrand<?php echo $i?>" style="width:130px;"  value="" />
					<input type='hidden' id='combovalbrand<?php echo $i?>' value=""   name="td_brands[]" class="td_brand"/>
				</div>
			<script type="text/javascript">			
    		$('#combobrand<?php echo $i?>').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect<?php echo $i?>","combovalbrand<?php echo $i?>",false,'brandChange(obj)');
    		</script>
    		</td>
    		<td class="">
    			<select name='td_products[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($products as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="td_textures[]" class='form-control chosen-select td_texture' onchange="textureChange(this)">
    			<option></option>
	            <?php foreach($textures as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class="">
    			<select name="td_ranks[]" class='form-control chosen-select td_rank' onchange="rankChange(this)">
    			<option></option>
	            <?php foreach($ranks as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class=""><input type="text"  name="td_length[]" style="" class="form-control td_length" placeholder=""  ></td>
    		<td><input type="text" name="td_card_id[]"  value="" class="form-control td_card"></td>
    		<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name=""   style="" class="form-control  td_weight" placeholder=""  >
    			<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    	</tr>
    	<?php }?>
    </tbody>
  </table>
</div>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list">
   <!--  -> <button type="submit" class="btn btn-primary btn-sm " data-dismiss="modal"  id="submit_btn1">保存提交</button>-->
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
$('#cancel').click(function(){
	window.history.back(-1);
});
	//新增采购合同明细
	var num=1;
	$("#add_list").click(function(){
		var count=parseInt($("#cght_tb tbody tr").length) + 1;
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
		'<td class="">'+
		'<div id="bbrandselect'+num+'" style="float:left; display:inline;position: relative;width:130px;margin-right:-23px;">'+
			'<input type="text" id="bcombobrand'+num+'" style="width:130px;"  value="" />'+
			'<input type="hidden" id="bcombovalbrand'+num+'" value=""   name="td_brands[]" class="td_brand"/>'+
		'</div>'+
		'</td>'+
		'<td class="">'+
			'<select name="td_products[]" class="form-control chosen-select td_product" onchange="productChange(this)">'+
			'<option></option>'+
			<?php foreach($products as $k=>$v){?>
            	'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
            <?php }?>
    		'</select>'+
		'</td>'+
		'<td class="">'+
		'<select name="td_textures[]" class="form-control chosen-select td_texture" oncahnge="textureChange(this)">'+
		'<option></option>'+
		<?php foreach($textures as $k=>$v){?>
    		'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
    	<?php }?>
		'</select>'+
		'</td>'+
		'<td class="">'+
			'<select name="td_ranks[]" class="form-control chosen-select td_rank" onchange="rankChange(this)">'+
			'<option></option>'+
			<?php foreach($ranks as $k=>$v){?>
        	'<option value="<?php echo $k;?>"><?php echo $v;?></option>'+
        	<?php }?>
    		'</select>'+
		'</td>'+
		'<td class=""><input type="text" name="td_length[]" style="" class="form-control td_length" placeholder=""  ></td>'+
		'<td><input type="text" name="td_card_id[]"  value="" class="form-control td_card"></td>'+
		'<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>'+
		'<td class="">'+
			'<input type="text"  name=""  style="" class="form-control  td_weight" placeholder=""  >'+
			'<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >'+
		'</td>'+
	'</tr>';

		//获取最后一条的值
		var nextBrand= $("#cght_tb tr:last").find('.td_brand').val();
		var nextProduct= $("#cght_tb tr:last").find('.td_product').val();
		var nextTexture= $("#cght_tb tr:last").find('.td_texture').val();
		var nextRank= $("#cght_tb tr:last").find('.td_rank').val();
		var nextLength= $("#cght_tb tr:last").find('.td_length').val();
		var nextNum= $("#cght_tb tr:last").find('.td_num').val();
		var nextWeight= $("#cght_tb tr:last").find('.td_weight').val();
		var nextTotalWeight= $("#cght_tb tr:last").find('.td_total_weight').val();
	
		$("#cght_tb tbody ").append(newRow);
		$('#bcombobrand'+num).combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"bbrandselect"+num,"bcombovalbrand"+num,false,'brandChange(obj)');
		$("#tr_num").val(count);
		num++;

		$("#cght_tb tr:last").find('.td_brand').val(nextBrand);
		$("#cght_tb tr:last").find('.td_product').val(nextProduct);
		$("#cght_tb tr:last").find('.td_texture').val(nextTexture);
		$("#cght_tb tr:last").find('.td_rank').val(nextRank);
		$("#cght_tb tr:last").find('.td_length').val(nextLength);
		$("#cght_tb tr:last").find('.td_num').val(nextNum);
		$("#cght_tb tr:last").find('.td_weight').val(nextWeight);
		$("#cght_tb tr:last").find('.td_total_weight').val(nextTotalWeight);

		//初始化下拉框
		$('#cght_tb tr:last').each(function(){
			var obj=$(this).find('.td_brand');
			var brand=$(obj).val();
			var product=$(obj).parent().parent().parent().find('.td_product').val();
			var texture=$(obj).parent().parent().parent().find('.td_texture').val();
			var rank=$(obj).parent().parent().parent().find('.td_rank').val();		
			$.ajaxSetup({async:false});	
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
					$(obj).parent().parent().parent().find('.td_product').html(data1);
					$(obj).parent().parent().parent().find('.td_texture').html(data2);
					$(obj).parent().parent().parent().find('.td_rank').html(data3);
					$(obj).parent().parent().parent().find('.td_length').val(data4);
					if(product!='')$(obj).parent().parent().parent().find('.td_product').val(product);
					if(texture!='')$(obj).parent().parent().parent().find('.td_texture').val(texture);
					if(rank!='')$(obj).parent().parent().parent().find('.td_rank').val(rank);
			});
			productChange($(obj).parent().parent().parent().find('.td_product'));			
		});
		$("#cght_tb tr:last").find('.td_length').val(nextLength);		
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
	})
var  can_submit = true;
$("#submit_btn").click(function(){
	if(!can_submit){return false;}
		var card_array=new Array();
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var cggs = $("#comboval2").val();
		var contact=$('#contact_id').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(ware==''){confirmDialog('请选择输入仓库');return false;}
		if(contact==''){confirmDialog("请选择输入联系人！");return false;}
		if(form_time==''){confirmDialog("请选择输入入库日期！");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		var flag=true;
		var havedetail=false;
		$("#cght_tb tbody tr").each(function(){
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var brand=$(this).find(".td_brand").val();
			var td_card = $(this).find('.td_card').val();
			if(brand==''&&td_weight==''&&td_amount==''&&td_card=='')
			{				
			}else{
				havedetail=true;
				if(brand==''){confirmDialog('请选择输入编号为'+list_num+'的产地');flag=false;return false;}
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
						},function(data){
							if(data)
							{
								confirmDialog('仓库中已经有卡号为'+td_card+'的商品，请输入其他的卡号');
								flag=false;
								return false;
							}
						});			
				}
				if(!flag){return false;}
				if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为整数");flag= false;return false;}
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
			}
			
		})
		if(!flag){return false;}
		if(!havedetail){confirmDialog('请选择输入明细信息');return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})

	
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
	    changeOwnerT();
    	$('#contact_id').change(function(){
			var contact_id=$(this).val();
			$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
				$('#phone').val(data);
			});
        });
     $.ajaxSetup({ async: false });
  	    var unit_weight=0;
  		$(document).on('change','.td_num',function(){
  			var that=$(this);
  			var td_num=$(this).val();
  			if(!/^[1-9][0-9]*$/.test(td_num))
  			{
  				confirmDialog('件数必须为大于0的整数');
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
  		});

		//重量改变
		$(document).on('change','.td_weight',function(){
			//改变金额
			var td_weight=$(this).val();
			if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
			{						
				confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
				return;
			}
			$(this).next().val(td_weight);
		});
		var brand_name='';	
	
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/changeFunction.js"></script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script>
	$(function(){
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"supplyselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");		
	})
	</script>
