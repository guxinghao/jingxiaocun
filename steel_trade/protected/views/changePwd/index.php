<style type="text/css">
	.shop_more_one input{
        width:240px;
        float: left;
    }
    .shop_more_one{
    	float:none;
    	width: 80%;
    }
    .shop_select_box{
    	margin-top: 60px;
    	margin-bottom: 30px;
    }
    .showError{
    	margin-left: 20px;
    	color: red;
    }
</style>
<form action="<?php echo Yii::app()->createUrl('ChangePwd/updatePwd'); ?>" method="post" class="form_message">
    <div class="shop_select_box">
    	<div class="shop_more_one">
            <div class="shop_more_one_l">用户名：</div>
            <input type="text" id="userName" name="userName"  class="form-control">
        	<span class="showError"></span>
        </div>
    	<div class="shop_more_one">
            <div class="shop_more_one_l">原密码：</div>
            <input type="text" id="oldPwd" name="oldPwd"  class="form-control">
        	<span class="showError"></span>
        </div>
        <div class="shop_more_one">
            <div class="shop_more_one_l">新密码：</div>
            <input type="text" id="newPwd" name="newPwd"  class="form-control">
            <span class="showError"></span>
        </div>
        <div class="shop_more_one">
            <div class="shop_more_one_l">确认密码：</div>
            <input type="text" id="againPwd" name="againPwd"  class="form-control">
            <span class="showError"></span>
        </div>

    </div>
    <div class="btn_list create_table" style="width:99%">
        <button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
        <button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
        </div>
</form>
<script type="text/javascript">
$('.cancel').click(function(event) {
	window.location = '<?php echo yii::app()->createUrl("site/index") ?>';
});
<?php if($msg){?>
    confirmDialog('<?php echo $msg; ?>');
<?php }?>
$("#userName").focus();
$(function(){
	// 验证用户名是否存在
	$("#userName").blur(function(){
	  var _this = $(this);
	  var userName = _this.val();
	  if (!userName) {
	  	_this.next('span').html('请填写用户名!');
	  	return;
	  }
	  var _url = "<?php echo Yii::app()->createUrl('ChangePwd/CheckUser'); ?>" ;
	  // var flag=true;
	  $.ajax({
		    url:_url,
		    type:"get",
		    async:false,
		    cache:false,
		    data:{'userName':userName},
		    dataType:"json",
		    success:function(data){
		    	// console.log(data);
		        if (!data) {
		            _this.next('span').html('用户名不正确!');
		            // flag = false;
		        }else{
		            _this.next('span').html('');
		            // flag = true;
		        }
		    }
	  });
	});
	// 验证原始密码是否正确
	$("#oldPwd").blur(function(){
	  var _this = $(this);
	  var userName = $('#userName').val();
	  var oldPwd = _this.val();
	  if (!oldPwd) {
	  	_this.next('span').html('请填写原始密码!');
	  	return;
	  }
	  var _url = "<?php echo Yii::app()->createUrl('ChangePwd/CheckOldPwd'); ?>" ;
	  // var flag=true;
	  $.ajax({
		    url:_url,
		    type:"get",
		    async:false,
		    cache:false,
		    data:{'oldPwd':oldPwd,'userName':userName},
		    dataType:"json",
		    success:function(data){
		        if (!data) {
		            _this.next('span').html('原始密码不正确!');
		            // flag = false;
		        }else{
		            _this.next('span').html('');
		            // flag = true;
		        }
		    }
	  });
	});


	// 验证两次密码是否一致
	$("#againPwd").blur(function(){
	  var _this = $(this);
	  var againPwd = _this.val();
	  var newPwd = $('#newPwd').val();
	  if (!againPwd) {
	  	_this.next('span').html('请确认新密码!');
	  	return;
	  }
	  if (againPwd != newPwd) {
	  	_this.next('span').html('两次输入不一致,请重新输入!');
	  }else{
	  	_this.next('span').html('');
	  }
	});

	// 判断条件是否满足  点击提交 修改密码
	$(".save").click(function(){
		var str = '';
	  	$('.showError').each(function() {
	  		str += $(this).html();
	  	});
	  	if (!str) {
	  		$(".form_message").submit();
	  	}else{
	  		return;
	  	}
	});
})

</script>
