<div class="con_tit">
	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('warehouse/create',array('page'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建仓库</button>
		</a>
	</div>
</div>


<style>
a{cursor:pointer}
#isearch input{line-height:16px}
.cz_list_btn{height:22px}
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
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $search->name?>" name="Warehouse[name]">
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none">
		<thead>
			<tr class="data">
				<th width="60" class='table_cell_first'>操作</th>
				<th width="130" class="flex-col">仓库名称</th>
				<th width="100" class="flex-col">简称</th>
				<th width="120" class="flex-col">公司抬头</th>
				<th width="100" class="flex-col">联系人</th>
				<th width="100" class="flex-col">拼音</th>
				<th width="150" class="flex-col">标准码</th>
			 
				<th width="100" class="flex-col">区域</th>
				<th width="100" class="flex-col">传真</th>
				<th width="100" class="flex-col">电话</th>
				<th width="300" class="flex-col">地址</th>
				<th width="100" class="flex-col">是否引入</th>
				<th width="200" class="flex-col">备注</th>
				<th width="100" class="flex-col">先销后进</th>
				<th width="180" class="flex-col">创建时间</th>
				<th width="100" class="flex-col">创建者</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
					$edit_url = Yii::app()->createUrl('warehouse/update',array('id'=>$item->id,'page'=>$_REQUEST['page']));
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class='table_cell_first'>
					<div class="cz_list_btn">
						<a class="update_b" title="修改"  href='<?php echo $edit_url;?>'><i class="icon icon-edit"></i></a>
						<a class="update_b del_user" title="删除"  name='<?php echo $item->id?>' style="margin-left:5px"><i class="icon icon-trash" ></i></a>
					</div>
				</td>
				<td ><?php echo $item->name ?></td>
				<td ><?php echo $item->short_name ?></td>
				<td ><?php echo $item->title ?></td>
				<td ><?php echo $item->contact ?></td>
				<td ><?php echo $item->code?></td>
				<td ><?php echo $item->std?></td>
				
				<td ><?php echo WareArea::getName($item->area)?></td>
				<td ><?php echo $item->fax?></td>
				<td ><?php echo $item->mobile?></td>
				<td ><?php echo $item->address?></td>
				<td ><?php echo $item->is_jxc==1?"是":""?></td>
				<td ><?php echo $item->common?></td>
				<td ><?php echo $item->is_other==1?"是":""?></td>
				<td ><?php echo $item->created_at>0?date("Y-m-d H:i:s",$item->created_at):""?></td>
				<td ><?php echo $item->creater->nickname?></td>		
			</tr>
			<?php $i++;  }?>
		</tbody>
		
	</table>
	
</div>	
<?php paginate($pages,"ck")?>		
<script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:60,
    	 fixedRightWidth:0,
      });
   });
</script>
<script>
	$(function(){
		$(".del_user").click(function(){
			var item = this;
			confirmDialogWithCallBack("确定要删除吗",function(){
				$.post("<?php echo Yii::app()->createUrl('warehouse/delete')?>", {
					'del_id' : $(item).attr('name'),
					'time':<?php echo time();?>
				}, function(data) {
					if(data=="updated"){
						confirmDialogRefresh("数据非最新，请刷新后操作！");	
						return;
					}
					window.location.reload();
				});
			},function(){return false;});
		});
	});
</script>
