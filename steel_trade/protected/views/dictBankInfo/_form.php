<style>
.shop_more_one .check_box{margin:10px 3px 0 0;;width:14px;height:14px;}
label{float:left;}
.shop_more_checkbox input{float:left;}
</style>
<?php
$form = $this->beginWidget ( 'CActiveForm', array (
        'enableAjaxValidation'=>true,
        'htmlOptions' => array (
                'id' => 'output_fee' ,
                'enctype'=>'multipart/form-data',
        ) 
) );
?>
<div class="shop_select_box">
    <div class="shop_more_one">
        <div class="shop_more_one_l"><span class="bitian">*</span>开户银行：</div>
        <input type="text"  id="name" name="DictBankInfo[bank_name]" style="width:150px;height:33px;" value="<?php echo $model->bank_name;?>" class="form-control "   >
    </div>
    <div class="shop_more_one">
        <div class="shop_more_one_l"><span class="bitian">*</span>账户名称：</div>
        <input type="text"  id="dname" name="DictBankInfo[dict_name]" style="width:150px;height:33px;" value="<?php echo $model->dict_name;?>" class="form-control "   >
    </div>
    <div class="shop_more_one">
        <div class="shop_more_one_l"><span class="bitian">*</span>账　　号：</div>
        <input type="text"  id="account" name="DictBankInfo[bank_number]" style="width:150px;height:33px;" value="<?php echo $model->bank_number;?>" class="form-control "   >
    </div>
    <div class="shop_more_one"></div>
    <div class="shop_more_one">
        <div class="shop_more_one_l">拼　　音：</div>
        <input type="text"  id="code" name="DictBankInfo[code]" style="width:150px;height:33px;" value="<?php echo $model->code;?>" class="form-control "   >
    </div>
   <div class="shop_more_one">
        <div class="shop_more_one_l">期初金额：</div>
        <input type="text" onKeypress="return (/[\d.]/.test(String.fromCharCode(event.keyCode)))" id="money" name="DictBankInfo[initial_money]" style="width:150px;height:33px;" value="<?php echo $model->initial_money;?>" class="form-control "   >
    </div>
    <?php if($model->id){?>
    <input type="hidden" name="DictBankInfo[id]" value="<?php echo $model->id;?>">
    <div class="shop_more_one">
        <div class="shop_more_one_l">创建时间：</div>
        <span><?php echo $model->created_at?date("Y-m-d H:i:s",$model->created_at):"-";?></span>
    </div>
     <div class="shop_more_one"></div>
    <div class="shop_more_one">
        <div class="shop_more_one_l">创&nbsp;&nbsp;建&nbsp;&nbsp;者：</div>
        <span><?php echo $model->created_by?User::getUserName($model->created_by):"-";?></span>
    </div>
    <div class="shop_more_one">
        <div class="shop_more_one_l">最后修改：</div>
        <span><?php echo $model->last_update_at?date("Y-m-d H:i:s",$model->last_update_at):"-";?></span>
    </div>
    <div class="shop_more_one">
        <div class="shop_more_one_l">最后修改人：</div>
        <span><?php echo $model->last_update_by?User::getUserName($model->last_update_by):"-";?></span>
    </div>
     <div class="shop_more_one"></div>
    <!-- 进入本页最后更新时间 -->
    <input type="hidden" name='lupt' value="<?php echo time();#$this->getUpdateTime($model->tableName(), $model->id)?>"/>
    <?php }?>
     <div class="shop_more_one" style="width:100%;height:auto;">
        <div class="shop_more_one_l" ><span class="bitian">*</span>公司抬头：</div>
       <div style="float:right;width:1000px;min-height:30px;">
		<?php
			$num = 0;
			foreach ($titles as $k=>$v){
			if($num % 8 == 0 && $num !=0){echo "<br/>";}
			$num++;
		?>
		<div class="shop_more_checkbox" style="width:auto;">
			<label class="radio-inline">
				<input class="check_box title" type="checkbox" value="<?php echo $k;?>" name="DictBankInfo[dict_title_id][]" <?php if(in_array($k,$titlelist)){echo "checked";}?>><?php echo $v?>
			</label>
			</div>
		<?php }?>
       </div>
    </div>

    <div class="shop_more_one" style="width:100%;height:auto;">
        <div class="shop_more_one_l" ><span class="bitian">*</span>账户类别：</div>
        <div style="float:right;width:1000px;min-height:30px;">
        <input type="hidden" id="level" name="DictBankInfo[bank_level]" value="<?php echo $model->bank_level?>">
        <?php
            $types=array('1'=>'甲单','-1'=>'乙单');
            foreach ($types as $k=>$v){            
        ?>
        <div class="shop_more_checkbox" style="width:auto;">
            <label class="radio-inline">
                <input class="check_box level" type="checkbox" value="<?php echo $k;?>" <?php if(in_array($model->bank_level,array($k,0))){echo "checked";}?>><?php echo $v?>
            </label>
            </div>
        <?php }?>
       </div>
    </div>
<?php if(checkOperation("凭证列表")){?>
	<div class="shop_more_one" >
		   <div class="shop_more_one">
		        <div class="shop_more_one_l">财务编码：</div>
		        <input type="text"  id="number" name="DictBankInfo[number]" style="width:150px;height:33px;" value="<?php echo $model->number;?>" class="form-control "   >
    		</div>
	</div>
	<div class="shop_more_one" >
		   <div class="shop_more_one">
		        <div class="shop_more_one_l">是否现收：</div>
		        <div class="shop_more_checkbox" style="width:auto;">
			       <label class="radio-inline">
	                	<input class="check_box" type="checkbox" value="1" <?php if($model->voucher_type==1){echo "checked";}?> name="DictBankInfo[voucher_type]" style="margin-top:9px;">是
	           		</label>
           		</div>
    		</div>
	</div>
<?php } ?>
</div>
<div class="btn_list create_table" style="width:99%">
    <button type="button" class="btn btn-primary btn-sm blue save" data-dismiss="modal" >保存</button>
    <button type="button" class="btn btn-primary btn-sm gray cancel" data-dismiss="modal" style="color:#333;">取消</button>
</div>
<?php
$this->endWidget ();
?>	
<script>
    $(function(){	
        <?php if($msg){?>
        confirmDialog('<?php echo $msg?>');
        <?php }?>
        $(".cancel").click(function(){
            location.href="<?php echo Yii::app()->createUrl('dictBankInfo/index',array('page'=>$_REQUEST['page'],'title_id'=>$_REQUEST['title_id']))?>";
        });
        $(".save").click(function(){
            if($.trim( $("#name").val() ) == "" || $.trim( $("#name").val() ) == null){
                confirmDialog("开户银行不能为空！");
                $("#name").focus();
                return false;
            }
            if($.trim( $("#dname").val() ) == "" || $.trim( $("#dname").val() ) == null){
                confirmDialog("账户名称不能为空！");
                $("#dname").focus();
                return false;
            }
            if($.trim( $("#account").val() ) == "" || $.trim( $("#account").val() ) == null){
                confirmDialog("账号不能为空！");
                $("#account").focus();
                return false;
            }
            var check  = false;
            $(".title").each(function(){
				if($(this).attr("checked")){
					check = true;
					return true;
				}
            });
            if(!check){
                confirmDialog("公司抬头不能为空！");
                return false;
            }
            
            var leveled=false;
            $(".level").each(function(){
                if($(this).attr("checked")){
                    leveled = true;
                    return true;
                }
            });
             if(!leveled){
                confirmDialog("账户类别至少选择一个！");
                return false;
            }

            if($("#money").val()>99999999.99){
                confirmDialog("金额过大，金额不能超过1亿元(不含1亿)！");
                $("#money").focus().select();
                return false;
            }
            $("form").submit();
        });
        $('.level').click(function(){
            var select=$(this).attr('checked');
            var level=parseInt($(this).val());
            if(select)
            {
                $('#level').val(parseInt($('#level').val())+level);
            }else{
                $('#level').val(parseInt($('#level').val())-level);
            }
        });
       // var array_t=<?php //echo $titles;?>;
       // $('#combo_t').combobox(array_t, {imageUrl : "<?php echo imgUrl('dropdown.png');?>"},"comselect_t","comboval_t",false);
    });
</script>