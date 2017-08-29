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

class SecurityServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    function testCreateToken必ず異なる値を返すテスト()
    {
        $token = [];
        for ($i = 0; $i < 100; $i++) {
            $token[$i] = $this->app['infotown.media.security']->createToken();
        }
        $this->assertEquals(100, count($token));
    }

    function testRemoveTokenでitmTokenキーの値を正しく削除するテスト()
    {
        $this->app['infotown.media.security']->createToken();
        $this->app['infotown.media.security']->removeToken();
        $this->assertEmpty($this->app['session']->get('itmToken'));

    }


}