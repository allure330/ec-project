<?php
/*
    This file is part of InfoTownLinkWp Plugin of EC-CUBE3
    
    Copyright(c) 2015- Hiroshi Sawai All Rights Reserved.
    
    http://www.info-town.co.jp/
    
    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.
    
    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
    
    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
    */
namespace Plugin\InfoTownLinkWp\Service;

use Eccube\Application;

/**
 * Handle filter sting that is used to filter posts data. Filter is made by WP Query variables.
 * WP Query variables can filter post data.
 * Format is following.
 * GET http://www.example.com/wp-json/wp/v2/posts?filter['key']=value&filter['key']=value
 * @link https://github.com/kadamwhite/wordpress-rest-api I made following site as reference about query variables.
 * @package Plugin\InfoTownLinkWp\Service
 */
class WpApiFilterService
{
    /**
     * WpApiFilterService constructor.
     *
     * @param \Eccube\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get query string to specify condition.
     *
     * @param array $params associated array of TagType form parameter.
     * @return mixed(string|boolean) query string.
     */
    public function getFilter($params)
    {
        if (empty($params)) {
            return false;
        }
        $queries = [];
        foreach ($params as $key => $value) {
            if ('' === $value || '-' === $value) {
                continue;
            }
            if (in_array($key, $this->publicQueryVar)) {
                if ($key === 'after' || $key === 'before') {
                    $value = explode('-', $value);
                    if ($value === false) {
                        continue;
                    }
                    if (!ctype_digit($value[0]) || !ctype_digit($value[1]) || !ctype_digit($value[2])) {
                        continue;
                    }
                    $value = $value[0].'-'.$value[1].'-'.$value[2].'T00:00:00';
                }
                array_push($queries, $key.'='.urlencode($value));
            }
        }
        if (!empty($queries)) {
            return '?'.implode('&', $queries);
        }

        return '';
    }

    /**
     * Query string  to filtering post.
     * @https://developer.wordpress.org/rest-api/reference/posts/
     */
    public $publicQueryVar = [
        'search',
        'per_page',
        'after',  // ISO8601 2016-10-10T13:50:40
        'before', // ISO8601 2016-10-10T13:50:40
    ];
}