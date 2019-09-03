

<style>
a{cursor:pointer}

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
		<input placeholder="请输入关键字" id="srarch" class="forreset" value="<?php echo $search->name?>" name="DictGoodsProperty[name]">
	</div><!-- 
	<div class="shop_more_one" style="margin-top:8px;width:230px;">
		<div class="shop_more_one_l" style="width: 70px;">属性类型：</div>
		<select name="DictGoodsProperty[property_type]" class="form-control chosen-select forreset">
	        <option value="product" >品名</option>
	    	<option value="texture" <?php #if($search->property_type=="texture"){?>selected="selected"<?php #}?>>材质</option> 
	    	<option value="brand" <?php #if($search->property_type=="brand"){?>selected="selected"<?php #}?>>产地</option> 
	    	<option value="rank" <?php #if($search->property_type=="rank"){?>selected="selected"<?php #}?>>规格</option>    	            	 
        </select>
	</div> -->
	<input type="submit" class="btn btn-primary btn-sm btn_sub " data-dismiss="modal" value="查询">
	<img  src="<?php echo imgUrl('reset.png');?>" class="reset" title="重置">
</div>

<?php $this->endWidget ();?>					
<div id=""  class="div_table">
	<table cellspacing="1" align="center" id="datatable1" class="table datatable" >
		<thead>
			<tr class="data">
				<th width="100" class="table_cell_first">优先级</th>
				<th width="100" >名称</th>
				<th width="100" >简称</th>
				<th width="100" >拼音</th>
				<th width="100" >标准码</th>
				<th width="100" >更新时间</th>
				<th width="100" class="table_cell_last">是否可用</th>
			</tr>
		</thead>
		<tbody>
			<?php $i=0;
				foreach ($items as $item){
			?>
			<tr id="<?php echo $item->id?>" class="data">
				<!--<td ><?php 
					switch ($item->property_type){
						case "product":echo "品名";break;
						case "texture":echo "材质";break;
						case "brand":echo "产地";break;
						case "rank":echo "规格";break;
						default:echo "未知";	
					}
				?></td>-->
				<td class="type table_cell_last">
					<input type="text" class="priority" name="<?php echo $item->name;?>" value="<?php echo $item->priority?>" style="width:50px;">
				</td>
				<?php if($item->property_type=="texture"){?>
				<td class="table_cell_first"><?php echo str_replace('E','<span class="red">E</span>',$item->name) ?></td>
				<td><?php echo str_replace('E','<span class="red">E</span>',$item->short_name) ?></td>
				<?php }else{?>
				<td class="table_cell_first"><?php echo $item->name ?></td>
				<td ><?php echo $item->short_name?></td>
				<?php }?>
				<td ><?php echo $item->code?></td>
				<td ><?php echo $item->std?></td>
				<td ><?php echo date("Y-m-d H:i:s",$item->last_update);?></td>
				<td class="table_cell_last"><?php echo $item->is_available==1? "是":"否"?></td>			
			</tr>
			<?php $i++;  }?>
			<tr class="">
				<input type="hidden" id="db_table" value="DictGoodsProperty">
				<input type="hidden" id="type" value="name">
	 			<td colspan="13" class="table_cell_first table_cell_last">
	 				<div class="page">
		 				<div class="btn_sub btn-sm btn-primary" id="edit_priority" style="margin-top:0"><span style="">保存优先级</span></div>
		 				<div class="btn_sub btn-sm btn-primary" id="clear_priority" style="margin-top:0"><span style="">清空优先级</span></div>
	 				</div>
	 			</td>
			</tr>
		</tbody>
		
	</table>
	
</div>			
<?php paginate($pages,"d_goodsd")?>