<?php
$form = $this->beginWidget ( 'CActiveForm', array (
		'enableAjaxValidation'=>true,
		'htmlOptions' => array(
				'id' => 'form_data' ,
				'enctype'=>'multipart/form-data',
		)
) );
?>
<div class="shop_select_box">
	<div class="shop_more_one">
		<div class="shop_more_one_l">年份：</div>
		 <select name='commission[year]' class='form-control chosen-select year'>
	       <?php for($i=2016;$i<=2020;$i++){?>
	            <option <?php echo $i==date("Y",strtotime($model->date))?'selected="selected"':'';?> value='<?php echo $i;?>'><?php echo $i;?></option>
	       <?php }?>
	      </select>
	</div>
	<div class="shop_more_one">
		<div class="shop_more_one_l">月份：</div>
		 <select name='commission[month]' class='form-control chosen-select month'>
	       <?php for($i=1;$i<=12;$i++){?>
	            <option <?php echo $i==date("m",strtotime($model->date))?'selected="selected"':'';?> value='<?php echo $i;?>'><?php echo $i;?></option>
	       <?php }?>
	      </select>
	</div>
</div>
<div class="create_table">
	
</div>
<div class="ht_add_list" id="add_list">
	<img src="<?php echo imgUrl('add.png');?>">新增
</div>
<div class="btn_list" >
	<button type="submit" class="btn btn-primary btn-sm blue save" data-dismiss="modal">保存</button>
	<a href="<?php echo Yii::app()->createUrl('salesCommission/index')?>">
	<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	</a>
</div>
<?php $this->endWidget()?>
<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>

$(function(){
	var str='<tr>'+
	'<td class="text-center list_num">1</td>'+
	'<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>'+
	'<td class="">'+
		'<select name="name[]" class="form-control chosen-select td_name"  onchange="nameChange(this)">'+
		' <option  value="0">请选择业务员</option>'+
			<?php foreach($users as $k=>$v){
 				if(!Yii::app()->authManager->checkAccess('业务员',$k)){continue;}
 			?>
 			'<option <?php echo $model->owned_by==$k?'selected="selected"':'';?> value="<?php echo $k;?>"><?php echo $v;?></option>'+
 			<?php }?>
		'</select>'+
	'</td>'+
	'<td class=""><input type="text"  style="" class="form-control td_weight" placeholder="" name="weight[]"></td>'+
	'<td class=""><input type="text"  style="" class="form-control td_money" placeholder=""  name="money[]"></td>'+
	'</tr>';
	//年份发生变化
	$(".year").change(function(){
		$(".table tbody").html(str);
		$("#tr_num").val(1);
	});
	//月份发生变化
	$(".month").change(function(){
		$(".table tbody").html(str);
		$("#tr_num").val(1);
	});
	//删除明细
	$(".deleted_tr").live("click",function(){
		$(this).parent().parent().remove();
		refreshTableStyle();
	})
	//点击新增按钮
	$("#add_list").click(function(){
			var count=parseInt($("#tr_num").val()) + 1;
			var newRow = '';
			var yu = count % 2;
			if(yu == 0)
			{
				newRow = '<tr class="selected">';
			}else{
				newRow = '<tr class="">';
			}
			newRow+='<td class="text-center list_num">'+count+'</td>'+
			'<td class="text-center"><i class="icon icon-trash deleted_tr"></i></td>'+
			'<td class="">'+
				'<select name="name[]" class="form-control chosen-select td_name"  onchange="nameChange(this)">'+
				' <option  value="0">请选择业务员</option>'+
					<?php foreach($users as $k=>$v){
		 				if(!Yii::app()->authManager->checkAccess('业务员',$k)){continue;}
		 			?>
		 			'<option <?php echo $model->owned_by==$k?'selected="selected"':'';?> value="<?php echo $k;?>"><?php echo $v;?></option>'+
		 			<?php }?>
				'</select>'+
			'</td>'+
			'<td class=""><input type="text"  style="" class="form-control td_weight" placeholder="" name="weight[]"></td>'+
			'<td class=""><input type="text"  style="" class="form-control td_money" placeholder=""  name="money[]"></td>'+
			'</tr>';
			
			$("#cght_tb tbody").append(newRow);
			$("#tr_num").val(count);
		});

	//保存
	$(".save").click(function(){
		var user = $(".se_yw").val();
		if(user == 0){confirmDialog("请选择业务员");return false;}
	});
})

function nameChange(obj){
	var user = $(obj).val();
	var year = $(".year").val();
	var month = $(".month").val();
	$.post("/index.php/frmSales/getUserWeight",{
		"user":user,"year":year,"month":month,
		},function(data){
			var arr = data.split(',');
			$(obj).parent().parent().find(".td_weight").val(fmoney(arr[0],3));
			$(obj).parent().parent().find(".td_money").val(fmoney(arr[1],2));
		});
}
//表格样式初始化
function refreshTableStyle(){
	var row_num=0;
	$("#cght_tb tbody tr").each(function(){
		row_num++;
		$(this).find(".list_num").html(row_num);
		if(row_num%2 == 0){
			$(this).addClass("selected");
		}else{
			$(this).removeClass("selected");
		}
	});
	$("#tr_num").val(row_num);
}

</script>