// JavaScript Document
function layoutTypeChange(selectedValue)
	{
		if(selectedValue === 'page')
		{
			$("#typeIsPage").fadeIn('fast');
			$("#typeIsNotPage").fadeOut(0);
			$("#addBlocks").fadeIn('fast');
		}
		else
		{
			$("#typeIsPage").fadeOut(0);
			$("#typeIsNotPage").fadeIn('fast');
			$("#addBlocks").fadeOut('fast');
		}
	}

$(document).ready(function(e) {
   
	$("#controller").on('keyup',function(){
		if($(this).val() === "")
		{
			$("#pageViewField").fadeIn('fast');
			$('#hasController').fadeOut('fast');
		}
		else
		{
			$("#pageViewField").fadeOut(0);
			$('#hasController').fadeIn('fast');
		}
   });
   
	
	$("#delete").on('change',function(){
		if($(this).is(":checked") )
		{
			$("#delete_message").fadeIn('fast');
			/*
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
			*/
		}
		else
		{
			$("#delete_message").fadeOut('fast');
		}	
	});
	
});