<?php
namespace Plugin\InfoTownLinkWp\Tests\Form;

use Eccube\Tests\EccubeTestCase;
use Plugin\InfoTownLinkWp\ServiceProvider\ServiceProvider;

class WordPressTypeTest extends EccubeTestCase
{

    /** @var array デフォルト値（正常系）を設定 */
    private $formData = array(
        'home_url' => 'http://www.findxfine.com',
        'api_url'  => 'wp-json',
    );

    public function setUp()
    {
        parent::setUp();
        $provider = new ServiceProvider();
        $provider->register($this->app);

        // CSRF tokenを無効にしてFormを作成
        $this->form = $this->app['form.factory']
            ->createBuilder(
                'infotown_linkwp_wordpress',
                null,
                array(
                    'csrf_protection' => false,
                )
            )
            ->getForm();
    }

    public function test正常系のテスト()
    {
        $this->form->submit($this->formData);
        $this->assertTrue($this->form->isValid());
    }

    public function testHomeUrlが空白のときバリデーションエラーを返すテスト()
    {
        $this->formData['home_url'] = '';
        $this->form->submit($this->formData);
        $this->assertFalse($this->form->isValid());
    }

    public function testApiUrlが空白のときバリデーションエラーを返すテスト()
    {
        $this->formData['api_url'] = '';
        $this->form->submit($this->formData);
        $this->assertTrue($this->form->isValid());
    }
}