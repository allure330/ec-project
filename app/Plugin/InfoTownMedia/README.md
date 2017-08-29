# InfoTownMedia

商品登録編集のフリーエリアへ画像挿入機能を追加します。   

## ライセンス

### InfoTownMedia

InfoTownMediaの著作権は澤井 寛にあります。  
InfoTownMediaはLGPLのもと配布しています。

またInfoTownMediaは下記リソースをバンドルしています。

### CKEditor

[CKEditor.com | The best web text editor for everyone](http://ckeditor.com/)  
CKEditor - The text editor for Internet - http://ckeditor.com  
Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.  
CKEditor is distributed under the GPL, LGPL, and MPL Open Source licenses.

## 確認ブラウザ

* Google Chrome 最新版(2016.01.18時点)
* Firefox 最新版(2016.01.18時点)
* Safari 最新版(2016.01.18時点)
* Internet Explorer 11

__Microsoft Edgeは対応しておりません。__

## 機能

機能は2つあります。

1. 画像の一覧表示とフリーエリアへ挿入(メディアライブラリ)  
   EC-CUBE3の画像保存ディレクトリ(html/upload/save_image)の画像を一覧表示します。  
   一覧から画像を選択しフリーエリアへ挿入できます。  
   同様に画像一覧の画像をフリーエリアへドラッグ&ドロップしても挿入できます。  
   (プラグインをインストールし有効にするとフリーエリアでビジュアルエディタ(CKEditor)が利用できます。)
2. 画像アップロード機能(ファイルのアップロード)  
   画像をEC-CUBE3の画像ディレクトリ(html/upload/save_image)へ保存します。  
   (GIF画像はPNG形式へ変換されます。)


またInfoTownLinkWpと連携し、  
WordPressの画像をメディアライブラリへ表示することができます。

## 要件

__PHP5.3.9ではご利用いただけません。__

* PHP 5.4以上  
  PHP 5.4で導入された機能を利用しています。
* MySQL  
  EC-CUBE3のインストール要件に準じます。

## 依存

本プラグインはCKEditorを利用しています。

* [CKEditor.com | The best web text editor for everyone](http://ckeditor.com/)  
  Version 4.5.6

## 追加テーブル

plg_infotown_media_config

EC-CUBE3がドキュメントルートへインストールされていない場合、  
プラグインディレクトリと画像保存ディレクトリのパスを保存します。  
__現在はプラグインディレクトリのみ対応しています。__  
__画像保存ディレクトリの指定機能は実装されていません。__

## 注意

本プラグインは商品登録編集でフリーエリアへ画像を挿入することに特化しています。  
画像と商品の紐付け情報をデータベースで保存する機能はありません。

## コーディング規約

* InfoTownMediaで追加したCSSのid名,class名はローワーキャメルケースを用います。  
  (Twigでハイフン(-)が数学の演算子として扱われるためです。)
* InfoTownMediaで追加したCSSはプレフィックスitm(InfoTownMedia)を付けています。

## アップデートのの注意点

アップデートで正しく動作しない場合、キャッシュの影響が考えられます。  
キャッシュをクリアして再度処理を行ってください。

* Twig
  EC-CUBE3管理画面 > コンテンツ管理 > キャッシュ管理からキャッシュをクリアできます。
* JavaScript
  ブラグザごとに操作がことなりますので各ブラウザに合わせた処理をしてください。

## 変更履歴

## version 1.0.6

* EC-CUBE 3.0.10へ対応しました。
  (InfoTownMedia/Resource/template/Admin/Product/product.html.twigを修正しました。)

## version 1.0.5

* 画像一覧へページャー機能を追加しました(1ページ20件表示します)。
* サブディレクトリを持つ画像ディレクトリの画像読み込み機能を改善しました。
* アップデートでアセットが更新されない問題を改善しました。

## version 1.0.4

* README.mdへライセンスの記載を追加しました。
* アップロード機能のファイル命名規則を変更しました。  
  元画像ファイル名_タイムスタンプ.拡張子(pngまたはjpg)  
  変更前は元画像名によらずinfotown_media_タイムスタンプ.pngでした。
* アップロード機能がJPG形式へ対応しました。  
  (GIFファイルはPNGへ変換されます。)
* 画像挿入形式が絶対パスとURLを選択可能になりました。

## version 1.0.3

* Firefoxでダイアログが表示されない問題を改善しました。
* README.mdへ確認ブラグザを記載しました。

## version 1.0.2

* 画像をオリジナル画像のサイズで挿入するよう改善しました。
* alt属性を設定できるよう改善しました。

### version 1.0.1

* 画像機能をダイアログで表示するよう変更しました。
* フリーエリアへ画像一覧から画像を選択し挿入できるようになりました。  
  (version 1.0.0はドラッグ&ドロップでのみ挿入できました。)
* pluginディレクトリを指定できるようになりました。
