/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	var formicIsDarkMode = function() {
		try {
			return /\bdark\b/.test(window.getComputedStyle(document.documentElement).colorScheme || '');
		} catch (e) {
			return false;
		}
	};
	var formicDarkMode = formicIsDarkMode();
	var formicConfigUrl = '';
	try {
		formicConfigUrl = config.customConfig || (typeof ckeditorConfig !== 'undefined' ? ckeditorConfig : '');
	} catch (e) {
		formicConfigUrl = '';
	}
	var formicConfigBase = formicConfigUrl ? formicConfigUrl.split('?')[0].replace(/\/config\.js$/, '/') : '/theme/formic/ckeditor/';
	var formicDarkContentsCss = formicConfigBase + 'dark-contents.css';

	// Define changes to default configuration here.
	// http://docs.cksource.com/CKEditor_3.x/Developers_Guide
	// http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html
	config.enterMode = CKEDITOR.ENTER_BR;
	//config.forceSimpleAmpersand = true;	// When you put a <img src="x?a=a&b=b"> into the textarea, and go into "source", then ckeditor change the & into &amp;. We don't want this. But this option does not fix this.
	//config.entities = false;			// When you put a <img src="x?a=a&b=b"> into the textarea, and go into "source", then ckeditor change the & into &amp;. We don't want this. But this option does not fix this.
	//config.entities_greek = false;
	config.resize_enabled = false;
	//config.resize_maxHeight = 3000;
	//config.resize_maxWidth = 3000;
	//config.height = '300px';
	//config.resize_dir = 'vertical';	// horizontal, vertical, both
	config.removePlugins = 'elementspath,save'; // this list is modified into DolEditor::Create()
	//config.extraPlugins = 'docprops,scayt,showprotected';
	config.removeDialogTabs = 'flash:advanced';	// config.removeDialogTabs = 'flash:advanced;image:Link';
	config.protectedSource.push( /<\?[\s\S]*?\?>/g );   // Prevent PHP Code to be formatted
	//config.menu_groups = 'clipboard,table,anchor,link,image';	// for context menu 'clipboard,form,tablecell,tablecellproperties,tablerow,tablecolumn,table,anchor,link,image,flash,checkbox,radio,textfield,hiddenfield,imagebutton,button,select,textarea'
	//config.language = 'de';
	//config.defaultLanguage = 'en';
	//config.contentsLanguage = 'fr';
	config.fullPage = false;	// Not a full html page string, just part of it
	config.dialog_backgroundCoverColor = 'rgb(255, 254, 253)';
	if (formicDarkMode) {
		config.dialog_backgroundCoverColor = 'rgb(0, 0, 0)';
		var formicContentsCss = config.contentsCss || CKEDITOR.getUrl('contents.css');
		if (!CKEDITOR.tools.isArray(formicContentsCss)) {
			formicContentsCss = [formicContentsCss];
		}
		if (formicContentsCss.indexOf(formicDarkContentsCss) === -1) {
			formicContentsCss.push(formicDarkContentsCss);
		}
		config.contentsCss = formicContentsCss;
	}
	//config.contentsCss = '/css/mysitestyles.css';
	config.image_previewText=' ';	// Must no be empty
	//config.autoParagraph = false;
	//config.removeFormatTags = 'b,big,code,del,dfn,em,font,i,ins,kbd';		// See also rules on this.dataProcessor.writer.setRules
	//config.forcePasteAsPlainText = true;
	config.entities_latin = false;

	config.toolbar_Full =
	[
	    ['Templates','NewPage'],
	    ['Save'],
	    ['Maximize','Preview'],
	    ['PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],	// 'Cut','Copy','Paste','-', are useless, can be done with right click, even on smarpthone
	    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	    ['CreateDiv','ShowBlocks'],
	    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
	    ['Bold','Italic','Underline','Strike','Superscript'],				// 'Subscript'
	    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['BidiLtr', 'BidiRtl'],
	    ['Link','Unlink'],
	    ['Image','Table','HorizontalRule','SpecialChar'],
	    ['Styles','Format','Font','FontSize'],
	    ['TextColor','BGColor'],
	 	['Source']
	];

	// Used for mailing fields
	config.toolbar_dolibarr_mailings =
	[
	 	['Maximize','Preview'],
	 	['SpellChecker', 'Scayt'],
	 	['Find','Replace'],
	 	['CreateDiv','ShowBlocks'],
	    ['Format','Font','FontSize'],
	 	['Bold','Italic','Underline','Strike','-','TextColor','RemoveFormat'],
	 	['NumberedList','BulletedList','Outdent','Indent'],
	 	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	 	['Link','Unlink','Image','Table','HorizontalRule','SpecialChar'],
	 	['Source']
	 ];

	// Used for notes fields
	config.toolbar_dolibarr_notes =
	[
	 	['Maximize'],
	 	['SpellChecker', 'Scayt'],		// 'Cut','Copy','Paste','-', are useless, can be done with right click, even on smarpthone
	 	['Find','Replace'],
	    ['Format','FontSize'],
	 	['Bold','Italic','Underline','Strike','-','TextColor','RemoveFormat'],
	 	['NumberedList','BulletedList','Outdent','Indent'],
	 	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Link','Unlink','Image','Table','HorizontalRule','SpecialChar'],
	 	['Source']
	];

	// Used for details lines
	config.toolbar_dolibarr_details =
	[
	 	['Maximize'],
	 	['SpellChecker', 'Scayt'],		// 'Cut','Copy','Paste','-', are useless, can be done with right click, even on smarpthone
	    ['Format','FontSize'],
	    ['Bold','Italic','Underline','Strike','-','TextColor','RemoveFormat'],	// ,'Subscript','Superscript' useless
	 	['NumberedList','BulletedList','Outdent','Indent'],
	 	['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
	    ['Link','Unlink','SpecialChar'],
	 	['Source']
	];

	// Used for mailing fields
	config.toolbar_dolibarr_readonly =
	[
		['Maximize'],
		['Find'],
		['Image'],
		['Source'],
		['SpecialChar'],
	];
};


/* Code to make links into CKEditor, in readonly, mode clickable */
CKEDITOR.on('instanceReady', function(event) {
    var editor = event.editor;
	var isDarkMode = false;
	try {
		isDarkMode = /\bdark\b/.test(window.getComputedStyle(document.documentElement).colorScheme || '');
	} catch (e) {
		isDarkMode = false;
	}
	if (isDarkMode) {
		var configUrl = '';
		try {
			configUrl = editor.config.customConfig || (typeof ckeditorConfig !== 'undefined' ? ckeditorConfig : '');
		} catch (e) {
			configUrl = '';
		}
		var configBase = configUrl ? configUrl.split('?')[0].replace(/\/config\.js$/, '/') : '/theme/formic/ckeditor/';
		var darkContentsCss = configBase + 'dark-contents.css';
		var installDarkContents = function(doc) {
			if (!doc || !doc.documentElement) return;
			doc.documentElement.className += doc.documentElement.className.indexOf('formic-ckeditor-dark') === -1 ? ' formic-ckeditor-dark' : '';
			if (!doc.getElementById('formic-ckeditor-dark-contents')) {
				var link = doc.createElement('link');
				link.id = 'formic-ckeditor-dark-contents';
				link.rel = 'stylesheet';
				link.type = 'text/css';
				link.href = darkContentsCss;
				(doc.head || doc.documentElement).appendChild(link);
			}
		};
		var installFrameDarkContents = function(frame) {
			try {
				if (frame && frame.contentDocument) {
					installDarkContents(frame.contentDocument);
				}
			} catch (e) {
				// Some browser-created frames may not be ready yet.
			}
		};
		var refreshDarkFrames = function() {
			var frames = document.querySelectorAll('iframe.cke_wysiwyg_frame, iframe.cke_panel_frame');
			for (var i = 0; i < frames.length; i++) {
				installFrameDarkContents(frames[i]);
			}
		};

		editor.container.addClass('formic-ckeditor-dark');
		if (editor.document && editor.document.$) {
			installDarkContents(editor.document.$);
		}
		editor.on('contentDom', function() {
			if (editor.document && editor.document.$) {
				installDarkContents(editor.document.$);
			}
		});
		refreshDarkFrames();
		setTimeout(refreshDarkFrames, 100);
		setTimeout(refreshDarkFrames, 500);
		if (window.MutationObserver && !window.formicCkeditorDarkFrameObserver) {
			window.formicCkeditorDarkFrameObserver = new MutationObserver(refreshDarkFrames);
			window.formicCkeditorDarkFrameObserver.observe(document.body, { childList: true, subtree: true });
		}
	}
	if (editor.readOnly) {
	  var editable = editor.editable();
	  editable.attachListener(editable, 'click', function(evt) {
	    var target = evt.data.getTarget();
	    var anchor = target.getAscendant('a', true);
	    if (anchor) {
	      var href = anchor.getAttribute('href');
	      if (href) {
	        window.open(href, '_blank'); // Open link in a new tab/window
	      }
	    }
	  });
	}
});
