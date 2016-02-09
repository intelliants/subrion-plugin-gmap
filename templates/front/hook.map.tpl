{if isset($gmap_data.listing) && $gmap_data.listing && ($gmap_data.listing.city || $gmap_data.listing.address)}
	<div class="gmap-renderer js-gmap hidden"
		 data-map-for="item"
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

	{ia_print_css files='_IA_URL_plugins/gmap/templates/front/css/style'}
	{ia_print_js files='_IA_URL_plugins/gmap/js/frontend/google-map' order=3}
{/if}