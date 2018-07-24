<?php
/**
 * Visual Composer Custom Parameters
 *
 * @package Total WordPress Theme
 * @subpackage VC Functions
 * @version 4.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-attach-images.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-select-buttons.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-menu-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-orderby-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-font-family-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-sizes-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-overlay-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-visibility-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-font-weights-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-font-icon-family-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-social-button-styles-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-hover-css-animations-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-lightbox-skins-select.php'; // @todo deprecate

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-responsive-sizes.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-ofswitch.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-trbl-field.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-number.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-notice.php';

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-hovers-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-filters-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-image-crop-locations-select.php';

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-grid-columns-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-grid-columns-responsive.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-grid-columns-gap-select.php';

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-text-transforms-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-text-alignments-select.php';

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-button-styles-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-button-colors-select.php';

require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-carousel-arrow-styles-select.php';
require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-carousel-arrow-positions-select.php';

if ( defined( 'WPCF7_VERSION' ) ) {
	require_once WPEX_VCEX_DIR . 'shortcode-params/vcex-contact-form-7-select.php';
}