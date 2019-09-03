$(function(){
	$('.costa-li').on('click', function(){
		$('.costa-li').removeClass('costa-lic');
		$('.costa-lib').hide();
		if( $(this). hasClass('costa-lic')){
			$(this).removeClass('costa-lic');
			$(this).next('li').hide();
			$('.cost-bj').hide();
		}else{
			$(this).addClass('costa-lic');
			$(this).next('li').show();
			$('.cost-bj').show();
		}
	});
	
	$('.cs-no').on('click',function(){
		$('.cost-bj2').hide();
		$('.cost-widw').hide();
	});
	
	$('.cde-no').on('click',function(){
		$('.cost-bj2').show();
		$('.unpass').show();
	});
	$('.cde-ok').on('click',function(){
		$('.cost-bj2').show();
		$('.pass').show();
	});
	$('.costa-lib span').on('click', function(event){
		$(this).parent().find("span").removeClass('costa-sa');
		$(this).parent().find("span").children('img').hide();
		$(this).children('img').show();
		$(this).addClass('costa-sa');
		var text = $(this).text();
		if(text == "全部"){
			if($(this).parent().hasClass("type")){
				text="分类";
			}else{
				text="全部";
			}
		}
		$(this).parent().prev().find("em").text(text);
		$('.costa-lib').hide();
		$('.cost-bj').hide();
		getDataList();
	});
	
	$('.cost-bj').on('click', function(){
		$('.costa-lib').hide();
		$('.cost-bj').hide();
	});
	
	$(document).on("click",".wow dl dd",function(){
		$('.wow').remove();
		$('.his_back').remove();
	});

	$(document).on("click",".wow p ",function(){
		$('.wow').remove();
		$('.his_back').remove();
	});
})

function removeCheckList(){
	$('.wow').remove();
	$('.his_back').remove();
}
function getDataList(){
	var type=$(".type").find(".costa-sa").attr("value");
	var result=$(".result").find(".costa-sa").attr("value");
	$.post("/index.php/moveApproval/getDataList",{"type":type,"result":result},function(html){
		$(".datalist").html(html);
	})
}

function getCheckList(id){
	$.post("/index.php/moveApproval/getCheckList",{"id":id},function(html){
		$("body").append(html);
	})
}

function unpass(id){
	var last = $("#last_update").val();
	$('.cost-bj2').fadeOut();
	$('.unpass').fadeOut();
	$.post("/index.php/moveApproval/unpass",{"id":id,"last":last},function(data){
		if(data == "success"){
			location.href = "/index.php/moveApproval/index";
		}else{
			moveConfirm(data);
		}
	})
}
function pass(id){
	var last = $("#last_update").val();
	$('.cost-bj2').fadeOut();
	$('.pass').fadeOut();
	$.post("/index.php/moveApproval/pass",{"id":id,"last":last},function(data){
		if(data == "success"){
			location.href = "/index.php/moveApproval/index";
		}else{
			moveConfirm(data);
		}
	})
}
//公共错误提示弹出方法
function moveConfirm(text){
	var str='<section class="cost-bj2 dialogbody"></section>'+
			'<section class="wow wow_1">'+
				'<dl>'+
					'<dt>提示：</dt>'+
					'<dd><i class="icon icon-times"></i></dd>'+
				'</dl>'+
				'<ul>'+
					'<li>'+text+
					'</li>'+
				'</ul>'+
				'<p class="IKnowIt"><span>确定</span></p>'+
			'</section>';
	$("body").append(str);
	$(".IKnowIt").on("click",function(e){
		//$(".dialogbody").remove();
		 location.reload();
	});
}
