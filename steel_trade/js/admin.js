/**
 * 
 */

$(function(){
	$(".list_del").click(function(){
		var id = $(this).attr("name");
		var table = $("#db_table").val();
		if (!window.confirm("确定要删除吗"))
			return false;
		else{
			$.post("/index.php/site/delete",{'id':id,'tableclass':table},function(data){
				if(data==1){
					window.location.reload();
				}
			});
		}
	});
	
	//跳转消息列表
	$(".index_top .message").click(function(){
	   location.href="/index.php/messageCenter/"; 
	});
	
	/*
	 * 定时获取最新消息数
	 * */
	setInterval("getNewMessage()",12000);
	
	//编辑优先级
	$("#edit_priority").click(function(){
		if(!window.confirm("编辑优先级")){return false;}
		var id_str="";
		var priority_str="";
		
		$(".priority").each(function(){
			id_str=id_str+$(this).attr("name")+"|";
			priority_str=priority_str+$(this).attr("value")+"|";
		});
		$.post("/index.php/site/editPriority",{
			'id_str':id_str,
			'priority_str':priority_str,
			'search': $('#type').attr('value'),
			'db_table':$('#db_table').attr('value'),
			'post_type':'edit_priority'
		},function(data){
			window.location.reload();
		});		
		
	});
	
	//清空优先级
	$("#clear_priority").click(function(){
		if(!window.confirm("清空优先级")){return false;}
		$(".priority").attr("value","");
		var id_str="";
		var priority_str="";
		$(".priority").each(function(){
			id_str=id_str+$(this).attr("name")+"|";
			priority_str=priority_str+$(this).attr("value")+"|";
		});
		$.post("/index.php/site/editPriority",{
			'id_str':id_str,
			'priority_str':priority_str,
			'search': $('#type').attr('value'),
			'db_table':$('#db_table').attr('value'),
			'post_type':'clear_priority'
		},function(data){
			window.location.reload();
		});		
	});
});

function formatNum(str){
	var newStr = "";
	var count = 0;
	if(str.indexOf(".")==-1){
	   	for(var i=str.length-1;i>=0;i--){
	    	 if(count % 3 == 0 && count != 0){
	    	   newStr = str.charAt(i) + "," + newStr;
	    	 }else{
	    	   newStr = str.charAt(i) + newStr;
	    	 }
	    	 count++;
	   }
	   str = newStr + ".00"; //自动补小数点后两位
	   return str;
	}else{
	   for(var i = str.indexOf(".")-1;i>=0;i--){
    	 if(count % 3 == 0 && count != 0){
    	   newStr = str.charAt(i) + "," + newStr;
    	 }else{
    	   newStr = str.charAt(i) + newStr; //逐个字符相接起来
    	 }
    	 count++;
	   }
	   str = newStr + (str + "00").substr((str + "00").indexOf("."),3);
	  return str;
	 }
}

function getUrlParam(url, name)
{	
	var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
	var r = url.substring(url.indexOf('?') + 1).match(reg);  //匹配目标参数
	if (r!=null) return unescape(r[2]); return null; //返回参数值
} 

function getNewMessage()
{
    $.post("/index.php/messageCenter/getCount/",{
    	'type':'all'
    },function(data){
        $(".msg_count").html(data);
        if(data == 0){
        	$(".msg_count").hide();
        }else{
        	$(".msg_count").show();
        }
    });
}






