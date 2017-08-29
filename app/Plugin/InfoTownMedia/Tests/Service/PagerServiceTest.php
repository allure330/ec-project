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

namespace Plugin\InfoTownMedia\Tests;

use Eccube\Tests\EccubeTestCase;
use Plugin\InfoTownMedia\ServiceProvider\ServiceProvider;

class PagerServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    function testGetPager総画像数110ページ当たり20件表示で1ページ目のとき次へのみ表示されるテスト()
    {
        // ページ番号の添字は0スタート
        $expect = '&emsp;<span class="itmBrowserNext" data-page="0">次へ&raquo;</span>';
        // 1ページ目, 総画像数100, 1ページ当たり20画像表示
        $actual = $this->app['infotown.media.pager']->getPager(0, 20, 110);

        $this->assertEquals($expect, $actual);
    }
    function testGetPager総画像数110ページ当たり20件表示で2ページ目のとき前へと次へが返されるテスト()
    {
        // ページ番号の添字は0スタート
        $expect = '<span class="itmBrowserPrev" data-page="1">&laquo;前へ</span>'
            .'&emsp;<span class="itmBrowserNext" data-page="1">次へ&raquo;</span>';
        // 2ページ目, 総画像数100, 1ページ当たり20画像表示
        $actual = $this->app['infotown.media.pager']->getPager(1, 20, 110);

        $this->assertEquals($expect, $actual);
    }

    function testGetPager総画像数110ページ当たり20件表示で6ページ目のとき前へのみ返されるテスト()
    {
        // ページ番号の添字は0スタート
        $expect = '<span class="itmBrowserPrev" data-page="5">&laquo;前へ</span>&emsp;';
        // 6ページ目, 総画像数100, 1ページ当たり20画像表示
        $actual = $this->app['infotown.media.pager']->getPager(5, 20, 110);

        $this->assertEquals($expect, $actual);
    }
}