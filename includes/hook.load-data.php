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
    if ($iaView->blockExists('listings_on_map')) {
        $params = [
            'items' => [],
            'language' => $iaView->language,
            'location' => is_string($iaView->get('location')) ? $iaView->get('location') : null,
            'style' => $iaCore->get('gmap_style')
        ];

        function keyLookup(array $data, array $keys)
        {
            foreach ($keys as $key)
                if (isset($data[$key])) return $data[$key];

            return null;
        }

        foreach (['listings', 'members', 'item'] as $varName) {
            if ($listings = $iaView->getValues($varName)) {
                'item' == $varName && $listings = [$listings];
                foreach ($listings as $listing) {
                    if (!empty($listing['latitude']) && !empty($listing['longitude'])) {
                        $entry = [
                            'lat' => $listing['latitude'],
                            'lng' => $listing['longitude'],

                            'title' => keyLookup($listing, ['title', 'venue_title', 'fullname'])
                        ];

                        $params['items'][] = $entry;
                    }
                }

                break;
            }
        }

        $params['items'] = json_encode($params['items']);

        $iaView->assign('gmap', $params);

        if ('default' != $params['style']) {
            $iaView->add_js('_IA_URL_modules/gmap/js/front/styles');
        }
    }
}
