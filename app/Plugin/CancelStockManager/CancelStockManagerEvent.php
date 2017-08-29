<?php
/*
 * This file is part of EC-CUBE
 */
namespace Plugin\CancelStockManager;

use Eccube\Application;
use Eccube\Event\RenderEvent;

use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Eccube\Event\EventArgs;

class CancelStockManagerEvent
{
  private $app;

  public function __construct($app)
  {
    $this->app = $app;
  }


  //キャンセルしたら在庫数が戻る
  public function adminOrderEdit(EventArgs $event)
  {
    $app = $this->app;
    $request = $event->getRequest();
    if ('POST' === $request->getMethod()) {
      $TargetOrder = $event["TargetOrder"];
      $OriginOrder = $event["OriginOrder"];
      if (is_null($TargetOrder)) {
        throw new NotFoundHttpException();
      }
      $builder = $app['form.factory']
        ->createBuilder('order', $TargetOrder);
      $form = $builder->getForm();
      $form->handleRequest($request);


      //変更前とステタースが違い、かつキャンセルの場合
      if ($TargetOrder->getOrderStatus()->getId() == $app['config']['order_cancel'] && $OriginOrder->getOrderStatus()->getId() != $TargetOrder->getOrderStatus()->getId()) {

        foreach ($event["OriginOrderDetails"] as $detail) {
          $ProductClass = $detail->getProductClass();
          $ProductStock = $ProductClass->getProductStock();
          $sum = $ProductClass["stock"] + $detail["quantity"];
          $ProductClass->setStock($sum);
          //データの更新
          $app['orm.em']->persist($ProductClass);
          $app['orm.em']->flush();

        }

      }

    }

  }

  public function adminOrderEditBefore(FilterResponseEvent $event){
    $response = $event->getResponse();
    $html = $response->getContent();
    if( !preg_match("/<title>Redirecting to /",$html) ) {
      $crawler = new Crawler($html);
      $oldElement = $crawler
        ->filter('#number_info_box__order_status_info');
      $oldHtml = $oldElement->html();
      $newHtml = '<del>' . $oldHtml . '</del><br>※「キャンセル」に変更すると在庫が自動的に元に戻ります。<br>「キャンセル」の状態からそれ以外のステータスにする時には手動で在庫を調整してください。';
      $html = $crawler->html();
      $html = str_replace($oldHtml, $newHtml, $html);
      $response->setContent('<!doctype html><html lang="ja">' . $html . '</html>');
      $event->setResponse($response);
    }
  }
}
