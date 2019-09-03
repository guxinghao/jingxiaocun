<div class="dialogbody2">
	<div class="pop_background"></div>
	<div class="check_background" id="check">
		<div class="retain_div" style="height:265px;">
			<input type="hidden" id="pypk_card" value="<?php echo $model->id;?>">
			<div class="pop_title">
				设置盘盈盘亏
			</div>
			<div style="margin-top:20px;">
			<div class="shop_more_one">
				<div class="shop_more_one_l">卡号：</div>
				<input type="text" value="<?php echo $model->card_no;?>" readonly class="pypk_card_no form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">件数：</div>
				<input type="text" value="<?php echo $model->input_amount;?>" readonly class="pypk_amount form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">剩余件数：</div>
				<input type="text" value="<?php echo $model->left_amount;?>" readonly class="pypk_left form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">剩余重量：</div>
				<input type="text" value="<?php echo number_format($model->left_weight,3);?>" readonly class="pypk_lock form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">盈亏重量：</div>
				<input type="text" value="<?php echo number_format($model->left_weight,3);?>" readonly class="pypk_weight form-control">
			</div>
			<div class="shop_more_one">
				<div class="shop_more_one_l">备注：</div>
				<input type="text" value="" class="pypk_comment form-control">
			</div>
			</div>
			<div class="pop_footer">
				<button type="button" class="btn btn-primary btn-sm gray" data-dismiss="modal" style="color:#333;" id="pypk_cancel">取消</button>
				<button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" id="pypk_sure">确定</button>
			</div>
		</div>
	</div>
</div>