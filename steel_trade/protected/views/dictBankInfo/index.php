<style>
<!--
a{ cursor: pointer;}
#isearch input{ line-height: 16px;}
-->
</style>

<div class="con_tit">
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('dictBankInfo/create', array('title_id' => $_REQUEST['title_id'], 'page', $_REQUEST['page']));?>">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建公司账户</button>
		</a>
	</div>
</div>
<?php 
$form = $this->beginWidget('CActiveForm', array(
		'htmlOptions' => array(
				'id' => "user_search_form", 
				'enctype' => "multipart/form-data", 
		),
));
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $model->bank_name?>" name="DictBankInfo[bank_name]">
	</div>
	<div class="more_one" style="margin-top:8px;width:220px;">
		<div class="more_one_l" style="width: 70px;">公司抬头：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_t" class="forreset" value="<?php echo $model->dict_title_name;?>" />
			<input type='hidden' id='comboval_t' value="<?php echo $model->dict_title_id;?>"  class="forreset" name="DictBankInfo[dict_title_id]"/>
		</div>
	</div>
	<!-- 此处加下拉控件 -->
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>

<div class="div_table">
	<table id="datatable1" class="table datatable" cellspacing="1" algin="center">
		<thead>
			<tr class="data">
				<th class="table_cell_first" style="width: 6%;">操作</th>
				<th style="width: 9%;">开户银行</th>
				<th style="width: 9%;">公司抬头</th>
				<th style="width: 9%;">账户名称</th>
				<th style="width: 14%;">账号</th>
				<th style="width: 9%;">拼音</th>
				<th class="rightAlign" style="width: 10%;">期初金额</th>
				<th style="width: 8%;">创建时间</th>
				<th style="width: 9%;">创建人</th>
				<th style="width: 8%;">最后修改</th>
				<th style="width: 9%;">修改人</th>
			</tr>
		</thead>
		
		<tbody>
		<?php $i = 0; 
		foreach ($items as $item) {
			$edit_url = Yii::app()->createUrl('dictBankInfo/update', array('id' => $item->id, 'title_id' => $_REQUEST['title_id'], 'page' => $_REQUEST['page']));
		?>
			<tr id="<?php echo $item->id;?>" class="data">
				<td class="table_cell_first">
					<div class="cz_list_btn">
						<a class="update_b" title="修改" href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
						<a class="update_b del_user" title="删除" name="<?php echo $item->id;?>" style="margin-left: 5px;"><i class="icon icon-trash"></i></a>
					</div>
				</td>
				
				<td><?php echo $item->bank_name;?></td>
				<td>
				<?php 
					$list = $item->dictTitle;
					$count = count($list);
					$str="";
					for($i=0;$i<$count;$i++){$str.=$list[$i]->short_name.",";}
					$str= substr($str, 0, -1);
					$text = "";
					if($count > 1){
						$text .=$list[0]->short_name."...";
					}else{
						$text .=$list[0]->short_name;
					}
				?>
				<div class="car_no" title="<?php echo $str;?>"><?php echo $text;?>
				</div>
				</td>
				<td><?php echo $item->dict_name;?></td>
				<td><?php echo $item->bank_number;?></td>
				<td><?php echo $item->code;?></td>
				<td class="rightAlign"><?php echo number_format($item->initial_money, 2);?></td>
				<td><?php echo $item->created_at > 0 ? date('Y-m-d', $item->created_at) : '';?></td>
				<td><?php echo $item->creater->nickname;?></td>
				<td><?php echo $item->last_update_at > 0 ? date('Y-m-d', $item->last_update_at) : '';?></td>
				<td><?php echo $item->updater->nickname;?></td>
			</tr>
		<?php $i++; }?>
		</tbody>
	</table>
</div>
<?php paginate($pages,"gsyh");?>	

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
				$.post("<?php echo Yii::app()->createUrl('dictBankInfo/delete')?>", {
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
		var array_t=<?php echo $titles;?>;
		$('#combo_t').combobox(array_t, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_t",false);
	});
</script>