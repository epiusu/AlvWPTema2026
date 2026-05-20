<?php
/**
 * WP Alveren — footer.php
 * Temaya uygun tam footer.
 *
 * @package WPAlveren
 */

$alv_site_name = get_bloginfo('name');
$alv_site_url  = esc_url( home_url('/') );
$alv_year      = esc_html( wp_date('Y') );

/* Sayfa linkleri — Customizer öncelikli, otomatik fallback */
/* Tema Ayarları > Footer'dan oku (get_option birincil, get_theme_mod fallback) */
$alv_footer_about   = trim( get_option( 'alv_footer_page_about',   get_theme_mod('alv_footer_page_about',   '') ) );
$alv_footer_contact = trim( get_option( 'alv_footer_page_contact', get_theme_mod('alv_footer_page_contact', '') ) );
$alv_footer_terms   = trim( get_option( 'alv_footer_page_terms',   get_theme_mod('alv_footer_page_terms',   '') ) );
$alv_footer_cookies = trim( get_option( 'alv_footer_page_cookies', get_theme_mod('alv_footer_page_cookies', '') ) );
/* Telif metni */
$alv_footer_copy    = get_option( 'alv_footer_copyright_text', '' );

if ( ! $alv_footer_about ) {
	$p = get_page_by_path('hakkimizda') ?: get_page_by_path('hakkinda') ?: get_page_by_path('about');
	if ( $p ) $alv_footer_about = get_permalink($p);
}
if ( ! $alv_footer_contact ) {
	$p = get_page_by_path('iletisim') ?: get_page_by_path('iletişim') ?: get_page_by_path('contact');
	if ( $p ) $alv_footer_contact = get_permalink($p);
}
if ( ! $alv_footer_terms ) {
	$p = get_page_by_path('kullanim-kosullari') ?: get_page_by_path('kullanim-ve-gizlilik-kosullari') ?: get_page_by_path('terms');
	if ( $p ) $alv_footer_terms = get_permalink($p);
}
if ( ! $alv_footer_cookies ) {
	$p = get_page_by_path('cerez-politikasi') ?: get_page_by_path('cookies') ?: get_page_by_path('gizlilik');
	if ( $p ) $alv_footer_cookies = get_permalink($p);
}
?>
		</div><!-- .row -->
	</div><!-- .container -->
</div><!-- #content -->

<footer class="alv-footer" role="contentinfo">
	<div class="alv-footer__inner">

		<!-- Sol: Copyright -->
		<div class="alv-footer__copy">
			<?php if ( $alv_footer_copy ) : ?>
				<?php echo wp_kses_post( $alv_footer_copy ); ?>
			<?php else : ?>
				<i class="fa fa-copyright" aria-hidden="true"></i>
				<span><?php echo $alv_year; ?></span>
				<a href="<?php echo $alv_site_url; ?>" class="alv-footer__site-name">
					<?php echo esc_html( $alv_site_name ); ?>
				</a>
				<span class="alv-footer__rights"><?php esc_html_e( '— Tüm hakları saklıdır.', 'alveren' ); ?></span>
			<?php endif; ?>
		</div>

		<!-- Sağ: Linkler -->
		<nav class="alv-footer__links" aria-label="<?php esc_attr_e( 'Footer Navigasyon', 'alveren' ); ?>">

			<?php if ( $alv_footer_about ) : ?>
			<a href="<?php echo esc_url( $alv_footer_about ); ?>">
				<i class="fas fa-info-circle" aria-hidden="true"></i>
				<?php esc_html_e( 'Hakkımızda', 'alveren' ); ?>
			</a>
			<span class="alv-footer__sep" aria-hidden="true">|</span>
			<?php endif; ?>

			<?php if ( $alv_footer_contact ) : ?>
			<a href="<?php echo esc_url( $alv_footer_contact ); ?>">
				<i class="fas fa-envelope" aria-hidden="true"></i>
				<?php esc_html_e( 'İletişim', 'alveren' ); ?>
			</a>
			<span class="alv-footer__sep" aria-hidden="true">|</span>
			<?php endif; ?>

			<?php if ( $alv_footer_terms ) : ?>
			<a href="<?php echo esc_url( $alv_footer_terms ); ?>">
				<i class="fas fa-file-contract" aria-hidden="true"></i>
				<?php esc_html_e( 'Kullanım Koşulları', 'alveren' ); ?>
			</a>
			<span class="alv-footer__sep" aria-hidden="true">|</span>
			<?php endif; ?>

			<?php if ( $alv_footer_cookies ) : ?>
			<a href="<?php echo esc_url( $alv_footer_cookies ); ?>">
				<i class="fas fa-cookie-bite" aria-hidden="true"></i>
				<?php esc_html_e( 'Çerezler', 'alveren' ); ?>
			</a>
			<?php elseif ( class_exists('FastCMP') || defined('FASTCMP_VERSION') ) : ?>
			<button class="alv-footer__cookie-btn" onclick="window.FastCMP && window.FastCMP.open();" type="button">
				<i class="fas fa-cookie-bite" aria-hidden="true"></i>
				<?php esc_html_e( 'Çerezler', 'alveren' ); ?>
			</button>
			<?php endif; ?>

		</nav>

	</div>
</footer>

<button id="alvBackToTop" class="alv-back-to-top" aria-label="<?php esc_attr_e('Yukarı çık', 'alveren'); ?>">
	<i class="fas fa-chevron-up" aria-hidden="true"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
