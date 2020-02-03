var order, original_order, order_isDirty = false;
var current_field = false;

function lockToolbar()
{
    $(".ui-icon-wrench, .ui-icon-document, .ui-icon-trash, .ui-icon-copy, .ui-icon-shuffle").off('click');
    $("#yb-wrap").append($("#page_toolbar").fadeOut(0)); // move the toolbar out of the hovered div before reloading the list
    $(".pages_menu_item").unbind('hover'); // stop the hover effect while the div is reloading
}

function orderchange()
{
    order = $(".dd-list").sortable("serialize", {key: "fieldID"});

    if (order !== original_order)
    {
        order_isDirty = true;
        $("#save-order").fadeIn('fast');
    }
    else
    {
        order_isDirty = false;
        $("#save-order").fadeOut('fast');
    }
}

function saveorder()
{

    $.post(XHR_PATH + 'orderFormFields', {list_order: order}, function(data) {
        data = $.trim(data);
        if (data !== "true")
        {
            uiAlert(data);
            return false;
        }
        else
        {
            order_isDirty = false;
            original_order = order // update original order to be the new order
            $("#save-order").fadeOut('fast');
            loadFormFields();
        }
    });
}

function initiateToolbar(toolbar_hit_zone)
{
    toolbar_hit_zone = (!toolbar_hit_zone) ? ".pages_menu_item" : toolbar_hit_zone;

    //add tool bar on hover and bind functionality to its buttons
    $(toolbar_hit_zone).hover(function() {
        var item_id = $(this).attr('data');
        var page_name = $("span", this).html();

        $(this).append($("#page_toolbar"));
        //bind "Edit Page" pop-up box
        $(".ui-icon-wrench").on('click', function(e) {
            e.stopPropagation();
            editField(item_id);
        });

        $(".ui-icon-trash").on('click', function(e) {
            e.stopPropagation();

            //	var confirmation_message ="Are you sure you want to PERMANENTLY DELETE the field \""+page_name+"\"?<br><br><span style='color: red'>All existing stored user data</span> associated with this field <span style='color: red'>will be lost!</span><br><br>This action cannot be undone."
            var confirmation_message = "Are you sure you want to PERMANENTLY DELETE the field \"" + page_name + "\"?<br><br>This action cannot be undone."
            uiConfirm({message: confirmation_message,
                title: "Confirm Field Deletion",
                callback: function(response) {
                    if (!response)
                    {
                        return false;
                    }
                    else
                    {
                        $.get(XHR_PATH + 'deleteFormField/', {field_id: item_id}, function(data) {
                            if ($.trim(data) !== "done") {
                                uiAlert(data);
                            }
                            else
                            {
                                var this_li = $("#fieldID_" + item_id);
                                this_li.fadeOut('fast');
                                lockToolbar();
                                setTimeout(function() {
                                    this_li.remove()
                                }, 500);
                            }
                        });

                    }
                    return response;
                }
            });
        });

        $("#page_toolbar").fadeIn(0);
    }, function() {
        $(".ui-icon-wrench, .ui-icon-document, .ui-icon-trash, .ui-icon-copy, .ui-icon-shuffle").off('click');
        $('body').append($("#page_toolbar"));
        $("#page_toolbar").fadeOut(0);
    });
}

function loadFormFields(dirty_refresh)
{
    if (order_isDirty)
    {
        if (dirty_refresh !== undefined && dirty_refresh)
        {
            order_isDirty = false;
            $("#save-order").fadeOut('fast');
        }
        else
        {

            uiConfirm({message: "There are unpublished changes to the order of fields.<br><br>Are you sure you want to refresh the list without saving?",
                title: "Confirm Refresh",
                callback: function(response) {
                    if (response)
                    {
                        order_isDirty = false;
                        $("#save-order").fadeOut('fast');
                        loadFormFields(true);
                    }
                    else
                    {
                        return false;
                    }
                }
            });
        }

    }

    lockToolbar(); // make sure the toolbar icon set is not in the #page area that is about to be replaced.
    $('#form').html('loading...');

    $.getJSON(XHR_PATH + 'formfields', {form_id: $("#selected_form").val()}, function(data) {

        var html = "<ul class=\"dd-list\">\n";

        $.each(data, function(index, fielddata) {
            html += '<li class="dd-item" id="fieldID_' + fielddata.id + '"><div class="dd-handle pages_menu_item" data="' + fielddata.id + '"><span class="dd-no-move">' + fielddata.label + '</span></div>';
            html += "</li>\n";
        });
        html += "</ul>\n";

        $('#form').html(html); // populate the #pages div with the generated content

        initiateToolbar(); // set the hover state for the toolbar 

        $(".dd-list").sortable({
            axis: "y",
            drag: function(event, ui) {
                return false;
            },
            handle: ".ui-icon-arrowthick-2-n-s",
            update: function() {
                orderchange();
            }
        })
                .disableSelection();

    });
}

var option_index = 0;
function drawFieldOption(label, value, checked)
{
    var checkedType = "checkbox",
            fieldtype = $("#fieldtype").val(),
            label = (label !== undefined) ? label : '',
            value = (value !== undefined) ? value : '',
            checked = (checked !== undefined && (checked === true || checked == 1)) ? " CHECKED" : "";

    if (fieldtype === "radio" || fieldtype === "select")
    {
        checkedType = "radio";
    }

    var html = '<tr><td><input type="text" id="option_label_' + option_index + '" value="' + label + '"></td>';
    html += '<td><input type="text" id="option_value_' + option_index + '" value="' + value + '"></td>';
    html += '<td><input type="' + checkedType + '" id="option_checked_' + option_index + '" name="option_checked[]"' + checked + '></td></tr>';

    $("#field_options tbody").append(html);
    option_index++;
}

function drawFieldOptions()
{

    var fieldtype = $("#fieldtype").val();
    if (fieldtype == "text" || fieldtype == "textarea")
    {
        $("#defaultvalue").fadeIn('fast');
        $("#options").fadeOut(0);
    }
    else if (fieldtype == "none")
    {
        $("#options, #defaultvalue").fadeOut('fast');
    }
    else
    {
        $("#options").fadeIn('fast');
        $("#defaultvalue").fadeOut(0);
    }

    $("#field_options tbody").html(''); // clear out exisiting fields
    option_index = 0; // reset

    if (current_field.field_options == "")
    {
        drawFieldOption(); // draw one blank line
        return;
    }

    var field_options = (!current_field) ? '' : JSON.parse(current_field.field_options);
    $.each(field_options, function(key, data)
    {
        if (data.label != '' && data.label != undefined && data.label.length > 0)
        {
            drawFieldOption(data.label, data.value, data.checked);
        }
    });

}

/*
 * refresh the page, even if order_isDirty is true
 */
function dirty_refresh()
{
    order_isDirty = false;
    location.reload(true);
}

$(window).bind('beforeunload', function() {
    var message = "There are unpublished changes to the order of fields.";
    if (order_isDirty) {
        return message;
    }
});

function createField()
{
    current_field = false;
    $("#label").val('');
    $("#fieldtype").val('text');
    $("#value").val('');
    $("#max_length").val('');
    $("#required").prop('checked', false);
    $("#active").prop('checked', true);
    drawFieldOptions();
    $("#editFieldDialog").dialog('open');
    $(".tooltip").tooltip();
}

function editField(item_id) { // load the data to be put in the pop-up
    $.getJSON(XHR_PATH + 'getFormFieldProperties/', {field_id: item_id}, function(data) {

        if (!data || $.trim(data) == "false" || data.id == null)
        {
            uiAlert("Internal Error:  Invalid Field ID or Field Not Found");
            return;
        }
        current_field = data; // set to resue later while this dialog is open, if neccessary

        $("#label").val(data.label);
        $("#fieldtype").val(data.field_type);
        $("#value").val(data.value);
        $("#max_length").val((data.max_length == 0) ? "" : data.max_length);
        $("#required").prop('checked', +data.required); // "+" reads the integer as bool
        $("#active").prop('checked', +data.active);

        drawFieldOptions();

        $("#editFieldDialog").dialog('open');
        $(".tooltip").tooltip();
    });
}

function formData()
{
    var json;
    if (option_index > 0 &&
            $("#fieldtype").val() != "none" &&
            $("#fieldtype").val() != "text" &&
            $("#fieldtype").val() != "textarea")
    {
        json = "[";
        for (var i = 0; i <= option_index; i++)
        {
            if ($.trim($("#option_label_" + i).val()) != "" && $("#option_label_" + i).val() != undefined)
            {
                var checked = ($("#option_checked_" + i).is(":checked")) ? 1 : 0;
                json += '{"value":"' + $("#option_value_" + i).val() + '",';
                json += ' "label":"' + $("#option_label_" + i).val() + '",';
                json += ' "checked": ' + checked + '},';
            }
        }
        json = json.substring(0, json.length - 1); // remove that last comma
        json += ']';
    }
    json = (json === undefined || json.length < 2) ? "" : json;

    return {
        form_id: $("#selected_form").val(),
        field_id: (!current_field) ? 'false' : current_field.id,
        label: $("#label").val(),
        field_type: $("#fieldtype").val(),
        field_options: json,
        value: $("#value").val(),
        max_length: $("#max_length").val(),
        required: ($("#required").is(':checked')) ? 1 : 0,
        active: ($("#active").is(':checked')) ? 1 : 0,
        placeholder: '',
        tooltip: '',
        class: ''
    }
}

$(document).ready(function() {
    loadFormFields();

    $("#editFieldDialog").dialog({
        width: 650,
        height: 'auto',
        modal: true,
        autoOpen: false,
        resizable: false,
        buttons: [
            {
                text: "Cancel",
                click: function() {
                    $(this).dialog('close');
                }
            },
            {
                text: "Save Changes >",
                click: function() {
                    var sendData = formData();
                    $.post(XHR_PATH + 'saveFormField/', sendData, function(data) {
                        data = $.trim(data);
                        if (data !== "done")
                        {
                            uiAlert(data);
                        }
                        else
                        {
                            lockToolbar();
                            var newString = '<span>' + $("#label").val() + '</span>';
                            loadFormFields();
                        }
                    });
                    $(this).dialog('close');
                }
            }
        ]
    });

});