<?php
/**
 * WP Alveren — 404.php
 *
 * @package WPAlveren
 */

get_header();

$recent = new WP_Query([
	'posts_per_page' => 8,
	'post_status'    => 'publish',
	'no_found_rows'  => true,
]);

$main_cats = get_categories([
	'taxonomy'   => 'category',
	'parent'     => 0,
	'hide_empty' => true,
	'number'     => 12,
]);
?>

<div class="<?php echo alveren_content_class(); ?>">
<main id="main" class="site-main" role="main">

	<!-- 404 Hero -->
	<div style="text-align:center;padding:52px 20px 36px;">
		<p class="alv-404-code" aria-hidden="true">404</p>
		<h1 style="font-family:var(--alv-font-head);font-size:clamp(20px,3vw,26px);font-weight:700;color:var(--alv-navy);margin:0 0 10px;">
			<?php esc_html_e('Sayfa Bulunamadı','alveren'); ?>
		</h1>
		<p style="font-size:15px;color:var(--alv-gray-500);margin:0 0 28px;max-width:400px;margin-left:auto;margin-right:auto;">
			<?php esc_html_e('Aradığınız sayfa taşınmış, silinmiş veya hiç olmamış olabilir.','alveren'); ?>
		</p>

		<!-- Arama -->
		<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"
			  style="display:flex;max-width:480px;margin:0 auto 20px;height:48px;border:1.5px solid var(--alv-border-dk);border-radius:var(--alv-radius);overflow:hidden;box-shadow:var(--alv-shadow);">
			<input type="search" name="s" value="<?php echo get_search_query(); ?>"
				   placeholder="<?php esc_attr_e('Arama yapın…','alveren'); ?>"
				   style="flex:1;border:none;padding:0 16px;font-size:14px;outline:none;min-width:0;background:var(--alv-white);">
			<button type="submit" style="background:var(--alv-red);color:#fff;border:none;padding:0 22px;font-size:14px;cursor:pointer;transition:background var(--alv-transition);">
				<i class="fas fa-search" aria-hidden="true"></i>
			</button>
		</form>

		<a href="<?php echo esc_url(home_url('/')); ?>"
		   style="display:inline-flex;align-items:center;gap:8px;background:var(--alv-navy);color:#fff;text-decoration:none;padding:10px 24px;border-radius:var(--alv-radius-sm);font-size:14px;font-weight:600;transition:background var(--alv-transition);">
			<i class="fas fa-home" aria-hidden="true"></i>
			<?php esc_html_e('Anasayfaya Dön','alveren'); ?>
		</a>
	</div>

	<!-- Paneller -->
	<div class="row g-4" style="text-align:left;">

		<!-- Son makaleler -->
		<div class="col-md-6">
			<div class="alv-404-panel">
				<div class="alv-404-panel__head">
					<i class="fas fa-clock" aria-hidden="true"></i>
					<?php esc_html_e('Son Makaleler','alveren'); ?>
				</div>
				<ul style="list-style:none;margin:0;padding:0;">
					<?php if ( $recent->have_posts() ) : while ( $recent->have_posts() ) : $recent->the_post(); ?>
					<li style="border-bottom:1px solid var(--alv-gray-100);">
						<a href="<?php the_permalink(); ?>"
						   style="display:flex;align-items:center;gap:9px;padding:10px 18px;text-decoration:none;color:var(--alv-gray-800);font-size:13.5px;transition:color var(--alv-transition),padding-left var(--alv-transition);"
						   onmouseover="this.style.color='var(--alv-red)';this.style.paddingLeft='24px';"
						   onmouseout="this.style.color='var(--alv-gray-800)';this.style.paddingLeft='18px';">
							<i class="fas fa-chevron-right" style="color:var(--alv-gray-300);font-size:10px;" aria-hidden="true"></i>
							<?php the_title(); ?>
						</a>
					</li>
					<?php endwhile; wp_reset_postdata(); endif; ?>
				</ul>
			</div>
		</div>

		<!-- Kategoriler -->
		<div class="col-md-6">
			<div class="alv-404-panel">
				<div class="alv-404-panel__head">
					<i class="fas fa-folder-open" aria-hidden="true"></i>
					<?php esc_html_e('Kategoriler','alveren'); ?>
				</div>
				<ul style="list-style:none;margin:0;padding:0;">
					<?php foreach ( $main_cats as $mcat ) : ?>
					<li style="border-bottom:1px solid var(--alv-gray-100);">
						<a href="<?php echo esc_url(get_category_link($mcat->term_id)); ?>"
						   style="display:flex;align-items:center;gap:9px;padding:10px 18px;text-decoration:none;color:var(--alv-gray-800);font-size:13.5px;transition:color var(--alv-transition),padding-left var(--alv-transition);"
						   onmouseover="this.style.color='var(--alv-red)';this.style.paddingLeft='24px';"
						   onmouseout="this.style.color='var(--alv-gray-800)';this.style.paddingLeft='18px';">
							<i class="fas fa-chevron-right" style="color:var(--alv-gray-300);font-size:10px;" aria-hidden="true"></i>
							<?php echo esc_html($mcat->name); ?>
							<span style="margin-left:auto;background:var(--alv-gray-100);color:var(--alv-gray-500);font-size:11px;font-weight:600;padding:2px 8px;border-radius:10px;">
								<?php echo (int)alveren_cat_total($mcat->term_id); ?>
							</span>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

	</div>

</main>
</div>

<?php get_footer(); ?>
