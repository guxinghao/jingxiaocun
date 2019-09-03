<div class="con_tit">
</div>


<style>
a{cursor:pointer}

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
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $search->comment?>" name="Log[comment]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $_POST['Log']['start_time']?>" name="Log[start_time]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $_POST['Log']['end_time']?>" name="Log[end_time]"  >
		</div>
	</div>
	<div class="shop_more_one1 short_shop_more_one" style="margin:9px 0 0 5px;">
		<div style="float:left;">操作人：</div>
		<div id="ywyselect" class="fa_droplist">
			<select name="Log[created]" class='form-control chosen-select forreset owned'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
           	 	<option <?php echo $k==$_POST['Log']['created']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
           		<?php }?>
	        </select>
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
				<th width="100" class="table_cell_first">业务名称</th>
				<th width="100" >操作类型</th>
				<th width="300" >描述</th>
				<th width="100" >操作时间</th>
				<th width="100" >操作人</th>
				
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class="table_cell_first"><?php echo $item->business_name ?></td>
				<td ><?php echo $item->operation_type ?></td>
				<td ><?php echo $item->comment?></td>
				<td ><?php echo date("Y-m-d H:i:s",$item->created_at);?></td>
				<td ><?php echo $item->user->nickname?></td>			
			</tr>
			<?php $i++;  }?>
		</tbody>
		
	</table>
	
</div>		
<?php paginate($pages,"log")?>
<script type="text/javascript">
  $(function(){
//     $('#datatable1').datatable({
//    	 fixedLeftWidth:0,
//    	 fixedRightWidth:0,
//      });
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