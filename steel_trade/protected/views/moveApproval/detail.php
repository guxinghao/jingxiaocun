<?php
	$_type = array("DQDK"=>"短借","FKDJ"=>"付款","FYBZ"=>"费用");
	$_status = array("submited"=>"待审核","approved_1"=>"业务经理审核通过","approved_2"=>"财务主管审核通过","approved_3"=>"总经理审核通过","approve"=>"出纳审核通过","accounted"=>"出纳审核通过");
	$_billstatus = array("submited"=>"待审核","approved_1"=>"业务经理审核通过","approved_2"=>"财务主管审核通过","approved_3"=>"已通过","approve"=>"已通过","accounted"=>"已通过");
	$_billtype = array("CGFK"=>"采购付款","GKFK"=>"高开付款","XSTH"=>"销售退货","FYBZ"=>"费用报支","lend"=>"短期贷款");
	
	$chuna1=CheckOperation('付款登记:出纳审核');
	$zongjingli1=CheckOperation('付款登记:总经理审核');
	$caiwuzhuguan1=CheckOperation('付款登记:财务主管审核');
	$yewu1=CheckOperation('付款登记:业务经理审核');
	
	$chuna2=CheckOperation('费用报支:出纳审核');
	$zongjingli2=CheckOperation('费用报支:总经理审核');
	$caiwuzhuguan2=CheckOperation('费用报支:财务主管审核');
	$yewu2=CheckOperation('费用报支:业务经理审核');
	
	$chuna3=CheckOperation('短期借贷:出纳审核');
	$zongjingli3=CheckOperation('短期借贷:总经理审核');
	$caiwuzhuguan3=CheckOperation('短期借贷:财务主管审核');
	$yewu3=CheckOperation('短期借贷:业务经理审核');
	
	$status = $baseform->form_status;
	$type = $baseform->form_type;
	$can_operation = '';
	$appreve_result = '';
	if($status == "unsubmit"){
		$can_operation = false;
		$appreve_result = "未通过";
	}
	//没有可操作权限，进不来单据明细，可以先不判断，不提示没有操作权限的情况
	if($status == "submited"){
		$can_operation = true;
		$appreve_result = "待审核";
	}
	if($status == "approved_1"){
		if($type == "FKDJ"){
			if($caiwuzhuguan1){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($yewu1){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
		if($type == "FYBZ"){
			if($caiwuzhuguan2){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($yewu2){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
		if($type == "DQDK"){
			if($caiwuzhuguan3){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($yewu3){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
	}
	if($status == "approved_2"){
		if($type == "FKDJ"){
			if($baseform->fee >=10000){
				if($zongjingli1){
					$can_operation = true;
					$appreve_result = "待审核";
					$liststatus = "待审核";
					$div_class = "cstbb-liing";
				}else if($caiwuzhuguan1){
					$can_operation = false;
					$appreve_result = "已通过";
					$liststatus = "已通过";
					$div_class = "cstbb-liok";
				}
			}else{
				if($chuna1){
					$can_operation = true;
					$appreve_result = "待审核";
					$liststatus = "待审核";
					$div_class = "cstbb-liing";
				}else if($caiwuzhuguan1){
					$can_operation = false;
					$appreve_result = "已通过";
					$liststatus = "已通过";
					$div_class = "cstbb-liok";
				}
			}
		}
		if($type == "FYBZ"){
			if($zongjingli2){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($caiwuzhuguan2){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
		if($type == "DQDK"){
			if($zongjingli3){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($caiwuzhuguan3){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
	}
	if($status == "approved_3"){
		if($type == "FKDJ"){
			if($baseform->fee >=10000){
				if($chuna1){
					$can_operation = true;
					$appreve_result = "待审核";
					$liststatus = "待审核";
					$div_class = "cstbb-liing";
				}else if($zongjingli1){
					$can_operation = false;
					$appreve_result = "已通过";
					$liststatus = "已通过";
					$div_class = "cstbb-liok";
				}
			}else{
				if($chuna1){
					$can_operation = true;
					$appreve_result = "待审核";
					$liststatus = "待审核";
					$div_class = "cstbb-liing";
				}else if($caiwuzhuguan1){
					$can_operation = false;
					$appreve_result = "已通过";
					$liststatus = "已通过";
					$div_class = "cstbb-liok";
				}
			}
		}
		if($type == "FYBZ"){
			if($chuna2){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($zongjingli2){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
		if($type == "DQDK"){
			if($chuna3){
				$can_operation = true;
				$appreve_result = "待审核";
			}else if($zongjingli3){
				$can_operation = false;
				$appreve_result = "已通过";
			}
		}
	}
	if($status == "approve"){
		if(($type == "FKDJ" && $chuna1) || ($type == "FYBZ" && $chuna2) || ($type == "DQDK" && $chuna3)){
			$can_operation = false;
			$appreve_result = "已通过";
		}
	}
?>
<input type="hidden" id="last_update" value="<?php echo $baseform->last_update;?>">
<section class="cde-main">
	<section class="cde-content">
	<ul class="cdet-a" style="<?php if($appreve_result!="已通过"){echo 'background:#fff;';}?>">
		<li><label>单据号：</label><span><?php echo $baseform->form_sn;?></span></li>
		<li><label>审批分类：</label><span><?php echo $_type[$baseform->form_type];?></span></li>
		<li><label>发起时间：</label><span class="cdet-s"><?php echo $baseform->form_time;?></span></li>
		<li><label>发起人：</label><span><?php echo $baseform->owned_by_nickname;?></span></li>
		<li><label>总金额：</label><span class="cdet-s"><?php echo number_format($baseform->fee,2);?></span></li>
		<?php if($baseform->purpose){?>
		<li><label>用途：</label><span><?php echo $baseform->purpose;?></span></li>
		<?php }?>
		<li><label>已审批人：</label><span><?php echo $baseform->approved_by_nickname;?></span></li>
		<li>
			<label>状态：</label>
			<span>
				<?php if($baseform->form_type == "FKDJ" && $baseform->form_status == "approved_3" && $baseform->fee <10000){echo "财务主管审核通过";}
						else{ echo $_status[$baseform->form_status];};?>
				<img src="<?php echo imgUrl('pic_6.png');?>" onclick="getCheckList(<?php echo $baseform->common_id;?>);"/>
			</span>
		</li>
		<li><label>审批时间：</label><span class="cdet-s"><?php echo $baseform->approved_at>100?date("Y-m-d",$baseform->approved_at):'';?></span></li>
		<li><label>公司抬头：</label><span ><?php echo $baseform->title_name;?></span></li>
		<?php if($bank){?>
		<li><label>公司账户：</label><span ><?php echo $bank->dict_name;?></span></li>
		<?php }?>
		<li><label>结算单位：</label><span ><?php echo $baseform->customer_name;?></span></li>
	</ul>
</section>
</section>
<section class="cde-h2">
	<h2><span>明细</span></h2>
</section>
<section class="cde-b">
	<ul>
		<li>审批类型： <?php echo $_billtype[$baseform->bill_type];?></li>
		<?php 
		if($detail){
			foreach ($detail as $li){
		?>
		<li><label><?php echo $li->recordType2->name;?></label><span><?php echo number_format($li->fee, 2);?></span></li>
		<?php 
			}
		}else{
		?>
		<li><label>金额：</label><span><?php echo number_format($baseform->fee, 2);?></span></li>
		<?php }?>
	</ul>
</section>
<?php ?>
<section class="cde-result">
	<ul>
		<li><label>备注：</label><span><?php echo $baseform->comment;?></span></li>
	</ul>
</section>
<?php if($can_operation){?>
<section class="cde-c">
	<ul>
		<li class="cde-ok"><span>同 意</span></li>
		<li class="cde-no"><span>拒 绝</span></li>
	</ul>
</section>
<?php }else{?>
<section class="cde-result">
	<ul>
		<li><label>审批结果：</label><span style="color: #00cc33;"><?php echo $appreve_result;?></span></li>
	</ul>
</section>
<?php }?>
<section class="cost-bj2" style="display: none;"></section>
<section class="cost-widw unpass" style="display: none;">
	<h3>温馨提示</h3>
	<p>您是否确认拒绝此项费用审批？</p>
	<ul>
		<li class="cs-no">取消</li>
		<li class="cs-ok" onclick="unpass(<?php echo $baseform->common_id;?>);">确定</li>
	</ul>
</section>
<section class="cost-widw pass" style="display: none;">
	<h3>温馨提示</h3>
	<p>您是否确认同意此项费用审批？</p>
	<ul>
		<li class="cs-no">取消</li>
		<li class="cs-ok" onclick="pass(<?php echo $baseform->common_id;?>);">确定</li>
	</ul>
</section>