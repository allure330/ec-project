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

class WpApiResponseServiceTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    function testParsePostsが正しい配列を返すテスト()
    {
        $params  = array(
            0 => array(
                'id'             => 995560947,
                'date'           => '2016-01-18T15:55:52',
                'date_gmt'       => '2016-01-18T06:55:52',
                'guid'           =>
                    array(
                        'rendered' => 'http://www.findxfine.com/?p=995560947',
                    ),
                'modified'       => '2016-01-18T15:55:52',
                'modified_gmt'   => '2016-01-18T06:55:52',
                'slug'           => 'aws-route53-a%e3%83%ac%e3%82%b3%e3%83%bc%e3%83%89',
                'type'           => 'post',
                'link'           => 'http://www.findxfine.com/aws/995560947.html',
                'title'          =>
                    array(
                        'rendered' => 'AWS Route53 Aレコード',
                    ),
                'content'        =>
                    array(
                        'rendered' => '<p>1番目の概要です。</p><p>これは本文です</p>',
                    ),
                'excerpt'        =>
                    array(
                        'rendered' => '<p>1番目の概要です。</p>',
                    ),
                'author'         => 1,
                'featured_image' => 0,
                'comment_status' => 'open',
                'ping_status'    => 'closed',
                'sticky'         => false,
                'format'         => 'standard',
                '_links'         =>
                    array(
                        'self'                         =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947',
                                    ),
                            ),
                        'collection'                   =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts',
                                    ),
                            ),
                        'about'                        =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/types/post',
                                    ),
                            ),
                        'author'                       =>
                            array(
                                0 =>
                                    array(
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/users/1',
                                    ),
                            ),
                        'replies'                      =>
                            array(
                                0 =>
                                    array(
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/comments?post=995560947',
                                    ),
                            ),
                        'version-history'              =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/revisions',
                                    ),
                            ),
                        'https://api.w.org/attachment' =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/media?parent=995560947',
                                    ),
                            ),
                        'https://api.w.org/term'       =>
                            array(
                                0 =>
                                    array(
                                        'taxonomy'   => 'category',
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/categories',
                                    ),
                                1 =>
                                    array(
                                        'taxonomy'   => 'post_tag',
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/tags',
                                    ),
                            ),
                        'https://api.w.org/meta'       =>
                            array(
                                0 =>
                                    array(
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/meta',
                                    ),
                            ),
                    ),
            ),
            1 => array(
                'id'             => 995560948,
                'date'           => '2016-01-18T15:55:52',
                'date_gmt'       => '2016-01-18T06:55:52',
                'guid'           =>
                    array(
                        'rendered' => 'http://www.findxfine.com/?p=995560948',
                    ),
                'modified'       => '2016-01-18T15:55:52',
                'modified_gmt'   => '2016-01-18T06:55:52',
                'slug'           => 'aws-route53-a%e3%83%ac%e3%82%b3%e3%83%bc%e3%83%89',
                'type'           => 'post',
                'link'           => 'http://www.findxfine.com/aws/995560948.html',
                'title'          =>
                    array(
                        'rendered' => 'AWS Route53 Aレコード',
                    ),
                'content'        =>
                    array(
                        'rendered' => '<p>2番目の概要です。</p><p>これは本文です</p>',
                    ),
                'excerpt'        =>
                    array(
                        'rendered' => '<p>2番目の概要です。</p>',
                    ),
                'author'         => 1,
                'featured_image' => 0,
                'comment_status' => 'open',
                'ping_status'    => 'closed',
                'sticky'         => false,
                'format'         => 'standard',
                '_links'         =>
                    array(
                        'self'                         =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947',
                                    ),
                            ),
                        'collection'                   =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts',
                                    ),
                            ),
                        'about'                        =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/types/post',
                                    ),
                            ),
                        'author'                       =>
                            array(
                                0 =>
                                    array(
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/users/1',
                                    ),
                            ),
                        'replies'                      =>
                            array(
                                0 =>
                                    array(
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/comments?post=995560947',
                                    ),
                            ),
                        'version-history'              =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/revisions',
                                    ),
                            ),
                        'https://api.w.org/attachment' =>
                            array(
                                0 =>
                                    array(
                                        'href' => 'http://www.findxfine.com/wp-json/wp/v2/media?parent=995560947',
                                    ),
                            ),
                        'https://api.w.org/term'       =>
                            array(
                                0 =>
                                    array(
                                        'taxonomy'   => 'category',
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/categories',
                                    ),
                                1 =>
                                    array(
                                        'taxonomy'   => 'post_tag',
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/tags',
                                    ),
                            ),
                        'https://api.w.org/meta'       =>
                            array(
                                0 =>
                                    array(
                                        'embeddable' => true,
                                        'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/meta',
                                    ),
                            ),
                    ),

            ),
        );
        $expect  = array(
            0 => array(
                'id'      => 995560947,
                'title'   => 'AWS Route53 Aレコード',
                'content' => '<p>1番目の概要です。</p><p>これは本文です</p>',
            ),
            1 => array(
                'id'      => 995560948,
                'title'   => 'AWS Route53 Aレコード',
                'content' => '<p>2番目の概要です。</p><p>これは本文です</p>',
            ),
        );
        $service = $this->app['linkwp.wp_api_response'];
        $actual  = $service->parsePosts($params, 'contents');
        $this->assertEquals($expect, $actual);
    }

    function testParsePostが正しい配列を返すテスト()
    {
        $params  = array(
            'id'             => 995560947,
            'date'           => '2016-01-18T15:55:52',
            'date_gmt'       => '2016-01-18T06:55:52',
            'guid'           =>
                array(
                    'rendered' => 'http://www.findxfine.com/?p=995560947',
                ),
            'modified'       => '2016-01-18T15:55:52',
            'modified_gmt'   => '2016-01-18T06:55:52',
            'slug'           => 'aws-route53-a%e3%83%ac%e3%82%b3%e3%83%bc%e3%83%89',
            'type'           => 'post',
            'link'           => 'http://www.findxfine.com/aws/995560947.html',
            'title'          =>
                array(
                    'rendered' => 'AWS Route53 Aレコード',
                ),
            'content'        =>
                array(
                    'rendered' => '<p>コレは概要です。</p><p>これは本文です</p>',
                ),
            'excerpt'        =>
                array(
                    'rendered' => '<p>コレは概要です。</p>',
                ),
            'author'         => 1,
            'featured_image' => 0,
            'comment_status' => 'open',
            'ping_status'    => 'closed',
            'sticky'         => false,
            'format'         => 'standard',
            '_links'         =>
                array(
                    'self'                         =>
                        array(
                            0 =>
                                array(
                                    'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947',
                                ),
                        ),
                    'collection'                   =>
                        array(
                            0 =>
                                array(
                                    'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts',
                                ),
                        ),
                    'about'                        =>
                        array(
                            0 =>
                                array(
                                    'href' => 'http://www.findxfine.com/wp-json/wp/v2/types/post',
                                ),
                        ),
                    'author'                       =>
                        array(
                            0 =>
                                array(
                                    'embeddable' => true,
                                    'href'       => 'http://www.findxfine.com/wp-json/wp/v2/users/1',
                                ),
                        ),
                    'replies'                      =>
                        array(
                            0 =>
                                array(
                                    'embeddable' => true,
                                    'href'       => 'http://www.findxfine.com/wp-json/wp/v2/comments?post=995560947',
                                ),
                        ),
                    'version-history'              =>
                        array(
                            0 =>
                                array(
                                    'href' => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/revisions',
                                ),
                        ),
                    'https://api.w.org/attachment' =>
                        array(
                            0 =>
                                array(
                                    'href' => 'http://www.findxfine.com/wp-json/wp/v2/media?parent=995560947',
                                ),
                        ),
                    'https://api.w.org/term'       =>
                        array(
                            0 =>
                                array(
                                    'taxonomy'   => 'category',
                                    'embeddable' => true,
                                    'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/categories',
                                ),
                            1 =>
                                array(
                                    'taxonomy'   => 'post_tag',
                                    'embeddable' => true,
                                    'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/tags',
                                ),
                        ),
                    'https://api.w.org/meta'       =>
                        array(
                            0 =>
                                array(
                                    'embeddable' => true,
                                    'href'       => 'http://www.findxfine.com/wp-json/wp/v2/posts/995560947/meta',
                                ),
                        ),
                ),
        );
        $expect  = array(
            0 =>
                array(
                    'id'      => 995560947,
                    'title'   => 'AWS Route53 Aレコード',
                    'content' => '<p>コレは概要です。</p><p>これは本文です</p>',
                ),
        );
        $service = $this->app['linkwp.wp_api_response'];
        $actual  = $service->parsePost($params, 'contents');
        $this->assertEquals($expect, $actual);
    }
}