var homepage_id = 1; // the page id of the homepage

var order, original_order, order_isDirty = false;

var sessiontoken =  $.cookie('ybr_loggedin');
//console.log(sessiontoken);

function lockToolbar()
{
    $(".ui-icon-wrench, .ui-icon-document, .ui-icon-trash, .ui-icon-copy, .ui-icon-shuffle").off('click');
    $("#yb-wrap").append($("#page_toolbar").fadeOut(0)); // move the toolbar out of the hovered div before reloading the list
    $(".pages_menu_item").unbind('hover'); // stop the hover effect while the div is reloading
}

function triggerNestable()
{
    $('#pages')
            .nestable({
                listNodeName: 'ul',
                maxDepth: 5,
                rootClass: 'pages',
                noDragClass: 'dd-no-move',
                collapsedClass: 'contentContainer'
            })
            .on('change', function () {
                order = $('#pages').nestable('serialize');

                if (JSON.stringify(order) !== JSON.stringify(original_order))
                {
                    order_isDirty = true;
                    $("#save-order").fadeIn('fast');
                }
                else
                {
                    order_isDirty = false;
                    $("#save-order").fadeOut('fast');
                }
            })
            .disableSelection();

    original_order = $('#pages').nestable('serialize');
}
//
function saveorder()
{
    // note the var "order" is set in the triggerNestable() function on change
     $.ajax({
        type:"post",
        url:XHR_PATH + 'orderPages',
        data:{ybr_loggedin: sessiontoken,list_order: order},
        dataType:"json",
        success:function(data){
            data = $.trim(data);
            if (data !== "true")
            {
                uiAlert(data);
                return false;
            }
            else
            {
                order_isDirty = false;
                original_order = $('#nestable').nestable('serialize'); // update original order to be the new order
                $("#save-order").fadeOut('fast');
            }
        }
    });


//    $.post(XHR_PATH + 'orderPages/', {list_order: order}, function (data) {
//        data = $.trim(data);
//        if (data !== "true")
//        {
//            uiAlert(data);
//            return false;
//        }
//        else
//        {
//            order_isDirty = false;
//            original_order = $('#pages').nestable('serialize'); // update original order to be the new order
//            $("#save-order").fadeOut('fast');
//        }
//    });
}

function initiateToolbar(toolbar_hit_zone)
{
    toolbar_hit_zone = (!toolbar_hit_zone) ? ".pages_menu_item" : toolbar_hit_zone;

    //add tool bar on hover and bind functionality to its buttons
    $(toolbar_hit_zone).hover(function () {
        var item_id = $(this).attr('data');
        var page_name = $("span", this).html();

        $(this).append($("#page_toolbar"));
        //bind "Edit Page" pop-up box
        $(".ui-icon-wrench").on('click', function (e) {
            e.stopPropagation();
            editPage(item_id)
        });

        //bind "Edit Content" link
        $(".ui-icon-document").on('click', function (e)
        {
            e.stopPropagation();
            var fwd = '/admin/edit?page_id=' + item_id + '&block_id=3&version_id=1';
            if (order_isDirty)
            {
                uiConfirm({message: "There are unpublished changes to the order of pages.<br><br>Are you sure you want to leave this page without saving?",
                    title: "Confirm Leaving Page",
                    callback: function (response) {
                        if (response)
                        {
                            order_isDirty = false;
                            window.location = fwd;
                        }
                        else
                        {
                            return false;
                        }
                    }
                });
            }
            else
            {
                window.location = fwd;
            }
        });

        //bind "Move Page" link
        $(".ui-icon-shuffle").on('click', function (e)
        {
            e.stopPropagation();
            movePage(item_id);
        });

        //bind "Add Supbage" function
        $(".ui-icon-copy").on('click', function (e) {
            e.stopPropagation();
            addPage(item_id)
        });

        //bind "Remove Project" delete action
        $(".ui-icon-trash").on('click', function (e) {
            e.stopPropagation();

            uiConfirm({message: "Are you sure you want to PERMANENTLY DELETE the page \"" + page_name + "\"?<br><br><span style='color: red'>All content</span>, past and present, associated with this page <span style='color: red'>will be lost!</span><br><br>This action cannot be undone.",
                title: "Confirm Page Deletion",
                callback: function (response) {
                    if (!response)
                    {
                        return false;
                    }
                    else
                    {
                        $.ajax({
                            type:"post",
                            url:XHR_PATH + 'deletePage',
                            data:{ybr_loggedin: sessiontoken,page_id: item_id},
                           // dataType:"json",
                            success:function(data){
                                 if ($.trim(data) !== "done") {
                                    uiAlert(data);
                                }
                                else
                                {
                                  //alertt(item_id);
                                   $("#pageID_" + item_id).remove();
//                                    setTimeout(function () {
//                                        this_li.remove();
//                                    }, 500);
                                }
                            }
                        });
                        
//                        $.get(XHR_PATH + 'deletePage/', {page_id: item_id}, function (data) {
//                            if ($.trim(data) !== "done") {
//                                uiAlert(data);
//                            }
//                            else
//                            {
//                                var this_li = $("#pageID_" + item_id).fadeOut('fast');
//                                lockToolbar();
//                                setTimeout(function () {
//                                    this_li.remove()
//                                }, 500);
//                            }
//                        });

                    }
                    return response;
                }
            });
        });

        $("#page_toolbar").fadeIn(0);
    }, function () {
        $(".ui-icon-wrench, .ui-icon-document, .ui-icon-trash, .ui-icon-copy, .ui-icon-shuffle").off('click');
        $('body').append($("#page_toolbar"));
        $("#page_toolbar").fadeOut(0);
    });
}


function loadSubPage(child)
{
     var html ="";
   // console.log("CHILD: "+child);
    if(child !== null){
      $.each(child, function (idx, c) {
           //console.log(c);
                html += '<li class="dd-item" id="pageID_' + c.page.id+ '" data-id="' + c.page.id + '"><div class="dd-handle pages_menu_item" data="' + c.page.id + '" data-subpages="' +c.children_role + '"><span class="dd-no-move" title="' +c.page.slug + '">' + c.page.label + '</span></div>';
                //html += '<li class="dd-item" id="pageID_' + c.thisID + '" data-id="' + c.thisID + '"><div class="dd-handle pages_menu_item" data="' + c.thisID + '" data-subpages="' + c.add_children_role + '"><span class="dd-no-move" title="' + c.uri + '">' +c.label + '</span></div>'; 
         // console.log(c.page.childrens);
//               if (c.children > 0)
//                    {
//                             html += "<ul class=\"dd-list\">\n";
//                            html +=  loadSubPage(c.child_pages);
//                              html +="</ul>";
//                    }
//console.log( c.page.children);
                       if(typeof c.page.children !== undefined){
                           html += "<ul class=\"dd-list\">\n";
                             html +=  loadSubPage(c.page.children);
                             html +="</ul>"
                    }
                html += "</li>";
          
        });
        return html;
    } 
    return "";
}


function loadPagesTable(expanded)
{
    if (order_isDirty)
    {
        uiConfirm({message: "There are unpublished changes to the order of pages.<br><br>Are you sure you want to refresh the list without saving?",
            title: "Confirm Refresh",
            callback: function (response) {
                if (response)
                {
                    order_isDirty = false;
                    $("#save-order").fadeOut('fast');
                    loadPagesTable(expanded);
                }
                else
                {
                    return false;
                }
            }
        });
        return false;
    }

    lockToolbar(); // make sure the toolbar icon set is not in the #page area that is about to be replaced.
    $('#nestable').html('loading...');

//    console.log("ybr_loggedin: " +);
    $.ajax({
        type:"post",
        url:XHR_PATH + 'pages',
        data:{ybr_loggedin: sessiontoken},
        dataType:"json",
        success:function(data){
       //     console.log("hshshshs");
//console.log(data);
             var html = "<ul class=\"dd-list\">\n";
             $.each(data, function (index, v) {
                    //console.log("CHILDREN");
                     if (v.page.id === homepage_id)
                    {
                        html += '<li class="dd-item" id="pageID_' +v.page.id + '" data-id="' + v.page.id + '"><div class="dd-hand-no-drag pages_menu_item" data="' + v.page.id + '" data-subpages="' + v.children_role + '"><span class="dd-no-move" title="' + v.page.slug + '">' + v.page.label + '</span></div>';
                    }
                    else
                    {
                        html += '<li class="dd-item" id="pageID_' + v.page.id+ '" data-id="' + v.page.id + '"><div class="dd-handle pages_menu_item" data="' + v.page.id + '" data-subpages="' + v.children_role + '"><span class="dd-no-move" title="' + v.page.slug + '">' + v.page.label + '</span></div>';
                    }
              
              
//                    if (v.thisID == homepage_id)
//                    {
//                        html += '<li class="dd-item" id="pageID_' + v.thisID + '" data-id="' + v.thisID + '"><div class="dd-hand-no-drag pages_menu_item" data="' + v.thisID + '" data-subpages="' + v.add_children_role + '"><span class="dd-no-move" title="' + v.uri + '">' +v.label + '</span></div>';
//
//                    }
//                    else
//                    {
//                        html += '<li class="dd-item" id="pageID_' + v.thisID + '" data-id="' + v.thisID + '"><div class="dd-handle pages_menu_item" data="' + v.thisID + '" data-subpages="' + v.add_children_role + '"><span class="dd-no-move" title="' + v.uri + '">' + v.label + '</span></div>';
//                    }
                    
//                    if (v.children > 0)
//                    {
////                        pages = v.child_pages;
////                        html = myfunction(); // run this again, recursively 
//                            html += "<ul class=\"dd-list\">\n";
//                            html +=  loadSubPage(v.child_pages);
//                              html +="</ul>"
//                    }

                        if(typeof v.page.children !== undefined){
                             html += "<ul class=\"dd-list\">\n";
                             html +=  loadSubPage(v.page.children);
                             html +="</ul>"
                        }
                     //html +=  loadSubPage(v.page.children);
                  
                 
                     html += "</li>\n";
                });
               html +="</ul>"
             $('#nestable').html(html);
              initiateToolbar(); // set the hover state for the toolbar 
                triggerNestable(); // make the content moveable with "Nestable" plugin.

//                if (location.hash)
//                {
//                    var preselected_page_id = location.hash.substr(1);
//                    editPage(preselected_page_id);
//                }
        }
    });
    //XHR_PATH + 'allPages', {ybr_loggedin: sessiontoken}, function (data) {
 //console.log(data);
      //  var html = "";
        
           
            //html += "<ul class=\"dd-list\">\n";
            //$.each(data, function (index, v) {
//console.log(v);
//                if (pagedata.thisID == homepage_id)
//                {
//                    html += '<li class="dd-item" id="pageID_' + pagedata.thisID + '" data-id="' + pagedata.thisID + '"><div class="dd-hand-no-drag pages_menu_item" data="' + pagedata.thisID + '" data-subpages="' + pagedata.add_children_role + '"><span class="dd-no-move" title="' + pagedata.uri + '">' + pagedata.label + '</span></div>';
//
//                }
//                else
//                {
//                    html += '<li class="dd-item" id="pageID_' + pagedata.thisID + '" data-id="' + pagedata.thisID + '"><div class="dd-handle pages_menu_item" data="' + pagedata.thisID + '" data-subpages="' + pagedata.add_children_role + '"><span class="dd-no-move" title="' + pagedata.uri + '">' + pagedata.label + '</span></div>';
//                }
//
//                if (pagedata.children > 0)
//                {
//                    pages = pagedata.child_pages;
//                    html = myfunction(); // run this again, recursively 
//                }
//                html += "</li>\n";

           // });
//            html += "</ul>\n";
//            return html;
      // };

   //     $('#pages').html(f); // populate the #pages div with the generated content
//        initiateToolbar(); // set the hover state for the toolbar 
//        triggerNestable(); // make the content moveable with "Nestable" plugin.
//
//        if (location.hash)
//        {
//            var preselected_page_id = location.hash.substr(1);
//            editPage(preselected_page_id);
//        }

    //});
}

function addPage(parent_id)
{
    $.post(XHR_PATH + 'addPage/', {parent_id: parent_id, ybr_loggedin: sessiontoken}, function (data) {
        data = $.trim(data);
        if (isNaN(parseInt(data, 10))) { // FYI, the "10" parameter indicates that pareInt should treat this value as a decimal
            uiAlert("Error! " + data);
        } else {

            var new_li = '<li class="dd-item" id="pageID_' + data + '" data-id="' + data + '"><div class="dd-handle pages_menu_item" data="' + data + '"><span>New Page</span></div></li>';

            if (parent_id === 0) // if this is a top level page, insert it below the "Homepage" 
            {
                $("#pageID_" + homepage_id).after(new_li);
            }
            else if ($("#pageID_" + parent_id).has("ul").length > 0)
            {
                $("#pageID_" + parent_id + " ul").first().append(new_li);
            } else {
                $("#pageID_" + parent_id).append("<ul class=\"dd-list\">" + new_li + "</ul>");
                $("#pageID_" + parent_id).addClass('contentContainer');
            }

            initiateToolbar('#pageID_' + data + ' div');
        }
    });
}

function editPage(item_id) { // load the data to be put in the pop-up
    
     $.ajax({
        type:"post",
        url:XHR_PATH + 'getPageProperties',
        data:{ybr_loggedin: sessiontoken,page_id:item_id},
        dataType:"json",
        success:function(data){
          //  console.log(data.id);
                if (!data || $.trim(data) == "false" || data.id == null)
                {
                    uiAlert("Internal Error:  Invalid Page ID or Page Not Found");
                    window.location.hash = '';
                    window.history.pushState({foo: 'bar'}, "page 2", "/admin/pages/");
                    return;
                }

                $("#current_page_parent_id").val(data.parent_id);

                $("#label").val(data.label);
                $("#slug, #original_slug").val(data.slug);
                $("#template_id").val(data.template_id);

                // Note, the select list excludes "locked" templates (for non-developers)
                // so if the page in question uses a locked template, it can't be set as the selected template.
                // the user won't notice because, since the page's template is locked, they don't see the
                // select list anyway.  But upon submitting the form, the selected value will be wrong.
                $('[name*="template_id"]').select2('val', data.template_id);

                if (data.template_available === 0)
                {
                    $("#list_templates").css('display', 'none');
                    $("#template_locked").html(data.template_name + " (Locked)").css('display', 'block');
                }
                else
                {
                    $("#list_templates").css('display', 'block');
                    $("#template_locked").css('display', 'none');
                }

                $("#active").attr('checked', data.active);
                $("#start_date").val(data.start_date);
                $("#end_date").val(data.end_date);
                $("#searchable").attr('checked', data.searchable);
                $("#display_in_sitemap").attr('checked', data.display_in_sitemap);
                $("#page_id").val(data.id);
                $("#required_role").val(data.required_role);
                $('[name*="required_role"]').select2('val', data.required_role);

                var add_childed_status = ($("#add_children_role").val() === data.add_children_role) ? true : false;
                $("#add_children_role").attr('checked', add_childed_status);

                $("#slug_icon").attr('title', '').html('').css('color', 'auto');
                $("#reset_slug_link").css('display', 'none');


                $("#start_date").datepicker({dateFormat: "yy-mm-dd 00:00:00"});
                $("#end_date").datepicker({dateFormat: "yy-mm-dd 00:00:00"});

                checkSlug();
                $("#editPageDialog").dialog('open');
                $(".tooltip").tooltip();
        }
    });
    /*
    $.getJSON(XHR_PATH + 'getPageProperties/', {page_id: item_id, ybr_loggedin: sessiontoken}, function (data) {

        if (!data || $.trim(data) == "false" || data.id == null)
        {
            uiAlert("Internal Error:  Invalid Page ID or Page Not Found");
            window.location.hash = '';
            window.history.pushState({foo: 'bar'}, "page 2", "/admin/pages/");
            return;
        }

        $("#current_page_parent_id").val(data.parent_id);

        $("#label").val(data.label);
        $("#slug, #original_slug").val(data.slug);
        $("#template_id").val(data.template_id);

        // Note, the select list excludes "locked" templates (for non-developers)
        // so if the page in question uses a locked template, it can't be set as the selected template.
        // the user won't notice because, since the page's template is locked, they don't see the
        // select list anyway.  But upon submitting the form, the selected value will be wrong.
        $('[name*="template_id"]').select2('val', data.template_id);

        if (data.template_available === 0)
        {
            $("#list_templates").css('display', 'none');
            $("#template_locked").html(data.template_name + " (Locked)").css('display', 'block');
        }
        else
        {
            $("#list_templates").css('display', 'block');
            $("#template_locked").css('display', 'none');
        }

        $("#active").attr('checked', data.active);
        $("#start_date").val(data.start_date);
        $("#end_date").val(data.end_date);
        $("#searchable").attr('checked', data.searchable);
        $("#display_in_sitemap").attr('checked', data.display_in_sitemap);
        $("#page_id").val(data.id);
        $("#required_role").val(data.required_role);
        $('[name*="required_role"]').select2('val', data.required_role);

        var add_childed_status = ($("#add_children_role").val() === data.add_children_role) ? true : false;
        $("#add_children_role").attr('checked', add_childed_status);

        $("#slug_icon").attr('title', '').html('').css('color', 'auto');
        $("#reset_slug_link").css('display', 'none');


        $("#start_date").datepicker({dateFormat: "yy-mm-dd 00:00:00"});
        $("#end_date").datepicker({dateFormat: "yy-mm-dd 00:00:00"});

        checkSlug();
        $("#editPageDialog").dialog('open');
        $(".tooltip").tooltip();
    });
    
    */
}

function pageData() {
    return {
        page_id: $("#page_id").val(),
        parent_id: $("#current_page_parent_id").val(),
        label: $("#label").val(),
        slug: $("#slug").val(),
        template_id: $("#template_id").val(),
        active: ($("#active").is(':checked')) ? 1 : 0,
        start_date: ($("#start_date").val() !== "") ? $("#start_date").val() : '0000-00-00 00:00:00',
        end_date: ($("#end_date").val() !== "") ? $("#end_date").val() : '0000-00-00 00:00:00',
        searchable: ($("#searchable").is(':checked')) ? 1 : 0,
        display_in_sitemap: ($("#display_in_sitemap").is(':checked')) ? 1 : 0,
        required_role: $("#required_role").val(),
        add_children_role: ($("#add_children_role").is(':checked')) ? $("#add_children_role").val() : '',
        ybr_loggedin: sessiontoken
    }
}

function drawSiblingSelect(parent_id, item_id)
{
    
      $.ajax({
                type:"post",
                url:XHR_PATH + 'allPages',
                data:{parent_id: parent_id, ybr_loggedin: sessiontoken},
                dataType:"json",
                success:function(siblings){
                    var html = (parent_id == 0) ? "" : "<option value=\"0\">Parent (first page in section)</option>",
                    previous_item = 0,
                    f2 = function myfunction(mydelimiter)
                    {
                        $.each(siblings, function (index, pagedata2)
                        {

                            if (pagedata2.thisID === item_id)
                            {
                                previous_item = index - 1;
                                //	alert("older sibling: "+ pages2[previous_item].label);
                            }

                            html += '\n\t<option value="' + pagedata2.thisID + '">' + pagedata2.label + '</option>';
                        });
                        return html;
                    };
                    $('#section_order').html(f2).select2();

                    if (parent_id == 0 && siblings[previous_item] === undefined)
                    {
                        var selected = homepage_id;
                    }
                    else
                    {
                        var selected = (siblings[previous_item] !== undefined) ? siblings[previous_item].thisID : 0;
                    }

                    $('#section_order').select2("val", selected)
                }
            });
    /*
    $.getJSON(XHR_PATH + 'allPages', {parent_id: parent_id, ybr_loggedin: sessiontoken}, function (siblings) {

        var html = (parent_id == 0) ? "" : "<option value=\"0\">Parent (first page in section)</option>",
                previous_item = 0,
                f2 = function myfunction(mydelimiter)
                {
                    $.each(siblings, function (index, pagedata2)
                    {

                        if (pagedata2.thisID === item_id)
                        {
                            previous_item = index - 1;
                            //	alert("older sibling: "+ pages2[previous_item].label);
                        }

                        html += '\n\t<option value="' + pagedata2.thisID + '">' + pagedata2.label + '</option>';
                    });
                    return html;
                };
        $('#section_order').html(f2).select2();

        if (parent_id == 0 && siblings[previous_item] === undefined)
        {
            var selected = homepage_id;
        }
        else
        {
            var selected = (siblings[previous_item] !== undefined) ? siblings[previous_item].thisID : 0;
        }

        $('#section_order').select2("val", selected)

    });
    
    */
}

function movePage(item_id)
{
    if (item_id == homepage_id)
    {
        uiAlert("You can not move the homepage");
        return false;
    }

    $("#move_page_id").val(item_id);
    
     $.ajax({
        type:"post",
        url:XHR_PATH + 'getPageProperties',
        data:{ybr_loggedin: sessiontoken,page_id:item_id},
        dataType:"json",
        success:function(data){
             $("#move_page_name").html(data.label);
             $.ajax({
                type:"post",
                url:XHR_PATH + 'allPages',
                data:{exclude: item_id, ybr_loggedin: sessiontoken},
                dataType:"json",
                success:function(site_map){
                    var html = "", pages = site_map, mydelimiter = "x", f = function myfunction(mydelimiter)
                    {
                        if (mydelimiter === 0) {
                            mydelimiter = "";
                        }

                        $.each(pages, function (index, pagedata)
                        {
                            var selected = (pagedata.thisID === data.parent_id) ? " SELECTED" : "";
                            html += '\n\t<option value="' + pagedata.thisID + '"' + selected + '>' + mydelimiter + ' ' + pagedata.label + '</option>';

                            if (pagedata.children > 0)
                            {
                                pages = pagedata.child_pages;

                                html = myfunction(mydelimiter + " -"); // run this again, recursively 
                            }
                        });
                        return html;
                    };

                    $('#parent_id').html(f).on('change', function () {
                        // on change, redraw the sub <select> menu of child pages in the new parent.
                        var selected_parent = ($(this).val() == homepage_id) ? 0 : $(this).val();
                        drawSiblingSelect(selected_parent, item_id);
                    }).select2();

                    // 3.	Draw the sub <select> menu of child pages with the given parent
                    drawSiblingSelect(data.parent_id, item_id);

                    // 4.	Open the dialog box				
                    $("#movePageDialog").dialog('open');
                    $(".tooltip").tooltip();
                }
            });
        }
    });
    /*

    // 1.	Get page data of given page to move
    $.getJSON(XHR_PATH + 'getPageProperties/', {page_id: item_id, ybr_loggedin: sessiontoken}, function (data) {

        $("#move_page_name").html(data.label);

        // 2.	Draw a <select> menu of all pages on this site
        //		except for this page and its children
        $.getJSON(XHR_PATH + 'allPages/', {exclude: item_id, ybr_loggedin: sessiontoken}, function (site_map) {
            $('#parent_id').html('<select disabled>Loading...</select>');

            var html = "", pages = site_map, mydelimiter = "x", f = function myfunction(mydelimiter)
            {
                if (mydelimiter === 0) {
                    mydelimiter = "";
                }

                $.each(pages, function (index, pagedata)
                {
                    var selected = (pagedata.thisID === data.parent_id) ? " SELECTED" : "";
                    html += '\n\t<option value="' + pagedata.thisID + '"' + selected + '>' + mydelimiter + ' ' + pagedata.label + '</option>';

                    if (pagedata.children > 0)
                    {
                        pages = pagedata.child_pages;

                        html = myfunction(mydelimiter + " -"); // run this again, recursively 
                    }
                });
                return html;
            };

            $('#parent_id').html(f).on('change', function () {
                // on change, redraw the sub <select> menu of child pages in the new parent.
                var selected_parent = ($(this).val() == homepage_id) ? 0 : $(this).val();
                drawSiblingSelect(selected_parent, item_id);
            }).select2();

            // 3.	Draw the sub <select> menu of child pages with the given parent
            drawSiblingSelect(data.parent_id, item_id);

            // 4.	Open the dialog box				
            $("#movePageDialog").dialog('open');
            $(".tooltip").tooltip();

        });	// end getJSON "allPages" to draw the <select> of the whole site

    });// end getJSON "getPageProperties"

*/


}

function updateSlug(page_name) {
    //var scrubed = scrubURL(page_name);
    //$('#slug').val(scrubed);
    
      $.ajax({
        type:"post",
        url:XHR_PATH + 'generateSlug',
        data:{ybr_loggedin: sessiontoken,string:page_name},
        dataType:"json",
        success:function(data){
            $('#slug').val(data);
            if (data !== $('#original_slug').val() && $('#original_slug').val !== "") {
                $("#reset_slug_link").fadeIn('fast');
            } else {
                $("#reset_slug_link").fadeOut('fast');
            }
            checkSlug();
        }
    });
    
//    $.get(XHR_PATH + 'generateSlug/', {string: page_name, ybr_loggedin: sessiontoken}, function (newSlug) {
//
//        
//        if (newSlug !== $('#original_slug').val() && $('#original_slug').val !== "") {
//            $("#reset_slug_link").fadeIn('fast');
//        } else {
//            $("#reset_slug_link").fadeOut('fast');
//        }
//        checkSlug();
//
//    });
}

function checkSlug() {
     $.ajax({
        type:"post",
        url:XHR_PATH + 'checkSlug',
        data:{ybr_loggedin: sessiontoken,slug:$('#slug').val(), page_id: $('#page_id').val(), parent_id: $("#current_page_parent_id").val()},
        dataType:"json",
        success:function(data){
             var slug_icon = $("#slug_icon");
            if (data) {
                var valid_icon = "&#10004;"; // ✓
                slug_icon.attr('title', 'This slug is valid').html(valid_icon).css('color', 'green');
            } else {
                slug_icon.attr('title', 'This slug is invalid or already in use').html("x").css('color', 'red');
            }
        }
    });
    
//    $.getJSON(XHR_PATH + 'checkSlug/', {slug: $('#slug').val(), page_id: $('#page_id').val(), parent_id: $("#current_page_parent_id").val(), ybr_loggedin: sessiontoken}, function (data) {
//        var slug_icon = $("#slug_icon");
//        if (data) {
//            var valid_icon = "&#10004;"; // ✓
//            slug_icon.attr('title', 'This slug is valid').html(valid_icon).css('color', 'green');
//        } else {
//            slug_icon.attr('title', 'This slug is invalid or already in use').html("x").css('color', 'red');
//        }
//    });
}

/*
 * refresh the page, even if order_isDirty is true
 */
function dirty_refresh()
{
    order_isDirty = false;
    location.reload(true);
}

$(window).bind('beforeunload', function () {
    var message = "There are unpublished changes to the order of pages.";
    if (order_isDirty) {
        return message;
    }
});


$(document).ready(function () {
    loadPagesTable();

    $("#editPageDialog").dialog({
        width: 650,
        height: 'auto',
        modal: true,
        autoOpen: false,
        resizable: false,
        buttons: [
            {
                text: "Cancel",
                click: function () {
                    $(this).dialog('close');
                    window.history.pushState({foo: 'bar'}, "page 2", "/admin/pages/");
                }
            },
            {
                text: "Save Changes >",
                click: function () {
                    var sendData = pageData();

                    // if the select menu is hidden it means this page's existing template is locked
                    // so don't send whatever bogus value has been assigned as the new template_id
                    if ($("#list_templates").css('display') == "none")
                    {
                        delete sendData.template_id;
                    }

                    $.post(XHR_PATH + 'savePageProperties/', sendData, function (data) {
                        data = $.trim(data);
                        if (data !== "done")
                        {
                            uiAlert(data);
                        }
                        else
                        {
                            lockToolbar();
                            var newString = '<span>' + $("#label").val() + '</span>';
                            $("#pageID_" + $("#page_id").val() + " .pages_menu_item").first().html(newString);
                        }
                    });
                    $(this).dialog('close');
                    window.history.pushState({foo: 'bar'}, "page 2", "/admin/pages/");
                }
            }
        ]
    });

    $("#movePageDialog").dialog({
        width: 650,
        height: 'auto',
        modal: true,
        autoOpen: false,
        resizable: false,
        buttons: [
            {
                text: "Cancel",
                click: function () {
                    $(this).dialog('close');
                }
            },
            {
                text: "Save Changes >",
                click: function () {

                    $.get(XHR_PATH + 'orderPageManually/', {page_id: $("#move_page_id").val(), new_parent: $("#parent_id").val(), new_big_brother: $('#section_order').val(), ybr_loggedin: sessiontoken}, function (data) {
                        data = $.trim(data);
                        if (data !== "done")
                        {
                            uiAlert(data);
                        }
                        else
                        {
                            lockToolbar();
                            loadPagesTable();
                        }
                    });
                    $(this).dialog('close');
                }
            }
        ]
    });




    // Nestable collapse/expand all buttons
    $('.toggleAll').on('click', function (e)
    {
        e.stopPropagation();
        var target = $(e.target),
                action = target.data('action');

        if (action === 'expand-all')
        {
            $('#pages').nestable('expandAll');
        }
        if (action === 'collapse-all')
        {
            $('#pages').nestable('collapseAll');
        }
    });

}); // End document.ready()