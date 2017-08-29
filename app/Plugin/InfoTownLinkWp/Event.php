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

namespace Plugin\InfoTownLinkWp;

use Eccube\Event\RenderEvent;
use Eccube\Event\ShoppingEvent;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Render front page.
 */
class Event
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
     * Render WordPress posts to front end product page.
     * That event is fired when product detail page is rendered at front end.
     * @param FilterResponseEvent $event
     */
    public function onRenderProductDetailBefore(FilterResponseEvent $event)
    {
        $app      = $this->app;
        $response = $event->getResponse();
        $html     = $response->getContent();
        /*
         * Replace tag to html markup.
         */
        $matches = [];
        if (preg_match_all('/<!-- linkwp [^>]* -->/u', $html, $matches, PREG_SET_ORDER)) {
            for ($i = 0; $i < count($matches); $i++) {
                $data        = $app['linkwp.wp_api_tag']->parseTag($matches[$i][0]);
                $replaceHtml = '<div class="linkwp">';
                // If tag has post id, then array has only 'post_id', 'format'.
                if (isset( $data['post_id'] ) && '' !== $data['post_id']) {
                    $post = $app['linkwp.wp_api_get']->getPostById($data['post_id']);
                    $post = $app['linkwp.wp_api_response']->parsePost($post['body'], $data['format']);
                    if ($data['format'] === 'links') {
                        $replaceHtml .= '<span class="linkwp-links">'.$post[0]['link'].'</span>';
                    } else {
                        $replaceHtml .= '<div class="linkwp-contents">'.PHP_EOL
                            .'<div>'.$post[0]['title'].'</div>'.PHP_EOL
                            .'<div>'.$post[0]['content'].'</div>'.PHP_EOL
                            .'</div><!-- .linkwp-contents -->'.PHP_EOL;
                    }
                }
                // If tag has filter, then array has only 'filter', 'format'.
                // If tag has not post id and filter then array has empty string 'post_id', empty string 'filter', 'format'.
                if (isset( $data['filter'] )) {
                    $post = $app['linkwp.wp_api_get']->getPosts($data['filter']);
                    $post = $app['linkwp.wp_api_response']->parsePosts($post['body'], $data['format']);
                    if ($data['format'] === 'links') {
                        foreach ($post as $link) {
                            $replaceHtml .= '<span class="linkwp-links">'.$link['link'].'</span>';
                        }
                    } else {
                        foreach ($post as $item) {
                            $replaceHtml .= '<div class="linkwp-contents">'.PHP_EOL
                                .'<div>'.$item['title'].'</div>'.PHP_EOL
                                .'<div>'.$item['content'].'</div>'.PHP_EOL
                                .'</div><!-- .linkwp-contents -->'.PHP_EOL;
                        }
                    }
                }
                $replaceHtml .= '</div><!-- .linkwp -->';
                $html = str_replace($matches[$i][0], $replaceHtml, $html);
            }
        }
        $response->setContent($html);
        $event->setResponse($response);
    }
}