/**
 * InfoTownMedia top level object.
 *
 * @namespace InfoTownMedia
 * @author Hiroshi Sawai <info@info-town.jp>
 */
var InfoTownMedia = {};

jQuery( function ( $ ) {
	/**
	 * InfoTownMedia common functions.
	 *
	 * @class Media
	 * @author Hiroshi Sawai <info@info-town.jp>
	 */
	InfoTownMedia.Media = (function () {
		var textAreaId;
		/**
		 * Open InfoTownMedia dialog.
		 */
		$( document ).on( 'click', ".itmOpenDialog", function () {
			textarea = $( this ).parent().next();
			setTextAreaId( textarea.attr( 'id' ) );
			$( "#infoTownMedia" ).dialog( {
				modal: false,
				buttons: {
					Ok: function () {
						$( this ).dialog( "close" );
					},
				},
				width: 840,
				position: {
					of: this,
					at: "left top",
					my: "left bottom"
				}
			} );
			return false;
		} );

		/**
		 * Get full url.
		 * (ex http://www.example/upload/save_image/example.png)
		 * @method getFullUrl
		 * @param {String} path Full path.
		 * @returns {string} url to admin.
		 */
		function getFullUrl( path ) {
			return location.protocol + '//' + location.host + path;
		}

		/**
		 * Set textarea id name
		 * @method setTextArea
		 * @public
		 * @param {String} name textarea id.
		 *
		 */
		function setTextAreaId( name ) {
			textAreaId = name;
		}

		/**
		 * Get textarea id name.
		 * @return {String} textarea id name.
		 */
		function getTextAreaId() {
			return textAreaId;
		}

		/**
		 * Get url of admin.
		 * (ex http://www.example/index.php/admin)
		 * @method getAdminUrl
		 * @returns {string} url to admin.
		 */
		function getAdminUrl() {
			var current, data, index, url = [];
			current = window.location.href;
			data = current.split( "/" );
			index = data.indexOf( "product" );

			for ( var i = 0; i < index; i++ ) {
				url[i] = data[i];
			}
			return url.join( '/' );
		}

		/**
		 * Get CSRF token for Ajax request.
		 * @method getToken
		 * @return {String} csrf token.
		 */
		function getToken() {
			return $( "input[name='itmToken']" ).val();
		}

		/* Public method */
		return {
			getFullUrl: getFullUrl,
			getTextAreaId: getTextAreaId,
			setTextAreaId: setTextAreaId,
			getAdminUrl: getAdminUrl,
			getToken: getToken
		}
	}());

} );
jQuery( function ( $ ) {
	/**
	 * Handle media area UI of InfoTownMedia.
	 *
	 * @class MediaUI
	 * @author Hiroshi Sawai <info@info-town.jp>
	 */
	InfoTownMedia.MediaUI = (function () {
		/**
		 * Handle tab navigation of InfoTownMedia.
		 * @method #media_tab.a.click
		 * @public
		 */
		$( '#itmTab' ).find( 'a' ).click( function ( e ) {
			var id;
			e.preventDefault();
			id = $( this ).attr( "aria-controls" );
			$( "#itmContent" ).find( "div" ).each( function () {
				if ( id == $( this ).attr( 'id' ) ) {
					$( this ).addClass( 'active' );
				} else {
					$( this ).removeClass( 'active' );
				}
			} );
		} );
	}());

} );
jQuery( function ( $ ) {
	/**
	 * Handle media of InfoTownMedia in Product registration page.
	 *
	 * @class MediaLibrary
	 * @author Hiroshi Sawai <info@info-town.jp>
	 */
	InfoTownMedia.MediaLibrary = (function () {
		var url, width, height;

		/*
		 * Load images of EC-CUBE3 to itmBrowserMain. 
		 * @method #itmLoadCubeMedia.click
		 * @public
		 */
		$( "#itmLoadCubeMedia" ).on( "click", function () {
			loadImages( 0 );
		} );

		/**
		 * Load images by pager click.
		 * @method document.click
		 * @public
		 */
		$( document ).on( 'click', "#itmBrowserPager span", function () {
			page = $( this ).data( "page" );
			if ( $( this ).attr( "class" ) === "itmBrowserNext" ) {
				loadImages( parseInt( page, 10 ) + 1 )
			}
			if ( $( this ).attr( "class" ) === "itmBrowserPrev" ) {
				loadImages( parseInt( page, 10 ) - 1 );
			}
			return false;
		} );

		/**
		 * Load images from EC-CUBE3 image save directory.
		 * @method loadImages
		 * @param pager number of page. If It has no argument, same to argument 0;
		 */
		function loadImages( page ) {
			var browserMain = $( "#itmBrowserMain" );
			/* Hack for IE9 */
			if ( !window.console ) {
				window.console = {
					log: function ( msg ) {
						// do nothing.
					}
				};
			}

			url = InfoTownMedia.Media.getAdminUrl()
				+ "/content/infotownmedia/media"
				+ "?csrf_token=" + InfoTownMedia.Media.getToken()
				+ "&page=" + page;

			$.ajax( {
				"method": "GET",
				"url": url,
				"cache": false,
				"dataType": "json"
			} ).done( function ( data ) {
				$( ".itmBrowserThumbnail" ).each( function () {
					$( this ).remove();
				} );
				$( data.images ).each( function ( i ) {
					var thumbnail = $( '<div class="itmBrowserThumbnail">' );
					thumbnail.css( {
						"float": "left",
						"margin-left": "1em",
						"width": "123px",
						"height": "123px",
						"overflow": "hidden"
					} );
					$( data.images[i] ).on( "drop", function () {
						alert( $( this ).attr( "src" ) );
					} );
					$( data.images[i] ).appendTo( thumbnail );
					$( thumbnail ).appendTo( browserMain );
				} );
				$( "#itmBrowserPager" ).html( data.pager );
			} ).fail( function ( data ) {
				$( "<p>エラーメッセージ: " + data.message + "<br>" + "エラーコード:" + data.code ).appendTo( browserMain );
			} );
			return false;
		}

		/**
		 * Load images from WordPress
		 * @method #itmloadWpMedia.click
		 * @public
		 */
		$( "#itmLoadWpMedia" ).on( "click", function () {
			var browserMain = $( "#itmBrowserMain" );
			var browserMessage = $( "#itmBrowserMessage" );
			/* Hack for IE9 */
			if ( !window.console ) {
				window.console = {
					log: function ( msg ) {
						// do nothing.
					}
				};
			}
			url = InfoTownMedia.Media.getAdminUrl()
				+ "/content/infotownmedia/media/wordpress?csrf_token="
				+ InfoTownMedia.Media.getToken();
			/* Load images to media browser */
			$.ajax( {
				"method": "GET",
				"url": url,
				"cache": false,
				"dataType": "json",
				"statusCode": {
					401: function ( data ) {
						browserMessage.addClass( "alert-warning" );
						browserMessage.html(
							"<p>" + data.responseJSON.message + "</p>"
						);
						return false;
					},
					403: function ( data ) {
						browserMessage.addClass( "alert-warning" );
						browserMessage.html(
							"<p>" + data.responseJSON.message + "</p>"
						);
						return false;
					},
					404: function ( data ) {
						browserMessage.addClass( "alert-warning" );
						browserMessage.html(
							"<p>" + data.responseJSON.message + "</p>"
						);
						return false;
					},
					500: function () {
						browserMessage.addClass( "alert-warning" );
						browserMessage.html(
							"<p>画像を読み込むことができませんでした。</p>"
						);
						return false;
					}
				}
			} ).done( function ( data ) {
				$( ".itmBrowserThumbnail" ).each( function () {
					$( this ).remove();
				} );
				$( data ).each( function ( i ) {
					var thumbnail = $( '<div class="itmBrowserThumbnail">' );
					thumbnail.css( {
						"float": "left",
						"margin-left": "1em",
						"width": "123px",
						"height": "123px",
						"overflow": "hidden"
					} );
					$( data[i] ).on( "drop", function () {
						alert( $( this ).attr( "src" ) );
					} );
					$( data[i] ).appendTo( thumbnail );
					$( thumbnail ).appendTo( browserMain );
				} )
			} ).fail( function ( data ) {
				$( "<p>画像を読み込むことができませんでした。</p>" ).appendTo( browserMessage );
			} );
			return false;
		} );

		/*
		 * Display image to preview
		 */
		$( document ).on( "click", ".itmBrowserThumbnail", function () {
			var img = $( this ).children( "img" );
			width = img.width();
			$( "#itmBrowserSelectedImgWidth" ).val( width );
			height = img.height();
			$( "#itmBrowserSelectedImgHeight" ).val( height );
			$( "#itmBrowserSelectedPreview" ).html( $( this ).html() );
		} );

		/*
		 * Insert image to free area.
		 */
		$( "#itmInsertMedia" ).on( "click", function () {
			var img = $( "#itmBrowserSelectedPreview img" ),
				src = $( "#itmBrowserSelectedPreview img" ).attr( "src" ),
				width = $( "#itmBrowserSelectedImgWidth" ),
				height = $( "#itmBrowserSelectedImgHeight" ),
				alt = $( "#itmBrowserSelectedImgAlt" ),
				format = $( "input[name=itmInsertFormat]:checked" ).val(),
				markup;
			if ( img.length > 0 ) {
				/*
				 * TODO:SAWAI CKEDITORのinsertHTMLはwidth,height属性をstyle属性のwidth, heightプロパティへ
				 * 自動変換して出力するためめwidth, height属性は設定していません。
				 */
				src = (format === 'url') ? InfoTownMedia.Media.getFullUrl( src ) : src;

				if ( alt.val() !== '' ) {
					markup = '<img src="' + src + '" alt="' + alt.val() + '">';
				} else {
					markup = '<img src="' + src + '">';
				}
				CKEDITOR.instances[InfoTownMedia.Media.getTextAreaId()].insertHtml( markup );
				width.val( '' );
				height.val( '' );
				alt.val( '' );
				$( "#itmBrowserSelectedPreview" ).empty();
				$( "#infoTownMedia" ).dialog( "close" );
			}
		} );
	}());
} );
jQuery( function ( $ ) {
	/**
	 * Handle InfoTownMedia media file upload in Product registration page.
	 *
	 * @class MediaUploader
	 * @author Hiroshi Sawai <info@info-town.jp>
	 */
	InfoTownMedia.MediaUploader = (function () {
		var imageType;
		var imageName;
		var inputImg;
		var itmUploaderView = $( "#itmUploaderView" );
		var itmUploaderMessage = $( "#itmUploaderMessage" );
		var inputCanvas = $( "<canvas>" ).appendTo( itmUploaderView ).get( 0 );
		var inputCxt;
		var isUpload = false;

		/* エラー表示 */
		function alert( text ) {
			itmUploaderMessage.addClass( 'alert-danger' );
			itmUploaderMessage.html( text );
		}

		/* 読込画像タイプの確認 */
		function checkFileType( text ) {
			// ファイルタイプの確認
			if ( text.match( /^image\/(png|jpeg|gif)$/ ) === null ) {
				alert( "<p>対応していないファイル形式です。" + "<br>" + "ファイルはPNG, JPEG, GIFに対応しています。</p>" );
				return false;
			}
			return true;
		}

		/*
		 * 画像表示処理
		 */
		/* 画像読込ハンドラ */
		function read( reader ) {
			return function () {
				/* imgへオブジェクト読み込み */
				inputImg = $( "<img>" ).get( 0 );
				inputImg.onload = function () {
					try {
						isUpload = true;
						inputCanvas.width = inputImg.width;
						inputCanvas.height = inputImg.height;
						inputCxt.clearRect( 0, 0, inputCanvas.width, inputCanvas.height );
						inputCxt.drawImage( inputImg, 0, 0, inputImg.width, inputImg.height );
					} catch ( e ) {
						alert( "<p>画像を開くことができませんでした。</p>" );
					}
				};
				inputImg.setAttribute( "src", reader.result );
			};
		}

		/* 
		 * 参照ボタン読込処理
		 */
		$( "#itmSelectMedia" ).change( function () {
			var file, reader;
			file = this.files[0];
			/* ファイルタイプの確認 */
			if ( checkFileType( file.type ) === false ) {
				return false;
			}
			imageType = file.type;
			imageName = file.name;
			/* 2d Context作成 */
			inputCxt = inputCanvas.getContext( "2d" );
			/* canvasへ描画 */
			reader = new FileReader();
			reader.onload = read( reader );
			reader.readAsDataURL( file );
		} );

		/*
		 * ドラッグアンドドロップ読込処理
		 */
		itmUploaderView.get( 0 ).ondragover = function () {
			return false;
		};

		itmUploaderView.get( 0 ).ondrop = function ( event ) {
			/*
			 * itmUploaderView.on("drop", function() {});では正常に動作に動作しませんでした(2016.01.18)。
			 */
			var file, reader;
			isUpload = true;
			if ( event.dataTransfer.files.length === 0 ) {
				alert( "<p>画像を開けませんでした。</p>" );
				return false;
			}
			/* ドロップされたファイル情報 */
			file = event.dataTransfer.files[0];
			/* ファイルタイプの確認 */
			if ( checkFileType( file.type ) === false ) {
				return false;
			}
			/* 2d Context作成 */
			inputCxt = inputCanvas.getContext( "2d" );
			/* canvasへ描画 */
			reader = new FileReader();
			reader.onload = read( reader );
			reader.readAsDataURL( file );

			return false;
		};

		/*
		 * EC-CUBE3へ保存
		 */
		$( "#itmUploadMedia" ).on( "click", function () {
			
			if ( isUpload == false ) {
				itmUploaderMessage.fadeIn(400);
				itmUploaderMessage.html( "<p>画像がアップロードされていません。</p>" );
				itmUploaderMessage.fadeOut(5000);
				return false;
			}
			var dataBase64, data = {};
			
			/* Hack for IE9 */
			if ( !window.console ) {
				window.console = {
					log: function ( msg ) {
						/* do nothing. */
					}
				};
			}

			/* Get base64 encoded data. */
			if (imageType === 'image/jpeg') {
				dataBase64 = inputCanvas.toDataURL('image/jpeg');
			} else {
				dataBase64 = inputCanvas.toDataURL();
			}
			dataBase64 = dataBase64.replace( /^data:image\/(png|jpg|jpeg);base64,/, "" );
			if ( "" === dataBase64 ) {
				return false;
			}
			/* Create Post parameter */
			data.imageData = dataBase64;
			data.imageType = imageType.split('/')[1];
			data.imageName = imageName;
			data.csrf_token = InfoTownMedia.Media.getToken();
			imageType = '';
			imageName = '';
			/* Post image to WordPress media. */
			$.ajax( {
				"method": "POST",
				"url": InfoTownMedia.Media.getAdminUrl() + "/content/infotownmedia/media",
				"cache": false,
				"data": data,
				"dataType": "json"
			} ).done( function ( data ) {
				isUpload = false;
				itmUploaderMessage.addClass( 'alert-success' );
				itmUploaderMessage.fadeIn(400);
				itmUploaderMessage.html( data.message );
				itmUploaderMessage.fadeOut(5000);
				return true;
			} ).fail( function ( data ) {
				itmUploaderMessage.addClass( 'alert-danger' );
				itmUploaderMessage.fadeIn(400);
				itmUploaderMessage.html( data.message );
				itmUploaderMessage.fadeOut(5000);
			} );
			return false;
		} );

		/**
		 * WordPressへ保存
		 */
		$( "#itmUploadMediaToWp" ).on( "click", function () {
			if ( isUpload == false ) {
				itmUploaderMessage.html( "<p>画像がアップロードされていません。</p>" );
				return false;
			}
			var dataBase64, data = {};

			/* Hack for IE9 */
			if ( !window.console ) {
				window.console = {
					log: function ( msg ) {
						/* do nothing. */
					}
				};
			}
			/* Get base64 encoded data. */
			if (imageType === 'image/jpeg') {
				dataBase64 = inputCanvas.toDataURL('image/jpeg');
			} else {
				dataBase64 = inputCanvas.toDataURL();
			}
			dataBase64 = dataBase64.replace( /^data:image\/(png|jpg|jpeg);base64,/, "" );
			if ( "" === dataBase64 ) {
				return false;
			}
			/* Create Post parameter */
			data.imageData = dataBase64;
			data.imageType = imageType.split('/')[1];
			data.imageName = imageName;
			data.csrf_token = InfoTownMedia.Media.getToken();
			imageType = '';
			imageName = '';
			/* Post image to WordPress media. */
			$.ajax( {
				"method": "POST",
				"url": InfoTownMedia.Media.getAdminUrl() + "/content/infotownmedia/media/wordpress",
				"cache": false,
				"data": data,
				"dataType": "json",
				"statusCode": {
					201: function (data) {
						isUpload = false;
						itmUploaderMessage.addClass( 'alert-success' );
						itmUploaderMessage.html(data.message);
						return true;
					}
				}
			} ).done( function (data) {
				isUpload = false;
				itmUploaderMessage.addClass( 'alert-success' );
				itmUploaderMessage.html( data.message );
				return true;
			} ).fail( function ( data ) {
				if ( data.code !== '201' ) {
					itmUploaderMessage.addClass( 'alert-danger' );
					itmUploaderMessage.html( data.message );
				}
			} );
			return false;
		} )

	}());
});
