<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	if (!isset($item))
	{
		return;
	}

	$iaItem = $iaCore->factory('item');
	$enabledItems = $iaItem->getEnabledItemsForPlugin('gmap');

	// FIXME: temporal solution for Coupons Package
	if ('coupons' == $item && in_array('shops', $enabledItems))
	{
		$enabledItems[] = 'coupons';
	}

	if (!in_array($item, $enabledItems))
	{
		return;
	}

	if ($item == iaUsers::getItemName())
	{
		$itemData = $iaCore->factory('users')->getInfo($listing);
	}
	// FIXME: temporal solution for Coupons Package
	elseif ('coupons' == $item)
	{
		$item = 'shops';
		$itemPackage = $iaItem->getPackageByItem($item);
		$itemClass = $iaCore->factoryPackage('item', $itemPackage, iaCore::FRONT, $item);
		$itemData = $itemClass->getById($listing);
	}
	else
	{
		$itemPackage = $iaItem->getPackageByItem($item);
		$itemClass = $iaCore->factoryPackage('item', $itemPackage, iaCore::FRONT, $item);
		$itemData = $itemClass->getById($listing);
	}

	// actually this code is seem to be written to work with the Yellowpages package.
	// FIXME: the common solution should be found

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
		$mapData = array('id' => $listing, 'item' => $item, 'listing' => $itemData);

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

		$iaView->assign('gmap_data', $mapData);
	}
}