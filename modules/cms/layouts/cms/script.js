function eveFilesBrowser (field_name, url, type, win) {

	alert("Field_Name: " + field_name + "nURL: " + url + "nType: " + type + "nWin: " + win); // debug/testing
	//return false;

	/* If you work with sessions in PHP and your client doesn't accept cookies you might need to carry
       the session name and session ID in the request string (can look like this: "?PHPSESSID=88p0n70s9dsknra96qhuk6etm5").
       These lines of code extract the necessary parameters and add them back to the filebrowser URL again. */


	// newer writing style of the TinyMCE developers for tinyMCE.openWindow
	tinyMCE.activeEditor.windowManager.open({
	    file : '/cms/fileBrowser' + "?type=" + type
	   ,width : 600
	   ,height : 600
	   ,resizable : "yes"
	   ,inline : "yes"
	   ,close_previous : "no"
	   ,popup_css : false // Disable TinyMCE's default popup CSS
	}, {
	    window : win,
	    input : field_name
	});
	return false;
	
	tinyMCE.openWindow({
		file : '/cms/fileBrowser' + "?type=" + type, // PHP session ID is now included if there is one at all
		title : "File Browser",
		width : 420,  // Your dimensions may differ - toy around with them!
		height : 400,
		close_previous : "no"
	}, {
		window : win,
		input : field_name,
		resizable : "yes",
		inline : "yes",  // This parameter only has an effect if you use the inlinepopups plugin!
		editor_id : tinyMCE.selectedInstance.editorId
	});
	return false;
}

$().ready(function(){
	
	
	tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "tinymce",
        height : "700",
        file_browser_callback : "eveFilesBrowser"
	});
	
	$('#menusForm .fieldType select').change(function(){
		$('#menusForm .fieldContent').toggle($(this).val() == 'content');
		$('#menusForm .fieldExternal').toggle($(this).val() == 'external');
	});
	$('#menusForm .fieldType select').change();
	
	$('.fancy').fancybox();

});