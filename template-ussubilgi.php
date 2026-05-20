<?php
/**
 * Template Name: usSuBilgi Anasayfa
 * Template Post Type: page
 *
 * WP Alveren — wikiHow tarzı 6 renkli kategori kutu tasarımı.
 *
 * @package WPAlveren
 */

get_header();
get_template_part( 'template-parts/featured-spots-band' );

/* ── Seçili 6 kategori ── */
$selected_ids = array();
for ( $i = 1; $i <= 6; $i++ ) {
	$val = alv_option( 'home_cat_' . $i, '' );
	if ( $val ) $selected_ids[] = (int) $val;
}

if ( empty( $selected_ids ) ) {
	$auto = get_categories( array(
		'taxonomy'   => 'category',
		'parent'     => 0,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
		'number'     => 6,
	) );
	foreach ( $auto as $c ) $selected_ids[] = $c->term_id;
}

/* Tüm kartlar lacivert */
$card_fixed_color = '#1a2744';
$def_icons = array(
	'fa-book', 'fa-laptop-code', 'fa-cogs',
	'fa-lightbulb', 'fa-users', 'fa-question-circle',
);

$hero_title    = alv_option( 'home_hero_title',    get_bloginfo('name') );
$hero_subtitle = alv_option( 'home_hero_subtitle', get_bloginfo('description') );
$hero_show     = alv_option( 'home_show_hero', '1' );
?>
<style>
/* ── SYD ALANI ─────────────────────────────── */
.alv-home-hero {
	background: linear-gradient(160deg, #0f0f1a 0%, #1c1c2e 40%, #2e1010 100%);
	border-radius: var(--alv-radius-lg);
	padding: 36px 36px 40px;
	text-align: center;
	margin-bottom: 44px;
	position: relative;
	overflow: hidden;
}
.alv-home-hero::before {
	content: '';
	position: absolute;
	top: -60px; right: -60px;
	width: 300px; height: 300px;
	background: radial-gradient(circle, rgba(184,28,28,.15) 0%, transparent 70%);
	pointer-events: none;
}

/* ── SYD CANLΙ ARAMA ─────────────────────── */
.alv-syd-search-wrap {
	position: relative;
	max-width: 600px;
	margin: 0 auto;
}
.alv-syd-search {
	display: flex;
	align-items: center;
	height: 54px;
	background: rgba(255,255,255,.97);
	border-radius: 27px;
	box-shadow: 0 8px 40px rgba(0,0,0,.35), 0 0 0 1px rgba(255,255,255,.08);
	overflow: hidden;
	transition: box-shadow .2s;
}
.alv-syd-search:focus-within {
	box-shadow: 0 8px 40px rgba(0,0,0,.45), 0 0 0 3px var(--alv-red-ring);
}
.alv-syd-search__icon {
	padding: 0 0 0 20px;
	color: var(--alv-gray-400);
	font-size: 14px;
	flex-shrink: 0;
}
.alv-syd-search__input {
	flex: 1;
	border: none;
	background: transparent;
	padding: 0 14px;
	font-size: 15px;
	color: var(--alv-gray-800);
	outline: none;
	min-width: 0;
	font-family: var(--alv-font-ui);
}
.alv-syd-search__input::placeholder {
	color: var(--alv-gray-400);
	opacity: 1;
	transition: opacity .2s;
}
.alv-syd-search:focus-within .alv-syd-search__input::placeholder { opacity: 0; }
.alv-syd-search__btn {
	display: flex;
	align-items: center;
	justify-content: center;
	width: 54px;
	height: 54px;
	flex-shrink: 0;
	background: var(--alv-red);
	border: none;
	color: #fff;
	font-size: 15px;
	cursor: pointer;
	transition: background .18s;
}
.alv-syd-search__btn:hover { background: var(--alv-red-dk); }

/* Canlı sonuç kutusu */
.alv-syd-results {
	position: absolute;
	top: calc(100% + 8px);
	left: 0; right: 0;
	background: #fff;
	border-radius: var(--alv-radius);
	box-shadow: 0 16px 48px rgba(0,0,0,.22);
	border: 1px solid var(--alv-border);
	z-index: 300;
	overflow: hidden;
	opacity: 0;
	transform: translateY(-8px) scale(.98);
	pointer-events: none;
	transition: opacity .18s, transform .18s;
	max-height: 400px;
	overflow-y: auto;
}
.alv-syd-results.is-open {
	opacity: 1;
	transform: translateY(0) scale(1);
	pointer-events: auto;
}
/* Sonuç grup başlığı */
.alv-syd-results__group-head {
	display: flex;
	align-items: center;
	gap: 7px;
	padding: 10px 16px 6px;
	font-size: 10.5px;
	font-weight: 700;
	text-transform: uppercase;
	letter-spacing: .08em;
	color: var(--alv-gray-400);
	border-top: 1px solid var(--alv-gray-100);
}
.alv-syd-results__group-head:first-child { border-top: none; }
.alv-syd-results__group-head i { color: var(--alv-red); font-size: 10px; }
/* Sonuç satırı */
.alv-syd-result {
	display: flex;
	align-items: center;
	gap: 11px;
	padding: 10px 16px;
	text-decoration: none;
	color: var(--alv-gray-800);
	font-size: 14px;
	transition: background .12s;
	cursor: pointer;
}
.alv-syd-result:hover, .alv-syd-result:focus {
	background: var(--alv-gray-50);
	outline: none;
}
.alv-syd-result__icon {
	width: 28px; height: 28px;
	flex-shrink: 0;
	border-radius: 6px;
	display: flex; align-items: center; justify-content: center;
	font-size: 11px;
}
.alv-syd-result__icon--post { background: var(--alv-red-lt); color: var(--alv-red); }
.alv-syd-result__icon--cat  { background: var(--alv-navy-muted); color: var(--alv-navy); }
.alv-syd-result__text { flex: 1; min-width: 0; }
.alv-syd-result__title {
	display: block;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
	line-height: 1.3;
}
.alv-syd-result__title mark { background: none; color: var(--alv-red); font-weight: 700; padding: 0; }
.alv-syd-result__meta {
	display: block;
	font-size: 11.5px;
	color: var(--alv-gray-400);
	margin-top: 2px;
}
.alv-syd-result__count {
	margin-left: auto;
	flex-shrink: 0;
	background: var(--alv-gray-100);
	color: var(--alv-gray-500);
	font-size: 11px;
	font-weight: 600;
	padding: 2px 8px;
	border-radius: 10px;
}
/* Tüm sonuçlar butonu */
.alv-syd-result--all {
	justify-content: center;
	gap: 7px;
	font-size: 13px;
	font-weight: 600;
	color: var(--alv-red);
	border-top: 1px solid var(--alv-border);
	padding: 12px 16px;
}
.alv-syd-result--all:hover { background: var(--alv-red-lt); color: var(--alv-red-dk); }
/* Boş / yükleniyor */
.alv-syd-empty {
	padding: 24px 16px;
	text-align: center;
	color: var(--alv-gray-500);
	font-size: 13.5px;
}
.alv-syd-loading {
	padding: 20px 16px;
	text-align: center;
}
.alv-home-stats {
	display: flex; align-items: center; justify-content: center;
	gap: 24px; margin-top: 20px; flex-wrap: wrap;
}
.alv-home-stats__item {
	display: flex; align-items: center; gap: 6px;
	font-size: 12px; color: rgba(200,212,238,.45);
}
.alv-home-stats__num { color: rgba(200,212,238,.8); font-weight: 700; }
.alv-home-stats__item i { color: var(--alv-red); font-size: 10px; }

/* ── KATEGORİ KUTULARI ─────────────────────── */
.alv-wiki-grid { margin-bottom: 8px; }

.alv-wiki-card {
	background: var(--alv-white);
	border: 1px solid var(--alv-border);
	border-radius: var(--alv-radius);
	overflow: hidden;
	box-shadow: var(--alv-shadow-sm);
	transition: box-shadow var(--alv-transition), border-color var(--alv-transition);
	margin-bottom: 24px;
	display: flex;
	flex-direction: column;
}
.alv-wiki-card:hover {
	box-shadow: var(--alv-shadow);
	border-color: #c8d4ee;
}

/* Üst şerit: ikon sol + başlık sağ */
.alv-wiki-card__header {
	display: flex;
	align-items: center;
	gap: 16px;
	padding: 16px 20px;
	background: var(--alv-navy);
	position: relative;
	overflow: hidden;
}
.alv-wiki-card__header::after {
	content: '';
	position: absolute;
	top: -30px; right: -30px;
	width: 100px; height: 100px;
	border-radius: 50%;
	background: rgba(255,255,255,.05);
	pointer-events: none;
}

/* İkon kutusu */
.alv-wiki-card__icon-wrap {
	width: 48px; height: 48px;
	border-radius: var(--alv-radius-sm);
	background: rgba(255,255,255,.12);
	display: flex; align-items: center; justify-content: center;
	flex-shrink: 0;
	border: 1px solid rgba(255,255,255,.1);
}
.alv-wiki-card__icon-wrap i {
	font-size: 20px;
	color: #fff;
}

/* Başlık + açıklama */
.alv-wiki-card__head-body { flex: 1; min-width: 0; }
.alv-wiki-card__cat-name {
	font-family: var(--alv-font-head);
	font-size: 15px;
	font-weight: 700;
	color: #fff;
	margin: 0;
	line-height: 1.25;
	letter-spacing: -.01em;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}
.alv-wiki-card__cat-name a {
	color: inherit;
	text-decoration: none;
}
.alv-wiki-card__cat-name a:hover { color: #f5c6c0; }
.alv-wiki-card__cat-desc {
	font-size: 12px;
	color: rgba(255,255,255,.45);
	margin: 3px 0 0;
	white-space: nowrap;
	overflow: hidden;
	text-overflow: ellipsis;
}

/* İçerik listesi */
.alv-wiki-card__body {
	padding: 6px 0 4px;
	flex: 1;
}
.alv-wiki-card__list {
	list-style: none;
	margin: 0; padding: 0;
}
.alv-wiki-card__list li {
	border-bottom: 1px solid var(--alv-gray-100);
}
.alv-wiki-card__list li:last-child { border-bottom: none; }
.alv-wiki-card__list li a {
	display: flex;
	align-items: center;
	gap: 10px;
	padding: 9px 20px;
	font-size: 13.5px;
	color: var(--alv-gray-800);
	text-decoration: none;
	line-height: 1.4;
	transition: background var(--alv-transition), color var(--alv-transition);
}
.alv-wiki-card__list li a:hover {
	background: var(--alv-gray-50);
	color: var(--alv-navy);
}
.alv-wiki-card__list li a::before {
	content: '\f054';
	font-family: 'Font Awesome 5 Free';
	font-weight: 900;
	font-size: 9px;
	color: var(--alv-gray-300);
	flex-shrink: 0;
	transition: color var(--alv-transition);
}
.alv-wiki-card__list li a:hover::before { color: var(--alv-red); }



@media (max-width: 575px) {
	.alv-wiki-card__header { padding: 14px 16px; gap: 12px; }
	.alv-wiki-card__icon-wrap { width: 40px; height: 40px; }
	.alv-wiki-card__icon-wrap i { font-size: 17px; }
	.alv-wiki-card__list li a { padding: 9px 16px; }
}
</style>

<div class="<?php echo alveren_content_class(); ?>">
<main id="main" class="site-main" role="main">

<?php if ( $hero_show ) : ?>
<div class="alv-home-hero">

	<!-- Duyuru / Breaking News bandı — hero içinde -->

	<!-- Gelişmiş Canlı Arama — placeholder döner -->
	<div class="alv-syd-search-wrap" id="alvSydSearchWrap">
		<form class="alv-syd-search" role="search" method="get" action="<?php echo esc_url( home_url('/') ); ?>">
			<i class="fas fa-search alv-syd-search__icon" aria-hidden="true"></i>
			<input
				type="search"
				name="s"
				id="alvSydSearchInput"
				class="alv-syd-search__input"
				placeholder="Site içinde ara…"
				value="<?php echo esc_attr( get_search_query() ); ?>"
				autocomplete="off"
				aria-label="<?php esc_attr_e( 'Arama', 'alveren' ); ?>"
				aria-controls="alvSydLiveResults"
				aria-autocomplete="list"
			>
			<button type="submit" class="alv-syd-search__btn" aria-label="<?php esc_attr_e( 'Ara', 'alveren' ); ?>">
				<i class="fas fa-arrow-right" aria-hidden="true"></i>
			</button>
		</form>
		<!-- Canlı sonuç kutusu -->
		<div id="alvSydLiveResults" class="alv-syd-results" role="listbox" aria-label="Arama önerileri"></div>
	</div>

</div>
<?php endif; ?>



<?php if ( ! empty( $selected_ids ) ) : ?>

<div class="row g-4 alv-wiki-grid">
<?php
$box_index = 0;
foreach ( $selected_ids as $cid ) :
	$cat = get_category( $cid );
	if ( ! $cat || is_wp_error($cat) ) continue;

	$box_index++;
	$cat_link  = esc_url( get_category_link( $cat ) );
	$cat_total = alveren_cat_total( (int) $cat->term_id );

	/* Sabit lacivert renk */
	$card_color = $card_fixed_color;

	/* İkon: Customizer > varsayılan */
	$card_icon_raw = alv_option( 'home_cat_icon_' . $box_index, '' );
	if ( ! $card_icon_raw ) {
		$card_icon_raw = $def_icons[ ( $box_index - 1 ) % count( $def_icons ) ];
	}
	/* fa- prefix kontrolü */
	$card_icon = ( strpos( $card_icon_raw, 'fa-' ) === 0 ) ? 'fas ' . $card_icon_raw : $card_icon_raw;

	/* İçerik: önce alt kategoriler, yoksa makaleler */
	$children = get_categories( array(
		'taxonomy'   => 'category',
		'parent'     => $cat->term_id,
		'hide_empty' => false,
		'number'     => 5,
	) );

	$posts = null;
	if ( empty( $children ) ) {
		$posts = new WP_Query( array(
			'cat'            => $cat->term_id,
			'posts_per_page' => 5,
			'post_status'    => 'publish',
			'no_found_rows'  => true,
		) );
	}
?>
<div class="col-lg-4 col-md-6">
	<div class="alv-wiki-card" style="--card-color: <?php echo esc_attr($card_color); ?>;">

		<!-- Lacivert başlık şeridi: ikon + başlık + açıklama -->
		<div class="alv-wiki-card__header">
			<div class="alv-wiki-card__icon-wrap">
				<i class="<?php echo esc_attr($card_icon); ?>" aria-hidden="true"></i>
			</div>
			<div class="alv-wiki-card__head-body">
				<h3 class="alv-wiki-card__cat-name">
					<a href="<?php echo $cat_link; ?>"><?php echo esc_html( $cat->name ); ?></a>
				</h3>
				<?php if ( $cat->description ) : ?>
				<p class="alv-wiki-card__cat-desc"><?php echo esc_html( wp_trim_words( $cat->description, 8 ) ); ?></p>
				<?php endif; ?>
			</div>
		</div>

		<!-- Liste -->
		<div class="alv-wiki-card__body">
			<ul class="alv-wiki-card__list">
				<?php if ( ! empty( $children ) ) :
					foreach ( $children as $child ) : ?>
					<li>
						<a href="<?php echo esc_url( get_category_link($child) ); ?>">
							<?php echo esc_html( $child->name ); ?>
						</a>
					</li>
				<?php endforeach;
				elseif ( $posts && $posts->have_posts() ) :
					while ( $posts->have_posts() ) : $posts->the_post(); ?>
					<li>
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</li>
				<?php endwhile; wp_reset_postdata();
				else : ?>
					<li style="padding:8px 2px;font-size:13px;color:var(--alv-gray-400);">
						Henüz içerik yok.
					</li>
				<?php endif; ?>
			</ul>
		</div>

	</div>
</div>
<?php endforeach; ?>
</div><!-- .row -->

<?php else : ?>
	<?php get_template_part( 'template-parts/no-results' ); ?>
<?php endif; ?>



</main>
</div>

<?php get_footer(); ?>
