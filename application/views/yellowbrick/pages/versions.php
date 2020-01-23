<div id="app-wrapper">
    <h1>Content Versions</h1>
    <br>
    <?php

    function value($field, $crud_selected, $htmlentities = false) {
        if ($crud_selected && isset($crud_selected->$field)) {
            return ($htmlentities) ? htmlentities($crud_selected->$field) : $crud_selected->$field;
        } else {
            return '';
        }
    }
    ?>
    <a href="/admin/versions/" class="yb-button"><span class="ui-icon ui-icon-plusthick"></span>Add New&nbsp;</a>
    <select class="yb-select" onchange="window.location = '/admin/versions/' + $(this).val()">
        <option value="">EDIT A VERSION ></option>
<?php
foreach ($crud_all as $crud_row) {
    $selected = (is_object($crud_selected) && $crud_row->id == $crud_selected->id) ? " SELECTED" : "";
    echo "  <option value=\"" . $crud_row->id . "\"" . $selected . ">" . $crud_row->name . "</option>\n";
}
?>
    </select>
    <br><br>
    <h2><?= (is_object($crud_selected)) ? "Edit" : "Add New" ?> Content Version</h2>
    <br>

    <?php if (isset($errors)) { ?>
        <ul class="form_errors">
            <?php foreach ($errors as $field => $error) {
                ?>
                <li><? echo $error; ?></li>    
            <?php } ?>
        </ul>
    <?php } ?>

    <form action="" method="post">

        <div class="form_row">
            <div class="form_label">Name</div>
            <div class="form_field"><input name="name" value="<?= value('name', $crud_selected) ?>" /></div>
        </div>

        <div class="form_row">
            <div class="form_label">Description</div>
            <div class="form_field"><input name="description" value="<?= value('description', $crud_selected) ?>" /></div>
        </div>

        <div class="form_row">
            <div class="form_label">Selector *</div>
            <div class="form_field">
                <? $selected = value('selector',$crud_selected) ?>
                <select name="selector">
                    <option value=""<?= ($selected == "") ? " SELECTED" : "" ?>>None (Default)</option>
                    <option value="url"<?= ($selected == "url") ? " SELECTED" : "" ?>>URL</option>
                    <option value="role"<?= ($selected == "role") ? " SELECTED" : "" ?>>Role</option>
                    <option value="session"<?= ($selected == "session") ? " SELECTED" : "" ?>>Session</option>
                </select>
            </div>
        </div>

        <div class="form_row">
            <div class="form_label">Selector Key</div>
            <div class="form_field"><input name="selector_key" value="<?= value('selector_key', $crud_selected) ?>" /></div>
        </div>

        <div class="form_row">
            <div class="form_label">&nbsp;</div>
            <div class="form_field">
                <button type="submit" name="submit" class="yb-button first" value="Submit" >Save</button>
                <span id="delete_message" style="display:none; color:#900">This item will be deleted upon submission</span>
            </div>
        </div>

        <div class="form_row" style="display: <?= (is_object($crud_selected)) ? " " : "none" ?>">
            <div class="form_label">Delete</div>
            <div class="form_field"><input type="checkbox" name="delete" id="delete" value="delete"> <label for="delete">Delete This Item</label>
            </div>

    </form>

    <br><br>
    Notes:
    <ul>
        <li>Only one version type can be the default (with no selector or selector key)</li>
        <li>All versions (except the default) must share a common selector type.</li>
        <li>Update bootstrap to define "<em>content_versioning</em>" as TRUE. (setting it to FALSE speeds up page processing for sites without versions)</li>
        <li>If using the "URL" seclector, update bootstrap to define "<em>url_locale</em>" as TRUE.</li>
    </ul>
</div> <!-- end "app-wrapper" -->