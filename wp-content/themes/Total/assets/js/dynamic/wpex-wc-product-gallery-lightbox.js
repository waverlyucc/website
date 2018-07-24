( function( $ ) {
    'use strict';

    if ( typeof wpex === 'undefined' ) {
        return;
    }

    var lightboxSettings = wpexLocalize.iLightbox;

    $( '.woocommerce-product-gallery__wrapper' ).each( function() {

        var $item = $( this ).find( '.woocommerce-product-gallery__image > a' );

        $item.iLightBox( lightboxSettings );

    } );
   
} ) ( jQuery );