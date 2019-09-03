<?php
	$_type = array("DQDK"=>"短借","FKDJ"=>"付款","FYBZ"=>"费用");
	$_billtype = array("lend"=>"短期借款","borrow"=>"短期贷款","CGFK"=>"采购付款","GKFK"=>"高开付款","XSTH"=>"销售退款","FYBZ"=>"费用报支");
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
	$num = 0;
	if($model){
		foreach ($model as $li){
			$liststatus = "";
			$div_class = "cstbb-liing";
			$status = $li->form_status;
			$formtype = $li->form_type;
			//待审核
			if($result == 1){
				$liststatus = "待审核";
				$div_class = "cstbb-liing";
				//付款登记
				//待审核，付款不必判断金额
				if($formtype == "FKDJ"){
					if(!(($yewu1 && $status == "submited") || ($caiwuzhuguan1 && $status == "approved_1") || ($zongjingli1 && $status == "approved_2") || ($chuna1 && $status == "approved_3"))){
						continue;
					}
				}
				//费用报支
				if($formtype == "FYBZ"){
					if(!(($yewu2 && $status == "submited") || ($caiwuzhuguan2 && $status == "approved_1") || ($zongjingli2 && $status == "approved_2") || ($chuna2 && $status == "approved_3"))){
						continue;
					}
				}
				//短期借贷
				if($formtype == "DQDK"){
					if(!(($yewu3 && $status == "submited") || ($caiwuzhuguan3 && $status == "approved_1") || ($zongjingli3 && $status == "approved_2") || ($chuna3 && $status == "approved_3"))){
						continue;
					}
				}
			}
			//已通过
			if($result == 2){
				$liststatus = "已通过";
				$div_class = "cstbb-liok";
				if($status == "submited" || $status == "unsubmit"){continue;}
				//付款登记
				if($formtype == "FKDJ"){
					if($status == "approved_1"){
						if(!($yewu1 && !$caiwuzhuguan1)){continue;}
					}
					if($li->fee >=10000){
						if($status == "approved_2"){
							if(!($caiwuzhuguan1 && !$zongjingli1)){continue;}
						}
						if($status == "approved_3"){
							if(!($zongjingli1 && !$chuna1)){continue;}
						}
					}else{
						if($status == "approved_3"){
							if(!($caiwuzhuguan1 && !$chuna1)){continue;}
						}
					}
					if($status == "approve"){
						if(!$chuna1){continue;}
					}
				}
				//费用报支
				if($formtype == "FYBZ"){
					if($status == "approved_1"){
						if(!($yewu2 && !$caiwuzhuguan2)){continue;}
					}
					if($status == "approved_2"){
						if(!($caiwuzhuguan2 && !$zongjingli2)){continue;}
					}
					if($status == "approved_3"){
						if(!($zongjingli2 && !$chuna2)){continue;}
					}
					if($status == "approve"){
						if(!$chuna2){continue;}
					}
				}
				//短期借贷
				if($formtype == "DQDK"){
					if($status == "approved_1"){
						if(!($yewu3 && !$caiwuzhuguan3)){continue;}
					}
					if($status == "approved_2"){
						if(!($caiwuzhuguan3 && !$zongjingli3)){continue;}
					}
					if($status == "approved_3"){
						if(!($zongjingli3 && !$chuna3)){continue;}
					}
					if($status == "approve"){
						if(!$chuna3){continue;}
					}
				}
			}
			//已拒绝
			if($result == 3){
				$liststatus = "未通过";
				$div_class = "cstbb-lino";
				if($status == "unsubmit"){
					$is_refuse = BillApproveLog::model()->find("form_id={$li->common_id} and (status='cancle' or status='refuse')");
					if(!$is_refuse){continue;}
				}else{
					continue;
				}
			}
			//全部，全部暂不显示未通过单据
			if($result == 0){
				if($status == "unsubmit"){continue;}
				if($status == "submited"){
					if(($formtype == "FKDJ" && $yewu1) || ($formtype == "FYBZ" && $yewu2) || ($formtype == "DQDK" && $yewu3)){
						$liststatus = "待审核";
						$div_class = "cstbb-liing";
					}else{
						continue;
					}
				}
				if($status == "approved_1"){
					if($formtype == "FKDJ"){
						if($caiwuzhuguan1){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($yewu1){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
					if($formtype == "FYBZ"){
						if($caiwuzhuguan2){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($yewu2){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
					if($formtype == "DQDK"){
						if($caiwuzhuguan3){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($yewu3){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
				}
				if($status == "approved_2"){
					if($formtype == "FKDJ"){
						if($li->fee >=10000){
							if($zongjingli1){
								$liststatus = "待审核";
								$div_class = "cstbb-liing";
							}else if($caiwuzhuguan1){
								$liststatus = "已通过";
								$div_class = "cstbb-liok";
							}else{
								continue;
							}
						}else{
							if($chuna1){
								$liststatus = "待审核";
								$div_class = "cstbb-liing";
							}else if($caiwuzhuguan1){
								$liststatus = "已通过";
								$div_class = "cstbb-liok";
							}else{
								continue;
							}
						}
					}
					if($formtype == "FYBZ"){
						if($zongjingli2){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($caiwuzhuguan2){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
					if($formtype == "DQDK"){
						if($zongjingli3){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($caiwuzhuguan3){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
				}
				if($status == "approved_3"){
					if($formtype == "FKDJ"){
						if($li->fee >=10000){
							if($chuna1){
								$liststatus = "待审核";
								$div_class = "cstbb-liing";
							}else if($zongjingli1){
								$liststatus = "已通过";
								$div_class = "cstbb-liok";
							}else{
								continue;
							}
						}else{
							if($chuna1){
								$liststatus = "待审核";
								$div_class = "cstbb-liing";
							}else if($caiwuzhuguan1){
								$liststatus = "已通过";
								$div_class = "cstbb-liok";
							}else{
								continue;
							}
						}
					}
					if($formtype == "FYBZ"){
						if($chuna2){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($zongjingli2){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
					if($formtype == "DQDK"){
						if($chuna3){
							$liststatus = "待审核";
							$div_class = "cstbb-liing";
						}else if($zongjingli3){
							$liststatus = "已通过";
							$div_class = "cstbb-liok";
						}else{
							continue;
						}
					}
				}
				if($status == "approve"){
					if(($formtype == "FKDJ" && $chuna1) || ($formtype == "FYBZ" && $chuna2) || ($formtype == "DQDK" && $chuna3)){
						$liststatus = "已通过";
						$div_class = "cstbb-liok";
					}else{
						continue;
					}
				}
			}
			$num++;
?>
<section class="costb-content">
	<article class="costb-one">
		<a href="<?php echo Yii::app()->createUrl('moveApproval/detail',array("id"=>$li->common_id))?>">
			<ul class="costb-ula">
				<li><?php echo $li->owned_by_nickname;?></li>
				<li><?php echo $_billtype[$li->bill_type];?></li>
				<li><?php echo number_format($li->fee,2);?></li>
				<li><?php echo $li->fee>=100000?date("m-d",strtotime($li->form_time)):date("y-m-d",strtotime($li->form_time));?></li>
			</ul>
			<p>备注：<?php echo $li->comment;?></p>
		</a>
		<ul class="costb-ulb">
			<li>
				<a href="<?php echo Yii::app()->createUrl('moveApproval/detail',array("id"=>$li->common_id))?>">
					<img src="<?php echo imgUrl('pic_8.png');?>"/>
				</a>
			</li>
			<li>|</li>
			<li><img src="<?php echo imgUrl('pic_6.png');?>" onclick="getCheckList(<?php echo $li->common_id;?>);"/></li>
			<li class="<?php echo $div_class;?>"><?php echo $liststatus;?></li>
		</ul>
	</article>
</section>
<?php 	
		}	
	}
	if($num == 0){
?>
<div class="tishi">
	<img alt="" src="<?php echo imgUrl('nodata.png');?>">
	<br/>
	对不起，您查看的分类暂无数据！
</div>		
<?php		
	}
?>