// Yellow Brick Helper Functions to Make Life Easier

/**
 * apply a Charecter Count Limit to a given form field
 *
 * example:
 *		<textarea id="myTextarea" class="lengthcount" maxlength="150"></textarea>]
 *
 *		<script>
 *		$(".lengthcount").each(function(){
 *			charCount( $(this));
 *			$(this).keyup( function(){ charCount( $(this) ) } )
 *		});
 *		</script>
 */
function charCount(element)
{
    var current_len = element.val().length,
            max_len = element.attr("maxlength"),
            content = element.val(),
            label = element.attr("id") + "_count_label";

    if (current_len > max_len)
    {
        element.val(content.substring(0, max_len));
        current_len = max_len;
    }

    if ($("#" + label).length == 0)
    {
        element.parent().append("<div id=\"" + label + "\"></div>");
    }

    $("#" + label).html(current_len + " of " + max_len + " characters.");
}

/**
 * display a styled jQuery UI dialog box in place of the native javascript alert()
 *
 * message	string	HTML message to display
 * title	string	optional title text to display in header of confirmation box
 * callback	function optional function to trigger when user clicks "OK"
 *
 */
function uiAlert(settings)
{
    if (typeof (settings) == "string")
    {
        settings = {message: settings};
    }
    var settings = $.extend({
        'message': '',
        'title': '',
        'width': '450px',
        'height': 'auto',
        'modal': true,
        'id': 'uiAlert',
        'resizable': false,
        'css': {'text-align': 'left'},
        'callback': function () {
            return true;
        }
    }, settings);
    $('body').append('<div id="' + settings.id + '" title="' + settings.title + '">' + settings.message + '</div>');
    $(function () {
        $("#" + settings.id).dialog({
            modal: settings.modal,
            resizable: settings.resizable,
            width: settings.width,
            height: settings.height,
            close: function () {
                $(this).remove()
            },
            buttons: {
                OK: function () {
                    $(this).dialog("close");
                    var callback = settings.callback;
                    callback();
                }
            }
        }).css(settings.css);
    });
}


/**
 * display a styled jQuery UI dialog box in place of the native javascript confirm() 
 * 
 * message	string		HTML message to display
 * title	string		optional title text to display in header of confirmation box
 * callback function	required function to handle user's response
 *
 */
function uiConfirm(settings)
{
    if (typeof (settings) == "string")
    {
        settings = {message: settings};
    }
    var settings = $.extend({
        'message': '',
        'title': '',
        'width': '450px',
        'height': 'auto',
        'modal': true,
        'id': 'uiConfirm',
        'resizable': false,
        'css': {'text-align': 'left'},
        'callback': function (bool) {
            return bool;
        }
    }, settings);
    $('body').append('<div id="' + settings.id + '" title="' + settings.title + '">' + settings.message + '</div>');
    $(function () {
        $("#" + settings.id).dialog({
            modal: settings.modal,
            resizable: settings.resizable,
            width: settings.width,
            height: settings.height,
            close: function () {
                $(this).remove();
            },
            buttons:
                    {
                        Cancel: function () {
                            $(this).dialog('close');
                            var callback = settings.callback;
                            callback(false);
                        },
                        Ok: function () {
                            $(this).dialog('close');
                            var callback = settings.callback;
                            callback(true);
                        }
                    }
        }).css(settings.css);
    });
}

function uiPrompt(settings)
{
    var settings = $.extend({
        'message': '',
        'title': '',
        'placeholder': '', // HTML5 placeholder text for the input field
        'input': 'text',
        'width': '450px',
        'height': 'auto',
        'modal': true,
        'id': 'uiPrompt',
        'resizable': false,
        'css': {'text-align': 'left'},
        'callback': function (value) {
            return value;
        }
    }, settings);
    $('body').append('<div id="' + settings.id + '" title="' + settings.title + '">' + settings.message + '<br><br><input type="' + settings.input + '" placeholder="' + settings.placeholder + '"></div>');
    $(function () {
        $("#" + settings.id).dialog({
            modal: settings.modal,
            resizable: settings.resizable,
            width: settings.width,
            height: settings.height,
            close: function () {
                $(this).remove();
            },
            buttons:
                    {
                        Cancel: function () {
                            $(this).dialog('close');
                        },
                        Ok: function () {
                            $(this).dialog('close');
                            var callback = settings.callback;
                            callback($('input', this).val());
                        }
                    }
        }).css(settings.css);
    });
}

/**
 * Return a formated string from a date Object mimicking PHP's date() functionality
 *
 * format  string  "Y-m-d H:i:s" or similar PHP-style date format string
 * date    mixed   Date Object, Datestring, or milliseconds 
 *
 */
function dateFormat(format, date) {

    if (!date || date === "")
    {
        date = new Date();
    }
    else if (typeof ('date') !== 'object')
    {
        date = new Date(date.replace(/-/g, "/")); // attempt to convert string to date object	
    }

    var string = '',
            mo = date.getMonth(), // month (0-11)
            m1 = mo + 1;			// month (1-12)
    dow = date.getDay(), // day of week (0-6)
            d = date.getDate(), // day of the month (1-31)
            y = date.getFullYear(), // 1999 or 2003
            h = date.getHours(), // hour (0-23)
            mi = date.getMinutes(), // minute (0-59)
            s = date.getSeconds(); // seconds (0-59)

    for (var i = 0, len = format.length; i < len; i++) {
        switch (format[i])
        {
            case 'j': // Day of the month without leading zeros  (1 to 31)
                string += d;
                break;

            case 'd': // Day of the month, 2 digits with leading zeros (01 to 31)
                string += (d < 10) ? "0" + d : d;
                break;

            case 'l': // (lowercase 'L') A full textual representation of the day of the week
                var days = Array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
                string += days[dow];
                break;

            case 'w': // Numeric representation of the day of the week (0=Sunday,1=Monday,...6=Saturday)
                string += dow;
                break;

            case 'D': // A textual representation of a day, three letters
                days = Array("Sun", "Mon", "Tue", "Wed", "Thr", "Fri", "Sat");
                string += days[dow];
                break;

            case 'm': // Numeric representation of a month, with leading zeros (01 to 12)
                string += (m1 < 10) ? "0" + m1 : m1;
                break;

            case 'n': // Numeric representation of a month, without leading zeros (1 to 12)
                string += m1;
                break;

            case 'F': // A full textual representation of a month, such as January or March 
                var months = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
                string += months[mo];
                break;

            case 'M': // A short textual representation of a month, three letters (Jan - Dec)
                months = Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
                string += months[mo];
                break;

            case 'Y': // A full numeric representation of a year, 4 digits (1999 OR 2003)	
                string += y;
                break;

            case 'y': // A two digit representation of a year (99 OR 03)
                string += y.toString().slice(-2);
                break;

            case 'H': // 24-hour format of an hour with leading zeros (00 to 23)
                string += (h < 10) ? "0" + h : h;
                break;

            case 'g': // 12-hour format of an hour without leading zeros (1 to 12)
                var hour = (h === 0) ? 12 : h;
                string += (hour > 12) ? hour - 12 : hour;
                break;

            case 'h': // 12-hour format of an hour with leading zeros (01 to 12)
                hour = (h === 0) ? 12 : h;
                hour = (hour > 12) ? hour - 12 : hour;
                string += (hour < 10) ? "0" + hour : hour;
                break;

            case 'a': // Lowercase Ante meridiem and Post meridiem (am or pm)
                string += (h < 12) ? "am" : "pm";
                break;

            case 'i': // Minutes with leading zeros (00 to 59)
                string += (mi < 10) ? "0" + mi : mi;
                break;

            case 's': // Seconds, with leading zeros (00 to 59)
                string += (s < 10) ? "0" + s : s;
                break;

            case 'c': // ISO 8601 date (eg: 2012-11-20T18:05:54.944Z)
                string += date.toISOString();
                break;

            default:
                string += format[i];
        }
    }

    return string;
}




function loadSubPage(child)
{
    var html = "";
    // console.log("CHILD: "+child);
    if (child !== null) {
        var edit_tool = $('#pages_toolbar').children('#page-edit').html();
        var prop_tool = $('#pages_toolbar').children('#page-properties').html();
        var move_tool = $('#pages_toolbar').children('#page-move').html();
        var sub_tool = $('#pages_toolbar').children('#page-sub').html();
        var rem_tool = $('#pages_toolbar').children('#page-remove').clone();

        $.each(child, function (index, v) {
            //console.log(v.pages);
            html += '<li class="dd-item" id="pageID_' + v.page.id + '" data-page-item="' + v.page.id + '"  data-id="' + v.page.id + '">';
            html += "<div class='page-item'>";


            html += '<div class="dd-handle pages_menu_item" data="' + v.page.id + '" data-page-item="' + v.page.id + '" data-subpages="' + v.page.children_role + '">';

            html += '<span class="" title="' + v.page.slug + '">' + v.page.label + '</span>';

            html += "</div>";


            html += "<div class='yb-toolbar hide'>";
            html += edit_tool;
            html += prop_tool;
            html += move_tool;
            html += sub_tool;
            if (v.page.children !== null) {
                $(rem_tool).find('.menu-remove').addClass('hide');
            } else {
                $(rem_tool).find('.menu-remove').removeClass('hide');
            }
            html += $(rem_tool).html();
            html += "</div>";//END .yb-toolbar

            html += "</div>";//end .menu-item
            if (typeof v.page.children !== undefined && v.page.children !== null) {
                html += "<ul class='dd-list'>\n";
                html += loadSubPage(v.page.children);
                html += "</ul>";
            }
            html += "</li>\n";
        });
        return html;
    }
    return "";
}


function loadSubMenu(child)
{
    var html = "";
    // console.log("CHILD: "+child);
    if (child !== null) {
        var edit_tool = $('#menus_toolbar').children('#menu-edit').html();
        var rem_tool = $('#menus_toolbar').children('#menu-remove').clone();
        //console.log(rem_tool);
        $.each(child, function (idx, c) {
            //var toolbar = $('#menus_toolbar').html();

            // console.log(c.menu.children);      
            //console.log(c);
            html += '<li class="dd-item dd3-item" id="pageID_' + c.menu.id + '" data-menu-item="' + c.menu.id + '" data-menu-id="' + c.menu.menu_id + '" data-id="' + c.menu.id + '">';
            html += "<div class='dd-handle dd3-handle'>&nbsp;</div>";
            html += "<div class='menu-item dd3-content'>";
            //  html += '<div class="dd-handle pages_menu_item" data="'+c.menu.id+'" data-menu-item="' + c.menu.id + '" data-menu-id="' + c.menu.menu_id + '" data-subpages="' + c.menu.children_role + '">';
            if (c.pages !== null) {
                if( c.menu.label == ""){ 
                    html += '<span class="" title="' + c.pages.page.slug + '">' + c.pages.page.label + '</span>';
                } else {
                  html += '<span class="" title="' + c.menu.link_value + '">' + c.menu.label + '</span>';
                }
            } else {
                html += '<span class="" title="' + c.menu.link_value + '">' + c.menu.label + '</span>';
            }
            // html += "</div>";
            html += "<div class='yb-toolbar hide'>";
            html += edit_tool;
            if (c.menu.children !== null) {
                $(rem_tool).find('.menu-remove').addClass('hide');
            } else {
                $(rem_tool).find('.menu-remove').removeClass('hide');
            }
            html += $(rem_tool).html();
            html += "</div>";
            html += "</div>";

            if (typeof c.menu.children !== undefined && c.menu.children !== null) {
                html += "<ul class=\"dd-list\">\n";
                html += loadSubMenu(c.menu.children);
                html += "</ul>"
            }
            html += "</li>";

        });
        return html;
    }
    return "";
}

/*Helper function to do the menu creation in the a dropdown select with dash to mark indentation and hiearchy
 * This is  used in both the menu and page section of the admin tool.
 * 
 * @param {json} child : object that contains the page data
 * @param {int} iteration: the iteration for the dash system
 * @param {int} pid: parent id to see if the current parent iteration matches the pid
 * @param {int} sid: sibling id to see if the select the sibling item that is before the one we want
 * @returns {json} on success or null 
 */
function listSubMenus(child, iteration, pid, sid)
{

    if (child !== null) {
        var phtml = "";
        var shtml = "";
        $.each(child, function (idx, c) {
            var dash = "";
            //console.log(iteration +" "+c.page.label);
            for (var i = 0; i < iteration; i++) {
                dash += " - "
            }
            if (iteration === "1") {
                dash = " - ";
            }
            var sel = "";
            if (pid !== null) {

                if (c.page.id == pid) {
                    sel = " selected='selected' ";
                }
            }
            if (c.page.parent_id == pid) {
                var ssel = "";
                if (c.page.id == sid) {
                    ssel = "selected='selected' ";
                }
                //console.log("bbPID: "+c.page.parent_id+" bbID: "+pid );
                shtml += "<option value='" + c.page.id + "' " + ssel + ">" + c.page.label + "</option>";
            }

            phtml += "<option value='" + c.page.id + "' " + sel + ">" + dash + " " + c.page.label + "</option>";
            if (typeof c.page.children !== undefined && c.page.children !== null) {
                var res = listSubMenus(c.page.children, iteration + 1, pid, sid);
                //console.log(res);
                phtml += res.phtml;
                shtml += res.shtml;
            }
        });
        return {phtml: phtml, shtml: shtml};
    }
    return null;

}

/**
 * Function that will generetate the options for a select dropdown and will populate the pages that belong to that particular menu
 * @param {int} menu_id
 * @param {json} child 
 * @param {int} iteration
 * @param {int} pid
 * @param {int} sid
 * @returns json object on success null on fail
 */

function listPagesDD(menu_id, child, iteration, pid, sid)
{
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
                shtml += "<option value='" + c.menu.link + "'>" + c.menu.label + "</option>";
            }
           

            phtml += "<option value='" + c.menu.link+ "'>" + dash + " " + c.menu.label + "</option>";
            if (typeof c.menu.children !== undefined && c.menu.children !== null) {
                var res = listPagesDD(menu_id, c.menu.children, iteration + 1, pid, sid);
                //console.log(res);
                phtml += res.phtml;
                shtml += res.shtml;
              
            }
        });
        return {phtml: phtml,shtml:shtml};
    }
    return null;
}


function nestable(child_class) {
    $('#nestable')
            .nestable({
                listNodeName: 'ul',
                maxDepth: 5,
                rootClass: 'nestable',
                noDragClass: 'dd-no-move',
                collapsedClass: 'contentContainer',
                toolbar: true
            })
            .on('change', function () {
                $('li.dd-item').each(function (x) {
                    // console.log(x);
                    if ($(this).children('ul').length > 0) {
                        $(this).children('.' + child_class).find('.remove-item').addClass('hide');
                    } else {
                        $(this).children('.' + child_class).find('.remove-item').removeClass('hide');
                    }
                });
                $('#save_item').removeClass('hide');
                $('#cancel_item').removeClass('hide');
                $('#publish_item').addClass('hide');
            })
            .disableSelection();
}