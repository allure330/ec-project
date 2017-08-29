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
namespace Plugin\InfoTownMedia\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Handle media between EC-CUBE and WordPress via WP API.
 * @package Plugin\InfoTownMedia\Controller
 */
class MediaController
{
    /**
     * Handle EC CUBE media.
     * @param Application $app instance of application.
     * @param Request $request Http request.
     * @param integer $page Pager no.
     * @return Response JSON of media form WP API
     */
    public function index(Application $app, Request $request)
    {
        try {
            /* 
             * Check csrf when image is uploaded by Ajax.
             */
            if ('GET' === strtoupper($request->getMethod())) {
                $token = $request->query->get('csrf_token');
            }
            if ('POST' === strtoupper($request->getMethod())) {
                $token = $request->request->get('csrf_token');
            }
            if (false === $app['infotown.media.security']->isValidToken($token)) {
                $response = new Response();
                $response->setContent(json_encode(['code' => '401']));
                $response->setStatusCode(( 401 ));

                return $response;
            }

            /* 
             * Save canvas image data to EC CUBE image directory
             */
            if ('POST' === strtoupper($request->getMethod()) && ( $request->request->has('imageData') )) {
                $imageData = base64_decode($request->request->get('imageData'));
                $imageType = $request->request->get('imageType');
                $imageName = $request->request->get('imageName');
                $path      = $app['infotown.media.image']->createFileByRaw($imageData, $imageType, $imageName);

                $response = new Response();
                if ($path) {
                    $data = [
                        'code'    => 200,
                        'message' => '<p>WordPressへ画像を保存しました。<br>「メディアライブラリ」タブで画像を読み込んでください。</p>',
                    ];
                    $response->setStatusCode(200);
                    $response->setContent(json_encode($data));
                } else {
                    $data = [
                        'code'    => 500,
                        'message' => '<p>EC-CUBE3へ画像を保存できませんでした。</p>',
                    ];
                    $response->setStatusCode(500);
                    $response->setContent(json_encode($data));
                }

                return $response;
            }

            /*
             *  Get images from EC-CUBE3 image_save_realdir directory.
             */
            $page   = (int) $request->query->get('page');
            $page   = ( !empty( $page ) ) ? $page : 0;
            $images = $app['infotown.media.image']->getAllImg($app['config']['image_save_urlpath']);
            $pager  = $app['infotown.media.pager']->getPager($page, 20, count($images));
            $data   = [
                'images' => array_slice($images, $page * 20, 20),
                'pager'  => $pager,
            ];

            /*
             * Response JSON.
             */
            $response = new Response();
            $response->setContent(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } catch (\Exception $e) {
            $data     = [
                'code'    => '-1',
                'message' => '画像は取得できませんでした',
            ];
            $response = new Response();
            $response->setContent(json_encode($data));
            $response->headers->set('Content-Type', 'application/json');
        }
    }

    public function wordpress(Application $app, Request $request)
    {
        /*
         *  Check csrf when image is uploaded by Ajax.
         */
        if ('GET' === strtoupper($request->getMethod())) {
            $token = $request->query->get('csrf_token');
        }
        if ('POST' === strtoupper($request->getMethod())) {
            $token = $request->request->get('csrf_token');
        }
        if (false === $app['infotown.media.security']->isValidToken($token)) {
            $response = new Response();
            $data     = [
                'code'    => 401,
                'message' => '許可されていません。',
            ];
            $response->setStatusCode(( 401 ));
            $response->setContent(json_encode($data));

            return $response;
        }

        /* 
         * Post canvas data(image data) to WordPress via WP API.
         */
        if ('POST' === strtoupper($request->getMethod()) && ( $request->request->has('canvas') )) {
            $response = new Response();
            /*
             * InfoTownPostWpを呼び出す
             */
            if (isset( $app['infotown.postwp.client'] )) {
                try {
                    $imageData = base64_decode($request->request->get('canvas'));
                    $imageType = $request->request->get('imageType');
                    $imageName = $request->request->get('imageName');
                    $path      = $app['infotown.media.image']->createFileByRaw(
                        $imageData,
                        $imageType,
                        $imageName
                    );
                    $client    = $app['infotown.postwp.client']->getClient();
                    $result    = $app['infotown.postwp.post']->attachMediaFile($client, $path);
                    if (201 === (int) $result['code']) {
                        $data = [
                            'code'    => 201,
                            'message' => '<p>WordPressへ画像を保存しました。<br>「メディアライブラリ」タブで画像を読み込んでください。</p>',
                        ];
                        $response->setContent(json_encode($data));
                        $response->setStatusCode(201);

                        return $response;
                    } else {
                        $data = [
                            'code'    => 500,
                            'message' => '<p>WordPressへ画像を保存できませんでした。</p>',
                        ];
                        $response->setContent(json_encode($data));
                        $response->setStatusCode(500);

                        return $response;
                    }
                } catch (\Exception $e) {
                    $data = [
                        'code'    => $e->getCode(),
                        'message' => $e->getMessage(),
                    ];
                    $response->setContent(json_encode($data));
                    $response->setStatusCode(500);

                    return $response;
                }

            } else {
                $response->setContent(json_encode(['message' => 'InfoTownPostWpがインストールされていません。']));
                $response->setStatusCode(200);

                return $response;
            }

        }

        /*
         *  Get media from WordPress via WP API.
         */
        if ('GET' === strtoupper($request->getMethod())) {
            try {
                $response = new Response();
                $data     = $app['infotown.media.wp_api_get']->getMedia();
                $data     = $app['infotown.media.wp_api_response']->parseMedia($data['body'], 'img');
                $response->setContent(json_encode($data));
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            } catch (\Exception $e) {
                $response->setStatusCode((int) $e->getCode());
                $response->setContent(
                    json_encode(
                        [
                            'code'    => $e->getCode(),
                            'message' => $e->getMessage(),
                        ]
                    )
                );
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }
    }
}