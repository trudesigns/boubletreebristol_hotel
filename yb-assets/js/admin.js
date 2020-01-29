/**
HELPER Functions for the menus section
*/

function menuRemove(ob)
{
    alert(ob);
}


/*********END HELPERS FOR MENUS section ******************/


function getYBmsg()
{
     $.ajax({
            type:"post",
            url:XHR_PATH + 'getYBmsg',
            data:{ybr_loggedin: sessiontoken},
            dataType:"json",
            success:function(data){
                if($.trim(data) != "") {
                        //console.log('found a status message!');
                        $("#yb-status-msg").html(data)
                        $("#yb-status-msg-wrap").fadeIn();
                }
            }
        });
//    $.get(XHR_PATH +'getYBmsg',{ybr_loggedin:sessiontoken}, function(data){
//		   if($.trim(data) != "")
//		   {
//				//console.log('found a status message!');
//				$("#yb-status-msg").html(data)
//				$("#yb-status-msg-wrap").fadeIn();
//		   }
//		   
//		});
}

// set a YB message from the client side without setting the $_SESSION value
// optional parameter to to make the message go away on its own (milliseconds)
function setLocalYBmsg(message,timeToExpire)
{
	$("#yb-status-msg").html(message)
	$("#yb-status-msg-wrap").fadeIn();

	if(!timeToExpire || timeToExpire === "")
	{
		return false;
	}

	setTimeout( function(){ $("#yb-status-msg-wrap").fadeOut(); },timeToExpire);
}


function clearYBmsg() {
	
	$.get(XHR_PATH +'clearYBmsg', function(data){
		 if($.trim(data) == "done")
		   {
				$("#yb-status-msg-wrap").fadeOut();
				
		   } else {
			   
				console.log('error clearing session var');   
		   }
	});
}

// Prompt user to re-enter their password to log back in after inactivity
function reenterPassword()
{
	uiPrompt({	title: "Session Timed Out",
				message: "Your session has timed out and could not be extended.  Please enter your re-password to continue.",
				input: "password",
				placeholder: "password",
				callback: function(value){
					$.post(BASE_PATH+'user/signin',{username: $("#user-username").val(), password: value}, function(data){
						if($.trim(data) != "done")
						{
							alert("Invalid Password");
							reenterPassword();
						}
						else
						{
							extendSession();	
						}
					});
				}
	});
}


// on every page load, start a 20 minute timer to ping the server and keep the user logged in.
//var extendSessionTimer;

function extendSession()
{
//    var delay = /*20*60* */100;
//   extendSessionTimer = setTimeout(function(){
//        $.ajax({
//            type:"post",
//            url:XHR_PUBLIC_PATH + 'extendSession',
//            data:{ybr_token: sessiontoken},
//            dataType:"json",
//            success:function(data){
//                if(data){
//                    reenterPassword();
//                    clearTimeout(extendSessionTimer);
//                } else {
//                    extendSession(); // do this again later
//                }
//            }
//        });
//   },delay);
  
    
//	extendSessionTimer = setTimeout(function()
//	{
//		$.get(XHR_PUBLIC_PATH +'extendSession/'+{ybr_loggedin:sessiontoken}, function(data){
//		   if($.trim(data) != "success")
//		   {
//				reenterPassword();
//				clearTimeout(extendSessionTimer);
//		   }
//		   else
//		   {
//				extendSession(); // do this again later
//		   } 
//		});
//	}, 20*60*1000);
}

function selectPage(callback)
{
	$('body').append('<div id="selectPageDialog"><select style="width: 100%" id="selectPageDropdown"><option>Loading...</option></select></div>');
	//console.log("SESSIONTOKEN LOGGEDIN: "+sessiontoken);	
	$.post( XHR_PATH+'allPages/',{ybr_loggedin:sessiontoken},function(pages){	
		
		var i=0, myResult = new Array,
		optionsHTML="", 
		f = function myfunction(params)
		{
			if( typeof(params) != "object")
			{
				params = {mydelimiter:"", previousURL:"/"};
			}
		
			$.each(pages,function(index,pagedata) 
			{					
				var thisURL= params.previousURL + pagedata.slug+"/";
				
				myResult[i] = new Array; 
				myResult[i]['url'] = thisURL;
				myResult[i]['id'] = pagedata.id;
				myResult[i]['label'] = pagedata.label;
				myResult[i]['slug'] = pagedata.slug;			
							
				optionsHTML+= '\n\t<option value="'+ i +'">'+ params.mydelimiter +' '+ pagedata.label +'</option>';		
				i++;
				
				if( pagedata.children > 0)
				{
					pages = pagedata.child_pages;
					options = myfunction({mydelimiter: params.mydelimiter +" -", previousURL: thisURL}); // run this again, recursively 
				}				
			});
			return optionsHTML;		
		};
		
		$("#selectPageDialog").dialog({
			title: "Select page",
			width: 450,
			height: 'auto',
			modal: true,
			resizable: false,
			buttons: [ 
				{
					text: "Cancel",
					click: function(){ $(this).dialog('close'); }
				},
				{ 
					text: "Select >",
					click: function (){				
							var selectedIndex = $("#selectPageDropdown").val();
							
							if( typeof(callback) === "function")
							{
								callback(myResult[selectedIndex]);
								$(this).dialog('close');
							}
							else
							{
								alert("no callback defined");
							}
					}			
				}
			]
		});
		
		$('#selectPageDropdown')
		.html( f )
		.select2();
	});
	
	
}

function triggerTooltip() {
	$(".tooltip").tooltip();
}

//function triggerSelect2() {
//	$(".yb-select").select2({
//		width: 'resolve' //First attempts to "copy" than falls back on "element".
//	});	
//}

$(document).ready(function() {
	
	// see if a status message was set
	//getYBmsg();
	
//	$('.sf-menu').superfish({
//		delay:		1000,	// one second delay on mouseout
//		animation:	{opacity:'show'},	// an object equivalent to first parameter of jQuery’s .animate() method. Used to animate the submenu open
//		animationOut: {opacity:'hide'},	// an object equivalent to first parameter of jQuery’s .animate() method Used to animate the submenu closed
//		speed:		'fast',	// faster animation speed
//		cssArrows:	false	// disable generation of arrow mark-up
//	});
	
//	$("#yb-pages-menu li").each(function(){
//		var link = $("a",this).first();
//		if( link.attr('data-active') == 0 )
//		{
//			link.html( link.html() +'<span style="display: inline-block" class="tooltip ui-icon ui-icon-cancel" title="Page is set as inactive and returns a 404 \'Page not found\' error"></span>');	
//		}
//		if( link.attr('data-role') != '')
//		{
//			link.html( link.html() +'<span style="display: inline-block" class="tooltip ui-icon ui-icon-key" title="Page requires log in"></span>' );
//		}
//		
//		if( link.attr('data-startdate') != '0000-00-00 00:00:00' || link.attr('data-enddate') != '0000-00-00 00:00:00' )
//		{
//			var start = new Date( link.attr('data-startdate').replace(/-/g,"/")),
//				end = new Date( link.attr('data-enddate').replace(/-/g,"/")),
//				now = new Date(),
//				message = "Page has specific time parameters for availability";
//			
//			if( start > now)
//			{
//				message = "Page will go live on "+ dateFormat("F j, Y",link.attr('data-startdate'));
//			}
//			else if (end < now)
//			{
//				message = "Page has expired on "+ dateFormat("F j, Y",link.attr('data-enddate'));		
//			}
//			else if (end > now)
//			{
//				message = "Page will expire on "+ dateFormat("F j, Y",link.attr('data-enddate'));	
//			}	
//			
//			link.html( link.html() +'<span style="display: inline-block" class="tooltip ui-icon ui-icon-clock" title="'+ message +'"></span>' );		
//		}
//	});
//	
//	
//	triggerTooltip();
//	
//	triggerSelect2();
	
	//extendSession();
	
	
	
}); // end document.ready()
	
