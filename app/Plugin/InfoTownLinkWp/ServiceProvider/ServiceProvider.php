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
namespace Plugin\InfoTownLinkWp\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register InfoTownLinkWp service configuration.
     * 1. Bundle for Debug.
     * 2. Constant.
     * 3. Routing.
     * 4. Menu.
     * 4. Service.
     * 5. Form.
     * 6. Form Extension.
     * @param BaseApplication $app
     */
    public function register(BaseApplication $app)
    {
        /*
         * TODO:SAWAI: プロダクトでは下記をコメントアウトしてください。
         * 
         * IDEでSilexを補完するため追加しています。
         * http://qiita.com/chihiro-adachi/items/4fd0f3c4e19f322b6b16
         * 
         * 1. PhpStormのSilex用プラグインsilex-idea-pluginをインストール
         * 2. Silex Pimple Dumperバンドルのインストール
         * 
         */
//        $app->register(new \Sorien\Provider\PimpleDumpProvider());
        
        /*
         * Register constant
         */
        // Guzzle HTTP request time out.
        $app['linkwp.timeout'] = 15.0;
        $app['linkwp.connect_timeout'] = 1.5;
        // WP API post endpoinet
        $app['linkwp_api_routes_post'] = 'wp/v2/posts';

        /*
         * Register routing.
         */
        // Define InfoTownLinkWp config page
        $app->match(
            $app['config']['admin_route'] . '/plugin/InfoTownLinkWp/config',
            '\Plugin\InfoTownLinkWp\Controller\ConfigController::index'
        )->bind('plugin_InfoTownLinkWp_config');
        // Define InfoTownLinkWp WordPress form action of config page
        $app->match(
            $app['config']['admin_route'] . '/plugin/InfoTownLinkWp/wordpress',
            '\Plugin\InfoTownLinkWp\Controller\ConfigController::wordpressAction'
        )->bind('plugin_InfoTownLinkWp_config_wordpress');
        // Define InfoTownLinkWp plugin top page.
        $app->match(
            $app['config']['admin_route'].'/content/infotownlinkwp',
            '\Plugin\InfoTownLinkWp\Controller\GetController::index'
        )->bind('infotown_linkwp');
        // Define get page. equivalent to above.
        $app->match(
            $app['config']['admin_route'] . '/content/infotownlinkwp/get',
            '\Plugin\InfoTownLinkWp\Controller\GetController::index'
        )->bind('infotown_linkwp_get'); 
        
        /*
         * Register InfoTownLinkWp admin menu.
         */
        $app['config'] = $app->share(
            $app->extend(
                'config',
                function ($config) {
                    $config['nav'][3]['child'][] = [
                        'id'   => 'infotown_linkwp_get',
                        'name' => 'LinkWp 投稿表示タグ',
                        'url'  => 'infotown_linkwp_get',
                    ];

                    return $config;
                }
            )
        );

        /*
         * Register Services
         */
        // Register service for get from WordPress via WP API.
        $app['linkwp.wp_api_get'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownLinkWp\Service\WpApiGetService($app);
            }
        );
        // Register service for request filter.
        $app['linkwp.wp_api_filter'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownLinkWp\Service\WpApiFilterService($app);
            }
        );
        // Register service for handling Guzzle HTTP Exception(Guzzle 3).
        $app['linkwp.wp_exception'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownLinkWp\Service\ExceptionService($app);
            }
        );
        // Register service for parse response form WP API.
        $app['linkwp.wp_api_response'] = $app->share(
            function () {
                return new \Plugin\InfoTownLinkWp\Service\WpApiResponseService();
            }
        );
        // Register service for handle HTTP client for WP API.
        $app['linkwp.wp_api_client'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownLinkWp\Service\HttpClientService($app);
            }
        );
        // Register publishing tag service that display WordPress post.
        $app['linkwp.wp_api_tag'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownLinkWp\Service\TagService($app);
            }
        );
        // Register service for HTTP handling.
        $app['linkwp.wp_http_status'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownLinkWp\Service\HttpStatusCodeService($app);
            }
        );

        /* 
         * Register form.
         */
        // WordPress configuration form.
        $app['form.types'] = $app->share(
            $app->extend(
                'form.types',
                function ($types) use ($app) {
                    $types[] = new \Plugin\InfoTownLinkWp\Form\WordpressType($app);

                    return $types;
                }
            )
        );
        // Publish tag from.
        $app['form.types'] = $app->share(
            $app->extend(
                'form.types',
                function ($types) use ($app) {
                    $types[] = new \Plugin\InfoTownLinkWp\Form\TagType($app);

                    return $types;
                }
            )
        );
        
    }

    public function boot(BaseApplication $app)
    {
    }
}