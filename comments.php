<?php
/**
 * WP Alveren — comments.php
 *
 * @package WPAlveren
 */

if ( post_password_required() ) {
	echo '<p class="nopassword">' . __( 'Bu içerik parola korumalıdır. Yorumları görüntülemek için parola girin.', 'alveren' ) . '</p>';
	return;
}
?>
<style>
.alv-comments { margin-top: 36px; }
.alv-comments-title {
	font-family: var(--alv-font-head);
	font-size: 17px; font-weight: 700;
	color: var(--alv-navy);
	margin: 0 0 20px;
	padding-bottom: 12px;
	border-bottom: 2px solid var(--alv-red);
	display: flex; align-items: center; gap: 8px;
}
.alv-comments-title i { color: var(--alv-red); font-size: 14px; }

/* Yorum listesi */
.alv-comment-list { list-style: none; margin: 0; padding: 0; }
.alv-comment-list .comment {
	background: var(--alv-white);
	border: 1px solid var(--alv-border);
	border-radius: var(--alv-radius);
	padding: 18px 20px;
	margin-bottom: 12px;
}
.alv-comment-list .children {
	list-style: none;
	padding-left: 32px;
	margin-top: 12px;
}
.comment-author img {
	border-radius: 50%;
	width: 40px; height: 40px;
	flex-shrink: 0;
}
.comment-meta {
	display: flex; align-items: center; gap: 10px;
	margin-bottom: 10px; flex-wrap: wrap;
}
.comment-author .fn {
	font-weight: 700; font-size: 14px; color: var(--alv-navy);
}
.comment-metadata a {
	font-size: 12px; color: var(--alv-gray-400); text-decoration: none;
}
.comment-body p { font-size: 14.5px; color: var(--alv-gray-700); margin: 0 0 8px; }
.reply a {
	font-size: 12px; font-weight: 600; color: var(--alv-red);
	text-decoration: none;
}
.reply a:hover { color: var(--alv-red-dk); }

/* Yorum formu */
.comment-form label {
	display: block; font-size: 12.5px; font-weight: 600;
	color: var(--alv-navy); margin-bottom: 5px;
	text-transform: uppercase; letter-spacing: .05em;
}
.comment-form input[type="text"],
.comment-form input[type="email"],
.comment-form input[type="url"],
.comment-form textarea {
	width: 100%; border: 1.5px solid var(--alv-border-dk);
	border-radius: var(--alv-radius-sm);
	padding: 10px 14px; font-size: 14px;
	font-family: var(--alv-font-ui);
	color: var(--alv-gray-800); background: var(--alv-white);
	outline: none; transition: border-color var(--alv-transition), box-shadow var(--alv-transition);
}
.comment-form input:focus,
.comment-form textarea:focus {
	border-color: var(--alv-navy);
	box-shadow: 0 0 0 3px var(--alv-navy-muted);
}
.comment-form textarea { min-height: 120px; resize: vertical; }
.comment-form .form-submit input {
	background: var(--alv-navy);
	color: #fff; border: none;
	padding: 11px 28px;
	border-radius: var(--alv-radius-sm);
	font-size: 14px; font-weight: 600;
	cursor: pointer; width: auto;
	transition: background var(--alv-transition);
}
.comment-form .form-submit input:hover { background: var(--alv-red); }
</style>

<section id="comments" class="alv-comments">

	<?php if ( have_comments() ) : ?>

	<h2 class="alv-comments-title">
		<i class="fas fa-comments" aria-hidden="true"></i>
		<?php comments_number('Yorum Yok', '1 Yorum', '% Yorum'); ?>
	</h2>

	<ol class="alv-comment-list">
		<?php
		wp_list_comments([
			'style'       => 'ol',
			'short_ping'  => true,
			'avatar_size' => 40,
		]);
		?>
	</ol>

	<?php if ( get_comment_pages_count() > 1 && get_option('page_comments') ) : ?>
	<nav class="alv-pagination" style="margin-top:20px;" aria-label="Yorum sayfaları">
		<?php paginate_comments_links(['prev_text'=>'&laquo;','next_text'=>'&raquo;']); ?>
	</nav>
	<?php endif; ?>

	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports(get_post_type(), 'comments') ) : ?>
	<p style="font-size:14px;color:var(--alv-gray-500);padding:16px;background:var(--alv-gray-50);border-radius:var(--alv-radius);border:1px solid var(--alv-border);">
		<?php esc_html_e('Yorumlar şu anda kapalı.','alveren'); ?>
	</p>
	<?php endif; ?>

	<?php
	comment_form([
		'title_reply'         => '<span style="font-family:var(--alv-font-head);font-size:17px;font-weight:700;color:var(--alv-navy);">Yorum Yaz</span>',
		'title_reply_before'  => '<h2 id="reply-title" class="comment-reply-title" style="margin-top:28px;padding-top:20px;border-top:1px solid var(--alv-border);">',
		'title_reply_after'   => '</h2>',
		'label_submit'        => __('Yorumu Gönder','alveren'),
		'comment_notes_before'=> '',
		'comment_notes_after' => '',
	]);
	?>

</section>
