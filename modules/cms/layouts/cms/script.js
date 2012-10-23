$().ready(function(){
	
	tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "tinymce"
	});
	
	$('#menusForm .fieldType select').change(function(){
		$('#menusForm .fieldContent').toggle($(this).val() == 'content');
		$('#menusForm .fieldExternal').toggle($(this).val() == 'external');
	});
	$('#menusForm .fieldType select').change();
});