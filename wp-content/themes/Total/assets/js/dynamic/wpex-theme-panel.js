/**
 * Total WordPress Theme Panel JS
 * @version 4.5.4.2
 */
( function( $ ) {

	'use strict';

	// Enable/disable theme panel modules
	function wpexPanelEnableDisableModules() {

		// Show notice
		$( '.wpex-checkbox' ).click( function() {
			$( '.wpex-theme-panel-updated' ).show();
		} );
		
		$( '.wpex-theme-panel .manage-right input[type="text"]' ).change( function() {
			$( '.wpex-theme-panel-updated' ).show();
		} );

		// Save on link click
		$( '.wpex-theme-panel-updated a' ).click( function( e ) {
			e.preventDefault();
			$( '#wpex-theme-panel-form #submit' ).click();
		} );

		// Checkbox change function - tweak classes
		$( '.wpex-checkbox' ).change(function() {
			var $this = $( this ),
				$parentTr = $this.parent( 'th' ).parent( '.wpex-module' );
			if ( $parentTr.hasClass( 'wpex-disabled' ) ) {
				$parentTr.removeClass( 'wpex-disabled' );
			} else {
				$parentTr.addClass( 'wpex-disabled' );
			}
		} );

		$( '.wpex-theme-panel-module-link' ).click( function() {
			$( '.wpex-theme-panel-updated' ).show();
			var $this     = $( this ),
				$ref      = $this.attr( 'href' ),
				$checkbox = $( $ref ).find( '.wpex-checkbox' ),
				$parentTr = $this.parents( '.wpex-module' );
			if ( $checkbox.is( ':checked' ) ) {
				$checkbox.attr( 'checked', false );
			} else {
				$checkbox.attr( 'checked', true );
			}
			if ( $parentTr.hasClass( 'wpex-disabled' ) ) {
				$parentTr.removeClass( 'wpex-disabled' );
			} else {
				$parentTr.addClass( 'wpex-disabled' );
			}
			return false;
		} );

	}

	// Filter
	function wpexPanelFilter() {
		var $filter_buttons = $( '.wpex-filter-active button' );
		$filter_buttons.click( function() {
			var $filterBy = $( this ).data( 'filter-by' );
			$filter_buttons.removeClass( 'active' );
			$( this ).addClass( 'active' );
			$( '.wpex-module' ).removeClass( 'wpex-filterby-hide' );
			if ( 'active' === $filterBy ) {
				$( '.wpex-module' ).each( function() {
					if ( $( this ).hasClass( 'wpex-disabled' ) ) {
						$( this ).addClass( 'wpex-filterby-hide' );
					}
				} );
			} else if ( 'inactive' === $filterBy ) {
				$( '.wpex-module' ).each( function() {
					if ( ! $( this ).hasClass( 'wpex-disabled' ) ) {
						$( this ).addClass( 'wpex-filterby-hide' );
					}
				} );
			}
			return false;
		} );
	}

	// Sort
	function wpexPanelSort() {
		$( '.wpex-theme-panel-sort a' ).click( function() {
			var $data = $( this ).data( 'category' );
			$( '.wpex-theme-panel-sort a' ).removeClass( 'wpex-active-category' );
			$( this ).addClass( 'wpex-active-category' );
			if ( 'all' === $data ) {
				$( '.wpex-module' ).removeClass( 'wpex-sort-hide' );
			} else {
				$( '.wpex-module' ).addClass( 'wpex-sort-hide' );
				$( '.wpex-category-'+ $data ).each( function() {
					$( this ).removeClass( 'wpex-sort-hide' );
				} );
			}
			return false;
		} );
	}

	// Active and non active counters
	function wpexPanelActiveCounters() {
		var activeCount   = $( '.wpex-module.wpex-active' ).length;
		var inactiveCount = $( '.wpex-module.wpex-disabled' ).length;
		$( '.wpex-active-items-btn > span' ).text( '(' + activeCount + ')' );
		$( '.wpex-inactive-items-btn > span' ).text( '(' + inactiveCount + ')' );
	}

	// Help toggle
	function wpexHelpToggle() {
		$( 'a#wpex-help-toggle' ).click( function() {
			$( '#wpex-notice' ).toggle();
			return false;
		} );
	}

	// Chosen dropdowns
	function wpexChosenSelect() {
		if ( undefined === $.fn.chosen ) {
            return;
        }
		$( '.wpex-chosen' ).chosen();
		$( '.wpex-chosen-multiselect' ).chosen( {
			search_contains: true
		} );
		$( '#wpex_header_builder_select_chosen, #wpex_footer_builder_select_chosen' ).css( 'width', '300' );
	}

	// Color picker
	function wpexColorPicker() {
		if ( undefined === $.fn.wpColorPicker ) {
            return;
        }
		$( '.wpex-color-field' ).wpColorPicker();
	}

	// JS tabs
	function wpexPanelTabs() {
		var $tabs = $( '.wpex-panel-js-tabs a' );
		if ( ! $tabs.length ) {
			return;
		}
		var $firstTab     = $( '.wpex-panel-js-tabs a.nav-tab-active' );
		var $firstTabHash = $firstTab.attr( 'href' ).substring(1);
		$( '.wpex-' + $firstTabHash ).show();
		$( $tabs ).each( function() {
			var $this = $( this );
			$this.click( function( e ) {
				e.preventDefault();
				$tabs.removeClass( 'nav-tab-active' );
				$this.addClass( 'nav-tab-active' );
				var $hash = $( this ).attr( 'href' ).substring(1);
				$( '.wpex-tab-content' ).hide();
				$( '.wpex-' + $hash ).show();
			} );
		} );
	}

	// Theme license activation
	function wpexLicenseAjax() {

		var $licenseForm = $( '#wpex-theme-license-form' );
		
		if ( ! $licenseForm.length ) {
			return;
		}

		$licenseForm.submit( function( e ) {
			e.preventDefault();

			var $form            = $( this );
			var $submit          = $form.find( '#submit' );
			var $spinner         = $form.find( '.wpex-spinner' );
			var actionProcess    = $submit.hasClass ( 'activate' ) ? 'activate' : 'deactivate';
			var $licenseField    = $form.find( 'input#wpex_license' );
			var $devlicenseField = $form.find( 'input#wpex_dev_license' );

			$( '.wpex-admin-ajax-notice' ).hide().removeClass( 'notice-warning updated notice-error' );

			$.ajax( {
				type : 'POST',
				url  : ajaxurl,
				data : {
					action     : 'wpex_theme_license_form',
					process    : actionProcess,
					license    : $form.find( 'input#wpex_license' ).val(),
					devlicense : $devlicenseField.is( ':checked' ) ? 'checked' : 0,
					nonce      : $form.find( 'input#wpex_theme_license_form_nonce' ).val()
				},
				beforeSend : function() { 
					$spinner.css( 'opacity', '1' );
					$submit.prop('disabled', true );
				},
				success : function( response ) {

					$spinner.css( 'opacity', '0' );

					$submit.prop('disabled', false );

					//console.log( response );

					if ( response.success ) {

						$devlicenseField.parent().hide();

						if ( 'activate' === actionProcess ) {
							$licenseField.attr( 'readonly', 'readonly' );
							$submit.removeClass( 'activate' ).addClass( 'deactivate' ).val( $submit.data( 'deactivate' ) );
						} else if ( 'deactivate' === actionProcess ) {
							$licenseField.attr( 'placeholder', '' ).removeAttr( 'readonly' );
							$licenseField.val( '' );
							$submit.removeClass( 'deactivate' ).addClass( 'activate' ).val( $submit.data( 'activate' ) );
							location.reload();
						}

					}

					if ( response.message ) {
						$( '.wpex-admin-ajax-notice' ).addClass( response.messageClass ).html( '<p>' + response.message + '</p>' ).show();
					}
				}
			} );

		} );

	}

	// Media upload
	function wpexMediaUpload() {

		// Select & insert image
		var _custom_media = true,
		_orig_send_attachment = wp.media.editor.send.attachment;

		$( '.wpex-media-upload-button' ).click( function() {
			var send_attachment_bkp	= wp.media.editor.send.attachment,
				button = $(this),
				id = button.prev();
			wp.media.editor.send.attachment = function( props, attachment ) {
				if ( _custom_media ) {
					$( id ).val( attachment.id );
					var $preview = button.parent().find( '.wpex-media-live-preview img' );
					var $remove  = button.parent().find( '.wpex-media-remove' );
					if ( $remove.length ) {
						$remove.show();
					}
					if ( $preview.length ) {
						$preview.attr( 'src', attachment.url );
					} else {
						$preview = button.parent().find('.wpex-media-live-preview');
						var $imgSize = $preview.data( 'image-size' ) ? $preview.data( 'image-size' ) : 'auto';
						$preview.append( '<img src="'+ attachment.url +'" style="height:'+ $imgSize +'px;width:'+ $imgSize +'px;" />' );
					}
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				}
			};
			wp.media.editor.open( button );
			return false;
		} );

		$( '.add_media' ).on('click', function() {
			_custom_media = false;
		} );

		$( '.wpex-media-remove' ).each( function() {
			var $this     = $( this );
			var $input    = $this.parent().find( '.wpex-media-input' );
			var $inputVal = $input.val();
			var $preview  = $this.parent().find( '.wpex-media-live-preview' );
			if ( $inputVal ) {
				$this.show();
			}
			$this.on('click', function() {
				$input.val( '' );
				$preview.find( 'img' ).remove();
				$this.hide();
				return false;
			} );
		} );

	}

	// Custom CSS remember to save
	function wpexPanelCustomCSS() {

		// Show notice
		$( '.wpex-custom-css-panel-wrap .form-table' ).click( function() {
			$( '.wpex-remember-to-save' ).show();
		} );

		// Save on link click
		$( '.wpex-custom-css-panel-wrap .wpex-remember-to-save a' ).click( function( e ) {
			e.preventDefault();
			$( '.wpex-custom-css-panel-wrap form #submit' ).click();
		} );

	}

	// Run functions on doc ready
	$( document ).ready( function() {
		wpexPanelEnableDisableModules();
		wpexPanelFilter();
		wpexPanelSort();
		wpexPanelActiveCounters();
		wpexHelpToggle();
		wpexChosenSelect();
		wpexColorPicker();
		wpexPanelTabs();
		wpexMediaUpload();
		wpexLicenseAjax();
		wpexPanelCustomCSS();
	} );

} ) ( jQuery );