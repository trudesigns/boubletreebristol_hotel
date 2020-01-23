<?php defined('SYSPATH') OR die('No direct access allowed.'); 

return [
 
 	'title' => ['not_empty'=>'<span class="required_red">The <strong>Title</strong> field is required.</span>'],
        'slug' => ['not_empty'=>'<span class="required_red">The <strong>Slug</strong> field is required.</span>'],
        'content' => ['not_empty'=>'<span class="required_red">The <strong>Content</strong> field is required.</span>'],
	'sdate' => ['not_empty'=>'<span class="required_red">The <strong>Start Date</strong> field is required.</span>'],
        'category' => ['not_empty'=>'<span class="required_red">The <strong>Category</strong> field is required.</span>'],
	
 
];
