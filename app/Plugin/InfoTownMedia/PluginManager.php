<?php
namespace Plugin\InfoTownMedia;

use Symfony\Component\Filesystem\Filesystem;
use Eccube\Plugin\AbstractPluginManager;

/**
 * Handle install, uninstall, enable, disable.
 * @link https://github.com/EC-CUBE/Holiday-plugin/blob/master/PluginManager.php
 * @package Plugin\InfoTownMedia
 */
class PluginManager extends AbstractPluginManager
{
    /**
     * @var string コピー元リソースディレクトリ
     */
    private $origin;
    /**
     * @var string コピー先リソースディレクトリ
     */
    private $target;

    /**
     * Deploy plugin front end assets.
     */
    public function __construct()
    {
        $this->origin = __DIR__.'/Resource/assets';
        $this->target = __DIR__.'/../../../html/plugin/infotownmedia';
    }

    /**
     * Handle InfoTownMedia installation.
     * 1. Migration.
     * 2. Asset deploy.
     * @param $config
     * @param $app
     */
    public function install($config, $app)
    {
        $this->migrationSchema($app, __DIR__.'/Resource/doctrine/migration', $config['code']);
        $this->copyAssets();
    }

    /**
     * Handle InfoTownMedia un installation.
     * @param $config
     * @param $app
     */
    public function uninstall($config, $app)
    {
        $this->migrationSchema($app, __DIR__.'/Resource/doctrine/migration', $config['code'], 0);
        $this->removeAssets();

    }
    public function enable($config, $app)
    {
    }
    public function disable($config, $app)
    {
    }
    public function update($config, $app)
    {
        $this->migrationSchema($app, __DIR__.'/Resource/doctrine/migration', $config['code']);
        $this->copyAssets();
    }

    /**
     * 画像ファイル等をコピー
     */
    private function copyAssets()
    {
        $file = new Filesystem();
        $file->mirror($this->origin, $this->target.'/assets');
    }

    /**
     * コピーした画像ファイルなどを削除
     */
    private function removeAssets()
    {
        $file = new Filesystem();
        $file->remove($this->target);
    }

}
