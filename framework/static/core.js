$(function(){

	$('.apiCall').click(function(e){ 
		$.get($(this).attr('href'));
		//TODO:
		//e.preventDefault();
		//e.stopImmediatePropagation();
		return false;

	});
	
});