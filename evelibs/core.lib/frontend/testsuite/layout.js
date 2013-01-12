$(function(){
	
	$('.suite').hide();
	$('.suite').first().show();
	
	$('.navigation li a').click( function(){
		$('.suite').hide();
		$($(this).attr('href')).parent().show();
		return false;
	});
	
	$('pre').hide();
	
	$('ul.tests h2').hover( function(){
		$('pre').hide();
		$(this).next().show();
		return false;
	});
});