( function( $ ) {
    'use strict';

    $.wpexGalleryAdditionalImagesLightbox = {

        init : function() {

            $( document ).ready( function() {
                $.wpexGalleryAdditionalImagesLightbox.runLightbox();
            } );

            $( 'form.variations_form' ).on( 'wc_additional_variation_images_frontend_lightbox', function() {
                $.wpexGalleryAdditionalImagesLightbox.runLightbox();
            } );

        },

        runLightbox : function() {

            var lightboxSettings = wpexLocalize.iLightbox;

            $( "<style>.woocommerce-product-gallery__wrapper{cursor:pointer;}</style>" ).appendTo( 'head' );

            $( 'body' ).on( 'click', '.woocommerce-product-gallery__wrapper', function () {

                event.preventDefault(); // prevents issues

                var iLightboxData = $( this ).data( 'ilightbox' );
                if ( iLightboxData ) {
                    iLightboxData.destroy();
                }

                var $items   = $( '[data-large_image]', $( this ) );
                var images   = [];
                var active   = false;
                var iLightbox = null;

                $items.each( function() {
                    var $parent = $( this ).parent();
                    if ( ! $parent.hasClass( 'clone' ) ) {
                        var largeImage = $( this ).data( 'large_image' );
                        if ( $parent.hasClass( 'flex-active-slide' ) ) {
                            active = largeImage;
                        }
                        images.push( largeImage );
                    }
                } );

                if ( ! $.isEmptyObject( images ) ) {

                    if ( images.length > 1 ) {

                        var activeIndex = $.inArray( active, images );

                        lightboxSettings.startFrom = parseInt( activeIndex );

                        iLightbox = $.iLightBox( images, lightboxSettings );

                    } else {

                        lightboxSettings.controls.arrows     = false;
                        lightboxSettings.controls.mousewheel = false;
                        lightboxSettings.controls.slideshow  = false;
                        lightboxSettings.infinite            = false;

                        iLightbox = $.iLightBox( images, lightboxSettings );

                    }

                    $( this ).data( 'ilightbox', iLightbox );

                }

            } );

        }

    }

    $.wpexGalleryAdditionalImagesLightbox.init();

} ) ( jQuery );