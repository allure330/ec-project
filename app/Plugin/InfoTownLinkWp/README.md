# InfoTownLinkWp

WordPressの投稿内容をEC-CUBE3の商品登録ページへ表示するプラグインです。

## 機能

プラグインをインストールするとコンテンツ管理メニューへ「LinkWp 投稿表示タグ」メニューが追加されます。  
「LinkWp 投稿表示タグ」画面でWordPressの投稿を表示するためのタグを発行します。  
発行されたタグを商品登録画面のフリーエリアへ貼付けることで商品ページへWordPressの投稿を表示します。 

取得形式は「内容」と「リンク」を選択できます。

## プラグイン設定

下記2項目を設定してください。

* WordPress サイトアドレス(URL)
* WP APIパス(デフォルト wp-json)  
  ほとんどの場合、デフォルトのwp-jsonを変更する必要はありません。

## 要件

* WordPress 4.7.4以上
* PHP 5.4以上  
  PHP 5.4で導入された機能を利用しています。
* MySQL  
  EC-CUBE3のインストール要件に準じます。


## 追加テーブル

plg_infotown_linkwp_wordpress  
WordPressの基本設定情報を管理します。

## マークアップ

表示のマークアップは下記のようになります。


### 内容(contents)


	<div class="linkwp">
		<div class="linkwp-contents">
			<div>タイトル1</div>
			<div>
				コンテンツ1です。
			</div>
		</div><!-- .linkwp-contents -->
		.....
		
		<div class="linkwp-contents">
			<div>タイトルN</div>
			<div>
				コンテンツNです。
			</div>
		</div><!-- .linkwp-contents -->
	</div>


### リンク(links)

	<div class="linkwp">
	  <span class="linkwp-links">
		  <a href="http://www.example.com/?p=1">サンプル記事1</a>
	  </span>
	  .....
	  <span class="linkwp-links">
		  <a href="http://www.example.com/?p=n">サンプル記事N</a>
	  </span>
	</div>


## アンインストール時の注意

アンインストールでは商品登録画面のフリーエリアに記載したタグを自動で削除することはありません。  
(タグはHTMLのコメント形式ですのでアンインストール後は単にHTMLのコメントとして扱われます。)


## 変更履歴

## version 2.0.0

* WordPress 4.7.4へ対応しました。
* WordPressのコア機能で利用できる内容へ変更しました。

## version 1.0.1

* フリーエリアの投稿取得タグ複数入力へ対応しました。
* 投稿表示タグ画面でWordPressから取得した内容の編集機能を追加しました。
* 投稿表示タグ画面のモバイル端末での見た目を改善しました。
* 設定画面でユーザーメッセージを改善しました。

## 今後の機能追加予定(変更)

本プラグインでEC-CUBEからWordPressへ新規投稿、編集、削除などを行えるように予定していましたが、
別プラグインとしてリリース予定です。

