<?php
namespace Plugin\InfoTownMedia;

use Eccube\Event\RenderEvent;
use Eccube\Event\ShoppingEvent;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Define event handler. Event describes event.yml.
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
     * Render media area of InfoTownMedia.
     * Event is fired when product detail page is rendered at admin.
     * @param FilterResponseEvent $event target event.
     */
    public function onRenderAdminProductProductNewBeforeInfoTownMedia(FilterResponseEvent $event)
    {
        if (200 === (int) $event->getResponse()->getStatusCode()) {
            $response = $event->getResponse();

            // Get default html.
            $html = $response->getContent();

            // Get Crawler for treat DOM.
            $crawler = new Crawler($html);

            // Get plugin configuration.
            $entity         = $this->app['orm.em']
                ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                ->getEntity($this->app, 1);
            $plugin_url     = '';
            if (!empty( $entity ) && '' != $entity->getPluginUrl()) {
                $plugin_url     = rtrim($entity->getPluginUrl(), '/') . '/';
            }
            /*
             * Set plugin view.
             */
            $twig = $this->app->renderView(
                'InfoTownMedia/Resource/template/Admin/Product/product.html.twig',
                [
                    'itmToken'  => $this->app['infotown.media.security']->setToken(),
                    'pluginUrl' => $plugin_url,
                ]
            );
            $beforeHtml = $crawler
                ->filter('.col-md-9 .box')
                ->last()
                ->html();
            $afterHtml  = $beforeHtml.$twig;
            $html       = $crawler->html();
            $html       = str_replace($beforeHtml, $afterHtml, $html);
            $response->setContent($html);

            /*
             * Set response to event.
             */
            $event->setResponse($response);
        }
    }

    /**
     * Render media area of InfoTownMedia.
     * Event is fired when product detail page is rendered at admin.
     * @param FilterResponseEvent $event target event.
     */
    public function onRenderAdminProductProductEditBeforeInfoTownMedia(FilterResponseEvent $event)
    {
        if (200 === (int) $event->getResponse()->getStatusCode()) {
            $response = $event->getResponse();
            
            // Get default html.
            $html = $response->getContent();

            // Get Crawler for treat DOM.
            $crawler = new Crawler($html);

            // Get plugin configuration.
            $entity         = $this->app['orm.em']
                ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                ->getEntity($this->app, 1);
            $plugin_url     = '';
            if (!empty( $entity ) && '' != $entity->getPluginUrl()) {
                $plugin_url     = rtrim($entity->getPluginUrl(), '/') . '/';
            }
            /*
             * Set plugin view.
             */
            $twig = $this->app->renderView(
                'InfoTownMedia/Resource/template/Admin/Product/product.html.twig',
                [
                    'itmToken'  => $this->app['infotown.media.security']->setToken(),
                    'pluginUrl' => $plugin_url,
                ]
            );
            $beforeHtml = $crawler
                ->filter('.col-md-9 .box')
                ->last()
                ->html();
            $afterHtml  = $beforeHtml.$twig;
            $html       = $crawler->html();
            $html       = str_replace($beforeHtml, $afterHtml, $html);
            $response->setContent($html);

            /*
             * Set response to event.
             */
            $event->setResponse($response);
        }
    }

}