<style>
.shop_more_one{width:145px;margin-left:20px;}
</style>
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
		 <select name='commission[year]' class='form-control chosen-select year'>
	       <?php for($i=2016;$i<=2020;$i++){?>
	            <option <?php echo $i==date("Y")?'selected="selected"':'';?> value='<?php echo $i;?>'><?php echo $i;?>年</option>
	       <?php }?>
	      </select>
	</div>
	<div class="shop_more_one">
		 <select name='commission[month]' class='form-control chosen-select month'>
	       <?php for($i=1;$i<=12;$i++){?>
	            <option  value='<?php echo $i;?>'><?php echo $i;?>月</option>
	       <?php }?>
	      </select>
	</div>
</div>
<div class="create_table">

</div>
<div class="btn_list" >
	<button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal">保存</button>
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
	getUserData();
	//年份发生变化
	$(".year").change(function(){
		getUserData();
	});
	//月份发生变化
	$(".month").change(function(){
		getUserData();
	});
	
	//保存
	var can_submit	= true;
	$(".save").click(function(){
		var is_submit = true;
		var num = 0;
		$("#cght_tb tbody tr").each(function(){
			if($(this).find(".checkone").attr("checked")){
				num ++;
				var name = $(this).find(".name").text();
				var money = $(this).find(".td_money").val();
				if(money == ""){
					confirmDialog("请输入业务员"+name+"的提成");
					is_submit = false;
					return false;
				}
			}
		})
		if(num == 0){
			confirmDialog("您至少需要选择一个业务员");
			return false;
		}
		if(is_submit){
			if(can_submit){
				can_submit = false;
				setSubmitStatus();
				$("#form_data").submit();
			}
		}
	});
})

//金额发生变化
$(document).on('change','.td_money',function(){
	var money = $(this).val();
	money = $.trim(money);
	money = numChange(money);
	if(!/^[0-9]+(.[0-9]{1,2})?$/.test(money))
	{
		confirmDialog('提成金额必须是大于等于0且小数点后只有2位的正数');
		$(this).val('');
		return false;
	}
})

//点击选择按钮
$(document).on('click','.checkone',function(){
	status = $(this).attr("checked");
	if(status == "checked"){
		$(this).next().val(1);
	}else{
		$(this).next().val(0);
	}
})

//点击全选按钮
$(document).on('click','.checkAll',function(){
	var all = true;
	//判断此页所有单选按钮是否是全部选中状态
	$(".checkone").each(function(){
		if(!$(this).attr("checked")){
			all = false;
			return true;
		}
	})
	
	//全部选中，取消全部选中
	if(all){
		$(this).attr("checked",false);
		$(".checkone").each(function(){
			$(this).attr("checked",false);
			$(this).next().val(0);
		})
	}else{//非全部选中，设置全部选中
		$(this).attr("checked",true);
		$(".checkone").each(function(){
			if(!$(this).attr("checked")){
				$(this).attr("checked",true);
				$(this).next().val(1);
			}
		})
	}
})

//获取业务员列表数据
function getUserData(){
	var year = $(".year").val();
	var month = $(".month").val();
	$.post("/index.php/SalesCommission/getUserData",{"year":year,"month":month},function(html){
		$(".create_table").html(html);
	})
}
</script>