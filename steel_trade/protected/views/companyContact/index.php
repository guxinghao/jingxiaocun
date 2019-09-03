<div class="con_tit">
	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('companyContact/create',array('page'=>$_REQUEST['page'],'dict_company_id'=>$_GET['dict_company_id']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建联系人</button>
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
		<input placeholder="请输入姓名" id="srarch" class="forreset" value="<?php echo $search->name?>" name="CompanyContact[name]">
	</div>
	<div class="more_one" style="margin-top:8px;width:220px;">
		<div class="more_one_l" style="width: 70px;">结算单位：</div>
		<div id="comselect_c" class="fa_droplist">
			<input type="text" id="combo_c" class="forreset" value="<?php echo $search->company->short_name;?>" />
			<input type='hidden' id='comboval_c' value="<?php echo $search->dict_company_id;?>"  class="forreset" name="CompanyContact[dict_company_id]"/>
		</div>
	</div>
	<!-- 此处加下拉控件 -->
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="50" class="table_cell_first">操作</th>
				<th width="100" >姓名</th>
				<th width="180" >结算单位</th>
				<th width="100" >手机</th>
				<th width="80" >是否默认</th>
				<th width="180" >创建时间</th>
				<th width="80" >创建人</th>
				<th width="180" >最后修改</th>
				<th width="70" >修改人</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
					$edit_url = Yii::app()->createUrl('companyContact/update',array('id'=>$item->id,'page'=>$_REQUEST['page'],'dict_company_id'=>$_GET['dict_company_id']));
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td  class="table_cell_first">
					<div class="cz_list_btn">
						<a class="update_b" title="修改"  href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
						<a class="update_b del_user" title="删除"  name="<?php echo $item->id?>" style="margin-left:5px"><i class="icon icon-trash" ></i></a>
					</div>
				</td>
				<td ><?php echo $item->name ?></td>
				<td ><?php echo $item->company->short_name ?></td>
				<td ><?php echo $item->mobile ?></td>
				<td ><?php echo $item->is_default==1?"是":"" ?></td>
				<td ><?php echo $item->created_at?date("Y-m-d H:i:s",$item->created_at):""; ?></td>
				<td ><?php echo $item->creater->nickname?></td>
				<td ><?php echo $item->last_update_at?date("Y-m-d H:i:s",$item->last_update_at):"";?></td>
				<td ><?php echo $item->updater->nickname?></td>			
			</tr>
			<?php $i++;  }?>
		</tbody>
		
	</table>
	
</div>			
<?php paginate($pages,"cc")?>
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
			var item = this;
			confirmDialogWithCallBack("确定要删除吗",function(){
				$.post("<?php echo Yii::app()->createUrl('companyContact/delete')?>", {
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
		var array_c=<?php echo $coms;?>;
		$('#combo_c').combobox(array_c, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_c","comboval_c",false);
	});
</script>