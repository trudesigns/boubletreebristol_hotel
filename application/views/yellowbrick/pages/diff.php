<?php
function beautify($str)
{
//	$str = preg_replace("/\&\#39\;/","'",$str);
	if( isset($_GET['textonly']))
	{
		return strip_tags($str);
	}
	else
	{
		return htmlspecialchars($str);
	}
}
?>
<div id="leftColumn">
	
	Compare selected revision to:<br>
	<input type="radio" name="compareto" id="compareto_published" value="none">
	<label for="compareto_published">Current Live Content</label>
	<br>
	<input type="radio" name="compareto" id="compareto_mostrecent" value="0" checked="checked">
	<label for="compareto_mostrecent">Most Recent New Content</label>
	<br>
	<input type="radio" name="compareto" id="compareto_previous" value="none">
	<label for="compareto_previous" class="tooltip" title="Compare the selected revison to the revison before it">Previous Content</label>
	<br>
	<input type="checkbox" id="showHTML" value="1">
	<label for="showHTML">Show HTML Difference</label>
	<br>
	<input type="checkbox" id="twoColumns" value="1" checked="checked">
	<label for="twoColumns">Compare Side-by-side</label>
<br>

	<table id="revisionList" class="tablesorter" width="100%"></table>
</div>

<div id="mainBody">
Comparing: <ins>Revision <span id="compare_title_b"></span></ins>
 and <del>Revision <span id="compare_title_a"></span></del>.

<?php if(isset($_GET['textonly'])){ ?>
The following reflects only the plain text differences between these two revisions.  It does not account for HTML mark-up and therefore does not include style changes (such as <strong>bolding</strong> or <em>italics</em> nor does it contain links or images.
<?php } ?>
<div id="result" class="code diff-code"></div>

</div><!-- end "mainBody" section-->

<script type="text/javascript">
var a_selected="0",a,b_selected="0",b_title_extension,b,allRevisions;

function htmlencode(str) {
    
    if(! $("#showHTML").is(":checked"))
    {
    	return str;
    }
    
    return str.replace(/[<>]/g, function($0) {
        return "&" + {"<":"lt", ">":"gt"}[$0] + ";";
    });
}

function findDiff()
{
	
	if( $("#compareto_published").is(":checked"))
	{
		b_title_extension = "Live";	
		b_selected = $("#compareto_published").val();
	} 
	else if ( $("#compareto_mostrecent").is(":checked"))
	{
		b_title_extension = "Most Recent";	
		b_selected = $("#compareto_mostrecent").val();
	}
	else
	{
		b_title_extension = "Previous";
		b_selected = parseInt(a_selected) + 1;
	}

//	if( new Date(allRevisions[a_selected].revision_date) < new Date(allRevisions[a_selected].revision_date) )
//	{
//		a = htmlencode(allRevisions[a_selected].content);
//		b = htmlencode(allRevisions[b_selected].content);
//	}
//	else
//	{
		a = htmlencode(allRevisions[a_selected].content);
		b = htmlencode(allRevisions[b_selected].content);
//	}
	
	
	$("#compare_title_a").html( dateFormat("m/d/y h:ia",allRevisions[a_selected].revision_date) +" (Selected)");
	$("#compare_title_b").html( dateFormat("m/d/y h:ia",allRevisions[b_selected].revision_date) +" ("+b_title_extension+")");
	
	if(a != b)
	{

		//get the differences
		var diff = ( $("#showHTML").is(":checked")) ? JsDiff.diffHtml(a,b) : JsDiff.diffLines(a,b);
		
		//flip the order of the arrays so that "removed" items come before "added"
		var i=0, newdiff = new Array(), skip=false;
		$.each(diff,function(index,value)
		{
			if(!skip){
				skip = true; // set flag for next time	
				
				if(diff[i+1] != undefined)
				{
					newdiff[i] = diff[i+1];
					newdiff[i+1] = diff[i];
				}
				else
				{
					newdiff[i] = diff[i];
				}
			}
			else
			{
				skip = false; // unset flag
				
			}	
			i++;
		});
		diff = newdiff;
	
		var output = "",
			table = "<table><tr><th width=\"50%\">rev1</th><th width=\"50%\">rev2</th></tr>\n",
		
			column_num = 1,
			previous_diff_was_added = false; 
		
		for (var i=0; i < diff.length -1; i++) 
		{		
			if(column_num == 1)
			{
				table+=" <tr>\n";
			}
			
			var diff_value = '<div class="wordwrap_NEVERMIND">'+diff[i].value +'</div>';
					
		/*
			if(diff[i].removed)
			{
				output+= "<del class=\"tooltip\">"+ diff_value +"</del>";
				table+= "  <td><del>"+ diff_value +"</del></td>\n  <td><ins> </ins></td>\n";
			} 
			else if(diff[i].added)
			{
				output+= "<ins class=\"tooltip\">"+ diff_value +"</ins>";
				table+= "  <td><del> </del></td>\n  <td><ins>"+ diff_value +"</ins></td>\n";
			} 
			else
			{
				output+= diff[i].value;
				table+="  <td>"+ diff[i].value +"</td>\n  <td>"+ diff[i].value +"</td>\n";
			}
		*/
			if(diff[i].removed)
			{
				output+= "<del class=\"tooltip\">"+ diff_value +"</del>";
				table+= "  <td><del>"+column_num +"-- "+ diff_value +"</del></td>\n";
				previous_diff_was_added = true;
			} 
			else if(diff[i].added)
			{
				output+= "<ins class=\"tooltip\">"+ diff_value +"</ins>";
				table+= "  <td><ins>"+column_num +"-- "+ diff_value +"</ins></td>\n";
				previous_diff_was_added = true;
			} 
			else
			{
				output+= diff[i].value;
				
				if(column_num == 2 && previous_diff_was_added)
				{
					table+="  <td>&nbsp;</td></tr><tr>\n";		
				}
			//	else
			//	{
					table+="  <td>"+column_num +"A-- "+ diff_value +"</td>\n  <td>"+column_num +"B-- "+ diff_value +"</td>\n";
					column_num = 2;
			//	}
				previous_diff_was_added = false;
				
				
			}


		
			if(column_num == 2)
			{
				table+= "</tr>\n";	
			}
			
			column_num = (column_num == 1) ? 2 : 1;
		}
		table+="</table>";
		
		output = ( $("#showHTML").is(":checked") )? "<pre>\n" + output +"\n</pre>" : output;
		
		var layout = ( $("#twoColumns").is(":checked") ) ? table : output;
		output = layout;
	}
	else
	{
		var output = '<div id="sameContent">There are no differences between these revisions</div>'+a;
	}
	
	$("#result").html(output);
	$("del").attr("title","This content was removed").tooltip();
}


function getRevisions(preselect)
{
	$.getJSON('/admin/request/allRevisions',{page_id:<?=$content->page_id ?>,block_id:<?=$content->block_id ?>,version_id:<?=$content->version_id ?>}, function(data){
		allRevisions = data;
		
		var status,
			html="<thead><tr class=\"header\"><th>Revision Time</th><th>Status</th><th>Author</th></tr></thead>\n<tbody>\n";
		
		$(data).each(function(index,revision){
			
			status = "Old";
			if(revision.live == 1)
			{
				$("#compareto_published").val(index);
				status = "Live";
			}
			else if(revision.publish_date == "0000-00-00 00:00:00")
			{
				status = "Draft";
			}	
			
			
		//	html+='<li data-id="'+index+'">'+ dateFormat("m/d/y h:i a",revision.revision_date) +' -'+revision.updated_name +'</li>\n';
			html+='<tr data-id="'+index+'"><td>'+ dateFormat("m/d/y h:i a",revision.revision_date) +'</td><td>'+status+'<br><a href="/admin/edit/'+ revision.id +'">edit</a></td><td>'+revision.updated_name +'</td></tr>\n';
			
			
		
		});
		html+="</tbody>";
		$("#revisionList")
		.html(html);
		setTimeout(function(){ $("#revisionList").tablesorter({ widgets: ['zebra'],}); }, 1000);
		
		$("#revisionList tr").on('click',function(){
			a_selected = $(this).attr("data-id");
			
			$("#revisionList tr").removeClass('selected_revision');
			$(this).addClass('selected_revision');
			
			findDiff();
		})
		.css({cursor:'pointer'});
	
		if(preselect != undefined)
		{
			$("[data-id="+preselect+"]").click();
		}
		
	});
}

$(document).ready(function(){

	getRevisions(0);
	
	$("#showHTML, input[name=compareto], #twoColumns").on("change",function(){ findDiff() });
	
	 
	 
});
</script>