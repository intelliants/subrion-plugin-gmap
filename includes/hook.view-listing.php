<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	if (!isset($item))
	{
		return;
	}

	$mapData = array('id' => $listing, 'item' => $item, 'listing' => array());

	$iaItem = $iaCore->factory('item');
	$iaUsers = $iaCore->factory('users');

	if ($item == iaUsers::getItemName())
	{
		$itemData = $iaUsers->getInfo($listing);
	}
	else
	{
		$itemPackage = $iaItem->getPackageByItem($item);
		$itemClass = $iaCore->factoryPackage('item', $itemPackage, iaCore::FRONT, $item);
		$itemData = $itemClass->getById($listing);
	}

	// get author information
	if ($itemData && isset($itemData['member_id']) && $itemData['member_id'])
	{
		if ($mapData['author'] = $iaUsers->getInfo($itemData['member_id']))
		{
			$mapData['author']['description'] =
				($mapData['author']['address'] ? $mapData['author']['address'] . ',<br>' : '') .
				($mapData['author']['city'] ? $mapData['author']['city'] . ',' : '') .
				($mapData['author']['state'] ? $mapData['author']['state'] . ',<br>' : '') .
				($mapData['author']['country'] ? $mapData['author']['country'] : '');
		}
	}

	$enabledItems = $iaItem->getEnabledItemsForPlugin('gmap');
	if (!in_array($item, $enabledItems))
	{
		$iaView->assign('gmap_data', $mapData);

		return;
	}

	// yellow pages specific code
	if (empty($itemData['state']) && empty($itemData['city']) && isset($itemData['loc_id']))
	{
		$sql = "SELECT l1.*, l2.`title` `parent_title`, l2.`abbreviation` `abbr` ";
		$sql .= "FROM `{$iaDb->prefix}ylocs` l1 ";
		$sql .= "LEFT JOIN `{$iaDb->prefix}ylocs` l2 ";
		$sql .= "ON `l1`.`parent_id` = `l2`.`id`";
		$sql .= "WHERE `l1`.`id` = '{$itemData['loc_id']}'";

		if ($location = $iaDb->getRow($sql))
		{
			$itemData['state'] = $location['parent_title'];
			$itemData['city'] = $location['title'];
			$itemData['addr'] = $location['abbr'];
		}
	}

	if ($itemData)
	{
		$fieldsList = array('zipcode', 'country', 'state', 'city', 'address', 'latitude', 'longitude');

		$mapData['listing'] = $itemData;

		foreach ($fieldsList as $fieldName)
		{
			$mapData[$fieldName] = $itemData[$fieldName];
		}

		if (isset($itemData['title']) && $itemData['title'])
		{
			$mapData['title'] = $itemData['title'];
		}
		if (isset($itemData['description']) && $itemData['description'])
		{
			$mapData['description'] = $itemData['description'];
		}

		if (!isset($mapData['title']))
		{
			$mapData['title'] =
				($mapData['country'] ? $mapData['country'] . ', ' : '') .
				($mapData['state'] ? $mapData['state'] . ', ' : '') .
				($mapData['city'] ? $mapData['city'] . ', ' : '') .
				($mapData['address'] ? $mapData['address'] . ', ' : '');
		}
	}

	$iaView->assign('gmap_data', $mapData);
}