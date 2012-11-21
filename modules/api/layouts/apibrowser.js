$(function(){
	$('.methodForm').submit(function(){
		var responseDiv = $(this).find('.response');
		var data = {args : {}};
		$(this).find('input').each(function(id,elem){
			data.args[$(elem).attr('name')] = $(elem).val();
		});
		$.ajax({
			type: $(this).attr('method'),
			url: $(this).attr('action'),
			data: data,
			dataType: 'text',
			success: function(data){
				responseDiv.text(data);
			},
		});
		return false;
	});
});