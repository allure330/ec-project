<?php

/*
 * This file is part of the PluginGenerator
 *
 * Copyright (C) 2016 Cule Inc.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\PluginGenerator\Controller;

use Eccube\Application;
use Eccube\Common\Constant;
use Eccube\Entity\Plugin;
use Eccube\Entity\PluginEventHandler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class ConfigController
{

    /**
     * プラグインジェネレータ用設定画面
     *
     * @param Application $app
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Application $app, Request $request)
    {

        $form = $app['form.factory']->createBuilder('plugingenerator_config')->getForm();

        if ($this->isHookPoint()) {
            // イベント名の呼び出し

            $refrection = new \ReflectionClass('Eccube\Event\EccubeEvents');
            $data = array_flip($refrection->getConstants());

            $admin_hookpoints = array();
            $front_hookpoints = array();
            $mail_hookpoints = array();
            foreach ($data as $key => $item) {
                if (strpos($key, 'admin') === 0) {
                    $admin_hookpoints[$key] = $item;
                } elseif (strpos($key, 'front') === 0) {
                    $front_hookpoints[$key] = $item;
                } else {
                    $mail_hookpoints[$key] = $item;
                }
            }

            $form
                ->add('admin_hookpoints', 'choice', array(
                    'choices' => $admin_hookpoints,
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => '管理画面用フックポイント',
                    'attr' => array(
                        'size' => '30',
                    )
                ))
                ->add('front_hookpoints', 'choice', array(
                    'choices' => $front_hookpoints,
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'フロント画面用フックポイント',
                    'attr' => array(
                        'size' => '30',
                    ),
                ))
                ->add('mail_hookpoints', 'choice', array(
                    'choices' => $mail_hookpoints,
                    'expanded' => false,
                    'multiple' => true,
                    'required' => false,
                    'label' => 'メール送信用フックポイント',
                    'attr' => array(
                        'size' => '10',
                    ),
                ));
        } else {
            $form
                ->add('admin_hookpoints', 'hidden')
                ->add('front_hookpoints', 'hidden')
                ->add('mail_hookpoints', 'hidden');
        }


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $code = $data['code'];

            $Plugin = $app['eccube.repository.plugin']->findOneBy(array('code' => $code));
            if ($Plugin) {
                $app->addError('admin.plugin_generator.save.error', 'admin');

                return $app->render('PluginGenerator/Resource/template/admin/config.twig', array(
                    'form' => $form->createView(),
                ));
            }

            // config.ymlを作成
            $config = array();
            $config['name'] = $data['name'];
            $config['code'] = $code;
            $config['version'] = $data['version'];
            $isEvent = $data['is_event'];
            if ($isEvent) {
                $config['event'] = $code.'Event';
            }
            $isLog = $data['is_log'];

            $config['service'] = array($code.'ServiceProvider');

            $codePath = $app['config']['root_dir'].'/app/Plugin/'.$code;

            $file = new Filesystem();
            $file->mkdir($codePath);

            file_put_contents($codePath.'/config.yml', Yaml::dump($config));

            $author = $data['author'];
            $year = date('Y');

            // PluginManager
            $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/PluginManager.php');
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
            $from = '/\[author\]/';
            $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
            $from = '/\[year\]/';
            $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);

            file_put_contents($codePath.'/PluginManager.php', $pluginFileAfter);

            // ServiceProvider
            if ($isLog == 'none') {
                $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/ServiceProvider.php');
            } else {
                $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/ServiceProvider2.php');
            }
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
            $from = '/\[lower_code\]/';
            $pluginFileAfter = preg_replace($from, mb_strtolower($code), $pluginFileAfter);
            $from = '/\[author\]/';
            $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
            $from = '/\[year\]/';
            $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);
            $from = '/\[log_require\]/';
            $to = PHP_EOL.PHP_EOL.'require_once(__DIR__.\'/../log.php\');';
            if ($isLog == 'none' || $isLog == '3.0.12') {
                $to = '';
            }
            $pluginFileAfter = preg_replace($from, $to, $pluginFileAfter);

            $from = '/\[log_init\]/';
            $to = PHP_EOL.PHP_EOL.'        if (!method_exists(\'Eccube\Application\', \'getInstance\')) {
            eccube_log_init($app);
        }';
            if ($isLog == 'none' || $isLog == '3.0.12') {
                $to = '';
            }
            $pluginFileAfter = preg_replace($from, $to, $pluginFileAfter);

            $file->mkdir($codePath.'/ServiceProvider');

            file_put_contents($codePath.'/ServiceProvider/'.$code.'ServiceProvider.php', $pluginFileAfter);

            // log.php
            if ($isLog == '3.0.9') {
                $file->copy(__DIR__.'/../Resource/assets/log.php', $codePath.'/log.php');
            }

            // ConfigController
            $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/ConfigController.php');
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
            $from = '/\[author\]/';
            $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
            $from = '/\[year\]/';
            $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);
            $from = '/\[code_name\]/';
            $pluginFileAfter = preg_replace($from, mb_strtolower($code), $pluginFileAfter);

            $file->mkdir($codePath.'/Controller');

            file_put_contents($codePath.'/Controller/ConfigController.php', $pluginFileAfter);

            // Controller
            $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/Controller.php');
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
            $from = '/\[author\]/';
            $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
            $from = '/\[year\]/';
            $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);
            $from = '/\[code_name\]/';
            $pluginFileAfter = preg_replace($from, mb_strtolower($code), $pluginFileAfter);

            file_put_contents($codePath.'/Controller/'.$code.'Controller.php', $pluginFileAfter);

            // Form
            $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/ConfigType.php');
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
            $from = '/\[author\]/';
            $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
            $from = '/\[year\]/';
            $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);
            $from = '/\[code_name\]/';
            $pluginFileAfter = preg_replace($from, mb_strtolower($code), $pluginFileAfter);

            $file->mkdir($codePath.'/Form/Type');

            file_put_contents($codePath.'/Form/Type/'.$code.'ConfigType.php', $pluginFileAfter);

            // Twig
            $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/config.twig');
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);

            $file->mkdir($codePath.'/Resource/template/admin');

            file_put_contents($codePath.'/Resource/template/admin/config.twig', $pluginFileAfter);

            // index.twig
            $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/index.twig');
            $from = '/\[code\]/';
            $pluginFileAfter = preg_replace($from, mb_strtolower($code), $pluginFileBefore);

            $file->mkdir($codePath.'/Resource/template/admin');

            file_put_contents($codePath.'/Resource/template/index.twig', $pluginFileAfter);

            // event.yml
            $hookpointFunctions = array();
            if ($this->isHookPoint()) {

                if ($isEvent) {
                    $eventYml = file_get_contents(__DIR__.'/../Resource/assets/event2.yml');
                    file_put_contents($codePath.'/event.yml', $eventYml, FILE_APPEND);
                    $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/EventHookpoint2.php');
                } else {
                    $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/EventHookpoint.php');
                }

                $events = array();
                $hookpoints = $data['admin_hookpoints'];
                foreach ($hookpoints as $hookpoint) {
                    $functionName = 'on.'.$hookpoint;
                    $functionName = lcfirst(strtr(ucwords(strtr($functionName, array('.' => ' '))), array(' ' => '')));
                    $events[$hookpoint] = array(array($functionName.', NORMAL'));
                    $hookpointFunctions[] = $functionName;
                }

                $hookpoints = $data['front_hookpoints'];
                foreach ($hookpoints as $hookpoint) {
                    $functionName = 'on.'.$hookpoint;
                    $functionName = lcfirst(strtr(ucwords(strtr($functionName, array('.' => ' '))), array(' ' => '')));
                    $events[$hookpoint] = array(array($functionName.', NORMAL'));
                    $hookpointFunctions[] = $functionName;
                }

                $hookpoints = $data['mail_hookpoints'];
                foreach ($hookpoints as $hookpoint) {
                    $functionName = 'on.'.$hookpoint;
                    $functionName = lcfirst(strtr(ucwords(strtr($functionName, array('.' => ' '))), array(' ' => '')));
                    $events[$hookpoint] = array(array($functionName.', NORMAL'));
                    $hookpointFunctions[] = $functionName;
                }

                if (count($events) > 0) {
                    // eventが設定されていれば作成
                    // file_put_contents($codePath.'/event.yml', str_replace('\'', '', Yaml::dump($events)));
                    file_put_contents($codePath.'/event.yml', str_replace('\'', '', Yaml::dump($events)), FILE_APPEND);

                    // Event
                    $from = '/\[code\]/';
                    $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
                    $from = '/\[author\]/';
                    $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
                    $from = '/\[year\]/';
                    $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);

                    $functions = '';
                    foreach ($hookpointFunctions as $functionName) {
                        $functions .= "    public function ".$functionName."(EventArgs \$event)\n    {\n    }\n\n";
                    }
                    $from = '/\[hookpoint_function\]/';
                    $pluginFileAfter = preg_replace($from, $functions, $pluginFileAfter);

                    file_put_contents($codePath.'/'.$code.'Event.php', $pluginFileAfter);

                    // config.ymlを再作成
                    $config = array();
                    $config['name'] = $data['name'];
                    $config['code'] = $code;
                    $config['version'] = $data['version'];
                    $config['event'] = $code.'Event';
                    $config['service'] = array($code.'ServiceProvider');
                    file_put_contents($codePath.'/config.yml', Yaml::dump($config));


                } else {
                    if ($isEvent) {
                        $file->copy(__DIR__.'/../Resource/assets/event2.yml', $codePath.'/event.yml');
                        // Event
                        $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/Event2.php');
                        $from = '/\[code\]/';
                        $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
                        $from = '/\[author\]/';
                        $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
                        $from = '/\[year\]/';
                        $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);

                        file_put_contents($codePath.'/'.$code.'Event.php', $pluginFileAfter);
                    }
                }
            } else {
                if ($isEvent) {
                    $file->copy(__DIR__.'/../Resource/assets/event.yml', $codePath.'/event.yml');

                    // Event
                    $pluginFileBefore = file_get_contents(__DIR__.'/../Resource/assets/Event.php');
                    $from = '/\[code\]/';
                    $pluginFileAfter = preg_replace($from, $code, $pluginFileBefore);
                    $from = '/\[author\]/';
                    $pluginFileAfter = preg_replace($from, $author, $pluginFileAfter);
                    $from = '/\[year\]/';
                    $pluginFileAfter = preg_replace($from, $year, $pluginFileAfter);

                    file_put_contents($codePath.'/'.$code.'Event.php', $pluginFileAfter);
                }
            }

            // LICENSE
            $file->copy(__DIR__.'/../Resource/assets/LICENSE', $codePath.'/LICENSE');

            // DB登録
            $Plugin = new Plugin();
            $Plugin->setName($config['name']);
            $Plugin->setCode($code);
            $Plugin->setClassName('');
            $Plugin->setVersion($config['version']);
            $Plugin->setEnable(Constant::DISABLED);
            $Plugin->setSource(0);
            $Plugin->setDelFlg(Constant::DISABLED);

            $app['orm.em']->persist($Plugin);
            $app['orm.em']->flush($Plugin);

            foreach ($hookpointFunctions as $functionName) {
                $PluginEventHandler = new PluginEventHandler();
                $eventName = ltrim(strtolower(preg_replace('/[A-Z]/', '.\0', $functionName)), '_');
                $eventName = str_replace('on.', '', $eventName);
                $PluginEventHandler->setPlugin($Plugin)
                    ->setEvent($eventName)
                    ->setPriority($app['eccube.repository.plugin_event_handler']->calcNewPriority($eventName, $functionName))
                    ->setHandler($functionName)
                    ->setHandlerType('NORMAL')
                    ->setDelFlg(Constant::DISABLED);
                $app['orm.em']->persist($PluginEventHandler);
            }
            $app['orm.em']->flush();

            $app->addSuccess('admin.plugin_generator.save.complete', 'admin');

            return $app->redirect($app->url('admin_store_plugin'));
        }

        return $app->render('PluginGenerator/Resource/template/admin/config.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * 3.0.9以上かチェック
     * 3.0.9以上であれば新しいフックポイントが利用可能
     *
     * @return mixed
     */
    private function isHookPoint()
    {
        return version_compare(Constant::VERSION, '3.0.9', '>=');
    }

}
