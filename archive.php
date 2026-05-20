<?php
/**
 * WP Alveren — archive.php
 * Tarih/yazar arşiv şablonu.
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );
?>

<div class="<?php echo alveren_content_class(); ?>">
<main id="main" class="site-main" role="main">

	<!-- Arşiv Hero -->
	<div class="alv-hero" style="margin-bottom:24px;">
		<div class="alv-hero__icon">
			<i class="fas <?php echo is_author() ? 'fa-user' : 'fa-calendar-alt'; ?>" aria-hidden="true"></i>
		</div>
		<div class="alv-hero__body">
			<?php the_archive_title('<h1 class="alv-hero__title" style="margin-bottom:0;">', '</h1>'); ?>
			<?php the_archive_description('<p class="alv-hero__desc">', '</p>'); ?>
		</div>
	</div>

	<?php if ( have_posts() ) : ?>

		<ul class="alv-article-list" role="list">
			<?php while ( have_posts() ) : the_post(); ?>
			<li class="alv-article-list__item">
				<a href="<?php the_permalink(); ?>" class="alv-article-list__link">
					<span class="alv-article-list__icon" aria-hidden="true"><i class="fas fa-file-alt"></i></span>
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
