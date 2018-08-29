$( function() {
    var id = map0.dataset.identifiant;
    var numberComments = numbercomments.dataset.number;
    $('#comments').pagination({
        dataSource: 'http://localhost:8000/api/capturepublishedcomments/'+ id +'/',
        locator: 'items',
        pageSize: 1,
        totalNumber: numberComments,
        autoHidePrevious: true,
        autoHideNext: true,   
    },
        callback: function(data, pagination) {
            var currentPage = $('#comments').data('pagination').model.pageNumber;
            var pageSize = $('#comments').data('pagination').model.pageSize;
            var start = (currentPage * pageSize) - pageSize;
            var limit = start + pageSize;
            var totalPage = Math.ceil(numberComments / pageSize);

            var html = "";
            for (i = start; i < limit; i++)
            {
                html += '<li>';
                if (data[i].authorAvatar != null) {html += '<img src="'+ data[i].authorAvatar +'">';}
                html += '<p>'+ data[i].authorFirstname + ' ' + data[i].authorLastname +'</p>';
                html += '<p>'+ data[i].content +'</p>';
                html += '<form action="/signaler-commentaire/' + data[i].id +'/" method="get">';
                html += '<input id="reportcomment" type="submit" value="Signaler le commentaire">';
                html += '</form>';
                html += '</li>';
            }
            $('#comment').html(html);

            if (totalPage <= 1)
            {
                $('.paginationjs').hide();
            }
        }
    })
});