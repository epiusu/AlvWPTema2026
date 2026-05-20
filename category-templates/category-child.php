<?php
/**
 * WP Alveren — category-templates/category-child.php
 * Alt kategori makale listesi.
 *
 * @package WPAlveren
 */

global $wp_query;

$cat_id      = get_queried_object_id();
$cat         = get_queried_object();
$cat_totals  = alveren_cat_total( $cat_id );
$term_desc   = term_description();
$current_p   = max(1, get_query_var('paged'));
$total_p     = $wp_query->max_num_pages;
?>

<!-- Hero -->
<div class="alv-hero">
	<div class="alv-hero__icon"><i class="fas fa-folder-open" aria-hidden="true"></i></div>
	<div class="alv-hero__body">
		<?php alveren_breadcrumb(); ?>
		<h1 class="alv-hero__title"><?php single_cat_title(); ?></h1>
		<div class="alv-hero__meta">
			<span><span class="alv-hero__meta-badge"><?php echo (int)$cat_totals; ?></span> makale</span>
			<?php if ( $total_p > 1 ) : ?>
			<span style="color:#556;">
				<i class="fas fa-book-open" style="color:var(--alv-red);margin-right:4px;" aria-hidden="true"></i>
				Sayfa <?php echo $current_p; ?> / <?php echo $total_p; ?>
			</span>
			<?php endif; ?>
		</div>
		<?php if ( $term_desc ) : ?>
		<p class="alv-hero__desc"><?php echo wp_strip_all_tags($term_desc); ?></p>
		<?php endif; ?>
	</div>
</div>

<?php if ( have_posts() ) : ?>

	<ul class="alv-article-list" role="list">
		<?php while ( have_posts() ) : the_post(); ?>
		<li class="alv-article-list__item" role="listitem">
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


