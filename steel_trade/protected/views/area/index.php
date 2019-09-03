<div class="con_tit">	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('area/create',array('page'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建区域</button>
		</a>
	</div>
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
		<input placeholder="请输入名称" id="srarch" class="forreset" value="<?php echo $search->name?>" name="search[name]">
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="200" class='table_cell_first'>操作</th>
				<th width="500" class='table_cell_last'>名称</th>				
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
					$edit_url = Yii::app()->createUrl('area/update',array('id'=>$item->id));
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class='table_cell_first'>
					<div class="cz_list_btn">
						<a class="update_b" title="修改"  href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
						<a class="update_b del_user" title="删除"  name="<?php echo $item->id?>" style="margin-left:5px"><i class="icon icon-trash" ></i></a>
					</div>
				</td>
				<td class='table_cell_last'><?php echo $item->name ?></td>		
			</tr>
			<?php $i++;  }?>
		</tbody>		
	</table>	
</div>			
<script>
	$(function(){
		$(".del_user").click(function(){
			var item = this;
			confirmDialogWithCallBack("确定要删除吗",function(){
				$.post("<?php echo Yii::app()->createUrl('area/delete')?>", {
					'del_id' : $(item).attr('name'),
					'time':<?php echo time();?>
				}, function(data) {
					if(data=="updated"){
						confirmDialogRefresh("数据非最新，请刷新后操作！");	
						return;
					}else if(data=="deny"){
						confirmDialog("有仓库属于此区域,不能删除");	
						return;
					}
					window.location.reload();
				});
			},function(){return false;});
		});
	});
</script>