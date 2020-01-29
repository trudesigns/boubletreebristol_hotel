$(function(){
   // console.log($(window).width());
     if($(window).width() < 768){
              //  alert("SJSHDSH");
                $('#filter-category select option[value=""]').text("Select Category");
            } else {
                $('#filter-category select option[value=""]').text("");
            }
    
    //PHOTOBOX
    $('.photobox').photobox('a',{time:0,thumbs:true});
    
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
    
    $('nav').affix({ offset: {top:$("header").height() } });
   
    $('nav ul ul').addClass('dropdown-menu');
    $('nav li:has(> ul)').addClass('dropdown');
    
   $('.carousel').carousel();
   
   $('.owl-carousel').owlCarousel({
    items:1,
    loop:false,
    margin:0,
    center:true,
    nav:true,
    mouseDrag:true,
    stagePadding:40,
    startPosition:2,
    navText: ['<div class="arrow-left"></div>','<div class="arrow-right"></div>'],
    responsive:{
        500:{
            items:2,
            nav:true,
            mouseDrag:true
        },
        768:{
            items:2,
            nav:true,
            mouseDrag:true
        },
        992:{
            items:5,
            nav:false,
            mouseDrag:false,
            stagePadding:0
        }
    }
});


    
//    $('.photobox').masonry({
//        percentPosition:true,
//        columnWidth:80,
//        //itemSelector:'.photobox'
//        
//    });

    //DATEPICKER INIT
    $('.datepicker').datepicker({ dateFormat: "mm/dd/yy",minDate:0 } );

    //CHOSEN DROPDOWN
    if($('.chosen-single').length>0){
            $(".chosen-single").chosen({
                allow_single_deselect: true,
                //disable_search_threshold: 4
                disable_search:true
            }); 
           
     }
    if($('.chosen-multi').length>0){
        $(".chosen-multi").chosen({
               no_results_text: "Oops, nothing found!",
               placeholder_text_multiple:'Select a(n) item(s)'
       }); 
    }


    //MENUS of LIST OF PAGES FROM SITE TO PICK FROM 
    $('.select-menus').each(function(){
        var o =$(this);
        var menu_type = o.data('menu-type');
        var menu_id = o.data('menu-id');
        var posted = {ybr_token:sessiontoken,item_id:menu_id};
        if(menu_type !== "id"){
            posted ={ybr_token:sessiontoken,item_name:menu_id};
        } 
        
        //var menu_name = o.data('menu-name');
        $.ajax({
                type:"post",
                url:XHR_PUBLIC_PATH + 'menus',
                data:posted,
                //data:{ybr_token: sessiontoken,item_name:menu_name},
                dataType:"json",
                success:function(data){
                      //console.log(data);
                     var html = "";
                    if(data != null){
                         $.each(data, function (index, v) {
                             //  console.log(v);

                             html += "<option value='"+v.menu.link+"'>"+v.menu.label+"</option>";
                             if (typeof v.menu.children !== undefined) {
                                 var res =  listPagesDD(v.menu.menu_id,v.menu.children,1,null,null);
                              //console.log(res);
                                 if(res !== null){
                                     html += res.phtml;
                                 }
                             }
                         });
                     }
                    o.html(html);
                    }
            });
    });
                     
    
    //NEWS FUNCTIONALITY
    if($('#news').length>0){
        var dt =$('.datatable-news').dataTable({
             "lengthMenu":[[10,25,50,-1],['10 per page','25 per page','50 per page',"all"]]
              ,"bAutoWidth": false 
            //, "bStateSave":true
           // ,order: [[0, "desc"]]
             ,"fnDrawCallback":function(){
                 $('.dataTables_filter input[type=search]').prop("placeholder", "Search");
             }
             ,"language":{
                 lengthMenu:'_MENU_'
                 ,search:""
             }
         });
         $('.datatable-news').DataTable().columns([0,1]).order("desc").visible(false).draw();
         $('#filter-date select').on({
                'change':function(event){
                //   alert($(this).val());
                          var ndt = $('.datatable-news').DataTable();
                          ndt.order([0,$(this).val()]).draw();
                }
        });
         $('#filter-category select').on({
                'change':function(event){
                    //alert($(this).val());
                          var ndt = $('.datatable-news').DataTable();
                          ndt.column(1).search($(this).val()).draw();
                         // ndt.order([0,$(this).val()]).draw();
                }
        });   
        
        $('.sortby').on({
            click:function(event){
                $('#dt-filters').toggleClass('hide');
                event.preventDefault();
            }
        });
        
         
     }
     
     //OPENTABLE
     if($('#opentable').length >0){
         
         //$.validator.setDefaults({ ignore: ":hidden:not(select)" });
          $("#frmopentable").validate({
              //debug:true,
                ignore: '.ignore',
                onkeyup:false,//every time the data gets changed we re-validate
                onfocusout: function(element) {
                     $(element).valid();
                },
                success: function (element, baseEle) {
                    //console.log($(baseEle).attr('class'));
                        $(baseEle).parent().removeClass('has-error').addClass('has-success');
                        var span = $(baseEle).next('.chosen-container').find('.chosen-single').find('span');
                        var txt = span.data('text');
                        if(txt !== ""){
                           span.text(txt);
                       }
                        //$(baseEle).next('.chosen-container').find('.chosen-single').find('span').text(""));
                },
                errorPlacement: function (error, element) {
                 //console.log($(element).attr('class'));
                
                   //console.debug(error);
                   //console.log(element.next('.chosen-container').attr('id'));
                    var span = element.next('.chosen-container').find('.chosen-single').find('span');
//                     var txt = span.data('text');
//                     if(txt !== ""){
//                        span.text(txt);
//                    }
                    if (error) {
                        element.parent()
                        .addClass('has-error').removeClass('has-success');
                      
                       var txt  = span.text();
                        span.attr('data-text',txt).text(error.text());
                        element.attr('data-placeholder',error.text()).attr('placeholder',error.text());
                       
                
                    } 
                },
                rules: {
                    date: {
                        required:true,
                        date:true
                    },
                    time: "required",
                    people:"required"
                },
                messages: {
                    time:{
                        required:"The Time field is requried."
                    },
                    people:{
                        required:"The Party size field is required."
                    },
                    date:{
                        required:"The Date field is required.",
                        date:"The Date field is incorrect."
                    }

                },
                submitHandler:function(){
                    //alert("HERE");
                    var sdate = $('#date').val();
                    var stime = $('#time').val();
                    var sp    = stime.split(" ");
                    var speop = $('#people').val();
                    var url = "http://www.opentable.com/opentables.aspx?rid=102805&restref=102805&t=single&p="+speop+"&d="+sdate+"+"+sp[0]+"+"+sp[1];
                    window.open(url);
                }
        });
         
         
     }
    
       //LOGIN FUNCTIONALITY
    if ($('#login').length > 0) {
        $('#reset_password').on({
           'click':function(event){
               var email = $('#email').val();
                console.log(email);
                var o = $(this);
                var id = o.data('target-id');
                //alert(id);
                $('#'+id).toggle();
                $('#resetEmail').val(email);
                event.preventDefault();
            }

       });
        $("#resetPasswordSubmit").on({
            click: function (event) {
                var email = $("#resetEmail").val();
                //alert("here");
                if (email === "") {
                    alert("Please enter your e-mail address to continue");
                } else {
                    // $(this).fadeOut('fast'); // hide submit button		
                    $.ajax({
                        type: "post",
                        url: XHR_PUBLIC_PATH + 'resetPassword',
                        data: {ybr_token: sessiontoken, email: email},
                        dataType: "json",
                        success: function (data) {
                            if (data) {
                                $("#reset-msg").html("<p class='alert alert-success'>Your password has been reset and e-mailed to <strong>" + email + "</strong>.</p>");
                                $('#resetPasswordForm').hide();
                            } else {
                                //alert("ahshshs");
                                $("#reset-msg").html("<p class='alert alert-warning'>There was a probelm and we were not able to send a reset link to <strong>" + email + "</strong>.</p>");
                            }
                        }
                    });



                }


                event.preventDefault();
            }
        });
    }
    
    //CONTACT FORM
    if($('#contact-form').length >0){
        
        //alert("FORM");
            $("#contact-form").validate({
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
                                  .removeClass('has-error')
                                  .addClass('has-success')
                                  .find('.message')
                                  .text(' * ');
                      },
                      errorPlacement: function (error, element) {
                         // console.log($(element).attr('class'));
                         //console.debug(error);
                          if (error) {
                              element.closest('.form-group')
                              .addClass('has-error')
                              .removeClass('has-success')
                              .find('.message')
                              .text(' * ' + error.text());
                          }
                      },
                      rules: {
                          name: "required",
                          email:{
                              required:true,
                              email:true
                          },
                          phone:{phoneUS:true},
                          message:'required',
                          //verify:'required'


                      },
                      messages: {
                          name: {
                             required: 'Required.'
                          },
                          email:{
                             required:'Required.',
                             email:'Invalid.'
                          },
                          phone:{
                              phoneUS:"Invalid."
                          },
                          message:{
                             required:"Required."
                          },
//                          verify:{
//                              required:"The Security Code is required."
//                          }
                          

                      }
                     
              });
        
        
    }
    
     //Request For Proposal FORM
    if($('#rfp-form').length >0){
        
            $("#rfp-form").validate({
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
                                  .removeClass('has-error')
                                  .addClass('has-success')
                                  .find('.message')
                                  .text(' * ');
                      },
                      errorPlacement: function (error, element) {
                         // console.log($(element).attr('class'));
                         //console.debug(error);
                          if (error) {
                              element.closest('.form-group')
                              .addClass('has-error')
                              .removeClass('has-success')
                              .find('.message')
                              .text(' * ' + error.text());
                          }
                      },
                      rules: {
                          name: "required",
                          email:{
                              required:true,
                              email:true
                          },
                          phone:{phoneUS:true},
                          message:'required'


                      },
                      messages: {
                          name: {
                             required: 'Required.'
                          },
                          email:{
                             required:'Required.',
                             email:'Invalid.'
                          },
                          phone:{
                              phoneUS:"Invalid."
                          },
 
                          message:{
                             required:"Required."
                          }                          

                      }
                     
              });
        
        
    }
    
    
});


