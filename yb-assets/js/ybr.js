$(function(){

    /*JQUERY VALIDATE EXTRA FUNCS*/
     $.validator.addMethod(
        'digits',
        function (value, element) {
            if(this.optional(element)){
                return true;
            }
           return /^\d+$/.test(value);

        },
        'Please enter a valid number.'
    );

    $.validator.addMethod(
        'phone',
        function (value, element, params) {
             if(this.optional(element)){
                return true;
            }
         return /^[0-9+()\s\.-]{6,25}$/.test(value); // Allows digits, dashes and spaces in between


        },
        'Please enter a valid phone number.'
    );

    $.validator.addMethod(
        'email',
        function (value, element, params) {
             if(this.optional(element)){
                return true;
            }
            return  /^\S+@\S+\.\S+$/.test(value);

        },
        'Please enter a valid email address.'
    );
  /***********END JQUERY.VALIDATE EXTRA FUNCTION********************/


    /*****
     * JQUERY LIBRARIES INIT
     */
      /*CKEDITORS*/
      CKEDITOR.timestamp=ckeditor_cachebuster; //SET in SITECONFIG  and PULLED FROM THE HEAD OF TEMPLATE
    //allows to load different wysiwyg editor config
   $('.ckeditor_basic').ckeditor(function()
	{ CKFinder.setupCKEditor(this,'/ckfinder/') },
	{
                    allowedContent: true,
                    resize_enabled : true,
                    height : '350px',
                    toolbar : 'CMSbasic' //these are set in CKeditor/config.js
	}
        );

        $('.ckeditor_basicLinks').ckeditor(function()
	{ CKFinder.setupCKEditor(this,'/ckfinder/') },
	{
                    allowedContent: true,
                    resize_enabled : true,
                    height : '350px',
                    toolbar : 'CMSbasicPlusLinks' //these are set in CKeditor/config.js
	}
        );
        $('.ckeditor_default').ckeditor(function()
               { CKFinder.setupCKEditor(this,'/ckfinder/') },
               {
                           allowedContent: true,
                           resize_enabled : true,
                           height : '350px',
                           toolbar : 'CMSdefault' //these are set in CKeditor/config.js
               }
        );

    //Allows the browsing of files by ckeditor/ckfinder
    $('.image_browser').on({
        click:function(event){
           //alert("sdhsdhedhe");
                CKFinder.popup( {
                    basePath : '/ckfinder/'
                    ,resourceType: 'Images' // (only show Images folder)
                    ,rememberLastFolder: false
                    ,startupPath : 'Images:/'
                    ,startupFolderExpanded: true
                    ,selectActionFunction : function(fileURL){
                        $(".callback_image").val(fileURL);
                        $('.img-preview').attr('src',fileURL);

                    }
                });
                //event.preventDefault();
                return false;
        }

    });
    
     /***
     * ALLOWS THE USER TO CALL THE CKFINDER FOR FILES SUING A SINGLE BUTTON TO GET THERE
     * ie  <a href='#' id="linkTofile" class="file_browser btn btn-default" data-callback="#link">Select File</a>
     * data-callback tells the method where it needs to put the file path. 
     */
    
    $('#editor').on({
        click:function(event){
            var callback = $(this).data('callback');
            
           //alert("sdhsdhedhe");
                CKFinder.popup( {
                    basePath : '/ckfinder/'
                    ,resourceType: 'Files' // (only show Images folder)
                    ,rememberLastFolder: false
                    ,startupPath : 'Files:/'
                    ,startupFolderExpanded: true
                    ,selectActionFunction : function(fileURL){
                        $(callback).val(fileURL);
                       // $('.img-preview').attr('src',fileURL);

                    }
                });
                //event.preventDefault();
                return false;
        }

    },'.file_browser');

    /*DATATABLE*/
    var dt =$('.datatable').dataTable({
         "lengthMenu":[[10,25,50,-1],[10,25,50,"all"]]
         ,"bStateSave":true
         ,"aoColumnDefs": [
	                     { "asSorting": ['desc'] ,"aTargets": [ 0 ] , "bVisible":false},
	                     { "asSorting": false, "aTargets": [ -1 ] }
	                   ]
     });
    $('#dt-filters input[type=radio]').on({
                'change':function(event){
                          //  alert($(this).val());
                            if($(this).is(':checked')){
                                    dt.fnFilter("");
                                    dt.fnFilter($(this).val(),0);
                            }
                }
        });
    $('#filter-status select').on({
        change:function(evt,params){
            var o = $(this);
           // console.log(o.val());
           // if(o.val() !== ""){
            var sel = parseInt(o.val(),10);
            //console.log(typeof sel);
            if(sel >= 0){
                 //console.log(typeof sel);
                    dt.fnFilter(sel,0);
            } else {
                  dt.fnFilter(" ",0);
            }

        }
    });

     /*JQUERY UI DATEPICKER & TIMEPICKER*/
    $('.datepicker').datepicker({ dateFormat: "mm/dd/yy" } );
    $('.datepicker-time').datetimepicker({dateFormat:"mm/dd/yy",timeFormat: 'h:mmtt'});

    /*SUPERFISH*/
    $('.sf-menu').superfish({
            delay:		1000,	// one second delay on mouseout
            animation:	{opacity:'show'},	// an object equivalent to first parameter of jQuery’s .animate() method. Used to animate the submenu open
            animationOut: {opacity:'hide'},	// an object equivalent to first parameter of jQuery’s .animate() method Used to animate the submenu closed
            speed:		'fast',	// faster animation speed
            cssArrows:	false	// disable generation of arrow mark-up
    });

    /*TOOLTIP from JQUERY UI*/
    $('.yb-tooltip').tooltip({
        template:'<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
    });
    $('.yb-tooltip-html').tooltip({
        template:'<div class="tooltip" role="tooltip"><div class="tooltip-inner"></div></div>'
        ,html:true
    });

    /*SELECT2*/
    $(".yb-select").select2({
            width: 'resolve' //First attempts to "copy" than falls back on "element".
    });

    /*CHOSEN*/
     if($('.chosen-single').length>0){
            $(".chosen-single").chosen({
                allow_single_deselect: true,
                width: "95%"
            });
     }
     if($('.chosen-multi').length>0){
        // alert("here");
        $(".chosen-multi").chosen({
               no_results_text: "Oops, nothing found!",
               placeholder_text_multiple:'Select a(n) item(s)',
               width:"95%"
       });
    }


    /**BOOTSTRAP PLUGINS***/
    if($('[data-toggle="popover"]').length>0){
        $('[data-toggle="popover"]').popover();
    }
    if($('[data-toggle="tooltip"]').length>0){
        $('[data-toggle="tooltip"]').tooltip();
    }
    /********END HELPERS FOR JQUERY INIT*************/


    /***
     * COMMON THROUGHOUT
     */
    if($('#reenter-password-form').length >0){

        $('#reenter-password-form').validate({
            ignore: '.ignore',
            onkeyup:false,//every time the data gets changed we re-validate
            onfocusout: function(element) {
                 $(element).valid();
            },
            success: function (element, baseEle) {
                //console.log($(baseEle).attr('class'));
                    $(baseEle)
                        .prev('label')
                        .find('.message')
                        //.text(' * ')
                        .parent()
                        .parent()
                        .removeClass('has-error')
                        .addClass('has-success');
            },
            errorPlacement: function (error, element) {
                //console.log($(element).attr('class'));
                if (error) {
                    element
                    .prev('label').find('.message')
                    .text( error.text())
                    .parent()
                    .parent()
                    .addClass('has-error')
                    .removeClass('has-success');
                }
            },
            rules: {
                password:"required",
            },
            messages: {
                password:{
                    required:'The Password field is required.'
                }
            },
            submitHandler:function(form){
                var sdata = $(form).serialize();

               //  alert("shshshsh");
               // console.log(window.location.pathname);
                //,{username: $("#user-username").val(), password: value}
                  $.ajax({
                    type:"post",
                    url:BASE_PATH+'user/signin',
                    dataType:"json",
                    data:sdata+"&ybr_loggedin="+sessiontoken,
                }).done(function(data){
                    // console.log(data);
                        if(data){
                                  $('#reenter-password').modal('hide');
                        }else {
                            var v =  $('#reenter-password-form').validate();
                            v.showErrors({
                                'password':'Invalid Pasword'
                            });
                        }
                 });

              // return false;
            }
        });

 }

    $('.status').on('click','.ajax', function(event){
            var o = $(this);
            var idx =o.parents('tr')[0];
       // console.log("IDX");
       /// console.log(idx);
            var gp = o.parents('.status');
            var gpp = gp.parent();
            var tbl = o.parents('table');
            var mdl = tbl.data('model');

        //   console.log("LENGHTH: "+gp.length);
            var recid = gpp.data('recordid');
             //console.log(userid);

             var action = o.data('action');
             if(action==='status'){
                 var id = o.data('actionid');
                   if(id==4)id=2; //THE UNFEATURED HAS A STATUS CODE OF 4 SO WE CHANEG IT TO 2 TO JUST BE ACTIVE
                         
                $.ajax({
                     type:"POST"
                     ,url:XHR_PATH + "changeStatus/"
                     ,data:{recordid:recid,status:id,model:mdl,ybr_loggedin:sessiontoken}
                 }).done(function(data){
                          var oTable = $('.table').dataTable();//instead of calling the instanciated object (dt) we are just reinstantiating the table with more generic call
                          var pos = oTable.fnGetPosition(idx);//actual row obj
                          // console.log("POS");
                         //  console.log(pos);
                       
                        var txt = oTable.fnUpdate(id ,pos , 0);
                        var txt2= oTable.fnUpdate(data ,pos , -2);//update the 2 to last row with the data we got back from ajax
                        gp.parent().removeClass('yb-inactive').removeClass('yb-active').removeClass('yb-deleted');
                        var css_class = "";
                        switch(id){
                          case 0:
                             css_class = "yb-deleted";
                              break;
                          case 1:
                                css_class = "yb-inactive";
                              break;
                          case 2:
                                css_class = "yb-active";
                                 break;
                      }
                        gp.parent().addClass(css_class);
                 });


             }
              event.preventDefault();
          });



    /***
     * LOGIN
     */
    if($('#login').length >0 ){

       $('#reset_password').on({
           'click':function(event){

               var o = $(this);
                var id = o.data('target-id');
                //alert(id);
                $('#'+id).toggle();
                event.preventDefault();
            }

       });

    }

    /**
     * USERS
     */
    if($('#users.list').length >0 ){


        dt.fnSort( [ [2,'asc'] ] );//preset the sort to be on the last name

         $('#reset-pass').on({
             click:function(event){
                 var userid = $('#reset-modal').data('userid');

                 $.ajax({
                      type:"POST"
                      ,url:XHR_PATH + "resetPassword/"
                      //,async:false
                      ,data:{user:userid,ybr_loggedin:sessiontoken}
                  }).done(function(data){
                         $('#reset-modal .msg').text(data).show();
                           $('#reset-modal .start').hide();
                          $('#reset-pass').hide();
                      
                  });

             }
         });

        $('#reset-modal').on('show.bs.modal',function(event){
            var rTarget  = $(event.relatedTarget);
            var reset =  rTarget.parents('.reset');
            var userid = reset.data('userid');
            var uname = reset.data('username');
            $('#reset-modal').data('userid',userid);
            $('#reset-modal .user_name').text(uname);
           // event.preventDefault();

        });

         $('#users').on('change','.role-change',function(){

                var o = $(this);
                var act = true;
                if(o.is(':checked')){
                    act = false;
                }
                var userid = o.data('userid');

                $.ajax({
                      type:"POST"
                      ,url:XHR_PATH + "setRole/"
                      //,async:false
                      ,data:{user:userid,role:o.val(),action:act,ybr_loggedin:sessiontoken}

                  });


            
        });

    }

    if($('#users.form').length>0){

         $("#user_form").validate({
            ignore: '.ignore',
            onkeyup:false,//every time the data gets changed we re-validate
            onfocusout: function(element) {
                 $(element).valid();
            },
            success: function (element, baseEle) {
                //console.log($(baseEle).attr('class'));
                    $(baseEle)
                        .prev('label')
                        .find('.message')
                        .text(' * ')
                        .parent()
                        .parent()
                        .removeClass('has-error')
                        .addClass('has-success');
            },
            errorPlacement: function (error, element) {
                //console.log($(element).attr('class'));
                if (error) {
                    element
                    .prev('label').find('.message')
                    .text(' * ' + error.text())
                    .parent()
                    .parent()
                    .addClass('has-error')
                    .removeClass('has-success');
                }
            },
            rules: {
                first: "required",
                last:"required",
                email: {
                    required: true,
                    email: true,
                    maxlength: 255
                },
                phone:{
                    phone:true
                },
                password:"required",
                password2:{
                    equalTo:'.password'
                }
            },
            messages: {
                email: {
                   required: 'The Email field is required.'
                    ,email:'The Email provided is invalid.'
                },
                first:{
                    required:'The First Name field is required.'
                },
                last:{
                    required:'The Last Name field is required.'
                },
                password:{
                    required:'The Password field is required.'
                },
                password2:{
                    required:'The Confirm Password field is required.' ,
                    equalTo:'Please check that your passwords match.'
                }

            }
        });
    }

    /***
     * TEMPLATES
     */
    if($('#addBlocks').length>0){
        $('.blocks_checkbox').change(function(){
                var action = ( $(this).is(':checked') ) ? "add" : "delete";
                var templateid = $(this).data('template-id');
                //console.log("TEMPLATEID: "+templateid);
                $.ajax({
                    type:"POST"
                    ,url:XHR_PATH + "templateblocks/"
                    ,data:{ybr_loggedin:sessiontoken,action:action,block_id:$(this).val(),template_id:templateid}
                }).done(function(data){
                         if(data)
                        {
                                var blocks_message = (action == "delete") ? "Block Removed" : "Block Added";
                                setLocalYBmsg(blocks_message,1000);
                        }
                        else
                        {
                                alert(data);
                        }
                    
                });

        });
    }

    /**
     * MENUS
     */
   if($('article#menus').length>0){
       //var menu_id = $("#selected_menu").val();
       
        $('#thisMenu').on({
            change:function(e){
//           /     console.log(e.originalEvent );

                var id = $(this).val();
               // console.log(id);
                if(id !== ""){

                    //PAGES ALREADY SELECTED IF ANY
                    var pages_selected = $.ajax({
                        type:"post",
                        url:XHR_PATH + 'pages_menu',
                        dataType:"json",
                        data:{ybr_loggedin: sessiontoken,item_id:id}
                    }).done(function(data){
                                var edit_tool = $('#menus_toolbar').children('#menu-edit').html();
                                var rem_tool = $('#menus_toolbar').children('#menu-remove').clone();
                                // console.log("DATA: "+typeof data);
                               // console.log(rem_tool);
                                if(data !== null){
                                    var html = "<ul class='dd-list' >\n";
                                    $.each(data, function (index, v) {
                                        //console.log(v.pages);

                                        html += '<li class="dd-item dd3-item" id="pageID_' + v.menu.id + '" data-menu-item="' + v.menu.id + '" data-menu-id="' + v.menu.menu_id + '" data-id="'+v.menu.id+'">';

                                        html += "<div class='dd-handle dd3-handle'>&nbsp;</div>";
                                        html += "<div class='menu-item dd3-content'>";
                                       // html += '<div class="pages_menu_item" data="'+v.menu.id+'" data-menu-item="' + v.menu.id + '" data-menu-id="' + v.menu.menu_id + '" ';
                                          if (v.pages !== null) {
                                           //html += '  data-subpages="' + v.pages.page.children_role + '">';
                                            html += '<span class="" title="' + v.pages.page.slug + '">' + v.pages.page.label + '</span>';
                                        } else {
                                          //  html += ">";
                                            html += '<span class="" title="' + v.menu.link_value + '">' + v.menu.label + '</span>';
                                        }



                                       html += "<div class='yb-toolbar hide'>";
                                       html+= edit_tool;

                                        if ( v.menu.children === null) {
                                           html += $(rem_tool).html()
                                       }
                                       // console.log($(rem_tool).html());
                                    //    html += $(edit_tool).after($(rem_tool).html());
                                        html += "</div>";//END .yb-toolbar

                                        html += "</div>";//end .menu-item
                                        if (typeof v.menu.children !== undefined && v.menu.children !== null) {
                                            html += "<ul class='dd-list'>\n";
                                            html += loadSubMenu(v.menu.children);
                                            html += "</ul>";
                                        }
                                        html += "</li>\n";
                                    });
                                   html += "</ul>";
                                   $('#nestable').html(html);
                                } else{
                                    $("#nestable").html("<p>No Menu Item currently defined</p>")
                                }

                            

                    });
                    
                    pages_selected.then(function(){//SUCCESS
                        //MENUS of LIST OF PAGES FROM SITE TO PICK FROM
                        var menus_list =  $.ajax({
                            type:"post",
                            url:XHR_PATH + 'pages',
                            dataType:"json",
                            data:{ybr_loggedin: sessiontoken,menu_id:id}
                            }).done(function(data){
                                 // console.log(data);
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
                               // console.log(html);
                                $('#add_to_menu').html(html).trigger("chosen:updated");

                        });
                        
                        menus_list.then(function(){//SUCCESS
                             //DO I NEED TO SHOW THE PUBLISH MENU
                            $.ajax({
                                  type:"post",
                                 url:XHR_PATH + 'compare',
                                 data:{ybr_loggedin: sessiontoken,id:id,what:'menus'}
                             }).done(function(data){
                                 data = JSON.parse(data);
                                     //console.log(typeof data );
                                     if(data === false){
                                         $('#publish_item').removeClass('hide');
                                     }else {
                                         $('#publish_item').addClass('hide');
                                     }


                             });
                              $('.hide-on-load').removeClass('hide-on-load');
                              nestable('menu-item');
                             
                        },function(){//ERROR
                            alert("i was not able to load the Menus List ");
                        });
                    },function(){//ERROR
                        alert("i was not able to load the pages you sleected");
                    });
                    $('#copy_menu').attr("data-menu",id);
                } else {

                     $('#menu_tools').addClass('hide-on-load');
                     $('#nestable').empty();
                }



            }
        });
        
        $('#menus').on('change','#copy_menu',function(){
            var copy_from = $(this).data('menu');
            var copy_to = $(this).val();
            //console.debug("FROM: "+copy_from+" TO: "+copy_to);
            var conf = confirm("Are you sure you want to copy this menu?");
            $.ajax({
                       type:"post",
                       url:XHR_PATH + 'copyMenus',
                       data:{
                           ybr_loggedin: sessiontoken
                           ,from: copy_from
                           , to: copy_to
                       },
                   }).done(function(data){
                            
                });
            
        });

        $('#menus').on('click','.add_tree_page',function(event){
               // alert("shshshshs");
                var page_id = $("#add_to_menu").val();
                var menu_id = $('#thisMenu').val();

                if (page_id === ""){
                    uiAlert("Select a page to add");
                    return false;
                }

                //console.log($(this).data('children'));
                var parent_id = (parent_id !== undefined && parent_id !== "") ? parent_id : 0;

                //console.log("PARENT_ID: "+parent_id);
                $.ajax({
                       type:"post",
                       url:XHR_PATH + 'menuAddPages',
                       data:{
                           ybr_loggedin: sessiontoken
                           , menu_id: menu_id
                           , page_id: page_id
                           , parent_id: parent_id
                           , link_type: 'page_id'
                           , add_children:$(this).data('children')
                       },
                   }).done(function(data){
                            $('#save_item').click(); //SAVE IF YOU HAVE CHANGES THAT WERE NOT SAVED
                            // console.log("SJSJSJSJS");
                            $('#add_to_menu').val(" ").trigger("chosen:updated");//RESET THE PAGE LIST DROPDOWN
                            $('#thisMenu').val(menu_id).trigger("change");
                });
                event.preventDefault();

        });

        $('#menus').on('click','.add_custom',function(event){
                var o = $(this);
                var item = o.parents('.menu-item').children('.dd-handle');
                //var item_id = item.data('menu-item');
                var menu_id =$('#thisMenu').val();
                var mdl = $('#custom_url');
                var title = mdl.find('h4.modal-title');
                title.text('Add Custom Page');
                var frm = mdl.find('form');
                frm.find('#link_value').removeAttr("readonly").removeClass('ignore');
                frm.find('#link_type').val('url');
                // frm.find('#link_id').val(item_id);
                frm.find('#menu_id').val(menu_id);
                frm.find('#parent_id').val('0');
                frm.find('#display_order').val('0');
                frm.find('#link_type').val('url');
                frm.find('#link_value').val("");
                frm.find('#label').val("");
                frm.find('#target').val("");
                frm.find('#attributes').val("");
                event.preventDefault();
          });

        $('#menus').on('click','.add_sitemap',function(event){

                var menu_id = $('#thisMenu').val();
                var conf = confirm("Are you sure you want tot add the entiresitemap to this menu?");
                if(conf){
                     $.ajax({
                        type:"post",
                        url:XHR_PATH + 'menuAllPages',
                        data: {menu_id: menu_id,ybr_loggedin:sessiontoken,link_type: 'page_id'},
                    }).done(function(data){
                                $('#save_item').click(); //SAVE IF YOU HAVE CHANGES THAT WERE NOT SAVED
                                $('#add_to_menu').val(" ").trigger("chosen:updated");//RESET THE PAGE LIST DROPDOWN
                               $('#thisMenu').val(menu_id).trigger("change");
                        
                    });
                }
                event.preventDefault();
        });

        $('#menus').on('mouseenter','.menu-item',function(){
           // alert("sjdhdhdh");
            var o = $(this).children('.yb-toolbar');
            o.removeClass('hide');

        });

        $('#menus').on('mouseleave','.menu-item',function(){
           // alert("sjdhdhdh");
            var o = $(this).children('.yb-toolbar');
            o.addClass('hide');

        });

        $('#menus').on('click','.edit-item',function(event){
//alert("sjdhjshd");
                var o = $(this);
                var item = o.parents('.menu-item').parents('.dd-item').first();
                var parent_id = o.parents('.menu-item').parents('.dd-item').first().parents('.dd-item').first().data('menu-item');
                var item_id = item.data('menu-item');
                var menu_id = $('#thisMenu').val();//.data('menu-id');
                //console.log("MENU_ITEM: "+item_id);
                var mdl = $('#custom_url');
                var title = mdl.find('h4.modal-title');
                title.text('Edit Menu Page');
                var frm = mdl.find('form');
                $.ajax({
                    type:"post",
                    url:XHR_PATH + 'menu_item',
                    data:{ybr_loggedin: sessiontoken,item_id:item_id},
                    dataType:"json",
                }).done(function(data){
                        frm.find('#link_id').val(item_id);
                        frm.find('#menu_id').val(menu_id);
                        frm.find('#parent_id').val(parent_id);
                        if($.isNumeric(data.link_value)){
                            frm.find('#link_value').attr("readonly","readonly").addClass('ignore');
                            frm.find('#link_type').val('pages_id');

                        } else {
                            frm.find('#link_type').val('url');
                        }
                         frm.find('#link_value').val(data.link_value);
                        frm.find('#label').val(data.label);
                         frm.find('#target').val(data.target);
                        frm.find('#attributes').val(data.attributes);
                       // console.log(data);
                    
                  });
      //console.log("sjshshjs");
            $('#custom_url').modal();//the modal needs help to actully get triggered
               event.preventDefault();
          });

        $('#menus').on('click','.remove-item',function(event){
               //console.log("blahblah menu");
                var o = $(this);
                var par = o.parents('.dd-item').first();
                var gp = par.parents('.dd-item').first();
                var conf = confirm("Are you sure you want to REMOVE  the page '"+ par.find('.menu-item span').text()+"' from the menu?");
                if(conf){

                var item_id = par.data('menu-item');
                //console.log(item_id);
               //  o.toggleClass('hide');
                  $.ajax({
                         type:"post",
                         url:XHR_PATH + 'delete_menu_item',
                         data:{ybr_loggedin: sessiontoken,item_id:item_id}
                     }).done(function(){
                             var child_cnt = par.parents('.dd-item').children('.dd-item').length;
                            //console.log("CNT: "+child_cnt);
                            if(child_cnt  <1 ){
                                //console.log("CNTBBB: "+gp.find('.menu-remove').length);
                                if(gp.find('.yb-toolbar .remove-item').length <2){
                                     var rem = $('#menus_toolbar').children('#menu-remove').html();
                                     gp.find('.yb-toolbar .edit-item').after(rem);
                                 }
                            }

                             par.remove();//remove the html
                             $('#publish_item').removeClass('hide');
                         
                     });
            }
                event.preventDefault();
        });
        $('#menus').on('submit','#custom_url_form',function(event){
              var menu_id =$('#thisMenu').val();
              $.ajax({
                    type:"post",
                    url:XHR_PATH + 'custom_url',
                    data:$( this ).serialize()+"&ybr_loggedin="+sessiontoken,
                    dataType:"json",
                }).done(function(data){
                     $('#custom_url').modal('hide');
                     $('#publish_menu').removeClass('hide');
//console.log("SAVE CLICK");
                     $('#save_item').click(); //SAVE IF YOU HAVE CHANGES THAT WERE NOT SAVED
   //                  console.log("UPDATE CHOSEN");
                     $('#add_to_menu').val(" ").trigger("chosen:updated");//RESET THE PAGE LIST DROPDOWN
      //               console.log("MENU ID");
                     $('#thisMenu').val(menu_id).trigger("change");
                     $('#publish_menu').removeClass('hide');

                });

             event.preventDefault();
         });
//        $('#menus').on('submit','#custom_url_form',function(event){
//              var menu_id =$('#thisMenu').val();
//              $.ajax({
//                    type:"post",
//                    url:XHR_PATH + 'custom_url',
//                    data:$( this ).serialize()+"&ybr_loggedin="+sessiontoken,
//                    dataType:"json",
//                }).done(function(data){
//                     $('#custom_url').modal('hide');
//                    
////console.log("SAVE CLICK");
//                     $('#save_item').click(); //SAVE IF YOU HAVE CHANGES THAT WERE NOT SAVED
//   //                  console.log("UPDATE CHOSEN");
//                     $('#add_to_menu').val(" ").trigger("chosen:updated");//RESET THE PAGE LIST DROPDOWN
//      //               console.log("MENU ID");
//                     //var menu_id = $('#thisMenu').val();
//                      menus_list.then(function(){//SUCCESS
//                             //DO I NEED TO SHOW THE PUBLISH MENU
//                            $.ajax({
//                                  type:"post",
//                                 url:XHR_PATH + 'compare',
//                                 data:{ybr_loggedin: sessiontoken,id:id,what:'menus'}
//                             }).done(function(data){
//                                 data = JSON.parse(data);
//                                     //console.log(typeof data );
//                                     if(data === false){
//                                         $('#publish_item').removeClass('hide');
//                                     }else {
//                                         $('#publish_item').addClass('hide');
//                                     }
//
//
//                             });
//                              $('.hide-on-load').removeClass('hide-on-load');
//                              nestable('menu-item');
//                             
//                        },function(){//ERROR
//                            alert("i was not able to load the Menus List ");
//                        });
//
//                });
//
//             event.preventDefault();
//         });

        $('#menus').on('click','#cancel_item',function(event){
            $('#thisMenu').val($('#thisMenu').val()).trigger("change");
            $('#save_item').addClass('hide');
            $('#cancel_item').addClass('hide');

        });

        $('#menus').on('click','#save_item',function(event){
                var id = $('#thisMenu').val();
                $.ajax({
                    type: "post",
                    url: XHR_PATH + 'orderMenuPages',
                    data: {
                        ybr_loggedin: sessiontoken
                        , menu_id: id
                        , list_order:JSON.stringify($('.nestable').nestable('serialize'))
                    }
                }).done(function (data) {
                        $('#save_item').addClass('hide');
                        $('#cancel_item').addClass('hide');
                        $('#publish_item').removeClass('hide');
                        //$('#thisMenu').val(id).trigger("change");
                    
                });
                event.preventDefault();
        });

        $('#menus').on('click','#publish_item',function(event){

                $.ajax({
                    type:"post",
                    url:XHR_PATH + 'publish',
                    dataType:"json",
                    data:{
                        ybr_loggedin:sessiontoken
                        ,item_name:"Menu_"+$('#thisMenu option:selected').text()
                        ,recordid:  $('#thisMenu').val()
                        ,model:"Menupage"
                    }
                }).done(function(data){
                        if(data){
                                $('#publish_item').addClass('hide');
                        } else {
                            alert("There was a problem publishing the menu. It is most likely happening because the cache folder and its children is not writable");
                        }
                    
                });

                event.preventDefault();
         });


   }

   /******
    * REDIRECTS
    */
   if($('article#redirects').length>0){

       $('#path').on({
           'focusout':function(){
               var o= $(this);
               var entered_value = o.val();
                if(entered_value.length > 1)
                {
                    var alias_msg = "";
                        $.ajax({
                            type:"post",
                            url:XHR_PATH + 'checkAlias',
                            dataType:"json",
                            data:{ybr_loggedin: sessiontoken,alias: entered_value, alias_id: $("#editid").val() },
                        }).done(function(data){
                                    if( data.pages )
                                    {
                                          alias_msg = "A live page already exists with this URL. To make the page available again deactivate this alias.";
                                    }
                                    else if (data.redirects)
                                    {
                                            alias_msg = "An alias already exists with this value. Activating this alias will make the page unavailable.";
                                    }
                                    if(alias_msg !==""){
                                        var html = "<div id='path_alert'  class='alert alert-warning alert-dismissible' role='alert'>";
                                        html += "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Close</span></button>";
                                        html += "<span>"+alias_msg+"</span>";
                                        html += "</div>";
                                        o.after(html);
                                    }
                            
                        });
                }
                else
                {
                        return false;
                }
           }

       });

       $('#page_select').on({
           'click':function(event){
                $.ajax({
                    type:"post",
                    url:XHR_PATH + 'pages',
                    dataType:"json",
                    data:{ybr_loggedin: sessiontoken},
                }).done(function(data){
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

                            $('#select-destination').removeClass('hide').html(html);
                             $('#page_select').addClass('hide');
                             $('#destination_control').removeClass('hide');
                            //$('#add_to_menu').html(html);
                    
                });
               event.preventDefault();
           }
       });

        $('#destination').one({
           keypress:function(){
              // alert("sdjhdsfhsdfh");
               $('#destination').val("");
               $('#destination_id').val("");
               $('#page_select').removeClass('hide');
               $('#destination_control,#select-destination').addClass('hide');

           }
       });

        $('#destination').on({
           focusout:function(){
               $('#destination').one({
                    keypress:function(){
                       // alert("sdjhdsfhsdfh");
                        $('#destination').val("");
                        $('#destination_id').val("");

                    }
                });
           }
       });

        $('#redirects').on('change','#select-destination',function(event){
           var o = $(this);
           //console.log(o.text());
           $('#destination_id').val(o.val());

          // $('#destination').val(o.children('option:selected').text());
       });

       $('#page_accept').on({
           'click':function(event){
                        $('#page_select').removeClass('hide');
                        $('#destination_control,#select-destination').addClass('hide');

                        var o = $('#select-destination');
                        var v = o.val();
                        $.ajax({
                                type:"post",
                                url:XHR_PATH+'/getUrl',
                                data:{ybr_loggedin:sessiontoken,page_id:v},
                            }).done(function(data){
                                   $('#destination').val(data);
                               
                            });


                    event.preventDefault();
           }
       });

        $('#page_cancel').on({
           'click':function(event){
                     window.location.reload();
                    // window.location.href = window.location.href;
                    event.preventDefault();
           }
       });


   }

    /*******
     * PAGES HELPERS
     */
    if($('article#pages.list').length >0){


        $('#test').scrollTop(500);
        nestable('page-item');



        $("#addrootpage").on({
                'click':function(event){
                        //alert(XHR_PATH );
                         $.ajax({
                            type:"post",
                            url:XHR_PATH + 'addPage',
                            data:{parent_id: 0, ybr_loggedin: sessiontoken}
                        }).done(function(data){
                               // console.log(data);
                                    data = $.trim(data);
                                    if (isNaN(parseInt(data, 10))) { // FYI, the "10" parameter indicates that pareInt should treat this value as a decimal
                                        uiAlert("Error! " + data);
                                    } else {
                                                 window.location.reload();
                                       }
                            
                       });
                        event.preventDefault();
                }
        });

        $('#pages').on('click','.toggleAll',function(event){
               //  console.log($(this).data('action'));
                    var action = $(this).data('action');
                    switch(action){
                        case "expand-all":
                            $('#nestable').nestable('expandAll');
                            break;
                        case "collapse-all":
                             $('#nestable').nestable('collapseAll');
                            break;
                    }

         });

        $('#pages').on('mouseenter','.dd-item >div',function(){
           // alert("sjdhdhdh");
            var o = $(this).children('.yb-toolbar');
            o.removeClass('hide');

        });

        $('#pages').on('mouseleave','.dd-item >div',function(){
           // alert("sjdhdhdh");
            var o = $(this).children('.yb-toolbar');
            o.addClass('hide');

        });

        $('#pages').on('click','#cancel_item',function(event){
                window.location.reload();
        });

        $('#pages').on('click','#save_item',function(event){

              $.ajax({
                    type: "post",
                    url: XHR_PATH + 'orderPages',
                    data: {
                        ybr_loggedin: sessiontoken
                        //, menu_id: id
                        , list_order:$('.nestable').nestable('serialize')
                    },
                }).done(function (data) {
                      window.location.reload();
                    
                });
                event.preventDefault();

        });

        $('#pages').on('click','.remove-item',function(event){
               //console.log("blahblah menu");
                var o = $(this);
                var par = o.parents('.dd-item').first();
                var gp = par.parents('.dd-item').first();
                var conf = confirm("Are you sure you want to REMOVE  the page '"+ par.find('.page-item span').text()+"'");
                if(conf){

                        var item_id = par.data('page-item');
                        $.ajax({
                             type:"post",
                             url:XHR_PATH + 'delete_page_item',
                             data:{ybr_loggedin: sessiontoken,item_id:item_id},
                         }).done(function(){
                                // console.log(par.length);


                                var child_cnt = par.parents('.dd-item').children('.dd-item').length;
                              //  console.log("CNT: "+child_cnt);
                                if(child_cnt  <1 ){
                                    //console.log("CNTBBB: "+gp.find('.menu-remove').length);

                                      // var rem = $('#pages_toolbar').children('#page-remove').html();
                                       gp.find('.yb-toolbar .remove-item').removeClass('hide');

                                }

                                 par.remove();//remove the html
                               //  $('#publish_menu').removeClass('hide');
                             
                         });
                }
                event.preventDefault();
        });

        $('#pages').on('click','.page-sub',function(event){
            var o = $(this);

                var par = o.parents('.dd-item').first();

            var pid = o.data('parent-id');
            //console.log("PID: "+pid);
             $.ajax({
                 type:"post",
                 url:XHR_PATH + 'addPage',
                 data:{ybr_loggedin: sessiontoken,parent_id:pid}
             }).done(function(data){
                 if (isNaN(parseInt(data, 10))) {
                             alert(data);
                         } else{
                            //console.log("PID: "+pid);
                   //console.log($("#pageID_"+pid).find('.dd-list').length);
                            var output = "";
                            if($("#pageID_"+pid).find('.dd-item').length < 1 ){
                               output += "<ul class='dd-list '>";
                            }
                            output += '<li class="dd-item dd3-item new-page" id="pageID_' + data + '" data-id="' + data + '" data-page-item="'+data+'">';
                            output += '<div class="page-item dd-handle dd3-handle">&nbsp;</div>';
                            output += '<div class=" pages_menu_item dd3-content" data="' + data + '" data-page-item="'+data+'">';
                             output += '<span>New Page</span>\n';

                              output += "<div class='yb-toolbar hide'>";
                            output +="<a href='/admin/edit/?page_id="+data+"&block_id=3&version_id=1' class='glyphicon glyphicon-edit page-edit' title='Edit page content' >&nbsp;</a>";
                            output +="<a href='#' class='glyphicon glyphicon-list-alt page-properties' data-toggle='modal' data-target='#page-prop' title='Edit page properties' >&nbsp;</a>";
                            output +="<a href='#' class='glyphicon glyphicon-chevron-down page-sub'  title='Create a sub page' data-parent-id='"+data+"'>&nbsp;</a>";
                            output += "<a href='#' class='glyphicon glyphicon-sort page-move' data-toggle='modal' data-target='#page-move' title='Move page' >&nbsp;</a>";
                            output +="<a href='#' class='glyphicon glyphicon-remove remove-item' title='Remove this page'>&nbsp;</a>";
                             output +="</div>";
                             output +="</div>";
                            output += '</li>';
                            if($("#pageID_"+pid).find('.dd-list').length < 1  ){
                               output += "</ul>";
                               //console.log(pid);
                               //console.log(output);
                               //alert("there");
                               $("#pageID_"+pid).find('.dd3-content').after(output);
                            } else {
                                //alert("HERE");
                                $("#pageID_"+pid).find('.dd-list').first().append(output);
                            }

                         nestable('page-item');
                         par.find('.yb-toolbar .remove-item').first().addClass('hide');

//                var off = $('.new-page').offset();
//                 console.log(off.left);
//                 console.log("Top: "+off.top);
                 //$('.new-page').scrollTop(100);
                 //$.scrollTo({top:off.top+'px',left:off.left+'px'},1000);
               // return false;
                         
                        

                }
             });

           
             setTimeout(function(){
               $('.new-page').removeClass('new-page');
             },30000);

           event.preventDefault();
        });

        /************** END PAGE LIST HELPERS*********************/

        /*****PAGE MOVE HELPERS ********/
        $('#pages').on('show.bs.modal','#page-move',function(data){//the modal open get the main main menu with preselected page
            var o = data.relatedTarget;
            var id   = $(o).parents('.dd-item').data('id');
            var name = $(o).parents('.dd-item').first().text();
            var pid = $(o).parents('.dd-item').parents('.dd-item').data('id');
            var sid = $(o).parents('.dd-item').prev('.dd-item').data('id');
            //console.log("ID: "+id+" PID: "+pid+" SID: "+sid);
            if(pid === null){
                pid = 0;
            }

            $.ajax({
                        type:"post",
                        url:XHR_PATH + 'pages',
                        dataType:"json",
                        data:{ybr_loggedin: sessiontoken,menu_id:id}
                    }).done(function(data){
                             // console.log(data);
                            var phtml = "";
                            var shtml = "";
                            var frm = $('#form-page-move');
                            frm.find('input[name=editid]').val(id);
                            $.each(data, function (index, v) {
                                 // console.log(v);
                                var sel = "";
                                if(v.page.id == pid){
                                    sel= " selected='selected' ";
                                }
                                if(v.page.parent_id == pid){
                                    var ssel = "";
                                    if(v.page.id == sid){
                                        ssel = "selected='selected' ";
                                    }
                                    shtml += "<option value='"+v.page.id+"' "+ssel+">"+v.page.label+"</option>";
                                }
                                phtml += "<option value='"+v.page.id+"' "+sel+" >"+v.page.label+"</option>";
                                if (typeof v.page.children !== undefined && v.page.children !== null) {
                                   var  res =   listSubMenus(v.page.children,1,pid,sid);
                                  // console.log(res);
                                   phtml += res.phtml;
                                   shtml += res.shtml;
                                }
                            });
                            $('#dd-pages').html(phtml);
                            $('#dd-siblings').html(shtml);
                            $('.move-name').text(name);
                            
                    });
        });

        $('#pages').on('change','#dd-pages',function(event){
            var o = $(this);
            var id = o.val();
           // console.log("SELECTEDID: "+id);
            $.ajax({
                        type:"post",
                        url:XHR_PATH + 'pages',
                        dataType:"json",
                        data:{ybr_loggedin: sessiontoken,page_id:id},
                    }).done(function(data){
                            //console.log(data);
                                var shtml = "";
                                if (typeof data.page.children !== undefined && data.page.children !== null) {
                                   var  res =   listSubMenus(data.page.children,1,id,null);
                                   shtml += res.shtml;
                                }
                                $('#dd-siblings').html(shtml);
                        
                    });

            //console.log();
        });

        $('#pages').on('click','#save-move',function(event){
            var sdata  =$('#form-page-move').serialize();
           // console.log(sdata);
              $.ajax({
                        type:"post",
                        url:XHR_PATH + 'orderpages_manually',
                        dataType:"json",
                        data:sdata+"&ybr_loggedin="+sessiontoken,
                    }).done(function(data){
                           // console.log(data);
                            if(data){
                                window.location.reload();
                                //window.location.href = window.location.href;

                            }
                        
                    });


            event.preventDefault();
        });

        /**********END PAGE MOVE HELPERS******************/

        /**********PAGE PROPERTIES HELPERS**********************/
        $('#pages').on('show.bs.modal','#page-prop',function(data){//the modal open the the form item gets populated
            var o = data.relatedTarget;
            var id = $(o).parents('.dd-item').data('id');
            var pid = $(o).parents('.dd-item').parents('.dd-item').data('id');
            $.ajax({
                        type:"post",
                        url:XHR_PATH + 'getPageProperties',
                        dataType:"json",
                        data:{ybr_loggedin: sessiontoken,page_id:id},
                    }).done(function(data){


         //console.log(data);
                            var frm = $('#form-page-prop');
                            frm.find('input[name=editid]').val(id);
                            var rpid = 0;
                             if (typeof pid !== undefined && pid !== null) {
                                rpid = pid;
                             }
                            frm.find('input[name=parentid]').val(rpid);
                            frm.find('input[name=label]').val(data.label);
                            frm.find('input[name=slug]').val(data.slug);
                           // console.log(id);
                            if(id===1){
                                frm.find('input[name=slug]').prop('disabled',true).addClass('ignore');
                            } else {
                                 frm.find('input[name=slug]').prop('disabled',false).removeClass('ignore');
                            }
                            $('#template_id').find('option').each(function(e,i){
                            //   console.log($(this).val());
                                if($(this).val() === data.template_id){
                                       // $(this).addClass('test');
                                        $(this).prop('selected',true);
                                }
                            });
                            $("#template_id").trigger("chosen:updated");
                            var status = "0";
                            if(data.active){
                                status = "1";
                            }
                            $('#status_id').find('option').each(function(e,i){
                            //   console.log($(this).val());
                                if($(this).val() === status){
                                       // $(this).addClass('test');
                                        $(this).prop('selected',true);
                                }
                            });
                            $("#status_id").trigger("chosen:updated");
                            //frm.find('select[name=status]').val(status);
                            frm.find('input[name=startDate]').val(data.start_date);
                            frm.find('input[name=endingDate]').val(data.end_date);
                            if(data.display_in_sitemap){
                                frm.find('input[name=sitemap]').prop("checked",true);
                            }
                            if(data.searchable){
                                frm.find('input[name=searchable]').prop("checked",true);
                            }
                            var crole = Number(data.add_children_role);
                            if(crole >0){
                                frm.find('input[name=lock]').prop("checked",true);
                            } else {
                                frm.find('input[name=lock]').prop("checked",false);
                            }
                            if(data.required_role !== "") {
                                $('#groups_id').find('option').each(function(e,i){
                            //   console.log($(this).val());
                                    var sp = data.required_role.split(",");
                                    //console.log(sp);
                                    var o =$(this);
                                    $.each(sp,function(k,v){
                                       //console.log(v);
                                         if(o.val() === v ){
                                                   // $(this).addClass('test');
                                                    o.prop('selected',true);
                                            }
                                    });

                                });
                                $("#groups_id").trigger("chosen:updated");
                            }
                        
                    });
            $(".chosen-multi").trigger("chosen:updated");

            $('.disabled').each(function(){
                var o = $(this);
                o.find('input').prop('disabled',true);
            });
        });

        $('#pages').on('focusout','input[name=label]',function(){
            var o = $(this);
            o.after("<a href='#' class='update-slug' >Update Slug</a>");
        });

        $('#pages').on('click','.update-slug',function(event){
            var o = $(this);
            var label = o.prev('input[name=label]').val();
              $.ajax({
                        type:"post",
                        url:XHR_PATH + 'generateSlug',
                        data:{ybr_loggedin: sessiontoken,string:label},
                    }).done(function(data){
                           $('input[name=slug]').val(data);
                           $('.update-slug').remove();
                        
                    });
            event.preventDefault();
        });

        $("#form-page-prop").validate({
                                ignore: '.ignore',
                                onkeyup:false,//every time the data gets changed we re-validate
                                onfocusout: function(element) {
                                     $(element).valid();
                                },
                                success: function (element, baseEle) {
                                    //console.log($(baseEle).attr('class'));
                                        $(baseEle)
                                            .prev('label')
                                            .find('.message')
                                            .text(' * ')
                                            .parent()
                                            .parent()
                                            .removeClass('has-error')
                                            .addClass('has-success');
                                },
                                errorPlacement: function (error, element) {
                                    //console.log($(element).attr('class'));
                                    if (error) {
                                        element
                                        .prev('label').find('.message')
                                        .text(' * ' + error.text())
                                        .parent()
                                        .parent()
                                        .addClass('has-error')
                                        .removeClass('has-success');
                                    }
                                },
                                rules: {
                                    label: "required",
                                    slug:{
                                        "required":true,
                                        remote:{
                                                type:"post",
                                                url:XHR_PATH + 'checkSlug',
                                               data:{
                                                   ybr_loggedin: sessiontoken,
                                                   slug:function(){
                                                       return $('#form-page-prop').find('input[name=slug]').val();
                                                   },
                                                   page_id: function(){
                                                       return $('#form-page-prop').find('input[name=editid]').val();
                                                   },
                                                   parent_id: function(){
                                                       return $('#form-page-prop').find('input[name=parentid]').val();
                                                   }
                                               }

                                        }

                                    },
                                    template:"required",
                                    status:'required'

                                },
                                messages: {
                                    label: {
                                       required: 'The Label field is required.'
                                    },
                                    slug:{
                                        required:'The URL Slug field is required.'
                                        ,remote:'The Slug you provided is being used'
                                    },
                                    template:{
                                        required:'The Template field is required.'
                                    },
                                    status:{
                                        required:'The Status field is reuqired.'
                                    }

                                },
                                submitHandler:function(){
                                    var sdata = $('#form-page-prop').serialize();
                                      $.ajax({
                                        type:"post",
                                        url:XHR_PATH + 'saveproperties',
                                        dataType:"json",
                                        data:sdata+"&ybr_loggedin="+sessiontoken,
                                    }).done(function(data){
                                         //alert(data);
                                            if(data){
                                               window.location.reload();
                                            }
                                        
                                      });


                                }

                            });

        $('#pages').on('click','#save-prop',function(event){

                $('#form-page-prop').submit();

            //  event.preventDefault();
        });

        /****************END PAGE PROPERTIES HELPERS**************************/
    }

    //CONTENT EDITOR
    if($('article#editor').length>0){

        $('#editor').on('click','#content-preview',function(event){
                //alert("djdhjdjd");
                var o = $(this);
                var page_id  = o.data('page-id');
                var content_id   = o.data('content-id');
                var block_key    = o.data('block-key');
                var page_name = o.data('page-name');
                //var url =

                 var url = $.ajax({
                             type:"post",
                             async:false,
                             url:XHR_PATH + 'getUrl',
                             data:{ybr_loggedin: sessiontoken,page_id:page_id}
                         }).responseText;
               // console.log(url);
                $('#preview-frame').prop('src',url+"?preview_block_objectkey="+block_key+"&content_id="+content_id);
                $('#preview-modal .modal-header .modal-title span').text(page_name);
                $('#preview-modal').modal('show');
                event.preventDefault();

        });

    }

    /**
     * DROPDOWN FORM
     */
    if($('.dd-form').length>0){
        //THIS looks for form selector where you pass in the link for the page the load from as well as id so that when you chnage the drop down form get populated.
        $('.form-selector').on({
            change:function(){
                var o = $(this);
                var link= o.data('link');
                var id = o.val();
                window.location.href=link+id;
            }
        });

    }

    /********END CUSTOM HELPERS *************/
});
