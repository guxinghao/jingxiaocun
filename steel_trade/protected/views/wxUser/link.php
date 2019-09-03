<html>
<head>
<style>
.submit_form{
	cursor:pointer;
}
.button_left{
	float:left;
	width: 50%;
	line-height:50px;
	text-align:center;
	border:1px solid #ccc;
	border-radius:5px;
	font-size:14px;
	cursor:pointer;
	color:#000;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin.css" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/css/zui.min.css"	rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css"	rel="stylesheet" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery_ui.css" />
<link rel="stylesheet" type="text/css"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery-ui-timepicker-addon.css" />
<link	href="<?php echo Yii::app()->request->baseUrl; ?>/zui/lib/datetimepicker/datetimepicker.min.css"	rel="stylesheet" />
<link rel="stylesheet"	href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.combobox.css" />
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-1.8.0.min.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui.js"></script>
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-timepicker-addon.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/index.js"></script>
<!-- ZUI Javascript组件 -->
<script	src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/zui/js/zui.js"></script>
</head>
<body >
	<div>
		<!-- <a href="<?php echo Yii::app()->createUrl('wxUser/link',array('type'=>'user','id'=>$id))?>">
		<div class="button_left <?php echo $type=='user'?'blue':''?>" style="">绑定用户</div>
		</a>
		<a href="<?php echo Yii::app()->createUrl('wxUser/link',array('type'=>'company','id'=>$id))?>">
		<div class="button_left  <?php echo $type=='company'?'blue':''?>">绑定公司</div>
		</a> -->
		<form method="post">
		<div class="search_body" style="min-width:680px">
			<div class="srarch_box">
				<img src="<?php echo imgUrl('search.png');?>">
				<input placeholder="请输入名称" class="forreset" value="" name="name" id="_name">
			</div>
			<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
			<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
		</div>
	</form>
		<div class="div_table"  data-sortable='true' >
		<?php 
			$this->widget('DataTableWdiget', array (
					'id' => 'datatable1',
					'tableHeader' =>$tableHeader,		
					'tableData' =>$tableData
			));
		?>
		 </div>
		<?php paginate($pages, "choose_list")?>
		<div class="btn_list"   style="padding:8px 16px 0;">
		<button type="button" style="float:right;" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
		<button type="submit" style="float:right;" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">确定</button>	
		</div>

	</div>
</body>
<script type="text/javascript">
	var id='<?php echo $id;?>';
	var type='<?php echo $type;?>';
	var selected="<?php echo $selected;?>";
	
	if(type=='company')
	{
		if(selected=='')
		{
			selected=',';
		}else{
			selected=','+selected+',';
		}
	}
	$('.user_select').click(function(){
		selected=$(this).val();
	});
	$('.com_select').click(function(){
		var thisid=$(this).val();
		var check=$(this).attr('checked');
		if(check)
		{
			selected=selected+thisid+',';
		}else{
			selected=selected.replace(','+thisid+',',',');
		}
	})		
		 //确定
	   $('#submit_btn').click(function(){
// 		   if(selected==''||selected==',')
// 			{
// 			   parent.$.colorbox.close();
// 				return;
// 			}
			$.post('/index.php/wxUser/saveSelected',{
				'type':type,
				'selected':selected,
				'id':id,
			},function(data){
				if(data=='1')
				{
					parent.refresh=true;
					parent.$.colorbox.close();
				}else{
					confirmDialog(data);
				}
			});		
		});

		//取消，关闭
		$('#cancel').click(function(){			
			parent.$.colorbox.close();
		});
</script>
</html>

