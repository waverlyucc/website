( function( $ ) {

    'use strict';

	if ( typeof wpex === 'undefined' ) {
    	console.log( 'wpex is not defined' );
        return;
    }

    function vcexLoadMore() {

    	var self      = this;
	   	var $loadMore = $( '.vcex-loadmore' );

	    $loadMore.each( function() {

	    	var $buttonWrap = $( this );
	    	var $button     = $buttonWrap.children();

	    	var $grid          = $buttonWrap.parent().find( '.wpex-row, .entries, .vcex-recent-news' );
	        var loading        = false;
	        var ajaxUrl        = wpexLocalize.ajaxurl;
	        var loadMoreData   = $button.data();
	        var page           = loadMoreData.page + 1;
	        var maxPages       = loadMoreData.maxPages;
	        var $textSpan      = $button.find( '.vcex-txt' );
	        var text           = loadMoreData.text;
	        var loadingText    = loadMoreData.loadingText;
	        var failedText     = loadMoreData.failedText;

	        $buttonWrap.css( 'min-height', $buttonWrap.outerHeight() ); // prevent jump when showing loader icon

	        $button.on( 'click', function( e ) {

	        	var shortcode = loadMoreData.shortcode; // this gets updated on each refresh

				shortcode = shortcode.replace( ']', ' paged="' + page + '"]' );

				//console.log( shortcode );

	            if ( ! loading ) {

	                loading = true;

	                $button.parent().addClass( 'vcex-loading' );
	                $textSpan.text( loadingText );

	                var data = {
	                    action    : 'vcex_loadmore_ajax_render',
	                    nonce     : loadMoreData.nonce,
	                    shortcode : shortcode
	                };

	                $.post( ajaxUrl, data, function( res ) {

	                	var $newElements = '';

	                    if ( res.success ) {

	                        page = page + 1;

	                        if ( $grid.parent().hasClass( 'vcex-post-type-archive' ) ) {
	                        	$newElements = $( res.data ).find( '.col, .blog-entry' );
	                        } else {
	                        	$newElements = $( res.data ).find( '.vcex-grid-item' );
	                        }

	                        if ( $newElements.length ) {

	                        	$newElements.css( 'opacity', 0 ); // hide until images are loaded

		                        $newElements.each( function() {
		                            var $this = $( this );
		                            if ( $this.hasClass( 'sticky' ) ) {
		                                $this.addClass( 'vcex-duplicate' );
		                            }
		                        } );

		                        $grid.append( $newElements ).imagesLoaded( function() {

		                        	if ( typeof retinajs !== 'undefined' && $.isFunction( retinajs ) ) {
										retinajs();
									}

		                        	wpex.equalHeights();

		                            if ( $grid.hasClass( 'vcex-isotope-grid' ) || $grid.hasClass( 'vcex-navbar-filter-grid' ) ) {
		                            	$grid.isotope( 'appended', $newElements );
		                            	//$grid.isotope().append( $newElements ).isotope( 'appended', $newElements ).isotope( 'layout' );
		                            } else {
										$newElements.css( 'opacity', 1 );
		                            }

		                            wpex.iLightboxSingular( $newElements );
		                            wpex.iLightboxGallery();
		                            wpex.overlayHovers( $grid );
		                            wpex.customHovers();


		                            $( '.wpb_animate_when_almost_visible', $grid ).addClass( 'wpb_start_animation animated' );

		                            wpex.sliderPro( $newElements );

		                            if ( typeof( $.fn.mediaelementplayer ) !== 'undefined' ) {
		                                $newElements.find( 'audio, video' ).mediaelementplayer();
		                            }

		                            $grid.trigger( 'vcexLoadMoreFinished', [$newElements] ); // Use this trigger if you need to run other js functions after items are loaded

		                            var newData  = $( res.data ).find( '.vcex-loadmore a' ).data();
		                            loadMoreData = newData ? newData : loadMoreData;

		                            $button.attr( 'data-shortcode', $( res.data ).find( '.vcex-loadmore a' ).data( 'shortcode' ) );
		                            $button.parent().removeClass( 'vcex-loading' );
									$textSpan.text( text );

		                        	// Hide button
		                            if ( ( page - 1 ) == maxPages ) {
		                                $buttonWrap.hide();
		                            }

		                            // Set loading to false
		                            loading = false;

		                        } ); // End images loaded

		                    } // End $newElements check

		                    else {

		                    	 console.log( res );

		                    }

	                    } // End success

	                    else {

	                        $button.text( failedText );

	                        console.log( res );

	                    }

	                } ).fail( function( xhr, textGridster, e ) {

	                    console.log( xhr.responseText );

	                } );

	            } // end loading check

	            return false;

	        } ); // end click event

	    } );

    }

	$( window ).on( 'load', function() {
		vcexLoadMore();
	} );

} ) ( jQuery );