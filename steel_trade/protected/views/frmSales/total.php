<style>
a{cursor:pointer}
.datatable{border-right:1px solid #989898}
#isearch input{line-height:16px}
</style>

<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $search->description?>" name="Turnover[description]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $_POST['Turnover']['start_time']?>" name="Turnover[start_time]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $_POST['Turnover']['end_time']?>" name="Turnover[end_time]"  >
		</div>
	</div>
	<div class="more_one">
			<div class="more_one_l">公　　司：</div>
			<div id="ttselect" class="fa_droplist">
				<input type="text" id="combott" class="forreset" value="<?php echo $search->title->short_name;?>" />
				<input type='hidden' id='combovaltt' value="<?php echo $search->title_id;?>"  class="forreset" name="Turnover[title_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">结算单位：</div>
			<div id="tgselect" class="fa_droplist">
				<input type="text" id="combotg" class="forreset" value="<?php echo $search->target->short_name;?>" />
				<input type='hidden' id='combovaltg' value="<?php echo $search->target_id;?>"  class="forreset" name="Turnover[target_id]"/>
			</div>
		</div>
	
	<div class="shop_more_one" style="width:0">
		<div class="more_select_box" style="top:90px;left:280px">
		<div class="more_one">
			<div class="more_one_l">托盘公司：</div>
			<div id="tpselect" class="fa_droplist">
				<input type="text" id="combotp" class="forreset" value="<?php echo $search->proxyCompany->short_name;?>" />
				<input type='hidden' id='combovaltp' value="<?php echo $search->proxy_company_id;?>"  class="forreset" name="Turnover[proxy_company_id]"/>
			</div>
		</div>
		
		<div class="more_one" >
			<div class="more_one_l">业务类型：</div>
			<select name="Turnover[turnover_type]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
       			<option <?php echo $search->turnover_type=="CGMX"?'selected="selected"':''?>  value="CGMX">采购明细往来</option>
	           	<option <?php echo $search->turnover_type=="CGHT"?'selected="selected"':''?>  value="CGHT">采购合同往来</option>
	           	<option <?php echo $search->turnover_type=="HTZX"?'selected="selected"':''?>  value="HTZX">合同执行往来</option>
	           	<option <?php echo $search->turnover_type=="HTBL"?'selected="selected"':''?>  value="HTBL">履约补录往来</option>
	           	<option <?php echo $search->turnover_type=="FYDJ"?'selected="selected"':''?>  value="FYDJ">运费</option>
	           	<option <?php echo $search->turnover_type=="CGZR"?'selected="selected"':''?>  value="CGZR">采购折让</option>
	           	<option <?php echo $search->turnover_type=="CGTH"?'selected="selected"':''?>  value="CGTH">采购退货</option>
	           	<option <?php echo $search->turnover_type=="TPCG"?'selected="selected"':''?>  value="TPCG">托盘采购</option>
	           	<option <?php echo $search->turnover_type=="TPSH"?'selected="selected"':''?>  value="TPSH">托盘赎回计息</option>
	           	<option <?php echo $search->turnover_type=="FKDJ"?'selected="selected"':''?>  value="FKDJ">付款登记</option>
	           	<option <?php echo $search->turnover_type=="XSMX"?'selected="selected"':''?>  value="XSMX">销售明细</option>
	           	<option <?php echo $search->turnover_type=="XSZR"?'selected="selected"':''?>  value="XSZR">销售折让</option>            	
				<option <?php echo $search->turnover_type=="XSTH"?'selected="selected"':''?>  value="XSTH">销售退货</option>
				<option <?php echo $search->turnover_type=="SKDJ"?'selected="selected"':''?>  value="SKDJ">收款登记</option>
				<option <?php echo $search->turnover_type=="CKFL"?'selected="selected"':''?>  value="CKFL">仓库返利</option>
				<option <?php echo $search->turnover_type=="GCFL"?'selected="selected"':''?>  value="GCFL">钢厂返利</option>
				<option <?php echo $search->turnover_type=="CCFY"?'selected="selected"':''?>  value="CCFY">仓储费用</option>
				<option <?php echo $search->turnover_type=="GKMX"?'selected="selected"':''?>  value="GKMX">高开往来</option>
		    </select>
			
		</div>
		<div class="more_one">
			<div class="more_one_l">往来类型：</div>
			<select name="Turnover[turnover_direction]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
       			<option <?php echo $search->turnover_direction=="need_pay"?'selected="selected"':''?>  value="need_pay">应付</option>
	           	<option <?php echo $search->turnover_direction=="need_charge"?'selected="selected"':''?>  value="need_charge">应收</option>
	           	<option <?php echo $search->turnover_direction=="payed"?'selected="selected"':''?>  value="payed">付款</option>
	           	<option <?php echo $search->turnover_direction=="charged"?'selected="selected"':''?>  value="charged">收款</option>            	
		    </select>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">往来状态：</div>
			<select name="Turnover[status]" class='form-control chosen-select forreset'>
		    	<option value='' selected='selected'>-全部-</option>
<!--		    	<option value='unsubmit' <?php #if($search->status=="unsubmit"){?>selected='selected'<?php #}?>>未提交</option>-->
		    	<option value='submited' <?php if($search->status=="submited"){?>selected='selected'<?php }?>>已提交</option>
		    	<option value='accounted' <?php if($search->status=="accounted"){?>selected='selected'<?php }?>>入账</option>
		    </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">乙　　单：</div>
			<select name="Turnover[is_yidan]" class='form-control chosen-select forreset'>
		    	<option value='2' selected='selected'>-全部-</option>
<!--		    	<option value='-1' <?php if($search->is_yidan==-1){?>selected='selected'<?php }?>>非甲乙单</option>-->
		    	<option value='0' <?php if($search->is_yidan==0){?>selected='selected'<?php }?>>甲单</option>
		    	<option value='1' <?php if($search->is_yidan==1){?>selected='selected'<?php }?>>乙单</option>
		    </select>
		</div>
	
	</div>
</div>
	
	
<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<div class="more_toggle" title="更多"></div>
<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none">
		<thead>
			<tr class="data">
				<th width="60" class='table_cell_first'>序号</th>
				<th width="160">创建时间</th>
				<th width="180">公司</th>
				<th width="180">结算单位</th>
				<th width="150" class="flex-col">往来业务类型</th>
				<th width="120"  class="flex-col">往来类型</th>
				<th width="150" class="flex-col" >代理付费公司</th>
				<th width="100" class="flex-col" >乙单</th>
				<th width="400"  class="flex-col">往来描述</th>
				<th width="100"  class="flex-col rightAlign">数量</th>
				<th width="140"  class="flex-col rightAlign">单价</th>
				<th width="100"  class="flex-col rightAlign">总金额</th>
				<th width="100" class="flex-col rightAlign">余额</th>
				<th width="100"  class="flex-col">往来状态</th>
				<th width="100" class="flex-col">负责人</th>
				<th width="100" class="flex-col">经办人</th>
				<th width="100" class="flex-col">入账人</th>
				
				
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
					$this_ye = $arr_ye[$item->title_id.",".$item->target_id];
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class='table_cell_first'><?php echo $i+1?></td>
				<td ><?php echo date("Y-m-d H:i:s",$item->created_at);?></td>
				<td ><?php echo $item->title->short_name?></td>
				<td ><?php echo $item->target->short_name?></td>
				<td ><?php 
				switch ( $item->turnover_type ){
					case "CGMX":echo "采购明细往来";break;
					case "CGHT":echo "采购合同往来";break;
					case "HTZX":echo "合同执行往来";break;
					case "HTBL":echo "履约补录往来";break;
					case "FYDJ":echo "运费";break;
					case "CGZR":echo "采购折让";break;
					case "CGTH":echo "采购退货";break;
					case "TPCG":echo "托盘采购";break;
					case "TPSH":echo "托盘赎回计息";break;
					case "FKDJ":echo "付款登记";break;
					case "XSMX":echo "销售明细";break;
					case "XSZR":echo "销售折让";break;
					case "XSTH":echo "销售退货";break;
					case "SKDJ":echo "收款登记";break;
					case "CKFL":echo "仓库返利";break;
					case "GCFL":echo "钢厂返利";break;
					case "CCFY":echo "仓储费用";break;
					case "GKMX":echo "高开往来";break;
					default:echo "未知";
				}
				?></td>
				<td ><?php 
				switch ($item->turnover_direction){
					case "need_pay":echo "应付";break;
					case "need_charge":echo "应收";break;
					case "payed":echo "付款";break;
					case "charged":echo "收款";break;			
				}
				?></td>
				<td ><?php echo $item->proxyCompany->short_name?></td>
				<td ><?php switch($item->is_yidan){
//					case 0:echo "甲单";break;
					case 1:echo "乙单";break;
					default:echo "";
//					default:echo "非甲乙单";
					
				}?></td>
				<td ><?php echo $item->description?></td>
				<td class="rightAlign"><?php echo number_format($item->amount,3)?></td>
				<?php if($item->turnover_type=="GKMX"){?>
				<td class="rightAlign"><?php echo number_format($item->price*0.83,2)."(".number_format($item->price,2).")"?></td>
				<?php }else{?>
				<td class="rightAlign"><?php echo number_format($item->price,2)?></td>
				<?php }?>
				<td class="rightAlign"><?php echo  number_format($item->fee>=0?$item->fee:-$item->fee,2);?></td>
				<td class="rightAlign"><?php echo number_format($arr_ye[$item->title_id.",".$item->target_id],2); $arr_ye[$item->title_id.",".$item->target_id]-=$item->fee?></td>	
			
				<td ><?php
				switch ($item->status){
					case "unsubmit"	:echo "未提交";break;
					case "submited"	:echo "已提交";break;
					case "accounted"	:echo "入账";break;
					default:echo "未知";
				}
				?></td>
				<td ><?php echo $item->owner->nickname?></td>
				<td ><?php echo $item->creater->nickname?></td>
				<td ><?php echo $item->account->nickname?></td>
				
			</tr>
			<?php $i++;  }?>
			
		</tbody>
		
	</table>
	
</div>			
<?php paginate($pages,"to")?>
<script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:580,
    	 fixedRightWidth:0,
      });
   });
</script>
<script>
	$(function(){
		$(".del_user").click(function(){
			if (!window.confirm("确定要删除吗")) {
				return false;
			} else {
				$.post("<?php echo Yii::app()->createUrl('dictTitle/delete')?>", {
					'del_id' : $(this).attr('name')
				}, function(data) {
					window.location.reload();
				});
			}
		});
		
	});
</script>

<script type="text/javascript">	
	$(function(){
		var array_tt=<?php echo $titles?$titles:"[]";?>;
		var array_tg=<?php echo $targets?$targets:"[]";?>;
		var array_tp=<?php echo $tps?$tps:"[]";?>;
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
		$('#combotg').combobox(array_tg, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"tgselect","combovaltg",false);
		$('#combotp').combobox(array_tp, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"tpselect","combovaltp",false);
	})
</script>