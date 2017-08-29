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

namespace Plugin\InfoTownLinkWp\Tests\Service;

use Eccube\Tests\EccubeTestCase;
use Plugin\InfoTownLinkWp\ServiceProvider\ServiceProvider;

class HttpStatusCodeServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    function testGetStatusTextは404に対して正しいステータステキストを返すテスト()
    {
        $expect  = '記事が見つかりませんでした。';
        $service = $this->app['linkwp.wp_http_status'];
        $actual  = $service->getStatusText(404, 'ja');
        $this->assertEquals($expect, $actual);
    }
    
    function testGetStatusTextは第2引数を省略したとき日本語のステータステキストを返すテスト()
    {
        $expect  = '記事が見つかりませんでした。';
        $service = $this->app['linkwp.wp_http_status'];
        $actual  = $service->getStatusText(404);
        $this->assertEquals($expect, $actual);
    }
    
    function testGetStatusTextはenを第2引数へ渡したとき英語のステータステキストを返すテスト()
    {
        $expect  = 'Not Found';
        $service = $this->app['linkwp.wp_http_status'];
        $actual  = $service->getStatusText(404, 'en');
        $this->assertEquals($expect, $actual);
    }   
}