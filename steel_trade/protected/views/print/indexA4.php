<html>
	<head>
		<?php header("Content-type:text/html;charset=utf-8");?>
		<title></title>
	</head>
<body>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/LodopFuncs.js"></script>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width="0" height="0"> 
	<embed id="LODOP_EM" type="application/x-print-lodop" width="0" height="0"></embed>
</object>
<script src='http://localhost:8000/CLodopfuncs.js'></script>
<!--<script src='http://192.168.0.150:8000/CLodopfuncs.js'></script>-->
<script src="/js/jquery-1.8.0.min.js"></script>
<script type="text/javascript" language="javascript">
$(function(){
	setTimeout('readyPri()',200);	
});

function readyPri()
{
	var LODOP = getLodop();
	LODOP.SET_LICENSES("上海钢瑞网络科技有限公司","E6937A1B8EE17DEFCF06C684A683CA26","","");
	LODOP.SET_PRINT_PAGESIZE(1, 0,0, "A4");
	LODOP.ADD_PRINT_URL("2mm", "5mm", "100%", "100%", '<?php echo Yii::app()->request->hostInfo.$print_url;?>');
// 	LODOP.PRINTA();
	LODOP.PREVIEW();
	window.close();
}
</script>
</body>
</html>
