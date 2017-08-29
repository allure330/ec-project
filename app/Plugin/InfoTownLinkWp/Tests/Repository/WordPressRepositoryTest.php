<?php
namespace Plugin\InfoTownLinkWp\Tests\Repository;

use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Plugin\InfoTownLinkWp\ServiceProvider\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class WordPressRepositoryTest extends AbstractAdminWebTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    public function testGetEntityでidが1のレコードを正しく取得できるテスト()
    {
        $entity = $this->app['orm.em']
            ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')
            ->getEntity($this->app, 1);
        $expect = [
            'id'      => 1,
            'api_url' => 'wp-json',
        ];
        $actual = [
            'id'      => $entity->getId(),
            'api_url' => $entity->getApiUrl(),
        ];
        $this->assertEquals($expect, $actual);
    }

    public function testレコードが空でないときエンティティのidは必ず1となるテスト()
    {
        $entity = $this->app['orm.em']->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')->find(2);
        $this->assertEmpty($entity);
    }

    public function testReplaceEntityが正しく更新するテスト()
    {
        $expect = [
            'home_url' => 'http://www.findxfine.com',
            'api_url'  => 'wp-json',
        ];
        // HttpFoundation\Requestをエミュレート
        $request = Request::create(
            $this->app['config']['admin_route'].'/plugin/InfoTownLinkWp/wordpress',
            'POST',
            ['infotown_linkwp_wordpress' => $expect]
        );
        // 更新処理
        $this->app['orm.em']
            ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')
            ->replaceEntity($this->app, $request);
        // 更新検証
        $entity = $this->app['orm.em']
            ->getRepository('Plugin\InfoTownLinkWp\Entity\Wordpress')
            ->getEntity($this->app, 1);
        $actual = [
            'home_url' => $entity->getHomeUrl(),
            'api_url'  => $entity->getApiUrl(),
        ];
        $this->assertEquals($expect, $actual);
    }
}