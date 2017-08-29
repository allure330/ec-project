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
 * Handle WordPress configuration.
 * @package Plugin\InfoTownLinkWp\Controller
 */
class ConfigController
{
    /**
     * Handle GET request to configuration.
     * If access method is GET then existing setting render.
     * @param Application $app Instance of application.
     * @param Request $request Instance of Request.
     * @return \Symfony\Component\HttpFoundation\Response HTTP response.
     */
    public function index(Application $app, Request $request)
    {
        if ("GET" === strtoupper($request->getMethod())) {
            // Get configuration form.
            $form = $app['form.factory']->createBuilder('infotown_linkwp_wordpress')->getForm();
            $form->handleRequest($request);

            // Set existing value to form.
            $app['orm.em']
                ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')->setEntityToForm($app, $form);

            // Render form.
            return $app['twig']->render(
                'InfoTownLinkWp\Resource\template\Admin\Config\index.html.twig',
                array('form' => $form->createView())
            );
        }
    }

    /**
     * Handle configuration form submission(POST request) or GET request.
     * If access method is GET then existing setting render.
     * If access method is POST(e.g form submission) then insert or update database.
     * @param Application $app Instance of application.
     * @param Request $request Instance of Request.
     * @return \Symfony\Component\HttpFoundation\Response HTTP response.
     */
    public function wordpressAction(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder('infotown_linkwp_wordpress')->getForm();
        $form->handleRequest($request);

        if ($form->isValid()) {
            // Handle POST request from configuration form.
            if ("POST" === strtoupper($request->getMethod())) {
                if ($form->isValid()) {
                    // Handle valid submission
                    $entity = $app['orm.em']
                        ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')
                        ->getEntity($app, 1);
                    if (!empty( $entity )) {
                        // Update data.
                        $app['orm.em']
                            ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')
                            ->replaceEntity($app, $request);
                    } else {
                        // Add new data.
                        $app['orm.em']
                            ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')
                            ->setEntity($app, $request);
                    }
                    $app['session']->getFlashBag()->add(
                        'infotown.linkwp.message',
                        '保存が完了しました。'
                    );
                    $app['session']->getFlashBag()->add(
                        'infotown.link.message_type',
                        'alert-success'
                    );
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'infotown.linkwp.message',
                '入力内容に誤りがあります。入力内容をご確認ください。'
            );
            $app['session']->getFlashBag()->add(
                'infotown.link.message_type',
                'alert-warning'
            );
        }
        // Handle GET request. Set existing value to form.
        if ("GET" === strtoupper($request->getMethod())) {
            $app['orm.em']->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')->setEntityToForm($app, $form);
        }

        // Render form.
        return $app['twig']->render(
            'InfoTownLinkWp\Resource\template\Admin\Config\index.html.twig',
            array('form' => $form->createView())
        );

    }
}