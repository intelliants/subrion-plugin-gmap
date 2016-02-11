{if isset($listingsOnMap)}
	<div id="js-gm-canvas" style="height: 300px"></div>
	<div style="display: none" id="js-gm-data"{if isset($core.page.info.location) && is_string($core.page.info.location)} data-location="{$core.page.info.location|escape:'html'}"{/if}>{$listingsOnMap}</div>

	<script src="https://maps.googleapis.com/maps/api/js"></script>
	{ia_print_js files='_IA_URL_plugins/gmap/js/frontend/map-block'}
{/if}