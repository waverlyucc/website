( function ( $ ) {

	if ( typeof vc === 'undefined' || typeof vc.shortcode_view === 'undefined' ) {
		return false;
	}

	//console.log( window );

	/**
	 * Shortcode vcex_heading
	 */
	window.VcexHeadingView = vc.shortcode_view.extend( {
		changeShortcodeParams: function ( model ) {
			var params;

			window.VcexHeadingView.__super__.changeShortcodeParams.call( this, model );
			params = model.get( 'params' );
			var $title = this.$el.find( '.vcex-heading-text > span' );
			if ( _.isObject( params ) && _.isString( params.text ) ) {
				if ( params.text.match(/^#E\-8_/) ) {
					$title.html( '' );
				} else {
					$title.html( ': ' + params.text );
				}
			}
		}
	} );

} ) ( window.jQuery );