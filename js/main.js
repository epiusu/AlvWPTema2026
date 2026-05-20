/**
 * WP Alveren — main.js  v1.28
 * Vanilla JS — jQuery bağımlılığı yok, Bootstrap JS dropdown/collapse kullanılmaz.
 *
 * İçerik:
 *  1. Drawer (hamburger)
 *  2. Drawer accordion (alt menü)
 *  3. Canlı Arama — initLiveSearch() fabrika fonksiyonu
 *     · Header arama çubuğu
 *     · Drawer arama
 *     · Arama sayfası
 *  4. Drawer saati
 *  5. Beğeni butonu
 *  6. Smooth scroll
 *  7. Back-to-top
 *  8. Sticky header (hysteresis)
 *
 * @package WPAlveren
 */
( function () {
	'use strict';

	/* ============================================================
	   1. DRAWER — Hamburger Menü
	   ============================================================ */
	const drawer  = document.getElementById( 'alvDrawer' );
	const overlay = document.getElementById( 'alvOverlay' );
	const btnOpen = document.getElementById( 'alvDrawerOpen' );
	const btnClose= document.getElementById( 'alvDrawerClose' );

	function openDrawer() {
		if ( ! drawer ) return;
		drawer.classList.add( 'is-open' );
		if ( overlay ) overlay.classList.add( 'is-visible' );
		document.body.style.overflow = 'hidden';
		if ( btnClose ) btnClose.focus();
	}

	function closeDrawer() {
		if ( ! drawer ) return;
		drawer.classList.remove( 'is-open' );
		if ( overlay ) overlay.classList.remove( 'is-visible' );
		document.body.style.overflow = '';
		if ( btnOpen ) btnOpen.focus();
	}

	if ( btnOpen )  btnOpen.addEventListener( 'click', openDrawer );
	if ( btnClose ) btnClose.addEventListener( 'click', closeDrawer );
	if ( overlay )  overlay.addEventListener( 'click', closeDrawer );

	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && drawer && drawer.classList.contains( 'is-open' ) ) {
			closeDrawer();
		}
	} );

	/* ============================================================
	   2. DRAWER ACCORDION — Alt menü aç/kapat (Bootstrap olmadan)
	   Walker: <button data-alv-target="alvDrSub1">
	           <ul id="alvDrSub1" class="alv-drawer-nav__sub">
	   ============================================================ */
	document.addEventListener( 'click', function ( e ) {
		const btn = e.target.closest( '.alv-drawer-nav__toggle' );
		if ( ! btn ) return;

		const targetId = btn.getAttribute( 'data-alv-target' );
		if ( ! targetId ) return;
		const sub = document.getElementById( targetId );
		if ( ! sub ) return;

		const isOpen = sub.classList.toggle( 'alv-open' );
		btn.setAttribute( 'aria-expanded', String( isOpen ) );
	} );

	/* ============================================================
	   3. CANLI ARAMA — fabrika fonksiyonu
	   Her arama alanı için bağımsız instance.
	   Kullanım:
	     initLiveSearch( inputEl, resultsEl, options )
	   options.wrapSel: dışarı tıklama için kapsayıcı CSS selector
	   ============================================================ */
	function initLiveSearch( inputEl, resultsEl, options ) {
		if ( ! inputEl || ! resultsEl ) return;

		options = options || {};
		var wrapSel = options.wrapSel || null;

		var timer     = null;
		var lastQuery = '';

		/* Arama terimini vurgula */
		function highlight( text, q ) {
			if ( ! q ) return text;
			var escaped = q.replace( /[.*+?^${}()|[\]\\]/g, '\\$&' );
			return text.replace( new RegExp( '(' + escaped + ')', 'gi' ), '<mark>$1</mark>' );
		}

		/* Sonuçları render et */
		function showResults( items, q ) {
			var html = '';

			if ( ! items.length ) {
				html = '<div class="alv-live-empty">'
				     + ( ( window.alvData && window.alvData.strings && window.alvData.strings.noResults )
				         ? window.alvData.strings.noResults : 'Sonuç bulunamadı.' )
				     + '</div>';
			} else {
				html += '<ul class="alv-live-list">';
				items.forEach( function ( item ) {
					html += '<li><a href="' + item.url + '" class="alv-live-item">';
					if ( item.thumb ) {
						html += '<img src="' + item.thumb + '" alt="" class="alv-live-thumb">';
					}
					if ( item.cat ) {
						html += '<span class="alv-live-cat">' + item.cat + '</span>';
					}
					html += '<span class="alv-live-title">' + highlight( item.title, q ) + '</span>';
					html += '<span class="alv-live-date">' + item.date + '</span>';
					html += '</a></li>';
				} );
				html += '</ul>';

				var searchUrl = ( ( window.alvData && window.alvData.searchUrl ) || '/?s=' )
				              + encodeURIComponent( q );
				var allLabel = ( window.alvData && window.alvData.strings && window.alvData.strings.allResults )
				             ? window.alvData.strings.allResults : 'Tüm sonuçları gör';
				html += '<a href="' + searchUrl + '" class="alv-live-all">'
				      + allLabel + ' <i class="fas fa-arrow-right"></i></a>';
			}

			resultsEl.innerHTML = html;
			resultsEl.classList.add( 'is-visible' );
		}

		function hideResults() {
			resultsEl.classList.remove( 'is-visible' );
			setTimeout( function () {
				if ( ! resultsEl.classList.contains( 'is-visible' ) ) {
					resultsEl.innerHTML = '';
				}
			}, 200 );
		}

		/* AJAX isteği */
		function doSearch( q ) {
			var ajaxUrl = ( window.alvData && window.alvData.ajaxUrl )
			            ? window.alvData.ajaxUrl : '/wp-admin/admin-ajax.php';
			var nonce   = ( window.alvData && window.alvData.nonce ) ? window.alvData.nonce : '';

			var params = new URLSearchParams( {
				action : 'alv_live_search',
				nonce  : nonce,
				q      : q,
			} );

			fetch( ajaxUrl + '?' + params.toString(), { credentials: 'same-origin' } )
				.then( function ( r ) { return r.json(); } )
				.then( function ( json ) {
					if ( json.success ) showResults( json.data, q );
				} )
				.catch( function ( err ) {
					console.warn( 'Alveren live search:', err );
				} );
		}

		/* Input olayı */
		inputEl.addEventListener( 'input', function () {
			var q = inputEl.value.trim();
			clearTimeout( timer );
			if ( q.length < 2 ) { hideResults(); lastQuery = ''; return; }
			if ( q === lastQuery ) return;
			lastQuery = q;
			timer = setTimeout( function () { doSearch( q ); }, 280 );
		} );

		/* Klavye navigasyonu */
		inputEl.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' ) { hideResults(); inputEl.blur(); return; }
			if ( e.key === 'ArrowDown' ) {
				e.preventDefault();
				var first = resultsEl.querySelector( '.alv-live-item, .alv-live-all' );
				if ( first ) first.focus();
			}
		} );

		resultsEl.addEventListener( 'keydown', function ( e ) {
			var links = Array.from( resultsEl.querySelectorAll( 'a' ) );
			var idx   = links.indexOf( document.activeElement );
			if ( e.key === 'ArrowDown' && idx < links.length - 1 ) {
				e.preventDefault(); links[ idx + 1 ].focus();
			}
			if ( e.key === 'ArrowUp' ) {
				e.preventDefault();
				if ( idx > 0 ) links[ idx - 1 ].focus(); else inputEl.focus();
			}
			if ( e.key === 'Escape' ) { hideResults(); inputEl.focus(); }
		} );

		/* Dışarı tıklama */
		document.addEventListener( 'click', function ( e ) {
			var wrap = wrapSel ? inputEl.closest( wrapSel ) : null;
			var inWrap    = wrap ? wrap.contains( e.target ) : inputEl.contains( e.target );
			var inResults = resultsEl.contains( e.target );
			if ( ! inWrap && ! inResults ) hideResults();
		} );
	}

	/* ── Canlı arama bağlamaları ── */

	/* Header arama çubuğu */
	initLiveSearch(
		document.getElementById( 'alvSearchBarInput' ),
		document.getElementById( 'alvSearchBarLive' ),
		{ wrapSel: '.alv-header__search' }
	);

	/* Drawer arama */
	initLiveSearch(
		document.getElementById( 'alvDrawerSearchInput' ),
		document.getElementById( 'alvDrawerLiveResults' ),
		{ wrapSel: '.alv-drawer__search' }
	);

	/* Arama sonuç sayfası */
	initLiveSearch(
		document.getElementById( 'alvSearchPageInput' ),
		document.getElementById( 'alvSearchPageResults' ),
		{ wrapSel: '.alv-search-wrap' }
	);

	/* ============================================================
	   4. DRAWER SAATI
	   ============================================================ */
	var clockEl = document.getElementById( 'alvDrawerClock' );
	if ( clockEl ) {
		function updateClock() {
			var now = new Date();
			var pad = function ( n ) { return String( n ).padStart( 2, '0' ); };
			clockEl.textContent = pad( now.getHours() ) + ':' + pad( now.getMinutes() ) + ':' + pad( now.getSeconds() );
		}
		updateClock();
		setInterval( updateClock, 1000 );
	}

	/* ============================================================
	   5. BEĞENİ BUTONU
	   ============================================================ */
	var likeBtn = document.querySelector( '.alv-like-btn' );
	if ( likeBtn ) {
		likeBtn.addEventListener( 'click', function () {
			if ( likeBtn.disabled ) return;
			likeBtn.disabled = true;

			var body = new FormData();
			body.append( 'action',  'alv_like' );
			body.append( 'post_id', likeBtn.dataset.post );
			body.append( 'nonce',   likeBtn.dataset.nonce );

			var ajaxUrl = ( window.alvData && window.alvData.ajaxUrl )
			            ? window.alvData.ajaxUrl : '/wp-admin/admin-ajax.php';

			fetch( ajaxUrl, { method: 'POST', credentials: 'same-origin', body: body } )
				.then( function ( r ) { return r.json(); } )
				.then( function ( json ) {
					if ( json.success ) {
						likeBtn.classList.add( 'is-liked' );
						var countEl = likeBtn.querySelector( '.alv-like-btn__count' );
						if ( countEl ) countEl.textContent = '(' + json.data.count + ')';
						var labelEl = likeBtn.querySelector( '.alv-like-btn__label' );
						if ( labelEl ) labelEl.textContent = 'Teşekkürler!';
					} else {
						likeBtn.disabled = false;
					}
				} )
				.catch( function () { likeBtn.disabled = false; } );
		} );
	}

	/* ============================================================
	   6. SMOOTH ANCHOR SCROLL
	   ============================================================ */
	document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( anchor ) {
		anchor.addEventListener( 'click', function ( e ) {
			var id = anchor.getAttribute( 'href' );
			if ( id === '#' ) return;
			var target = document.querySelector( id );
			if ( target ) {
				e.preventDefault();
				var header = document.getElementById( 'alvHeader' );
				var navH   = header ? header.offsetHeight : 70;
				var top    = target.getBoundingClientRect().top + window.scrollY - navH - 12;
				window.scrollTo( { top: top, behavior: 'smooth' } );
			}
		} );
	} );

	/* ============================================================
	   7. BACK-TO-TOP
	   ============================================================ */
	var btt = document.getElementById( 'alvBackToTop' );
	if ( btt ) {
		window.addEventListener( 'scroll', function () {
			btt.classList.toggle( 'is-visible', window.scrollY > 400 );
		}, { passive: true } );
		btt.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

} )();

/* ============================================================
   8. STICKY HEADER — Hysteresis (aşağı=80px, yukarı=20px)
      Titreme yok, sadece box-shadow toggle.
   ============================================================ */
( function () {
	'use strict';

	var header = document.getElementById( 'alvHeader' );
	if ( ! header ) return;

	var DOWN = 80, UP = 20;
	var ticking = false, last = null;

	function applyState( y ) {
		var next = ( last === 'scrolled' )
			? ( y > UP   ? 'scrolled' : 'top' )
			: ( y > DOWN ? 'scrolled' : 'top' );
		if ( next === last ) return;
		last = next;
		header.classList.toggle( 'is-scrolled', next === 'scrolled' );
	}

	window.addEventListener( 'scroll', function () {
		if ( ! ticking ) {
			requestAnimationFrame( function () {
				applyState( window.scrollY );
				ticking = false;
			} );
			ticking = true;
		}
	}, { passive: true } );

	applyState( window.scrollY );
} )();
