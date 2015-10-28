<?php
//##copyright##

$fields = array('zip', 'country', 'state', 'city', 'address', 'latitude', 'longitude');

$items = array('accounts');
$relation = array('accounts' => ''); // item/package relations
$gmap_enabled = explode(',', $iaCore->get('gmap_enable_for'));
$tmp = $iaDb->onefield('items', "`items` != ''", 0, 0, 'extras');

foreach ($tmp as $i)
{
	$i = unserialize($i);
	foreach ($i as $q)
	{
		$items[] = $q['item'];
		$relation[$q['item']] = $q['package'];
	}
}

if (isset($_POST['save']))
{
	$gmap_enabled_old = $gmap_enabled;
	$gmap_enabled = array();
	foreach ($_POST['items'] as $i)
	{
		in_array($i, $items) AND $gmap_enabled[] = $i;
	}
	$iaCore->set('gmap_enable_for', implode(',', $gmap_enabled), true);

	// disable old fields
	foreach ($gmap_enabled_old as $o)
	{
		if (in_array($o, $gmap_enabled)) continue;
		$where = sprintf("`item`='$o' AND `name` IN('%s')", implode("', '", $fields));
		$iaDb->update(array('status' => 'approval'), $where, null, 'fields');
	}

	// build fields
	foreach ($gmap_enabled as $gitem)
	{
		$tbl_field = $iaDb->getKeyValue("SHOW COLUMNS FROM `{$iaDb->prefix}{$gitem}`");
		$tbl_field = array_keys($tbl_field);

		$item_fields = $iaDb->onefield('name', "`item`='$gitem'", 0, 0, 'fields');

		foreach ($fields as $f)
		{
			if (!in_array($f, $tbl_field))
			{
				$iaDb->query("ALTER TABLE `{$iaDb->prefix}{$gitem}` ADD `$f` VARCHAR(50) NOT NULL");
			}

			if (!in_array($f, $item_fields))
			{
				$data = array(
					'name' => $f,
					'item' => $gitem,
					'type' => 'text',
					'length' => 50,
					'status' => 'active',
					'extras' => $relation[$gitem]
				);
				$iaDb->insert($data, false, 'fields');
			}
			else
			{
				$iaDb->update(array('status' => 'active'), "`name`='$f' AND `item`='$gitem'", false, 'fields');
			}
		}
	}
}

$iaView->assign('items', $items);
$iaView->assign('gmap_enabled', $gmap_enabled);