$( function() {

   var roles = userRole.dataset.userrole;

	$('#'+ roles +'_capture_latitude').parent().before('<div><input id="geolocation" type="button" value="Me géolocaliser"></div>');
	$('#'+ roles +'_capture_latitude').parent().hide();
	$('#'+ roles +'_capture_longitude').parent().hide();

	function myPosition(position) {
		$('#'+ roles +'_capture_latitude').parent().show();
		$('#'+ roles +'_capture_longitude').parent().show(); 
		$('#'+ roles +'_capture_latitude').val(position.coords.latitude);
		$('#'+ roles +'_capture_longitude').val(position.coords.longitude);

		//Ajout de l'adresse à partir de la latitude et de la longitude
		//$.getJSON('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=' + position.coords.latitude + '&lon=' + position.coords.longitude + '', function( data ) {
		$.getJSON('https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=49.588633599999994&lon=1.3508608', function( data ) {
			console.log(data.address);
			var address = data.address.house_number + ' ' + data.address.road;
			var zipcode = data.address.postcode;
			var town = town ? data.address.town : data.address.village;
			var region = data.address.state;
			$('#'+ roles +'_capture_address').val(address);
			$('#'+ roles +'_capture_zipcode').val(zipcode);
			$('#'+ roles +'_capture_city').val(town);
			$('#'+ roles +'_capture_region').val(region);
		});
	}

	$("#geolocation").click(function()
	{
		if(navigator.geolocation)
		{
	  		navigator.geolocation.getCurrentPosition(myPosition, errorPosition);
	  	}
	  	else 
	  	{
    		alert("Ce navigateur ne supporte pas la géolocalisation");
		}
	})

	function errorPosition(error) {
	    var info = "Erreur lors de la géolocalisation : ";
	    switch(error.code) {
		    case error.TIMEOUT:
		    	info += "Timeout !";
		    break;
		    case error.PERMISSION_DENIED:
			info += "Vous n’avez pas donné la permission";
		    break;
		    case error.POSITION_UNAVAILABLE:
		    	info += "La position n’a pu être déterminée";
		    break;
		    case error.UNKNOWN_ERROR:
			info += "Erreur inconnue";
		    break;
	    }
    	$('#geolocation').after('<div><p id="errorPosition"></p></div>');
		document.getElementById("errorPosition").innerHTML = info;
		$('#geolocation').hide();
	}

	// Ajout de la latitude et de la latitude à partir de l'adresse
	$('#'+ roles +'_capture_Enregistrer').click(function()
	{
		if ($('#'+ roles +'_capture_latitude').val() == 0)
		{
			var address = $('#'+ roles +'_capture_address').val();
			var complement = $('#'+ roles +'_capture_complement').val();
			var city = $('#'+ roles +'_capture_city').val();
			$.getJSON('https://nominatim.openstreetmap.org/search?q='+ address + ' ' + complement +  ' ' + city +'&format=json&polygon=1&addressdetails=1',  function( data ) {
				var latitude = data[0].lat;
				var longitude = data[0].lon;
				$('#'+ roles +'_capture_latitude').val(latitude);
				$('#'+ roles +'_capture_longitude').val(longitude);
			})
		}
	})
});