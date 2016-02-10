<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	if ($iaView->blockExists('listings_on_map'))
	{
		$data = array();

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

					$data[] = $entry;
				}
			}
		}

		$data = iaUtil::jsonEncode($data);

		$iaView->assign('listingsOnMap', $data);
	}
}