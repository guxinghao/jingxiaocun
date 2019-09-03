<?php
/* @var $dataProvider CActiveDataProvider */

?>
<div class="con_tit">
<!--	<div class="con_tit_cz">-->
<!--		<a href="<?php #echo Yii::app()->createUrl('user/create',array('page'=>$_REQUEST['page']))?>">-->
<!--		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">新建用户</button>-->
<!--		</a>-->
<!--	</div>-->
</div>
<form method="post" action="/index.php/user/index?page=1" url="/index.php/user/index?page=1">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入登录名或昵称" class="forreset" value="<?php echo $name?>" name="name" id="_name">
	</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>	
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data itable_title">
				<th width="60" class='table_cell_first'>操作</th>
				<th width="150" >登录名</th>
				<th width="150" >昵称</th>
				<th width="150" >业务组</th>
				<th width="150" >邀请码</th>
				<th width="150">绑定人数</th>
				<th width="150" >创建时间</th>
				<th width="200" >上次登录时间</th>
				<th width="200" >上次登录ip</th>
			</tr>
		</thead>
		<tbody>
	<?php	$i=0;
		foreach ( $items as $item ) {
			if(checkOperation("财务凭证")){
				$edit_url = Yii::app()->createUrl('user/updateVoucher',array('id'=>$item->id,'page'=>$_REQUEST['page']));
			}else{
				$edit_url = Yii::app()->createUrl('user/update',array('id'=>$item->id,'page'=>$_REQUEST['page']));
			}
			$link_url = Yii::app()->createUrl('wxUser/index',array('owned_by'=>$item->id));
		?>
			<tr class="" id="<?php echo $item->id;?>">
				<td class='table_cell_first'>
					<a class="update_b" title="修改"  href="<?php echo $edit_url;?>"><i class="icon icon-edit"></i></a>
<!--					<a class="update_b list_del" title="删除"  name="<?php echo $item->id?>" style="margin-left:5px"><i class="icon icon-trash" ></i></a>-->
				</td>
				
				<td><?php echo $item->loginname ?></td>
				<td><?php echo $item->nickname ?></td>
				<td><?php echo $item->team->name ?></td>
				<td><?php echo $item->invit_code ?></td>
				<td><a title="查看绑定" href="<?php echo $link_url;?>"><?php echo $item->count;?></a></td>
				<td><?php echo $item->created_at?date("Y-m-d H:i:s",$item->created_at):"";?></td>
				<td><?php if ($item->last_login_at) echo date("Y-m-d H:i:s",$item->last_login_at);?></td>
				<td><?php echo $item->last_login_ip ?></td>
				
			</tr>
	<?php $i++;}?>
			<tr>
				<td colspan='9' style="padding:0;height:auto">
				<?php paginate($pages,"user")?>
				</td>
			</tr>
		</tbody>
	</table>
</div>



<input type="hidden" id="db_table" value="User">
<input type="hidden" id="page" value=<?php echo $_GET['page']?>>
