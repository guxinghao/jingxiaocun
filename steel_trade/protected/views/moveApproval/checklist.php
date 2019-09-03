<style>
.wow ul li span span{float:none;margin:0;padding:0;}
</style>
<section class="his_back"></section>
<section class="wow">
	<dl>
		<dt>审批记录</dt>
		<dd><img src="<?php echo imgUrl('close.png');?>"/></dd>
	</dl>
	<ul>
	<?php 
		if($items){
			foreach ($items as $li){
	?>
		<li>
			<span><?php echo $li->approver->nickname;?></span>
			<span><?php echo $li->description;?></span>
			<span><?php echo $li->created_at > 0 ? date("y-m-d H:i", $li->created_at) : '';?></span>
		</li>
		<?php 
			}
		}
		?>
	</ul>
	<p onclick="removeCheckList();"><span>确定</span></p>
</section>