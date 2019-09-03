// usage:
//--------------Html----------------
// <input class="combo" />
//--------------Script-------------
// jQuery('.combo').combobox(['option1', 'option2', 'option3'], {imageUrl : '/images/dropdown.gif'});

(function($) {
    //these variables are placed here so that they are shared between different times of setting up comboboxes

    //keyCode data from http://unixpapa.com/js/key.html
	
    var keys = { up: 38, down: 40, enter: 13, tab: 9, esc: 27 };
    var hideTimer;
    var showing = false;
    var suggestionsKey = 'combobox_suggestions';
    var optionsContainer=new Dictionary();
    var functionname;
    $.fn.combobox = function(suggestions,config,parentclass,valid,isstand,funname,positionloc,is_clear) {
    	jsonData=eval(suggestions);
    	var isstandard=isstand ? isstand : false;
    	var position_loc=positionloc ? positionloc : "";
    	var clear=true;
    	if(is_clear==false)
    	{
    		clear=is_clear;
    	}
    	else
    	{
    		clear=true;
    	}
    	if(isstandard)
    	{
    		$(this).attr("disabled",isstandard);
    	}
        config = $.extend({ imageUrl: '/images/dropdown.png' }, config);
        optionsContainer.put(parentclass, $('<ul class="comboboxDropdown" />').appendTo($('#'+parentclass)));
        if(!optionsContainer.get(parentclass)) {
			 
		    //if there is jquery.bgiframe plugin, use it to fix ie6 select/z-index bug.
		    //search "z-index ie6 select bug" for more infomation
		    if ($.fn.bgiframe)
		    	optionsContainer.get(parentclass).bgiframe();
		 }
       $("body").click(function()
    	{
        	$(".comboboxDropdown").hide();
    	});
        $(this).each(function(i) {
 
            var $$ = this;
            var textBox = $($$);
            var textboxwidth=parseInt(textBox.parent().css('width'));
            textBox.css("width",textboxwidth-25+"px");

            //turn off browser auto complete feature for textbox
            //keydown to process Up,Down,Enter,Tab,Esc
            //keyup to see if text changed
            textBox.attr('autocomplete', 'off').focus(function() { /*this.select(); show("");*/ }).blur(blur).keydown(keydown).keyup(keyup);
            textBox.mouseup(function(e){
            	e.preventDefault();
             });
            var container = textBox.wrap('<div class="combobox" />').parent();

            var additionalHeight = $.browser.msie ? 5 : 3;
            
            var button = $('<img class="button" src="' + config.imageUrl + '" />').insertAfter(textBox).click(function(e) {
            	
            	if($(this).parent().next().next().css("display") == 'none')
            	{
            		//防止多个一起展开
            		$(".comboboxDropdown").hide();
            		showing=false;
            	}
            	else
            	{
            		showing=true;
            	}
            	if(!showing)
            	{
            		show("");
            		e.stopPropagation();    //  阻止事件冒泡
            	}
            	else
            	{
            		hide();
            	}
             });
            //textBox.width(textBox.width() - button.width());
              
            //keep the original value of textbox so we can recove it if use presses esc
            var oriValue;
            function show(filter) {
                if (hideTimer) {
                    window.clearTimeout(hideTimer);
                    hideTimer = 0;
                  }
                oriValue = textBox.val();
               
                if(oriValue)
                {
                	oriValue=oriValue.replace("'","");
                }
                hide();

                //generate the options (li inside ul)
                //var html = '<li param="">全部</li>';
                var html="";
                var jsonData = eval(suggestions);
                var num=0;
                $.each(jsonData, function(index, objVal)
                  {
                	
                	 var v=objVal["name"];
                	 var bs=objVal["bs"];
                	 if ((!filter) || (filter && (v.toLowerCase().indexOf(filter.toLowerCase()) >= 0 || bs.toLowerCase().indexOf(filter.toLowerCase()) >= 0))) {
                		 if(num>9)
                		{
                			 return false;
                		}
                		 html += '<li param="'+objVal["id"]+'">' + objVal["name"] + '</li>';
                		 num++;
                     }
                	
                	
                  });
                /*for (var k in suggestions) {
                    var v = suggestions[k];
                   
                }*/
                //alert(textBox.offset().left);
                //position and size of the options UI
                if(position_loc=="")
                  {
                	var loc = { left: 0, top: textBox.height() + 1, width: textBox.width() + button.width()+8}
                }else{
//                	var positionarray=position_loc.split(",");
//                	var loc = {left:parseInt(positionarray[0]),top:parseInt(positionarray[1]), width:textBox.width() + button.width()};
                	var loc = {left:0,top:textBox.height() + 1, width:position_loc};
                  }
                optionsContainer.get(parentclass).html(html).css(loc).css("display","block");
                
                //decide which option is currently selected
                selIndex = 0;
                var found = false;
                var options = optionsContainer.get(parentclass).children('li').each(function(i) {
                    if (found) return;
                    if ($(this).text().toLowerCase() == oriValue.toLowerCase()) {
                        $(this).addClass('selected');
                        selIndex = i;
                        found = true;
                    }
                });
             
                //if there is no items matched, hide the empty select list, so user can show options with down key
                if (!options.size()) {
                    hide();
                    return;
                }
                if (!found)
                    options.eq(0).addClass('selected');

                //mouse hover to change the highlight option, clicking to select it
                options.click(function() {
                		functionname=funname ? funname : "";
                		textBox.val($(this).text());
                		$("#"+valid).val($(this).attr("param"));
                		$("#"+parentclass+" .comboboxDropdown").hide();
                		
                		if(functionname)
                    	{
                			var obj=$(this);
                    		eval(functionname);
                    	}
                }).hover(function() {
                    options.each(function() {
                        $(this).removeClass('selected');
                       });
                    $(this).addClass('selected');
                    selIndex = options.index(this);
                });
               
                if (!filter){
                //showing all the options
                	optionsContainer.get(parentclass).slideDown();
                }else{
                //showing filtered options, happens when textbox.value changed, should not flick
                	optionsContainer.get(parentclass).show();
                }
               
            	  showing = true;
            	  
            }
            //alert(1);
            var selIndex;
            function keydown(evt) {
             	 $("#"+valid).val("");
                switch (evt.keyCode) {
                    case keys.esc:
                        hide();
                        textBox.val(oriValue);
                        //fixes esc twice clears the textbox value bug in ie
                        evt.preventDefault();
                        return;
                    case keys.enter:
                        choose();
                        //don't submit the form
                        evt.preventDefault();
                        return;
                    case keys.tab:
                        choose();
                        return;
                    case keys.up:
                        goup();
                        return;
                    case keys.down:
                        godown();
                        return;
                }
            }

            var oldVal = '';
            function keyup(evt) {
            	  
                var v = $(this).val();
                if (v != oldVal) {
                    show(oldVal = v);
                  }
                	
            }

            function godown() {
                if (showing) {
                    var options = optionsContainer.get(parentclass).children('li');
                    var n = options.size();
                    if (!n)
                        return;
                    selIndex++;

                    if (selIndex > n - 1)
                        selIndex = 0;

                    var v = options.eq(selIndex);
                    if (v.size()) {
                        options.each(function() { $(this).removeClass('selected') });
                        v.addClass('selected');
                    }
                } else {
                    show('');
                }
            }
            function goup() {
                if (showing) {
                    var options = optionsContainer.get(parentclass).children('li');
                    var n = options.size();
                    if (!n)
                        return;
                    selIndex--;

                    if (selIndex < 0)
                        selIndex = n - 1;

                    var v = options.eq(selIndex);
                    if (v.size()) {
                        options.each(function() { $(this).removeClass('selected') });
                        v.addClass('selected');
                    }
                } else {
                    show('');
                }
            }
            function choose() {
                if (showing) {
                    var v = $('li', optionsContainer.get(parentclass)).eq(selIndex);
                    if (v.size()) {
                        textBox.val(v.text());
                        $("#"+valid).val(v.attr("param"));
                        oldVal = v.text();
                        hide();
                    }
                  functionname=funname ? funname : "";
                  if(functionname)
                	{
                	  	var obj=v;
                		eval(functionname);
                	}
                }
            }

            function hide() {
                if (showing) {
                	  //optionsContainer.get(parentclass).hide().children('li').each(function() { $(this).remove(); });
                	optionsContainer.get(parentclass).hide();
                	showing = false;
                }
            }

            function blur() {
            		if(!showing)
            		{
		            	var v = $('li', optionsContainer.get(parentclass)).eq(selIndex);
		            	if(v.size())
		            	{
		            		if(v.attr("param"))
		            		{
		            			$("#"+valid).val(v.attr("param"));
		            		}
		            	}
		            	if (!hideTimer) {
	                    hideTimer = window.setTimeout(hide, 300);
	                	}
            		}
            		if(clear==true)
            		{
	            		if(!$("#"+valid).val())
	            		{
	            			textBox.val("");
	            		}
            		}
            }
           
        });
    };
})(jQuery);
function Dictionary(){
  	 this.data = new Array();
  	 
  	 this.put = function(key,value){
  	  this.data[key] = value;
  	 };
  	
  	 this.get = function(key){
  	  return this.data[key];
  	 };
  	
  	 this.remove = function(key){
  	  this.data[key] = null;
  	 };
  	 
  	 this.isEmpty = function(){
  	  return this.data.length == 0;
  	 };
  	
  	 this.size = function(){
  	  return this.data.length;
  	 };
  }