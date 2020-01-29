// JavaScript Document
$(document).ready(function(){
	
	$("#resetPasswordSumbit").click( function(){
		var email = $("#resetEmail").val();
		if(email == ""){ alert("Please enter your e-mail address to continue"); return; }
	
 		$(this).fadeOut('fast'); // hide submit button		
		
		$.post("/request/resetPassword",{email:email, token:sessiontoken}, function(data){
			if($.trim(data) != "success"){
				$("#resetPasswordSumbit").fadeIn('fast'); // display the submit button again
				alert($.trim(data));
			}else{
				$("#resetPasswordForm").html("<p class='form_success_message'>Your password has been reset and e-mailed to <strong>"+email +"</strong>.</p>");
			}
		});
	});
	
}); // end document.ready()