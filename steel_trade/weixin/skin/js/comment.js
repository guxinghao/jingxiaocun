$(function(){
	
	
	
	//消息

	
	$(".infoa-li").on("swipeleft",function(){
			$(this).children('article').addClass('selected');
		}).on("swiperight",function(){
			$(this).children('article').removeClass('selected');
		});
	
	
	//修改电话号码
	var oPhone = $('.edit-number input');
//	console.log(oPhone)
	$('.edit-number img').on('touchstart', function(){
		oPhone.attr('value','');
		oPhone.focus();
//		alert(333)
//		console.log(oPhone.val());
	})
	
	
	//公司编辑
	$('.company-default').on('touchstart',function(){
		$(".company-eul").removeClass('company-eul');
		$(this).addClass('company-eul');
		$(this).siblings('li').addClass('company-eul');
		setDefault($(this).attr("data"));
		//if($(this).hasClass('company-eul')){
		//	$(this).removeClass('company-eul');
		//	$(this).siblings('li').removeClass('company-eul');
		//	setDefault(0);
		//}else{
		//	$(this).addClass('company-eul');
		//	$(this).siblings('li').addClass('company-eul');
		//	setDefault($(this).attr("data"));
		//}
	})
	
	//最新报价
	
	var oH = $(window).height();
	var oHhz = $('.lastest-hz').children('.lastest-hdla').height();
	var oHhzb = $('.lastest-hz').children('.lastest-hdlb').height();
	var oHhzc = $('.lastest-hz').children('.lastest-hdlc').height();
	var oHs = oH - 36;
	//$('.lastest-hdlb').css({'height':oHs-120+'px'})
	//$('.lastest-hdla').css({'overflow-y':'auto','height':oHs-120+'px'});
	//$('.lastest-hzlc').css({'overflow-y':'auto','height':oHs-120+'px'});
	$('.lastest-hdlb').css({'height':'auto'});
	$('.lastest-hdla').css({'overflow-y':'auto','height':'auto'});
	$('.lastest-hzlc').css({'overflow-y':'auto','height':'auto'});
	
	$('.lastest-hc').on('click',function(){
//		    $('.lastest-hz').hide();
			$('.lastest-hc').removeClass('lastest-hd');
			$('.cost-bj2').show();
			
		if($(this).next('.lastest-hz').is(':hidden')){
            $(this).addClass('lastest-hd');
			$(this).next('.lastest-hz').slideDown();
			$('.lastest-hz').not($(this).next('.lastest-hz')).hide();
			
		}else{
			$(this).next('.lastest-hz').slideUp();
			$('.cost-bj2').hide()
		}
		
	});
	$('.cost-bj2').on('touchstart',function(){
		$('.lastest-hd').trigger("click");
		return false;
	});
	//产地
	$('.lastest-hdla dt').children('span').on('click',function(){
		$('.lastest-hdla dt').children('span').removeClass('lastest-hsok');
		$(this).addClass('lastest-hsok');
		setSearch($(this),"brand");
		if($(this).hasClass('lastest-hsok')){
//			$(this).removeClass('lastest-hsok');
		}else{
			
		}
	});
	
	//产品
	var oNum = $('.lastest-hdlb').children('dd');
	$('.lastest-hdlb dt').children('span').on('click',function(){
		var oIndex = $(this).index();
		$('.lastest-hdlb dt').children('span').removeClass('lastest-hscolor');
		oNum.hide();
//		console.log(oNum.length);
         $(this).addClass('lastest-hscolor');
			if(oIndex == 0){
				
			}else{
				oNum.eq(oIndex-1).show();
			}
				
		if($(this).hasClass('lastest-hsok')){
//			$(this).removeClass('lastest-hscolor');
		}else{
			
//			console.log(oIndex)
			
		}
	});
	
	//oNum.eq(0).children('p').children('span').on('click',function(){
	//	oNum.eq(0).children('p').children('span').removeClass('lastest-hdlbcolor');
	//	$(this).addClass('lastest-hdlbcolor');
	//	setSearch($(this),"prefecture");
	//});
	oNum.eq(0).children('p').children('span').on('click',function(){
		oNum.eq(0).children('p').children('span').removeClass('lastest-hdlbcolor');
		$(this).addClass('lastest-hdlbcolor');
		setSearch($(this),"texture");
	});
	oNum.eq(1).children('p').children('span').on('click',function(){
		oNum.eq(1).children('p').children('span').removeClass('lastest-hdlbcolor');
		$(this).addClass('lastest-hdlbcolor');
		setSearch($(this),"rank");
	});
	oNum.eq(2).children('p').children('span').on('click',function(){
		oNum.eq(2).children('p').children('span').removeClass('lastest-hdlbcolor');
		$(this).addClass('lastest-hdlbcolor');
		setSearch($(this),"length");
	});
	
	//产区
	
	$('.lastest-hzlc dt').on('click',function(){
		$('.lastest-hzlc dt').removeClass('lastest-hlccolor');
		if($(this).hasClass('lastest-hsok')){}else{
			$(this).addClass('lastest-hlccolor');
			setSearch($(this),"product");
		}
	});
	
	function setDefault(company_id){
		$(".company_de").val(company_id);
		$.post("/wechat.php/user/companyDelete", {"company_id": company_id,"type":1}, function (data) {
			data = eval('(' + data + ')');
			if (data["code"] == 0) {
				alert("设置成功");
				window.location.href="/wechat.php/user";
			} else {
				alert(data["info"]);
			}
		});
	}

	function setSearch(_this,name){
		var data = _this.attr("data");
		$("."+name+"").val(data);
		var brand = $(".brand").val();
		var product = $(".product").val();
		var texture = $(".texture").val();
		var rank = $(".rank").val();
		var length = $(".length").val();
		var prefecture = $(".prefecture").val();
		window.location.href = "/wechat.php/quoted/?brand="+brand+"&product="+product+"&texture="+texture+"&rank="+rank+"&length="+length+"&prefecture="+prefecture;
	}
	
	
	
	
	
	
	
	
	
	
	
	//end
	
})

