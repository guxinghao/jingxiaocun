<div class="con_tit">
	<div class="con_tit_daoru">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('inputPlan/create',array('id'=>'0','fpage'=>$_REQUEST['page']))?>">
		<button type="button" class="btn btn-primary btn-sm create_btn" data-dismiss="modal">新建入库计划</button>
		</a>
	</div>
</div>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<form method="post" action="/index.php/inputPlan/index">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="采购单/入库计划单号" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">

	<div class="more_select_box" style="top:130px;">
	<div class="more_one">
		<div class="more_one_l">入库类型：</div>
		 <select name="search[input_type]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['input_type']=="purchase"?'selected="selected"':''?>  value="purchase">采购入库</option>
           		 <option <?php echo $search['input_type']=="thrk"?'selected="selected"':''?>  value="thrk">销售退货</option>
           		 <option <?php echo $search['input_type']=="ccrk"?'selected="selected"':''?>  value="ccrk">船舱入库</option>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">入库状态：</div>
		 <select name="search[status]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['status']=="0"?'selected="selected"':''?>  value="0">未推送</option>
           		 <option <?php echo $search['status']=="1"?'selected="selected"':''?>  value="1">已推送</option>
           		 <option <?php echo $search['status']=="2"?'selected="selected"':''?>  value="2">已完成</option>
           		 <option <?php echo $search['status']=="3"?'selected="selected"':''?>  value="3">作废中</option>
           		 <option <?php echo $search['status']=="4"?'selected="selected"':''?>  value="4">已作废</option>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand" class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
			<input type='hidden' id='combovalbrand' value="<?php echo $search['brand'];?>"  class="forreset" name="search[brand]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">品名：</div>
		 <select name="search[product]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            <?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规格：</div>
		 <select name="search[rand]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($rands as $k=>$v){?>
            	 <option <?php echo $k==$search['rand']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材质：</div>
		 <select name="search[texture]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>
	             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo$k?>"><?php echo $v?></option>
            	<?php }?>
	        </select>
	</div>
	
</div>
</div>
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>
</form>
<div class="div_table"  data-sortable='true'>
<?php 
	$this->widget('DataTableWdiget', array (
			'id' => 'datatable1',
			'tableHeader' =>$tableHeader,		
			'tableData' =>$tableData,
			'hide'=>1
	));
?>
 <script type="text/javascript">
  $(function(){
     $('#datatable1').datatable({
    	 fixedLeftWidth:<?php echo  $search['status']=='4'?20:240;?>,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
<!-- ->	<div class="total_data_one">金额：<span><?php echo number_format($totalData["money"],2);?></span></div> -->
<!-- >	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totalData["total_num"];?></span></div>-->
</div>
<?php paginate($pages, "input_list")?>
<div class="pop_background" style="display:none;"></div>
<div class="check_background" id="check" style="display:none;">
	<div class="check_div">
		<div class="pop_title">审核
			<span class="pop_cancle"><i class="icon icon-times"></i></span>
		</div>
		<div class="check_str"></div>
		<div class="pop_footer">
			<button type="button" class="btn btn-primary btn-sm gray pop_cancle" data-dismiss="modal" style="color:#333;">取消</button>
			<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="unpass">拒绝</button>
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="check_sub">同意</button>
		</div>
	</div>
</div>
<div class="check_background" id="deleted" style="display:none;">
	<div class="deleted_div">
		<div class="pop_title">请输入作废理由
			<span class="pop_cancle"><i class="icon icon-times"></i></span>
		</div>
		<textarea class="pop_textarea"></textarea>
		<div class="pop_footer">
			<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="submit">提交</button>
		</div>
	</div>
</div>
<script>
	    $('.reset').click(function(){
		    $('.forreset').val('');		    
		    });
	    $(function(){
		    $('.submit_form').click(function(e){
		    	 	var title=$(this).attr("str");
					var href = $(this).attr('url');
					var num = $(this).parent().parent().find(".form_sn").val();
					var text =title;
				    confirmDialog2(text,href);			  
			});
		    $('.create_btn').click(function(e){
				var result=checkAuthority('入库单:新增');
				if(result=='no')
				{
// 					alert('您没有权限执行此操作');
					e.preventDefault();
				}			
				
			});	
		  });
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script type="text/javascript">	
	$(function(){
		var array=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array2=<?php echo $coms;?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval",false);
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);
	})
</script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/pub_index.js"></script>
