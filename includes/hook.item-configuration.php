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
	$plugin = 'gmap';
	$fields = array('zipcode', 'country', 'state', 'city', 'address', 'latitude', 'longitude');
	$length = 64;
	$pages = array(
		'members' => array('profile', 'view_member'),
		'autos' => array('autos_add', 'autos_edit', 'autos_view'),
		'articles' => array('submit_article', 'edit_article', 'view_article'),
		//'estates' => array('estate_submit', 'estate_edit'),
		'listings' => array('add_listing', 'edit_listing', 'view_listing'),
		'venues' => array('venue_add', 'venue_edit', 'venue_view'),
		'coupons' => array('coupon_add', 'coupon_edit', 'coupon_view'),
		'shops' => array('shop_view'),
	);

	$iaItem = $iaCore->factory('item');
	$iaField = $iaCore->factory('field');

	foreach ($data as $entry)
	{
		$itemName = $entry['item'];
		$package = $iaItem->getModuleByItem($itemName);

		iaLanguage::addPhrase(sprintf('fieldgroup_%s_location', $entry['item']), 'Location');

		iaLanguage::addPhrase(sprintf('field_%s_zipcode', $entry['item']), 'ZIP Code');
		iaLanguage::addPhrase(sprintf('field_%s_country', $entry['item']), 'Country');
		iaLanguage::addPhrase(sprintf('field_%s_state', $entry['item']), 'State');
		iaLanguage::addPhrase(sprintf('field_%s_city', $entry['item']), 'City');
		iaLanguage::addPhrase(sprintf('field_%s_address', $entry['item']), 'Address');
		iaLanguage::addPhrase(sprintf('field_%s_latitude', $entry['item']), 'Latitude');
		iaLanguage::addPhrase(sprintf('field_%s_longitude', $entry['item']), 'Longitude');

		switch ($entry['action'])
		{
			case '+':
				$group = 0;
				if ($id = $iaDb->one(iaDb::ID_COLUMN_SELECTION, "`item` = '{$itemName}' AND `name` LIKE '%location%'", iaField::getTableGroups()))
				{
					$group = $id;
				}
				else
				{
					$order = $iaDb->one('MAX(`order`) + 1', null, iaField::getTableGroups());
					$group = $iaDb->insert(array(
						'name' => 'location',
						'item' => $itemName,
						'order' => $order,
						'module' => $package,
						'collapsed' => false,
						'tabview' => true
					), null, iaField::getTableGroups());
				}

				$sql = sprintf('SHOW COLUMNS FROM `%s%s`', $iaDb->prefix, $iaItem->getItemTable($itemName));

				$existColumns = array_keys($iaDb->getKeyValue($sql));
				$existFields = $iaDb->onefield('name', "`item` = '$itemName'", null, null, iaField::getTable());

				$order = (int)$iaDb->one('MAX(`order`)', null, iaField::getTable());

				foreach ($fields as $field)
				{
					if (!in_array($field, $existColumns))
					{
						$sql = sprintf('ALTER TABLE `%s%s` ADD `%s` VARCHAR(%d) NOT NULL', $iaDb->prefix, $iaItem->getItemTable($itemName), $field, $length);
						$iaDb->query($sql);
					}

					if (!in_array($field, $existFields))
					{
						if ('latitude' != $field && 'longitude' != $field)
						{
							$row = array(
								'fieldgroup_id' => $group,
								'name' => $field,
								'item' => $itemName,
								'type' => iaField::TEXT,
								'length' => $length,
								'status' => iaCore::STATUS_ACTIVE,
								'editable' => false,
								'order' => $order++,
								'module' => $plugin
							);

							$fieldId = $iaDb->insert($row, null, iaField::getTable());

							if (isset($pages[$itemName]))
							{
								foreach ($pages[$itemName] as $page)
								{
									$row = array(
										'page_name' => $page,
										'field_id' => $fieldId
									);

									$iaDb->insert($row, null, iaField::getTablePages());
								}
							}
						}
					}
					else
					{
						$iaDb->update(array('status' => iaCore::STATUS_ACTIVE), "`name` = '$field' AND `item` = '$itemName'", null, iaField::getTable());
					}
				}

				break;

			case '-':
				$stmt = sprintf("`item` = '%s' AND `required` = 0 AND `module` = '%s' AND `name` IN ('%s')", $itemName, $plugin, implode("', '", $fields));
				$iaDb->update(array('status' => iaCore::STATUS_APPROVAL), $stmt, null, iaField::getTable());
		}
	}
}
