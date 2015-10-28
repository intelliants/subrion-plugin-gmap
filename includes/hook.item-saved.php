<?php
//##copyright##

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	$iaItem = $iaCore->factory('item');

	$enabledItems = $iaItem->getEnabledItemsForPlugin('gmap');

	if (in_array($itemName, $enabledItems) && isset($_POST['longitude']) && isset($_POST['latitude']))
	{
		$itemPackage = $iaItem->getPackageByItem($itemName);
		$itemClass = $iaCore->factoryPackage('item', $itemPackage, iaCore::FRONT, $itemName);

		$dbTable = call_user_func(array($itemClass, 'getTable'));
		$iaCore->iaDb->update(array('id' => $itemId, 'longitude' => $_POST['longitude'], 'latitude' => $_POST['latitude']), null, null, $dbTable);
	}
}