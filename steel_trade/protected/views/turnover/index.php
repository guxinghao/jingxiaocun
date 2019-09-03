<style>
a{cursor:pointer}
#isearch input{line-height:16px}
</style>
<?php $yidan_auto=!checkOperation('不看甲乙单');?>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('turnover/indexExport');?>">
		<img src="/images/daochu.png">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
</div>
<form method="post" action="/index.php/turnover/index?page=1" url="/index.php/turnover/index?page=1">
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
			<div id="title_select" class="fa_droplist">
				<input type="text" id="title_combo" class="forreset" value="<?php echo $search->title->short_name;?>" />
				<input type='hidden' id='title_val' value="<?php echo $search->title_id;?>"  class="forreset" name="Turnover[title_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">结算单位：</div>
			<div id="tgselect" class="fa_droplist">
				<input type="text" id="combotg" class="forreset" value="<?php echo $search->target->name;?>" />
				<input type='hidden' id='combovaltg' value="<?php echo $search->target_id;?>"  class="forreset" name="Turnover[target_id]"/>
			</div>
		</div>
	
	<div class="shop_more_one" style="width:0">
		<div class="more_select_box" style="top:125px;left:280px">
		<div class="more_one">
			<div class="more_one_l">客户：</div>
			<div id="tgselect1" class="fa_droplist">
				<input type="text" id="combotg1" class="forreset" value="<?php echo $search->client->name;?>" />
				<input type='hidden' id='combovaltg1' value="<?php echo $search->client_id;?>"  class="forreset" name="Turnover[client_id]"/>
			</div>
		</div>
		<div class="more_one">
			<div class="more_one_l">托盘公司：</div>
			<div id="tpselect" class="fa_droplist">
				<input type="text" id="combotp" class="forreset" value="<?php echo $search->proxyCompany->short_name;?>" />
				<input type='hidden' id='combovaltp' value="<?php echo $search->proxy_company_id;?>"  class="forreset" name="Turnover[proxy_company_id]"/>
			</div>
		</div>
		
		<div class="more_one" >
			<div class="more_one_l">类　　别：</div>
			<select name="Turnover[big_type]" class='form-control chosen-select forreset'>
				<option value='' selected='selected'>-全部-</option>	
				<?php foreach (Turnover::$bigType as $key => $value) {?>
				<option value="<?php echo $key;?>"<?php echo $search->big_type == $key ? 'selected="selected"' : '';?>><?php echo $value;?></option>
				<?php }?>
			</select>
		</div>
		
		<div class="more_one" >
			<div class="more_one_l">业务类型：</div>
			<select name="Turnover[turnover_type]" class='form-control chosen-select forreset'>
				<option value='' selected='selected'>-全部-</option>
				<?php foreach (Turnover::$turnover_type as $key => $value) {?>
				<option value="<?php echo $key;?>"<?php echo $search->turnover_type == $key ? ' selected="selected"' : '';?>><?php echo $value;?></option>
				<?php }?>
		    </select>
		</div>
		
		<div class="more_one">
			<div class="more_one_l">往来类型：</div>
			<select name="Turnover[turnover_direction]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>
	            <?php foreach (Turnover::$turnover_direction as $key => $value) {?>
	            <option value="<?php echo $key;?>"<?php echo $search->turnover_direction == $key ? ' selected="selected"' : '';?>><?php echo $value;?></option>
	            <?php }?>	             
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
		<?php if($yidan_auto){?>
		<div class="more_one">
			<div class="more_one_l">乙　　单：</div>
			<select name="Turnover[is_yidan]" class='form-control chosen-select forreset'>
		    	<option value='0' selected='selected'>-全部-</option>
<!--		    	<option value='-1' <?php if($search->is_yidan==-1){?>selected='selected'<?php }?>>非甲乙单</option>-->
		    	<option value='2' <?php if($search->is_yidan==2){?>selected='selected'<?php }?>>甲单</option>
		    	<option value='1' <?php if($search->is_yidan==1){?>selected='selected'<?php }?>>乙单</option>
		    </select>
		</div>
		<?php }?>
		<div class="more_one">
			<div class="more_one_l">审单状态：</div>
			<select id="confirmed" name="Turnover[confirmed]" class='form-control chosen-select forreset'>
		    	<option value='2' selected='selected'>-全部-</option>
		    	<option value='0' <?php if($search->confirmed=='0'){?>selected='selected'<?php }?>>未审单</option>
		    	<option value='1' <?php if($search->confirmed=='1'){?>selected='selected'<?php }?>>已审单</option>
		    </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">业&nbsp;务&nbsp;员：</div>
				<select name="Turnover[created_by]" class='form-control chosen-select forreset owned'>
				<?php if(checkOperation('全部往来汇总')){?>
		            <option value='0' selected='selected'>-全部-</option>
		             <?php foreach ($user_array as $k=>$v){?>
	            <option <?php echo $k==$search->created_by?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
	            <?php }}else{?>
	            	<option value="<?php echo currentUserId()?>"><?php echo Yii::app()->user->nickname;?></option>
	            <?php }?>
		        </select>
		</div>
		<div class="more_one">
			<input id="title_rl" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('瑞亮物资');?>" name="title_rl"<?php echo $_REQUEST['title_rl'] ? ' checked="checked"' : '';?> />
			<label for="title_rl" class="lab_check_box">瑞亮物资</label>
			<input id="title_cx" type="checkbox" class="check_box" value="<?php echo DictTitle::getTitleId('乘翔实业');?>" name="title_cx"<?php echo $_REQUEST['title_cx'] ? ' checked="checked"' : '';?> />
			<label for="title_cx" class="lab_check_box">乘翔实业</label>
			<input id="title_other" type="checkbox" class="check_box" value="other" name="other"<?php echo $_REQUEST['other'] ? ' checked="checked"' : '';?> />
			<label for="title_other" class="lab_check_box">其他</label>
		</div>
	</div>
</div>
<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
<div class="more_toggle" title="更多"></div>
<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>		
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none">
		<thead>
			<tr class="data">
				<th width="20" class=' table_cell_first'></th>
				<th width="80">发生时间</th>
				<th width="60">公司</th>
				<th width="70">结算单位</th>
				<th width="70">客户</th>
				<th width="60" class="flex-col">负责人</th>
				<th width="90"  class="flex-col rightAlign">数量</th>
				<th width="70"  class="flex-col rightAlign">单价</th>
				<th width="100"  class="flex-col rightAlign">金额小计</th>
				<th width="100"  class="flex-col rightAlign">收付金额</th>
				<th width="100" class="flex-col rightAlign">余额</th>
				<?php if($yidan_auto){?>
				<th width="40" class="flex-col" >乙单</th>
				<?php }?>
				<th width="70"  class="flex-col">往来状态</th>
				<th width="400"  class="flex-col">往来描述</th>				
				<th width="100" class="flex-col">往来业务类型</th>				
				<th width="90" class="flex-col" >代理付费公司</th>				
				<th width="70" class="flex-col" >类别</th>
				<th width="90"  class="flex-col">往来类型</th>
				<th width="70" class="flex-col">经办人</th>
				<th width="70" class="flex-col">入账人</th>
				
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				if(intval($_REQUEST['page'])<2)
				{
					echo '<tr class="data">
				<td class="table_cell_first"></td>
				<td >结转</td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td class="rightAlign"></td>
				<td class="rightAlign"></td>
				<td class="rightAlign"></td>
				<td class="rightAlign"></td>
				<td class="rightAlign">'.number_format($qichu_y,2).'</td>'.($yidan_auto?'<td ></td>':'').'						
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
			</tr>';
				}


				foreach ($items as $item){
					$this_ye = $arr_ye[$item->title_id.",".$item->target_id];
					
					$qichu_y = $qichu_y + $item->fee;
					
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class='table_cell_first text-center'><?php echo $i+1?></td>
				<td ><?php echo $item->created_at > 0 ? date("Y-m-d ",$item->created_at) : '';?></td>
				<td ><?php echo $item->title->short_name?></td>
				<td ><span title="<?php echo $item->target->name?>" ><?php echo $item->target->short_name?></span></td>
				<td ><span title="<?php echo $item->client->name?>" ><?php echo $item->client->short_name?></span></td>
				<td ><?php echo $item->owner->nickname?></td>
				<td class="rightAlign"><?php echo $item->amount>0?number_format($item->amount,3):'';?></td>
				<?php if($item->turnover_type=="GKMX"&&$item->turnover_direction!='need_charge'){?>
				<td class="rightAlign"><?php echo number_format($item->price*0.83,2)."(".number_format($item->price,2).")"?></td>
				<?php }else{?>
				<td class="rightAlign"><?php echo $item->price>0?number_format($item->price,2):'';?></td>
				<?php }?>
				<td class="rightAlign">
					<?php if($item->turnover_direction == "need_pay" || $item->turnover_direction == "need_charge") echo  number_format(-$item->fee,2);?>
				</td>
				<td class="rightAlign"><?php if($item->turnover_direction == "payed" || $item->turnover_direction == "charged") echo  number_format($item->fee,2);?></td>
				<td class="rightAlign"><?php echo number_format($qichu_y,2); //$arr_ye[$item->title_id.",".$item->target_id]+=$item->fee?></td>
				<?php if($yidan_auto){?>
				<td ><?php echo $item->is_yidan == 1 ? "乙单" : "";?></td>
				<?php }?>
				<td ><?php
				switch ($item->status){
					case "unsubmit"	:echo "未提交";break;
					case "submited"	:echo "已提交";break;
					case "accounted"	:echo "入账";break;
					default:echo "未知";
				}
				?></td>	
				<td ><?php echo $item->description?></td>
				
				<td ><?php echo Turnover::$turnover_type[$item->turnover_type] ? Turnover::$turnover_type[$item->turnover_type] : "未知";?></td>
				
				<td ><span title="<?php echo $item->proxyCompany->name?>" ><?php echo $item->proxyCompany->short_name?></span></td>
				
				<td ><?php echo Turnover::$bigType[$item->big_type];?></td>
				<td ><?php echo Turnover::$turnover_direction[$item->turnover_direction];?></td>
				
				<td ><?php echo $item->creater->nickname?></td>
				<td ><?php echo $item->account->nickname?></td>
				
			</tr>
			<?php $i++;  }?>
			<tr class="data">
				<td class="table_cell_first" colspan="5">合计</td>
				<td ></td>
				<td class="rightAlign"></td>
				<td class="rightAlign"></td>
				<td class="rightAlign"><?php echo number_format(-$totaldata['one'],2);?></td>
				<td class="rightAlign"><?php echo number_format($totaldata['two'],2);?></td>
				<td class="rightAlign"></td>	
				<?php if($yidan_auto){?>
				<td ></td>
				<?php }?>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
				<td ></td>
			</tr>
		</tbody>
	</table>
</div>
<?php paginate($pages,"to")?>
<script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:370,
    	 fixedRightWidth:0,
      });
   });
</script>

<script type="text/javascript">	
	$(function(){
		var array_tt=<?php echo $titles;?>;
		var array_tg=<?php echo $targets;?>;		
		var array_tp=<?php echo $tps?$tps:json_encode(array());?>;
		//var array_ywy=<?php echo $user_array;?>;
		$('#title_combo').combobox(array_tt, {},"title_select","title_val",false);
		$('#combotg').combobox(array_tg, {},"tgselect","combovaltg",false);
		$('#combotg1').combobox(array_tg, {},"tgselect1","combovaltg1",false);
		$('#combotp').combobox(array_tp, {},"tpselect","combovaltp",false);
		//$('#comboywy').combobox(array_ywy, {},"ywyselect","combovalywy",false);
		$(".reset").click(function(){
			$("#title_rl, #title_cx, #title_other").removeAttr("checked");
		});
		$("#title_rl, #title_cx, #title_other").bind('click', function() {
			checkTitle();
		});
	});
</script>