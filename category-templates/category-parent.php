<?php
/**
 * WP Alveren — category-templates/category-parent.php
 * Ana (üst) kategori: hero başlık + alt kategori kart grid'i.
 *
 * @package WPAlveren
 */

$cat_id         = get_queried_object_id();
$cat            = get_queried_object();
$sub_categories = get_categories([
	'taxonomy'   => 'category',
	'parent'     => $cat->term_id,
	'hide_empty' => false,
	'number'     => 0,
	'orderby'    => 'name',
	'order'      => 'ASC',
]);
$pcat_totals    = alveren_cat_total( $cat_id );
$term_desc      = term_description();
?>

<!-- Hero -->
<div class="alv-hero">
	<div class="alv-hero__icon"><i class="fas fa-layer-group" aria-hidden="true"></i></div>
	<div class="alv-hero__body">
		<?php alveren_breadcrumb(); ?>
		<h1 class="alv-hero__title"><?php single_cat_title(); ?></h1>
		<div class="alv-hero__meta">
			<span><span class="alv-hero__meta-badge"><?php echo (int)$pcat_totals; ?></span> içerik</span>
			<span style="color:#556;"><i class="fas fa-sitemap" style="color:var(--alv-red);margin-right:4px;" aria-hidden="true"></i><?php echo count($sub_categories); ?> alt kategori</span>
		</div>
		<?php if ( $term_desc ) : ?>
		<p class="alv-hero__desc"><?php echo wp_strip_all_tags($term_desc); ?></p>
		<?php endif; ?>
	</div>
</div>

<!-- Alt kategori kart grid'i -->
<div class="row g-4">
<?php foreach ( $sub_categories as $scat ) :
	$sterm_link  = esc_url( get_category_link($scat) );
	$scat_totals = alveren_cat_total( (int)$scat->term_id );

	/* Kartta gösterilecek içerikler: önce alt kategorileri, yoksa makaleleri */
	$scat_children = get_categories([
		'taxonomy'   => 'category',
		'parent'     => $scat->term_id,
		'hide_empty' => false,
		'number'     => 6,
	]);

	$scat_posts = null;
	if ( empty($scat_children) ) {
		$scat_posts = new WP_Query([
			'cat'            => $scat->term_id,
			'posts_per_page' => 6,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		]);
	}
?>
<div class="col-lg-4 col-md-6">
	<div class="alv-cat-card">

		<h2 class="alv-cat-card__title">
			<a href="<?php echo $sterm_link; ?>"><?php echo esc_html($scat->name); ?></a>
			<span class="alv-cat-card__count"><?php echo (int)$scat_totals; ?></span>
		</h2>

		<ul class="alv-cat-card__list">
			<?php if ( ! empty($scat_children) ) :
				foreach ( $scat_children as $child ) :
					$child_total = alveren_cat_total( (int)$child->term_id );
			?>
				<li>
					<a href="<?php echo esc_url( get_category_link($child) ); ?>">
						<i class="fas fa-chevron-right alv-list-icon" aria-hidden="true"></i>
						<?php echo esc_html($child->name); ?>
						<span class="alv-badge"><?php echo (int)$child_total; ?></span>
					</a>
				</li>
			<?php endforeach;
			elseif ( $scat_posts && $scat_posts->have_posts() ) :
				while ( $scat_posts->have_posts() ) : $scat_posts->the_post(); ?>
				<li>
					<a href="<?php the_permalink(); ?>">
						<i class="fas fa-chevron-right alv-list-icon" aria-hidden="true"></i>
						<?php the_title(); ?>
					</a>
				</li>
			<?php endwhile; wp_reset_postdata();
			else : ?>
				<li style="padding:8px 4px;font-size:13px;color:var(--alv-gray-400);"><?php esc_html_e('Henüz içerik yok.','alveren'); ?></li>
			<?php endif; ?>
		</ul>

	</div>
</div>
<?php endforeach; ?>
</div>
