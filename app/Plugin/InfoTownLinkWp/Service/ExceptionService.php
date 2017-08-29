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

use Symfony\Component\HttpFoundation\Response;
use Guzzle\Common\Exception\GuzzleException;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Guzzle\Http\Exception\ServerErrorResponseException;

/**
 * Handle Exception of Guzzle Http.
 * @link http://guzzle.readthedocs.org/en/latest/quickstart.html#exceptions Guzzle6 Exceptions.
 * @package Plugin\InfoTownLinkWp\Service
 */
class ExceptionService
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
     * Get HTTP request/response exception data of Guzzle6.
     * @param \Exception $e
     * @return array
     */
    public function getExceptionData(\Exception $e)
    {
        // Exception 4xx.
        if ($e instanceof ClientErrorResponseException || $e instanceof ServerException) {
            $data = [
                'code'    => $e->getResponse()->getStatusCode(),
                'message' => $this->app['linkwp.wp_http_status']
                    ->getStatusText($e->getResponse()->getStatusCode(), 'ja'),
            ];

            return $data;
        }
        // Exception 5xx.
        if ($e instanceof ServerErrorResponseException) {
            $data = [
                'code'    => $e->getResponse()->getStatusCode(),
                'message' => $this->app['linkwp.wp_http_status']
                    ->getStatusText($e->getResponse()->getStatusCode(), 'ja'),
            ];

            return $data;
        }
        // Response Exception.
        if ($e instanceof BadResponseException) {
            $data = [
                'code'    => $e->getResponse()->getStatusCode(),
                'message' => $this->app['linkwp.wp_http_status']
                    ->getStatusText($e->getResponse()->getStatusCode(), 'ja'),
            ];

            return $data;
        }
        // Http Exception.
        if ($e instanceof RequestException) {
            $data = [
                'code'    => $e->getCode(),
                'message' => $e->getMessage(),
            ];

            return $data;
        }
        if ($e instanceof GuzzleException) {
            $data = [
                'code'    => -1,
                'message' => 'GuzzleException',
            ];

            return $data;

        }
        // Default Exception
        $data = [
            'code'    => $e->getCode(),
            'message' => $e->getMessage(),
        ];

        return $data;
    }
}