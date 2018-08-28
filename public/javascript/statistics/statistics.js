$( function() {

	var currentYear = year.dataset.year;

	$.getJSON('http://localhost:8000/api/datastatistics/'+ currentYear +'/', function( data ) {
		var stats = "";
		for (i=0; i < data[0].regions.length; i++)
		{
			stats += '<h3>' + data[0].regions[i].region +'</h3>';
			stats += '<p>' + data[0].regions[i].numberOfBirds + ' espèces d\'oiseaux observées :</p>';
			$('#statisticsData').html(stats);

			var numberOfBirds = data[0].regions[i].numberOfBirds;
			var region = data[0].regions[i];

			$('#statisticsByYear').pagination({
        		dataSource: 'http://localhost:8000/api/datastatistics/'+ currentYear +'/',
        		locator: 'items',
        		pageSize: 1,
        		totalNumber: numberOfBirds,
        		autoHidePrevious: true,
        		autoHideNext: true,   
        		ajax: {
            		beforeSend: function() {
                		dataContainer.html('Loading data from flickr.com ...');
        			}
    			},
        	
        		callback: function(data, pagination) {
            		var currentPage = $('#statisticsByYear').data('pagination').model.pageNumber;
            		var pageSize = $('#statisticsByYear').data('pagination').model.pageSize
            		var start = (currentPage * pageSize) - pageSize;
            		var limit = start + pageSize;
            		var totalPage = Math.ceil(numberOfBirds / pageSize);

            		var birds = "";
            		birds += '<ul>';
            		for (t = start; t < limit; t++)
            		{
                		birds += '<li>';
						birds += region.birds[t];
						birds += '</li>';
            		}
            		birds += '</ul>';

            		$('#statisticsBirds').html(birds);

            		if (totalPage <= 1)
            		{
                		$('.paginationjs').hide();
            		}
        		}
    		})
		}
	})
});