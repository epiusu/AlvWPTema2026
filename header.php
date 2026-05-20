<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
/* ── Değişkenler ── */
$alv_logo_url        = trim( get_option( 'alv_logo_url', get_theme_mod( 'alv_logo_url', '' ) ) );
$alv_logo_mobile_url = trim( get_option( 'alv_logo_mobile_url', '' ) );
if ( ! $alv_logo_mobile_url ) $alv_logo_mobile_url = $alv_logo_url;

$alv_logo_h      = max( 24, (int) get_option( 'alv_logo_height',        '60' ) );
$alv_logo_w      = (int) get_option( 'alv_logo_width',          '0' );
$alv_logo_mob_h  = max( 20, (int) get_option( 'alv_logo_mobile_height', '40' ) );
$alv_logo_mob_w  = (int) get_option( 'alv_logo_mobile_width',   '0' );
$alv_logo_sm_h   = max( 20, (int) get_option( 'alv_logo_scroll_height', '38' ) );
$alv_logo_sm_w   = (int) get_option( 'alv_logo_scroll_width',   '0' );

$css_logo_h      = absint( $alv_logo_h )    . 'px';
$css_logo_w      = $alv_logo_w   > 0 ? absint( $alv_logo_w )   . 'px' : 'auto';
$css_logo_mob_h  = absint( $alv_logo_mob_h ) . 'px';
$css_logo_mob_w  = $alv_logo_mob_w > 0 ? absint( $alv_logo_mob_w ) . 'px' : 'auto';
$css_logo_sm_h   = absint( $alv_logo_sm_h )  . 'px';
$css_logo_sm_w   = $alv_logo_sm_w  > 0 ? absint( $alv_logo_sm_w  ) . 'px' : 'auto';

$alv_site_name   = get_bloginfo( 'name' );
$alv_date        = date_i18n( 'd F Y, l' );


/* Sosyal medya */
$alv_social_links = get_option( 'alv_social_links', [] );
if ( empty( $alv_social_links ) ) {
	foreach ( [
		[ 'key'=>'social_twitter',   'icon'=>'fab fa-x-twitter',  'label'=>'Twitter'  ],
		[ 'key'=>'social_facebook',  'icon'=>'fab fa-facebook-f', 'label'=>'Facebook' ],
		[ 'key'=>'social_instagram', 'icon'=>'fab fa-instagram',  'label'=>'Instagram'],
		[ 'key'=>'social_youtube',   'icon'=>'fab fa-youtube',    'label'=>'YouTube'  ],
		[ 'key'=>'social_telegram',  'icon'=>'fab fa-telegram',   'label'=>'Telegram' ],
	] as $item ) {
		$url = trim( get_theme_mod( 'alv_' . $item['key'], '' ) );
		if ( $url ) $alv_social_links[] = [ 'url'=>$url, 'icon'=>$item['icon'], 'label'=>$item['label'] ];
	}
}
?>
<style>
/* ================================================================
   ALV HEADER v1.27
   Yapı (ekran görüntüsüne birebir):
   SATIR 1 : [Logo(sol)] [Arama(orta-geniş)] [boş sağ]
   SATIR 2 : [≡ Hamburger] [Nav linkleri...]
   Alt kenar: kalın kırmızı çizgi
   Scroll : aynı iki satır yapışık kalır, sadece gölge eklenir
   ================================================================ */
:root {
	--alv-logo-h:     <?php echo $css_logo_h; ?>;
	--alv-logo-w:     <?php echo $css_logo_w; ?>;
	--alv-logo-mob-h: <?php echo $css_logo_mob_h; ?>;
	--alv-logo-mob-w: <?php echo $css_logo_mob_w; ?>;
	--alv-logo-sm-h:  <?php echo $css_logo_sm_h; ?>;
	--alv-logo-sm-w:  <?php echo $css_logo_sm_w; ?>;
	--alv-top-h:      56px;   /* Satır 1 yüksekliği */
	--alv-nav-h:      44px;   /* Satır 2 yüksekliği */
}

/* ── TEMEL HEADER ── */
.alv-header {
	position: sticky;
	top: 0;
	z-index: 1030;
	width: 100%;
	background: var(--alv-navbar-bg, #0a0a0a);
	border-bottom: 3px solid var(--alv-red, #c0392b);
	transition: box-shadow .22s ease;
	overflow: visible;
}
.alv-header.is-scrolled {
	box-shadow: 0 2px 24px rgba(0,0,0,.55);
}
body.admin-bar .alv-header { top: 32px; }
@media (max-width: 782px) { body.admin-bar .alv-header { top: 46px; } }

/* ================================================================
   SATIR 1 — Logo + Arama
   ================================================================ */
.alv-header__row1 {
	display: flex;
	align-items: center;
	width: 100%;
	height: var(--alv-top-h);
	padding: 0 16px 0 0;
	box-sizing: border-box;
	border-bottom: 1px solid rgba(255,255,255,.06);
	overflow: visible;
	position: relative;
}

/* Logo bloğu — sol, koyu arka plan, scroll'da küçülmez */
.alv-header__logo {
	display: flex;
	align-items: center;
	justify-content: center;
	flex-shrink: 0;
	height: 100%;
	padding: 6px 16px;
	box-sizing: border-box;
	background: var(--alv-navbar-bg, #0a0a0a);
	border-right: 1px solid rgba(255,255,255,.07);
}
.alv-header__logo a {
	display: inline-flex;
	align-items: center;
	text-decoration: none;
	height: 100%;
}
.alv-header__logo img,
.alv-header__logo .custom-logo {
	height: var(--alv-logo-h);
	width: var(--alv-logo-w);
	max-width: 220px;
	max-height: calc(var(--alv-top-h) - 14px);
	object-fit: contain;
	display: block;
}
.alv-header__logo .alv-logo-text {
	font-family: var(--alv-font-head, Georgia, serif);
	font-size: 22px;
	font-weight: 800;
	color: #fff;
	letter-spacing: -.03em;
	white-space: nowrap;
}
.alv-header__logo .alv-logo-text span { color: var(--alv-red, #c0392b); }

/* Arama alanı — ortada, esnek genişlik */
.alv-header__search {
	flex: 1;
	display: flex;
	align-items: center;
	padding: 0 20px;
	height: 100%;
	position: relative;
	overflow: visible;
}
.alv-header__search-form {
	display: flex;
	align-items: center;
	width: 100%;
	max-width: 680px;
	height: 36px;
	background: var(--alv-search-input-bg, rgba(255,255,255,.07));
	border: 1px solid var(--alv-search-input-border, rgba(255,255,255,.14));
	border-radius: 4px;
	overflow: hidden;
	transition: background .18s, border-color .18s, box-shadow .18s;
}
.alv-header__search-form:focus-within {
	background: var(--alv-search-input-bg-focus, rgba(255,255,255,.11));
	border-color: var(--alv-search-input-border-focus, rgba(255,255,255,.38));
	box-shadow: 0 0 0 3px rgba(192,57,43,.2);
}
.alv-header__search-icon {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 38px;
	flex-shrink: 0;
	color: var(--alv-search-icon-color, rgba(255,255,255,.4));
	font-size: 13px;
}
.alv-header__search-input {
	flex: 1;
	border: none;
	background: transparent;
	padding: 0 8px 0 0;
	font-size: 14px;
	color: var(--alv-search-input-tx, #fff);
	outline: none;
	min-width: 0;
}
.alv-header__search-input::placeholder {
	color: var(--alv-search-placeholder, rgba(255,255,255,.32));
}
.alv-header__search-btn {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 42px;
	height: 36px;
	flex-shrink: 0;
	border: none;
	background: var(--alv-search-btn-bg, rgba(255,255,255,.1));
	color: var(--alv-search-btn-tx, rgba(255,255,255,.7));
	font-size: 13px;
	cursor: pointer;
	transition: background .15s, color .15s;
}
.alv-header__search-btn:hover {
	background: var(--alv-search-btn-bg-hover, var(--alv-red, #c0392b));
	color: #fff;
}

/* Canlı sonuçlar kutusu */
.alv-header__search-live {
	position: absolute;
	top: calc(100% + 4px);
	left: 20px;
	right: 0;
	min-width: 340px;
	max-width: 700px;
	background: var(--alv-search-result-bg, #fff);
	border: 1px solid #e0e4ef;
	border-radius: 0 0 6px 6px;
	box-shadow: 0 12px 40px rgba(0,0,0,.22);
	z-index: 2000;
	max-height: 440px;
	overflow-y: auto;
	opacity: 0;
	transform: translateY(-4px);
	pointer-events: none;
	transition: opacity .18s, transform .18s;
}
.alv-header__search-live.is-visible {
	opacity: 1;
	transform: translateY(0);
	pointer-events: auto;
}
/* Sonuç öğeleri */
.alv-live-list  { list-style: none; margin: 0; padding: 6px 0; }
.alv-live-item  {
	display: flex; align-items: center; gap: 10px;
	padding: 9px 16px; text-decoration: none;
	color: var(--alv-search-result-tx, #1e2640);
	font-size: 13.5px; transition: background .12s;
	border-bottom: 1px solid #f0f2f8;
}
.alv-live-item:last-child { border-bottom: none; }
.alv-live-item:hover { background: var(--alv-search-result-hover-bg, #f5f7ff); }
.alv-live-cat   {
	display: inline-block; background: #e8ecf8; color: #1a2744;
	font-size: 10px; font-weight: 700; padding: 2px 7px; border-radius: 4px;
	flex-shrink: 0; text-transform: uppercase; letter-spacing: .04em;
}
.alv-live-title { flex: 1; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.alv-live-title mark { background: none; color: var(--alv-search-highlight, #c0392b); font-weight: 700; padding: 0; }
.alv-live-date  { font-size: 11px; color: #999; flex-shrink: 0; }
.alv-live-all   {
	display: flex; align-items: center; gap: 6px; justify-content: center;
	padding: 11px 16px; font-size: 13px; font-weight: 700;
	color: var(--alv-search-highlight, #c0392b); text-decoration: none;
	border-top: 1px solid #eee; transition: background .12s;
}
.alv-live-all:hover { background: #fff5f5; }
.alv-live-empty { padding: 20px 16px; text-align: center; color: #999; font-size: 13.5px; }

/* ================================================================
   SATIR 2 — Hamburger + Navigasyon
   ================================================================ */
.alv-header__row2 {
	display: flex;
	align-items: center;
	width: 100%;
	height: var(--alv-nav-h);
	padding: 0 12px 0 8px;
	box-sizing: border-box;
	gap: 0;
}

/* Hamburger */
.alv-hamburger {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 40px;
	height: 40px;
	flex-shrink: 0;
	background: transparent;
	border: none;
	color: rgba(255,255,255,.8);
	font-size: 18px;
	cursor: pointer;
	border-radius: 4px;
	transition: color .15s, background .15s;
	margin-right: 4px;
}
.alv-hamburger:hover {
	color: #fff;
	background: rgba(255,255,255,.08);
}

/* Desktop nav listesi */
.alv-nav {
	display: flex;
	align-items: stretch;
	list-style: none;
	margin: 0;
	padding: 0;
	height: 100%;
	flex: 1;
	gap: 0;
	min-width: 0;
}
.alv-nav > .nav-item {
	display: flex;
	align-items: stretch;
	position: relative;
}
.alv-nav > .nav-item > .nav-link {
	display: flex;
	align-items: center;
	gap: 4px;
	padding: 0 14px;
	font-size: 13.5px;
	font-weight: 600;
	color: rgba(255,255,255,.82);
	text-decoration: none;
	white-space: nowrap;
	height: 100%;
	letter-spacing: .01em;
	border-bottom: 2px solid transparent;
	transition: color .15s, border-color .15s;
}
.alv-nav > .nav-item > .nav-link:hover,
.alv-nav > .nav-item > .nav-link.active {
	color: #fff;
	border-bottom-color: var(--alv-red, #c0392b);
}
/* Aktif (güncel sayfa) */
.alv-nav > .nav-item.current-menu-item > .nav-link,
.alv-nav > .nav-item.current-menu-ancestor > .nav-link {
	color: #fff;
	border-bottom-color: var(--alv-red, #c0392b);
}

/* Hover dropdown — CSS, titreme yok */
.alv-nav > .nav-item.dropdown { position: relative; }
.alv-nav > .nav-item.dropdown > .dropdown-menu {
	position: absolute;
	top: 100%;
	left: 0;
	min-width: 230px;
	background: #111;
	border: 1px solid rgba(255,255,255,.1);
	border-top: 3px solid var(--alv-red, #c0392b);
	border-radius: 0 0 6px 6px;
	box-shadow: 0 12px 32px rgba(0,0,0,.5);
	padding: 6px 0;
	z-index: 1040;
	opacity: 0;
	visibility: hidden;
	transform: translateY(-4px);
	transition: opacity .18s ease, transform .18s ease, visibility 0s .18s;
	pointer-events: none;
}
.alv-nav > .nav-item.dropdown:hover > .dropdown-menu,
.alv-nav > .nav-item.dropdown:focus-within > .dropdown-menu {
	opacity: 1;
	visibility: visible;
	transform: translateY(0);
	transition: opacity .18s ease, transform .18s ease, visibility 0s 0s;
	pointer-events: auto;
}
.alv-nav .dropdown-item {
	display: block;
	padding: 9px 18px;
	font-size: 13.5px;
	color: rgba(255,255,255,.8);
	text-decoration: none;
	transition: background .12s, color .12s;
}
.alv-nav .dropdown-item:hover {
	background: rgba(255,255,255,.06);
	color: #fff;
}

/* ================================================================
   MOBİL
   ================================================================ */
@media (max-width: 991px) {
	.alv-nav { display: none; }
	.alv-header__row1 { height: calc(var(--alv-logo-mob-h) + 16px); }
	.alv-header__logo img,
	.alv-header__logo .custom-logo {
		height: var(--alv-logo-mob-h);
		width:  var(--alv-logo-mob-w);
		max-height: none;
	}
}
@media (max-width: 575px) {
	.alv-header__row1 { padding-right: 8px; }
	.alv-header__search { padding: 0 10px; }
	.alv-header__logo { padding: 6px 10px; }
}
</style>

<div id="page" class="site">
<div id="alvOverlay" class="alv-overlay" aria-hidden="true"></div>

<!-- ================================================================
     DRAWER — ABC News tarzı
     ================================================================ -->
<aside id="alvDrawer" class="alv-drawer"
	aria-label="<?php esc_attr_e( 'Gezinti Menüsü', 'alveren' ); ?>"
	role="dialog" aria-modal="true">

	<div class="alv-drawer__head">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="alv-drawer__logo">
			<?php if ( $alv_logo_mobile_url ) : ?>
				<img src="<?php echo esc_url( $alv_logo_mobile_url ); ?>"
					 alt="<?php echo esc_attr( $alv_site_name ); ?>"
					 style="height:40px;max-width:180px;object-fit:contain;">
			<?php elseif ( has_custom_logo() ) : ?>
				<?php the_custom_logo(); ?>
			<?php else : ?>
				<span class="alv-drawer__logo-text"><?php echo esc_html( $alv_site_name ); ?></span>
			<?php endif; ?>
		</a>
		<button id="alvDrawerClose" class="alv-drawer__close" type="button"
			aria-label="<?php esc_attr_e( 'Menüyü kapat', 'alveren' ); ?>">
			<i class="fas fa-times" aria-hidden="true"></i>
		</button>
	</div>

	<div class="alv-drawer__search">
		<form class="alv-drawer__search-form" role="search" method="get"
			action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span class="alv-drawer__search-icon" aria-hidden="true">
				<i class="fas fa-search"></i>
			</span>
			<input type="search" id="alvDrawerSearchInput" name="s"
				class="alv-drawer__search-input"
				placeholder="<?php esc_attr_e( 'Haber, kategori ara…', 'alveren' ); ?>"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				autocomplete="off"
				aria-label="<?php esc_attr_e( 'Arama', 'alveren' ); ?>">
			<button type="submit" class="alv-drawer__search-btn"
				aria-label="<?php esc_attr_e( 'Ara', 'alveren' ); ?>">
				<i class="fas fa-arrow-right" aria-hidden="true"></i>
			</button>
		</form>
		<div id="alvDrawerLiveResults" class="alv-drawer-live-results"
			role="listbox"
			aria-label="<?php esc_attr_e( 'Canlı arama sonuçları', 'alveren' ); ?>"></div>
	</div>

	<nav class="alv-drawer__nav"
		aria-label="<?php esc_attr_e( 'Mobil Navigasyon', 'alveren' ); ?>">
		<?php
		$rendered = false;
		if ( has_nav_menu( 'mobile' ) ) {
			wp_nav_menu( [
				'theme_location' => 'mobile',
				'fallback_cb'    => false,
				'container'      => false,
				'items_wrap'     => '<ul class="alv-drawer-nav">%3$s</ul>',
				'walker'         => new Alveren_Drawer_Walker(),
			] );
			$rendered = true;
		}
		if ( ! $rendered && has_nav_menu( 'primary' ) ) {
			wp_nav_menu( [
				'theme_location' => 'primary',
				'fallback_cb'    => false,
				'container'      => false,
				'items_wrap'     => '<ul class="alv-drawer-nav">%3$s</ul>',
				'walker'         => new Alveren_Drawer_Walker(),
			] );
		}
		?>
	</nav>

	<?php if ( ! empty( $alv_social_links ) ) : ?>
	<div class="alv-drawer__social">
		<p class="alv-drawer__social-label">
			<?php esc_html_e( 'Takip Edin', 'alveren' ); ?>
		</p>
		<div class="alv-drawer__social-links">
			<?php foreach ( $alv_social_links as $soc ) :
				if ( empty( $soc['url'] ) ) continue; ?>
			<a href="<?php echo esc_url( $soc['url'] ); ?>"
				target="_blank" rel="noopener noreferrer"
				aria-label="<?php echo esc_attr( $soc['label'] ?? '' ); ?>">
				<i class="<?php echo esc_attr( $soc['icon'] ?? 'fas fa-globe' ); ?>"
					aria-hidden="true"></i>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="alv-drawer__foot">
		<span class="alv-drawer__foot-date">
			<i class="fas fa-calendar-alt" aria-hidden="true"></i>
			<?php echo esc_html( $alv_date ); ?>
		</span>
		<span id="alvDrawerClock" class="alv-drawer__foot-clock"
			aria-live="polite"></span>
	</div>

</aside>

<!-- ================================================================
     HEADER — İki satır, her zaman yapışık
     ================================================================ -->
<header class="alv-header" id="alvHeader" role="banner">

	<!-- SATIR 1: Logo (sol) + Arama (orta) -->
	<div class="alv-header__row1">

		<!-- Logo -->
		<div class="alv-header__logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"
				aria-label="<?php echo esc_attr( $alv_site_name ); ?>">
				<?php if ( $alv_logo_url ) : ?>
					<img src="<?php echo esc_url( $alv_logo_url ); ?>"
						 alt="<?php echo esc_attr( $alv_site_name ); ?>"
						 loading="eager">
				<?php elseif ( has_custom_logo() ) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
					<span class="alv-logo-text"><?php
						$_p = explode( ' ', $alv_site_name, 2 );
						echo esc_html( $_p[0] );
						if ( isset( $_p[1] ) ) echo ' <span>' . esc_html( $_p[1] ) . '</span>';
					?></span>
				<?php endif; ?>
			</a>
		</div>

		<!-- Arama -->
		<div class="alv-header__search">
			<form class="alv-header__search-form" role="search" method="get"
				action="<?php echo esc_url( home_url('/') ); ?>">
				<span class="alv-header__search-icon" aria-hidden="true">
					<i class="fas fa-search"></i>
				</span>
				<input
					type="search"
					id="alvSearchBarInput"
					name="s"
					class="alv-header__search-input"
					placeholder="<?php esc_attr_e( 'Ara… makale, kategori, konu', 'alveren' ); ?>"
					value="<?php echo esc_attr( get_search_query() ); ?>"
					autocomplete="off"
					aria-label="<?php esc_attr_e( 'Arama', 'alveren' ); ?>"
					aria-controls="alvSearchBarLive"
					aria-autocomplete="list"
				>
				<button type="submit" class="alv-header__search-btn"
					aria-label="<?php esc_attr_e( 'Ara', 'alveren' ); ?>">
					<i class="fas fa-search" aria-hidden="true"></i>
				</button>
			</form>
			<div id="alvSearchBarLive"
				class="alv-header__search-live"
				role="listbox"
				aria-label="<?php esc_attr_e( 'Canlı arama sonuçları', 'alveren' ); ?>">
			</div>
		</div>

	</div><!-- .alv-header__row1 -->

	<!-- SATIR 2: Hamburger + Navigasyon -->
	<div class="alv-header__row2">

		<!-- Hamburger -->
		<button id="alvDrawerOpen" class="alv-hamburger" type="button"
			aria-label="<?php esc_attr_e( 'Menüyü aç', 'alveren' ); ?>"
			aria-expanded="false"
			aria-controls="alvDrawer">
			<i class="fas fa-bars" aria-hidden="true"></i>
		</button>

		<!-- Desktop navigasyon — hover dropdown -->
		<?php
		wp_nav_menu( [
			'theme_location' => 'primary',
			'fallback_cb'    => false,
			'container'      => false,
			'items_wrap'     => '<ul id="alvPrimaryMenu" class="alv-nav" role="menubar">%3$s</ul>',
			'walker'         => new Alveren_Nav_Walker(),
			'depth'          => 2,
		] );
		?>

	</div><!-- .alv-header__row2 -->

</header>

<!-- İÇERİK -->
<div id="content" class="site-content">
	<div class="container" style="max-width:var(--alv-container);">
		<div class="row">
