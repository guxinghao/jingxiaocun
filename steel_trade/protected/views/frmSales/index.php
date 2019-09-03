<style>
.cz_list_btn_more{height:70px;}
.sales_export{cursor: pointer;}
</style>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sales.js"></script>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('frmSales/salesExport'); ?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
	<?php 
		if(checkOperation("销售单:新增")){
	?>
	<a href="<?php echo Yii::app()->createUrl('frmSales/create',array("type"=>"normal"))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">库存销售</button>
	</a>
	<a href="<?php echo Yii::app()->createUrl('frmSales/xscreate',array("type"=>"xxhj"))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">先销后进</button>
	</a>
	<a href="<?php echo Yii::app()->createUrl('frmSales/dxcreate',array("type"=>"dxxs"))?>" style="text-decoration: none;"> 
		<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">代销销售</button>
	</a>
	<?php }?>
	</div>
	<?php if(checkOperation("基价核算视图")){?>
	<div class="view_section" style="margin-left:-15px;">	
		<a href="<?php echo Yii::app()->createUrl('frmSales/index',array("view"=>"baseview"))?>">
		<div class=" view_button_right view_button_left view_button <?php if($type=="baseview"){echo "blue_back";}?>" title="基价核算">
			<img alt="" class="view_button_img" src="/images/baseview_<?php if($type=="baseview"){echo "white";}else{echo "blue";}?>.png">
		</div>
		</a>
	</div>
	<?php }?>
	
	<div class="view_section">
	<?php 
	 $num = 0;
	 if(checkOperation("销售审核视图")){
	 	$num ++;
	?>
		<a href="<?php echo Yii::app()->createUrl('frmSales/index',array("view"=>"checkview"))?>">
		<div class="view_button_right view_button <?php if($type=="checkview"){echo "blue_back";}?>" title="审核视图">
			<img alt="" class="view_button_img" src="/images/right_<?php if($type=="checkview"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
	<?php }?>
	<?php 
	if(checkOperation("销售出库视图")){ 
		$num ++;
	?>
		<a href="<?php echo Yii::app()->createUrl('frmSales/index',array("view"=>"outview"))?>">
		<div class="<?php if($num == 1){ echo "view_button_right";}else{ echo view_button_middle;}?> view_button <?php if($type=="outview"){echo "blue_back";}?>" title="出库视图">
			<img alt=""  class="view_button_img"  src="/images/middle_<?php if($type=="outview"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
	<?php }?>
	<?php if($num >=1 ){?>
		<a href="<?php echo Yii::app()->createUrl('frmSales/index',array("view"=>"index"))?>">
		<div class=" view_button_left view_button <?php if($type=="index"){echo "blue_back";}?>" title="普通视图">
			<img alt="" class="view_button_img" src="/images/left_<?php if($type=="index"){echo "blue";}else{echo "white";}?>1.png">
		</div>
		</a>
	<?php }?>
	</div>
	<?php if($num == 0) {?>
	<script>
		$(".view_section").hide();
	</script>	
	<?php }?>	
</div>
<form method="post" action="" url="">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入销售单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date start_time" placeholder="开始日期"  value="<?php echo $search?$search['time_L']:date("Y-m-d");?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date end_time" placeholder="结束日期" value="<?php echo $search?$search['time_H']:date("Y-m-d");?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">
	<div class="shop_more_one1 short_shop_more_one">
		<div style="float:left;">公司：</div>
		<div id="ywyselect" class="fa_droplist">
			<input type="text" id="combo2" class="forreset" value="<?php echo $search['title_name'];?>" name="search[title_name]" />
			<input type='hidden' id='combval' class="forreset" value="<?php echo $search['title_id'];?>" name="search[title_id]" />
		</div>
	</div>
	<div class="shop_more_one1">
		<div style="float:left;">结算单位：</div>
		 <div id="cusselect" class="fa_droplist">
			<input type="text" id="cuscombo" value="<?php echo $search['custome_name'];?>" class="forreset" name="search[custome_name]"/>
			<input type='hidden' id='cuscomboval' class="forreset" value="<?php echo $search['customer_id'];?>" name="search[customer_id]" />
		</div>
	</div>
	<div class="more_select_box" >
	<div class="more_one" >
		<div class="more_one_l">客户：</div>
		<div id="wareselect" class="fa_droplist">
			<input type="text" id="combo" value="<?php echo $search['client_name'];?>" class="forreset" name="search[client_name]"/>
			<input type='hidden' id='comboval' class="forreset" value="<?php echo $search['client_id'];?>" name="search[client_id]" />
		</div>
	</div>
	<?php if($type != "outview"){?>
	<div class="more_one">
		<div class="more_one_l">审核状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset form_status'>
	            <option value='0' selected='selected'>-未完成-</option>	             
           		 <option <?php echo $search['form_status']=="unsubmit"?'selected="selected"':''?>  value="unsubmit">未提交</option>
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已提交</option>
           		 <option <?php echo $search['form_status']=="approve"?'selected="selected"':''?>  value="approve">已审核</option>
           		  <option <?php echo $search['form_status']=="preout"?'selected="selected"':''?>  value="preout">待出库</option>
           		 <option <?php echo $search['form_status']=="complete"?'selected="selected"':''?>  value="complete">已完成</option>
           		 <option <?php echo $search['form_status']=="delete"?'selected="selected"':''?>  value="delete">已作废</option>
           		 <option <?php echo $search['form_status']=="all"?'selected="selected"':''?>  value="all">全部</option>            	
	        </select>
	</div>
	<?php }?>
	<div class="more_one">
		<div class="more_one_l">销售类型：</div>
		 <select name="search[sales_type]" class='form-control chosen-select forreset sales_type'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['sales_type']=="normal"?'selected="selected"':''?>  value="normal">库存销售</option>
           		 <option <?php echo $search['sales_type']=="xxhj"?'selected="selected"':''?>  value="xxhj">先销后进</option>
           		 <option <?php echo $search['sales_type']=="dxxs"?'selected="selected"':''?>  value="dxxs">代销销售</option>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset is_yidan'>
	            <option value='-1' selected='selected'>-全部-</option>
	            <option <?php echo $search['is_yidan']=="1"?'selected="selected"':''?>  value="1">乙单</option>	             
           		<option <?php echo $search['is_yidan']=="0"?'selected="selected"':''?>  value="0">甲单</option>
	     </select>
	</div>
	<?php if($type!='baseview'){?>
	<div class="more_one">
		<div class="more_one_l">业务组：</div>
		 <select name="search[team]" class='form-control chosen-select forreset team'>
	            <option value='0' selected='selected'>-全部-</option>
	            <?php foreach ($teams as $k=>$v){?>
           		<option <?php echo $k==$search['team']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<?php }?>
	<div class="more_one">
		<div class="more_one_l">业务员：</div>
		 <select name="search[owned]" class='form-control chosen-select forreset owned'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($users as $k=>$v){?>
            <option <?php echo $k==$search['owned']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l">产地：</div>
	     <div id="brandselect" class="fa_droplist">
			<input type="text" id="combo_brand" value="<?php echo $search['brand_name'];?>" class="forreset" name="search[brand_name]"/>
			<input type='hidden' id='comboval_brand' class="forreset" value="<?php echo $search['brand'];?>" name="search[brand]" />
		</div>
	</div>	
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset product'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rand]" class='form-control chosen-select forreset rank'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset texture'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">长度：</div>
		 <select name="search[length]" class='form-control chosen-select forreset form_status'>
	            <option value='-1' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['length']==0 && isset($search['length'])?'selected="selected"':''?>  value="0">0</option>
           		 <option <?php echo $search['length']==9?'selected="selected"':''?>  value="9">9</option>
           		 <option <?php echo $search['length']==12?'selected="selected"':''?>  value="12">12</option>
	        </select>
	</div>
	<div class="more_one" >
		<div class="more_one_l">仓库：</div>
		 <select name="search[warehouse]" class='form-control chosen-select forreset warehouse'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($warehouses as $k=>$v){?>
            	 <option <?php echo $k==$search['warehouse']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询" style="margin-left:0px;">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array(
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			//'totalData' =>$totalData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:225,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["price"],2);?></span></div>
	<?php 
	if($type=="checkview"){
	?>
	<div class="total_data_one">核定件数：<span><?php echo $totalData["o_amount"];?></span></div>
	<div class="total_data_one">核定重量：<span class="color_org"><?php echo number_format($totalData["o_weight"],3);?></span></div>
	<div class="total_data_one">核定金额：<span><?php echo number_format($totalData["o_total_num"],2);?></span></div>
	<?php }?>
	<?php	if($type=='baseview'){?>
	<div class="total_data_one">平均基价核算：<span class="color_org"><?php echo $totalData['baseweight']!=0?number_format($totalData["baseprice"]/$totalData['baseweight'],2):0;?></span></div>
	<?php }?>
</div>
<?php paginate($pages, "sales_list")?>
<script>
<?php if($msg){?>
confirmDialog('<?php echo $msg?>');
<?php }?>
</script>
	<script type="text/javascript">
	$(function(){
		$(document).on('click','.submit_form',function(){
			var thisobj=$(this);
			var title=$(this).attr("title");
			var href = $(this).attr('url');
			var num = $(this).parent().parent().find(".form_sn").val();
			var text = '确认要'+title+'销售单'+num+'吗';
			var real_cancle= $(this).hasClass('real_cancle');
			if($(this).hasClass("cancelCheck")){
				var sales_type=$(this).attr("sales_type");
				var salesid = $(this).attr("salesid");
				if(sales_type!='normal'){
					var result=checkAccess(salesid);
					if(result!=1){
						confirmDialog(result);	return;
					}
				}
				$.post("/index.php/frmSales/getGaokaiDJ",{"salesid":salesid},function(data){
						if(data == "hasHigh"){
							text = '销售单'+num+'已做高开登记，您确定要取消审核吗';
						}
						if(data == "hasYf"){
							text = '销售单'+num+'已做运费登记，您确定要取消审核吗';
						}
						if(real_cancle){				
							var common_id=$(thisobj).attr('common_id');
							confirmDialog_uncheck(common_id,text,href);
						}else{
							cancleCheckSales(text,href);
						}						
					})
			}else{
				if(href != ''){
					confirmDialog2(text,href);
				}
			}
		});

		$('.review_reason').click(function(){
			var href=$(this).attr('url');
			$.get(href,{},function(data){
				if(data)
				{ 
					confirmDialog(data);
				}else{
					confirmDialog('未找到数据');
				}				
			});			
		})
		$(document).on('click','.update_true',function(e){
			var sales_type=$(this).attr('sales_type');
			if(sales_type!='normal')
			{
				var sales_id=$(this).attr('salesid');
				var can=checkAccess(sales_id);
				if(can!=1)
				{					
					e.preventDefault();
					confirmDialog("已生成采购单，不能编辑");
				}
								
			}
		})
		function checkAccess(id)
		{
			var ret;
			$.ajaxSetup({async:false});
			$.post('/index.php/frmSales/checkPurchase',{'id':id},function(data){ret= data;});
			return ret;
		}
		$(".delete_form").on("click",function(){			
			if(checkAuthority('销售单:作废')=='no')
			{
				confirmDialog('您没有权限执行此操作');
				return false;
			}
			var num = $(this).parent().parent().find(".form_sn").val();
			var salesid = $(this).attr("salesid");
			var that = $(this);
			$.post("/index.php/frmSales/getGaokaiDJ",{"salesid":salesid},function(data){
				if(data == "hasHigh"){
					confirmDialog("销售单"+num+"已做高开登记，不能作废");	
				}else if(data == "hasYf"){
					confirmDialog("销售单"+num+"已做付费登记，不能作废");	
				}else{
					deleteIt(that);
				}
			})	
		});
		//点击车牌号
		$(document).on("click",".car_no",function(){
			var car_list = $(this).attr("title");
			confirmCarNo(car_list);
		});	
		//搜索条件重置按钮
		$(".reset").click(function(){
			$(".forreset").val('');
			$("#combo").val('');
			$("#comboval").val('');
			$("#combo2").val('');
			$("#combval").val('');
		});
	})
	</script>
	<script>
	$(function(){
		var brand = <?php echo $brands?$brands:'[]'?>;
		var array=<?php echo $vendors?$vendors:"[]";?>;
		var coms=<?php echo $coms?$coms:"[]";?>;
		$('#combo').combobox(array,{},"wareselect","comboval","","",200);
		$('#cuscombo').combobox(array, {},"cusselect","cuscomboval","","",200);
		$('#combo2').combobox(coms, {},"ywyselect","combval");
		$('#combo_brand').combobox(brand,{},"brandselect","comboval_brand");
	})
	</script>