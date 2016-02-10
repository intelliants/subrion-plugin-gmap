{if isset($listingsOnMap)}
	<div id="js-gm-canvas" style="height: 300px"></div>
	<div style="display: none" id="js-gm-data">{$listingsOnMap}</div>

	<script src="https://maps.googleapis.com/maps/api/js"></script>
	{ia_print_js files='_IA_URL_plugins/gmap/js/frontend/map-block'}
{/if}