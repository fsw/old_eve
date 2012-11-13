function eveFilesBrowser (field_name, url, type, win) {

	//alert("Field_Name: " + field_name + "nURL: " + url + "nType: " + type + "nWin: " + win); // debug/testing

	tinyMCE.activeEditor.windowManager.open({
	    file : '/cms-files/browser' + "?type=" + type
	   ,width : 600
	   ,height : 600
	   ,resizable : "yes"
	   ,inline : "yes"
	   ,close_previous : "no"
	   ,popup_css : false
	}, {
	    window : win,
	    input : field_name
	});
	return false;
}

$().ready(function(){
	
	
	tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "tinymce",
        height : "700",
        file_browser_callback : "eveFilesBrowser",
        relative_urls : false,
	});
	
	$('#menusForm .fieldType select').change(function(){
		$('#menusForm .fieldContent').toggle($(this).val() == 'content');
		$('#menusForm .fieldExternal').toggle($(this).val() == 'external');
	});
	$('#menusForm .fieldType select').change();
	
	$('.fancy').fancybox();

});