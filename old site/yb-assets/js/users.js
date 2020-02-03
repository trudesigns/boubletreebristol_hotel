// JavaScript Document

function resetPassword(id,name){
	 
	 $("#dialog_box").html("<p>This action will reset "+name+"'s password and generate an e-mail to the address associated with that user.</p><p>Are you sure you want to reset this password?</p>");
	 
	 $("#dialog_box").dialog({title:'Reset Password',
			autoOpen: true,
			width: 450, height:280,
			resizable : false,
			buttons: [ 
				{
						text:"Cancel",
						click: function(){ $(this).dialog('close'); }
				},
				{ 
						text: "Reset Password",
						click:   function(){ 
								  
								$.post(XHR_PATH+"resetPassword/", {user:id}, function(data){	
									if($.trim(data) == "done"){
										$("#dialog_box").dialog('close');
										alert(name +"'s password has been reset!");
									}else{
										$("#dialog_box").dialog('close');
										alert("ERROR! " + $.trim(data) );
									}
								});
						}

				}
			]				
	});
}

function updateRole(user_id, role_id){
	
	var has_role = ( $("#role_" + role_id + "_user_" + user_id).is(':checked')  ) ? "yes" : "no"; // set to true or false;
	
	$.post(XHR_PATH+"setRole/", {user:user_id,role:role_id,has_role:has_role }, function(data){
		
		if($.trim(data) == "done"){
		
			var chkbx_id = "role_"+role_id +"_user_"+user_id;
			$("label[for='"+chkbx_id+"']").animate({color: 'green'},'fast');
			
			setTimeout( function(){
			  $("label[for='"+chkbx_id+"']").animate({color: '#666'},'slow');
			}, 1500);
			
		} else {
			alert("Error: "+$.trim(data) );
		}
		
	});	
	
}

function deleteUser(id,name){
	 
	 $("#dialog_box").html("<p>Are you sure you want to permenetly delete " + name + "'s user account?</p><p>This action can not be undone!</p><p>Note: to temporarily disable a user's access, you can simply remove their \"Log In\" and \"Admin\" roles.</p>");
	 
	 $("#dialog_box").dialog({title:'Delete User Account',
			autoOpen: true,
			width: 500, height:330,
			resizable : false,
			buttons: [
					{  	
						text:"Cancel",
						click: function(){ $(this).dialog('close'); }
					},
					{ 
						text: "Delete User",
						click:   function(){ 
									  
									$.post(XHR_PATH+'deleteUser/',{user:id}, function(data){
										if( $.trim(data) == "done"){
											window.location.reload(true);
										}else{
											$("#dialog_box").dialog('close');
											alert("Error! "+$.trim(data) );
										}

									});

								 } 
					}
				]				
	});
}

// add mouseover effect for icons
/*
$(function(){
		$('.ui-state-default').hover(
			function(){ $(this).addClass('ui-state-hover'); }, 
			function(){ $(this).removeClass('ui-state-hover'); }
		);
});
*/

$(document).ready(function() { 

	//define this pop-up box for use throughout the app
	$("#dialog_box").dialog({
		autoOpen: false,
		modal: true
	}).css({'text-align':'left', 'margin':'0 auto'});
	

}); // End document.ready()