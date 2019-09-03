<div class="con_tit">
	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('bankInfo/create',array('page'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建结算账户</button>
		</a>
	</div>
</div>


<style>
a{cursor:pointer}
#isearch input{line-height:16px}
.del_user{ margin-left: 5px;}
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
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $search->bank_name?>" name="BankInfo[bank_name]">
	</div>
	<div class="more_one" style="margin-top:8px;width:220px;">
		<div class="more_one_l" style="width: 70px;">结算单位：</div>
		<div id="comselect_c" class="fa_droplist">
			<input type="text" id="combo_c" class="forreset" value="<?php echo $search->dict_company_name;?>" />
			<input type='hidden' id='comboval_c' value="<?php echo $search->dict_company_id;?>"  class="forreset" name="BankInfo[dict_company_id]"/>
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
				<th width="55" class="table_cell_first">操作</th>
				<th width="120" >开户银行</th>
				<th width="80" >结算单位</th>
				<th width="100" >账户名称</th>
				<th width="90" >账号</th>
				<th width="70" >拼音</th>
<!--				<th width="70" >期初金额</th>-->
				<th width="120" >创建时间</th>
				<th width="70" >创建人</th>
				<th width="120" >最后修改</th>
				<th width="70" >修改人</th>
			</tr>
		</thead>
		<tbody>
		<?php $i = 0; 
		foreach ($items as $item) { 
			$edit_url = Yii::app()->createUrl('bankInfo/update', array('id' => $item->id, 'page' => $_REQUEST['page']));
		?>
			<tr id="<?php echo $item->id?>" class="data">
				<td  class="table_cell_first">
					<div class="cz_list_btn">
						<a class="update_b" title="编辑" href="<?php echo $edit_url;?>"><span><img src="/images/bianji.png"/></span></a>
						<a class="update_b del_user" title="删除"  name="<?php echo $item->id?>"><img src="/images/zuofei.png"/></a>
					</div>
				</td>
				<td><?php echo $item->bank_name ?></td>
				<td><span title="<?php echo $item->dictCompany->name;?>"><?php echo $item->dictCompany->short_name;?></span></td>
				<td><?php echo $item->company_name ?></td>
				<td><?php echo $item->bank_number ?></td>
				<td><?php echo $item->code ?></td>
<!--				<td><?php #echo $item->money?></td>-->
				<td><?php echo date("Y-m-d",$item->created_at); ?></td>
				<td><?php echo $item->creater->nickname?></td>
				<td><?php echo date("Y-m-d",$item->last_update_at);?></td>
				<td><?php echo $item->updater->nickname?></td>			
			</tr>
			<?php $i++;  }?>
		</tbody>
		
	</table>
	
</div>			
<?php paginate($pages,"jsyh")?>
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
				$.post("<?php echo Yii::app()->createUrl('bankInfo/delete')?>", {
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