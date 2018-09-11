$( function() {
    
	var macarte = null;
    
    if (typeof(map0.dataset.identifiant) !== 'undefined')
    {
        var id = map0.dataset.identifiant;
        var url = 'latloncapture/'+id;
    }
    else 
    {
        var url = 'lastcaptures';
    }

	function initMap() {
        $.getJSON('http://road-web.fr/api/'+url+'/', function( data ) {
            for (i = 0; i < data.length; i++)
            {
                macarte = L.map('map'+i).setView([ data[i].latitude, data[i].longitude], 7);
                L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                    attribution: 'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                    minZoom: 1,
                    maxZoom: 20
                }).addTo(macarte);
                var marker = L.marker([data[i].latitude, data[i].longitude]).addTo(macarte);
            }
        })
    }
    
	window.onload = function()
    {   
        initMap(); 
	};
});
