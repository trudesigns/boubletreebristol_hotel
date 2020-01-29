// JavaScript Document

var param_template_wysiwyg = "<textarea name=\"content\" id=\"content\">{content}</textarea> \n"
   							+"<script type=\"text/javascript\"> \n"
							+"$('#content').ckeditor(function() \n { CKFinder.setupCKEditor(this,'/ckfinder/') }, \n"
							+"	{ allowedContent: true, \n"
							+"	  resize_enabled : true, \n"
							+"	  height : '350px', \n"
							+"	  toolbar : 'CMSdefault' //these are set in CKeditor/config.js \n"					
							+"	} \n); \n"
							+"</script>";

var param_template_textfield = "<input type=\"text\" name=\"content\" id=\"content\" value=\"{content}\" />";

var param_template_textarea = "<textarea name=\"content\" id=\"content\" rows=\"5\" style=\"width: 500px\">{content}</textarea>";

var param_template_filemanager = "<input type=\"text\" name=\"content\" id=\"content\" value=\"{content}\" /> \n"
							   + "<button id=\"content_launchfilemanager\">Browse...</button>\n"
							   + "<script type=\"text/javascript\"> \n"
							   + "$('#content_launchfilemanager').click(function() { \n"
							   + "		CKFinder.popup( { basePath : '/ckfinder/',  \n"
							   + "			resourceType: 'Images', // (only show Images folder) \n"
							   + "			rememberLastFolder: false, \n"
							   + "			startupPath : 'Images:/',\n"
							   + "			startupFolderExpanded: true, \n"
							   + "			selectActionFunction : function(fileURL){  $(\"#content\").val(fileURL); }\n"
							   + " 		});\n"
							   + "		return false;\n"
							   + "});\n"
							   + "</script>";

// JSON object
var param_template_multifield = "[\n"
								+" { \"name\":	{\"label\": \"Name\" , \n"
  		 						+"		\"input\": \"<input type=\\\"text\\\" name=\\\"name\\\" value=\\\"{content}\\\" />\" \n"
  								+"		}\n " 
								+" }, \n"
								+" { \"age\":	{\"label\": \"Age\" , \n"
  		 						+"		\"input\": \"<input type=\\\"text\\\" name=\\\"age\\\" value=\\\"{content}\\\" />\" \n"
  								+"		}\n " 
								+" }, \n"
								+" { \"bod\":	{\"label\": \"On Board?\" , \n"
  		 						+"		\"input\": \"<input type=\\\"checkbox\\\" name=\\\"bod\\\" value=\\\"1\\\" selected-data=\\\"{content}\\\" />\" \n"
  								+" 		}\n " 
								+" } \n"
								+"]";

var param_template_customform = "yellowbrick/widgets/customblock.php";
var param_template_module = "modules/blockbuilder/blockbuilder.php";
			
$(document).ready(function(e) {
	
	$("#input_type").change(function(){
		$("#original_params").val( $("#input_parameters").val() );
		var template = eval("param_template_"+$(this).val());
		$("#input_parameters").val( template  ); 
	});
	
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
	
	   
});	// end document.ready()				
