<h1>Edit</h1>

<article id="editor">
<span class="loading-text">Loading...</span>
<input id="content_id" value="<?=$content->id ?>" type="hidden">
</article>

<div id="previewWindow" style="margin: 0; padding: 0; overflow: hidden" title="[Preview] <?=$page->label.": ".$block->name ." (revision ". date("m/d/Y g:ia",strtotime($content->revision_date)) ?>)"><iframe src="" width="100%" height="100%" frameborder="0" style="border: none; margin: 0; padding: 0;"></iframe></div>