( function( $ ) {
    'use strict';

    if ( typeof wpex === 'undefined' ) {
        return;
    }

    var lightboxSettings = wpexLocalize.iLightbox;

    function wpexWcGalleryLightboxSet() {

        var $gallery = $( '.woocommerce-product-gallery__wrapper' );

        $( $gallery ).each( function() {

            var $this  = $( this );

            $this.css( 'cursor', 'pointer' );

            $this.on( 'click', function( event ) {

            	event.preventDefault(); // prevents issues

                var $items  = $this.find( '.woocommerce-product-gallery__image > a' );
                var images  = [];
                var active = '';

                $items.each( function() {
                    var $this   = $( this );
                    var $parent = $this.parent();
                    if ( ! $parent.hasClass( 'clone' ) ) {
                        var $href = $this.attr( 'href' );
                        if ( $parent.hasClass( 'flex-active-slide' ) ) {
                            active = $href;
                        }
                        images.push( $href );
                    }
                } );

                if ( images.length > 1 ) {

                    var activeIndex = $.inArray( active, images );

                    lightboxSettings.startFrom = parseInt( activeIndex );

                    $.iLightBox( images, lightboxSettings );

                } else {

                    lightboxSettings.controls.arrows     = false;
                    lightboxSettings.controls.mousewheel = false;
                    lightboxSettings.controls.slideshow  = false;
                    lightboxSettings.infinite            = false;

                    $.iLightBox( images, lightboxSettings );

                }

            } );

        } );

    }

    wpex.config.$window.on( 'load', function() {
        wpexWcGalleryLightboxSet();
    } );
   
} ) ( jQuery );