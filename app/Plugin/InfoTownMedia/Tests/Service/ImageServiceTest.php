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
use Plugin\InfoTownMedia\Service\ImageService;

class ImageServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    function testGetAllImgは画像の絶対URLをsrcへ指定したimg要素を返すテスト() {
        $expect = '<img class="infotown-attachment" src="/upload/save_image';
        $data = $this->app['infotown.media.image']->getAllImg('/upload/save_image');
        $result = true;
        foreach($data as $markup) {
            if (0 !== mb_strpos($markup, $expect)) {
               $result = false; 
            }
        }
        $this->assertTrue($result);
    }

    function testGetAllUrlPathAndTimestampで取得したurl配列は絶対URLを持つテスト()
    {
        $method = new \ReflectionMethod(get_class($this->app['infotown.media.image']), 'getAllUrlPathAndTimestamp');
        $method->setAccessible(true);
        $data = $method->invoke($this->app['infotown.media.image'], '/upload/save_image');
        $result = true;
        foreach ($data['url'] as $url) {
            if (false === preg_match('/^\/upload\/save_image\/.*\.(jpe?g|png|gif)/u', $url)) {
                $result = false;
            }
        }
        $this->assertTrue($result);
    }

    function testGetAllUrlPathAndTimestampで取得したtimestampは降順であるテスト()
    {
        $method = new \ReflectionMethod(get_class($this->app['infotown.media.image']), 'getAllUrlPathAndTimestamp');
        $method->setAccessible(true);
        $data = $method->invoke($this->app['infotown.media.image'], '/upload/save_image');
        $result = true;
        $prev   = $data['timestamp'][0] + 1;
        foreach ($data['timestamp'] as $current) {
            if (!$prev > $current) {
                $result = false;
            }
            $prev = $current;
        }
        $this->assertTrue($result);
    }
}