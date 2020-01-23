// JavaScript Document
function showIncludeCode(){
$("#menu-setup-popup").html("&lt;?php<br /> &nbsp; include $_SERVER['DOCUMENT_ROOT'].'/assets/menus/menubuilder_menu"
						+$("#thisMenu").val()+".html'; <br/>?&gt; <br /><br /> &nbsp; OR <br /><br />"
						+"&lt;?php<br /> &nbsp; $menu = new Model_Menu;<br /> &nbsp; echo $menu->loadMenu("+$("#thisMenu").val()+",$_SERVER['REQUEST_URI']);<br />?&gt;");

$("#menu-setup-popup").dialog({title:'MENU PHP CODE', 
									   	 autoOpen: true,
									     width:665, height:300,
									     buttons: { "Close": function(){ $(this).dialog('close');}
					 							  }				
							    });	
}

$(document).ready(function(e) {
   

	$("#delete").on('change',function(){
		if($(this).is(":checked") )
		{
			uiConfirm({ message: "Are you sure you want to permanently delete this item?<br><br>This action can not be undone",
						title: "Confirm Deletion",
						callback: function(response){
							if(!response)
							{
								$("#delete").attr('checked',false);
							}
							else
							{
								$("#delete_message").fadeIn('fast');
							}
							return response;		
						}
			});
		}
		else
		{
			$("#delete_message").fadeOut('fast');
		}	
	});
	
});