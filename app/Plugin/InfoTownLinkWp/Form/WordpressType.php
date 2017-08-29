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
namespace Plugin\InfoTownLinkWp\Form;

use Symfony\Component\Form;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * WordPress information settings form.
 * @package Plugin\InfoTownLinkWp\Form
 */
class WordpressType extends AbstractType
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
     * Config WordPress settings form.
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->app['url_generator']->generate( 'plugin_InfoTownLinkWp_config_wordpress'))
            ->add(
                'home_url',
                'text',
                [
                    'label'       => 'WordPress サイトアドレス(URL) 必須',
                    'mapped'      => false,
                    'required'    => false,
                    'constraints' => [new Assert\NotBlank(), new Assert\Url()],
                    'attr'        => [
                        'class' => 'form-control',
                    ],
                ]
            )->add(
                'api_url',
                'text',
                [
                    'label'       => 'WP APIパス(デフォルトwp-json) 必須',
                    'data'        => 'wp-json',
                    'mapped'      => false,
                    'required'    => false,
                    'constraints' => [
                        new Assert\Regex(
                            [
                                'match'   => false,
                                'pattern' => "/[^A-Za-z0-9_.!*'()-]/",
                                'message' => '使用できない文字が含まれています。',
                            ]
                        ),
                    ],
                    'attr'        => [
                        'class' => 'form-control',
                    ],
                ]
            )->add(
                'save',
                'submit',
                [
                    'label' => 'WordPress情報保存',
                    'attr'  => [
                        'class' => 'btn btn-default',
                    ],
                ]
            );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function getName()
    {
        return 'infotown_linkwp_wordpress';
    }
}