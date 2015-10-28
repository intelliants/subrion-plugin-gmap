$(function()
{
    intelli.marker = null;
	var mapInfo = $('#item-gmap-data');
	if (mapInfo.length)
	{
		if ($('#fieldgroup_ypage_location').length > 0)
		{
			$('#js-gmap-renderer').appendTo('#fieldgroup_ypage_location');
		}
		else
		{
			$('#js-gmap-renderer').appendTo('#fieldgroup_location');
		}

		$('#js-gmap-renderer').removeClass('hidden');

		var map = new google.maps.Map(document.getElementById('js-gmap-renderer'), {mapTypeId: google.maps.MapTypeId.ROADMAP});
		var bounds = new google.maps.LatLngBounds();
		var geocoder = new google.maps.Geocoder();
		var infowindows = [];
		var markersCount = mapInfo.length;
		var address = mapInfo.data('address');
		var city = mapInfo.data('city');
		var state = mapInfo.data('state');
		var zip = mapInfo.data('zip');
		var country = mapInfo.data('country');
		var title = mapInfo.data('title');
		var description = mapInfo.data('description');
		var url = mapInfo.data('url');

		var fullAddress = address + ' ' + city + ', ' + state + ' ' + zip + ', ' + country;
		var lat = mapInfo.data('lat');
		var lng = mapInfo.data('lng');
		var zoom = mapInfo.data('zoom');

		if ('' != lat && 'undefined' != typeof lat && '' != lng && 'undefined' != typeof lng)
		{
			var html = '';
			var point =	new google.maps.LatLng(lat * 1, lng * 1);

			intelli.marker = new google.maps.Marker({
				position: point, 
				map: map,
				title: title
			});
			intelli.marker.setMap(map);
			bounds.extend(point);
			if ('' != zoom)
			{
				map.setZoom(zoom * 1);
			}
			if (markersCount > 1)
				map.fitBounds(bounds);
			else
				map.setCenter(point);

			infowindows = new google.maps.InfoWindow(
			{
				content: getInfoWindowContent({url: url, title: title, description: description ? description : ''})
			});

			google.maps.event.addListener(intelli.marker, 'click', function()
			{
				$.each(infowindows, function(a, b)
				{
					if (b)
					{
						b.close();
					}
				});
				infowindows.open(map, intelli.marker);
			});
		}
		else
		{
			if ('' != fullAddress.split(' ').join('').replace(/\,/g, ''))
			{
				geocoder.geocode({'address': fullAddress}, function(results, status)
				{
					if (status == google.maps.GeocoderStatus.OK)
					{
						intelli.marker = new google.maps.Marker({
							map: map,
							position: results[0].geometry.location
						});
						bounds.extend(results[0].geometry.location);
						if ('' != zoom)
						{
							map.setZoom(zoom * 1);
						}
						if (markersCount > 1)
							map.fitBounds(bounds);
						else
							map.setCenter(results[0].geometry.location);
						infowindows = new google.maps.InfoWindow({
							content: getInfoWindowContent({url: url, title: title, description: description})
						});

						google.maps.event.addListener(intelli.marker, 'click', function()
						{
							infowindows.open(map, intelli.marker);
						});
					}
				});
			}
		}
	}

	intelli.map = map;

	$("a[href='#tab-fieldgroup_location']").click(function()
	{
		setTimeout(function()
		{
			google.maps.event.trigger(intelli.map, 'resize');

			if (intelli.marker !== null)
			{
				intelli.map.setCenter(intelli.marker.getPosition());
			}
		}, 300);
	});

	function getInfoWindowContent(o)
	{
		if (o.title) html = '<h3><a href="' + o.url + '"><strong>' + o.title +'</strong></a></h3><p>' + o.description + '</p>';
		else html = '<p><a href="' + o.url + '">' + o.description + '</a></p>';
		return '<div style="width: 250px">' + html + '</div>';
	}
});