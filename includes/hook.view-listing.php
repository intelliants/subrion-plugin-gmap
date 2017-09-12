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
    if (!isset($item)) {
        return;
    }

    $mapData = ['id' => $listing, 'item' => $item, 'listing' => []];

    $iaItem = $iaCore->factory('item');
    $iaUsers = $iaCore->factory('users');

    $itemData = $item == iaUsers::getItemName()
        ? $iaUsers->getInfo($listing)
        : $iaCore->factoryItem($item)->getById($listing);

    $enabledItems = $iaItem->getEnabledItemsForPlugin('gmap');

    // get author information
    if (in_array(iaUsers::getItemName(), $enabledItems) && $itemData && isset($itemData['member_id']) && $itemData['member_id']) {
        if ($mapData['author'] = $iaUsers->getInfo($itemData['member_id'])) {
            $mapData['author']['description'] =
                ($mapData['author']['address'] ? $mapData['author']['address'] . ',<br>' : '') .
                ($mapData['author']['city'] ? $mapData['author']['city'] . ',' : '') .
                ($mapData['author']['state'] ? $mapData['author']['state'] . ',<br>' : '') .
                ($mapData['author']['country'] ? $mapData['author']['country'] : '');
        }
    }

    if (!in_array($item, $enabledItems)) {
        $iaView->assign('gmap_data', $mapData);

        return;
    }

    // yellow pages specific code
    if (empty($itemData['state']) && empty($itemData['city']) && isset($itemData['loc_id'])) {
        $sql = "SELECT l1.*, l2.`title` `parent_title`, l2.`abbreviation` `abbr` ";
        $sql .= "FROM `{$iaDb->prefix}ylocs` l1 ";
        $sql .= "LEFT JOIN `{$iaDb->prefix}ylocs` l2 ";
        $sql .= "ON `l1`.`parent_id` = `l2`.`id`";
        $sql .= "WHERE `l1`.`id` = '{$itemData['loc_id']}'";

        if ($location = $iaDb->getRow($sql)) {
            $itemData['state'] = $location['parent_title'];
            $itemData['city'] = $location['title'];
            $itemData['addr'] = $location['abbr'];
        }
    }

    if ($itemData) {
        $fieldsList = ['zipcode', 'country', 'state', 'city', 'address', 'latitude', 'longitude'];

        $mapData['listing'] = $itemData;

        foreach ($fieldsList as $fieldName) {
            $mapData[$fieldName] = $itemData[$fieldName];
        }

        if (isset($itemData['title']) && $itemData['title']) {
            $mapData['title'] = $itemData['title'];
        }
        if (isset($itemData['description']) && $itemData['description']) {
            $mapData['description'] = $itemData['description'];
        }

        if (!isset($mapData['title'])) {
            $mapData['title'] =
                ($mapData['country'] ? $mapData['country'] . ', ' : '') .
                ($mapData['state'] ? $mapData['state'] . ', ' : '') .
                ($mapData['city'] ? $mapData['city'] . ', ' : '') .
                ($mapData['address'] ? $mapData['address'] . ', ' : '');
        }
    }

    $iaView->assign('gmap_data', $mapData);
}
