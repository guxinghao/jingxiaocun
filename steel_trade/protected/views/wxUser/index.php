<?php
/* @var $dataProvider CActiveDataProvider */

?>
<style>
.update_b{cursor:pointer;}
</style>
<link rel="stylesheet"  type="text/css"  href="/css/colorbox.css"/>
<div class="con_tit">

</div>
<form method="post" action="/index.php/wxUser/index?page=1" >
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入登录名或昵称" class="forreset" value="<?php echo $search['name']?>" name="search[name]" id="_name">
	</div>
	<div class="more_one">
		<div class="more_one_l">业&nbsp;务&nbsp;员：</div>
			<select name="search[owned_by]" class='form-control chosen-select forreset owned'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($user_array as $k=>$v){?>
            	<option <?php echo $k==$search['owned_by']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">是否绑定：</div>
			<select name="search[linked]" class='form-control chosen-select forreset owned'>
	            <option value='0' selected='selected'>-全部-</option>
            	<option <?php echo $search['linked']=='yes'?'selected="selected"':''?>  value="yes">已绑定</option>
            	<option <?php echo $search['linked']=='no'?'selected="selected"':''?>  value="no">未绑定</option>
	        </select>
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
				<th width="120" >用户名</th>
				<th width="120" >登录名</th>
				<th width="120" >手机号码</th>
				<th width="120" >业务员</th>
				<th width="120" >公司数</th>
				<th width="120" >qq</th>
				<th width="120" >传真</th>
			</tr>
		</thead>
		<tbody>
	<?php	$i=0;
		foreach ( $items as $item ) {
			$edit_url = Yii::app()->createUrl('wxUser/update',array('id'=>$item['id']));
			$link_url = Yii::app()->createUrl('wxUser/link',array('id'=>$item['id'],'type'=>'user'));
		?>
			<tr class="" id="<?php echo $item->id;?>">
				<td class='table_cell_first'>
					<?php if(checkOperation('微信客户:修改')){?>
					<a class="update_b" title="修改"  href="<?php echo $edit_url;?>"><img src="/images/bianji.png"></a>
					<?php }if(checkOperation('微信客户:绑定')){	?>
					<a class="update_b colorbox" title="绑定"  url="<?php echo $link_url;?>" style="margin-left:5px;"><img src="/images/contact.png"></a>
					<?php }if(checkOperation('微信客户:删除')){?>
					<a class="update_b del_but" title="删除"  name="<?php echo $item['id']?>" style="margin-left:5px"><img  src="/images/zuofei.png"></a>
					<?php }?>
				</td>
				
				<td><?php echo $item['username'] ?></td>
				<td><?php echo $item['loginname'] ?></td>
				<td><?php echo $item['phone'] ?></td>
				<td><?php echo User::getUserName($item['user_id'])?></td>
				<td>
				<?php if(checkOperation('微信客户:绑定公司')){?>
				<a title="绑定公司" class="colorbox" url="<?php echo Yii::app()->createUrl('wxUser/link',array('type'=>'company','id'=>$item['id']))?>">
				<?php echo $item['all_com']?$item['all_com']:0; ?>
				</a>
				<?php }else{ echo $item['all_com']?$item['all_com']:0; }?>				
				</td>
				<td><?php echo $item['qq']?></td>
				<td><?php echo $item['fax']?></td>
			</tr>
	<?php $i++;}?>
		
		</tbody>
	</table>
</div>
<?php paginate($pages,"to")?>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.colorbox.js"></script>
<script type="text/javascript">
	var refresh=false;
	$('.colorbox').click(function(e){
		e.preventDefault();
		var url = $(this).attr('url');
		$.colorbox({
			href:url,
			opacity:0.6,
			iframe:true,
			title:"",
			width: "700px",
			height: "600px",
			overlayClose: false,
			speed: 0,
			onClosed:function(){if(refresh){window.location.reload()};},
		});
	});

	$(".del_but").click(function(){
		var item = this;
		confirmDialogWithCallBack("确定要删除吗",function(){
			var url="<?php echo Yii::app()->createUrl('wxUser/delete')?>";
			var del_id=$(item).attr('name');
			var time=<?php echo time();?>;
			$.post(url, {
				'del_id' : del_id,
				'time':time
			}, function(data) {
				if(data=="updated"){
					confirmDialogRefresh("数据非最新，请刷新后操作！");	
					return;
				}else if(data=="deny"){
					confirmDialog2("此用户有关联的公司,确定要删除吗",url+'?del_id='+del_id+'&time='+time+'&sure=yes');	
					return;
				}
				window.location.reload();
			});
		},function(){return false;});
	});

	
</script>
