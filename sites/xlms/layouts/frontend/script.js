$().ready(function(){

	var params = {
			onComplete : function () {
					$('#fancybox-content .datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
			}
	};

	$('#addButton').fancybox(params);
	$('.projectLink').fancybox(params);
	$('.ticketLink').fancybox(params);

	$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
});