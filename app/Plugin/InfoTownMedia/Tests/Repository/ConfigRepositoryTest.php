<?php
namespace Plugin\InfoTownMedia\Tests\Repository;

use Eccube\Tests\EccubeTestCase;
use Plugin\InfoTownMedia\ServiceProvider\ServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class ConfigRepositoryTest extends EccubeTestCase
{
    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);
    }

    public function testReplaceEntityはただしくフィールドを更新できるテスト()
    {
        $expect = [
            'plugin_url'     => '/my_plugin_dir/',
            'image_save_url' => '/my_save_dir/',
        ];
        // HttpFoundation\Requestをエミュレート
        $request = Request::create(
            $this->app['config']['admin_route'].'/plugin/InfoTownMedia/config',
            'POST',
            ['infotown_media_config' => $expect]
        );
        // 更新処理
        $this->app['orm.em']
            ->getRepository('Plugin\InfoTownMedia\Entity\Config')
            ->replaceEntity($this->app, $request);
        // 更新検証
        $entity = $this->app['orm.em']
            ->getRepository('Plugin\InfoTownMedia\Entity\Config')
            ->getEntity($this->app, 1);
        $actual = [
            'plugin_url'     => $entity->getPluginUrl(),
            'image_save_url' => $entity->getImageSaveUrl(),
        ];
        $this->assertEquals($expect, $actual);
    }

    public function testReplaceEntityは新規レコードを追加しないテスト()
    {
        for ($i = 0; $i < 10; $i++) {
            $expect  = [
                'plugin_url'     => 'test',
                'image_save_url' => 'test',
            ];
            $request = Request::create(
                $this->app['config']['admin_route'].'/plugin/InfoTownMedia/config',
                'POST',
                ['infotown_media_config' => $expect]
            );
            $this->app['orm.em']
                ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                ->replaceEntity($this->app, $request);
        }
        $query  = $this->app['orm.em']->createQuery('SELECT COUNT(c.id) FROM Plugin\InfoTownMedia\Entity\Config c');
        $actual = $query->getSingleScalarResult();
        $this->assertEquals(1, $actual);
    }

    public function testGetEntityはレコードが空でないときidが1のEntityを返すテスト()
    {
        $entity = $this->app['orm.em']
            ->getRepository('Plugin\InfoTownMedia\Entity\Config')
            ->findAll();
        if (!empty( $entity )) {
            $entity = $this->app['orm.em']
                ->getRepository('Plugin\InfoTownMedia\Entity\Config')
                ->getEntity($this->app);

            $this->assertEquals(1, $entity->getId());
        }
    }

    function tearDown()
    {
        /* 空の値を設定 */
        $expect = [
            'plugin_url'     => '',
            'image_save_url' => '',
        ];
        // HttpFoundation\Requestをエミュレート
        $request = Request::create(
            $this->app['config']['admin_route'].'/plugin/InfoTownMedia/config',
            'POST',
            ['infotown_media_config' => $expect]
        );
        // 更新処理
        $this->app['orm.em']
            ->getRepository('Plugin\InfoTownMedia\Entity\Config')
            ->replaceEntity($this->app, $request);
        $entity = $this->app['orm.em']
            ->getRepository('Plugin\InfoTownMedia\Entity\Config')
            ->getEntity($this->app, 1);
    }
}