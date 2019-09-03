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
var array_rank=<?php echo $ranks;?>;
var array_brand=<?php echo $brands;?>;
var array_texture=<?php echo $textures;?>;
</script>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购公司：</div>
		<div id="comselect" class="fa_droplist">
			<input type="text" id="combo2" value=""/>
			<input type='hidden' id='comboval2' value=""  name="FrmPurchaseContract[dict_title_id]"/>
		</div>
	</div>
<div class="shop_more_one">
		<div class="shop_more_one_l" ><span class="bitian">*</span>供应商：</div>
		<div id="supplyselect" class="fa_droplist">
			<input type="text" id="combo" value="" />
			<input type='hidden' id='comboval'  value=""  name="FrmPurchaseContract[dict_company_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchaseContract[contact_id]" class='form-control chosen-select se_ywz' id="contact_id">
				<option value=""></option>
				<?php if(!empty($contacts)){foreach($contacts as $k=>$v){?>
	            	<option value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" class="form-control con_tel" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>合同编号：</div>
		<input type="text"  name="FrmPurchaseContract[contract_no]" value=""  class="form-control con_tel"  id="contract" >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l" ><span class="bitian">*</span>合同订立日期：</div>
		<input type="text"  name="CommonForms[form_time]" value="<?php echo date('Y-m-d',time());?>" id="form_time" class="form-control form-date date start_time input_backimg" placeholder="选择日期"  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name="CommonForms[owned_by]" id="CommonForms_owned_by"  onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php foreach($users as $k=>$v){?>
	            	<option <?php echo $k==Yii::app()->user->userid?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>		
		 <select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option  value=''></option>
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option  value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
			<input type="hidden" name="FrmPurchaseContract[team_id]"   >
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
         		<th class="text-center" style="width:3%;"></th>
         		<th class="text-center" style="width:5%;">操作</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>产地</th>
         		<th class="text-center" style="width:9%;"><span class="bitian">*</span>品名</th>
         		<th class="text-center" style="width:12%;"><span class="bitian">*</span>材质</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>规格</th>         		
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>重量</th>
         		<th class="text-center" style="width:7%;"><span class="bitian">*</span>单价</th>
         		<th class="text-center" style="width:8%;"><span class="bitian">*</span>金额</th>
      		</tr>
    	</thead>
    <tbody>
    <?php for($i=1;$i<=5;$i++){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">
    		<td class="text-center list_num"><?php echo $i;?></td>
    		<td class="text-center"><i class="icon icon-trash deleted_tr" ></i></td>
    		<td class="">
    			<div id="brandselect<?php echo $i?>" style="float:left; display:inline;position: relative;width:140px;margin-right:-23px;">
					<input type="text" id="combobrand<?php echo $i?>" style="width:140px;"  value=""  />
					<input type='hidden' id='combovalbrand<?php echo $i?>' value=""  name="td_brands[]" class="td_brand" />
				</div>
				<script type="text/javascript">				
	    		$('#combobrand<?php echo $i?>').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect<?php echo $i?>","combovalbrand<?php echo $i?>",false,'brandChange(obj)');
	    		</script>	
    		</td>
    		<td class="">
    			<select name='td_products[]' class='form-control chosen-select td_product' onchange="productChange(this)">
    			<option></option>
    			<?php foreach($products as $k=>$v){?>
	            	<option value="<?php echo $k;?>" ><?php echo $v;?></option>
	            <?php }?>
	    		</select>
    		</td>
    		<td class=""><input type="text"  name="td_textures[]" style="" class="form-control td_texture" placeholder="多个材质间以-相隔" ></td>    	
    		<td class=""><input type="text"  name="td_ranks[]" style="" class="form-control td_rank" value="Φ" ></td>    	
    		
    		<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name=""   style="" class="form-control  td_weight" placeholder=""  >
    			<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>
    		<td class=""><input type="text"  name="td_totalMoney[]"  style="" class="form-control td_money" placeholder=""  ></td>
    	</tr>
    	<?php }?>
    </tbody>
  </table>
</div>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list">
    <button type="button" class="btn btn-primary btn-sm " data-dismiss="modal"  id="submit_btn1">保存提交</button>
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('contract/index',array("page"=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
$(document).on("blur","input",function(){
	$(this).removeClass("red-border");
});
$(document).on("blur","select",function(){
	$(this).removeClass("red-border");
});
var array_brand=<?php echo $brands;?>;
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
		'<td class="text-center"><i class="icon icon-trash deleted_tr" ></i></td>'+
		'<td class="">'+
		'<div id="bbrandselect'+num+'" style="float:left; display:inline;position: relative;width:140px;margin-right:-23px;">'+
			'<input type="text" id="bcombobrand'+num+'" style="width:140px;"  value="" />'+
			'<input type="hidden" id="bcombovalbrand'+num+'" value=""  name="td_brands[]" class="td_brand" />'+
		'</div>'+
	'</td>'+
		'<td class="">'+
			'<select name="td_products[]" class="form-control chosen-select td_product" onchange="productChange(this)">'+
			'<option></option>'+
			<?php foreach($products as $k=>$v){?>
            	'<option value="<?php echo $k;?>" ><?php echo $v;?></option>'+
            <?php }?>
    		'</select>'+
		'</td>'+
		'<td class=""><input type="text"  name="td_textures[]" style="" class="form-control td_texture" placeholder="多个材质间以-相隔"></td>'+
		'<td class=""><input type="text"  name="td_ranks[]" style="" class="form-control td_rank" value="Φ"></td>'+
		'<td class=""><input type="text"  name="td_amount[]" style="" class="form-control td_num" placeholder=""  ></td>'+
		'<td class="">'+
			'<input type="text"  name=""  style="" class="form-control  td_weight" placeholder=""  >'+
			'<input type="hidden"  name="td_weight[]"   style="" class="form-control td_total_weight " placeholder=""  >'+
		'</td>'+
		'<td class=""><input type="text"  name="td_price[]" style="" class="form-control td_price" placeholder=""  ></td>'+
		'<td class=""><input type="text"  name="td_totalMoney[]"  style="" class="form-control td_money" placeholder=""  ></td>'+
	'</tr>';

	//获取最后一条的值
	var nextBrand= $("#cght_tb tr:last").find('.td_brand').val();
	var nextProduct= $("#cght_tb tr:last").find('.td_product').val();
	var nextTexture= $("#cght_tb tr:last").find('.td_texture').val();
	var nextRank= $("#cght_tb tr:last").find('.td_rank').val();
	var nextNum= $("#cght_tb tr:last").find('.td_num').val();
	var nextWeight= $("#cght_tb tr:last").find('.td_weight').val();
	var nextTotalWeight= $("#cght_tb tr:last").find('.td_total_weight').val();
	var nextPrice= $("#cght_tb tr:last").find('.td_price').val();
	var nextMoney= $("#cght_tb tr:last").find('.td_money').val();	
	
	$("#cght_tb tbody").append(newRow);
	$('#bcombobrand'+num).combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"bbrandselect"+num,"bcombovalbrand"+num,false,'brandChange(obj)');	
	$("#tr_num").val(count);
	num++;

	$("#cght_tb tr:last").find('.td_brand').val(nextBrand);
	$("#cght_tb tr:last").find('.td_product').val(nextProduct);
	$("#cght_tb tr:last").find('.td_texture').val(nextTexture);
	$("#cght_tb tr:last").find('.td_rank').val(nextRank);
	$("#cght_tb tr:last").find('.td_num').val(nextNum);
	$("#cght_tb tr:last").find('.td_weight').val(nextWeight);
	$("#cght_tb tr:last").find('.td_total_weight').val(nextTotalWeight);
	$("#cght_tb tr:last").find('.td_price').val(nextPrice);
	$("#cght_tb tr:last").find('.td_money').val(nextMoney);

		//初始化下拉框
		$('#cght_tb tr:last').each(function(){
			var obj=$(this).find('.td_brand');
			var brand=$(obj).val();
			var product=$(obj).parent().parent().parent().find('.td_product').val();
			$.ajaxSetup({async:false});	
			$.post('/index.php/dictGoodsProperty/propertySelect',{
				'type':'brand',
				'id':brand,
				'product':product,
				},function(data){
					var data1=data.substring(0,data.indexOf('o1@o'));
					var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
					var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
					var data4=data.substring(data.indexOf('o3@o')+4);
					$(obj).parent().parent().parent().find('.td_product').html(data1);
					if(product!='')$(obj).parent().parent().parent().find('.td_product').val(product);
			});
			productChange($(obj).parent().parent().parent().find('.td_product'));			
		});
	});
	$('#contact_id').change(function(){
		var contact_id=$(this).val();
		$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
			$('#phone').val(data);
		});
    });
</script>
<!-- <script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script> -->	
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script>
	$(function(){
		changeOwnerT();
		var array=<?php echo $vendors;?>;
// 		var array=[{"id":"1","bs":true,'name':"供应商1"},{"id":"2","bs":"gys2",'name':"供应商2"},{"id":"3","bs":"gys3",'name':"供应商3"},{"id":"4","bs":"gys4",'name':"供应商4"}];
		var array2=<?php echo $coms?$coms:json_encode(array());?>;
		var array3=<?php echo $warehouses;?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"supplyselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
	})	
	
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
function changeBetween(str)
{
		var str1 = str.replace(/ /g,"-");
		str1 = str1.replace(/，/g,"-");
		str1 = str1.replace(/　/g,"-");
		str1 = str1.replace(/,/g,"-");
		return str1;
}
var goods_arr=new Array();
var  can_submit = true;
$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var str='';
		var gys = $("#comboval").val();
		var contact=$('#contact_id').val();
		var contract=$('#contract').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(!contact){confirmDialog("请选择输入联系人！");return false;}
		if(contract==''|| /^\s*$/.test(contract)){confirmDialog("请选择输入合同编号！");return false;}
		if(form_time==''){confirmDialog("请选择输入采购日期！");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		var flag=true;
		var havedetail=false;
		goods_arr=new Array();
		$("#cght_tb tbody tr").each(function(){
			var brand=$(this).find(".td_brand").val();
			var product=$(this).find('.td_product').val();
			var texture=$(this).find(".td_texture").val();
			var rank=$(this).find(".td_rank").val();
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(brand==''&&product==''&&texture==''&&(rank==''||rank=='Φ'))
			{				
			}else{
				havedetail=true;
				if(brand==''){confirmDialog("请选择产地");flag=false;return false;}
				if(product==''){confirmDialog("请选择品名");$(this).find('.td_product').addClass('red-border');flag=false;return false;}
				if(texture==''|| /^\s*$/.test(texture)){confirmDialog("请输入材质");$(this).find('.td_texture').addClass('red-border');flag=false;return false;}
				var newtexture=changeBetween(texture);				
				$(this).find(".td_texture").val(newtexture);
				if(rank==''||rank=='Φ'|| /^Φ\s*$/.test(rank)){confirmDialog("请输入规格");$(this).find('.td_rank').addClass('red-border');flag=false;return false;}
				if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数须为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
				// if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价须为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
				if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("单价须为整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
				var result=checkRepeat(brand,product,$.trim(texture),$.trim(rank));
				if(!result){confirmDialog("选择输入的产地,品名,材质,规格不能重复");flag= false;return false;}
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
	function checkRepeat(brand,product,texture,rank)
	{
		var temp=[brand,product,texture,rank];
		if(goods_arr.indexOf(temp.toString())>-1)
		{
			return false;
		}else{
			goods_arr.push(temp.toString());
			return true;
		}
	}
	$('#submit_btn1').click(function(){
		if(!can_submit){return false;}
		var str='';
		var gys = $("#comboval").val();
		var ware=$('.wareinput').val();
		var contact=$('#contact_id').val();
		var contract=$('#contract').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		if(ware==''){confirmDialog("请选择输入仓库！");return false;}
		if(!contact){confirmDialog("请选择输入联系人！");return false;}
		if(contract==''||/^\s*$/.test(contract)){confirmDialog("请选择输入合同编号！");return false;}
		if(form_time==''){confirmDialog("请选择输入采购日期！");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		var flag=true;
		var havedetail=false;
		goods_arr=new Array();
		$("#cght_tb tbody tr").each(function(){
			var brand=$(this).find(".td_brand").val();
			var product=$(this).find(".td_product").val();
			var texture=$(this).find(".td_texture").val();
			var rank=$(this).find(".td_rank").val();
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(brand==''&&product==''&&texture==''&&(rank==''||rank=='Φ'))
			{				
			}else{
				havedetail=true;
				if(brand==''){confirmDialog("请选择产地");flag=false;return false;}
				if(product==''){confirmDialog("请选择品名");$(this).find('.td_product').addClass('red-border');flag=false;return false;}
				if(texture==''|| /^\s*$/.test(texture)){confirmDialog("请输入材质");$(this).find('.td_texture').addClass('red-border');flag=false;return false;}
				var newtexture=changeBetween(texture);				
				$(this).find(".td_texture").val(newtexture);
				if(rank==''||rank=='Φ'|| /^Φ\s*$/.test(rank)){confirmDialog("请输入规格");$(this).find('.td_rank').addClass('red-border');flag=false;return false;}
				if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("件数须为大于0的整数");$(this).find('.td_num').addClass('red-border');flag= false;return false;}
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0){confirmDialog("重量须为大于0的整数或6位小数点的小数");$(this).find('.td_weight').addClass('red-border');flag=false;return false;}
				// if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0){confirmDialog("单价须为大于0的整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
				if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("单价须为整数或2位小数点的小数");$(this).find('.td_price').addClass('red-border');flag= false;return false;}
				var result=checkRepeat(brand,product,$.trim(texture),$.trim(rank));
				if(!result){confirmDialog("选择输入的产地,品名,材质,规格不能重复");flag= false;return false;}
			}
		})
		if(!flag){return false;}
		if(!havedetail){confirmDialog('请选择输入明细信息');return false;}
		var str='<input type="hidden" name="CommonForms[submit]" value="yes">';
		$(this).parent().append(str);
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn1');
	        $("#form_data").submit();
	    }
	})
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
	    function changeOwnerT()
	    {
	    	var owner=$('#CommonForms_owned_by').val();
	    	$.get('/index.php/contract/getUserTeam',{
	    		'owner':owner,
	    	},function(data){
	    		$('#team_id').val(data);
	    		$('#team_id').next().val(data);
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
			//)$('.td_num').change(function(){			
			var that=$(this);
			var td_num=$(this).val();
			var td_price=numChange($(this).parent().parent().find('.td_price').val());
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				confirmDialog('件数必须为大于0的整数');
				return;
			}			
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;					
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();
			if(!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)||td_weight==0)
			{
				if(td_weight=='')return;						
				confirmDialog('重量必须是大于0的整数或小数点后6位的小数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
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
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)||td_price==0)
			{
				if(td_price=='')return;								
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
		});
		
	    //单价改变
		$(document).on('change','.td_price',function(){
			var td_price=numChange($(this).val());
			var td_num=$(this).parent().parent().find('.td_num').val();		
			var td_weight=$(this).parent().parent().find('.td_total_weight').val();		
			if(!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price))
			{						
				confirmDialog('价格必须是大于0的整数或小数点后2位的小数');
				return;
			}
			if(!/^[1-9][0-9]*$/.test(td_num))
			{
				if(td_num=='')return;		
				confirmDialog('件数必须为大于0的整数');
				return;
			}
			$(this).parent().parent().find('.td_money').val(formatNum((td_price*td_weight).toFixed(2)));
		});	    

		var brand_name='';
		function brandChange(obj)
		{	
			var brand=$(obj).attr('param');
			brand_name=$(obj).text();
			var product=$(obj).parent().parent().parent().parent().find('.td_product').val();
			$.ajaxSetup({async:false});
			$.post('/index.php/dictGoodsProperty/propertySelect',{
				'type':'brand',
				'id':brand,
				'product':product,
				},function(data){
					var data1=data.substring(0,data.indexOf('o1@o'));
					var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
					var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
					var data4=data.substring(data.indexOf('o3@o')+4);
					$(obj).parent().parent().parent().parent().find('.td_product').html(data1);
					if(product!='')$(obj).parent().parent().parent().parent().find('.td_product').val(product);
			});
			return false;
		}
		function productChange(obj)
		{
			var product=$(obj).val();
			var brand=$(obj).parent().parent().find('.td_brand').val();
			if(brand)
			{
				brand_name=getBrandName(brand);
			}
			$.post('/index.php/dictGoodsProperty/propertySelect',{
				'type':'product',
				'id':product,
				'brand':brand,
				},function(data){
					var data1=data.substring(0,data.indexOf('o1@o'));
					var data2=data.substring(data.indexOf('o1@o')+4,data.indexOf('o2@o'));
					var data3=data.substring(data.indexOf('o2@o')+4,data.indexOf('o3@o'));
					var data4=data.substring(data.indexOf('o3@o')+4);
					var div_id=$(obj).parent().parent().find('.td_brand').parent().attr('id');
					var combo_id=$(obj).parent().parent().find('.td_brand').prev().children('input').attr('id');
					var val_id=$(obj).parent().parent().find('.td_brand').attr('id');		
					var str='<div id="'+div_id+'" style="float:left; display:inline;position: relative;width:140px;margin-right:-23px;">'+
					'<input type="text" id="'+combo_id+'" style="width:140px;"  value="" />'+
					'<input type="hidden" id="'+val_id+'" value=""  name="td_brands[]" class="td_brand" />'+
					'</div>';
					$(obj).parent().parent().find('.td_brand').parent().parent().html(str);
					$('#'+combo_id).combobox(data1, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},$(obj).parent().parent().find('.td_brand').parent().attr('id'),$(obj).parent().parent().find('.td_brand').attr('id'),false,'brandChange(obj)','',true);
					if(brand!=''){
						$(obj).parent().parent().find('.td_brand').val(brand);
						$(obj).parent().parent().find('.td_brand').prev().children('input').val(brand_name);
					}				
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
	
	</script>
