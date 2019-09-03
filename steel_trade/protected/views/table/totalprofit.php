<style>
.submit_form{cursor:pointer;}
.red{color:#ff3333;}
a {text-decoration:none;}
</style>
<form method="post" action="/index.php/table/totalprofit">
<div class="search_body" style="height:auto">
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date " placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 15px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body" style="float: none">	
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">供应商：</div>
		<div id="venselect" style="float:left; display:inline;position: relative;">
			<input type="text" id="combo2" class="forreset"  value="<?php echo DictCompany::getName($search['vendor'])?>" />
			<input type='hidden' id='comboval2' value="<?php echo $search['vendor'];?>"  class="forreset" name="search[vendor]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">结算单位：</div>
		<div id="comselect" style="float:left; display:inline;width:145px;position: relative;">
			<input type="text" style="width:145px;" id="combo" class="forreset"  value="<?php echo DictCompany::getName($search['company']);?>" />
			<input type='hidden' id='comboval'  class="forreset"  value="<?php echo $search['company'];?>"  name="search[company]"/>
		</div>
	</div>
	<div class="clear"></div>
	<div class="shop_more_one" style="margin-top:8px;width:195px">
		<div class="shop_more_one_l" style="width: 41px;padding-right:0px;">产地：</div>
		<div id="brandselect" class="fa_droplist">
				<input type="text" id="combobrand"  class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
				<input type='hidden' id='combovalbrand' value="<?php echo $search['brand'];?>"  class="forreset" name="search[brand]"/>
			</div>	
	</div>	
	<div class="shop_more_one" style="margin-top:8px;width:200px">
	<div class="shop_more_one_l" style="width: 36px;padding-right:0px;">品名：</div>
		<select name="search[product]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:200px">
	<div class="shop_more_one_l" style="width: 52px;">规格：</div>
		 <select name="search[rank]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rank']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:230px">
	<div class="shop_more_one_l" style="width: 72px;">材质：</div>
		<select name="search[texture]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	    </select>
	</div>
	<div class="clear"></div>
	<div class="more_one" style="width:210px;margin-left:-10px;">
		<div class="more_one_l" style="width:50px;">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
		<div class="more_one" style="width:200px;">
		<div class="more_one_l" style="width:36px;margin-left:-15px;">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['is_yidan']=='1'?'selected="selected"':''?>  value="1">乙单</option>
            	<option <?php echo $search['is_yidan']=='0'?'selected="selected"':''?>  value="0">甲单</option>
	      </select>
	</div>		
</div>
<div class="more_one" style="width:200px;margin-left:-10px;">
		<div class="more_one_l" style="width:48px;padding-right:5px;">仓库：</div>
		 <select name="search[warehouse]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($warehouses as $k=>$v){?>
            	<option <?php echo $k==$search['warehouse']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>           
	        </select>
	</div>
	<!--  
<div class="more_one" style="width: 260px;">
<input type="checkbox" style="margin-left: 15px" name="search[dg_title]" <?php echo $search['dg_title']=='on'?'checked':''?> class="l check_box" id="dg_title_button"><label style="float:left;margin-top:1px;" for="dg_title_button" >只查登钢</label>
<input type="checkbox" style="margin-left: 10px" name="search[jm_title]" <?php echo $search['jm_title']=='on'?'checked':''?>  class="l check_box" id="jm_title_button"><label style="float:left;margin-top:1px;" for="jm_title_button">只查爵淼</label>
<input type="checkbox" style="margin-left: 10px" name="search[dgjm_title]" <?php echo $search['dgjm_title']=='on'?'checked':''?> class="l check_box" id="dgjm_title_button"><label style="float:left;margin-top:1px;" for="dgjm_title_button">只查登钢和爵淼</label>
</div>
-->
<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<!-- 	<div class="more_toggle" title="更多"></div> -->
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="clear"></div>
<div class="board_back">
<div class="data_statistics">
	<div class="statistics_each">
		<div class="statistics_each_left"><img alt="" src="/images/statistics_left.png"></div>
		<div class="statistics_each_right">
			<div class="statistics_each_title">预估总利润：</div>
			<div class="color_l"><span class="money_identifier">￥</span><?php echo number_format($data['sales_profit']+$data['s_profit'],2)?></div>
		</div>
	</div>
	<div class="statistics_each statistics_margin">
		<div class="statistics_each_left"><img alt="" src="/images/statistics_middle.png"></div>
		<div class="statistics_each_right">
			<div class="statistics_each_title">采销利润：</div>			
			<a href="<?php echo Yii::app()->createUrl('table/bsProfit',array('search_url'=>json_encode($search)));?>">
			<div class="color_m">
				<span class="money_identifier">￥</span>				
				<?php echo number_format($data['sales_profit'],2)?>				
			</div>
			</a>
		</div>
	</div>
	<div class="statistics_each statistics_margin">
	<div class="statistics_each_left"><img alt="" src="/images/statistics_right.png">	</div>
		<div class="statistics_each_right">
			<div class="statistics_each_title">库存预估利润：</div>
			<a href="<?php echo Yii::app()->createUrl('table/storageProfit',array('search_url'=>json_encode($search)))?>">
			<div class="color_r"><span class="money_identifier">￥</span>			
			<?php echo number_format($data['s_profit'],2)?>			
			</div>
			</a>
		</div>
	</div>
</div>
<div class="clear"></div>
<div class="data_detail">
	<div class="data_detail_each">
		<div class="detail_each_title">
			<div class="float_left"><strong>采销利润：</strong></div>
			<a href="<?php echo Yii::app()->createUrl('table/bsProfit',array('search_url'=>json_encode($search)));?>">
			<div class="float_right color_m">￥			
			<?php echo number_format($data['sales_profit'],2)?>			
			</div>
			</a>
			<div class="clear"></div>
			<div style="width:100%;height:1px;border-bottom:1px solid #ccc"></div>
			<div  class="more_data_out"  style="width: 50%;margin-left:0;">
			<div class="more_data" style="color: #666;font-size:14px;">
				<span class="font_left_blank">吨均利润:</span><?php echo $data['weight']!=0?number_format($data['sales_profit']/$data['weight'],2):0?>元/吨
				</div>
			</div>
			<div  class="more_data_out" >
			<div class="more_data" style="color: #666;font-size:14px;">
				<span class="font_left_blank">销售重量:</span><?php echo number_format($data['weight'],3)?>吨
				</div>
			</div>			
			<div class="clear"></div>
		</div>
		
		<div class="detail_little_title">
			<span class="font_left_blank"><b>销售收入:	</b></span><?php echo number_format($data['fee'],2)?>元
		</div>
		<div class="horizontal_separate"></div>
		<div class="detail_little_title"><?php $pur_cost=$data['purchase_money']+$data['invoice']+$data['purchase_freight']+$data['pledge_fee']+$data['supply_rebate'];?>		
			<span class="font_left_blank"><b>采购成本:	</b></span><?php  echo $pur_cost>0?'<span class="red">('.number_format(abs($pur_cost),2).')</span>':number_format(abs($pur_cost),2)?>元
		</div>
		<div class="more_data_container">
			<div  class="more_data_out" >
				<div class="more_data ">
				<span class="font_left_blank">采购费用:</span><?php echo $data['purchase_money']>0?'<span class="red">('.number_format($data['purchase_money'],2).')</span>':number_format($data['purchase_money'],2)?>元
				</div>			
				<div class="more_data ">
				<span class="font_left_blank">发票成本:</span><?php echo $data['invoice']>0?'<span class="red">('.number_format($data['invoice'],2).')</span>':number_format($data['invoice'],2)?>元
				</div>
				<div class="more_data ">
				<span class="font_left_blank">采购运费:</span><?php echo $data['purchase_freight']>0?'<span class="red">('.number_format($data['purchase_freight'],2).')</span>':number_format($data['purchase_freight'],2)?>元
				</div>
				<div class="more_data ">
				<span class="font_left_blank ">托盘费用:</span><?php echo $data['pledge_fee']>0?'<span class="red">('.number_format($data['pledge_fee'],2).')</span>':number_format($data['pledge_fee'],2)?>元
				</div>
				<div class="clear"></div>
			</div>
			<div class="more_data_out">
				<div class="more_data">
				<span class="font_left_blank">钢厂返利:</span><?php echo number_format($data['supply_rebate'],2)?>元
				</div>
				<div class="clear"></div>
			</div>					
			<div class="clear"></div>
		</div>
		<div class="horizontal_separate"></div>
		<div class="detail_little_title"><?php $sale_cost=/*$data['sale_subsidy']+*/$data['sales_rebate']+$data['hight_open']+$data['sales_freight'];?>
			<span class="font_left_blank"><b>销售成本:</b></span><?php echo $sale_cost>0?'<span class="red">('.number_format(abs($sale_cost),2).')</span>':number_format(abs($sale_cost),2)?>元
		</div>
		<div class="more_data_container">
		<div  class="more_data_out" >
<!-- 			<div class="more_data"> --
			<span class="font_left_blank">销售提成:</span><?php echo $data['sale_subsidy']>0?'<span class="red">('.number_format($data['sale_subsidy'],2).')</span>':number_format($data['sale_subsidy'],2)?>元
<!-- 			</div> -->
			<div class="more_data">
			<span class="font_left_blank">销售折让:</span><?php echo $data['sales_rebate']>0?'<span class="red">('.number_format($data['sales_rebate'],2).')</span>':number_format(abs($data['sales_rebate']),2)?>元
			</div>
			<div class="more_data">
			<span class="font_left_blank">高开&nbsp;&nbsp;:</span><?php echo $data['hight_open']>0?'<span class="red">('.number_format($data['hight_open'],2).')</span>':number_format($data['hight_open'],2)?>元
			</div>			
			<div class="more_data">
			<span class="font_left_blank">销售运费:</span><?php echo $data['sales_freight']>0?'<span class="red">('.number_format($data['sales_freight'],2).')</span>':number_format($data['sales_freight'],2)?>元
			</div>
			
		</div>
		<div  class="more_data_out" >
		</div>				
		<div class="clear"></div>
		</div>
		<div class="horizontal_separate"></div>
		<!--  
		<div class="detail_little_title"><?php $ware_cost=$data['warehouse_fee']-$data['warehouse_rebate'];?>
			<span class="font_left_blank"><b>仓库成本:</b></span><?php echo $ware_cost>0?'<span class="red">('.number_format($ware_cost,2).')</span>':number_format(abs($ware_cost),2)?>元
		</div>
		<div class="more_data_container">
		<div  class="more_data_out" >
		<div class="more_data">
			<span class="font_left_blank">仓库费用:</span><?php echo $data['warehouse_fee']>0?'<span class="red">('.number_format($data['warehouse_fee'],2).')</span>':number_format($data['warehouse_fee'],2)?>元
			</div>
			<div class="more_data">&nbsp;</div>
		</div>			
		<div  class="more_data_out" >
			<div class="more_data">
			<span class="font_left_blank">仓库返利:</span><?php echo number_format($data['warehouse_rebate'],2)?>元
			</div>
		</div>						
		<div class="clear"></div>
		</div>		
		-->	
	</div>
	<div class="data_detail_each detail_margin">
		<div class="detail_each_title">
			<div class="float_left"><strong>库存预估利润：</strong></div>
			<a href="<?php echo Yii::app()->createUrl('table/storageProfit',array('search_url'=>json_encode($search)))?>">
			<div class="float_right color_r">￥			
			<?php echo number_format($data['s_profit'],2)?>			
			</div>
			</a>			
			<div class="clear"></div>
			<div style="width:100%;height:1px;border-bottom:1px solid #ccc"></div>
			<div  class="more_data_out"  style="width: 50%;margin-left:0;">
			<div class="more_data" style="color: #666;font-size:14px;">
				<span class="font_left_blank">吨均利润:</span><?php echo $data['s_weight']!=0?number_format($data['s_profit']/$data['s_weight'],2):0?>元/吨
				</div>
			</div>
			<div  class="more_data_out" >
			<div class="more_data" style="color: #666;font-size:14px;">
				<span class="font_left_blank">剩余重量:</span><?php echo number_format($data['s_weight'],3)?>吨
				</div>
			</div>		
			<div class="clear"></div>
		</div>
		<div class="detail_little_title">
			<span class="font_left_blank"><b>预估销售收入:</b>	</span><?php echo number_format($data['s_sale_money'],2)?>元
		</div>
		<div class="horizontal_separate"></div>
		<div class="detail_little_title"><?php $s_pur_cost=$data['s_purchase_money']+$data['s_pledge_fee']-$data['s_supply_rebate']+$data['s_purchase_freight'];?>
			<span class="font_left_blank"><b>采购成本:	</b></span><?php echo $s_pur_cost>0?'<span class="red">('.number_format($s_pur_cost,2).')</span>':number_format(abs($s_pur_cost),2)?>元
		</div>
		<div class="more_data_container">
		<div  class="more_data_out" >
		<div class="more_data">
			<span class="font_left_blank">采购价:</span><?php echo $data['s_purchase_money']>0?'<span class="red">('.number_format($data['s_purchase_money'],2).')</span>':number_format($data['s_purchase_money'],2);?>元
			</div>		
			<div class="more_data ">
				<span class="font_left_blank">发票成本:</span><?php echo $data['s_invoice']>0?'<span class="red">('.number_format($data['s_invoice'],2).')</span>':number_format($data['s_invoice'],2)?>元
				</div>
			<div class="more_data">
				<span class="font_left_blank">采购运费:</span><?php echo $data['s_purchase_freight']>0?'<span class="red">('.number_format($data['s_purchase_freight'],2).')</span>':number_format($data['s_purchase_freight'],2);?>元
			</div>
			<div class="more_data">
			<span class="font_left_blank">托盘费用:</span><?php echo $data['s_pledge_fee']>0?'<span class="red">('.number_format($data['s_pledge_fee'],2).')</span>':number_format($data['s_pledge_fee'],2);?>元
			</div>
			
		</div>
		<div  class="more_data_out" >
		<div class="more_data">
			<span class="font_left_blank">钢厂返利:</span><?php echo number_format($data['s_supply_rebate'],2)?>元
			</div>			
			<div class="more_data">&nbsp;</div><div class="more_data">&nbsp;</div>
		</div>			
			<div class="clear"></div>
		</div>
		<!-- 
		<div class="horizontal_separate"></div>
		<div class="detail_little_title">
			<span class="font_left_blank"><b>预估销售成本:	</b></span><?php echo $data['s_sale_subsidy']>0?'<span class="red">('.number_format($data['s_sale_subsidy'],2).')</span>':number_format($data['s_sale_subsidy'],2);?>元
		</div>
		<div class="more_data_container">
		<div  class="more_data_out" >
		<div class="more_data">
			<span class="font_left_blank">销售提成:</span><?php echo $data['s_sale_subsidy']>0?'<span class="red">('.number_format($data['s_sale_subsidy'],2).')</span>':number_format($data['s_sale_subsidy'],2);?>元
			</div>
			<div class="more_data">&nbsp;</div><div class="more_data">&nbsp;</div>
			<div class="more_data">&nbsp;</div>
		</div>
		<div  class="more_data_out" >
		</div>			
			<div class="clear"></div>
		</div>
		-->
		<!-- 
		<div class="horizontal_separate"></div>	
		<div class="detail_little_title"><?php $s_ware=$data['s_warehouse_fee']-$data['s_warehouse_rebate'];?>
			<span class="font_left_blank"><b>仓储成本:	</b></span><?php echo $s_ware>0?'<span class="red">('.number_format($s_ware,2).')</span>':number_format(abs($s_ware),2)?>元
		</div>
		<div class="more_data_container">
		<div  class="more_data_out" >
			<div class="more_data">
			<span class="font_left_blank">仓储费用:</span><?php echo $data['s_warehouse_fee']>0?'<span class="red">('.number_format($data['s_warehouse_fee'],2).')</span>':number_format($data['s_warehouse_fee'],2);?>元
			</div>
			<div class="more_data">&nbsp;</div>
		</div>
		<div  class="more_data_out" >
		<div class="more_data">
			<span class="font_left_blank">仓储返利:</span><?php echo number_format($data['s_warehouse_rebate'],2)?>元
			</div>
		</div>					
			<div class="clear"></div>
		</div>
		-->
	</div>
</div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
<script type="text/javascript">
var array1=<?php echo $brands;?>;
var array2=<?php echo $coms;?>;
var array3=<?php echo $vendors?$vendors:json_encode(array());?>;
$('#combo2').combobox(array3, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"venselect","comboval2");
$('#combo').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval");
$('#combobrand').combobox(array1, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand");
$('.check_box').click(function(){
	var check=$(this).attr('checked');
	if(check=='checked')
	{
		$(this).siblings().each(function(){
			$(this).removeAttr('checked');
		})
	}
})
$('.reset').click(function(){
		$('.check_box').removeAttr('checked');
	})
</script>

