<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2017 Intelliants, LLC <https://intelliants.com>
 *
 * This file is part of Subrion.
 *
 * Subrion is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Subrion is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Subrion. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @link https://subrion.org/
 *
 ******************************************************************************/

if (iaView::REQUEST_HTML == $iaView->getRequestType())
{
	$name = isset($itemName) && !empty($itemName) ? $itemName : $item;
	$id = isset($itemId) && !empty($itemId) ? $itemId : $listing;

	$iaItem = $iaCore->factory('item');
	$enabledItems = $iaItem->getEnabledItemsForPlugin('gmap');

	if (in_array($name, $enabledItems) && isset($_POST['longitude']) && isset($_POST['latitude']))
	{
		$itemPackage = $iaItem->getPackageByItem($name);
		$itemClass = (iaCore::CORE == $itemPackage)
			? $iaCore->factory((iaUsers::getItemName() == $name) ? 'users' : $name)
			: $iaCore->factoryModule('item', $itemPackage, iaCore::FRONT, $name);

		$dbTable = call_user_func(array($itemClass, 'getTable'));
		$iaCore->iaDb->update(array('id' => $id, 'longitude' => $_POST['longitude'], 'latitude' => $_POST['latitude']), null, null, $dbTable);
	}
}