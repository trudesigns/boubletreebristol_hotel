var homepage_id = 1; // the page id of the homepage
var order, original_order, order_isDirty = false;
var menu_id; // value is set by PHP in calling view
var sessiontoken = $.cookie('ybr_loggedin');

//function lockToolbar()
//{
//    $(".ui-icon-wrench, .ui-icon-document, .ui-icon-trash, .ui-icon-copy, .ui-icon-shuffle").off('click');
//    $("#yb-wrap").append($("#page_toolbar").fadeOut(0)); // move the toolbar out of the hovered div before reloading the list
//    $(".pages_menu_item").unbind('hover'); // stop the hover effect while the div is reloading
//}



function saveorder()
{
    // note the var "order" is set in the triggerNestable() function on change

//    $.ajax({
//        type: "post",
//        url: XHR_PATH + 'orderPage',
//        data: {ybr_loggedin: sessiontoken, menu_id: menu_id, list_order: order},
//        dataType: "json",
//        success: function (data) {
//            data = $.trim(data);
//            if (data !== "true")
//            {
//                uiAlert(data);
//                return false;
//            }
//            else
//            {
//                order_isDirty = false;
//                original_order = $('#pages').nestable('serialize'); // update original order to be the new order
//                $("#save-order").fadeOut('fast');
//                compare_cache();
//            }
//        }
//    });


//	$.post(XHR_PATH+'orderPages/',{ menu_id: menu_id, list_order: order }, function(data){
//		data = $.trim(data);
//		if(data !== "true")
//		{
//			uiAlert(data);
//			return false;
//		}
//		else
//		{
//			order_isDirty = false;
//			original_order = $('#pages').nestable('serialize'); // update original order to be the new order
//			$("#save-order").fadeOut('fast'); 
//			compare_cache(); 
//		}
//	});
}

function publish()
{
    if (order_isDirty)
    {
        uiConfirm({message: "There are unsaved changes to the order of pages.<br><br>Are you sure you want to publish with the old order?",
            title: "Confirm Refresh",
            callback: function (response) {
                if (response)
                {
                    order_isDirty = false;
                    $("#save-order").fadeOut('fast');
                    publish();
                }
                else
                {
                    return false;
                }
            }
        });
        return false;
    }
    $.ajax({
        type: "post",
        url: XHR_PATH + 'publishMenu',
        data: {ybr_loggedin: sessiontoken, menu_id: menu_id},
        dataType: "json",
        success: function (data) {
            data = $.trim(data);
            if (data !== "true")
            {
                uiAlert(data);
            }
            else
            {
                compare_cache();
                uiAlert("Menu has been published");
            }
        }
    });


//	$.get(XHR_PATH+'publishMenu',{menu_id: menu_id}, function(data){
//		data = $.trim(data);
//		if(data !== "true")
//		{
//			uiAlert(data);
//		}
//		else
//		{
//			compare_cache();
//			uiAlert("Menu has been published");
//		}
//	});	
}

//function initiateToolbar(toolbar_hit_zone)
//{
//    toolbar_hit_zone = (!toolbar_hit_zone) ? ".pages_menu_item" : toolbar_hit_zone;
//
//    //add tool bar on hover and bind functionality to its buttons
//    $(toolbar_hit_zone).hover(function () {
//        var item_id = $(this).attr('data');
//        var page_name = $("span", this).html();
//
//        $(this).append($("#page_toolbar"));
//        //bind "Edit Page" pop-up box
//        $(".ui-icon-wrench").on('click', function (e) {
//            e.stopPropagation();
//            editPage(item_id)
//        });
//
//        /*
//         //bind "Move Page" link
//         $(".ui-icon-shuffle").on('click', function(e)
//         {
//         e.stopPropagation();
//         movePage(item_id);				
//         });
//         
//         //bind "Add Supbage" function
//         $(".ui-icon-copy").on('click', function(e){ e.stopPropagation(); addPage(item_id) });
//         */
//
//        //bind "Remove Project" delete action
//        $(".ui-icon-trash").on('click', function (e) {
//            e.stopPropagation();
//
//            uiConfirm({message: "Are you sure you want to REMOVE the page \"" + page_name + "\" from the menu?",
//                title: "Confirm Page Deletion",
//                callback: function (response) {
//                    if (!response)
//                    {
//                        return false;
//                    }
//                    else
//                    {
//                        $.get(XHR_PATH + 'deletePage/', {menu_id: menu_id, page_id: item_id}, function (data) {
//                            if ($.trim(data) !== "done") {
//                                uiAlert(data);
//                            }
//                            else
//                            {
//                                var this_li = $("#pageID_" + item_id).fadeOut('fast');
//                                lockToolbar();
//                                compare_cache();
//                                setTimeout(function () {
//                                    this_li.remove()
//                                }, 500);
//                            }
//                        });
//
//                    }
//                    return response;
//                }
//            });
//        });
//
//        $("#page_toolbar").fadeIn(0);
//    }, function () {
//        $(".ui-icon-wrench, .ui-icon-document, .ui-icon-trash, .ui-icon-copy, .ui-icon-shuffle").off('click');
//        $('body').append($("#page_toolbar"));
//        $("#page_toolbar").fadeOut(0);
//    });
//}





//function loadPagesTable(expanded)
//{
//    if (order_isDirty)
//    {
//        uiConfirm({message: "There are unpublished changes to the order of pages.<br><br>Are you sure you want to refresh the list without saving?",
//            title: "Confirm Refresh",
//            callback: function (response) {
//                if (response)
//                {
//                    order_isDirty = false;
//                    $("#save-order").fadeOut('fast');
//                    loadPagesTable(expanded);
//                }
//                else
//                {
//                    return false;
//                }
//            }
//        });
//        return false;
//    }
//
//    lockToolbar(); // make sure the toolbar icon set is not in the #page area that is about to be replaced.
//    $('#pages').html('loading...');
//
////    console.log("ybr_loggedin: " +);
//    $.ajax({
//        type:"post",
//        url:XHR_PATH + 'pages',
//        data:{ybr_loggedin: sessiontoken},
//        dataType:"json",
//        success:function(data){
//       //     console.log("hshshshs");
////console.log(data);
//             var html = "<ul class=\"dd-list\">\n";
//             $.each(data, function (index, v) {
//                    //console.log("CHILDREN");
//                     if (v.page.id === homepage_id)
//                    {
//                        html += '<li class="dd-item" id="pageID_' +v.page.id + '" data-id="' + v.page.id + '"><div class="dd-hand-no-drag pages_menu_item" data="' + v.page.id + '" data-subpages="' + v.children_role + '"><span class="dd-no-move" title="' + v.page.slug + '">' + v.page.label + '</span></div>';
//                    }
//                    else
//                    {
//                        html += '<li class="dd-item" id="pageID_' + v.page.id+ '" data-id="' + v.page.id + '"><div class="dd-handle pages_menu_item" data="' + v.page.id + '" data-subpages="' + v.children_role + '"><span class="dd-no-move" title="' + v.page.slug + '">' + v.page.label + '</span></div>';
//                    }
//              
//              
////                    if (v.thisID == homepage_id)
////                    {
////                        html += '<li class="dd-item" id="pageID_' + v.thisID + '" data-id="' + v.thisID + '"><div class="dd-hand-no-drag pages_menu_item" data="' + v.thisID + '" data-subpages="' + v.add_children_role + '"><span class="dd-no-move" title="' + v.uri + '">' +v.label + '</span></div>';
////
////                    }
////                    else
////                    {
////                        html += '<li class="dd-item" id="pageID_' + v.thisID + '" data-id="' + v.thisID + '"><div class="dd-handle pages_menu_item" data="' + v.thisID + '" data-subpages="' + v.add_children_role + '"><span class="dd-no-move" title="' + v.uri + '">' + v.label + '</span></div>';
////                    }
//                    
////                    if (v.children > 0)
////                    {
//////                        pages = v.child_pages;
//////                        html = myfunction(); // run this again, recursively 
////                            html += "<ul class=\"dd-list\">\n";
////                            html +=  loadSubPage(v.child_pages);
////                              html +="</ul>"
////                    }
//
//                        if(typeof v.page.children !== undefined){
//                             html += "<ul class=\"dd-list\">\n";
//                             html +=  loadSubPage(v.page.children);
//                             html +="</ul>"
//                        }
//                     //html +=  loadSubPage(v.page.children);
//                  
//                 
//                     html += "</li>\n";
//                });
//               html +="</ul>"
//             $('#pages').html(html);
//              initiateToolbar(); // set the hover state for the toolbar 
//                triggerNestable(); // make the content moveable with "Nestable" plugin.
//
////                if (location.hash)
////                {
////                    var preselected_page_id = location.hash.substr(1);
////                    editPage(preselected_page_id);
////                }
//        }
//    });
//    //XHR_PATH + 'allPages', {ybr_loggedin: sessiontoken}, function (data) {
// //console.log(data);
//      //  var html = "";
//        
//           
//            //html += "<ul class=\"dd-list\">\n";
//            //$.each(data, function (index, v) {
////console.log(v);
////                if (pagedata.thisID == homepage_id)
////                {
////                    html += '<li class="dd-item" id="pageID_' + pagedata.thisID + '" data-id="' + pagedata.thisID + '"><div class="dd-hand-no-drag pages_menu_item" data="' + pagedata.thisID + '" data-subpages="' + pagedata.add_children_role + '"><span class="dd-no-move" title="' + pagedata.uri + '">' + pagedata.label + '</span></div>';
////
////                }
////                else
////                {
////                    html += '<li class="dd-item" id="pageID_' + pagedata.thisID + '" data-id="' + pagedata.thisID + '"><div class="dd-handle pages_menu_item" data="' + pagedata.thisID + '" data-subpages="' + pagedata.add_children_role + '"><span class="dd-no-move" title="' + pagedata.uri + '">' + pagedata.label + '</span></div>';
////                }
////
////                if (pagedata.children > 0)
////                {
////                    pages = pagedata.child_pages;
////                    html = myfunction(); // run this again, recursively 
////                }
////                html += "</li>\n";
//
//           // });
////            html += "</ul>\n";
////            return html;
//      // };
//
//   //     $('#pages').html(f); // populate the #pages div with the generated content
////        initiateToolbar(); // set the hover state for the toolbar 
////        triggerNestable(); // make the content moveable with "Nestable" plugin.
////
////        if (location.hash)
////        {
////            var preselected_page_id = location.hash.substr(1);
////            editPage(preselected_page_id);
////        }
//
//    //});
//}

/*
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
    $('#pages').html('loading...');
    $.ajax({
        type: "post",
        url: XHR_PATH + 'menus',
        data: {ybr_loggedin: sessiontoken, menu_id: $('#thisMenu').val()},
        dataType: "json",
        success: function (data) {

            var html = "<ul class=\"dd-list\">\n";
            $.each(data, function (index, v) {
                //console.log("CHILDREN");
                if (v.page.id === homepage_id)
                {
                    html += '<li class="dd-item" id="pageID_' + v.page.id + '" data-id="' + v.page.id + '"><div class="dd-hand-no-drag pages_menu_item" data="' + v.page.id + '" data-subpages="' + v.children_role + '"><span class="dd-no-move" title="' + v.page.slug + '">' + v.page.label + '</span></div>';
                }
                else
                {
                    html += '<li class="dd-item" id="pageID_' + v.page.id + '" data-id="' + v.page.id + '"><div class="dd-handle pages_menu_item" data="' + v.page.id + '" data-subpages="' + v.children_role + '"><span class="dd-no-move" title="' + v.page.slug + '">' + v.page.label + '</span></div>';
                }
                if (typeof v.page.children !== undefined) {
                    html += "<ul class=\"dd-list\">\n";
                    html += loadSubPage(v.page.children);
                    html += "</ul>"
                }
                html += "</li>\n";
            });
            html += "</ul>"

            $('#pages').html(html);
            initiateToolbar(); // set the hover state for the toolbar 
            triggerNestable(); // make the content moveable with "Nestable" plugin.


        }
    });


//	$.getJSON( XHR_PATH+'allPages',{menu: menu_id },function(data){	
//		
//		var html="", pages=data, f = function myfunction()
//		{
//			html+= "<ul class=\"dd-list\">\n"		
//			$.each(pages,function(index,pagedata) {
//				
//				var label_sans_html = pagedata.label.replace(/(<([^>]+)>)/ig,"");
//				if( pagedata.label.length > label_sans_html.length)
//				{
//					pagedata.label = label_sans_html +" <span class='ui-icon ui-icon-script tooltip' style='display: inline-block' title='label includes HTML (not shown here)'></span>";
//				}
//				
//				var label = (pagedata.link_type == "pages_id" && pagedata.label == "") ? pagedata.page_label : pagedata.label ;
//				var linktypespan = (pagedata.link_type == "pages_id") ? '' : "<span class='ui-icon ui-icon-link tooltip' style='display: inline-block' title='Custom URL Link'></span>";
//					label = (pagedata.link_type == "pages_id" && pagedata.label != "") ? label+"<span class='ui-icon ui-icon-gear tooltip' style='display: inline-block' title='Menu label is different from the page label'></span>" : label;
//				html+= '<li class="dd-item" id="pageID_'+ pagedata.thisID +'" data-id="'+ pagedata.thisID +'"><div class="dd-handle pages_menu_item" data="'+ pagedata.thisID +'" data-subpages="'+ pagedata.add_children_role +'"><span class="dd-no-move" title="'+ pagedata.page_label +'">'+linktypespan + label +'</span></div>';		
//			
//				if( pagedata.children > 0)
//				{
//					pages = pagedata.child_pages;
//					html = myfunction(); // run this again, recursively 
//				}
//				html+= "</li>\n";
//				
//			});
//			html+= "</ul>\n";
//						
//			return (data !== false) ? html : '<h3>Menu has no pages yet</h3>';				
//		};	
//		
//		$('#pages').html( f ); // populate the #pages div with the generated content
//		initiateToolbar(); // set the hover state for the toolbar 
//		triggerNestable(); // make the content moveable with "Nestable" plugin.
//	
//		compare_cache();
//	
//		$(".tooltip").tooltip();
//		
//	});
}
*/
function compare_cache()
{
    $.get(XHR_PATH + 'menuCheckCache', {menu_id: menu_id}, function (data)
    {
        if ($.trim(data) == "current")
        {
            $("#cache_warning").fadeOut('fast');
        }
        else
        {
            $("#cache_warning").fadeIn('fast');
        }

        console.log(data);
    });
}

/**
 * Add page(s) to the menu_pages table and update DOM with new pages
 * 
 */
//function createPages(include_children, parent_id)
//{
//    var page_id = $("#add_to_menu").val();
//
//    if (page_id == "")
//    {
//        uiAlert("Select a page to add");
//        return false;
//    }
//
//    parent_id = (parent_id !== undefined && parent_id !== "") ? parent_id : 0;
//
//    $.getJSON(
//            XHR_PATH + 'menuAddPages',
//            {
//                menu_id: menu_id, page_id: page_id, parent_id: parent_id, add_children: include_children
//            },
//    // "menuAddPages" will pull the data abaout the requested page (and its children if requsted)
//    // then save that data to the menu_pages table 
//    // returns a pages object of data about the given page (and its children if requested)
//    function (pagedata) {
//        loadPagesTable();
//    }//end of ajax callback(data) function
//
//    ); //end of getJSON() 
//}



//function editPage(item_id) { // load the data to be put in the pop-up
//    $.getJSON(XHR_PATH + 'getMenuPageProperties', {page_id: item_id}, function (data) {
//
//        if (!data || $.trim(data) == "false" || data.id == null)
//        {
//            uiAlert("Internal Error:  Invalid Page ID or Page Not Found");
//            return;
//        }
//
//        $("#link_id").val(item_id);
//
//        if (data.link_type === "pages_id")
//        {
//            $("#link_value_label").html('Page ID: ');
//            $("#link_value_field").html('<input type="hidden" id="link_value" value="' + data.link_value + '"><a href="/admin/pages/#' + data.link_value + '">' + data.link_value + '</a>');
//            $("#label_description").html('Leave blank to inherit the <a href="/admin/pages/#' + data.link_value + '">page\'s label</a> (recommended)');
//        }
//        else
//        {
//            $("#link_value_label").html('URL: ');
//            $("#link_value_field").html('<input type="text" id="link_value" size="40" value="' + data.link_value + '">');
//            $("#label_description").html('');
//        }
//
//        $("#label").val(data.label);
//        $("#menu_edit_item_target").val(data.target);
//        $("#link_attributes").val(data.link_attributes);
//
//        $("#editPageDialog").dialog('open');
//        $(".tooltip").tooltip();
//    });
//}

function editNewPage()
{
    $("#link_id").val('false');
    $("#link_value_label").html('URL: ');
    $("#link_value_field").html('<input type="text" id="link_value" size="40" value="http://">');
    $("#label_description").html('');
    $("#label").val('New Link');
    $("#menu_edit_item_target").val('');
    $("#link_attributes").val('');
    $("#editPageDialog").dialog('open');
    $(".tooltip").tooltip();
}

function pageData() {
    return {
        menu_id: menu_id,
        page_id: $("#link_id").val(),
        link_value: $("#link_value").val(),
        label: $("#label").val(),
        target: $("#menu_edit_item_target").val(),
        link_attributes: $("#link_attributes").val()
    }
}

//function drawSiblingSelect(parent_id, item_id)
//{
//    $.getJSON(XHR_PATH + 'allPages', {parent_id: parent_id, menu_id: menu_id}, function (siblings) {
//
//        var html = (parent_id == 0) ? "" : "<option value=\"0\">Parent (first page in section)</option>",
//                previous_item = 0,
//                f2 = function myfunction(mydelimiter)
//                {
//                    $.each(siblings, function (index, pagedata2)
//                    {
//
//                        if (pagedata2.thisID === item_id)
//                        {
//                            previous_item = index - 1;
//                            //	alert("older sibling: "+ pages2[previous_item].label);
//                        }
//
//                        html += '\n\t<option value="' + pagedata2.thisID + '">' + pagedata2.label + '</option>';
//                    });
//                    return html;
//                };
//        $('#section_order').html(f2).select2();
//
//        if (parent_id == 0 && siblings[previous_item] === undefined)
//        {
//            var selected = homepage_id;
//        }
//        else
//        {
//            var selected = (siblings[previous_item] !== undefined) ? siblings[previous_item].thisID : 0;
//        }
//
//        $('#section_order').select2("val", selected)
//
//    });
//}

//function movePage(item_id)
//{
//    if (item_id == homepage_id)
//    {
//        uiAlert("You can not move the homepage");
//        return false;
//    }
//
//    $("#move_page_id").val(item_id);
//
//    // 1.	Get page data of given page to move
//    $.getJSON(XHR_PATH + 'getPageProperties/', {page_id: item_id}, function (data) {
//
//        $("#move_page_name").html(data.label);
//
//        // 2.	Draw a <select> menu of all pages on this site
//        //		except for this page and its children
//        $.getJSON(XHR_PATH + 'allPages/', {exclude: item_id, menu_id: menu_id}, function (site_map) {
//            $('#parent_id').html('<select disabled>Loading...</select>');
//
//            var html = "", pages = site_map, mydelimiter = "x", f = function myfunction(mydelimiter)
//            {
//                if (mydelimiter === 0) {
//                    mydelimiter = "";
//                }
//
//                $.each(pages, function (index, pagedata)
//                {
//                    var selected = (pagedata.thisID === data.parent_id) ? " SELECTED" : "";
//                    html += '\n\t<option value="' + pagedata.thisID + '"' + selected + '>' + mydelimiter + ' ' + pagedata.label + '</option>';
//
//                    if (pagedata.children > 0)
//                    {
//                        pages = pagedata.child_pages;
//
//                        html = myfunction(mydelimiter + " -"); // run this again, recursively 
//                    }
//                });
//                return html;
//            };
//
//            $('#parent_id').html(f).on('change', function () {
//                // on change, redraw the sub <select> menu of child pages in the new parent.
//                var selected_parent = ($(this).val() == homepage_id) ? 0 : $(this).val();
//                drawSiblingSelect(selected_parent, item_id);
//            }).select2();
//
//            // 3.	Draw the sub <select> menu of child pages with the given parent
//            drawSiblingSelect(data.parent_id, item_id);
//
//            // 4.	Open the dialog box				
//            $("#movePageDialog").dialog('open');
//            $(".tooltip").tooltip();
//
//        });	// end getJSON "allPages" to draw the <select> of the whole site
//
//    });// end getJSON "getPageProperties"
//
//}

//function drawPagesSelect() {
//
//    $.getJSON(XHR_PATH + 'allPages/', function (site_map)
//    {
//        $('#add_to_menu').html('<select disabled>Loading...</select>');
//
//        var html = "", pages = site_map, mydelimiter = "x", f = function myfunction(mydelimiter)
//        {
//            if (mydelimiter === 0) {
//                mydelimiter = "";
//            }
//
//            $.each(pages, function (index, pagedata)
//            {
//                //var selected = ( pagedata.thisID === data.parent_id) ? " SELECTED" : "";
//                //html+= '\n\t<option value="'+ pagedata.thisID +'"'+selected+'>'+ mydelimiter +' '+ pagedata.label +'</option>';
//                html += '\n\t<option value="' + pagedata.thisID + '">' + mydelimiter + ' ' + pagedata.label + '</option>';
//
//                if (pagedata.children > 0)
//                {
//                    pages = pagedata.child_pages;
//
//                    html = myfunction(mydelimiter + " -"); // run this again, recursively 
//                }
//            });
//
//            return html;
//        };
//
//        $('#add_to_menu').html(f); // populate the #add_to_menu select with the generated content	
//    });
//
//
//}

/*
 * refresh the page, even if order_isDirty is true
 */
function dirty_refresh()
{
    order_isDirty = false;
    location.reload(true);
}

//$(window).bind('beforeunload', function () {
//    var message = "There are unpublished changes to the order of pages.";
//    if (order_isDirty) {
//        return message;
//    }
//});

//function triggerNestable()
//{
//    $('#pages')
//            .nestable({
//                listNodeName: 'ul',
//                maxDepth: 5,
//                rootClass: 'pages',
//                noDragClass: 'dd-no-move',
//                collapsedClass: 'contentContainer'
//            })
//            .on('change', function () {
//                
//                $.ajax({
//                    type: "post",
//                    url: XHR_PATH + 'orderMenuPages',
//                    data: {ybr_loggedin: sessiontoken, menu_id: menu_id, list_order: $('#pages').nestable('serialize')},
//                    //dataType: "json",
//                    success: function (data) {
//                        $('#publish_menu').removeClass('hide');
//                            var menu_id = $('#thisMenu').val();
//                            $('#thisMenu').val(menu_id).trigger("change");
//                        
//                    }
//                });
//                
////                 
//            })
//            .disableSelection();
//}

function sitemap() {
    uiConfirm({title: "Confirm Menu Replacement",
        message: "Are you sure you want to add the entire sitemap to this menu?",
        callback: function ($ok) {
            if ($ok) {
                $.getJSON(XHR_PATH + 'menuAddAllPages', {menu_id: menu_id}, function (data) {
                    loadPagesTable();
                });
            }
        }
    });
}

function loadSubPage(child)
{
    var html = "";
    // console.log("CHILD: "+child);
    if (child !== null) {
         var edit_tool = $('#menus_toolbar').children('#menu-edit').html();
              
          var rem_tool = $('#menus_toolbar').children('#menu-remove').html();
               //console.log(rem_tool);
        $.each(child, function (idx, c) {
            //var toolbar = $('#menus_toolbar').html();
              
         // console.log(c.menu.children);      
            //console.log(c);
            html += '<li class="dd-item" id="pageID_' + c.menu.id + '" data-menu-item="' + c.menu.id + '" data-menu-id="' + c.menu.menu_id + '">';
             html += "<div class='menu-item'>";
            html += '<div class="dd-handle pages_menu_item" data-menu-item="' + c.menu.id + '" data-menu-id="' + c.menu.menu_id + '" data-subpages="' + c.menu.children_role + '">';
          if(c.pages !== null){
                html += '<span class="dd-no-move" title="' + c.pages.page.slug + '">' + c.pages.page.label + '</span>';
          } else {
               html += '<span class="dd-no-move" title="' + c.menu.link_value + '">' + c.menu.label + '</span>';
          }
            html += "</div>";
            html += "<div class='yb-toolbar hide'>";
            html+= edit_tool;
            if (c.menu.children === null) {
             html += rem_tool;   
            }
             html += "</div>";
              html += "</div>";
           
            if (typeof c.menu.children !== undefined && c.menu.children !== null) {
                html += "<ul class=\"dd-list\">\n";
                html += loadSubPage(c.menu.children);
                html += "</ul>"
            }
            html += "</li>";

        });
        return html;
    }
    return "";
}


function loadSubMenus(child,iteration)
{
    
     if (child !== null) {
         var html ="";
        $.each(child, function (idx, c) {
            var dash="";
            //console.log(iteration +" "+c.page.label);
            for(var i=0;i<iteration;i++){
                dash += " - "
            }
            if(iteration==="1"){
                dash = " - ";
            }
            html += "<option value='"+c.page.id+"'>"+dash+" " +c.page.label+"</option>";
            if (typeof c.page.children !== undefined) {
                    html +=loadSubMenus(c.page.children,iteration+1);
            }
        });
        return html;
    }
    return null;
        
}

$(document).ready(function () {
    var menu_id = $("#selected_menu").val(); // value is set by PHP in calling view
    
    
    
    
    
    $('#thisMenu').on({
        change:function(){
            var id = $(this).val();
            if(id !== ""){
             
                //PAGES ALREADY SELECTED IF ANY
                $.ajax({
                    type:"post",
                    url:XHR_PATH + 'pages_menu',
                    data:{ybr_loggedin: sessiontoken,menu_id:id},
                    dataType:"json",
                    success:function(data){
                            var edit_tool = $('#menus_toolbar').children('#menu-edit').html();
                            var rem_tool = $('#menus_toolbar').children('#menu-remove').html();
                            // console.log("DATA: "+typeof data);
                            //console.log(data);
                            if(data !== null){
                                 var html = "<ul class=\"dd-list\">\n";
                               $.each(data, function (index, v) {
                           //console.log(v.pages);
                                   html += '<li class="dd-item" id="pageID_' + v.menu.id + '" data-menu-item="' + v.menu.id + '" data-menu-id="' + v.menu.menu_id + '">';
                                  html += "<div class='menu-item'>";
                                  if(v.pages !== null){
                                        html+= '<div class="dd-handle pages_menu_item" data-menu-item="' + v.menu.id + '" data-menu-id="' + v.menu.menu_id + '" data-subpages="' + v.pages.page.children_role + '">';
                                           html += '<span class="dd-no-move" title="' + v.pages.page.slug + '">' + v.pages.page.label + '</span>';
                                        html += "</div>";
                                    } else {
                                        html+= '<div class="dd-handle pages_menu_item" data-menu-item="' + v.menu.id + '" data-menu-id="' + v.menu.menu_id + '" data-subpages="">';
                                           html += '<span class="dd-no-move" title="' + v.menu.link_value + '">' + v.menu.label + '</span>';
                                        html += "</div>";
                                    }
                                   html += "<div class='yb-toolbar hide'>";
                                   html+= edit_tool;
                                   if ( v.menu.children === null) {
                                    html += rem_tool;   
                                   }

                                     html += "</div>";
                                     html += "</div>";
                               if (typeof v.menu.children !== undefined && v.menu.children !== null) {
                                   html += "<ul class=\"dd-list\">\n";
                                   html += loadSubPage(v.menu.children);
                                   html += "</ul>";
                               }
                               html += "</li>\n";
                           });
                               html += "</ul>";
                               $('#pages').html(html);
                            } else{
                                $("#pages").html("<p>No Menu Item currently defined</p>")
                            }
                        
                        }

                });

                //MENUS of LIST OF PAGES FROM SITE TO PICK FROM 
                 $.ajax({
                    type:"post",
                    url:XHR_PATH + 'pages',
                    data:{ybr_loggedin: sessiontoken,menu_id:id},
                    dataType:"json",
                    success:function(data){
                         // console.log(data);
                         var html = "";
                        $.each(data, function (index, v) {
                            //  console.log(v);
                            html += "<option value='"+v.page.id+"'>"+v.page.label+"</option>";
                            if (typeof v.page.children !== undefined) {
                                html += loadSubMenus(v.page.children,1);
                            }
                        });
                        $('#add_to_menu').html(html);
                        }
                });

                $('.hide-on-load').removeClass('hide-on-load');
                 triggerNestable(); // make the content moveable with "Nestable" plugin.
                 
            } else {
                
                 $('#menu_tools').addClass('hide-on-load');
                 $('#pages').empty();
            }
        
            
        }
    });
    
    $('#app-wrapper').on('click','.add_tree_page',function(event){
           // alert("shshshshs");
                var page_id = $("#add_to_menu").val();
                var menu_id = $('#thisMenu').val();
                if (page_id === ""){
                    uiAlert("Select a page to add");
                    return false;
                }

                //console.log($(this).data('children'));
                var parent_id = (parent_id !== undefined && parent_id !== "") ? parent_id : 0;
                 $.ajax({
                        type:"post",
                        url:XHR_PATH + 'menuAddPages',
                        data:{
                            ybr_loggedin: sessiontoken
                            , menu_id: menu_id
                            , page_id: page_id
                            , parent_id: parent_id
                            , add_children:$(this).data('children')
                        },
                        //dataType:"json",
                        success:function(data){
                     
                                  //  console.log(data);
                                     $('#thisMenu').val(menu_id).trigger("change");
                                  
                        }
                 });
            event.preventDefault();
        
    });
    
    $('#app-wrapper').on('click','.add_custom',function(event){
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
      
    $('#app-wrapper').on('click','.add_sitemap',function(event){
         var menu_id = $('#thisMenu').val();
        var conf = confirm("Are you sure you want tot add the entiresitemap to this menu?");
        if(conf){
             $.ajax({
                type:"post",
                url:XHR_PATH + 'menuAllPages',
                data: {menu_id: menu_id,ybr_loggedin:sessiontoken},
                //dataType:"json",
                success:function(data){
                    
                       $('#thisMenu').val(menu_id).trigger("change");
                }
            });
        }
        event.preventDefault();
    });
      
    $('#app-wrapper').on('mouseenter','.menu-item',function(){
       // alert("sjdhdhdh");
        var o = $(this).children('.yb-toolbar');
        o.removeClass('hide');
        
    });
    
    $('#app-wrapper').on('mouseleave','.menu-item',function(){
       // alert("sjdhdhdh");
        var o = $(this).children('.yb-toolbar');
        o.addClass('hide');
        
    });
    
    $('#app-wrapper').on('click','.menu-remove',function(event){
       // alert("sjdhdhdh");
        var o = $(this);
        var par = o.parents('.dd-item').first();
        var gp = par.parents('.dd-item').first();
       var conf = confirm("Are you sure you want to REMOVE  the page "+ par.find('.dd-handle span').text()+" from the menu?");
       if(conf){
       
            var item_id = par.data('menu-item');
            //console.log(item_id);
           //  o.toggleClass('hide');
              $.ajax({
                     type:"post",
                     url:XHR_PATH + 'delete_menu_item',
                     data:{ybr_loggedin: sessiontoken,item_id:item_id},
                     //dataType:"json",
                     success:function(){
                        // console.log(par.length);


                        var child_cnt = par.parents('.dd-item').children('.dd-item').length;
                        //console.log("CNT: "+child_cnt);
                        if(child_cnt  <1 ){
                            //console.log("CNTBBB: "+gp.find('.menu-remove').length);
                            if(gp.find('.yb-toolbar .menu-remove').length <2){
                                 var rem = $('#menus_toolbar').children('#menu-remove').html();
                                 gp.find('.yb-toolbar .menu-edit').after(rem);
                             }
                        }

                         par.remove();//remove the html
                         $('#publish_menu').removeClass('hide');
                     }
                 });
        }
        event.preventDefault();
    });
    
    $('#app-wrapper').on('click','.menu-edit',function(event){
         
         var o = $(this);
         var item = o.parents('.menu-item').children('.dd-handle');
         var parent_id = o.parents('.menu-item').parent().parents('.dd-item').first().data('menu-item');
         var item_id = item.data('menu-item');
          var menu_id = item.data('menu-id');
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
                success:function(data){
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
                }
              });
          
          
          
          $('#custom_url').modal();//the modal needs help to actully get triggered 
          event.preventDefault();
      });
      
    $('#app-wrapper').on('submit','#custom_url_form',function(event){
       
          $.ajax({
                type:"post",
                url:XHR_PATH + 'custom_url',
                data:$( this ).serialize()+"&ybr_loggedin="+sessiontoken,
                dataType:"json",
                success:function(data){
                 $('#custom_url').modal('hide');
                 $('#publish_menu').removeClass('hide');
                 var menu_id = $('#thisMenu').val();
                 $('#thisMenu').val(menu_id).trigger("change");
                }
            });
         
         event.preventDefault();
     });
    
    
//    $("#custom_url").validate({
//            ignore: '.ignore',
//            onkeyup:false,//every time the data gets changed we re-validate
//            onfocusout: function(element) {
//                 $(element).valid();
//            },
//              rules: {
//                link_value:{ 
//                    required:true
//                    ,url:true
//                },
//               // label:"required",
//               
//            },
//            messages: {
//                link_value: {
//                   required: 'The URL is required.'
//                    ,url:'The URL provided is invalid.'
//                },
//                label:{
//                    required:'The First Name field is required.'
//                },
//            }
//        });
    
    
    
    
    
    
    
    //loadPagesTable();
  // drawPagesSelect();

//    $("#editPageDialog").dialog({
//        width: 650,
//        height: 'auto',
//        modal: true,
//        autoOpen: false,
//        resizable: false,
//        buttons: [
//            {
//                text: "Cancel",
//                click: function () {
//                    $(this).dialog('close');
//                }
//            },
//            {
//                text: "Save Changes >",
//                click: function () {
//                    var sendData = pageData();
//                    $.post(XHR_PATH + 'saveMenuPageProperties/', sendData, function (data) {
//                        data = $.trim(data);
//                        if (data !== "done")
//                        {
//                            uiAlert(data);
//                        }
//                        else
//                        {
//                            loadPagesTable();
//                        }
//                    });
//                    $(this).dialog('close');
//                }
//            }
//        ]
//    });

//    $("#movePageDialog").dialog({
//        width: 650,
//        height: 'auto',
//        modal: true,
//        autoOpen: false,
//        resizable: false,
//        buttons: [
//            {
//                text: "Cancel",
//                click: function () {
//                    $(this).dialog('close');
//                }
//            },
//            {
//                text: "Save Changes >",
//                click: function () {
//
//                    $.get(XHR_PATH + 'orderPageManually/', {page_id: $("#move_page_id").val(), new_parent: $("#parent_id").val(), new_big_brother: $('#section_order').val()}, function (data) {
//                        data = $.trim(data);
//                        if (data !== "done")
//                        {
//                            uiAlert(data);
//                        }
//                        else
//                        {
//                            lockToolbar();
//                            loadPagesTable();
//                        }
//                    });
//                    $(this).dialog('close');
//                }
//            }
//        ]
//    });

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