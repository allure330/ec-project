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
use Symfony\Component\HttpFoundation\RedirectResponse;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Client;
use Guzzle\Plugin\Oauth\OauthPlugin;
/**
 * Handle InfoTown Media configuration.
 * @package Plugin\InfoTownMedia\Controller
 */
class ConfigController
{
    /**
     * Handle form submission(post request) or get request.
     * If access method is GET then existing setting render.
     * If access method is POST(e.g form submission) then insert or update database.
     * @param Application $app Get from EC-CUBE3.
     * @param Request $request Get from Symfony.
     * @return \Symfony\Component\HttpFoundation\Response HTTP response.
     */
    public function index(Application $app, Request $request)
    {
        $form = $app['form.factory']->createBuilder('infotown_media_config')->getForm();
        // Handle form submission.
        if ("POST" === strtoupper($request->getMethod())) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                // Handle valid submission
                $entity = $app['orm.em']
                    ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                    ->getEntity($app, 1);
                if ( ! empty( $entity )) {
                    // Update data.
                    $app['orm.em']
                        ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                        ->replaceEntity($app, $request);
                } else {
                    // Add new data.
                    $app['orm.em']
                        ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                        ->setEntity($app, $request);
                }
            }
        }

        // Set existing value to form.
        if ("GET" === strtoupper($request->getMethod())) {
            $app['orm.em']
                ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                ->setEntityToForm($app, $form);
        }

        // Render form.
        return $app['twig']->render(
            'InfoTownMedia\Resource\template\Admin\Config\index.html.twig',
            ['form' => $form->createView()]
        ); 
    }

}