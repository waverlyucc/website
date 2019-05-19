/**
 * Customizer controls
 *
 * @version 4.8
 */

( function( api, $, window, document, undefined ) {

    "use strict"

    // Bail if customizer object isn't in the DOM.
    if ( ! wp || ! wp.customize ) {
        console.log( 'wp or wp.customize objects not found.' );
        return;
    }

    // Custom Select
    var controls = [
        'wpex-dropdown-pages',
        'wpex-font-family',
        'wpex-fa-icon-select'
    ];

    _.each( controls, function( control ) {

        api.controlConstructor[control] = api.Control.extend( {

            ready: function() {

                this.container.find( 'select' ).chosen( {
                    width           : '100%',
                    search_contains : true
                } );

            }

        } );

    } );

    /**
     * Show/Hide Controls
     *
     */
    var wpexControlDisplay = {

        /**
         * Get and loop through control display settings
         *
         * @since 4.4
         */
        init : function() {

            if ( typeof wpexControlVisibility === 'undefined' ) {
                return;
            }

            var settings = wpexControlVisibility;

            _.each( settings, function( settings, id ) {

                var check = settings.check;
                var value = settings.value;

                api( check, function( setting ) {

                    var isEnabled, linkSettingValueToControlActiveState;

                    isEnabled = function() {
                        var getSetting = setting.get();
                        if ( settings.multiCheck ) {
                            return ( $.inArray( getSetting, value ) != -1 ) ? true : false;
                        } else {
                            if ( 'true' == value ) {
                                return getSetting;
                            } else if ( 'false' == value ) {
                                return getSetting ? false : true;
                            } else {
                                return value == getSetting ? true : false;
                            }
                        }
                    };

                    linkSettingValueToControlActiveState = function( control ) {

                        var setActiveState = function() {
                            control.active.set( isEnabled() );
                        };

                        // FYI: With the following we can eliminate all of our PHP active_callback code.
                        control.active.validate = isEnabled;

                        // Set initial active state.
                        setActiveState();

                        /*
                         * Update activate state whenever the setting is changed.
                         * Even when the setting does have a refresh transport where the
                         * server-side active callback will manage the active state upon
                         * refresh, having this JS management of the active state will
                         * ensure that controls will have their visibility toggled
                         * immediately instead of waiting for the preview to load.
                         * This is especially important if the setting has a postMessage
                         * transport where changing the setting wouldn't normally cause
                         * the preview to refresh and thus the server-side active_callbacks
                         * would not get invoked.
                         */
                        setting.bind( setActiveState );

                    };

                    // Call linkSettingValueToControlActiveState on the site title and tagline controls when they exist.
                    api.control( id, linkSettingValueToControlActiveState );

                } );

            } );

        }

    };

    wpexControlDisplay.init();

} ( wp.customize, jQuery, window, document ) );