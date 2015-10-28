{if isset($gmap_data) && ($gmap_data.listing.city || $gmap_data.listing.address)}
	{ia_block name='gmap' header=false}
		<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
		<div class="gmap-data hidden" id="item-gmap-data"
			data-item-id="{$gmap_data.id}"
			data-title="{$gmap_data.title}"
			data-description="{if isset($gmap_data.description)}{$gmap_data.description|strip_tags|truncate:250}{/if}"

			data-zip="{$gmap_data.zipcode}"
			data-country="{$gmap_data.country}"
			data-state="{$gmap_data.state}"
			data-city="{$gmap_data.city}"
			data-address="{$gmap_data.address}"
			data-lat="{$gmap_data.latitude}"
			data-lng="{$gmap_data.longitude}"

			data-url="{ia_url type='url' item=$gmap_data.item data=$gmap_data.listing}"
			data-zoom="15">
		</div>
		<div id="js-gmap-renderer" class="full-width hidden"></div>
	{/ia_block}

	{ia_print_css files='_IA_URL_plugins/gmap/templates/common/css/style'}
	{ia_print_js files='_IA_URL_plugins/gmap/js/frontend/google-map' order=3}
{/if}