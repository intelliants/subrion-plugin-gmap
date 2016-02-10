intelli.gm = function()
{
	var paramsEl = document.getElementById('js-gm-data');
	var options = {zoom: 8, center: new google.maps.LatLng(0, 0)};

	this.data = JSON.parse(paramsEl.innerHTML);

	if (1 == this.data.length)
	{
		options.mapTypeId = google.maps.MapTypeId.HYBRID
	}

	this.map = new google.maps.Map(document.getElementById('js-gm-canvas'), options);

	var self = this;

	var bounds = new google.maps.LatLngBounds();
	var i;

	for (i in this.data)
	{
		var entry = self.data[i];
		_addMarker(i, new google.maps.LatLng(entry.lat, entry.lng));
	}

	function _composeInfoWindowContent(entry)
	{
		return '<strong>' + entry.title + '</strong>';
	}

	function _mapCenterAndZoom(coords)
	{
		self.map.setCenter(coords);

		var maxZoomService = new google.maps.MaxZoomService();
		maxZoomService.getMaxZoomAtLatLng(coords, function(response)
		{
			if (google.maps.MaxZoomStatus.OK == response.status)
			{
				self.map.setZoom(response.zoom - 1);
			}
		});
	}

	function _addMarker(entryIdx, coords)
	{
		var entry = self.data[entryIdx];

		var options = {animation: google.maps.Animation.DROP, map: self.map, position: coords, title: entry.title};

		var marker = new google.maps.Marker(options);
		var infowindow = new google.maps.InfoWindow({content: _composeInfoWindowContent(entry)});

		google.maps.event.addListener(marker, 'click', function()
		{
			infowindow.open(self.map, marker);
		});

		bounds.extend(coords);

		if (entryIdx == (self.data.length - 1))
		{
			(1 == self.data.length)
				? _mapCenterAndZoom(coords)
				: self.map.fitBounds(bounds);
		}
	}

	return self;
};

google.maps.event.addDomListener(window, 'load', intelli.gm);