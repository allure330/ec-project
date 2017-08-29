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
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;

/**
 * Get data from WordPress via WP API.
 * @package Plugin\InfoTownMedia\Service
 */
class WpApiGetService
{
    /**
     * @var \Eccube\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Retrieve media.
     * @param string $filters Parameters used to query for media.
     * @return array associated array of response for WP API and status.
     *               class
     *               code
     *               message
     *               body  response body form WP API.
     */
    public function getMedia($filters = '')
    {
        try {
            $client = $this->app['infotown.media.wp_api_client']->getClient();
            $data   = $this->requestMedia($client, $filters);

            return $data;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Get media from WordPress via WP API.
     * @param Client $client Guzzle\Http\Client.
     * @param string $param parameter of get request routes.
     * @return array Associated array of response from WP API.
     *               code is statusCode of response header.
     *               text is message corresponding to the statusCode.
     *               body is response body that Convert to array.
     */
    private function requestMedia(Client $client, $param)
    {
        $routes = 'wp/v2/media/'.$param;
        try {
            $request  = $client->get($routes);
            $response = $request->send();
            $data     = [
                'code' => $response->getStatusCode(),
                'message' => Response::$statusTexts[$response->getStatusCode()],
                'body' => $response->json(),
            ];

            return $data;
        } catch (RequestException $e) {
            $data = $this->app['infotown.media.exception']->getExceptionData($e);

            throw new \Exception($data['message'], $data['message']);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode);
        }
    } 
}