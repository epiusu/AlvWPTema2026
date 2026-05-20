<?php
/**
 * WP Alveren — category.php
 * Kategori sayfası yönlendiricisi.
 * Üst kategori → category-parent şablonu
 * Alt kategori veya alt kategorisiz → category-child şablonu
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );

$cat_id         = get_queried_object_id();
$cat            = get_queried_object();
$sub_categories = get_categories([
	'taxonomy'   => 'category',
	'parent'     => $cat->term_id,
	'hide_empty' => false,
	'number'     => 0,
]);
?>

<div class="<?php echo alveren_content_class(); ?>">
	<main id="main" class="site-main" role="main">

		<?php if ( $cat->parent == 0 && ! empty( $sub_categories ) ) : ?>
			<?php get_template_part('category-templates/category', 'parent'); ?>
		<?php else : ?>
			<?php get_template_part('category-templates/category', 'child'); ?>
		<?php endif; ?>

	</main>
</div>

<?php get_footer(); ?>
