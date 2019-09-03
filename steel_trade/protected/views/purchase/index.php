<?php 
	setcookie('purchase_view','index',time()+60*60*24,'/');	
?>
<div class="con_tit">
<?php if (checkOperation("导出")) {?>
	<div class="con_tit_daoru btn_export" url="<?php echo Yii::app()->createUrl('purchase/export');?>">
		<img src="<?php echo imgUrl('daochu.png');?>">导出
	</div>
	<div class="con_tit_duanshu"></div>
<?php }?>
	<div class="con_tit_cz">
		<a href="<?php echo Yii::app()->createUrl('purchase/create',array('type'=>'normal','fpage'=>$_REQUEST['page']))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm create_btn" data-dismiss="modal">库存采购</button>
		</a>
		<a href="<?php echo Yii::app()->createUrl('purchase/create',array('type'=>'xxhj','fpage'=>$_REQUEST['page']))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm create_btn" data-dismiss="modal">直销采购</button>
		</a>
		<a href="<?php echo Yii::app()->createUrl('purchase/createDxcgStepOne',array('type'=>'dxcg','fpage'=>$_REQUEST['page']))?>" style="text-decoration: none;">
		<button type="button" class="btn btn-primary btn-sm create_btn" data-dismiss="modal">代销采购</button>
		</a>
	</div>
	<div class="view_section">
	<?php 
	 $num = 0;
	 if(checkOperation("采购审核视图")){
	 	$num ++;
	?>
		<a href="<?php echo Yii::app()->createUrl('purchase/indexForCheck')?>">
		<div class="view_button_right view_button" title="审核视图">
			<img alt="" class="view_button_img" src="/images/right_white1.png">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
	<?php }?>
	<?php 
	if(checkOperation("采购配送视图")){
		$num ++;
	?>
		<a href="<?php echo Yii::app()->createUrl('purchase/indexForStore')?>">
		<div class="view_button_middle view_button" title="配送视图">
			<img alt=""  class="view_button_img"  src="/images/middle_white1.png">
		</div>
		</a>
		<img alt="" class="view_section_img" src="/images/view_sep.png">
		<?php }?>
	<?php if($num >=1 ){?>
		<div class=" view_button_left blue_back view_button"  title="采购视图">
			<img alt="" class="view_button_img" src="/images/left_blue1.png">
		</div>
	<?php }?>
	</div>
	<?php if($num == 0) {?>
	<script>
		$(".view_section").hide();
	</script>
	<?php }?>
</div>
<style>
.submit_form{
	cursor:pointer;
}
</style>
<form method="post" action="/index.php/purchase/index?page=1" url="">
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入采购单号或备注" id="srarch" class="forreset" value="<?php echo $search['keywords']?>" name="search[keywords]">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date " placeholder="选择日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="选择日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="select_body">
	
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">采购公司：</div>
		<div id="comselect" style="float:left; display:inline;position: relative;">
			<input type="text" id="combo2" class="forreset"  value="<?php echo DictTitle::getName($search['company'])?>" />
			<input type='hidden' id='comboval2' value="<?php echo $search['company'];?>"  class="forreset" name="search[company]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">供应商：</div>
		<div id="wareselect" style="float:left; display:inline;width:145px;position: relative;">
			<input type="text" style="width:145px;" id="combo" class="forreset"  value="<?php echo DictCompany::getName($search['vendor']);?>" />
			<input type='hidden' id='comboval'  class="forreset"  value="<?php echo $search['vendor'];?>"  name="search[vendor]"/>
		</div>
	</div>
	
	<div class="more_select_box" style="top:130px;">
	<div class="more_one">
		<div class="more_one_l">产地：</div>
		<div id="brandselect" class="fa_droplist">
			<input type="text" id="combobrand"  class="forreset" value="<?php echo DictGoodsProperty::getProName($search['brand']);?>" />
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
	
	<div class="search_date" style="width:425px;margin-bottom:6px;">
		<div style="float:left">预期到货日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期"  value="<?php echo $search['reach_time_L']?>" name="search[reach_time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset" placeholder="选择日期" value="<?php echo $search['reach_time_H']?>" name="search[reach_time_H]"  >
		</div>
	</div>
	
	
	<div class="more_one" style="width:240px;">
		<div class="more_one_l" style="width:90px;">采购单类型：</div>
		 <select name="search[purchase_type]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['purchase_type']=='normal'?'selected="selected"':''?>  value="normal">库存采购</option>
            	<option <?php echo $search['purchase_type']=='tpcg'?'selected="selected"':''?>  value="tpcg">托盘采购</option>
            	<option <?php echo $search['purchase_type']=='xxhj'?'selected="selected"':''?>  value="xxhj">直销采购</option>
            	<option <?php echo $search['purchase_type']=='dxcg'?'selected="selected"':''?>  value="dxcg">代销采购</option>
	      </select>
	</div>
	<div class="more_one">
			<div class="more_one_l">长度：</div>
			 <select name="search[length]" class='form-control chosen-select forreset form_status' >
		            <option value='-1' selected='selected'>-全部-</option>	             
	           		 <option <?php echo $search['length']==0 && isset($search['length'])?'selected="selected"':''?>  value="0">0</option>
	           		 <option <?php echo $search['length']==9?'selected="selected"':''?>  value="9">9</option>
	           		 <option <?php echo $search['length']==12?'selected="selected"':''?>  value="12">12</option>
		      </select>
		</div>
	<div class="more_one">
		<div class="more_one_l">乙单：</div>
		 <select name="search[is_yidan]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['is_yidan']=='1'?'selected="selected"':''?>  value="1">乙单</option>
            	<option <?php echo $search['is_yidan']=='0'?'selected="selected"':''?>  value="0">甲单</option>
	      </select>
	</div>	
	<div class="more_one">
		<div class="more_one_l">合同编号：</div>
		<input type="text" class="form-control forreset" value="<?php echo $search['contract']?>" name="search[contract]">
	</div>	
		<div class="more_one">
		<div class="more_one_l">单据状态：</div>
		 <select name="search[form_status]" class='form-control chosen-select forreset'>
	            <option value='0' selected='selected'>-全部-</option>	             
           		 <option <?php echo $search['form_status']=="unsubmit"?'selected="selected"':''?>  value="unsubmit">未提交</option>
           		 <option <?php echo $search['form_status']=="submited"?'selected="selected"':''?>  value="submited">已提交</option>
           		 <option <?php echo $search['form_status']=="approve"?'selected="selected"':''?>  value="approve">已审核</option>
           		 <option <?php echo $search['form_status']=="delete"?'selected="selected"':''?>  value="delete">已作废</option>            	
	        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">审单状态：</div>
		 <select name="search[confirm_status]" class='form-control chosen-select forreset'>
	            <option value='' selected='selected'>-全部-</option>	             
            	<option <?php echo $search['confirm_status']=='1'?'selected="selected"':''?>  value="1">已审单</option>
            	<option <?php echo $search['confirm_status']=='0'?'selected="selected"':''?>  value="0">未审单</option>
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
    	 fixedLeftWidth:<?php echo  $search['form_status']=='delete'?20:240;?>,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
	<div class="total_data_one">金额：<span><?php echo number_format($totalData["money"],2);?></span></div>
	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totalData["total_num"];?></span></div>
</div>
<?php paginate($pages, "purchase_list")?>
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
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/pub_index.js"></script>
<script>
	    $('.reset').click(function(){
		    $('.forreset').val('');		    
		    });
	    $(function(){
		    $('.submit_form').click(function(e){
		    	 var title=$(this).attr("title");
				var href = $(this).attr('url');
				var num = $(this).parent().parent().find(".form_sn").val();
				var text = '确认要'+title+'采购单'+num+'吗';
			    confirmDialog2(text,href);			  
			});
		    $('.cancelcheck_form').click(function(e){
		    	 var name=$(this).attr('str');
		    	 var id=$(this).attr('thisid');
		    	 var href = $(this).attr('url');	
		    	 var haveB;	    	
		    	 $.ajaxSetup({async:false});
		    	 $.get('/index.php/purchase/haveBill/'+id,
		    	   {},function(data){
					haveB=data;
			    })
			    var haveP;
			    $.get('/index.php/purchase/havePlan/'+id,
					 {},function(da){
						haveP=da;
					})
				var haveK;
				$.get('/index.php/purchase/haveKaiPiao/'+id,{},function(dat){
					haveK=dat;
				});
				var haveShu;
				$.get('/index.php/purchase/havePledge/'+id,{},function(dada){
						haveShu=dada;
				});
				if(haveK=='1')
				{
					var text='已经开票,不能取消审核';
					confirmDialog(text);
					return;
				}
				if(haveShu)
				{
					var text='已经产生赎回记录,不能取消审核';
					confirmDialog(text);
					return;
				}
				if(haveB=='1')
				{
					if(haveP=='1')
					{
						var text='已关联运费登记和创建入库计划,'+name;
					}else{
						var text='已关联运费登记,'+name;
					}
					 confirmDialog2(text,href);			  
				}else{
					if(haveP=='1')
					{					
						var text='已创建入库计划,'+name;
					}else{
						var text=name;
					}					
					confirmDialog2(text,href);			  
				}				
			});		
			$('.create_btn').click(function(e){
				var result=checkAuthority('采购单:新增');
				if(result=='no')
				{
// 					alert('您没有权限执行此操作');
					e.preventDefault();
				}						
			});	
			$('.confirm_link').click(function(e){
				//看是否还有没有入库的入库单
				var result='';
				var id=$(this).attr('thisid');
				$.ajaxSetup({async:false});
				//看是否最新
				var lastupdate=$(this).attr('lastupdate');
				$.ajaxSetup({async:false});
				$.get('/index.php/commonForms/lastUpdate/'+id,{
					'time':lastupdate,
				},function(data){
					if(data==='error')
					{
						confirmDialog('获取信息失败，请稍后再试');
						e.preventDefault();
					}else 	if(data!=='pass')
					{
						confirmDialog('您看到的信息不是最新的，请刷新后再试');
						e.preventDefault();
// 						setTimeout('',4500);
// 						window.location.reload();
					}
				});	
				$.get('/index.php/purchase/haveInputingForm/'+id,{
				},function(data){
					result=data;
				});
				if(result=='normal')
				{
					confirmDialog('有尚未入库的入库单，您不能审单');
					e.preventDefault();
				}else if(result=='ccrk'){
					confirmDialog('有尚未入库或真实入库的船舱入库单，您不能审单');
					e.preventDefault();
				}
			})
			
			//修改
			$('.update_button').click(function(e){
				var result='';
				var id=$(this).attr('thisid');
				$.ajaxSetup({async:false});
				//看是否最新
				var lastupdate=$(this).attr('lastupdate');
				$.ajaxSetup({async:false});
				$.get('/index.php/commonForms/lastUpdate/'+id,{
					'time':lastupdate,
				},function(data){
					if(data==='error')
					{
						confirmDialog('获取信息失败，请稍后再试');
						e.preventDefault();
					}else 	if(data!=='pass')
					{
						confirmDialog('您看到的信息不是最新的，请刷新后再试');
						e.preventDefault();
					}
				});	
				$.get('/index.php/purchase/havePlan/'+id,
				 {},function(da){
					have=da;
				})
				if(have=='1')
				{
					confirmDialog('已创建入库计划,不能编辑');
					e.preventDefault();
				}
			})
			
			 $(document).off("click",".delete_form");
			 $('.delete_form').click(function(e){
		    	 var id=$(this).attr('thisid');
		    	  href = $(this).attr('url');	
		    	 var have;	    	
		    	 $.ajaxSetup({async:false});
		    	 $.get('/index.php/purchase/haveBill/'+id,
		    	   {},function(data){
					have=data;
			    })
				if(have=='1')
				{
					 confirmDialog('已关联运费登记,不能作废');
				}else{
					$.get('/index.php/purchase/havePlan/'+id,
					 {},function(da){
						have=da;
					})
					if(have=='1')
					{
						confirmDialog('已创建入库计划,不能作废');
					}else{
						$(".pop_background").show();
						$("#deleted").show();
					}				
				}
			 });
		  });
	   
	</script>
	<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
	<script type="text/javascript">	
	$(function(){
		var array=<?php echo $vendors?$vendors:json_encode(array());?>;
		var array2=<?php echo $coms?$coms:json_encode(array());?>;
		var array4=<?php echo $teams?$teams:json_encode(array());?>;
		var array_brand=<?php echo $brands;?>;
		$('#combo').combobox(array, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"wareselect","comboval",false);
		$('#combo2').combobox(array2, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect","comboval2");
		$('#combo4').combobox(array4, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ywyselect","comboval4",false);
		$('#combobrand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"brandselect","combovalbrand",false);
	})	
</script>
