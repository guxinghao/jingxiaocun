<style type="text/css">
.register_bottom{
	min-height:360px
}
ul,li{
	margin: 0;
	padding: 0;
	display: block;
	overflow: hidden;
	list-style: none
}
.form-control{
	float: left;
	height: 28px;
	width: 198px;
	border-radius: 5px;
	border-width:1px
}
.btn{
	width: 65px;
	height:30px;
	border-radius: 5px;
	font-size: 16px;
	margin-top: 5px;
	margin-bottom: 5px;
}
/*遮罩*/
#shandow{
  width: 300px;
  height: 200px;
  background: rgba(125,125,125,.50);
  position: fixed;
  top: 0;
  z-index: 99999; 
}
 .box{
 	position: fixed;
 	left: 50%;
  top: 50%;
  width: 20rem;
  height: 10rem;
  background: #fff;
  border-radius: 8px;
  overflow: hidden;
  margin-left: -10rem;
  margin-top: -5rem;
  z-index: 999;
  box-shadow: 16px 14px 28px 1px rgba(135,134,134,.5);
}
 .box p{
  height: 6.4rem;
  line-height: 6.4rem;
  font-size: 2rem;
  text-align: center;
  color: #333;
  border-bottom: 1px solid #dbdbdb;
}
 .box ul li{
  float: left;
  width: 49.4%;
  text-align: center;
  height: 2.2rem;
  line-height: 2.2rem;
  display: block;
  cursor: pointer;
}
.box ul li:nth-of-type(1){
  border-right: 1px solid #dbdbdb;
}
 .box ul li a{
  color: #333;
  font-size: 1.2rem;
  height: 100%;
  display: inline-block;
  
}
.customalert .ui-content{
  width: 65%;
  background: #fff;
  margin: 0 auto;
  border-radius: 8px;
  margin-top: 50%;
}

 .customalert .ui-content .popcontent{
  text-align: center;
  font-size: 16px;
  line-height: 40px;
}
.customalert .ui-content .popsure{
    float: left;
    display: inline-block;
    width: 91%;
    height: 30px;
    line-height: 30px;
    font-size: 14px;
    text-align: center;
    background: #1fadff;
    color: #fff;
    text-shadow: 0 0 0;
    margin: 15px 5% 5px;
    border-radius: 5px;
    cursor: pointer;
}
.clear{
	clear: both;
}
</style>

<div id="hide" style="width: 100%;height:100%;background-color: rgba(0,0,0,0.5);position: fixed;z-index: 99"></div>
<div class="box">
	<p>去修改密码？</p>
	<ul>
		<li class="cancel"><a>取消</a></li>
		<li class="confirm" param="param"><a>确定</a></li>
	</ul>
</div>
<script type="text/javascript">
	//点击取消 弹框消失
	$("body").on('click', '.cancel', function(event) {
		$('#hide').remove();
		$('.box').remove();
    window.location = '<?php echo yii::app()->createUrl("site/index") ?>';
	});
  $("body").on('click', '.confirm', function(event) {
    window.location = '<?php echo yii::app()->createUrl("changePwd/index") ?>';
  });
</script>