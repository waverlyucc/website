( function( $ ) {
	'use strict';

	var $doc = $( document );

	$doc.ready( function( $ ) {

		function sortServices() {
			$( '#widgets-right .wpex-social-widget-services-list, .customize-control .wpex-social-widget-services-list' ).each( function() {
				var id = $( this ).attr( 'id' ),
					$el = $( '#' + id );
				$el.sortable( {
					placeholder : "placeholder",
					opacity     : 0.6,
					update      : function( event, ui ) {
						if ( wp.customize !== undefined ) {
							$el.find( 'input.ui-sortable-handle' ).trigger( 'change' );
						} else {
							$el.find( 'input.wpex-social-widget-services-hidden-field' ).trigger( 'change' );
						}
					}
				} );
			} );
		}

		sortServices();

		// Customizer support
		$doc.on( 'widget-updated', sortServices );
		$doc.on( 'widget-added', sortServices );

	} );

} ) ( jQuery );