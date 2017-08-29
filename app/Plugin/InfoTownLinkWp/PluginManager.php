<?php
namespace Plugin\InfoTownLinkWp;

use Symfony\Component\Filesystem\Filesystem;
use Eccube\Plugin\AbstractPluginManager;

/**
 * Handle install, uninstall, enable, disable.
 * @link https://github.com/EC-CUBE/Holiday-plugin/blob/master/PluginManager.php
 * @package Plugin\InfoTownLinkWp
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
    }

    /**
     * Handle InfoTownLinkWp installation.
     * @param $config
     * @param $app
     */
    public function install($config, $app)
    {
        $this->migrationSchema($app, __DIR__ . '/Resource/doctrine/migration', $config['code']);
    }

    /**
     * Handle InfoTownLinkWp un installation.
     * @param $config
     * @param $app
     */
    public function uninstall($config, $app)
    {
        $this->migrationSchema($app, __DIR__ . '/Resource/doctrine/migration', $config['code'], 0);
    }
    public function enable($config, $app)
    {
    }
    public function disable($config, $app)
    {
    }
    public function update($config, $app)
    {
    }
}
