( function( $ ) {
    'use strict';

   $( window ).on( 'load', function() {

	   	var self      = this;
	    var $loadMore = $( '.vcex-loadmore' );

	    $loadMore.each( function() {

	        var $button      = $( this );
	        var $buttonInner = $button.find( '.theme-button-inner' );
	        var loading      = false;
	        var text         = $button.data( 'text' );
	        var ajaxUrl      = wpexLocalize.ajaxurl;
	        var loadingText  = $button.data( 'loading-text' );
	        var failedText   = $button.data( 'failed-text' );
	        var maxPages     = $button.data( 'max-pages' );
	        var $grid        = $button.parent().parent().find( '.wpex-row' );
	        var page         = 2;

	        if ( 1 != maxPages ) {
	            $button.addClass( 'wpex-visible' );
	        }

	        var shortcode = $button.data( 'shortcode' );

	        $button.on( 'click', function() {

				shortcode = shortcode.replace( ']', ' paged="' + page + '"]' );

	            if ( ! loading ) {

	                loading = true;

	                $button.addClass( 'loading' );
	                $buttonInner.text( loadingText );

	                var data = {
	                    action    : 'vcex_loadmore_render',
	                    nonce     : $button.data( 'nonce' ),
	                    shortcode : shortcode,
	                    page      : page,
	                };

	                $.post( ajaxUrl, data, function( res ) {

	                    // Ajax request successful
	                    if ( res.success ) {

	                        // Increase page
	                        page = page + 1;

	                        // Define vars
	                        var $newElements = $( res.data ).find( '.entry' );
	                        $newElements.css( 'opacity', 0 );

	                        // Remove duplicate posts (sticky)
	                        $newElements.each( function() {
	                            var $this = $( this );
	                            if ( $this.hasClass( 'sticky' ) ) {
	                                $this.addClass( 'wpex-duplicate' );
	                            }
	                        } );

	                        $grid.append( $newElements ).imagesLoaded( function() {

	                        	wpex.equalHeights( $grid );
	                            
	                            if ( $grid.hasClass( 'vcex-isotope-grid' ) ) {
	                            	$grid.isotope().append( $newElements ).isotope( 'appended', $newElements ).isotope( 'layout' );
	                            }

	                            wpex.iLightbox( $grid );
	                            wpex.overlayHovers( $grid );

	                            $grid.trigger( 'vcexLoadMoreAddedHidden', [$newElements] );

	                            $newElements.css( 'opacity', 1 );

	                            wpex.sliderPro( $newElements );

	                            if ( typeof( $.fn.mediaelementplayer ) !== 'undefined' ) {
	                                $newElements.find( 'audio, video' ).mediaelementplayer();
	                            }

	                            $grid.trigger( 'vcexLoadMoreAddedVisible', [$newElements] );

	                            // Reset button
	                            $button.removeClass( 'loading' );
	                            $buttonInner.text( text );

	                            // Hide button
	                            if ( ( page - 1 ) == maxPages ) {
	                                $button.hide();
	                            }

	                            // Set loading to false
	                            loading = false;

	                        } ); // End images loaded

	                    } // End success

	                    else {

	                        $buttonInner.text( failedText );

	                        console.log( res );

	                    }

	                } ).fail( function( xhr, textGridster, e ) {

	                    console.log( xhr.responseText );

	                } );

	            } // end loading check

	        } );

	    } );


   } );
   
} ) ( jQuery );