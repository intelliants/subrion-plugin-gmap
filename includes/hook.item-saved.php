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

if (iaView::REQUEST_HTML == $iaView->getRequestType()) {
    $name = empty($itemName) ? $item : $itemName;
    $id = empty($itemId) ? $listing : $itemId;

    $enabledItems = $iaCore->factory('item')->getEnabledItemsForPlugin('gmap');

    if (in_array($name, $enabledItems) && isset($_POST['longitude']) && isset($_POST['latitude'])) {
        $itemInstance = iaUsers::getItemName() == $name
            ? $iaCore->factory('users')
            : $iaCore->factoryItem($name);

        $dbTable = call_user_func([$itemInstance, 'getTable']);

        $iaCore->iaDb->update(['id' => $id, 'longitude' => $_POST['longitude'], 'latitude' => $_POST['latitude']], null, null, $dbTable);
    }
}
