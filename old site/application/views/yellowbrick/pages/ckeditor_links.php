<html>
<head>
<title>Link to a Site page</title>
<link type="text/css" href="/yb-assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen, projection" />
<link type="text/css" href="/yb-assets/plugins/chosen/chosen.min.css" rel="stylesheet" media="screen, projection" />
<link type="text/css" href="/yb-assets/css/chosen-bootstrap.css" rel="stylesheet" media="screen, projection" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="/yb-assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/yb-assets/plugins/jquery.cookie.js"></script>
<script type="text/javascript" src="/yb-assets/js/functions.js"></script>
<script type="text/javascript" src="/yb-assets/plugins/chosen/chosen.jquery.min.js"></script>


<script  language="JavaScript">
var XHR_PATH = "/admin/request/";
 var sessiontoken = $.cookie('ybr_loggedin');
window.resizeTo(450,475) 

$(document).keyup(function(e) {
  if (e.keyCode == 27) { window.close(); }   // esc
}); 

$(document).ready(function(){
   
   // alert("sadasdasdads");
                $.ajax({
                        type:"post",
                        url:XHR_PATH + 'pages',
                        data:{ybr_loggedin: sessiontoken},
                        dataType:"json",
                        success:function(data){
                            //alert(data);
                             //alert("BLAJ");
                             var html = "";
                            $.each(data, function (index, v) {
                                //  console.log(v);
                           
                                html += "<option value='"+v.page.id+"'>"+v.page.label+"</option>";
                                if (typeof v.page.children !== undefined) {
                                    var res =  listSubMenus(v.page.children,1,null,null);
                                   // console.log(res);
                                    if(res !== null){
                                        html += res.phtml;
                                    }
                                }
                            });
                            $('#select_page').html(html).chosen({max_selected_options: 1,width:'90%'});
                            
                        }
                    });
                    $('#ckeditor_links').on('click','#select',function(event){
                        var v = $('#select_page').val();
                        //console.log(v);
                        $.ajax({
                            type:"post",
                            url:XHR_PATH + 'buildlink',
                            data:{ybr_loggedin: sessiontoken,page_id:v},
                            dataType:"json",
                            success:function(data){
                              //console.log(window.location.origin+data);
                             //   console.log(data);
                              var dialog = window.opener.CKEDITOR.dialog.getCurrent();
                                dialog.setValueOf('info','url', data);  // Populates the URL field in the Links dialogue.
                                dialog.setValueOf('info','protocol','');  // This sets the Link's Protocol to Other which loads the file from the same folder the link is on
                             window.close(); // closes the popup window
                            }
                        });
                        
                        
                        
                        event.preventDefault();
                    });
    
    
	
//	$.getJSON( XHR_PATH+'allPages/',function(site_map){	
//		$('#parent_id').html('<select disabled>Loading...</select>');
//	
//		var html="", thisSlug="", pages=site_map, mydelimiter = "x", f = function myfunction(params)
//		{
//		
//			if( typeof(params) != "object")
//			{
//				params = {mydelimiter:"", previousURL:"/"};
//			}
//			
//			$.each(pages,function(index,pagedata) 
//			{			
//				var thisURL = params.previousURL + pagedata.slug+"/";
//				html+= '\n\t<option value="'+ thisURL +'">'+ params.mydelimiter +' '+ pagedata.label +'</option>';		
//				
//				if( pagedata.children > 0)
//				{
//					pages = pagedata.child_pages;
//	
//					html = myfunction({mydelimiter: params.mydelimiter +" -", previousURL: thisURL}); // run this again, recursively 
//				}				
//			});
//			return html;		
//		};
//		
//		$('#select_page')
//		.html( f )
//		.on('change',function(){
//			var dialog = window.opener.CKEDITOR.dialog.getCurrent();
//	        dialog.setValueOf('info','url', $(this).val() );  // Populates the URL field in the Links dialogue.
//	        dialog.setValueOf('info','protocol','');  // This sets the Link's Protocol to Other which loads the file from the same folder the link is on
//	        window.close(); // closes the popup window
//		})
//		.select2();
//	});

});
</script>
<style>
.row{padding-bottom:20px;}
</style>
</head>

<body>
    <main class="container" id="ckeditor_links">
        <p>Select the internal page you would like to link to:</p>
        <div class="row">
        <div class="col-xs-12">
            <select id="select_page" style="width: 100%"><option value="">Loading...</option></select>
        </div>
        </div>
        <div class="row">
        <div class="col-xs-12">
            <a id="cancel" class="btn btn-default" href="Javascript:window.close()">cancel</a>
            <a id="select" class="btn btn-primary" href="#">Select</a>
        </div>
        </div>
    </main>

</body>
</html>