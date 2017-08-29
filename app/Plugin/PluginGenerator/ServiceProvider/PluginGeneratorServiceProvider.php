<?php

/*
 * This file is part of the PluginGenerator
 *
 * Copyright (C) 2016 Cule Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\PluginGenerator\ServiceProvider;

use Eccube\Common\Constant;
use Monolog\Handler\FingersCrossed\ErrorLevelActivationStrategy;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Plugin\PluginGenerator\Form\Type\PluginGeneratorConfigType;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;

class PluginGeneratorServiceProvider implements ServiceProviderInterface
{

    public function register(BaseApplication $app)
    {

        // 管理画面定義
        $admin = $app['controllers_factory'];
        // 強制SSL
        if ($app['config']['force_ssl'] == Constant::ENABLED) {
            $admin->requireHttps();
        }

        // プラグイン用設定画面
        $admin->match('/plugin/plugingenerator/config', 'Plugin\PluginGenerator\Controller\ConfigController::index')->bind('plugin_PluginGenerator_config');

        $app->mount('/'.trim($app['config']['admin_route'], '/').'/', $admin);

        // Form
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) {
            $types[] = new PluginGeneratorConfigType();

            return $types;
        }));

        // メッセージ登録
        $file = __DIR__.'/../Resource/locale/message.'.$app['locale'].'.yml';
        $app['translator']->addResource('yaml', $file, $app['locale']);

        if (isset($app['config']['PluginGenerator']['const'])) {
            $config = $app['config'];
            $app['plugingeneratorconfig'] = $app->share(function () use ($config) {
                return $config['PluginGenerator']['const'];
            });
        }

        // ログファイル設定
        $app['monolog.logger.plugingenerator'] = $app->share(function ($app) {

            $logger = new $app['monolog.logger.class']('plugingenerator');

            $filename = $app['config']['root_dir'].'/app/log/plugingenerator.log';
            $RotateHandler = new RotatingFileHandler($filename, $app['config']['log']['max_files'], Logger::INFO);
            $RotateHandler->setFilenameFormat(
                'plugingenerator_{date}',
                'Y-m-d'
            );

            $logger->pushHandler(
                new FingersCrossedHandler(
                    $RotateHandler,
                    new ErrorLevelActivationStrategy(Logger::ERROR),
                    0,
                    true,
                    true,
                    Logger::INFO
                )
            );

            return $logger;
        });

    }

    public function boot(BaseApplication $app)
    {
    }

}
