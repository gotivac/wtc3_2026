CKEDITOR.editorConfig = function( config ) {
    
   var host = "http://"+window.location.hostname;
   config.filebrowserBrowseUrl = host+'/wtc/kcfinder/browse.php?type=files';
   config.filebrowserImageBrowseUrl = '/wtc/kcfinder/browse.php?type=images';
   config.filebrowserFlashBrowseUrl = host + '/wtc/kcfinder/browse.php?type=flash';
   config.filebrowserUploadUrl = host + '/wtc/kcfinder/upload.php?type=files';
   config.filebrowserImageUploadUrl = host + '/wtc/kcfinder/upload.php?type=images';
   config.filebrowserFlashUploadUrl = host + '/wtc/kcfinder/upload.php?type=flash';
    
	config.toolbarGroups = [
		{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
		
		
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
		
		{ name: 'insert', groups: [ 'insert' ] },
		'/',
		{ name: 'styles', groups: [ 'styles' ] },
		{ name: 'colors', groups: [ 'colors' ] },
		{ name: 'tools', groups: [ 'tools' ] },
		{ name: 'others', groups: [ 'others' ] },
		{ name: 'about', groups: [ 'about' ] }
	];

	config.removeButtons = 'HiddenField,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton';
        config.height = 900;
        
};