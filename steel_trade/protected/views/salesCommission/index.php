<?php $_status = array("-1"=>"已删除","0"=>"未审核","1"=>"已审核");?>
<div class="con_tit">
<?php if(checkOperation("导出")){?>
	<div class="con_tit_daoru btn_export" url="">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php 
		if(checkOperation("销售提成:新增")){
	?>
	<a href="<?php echo Yii::app()->createUrl('salesCommission/create')?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">登记提成</button>
	</a>
	<?php }?>
	</div>
</div>
<form method="post" action="" url="">
	<div class="search_body">
		<div class="shop_more_one1 short_shop_more_one">
			<div style="float:left;">年份：</div>
			<select name='search[year]' class='form-control chosen-select year forreset'>
			<option value='0' >-全部-</option>
	       	<?php for($i=2016;$i<=2020;$i++){?>
	            <option <?php  if(isset($search)){echo $i==$search['year']?'selected="selected"':''; }else{echo $i==date("Y")?'selected="selected"':'';}?> value='<?php echo $i;?>'><?php echo $i;?></option>
	      	<?php }?>
	        </select>
		</div>
		<div class="shop_more_one1 short_shop_more_one">
			<div style="float:left;">月份：</div>
			<select name='search[month]' class='form-control chosen-select month forreset'>
				<option value='0' selected='selected'>-全部-</option>
	       		<?php for($i=1;$i<=12;$i++){?>
	            <option <?php echo $i==$search['month']?'selected="selected"':'';?> value='<?php echo $i;?>'><?php echo $i;?></option>
	      		<?php }?>
	       </select>
		</div>
		<div class="shop_more_one1 short_shop_more_one">
			<div style="float:left;">业务员：</div>
			 <select name="search[owned]" class='form-control chosen-select forreset owned'>
	             <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){
	             	if(!Yii::app()->authManager->checkAccess('业务员',$k)){continue;}
	             	?>
            		<option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
           		 <?php }?>
	        </select>
		</div>
		<div class="shop_more_one1 short_shop_more_one" style="margin-left:10px;">
			<div style="float:left;">状态：</div>
			 <select name="search[status]" class='form-control chosen-select forreset owned'>
	             <option value='100' selected='selected'>-全部-</option>
	             <option value='0' <?php if($search[status]=='0'){?>selected='selected'<?php }?>>未审核</option>
	             <option value='1' <?php if($search[status]=='1'){?>selected='selected'<?php }?>>已审核</option>
	             <option value='-1' <?php if($search[status]=='-1'){?>selected='selected'<?php }?>>已删除</option>
	        </select>
		</div>
		<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:10px;">
		<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
	</div>
</form>
<div class="div_table"  data-sortable='true'>
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none;">
		<thead>
			<tr class="data">
				<th width="100" class='table_cell_first'>操作</th>
				<th width="100">业务员</th>
				<th width="60" class="flex-col">时间</th>
				<th width="60" class="flex-col">状态</th>
				<th width="110" class="flex-col rightAlign">销售重量</th>
				<th width="110" class="flex-col rightAlign">提成金额</th>
				<th width="100"  class="flex-col">创建人</th>
				<th width="140"  class="flex-col">创建时间</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($details as $li) {?>
			<tr>
			<td class='table_cell_first'>
				<div class="cz_list_btn" >
					<?php if($li->status == 0 && checkOperation("销售提成:审核")){?>
						<span class="check_form"  url="/index.php/salesCommission/check/<?php echo $li->id;?>?type=pass" title="审核" str="您确定审核通过此销售提成吗？"><img src="/images/shenhe.png"></span>
					<?php }
					if($li->status == 1 && checkOperation("销售提成:审核")){
					?>
					<span class="check_form" url="/index.php/salesCommission/check/<?php echo $li->id;?>?type=unpass" title="取消审核"><img src="/images/qxsh.png"></span>
					<?php }
					if($li->status == 0 && checkOperation("销售提成:新增")){
					?>
						<a class="update_b" href="/index.php/salesCommission/update/<?php echo $li->id;?>" title="编辑"><span><img src="/images/bianji.png"></span></a>
						<span class="delete_form" title="作废" url="/index.php/salesCommission/delete/<?php echo $li->id;?>"><img src="/images/zuofei.png"></span>
					<?php }?>
				</div>
			</td>
			<td><?php echo $li->owned->nickname;?></td>
			<td class="flex-col"><?php echo $li->date;?></td>
			<td class="flex-col"><?php echo $_status[$li->status];?></td>
			<td class="flex-col rightAlign"><?php echo number_format($li->weight,3);?></td>
			<td class="flex-col rightAlign"><?php echo number_format($li->money,2);?></td>
			<td class="flex-col"><?php echo $li->created->nickname;?></td>
			<td class="flex-col"><?php echo date("Y-m-d H:i:s",$li->created_at)?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>
<div class="total_data">
	<div class="total_data_one">总重：<?php echo number_format($totaldata->total_weight, 3);?></div>
	<div class="total_data_one">总金额：<?php echo number_format($totaldata->total_money, 2);?></div>
</div>
<?php paginate($pages,"commission_list")?>
<script type="text/javascript">
$(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:240,
    	 fixedRightWidth:0,
      });

	$(".delete_form").on("click",function(){
		var obj = $(this);
		deleteCom(obj);
	});
	$(".check_form").on("click",function(){
		var obj = $(this);
		CheckCom(obj);
	});
});
</script>