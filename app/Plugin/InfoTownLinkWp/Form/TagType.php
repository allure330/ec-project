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
 * Publish tag to get posts from WordPress via WP API.
 *
 * @package Plugin\InfoTownLinkWp\Form
 */
class TagType extends AbstractType
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
     * Config Tag form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'format',
                'choice',
                [
                    'label'             => '取得形式',
                    'mapped'            => false,
                    'choices'           => [
                        'contents' => '内容',
                        'links'    => 'リンク',
                    ],
                    // *this line is important*
                    'choices_as_values' => false,
                    'attr'              => [
                        'class' => 'form-control',
                    ],
                ]
            )
            ->add(
                'post_id',
                'integer',
                [
                    'label'       => '投稿ID',
                    'mapped'      => false,
                    'required'    => false,
                    'constraints' => [new Assert\Type('numeric')],
                    'attr'        => [
                        'class' => 'form-control',
                    ],

                ]
            )->add(
                'per_page',
                'integer',
                [
                    'label'       => '取得投稿数',
                    'mapped'      => false,
                    'required'    => false,
                    'constraints' => [new Assert\Type('numeric')],
                    'attr'        => [
                        'class' => 'form-control',
                    ],
                ]
            )->add(
                'search',
                'text',
                [
                    'label'    => 'キーワード',
                    'mapped'   => false,
                    'required' => false,
                    'attr'     => [
                        'class' => 'form-control',
                    ],
                ]
            )->add(
                'after',
                'text',
                [
                    'label'    => '指定より後に公開した投稿(例: 2017-01-01)',
                    'mapped'   => false,
                    'required' => false,
                    'attr'     => [
                        'class' => 'form-control',
                    ],
                ]
            )->add(
                'before',
                'text',
                [
                    'label'    => '指定より前に公開した投稿(例: 2017-01-01)',
                    'mapped'   => false,
                    'required' => false,
                    'attr'     => [
                        'class' => 'form-control',
                    ],
                ]
            )->add(
                'filters', // 互換性のためfiltersにしています。
                'text',
                [
                    'label'    => 'クエリ文字',
                    'mapped'   => false,
                    'required' => false,
                    'attr'     => [
                        'class' => 'form-control',
                    ],

                ]
            )->add(
                'publish',
                'submit',
                [
                    'label' => 'タグ発行',
                    'attr'  => [
                        'class' => 'btn btn-primary btn-block',
                    ],
                ]
            )->add(
                'reset',
                'reset',
                [
                    'label' => 'リセット',
                    'attr'  => [
                        'class' => 'btn btn-default btn-block',
                    ],
                ]
            );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    public function getName()
    {
        return 'linkwp_tag';
    }
}