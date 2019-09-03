<html>
<head>
<style>
.submit_form{
	cursor:pointer;
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
<body style="min-height: 500px;">
<form method="post" action="<?php echo Yii::app()->createUrl('contract/listForSelect') ?>">
<div class="search_body" style="min-width: 980px;">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入合同号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 70px;">采购公司：</div>
		<div id="comselect"  class="fa_droplist">
			<input type="text" id="combo2" class="forreset"  value="<?php echo DictTitle::getName($search['company'])?>" />
			<input type='hidden' id='comboval2' value="<?php echo $search['company'];?>"  class="forreset" name="search[company]"/>
		</div>
	</div>	
	<div class="more_select_box" style="width: 500px;top:40px;left:420px;">
	<div class="more_one" >
<!-- 		<div class="more_one_l">供应商：</div> -->
		<div class="shop_more_one">
		<div class="more_one_l" >供应商：</div>
		<div id="wareselect"  class="fa_droplist">
			<input type="text"   id="combo" class="forreset"  value="<?php echo DictCompany::getName($search['vendor']);?>" />
			<input type='hidden' id='comboval'  value="<?php echo $search['vendor'];?>" class="forreset"  name="search[vendor]"/>
		</div>
		</div>
	</div>
	<div class="more_one">
	<div class="shop_more_one">
		<div class="more_one_l">业务组：</div>		
		<div id="ywyselect"  class="fa_droplist">
			<input type="text" id="combo4"   class="forreset"  value="<?php echo Team::getName($search['team'])?>" />
			<input type='hidden' id='comboval4' class="forreset"   value="<?php echo $search['team']?>"  name="search[team]"/>
		</div>
		</div>
	</div>
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
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
<?php paginate($pages, "contract_simlist")?>
<div class="btn_list"   style="padding:8px 16px 0;">
	<button type="button" style="float:right;" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancel">取消</button>
	<button type="submit" style="float:right;" class="btn btn-primary btn-sm blue" data-dismiss="modal"  id="submit_btn">确定</button>	
</div>

<script>
var abc='';
$(function(){
    $('#datatable1').datatable({
   	 fixedLeftWidth:80,
   	 fixedRightWidth:0,
     });
// 	$('input[type=radio]').each(function(){
// 		var id=$(this).val();
// 		if(id==abc)
// 		{
// 			$(this).attr('checked','checked');
// 		}
// 	});
    
  });
	    $(document).on('click','tbody tr',function(){
		  var a= $(this).index();
		  var trinput=$(this).parent().parent().parent().parent().prev().children().children().children().children().eq(a).find('.selected_contract');
		  trinput.attr('checked','checked');
		  abc= trinput.val();	
		  parent.template_select=abc;
	    });
	    $('tbody tr').each(function(){
		    var radio=$(this).find('.selected_contract');
		    var id=radio.val();
		    if(id==parent.selected_contract)
		    {
			    radio.attr('checked','checked');			  
		    }
		 });	    
	    $('.reset').click(function(){
		    $('.forreset').val('');		    
		    });
	  
	    $(document).on('click','.selected_contract',function(e){
	    	 e.stopPropagation();
			 abc= $(this).val();		
			 parent.template_select=abc;	
		});

	    //确定，加载数据
	    $('#submit_btn').click(function(){
		   parent.selected_contract=abc;
		    if(parent.selected_contract=='')
		    {
		    	 parent.$.colorbox.close();
			    return false;
		    }
	    	$.get('giveContData',{
				'id':parent.selected_contract,
			},function(data){
				var json=eval('('+data+')');

				//在填充数据时进行输入框的转换
				parent.$('.supply_div').next().remove();
				var supply_str='<div class="search_date_box" style="margin-top:0px;width:145px;background-position:155px 8px;">'
					+'<input type="text"  readonly value="'+json.company_name+'" class="form-control"/>'
					+'<input type="hidden"  name="FrmPurchase[supply_id]" value="'+json.dict_company_id+'" class="form-control supply_id" id="comboval" placeholder=""  >'
					+'</div>';
				parent.$('.supply_div').after(supply_str);

				parent.$('.title_div').next().remove();
				var title_str='<div class="search_date_box" style="margin-top:0px;width:145px;background-position:155px 8px;">'
					+'<input type="text"  readonly value="'+json.title_name+'" class="form-control"/>'
					+'<input type="hidden"  name="FrmPurchase[title_id]" value="'+json.dict_title_id+'" class="form-control title_id" placeholder=""  >'
					+'</div>';
				parent.$('.title_div').after(title_str);
				parent.changeCont();
				parent.$('#contact_id').val(json.contact_id);				
				parent.$('#comboval2').val(json.dict_title_id);
				parent.$('#combo2').val(json.title_name);
				parent.$('#combo4').val(json.team_name);
				parent.$('#CommonForms_owned_by').val(json.owned);				
				parent.changeOwnerT();
				parent.$('#team_id').val(json.team_id);
				parent.$('#combo3').val(json.warehouse);
				parent.$('#comboval3').val(json.warehouse_id);
				parent.$('#frmpurchase_contract').val(json.contract_no);
				parent.$('#frmpurchase_contract_hidden').val(parent.selected_contract);			
			});
			$.get('getDetailData',{
				'id':parent.selected_contract,
			},function(data){
				parent.$('#forinsert').html(data);
			})
			parent.$.colorbox.close();
		});

		//取消，关闭
		$('#cancel').click(function(){
			parent.selected_contract='';
			parent.$.colorbox.close();
		});
		
	    
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script type="text/javascript">	
	$(function(){
		var array=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array2=<?php echo $coms;?>;
		var array4=<?php echo $teams?$teams:json_encode(array());?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval",false);
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval4",false);
	})
</script>
</body>
</html>
