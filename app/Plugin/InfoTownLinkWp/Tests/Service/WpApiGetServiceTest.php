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

    # POSTパラメーター
    $params = [
        '_token'         => '',
        'format'         => 'contents',
        'post_id'        => '',
        'publish'        => '',
        's'              => '',
        'pagename'       => '',
        'page_id'        => '',
        'm'              => '',
        'year'           => '',
        'monthnum'       => '-',
        'day'            => '-',
        'w'              => '-',
        'hour'           => '-',
        'minute'         => '-',
        'second'         => '-',
        'name'           => '',
        'cat'            => '',
        'category_name'  => '',
        'tag'            => '',
        'author'         => '',
        'author_name'    => '',
        'post_type'      => '',
        'order'          => '',
        'posts_per_page' => '',
        'filters'        => '',
    ];
*/

namespace Plugin\InfoTownLinkWp\Tests\Service;

use Eccube\Tests\EccubeTestCase;
use Plugin\InfoTownLinkWp\ServiceProvider\ServiceProvider;

class WpApiGetServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    public function testGetPostsは検索条件が設定されていないとき最新の10件を返すテスト()
    {
        /*
         * 全体条件
         * 設定画面のhome_urlはfindxfine.com, api_urlはwp-jsonが設定されています。
         */
        $data = $this->app['linkwp.wp_api_get']->getPosts();
        $this->assertEquals(200, $data['code']);
        $this->assertEquals(10, count($data['body']));
    }

    public function testGetPostByIdは指定された投稿を返すテスト()
    {
        /*
         * 全体条件
         * 設定画面のhome_urlはfindxfine.com, api_urlはwp-jsonが設定されています。
         */
        $data = $this->app['linkwp.wp_api_get']->getPostById(995560885);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals('成功しました。', $data['message']);
        $this->assertEquals(995560885, $data['body']['id']);
    }

    public function testGetPostはキーワードで記事を取得できるテスト()
    {
        /*
         * 全体条件
         * 設定画面のhome_urlはfindxfine.com, api_urlはwp-jsonが設定されています。
         */
        $filters = '?search=EC-CUBE3';
        $data    = $this->app['linkwp.wp_api_get']->getPosts($filters);
        $this->assertEquals(200, $data['code']);
        $this->assertEquals('成功しました。', $data['message']);
        $match = true;
        foreach ($data['body'] as $item) {
            if (
                false === strpos($item['content']['rendered'], 'EC-CUBE3')
                && false === strpos($item['title']['rendered'], 'EC-CUBE3')
            ) {
                $match = false;
            }
        }
        $this->assertTrue($match);
    }
}