var ybeditor = {
    validation:true,
    publish:false,
    ckeditor:function(){
        for (instance in CKEDITOR.instances)
        {
            CKEDITOR.instances[instance].updateElement();
        }
    },
    validate:function(){
       // alert(this.validation);
        this.ckeditor();
        if(this.publish){
             $("#publish").val(1); // POST value is set here but role validation is still required at server level
        }
        
        if(this.validation){
           // alert("GETTING AJAX");
            this.ajax();
        }
    },
    ajax:function(){
        
        var ajax = $.ajax({
           type: "post",
           url: XHR_PATH + 'latestRevision',
           data: {ybr_loggedin: sessiontoken, page_id: $("#page_id").val(), block_id: $("#block_id").val(), version_id: $("#version_id").val()},
        }).done(function (data) {
               if (!data) {
                   alert(data);
                   return false;
               }
               // alert(data);
               var pageLoadDate = new Date($("#edit_time").val());
               var latestRevisionDate = new Date(data.revision_date);
               if (latestRevisionDate > pageLoadDate)
               {
                   var msg, msg2;

                   if (data.live === 1) {
                       msg = "revision was published live to the site ";
                   }
                   else
                   {
                       msg = "draft was saved ";
                   }
                   if (publish)
                   {
                       msg2 = "publish";
                   }
                   else
                   {
                       msg2 = "save";
                   }

                   uiConfirm({
                       message: "While you were editing this content, a more recent " + msg + "by <strong>" + data.updated_name + '</strong> (' + dateFormat('M j, Y g:ia', data.revision_date) + ")<br /><br />" + "Do you still want to " + msg2 + " your changes?<br /><br />Select \"Cancel\" to return to the form.",
                       title: 'Notice',
                       callback: function (yes) {
                           if (yes === false)
                           {
                               return;
                           }
                           else
                           {
                               ybeditor.submit();//submitform();
                           }
                       }
                   });

               }
               else
               {
                   ybeditor.submit();//submitform();
               }
        });
    },
    submit:function(){
        //alert("SUBMIT");
        $(window).unbind("beforeunload");
        // return true;
        document.forms["savecontent"].submit();
    }
};



function isDirty()
{
    if ($("#blocktype").val() == "wysiwyg")
    {
        var editor = $('#content').ckeditorGet()
        if (editor.checkDirty()) {
            return true;
        }
    }
    else if ($("#blocktype").val() != "customform")
    {
        var o = $("#original_content").val().replace(/\s|\t|\n/g, ''),
                c = $("#content").val().replace(/\s|\t|\n/g, '')

        if (o != c)
        {
            return true;
            //return "|"+o+"|\n\n|"+c+"|\n\n"+message;
        }
    }
}



function preview(url)
{
    if (isDirty())
    {
        uiConfirm({title: "Confirm Unsaved Changes",
            message: "The content has been changed since your last save.<br><br>Select 'Ok' to continue previewing the last saved revision.",
            callback: function (ok)
            {
                if (ok)
                {
                    $("#previewWindow iframe").attr("src", url);
                    $("#previewWindow").dialog('open');
                }
            }
        });
    }
    else
    {
        $("#previewWindow iframe").attr("src", url);
        $("#previewWindow").dialog('open');
    }
}

function set_ckfinder_baseURL() {
//	$.getJSON(XHR_PATH+'setCkfinderBaseURL',{baseURL: ( $("#securefolder").is(':checked') ) ? $("#securefolder").val() : '' }, function(data){
//		if(data){ /* trigger some success message */
//                    alert(data);
//                }else{ alert(data); }
//	});

    if ($("#securefolder").is(':checked')) {
        //alert($("#securefolder").val());
        //document.cookie = "ckfinder_baseURL="+$("#securefolder").val();
        $.cookie("ckfinder_baseURL", $("#securefolder").val(), {path: '/'});
        //console.log($.cookie());
        //;
    } else {
        //alert("herer");
        $.removeCookie("ckfinder_baseURL", {path: "/"});
        //window.location.reload();
    }
    //console.log($.cookie());
}


function editBlock(block_id) {
    // grab block data and replace editor
    $.ajax({
        type: "post",
        url: XHR_PATH + 'editor',
        data: {ybr_loggedin: sessiontoken, page_id: $("#page_id").val(), block_id: $("#block-select").val(), version_id: $("#version_id").val()},
        //  dataType:"json",
        success: function (data) {
            $("#editor").html(data);
            documentReady();
        }
    });
//	$.get(XHR_PATH+'editor',{page_id:$("#page_id").val(),block_id:block_id,version_id:$("#version_id").val()},function(data){
//			$("#editor").html(data);
//			documentReady();
//	});


}

function documentReady()
{
    //make note of current block ID.
    var current_selected_block = $("#block-select").val();

    // re-initialize select menu replacement plugin
   // triggerSelect2();

    // re-initialize tooltip plugin
    triggerTooltip();

    $("#block-select, #version-select").on('change', function (e) {
       // e.stopPropagation();
        var selectedBlockID = $(this).val();

        if (isDirty())
        {
            uiConfirm({title: "Confirm Unsaved Changes",
                message: "The content has been changed since your last save.<br><br>Press 'Cancel' to stay on this page and save your changes or 'OK' to continue without saving.",
                callback: function (ok)
                {
                    if (ok)
                    {
                        editBlock(selectedBlockID);
                        current_selected_block = selectedBlockID; //update flag
                    }
                    else
                    {
                        // if user canceled request, reset the drop down to the original value
                        $("#block-select").select2('val', current_selected_block);
                    }
                }
            });
        }
        else
        {
            editBlock(selectedBlockID);
        }
        e.preventDefault();
    });


    // "Save" button actions to save (but not publish) a draft
//    $("#save").click(function () {
//        ybeditor.validate();
//    });

    $("#previewWindow").dialog({
        width: window.innerWidth - 40,
        height: window.innerHeight - 40,
        modal: true,
        autoOpen: false,
        resizable: false
    });

    /**
     * if any form inputs use checkboxes or radio buttons, pre-select them on load
     * this is necessary for block inputs that need to be dynamically checked, since the code isn't run server side
     */
    $("input:checkbox, input:radio").each(function ()
    {
        if ($(this).attr('selected-data') != ""
                && $(this).attr('selected-data') == $(this).val()
                )
        {
            $(this).prop('checked', true);
        }
    });
    
    $(".yb-select").select2({
            width: 'resolve' //First attempts to "copy" than falls back on "element".
    });






}

$(function () {
    $.ajax({
        type: "post",
        url: XHR_PATH + 'editor',
        data: {ybr_loggedin: sessiontoken, content_id: $("#content_id").val()},
        //dataType:"json",
        success: function (data) {
          // console.log(data);
            $("#editor").html(data);
           documentReady();
        }
    });
    
    //YOU WILL NEED THE NEXT LINE OF JS CODE IF YOU WANT TO PREVENT THE DEFAULT BEHAVIOR (SUBMIT FORM)
    //$('#publishBTN,#save').removeAttr('id');//REMOVE THE ID OF THE PUBLISH/SAVE BUTTON TO PREVENT SUBMISSION OF VALIDATION ERROR
    //LOOK AT The doubletreebristol project  /classes/views/yellowbrick/widgets/specials.php
    
     // "Save" button actions to save (but not publish) a draft
    $('#editor').on({
        click:function(event){
            ybeditor.publish = false;
            ybeditor.validate();
            event.preventDefault();
        }
    },'#save');

 // "PUBLISH" button actions to save and make live
    $('#editor').on({
        click:function(event){  
            ybeditor.publish = true;
            ybeditor.validate();
            event.preventDefault();
        }
    },'#publishBTN');

//    $.get(XHR_PATH+'editor',{content_id: $("#content_id").val() },function(data){
//        $("#editor").html(data);
//        documentReady();
//    });

    $(window).bind('beforeunload', function () {
        var message = "Changes have been made to the content since your last save.";
        if (isDirty()) {
            return message;
        }
    });
    
    
    
     if($("#securefolder").is(':checked')){
        $.cookie("ckfinder_baseURL",$("#securefolder").val(),{path:'/'});
    } else {
     $.removeCookie("ckfinder_baseURL",{path:"/"});
    }



});