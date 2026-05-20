<?php
/**
 * WP Alveren — functions.php
 *
 * @package WPAlveren
 * @version 1.22
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'ALV_VERSION', '1.23' );
define( 'ALV_DIR',     get_template_directory() );
define( 'ALV_URI',     get_template_directory_uri() );
define( 'ALV_TEXT',    'alveren' );

/* ============================================================
   1. TEMA KURULUMU
   ============================================================ */
function alveren_setup() {
	load_theme_textdomain( ALV_TEXT, ALV_DIR . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'alv-hero',  1200, 480, true );
	add_image_size( 'alv-card',   600, 340, true );
	add_image_size( 'alv-thumb',  120, 120, true );

	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
		'gallery', 'caption', 'style', 'script',
	) );

	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 260,
		'flex-height' => true,
		'flex-width'  => true,
	) );

	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );
	add_editor_style( 'css/editor-style.css' );

	register_nav_menus( array(
		'primary' => __( 'Üst Menü (Navbar)',   ALV_TEXT ),
		'footer'  => __( 'Alt Menü (Footer)',    ALV_TEXT ),
		'mobile'  => __( 'Mobil Drawer Menüsü', ALV_TEXT ),
	) );
}
add_action( 'after_setup_theme', 'alveren_setup' );

/* ============================================================
   2. CONTENT WIDTH
   ============================================================ */
function alveren_content_width() {
	if ( ! isset( $GLOBALS['content_width'] ) ) {
		$GLOBALS['content_width'] = 1200;
	}
}
add_action( 'after_setup_theme', 'alveren_content_width', 0 );

/* ============================================================
   3. WİDGET ALANLARI
   ============================================================ */
function alveren_widgets_init() {
	// Footer widget alanları kaldırıldı — footer tamamen tema tarafından yönetiliyor
}
add_action( 'widgets_init', 'alveren_widgets_init' );

/* ============================================================
   4. SCRIPT & STİL ENQUEUE
   ============================================================ */
function alveren_enqueue_assets() {
	$v = ALV_VERSION;

	wp_enqueue_style(
		'alv-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;600;700;800&family=Ubuntu:wght@300;400;500;700&display=swap',
		array(), null
	);

	wp_enqueue_style(
		'alv-fontawesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
		array(), '6.5.0'
	);

	wp_enqueue_style(
		'alv-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
		array(), '5.3.2'
	);

	wp_enqueue_style(
		'alv-style',
		get_stylesheet_uri(),
		array( 'alv-bootstrap', 'alv-fontawesome', 'alv-google-fonts' ),
		$v
	);

	wp_enqueue_script(
		'alv-bootstrap',
		'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js',
		array(), '5.3.2', true
	);

	wp_enqueue_script(
		'alv-main',
		ALV_URI . '/js/main.js',
		array( 'alv-bootstrap' ), $v, true
	);

	wp_localize_script( 'alv-main', 'alvData', array(
		'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
		'homeUrl'   => esc_url( home_url( '/' ) ),
		'searchUrl' => esc_url( home_url( '/?s=' ) ),
		'nonce'     => wp_create_nonce( 'alv_live_search' ),
		'strings'   => array(
			'searching'  => __( 'Aranıyor…',        ALV_TEXT ),
			'noResults'  => __( 'Sonuç bulunamadı.', ALV_TEXT ),
			'allResults' => __( 'Tüm sonuçları gör', ALV_TEXT ),
		),
	) );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'alveren_enqueue_assets' );

/* ============================================================
   5. LIVE SEARCH AJAX
   ============================================================ */
function alveren_live_search() {
	// Nonce kontrolü — hem GET hem POST destekle
	if ( ! check_ajax_referer( 'alv_live_search', 'nonce', false ) ) {
		wp_send_json_error( 'Geçersiz istek.', 403 );
	}

	// FIX: $_REQUEST ile hem GET hem POST parametresini yakala
	$query = sanitize_text_field( wp_unslash( isset( $_REQUEST['q'] ) ? $_REQUEST['q'] : '' ) );

	if ( mb_strlen( $query ) < 2 ) {
		wp_send_json_success( array() );
	}

	$results = new WP_Query( array(
		's'              => $query,
		'posts_per_page' => 8,
		'post_status'    => 'publish',
		'no_found_rows'  => true,
	) );

	$data = array();

	if ( $results->have_posts() ) {
		while ( $results->have_posts() ) {
			$results->the_post();
			$cats   = get_the_category();
			$data[] = array(
				'title' => get_the_title(),
				'url'   => get_permalink(),
				'cat'   => $cats ? esc_html( $cats[0]->name ) : '',
				'date'  => get_the_date( 'd.m.Y' ),
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( $data );
}
add_action( 'wp_ajax_alv_live_search',        'alveren_live_search' );
add_action( 'wp_ajax_nopriv_alv_live_search', 'alveren_live_search' );

/* ============================================================
   6. YARDIMCI FONKSİYONLAR
   ============================================================ */

/**
 * Kategori + alt kategoriler toplam makale sayısı.
 */
function alveren_cat_total( $cat_id ) {
	$cat = get_category( $cat_id );
	if ( ! $cat || is_wp_error( $cat ) ) return 0;
	$count = (int) $cat->count;
	$children = get_categories( array(
		'taxonomy'   => 'category',
		'parent'     => $cat_id,
		'hide_empty' => false,
		'number'     => 0,
	) );
	foreach ( $children as $child ) {
		$count += alveren_cat_total( (int) $child->term_id );
	}
	return $count;
}

/**
 * Breadcrumb HTML.
 * FIX: get_the_title() çıktıları esc_html() ile kaçırıldı (XSS koruması).
 */
function alveren_breadcrumb() {
	echo '<nav class="alv-breadcrumb" aria-label="' . esc_attr__( 'Konum', ALV_TEXT ) . '">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '"><i class="fas fa-home"></i></a>';

	if ( is_single() ) {
		$cats = get_the_category();
		if ( $cats ) {
			$cat       = $cats[0];
			$ancestors = array_reverse( get_ancestors( $cat->term_id, 'category' ) );
			foreach ( $ancestors as $anc_id ) {
				$anc = get_category( $anc_id );
				echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
				echo '<a href="' . esc_url( get_category_link( $anc_id ) ) . '">' . esc_html( $anc->name ) . '</a>';
			}
			echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
			echo '<a href="' . esc_url( get_category_link( $cat ) ) . '">' . esc_html( $cat->name ) . '</a>';
		}
		echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
		// FIX: esc_html eklendi
		echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';

	} elseif ( is_category() ) {
		$cat_id    = get_queried_object_id();
		$ancestors = array_reverse( get_ancestors( $cat_id, 'category' ) );
		foreach ( $ancestors as $anc_id ) {
			$anc = get_category( $anc_id );
			echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
			echo '<a href="' . esc_url( get_category_link( $anc_id ) ) . '">' . esc_html( $anc->name ) . '</a>';
		}
		echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
		$cat = get_queried_object();
		echo '<span class="current">' . esc_html( $cat->name ) . '</span>';

	} elseif ( is_page() ) {
		echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
		// FIX: esc_html eklendi
		echo '<span class="current">' . esc_html( get_the_title() ) . '</span>';

	} elseif ( is_search() ) {
		echo '<span class="sep"><i class="fas fa-chevron-right"></i></span>';
		echo '<span class="current">' . __( 'Arama Sonuçları', ALV_TEXT ) . '</span>';
	}

	echo '</nav>';
}

/**
 * Sayfalama.
 * FIX: Kırılgan str_replace zincirleri yerine daha güvenilir yöntem.
 */
function alveren_pagination( $query = null ) {
	global $wp_query;
	$q = $query ? $query : $wp_query;

	if ( $q->max_num_pages <= 1 ) return;

	$current = max( 1, get_query_var( 'paged' ) );

	$links = paginate_links( array(
		'base'      => str_replace( PHP_INT_MAX, '%#%', esc_url( get_pagenum_link( PHP_INT_MAX ) ) ),
		'format'    => '?paged=%#%',
		'current'   => $current,
		'total'     => $q->max_num_pages,
		'prev_text' => '<i class="fas fa-chevron-left"></i>',
		'next_text' => '<i class="fas fa-chevron-right"></i>',
		'type'      => 'array',
		'end_size'  => 2,
		'mid_size'  => 2,
	) );

	if ( ! $links ) return;

	echo '<nav class="alv-pagination" aria-label="' . esc_attr__( 'Sayfalama', ALV_TEXT ) . '">';
	foreach ( $links as $link ) {
		// FIX: Güvenilir sınıf değiştirme — tırnak tiplerinden bağımsız regex
		$link = preg_replace( '/class=["\']page-numbers current["\']/', 'class="current"', $link );
		$link = preg_replace( '/class=["\']page-numbers dots["\']/',    'class="dots"',    $link );
		$link = preg_replace( '/class=["\']page-numbers/',              'class="alv-page-num', $link );
		echo $link; // phpcs:ignore WordPress.Security.EscapeOutput
	}
	echo '</nav>';
}

/**
 * Her zaman tam genişlik (sidebar yok).
 */
function alveren_has_sidebar() {
	return false;
}

function alveren_content_class() {
	return 'col-12';
}

/**
 * Tema seçeneği okuma.
 * FIX: Static cache null yerine '_not_set_' sentinel ile kontrol edildi.
 *      İlk yükte boş string döndürmeme sorunu giderildi.
 */
function alv_option( $key, $default = '' ) {
	static $cache = array();
	$sentinel = '__not_set__';
	if ( ! isset( $cache[ $key ] ) || $cache[ $key ] === $sentinel ) {
		$val = get_option( 'alv_' . $key, $sentinel );
		if ( $val === $sentinel || $val === '' ) {
			$val = get_theme_mod( 'alv_' . $key, $default );
		}
		$cache[ $key ] = ( $val !== $sentinel ) ? $val : $default;
	}
	return $cache[ $key ];
}

/* ============================================================
   7. LIKE SİSTEMİ
   ============================================================ */
function alveren_like_handler() {
	if ( ! check_ajax_referer( 'alv_like', 'nonce', false ) ) {
		wp_send_json_error( 'Geçersiz istek.', 403 );
	}
	$post_id = (int) ( isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0 );
	if ( ! $post_id || ! get_post( $post_id ) ) {
		wp_send_json_error( 'Geçersiz Post ID.', 400 );
	}

	$key     = 'alv_likes';
	$current = (int) get_post_meta( $post_id, $key, true );
	$ip_key  = 'alv_liked_ips_' . $post_id;
	$liked   = get_option( $ip_key, array() );

	// FIX: REMOTE_ADDR her zaman mevcut olmayabilir; boş string yerine placeholder kullan
	$ip = '';
	if ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
	}

	if ( $ip && in_array( $ip, $liked, true ) ) {
		wp_send_json_error( 'Zaten beğendiniz.', 409 );
	}

	$new_count = $current + 1;
	update_post_meta( $post_id, $key, $new_count );
	if ( $ip ) {
		$liked[] = $ip;
		update_option( $ip_key, $liked, false );
	}
	wp_send_json_success( array( 'count' => $new_count ) );
}
add_action( 'wp_ajax_alv_like',        'alveren_like_handler' );
add_action( 'wp_ajax_nopriv_alv_like', 'alveren_like_handler' );


/* ============================================================
   8. BODY CLASS
   ============================================================ */
function alveren_body_classes( $classes ) {
	$classes[] = 'no-sidebar';
	if ( is_singular() ) $classes[] = 'is-singular';
	return $classes;
}
add_filter( 'body_class', 'alveren_body_classes' );

/* ============================================================
   9. EXCERPT
   ============================================================ */
function alveren_excerpt_length() { return 28; }
add_filter( 'excerpt_length', 'alveren_excerpt_length', 999 );

function alveren_excerpt_more( $more ) { return '&hellip;'; }
add_filter( 'excerpt_more', 'alveren_excerpt_more' );

/* ============================================================
   10. HEAD TEMİZLİĞİ
   ============================================================ */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/* ============================================================
   11. TEMA RENKLERİ — Admin Ayarlarından CSS Olarak Enjekte Et
   ============================================================ */
function alveren_menu_color_css() {

	$o = function( $key, $default ) {
		$v = get_option( 'alv_' . $key, null );
		if ( $v === null || $v === '' ) $v = get_theme_mod( 'alv_' . $key, $default );
		return $v ?: $default;
	};

	$align_map   = [ 'left' => 'flex-start', 'center' => 'center', 'right' => 'flex-end' ];
	$logo_align  = $o( 'logo_align',        'left' );
	$logo_mob    = $o( 'logo_mobile_align', 'left' );
	$logo_h      = max( 24, (int) $o( 'logo_height',        '60' ) );
	$logo_mob_h  = max( 24, (int) $o( 'logo_mobile_height', '48' ) );
	$logo_just   = $align_map[ $logo_align ]  ?? 'flex-start';
	$logo_mob_j  = $align_map[ $logo_mob ]    ?? 'flex-start';
	$nav_logo_mr = ( $logo_align === 'right' ) ? 'auto' : '6px';
	$nav_logo_ml = ( $logo_align === 'center' ) ? 'auto' : '0';

	$mb_bg   = sanitize_hex_color( $o( 'color_menubar_bg',        '#00000038' ) );
	$hd_bg   = sanitize_hex_color( $o( 'color_header_bg',           '#1a2744' ) );
	$ft_bg   = sanitize_hex_color( $o( 'color_footer_bg',            '#111c33' ) );
	$ft_tx   = sanitize_hex_color( $o( 'color_footer_tx',            '#8899bb' ) );
	$ft_lk   = sanitize_hex_color( $o( 'color_footer_link',          '#c0392b' ) );
	$nb_bg   = sanitize_hex_color( $o( 'color_navbar_bg',         '#0f0f1a' ) );
	$nb_tx   = sanitize_hex_color( $o( 'color_navbar_tx',         '#c8d4ee' ) );
	$nb_htx  = sanitize_hex_color( $o( 'color_navbar_tx_hover',   '#ffffff' ) );
	$dd_bg   = sanitize_hex_color( $o( 'color_dropdown_bg',       '#ffffff' ) );
	$dd_tx   = sanitize_hex_color( $o( 'color_dropdown_tx',       '#1e2640' ) );
	$dd_hbg  = sanitize_hex_color( $o( 'color_dropdown_hover_bg', '#fdf5f4' ) );
	$dd_htx  = sanitize_hex_color( $o( 'color_dropdown_hover_tx', '#c0392b' ) );
	$dr_bg   = sanitize_hex_color( $o( 'color_drawer_bg',             '#f1f3f8' ) );
	$dr_hd   = sanitize_hex_color( $o( 'color_drawer_head',           '#0f0f1a' ) );
	$dr_tx   = sanitize_hex_color( $o( 'color_drawer_tx',             '#1a2744' ) );
	$dr_lhbg = sanitize_hex_color( $o( 'color_drawer_link_hover_bg',  '#e8ebf2' ) );
	$dr_lhtx = sanitize_hex_color( $o( 'color_drawer_link_hover_tx',  '#c0392b' ) );
	$dr_sub  = sanitize_hex_color( $o( 'color_drawer_sub_bg',         '#f1f3f8' ) );
	$sr_bar  = sanitize_hex_color( $o( 'color_search_bar_bg',           '#000000' ) );
	$sr_ibg  = sanitize_hex_color( $o( 'color_search_input_bg',        '#1a1a2e' ) );
	$sr_ibr  = sanitize_hex_color( $o( 'color_search_input_border',    '#2e2e4e' ) );
	$sr_itx  = sanitize_hex_color( $o( 'color_search_input_tx',        '#ffffff' ) );
	$sr_bbg  = sanitize_hex_color( $o( 'color_search_btn_bg',          '#c0392b' ) );
	$sr_ph   = $o( 'color_search_placeholder', 'rgba(255,255,255,0.38)' );
	$sr_btx  = sanitize_hex_color( $o( 'color_search_btn_tx',          '#ffffff' ) );
	$sr_rbg  = sanitize_hex_color( $o( 'color_search_result_bg',       '#ffffff' ) );
	$sr_rtx  = sanitize_hex_color( $o( 'color_search_result_tx',       '#1e2640' ) );
	$sr_rhbg = sanitize_hex_color( $o( 'color_search_result_hover_bg', '#f8f9fc' ) );
	$sr_hl   = sanitize_hex_color( $o( 'color_search_highlight',       '#c0392b' ) );

	/* Logo w/h scroll */
	$logo_w     = (int) get_option( 'alv_logo_width',          '0' );
	$logo_mob_w = (int) get_option( 'alv_logo_mobile_width',   '0' );
	$logo_sm_h  = max( 20, (int) get_option( 'alv_logo_scroll_height', '38' ) );
	$logo_sm_w  = (int) get_option( 'alv_logo_scroll_width',   '0' );
	$css_lw     = $logo_w     > 0 ? absint( $logo_w )     . 'px' : 'auto';
	$css_lmw    = $logo_mob_w > 0 ? absint( $logo_mob_w ) . 'px' : 'auto';
	$css_lsmw   = $logo_sm_w  > 0 ? absint( $logo_sm_w )  . 'px' : 'auto';

	echo '<style id="alv-theme-colors">
:root {
	--alv-navbar-bg:       ' . esc_attr( $nb_bg )   . ';
	--alv-navbar-tx:       ' . esc_attr( $nb_tx )   . ';
	--alv-navbar-tx-hover: ' . esc_attr( $nb_htx )  . ';
	--alv-drawer-bg:       ' . esc_attr( $dr_bg )   . ';
	--alv-drawer-head:     ' . esc_attr( $dr_hd )   . ';
	--alv-drawer-tx:       ' . esc_attr( $dr_tx )   . ';
	--alv-logo-h:          ' . absint( $logo_h )    . 'px;
	--alv-logo-w:          ' . $css_lw              . ';
	--alv-logo-mob-h:      ' . absint( $logo_mob_h ) . 'px;
	--alv-logo-mob-w:      ' . $css_lmw             . ';
	--alv-logo-sm-h:       ' . absint( $logo_sm_h ) . 'px;
	--alv-logo-sm-w:       ' . $css_lsmw            . ';
	--alv-menu-h:          44px;
	--alv-sticky-h:        56px;
	--alv-search-bar-bg:      ' . esc_attr( $sr_bar )  . ';
	--alv-search-placeholder: ' . esc_attr( $sr_itx )  . ';
	--alv-header-bg:       ' . esc_attr( $hd_bg )   . ';
	--alv-footer-bg:       ' . esc_attr( $ft_bg )   . ';
	--alv-footer-tx:       ' . esc_attr( $ft_tx )   . ';
	--alv-footer-link:     ' . esc_attr( $ft_lk )   . ';
}
.alv-header { background:' . esc_attr( $hd_bg ) . '; }
.alv-header__row2 { background:' . esc_attr( $mb_bg ) . '; }
.alv-footer { background:' . esc_attr( $ft_bg ) . ' !important; color:' . esc_attr( $ft_tx ) . '; }
.alv-footer a, .alv-footer__site-name { color:' . esc_attr( $ft_lk ) . '; }
.alv-nav .dropdown-menu { background:' . esc_attr( $dd_bg ) . '; }
.alv-nav .dropdown-item { color:' . esc_attr( $dd_tx ) . '; }
.alv-nav .dropdown-item:hover { background:' . esc_attr( $dd_hbg ) . '; color:' . esc_attr( $dd_htx ) . '; }
.alv-nav > .nav-item > .nav-link:hover, .alv-nav > .nav-item > .nav-link.active { color:' . esc_attr( $nb_htx ) . '; }
.alv-drawer-nav__link, .alv-drawer-nav__toggle { color:' . esc_attr( $dr_tx ) . '; }
.alv-drawer-nav__link:hover, .alv-drawer-nav__item.active > .alv-drawer-nav__link { background:' . esc_attr( $dr_lhbg ) . '; color:' . esc_attr( $dr_lhtx ) . '; }
.alv-drawer-nav__toggle:hover { background:' . esc_attr( $dr_lhbg ) . '; color:' . esc_attr( $dr_lhtx ) . '; }
.alv-drawer-nav__sub { background:' . esc_attr( $dr_sub ) . '; }
.alv-header__search-form { background:' . esc_attr( $sr_ibg ) . '; border-color:' . esc_attr( $sr_ibr ) . '; }
.alv-header__search-input { color:' . esc_attr( $sr_itx ) . '; }
.alv-header__search-input::placeholder { color:' . esc_attr( $sr_itx ) . '; opacity:.5; }
.alv-header__search-btn { background:' . esc_attr( $sr_bbg ) . '; color:' . esc_attr( $sr_btx ) . '; }
.alv-header__search-btn:hover { background:' . esc_attr( $sr_bbg ) . '; filter:brightness(.9); }
.alv-drawer__search-form { background:' . esc_attr( $sr_ibg ) . '; border-color:' . esc_attr( $sr_ibr ) . '; }
.alv-drawer__search-input { color:' . esc_attr( $sr_itx ) . '; }
.alv-drawer__search-btn { background:' . esc_attr( $sr_bbg ) . '; color:' . esc_attr( $sr_btx ) . '; }
.alv-header__search-live, .alv-live-results, .alv-drawer-live-results, .alv-hero-live-results { background:' . esc_attr( $sr_rbg ) . '; }
.alv-live-item { color:' . esc_attr( $sr_rtx ) . '; }
.alv-live-item:hover { background:' . esc_attr( $sr_rhbg ) . '; }
.alv-live-title mark { color:' . esc_attr( $sr_hl ) . '; }
.alv-live-all { color:' . esc_attr( $sr_hl ) . '; }
</style>';
}
add_action( 'wp_head', 'alveren_menu_color_css', 99 );

/* ============================================================
   12b. FAVİCON + LOGO PADDİNG — wp_head enjeksiyonu
   ============================================================ */
function alveren_head_extras() {
	/* Favicon */
	$fav = get_option( 'alv_favicon_url', '' );
	if ( $fav ) {
		echo '<link rel="icon" href="' . esc_url( $fav ) . '" sizes="any">' . "\n";
		echo '<link rel="apple-touch-icon" href="' . esc_url( $fav ) . '">' . "\n";
	}

	/* Logo padding CSS değişkenleri */
	$pt = absint( get_option( 'alv_logo_pad_top',    '10' ) );
	$pb = absint( get_option( 'alv_logo_pad_bottom', '10' ) );
	$pl = absint( get_option( 'alv_logo_pad_left',   '20' ) );
	echo '<style id="alv-logo-padding">';
	echo '.alv-logo-zone { padding-top:' . $pt . 'px; padding-bottom:' . $pb . 'px; padding-left:' . $pl . 'px; }';
	echo '</style>' . "\n";

	/* Header image */
	$hi_url  = get_option( 'alv_header_image_url',  '' );
	$hi_show = get_option( 'alv_header_image_show', '0' );
	if ( $hi_show && $hi_url ) {
		echo '<style id="alv-header-image">';
		echo '.alv-header { background-image: url(' . esc_url( $hi_url ) . '); background-size: cover; background-position: center; background-repeat: no-repeat; }';
		echo '</style>' . "\n";
	}

}
add_action( 'wp_head', 'alveren_head_extras', 5 );

/* ============================================================
   11c. ÖNE ÇIKAN HABERLER — AJAX Önizleme Handler
   URL girilince başlık + öne çıkan görsel çekilir
   ============================================================ */
function alveren_fetch_spot() {
	check_ajax_referer( 'alv_save', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) wp_send_json_error( 'Yetkisiz.', 403 );

	$url     = esc_url_raw( wp_unslash( $_POST['spot_url'] ?? '' ) );
	$post_id = url_to_postid( $url );

	if ( ! $post_id ) {
		wp_send_json_error( 'Bu URL sitenizde bir yazıya ait değil.' );
	}

	$title    = get_the_title( $post_id );
	$thumb    = '';
	$thumb_id = get_post_thumbnail_id( $post_id );
	if ( $thumb_id ) {
		$img_src = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
		if ( $img_src ) $thumb = $img_src[0];
	}

	wp_send_json_success( [
		'title' => $title,
		'thumb' => $thumb,
		'url'   => get_permalink( $post_id ),
	] );
}
add_action( 'wp_ajax_alv_fetch_spot', 'alveren_fetch_spot' );

/* ============================================================
   12. INC DOSYALARI
   ============================================================ */
$alv_inc_files = array(
	'/inc/class-nav-walker.php',
	'/inc/customizer.php',
	'/inc/admin-page.php',
	'/inc/template-tags.php',
);
foreach ( $alv_inc_files as $file ) {
	$path = ALV_DIR . $file;
	if ( file_exists( $path ) ) {
		require_once $path;
	}
}
