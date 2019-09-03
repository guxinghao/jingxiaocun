<div class="con_tit">
</div>


<style>
a{cursor:pointer}
td.data_value {word-break:break-all}
#isearch input{line-height:16px}
.div_table table td span.data_block{cursor:auto;margin-left:5px}
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
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $search->table_name?>" name="LogDetail[table_name]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $_POST['LogDetail']['start_time']?>" name="LogDetail[start_time]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $_POST['LogDetail']['end_time']?>" name="LogDetail[end_time]"  >
		</div>
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="150" class="table_cell_first">表名称</th>
				<th width="250" >修改</th>
				<th width="150" >操作时间</th>
				<th width="100" class="table_cell_last">操作人</th>
				
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class="table_cell_first"><?php echo $item->table_name ?></td>
				<td class="data_value">
					<?php $arr_n = json_decode($item->newValue,true);
						$arr_o = json_decode($item->oldValue,true);
						if(!$arr_n)$arr_n = array();
						if(!$arr_o)$arr_o = array();
						if($arr_n['id']){
							?>
							<span class="data_block">【id】：[<?php echo $arr_n['id'];?>]</span><br/>
					<?php 
						}
						foreach ($arr_n as $k=>$v){
							if($v!=$arr_o[$k]){?>
								<span class="data_block">【<?php echo $k?>】：[<?php echo $arr_o[$k]?>]</span><span class="data_block" style="color:red">更改为[<?php echo $v?>]</span><br/>
					<?php }}?>
				</td>
				<td ><?php echo date("Y-m-d H:i:s",$item->created_at);?></td>
				<td class="table_cell_last"><?php echo $item->user->nickname?></td>			
			</tr>
			<?php $i++;  }?>
		</tbody>
		
	</table>
	
</div>			
<?php paginate($pages,"log_d")?>
