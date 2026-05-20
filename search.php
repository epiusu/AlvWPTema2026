<?php
/**
 * WP Alveren — search.php
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );
// FIX: global $wp_query açık olarak belirtildi
global $wp_query;
$query_str = get_search_query();
?>

<div class="<?php echo alveren_content_class(); ?>">
<main id="main" class="site-main" role="main">

	<!-- Arama Hero -->
	<div class="alv-hero" style="margin-bottom:24px;">
		<div class="alv-hero__icon"><i class="fas fa-search" aria-hidden="true"></i></div>
		<div class="alv-hero__body">
			<p style="font-size:11.5px;color:#556;text-transform:uppercase;letter-spacing:.08em;margin:0 0 6px;">
				<?php esc_html_e('Arama Sonuçları','alveren'); ?>
			</p>
			<h1 class="alv-hero__title">
				"<span style="color:var(--alv-red);"><?php echo esc_html($query_str); ?></span>"
			</h1>
			<div style="position:relative;max-width:440px;margin-top:12px;">
				<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"
					  style="display:flex;height:38px;border:1.5px solid rgba(200,212,238,.2);border-radius:var(--alv-radius-sm);overflow:visible;background:rgba(255,255,255,.08);">
					<input type="search" name="s" id="alvSearchPageInput" value="<?php echo esc_attr($query_str); ?>"
						   placeholder="<?php esc_attr_e('Yeniden ara…','alveren'); ?>"
						   autocomplete="off"
						   style="flex:1;border:none;background:transparent;padding:0 12px;font-size:13.5px;color:#fff;outline:none;min-width:0;">
					<button type="submit" style="background:var(--alv-red);border:none;color:#fff;padding:0 16px;cursor:pointer;font-size:13px;border-radius:0 var(--alv-radius-sm) var(--alv-radius-sm) 0;">
						<i class="fas fa-search" aria-hidden="true"></i>
					</button>
				</form>
				<div id="alvSearchPageResults" class="alv-live-results" role="listbox"></div>
			</div>
		</div>
	</div>

	<?php if ( have_posts() ) : ?>

		<p style="font-size:13px;color:var(--alv-gray-500);margin-bottom:16px;">
			<strong style="color:var(--alv-navy);"><?php echo absint( $wp_query->found_posts ); ?></strong>
			<?php esc_html_e('sonuç bulundu','alveren'); ?>
		</p>

		<ul class="alv-article-list" role="list">
			<?php while ( have_posts() ) : the_post(); ?>
			<li class="alv-article-list__item">
				<a href="<?php the_permalink(); ?>" class="alv-article-list__link">
					<span class="alv-article-list__icon" aria-hidden="true"><i class="fas fa-file-alt"></i></span>
					<span class="alv-article-list__title"><?php the_title(); ?></span>
					<span class="alv-article-list__date"><?php echo esc_html( get_the_date('d.m.Y') ); ?></span>
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
