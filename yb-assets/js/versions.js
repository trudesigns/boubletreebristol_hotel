// JavaScript Document
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
	
}); // end document.ready()