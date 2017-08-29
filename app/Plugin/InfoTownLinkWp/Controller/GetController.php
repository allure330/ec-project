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

namespace Plugin\InfoTownLinkWp\Controller;

use Eccube\Application;
use Symfony\Component\HttpFoundation\Request;

/**
 * Get posts from WordPress via WP API.
 *
 * @package Plugin\InfoTownLinkWp\Controller
 */
class GetController
{
    /**
     * Handle Tag form to get posts form WordPress via WP API.
     *
     * @param Application $app
     * @param Request     $request
     * @return string Get tag to display posts.
     */
    public function index(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder('linkwp_tag')->getForm();
        // Handle form submission.
        if ("POST" === strtoupper($request->getMethod())) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $post_id = $request->request->get('linkwp_tag')['post_id'];
                $format  = $request->request->get('linkwp_tag')['format'];

                /*
                 * Get tag to display specific post.
                 */
                if (!empty($post_id)) {
                    $response = $app['linkwp.wp_api_get']->getPostById($post_id);
                    $data     = [
                        'code'    => $response['code'],
                        'message' => $response['message'],
                    ];
                    if (200 === (int) $response['code']) {
                        $data['tag']    = $app['linkwp.wp_api_tag']->getTagById($post_id, $format);
                        $data['render'] = $app['linkwp.wp_api_response']->parsePost($response['body'], $format);
                        $data['num']    = 1;
                        $data['format'] = $format;
                    } else {
                        $data['tag']    = '';
                        $data['num']    = '-';
                        $data['format'] = $format;
                    }

                    return $app['twig']->render(
                        'InfoTownLinkWp\Resource\template\Admin\Get\index.html.twig',
                        [
                            'data' => $data,
                            'form' => $form->createView(),
                        ]
                    );
                }

                /* 
                 * Get tag to display filtering posts.
                 */
                if (empty($post_id)) {
                    $filters = $request->request->get('linkwp_tag')['filters'];
                    if (empty($filters)) {
                        $filters = $app['linkwp.wp_api_filter']->getFilter($request->request->get('linkwp_tag'));
                    }
                    $response = $app['linkwp.wp_api_get']->getPosts($filters);
                    $data     = [
                        'code'    => $response['code'],
                        'message' => $response['message'],
                    ];
                    if (200 === (int) $response['code']) {
                        $data['tag']    = $app['linkwp.wp_api_tag']->getTagByFilter($filters, $format);
                        $data['render'] = $app['linkwp.wp_api_response']->parsePosts($response['body'], $format);
                        $data['num']    = count($data['render']);
                        $data['format'] = $format;
                    } else {
                        $data['tag']    = '';
                        $data['num']    = '-';
                        $data['format'] = $format;
                    }

                    return $app['twig']->render(
                        'InfoTownLinkWp\Resource\template\Admin\Get\index.html.twig',
                        [
                            'form' => $form->createView(),
                            'data' => $data,
                        ]
                    );
                }
            } else {
                $app['session']->getFlashBag()->add(
                    'infotown.linkwp.message',
                    '入力内容に誤りがある可能性があります。入力内容をご確認ください。'
                );
                $app['session']->getFlashBag()->add(
                    'infotown.link.message_type',
                    'alert-warning'
                );
            }
        }

        // Response for Get request.
        return $app['twig']->render(
            'InfoTownLinkWp\Resource\template\Admin\Get\index.html.twig',
            [
                'form' => $form->createView(),
                'data' => '',
            ]
        );
    }
}