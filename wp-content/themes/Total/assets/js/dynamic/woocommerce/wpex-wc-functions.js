( function( $ ) {
	'use strict';

	$( document ).ready(function() {
		wooDisableScrollTo();
		wooGallery();
		wooAddToCartNotice();
	} );

	 /**
	 * Disables the body scroll on WooCommerce notices
	 *
	 * @since 4.8
	 */
	function wooDisableScrollTo() {

		var $body = $( 'body' );

		$( document ).ajaxComplete( function() {
			if ( $body.hasClass( 'woocommerce-checkout' ) || $body.hasClass( 'woocommerce-cart' ) ) {
				$( 'html, body' ).stop();
			}
		} );

	}

	 /**
	 * WooCommerce Gallery functions
	 *
	 * @since 4.1
	 */
	function wooGallery() {

		if ( typeof wc_single_product_params === 'undefined' || ! wc_single_product_params.flexslider.directionNav ) {
			return;
		}

		var $window     = $( window );
		var $wooGallery = $( '.woocommerce-product-gallery--with-images' );

		if ( ! $wooGallery.length ) {
			return;
		}

		function setWooSliderArrows() {

			$wooGallery.each( function() {

				var $this      = $( this );
				var $nav       = $( this ).find( '.flex-direction-nav' );
				var $thumbsNav = $( this ).find( '.flex-control-thumbs' );

				if ( $nav.length && $thumbsNav.length ) {

					var thumbsNavHeight = $thumbsNav.outerHeight();
					var arrowHeight     = $nav.find( 'a' ).outerHeight();
					var arrowTopoffset  = - ( thumbsNavHeight + arrowHeight ) / 2;

					if ( arrowTopoffset ) {
						$this.find( '.flex-direction-nav a' ).css( 'margin-top', arrowTopoffset );
					}

				}

			} );

		}

		$window.on( 'load', function() {
			setWooSliderArrows();
		} );

		$window.resize( function() {
			setWooSliderArrows();
		} );

	}

	/**
	 * Woo Add to cart notice
	 *
	 * @since 4.8
	 */
	function wooAddToCartNotice() {

		var noticeTxt = wpexLocalize.wcAddedToCartNotice;

		if ( ! noticeTxt ) {
			return;
		}

		var notice      = '';
		var image       = '';
		var productName = '';

		$( 'body' ).on( 'click', '.product .ajax_add_to_cart', function() {
			$( '.wpex-added-to-cart-notice' ).remove(); // prevent build-up

			var parent = $( this ).closest( 'li.product' );
			image = parent.find( '.woocommerce-loop-product__link img:first' );
			productName = parent.find( '.woocommerce-loop-product__title' );

			if ( image.length && productName.length ) {

				notice = '<div class="wpex-added-to-cart-notice"><div class="wpex-inner"><div class="wpex-image"><img src="' + image.attr( 'src' ) + '"></div><div class="wpex-text"><strong>' + productName.text() + '</strong> ' + noticeTxt + '</div></div></div>';
			}

		} ), $( document ).on( 'added_to_cart', function() {
			if ( notice ) {
				$( 'body' ).append( notice );
			}
		} );


	}

} ) ( jQuery );