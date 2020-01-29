$(function(){
    $.validator.addMethod('date',function (value, element) {
            if (this.optional(element)) {
                return true;
            }
            var ok = true;
            try {
                 $.datepicker.parseDate('mm/dd/yy', value);
            }
            catch (err) {
                //alert("dkdkd");
                ok = false;
            }
            return ok;
    });
    
    $('#filter-status select').on({
        change:function(evt,params){
            var o = $(this);
            var dt = $('table').dataTable();
           //console.log(dt);
           // if(o.val() !== ""){
            var sel = parseInt(o.val(),10);
           // console.log(sel);
            if(sel >= 0){
                 //console.log(typeof sel);
                    dt.fnFilter(sel,0);
            } else {
                  dt.fnFilter(" ",0);
            }

        }
    });
    $('#filter-categories select').on({
        change:function(evt,params){
            var o = $(this);
           // console.log(o.val());
            var sel = parseInt(o.val(),10);
            // console.log(typeof sel);
            if(sel == 0){
                  //$.fn.dataTable.ext.search = [];
                  dt.fnFilter(" ",1);
            } else {
                dt.fnFilter(sel,1);

            }

        }
    });
    
    
    //NEWS
    if($('article#news').length>0){

              $('#news').on('focusout','#title',function(){
                 $('#update-slug').removeClass('disabled');
              });

              $('#news').on('click','#update-slug',function(event){
                  var o = $(this);
                  var title = $('#title').val();
                    $.ajax({
                              type:"post",
                              url:XHR_PATH + 'generateSlug',
                              data:{ybr_loggedin: sessiontoken,string:title},
                            //  dataType:"json",
                              success:function(data){
                                 $('#slug').val(data); 
                                 $('.update-slug').remove();
                              }
                          });
                  event.preventDefault();
              });

              $('#check-slug').on({
                  click:function(event){

                      var o = $(this);
                      var slug  = $('#slug').val();
                      var editid = $('#editid').val();
                       $.ajax({
                              type:"post",
                              url: '/admin/news/newsSlug',
                              data:{ybr_loggedin: sessiontoken,slug:slug,editid:editid},
                              dataType:"json",
                              success:function(data){
                                 //    alert(data);
                                 //console.debug(data);
                                  if(data){
                                      o.parents('.form-group').removeClass('has-error').addClass('has-success');
                                  } else {
                                      o.parents('.form-group').removeClass('has-success').addClass('has-error');
                                  }

                              }
                          });
                      event.preventDefault();
                  }

              });   

              $('#news').on('load','input.datepicker.error',function(){
                          var o = $(this);
                          o.parent('.form-group').addClass('has-error');
              });

              $('#news').on('load','input.datepicker',function(){
                          var o = $(this);
                          o.parent('.form-group').addClass('has-success');
              });

              $("#news_form").validate({
                 // debug:true,
                      ignore: '.ignore',
                      onkeyup:false,//every time the data gets changed we re-validate
                      onfocusout: function(element) {
                           $(element).valid();
                      },
                      success: function (element, baseEle) {
                          //console.log($(baseEle).attr('class'));
                              $(baseEle)
                                  .closest('.form-group')
                                  .find('.message')
                                  .text(' * ')
                                  .parent()
                                  .parent()
                                  .removeClass('has-error')
                                  .addClass('has-success');
                      },
                      errorPlacement: function (error, element) {
                         // console.log($(element).attr('class'));
                         //console.debug(error);
                          if (error) {
                              element.closest('.form-group')
                              .find('.message')
                              .text(' * ' + error.text())
                              .parent()
                              .parent()
                              .addClass('has-error')
                              .removeClass('has-success');
                          }
                      },
                      rules: {
                          title: "required",
                          sdate:{
                              required:true,
                              date:true
                          },
                          content:'required',
                          category:'required',
//                          slug:{
//                              required:true,
//                              remote:{
//                                       // async:false,
//                                      type:"post",
//                                      url: '/admin/news/newsSlug',
//                                      //contentType: "application/json; charset=utf-8",
//                                      dataType: "json",
//                                      //success:function(d){console.log(d);console.log(typeof d);return d;},
//                                      data:{
//                                         ybr_loggedin: sessiontoken,
//                                         slug:function(){
//                                             return $('#slug').val();
//                                         },
//                                         editid:function(){
//                                             return $('#editid').val();
//                                         }
//                                     }
//
//                              }
//
//                          },

                      },
                      messages: {
                          label: {
                             required: 'The Title field is required.'
                          },
//                          slug:{
//                              required:'The Slug field is required.'
//                              ,remote:'The Slug you provided is being used'
//                          },
                          content:{
                             required:'The Content field is required.'
                          },
                          sdate:{
                              required:"The Date field is required."
                              ,date:'The Date field is incorrect.'  
                          },
                          category:{
                             required:"The Category field is required."
                          }

                      },
//                      submitHandler:function(form){
//                          console.log(form);
//                         // $('#news_form').submit();
//                      }
                     
              });
              
              var dt =$('.datatable-news').dataTable({
                              "lengthMenu":[[10,25,50,-1],[10,25,50,"All"]]
                            // ,"bStateSave":true
                             ,"aoColumnDefs": [
                                                      { "asSorting": ['desc'] ,"aTargets": [0] , "bVisible":false},
                                                      { "aTargets": [1] , "bVisible":false},
                                                      { "aTargets":[ 2 ],"aaSorting": ['desc'] },
                                                      { "asSorting": false, "aTargets": [ -1 ,-3] }
                                                    ]
                  });        
    }
    
    
    //TIER PAGES
    if($('article#editor').length>0){

        //SQUARES
        $('article#editor').on('click','#save-square',function(event){

            var totalItems = $('#sortable').find('.link_field').not('.clone_field').length;
            var errors  ="";
            var title = $('#title').val();
            if(title === ""){
                var msg ="The Title field is required.";
                errors += msg+"<br />";
                $('#title').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else {
                $('#title').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }

            var link = $('#link').val();
            if(link === ""){
                var msg = "The Link field is required.";
                errors += msg+"<br />";
                $('#link').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
                
            } else {
                 $('#link').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
             var fileURL  =$('#preview-image-field').val();
             if(fileURL === ""){
                var msg = "The Image field is required.";
                errors += msg+"<br />";
                $('#preview-image-field').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else {
                $('#preview-image-field').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
             
             if(errors !== ""){
                uiAlert(errors);
                return false;  
              } else {
                   $('.form-group').removeClass('has-success').removeClass('has-error');
              }
             
          // alert(link);
            var clone = $('.clone').clone();
            $(clone).prependTo('#sortable').removeClass('clone hide').addClass('newly-added');
            $('.newly-added').prop("id","item_"+totalItems);
            $('.newly-added').prop("id","item_"+totalItems).find('img').prop('src',fileURL);
            //$('.newly-added').find('.title_field').val(title).attr('name','title[]');
            $('.newly-added').find('.title_field').val(title).attr('name','title[]');
            $('.newly-added').find('.link_field').val(link).attr('name','link[]');
            $('.newly-added').find('.image_field').val(fileURL).attr('name','image[]');
            $('.newly-added').removeClass('newly-added').addClass('slide').find('.clone_field').removeClass('clone_field');

            $('#square-form :input, #square-form textarea').not(':hidden').val(" ");
            $('#square-form img').attr('src','');
            event.preventDefault();

        });


        //CALLOUTS
        $('article#editor').on('click','#save-callout',function(event){

            var totalItems = $('#sortable').find('.link_field').not('.clone_field').length;
            var errors  ="";
            var title = $('#title').val();
            if(title === ""){
                var msg ="The Title field is required.";
                errors += msg+"<br />";
                $('#title').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else {
                $('#title').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
            var cap = $('#caption').val();
            if(cap === ""){
                var msg= "The Caption field is required.";
                errors += msg+"<br />";
                $('#caption').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else{
                $('#caption').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
            var link = $('#link').val();
            if(link === ""){
                var msg = "The Link field is required.";
                errors += msg+"<br />";
                $('#link').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
                
            } else {
                 $('#link').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
             var fileURL  =$('#preview-image-field').val();
             if(fileURL === ""){
                var msg = "The Image field is required.";
                errors += msg+"<br />";
                $('#preview-image-field').closest('.form-group').addClass('has-error').find('.message').text("* "+msg);
            } else {
                $('#preview-image-field').closest('.form-group').addClass('has-success').find('.message').text("* ");
            }
             
             if(errors !== ""){
                uiAlert(errors);
                return false;  
              } else {
                   $('.form-group').removeClass('has-success').removeClass('has-error');
              }
             
          // alert(link);
            var clone = $('.clone').clone();
            $(clone).prependTo('#sortable').removeClass('clone hide').addClass('newly-added');
            $('.newly-added').prop("id","item_"+totalItems);
            $('.newly-added').prop("id","item_"+totalItems).find('img').prop('src',fileURL);
            //$('.newly-added').find('.title_field').val(title).attr('name','title[]');
            $('.newly-added').find('.title_field').val(title).attr('name','title[]');
            $('.newly-added').find('.caption_field').val(cap).attr('name','caption[]');
            $('.newly-added').find('.link_field').val(link).attr('name','link[]');
            $('.newly-added').find('.image_field').val(fileURL).attr('name','image[]');
            $('.newly-added').removeClass('newly-added').addClass('slide').find('.clone_field').removeClass('clone_field');

            $('#callout-form :input, #callout-form textarea').not(':hidden').val(" ");
            $('#callout-form img').attr('src','');
            event.preventDefault();

        });


        //IF you cahneg the dropdown 
        $('#editor').on('change','#block-select',function(){
             sortable("#sortable");
         });

        //if the page already that content block loaded
        sortable("#sortable");


        $('#editor').on('click','#deleteImage',function(event){

          $('#preview-image-field').val("");
          $('.publish-block').click();
          event.preventDefault();
        });

        $('#editor').on('click','.image_rotator',function(event){
               //alert("sdhsdhedhe");
               var o = $(this);
                    var startup_path = $(this).data('startup-path');
                    var spath = "Images:/";
                    if(startup_path !== undefined && startup_path !== ""){
                        spath = startup_path
                    }
    //console.log(startup_path);
    //console.log("SHSHHS");
                    CKFinder.popup( { 
                        basePath : '/ckfinder/'
                        ,resourceType: 'Images' // (only show Images folder) 
                        ,rememberLastFolder: false
                        ,startupPath : spath
                        ,startupFolderExpanded: true 
                        ,selectActionFunction : function(fileURL){  
                            o.next('#preview-image-field').val(fileURL);
                            $('#preview-image').prop('src',fileURL);
                        }
                    });
                    //event.preventDefault();
                    return false;

        });

        //SAVE SLIDE
        $('#editor').on('click','#save-slide',function(event){
            var totalPictures = $('#sortable').find('img').length;
            var fileURL  =$('#preview-image-field').val();
            if(fileURL === ""){
              uiAlert("Select an image to add");
              return false;  
            } 

            var clone = $('.clone').clone();
            $(clone).prependTo('#sortable').removeClass('clone hide').addClass('newly-added');
            $('.newly-added').prop("id","item_"+totalPictures).find('img').prop('src',fileURL);
            $('.newly-added').find('.image_field').val(fileURL).attr('name','image[]');
            $('.newly-added').removeClass('newly-added').addClass('slide').find('.clone_field').removeClass('clone_field');
            $('#saveRotator').removeClass('hide');
            $('#slide-form :input, #slide-form textarea').not(':hidden').val(" ");
            $('#slide-form img').attr('src','');
            event.preventDefault();
        });
        //SAVE GALLERY
        $('#editor').on('click','#save-gallery',function(event){
                    var totalPictures = $('#sortable').find('img').length;
                    var fileURL  =$('#preview-image-field').val();
                    var strError = "";
                    if(fileURL == ""){
                        strError += "Please pick an image";
                    }
                    var title = $('#title').val();

                    var caption = $('#caption').val();
                    var link = $('#link').val();

                    if(strError == ""){
                          var clone = $('.clone').clone();
                          $(clone).prependTo('#sortable').removeClass('clone hide').addClass('newly-added');
                          $('.newly-added').prop("id","item_"+totalPictures).find('img').prop('src',fileURL);
                          //$('.newly-added').find('.title_field').val(title).attr('name','title[]');
                          $('.newly-added').find('.title_field').val(title).attr('name','title[]');
                          $('.newly-added').find('.caption_field').val(caption).attr('name','caption[]');
                          $('.newly-added').find('.image_field').val(fileURL).attr('name','image[]');
                          $('.newly-added').find('.link_field').val(link).attr('name','link[]');
                          $('.newly-added').removeClass('newly-added').addClass('slide').find('.clone_field').removeClass('clone_field');
                          $('#saveRotator').removeClass('hide');
                          $('#slide-form :input, #slide-form textarea').not(':hidden').val(" ");
                          $('#slide-form img').attr('src','');
                    } else {
                        alert(strError);
                    }
                event.preventDefault();
            });

        $('#editor').on('click','.remove-item',function(event){
                var o =$(this);
                o.parents('li').remove();
                event.preventDefault();
        });

        $('#editor').on('click','.page_select',function(event){
                var o  = $(this);
                var work = o.parents('.link-selector');
                 $.ajax({
                     type:"post",
                     url:XHR_PATH + 'pages',
                     data:{ybr_loggedin: sessiontoken},
                     dataType:"json",
                     success:function(data){
                         var html = "";

                             $.each(data, function (index, v) {

                                 html += "<option value='"+v.page.id+"'>"+v.page.label+"</option>";
                                 if (typeof v.page.children !== undefined) {
                                     var res =  listSubMenus(v.page.children,1,null,null);
                                    // console.log(res);
                                     if(res !== null){
                                         html += res.phtml;
                                     }
                                 }
                             });

                             work.find('.select-destination').removeClass('hide').html(html);
                             o.addClass('hide');
                             work.find('.destination_control').removeClass('hide');
                             //$('#add_to_menu').html(html);
                     }
                 });
                event.preventDefault();

        });

        $('#editor').on('click','.page_accept',function(event){
           var o  = $(this);
           var work = o.parents('.link-selector');
           work.find('.page_select').removeClass('hide');
           work.find('.destination_control').addClass('hide');
           var dest =  work.find('.select-destination');
           dest.addClass('hide');

                    var v = dest.val();
                    //alert(v);
                     $.ajax({
                        type:"post",
                        url:XHR_PATH+'getUrl',
                        data:{ybr_loggedin:sessiontoken,page_id:v},
                        //dataType:"json",
                        success:function(data){
                        //    alert(data);
                           work.find('.link').val(data);
                        }
                    });

                    event.preventDefault();

        });

        $('#editor').on('click','.page_cancel',function(event){
            var o  = $(this);
            var work = o.parents('.link-selector');
            work.find('.page_select').removeClass('hide');
            work.find('.destination_control').addClass('hide');
            work.find('.select-destination').addClass('hide');
            work.find('.destination').val("");
            event.preventDefault();

        });

        $('#editor').on('click','.clear-link',function(event){
            var o  = $(this);
            var work = o.parents('.link-selector');
            work.find('.link').val("");
            event.preventDefault();
        });



    }
    
});


   function sortable(ul){
       //alert(ul);
       setTimeout(function(){
            var orderString ="";
             $( ul ).sortable({
                  placeholder: "ui-state-highlight",
                  opacity: 0.8,
                  revert: true,
                  distance: 20,
                  forcePlaceholderSize: true,
                  cursor: "move",
                   helper: 'clone'
                  
              });
        },500);
  }
  function listPagesID(menu_id, child, iteration, pid, sid){
    if (child !== null) {
        var phtml = "",shtml="";
        

        $.each(child, function (idx, c) {
            var dash = "";
            //console.log(iteration +" "+c.page.label);
            for (var i = 0; i < iteration; i++) {
                dash += " - "
            }
            if (iteration === "1") {
                dash = " - ";
            }
          
           if (c.menu.parent_id == pid) {
               
                //console.log("bbPID: "+c.page.parent_id+" bbID: "+pid );
                shtml += "<option value='" + c.menu.id+ "'>" + c.menu.label + "</option>";
            }
           

            phtml += "<option value='" + c.menu.id+ "'>" + dash + " " + c.menu.label + "</option>";
            if (typeof c.menu.children !== undefined && c.menu.children !== null) {
                var res = listPagesID(menu_id, c.menu.children, iteration + 1, pid, sid);
                //console.log(res);
                phtml += res.phtml;
                shtml += res.shtml;
              
            }
        });
        return {phtml: phtml,shtml:shtml};
    }
    return null;
}

//MDC (MAIN) Function carried over 
function listSitePages(selection){

//$.ajax({
//            url: "../api/v1/register",
//            type: "get",
//            contentType: "application/json",
//            dataType:"json"
//        }).done(function(data){
//            //console.log(data.jwt);
//           sessionStorage.setItem('jwt',data.jwt);
//        }).fail(function(data){
//            alert(data);
//        });



            $.ajax({
                type:"post",
                url: XHR_PATH+'allPages',
                data:'ybr_loggedin='+sessiontoken,
                dataType:"json"
            }).done(function(data){
                var html ="";
                //alert("smjsjhshshs");
              //  console.info(data);
                for(var p in data){
                   // console.log(data[p]);
                    if(Number(data[p].thisID) > 199){
                       // console.log(data[p].thisID+" === "+selection);
                        var selected ="";
                        if(Number(data[p].thisID) === Number(selection)){
                           selected = " selected='selected' ";  
                        }
                       
                        html += '\n\t<option value="'+ data[p].thisID +'"'+selected+'>'+ data[p].label +'</option>';
                    }
                }
                
//                var html="", pages=data, mydelimiter = "x", f = function myfunction(mydelimiter)
//		{
//			if( mydelimiter === 0) { mydelimiter = ""; }
//			
//			$.each(pages,function(index,pagedata) 
//			{			
//				//var selected = "";//( pagedata.thisID == <?=($crud_selected && $crud_selected->page_id != 0 )?$crud_selected->page->id : '-1'; ?>) ? " SELECTED" : "";
//				html+= '\n\t<option value="'+ pagedata.thisID +'"'+selected+'>'+ mydelimiter +' '+ pagedata.label +'</option>';		
//				
//				if( pagedata.children > 0)
//				{
//					pages = pagedata.child_pages;
//	
//					html = myfunction( mydelimiter +" -"); // run this again, recursively 
//				}				
//			});
//			return html;		
//		};	
                //console.log("ListSitesPages");
		//console.debug(html);
		$('#page_id').html(html).chosen();
            }).fail(function(data){
               // console.log(data);
            });

//	$.post( XHR_PATH+'allPages',{ybr_loggedin: sessiontoken},function(data){	
//		
//		var html="", pages=data, mydelimiter = "x", f = function myfunction(mydelimiter)
//		{
//			if( mydelimiter === 0) { mydelimiter = ""; }
//			
//			$.each(pages,function(index,pagedata) 
//			{			
//				//var selected = "";//( pagedata.thisID == <?=($crud_selected && $crud_selected->page_id != 0 )?$crud_selected->page->id : '-1'; ?>) ? " SELECTED" : "";
//				html+= '\n\t<option value="'+ pagedata.thisID +'"'+selected+'>'+ mydelimiter +' '+ pagedata.label +'</option>';		
//				
//				if( pagedata.children > 0)
//				{
//					pages = pagedata.child_pages;
//	
//					html = myfunction( mydelimiter +" -"); // run this again, recursively 
//				}				
//			});
//			return html;		
//		};	
//		
//		$('#page_id').html( f ).chosen();
//	});
}

function updateSlug(page_name){
	$.get(XHR_PATH+'generateSlug/',{string:page_name},function(newSlug){
	
		$('#page_slug').val(newSlug);
		if(newSlug !== $('#original_slug').val() && $('#original_slug').val !== ""){
			$("#reset_slug_link").fadeIn('fast');
		}else{
			$("#reset_slug_link").fadeOut('fast');	
		}
	//	checkSlug();
		
	});
}
