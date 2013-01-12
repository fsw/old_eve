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
        plugins : "bbcode,table",
        editor_selector : "tinymce",
        theme : "advanced",
        theme_advanced_buttons1 : "undo, redo, removeformat, separator, bold, italic, underline, strikethrough, separator, sub, sup, separator, forecolor, backcolor, separator, visualaid, charmap, code",
        theme_advanced_buttons2 : "bullist, numlist, separator, hr, separator, tablecontrols, table, row_props, cell_props, delete_col, delete_row, col_after, col_before, row_after, row_before, split_cells, merge_cells",
        theme_advanced_buttons3 : "",
        height : "700",
        file_browser_callback : "eveFilesBrowser",
        relative_urls : false,
        force_p_newlines : false,
        force_br_newlines : true,
        convert_newlines_to_brs : false,
	});
	
	$('#menusForm .fieldType select').change(function(){
		$('#menusForm .fieldContent').toggle($(this).val() == 'content');
		$('#menusForm .fieldExternal').toggle($(this).val() == 'external');
	});
	$('#menusForm .fieldType select').change();
	
	$('.fancy').fancybox();

});