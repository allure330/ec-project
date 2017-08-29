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
namespace Plugin\InfoTownLinkWp\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WordPress configuration.
 * @package Plugin\InfoTownLinkWp\Entity
 */
class Wordpress extends \Eccube\Entity\AbstractEntity
{
    protected $id;
    protected $home_url;
    protected $api_url;

    public function getId()
    {
        return $this->id;
    }
    public function getHomeUrl()
    {
        return $this->home_url;
    }
    public function getApiUrl()
    {
        return $this->api_url;
    }
    
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setHomeUrl($url)
    {
        $this->home_url = $url;
    }
    public function setApiUrl($url)
    {
        $this->api_url = $url;
    }
    
}