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
namespace Plugin\InfoTownMedia\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class ServiceProvider implements ServiceProviderInterface
{
    /**
     * Register InfoTownMedia service configuration.
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
//         $app->register(new \Sorien\Provider\PimpleDumpProvider());

        /*
         * Register constant
         */
        // Guzzle HTTP request time out.
        $app['infotown.media.timeout'] = 15.0;

        /*
         * Register routing.
         */
        $app->match(
            $app['config']['admin_route'].'/plugin/InfoTownMedia/config',
            '\Plugin\InfoTownMedia\Controller\ConfigController::index'
        )->bind('plugin_InfoTownMedia_config');
        $app->match(
            $app['config']['admin_route'].'/content/infotownmedia/media',
            '\Plugin\InfoTownMedia\Controller\MediaController::index'
        )->bind('infotown.media');
        $app->match(
            $app['config']['admin_route'].'/content/infotownmedia/media/wordpress',
            '\Plugin\InfoTownMedia\Controller\MediaController::wordpress'
        )->bind('infotown.media.wordpress');

        /*
         * Register Services
         */
        // Register service to handling image data.
        $app['infotown.media.image'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\ImageService($app);
            }
        );
        // Register service to handling pager of image browser.
        $app['infotown.media.pager'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\PagerService($app);
            }
        );
        // Register service for get from WordPress via WP API.
        $app['infotown.media.wp_api_get'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\WpApiGetService($app);
            }
        );
        // Register service to handle HTTP client for WP API.
        $app['infotown.media.wp_api_client'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\HttpClientService($app);
            }
        );
        // Register service for parse response form WP API.
        $app['infotown.media.wp_api_response'] = $app->share(
            function () {
                return new \Plugin\InfoTownMedia\Service\WpApiResponseService();
            }
        );
        // Register service for HTTP handling.
        $app['infotown.media.httpstatus'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\HttpStatusService($app);
            }
        );
        // Register service for handling Guzzle HTTP Exception(Guzzle 3).
        $app['infotown.media.exception'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\ExceptionService($app);
            }
        );
        // Register service for Security.
        $app['infotown.media.security'] = $app->share(
            function () use ($app) {
                return new \Plugin\InfoTownMedia\Service\SecurityService($app);
            }
        );

        /* 
         * Register form.
         */
        // Configuration form.
        $app['form.types'] = $app->share(
            $app->extend(
                'form.types',
                function ($types) use ($app) {
                    $types[] = new \Plugin\InfoTownMedia\Form\ConfigType($app);

                    return $types;
                }
            )
        );
    }

    public function boot(BaseApplication $app)
    {
    }
}