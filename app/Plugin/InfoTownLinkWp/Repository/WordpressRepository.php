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
namespace Plugin\InfoTownLinkWp\Repository;

use Doctrine\ORM\EntityRepository;
use Eccube\Application;
use Plugin\InfoTownLinkWp\Entity\Wordpress;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * Model for Wordpress.
 * @package Plugin\InfoTownLinkWp\Repository
 */
class WordpressRepository extends EntityRepository
{
    /**
     * Get Wordpress Entity from table.
     * @param Application $app Get from EC-CUBE3.
     * @param int $id Wordpress Entity id.
     * @return Wordpress Wordpress Entity.
     */
    public function getEntity(Application $app, $id = 1)
    {
        return $app['orm.em']->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')->find($id);
    }

    /**
     * Insert WordPress configuration data to table.
     * @param Application $app Get from EC-CUBE3.
     * @param Request $request HTTP request from form submission.
     */
    public function setEntity(Application $app, Request $request)
    {
        $entity = new Wordpress();
        $entity->setId(1);
        $this->setFormDataToEntity($request, $entity);
        $app['orm.em']->persist($entity);
        $app['orm.em']->flush();
    }

    /**
     * Update WordPress configuration data in table
     * @param Application $app Get from EC-CUBE3.
     * @param Request $request HTTP request from form submission.
     */
    public function replaceEntity(Application $app, Request $request)
    {
        $entity= $this->getEntity($app, 1);
        $this->setFormDataToEntity($request, $entity);
        $app['orm.em']->flush();
    }

    /**
     * Set request data to Wordpress Entity.
     * @param Request $request HTTP request from form submission.
     * @param Wordpress $entity Entity for WordPress configuration.
     */
    private function setFormDataToEntity(Request $request, Wordpress $entity)
    {
        $entity->setHomeUrl($request->request->get('infotown_linkwp_wordpress')['home_url']);
        $entity->setApiUrl($request->request->get('infotown_linkwp_wordpress')['api_url']);
        
    }

    /**
     * Set form data from Wordpress Entity.
     * @param Application $app Get from EC-CUBE3.
     * @param Form $form Form to handle credential.
     */
    public function setEntityToForm(Application $app, Form $form)
    {
        $entity = $this->getEntity($app, 1);
        if ($entity) {
            $form->get('home_url')->setData($entity->getHomeUrl());
            $form->get('api_url')->setData($entity->getApiUrl());

            return true;
        }

        return false;
    }
}