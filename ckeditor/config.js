/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.toolbar = 'Full';
	
	//config.toolbar = 'CMSdefault';
	
	config.contentsCss = '/yb-assets/css/ckeditor.css?'+ckeditor_cachebuster;
	
	config.format_tags = 'p;h2;h3';
	
	// Disables the built-in spell checker while typing natively available in the browser (currently Firefox and Safari only).
    config.disableNativeSpellChecker = true;

    // If enabled (true), turns on SCAYT (SpellCheckAsYouType) automatically after loading the editor.
    config.scayt_autoStartup = false;
	
    config.toolbar_CMSdefault =
	[
        ['Source','-','Cut','Copy','Paste','PasteText','PasteFromWord','RemoveFormat','-','Undo','Redo','-','Find','Replace','-','SelectAll','-','Scayt','-','Table','-','Image','-','Link','Unlink','Anchor','-','Maximize'],
        '/',
        ['Styles','Format','Bold','Italic','Underline','Strike','Subscript','Superscript','-','JustifyLeft', 'JustifyCenter', 'JustifyRight','-','NumberedList','BulletedList','Outdent','Indent','-','Blockquote','SpecialChar']
    ];
	
	config.templates_replaceContent = false;
	
    config.toolbar_CMSbasic =
	[
        ['Source','-','Cut','Copy','Paste','PasteText','PasteFromWord','RemoveFormat','-','Bold','Italic','-','JustifyLeft', 'JustifyCenter', 'JustifyRight'],
    ];	
	
    config.toolbar_CMSbasicPlusLinks =
	[
        ['Source','-','Cut','Copy','Paste','PasteText','PasteFromWord','RemoveFormat','-','Bold','Italic','Underline','-','JustifyLeft', 'JustifyCenter', 'JustifyRight','-','Link','Unlink'],
    ];	
	
	
// Custom Style Drop-down menu definitions
// Make sure to match style definitions within /yb-assets/css/ckeditor.css

	if(!CKEDITOR.stylesSet.get('custom_styles'))
	{
		// check for the existance of this configuration setting so it only triggers once to prevent an error
			
		CKEDITOR.stylesSet.add( 'custom_styles',
			[
			// Object Styles (shown only when specified object is selected in WYSIWYG editor)
			{ name : 'Product Image', element : 'img', attributes : { 'class' : 'product-image' }},
			
		    // Block-level styles
		    { name : 'Content Heading' , element : 'h2' },
			{ name : 'Sub Heading' , element : 'h3' },
			{ name : 'Small Heading' , element : 'h4' },
                       
		 
		    // Inline styles
		    { name : 'Dark Blue', element : 'span', attributes : { 'class' : 'dark-blue' }},
                    { name : 'More Block Link' , element : 'a', attributes :{ 'class' : 'moreBtn' } },
			{ name : 'Product Image', element : 'img', attributes : { 'class' : 'product-image' }},
			{ name : 'Download Button', element : 'span', attributes : { 'class' : 'download-button' }},
			{ name : 'Inline Callout - Left', element : 'div', attributes : { 'class' : 'inline_callout left' }},
			{ name : 'Purple Text', element : 'span', attributes : { 'class' : 'text-purple' }},
		]);
		
			config.stylesSet = 'custom_styles:/yb-assets/css/ckeditor.css';
	}		
};


CKEDITOR.on( 'dialogDefinition', function( ev ) {
		// Take the dialog name and its definition from the event data.
		var dialogName = ev.data.name;
		var dialogDefinition = ev.data.definition;
 
		// Check if the definition is from the dialog we're
		if (dialogName == 'image'){	
			dialogDefinition.removeContents( 'Upload' ); // hide the quick "upload" tab (note, the "image" dialog box has an UPPER CASE "U" on "Upload")
			dialogDefinition.removeContents( 'Link' );   // hide the "link" tab. force users to use the "link" button after inserting image.
		}
		
		if (dialogName == 'link'){	
			dialogDefinition.removeContents( 'upload' ); // hide the quick "upload" tab (note, the "link" dialog box has an lower case "u" on "upload")
		}
			
});
