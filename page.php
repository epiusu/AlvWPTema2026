<?php
/**
 * WP Alveren — page.php
 * Statik sayfa şablonu.
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );
?>

<div class="<?php echo alveren_content_class(); ?>">
<main id="main" class="site-main" role="main">

	<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class('alv-page-article'); ?>>

		<header style="margin-bottom:24px;padding-bottom:16px;border-bottom:2px solid var(--alv-border);">
			<?php alveren_breadcrumb(); ?>
			<h1 style="font-family:var(--alv-font-head);font-size:clamp(22px,3.5vw,30px);font-weight:800;color:var(--alv-navy);margin:0;letter-spacing:-.025em;">
				<?php the_title(); ?>
			</h1>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
		<div class="alv-featured-image" style="margin-bottom:28px;">
			<?php the_post_thumbnail('alv-hero', ['alt' => get_the_title()]); ?>
		</div>
		<?php endif; ?>

		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages(['before'=>'<nav class="alv-page-links">','after'=>'</nav>']); ?>
		</div>

	</article>

	<?php endwhile; ?>

</main>
</div>

<?php get_footer(); ?>
