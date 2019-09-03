<?php
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
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>采购公司：</div>
		<div id=""class="fa_droplist">
			<input type="text" id="" readonly value="<?php echo $contract->dictTitle->short_name?>" class="form-control"/>
			<input type='hidden' id='comboval2' value="<?php echo $contract->dict_title_id;?>"  name="FrmPurchaseContract[dict_title_id]"/>
		</div>
	</div>
<div class="shop_more_one">
		<div class="shop_more_one_l" ><span class="bitian">*</span>供应商：</div>
		<div id="" class="fa_droplist">
			<input type="text" id="" readonly value="<?php echo $contract->dictCompany->short_name;?>" class="form-control" />
			<input type='hidden' id='comboval'  value="<?php echo $contract->dict_company_id;?>"  name="FrmPurchaseContract[dict_company_id]"/>
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>联系人：</div>
		<select name="FrmPurchaseContract[contact_id]" class='form-control chosen-select se_ywz' id="contact_id">
				<option value=""></option>
				<?php if(!empty($contacts)){foreach($contacts as $k=>$v){?>
	            	<option<?php echo $contract->contact_id==$k?'selected="selected"':"";?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	     </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">联系电话：</div>
		<input type="text" readonly id="phone" value="<?php echo $contract->contact->mobile;?>"  class="form-control con_tel" placeholder=""  >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>合同编号：</div>
			<input type="text" readonly name="FrmPurchaseContract[contract_no]" value="<?php echo $contract->contract_no?>" id="contract" class="form-control con_tel"   >
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l" ><span class="bitian">*</span>合同订立日期：</div>
		<div class="search_date_box" >
			<input type="text" readonly name="CommonForms[form_time]" value="<?php echo $baseform->form_time?>" id="form_time" class="form-control   " placeholder="选择日期"  >
		</div>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l"><span class="bitian">*</span>业务员：</div>
		 <select name="" id="CommonForms_owned_by" disabled onchange="changeOwnerT()" class='form-control chosen-select se_yw'>
	            <?php foreach($users as $k=>$v){?>
	            	<option <?php echo $baseform->owned_by==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }?>
	       </select>
	       <input type="hidden" name="CommonForms[owned_by]"  value="<?php echo $baseform->owned_by;?>" >
	</div>	
	<div class="shop_more_one">
		<div class="shop_more_one_l">业务组：</div>	
		 <select name="" id="team_id" disabled class='form-control chosen-select se_yw'>
		 		<option selected="selected" value=''></option>
	            <?php if(!empty($teams)){foreach($teams as $k=>$v){?>
	            	<option <?php echo $contract->team_id==$k?'selected="selected"':''?> value='<?php echo $k;?>'><?php echo $v;?></option>
	            <?php }}?>
	       </select>
			<input type="hidden" name="FrmPurchaseContract[team_id]"  value="<?php echo $contract->team_id;?>" >
	</div>

	<div class="shop_more_one">
		<div class="shop_more_one_l">备注：</div>
		<input type="text"  value="<?php echo $baseform->comment?>" name="CommonForms[comment]" class="form-control tit_remark" placeholder=""  >
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
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>件数</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>重量</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>单价</th>
         		<th class="text-center" style="width:6%;"><span class="bitian">*</span>金额</th>
      		</tr>
    	</thead>
    <tbody>
    <?php $i=1; foreach ($details as $each){?>
    	<tr class="<?php echo $i%2==0?'selected':''?>">    		
    		<td class="text-center list_num"><?php echo $i;?></td>    	
    		<td class="">
    			<div id="<?php echo "bbbrandselect".$i;?>" style="float:left; display:inline;position: relative;margin-right:-23px;">
					<input type="text" id="<?php echo "bbcombobrand".$i?>" readonly class="form-control"  value="<?php echo DictGoodsProperty::getProName($each->brand_id)?>" />
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
    		<td class=""><input type="text" readonly  name="old_td_textures[]" style="" value="<?php echo $each->texture_id;?>" class="form-control td_texture" ></td>
    		<td class=""><input type="text"  readonly name="old_td_ranks[]" style="" value="<?php echo $each->rank_id?>" class="form-control td_rank" ></td>
    		<td class=""><input type="text" readonly name="old_td_amount[]" style="" value="<?php echo $each->amount;?>" class="form-control td_num" placeholder=""  ></td>
    		<td class="">
    			<input type="text"  name="" readonly value="<?php echo round($each->weight,3);?>" style="" class="form-control td_total_weight td_weight" placeholder=""  >
    			<input type="hidden"  name="old_td_weight[]"  value="<?php echo $each->weight;?>" style="" class="form-control td_total_weight " placeholder=""  >
    		</td>
    		<td class=""><input type="text"  readonly name="old_td_price[]" style="" value="<?php echo round($each->price,2);?>" class="form-control td_price" placeholder=""  ></td>
    		<td class=""><input type="text"  readonly name="old_td_totalMoney[]" value="<?php echo number_format($each->weight*$each->price,2);?>" style="" class="form-control td_money" placeholder=""  ></td>
    	</tr>
    	<?php $i++;}?>
    </tbody>
  </table>
</div>

<div class="btn_list">
	<button type="button" class="btn btn-primary btn-sm blue" data-dismiss="modal" style="" id="submit_btn">保存</button>
	<a href="<?php echo Yii::app()->createUrl('contract/index',array("page"=>$fpage))?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script type="text/javascript">
	$('#cancle').click(function(){
		var url=$(this).attr('url');
		window.location.href=url;
		});
	$('#contact_id').change(function(){
		var contact_id=$(this).val();
		$.get('/index.php/purchase/getUserPhone',{'contact_id':contact_id},function(data){
			$('#phone').val(data);
		});
    });
</script>
<!--  <script src="<?php echo Yii::app()->request->baseUrl;?>/js/public.js"></script>-->
<script>
	$(function(){
		var array=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array2=<?php echo $coms;?>;
		var array3=<?php echo $warehouses?$warehouses:json_encode(array());?>;
/*	var array4=<?php //echo $teams;?>;*/
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"supplyselect","comboval",false,'changeCont()');
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combo3').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval3");
	/*	$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywzselect","comboval4",false,'changeTeamU()');*/
	})
var  can_submit = true;
$("#submit_btn").click(function(){
		if(!can_submit){return false;}
		var flag=true;
		//判断更新时间
		var lastupdate='<?php echo $_REQUEST['last_update']?>';
		var id='<?php echo $_REQUEST['id']?>';
		var fpage='<?php echo $_REQUEST['fpage']?>';
		var url='/index.php/contract/update/'+id+'?fpage='+fpage;
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
// 				setTimeout('',2300);
// 				window.location.href=url+'&&last_update='+data;
				flag=false;
				return false;
			}
		});		
		if(!flag)return false;		
		var str='';
		var gys = $("#comboval").val();
		var contact=$('#contact_id').val();
		var contract=$('#contract').val();
		var form_time=$('#form_time').val();
		var owned_by=$('#CommonForms_owned_by').val();
		if(gys==''){confirmDialog("请选择输入供应商！");return false;}
		var cggs = $("#comboval2").val();
		if(cggs==''){confirmDialog("请选择输入采购公司！");return false;}
		
		if(contact==''){confirmDialog("请选择输入联系人！");return false;}
		if(contract==''){confirmDialog("请选择输入合同编号！");return false;}
		if(form_time==''){confirmDialog("请选择输入采购日期！");return false;}
		if(owned_by==''){confirmDialog("请选择输入业务员！");return false;}
		$("#cght_tb tbody tr").each(function(){
			var brand=$(this).find(".td_brand").val();
			var texture=$(this).find(".td_texture").val();
			var rank=$(this).find(".td_rank").val();
			var list_num = $(this).find(".list_num").text();
			var td_amount = $(this).find(".td_num").val();
			var td_weight = $(this).find(".td_weight").val();
			var td_price = numChange($(this).find(".td_price").val());
			if(brand==''&&texture==''&&(rank==''||rank=='Φ'))
			{				
			}else{
				if(brand==''){confirmDialog("请选择编号为"+list_num+"的产地");flag=false;return false;}
				if(texture==''){confirmDialog("请输入编号为"+list_num+"的材质");flag=false;return false;}
				if(rank==''){confirmDialog("请输入编号为"+list_num+"的规格");flag=false;return false;}
				if(td_amount == ''||!/^[1-9][0-9]*$/.test(td_amount)){confirmDialog("请修改编号为"+list_num+"的件数为大于0的整数");flag= false;return false;}
				if(td_weight == ''||!/^[0-9]+(.[0-9]{1,6})?$/.test(td_weight)){confirmDialog("请修改编号为"+list_num+"的重量为整数或6位小数点的小数");flag=false;return false;}
				if(td_price == ''||!/^[0-9]+(.[0-9]{1,2})?$/.test(td_price)){confirmDialog("请修改编号为"+list_num+"的单价为整数或2位小数点的小数");flag= false;return false;}
			}
			
		})
		if(!flag){return false;}
		if(can_submit){
	        can_submit = false;
	        // setTimeout(function(){can_submit = true;},3000);
	        notAnymore('submit_btn');
	        $("#form_data").submit();
	    }
	})
		changeCont();
		var contact='<?php echo $contract->contact_id?>';
		var mobile='<?php echo $contract->contact->mobile;?>';
		$('#contact_id').val(contact);
		$('#phone').val(mobile);
	    function changeCont()
	    {
			var vendor_id=$('#comboval').val();
			$.ajaxSetup({async:false});
			$.get('/index.php/contract/getVendorCont',{
				'vendor_id':vendor_id,
			},function(data){
				var data1=data.substring(0,data.indexOf('o1o'));
				var data2=data.substring(data.indexOf('o1o')+3);
				$('#contact_id').html(data1);
				$('#phone').val(data2);
			});
		}	   	    
	</script>
