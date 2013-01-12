
$(window).load(function(){
	$.get('/dev/footer.html', function(data) {
		$('body').append(data);
		
		//Cache switchers
		$('.devUseCache').each(function(i, e){
			if ($.cookie($(e).attr('name')))
			{
				$(e).attr('checked', 'checked');
			}
		});
		$('.devUseCache').click(function(){
			$.cookie($(this).attr('name'), $(this).is(':checked') ? 'true' : null, {path:'/'});
		});
		
		$('#devStats').hover(function(){
			var id = 0;
			$('.pieChart').each(function(id, elem){
				id++;
				$(elem).attr('id', 'chart' + id);
				var data = $.parseJSON($(elem).next().text());
				$(elem).next().hide();
				var chartData = [];
				var chartLabels = [];
				$.each(data, function(key, value){
					chartLabels.push(key);
					chartData.push(value);
				});
				var pie = new RGraph.Pie('chart' + id, chartData);
	            pie.Set('chart.labels', chartLabels);
	            pie.Set('chart.shadow', true);
	            pie.Set('chart.shadow.offsetx', 0);
	            pie.Set('chart.shadow.offsety', 0);
	            pie.Set('chart.shadow.blur', 15);
	            pie.Set('chart.strokestyle', 'transparent');
	            //pie.Set('chart.exploded', [15,15]);
	            pie.Set('chart.tooltips', chartLabels);
	            pie.Draw();
			});
		});
	});
});
