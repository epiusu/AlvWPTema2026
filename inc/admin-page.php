<?php
/**
 * WP Alveren — Tema Ayarları Admin Sayfası v1.25
 * Görünüm > Tema Ayarları
 *
 * Sekmeler:
 *  1. Genel      — Header image / Custom logo / Text logo / Padding / Favicon / Alıntılar
 *  2. Navigasyon — Menü bilgisi
 *  3. Kategoriler — Anasayfa 6 kutu
 *  4. Footer     — Telif + linkler
 *  6. Renkler    — Header · Menü · Hamburger · Footer · Arama
 *  7. Sosyal     — Tekrarlı liste
 *
 * @package WPAlveren
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Alveren_Admin_Settings {

	public static function init() {
		add_action( 'admin_menu',            [ __CLASS__, 'register_page' ] );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
		add_action( 'wp_ajax_alv_save',      [ __CLASS__, 'save' ] );
	}

	public static function register_page() {
		add_theme_page(
			__( 'Tema Ayarları', 'alveren' ),
			__( 'Tema Ayarları', 'alveren' ),
			'manage_options',
			'alv-tema-ayarlari',
			[ __CLASS__, 'render' ]
		);
	}

	public static function enqueue( $hook ) {
		if ( $hook !== 'appearance_page_alv-tema-ayarlari' ) return;
		wp_enqueue_media();
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script(
			'alv-admin-settings',
			ALV_URI . '/js/admin-settings.js',
			[ 'jquery', 'wp-color-picker' ],
			ALV_VERSION,
			true
		);
		wp_localize_script( 'alv-admin-settings', 'alvAdm', [
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'alv_save' ),
			'saved'   => __( 'Ayarlar kaydedildi ✓', 'alveren' ),
			'saving'  => __( 'Kaydediliyor…', 'alveren' ),
			'error'   => __( 'Bir hata oluştu!', 'alveren' ),
			'choose'  => __( 'Görsel Seç', 'alveren' ),
			'use'     => __( 'Kullan', 'alveren' ),
		] );
	}

	/* ── AJAX Kaydet ── */
	public static function save() {
		check_ajax_referer( 'alv_save', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Yetkisiz erişim.', 403 );
		}

		$text_map = [
			'logo_url'              => 'esc_url_raw',
			'logo_mobile_url'       => 'esc_url_raw',
			'logo_text'             => 'sanitize_text_field',
			'logo_align'            => 'sanitize_text_field',
			'logo_height'           => 'absint',
			'logo_width'            => 'absint',
			'logo_scroll_height'    => 'absint',
			'logo_scroll_width'     => 'absint',
			'logo_mobile_align'     => 'sanitize_text_field',
			'logo_mobile_height'    => 'absint',
			'logo_mobile_width'     => 'absint',
			'header_image_url'      => 'esc_url_raw',
			'logo_pad_top'          => 'absint',
			'logo_pad_bottom'       => 'absint',
			'logo_pad_left'         => 'absint',
			'favicon_url'           => 'esc_url_raw',
			/* Sol/Sağ öne çıkan haber kutusu */
			'footer_copyright_text' => 'wp_kses_post',
			'footer_page_about'     => 'esc_url_raw',
			'footer_page_contact'   => 'esc_url_raw',
			'footer_page_cookies'   => 'esc_url_raw',
			'footer_page_terms'     => 'esc_url_raw',
			'footer_extra_link'     => 'esc_url_raw',
			'footer_extra_text'     => 'sanitize_text_field',
		];
		foreach ( $text_map as $key => $fn ) {
			$raw = isset( $_POST[ $key ] ) ? wp_unslash( $_POST[ $key ] ) : '';
			update_option( 'alv_' . $key, call_user_func( $fn, $raw ) );
		}

		update_option( 'alv_logo_use_text',     isset( $_POST['logo_use_text'] )     ? '1' : '0' );
		update_option( 'alv_logo_use_image',    isset( $_POST['logo_use_image'] )    ? '1' : '0' );
		update_option( 'alv_header_image_show', isset( $_POST['header_image_show'] ) ? '1' : '0' );

		$color_keys = [
			'color_header_bg',
			'color_navbar_bg', 'color_menubar_bg',
			'color_navbar_tx', 'color_navbar_tx_hover',
			'color_dropdown_bg', 'color_dropdown_tx',
			'color_dropdown_hover_bg', 'color_dropdown_hover_tx',
			'color_drawer_bg', 'color_drawer_head',
			'color_drawer_tx', 'color_drawer_link_hover_bg',
			'color_drawer_link_hover_tx', 'color_drawer_sub_bg',
			'color_footer_bg', 'color_footer_tx', 'color_footer_link',
			/* Arama çubuğu */
			'color_search_bar_bg',            /* Arama çubuğu arka plan (katman 1) */
			'color_search_input_bg',          /* Input arka plan */
			'color_search_input_border',      /* Input kenarlık */
			'color_search_input_tx',          /* Input yazı */
			'color_search_placeholder',       /* Placeholder */
			'color_search_btn_bg',            /* Ara butonu */
			'color_search_btn_tx',
			'color_search_result_bg',
			'color_search_result_tx',
			'color_search_result_hover_bg',
			'color_search_highlight',
		];
		foreach ( $color_keys as $key ) {
			$raw = isset( $_POST[ $key ] ) ? sanitize_hex_color( wp_unslash( $_POST[ $key ] ) ) : '';
			if ( $raw ) update_option( 'alv_' . $key, $raw );
		}

		for ( $i = 1; $i <= 6; $i++ ) {
			update_option( "alv_home_cat_$i",      absint( $_POST["home_cat_$i"] ?? 0 ) );
			update_option( "alv_home_cat_icon_$i", sanitize_text_field( wp_unslash( $_POST["home_cat_icon_$i"] ?? '' ) ) );
		}

		/* Öne Çıkan Haberler (JSON, maks 10) */
		$spots_raw   = isset( $_POST['featured_spots'] ) ? wp_unslash( $_POST['featured_spots'] ) : '[]';
		$spots_arr   = json_decode( $spots_raw, true );
		$spots_clean = [];
		if ( is_array( $spots_arr ) ) {
			foreach ( array_slice( $spots_arr, 0, 10 ) as $sp ) {
				$su = esc_url_raw( trim( $sp['url']   ?? '' ) );
				$st = sanitize_text_field( trim( $sp['title'] ?? '' ) );
				$si = esc_url_raw( trim( $sp['img']   ?? '' ) );
				if ( $su ) $spots_clean[] = [ 'url'=>$su, 'title'=>$st, 'img'=>$si ];
			}
		}
		update_option( 'alv_featured_spots', $spots_clean );

		$social_raw = isset( $_POST['social_links'] ) ? wp_unslash( $_POST['social_links'] ) : '[]';
		$social_arr = json_decode( $social_raw, true );
		$clean = [];
		if ( is_array( $social_arr ) ) {
			foreach ( $social_arr as $item ) {
				$url   = esc_url_raw( trim( $item['url']   ?? '' ) );
				$icon  = sanitize_text_field( trim( $item['icon']  ?? '' ) );
				$label = sanitize_text_field( trim( $item['label'] ?? '' ) );
				if ( $url ) $clean[] = compact( 'url', 'icon', 'label' );
			}
		}
		update_option( 'alv_social_links', $clean );
		wp_send_json_success( [ 'message' => 'Kaydedildi.' ] );
	}

	/* ── Render ── */
	public static function render() {
		if ( ! current_user_can( 'manage_options' ) ) return;

		/* değerler */
		$logo_url        = get_option( 'alv_logo_url', '' );
		$logo_mobile_url = get_option( 'alv_logo_mobile_url', '' );
		$logo_text       = get_option( 'alv_logo_text', get_bloginfo('name') );
		$logo_use_image  = get_option( 'alv_logo_use_image', '1' );
		$logo_use_text   = get_option( 'alv_logo_use_text',  '0' );
		$logo_align      = get_option( 'alv_logo_align',         'left' );
		$logo_height     = get_option( 'alv_logo_height',         '60' );
		$logo_width      = get_option( 'alv_logo_width',          '0' );
		$logo_scroll_h   = get_option( 'alv_logo_scroll_height',  '38' );
		$logo_scroll_w   = get_option( 'alv_logo_scroll_width',   '0' );
		$logo_mob_align  = get_option( 'alv_logo_mobile_align',   'left' );
		$logo_mob_height = get_option( 'alv_logo_mobile_height',  '40' );
		$logo_mob_width  = get_option( 'alv_logo_mobile_width',   '0' );
		$logo_pad_top    = get_option( 'alv_logo_pad_top',    '10' );
		$logo_pad_bottom = get_option( 'alv_logo_pad_bottom', '10' );
		$logo_pad_left   = get_option( 'alv_logo_pad_left',   '20' );
		$header_img_url  = get_option( 'alv_header_image_url',  '' );
		$header_img_show = get_option( 'alv_header_image_show', '0' );
		$favicon_url        = get_option( 'alv_favicon_url',             '' );
		/* Sol/Sağ öne çıkan haber kutusu */
		$featured_spots = get_option( 'alv_featured_spots', [] );
		$footer_copy     = get_option( 'alv_footer_copyright_text', '' );
		$footer_about    = get_option( 'alv_footer_page_about',   '' );
		$footer_contact  = get_option( 'alv_footer_page_contact', '' );
		$footer_cookies  = get_option( 'alv_footer_page_cookies', '' );
		$footer_terms    = get_option( 'alv_footer_page_terms',   '' );
		$footer_extra    = get_option( 'alv_footer_extra_link',   '' );
		$footer_extra_tx = get_option( 'alv_footer_extra_text',   '' );
		$all_cats        = get_categories( [ 'taxonomy' => 'category', 'hide_empty' => false, 'number' => 300 ] );
		$social_links    = get_option( 'alv_social_links', [] );

		$col = [
			'color_header_bg'               => get_option( 'alv_color_header_bg',               '#1a2744' ),
			'color_navbar_bg'               => get_option( 'alv_color_navbar_bg',               '#1a2744' ),
			'color_menubar_bg'              => get_option( 'alv_color_menubar_bg',              '#00000038' ),
			'color_navbar_tx'               => get_option( 'alv_color_navbar_tx',               '#c8d4ee' ),
			'color_navbar_tx_hover'         => get_option( 'alv_color_navbar_tx_hover',         '#ffffff' ),
			'color_dropdown_bg'             => get_option( 'alv_color_dropdown_bg',             '#ffffff' ),
			'color_dropdown_tx'             => get_option( 'alv_color_dropdown_tx',             '#1e2640' ),
			'color_dropdown_hover_bg'       => get_option( 'alv_color_dropdown_hover_bg',       '#fdf5f4' ),
			'color_dropdown_hover_tx'       => get_option( 'alv_color_dropdown_hover_tx',       '#c0392b' ),
			'color_drawer_bg'               => get_option( 'alv_color_drawer_bg',               '#f1f3f8' ),
			'color_drawer_head'             => get_option( 'alv_color_drawer_head',             '#1a2744' ),
			'color_drawer_tx'               => get_option( 'alv_color_drawer_tx',               '#1a2744' ),
			'color_drawer_link_hover_bg'    => get_option( 'alv_color_drawer_link_hover_bg',    '#e8ebf2' ),
			'color_drawer_link_hover_tx'    => get_option( 'alv_color_drawer_link_hover_tx',    '#c0392b' ),
			'color_drawer_sub_bg'           => get_option( 'alv_color_drawer_sub_bg',           '#f1f3f8' ),
			'color_footer_bg'               => get_option( 'alv_color_footer_bg',               '#111c33' ),
			'color_footer_tx'               => get_option( 'alv_color_footer_tx',               '#8899bb' ),
			'color_footer_link'             => get_option( 'alv_color_footer_link',             '#c0392b' ),
			'color_search_bar_bg'           => get_option( 'alv_color_search_bar_bg',           '#000000' ),
			'color_search_input_bg'         => get_option( 'alv_color_search_input_bg',         'rgba(255,255,255,0.07)' ),
			'color_search_input_border'     => get_option( 'alv_color_search_input_border',     'rgba(255,255,255,0.15)' ),
			'color_search_placeholder'      => get_option( 'alv_color_search_placeholder',      'rgba(255,255,255,0.38)' ),
			'color_search_input_tx'         => get_option( 'alv_color_search_input_tx',         '#ffffff' ),
			'color_search_btn_bg'           => get_option( 'alv_color_search_btn_bg',           '#c0392b' ),
			'color_search_btn_tx'           => get_option( 'alv_color_search_btn_tx',           '#ffffff' ),
			'color_search_result_bg'        => get_option( 'alv_color_search_result_bg',        '#ffffff' ),
			'color_search_result_tx'        => get_option( 'alv_color_search_result_tx',        '#1e2640' ),
			'color_search_result_hover_bg'  => get_option( 'alv_color_search_result_hover_bg',  '#f8f9fc' ),
			'color_search_highlight'        => get_option( 'alv_color_search_highlight',        '#c0392b' ),
		];

		$tabs = [
			'genel'   => [ 'icon' => 'dashicons-admin-appearance', 'label' => 'Genel' ],
			'nav'     => [ 'icon' => 'dashicons-menu-alt',          'label' => 'Navigasyon Menü' ],
			'cats'    => [ 'icon' => 'dashicons-category',          'label' => 'Anasayfa Kategorileri' ],
			'footer'  => [ 'icon' => 'dashicons-admin-links',       'label' => 'Footer Ayarları' ],
			'renkler' => [ 'icon' => 'dashicons-art',               'label' => 'Renk Ayarları' ],
			'sosyal'  => [ 'icon' => 'dashicons-share',             'label' => 'Sosyal Medya' ],
		];
		?>
		<div class="wrap alv-wrap">
		<h1 class="alv-page-title">
			<span class="dashicons dashicons-admin-appearance" style="color:#c0392b;font-size:26px;width:26px;height:26px;margin-right:8px;"></span>
			<?php esc_html_e( 'Tema Ayarları', 'alveren' ); ?>
			<button id="alvSaveTop" type="button" class="button button-primary alv-save-btn">
				<span class="dashicons dashicons-saved"></span>
				<span class="alv-save-label"><?php esc_html_e( 'Kaydet', 'alveren' ); ?></span>
			</button>
		</h1>
		<div id="alvNotice" class="alv-notice" style="display:none;"></div>
		<div class="alv-tabs-wrap">
			<nav class="alv-tabs-nav" role="tablist">
				<?php $first = true; foreach ( $tabs as $id => $tab ) : ?>
				<button type="button" class="alv-tab-btn<?php echo $first ? ' is-active' : ''; ?>"
					data-tab="<?php echo esc_attr( $id ); ?>" role="tab"
					aria-selected="<?php echo $first ? 'true' : 'false'; ?>">
					<span class="dashicons <?php echo esc_attr( $tab['icon'] ); ?>"></span>
					<?php echo esc_html( $tab['label'] ); ?>
				</button>
				<?php $first = false; endforeach; ?>
			</nav>
			<form id="alvForm">
			<?php wp_nonce_field( 'alv_save', '_alv_nonce' ); ?>

			<?php /* ── TAB: GENEL ── */ ?>
			<div class="alv-panel is-active" data-panel="genel">

				<div class="alv-card" style="margin-bottom:20px;">
					<h2 class="alv-card-h"><span class="dashicons dashicons-format-image"></span> Header Image</h2>
					<p class="description" style="margin-bottom:12px;">Header alanının arka plan görseli. Logo bölgesinin arkasında görünür. Önerilen boyut: 1920×200 px.</p>
					<label class="alv-toggle" style="margin-bottom:14px;display:inline-flex;">
						<input type="checkbox" name="header_image_show" value="1" <?php checked( '1', $header_img_show ); ?>>
						<span class="alv-tog-track"><span class="alv-tog-thumb"></span></span>
						<span style="margin-left:8px;font-size:13px;color:#555;">Header image göster</span>
					</label><br>
					<?php self::media_field( 'header_image_url', 'headerImg', $header_img_url, 'JPG veya WebP. 1920×200 px önerilir.' ); ?>
				</div>

				<div class="alv-grid-2">

					<div class="alv-card">
						<h2 class="alv-card-h"><span class="dashicons dashicons-desktop"></span> Custom Logo</h2>
						<label class="alv-toggle" style="margin-bottom:12px;display:inline-flex;">
							<input type="checkbox" name="logo_use_image" value="1" <?php checked( '1', $logo_use_image ); ?>>
							<span class="alv-tog-track"><span class="alv-tog-thumb"></span></span>
							<span style="margin-left:8px;font-size:13px;color:#555;">Görsel logo kullan</span>
						</label><br>
						<?php self::media_field( 'logo_url', 'logoDesktop', $logo_url, 'Masaüstü navbar logosu. PNG, SVG veya WebP önerilir.' ); ?>
						<hr style="border:none;border-top:1px solid #e2e4e7;margin:14px 0 12px;">
						<h3 style="font-size:13px;font-weight:700;color:#1d2327;margin:0 0 8px;">Mobil Logo (Hamburger Drawer)</h3>
						<?php self::media_field( 'logo_mobile_url', 'logoMobile', $logo_mobile_url, 'Drawer başlığında görünür. Boş = masaüstü logo kullanılır.' ); ?>
						<div class="alv-logo-opts" style="margin-top:14px;">
							<div class="alv-opt-row">
								<label>Hizalama</label>
								<div class="alv-align-btns" data-target="logo_align">
									<button type="button" class="alv-align-btn <?php echo $logo_align==='left'?'is-active':''; ?>" data-val="left"><span class="dashicons dashicons-editor-alignleft"></span></button>
									<button type="button" class="alv-align-btn <?php echo $logo_align==='center'?'is-active':''; ?>" data-val="center"><span class="dashicons dashicons-editor-aligncenter"></span></button>
									<button type="button" class="alv-align-btn <?php echo $logo_align==='right'?'is-active':''; ?>" data-val="right"><span class="dashicons dashicons-editor-alignright"></span></button>
								</div>
								<input type="hidden" name="logo_align" id="logo_align" value="<?php echo esc_attr($logo_align); ?>">
							</div>
							<div class="alv-opt-row">
								<label>Yükseklik</label>
								<div class="alv-size-wrap">
									<input type="range" class="alv-range" min="24" max="120" step="2" id="logo_height_range" value="<?php echo esc_attr($logo_height); ?>" data-linked="logo_height">
									<input type="number" name="logo_height" id="logo_height" class="small-text" min="24" max="120" value="<?php echo esc_attr($logo_height); ?>"> px
								</div>
							</div>
							<div class="alv-opt-row">
								<label>Genişlik <small style="color:#aaa;">(0=oto)</small></label>
								<input type="number" name="logo_width" class="small-text" min="0" max="600" value="<?php echo esc_attr($logo_width); ?>"> px
							</div>
							<div class="alv-opt-row">
								<label>Scroll yükseklik</label>
								<input type="number" name="logo_scroll_height" class="small-text" min="20" max="80" value="<?php echo esc_attr($logo_scroll_h); ?>"> px
							</div>
							<div class="alv-opt-row">
								<label>Scroll genişlik <small style="color:#aaa;">(0=oto)</small></label>
								<input type="number" name="logo_scroll_width" class="small-text" min="0" max="400" value="<?php echo esc_attr($logo_scroll_w); ?>"> px
							</div>
						</div>
					</div>

					<div>
						<div class="alv-card" style="margin-bottom:16px;">
							<h2 class="alv-card-h"><span class="dashicons dashicons-editor-textcolor"></span> Text Logo</h2>
							<label class="alv-toggle" style="margin-bottom:12px;display:inline-flex;">
								<input type="checkbox" name="logo_use_text" value="1" <?php checked( '1', $logo_use_text ); ?>>
								<span class="alv-tog-track"><span class="alv-tog-thumb"></span></span>
								<span style="margin-left:8px;font-size:13px;color:#555;">Metin logo kullan</span>
							</label><br>
							<div style="margin-top:10px;">
								<label style="display:block;font-size:12.5px;color:#555;margin-bottom:4px;">Logo Metni</label>
								<input type="text" name="logo_text" class="large-text" value="<?php echo esc_attr($logo_text); ?>" placeholder="<?php echo esc_attr(get_bloginfo('name')); ?>">
							</div>
							<p class="description" style="margin-top:6px;">Görsel logo yoksa veya ek metin isteniyorsa kullanılır.</p>
						</div>

						<div class="alv-card" style="margin-bottom:16px;">
							<h2 class="alv-card-h"><span class="dashicons dashicons-move"></span> Logo Padding</h2>
							<div class="alv-opt-row">
								<label style="width:170px;">Padding Top (px)</label>
								<input type="number" name="logo_pad_top" class="small-text" min="0" max="80" value="<?php echo esc_attr($logo_pad_top); ?>">
							</div>
							<div class="alv-opt-row">
								<label style="width:170px;">Padding Bottom (px)</label>
								<input type="number" name="logo_pad_bottom" class="small-text" min="0" max="80" value="<?php echo esc_attr($logo_pad_bottom); ?>">
							</div>
							<div class="alv-opt-row">
								<label style="width:170px;">Padding Left (px)</label>
								<input type="number" name="logo_pad_left" class="small-text" min="0" max="120" value="<?php echo esc_attr($logo_pad_left); ?>">
							</div>
						</div>

						<div class="alv-card" style="margin-bottom:16px;">
							<h2 class="alv-card-h"><span class="dashicons dashicons-star-filled"></span> Custom Favicon</h2>
							<?php self::media_field( 'favicon_url', 'faviconImg', $favicon_url, '32×32 veya 64×64 px PNG / ICO / SVG. Tarayıcı sekmesinde görünür.' ); ?>
						</div>

						<!-- Öne Çıkan Haberler Bandı -->
						<div class="alv-card">
							<h2 class="alv-card-h"><span class="dashicons dashicons-megaphone"></span> Öne Çıkan Haberler Bandı</h2>
							<p class="description" style="margin-bottom:16px;">
								Header altında görünen haber bandı. URL girerek <strong>Önizlemeyi Çek</strong>'e tıklayın; başlık ve görsel otomatik alınır.
								En fazla <strong>10 haber</strong> eklenebilir.
							</p>

							<div id="alvFeaturedSpotsRepeater" class="alv-spots-repeater">
								<?php foreach ( (array) $featured_spots as $idx => $sp ) : ?>
								<div class="alv-spot-row" data-idx="<?php echo $idx; ?>">
									<div class="alv-spot-row__num"><?php echo $idx + 1; ?></div>
									<div class="alv-spot-row__fields">
										<input type="url" class="alv-spot-row__url widefat" placeholder="https://siteniz.com/haber/" value="<?php echo esc_attr($sp['url'] ?? ''); ?>" data-f="url">
										<input type="text" class="alv-spot-row__title widefat" placeholder="Başlık (boş=otomatik)" value="<?php echo esc_attr($sp['title'] ?? ''); ?>" data-f="title">
										<input type="hidden" class="alv-spot-row__img-val" value="<?php echo esc_attr($sp['img'] ?? ''); ?>" data-f="img">
									</div>
									<div class="alv-spot-row__actions">
										<button type="button" class="button alv-spot-fetch-row" title="Önizlemeyi otomatik çek">
											<span class="dashicons dashicons-update" style="font-size:14px;width:14px;height:14px;margin-top:2px;"></span>
										</button>
										<button type="button" class="button alv-spot-del-row" title="Kaldır">
											<span class="dashicons dashicons-trash" style="font-size:14px;width:14px;height:14px;margin-top:2px;color:#c0392b;"></span>
										</button>
									</div>
									<?php if ( ! empty($sp['img']) || ! empty($sp['title']) ) : ?>
									<div class="alv-spot-row__preview">
										<?php if ( ! empty($sp['img']) ) : ?>
										<img src="<?php echo esc_url($sp['img']); ?>" alt="" class="alv-spot-row__img">
										<?php endif; ?>
										<span class="alv-spot-row__ptitle"><?php echo esc_html($sp['title'] ?? ''); ?></span>
									</div>
									<?php endif; ?>
								</div>
								<?php endforeach; ?>
							</div><!-- .alv-spots-repeater -->

							<input type="hidden" id="alvFeaturedSpotsJson" name="featured_spots" value="<?php echo esc_attr( wp_json_encode( (array) $featured_spots ) ); ?>">

							<button type="button" id="alvAddSpot" class="button button-secondary"
								style="display:inline-flex;align-items:center;gap:6px;margin-top:8px;"
								data-max="10">
								<span class="dashicons dashicons-plus-alt"></span> Haber Ekle
							</button>
							<span id="alvSpotCount" style="font-size:12px;color:#888;margin-left:10px;">
								<?php echo count( (array)$featured_spots ); ?> / 10
							</span>

						</div><!-- .spot-card -->

					</div>

				</div>
			</div><!-- panel:genel -->


			<?php /* ── TAB: NAVİGASYON ── */ ?>
			<div class="alv-panel" data-panel="nav">
				<div class="alv-card" style="max-width:700px;">
					<h2 class="alv-card-h"><span class="dashicons dashicons-menu-alt"></span> Navigasyon Menü</h2>
					<p class="description" style="margin-bottom:16px;">
						Menüler <strong>Görünüm → Menüler</strong> sayfasından oluşturulup atanır.
						Atanmış menüler aşağıda görünmektedir.
					</p>
					<?php
					$assigned  = get_nav_menu_locations();
					$reg_locs  = [
						'primary' => 'Ana Menü (Desktop Navbar)',
						'mobile'  => 'Mobil Menü (Hamburger Drawer)',
					];
					?>
					<table class="widefat striped" style="margin-bottom:16px;">
						<thead><tr><th style="width:220px;">Konum</th><th>Atanmış Menü</th></tr></thead>
						<tbody>
						<?php foreach ( $reg_locs as $loc_key => $loc_label ) :
							$mid  = $assigned[ $loc_key ] ?? 0;
							$menu = $mid ? wp_get_nav_menu_object( $mid ) : null;
						?>
						<tr>
							<td><strong><?php echo esc_html( $loc_label ); ?></strong></td>
							<td>
								<?php if ( $menu ) : ?>
									<span style="color:#0a6;">&#10003; <?php echo esc_html( $menu->name ); ?></span>
								<?php else : ?>
									<span style="color:#c0392b;">&#10007; Menü atanmamış</span>
								<?php endif; ?>
							</td>
						</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<a href="<?php echo esc_url( admin_url('nav-menus.php') ); ?>" class="button button-secondary">
						<span class="dashicons dashicons-external" style="margin-top:3px;margin-right:4px;"></span>
						Görünüm → Menüler sayfasına git
					</a>
				</div>
			</div><!-- panel:nav -->


			<?php /* ── TAB: KATEGORİLER ── */ ?>
			<div class="alv-panel" data-panel="cats">
				<div class="alv-card">
					<h2 class="alv-card-h"><span class="dashicons dashicons-category"></span> Anasayfa 6 Kategori Kutusu</h2>
					<p class="description" style="margin-bottom:18px;">
						Boş bırakılan kutular otomatik doldurulur.
						İkon için FontAwesome sınıfı girin: <code>fa-book</code>, <code>fa-laptop-code</code>, <code>fa-cogs</code>…
					</p>
					<div class="alv-cat-grid">
						<?php for ( $i = 1; $i <= 6; $i++ ) :
							$cv = get_option( "alv_home_cat_$i", '' );
							$iv = get_option( "alv_home_cat_icon_$i", '' );
							$icon_class = $iv ? ( strpos($iv,'fa-') === 0 ? 'fas '.$iv : $iv ) : 'fas fa-star';
						?>
						<div class="alv-cat-box">
							<div class="alv-cat-num"><?php echo $i; ?></div>

							<label class="alv-cat-label">Kategori</label>
							<select name="home_cat_<?php echo $i; ?>" class="alv-cat-select">
								<option value=""><?php esc_html_e( '— Otomatik —', 'alveren' ); ?></option>
								<?php foreach ( $all_cats as $c ) : ?>
								<option value="<?php echo esc_attr( $c->term_id ); ?>" <?php selected( $cv, $c->term_id ); ?>>
									<?php echo ( $c->parent ? '↳ ' : '' ) . esc_html( $c->name ) . ' (' . $c->count . ')'; ?>
								</option>
								<?php endforeach; ?>
							</select>

							<label class="alv-cat-label" style="margin-top:12px;">FontAwesome İkon</label>
							<div class="alv-icon-row">
								<div class="alv-icon-prev" id="alvIconPrev_<?php echo $i; ?>">
									<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
								</div>
								<input type="text"
									name="home_cat_icon_<?php echo $i; ?>"
									class="alv-icon-inp"
									placeholder="fa-book"
									value="<?php echo esc_attr( $iv ); ?>"
									data-preview="alvIconPrev_<?php echo $i; ?>">
							</div>
							<p class="alv-icon-hint">Örn: <code>fa-book</code>, <code>fa-newspaper</code></p>

						</div>
						<?php endfor; ?>
					</div>
				</div>
			</div><!-- panel:cats -->


			<?php /* ── TAB: FOOTER ── */ ?>
			<div class="alv-panel" data-panel="footer">
				<div class="alv-card" style="max-width:700px;">
					<h2 class="alv-card-h"><span class="dashicons dashicons-admin-links"></span> Footer Ayarları</h2>
					<table class="form-table" style="background:transparent;border:none;">
						<tr>
							<th style="width:210px;"><label for="footer_copyright_text">Telif Metni</label></th>
							<td>
								<textarea name="footer_copyright_text" id="footer_copyright_text" rows="3" class="large-text"><?php echo esc_textarea( $footer_copy ); ?></textarea>
								<p class="description">HTML desteklenir. Boş = otomatik oluşturulur.</p>
							</td>
						</tr>
						<?php
						$ff = [
							'footer_page_about'   => [ 'Hakkımızda URL',         $footer_about,   'url'  ],
							'footer_page_contact' => [ 'İletişim URL',            $footer_contact, 'url'  ],
							'footer_page_cookies' => [ 'Çerez Politikası URL',    $footer_cookies, 'url'  ],
							'footer_page_terms'   => [ 'Kullanım Koşulları URL',  $footer_terms,   'url'  ],
							'footer_extra_link'   => [ 'Ekstra Link URL',          $footer_extra,   'url'  ],
							'footer_extra_text'   => [ 'Ekstra Link Metni',        $footer_extra_tx,'text' ],
						];
						foreach ( $ff as $fn => [ $fl, $fv, $ft ] ) : ?>
						<tr>
							<th><label for="<?php echo esc_attr($fn); ?>"><?php echo esc_html($fl); ?></label></th>
							<td><input type="<?php echo esc_attr($ft); ?>" name="<?php echo esc_attr($fn); ?>" id="<?php echo esc_attr($fn); ?>" class="large-text" value="<?php echo esc_attr($fv); ?>"></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</div>
			</div><!-- panel:footer -->


			<?php /* ── TAB: RENKLER ── */ ?>
			<div class="alv-panel" data-panel="renkler">
				<div class="alv-grid-2">

					<div class="alv-card">
						<h2 class="alv-card-h"><span class="dashicons dashicons-admin-appearance"></span> Header Arka Plan Rengi</h2>
						<?php self::color_row( $col, 'color_header_bg',  'Header / Navbar Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_menubar_bg', 'Menü Şeridi Arka Plan' ); ?>
					</div>

					<div class="alv-card">
						<h2 class="alv-card-h"><span class="dashicons dashicons-menu"></span> Menü Renkleri (Navbar)</h2>
						<?php self::color_row( $col, 'color_navbar_bg',         'Navbar Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_navbar_tx',         'Navbar Yazı' ); ?>
						<?php self::color_row( $col, 'color_navbar_tx_hover',   'Navbar Hover Yazı' ); ?>
						<?php self::color_row( $col, 'color_dropdown_bg',       'Dropdown Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_dropdown_tx',       'Dropdown Yazı' ); ?>
						<?php self::color_row( $col, 'color_dropdown_hover_bg', 'Dropdown Hover Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_dropdown_hover_tx', 'Dropdown Hover Yazı' ); ?>
					</div>

					<div class="alv-card">
						<h2 class="alv-card-h"><span class="dashicons dashicons-menu-alt3"></span> Hamburger Menü Renkleri (Drawer)</h2>
						<?php self::color_row( $col, 'color_drawer_bg',            'Menü Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_drawer_head',          'Başlık Bölümü' ); ?>
						<?php self::color_row( $col, 'color_drawer_tx',            'Yazı Rengi' ); ?>
						<?php self::color_row( $col, 'color_drawer_link_hover_bg', 'Link Hover Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_drawer_link_hover_tx', 'Link Hover Yazı' ); ?>
						<?php self::color_row( $col, 'color_drawer_sub_bg',        'Alt Menü Arka Plan' ); ?>
					</div>

					<div class="alv-card">
						<h2 class="alv-card-h"><span class="dashicons dashicons-admin-links"></span> Footer Renkleri</h2>
						<?php self::color_row( $col, 'color_footer_bg',   'Footer Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_footer_tx',   'Footer Yazı Rengi' ); ?>
						<?php self::color_row( $col, 'color_footer_link', 'Footer Link Rengi' ); ?>
					</div>

					<div class="alv-card">
						<h2 class="alv-card-h"><span class="dashicons dashicons-search"></span> Arama Renkleri</h2>
						<p style="font-size:11.5px;color:#888;margin:0 0 10px;">Logo üstündeki arama çubuğu (Katman 1) renk ayarları.</p>
						<?php self::color_row( $col, 'color_search_bar_bg',          'Arama Çubuğu Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_search_input_bg',        'Input Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_search_input_border',    'Input Kenarlık' ); ?>
						<?php self::color_row( $col, 'color_search_input_tx',        'Input Yazı Rengi' ); ?>
						<?php self::color_row( $col, 'color_search_placeholder',     'Placeholder Rengi' ); ?>
						<?php self::color_row( $col, 'color_search_btn_bg',          'Ara Butonu Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_search_btn_tx',          'Ara Butonu Yazı' ); ?>
						<p style="font-size:11px;color:#aaa;margin:8px 0 4px;">Sonuç kutusu:</p>
						<?php self::color_row( $col, 'color_search_result_bg',       'Sonuç Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_search_result_tx',       'Sonuç Kutusu Yazı' ); ?>
						<?php self::color_row( $col, 'color_search_result_hover_bg', 'Sonuç Hover Arka Plan' ); ?>
						<?php self::color_row( $col, 'color_search_highlight',       'Eşleşme Vurgu Rengi' ); ?>
					</div>

				</div>
			</div><!-- panel:renkler -->


			<?php /* ── TAB: SOSYAL MEDYA ── */ ?>
			<div class="alv-panel" data-panel="sosyal">
				<div class="alv-card">
					<h2 class="alv-card-h"><span class="dashicons dashicons-share"></span> Sosyal Medya Bağlantıları</h2>
					<p class="description" style="margin-bottom:6px;">
						Her satır için FontAwesome ikon sınıfı, etiket ve URL girin.
						<a href="https://fontawesome.com/search?ic=free-collection" target="_blank" rel="noopener noreferrer">FontAwesome ücretsiz ikonları →</a>
					</p>
					<p class="description" style="margin-bottom:18px;font-size:11.5px;color:#888;">
						Örnek: <code>fab fa-x-twitter</code> &nbsp; <code>fab fa-facebook-f</code> &nbsp; <code>fab fa-instagram</code> &nbsp;
						<code>fab fa-youtube</code> &nbsp; <code>fab fa-telegram</code> &nbsp; <code>fab fa-whatsapp</code>
					</p>
					<div id="alvSocialRepeater" class="alv-repeater">
						<?php foreach ( (array) $social_links as $item ) : ?>
						<div class="alv-rep-row">
							<span class="alv-rep-handle dashicons dashicons-menu" title="Sürükle"></span>
							<span class="alv-rep-icon"><i class="<?php echo esc_attr( $item['icon'] ?? '' ); ?>" style="font-size:20px;line-height:1;"></i></span>
							<div class="alv-rep-fields">
								<input type="text" class="alv-soc-icon code"   placeholder="fab fa-twitter"   value="<?php echo esc_attr( $item['icon']  ?? '' ); ?>" data-f="icon">
								<input type="text" class="alv-soc-label"       placeholder="Etiket (Twitter)" value="<?php echo esc_attr( $item['label'] ?? '' ); ?>" data-f="label">
								<input type="url"  class="alv-soc-url widefat" placeholder="https://..."      value="<?php echo esc_attr( $item['url']   ?? '' ); ?>" data-f="url">
							</div>
							<button type="button" class="button alv-rep-del" title="Kaldır"><span class="dashicons dashicons-trash"></span></button>
						</div>
						<?php endforeach; ?>
					</div>
					<input type="hidden" id="alvSocialJson" name="social_links" value="<?php echo esc_attr( wp_json_encode( (array) $social_links ) ); ?>">
					<button type="button" id="alvAddSocial" class="button button-secondary" style="display:flex;align-items:center;gap:6px;margin-top:4px;">
						<span class="dashicons dashicons-plus-alt"></span> Sosyal Medya Ekle
					</button>
				</div>
			</div><!-- panel:sosyal -->


			<div class="alv-panel-footer">
				<button id="alvSaveBottom" type="button" class="button button-primary button-hero alv-save-btn">
					<span class="dashicons dashicons-saved"></span>
					<span class="alv-save-label"><?php esc_html_e( 'Tüm Ayarları Kaydet', 'alveren' ); ?></span>
				</button>
			</div>

			</form>
		</div><!-- .alv-tabs-wrap -->
		</div><!-- .alv-wrap -->

		<style>
		.alv-wrap{max-width:100%;box-sizing:border-box;overflow-x:hidden}
		*{box-sizing:border-box}
		.alv-page-title{display:flex;align-items:center;gap:4px;font-size:22px;font-weight:700;color:#1d2327;margin:20px 0 16px;padding:0}
		.alv-page-title .alv-save-btn{margin-left:auto}
		.alv-notice{padding:12px 18px;border-radius:4px;margin-bottom:14px;font-weight:600;border-left:4px solid}
		.alv-notice.ok{background:#d4edda;border-color:#28a745;color:#155724}
		.alv-notice.err{background:#f8d7da;border-color:#dc3545;color:#721c24}
		.alv-tabs-wrap{background:#fff;border:1px solid #c3c4c7;border-radius:4px;box-shadow:0 1px 3px rgba(0,0,0,.06)}
		.alv-tabs-nav{display:flex;flex-wrap:wrap;background:#f6f7f7;border-bottom:1px solid #c3c4c7;overflow-x:auto;-webkit-overflow-scrolling:touch}
		.alv-tab-btn{display:flex;align-items:center;gap:6px;padding:11px 13px;font-size:12.5px;font-weight:500;color:#50575e;background:none;border:none;border-bottom:3px solid transparent;cursor:pointer;white-space:nowrap;transition:color .15s,border-color .15s,background .15s}
		.alv-tab-btn .dashicons{font-size:15px;width:15px;height:15px;line-height:1}
		.alv-tab-btn:hover{color:#1d2327;background:#ebebeb}
		.alv-tab-btn.is-active{color:#c0392b;border-bottom-color:#c0392b;background:#fff;font-weight:700}
		.alv-panel{display:none;padding:24px}
		.alv-panel.is-active{display:block}
		.alv-panel-footer{padding:18px 24px;border-top:1px solid #e2e4e7;background:#f9f9f9;display:flex;align-items:center}
		.alv-panel-footer .button-hero{display:inline-flex !important;align-items:center;gap:8px}
		.alv-grid-2{display:grid;grid-template-columns:repeat(auto-fill,minmax(400px,1fr));gap:20px}
		.alv-card{background:#f9f9f9;border:1px solid #e2e4e7;border-radius:6px;padding:20px}
		.alv-card-h{font-size:14px;font-weight:700;color:#1d2327;margin:0 0 18px;padding-bottom:10px;border-bottom:2px solid #c0392b;display:flex;align-items:center;gap:8px}
		.alv-card-h .dashicons{color:#c0392b}
		.alv-media-prev{width:100%;min-height:80px;border:2px dashed #c3c4c7;border-radius:4px;display:flex;align-items:center;justify-content:center;overflow:hidden;background:#f0f0f1;margin-bottom:10px}
		.alv-media-prev img{max-width:100%;max-height:120px;object-fit:contain;display:block}
		.alv-media-acts{display:flex;gap:8px;margin-bottom:8px}
		.alv-media-acts .button{display:inline-flex !important;align-items:center;gap:4px}
		.alv-color-r{display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid #eee;flex-wrap:wrap;min-width:0}
		.alv-color-r:last-child{border-bottom:none}
		.alv-color-r label{flex:1;min-width:120px;font-size:13px;color:#3c434a;word-break:break-word}
		.alv-color-r .wp-picker-container{flex-shrink:0;max-width:100%}
		.alv-color-r .wp-color-result{max-width:100px !important}
		.alv-repeater{display:flex;flex-direction:column;gap:8px;margin-bottom:14px}
		.alv-rep-row{display:flex;align-items:center;gap:8px;background:#fff;border:1px solid #ddd;border-radius:4px;padding:10px 12px;min-width:0;overflow:hidden;flex-wrap:wrap}
		.alv-rep-handle{cursor:grab;color:#aaa;flex-shrink:0}
		.alv-rep-icon{width:32px;height:32px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:20px;color:#555;background:#f0f0f1;border-radius:4px}
		.alv-rep-fields{display:flex;flex:1;flex-wrap:wrap;gap:6px}
		.alv-soc-icon{font-family:monospace !important;max-width:180px}
		.alv-soc-label{max-width:140px}
		.alv-soc-url{min-width:200px;flex:1}
		.alv-rep-del{flex-shrink:0;color:#c0392b !important;border-color:#c0392b !important;padding:4px 8px !important}
		/* ── KATEGORİ GRİD ── */
		.alv-cat-grid{display:grid;grid-template-columns:repeat(3,minmax(260px,1fr));gap:20px;width:100%}
		.alv-cat-box{background:#fff;border:1px solid #dde0e8;border-radius:8px;padding:22px 16px 16px;position:relative;box-shadow:0 1px 4px rgba(0,0,0,.04);transition:box-shadow .15s}
		.alv-cat-box:hover{box-shadow:0 3px 12px rgba(0,0,0,.1)}
		.alv-cat-num{position:absolute;top:-12px;left:14px;background:#c0392b;color:#fff;font-size:11px;font-weight:800;width:24px;height:24px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 6px rgba(192,57,43,.4)}
		.alv-cat-label{display:block;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#888;margin-bottom:5px}
		.alv-cat-select{display:block;width:100%;padding:7px 10px;border:1px solid #c3c4c7;border-radius:4px;font-size:13px;color:#1d2327;background:#f9f9f9;cursor:pointer;outline:none;transition:border-color .15s}
		.alv-cat-select:focus{border-color:#c0392b;background:#fff}
		.alv-icon-row{display:flex;align-items:center;gap:10px;margin-top:4px}
		.alv-icon-prev{width:40px;height:40px;flex-shrink:0;border-radius:6px;background:linear-gradient(135deg,#1a2744,#2e3f6e);display:flex;align-items:center;justify-content:center;font-size:18px;color:#fff;box-shadow:0 2px 8px rgba(26,39,68,.25);transition:transform .15s}
		.alv-icon-prev:hover{transform:scale(1.08)}
		.alv-icon-inp{flex:1;min-width:0;padding:7px 10px;border:1px solid #c3c4c7;border-radius:4px;font-size:13px;font-family:monospace;color:#1d2327;background:#f9f9f9;transition:border-color .15s}
		.alv-icon-inp:focus{border-color:#c0392b;background:#fff;outline:none}
		.alv-icon-hint{margin:6px 0 0;font-size:11px;color:#aaa}
		.alv-icon-hint code{background:#f0f0f1;padding:1px 5px;border-radius:3px;font-size:11px}
		.alv-logo-opts{border-top:1px solid #e2e4e7;padding-top:12px}
		.alv-opt-row{display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap}
		.alv-opt-row label{width:110px;font-size:12.5px;color:#555;flex-shrink:0}
		.alv-align-btns{display:flex;gap:4px}
		.alv-align-btn{width:30px;height:30px;border:1px solid #c3c4c7;border-radius:3px;background:#f6f7f7;cursor:pointer;display:flex;align-items:center;justify-content:center;padding:0;transition:all .15s}
		.alv-align-btn .dashicons{font-size:16px;width:16px;height:16px}
		.alv-align-btn:hover{background:#ebebeb;border-color:#999}
		.alv-align-btn.is-active{background:#c0392b;border-color:#c0392b;color:#fff}
		.alv-align-btn.is-active .dashicons{color:#fff}
		.alv-size-wrap{display:flex;align-items:center;gap:8px}
		.alv-range{width:130px;cursor:pointer}
		.alv-toggle{display:inline-flex;align-items:center;cursor:pointer}
		.alv-toggle input{position:absolute;opacity:0;width:0;height:0}
		.alv-tog-track{width:44px;height:24px;background:#ccc;border-radius:12px;position:relative;transition:.2s;flex-shrink:0}
		.alv-tog-thumb{position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;background:#fff;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.25)}
		.alv-toggle input:checked~.alv-tog-track{background:#c0392b}
		.alv-toggle input:checked~.alv-tog-track .alv-tog-thumb{transform:translateX(20px)}
		.alv-save-btn{display:inline-flex !important;align-items:center;gap:6px}
		@media(max-width:960px){.alv-grid-2{grid-template-columns:1fr}.alv-cat-grid{grid-template-columns:repeat(2,minmax(0,1fr))} .alv-cat-select{font-size:12px}.alv-tabs-nav{overflow-x:auto;flex-wrap:nowrap}.alv-card[style*="max-width"]{max-width:100% !important}}
		@media(max-width:782px){.alv-wrap{padding:0 10px}.alv-tabs-wrap{overflow-x:hidden}.form-table th{width:auto !important;display:block;padding-bottom:4px}.form-table td{display:block;padding-top:0}}
		/* ── SPOT ÖNIZLEME ── */
		/* ── SPOT REPEATER ── */
		.alv-spots-repeater{display:flex;flex-direction:column;gap:8px;margin-bottom:12px}
		.alv-spot-row{display:grid;grid-template-columns:28px 1fr 64px;align-items:start;gap:8px;background:#fff;border:1px solid #dde0e8;border-radius:6px;padding:10px 12px}
		.alv-spot-row__num{width:24px;height:24px;background:#c0392b;color:#fff;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;flex-shrink:0;margin-top:4px}
		.alv-spot-row__preview{display:flex;align-items:center;gap:8px;background:#f0f4ff;border:1px solid #c8d4ee;border-radius:5px;padding:6px 8px;margin-bottom:6px;grid-column:1/-1;margin-top:4px}
		.alv-spot-row__img{width:50px;height:38px;object-fit:cover;border-radius:3px;flex-shrink:0;border:1px solid #dde0e8}
		.alv-spot-row__ptitle{font-size:12px;font-weight:600;color:#1a2744;line-height:1.3;flex:1;min-width:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden}
		.alv-spot-row__fields{display:flex;flex-direction:column;gap:5px}
		.alv-spot-row__fields input{font-size:12.5px}
		.alv-spot-row__actions{display:flex;flex-direction:column;gap:4px;padding-top:2px}
		.alv-spot-row__actions .button{padding:4px 8px!important;min-height:0}
		@media(max-width:600px){.alv-cat-grid{grid-template-columns:1fr} .alv-icon-row{flex-wrap:wrap}.alv-rep-fields{flex-direction:column}.alv-soc-icon,.alv-soc-label,.alv-soc-url{max-width:none;width:100%}.alv-opt-row label{width:auto}.alv-size-wrap{flex-wrap:wrap}.alv-range{width:100%}.alv-color-r{flex-direction:column;align-items:flex-start}}
		</style>
		<?php
	}

	private static function media_field( $name, $id, $val, $desc = '' ) { ?>
		<div class="alv-media-prev" id="<?php echo esc_attr( $id.'Preview' ); ?>">
			<?php if ( $val ) : ?><img src="<?php echo esc_url( $val ); ?>" alt=""><?php endif; ?>
		</div>
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id.'Url' ); ?>" value="<?php echo esc_attr( $val ); ?>">
		<div class="alv-media-acts">
			<button type="button" class="button alv-pick-img"
				data-target="<?php echo esc_attr( $id.'Url' ); ?>"
				data-preview="<?php echo esc_attr( $id.'Preview' ); ?>">
				<span class="dashicons dashicons-upload"></span> Görsel Seç
			</button>
			<button type="button" class="button alv-del-img"
				data-target="<?php echo esc_attr( $id.'Url' ); ?>"
				data-preview="<?php echo esc_attr( $id.'Preview' ); ?>"
				<?php echo ! $val ? 'style="display:none"' : ''; ?>>
				<span class="dashicons dashicons-trash"></span>
			</button>
		</div>
		<?php if ( $desc ) : ?><p class="description"><?php echo esc_html( $desc ); ?></p><?php endif;
	}

	private static function color_row( $col, $key, $label ) {
		$val = $col[ $key ] ?? '#ffffff'; ?>
		<div class="alv-color-r">
			<label><?php echo esc_html( $label ); ?></label>
			<input type="text" name="<?php echo esc_attr( $key ); ?>" class="alv-cp" value="<?php echo esc_attr( $val ); ?>">
		</div>
		<?php
	}

	/* ── Yardımcı: Spot Önizleme HTML (admin paneli için) ── */
	private static function spot_preview_html( $url, $custom_title = '' ) {
		if ( ! $url ) return;
		$post_id  = url_to_postid( $url );
		$title    = $custom_title;
		$thumb    = '';
		if ( $post_id ) {
			if ( ! $title ) $title = get_the_title( $post_id );
			$thumb_id = get_post_thumbnail_id( $post_id );
			if ( $thumb_id ) {
				$img  = wp_get_attachment_image_src( $thumb_id, 'thumbnail' );
				if ( $img ) $thumb = $img[0];
			}
		}
		if ( ! $title ) {
			// URL'den fallback başlık
			$parts = explode( '/', rtrim( $url, '/' ) );
			$title = str_replace( [ '-', '_' ], ' ', end( $parts ) );
			$title = ucwords( $title );
		}
		?>
		<div class="alv-spot-prev-box">
			<?php if ( $thumb ) : ?>
			<img src="<?php echo esc_url( $thumb ); ?>" alt="" class="alv-spot-prev-img">
			<?php endif; ?>
			<span class="alv-spot-prev-title"><?php echo esc_html( $title ); ?></span>
			<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="alv-spot-prev-link">
				<span class="dashicons dashicons-external" style="font-size:12px;width:12px;height:12px;"></span>
			</a>
		</div>
		<?php
	}
}

Alveren_Admin_Settings::init();
