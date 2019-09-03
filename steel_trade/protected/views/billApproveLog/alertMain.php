
<?php if ($items) {?>
<style>
<!--
.check_background{ top: 155px;}
.check_div_short{ height: 305px;}
.check_str_short{ height: 230px; margin-top: 0; text-align: justify;}
.status_body{ float: left; width: 480px; height: 201px; margin: 0 10px; overflow: hidden;}
.status_table{ float: left; width: 497px; height: 201px; border-top: 1px solid #ccc; border-right: 1px solid #ccc; overflow-x: hidden; overflow-y: auto;}
.status_table .table{ margin-bottom: 0;}
.status_table .table th, .status_table .table td{ padding: 3px 8px;}
.status_table table th, .status_table table td{ border-left: 1px solid #d8d8d8;}
-->
</style>

<div class="search_title" style="line-height: 42px;">审核记录</div>
<div class="status_body">
	<div id="status_table" class="status_table">
		<table id="cght_tb" class="table">
			<thead>
				<tr>
					<th style="width: 100px;">审核人</th>
					<th style="width: 163px;">审核时间</th>
					<th style="width: 205px;">描述</th>
					<th style="width: 10px;"></th>
				</tr>
			</thead>
			
			<tbody>
			<?php foreach ($items as $item) {?>
				<tr>
					<td style="width: 100px;"><?php echo $item->approver->nickname;?></td>
					<td style="width: 155px;"><?php echo $item->created_at > 0 ? date("Y-m-d H:i:s", $item->created_at) : '';?></td>
					<td style="width: 213px;"><?php echo $item->description;?></td>
					<td style="width: 10px;"></td>
				</tr>
			<?php }?>
			</tbody>
		</table>
	</div>
</div>

<?php } else {?>
<h3>暂无审核记录</h3>
<?php }?>

<script type="text/javascript">
<!--
<?php if ($items) {?>
if (document.getElementById('cght_tb').offsetHeight > 115) 
{
	document.getElementById('status_table').style.width= '510px'; //设置宽度 
}
<?php }?>
//-->
</script>

