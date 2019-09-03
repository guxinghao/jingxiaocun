<style>
a{cursor:pointer}
.btn_sub{margin-bottom:0;line-height:24px}
</style>
<div class="con_tit">
	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('role/create',array('page'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建角色</button>
		</a>
	</div>
</div>

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
		<input placeholder="请输入角色名" class="forreset" value="<?php echo $search->name?>" name="AuthItem[name]" id="AuthItem_name">
	</div>
	<div class="srarch_box" style="margin-left:10px">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入描述" class="forreset" value="<?php echo $search->description?>" name="AuthItem[description]" id="AuthItem_description">
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>



<?php $this->endWidget ();?>	
<div   class="div_table" >
	<table  cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr >
				<th width="100" class='table_cell_first'>操作</th>
				<th width="100">角色名</th>
				<th width="100" >描述</th>
				<th width="100" class='table_cell_last'>优先级</th>
				
			</tr>
		</thead>
		<tbody>
		<?php $i=0;
			foreach ($auths as $item){
				$edit_url = Yii::app()->createUrl('role/update',array('name'=>$item->name,'page'=>$_REQUEST['page']));
		?>
		<tr class="" id="<?php echo $item->name?>">
			<td class='table_cell_first' style="text-align: center;">
			
				<div class="cz_list_btn">
					<a class="update_b operate_update" title="修改"  href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
					<a class="update_b del_user" title="删除"  name="<?php echo $item->name?>" style="margin-left:5px"><i class="icon icon-trash" ></i></a>
				</div>
				
			</td>
			<td class=""><?php echo $item->name ?></td>
			<td class=""><?php echo $item->description?></td>
			<td class="type table_cell_last"  ><input type="text" class="priority" name="<?php echo $item->name;?>" value="<?php echo $item->priority?>" style="width:50px;"></td>
			
		</tr>
		<?php }?>
		<tr class="">
			<input type="hidden" id="db_table" value="AuthItem">
			<input type="hidden" id="type" value="name">
 			<td colspan="13" class="table_cell_first table_cell_last">
 				<div class="page">
	 				<div class="btn_sub btn-sm btn-primary" id="edit_priority" style="margin-top:0"><span style="">保存优先级</span></div>
	 				<div class="btn_sub btn-sm btn-primary" id="clear_priority" style="margin-top:0"><span style="">清空优先级</span></div>
 				</div>
 			</td>
		</tr>
		</tbody>
	</table>
</div>
<?php paginate($pages,"role")?>
<script>
	$(function(){
		$(".reset_btn").click(function(){
			location.reload();
		});
		$(".del_user").click(function(){
			if (!window.confirm("确定要删除吗")) {
				return false;
			} else {
				$.post("<?php echo Yii::app()->createUrl('role/deleteAuth')?>", {
					'del_name' : $(this).attr('name')
				}, function(data) {
					window.location.reload();
				});
			}
		});
		
	});
</script>