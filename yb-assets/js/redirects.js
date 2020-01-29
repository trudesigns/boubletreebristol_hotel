// JavaScript Document

var can_submit = true;

function checkAlias()
{
	var entered_value = $("#alias").val();
	if(entered_value.length > 1)
	{
		$.getJSON(XHR_PATH +'checkAlias',{alias: entered_value, alias_id: $("#selected_redirect").val() },function(data){
				
				if( !data.existing_page && !data.existing_alias)
				{
					$("#submit").fadeIn('fast');
					$("#alias_msg").html('');
					can_submit = true;
					return true;
				}
				
				$("#submit").fadeOut('fast');
				can_submit = false;
				
				if( data.existing_page )
				{
					var alias_msg = "A live page already exists with this URL. To make the page available again deactivate this alias.";
				}
				else if (data.existing_alias)
				{
					var alias_msg = "An alias already exists with this value. Activating this alias will make the page unavailable.";
				}
				
				$("#alias_msg").html(alias_msg);
				can_submit = false;
				
		});
	}
	else
	{
		return false;
	}
}

function validate()
{
//	uiAlert('validation goes here');
}

function postDelete() {
		
		
}

	

$(document).ready(function(e) {
 
// reset hidden delete var on load to avoid accidental deletions  
$("#delete").val('');
  
    
	$('#delete-btn').click(function() {
		
		uiConfirm({ message: "Are you sure you want to permanently delete this item?<br><br>This action can not be undone",
						title: "Confirm Deletion",
						callback: function(response){
							if(!response)
							{
								$("#delete").val('');
							}
							else
							{
								$("#delete").val('confirm_delete');
								$("#redirectsform").submit();
							}
							return response;		
						}
			});
		
		
	});
	
	
	checkAlias(); // check the "alias" field on load to make sure its kosher
	
	$("#alias").blur( function(){ checkAlias() }); // check it any time the field is changed
	
	$("#redirectsform").submit(function(){ validate(); });
	
	/*
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
	*/

}); // End document.ready()