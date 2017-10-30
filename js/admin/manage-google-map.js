$(function () {
    if (typeof google === 'object' && typeof google.maps === 'object') {
        intelli.marker = null;
        var mapInfo = $('#item-gmap-data');

        var itemPosition = {
            lat: $('input[name="latitude"]', mapInfo).val(),
            lng: $('input[name="longitude"]', mapInfo).val()
        };

        $('#address_fieldzone').parent().append($('#js-gmap-wrapper'));
        $('#js-gmap-wrapper').removeClass('hidden');

        var map = new google.maps.Map(document.getElementById('js-gmap-renderer'), {mapTypeId: google.maps.MapTypeId.ROADMAP});
        var geocoder = new google.maps.Geocoder(),
            geoOptions = {};

        if (itemPosition.lat.length && itemPosition.lng.length) {
            map.setZoom(11);
            geoOptions = {latLng: new google.maps.LatLng(parseFloat(itemPosition.lat), parseFloat(itemPosition.lng))};
        }
        else {
            map.setZoom(3);
            geoOptions = {address: 'USA'};
        }

        geocoder.geocode(geoOptions, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                intelli.marker = new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    draggable: true
                });

                map.setCenter(results[0].geometry.location);

                google.maps.event.addListener(
                    intelli.marker,
                    'drag',
                    function () {
                        $('input[name="longitude"]', mapInfo).val(intelli.marker.position.lng());
                        $('input[name="latitude"]', mapInfo).val(intelli.marker.position.lat());
                    }
                );
            }
        });

        intelli.geocoder = geocoder;
        intelli.map = map;

        google.maps.event.trigger(intelli.map, 'resize');
        if (intelli.marker !== null) {
            intelli.map.setCenter(intelli.marker.getPosition());
        }

        var $fieldGroup = $('#address_fieldzone').parent();

        $('input[type="text"]', $fieldGroup).on('input', function () {
            var fullAddress = '';

            $.each($('input[type="text"]', $fieldGroup), function () {
                if ('' !== $(this).val()) {
                    fullAddress += ' ' + $(this).val();
                }
            });

            intelli.geocoder.geocode({address: fullAddress}, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;

                    intelli.marker.setPosition(location);
                    map.setCenter(location);

                    $('input[name="longitude"]', mapInfo).val(location.lng());
                    $('input[name="latitude"]', mapInfo).val(location.lat());
                }
            });
        });
    }
});