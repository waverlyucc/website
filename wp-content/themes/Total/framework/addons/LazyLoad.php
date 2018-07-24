<?php
/**
 * Image Lazy Load Support (under construction)
 *
 * @package Total WordPress theme
 * @subpackage Framework
 * @version 4.6.5
 *
 * @todo Finish
 */

namespace TotalTheme;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class LazyLoad {

	public $loader;
	public $class;

	public function __construct() {

		add_action( 'wp_head', array( $this, 'head_noscript' ), PHP_INT_MAX );

		$this->loader = apply_filters( 'wpex_lazy_load_placeholder', includes_url( 'images/spinner.gif' ) );
		$this->class  = 'lazy-img';

		add_filter( 'wpex_post_thumbnail_html', array( $this, 'add_lazy_load' ), PHP_INT_MAX );
		add_filter( 'post_thumbnail_html', array( $this, 'add_lazy_load' ), PHP_INT_MAX );
		add_filter( 'get_avatar', array( $this, 'add_lazy_load' ), PHP_INT_MAX );
		add_filter( 'the_content', array( $this, 'add_lazy_load' ), PHP_INT_MAX );
	}

	public function head_noscript() { ?>
		<noscript><style>.lazy-img{display:none;}</style></noscript>
	<?php }

	public function add_lazy_load( $html ) {

		// Don't LazyLoad if the thumbnail is in admin, a feed, REST API or a post preview.
		if ( is_feed() || is_preview() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || empty( $html ) ) {
			return $html;
		}

		// Return if data attribute is found
		if ( false !== strpos( $html, 'data-nolazy' ) || false !== strpos( $html, 'no-lazy' ) ) {
			return $html;
		}

		// Remove width and height
		//$html = preg_replace( '/(width|height)="\d*"/', '', $html );

		// Return correct html
		return preg_replace_callback( '#<img([^>]*) src=("(?:[^"]+)"|\'(?:[^\']+)\'|(?:[^ >]+))([^>]*)>#', array( $this, 'replace_code' ), $html );

	}

	public function replace_code( $matches ) {
		$img = sprintf( '<img%1$s src="%4$s" data-src=%2$s%3$s>', $matches[1], $matches[2], $matches[3], $this->loader );
		if ( false !== strpos( $img, 'class="' ) ) {
			$img = str_replace( 'class="', 'class="' . $this->class . ' ', $img );
		} elseif ( false !== strpos( $img, "class='" ) ) {
			$img = str_replace( "class='", "class='" . $this->class . " ", $img );
		} else {
			$img = str_replace( '<img ', '<img class="' . $this->class . '" ', $img );
		}
		$img_noscript = sprintf( '<noscript><img%1$s src=%2$s%3$s></noscript>', $matches[1], $matches[2], $matches[3] );
		return $img . $img_noscript;
	}

}
new LazyLoad();