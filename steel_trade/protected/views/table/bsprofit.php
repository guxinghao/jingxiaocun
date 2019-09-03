<style>.submit_form{cursor:pointer;}</style>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('table/bsExport');?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
</div>
<form method="post" action="/index.php/table/bsProfit?page=1" url="">
<div class="search_body">
		
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	
	<div class="shop_more_one_l" style="width: 140px;margin:13px 0 0 8px;;">销售/采购/退货单号：</div>
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="select_body">
	<div class="shop_more_one" style="margin-top:8px;width:230px;">
		<div class="shop_more_one_l" style="width: 70px;">销售公司：</div>
		<div id="comselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo DictTitle::getName($search['title']);?>" />
			<input type='hidden' id='comboval2' value="<?php echo $search['title'];?>"  class="forreset" name="search[title]"/>
		</div>
	</div>	
	<div class="more_select_box" style="top:130px;">
	<div class="more_one">
		<div class="more_one_l">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand" class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
			<input type='hidden' id='combovalbrand' value="<?php echo $search['brand'];?>"  class="forreset" name="search[brand]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rank]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rank']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            	 <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">甲乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>
            	 <option <?php echo ($search['is_yidan']==0&&$search['is_yidan']!==NULL&&$search['is_yidan']!=='')?'selected="selected"':''?>  value="0">甲单</option>
            	 <option <?php echo $search['is_yidan']==1?'selected="selected"':''?>  value="1">乙单</option>
	        </select>
	</div>
		<div class="more_one" >
		<div class="more_one_l" >结算单位：</div>
		<div id="cusselect"  class="fa_droplist">
			<input type="text" style="width:145px;" id="cusbo" class="forreset"  value="<?php echo DictCompany::getName($search['company']);?>" />
			<input type='hidden' id='cusboval'  class="forreset"  value="<?php echo $search['company'];?>"  name="search[company]"/>
		</div>
	</div>
	<div class="more_one" >
		<div class="more_one_l" >仓库：</div>
		 <select name="search[warehouse]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($warehouses as $k=>$v){?>
            	<option <?php echo $k==$search['warehouse']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>           
	        </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l" >供应商：</div>
		<div id="venselect"  class="fa_droplist">
			<input type="text" id="comboven" class="forreset"  value="<?php echo DictCompany::getName($search['vendor'])?>" />
			<input type='hidden' id='combovalven' value="<?php echo $search['vendor'];?>"  class="forreset" name="search[vendor]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l" style="width:75px;margin-left:-5px;">成本价来源：</div>
		 <select name="search[comment]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>
	            <?php foreach (ProfitCollecting::$source as $key =>$val){?>
            	 <option <?php echo $search['comment']==$key?'selected="selected"':''?>  value="<?php echo $key?>"><?php echo $val;?></option>           	 
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">采购审单：</div>
		 <select name="search[pur_confirm]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>
            	 <option <?php echo ($search['pur_confirm']==0&&$search['pur_confirm']!==NULL&&$search['pur_confirm']!=='')?'selected="selected"':''?>  value="0">未审单</option>
            	 <option <?php echo $search['pur_confirm']==1?'selected="selected"':''?>  value="1">已审单</option>
	        </select>
	</div>
	<!--  
	<div class="more_one" style="width: 260px;margin-left:0px;">
		<input type="checkbox" style="margin-left: 15px" name="search[dg_title]" <?php echo $search['dg_title']=='on'?'checked':''?> class="l check_box" id="dg_title_button"><label style="float:left;margin-top:1px;" for="dg_title_button" >只查登钢</label>
		<input type="checkbox" style="margin-left: 10px" name="search[jm_title]" <?php echo $search['jm_title']=='on'?'checked':''?>  class="l check_box" id="jm_title_button"><label style="float:left;margin-top:1px;" for="jm_title_button">只查爵淼</label>
		<input type="checkbox" style="margin-left: 10px" name="search[dgjm_title]" <?php echo $search['dgjm_title']=='on'?'checked':''?> class="l check_box" id="dgjm_title_button"><label style="float:left;margin-top:1px;" for="dgjm_title_button">只查登钢和爵淼</label>
	</div>
	-->
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:170,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">销售数量：<span><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">销售金额：<span class="color_org"><?php echo number_format($totalData["fee"],2);?></span></div>
	<div class="total_data_one">均价：<span ><?php echo $totalData["weight"]!=0?number_format($totalData["fee"]/$totalData["weight"],2):0;?></span></div>
	<div class="total_data_one">利润：<span class="color_org"><?php echo number_format($totalData["sales_profit"],2);?></span></div>
</div>
<?php paginate($pages, "profit_list")?>

<script>
	    $('.reset').click(function(){
		    $('.forreset').val('');		    
	    });
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script type="text/javascript">	
	$(function(){
		var array=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array2=<?php echo $titles;?>;
		var array4=<?php echo $teams?$teams:json_encode(array());?>;
		var array_brand=<?php echo $brands;?>;
		var arraycom=<?php echo $coms;?>;
		$('#comboven').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","combovalven",false);
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval4",false);
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);
		$('#cusbo').combobox(arraycom, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"cusselect","cusboval");
		$('.check_box').click(function(){
			var check=$(this).attr('checked');
			if(check=='checked')
			{
				$(this).siblings().each(function(){
					$(this).removeAttr('checked');
				})
			}
		})
		
	})
	$('.reset').click(function(){
		$('.check_box').removeAttr('checked');
	})
</script>
