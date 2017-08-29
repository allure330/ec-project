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
namespace Plugin\InfoTownMedia\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Media configuration.
 * @package Plugin\InfoTownMedia
 */
class Config extends \Eccube\Entity\AbstractEntity
{
    protected $id;
    protected $image_save_url;
    protected $plugin_url;

    public function getId()
    {
        return $this->id;
    }
    public function getImageSaveUrl()
    {
        return $this->image_save_url;
    }
    public function getPluginUrl()
    {
        return $this->plugin_url;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setImageSaveUrl($url)
    {
        $this->image_save_url = $url;
    }
    public function setPluginUrl($url)
    {
        $this->plugin_url = $url;
    }
}