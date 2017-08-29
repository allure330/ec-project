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
namespace Plugin\InfoTownLinkWp\Service;

use Eccube\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Guzzle\Http\Client;

/**
 * Handle Guzzle HTTP Client.
 * @package Plugin\InfoTownLinkWp\Service
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
     * If OAuth1 authentication is required, then set OAuth1 Authentication.
     * @return Client Guzzle\Http\Client. If WordPress site url and api path is not set at Plugin configuration,
     *         send flash message to Configration page.
     */
    public function getClient()
    {
        $entity = $this->app['orm.em']->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')->find(1);
        if (empty( $entity )) {
            // Display Error message to configuration form.
            $this->app['session']->getFlashBag()->add(
                'infotown.linkwp.message',
                'WordPress サイトアドレスとWP APIパスの入力は必須です。下記の設定を行ってください。'
            );
            $response = new RedirectResponse($this->app['url_generator']->generate('plugin_InfoTownLinkWp_config'));
            $response->send();
        }
        $base_url = rtrim($entity->getHomeUrl(), '/').'/'.rtrim(ltrim($entity->getApiUrl(), '/'), '/').'/';
        $client   = new Client(
            $base_url,
            array(
                'request.options' => array(
                    'headers' => array('Accept' => 'application/json'),
                ),
            )
        );

        return $client;
    }
}