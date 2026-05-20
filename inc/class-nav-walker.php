<?php
/**
 * WP Alveren — Nav Walker (CSS-only hover, Bootstrap JS gerektirmez)
 *
 * @package WPAlveren
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ============================================================
   Masaüstü Nav Walker — saf CSS hover dropdown
   ============================================================ */
class Alveren_Nav_Walker extends Walker_Nav_Menu {

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

		$indent  = $depth ? str_repeat( "\t", $depth ) : '';
		$classes = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'nav-item';

		$has_children = in_array( 'menu-item-has-children', $classes, true );
		if ( $has_children ) {
			$classes[] = 'dropdown';
		}

		$is_active = in_array( 'current-menu-item',     $classes, true )
		          || in_array( 'current-menu-parent',    $classes, true )
		          || in_array( 'current-menu-ancestor',  $classes, true );

		$class_names = esc_attr( implode( ' ', array_filter( array_unique( $classes ) ) ) );
		$output .= $indent . '<li class="' . $class_names . '">';

		/* Link nitelikleri */
		$href   = ! empty( $item->url )        ? $item->url        : '#';
		$target = ! empty( $item->target )     ? $item->target     : '';
		$rel    = ! empty( $item->xfn )        ? $item->xfn        : '';
		$title  = ! empty( $item->attr_title ) ? $item->attr_title : '';

		if ( $depth === 0 ) {
			$link_class = 'nav-link' . ( $is_active ? ' active' : '' );
		} else {
			$link_class = 'dropdown-item' . ( $is_active ? ' active' : '' );
		}

		$attrs  = ' class="' . esc_attr( $link_class ) . '"';
		$attrs .= ' href="' . esc_url( $href ) . '"';
		if ( $target ) $attrs .= ' target="' . esc_attr( $target ) . '"';
		if ( $rel )    $attrs .= ' rel="' . esc_attr( $rel ) . '"';
		if ( $title )  $attrs .= ' title="' . esc_attr( $title ) . '"';
		if ( $is_active ) $attrs .= ' aria-current="page"';

		$label = esc_html( apply_filters( 'the_title', $item->title, $item->ID ) );

		/* Alt menüsü olan üst link — ok ikonu */
		if ( $depth === 0 && $has_children ) {
			$label .= ' <i class="fas fa-chevron-down alv-nav-arrow" aria-hidden="true"></i>';
		}

		$output .= '<a' . $attrs . '>' . $label . '</a>';
	}

	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$output .= "\n" . str_repeat( "\t", $depth ) . '<ul class="dropdown-menu">' . "\n";
	}

	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$output .= str_repeat( "\t", $depth ) . "</ul>\n";
	}
}

/* ============================================================
   Drawer (Mobil) Walker — vanilla JS accordion, Bootstrap yok
   ============================================================ */
class Alveren_Drawer_Walker extends Walker_Nav_Menu {

	private int $acc_idx = 0;

	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {

		$classes      = empty( $item->classes ) ? [] : (array) $item->classes;
		$has_child    = in_array( 'menu-item-has-children', $classes, true );
		$is_active    = in_array( 'current-menu-item',    $classes, true )
		             || in_array( 'current-menu-parent',  $classes, true );

		$item_class = 'alv-drawer-nav__item' . ( $is_active ? ' active' : '' );
		$output .= '<li class="' . esc_attr( $item_class ) . '">';

		if ( $has_child && $depth === 0 ) {
			/* Alt menüsü olan üst öğe: toggle butonu */
			$this->acc_idx++;
			$sub_id   = 'alvDrSub' . $this->acc_idx;
			$expanded = $is_active ? 'true' : 'false';
			$output  .= '<button class="alv-drawer-nav__toggle" type="button"'
			          . ' data-alv-target="' . esc_attr( $sub_id ) . '"'
			          . ' aria-expanded="' . $expanded . '"'
			          . ' aria-controls="' . esc_attr( $sub_id ) . '">';
			$output  .= esc_html( apply_filters( 'the_title', $item->title, $item->ID ) );
			$output  .= '<i class="fas fa-chevron-right alv-drawer-nav__arrow" aria-hidden="true"></i>';
			$output  .= '</button>';
			/* Alt liste wrapper */
			$show    = $is_active ? ' alv-open' : '';
			$output .= '<ul class="alv-drawer-nav__sub' . $show . '" id="' . esc_attr( $sub_id ) . '">';
		} else {
			$href  = ! empty( $item->url ) ? esc_url( $item->url ) : '#';
			$class = $depth === 0 ? 'alv-drawer-nav__link' : 'alv-drawer-nav__sub-link';
			if ( $is_active ) $class .= ' active';
			$output .= '<a href="' . $href . '" class="' . esc_attr( $class ) . '">';
			$output .= esc_html( apply_filters( 'the_title', $item->title, $item->ID ) );
			$output .= '</a>';
		}
	}

	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$has_child = in_array( 'menu-item-has-children', $classes, true );
		if ( $has_child && $depth === 0 ) {
			$output .= '</ul>'; /* alt liste kapanışı */
		}
		$output .= '</li>';
	}

	/* start_lvl / end_lvl drawer'da kullanılmıyor (start_el içinde yönetiliyor) */
	public function start_lvl( &$output, $depth = 0, $args = null ) {}
	public function end_lvl( &$output, $depth = 0, $args = null ) {}
}
