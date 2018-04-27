navigator.geolocation.getCurrentPosition(success, error);

function success(position) {

    var GEOCODING = 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + position.coords.latitude + '%2C' + position.coords.longitude + '&language=en';

    $.getJSON(GEOCODING).done(function(location) {
	//	$('#country').html(location.results[0].address_components[5].long_name);
	//	$('#state').html(location.results[0].address_components[4].long_name);
		$('#city').html(location.results[0].address_components[3].long_name);
	//	$('#address').html(location.results[0].formatted_address);
	})

}

function error(err) {
    console.log(err)
}