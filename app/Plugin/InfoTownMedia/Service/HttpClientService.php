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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Guzzle\Http\Client;

/**
 * Handle Guzzle HTTP Client.
 * @package Plugin\InfoTownMedia\Service
 */
class HttpClientService
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
     * Get Guzzle HTTP Client.
     * @return Client Guzzle\Http\Client. 
     * @throws \Exception If InfoTownMedia do not install, then throws Exception.
     */
    public function getClient()
    {
        try {
            /*
             * If InfoTownLinkWp do not install and activate then Exception throws.
             */
            $em = $this->app['orm.em'];
            $connection = $em->getConnection();
            $statement = $connection->prepare("SELECT * FROM plg_infotown_linkwp_wordpress WHERE id = :id");
            $statement->bindValue('id', 1);
            $statement->execute();
            $results = $statement->fetchAll();

            if (empty( $results[0] )) {
                $this->app['session']->getFlashBag()->add(
                    'infotown_media_message',
                    'WordPresssの画像を読み込む機能を利用するには'
                    .'InfoTownLinkWpプラグインを有効化し基本設定を行う必要があります。'
                );
                $response = new RedirectResponse(
                    $this->app['url_generator']->generate('plugin_InfoTownMedia_config')
                );
                $response->send();
            }

            $client   = new Client(
                rtrim($results[0]['home_url'], '/')
                .'/'.rtrim(ltrim($results[0]['api_url'], '/'), '/').'/',
                [
                    'request.options' => [
                        'headers' => ['Accept' => 'application/json'],
                    ],
                ]
            );

            return $client;

        } catch (\Exception $e) {
            throw new \Exception('WordPresssの画像読み込みはInfoTownLinkWpプラグインをインストールする必要があります。');
        }
    }

}