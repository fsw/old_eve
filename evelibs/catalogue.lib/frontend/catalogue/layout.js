$(function(){
	
	$('#searchForm').submit(function(){
		var url = $(this).attr('action');
		url = url.replace('__CATEGORY__', $(this).find('[name=category]').attr('disabled', 'disabled').val());
		url = url.replace('__REGION__', $(this).find('[name=region]').attr('disabled', 'disabled').val());
		$(this).attr('action', url);
	});
	
	$('#advancedSearchForm').submit(function(){
		var url = $(this).attr('action');
		url = url.replace('__CATEGORY__', $(this).find('[name=category]').attr('disabled', 'disabled').val());
		url = url.replace('__REGION__', $(this).find('[name=region]').attr('disabled', 'disabled').val());
		$(this).attr('action', url);
	});
	
	$('#polandMap').cssMap({
		  'size': 340,
		  'cities': true,
		  'tooltips': 'floating-bottom-right',
		  'onClick': function(e){
			  var link = e.children('a').attr('href').substr(1);
			  $('#advancedSearchForm').find('[name=region]').val(link);
			  return false;
		  }
	});
	
	$('#showAdvanced').click(function(){
		$('#searchForm').slideUp();
		$('#advancedSearchForm').slideDown();
		$('#showAdvanced').hide();
		$('#showSimple').show();
		return false;
	});
	
	$('#showSimple').click(function(){
		$('#searchForm').slideDown();
		$('#advancedSearchForm').slideUp();
		$('#showAdvanced').show();
		$('#showSimple').hide();
		return false;
	});
	
	$('.rating .rate').each(function(){
		$(this).width(($(this).text() / 5.0) * 135);
		$(this).text('');
		$(this).fadeIn();
	});
});