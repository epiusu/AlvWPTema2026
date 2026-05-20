<?php
/**
 * WP Alveren — template-parts/content-nav.php
 * Önceki / Sonraki makale navigasyonu.
 *
 * @package WPAlveren
 */

$prev = get_previous_post();
$next = get_next_post();

if ( ! $prev && ! $next ) return;
?>

<nav class="alv-post-nav" aria-label="<?php esc_attr_e('Makale Navigasyonu','alveren'); ?>">

	<?php if ( $prev ) : ?>
	<a href="<?php echo esc_url( get_permalink($prev) ); ?>" class="alv-post-nav__item alv-post-nav__item--prev" rel="prev">
		<span class="alv-post-nav__dir"><i class="fas fa-arrow-left" aria-hidden="true"></i> Önceki Makale</span>
		<span class="alv-post-nav__title"><?php echo esc_html( get_the_title($prev) ); ?></span>
	</a>
	<?php else : ?>
	<span></span>
	<?php endif; ?>

	<?php if ( $next ) : ?>
	<a href="<?php echo esc_url( get_permalink($next) ); ?>" class="alv-post-nav__item alv-post-nav__item--next" rel="next">
		<span class="alv-post-nav__dir">Sonraki Makale <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
		<span class="alv-post-nav__title"><?php echo esc_html( get_the_title($next) ); ?></span>
	</a>
	<?php endif; ?>

</nav>
