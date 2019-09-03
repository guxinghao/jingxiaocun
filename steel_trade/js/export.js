$(function(){
	$(".btn_export").click(function(){
		var form = $(this).parent().parent().find("form");
		var export_ur = $(this).attr('url');
		form.attr('action', export_ur).attr('target', '_blank').submit();

	});

	$(".search_body .btn_sub").click(function(){
		if($(".btn_export").length>0)   
		{   
			var form = $(this).parent().parent('form');
			var search_url = form.attr('url');
			form.attr('action', search_url).attr('target', '_self');
		}   
	});
});