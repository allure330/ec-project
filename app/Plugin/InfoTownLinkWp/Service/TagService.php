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
 * Manage tag that is required to display WordPress post to EC-CUBE product free area.
 * @package Plugin\InfoTownLinkWp\Service
 */
class TagService
{
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get tag to display specified post.
     * @param int $id post id.
     * @param string $format data type. contents or links.
     * @return string Tag to display specific post.
     */
    public function getTagById($id,$format = 'contents')
    {
        $tag = $this->publishTagById($id, $format);

        return $tag;
    }

    /**
     * Get tag to display filtering posts.
     * @param string $filter WP_Query variable to filter posts.
     * @param string $format data type. contents or links.
     * @return string Tag to display filtering posts.
     */
    public function getTagByFilter($filter = '', $format = 'contents')
    {
        $tag = $this->publishTag($filter, $format);

        return $tag;
    }

    /**
     * Parse published tag to display post.
     * Format of tag is following.
     * <!-- linkwp post_id="<post id>" format="<format"> -->
     * <!-- linkwp filter="<filter>" format="<format>" -->
     * @param string $tag Published tag.
     * @return mixed(boolean|array) associated array of post_id and format or
     *                              associated array of filter and format.
     *                              If post id not found, then return false.
     */
    public function parseTag($tag)
    {
        if (false !== strpos($tag, 'format="links"')) {
            $format = 'links';
        } else {
            $format = 'contents';
        }
        $matches = array();
        // Tag has post_id attribute, tag is for Specific post id.
        if (preg_match('/post_id="(\d+)"/', $tag, $matches)) {
            $data           = $this->parseTagById($tag);
            $data['format'] = $format;

            return $data;
        }
        // Tag is filter attribute, tag is for filtered posts.
        if (preg_match('/filter="([^"]+)"/', $tag, $matches)) {
            $data           = array('filter' => $matches[1]);
            $data['format'] = $format;

            return $data;
        }
        // Tag has not post_id and filter.
        $data = [
            'post_id' => '',
            'filter'  => '',
            'format'  => $format,
        ];

        return $data;
    }

    /**
     * Parse tag to display post.
     * @param string $tag Published tag.
     * @return mixed(boolean|array) associated array of post_id.  
     * If post id not found, then return false.
     */
    private function parseTagById($tag)
    {
        $matches = [];
        if (preg_match('/post_id="(\d+)"/', $tag, $matches)) {
            $data = array('post_id' => $matches[1]);

            return $data;
        }

        return false;
    }

    /**
     * Publish tag to display filtering posts.
     * @param string $filter WP_Query variable to filter posts.
     * @param string $format data type. contents or links.
     * @return string Published tag to display posts.
     */
    private function publishTag($filter, $format)
    {
        $tag = '';
        $tag .= '<!-- linkwp';
        $tag .= ( $filter ) ? ' filter="'.$filter.'"' : '';
        $tag .= ( $format === 'links' ) ? ' format="links"' : ' format="contents"';
        $tag .= ' -->';

        return $tag;
    }

    /**
     * Publish tag to display a post.
     * @param int $id post id.
     * @param string $format data type. contents or links.
     * @return string Published tag to display a specific post.
     */
    private function publishTagById($id, $format)
    {
        $tag = '';
        $tag .= '<!-- linkwp';
        $tag .= ' post_id="'.(int) $id.'"';
        $tag .= ( $format === 'links' ) ? ' format="links"' : ' format="contents"';
        $tag .= ' -->';

        return $tag;
    }
}