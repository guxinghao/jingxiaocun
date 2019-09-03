<style>
a{cursor:pointer}
.color_gray{background:#f4f4f4}
#isearch input{line-height:16px}
</style>
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
		<input placeholder="请输入卡号" class="forreset" value="<?php echo $search->card_no?>" name="Storage[card_no]" id="Storage_card_no">
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:240px;">
		<div class="shop_more_one_l" style="width: 90px;">销售公司：</div>
		<div id="ttselect" class="fa_droplist">
			<input type="text" id="combott" class="forreset" value="<?php echo DictTitle::getName($search->title_id);?>" />
			<input type='hidden' id='combovaltt' value="<?php echo $search->title_id;?>"  class="forreset" name="Storage[title_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">仓　库：</div>
		<div id="comselect_ck" class="fa_droplist">
			<input type="text" id="combo_ck" class="forreset" value="<?php echo Warehouse::getName($search->warehouse_id);?>" />
			<input type='hidden' id='comboval_ck' value="<?php echo $search->warehouse_id;?>"  class="forreset" name="Storage[warehouse_id]"/>
		</div>
	</div>
	<div class="shop_more_one" style="margin-top:8px;width:220px;">
		<div class="shop_more_one_l" style="width: 70px;">产　地：</div>
		<div id="comselect_b" class="fa_droplist">
			<input type="text" id="combo_brand" class="forreset" value="<?php echo $search->brand_name;?>" />
			<input type='hidden' id='comboval_brand' value="<?php echo $search->brand_id;?>"  class="forreset" name="Storage[brand_id]"/>
		</div>
	</div>
	
	<div class="more_select_box" style="top:90px;left:280px">
	<div class="more_one">
		<div class="more_one_l">品　　名：</div>
		<div id="comselect_p" class="fa_droplist">
			<input type="text" id="combo_product" class="forreset" value="<?php echo $search->product_name;?>" />
			<input type='hidden' id='comboval_product' value="<?php echo $search->product_id;?>"  class="forreset" name="Storage[product_id]"/>
		</div>
	</div>
	<div class="more_one">
		<div class="more_one_l">材　　质：</div>
		<div id="comselect_t" class="fa_droplist">
			<input type="text" id="combo_texture" class="forreset" value="<?php echo $search->texture_name;?>" />
			<input type='hidden' id='comboval_texture' value="<?php echo $search->texture_id;?>"  class="forreset" name="Storage[texture_id]"/>
		</div>
	</div>
	
	<div class="more_one">
		<div class="more_one_l">规　　格：</div>
		<div id="comselect_r" class="fa_droplist">
			<input type="text" id="combo_rank" class="forreset" value="<?php echo $search->rank_name;?>" />
			<input type='hidden' id='comboval_rank' value="<?php echo $search->rank_id;?>"  class="forreset" name="Storage[rank_id]"/>
		</div>
	</div>
	<div class="more_one">
			<div class="more_one_l">长度：</div>
			  <select name="Storage[length]" class='form-control chosen-select forreset form_status' >
		            <option value='-1' selected='selected'>-全部-</option>	             
	           		 <option <?php echo $search->length==0 && isset($search->length)?'selected="selected"':''?>  value="0">0</option>
	           		 <option <?php echo $search->length==9?'selected="selected"':''?>  value="9">9</option>
	           		 <option <?php echo $search->length==12?'selected="selected"':''?>  value="12">12</option>
		      </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">卡号状态：</div>
			<select name="Storage[card_status]" class='form-control chosen-select forreset'>
       			<option <?php echo $search->card_status=="normal"?'selected="selected"':''?>  value="normal">正常</option>
	           	<option <?php echo $search->card_status=="clear"?'selected="selected"':''?>  value="clear">清卡</option>
	           	<option <?php echo $search->card_status=="deleted"?'selected="selected"':''?>  value="deleted">删除</option>            	
		    </select>
		</div>
		<div class="more_one">
			<div class="more_one_l">入库类型：</div>
			<select name="Storage[input_type]" class='form-control chosen-select forreset'>
				<option value="">-全部-</option>
       			<option <?php echo $search->input_type=="purchase"?'selected="selected"':''?>  value="purchase">采购</option>
	           	<option <?php echo $search->input_type=="thrk"?'selected="selected"':''?>  value="thrk">销售退货</option>
	           	<option <?php echo $search->input_type=="ccrk"?'selected="selected"':''?>  value="ccrk">船舱入库</option> 
	           	<option <?php echo $search->input_type=="qt"?'selected="selected"':''?>  value="qt">其他</option>           	
		    </select>
		</div>
		<?php if($_REQUEST["type"] != "retain"){?>
		<div class="more_one">
			<div class="more_one_l">是否保留：</div>
			<select name="Storage[retain_amount]" class='form-control chosen-select forreset'>
				<option value="0">-全部-</option>
       			<option <?php echo $search->retain_amount==1?'selected="selected"':''?>  value="1">是</option>
	           	<option <?php echo $search->retain_amount==2?'selected="selected"':''?>  value="2">否</option>
		    </select>
		</div>
		<?php }?>
		<div class="more_one">
			<div class="more_one_l">剩余件数：</div>
			<select name="Storage[left_amount]" class='form-control chosen-select forreset'>
				<option value="0">-全部-</option>
       			<option <?php echo $search->left_amount==1?'selected="selected"':''?>  value="1">0</option>
	           	<option <?php echo $search->left_amount==2?'selected="selected"':''?>  value="2">非0</option>
		    </select>
		</div>
	</div>
	
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<div class="more_toggle" title="更多"></div>
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" style="display:none">
		<thead>
			<tr class="data">
				<th width="60" class='table_cell_first'>操作</th>
				<th width="150" class=''>卡号</th>
				<th width="60" class="flex-col" >仓库</th>
				<th width="60" class="flex-col" >卡号状态</th>
				<th width="60" class="flex-col" >产地</th>
				<th width="60" class="flex-col" >品名</th>
				<th width="70" class="flex-col">材质</th>
				<th width="40" class="flex-col" >规格</th>
				<th width="40" class="flex-col rightAlign" >长度</th>
				<th width="50" class="flex-col rightAlign" >件重</th>
				<th width="80" class="flex-col rightAlign" >入库件数</th>
				<th width="80" class="flex-col rightAlign" >入库重量</th>
				<th width="80" class="flex-col rightAlign" >剩余件数</th>
				<th width="80" class="flex-col rightAlign" >剩余重量</th>
				<th width="80" class="flex-col rightAlign" >保留件数</th>
				<th width="80" class="flex-col rightAlign" >保留重量</th>
				<th width="80" class="flex-col" >入库时间</th>
				<th width="100" class="flex-col" >预计到货日期</th>
				<th width="80" class="flex-col" >入库类型</th>
				<th width="70" class="flex-col rightAlign" >成本单价</th>
				<th width="90" class="flex-col rightAlign" >采购发票成本</th>
				<th width="110" class="flex-col" >采购单价已确定</th>
				<th width="100" class="flex-col" >采购是否乙单</th>
				<th width="100" class="flex-col" >是否托盘库存</th>
				<th width="60" class="flex-col" >销售公司</th>
				<th width="60" class="flex-col" >托盘公司</th>
<!--				<th width="100" class="flex-col" >是否代销</th>-->
				
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
				    $storageturnover_url = Yii::app()->createUrl("storageTurnover/index",array('card_no'=>$item->card_no));
			?>
			<tr id="<?php echo $item->id?>" class="data  <?php if($i%2==1){?>color_gray<?php }?>">
				<td  class='table_cell_first'>
					<input type="hidden" class="card_no" value="<?php echo $item->card_no;?>">
					<input type="hidden" class="oneweight" value="<?php echo $item->weight;?>">
					<input type="hidden" class="title_name" value="<?php echo DictTitle::getName($item->title_id);?>">
					<input type="hidden" class="warehouse_name" value="<?php echo Warehouse::getName($item->warehouse_id);?>">
					<input type="hidden" class="amount" value="<?php echo $item->input_amount;?>">
					<input type="hidden" class="left_amount" value="<?php echo $item->left_amount;?>">
					<input type="hidden" class="left_weight" value="<?php echo number_format($item->left_weight,3);?>">
					<input type="hidden" class="lock_amount" value="<?php echo $item->lock_amount;?>">
					<input type="hidden" class="retain_amount" value="<?php echo $item->retain_amount;?>">
					<input type="hidden" class="retain_weight" value="<?php echo number_format($item->retain_weight,3);?>">
					<?php if($item->card_status == 'normal'){?>
					<div class="cz_list_btn"><input type="hidden" class="form_sn" value="">
						<?php if(checkOperation("保留库存:新增")){?>
						<span class="retain" id="<?php echo $item->id;?>" title="保留件数"><img src="/images/bljs.png"></span>
						<?php }?>
						<?php if($_REQUEST["type"] != "retain"){?>
						<?php if(checkOperation("盘盈盘亏:新增") && $item->left_amount == 0){?>
							<span onclick="setPypk(<?php echo $item->id;?>);"><img src="/images/pypk.png"></span>
						<?php }
							if($item->left_amount == 0){
								if(checkOperation("清卡")){ ?>
						<span class="qingka" id="<?php echo $item->id;?>" url="/index.php/storage/qingka/<?php echo $item->id;?>" title="清卡"><img src="/images/qingka.png"></span>
						<?php 	}
							 }?>
						<?php }else{
						?>
						<?php if(checkOperation("保留库存:新增")){?>
						<span class="delete" id="<?php echo $item->id;?>" title="作废"><img src="/images/zuofei.png"></span>
						<?php }?>
						<?php }?>
					</div>
					<?php }?>
				</td>
				<td><a href="<?php echo $storageturnover_url?>"><?php echo $item->card_no; ?></a></td>
				<td ><?php echo Warehouse::getName($item->warehouse_id); ?></td>	
				<td ><?php switch ($item->card_status){
					case "normal":echo "正常";break;
					case "clear":echo "清卡";break;	
					case "deleted":echo "删除";break;
					default:echo "未知";
				}
				?></td>
				<td ><?php echo DictGoodsProperty::getProName($item->brand_id); ?></td>
				<td ><?php echo DictGoodsProperty::getProName($item->product_id); ?></td>
				<td ><?php echo str_replace('E','<span class="red">E</span>',DictGoodsProperty::getProName($item->texture_id)); ?></td>
				<td ><?php echo DictGoodsProperty::getProName($item->rank_id); ?></td>
				<td class="rightAlign"><?php echo $item->length; ?></td>
				<td class="rightAlign"><?php echo $item->weight;?></td>
				<td class="rightAlign"><?php echo $item->input_amount; ?></td>
				<td class="rightAlign"><?php echo number_format($item->input_weight,3); ?></td>
				<td class="rightAlign"><?php echo $item->left_amount; ?></td>
				<td class="rightAlign"><?php echo number_format($item->left_weight,3); ?></td>
				<td class="rightAlign"><?php echo $item->retain_amount; ?></td>
				<td class="rightAlign"><?php echo number_format($item->retain_weight,3);?></td>
				<td ><?php echo $item->input_date?date("Y-m-d",$item->input_date):""; ?></td>
				<?php if($item->input_type=="ccrk"){?>
				<td style="color:red"><?php echo $item->pre_input_date?date("Y-m-d",$item->pre_input_date):""; ?></td>
				<?php }else{?>
				<td></td>
				<?php }?>
				<td ><?php switch($item->input_type){
							case "purchase":echo "采购";break;
							case "thrk":echo "销售退货";break;
							case "ccrk":echo "船舱入库";break;
							case "qt":echo "其他";
							default:echo "未知";}; ?></td>
				<td class="rightAlign"><?php echo number_format($item->cost_price,0); ?></td>
				<td class="rightAlign"><?php echo number_format($item->invoice_price,0); ?></td>
				<td ><?php echo $item->is_price_confirmed==1?"是":""; ?></td>
				<td ><?php echo $item->is_yidan==1?"是":""; ?></td>	
				<td ><?php echo $item->is_pledge==1?"是":""; ?></td>				
				<td ><?php echo $item->title->short_name; ?></td>
				<td ><span title="<?php echo $item->redeemCompany->name;?>"><?php echo $item->redeemCompany->short_name;?></span></td>
<!--				<td ><?php #echo $item->is_dx==1?"是":""; ?></td>-->
			</tr>
			<?php $i++;  }?>
		</tbody>
	</table>
</div>	
<div class="total_data">
	<div class="total_data_one">剩余件数：<span><?php echo $totaldata["amount"];?></span></div>
	<div class="total_data_one">剩余重量：<span class="color_org"><?php echo number_format($totaldata["weight"],3);?></span></div>
	<div class="total_data_one" style="display:none;">金额：<span><?php echo number_format($totaldata["price"],2);?></span></div>
	<div class="total_data_one" style="display:none;">单数：<span class="color_org"><?php echo $totaldata["total_num"];?></span></div>
</div>	
<?php paginate($pages,"kc")?>
<div class="dialogbody1" style="display:none;">
	<div class="pop_background"></div>
	<div class="check_background" id="check">
		<div class="retain_div">
			<input type="hidden" id="this_card" value="">
			<input type="hidden" id="this_weight" value="">
			<div class="pop_title">
				设置保留件数
			</div>
			<div style="margin-top:20px;">
			<div class="shop_more_one">
				<div class="shop_more_one_l">销售公司：</div>
				<input type="text" value="" readonly class="this_title form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">仓库：</div>
				<input type="text" value="" readonly class="this_warehouse form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">卡号：</div>
				<input type="text" value="" readonly class="this_card_no form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">剩余件数：</div>
				<input type="text" value="" readonly class="this_left form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">保留件数：</div>
				<input type="text" value="" class="this_retain form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">保留重量：</div>
				<input type="text" value="" class="this_retain_weight form-control">
			</div>
			</div>
			<div class="pop_footer">
				<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="bl_cancel">取消</button>
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="sure">确定</button>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//清卡
	$(document).on("click",".qingka",function(){
		var title=$(this).attr("title");
		var href = $(this).attr('url');
		var num = $(this).parent().parent().find(".card_no").val();
		var text = '卡号：'+num+',确认要清卡吗';
		if(href != ''){
			confirmDialog2(text,href);
		}
	})
	
	$(document).on("click",".retain",function(){
		var card_id = $(this).attr("id");
		var card_no = $(this).parent().parent().find(".card_no").val();
		var title = $(this).parent().parent().find(".title_name").val();
		var waerhouse = $(this).parent().parent().find(".warehouse_name").val();
		var left = $(this).parent().parent().find(".left_amount").val();
		var lock = $(this).parent().parent().find(".lock_amount").val();
		var retain = $(this).parent().parent().find(".retain_amount").val();
		var retain_weight = $(this).parent().parent().find(".retain_weight").val();
		var oneweight = $(this).parent().parent().find(".oneweight").val();
		$("#this_card").val(card_id);
		$("#this_weight").val(oneweight);
		$(".this_title").val(title);
		$(".this_warehouse").val(waerhouse);
		$(".this_card_no").val(card_no);
		$(".this_left").val(left);
		$(".this_retain").val(retain);
		$(".this_retain_weight").val(retain_weight);
		$(".dialogbody1").show();
	})
	
	$(document).on("click","#bl_cancel",function(){
		$(".dialogbody1").hide();
	})
	
	//保留件数发生变化
	$(document).on("change",".this_retain",function(){
		var oneweight = $("#this_weight").val();
		var amount = $(this).val();
		var weight = (oneweight*amount).toFixed(3);
		$(".this_retain_weight").val(weight);
	})
	
	$(document).on("click","#sure",function(){
		var id = $("#this_card").val();
		var left = Number($(".this_left").val());
		var lock = Number($(".this_lock").val());
		var retain = Number($(".this_retain").val());
		var weight = $(".this_retain_weight").val();
		if(weight == ''){confirmDialog("保留重量不能为空");return false;}
		if(left - lock - retain < 0){
			confirmDialog("保留件数大于可用件数");
		}else{
			$.post("/index.php/storage/setRetain",{"id":id,"num":retain,"weight":weight},function(data){
				if(data == "little")
				{
					confirmDialog("保留件数大于可用件数");
					return false;
				}
				if(data == "success"){
					window.location.reload();
				}else{
					confirmDialog("操作失败");
				}
			});
		}
	})
	
	//作废
	$(document).on("click",".delete",function(){
		var card_no = $(this).parent().parent().find(".card_no").val();
		var str="卡号："+card_no+",确定要作废设置的保留件数吗？";
		var id = $(this).attr("id");
		confirmDialog3(str,function(){
			$.post("/index.php/storage/setRetain",{"id":id,"num":0},function(data){
				if(data == "little")
				{
					confirmDialog("保留件数大于可用件数");
					return false;
				}
				if(data == "success"){
					window.location.reload();
				}else{
					confirmDialog("操作失败");
				}
			});
		});
	});

	//设置盘盈盘亏
	function setPypk(id){
		$.post("/index.php/storage/getPypkData",{"id":id},function(html){
			$("body").append(html);
		});
	}
	//盘盈盘亏确定
	$(document).on("click","#pypk_sure",function(){
		var id = $("#pypk_card").val();
		var amount = $(".pypk_num").val();
		var weight = $(".pypk_weight").val();
		var comment = $(".pypk_comment").val();
		$.post("/index.php/storage/setPypk",{"id":id,"weight":weight,"comment":comment},function(data){
			if(data == "success"){
				$(".dialogbody2").remove();
				var url="/index.php/storage/qingka/"+id;
				var text = "您是否对此单据进行清卡？";
				confirmDialogCancle(text,url);
			}else{
				confirmDialog("操作失败");
			}
		});
	})
	
	$(document).on("click","#pypk_cancel",function(){
		//$(".dialogbody1").hide();
		$(".dialogbody2").remove();
	})
	</script>
  <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.combobox.js"></script>
  <script type="text/javascript">	
	$(function(){
		$('#datatable1').datatable({
	    	 fixedLeftWidth:270,
	    	 fixedRightWidth:0,
	      });
		var array_product=<?php echo $products;?>;
		var array_texture=<?php echo $textures;?>;
		var array_rank=<?php echo $ranks;?>;
		var array_brand=<?php echo $brands;?>;
		var array_ck = <?php echo $warehouse;?>;
		var array_tt=<?php echo $titles;?>;
		$('#combo_product').combobox(array_product, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_p","comboval_product",false);
		$('#combo_texture').combobox(array_texture, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_texture");
		$('#combo_rank').combobox(array_rank, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_r","comboval_rank",false);
		$('#combo_brand').combobox(array_brand, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_b","comboval_brand",false);
		$('#combo_ck').combobox(array_ck, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_ck","comboval_ck",false);
		$('#combott').combobox(array_tt, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"ttselect","combovaltt",false);
	})
</script>