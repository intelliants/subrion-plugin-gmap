var gmStyles =
    {
        'blue-water': [{
            featureType: 'water',
            stylers: [{color: '#46bcec'}, {visibility: 'on'}]
        }, {featureType: 'landscape', stylers: [{color: '#f2f2f2'}]}, {
            featureType: 'road',
            stylers: [{saturation: -100}, {lightness: 45}]
        }, {featureType: 'road.highway', stylers: [{visibility: 'simplified'}]}, {
            featureType: 'road.arterial',
            elementType: 'labels.icon',
            stylers: [{visibility: 'off'}]
        }, {
            featureType: 'administrative',
            elementType: 'labels.text.fill',
            stylers: [{color: '#444444'}]
        }, {featureType: 'transit', stylers: [{visibility: 'off'}]}, {
            featureType: 'poi',
            stylers: [{visibility: 'off'}]
        }],
        'midnight-commander': [{featureType: 'water', stylers: [{color: '#021019'}]}, {
            featureType: 'landscape',
            stylers: [{color: '#08304b'}]
        }, {
            featureType: 'poi',
            elementType: 'geometry',
            stylers: [{color: '#0c4152'}, {lightness: 5}]
        }, {
            featureType: 'road.highway',
            elementType: 'geometry.fill',
            stylers: [{color: '#000000'}]
        }, {
            featureType: 'road.highway',
            elementType: 'geometry.stroke',
            stylers: [{color: '#0b434f'}, {lightness: 25}]
        }, {
            featureType: 'road.arterial',
            elementType: 'geometry.fill',
            stylers: [{color: '#000000'}]
        }, {
            featureType: 'road.arterial',
            elementType: 'geometry.stroke',
            stylers: [{color: '#0b3d51'}, {lightness: 16}]
        }, {
            featureType: 'road.local',
            elementType: 'geometry',
            stylers: [{color: '#000000'}]
        }, {elementType: 'labels.text.fill', stylers: [{color: '#ffffff'}]}, {
            elementType: 'labels.text.stroke',
            stylers: [{color: '#000000'}, {lightness: 13}]
        }, {featureType: 'transit', stylers: [{color: '#146474'}]}, {
            featureType: 'administrative',
            elementType: 'geometry.fill',
            stylers: [{color: '#000000'}]
        }, {
            featureType: 'administrative',
            elementType: 'geometry.stroke',
            stylers: [{color: '#144b53'}, {lightness: 14}, {weight: 1.4}]
        }],
        'pale-down': [{
            featureType: 'water',
            stylers: [{visibility: 'on'}, {color: '#acbcc9'}]
        }, {featureType: 'landscape', stylers: [{color: '#f2e5d4'}]}, {
            featureType: 'road.highway',
            elementType: 'geometry',
            stylers: [{color: '#c5c6c6'}]
        }, {
            featureType: 'road.arterial',
            elementType: 'geometry',
            stylers: [{color: '#e4d7c6'}]
        }, {
            featureType: 'road.local',
            elementType: 'geometry',
            stylers: [{color: '#fbfaf7'}]
        }, {
            featureType: 'poi.park',
            elementType: 'geometry',
            stylers: [{color: '#c5dac6'}]
        }, {
            featureType: 'administrative',
            stylers: [{visibility: 'on'}, {lightness: 33}]
        }, {featureType: 'road'}, {
            featureType: 'poi.park',
            elementType: 'labels',
            stylers: [{visibility: 'on'}, {lightness: 20}]
        }, {}, {featureType: 'road', stylers: [{lightness: 20}]}],
        'retro': [{featureType: 'administrative', stylers: [{visibility: 'off'}]}, {
            featureType: 'poi',
            stylers: [{visibility: 'simplified'}]
        }, {featureType: 'road', elementType: 'labels', stylers: [{visibility: 'simplified'}]}, {
            featureType: 'water',
            stylers: [{visibility: 'simplified'}]
        }, {featureType: 'transit', stylers: [{visibility: 'simplified'}]}, {
            featureType: 'landscape',
            stylers: [{visibility: 'simplified'}]
        }, {featureType: 'road.highway', stylers: [{visibility: 'off'}]}, {
            featureType: 'road.local',
            stylers: [{visibility: 'on'}]
        }, {featureType: 'road.highway', elementType: 'geometry', stylers: [{visibility: 'on'}]}, {
            featureType: 'water',
            stylers: [{color: '#84afa3'}, {lightness: 52}]
        }, {stylers: [{saturation: -17}, {gamma: 0.36}]}, {
            featureType: 'transit.line',
            elementType: 'geometry',
            stylers: [{color: '#3f518c'}]
        }],
        'subtle-grayscale': [{
            featureType: 'landscape',
            stylers: [{saturation: -100}, {lightness: 65}, {visibility: 'on'}]
        }, {
            featureType: 'poi',
            stylers: [{saturation: -100}, {lightness: 51}, {visibility: 'simplified'}]
        }, {
            featureType: 'road.highway',
            stylers: [{saturation: -100}, {visibility: 'simplified'}]
        }, {
            featureType: 'road.arterial',
            stylers: [{saturation: -100}, {lightness: 30}, {visibility: 'on'}]
        }, {
            featureType: 'road.local',
            stylers: [{saturation: -100}, {lightness: 40}, {visibility: 'on'}]
        }, {
            featureType: 'transit',
            stylers: [{saturation: -100}, {visibility: 'simplified'}]
        }, {featureType: 'administrative.province', stylers: [{visibility: 'off'}]}, {
            featureType: 'water',
            elementType: 'labels',
            stylers: [{visibility: 'on'}, {lightness: -25}, {saturation: -100}]
        }, {
            featureType: 'water',
            elementType: 'geometry',
            stylers: [{hue: '#ffff00'}, {lightness: -25}, {saturation: -97}]
        }]
    };