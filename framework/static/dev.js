function devToggleCache(obj){
	$.cookie('use_cache', $(obj).prop('checked'));
}

$().ready(function() {
	
	$('#devfooter').jixedbar();
	
});