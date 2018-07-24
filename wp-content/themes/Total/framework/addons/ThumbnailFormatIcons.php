<?php
/**
 * Used to show format icons over entry thumbnails
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 4.6.5
 */

namespace TotalTheme;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ThumbnailFormatIcons {

	/**
	 * Main constructor
	 *
	 * @since 4.5.4
	 */
	public function __construct() {
		add_filter( 'wpex_get_entry_media_after', array( $this, 'icon_html' ), 10, 2 );
	}

	/**
	 * Check if the thumbnail format icon html is enabled
	 *
	 * @since 4.5.4
	 */
	public function enabled( $instance ) {
		$bool = 'post' == get_post_type() ? true : false;
		return apply_filters( 'wpex_thumbnails_have_format_icons', $bool, $instance );
	}

	/**
	 * Return correct icon class
	 *
	 * @since 4.5.4
	 */
	public function icon_class( $instance ) {
		$icon   = 'fa fa-file-text-o';
		$format = get_post_format();
		if ( 'video' == $format ) {
			$icon = 'fa fa-play';
		} elseif ( 'audio' == $format ) {
			$icon = 'fa fa-music';
		} elseif ( 'gallery' == $format ) {
			$icon = 'fa fa-file-photo-o';
		} elseif ( 'quote' == $format ) {
			$icon = 'fa fa-quote-left';
		}
		return apply_filters( 'wpex_get_thumbnail_format_icon_class', $icon, $instance );

	}

	/**
	 * Get thumbnail format icon
	 *
	 * @since 4.5.4
	 */
	public function icon_html( $after_html = '', $instance = '' ) {
		if ( ! $this->enabled( $instance ) ) {
			return $after_html;
		}
		$icon = $this->icon_class( $instance );
		if ( ! $icon ) {
			return $after_html;
		}
		$icon = '<span class="' . $icon . '"></span>';
		$icon = apply_filters( 'wpex_get_thumbnail_format_icon_html', $icon, $instance );
		if ( $icon ) {
			return  $after_html . '<i class="wpex-thumbnail-format-icon" aria-hidden="true">' . $icon . '</i>';
		}
	}

}

new ThumbnailFormatIcons();