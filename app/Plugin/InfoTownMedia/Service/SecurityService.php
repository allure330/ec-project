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
namespace Plugin\InfoTownMedia\Service;

use Eccube\Application;

/**
 * Handle security of Ajax CSRF.
 * @package Plugin\InfoTownMedia\Service
 */
class SecurityService
{
    /**
     * @var \Eccube\Application $app
     */
    protected $app;

    /**
     * @param \Eccube\Application $app Get from EC-CUBE3.
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Set token to session.
     */
    public function setToken()
    {
        $this->removeToken();

        return $this->createToken();
    }

    /**
     * Create Token for CSRF.
     */
    public function createToken()
    {
        $token = sha1(uniqid(mt_rand(), true));
        $this->app['session']->set('itmToken', $token);

        return $token;
    }

    /**
     * Remove token form session.
     */
    public function removeToken()
    {
        $this->app['session']->remove('itmToken');
    }

    /**
     * Check token.
     * @param string $token token from post submission.
     * @return boolean Valid token is true, inValid token is false.
     */
    public function isValidToken($token)
    {
        $savedToken = $this->app['session']->get('itmToken');
        if ($savedToken === $token) {
            return true;
        }

        return false;
    }
}