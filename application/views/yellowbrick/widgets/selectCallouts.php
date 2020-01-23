<p><strong>Select Callouts to be associated with this page</strong></p>

<?php $callouts = ORM::factory('Callout')->find_all();
$item_list = "";
$i=1;
foreach($callouts as $callout)
{	
	$item_list.= "callout_".$callout->id.",";
?>
	<input type="checkbox" name="callout_<?=$callout->id ?>" id="callout_<?=$callout->id ?>" value="1"<?=( isset($content_array['callout_'.$callout->id]) && $content_array['callout_'.$callout->id] == 1) ? " CHECKED" : "" ?>> <label for="callout_<?=$callout->id ?>"><?=$callout->name ?></label><br>
<?php   
}
?>

<input type="hidden" name="serialze_fields" value="<?=rtrim($item_list,",") ?>">
<br><br>
<p>Ideally, a page should have no more than two (2) callouts associated with it.</p>