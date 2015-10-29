{if isset($gmap_data.author) && $gmap_data.author && ($gmap_data.author.city || $gmap_data.author.address)}
	<div class="ia-item-author__location">
		{ia_block name='gmap' header=false}
			<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
			<div class="js-gmap-data hidden"
				 data-item-id="{$gmap_data.author.id}"
				 data-title="{$gmap_data.author.fullname}"
				 data-description="{$gmap_data.author.description}"

				 data-zip="{$gmap_data.author.zipcode}"
				 data-country="{$gmap_data.author.country}"
				 data-state="{$gmap_data.author.state}"
				 data-city="{$gmap_data.author.city}"
				 data-address="{$gmap_data.author.address}"
				 data-lat="{$gmap_data.author.latitude}"
				 data-lng="{$gmap_data.author.longitude}"

				 data-url="{ia_url type='url' item='members' data=$gmap_data.author}"
				 data-zoom="15">
			</div>
			<div id="js-gmap-renderer" class="full-width hidden"></div>
		{/ia_block}

		{ia_print_css files='_IA_URL_plugins/gmap/templates/common/css/style'}
		{ia_print_js files='_IA_URL_plugins/gmap/js/frontend/google-map' order=3}
	</div>
{/if}