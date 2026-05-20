<?php
/**
 * WP Alveren — template-parts/no-results.php
 *
 * @package WPAlveren
 */
?>
<div class="alv-no-results">
	<div style="font-size:44px;color:var(--alv-gray-300);margin-bottom:18px;">
		<i class="fas fa-folder-open" aria-hidden="true"></i>
	</div>
	<h2 style="font-family:var(--alv-font-head);font-size:20px;font-weight:700;color:var(--alv-navy);margin:0 0 8px;">
		<?php esc_html_e('İçerik bulunamadı','alveren'); ?>
	</h2>
	<p style="font-size:14px;color:var(--alv-gray-500);margin:0 0 22px;">
		<?php if ( is_search() ) : ?>
			<?php printf( esc_html__('"%s" için eşleşen sonuç bulunamadı. Farklı anahtar kelimeler deneyin.','alveren'), '<strong>' . esc_html(get_search_query()) . '</strong>' ); ?>
		<?php else : ?>
			<?php esc_html_e('Bu alanda henüz içerik bulunmuyor.','alveren'); ?>
		<?php endif; ?>
	</p>
	<?php if ( ! is_category() ) : ?>
	<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>"
		  style="display:flex;max-width:420px;margin:0 auto;border:1.5px solid var(--alv-border-dk);border-radius:var(--alv-radius-sm);overflow:hidden;">
		<input type="search" name="s" value="<?php echo esc_attr(get_search_query()); ?>"
			   placeholder="<?php esc_attr_e('Arama yapın…','alveren'); ?>"
			   style="flex:1;border:none;padding:10px 14px;font-size:14px;outline:none;min-width:0;background:var(--alv-white);">
		<button type="submit"
				style="background:var(--alv-red);color:#fff;border:none;padding:0 18px;cursor:pointer;font-size:13px;transition:background var(--alv-transition);">
			<i class="fas fa-search" aria-hidden="true"></i>
		</button>
	</form>
	<?php endif; ?>
</div>
