<?php 
	if(checkOperation("盘盈盘亏:新增") && $id){
?>
<div class="con_tit">
	<div class="con_tit_daoru">
		<a href="<?php echo Yii::app()->createUrl("storage/index",array("page"=>$_GET['fpage']));?>"><img src="<?php echo imgUrl('back_url.png');?>">返回</a>
	</div>
	<div class="con_tit_duanshu"></div>
	<div class="con_tit_cz">
	<button type="button" class="btn btn-primary btn-sm pypk" data-dismiss="modal">新建</button>
	</div>
</div>
<input type="hidden" class="card_id" value="<?php echo $storage->id;?>">
<input type="hidden" class="card_no" value="<?php echo $storage->card_no;?>">
<input type="hidden" class="amount" value="<?php echo $storage->input_amount;?>">
<input type="hidden" class="left_amount" value="<?php echo $storage->left_amount;?>">
<input type="hidden" class="left_weight" value="<?php echo number_format($storage->left_weight,3);?>">
<?php }?>
<?php 
$form = $this->beginWidget ( 'CActiveForm', array (
		'htmlOptions' => array (
				'id' => 'user_search_form' ,
				'enctype'=>'multipart/form-data',
		) 
) );
?>
<div class="search_body">
	<div class="srarch_box">
		<img src="<?php echo imgUrl('search.png');?>">
		<input placeholder="请输入卡号" class="forreset" value="<?php echo $search["card_no"]?>" name="search[card_no]" id="search_card_no">
	</div>
	<div class="search_date">
		<div style="float:left">日期：</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="开始日期"  value="<?php echo $search['time_L']?>" name="search[time_L]">
		</div>
		<div style="float:left;margin:0 3px;">至</div>
		<div class="search_date_box">
			<input type="text"  class="form-control form-date forreset date" placeholder="结束日期" value="<?php echo $search['time_H']?>" name="search[time_H]"  >
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">销售公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search["title_id"]);?>" />
			<input type='hidden' id='combovaltt' value="<?php echo $search["title_id"];?>"  class="forreset" name="search[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">仓　库：</div>
		<div id="comselect_ck" class="fa_droplist">
			<input type="text" id="combo_ck" class="forreset" value="<?php echo Warehouse::getName($search["warehouse_id"]);?>" />
			<input type='hidden' id='comboval_ck' value="<?php echo $search["warehouse_id"];?>"  class="forreset" name="search[warehouse_id]"/>
		</div>
	</div>
	<div class="more_select_box" style="top:<?php if(checkOperation("盘盈盘亏:新增") && $id){ echo 127;}else{echo 90;}?>px;left:280px">
	<div class="more_one">
		<div class="more_one_l">产　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search["brand_name"];?>" name="search[brand_name]"/>
			<input type='hidden' id='comboval_brand' value="<?php echo $search["brand"];?>"  class="forreset" name="search[brand]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">品　　名：</div>
		<select name="search[product]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($products as $k=>$v){?>
            <option <?php echo $k==$search['product']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            <?php }?>
       </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">材　　质：</div>
		<select name="search[texture]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($textures as $k=>$v){?>
            	 <option <?php echo $k==$search['texture']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">规　　格：</div>
		<select name="search[rank]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
             <?php foreach ($ranks as $k=>$v){?>
            	 <option <?php echo $k==$search['rank']?'selected="selected"':''?>  value="<?php echo $k?>"><?php echo $v?></option>
            	<?php }?>
        </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">类　　型：</div>
		<select name="search[type]" class='form-control chosen-select forreset'>
            <option value='0' selected='selected'>-全部-</option>
            <option <?php echo $search['type']=="1"?'selected="selected"':''?>  value="1">盘盈</option>
            <option <?php echo $search['type']=="2"?'selected="selected"':''?>  value="2">盘亏</option>
       </select>
	</div>
	<div class="more_one">
		<div class="more_one_l">状　　态：</div>
		<select name="search[status]" class='form-control chosen-select forreset'>
            <option <?php echo $search['status']==1?'selected="selected"':''?>  value="1">正常</option>
            <option <?php echo $search['status']==2?'selected="selected"':''?>  value="2">已删除</option>
        </select>
	</div>
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>
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
    	 fixedLeftWidth:220,
    	 fixedRightWidth:0,
      });
   });
  </script>
</div>
<div class="total_data">
	<div class="total_data_one">盈亏件数：<span><?php echo $totalData["amount"];?></span></div>
	<div class="total_data_one">盈亏重量：<span class="color_org"><?php echo number_format($totalData["weight"],3);?></span></div>
</div>
<?php paginate($pages, "pypk_list")?>
<div class="dialogbody2" style="display:none;">
	<div class="pop_background"></div>
	<div class="check_background" id="check">
		<div class="retain_div" style="height:265px;">
			<input type="hidden" id="pypk_card" value="">
			<div class="pop_title">
				设置盘盈盘亏
			</div>
			<div style="margin-top:20px;">
			<div class="shop_more_one">
				<div class="shop_more_one_l">卡号：</div>
				<input type="text" value="" readonly class="pypk_card_no form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">件数：</div>
				<input type="text" value="" readonly class="pypk_amount form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">剩余件数：</div>
				<input type="text" value="" readonly class="pypk_left form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">剩余重量：</div>
				<input type="text" value="" readonly class="pypk_lock form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">盈亏重量：</div>
				<input type="text" value="" class="pypk_weight form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">备注：</div>
				<input type="text" value="" class="pypk_comment form-control">
			</div>
			</div>
			<div class="pop_footer">
				<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="cancelIt">取消</button>
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="pypk_sure">确定</button>
			</div>
		</div>
	</div>
</div>

 <script type="text/javascript">	
	$(function(){
		var array_brand=<?php echo $brands;?>;
		var array_ck = <?php echo $warehouse;?>;
		var array_tt=<?php echo $titles;?>;
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
		$('#combo_ck').combobox(array_ck, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_ck","comboval_ck",false);
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
	})
	//盘盈盘亏
	$(document).on("click",".pypk",function(){
		var card_no = $(".card_no").val();
		var amount = $(".amount").val();
		var left = $(".left_amount").val();
		var left_weight = $(".left_weight").val();
		$(".pypk_card_no").val(card_no);
		$(".pypk_amount").val(amount);
		$(".pypk_left").val(left);
		$(".pypk_lock").val(left_weight);
		if(left == 0){
			$(".pypk_num").val(left);
			$(".pypk_weight").val(left_weight);
		}
		$(".dialogbody2").show();
	});
	//盘盈盘亏确定
	$(document).on("click","#pypk_sure",function(){
		var id = $(".card_id").val();
		var amount = $(".pypk_num").val();
		var weight = $(".pypk_weight").val();
		//if(amount==""){confirmDialog("请输入盘盈件数");return false;}
		if(weight==""){confirmDialog("请输入盘盈重量");return false;}
// 		if(!/^-?\d+$/.test(amount))
// 		{
// 			confirmDialog('件数必须是整数');
// 			return false;
// 		}
		if(!/^-?[0-9]+(.[0-9]{1,3})?$/.test(weight))
		{
			confirmDialog('销售重量必须是小数点后只有3位的数字');
			return false;
		}
		var comment = $(".pypk_comment").val();
		$.post("/index.php/storage/setPypk",{"id":id,"weight":weight,"comment":comment},function(data){
			if(data == "success"){
				window.location.reload();
			}else{
				confirmDialog("操作失败");
			}
		});
	})
	
	$(document).on("click","#cancelIt",function(){
		//$(".dialogbody1").hide();
		$(".dialogbody2").hide();
	})
</script>
 