<?php
/**
 * WP Alveren — Öne Çıkan Haberler Bandı
 * Header'ın hemen altında gösterilir.
 * template-ussubilgi.php ve single.php tarafından include edilir.
 *
 * @package WPAlveren
 */

$alv_spots = get_option( 'alv_featured_spots', [] );
if ( empty( $alv_spots ) ) return;
?>
<div class="alv-spots-band" role="complementary" aria-label="<?php esc_attr_e( 'Öne Çıkan Haberler', 'alveren' ); ?>">
	<div class="alv-spots-band__inner">
		<?php foreach ( $alv_spots as $spot ) :
			if ( empty( $spot['url'] ) ) continue;
			$title = $spot['title'] ?? '';
			$img   = $spot['img']   ?? '';
			if ( ! $title ) {
				$pid = url_to_postid( $spot['url'] );
				if ( $pid ) $title = get_the_title( $pid );
			}
		?>
		<a href="<?php echo esc_url( $spot['url'] ); ?>" class="alv-spots-band__item">
			<?php if ( $img ) : ?>
			<img src="<?php echo esc_url( $img ); ?>" alt="" class="alv-spots-band__img" loading="lazy">
			<?php else : ?>
			<span class="alv-spots-band__dot" aria-hidden="true"></span>
			<?php endif; ?>
			<span class="alv-spots-band__title"><?php echo esc_html( $title ); ?></span>
		</a>
		<?php endforeach; ?>
	</div>
</div>
