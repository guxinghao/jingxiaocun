<div class="con_tit">
	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('dictTitle/create',array('page'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建抬头</button>
		</a>
	</div>
</div>


<style>
a{cursor:pointer}

#isearch input{line-height:16px}
</style>
<?php
/* @var $this AdminUserController */
/* @var $dataProvider CActiveDataProvider */

//$this->widget ( 'AdminHeaderWidget', array (
//		'title' => '公司抬头管理',
//		'opButtons' => array (
//				array (
//						'url' => Yii::app ()->createUrl ( 'dictTitle/create',array('page'=>$_REQUEST['page']) ),
//						'type' => 'add' 
//				) 
//		) 
//) );
?>


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
		<input placeholder="请输入抬头" id="srarch" class="forreset" value="<?php echo $search->name?>" name="DictTitle[name]">
	</div>
	<div class="srarch_box" style="margin-left:10px">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入拼音" id="srarch" class="forreset" value="<?php echo $search->code?>" name="DictTitle[code]">
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
<!--	
<div class="detail_find" id="isearch" style="line-height: 20px;">
	<div class="input_row">
		<div class="search_span first">公司抬头:</div>
		<div><?php echo $form->textField($search,'name',array('class'=>'searchinput'))?></div>
		<div class="search_span">拼 音:</div>
		<div><?php echo $form->textField($search,'code',array('class'=>'searchinput'))?></div>
		<input class="find_btn" title="查询" value="查询" type="submit" style="background:#fff;border:1px solid #ccc;height:18px;font-size:10px;line-height:10px;"/>
		<input class="reset_btn" title="重置" value="重置" type="reset" style="border:0" />
	</div>
</div>
-->
<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="100" class="table_cell_first">操作</th>
				<th width="300" >公司抬头</th>
				<th width="300" >公司简称</th>
				<th width="300" >拼音</th>
				<th width="300" >银行账号数</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
					$edit_url = Yii::app()->createUrl('dictTitle/update',array('id'=>$item->id,'page'=>$_REQUEST['page']));
					$bank_url = Yii::app()->createUrl('dictBankInfo/index',array('title_id'=>$item->id));
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class="table_cell_first">
					<div class="cz_list_btn">
						<a class="update_b" title="修改"  href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
						<a class="update_b del_user" title="删除"  name="<?php echo $item->id?>" style="margin-left:5px"><i class="icon icon-trash" ></i></a>
					</div>
				</td>
				<td ><?php echo $item->name ?></td>
				<td ><?php echo $item->short_name ?></td>
				<td ><?php echo $item->code?></td>	
				<td ><a href="<?php echo $bank_url;?>" style="width:100%;height:100%;float:left"><?php echo count($item->banks)?></a></td>		
			</tr>
			<?php $i++;  }?>
		</tbody>
		
	</table>
	
</div>	
<?php paginate($pages,"gstt")?>		
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
				$.post("<?php echo Yii::app()->createUrl('dictTitle/delete')?>", {
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