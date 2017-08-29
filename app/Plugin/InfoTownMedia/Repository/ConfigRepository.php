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
namespace Plugin\InfoTownMedia\Repository;

use Doctrine\ORM\EntityRepository;
use Eccube\Application;
use Plugin\InfoTownMedia\Entity\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * Model for Config entity.
 * @package Plugin\InfoTownMedia\Repository
 */
class ConfigRepository extends EntityRepository
{
    /**
     * Get Config entity from table.
     * @param Application $app instance of application.
     * @param int $id Config entity id.
     * @return Config Config entity.
     */
    public function getEntity(Application $app, $id = 1)
    {
        return $app['orm.em']->getRepository('Plugin\InfoTownMedia\Entity\Config')->find($id);
    }

    /**
     * Insert Config entity to table.
     * @param Application $app instance of application
     * @param Request $request HTTP request from form submission.
     */
    public function setEntity(Application $app, Request $request)
    {
        $entity = new Config();
        $entity->setId(1);
        $this->setFormDataToEntity($request, $entity);
        $app['orm.em']->persist($entity);
        $app['orm.em']->flush();
    }

    /**
     * Update config data in table
     * @param Application $app instance of application.
     * @param Request $request HTTP request from form submission.
     */
    public function replaceEntity(Application $app, Request $request)
    {
        $entity = $this->getEntity($app, 1);
        $this->setFormDataToEntity($request, $entity);
        $app['orm.em']->flush();
    }

    /**
     * Set form data to Config entity.
     * Data is required by OAuth1 Authentication.
     *     - image_save_url
     *     - plugin_url
     * @param Request $request HTTP request from form submission.
     * @param Config $config Entity for Media1.
     */
    private function setFormDataToEntity(Request $request, Config $entity)
    {
        $entity->setImageSaveUrl($request->request->get('infotown_media_config')['image_save_url']);
        $entity->setPluginUrl($request->request->get('infotown_media_config')['plugin_url']);
    }

    /**
     * Set Config entity to form.
     *     - image_save_url
     *     - plugin_url
     * @param Media $oauth Entity for Media1.
     * @param Form $form Form to handle credential.
     */
    public function setEntityToForm(Application $app, Form $form)
    {
        $entity = $this->getEntity($app, 1);
        
        if ($entity) {
            $form->get('image_save_url')->setData($entity->getImageSaveUrl());
            $form->get('plugin_url')->setData($entity->getPluginUrl());

            return true;
        }

        return false;
    }
}