<?php
//##copyright##

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
		'ypages' => array('ypage_add', 'ypage_edit', 'ypage_view'),
		'coupons' => array('coupon_add', 'coupon_edit', 'coupon_view'),
		'shops' => array('shop_view'),
	);

	$iaItem = $iaCore->factory('item');
	$iaField = $iaCore->factory('field');

	foreach ($data as $entry)
	{
		$itemName = $entry['item'];
		$package = $iaItem->getPackageByItem($itemName);

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
						'extras' => $package,
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
							$data = array(
								'fieldgroup_id' => $group,
								'name' => $field,
								'item' => $itemName,
								'type' => iaField::TEXT,
								'length' => $length,
								'status' => iaCore::STATUS_ACTIVE,
								'editable' => false,
								'order' => $order++,
								'extras' => $plugin
							);
							$fieldId = $iaDb->insert($data, null, iaField::getTable());

							if (isset($pages[$itemName]))
							{
								foreach ($pages[$itemName] as $page)
								{
									$data = array(
										'page_name' => $page,
										'field_id' => $fieldId,
										'extras' => $package
									);
									$iaDb->insert($data, null, iaField::getTablePages());
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
				$stmt = sprintf("`item` = '%s' AND `required` = 0 AND `extras` = '%s' AND `name` IN ('%s')", $itemName, $plugin, implode("', '", $fields));
				$iaDb->update(array('status' => iaCore::STATUS_APPROVAL), $stmt, null, iaField::getTable());
		}
	}
}