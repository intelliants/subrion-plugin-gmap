<?php
/******************************************************************************
 *
 * Subrion - open source content management system
 * Copyright (C) 2018 Intelliants, LLC <https://intelliants.com>
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
    switch (true) {
        case isset($userInfo): // 'phpUserProfileUpdate' hook - front
            $itemName = iaUsers::getItemName();
            $itemId = $userInfo['id'];
            break;
        case isset($item) && isset($listing): // 'phpAddItemAfterAll' hook - front
            $itemName = $item;
            $itemId = $listing;
            break;
        default: // 'phpItemSaved' hook - backend
            //$itemName
            //$itemId
    }

    $enabledItems = $iaCore->factory('item')->getEnabledItemsForPlugin('gmap');

    if (in_array($itemName, $enabledItems) && isset($_POST['longitude']) && isset($_POST['latitude'])) {
        $itemInstance = iaUsers::getItemName() == $itemName
            ? $iaCore->factory('users')
            : $iaCore->factoryItem($itemName);

        $iaDb->update(['longitude' => $_POST['longitude'], 'latitude' => $_POST['latitude']],
            iaDb::convertIds($itemId), null, $itemInstance::getTable());
    }
}
