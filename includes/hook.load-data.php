<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	if ($iaView->blockExists('listings_on_map'))
	{
		$params = array(
			'items' => array(),
			'language' => $iaView->language,
			'location' => is_string($iaView->get('location')) ? $iaView->get('location') : null,
			'style' => $iaCore->get('gmap_style')
		);

		if ($listings = $iaView->getValues('listings'))
		{
			foreach ($listings as $listing)
			{
				if (!empty($listing['latitude']) && !empty($listing['longitude']))
				{
					$entry = array(
						'lat' => $listing['latitude'],
						'lng' => $listing['longitude'],

						'title' => isset($listing['title']) ? $listing['title'] : null
					);

					if (is_null($entry['title']))
					{
						empty($listing['venue_title']) || $entry['title'] = $listing['venue_title'];
					}

					$params['items'][] = $entry;
				}
			}

			$params['items'] = iaUtil::jsonEncode($params['items']);
		}

		$iaView->assign('gmap', $params);

		if ('default' != $params['style'])
		{
			$iaView->add_js('_IA_URL_plugins/gmap/js/frontend/styles');
		}
	}
}