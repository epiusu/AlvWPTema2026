<?php
/**
 * WP Alveren — single.php
 * Tekil makale şablonu.
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );
?>

<div class="<?php echo alveren_content_class(); ?>">
	<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part('template-parts/content', 'single'); ?>

			<!-- Önceki / Sonraki -->
			<?php get_template_part('template-parts/content', 'nav'); ?>

			<!-- Yorumlar -->
			<?php
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
			?>

		<?php endwhile; ?>

	</main>
</div>

<?php get_footer(); ?>
