<link href="/css/message_center.css" type="text/css" rel="stylesheet" />
<div style="overflow: hidden; width: 100%; float: left">
	<div class="msg_head">
		<div class="msg_head_title">全部消息</div>
		<div class="msg_head_btns">
			<span class="all_read">全部已读</span>
			<div class="msg_head_sp"></div>
			<span class="all_del">全部删除</span>
		</div>
	</div>
	<div class="msg_list">
<?php foreach ($items as $item){
    ?>
    <div class="msg_item" href="<?php echo $item->url; ?>" isread="<?php echo $item->status;?>">
			<div class="msg_item_left">
            <?php if($item->status==1){?>
                <img alt="状态" src="/images/msgNew.png" />
            <?php }else{?>
                <img alt="状态" src="/images/msgRead.png" />
            <?php }?>
            </div>
			<div class="msg_item_right">
				<div class="msg_item_top">
					<div class="msg_item_title"><?php echo $item->title?></div>
					<div class="msg_item_date"><?php echo date("Y-m-d",$item->created_at);?></div>
				</div>
				<div class="msg_item_content"><?php echo $item->content?></div>
				<div class="msg_item_foot">
					<div class="msg_item_type">类型：<?php echo $item->type?></div>
					<div class="msg_item_btns">
				    <?php if($item->status==2){?>
					<span class="read_this">已读</span>
					<?php }else{?>
					<span class="unread_this" params="<?php echo $item->bid; ?>" >未读</span>
					<?php }?>
					<div class="msg_item_sp"></div>
					<span class="del_this" params="<?php echo $item->bid; ?>">删除</span>
				</div>
			</div>

		</div>
	</div>
    <?php }?>
</div>
<?php paginate($pages, "msg")?>
</div>
<script>
$(function(){
	$(".all_read").click(function(){
		var ids="";
		var num = 0;
		$(".del_this").each(function(){
			if($(this).prev().prev().html()=="未读")
			{
				ids+=","+$(this).attr("params");
				num ++;
			}
		});
		if(ids)
		{
			ids=ids.substring(1);
    		$.post("/index.php/messageCenter/setRead",{"id":ids},function(data){
    			if(data=="OK")
    			{
    			    //confirmDialog("修改成功！");
    				$(".unread_this").html("已读");
    				$(".unread_this").attr("class","read_this");
    				$(".msg_item_left img").attr("src","/images/msgRead.png");
    				var num1 = $(".msg_count").html();
    				num1 = Number(num1) - num;
    				$(".msg_count").html(num1);
    				if(num1 <= 0){
    					$(".msg_count").hide();
    				}	
    				$(".unread_this").attr("class","read_this");
    			}
    			else
    			{
    			    confirmDialog("修改失败！");
        		}
    		});
		}
		else
		{
		    confirmDialog("当前页没有未读消息！");
		}
	});
	$(".all_del").click(function(){
		confirmDialog3("您确定要全部删除吗？",function(){
			var ids="";
			$(".del_this").each(function(){
				ids+=","+$(this).attr("params");
			});
			if(ids)
			{
			    ids=ids.substring(1);
	    		$.post("/index.php/messageCenter/setDelete",{"id":ids},function(data){
	    			if(data=="OK")
	    			{
	    			    //confirmDialog("删除成功！");
	    				location.reload();
	    			}
	    			else
	    			{
	    			    confirmDialog("删除失败！");
	    			}
	    		});
			}
			else
			{
			    confirmDialog("您还没有消息！");
			}
		})
	});
	$(".unread_this").click(function(e){
	    var ids="";
	    var obj = $(this);
	    if($(this).hasClass("read_this")){return false;}
		$.post("/index.php/messageCenter/setRead",{"id":$(this).attr("params")},function(data){
			if(data=="OK")
			{
			    //confirmDialog("设置成功！");
			    obj.html("已读");
			    obj.attr("class","read_this");
			    obj.parent().parent().parent().parent().find(".msg_item_left img").attr("src","/images/msgRead.png");
			    obj.parent().parent().parent().parent().attr("isread",2);
			    var num = $(".msg_count").html();
			    num = Number(num) - 1;
			    $(".msg_count").html(num);
			    if(num <= 0){
			    	$(".msg_count").hide();
				}
			    obj.unbind("click");
			}
			else
			{
			    //confirmDialog("设置失败！");
			}
		});
		e.stopPropagation();
	});
	$(".del_this").click(function(e){
		var ids="";
		var obj=$(this);
		confirmDialog3("您确定要删除吗？",function(){
			$.post("/index.php/messageCenter/setDelete",{"id":obj.attr("params")},function(data){
				if(data=="OK")
				{
					window.location.reload();
				}
				else
				{
				    confirmDialog("删除失败！");
				}
			});
			e.stopPropagation();
		})
	});
	$(".msg_item").click(function(){
		var obj = $(this).find(".unread_this");
		var isread = $(this).attr("isread");
		var url = $(this).attr("href");
		if(isread == 2 || url == ''){return false;}
		$.post("/index.php/messageCenter/setRead",{"id":obj.attr("params")},function(data){
			if(data=="OK")
			{
				window.location.reload();
// 			    //confirmDialog("设置成功！");
// 			    obj.html("已读");
// 			    obj.attr("class","read_this");
// 			    obj.parent().parent().parent().parent().find(".msg_item_left img").attr("src","/images/msgRead.png");
// 			    obj.parent().parent().parent().parent().attr("isread",2);
			}
			else
			{
			    //confirmDialog("设置失败！");
			}
		});
	})
	$(".msg_item").each(function(){
	    var href=$(this).attr("href");
	    if($.trim(href))
	    {
			$(this).find(".msg_item_left").css("cursor","pointer");
			$(this).find(".msg_item_top").css("cursor","pointer");
			$(this).find(".msg_item_content").css("cursor","pointer");
		}
	});
	$(".msg_item_left").click(function(){
		var href=$(this).parent().attr("href");
		if($.trim(href))
		{
		    openUrl(href);
		}
	});
	$(".msg_item_top").click(function(){
		var href=$(this).parents(".msg_item").attr("href");
		if($.trim(href))
		{
		    openUrl(href);
		}
	});
	$(".msg_item_content").click(function(){
		var href=$(this).parents(".msg_item").attr("href");
		if($.trim(href))
		{
		    openUrl(href);
		}
	});
});
function openUrl(href)
{
	window.open(href);
}
</script>