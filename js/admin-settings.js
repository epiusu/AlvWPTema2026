/**
 * WP Alveren — Admin Settings JS
 * Görünüm > Tema Ayarları
 *
 * @package WPAlveren
 */
( function ( $ ) {
	'use strict';

	/* ── Tab geçişi ── */
	$( '.alv-tab-btn' ).on( 'click', function () {
		var tab = $( this ).data( 'tab' );
		$( '.alv-tab-btn' ).removeClass( 'is-active' ).attr( 'aria-selected', 'false' );
		$( this ).addClass( 'is-active' ).attr( 'aria-selected', 'true' );
		$( '.alv-panel' ).removeClass( 'is-active' );
		$( '.alv-panel[data-panel="' + tab + '"]' ).addClass( 'is-active' );
	} );

	/* ── Renk seçici ── */
	$( '.alv-cp' ).wpColorPicker( {
		change: function () {
			// değer otomatik güncellenir
		},
	} );

	/* ── Medya yükleme ── */
	var mediaFrame;
	$( document ).on( 'click', '.alv-pick-img', function ( e ) {
		e.preventDefault();
		var btn      = $( this );
		var targetId = btn.data( 'target' );
		var prevId   = btn.data( 'preview' );

		if ( mediaFrame ) { mediaFrame.open(); return; }

		mediaFrame = wp.media( {
			title    : alvAdm.choose,
			button   : { text: alvAdm.use },
			multiple : false,
			library  : { type: 'image' },
		} );

		mediaFrame.on( 'select', function () {
			var att = mediaFrame.state().get( 'selection' ).first().toJSON();
			$( '#' + targetId ).val( att.url );
			$( '#' + prevId ).html( '<img src="' + att.url + '" alt="">' );
			btn.siblings( '.alv-del-img' ).show();
		} );

		mediaFrame.open();
		mediaFrame = null; // yeni seçim için sıfırla
	} );

	$( document ).on( 'click', '.alv-del-img', function () {
		var targetId = $( this ).data( 'target' );
		var prevId   = $( this ).data( 'preview' );
		$( '#' + targetId ).val( '' );
		$( '#' + prevId ).empty();
		$( this ).hide();
	} );

	/* ══════════════════════════════════════════
	   Sosyal Medya Repeater
	══════════════════════════════════════════ */
	var repIdx = $( '.alv-rep-row' ).length;

	function buildRow( data ) {
		data = data || { icon: '', label: '', url: '' };
		return $( '<div class="alv-rep-row" data-idx="' + repIdx + '">' +
			'<span class="alv-rep-handle dashicons dashicons-menu" title="Sürükle"></span>' +
			'<span class="alv-rep-icon"><i class="' + ( data.icon || 'fab fa-globe' ) + '" style="font-size:20px;"></i></span>' +
			'<div class="alv-rep-fields">' +
				'<input type="text"  class="alv-soc-icon  code"   placeholder="fab fa-twitter"  value="' + escAttr( data.icon  ) + '" data-f="icon">' +
				'<input type="text"  class="alv-soc-label"         placeholder="Etiket (Twitter)" value="' + escAttr( data.label ) + '" data-f="label">' +
				'<input type="url"   class="alv-soc-url   widefat" placeholder="https://..."      value="' + escAttr( data.url   ) + '" data-f="url">' +
			'</div>' +
			'<button type="button" class="button alv-rep-del" title="Kaldır"><span class="dashicons dashicons-trash"></span></button>' +
		'</div>' );
	}

	function escAttr( str ) {
		return String( str || '' )
			.replace( /&/g, '&amp;' )
			.replace( /"/g, '&quot;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' );
	}

	function collectSocial() {
		var links = [];
		$( '.alv-rep-row' ).each( function () {
			links.push( {
				icon  : $( this ).find( '[data-f="icon"]'  ).val(),
				label : $( this ).find( '[data-f="label"]' ).val(),
				url   : $( this ).find( '[data-f="url"]'   ).val(),
			} );
		} );
		$( '#alvSocialJson' ).val( JSON.stringify( links ) );
	}

	$( '#alvAddSocial' ).on( 'click', function () {
		var row = buildRow();
		$( '#alvSocialRepeater' ).append( row );
		repIdx++;
	} );

	$( document ).on( 'click', '.alv-rep-del', function () {
		$( this ).closest( '.alv-rep-row' ).remove();
		collectSocial();
	} );

	/* Canlı ikon önizleme */
	$( document ).on( 'input', '.alv-soc-icon', function () {
		var cls = $( this ).val().trim() || 'fab fa-globe';
		$( this ).closest( '.alv-rep-row' ).find( '.alv-rep-icon i' ).attr( 'class', cls );
	} );

	/* Sürükleme (basit drag) */
	var dragging = null;
	$( document ).on( 'mousedown', '.alv-rep-handle', function ( e ) {
		dragging = $( this ).closest( '.alv-rep-row' );
	} );
	$( document ).on( 'mouseup', function () { dragging = null; } );
	$( document ).on( 'mouseover', '.alv-rep-row', function () {
		if ( dragging && dragging[ 0 ] !== $( this )[ 0 ] ) {
			var rep = $( '#alvSocialRepeater' );
			var rows = rep.children( '.alv-rep-row' );
			var hovIdx = rows.index( $( this ) );
			var dragIdx = rows.index( dragging );
			if ( hovIdx > dragIdx ) {
				$( this ).after( dragging );
			} else {
				$( this ).before( dragging );
			}
		}
	} );

	/* ── Hizalama butonları ── */
	/* ── Kategori ikon canlı önizleme ── */
	$( document ).on( 'input', '.alv-icon-inp', function () {
		var val     = $( this ).val().trim();
		var prevId  = $( this ).data( 'preview' );
		var $prev   = $( '#' + prevId );
		if ( ! $prev.length ) return;
		// fa- prefix yoksa ekle
		var cls = val;
		if ( val && val.indexOf( 'fa-' ) === 0 ) cls = 'fas ' + val;
		$prev.html( '<i class="' + cls + '"></i>' );
	} );

		$( document ).on( 'click', '.alv-align-btn', function () {
		var $wrap  = $( this ).closest( '.alv-align-btns' );
		var target = $wrap.data( 'target' );
		$wrap.find( '.alv-align-btn' ).removeClass( 'is-active' );
		$( this ).addClass( 'is-active' );
		$( '#' + target ).val( $( this ).data( 'val' ) );
	} );

	/* ── Range slider ↔ number input senkronizasyonu ── */
	$( document ).on( 'input change', '.alv-range', function () {
		var linked = $( this ).data( 'linked' );
		$( '#' + linked ).val( $( this ).val() );
	} );
	$( document ).on( 'input change', 'input[type="number"]', function () {
		var id = $( this ).attr( 'id' );
		$( '#' + id + '_range' ).val( $( this ).val() );
	} );

	/* ── Kategori ikon önizleme ── */
	$( document ).on( 'input', '.alv-icon-inp', function () {
		var val = $( this ).val().trim();
		var cls = val ? ( 'fas ' + val ) : 'fas fa-star';
		$( this ).siblings( '.alv-icon-prev' ).find( 'i' ).attr( 'class', cls );
	} );

	/* ══════════════════════════════════════════
	   Kaydet (AJAX)
	══════════════════════════════════════════ */
	function doSave() {
		collectSocial();

		var btns = $( '.alv-save-btn' );
		btns.prop( 'disabled', true )
		    .find( '.alv-save-label' ).text( alvAdm.saving );

		/* Form verisi topla */
		var data = { action: 'alv_save', nonce: alvAdm.nonce };

		/* Normal form inputları */
		$( '#alvForm' ).find( 'input:not(.alv-cp), textarea, select' ).each( function () {
			var nm = $( this ).attr( 'name' );
			if ( ! nm ) return;
			if ( $( this ).attr( 'type' ) === 'checkbox' ) {
				if ( $( this ).is( ':checked' ) ) data[ nm ] = '1';
			} else {
				data[ nm ] = $( this ).val();
			}
		} );

		/* Renk seçiciler (wp-color-picker hidden input) */
		$( '.wp-picker-holder .wp-color-picker, .wp-picker-container .wp-color-picker' ).each( function () {
			var nm = $( this ).attr( 'name' );
			if ( nm ) data[ nm ] = $( this ).val();
		} );
		/* Alternatif: orijinal input */
		$( '.alv-cp' ).each( function () {
			var nm = $( this ).attr( 'name' );
			if ( nm ) data[ nm ] = $( this ).val();
		} );

		$.post( alvAdm.ajaxUrl, data, function ( res ) {
			var notice = $( '#alvNotice' );
			notice.stop( true ).show();
			if ( res && res.success ) {
				notice.removeClass( 'err' ).addClass( 'ok' ).text( alvAdm.saved );
			} else {
				notice.removeClass( 'ok' ).addClass( 'err' ).text( alvAdm.error );
			}
			setTimeout( function () { notice.fadeOut( 400 ); }, 3000 );
		} ).fail( function () {
			$( '#alvNotice' ).removeClass( 'ok' ).addClass( 'err' ).text( alvAdm.error ).show();
		} ).always( function () {
			btns.prop( 'disabled', false )
			    .find( '.alv-save-label' ).text( 'Kaydet' );
		} );
	}

	/* ══════════════════════════════════════════
	   ÖNE ÇIKAN HABERLER REPEATER
	   ══════════════════════════════════════════ */

	var MAX_SPOTS = 10;

	/* JSON güncelle */
	function updateSpotsJson() {
		var spots = [];
		$( '.alv-spot-row' ).each( function () {
			var url   = $( this ).find( '.alv-spot-row__url' ).val().trim();
			var title = $( this ).find( '.alv-spot-row__title' ).val().trim();
			var img   = $( this ).find( '.alv-spot-row__img-val' ).val().trim();
			if ( url ) spots.push( { url: url, title: title, img: img } );
		} );
		$( '#alvFeaturedSpotsJson' ).val( JSON.stringify( spots ) );
		$( '#alvSpotCount' ).text( spots.length + ' / 10' );
		$( '#alvAddSpot' ).prop( 'disabled', spots.length >= MAX_SPOTS );
	}

	/* Sıra numaralarını güncelle */
	function reindexSpots() {
		$( '.alv-spot-row' ).each( function ( i ) {
			$( this ).attr( 'data-idx', i ).find( '.alv-spot-row__num' ).text( i + 1 );
		} );
	}

	/* Haber ekle */
	$( '#alvAddSpot' ).on( 'click', function () {
		var count = $( '.alv-spot-row' ).length;
		if ( count >= MAX_SPOTS ) return;
		var row = $( '<div class="alv-spot-row" data-idx="' + count + '">' +
			'<div class="alv-spot-row__num">' + ( count + 1 ) + '</div>' +
			'<div class="alv-spot-row__fields">' +
				'<input type="url" class="alv-spot-row__url widefat" placeholder="https://siteniz.com/haber/" data-f="url">' +
				'<input type="text" class="alv-spot-row__title widefat" placeholder="Başlık (boş=otomatik)" data-f="title">' +
				'<input type="hidden" class="alv-spot-row__img-val" data-f="img">' +
			'</div>' +
			'<div class="alv-spot-row__actions">' +
				'<button type="button" class="button alv-spot-fetch-row" title="Önizlemeyi çek"><span class="dashicons dashicons-update"></span></button>' +
				'<button type="button" class="button alv-spot-del-row" title="Sil"><span class="dashicons dashicons-trash" style="color:#c0392b"></span></button>' +
			'</div>' +
		'</div>' );
		$( '#alvFeaturedSpotsRepeater' ).append( row );
		updateSpotsJson();
		/* URL alanına odaklan */
		row.find( '.alv-spot-row__url' ).focus();
	} );

	/* Haber sil */
	$( document ).on( 'click', '.alv-spot-del-row', function () {
		$( this ).closest( '.alv-spot-row' ).remove();
		reindexSpots();
		updateSpotsJson();
	} );

	/* URL değişince JSON güncelle */
	$( document ).on( 'input change', '.alv-spot-row__url, .alv-spot-row__title', function () {
		updateSpotsJson();
	} );

	/* Önizleme Çek — URL'den başlık+görsel otomatik çek */
	$( document ).on( 'click', '.alv-spot-fetch-row', function () {
		var $btn = $( this );
		var $row = $btn.closest( '.alv-spot-row' );
		var url  = $row.find( '.alv-spot-row__url' ).val().trim();
		if ( ! url ) { alert( 'Önce URL girin.' ); return; }

		$btn.prop( 'disabled', true ).find( '.dashicons' ).addClass( 'dashicons-update-spin' );

		$.ajax( {
			url:  alvAdm.ajaxUrl,
			type: 'POST',
			data: { action: 'alv_fetch_spot', nonce: alvAdm.nonce, spot_url: url },
			success: function ( res ) {
				if ( res.success && res.data ) {
					var d = res.data;
					/* Başlık alanı boşsa otomatik doldur */
					var $titleField = $row.find( '.alv-spot-row__title' );
					if ( ! $titleField.val() && d.title ) $titleField.val( d.title );
					/* Görsel URL'yi gizli alana yaz */
					$row.find( '.alv-spot-row__img-val' ).val( d.thumb || '' );
					/* Önizleme kutusunu göster */
					var $prev = $row.find( '.alv-spot-row__preview' );
					if ( $prev.length === 0 ) {
						$prev = $( '<div class="alv-spot-row__preview"></div>' );
						$row.append( $prev );
					}
					var imgHtml = d.thumb ? '<img src="' + d.thumb + '" alt="" class="alv-spot-row__img">' : '';
					$prev.html( imgHtml + '<span class="alv-spot-row__ptitle">' + $( '<div>' ).text( $titleField.val() || d.title ).html() + '</span>' ).show();
					updateSpotsJson();
				} else {
					alert( res.data || 'Önizleme alınamadı.' );
				}
			},
			error: function () { alert( 'Bağlantı hatası.' ); },
			complete: function () {
				$btn.prop( 'disabled', false ).find( '.dashicons' ).removeClass( 'dashicons-update-spin' );
			},
		} );
	} );

	/* İlk yükleme: JSON güncelle */
	updateSpotsJson();

	$( '#alvSaveTop, #alvSaveBottom' ).on( 'click', doSave );

	/* Ctrl+S kısayol */
	$( document ).on( 'keydown', function ( e ) {
		if ( ( e.ctrlKey || e.metaKey ) && e.key === 's' ) {
			e.preventDefault();
			doSave();
		}
	} );

} )( jQuery );
