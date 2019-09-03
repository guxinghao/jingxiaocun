<div class="con_tit">
	
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('dictCompany/create',array('page'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建结算单位</button>
		</a>
	</div>
</div>


<style>
a{cursor:pointer}
.cz_list_btn{height:22px}
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
		<input placeholder="请输入公司名" id="srarch" class="forreset" value="<?php echo $search->name?>" name="DictCompany[name]">
	</div>
	<div class="srarch_box" style="margin-left:10px">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入拼音" id="srarch" class="forreset" value="<?php echo $search->code?>" name="DictCompany[code]">
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="90" class="table_cell_first">操作</th>
				<th width="240" >公司名称</th>
				<th width="200" class="flex-col">简称</th>
				<th width="100" class="flex-col">拼音</th>
				<th width="80" class="flex-col" >供应商</th>
				<th width="90" class="flex-col" >托盘公司</th>
				<th width="80" class="flex-col" >采购商</th>
				<th width="100" class="flex-col" >物流供应商</th>
				<th width="80" class="flex-col" >代销</th>
				<th width="80" class="flex-col" >高开</th>
				<th width="80" class="flex-col" >仓库</th>
				<th width="80" class="flex-col rightAlign" >运费</th>
				<th width="150" class="flex-col" >赎回限制等级</th>
				<th width="100" class="flex-col rightAlign" >托盘天数</th>
				<th width="120" class="flex-col rightAlign" >托盘利息利率</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
					$edit_url = Yii::app()->createUrl('dictCompany/update',array('id'=>$item->id,'page'=>$_REQUEST['page']));
					$contact_url = Yii::app()->createUrl('companyContact/index',array('dict_company_id'=>$item->id));
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<td class="table_cell_first">
					<div class="cz_list_btn">
					    <a class="update_b" title="联系人管理" href="<?php echo $contact_url;?>"><span><img style="height:14px" src="/images/contact.png"/></span></a>
						<a class="update_b" title="修改" style="margin-left:5px" href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
						<a class="update_b del_user" title="删除"  name="<?php echo $item->id?>" style="margin-left:5px"><i class="icon icon-trash" ></i></a>
					</div>
				</td>
				<td ><?php echo $item->name ?></td>
				<td ><?php echo $item->short_name ?></td>
				<td ><?php echo $item->code?></td>
				<td ><?php echo $item->is_supply==1? "是":""; ?></td>
				<td ><?php echo $item->is_pledge==1? "是":""; ?></td>
				<td ><?php echo $item->is_customer==1? "是":""; ?></td>
				<td ><?php echo $item->is_logistics==1? "是":""; ?></td>
				<td ><?php echo $item->is_dx==1? "是":""; ?></td>
				<td ><?php echo $item->is_gk==1? "是":""; ?></td>
				<td ><?php echo $item->is_warehouse==1? "是":""; ?></td>
				<td class="rightAlign"><?php echo $item->is_supply==1?number_format($item->fee,0):""; ?></td>
				<td ><?php if($item->is_pledge==1&&$item->level>0){echo $item->level==1?"根据产地":"根据产地、品名";} ?></td>
				<td class="rightAlign"><?php echo $item->is_pledge==1?$item->pledge_length:""; ?></td>
				<td class="rightAlign"><?php echo $item->is_pledge==1?number_format($item->pledge_rate,4):""; ?></td>
			</tr>
			<?php $i++;  }?>
		</tbody>
	</table>
</div>		
<?php paginate($pages,"jsdw")?>	
<script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:330,
    	 fixedRightWidth:0,
      });
   });
</script>
<script>
	$(function(){
		$(".del_user").click(function(){
			var item = this;
			confirmDialogWithCallBack("确定要删除吗",function(){
				$.post("<?php echo Yii::app()->createUrl('dictCompany/delete')?>", {
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