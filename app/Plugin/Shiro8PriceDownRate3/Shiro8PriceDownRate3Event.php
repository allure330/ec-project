<?php
/*
* This file is part of EC-CUBE
*
* Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
* http://www.lockon.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Plugin\Shiro8PriceDownRate3;

use Eccube\Application;
use Eccube\Common\Constant;
use Eccube\Entity\Category;
use Eccube\Event\EventArgs;
use Eccube\Event\TemplateEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class Shiro8PriceDownRate3Event
{
    /**
     * @var \Eccube\Application
     */
    private $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

	public function onRenderProductList(TemplateEvent $event)
	{
		$parameters = $event->getParameters();
		
		//商品データの数だけ繰り返す
		$priceDownRates = array();
		foreach ($parameters["pagination"] as $k => $product) {
			$rate = $this->calc($product);
			$priceDownRates[$product["id"]] = $rate;
		}
		
		// twigコードにカテゴリコンテンツを挿入
		$search = '</dd>';
        $replace = '&nbsp;<span class="plgShiro8PriceDownRate3">{{ priceDownRates[Product.id] | raw }}</span></dd>';
        $source = str_replace($search, $replace, $event->getSource());
        //商品一覧コメントに追加された情報を削除
        $search = '{{ Product.description_list|raw|nl2br }}&nbsp;<span class="plgShiro8PriceDownRate3">{{ priceDownRates[Product.id] | raw }}</span></dd>';
        $replace = '{{ Product.description_list|raw|nl2br }}</dd>';
        $source = str_replace($search, $replace, $source);
        
        $event->setSource($source);
        
        // twigパラメータにカテゴリコンテンツを追加
        $parameters["priceDownRates"] = $priceDownRates;
        $event->setParameters($parameters);
	}

	public function onRenderProductDetail(TemplateEvent $event)
	{
		$parameters = $event->getParameters();+
		
		$rate = $this->calc($parameters["Product"]);
		
		// twigコードにカテゴリコンテンツを挿入
        $replace = '<span class="price02_default">$1</span>&nbsp;<span class="plgShiro8PriceDownRate3">{{ priceDownRate | raw }}</span></p>';
        $search = '/<span class="price02_default">(.*)<\/span><\/p>/i';
        $source = preg_replace($search, $replace, $event->getSource());
        
        $event->setSource($source);

        // twigパラメータにカテゴリコンテンツを追加
        $parameters['priceDownRate'] = $rate;
        $event->setParameters($parameters);
	}
	
	/**
     * 商品の値引き率を計算します.
     * 
     * @param string $arrProduct 商品詳細情報の配列
     * @return void
     */
    public function calc($arrProduct) {
    
    	//通常価格がセットされているか判定
    	if ($arrProduct->getPrice01IncTaxMin() > 0) {
    		$priceDownValue = floor(100 - (($arrProduct->getPrice02IncTaxMin() / $arrProduct->getPrice01IncTaxMin()) * 100));
    		if ($priceDownValue > 0) {
    			$rate = $priceDownValue . '%OFF';
    		} else {
    			$rate = '';
    		}
    	}
    	
        return $rate;
    }
}
