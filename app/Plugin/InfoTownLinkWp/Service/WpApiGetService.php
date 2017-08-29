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
use Symfony\Component\HttpFoundation\Response;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;

/**
 * Get data from WordPress via WP API.
 * @package Plugin\InfoTownLinkWp\Service
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
     * Retrieve posts.
     * @param string $filters Parameters that is used to filter posts.
     * @return array associated array of response for WP API and status.
     *               class 
     *               code
     *               message
     *               body  response body form WP API.
     */
    public function getPosts($filters = '')
    {
        $client = $this->app['linkwp.wp_api_client']->getClient();
        $data = $this->request($client, $filters);

        return $data;
    }

    /**
     * Retrieve a post that is specified by post id.
     * @param int $id post id WordPress post id.
     * @return array associated array of response for WP API and status.
     *               type       (Success | Fail)
     *               statusCode
     *               statusText
     *               body  response body form WP API.
     */
    public function getPostById($id)
    {
        $client = $this->app['linkwp.wp_api_client']->getClient();
        $data   = $this->request($client, $id);

        return $data;
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
    public function getMedia($filters)
    {
        $client = $this->app['linkwp.wp_api_client']->getClient();
        $data   = $this->requestMedia($client, $filters);

        return $data;
    }

    /**
     * Get post form WordPress via WP API.
     * @param Client $client Guzzle\Http\Client.
     * @param string $param Parameter of get request routes.
     * @return array Associated array of response from WP API.
     *               code is statusCode of response header.
     *               text is message corresponding to the statusCode.
     *               body is response body that Convert to array.
     */
    private function request(Client $client, $param)
    {
        $routes = 'wp/v2/posts/'.$param;
        try {
            $request  = $client->get($routes);
            $response = $request->send();
            $data     = [
                'code' => $response->getStatusCode(),
                'message' => $this->app['linkwp.wp_http_status']->getStatusText($response->getStatusCode(), 'ja'),
                'body' => $response->json(),
            ];

            return $data;
        } catch (RequestException $e) {
            $data = $this->app['linkwp.wp_exception']->getExceptionData($e);

            return $data;
        } catch (\Exception $e) {
            return [
                'code' => -1,
                'message' => 'Exception.',
            ];
        }
    }
}