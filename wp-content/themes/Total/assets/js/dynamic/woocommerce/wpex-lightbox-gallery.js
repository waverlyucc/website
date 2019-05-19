( function( $ ) {
    'use strict';

    if ( typeof wpex === 'undefined' ) {
        return;
    }

    var lightboxSettings = wpexLocalize.iLightbox;
    var $gallery = $( '.woocommerce-product-gallery__wrapper' );

     $( $gallery ).each( function() {

     	var $gallery = $( this );
        var $items   = $gallery.find( '.woocommerce-product-gallery__image a' );

       $items.each( function( index, item ) {
			$( item ).attr( 'data-index', index );
		} )

        $items.on( 'click', function( event ) {

        	event.preventDefault(); // prevents issues

            var targets     = [];
            var images      = [];
            var activeIndex = $( this ).data( 'index' );

            $items.each( function() {
                var $this = $( this );
                var href  = $this.attr( 'href' );
                var title = '';
                var img   = $this.find( 'img' );
                if ( wpexLocalize.wcLightboxTitles ) {
                    title = $this.attr( 'title' )
                    if ( img.length ) {
                        title = img.attr( 'data-caption' ) ? img.attr( 'data-caption' ) : img.attr( 'title' );
                    }
                } else {
                    title = null;
                }
                targets.push( href );
                images.push( {
                    URL   : href,
                    title : title,
                    type  : 'image'
                } );
            } );

            if ( targets.length > 1 ) {

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

} ) ( jQuery );