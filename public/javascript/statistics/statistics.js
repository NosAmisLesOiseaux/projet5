$( function() {
    var currentYear = year.dataset.year;

    $.getJSON('https://road-web.fr/api/datastatistics/'+ currentYear +'/', function( data ) {

            $("button").click(function(e)
            { 
                var val = this.dataset.valbutton; 
                $(this).hide();

                $('#statisticsByRegion'+ val+'').pagination({
                    dataSource: 'https://road-web.fr/api/datastatistics/'+ currentYear +'/',
                    locator: 'items',
                    pageSize: 1,
                    totalNumber: data[0].regions[val].numberOfBirds,
                    autoHidePrevious: true,
                    autoHideNext: true,  
            
                    callback: function(data, pagination) {
                        var numberOfBirds = data[0].regions[val].numberOfBirds;
                        var region = data[0].regions[val];
                        var currentPage = $('#statisticsByRegion'+ val+'').data('pagination').model.pageNumber;
                        var pageSize = $('#statisticsByRegion'+ val+'').data('pagination').model.pageSize
                        var start = (currentPage * pageSize) - pageSize;
                        var limit = start + pageSize;
                        var totalPage = Math.ceil(numberOfBirds / pageSize);

                        $('#data'+ val +'').next().attr('id', 'pagination'+ val +'');
                        if (totalPage <= 1)
                        {
                            $('#pagination'+ val +'').hide();
                        }

                        var birds = "";
                        if (numberOfBirds > 0)
                        {   
                            birds += '<ul>';
                            for (t = start; t < limit; t++)
                            {
                                birds += '<li>';
                                birds += region.birds[t];
                                birds += '</li>';
                            }
                            birds += '</ul>';
                        }

                        $('#data'+ val +'').html(birds);
                    }
                });

         })
    });
});