<?php
/**
 * WP Alveren — index.php
 * Genel fallback şablonu. WP template hierarchy'de eşleşme olmadığında çalışır.
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );
?>

<div class="<?php echo alveren_content_class(); ?>">
	<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<div class="alv-archive-header" style="margin-bottom:24px;">
				<?php if ( is_home() && ! is_front_page() ) : ?>
					<h1 class="alv-archive-title"><?php single_post_title(); ?></h1>
				<?php elseif ( is_archive() ) : ?>
					<?php the_archive_title('<h1 class="alv-archive-title">', '</h1>'); ?>
					<?php the_archive_description('<div class="alv-archive-desc">', '</div>'); ?>
				<?php endif; ?>
			</div>

			<ul class="alv-article-list">
				<?php while ( have_posts() ) : the_post(); ?>
				<li class="alv-article-list__item">
					<a href="<?php the_permalink(); ?>" class="alv-article-list__link">
						<span class="alv-article-list__icon"><i class="fas fa-file-alt" aria-hidden="true"></i></span>
						<span class="alv-article-list__title"><?php the_title(); ?></span>
						<span class="alv-article-list__date"><?php echo get_the_date('d.m.Y'); ?></span>
					</a>
				</li>
				<?php endwhile; ?>
			</ul>

			<?php alveren_pagination(); ?>

		<?php else : ?>
			<?php get_template_part('template-parts/no-results'); ?>
		<?php endif; ?>

	</main>
</div>

<?php get_footer(); ?>
