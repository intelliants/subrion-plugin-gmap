var $elems = [];

function mapInit() {
    $(document).trigger('mapsLoaded');
    $elems.each(function () {
        showMap($(this));
    });
}

function showMap(elem) {
    var $this = elem,
        mapFor = ('author' == $this.data('map-for')) ? 'author' : 'item';

    if ('author' != mapFor) {
        if ($('#fieldgroup_ypage_location').length > 0) {
            $this.appendTo('#fieldgroup_ypage_location');
        } else {
            $this.appendTo('#fieldgroup_location');
        }
    }

    $this.removeClass('hidden');

    // the map
    var lat = $this.data('lat'),
        lng = $this.data('lng'),
        latLng = {},
        fullAddress = $this.data('address') + ' ' + $this.data('city') + ', ' + $this.data('state') + ' ' + $this.data('zip') + ', ' + $this.data('country');

    var placeMap = function () {
        var map = new google.maps.Map(elem.get(0), {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: $this.data('zoom'),
                center: latLng
            }),
            marker = new google.maps.Marker({
                position: latLng,
                map: map
            }),
            infowindow = new google.maps.InfoWindow({
                content: '<div class="gmap-infowindow"><span><b><a href="' + $this.data('url') + '">' + $this.data('title') + '</a></b></span>' + '<span>' + $this.data('description') + '</span></div>'
            });

        marker.addListener('click', function () {
            infowindow.open(map, marker);
        });
    }

    if ('' != lat && '' != lng) {
        latLng = {lat: lat, lng: lng}
        placeMap();
    } else {
        var geocoder = new google.maps.Geocoder();

        if ('' != fullAddress.split(' ').join('').replace(/\,/g, '')) {
            geocoder.geocode({'address': fullAddress}, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    latLng = {
                        lat: parseFloat(results[0].geometry.location.lat()),
                        lng: parseFloat(results[0].geometry.location.lng())
                    }

                    placeMap();
                }
            });
        }
    }
}

function loadScript(url, handler) {
    var tag = document.createElement('script');

    tag.type = 'text/javascript';
    tag.src = url;
    tag.onreadystatechange = function () {
        if (this.readyState == 'complete' || this.readyState == 'loaded') this.onload({target: this});
    };
    tag.onload = handler;

    document.getElementsByTagName('head')[0].appendChild(tag);
}

$(function () {
    $elems = $('.js-gmap');

    if (typeof google === 'object' && typeof google.maps === 'object') {
        mapInit();
    } else {
        if (intelli.config.maps_api_key) {
            loadScript('//maps.googleapis.com/maps/api/js?key=' + intelli.config.maps_api_key + '&callback=mapInit');
        } else {
            loadScript('//maps.googleapis.com/maps/api/js?callback=mapInit');
        }
    }
});