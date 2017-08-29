<?php
/*
    This file is part of InfoTownMedia Plugin of EC-CUBE3
    
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
namespace Plugin\InfoTownMedia\Service;

use Eccube\Application;

/**
 * Handle pager of image browser.
 * @package Plugin\InfoTownMedia\Service
 */
class PagerService
{
    /**
     * @var \Eccube\Application $app
     */
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get pager html of images browser.
     * @return array Img markup list(ie <img src="/upload/save_image/example.png">).
     */
    public function getPager($page, $perPage, $total)
    {
        $prev      = '';
        $next      = '';
        $totalPage = ceil($total / $perPage);
        if ($page > 0) {
            $prev = '<span class="itmBrowserPrev" data-page="'.$page.'">&laquo;前へ</span>';
        }
        if ($page < $totalPage - 1) {
            $next = '<span class="itmBrowserNext" data-page="'.$page.'">次へ&raquo;</span>';
        }
        $pager = $prev . '&emsp;' . $next;

        return $pager;
    }

}