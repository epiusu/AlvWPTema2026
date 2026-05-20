<?php
/**
 * WP Alveren — template-parts/content-single.php
 *
 * @package WPAlveren
 */
?>
<style>
/* ── MAKALE BAŞLIĞI ──────────────────────── */
.alv-article-header { margin-bottom: 20px; }
.alv-entry-title {
	font-family: var(--alv-font-head);
	font-size: clamp(20px, 3.2vw, 28px);
	font-weight: 800; color: var(--alv-black);
	line-height: 1.25; letter-spacing: -.025em; margin: 0 0 16px;
}

/* ── SOSYAL PAYLAŞIM ─────────────────────── */
.alv-share-box {
	background: var(--alv-gray-50);
	border: 1px solid var(--alv-border);
	border-radius: var(--alv-radius);
	padding: 18px 22px;
	margin-top: 32px;
}
.alv-share-box__head {
	display: flex; align-items: center; justify-content: space-between;
	flex-wrap: wrap; gap: 12px; margin-bottom: 14px;
}
.alv-share-box__title {
	font-size: 13px; font-weight: 700; color: var(--alv-navy);
	text-transform: uppercase; letter-spacing: .06em;
	display: flex; align-items: center; gap: 7px; margin: 0;
}
.alv-share-box__title i { color: var(--alv-red); }

/* Beğen butonu */
.alv-like-btn {
	display: inline-flex; align-items: center; gap: 7px;
	background: var(--alv-navy); color: #fff; border: none;
	padding: 8px 18px; border-radius: var(--alv-radius-sm);
	font-size: 13px; font-weight: 600; cursor: pointer;
	transition: background var(--alv-transition); font-family: var(--alv-font-ui);
}
.alv-like-btn:hover { background: var(--alv-red); }
.alv-like-btn:active { transform: scale(.97); }
.alv-like-btn.is-liked { background: var(--alv-red); cursor: default; }
.alv-like-btn:disabled:not(.is-liked) { opacity:.6; cursor:not-allowed; }

/* Paylaşım butonları */
.alv-share-btns {
	display: flex; flex-wrap: wrap; gap: 7px; align-items: center;
}
.alv-share-btn {
	display: inline-flex; align-items: center; gap: 6px;
	padding: 7px 13px; border-radius: var(--alv-radius-sm);
	font-size: 12.5px; font-weight: 600; text-decoration: none;
	white-space: nowrap; transition: opacity .15s, transform .1s;
	color: #fff; border: none; cursor: pointer;
	font-family: var(--alv-font-ui);
}
.alv-share-btn:hover { opacity: .88; color: #fff; transform: translateY(-1px); }
.alv-share-btn:active { transform: scale(.97); }
.alv-share-btn i { font-size: 13px; }
.alv-share-btn.fb  { background: #1877f2; }
.alv-share-btn.tw  { background: #000; }
.alv-share-btn.wa  { background: #25d366; }
.alv-share-btn.tg  { background: #0088cc; }
.alv-share-btn.vk  { background: #4a76a8; }
.alv-share-btn.mas { background: #6364ff; }
.alv-share-btn.bs  { background: #0085ff; }
.alv-share-btn.copy { background: var(--alv-navy); }
.alv-share-btn.copy.copied { background: #27ae60; }
.alv-share-btn.print { background: var(--alv-gray-600); }

/* ── YAZAN KUTUSU ────────────────────────── */
.alv-author-box {
	background: var(--alv-white);
	border: 1px solid var(--alv-border);
	border-radius: var(--alv-radius);
	padding: 20px 22px;
	margin-top: 20px;
	display: flex; align-items: flex-start; gap: 16px;
}
.alv-author-box__body { flex: 1; min-width: 0; }
.alv-author-box__badge {
	background: var(--alv-navy-muted);
	color: var(--alv-navy);
	font-size: 10px; font-weight: 700;
	text-transform: uppercase; letter-spacing: .08em;
	padding: 3px 9px; border-radius: 4px;
	display: inline-block; margin-bottom: 6px;
}
.alv-author-box__name {
	font-family: var(--alv-font-head);
	font-size: 16px; font-weight: 700; color: var(--alv-navy);
	margin: 0 0 4px; line-height: 1.3;
}
.alv-author-box__count {
	font-size: 12.5px; color: var(--alv-gray-500);
	display: flex; align-items: center; gap: 5px;
}
.alv-author-box__count i { color: var(--alv-red); font-size: 11px; }
.alv-author-box__bio {
	font-size: 13.5px; color: var(--alv-gray-600);
	line-height: 1.65; margin: 8px 0 0;
}

/* ── ÖNCEKI/SONRAKI ──────────────────────── */
.alv-post-nav {
	display: grid; grid-template-columns: 1fr 1fr;
	gap: 12px; margin-top: 28px;
}
.alv-post-nav__item {
	background: var(--alv-white); border: 1px solid var(--alv-border);
	border-radius: var(--alv-radius); padding: 15px 18px;
	text-decoration: none; display: flex; flex-direction: column; gap: 4px;
	transition: box-shadow var(--alv-transition), border-color var(--alv-transition);
}
.alv-post-nav__item:hover { box-shadow: var(--alv-shadow); border-color: var(--alv-border-dk); }
.alv-post-nav__item--next { text-align: right; }
.alv-post-nav__dir {
	font-size: 10.5px; font-weight: 700; text-transform: uppercase;
	letter-spacing: .07em; color: var(--alv-gray-400);
	display: flex; align-items: center; gap: 4px;
}
.alv-post-nav__item--next .alv-post-nav__dir { justify-content: flex-end; }
.alv-post-nav__dir i { color: var(--alv-red); font-size: 9px; }
.alv-post-nav__title {
	font-size: 13px; font-weight: 600; color: var(--alv-navy);
	line-height: 1.4; display:-webkit-box;
	-webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;
}
.alv-post-nav__item:hover .alv-post-nav__title { color: var(--alv-red); }
@media(max-width:575px){ .alv-post-nav { grid-template-columns:1fr; } }
</style>

<?php
$post_url   = rawurlencode( esc_url_raw( get_permalink() ) );
$post_title = rawurlencode( wp_strip_all_tags( get_the_title() ) );
$post_raw   = esc_url_raw( get_permalink() );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('alv-article'); ?>>

	<header class="alv-article-header">
		<?php alveren_breadcrumb(); ?>
		<h1 class="alv-entry-title"><?php the_title(); ?></h1>
		<div class="alv-article-meta">
			<span><i class="fas fa-calendar-alt" aria-hidden="true"></i> <?php echo get_the_date('d.m.Y'); ?></span>
			<span><i class="fas fa-sync-alt" aria-hidden="true"></i> <?php echo get_the_modified_date('d.m.Y'); ?></span>
		</div>
	</header>



	<!-- Öne çıkarılmış görsel -->
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="alv-featured-image">
		<?php the_post_thumbnail('alv-hero', array(
			'alt'   => get_the_title(),
			'loading' => 'eager',
			'style' => 'width:100%;height:auto;max-height:460px;object-fit:cover;border-radius:10px;display:block;',
		) ); ?>
		<?php $cap = get_the_post_thumbnail_caption(); if ($cap) : ?>
		<p style="font-size:12px;color:var(--alv-gray-500);text-align:center;margin:8px 0 0;font-style:italic;"><?php echo esc_html($cap); ?></p>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<!-- İçerik -->
	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages(array('before'=>'<nav class="alv-page-links">Sayfalar:','after'=>'</nav>')); ?>
	</div>



	<!-- Sosyal Paylaşım + Beğen -->
	<div class="alv-share-box">
		<div class="alv-share-box__head">
			<h4 class="alv-share-box__title">
				<i class="fas fa-share-alt" aria-hidden="true"></i>
				Paylaş
			</h4>
			<!-- Beğen butonu -->
			<?php
			$like_count = (int) get_post_meta( get_the_ID(), 'alv_likes', true );
			$like_nonce = wp_create_nonce('alv_like');
			?>
			<button class="alv-like-btn" data-post="<?php echo get_the_ID(); ?>" data-nonce="<?php echo esc_attr($like_nonce); ?>" type="button">
				<i class="fas fa-thumbs-up" aria-hidden="true"></i>
				<span class="alv-like-btn__label">Beğen</span>
				<span class="alv-like-btn__count">(<?php echo $like_count; ?>)</span>
			</button>
		</div>

		<div class="alv-share-btns">
			<!-- Facebook -->
			<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $post_url; ?>" class="alv-share-btn fb" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-facebook-f" aria-hidden="true"></i> Facebook
			</a>
			<!-- X / Twitter -->
			<a href="https://x.com/intent/tweet?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" class="alv-share-btn tw" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-x-twitter" aria-hidden="true"></i> X
			</a>
			<!-- WhatsApp -->
			<a href="https://wa.me/?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" class="alv-share-btn wa" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-whatsapp" aria-hidden="true"></i> WhatsApp
			</a>
			<!-- Telegram -->
			<a href="https://t.me/share/url?url=<?php echo $post_url; ?>&text=<?php echo $post_title; ?>" class="alv-share-btn tg" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-telegram-plane" aria-hidden="true"></i> Telegram
			</a>
			<!-- Mastodon -->
			<a href="https://mastodon.social/share?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" class="alv-share-btn mas" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-mastodon" aria-hidden="true"></i> Mastodon
			</a>
			<!-- Bluesky -->
			<a href="https://bsky.app/intent/compose?text=<?php echo $post_title; ?>%20<?php echo $post_url; ?>" class="alv-share-btn bs" target="_blank" rel="noopener noreferrer">
				<i class="fas fa-cloud" aria-hidden="true"></i> Bluesky
			</a>
			<!-- VK -->
			<a href="https://vk.com/share.php?url=<?php echo $post_url; ?>" class="alv-share-btn vk" target="_blank" rel="noopener noreferrer">
				<i class="fab fa-vk" aria-hidden="true"></i> VK
			</a>
			<!-- Bağlantı kopyala -->
			<button class="alv-share-btn copy" id="alvCopyBtn" data-url="<?php echo esc_attr($post_raw); ?>" type="button">
				<i class="fas fa-link" aria-hidden="true"></i> <span>Bağlantıyı Kopyala</span>
			</button>
			<!-- Yazdır -->
			<button class="alv-share-btn print" onclick="window.print();" type="button">
				<i class="fas fa-print" aria-hidden="true"></i> Yazdır
			</button>
		</div>
	</div>

	<!-- Yazan Kutusu -->
	<?php
	$author_id    = get_the_author_meta('ID');
	$author_name  = get_the_author_meta('display_name');
	$author_bio   = get_the_author_meta('description');
	$author_posts = count_user_posts( $author_id );
	?>
	<div class="alv-author-box">
		<div class="alv-author-box__body">
			<span class="alv-author-box__badge">Yazan</span>
			<p class="alv-author-box__name"><?php echo esc_html($author_name); ?></p>
			<p class="alv-author-box__count">
				<i class="fas fa-file-alt" aria-hidden="true"></i>
				<?php echo (int)$author_posts; ?> makale
			</p>
			<?php if ( $author_bio ) : ?>
			<p class="alv-author-box__bio"><?php echo esc_html($author_bio); ?></p>
			<?php endif; ?>
		</div>
	</div>

</article>

<script>
/* Bağlantı kopyala */
(function(){
	var btn = document.getElementById('alvCopyBtn');
	if (!btn) return;
	btn.addEventListener('click', function(){
		var url = btn.dataset.url;
		if (navigator.clipboard) {
			navigator.clipboard.writeText(url).then(function(){
				btn.classList.add('copied');
				btn.querySelector('span').textContent = 'Kopyalandı!';
				setTimeout(function(){ btn.classList.remove('copied'); btn.querySelector('span').textContent = 'Bağlantıyı Kopyala'; }, 2500);
			});
		} else {
			var ta = document.createElement('textarea');
			ta.value = url; document.body.appendChild(ta);
			ta.select(); document.execCommand('copy'); document.body.removeChild(ta);
			btn.classList.add('copied');
			btn.querySelector('span').textContent = 'Kopyalandı!';
			setTimeout(function(){ btn.classList.remove('copied'); btn.querySelector('span').textContent = 'Bağlantıyı Kopyala'; }, 2500);
		}
	});
})();
</script>
