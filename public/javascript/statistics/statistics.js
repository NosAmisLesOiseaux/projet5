$( function() {
    var currentYear = year.dataset.year;

    $.getJSON('http://localhost:8000/api/datastatistics/'+ currentYear +'/', function( data ) {

        for (i=0; i < data[0].regions.length; i++)
        {
            (function (i) {
                $('#statisticsByRegion'+ i+'').pagination({
                    dataSource: 'http://localhost:8000/api/datastatistics/'+ currentYear +'/',
                    locator: 'items',
                    pageSize: 1,
                    totalNumber: data[0].regions[i].numberOfBirds,
                    autoHidePrevious: true,
                    autoHideNext: true,

                    callback: function(data) {
                        var numberOfBirds = data[0].regions[i].numberOfBirds;
                        var region = data[0].regions[i];
                        var currentPage = $('#statisticsByRegion'+ i+'').data('pagination').model.pageNumber;
                        var pageSize = $('#statisticsByRegion'+ i+'').data('pagination').model.pageSize
                        var start = (currentPage * pageSize) - pageSize;
                        var limit = start + pageSize;
                        var totalPage = Math.ceil(numberOfBirds / pageSize);

                        var birds = "";
                        if (numberOfBirds == 1)
                        {
                            birds += '<p>' + numberOfBirds + ' espèce d\'oiseau observée :</p>';
                        }
                        else if (numberOfBirds > 1)
                        {
                            birds += '<p>' + numberOfBirds + ' espèces d\'oiseaux observées :</p>';
                        }
                        else
                        {
                            birds += '<p> Aucune espèce d\'oiseau observée</p>';
                        }

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

                        $('#data'+ i +'').html(birds);

                        $('#data'+ i +'').next().attr('id', 'pagination'+ i +'');

                        if (totalPage <= 1)
                        {
                            $('#pagination'+ i +'').hide();
                        }
                    }
                })
            })(i);
        }
    })
});