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

class WpApiFilterServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    function testGetFilterが正しいフィルター文字列を返すテスト()
    {
        $params  = array(
            '_token'   => 'AeBra6KcHow4Dz7FhJ9tzdQt9qJessa4DxwhmBrN3pY',
            'format'   => 'contents',
            'post_id'  => '',
            'per_page' => '8',
            'search'   => 'aws',
            'after'    => '2016-01-01',
            'before'   => '2017-01-01',
            'second'   => '-',
            'filters'  => '',
            'publish'  => '',
        );
        $expect  = '?per_page=8&search=aws&after=2016-01-01T00%3A00%3A00&before=2017-01-01T00%3A00%3A00';
        $service = $this->app['linkwp.wp_api_filter'];
        $actual  = $service->getFilter($params);
        $this->assertEquals($expect, $actual);
    }

    function testGetFilter有効なパラメーターがないときは空文字を返すテスト()
    {
        $params  = array(
            '_token'   => 'AeBra6KcHow4Dz7FhJ9tzdQt9qJessa4DxwhmBrN3pY',
            'format'   => 'contents',
            'post_id'  => '',
            'per_page' => '',
            'search'   => '',
            'after'    => '',
            'before'   => '',
            'filters'  => '',
            'publish'  => '',
        );
        $expect  = '';
        $service = $this->app['linkwp.wp_api_filter'];
        $actual  = $service->getFilter($params);
        $this->assertEquals($expect, $actual);
    }
}