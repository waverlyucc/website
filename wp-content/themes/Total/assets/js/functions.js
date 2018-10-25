/**
 * Project: Total WordPress Theme
 * Description: Initialize all scripts and add custom js
 * Author: WPExplorer
 * Theme URI: http://www.wpexplorer.com
 * Author URI: http://www.wpexplorer.com
 * License: Custom
 * License URI: http://themeforest.net/licenses
 * Version 4.7
 */

var wpex = {};

( function( $ ) {

	'use strict';

	wpex = {

		/**
		 * Main init function
		 *
		 * @since 2.0.0
		 */
		init : function() {
			this.config();
			this.bindEvents();
		},

		/**
		 * Define vars for caching
		 *
		 * @since 2.0.0
		 */
		config : function() {

			this.config = {

				// General
				$window                 : $( window ),
				$document               : $( document ),
				$head                   : $( 'head' ),
				windowWidth             : $( window ).width(),
				windowHeight            : $( window ).height(),
				windowTop               : $( window ).scrollTop(),
				$body                   : $( 'body' ),
				viewportWidth           : '',
				$wpAdminBar             : null,
				isRetina                : false,
				heightChanged           : false,
				widthChanged            : false,
				isRTL                   : false,
				iLightboxSettings       : {},

				// VC
				vcActive                : false,

				// Mobile
				isMobile                : false,
				mobileMenuStyle         : null,
				mobileMenuToggleStyle   : null,
				mobileMenuBreakpoint    : 960,

				// Main Divs
				$siteWrap               : null,
				$siteMain               : null,

				// Header
				$siteHeader             : null,
				siteHeaderStyle         : null,
				siteHeaderHeight        : 0,
				siteHeaderTop           : 0,
				siteHeaderBottom        : 0,
				verticalHeaderActive    : false,
				hasHeaderOverlay        : false,
				hasStickyHeader         : false,
				stickyHeaderStyle       : null,
				hasStickyMobileHeader   : false,
				hasStickyNavbar         : false,

				// Logo
				$siteLogo               : null,
				siteLogoHeight          : 0,
				siteLogoSrc             : null,

				// Nav
				$siteNavWrap            : null,
				$siteNav                : null,
				$siteNavDropdowns       : null,

				// Local Scroll
				$localScrollTargets     : 'li.local-scroll a, a.local-scroll, .local-scroll-link, .local-scroll-link > a',
				localScrollOffset       : 0,
				localScrollSpeed        : 600,
				localScrollEasing       : 'easeInOutCubic',
				localScrollSections     : [],   

				// Topbar
				hasTopBar               : false,
				hasStickyTopBar         : false,
				$stickyTopBar           : null,
				hasStickyTopBarMobile   : false,

				// Footer
				hasFixedFooter          : false

			};

		},

		/**
		 * Bind Events
		 *
		 * @since 2.0.0
		 */
		bindEvents : function() {
			var self = this;

			/*** Run on Document Ready ***/
			self.config.$document.on( 'ready', function() {
				self.initUpdateConfig();
				self.responsiveText();
				self.superfish();
				self.mobileMenu();
				self.navNoClick();
				self.hideEditLink();
				self.menuWidgetAccordion();
				self.inlineHeaderLogo(); // Header 5 logo
				self.menuSearch();
				self.headerCart();
				self.backTopLink();
				self.smoothCommentScroll();
				self.tipsyTooltips();
				self.customHovers();
				self.toggleBar();
				self.localScrollLinks();
				self.customSelects();
				self.wpexOwlCarousel();
				self.autoLightbox();
				self.iLightbox();
				self.overlayHovers();
				self.skillbar();
				self.milestone();
				self.countdown();
				self.typedText();
				self.equalHeights();
				self.archiveMasonryGrids();
				self.isotopeGrids();
				self.responsiveCSS();
				self.vcexFilterNav();
				self.ctf7Preloader();
				self.vcAccessability();
				self.vcPageEditable();
				self.wooGallery();
				self.twentytwenty();
			} );

			/*** Run on Window Load ***/
			self.config.$window.on( 'load', function() {
				self.config.$body.addClass( 'wpex-window-loaded' );
				self.windowLoadUpdateConfig();
				self.megaMenusWidth();
				self.megaMenusTop();
				self.flushDropdownsTop();
				self.fadeIn();
				self.parallax();
				self.cartDropdownRelocate();
				self.sliderPro();
				self.stickyTopBar();
				self.vcTabsTogglesJS();
				self.headerOverlayOffset(); // Add before sticky header ( important )

				// Sticky Header
				if ( self.config.hasStickyHeader ) {
					self.stickyHeaderStyle = wpexLocalize.stickyHeaderStyle;
					if ( 'standard' == self.stickyHeaderStyle || 'shrink' == self.stickyHeaderStyle || 'shrink_animated' == self.stickyHeaderStyle ) {
						self.stickyHeader();
					}
				}

				self.stickyHeaderMenu();
				self.stickyVcexNavbar();
				self.footerReveal();  // Footer Reveal => Must run before fixed footer!!!
				self.fixedFooter();
				self.titleBreadcrumbsFix();

				// Infinite scroll
				if ( $.fn.infinitescroll !== undefined && $( 'div.infinite-scroll-nav' ).length ) {
					self.infiniteScrollInit();
				}

				// Load more
				self.loadMore();

				// Scroll to hash
				if ( wpexLocalize.scrollToHash ) {
					window.setTimeout( function() {
						self.scrollToHash( self );
					}, parseInt( wpexLocalize.scrollToHashTimeout ) );
				}

			} );

			/*** Run on Window Resize ***/
			self.config.$window.resize( function() {

				// Reset
				self.config.widthChanged  = false;
				self.config.heightChanged = false;

				// Window width change
				if ( self.config.$window.width() != self.config.windowWidth ) {
					self.config.widthChanged = true;
					self.widthResizeUpdateConfig();
				}

				// Height changes
				if ( self.config.$window.height() != self.config.windowHeight ) {
					self.config.windowHeight  = self.config.$window.height(); // update height
					self.config.heightChanged = true;
				}

			} );

			/*** Run on Window Scroll ***/
			self.config.$window.scroll( function() {

				// Reset
				self.config.$hasScrolled = false;

				// Yes we actually scrolled
				if ( self.config.$window.scrollTop() != self.config.windowTop ) {
					self.config.$hasScrolled = true;
					self.config.windowTop = self.config.$window.scrollTop();
					self.localScrollHighlight();
				}

			} );

			/*** Run on Orientation Change ***/
			self.config.$window.on( 'orientationchange', function() {
				self.widthResizeUpdateConfig();
				self.isotopeGrids();
				self.vcexFilterNav();
				self.archiveMasonryGrids();
			} );

		},

		/**
		 * Updates config on doc ready
		 *
		 * @since 3.0.0
		 */
		initUpdateConfig: function() {
			var self = this;

			self.config.$body.addClass( 'wpex-docready' );

			// Check if VC is enabled
			self.config.vcActive = this.config.$body.hasClass( 'wpb-js-composer' );

			// Get Viewport width
			self.config.viewportWidth = self.viewportWidth();

			// Check if retina
			self.config.isRetina = self.retinaCheck();
			if ( self.config.isRetina ) {
				self.config.$body.addClass( 'wpex-is-retina' );
			}

			// Mobile check & add mobile class to the header
			if ( self.mobileCheck() ) {
				self.config.isMobile = true;
				self.config.$body.addClass( 'wpex-is-mobile-device' );
			}

			// Define Wp admin bar
			var $wpAdminBar = $( '#wpadminbar' );
			if ( $wpAdminBar.length ) {
				self.config.$wpAdminBar = $wpAdminBar;
			}

			// Define wrap
			var $siteWrap = $( '#wrap' );
			if ( $siteWrap ) {
				self.config.$siteWrap = $siteWrap;
			}

			// Define main
			var $siteMain = $( '#main' );
			if ( $siteMain ) {
				self.config.$siteMain = $siteMain;
			}

			// Define header
			var $siteHeader = $( '#site-header' );
			if ( $siteHeader.length ) {
				self.config.siteHeaderStyle = wpexLocalize.siteHeaderStyle;
				self.config.$siteHeader = $( '#site-header' );
			}

			// Define logo
			var $siteLogo = $( '#site-logo img.logo-img' );
			if ( $siteLogo.length ) {
				self.config.$siteLogo = $siteLogo;
				self.config.siteLogoSrc = self.config.$siteLogo.attr( 'src' );
			}

			// Menu Stuff
			var $siteNavWrap = $( '#site-navigation-wrap' );
			if ( $siteNavWrap.length ) {

				// Define menu
				self.config.$siteNavWrap = $siteNavWrap;
				var $siteNav = $( '#site-navigation', $siteNavWrap );
				if ( $siteNav.length ) {
					self.config.$siteNav = $siteNav;
				}

				// Check if sticky menu is enabled
				if ( wpexLocalize.hasStickyNavbar ) {
					self.config.hasStickyNavbar = true;
				}

				// Store dropdowns
				var $siteNavDropdowns = $( '.dropdown-menu > .menu-item-has-children > ul', $siteNavWrap );
				if ( $siteNavWrap.length ) {
					self.config.$siteNavDropdowns = $siteNavDropdowns;
				}

			}

			// Mobile menu settings
			if ( wpexLocalize.hasMobileMenu ) {
				self.config.mobileMenuStyle       = wpexLocalize.mobileMenuStyle;
				self.config.mobileMenuToggleStyle = wpexLocalize.mobileMenuToggleStyle;
				self.config.mobileMenuBreakpoint  = wpexLocalize.mobileMenuBreakpoint;
			}

			// Check if fixed footer is enabled
			if ( self.config.$body.hasClass( 'wpex-has-fixed-footer' ) ) {
				self.config.hasFixedFooter = true;
			}
			
			// Footer reveal
			self.config.$footerReveal = $( '.footer-reveal-visible' );
			if ( self.config.$footerReveal.length && self.config.$siteWrap && self.config.$siteMain ) {
				self.config.$hasFooterReveal = true;
			}

			// Header overlay
			if ( self.config.$siteHeader && self.config.$body.hasClass( 'has-overlay-header' ) ) {
				self.config.hasHeaderOverlay = true;
			}

			// Top bar enabled
			var $topBarWrap =  $( '#top-bar-wrap' );
			if ( $topBarWrap.length ) {
				self.config.hasTopBar = true;
				if ( $topBarWrap.hasClass( 'wpex-top-bar-sticky' ) ) {
					self.config.$stickyTopBar = $topBarWrap;
					if ( self.config.$stickyTopBar.length ) {
						self.config.hasStickyTopBar = true;
						self.config.hasStickyTopBarMobile = wpexLocalize.hasStickyTopBarMobile;
					}
				}
			}

			// Sticky Header => Mobile Check (must check first)
			self.config.hasStickyMobileHeader = wpexLocalize.hasStickyMobileHeader;

			// Check if sticky header is enabled
			if ( self.config.$siteHeader && wpexLocalize.hasStickyHeader ) {
				self.config.hasStickyHeader = true;
			}

			// Vertical header
			if ( this.config.$body.hasClass( 'wpex-has-vertical-header' ) ) {
				self.config.verticalHeaderActive = true;
			}

			// Local scroll speed
			if ( wpexLocalize.localScrollSpeed ) {
				self.config.localScrollSpeed = parseInt( wpexLocalize.localScrollSpeed );
			}

			// Local scroll easing
			if ( wpexLocalize.localScrollEasing ) {
				self.config.localScrollEasing = wpexLocalize.localScrollEasing;
				if ( 'false' == self.config.localScrollEasing ) {
					self.config.localScrollEasing = 'swing';
				}
			}

			// Get local scrolling sections
			self.config.localScrollSections = self.localScrollSections();

		},

		/**
		 * Updates config on window load
		 *
		 * @since 3.0.0
		 */
		windowLoadUpdateConfig: function() {

			// Header bottom position
			if ( this.config.$siteHeader ) {
				var siteHeaderTop = this.config.$siteHeader.offset().top;
				this.config.windowHeight = this.config.$window.height();
				this.config.siteHeaderHeight = this.config.$siteHeader.outerHeight();
				this.config.siteHeaderBottom = siteHeaderTop + this.config.siteHeaderHeight;
				this.config.siteHeaderTop = siteHeaderTop;
				if ( this.config.$siteLogo ) {
					this.config.siteLogoHeight = this.config.$siteLogo.height();
				}
			}

			// Set localScrollOffset after site is loaded to make sure it includes dynamic items
			this.config.localScrollOffset = this.parseLocalScrollOffset( 'init' );

		},

		/**
		 * Updates config whenever the window is resized
		 *
		 * @since 3.0.0
		 */
		widthResizeUpdateConfig: function() {

			// Update main configs
			this.config.windowHeight  = this.config.$window.height();
			this.config.windowWidth   = this.config.$window.width();
			this.config.windowTop     = this.config.$window.scrollTop();
			this.config.viewportWidth = this.viewportWidth();

			// Update header height
			if ( this.config.$siteHeader ) {
				this.config.siteHeaderHeight = this.config.$siteHeader.outerHeight();
			}

			// Get logo height
			if ( this.config.$siteLogo ) {
				this.config.siteLogoHeight = this.config.$siteLogo.height();
			}

			// Vertical Header
			if ( this.config.windowWidth < 960 ) {
				this.config.verticalHeaderActive = false;
			} else if ( this.config.$body.hasClass( 'wpex-has-vertical-header' ) ) {
				this.config.verticalHeaderActive = true;
			}

			// Local scroll offset => update last
			this.config.localScrollOffset = this.parseLocalScrollOffset( 'resize' );

			// Re-run functions
			this.megaMenusWidth();
			this.cartDropdownRelocate();
			this.overlayHovers();
			this.responsiveText();

		},

		/**
		 * Retina Check
		 *
		 * @since 3.4.0
		 */
		retinaCheck: function() {
			var mediaQuery = '(-webkit-min-device-pixel-ratio: 1.5), (min--moz-device-pixel-ratio: 1.5), (-o-min-device-pixel-ratio: 3/2), (min-resolution: 1.5dppx)';
			if ( window.devicePixelRatio > 1 ) {
				return true;
			}
			if ( window.matchMedia && window.matchMedia( mediaQuery ).matches ) {
				return true;
			}
			return false;
		},

		/**
		 * Mobile Check
		 *
		 * @since 2.1.0
		 */
		mobileCheck: function() {
			if ( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test( navigator.userAgent ) ) {
				return true;
			}
		},

		/**
		 * Viewport width
		 *
		 * @since 3.4.0
		 */
		viewportWidth: function() {
			var e = window, a = 'inner';
			if ( ! ( 'innerWidth' in window ) ) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return e[ a+'Width' ];
		},

		/**
		 * Superfish menus
		 *
		 * @since 2.0.0
		 */
		superfish: function() {

			if ( ! this.config.$siteNav || undefined === $.fn.superfish ) {
				return;
			}

			$( 'ul.sf-menu', this.config.$siteNav ).superfish( {
				delay     : wpexLocalize.superfishDelay,
				speed     : wpexLocalize.superfishSpeed,
				speedOut  : wpexLocalize.superfishSpeedOut,
				cssArrows : false,
				disableHI : false,
				animation   : {
					opacity : 'show'
				},
				animationOut : {
					opacity : 'hide'
				}
			} );
			
		},

		 /**
		 * MegaMenus Width
		 *
		 * @since 2.0.0
		 */
		megaMenusWidth: function() {

			if ( ! wpexLocalize.megaMenuJS
				|| 'one' != this.config.siteHeaderStyle
				|| ! this.config.$siteNavDropdowns
				|| ! this.config.$siteNavWrap.is( ':visible' )
			) {
				return;
			}

			// Define megamenu
			var $megamenu = $( '.megamenu > ul', this.config.$siteNavWrap );

			// Don't do anything if there isn't any megamenu
			if ( ! $megamenu.length ) {
				return;
			}

			var $headerContainerWidth       = this.config.$siteHeader.find( '.container' ).outerWidth(),
				$navWrapWidth               = this.config.$siteNavWrap.outerWidth(),
				$siteNavigationWrapPosition = parseInt( this.config.$siteNavWrap.css( 'right' ) );

			if ( 'auto' == $siteNavigationWrapPosition ) {
				$siteNavigationWrapPosition = 0;
			}

			var $megaMenuNegativeMargin = $headerContainerWidth-$navWrapWidth-$siteNavigationWrapPosition;

			$megamenu.css( {
				'width'       : $headerContainerWidth,
				'margin-left' : -$megaMenuNegativeMargin
			} );

		},

		/**
		 * MegaMenus Top Position
		 *
		 * @since 2.0.0
		 */
		megaMenusTop: function() {
			var self = this;
			if ( ! self.config.$siteNavDropdowns || 'one' != self.config.siteHeaderStyle ) {
				return;
			}

			var $megamenu = $( '.megamenu > ul', self.config.$siteNavWrap );
			if ( ! $megamenu.length ) return; // Don't do anything if there isn't any megamenu

			function setPosition() {
				if ( self.config.$siteNavWrap.is( ':visible' ) ) {
					var $headerHeight = self.config.$siteHeader.outerHeight();
					var $navHeight    = self.config.$siteNavWrap.outerHeight();
					var $megaMenuTop  = $headerHeight - $navHeight;
					$megamenu.css( {
						'top' : $megaMenuTop/2 + $navHeight
					} );
				}
			}
			setPosition();

			// update on scroll
			this.config.$window.scroll( function() {
				setPosition();
			} );

			// Update on resize
			this.config.$window.resize( function() {
				setPosition();
			} );

			// Update on hover just incase
			$( '.megamenu > a', self.config.$siteNav ).hover( function() {
				setPosition();
			} );

		},

		/**
		 * FlushDropdowns top positioning
		 *
		 * @since 2.0.0
		 */
		flushDropdownsTop: function() {
			var self = this;
			if ( ! self.config.$siteNavDropdowns || ! self.config.$siteNavWrap.hasClass( 'wpex-flush-dropdowns' ) ) {
				return;
			}

			// Set position
			function setPosition() {
				if ( self.config.$siteNavWrap.is( ':visible' ) ) {
					var $headerHeight      = self.config.$siteHeader.outerHeight();
					var $siteNavWrapHeight = self.config.$siteNavWrap.outerHeight();
					var $dropTop           = $headerHeight - $siteNavWrapHeight;
					self.config.$siteNavDropdowns.css( 'top', $dropTop/2 + $siteNavWrapHeight );
				}
			}
			setPosition();

			// Update on scroll
			this.config.$window.scroll( function() {
				setPosition();
			} );

			// Update on resize
			this.config.$window.resize( function() {
				setPosition();
			} );

			// Update on hover
			$( '.wpex-flush-dropdowns li.menu-item-has-children > a' ).hover( function() {
				setPosition();
			} );

		},

		/**
		 * Mobile Menu
		 *
		 * @since 2.0.0
		 */
		mobileMenu: function() {
			if ( 'sidr' == this.config.mobileMenuStyle && typeof wpexLocalize.sidrSource !== 'undefined' ) {
				this.mobileMenuSidr();
			} else if ( 'toggle' == this.config.mobileMenuStyle ) {
				this.mobileMenuToggle();
			} else if ( 'full_screen' == this.config.mobileMenuStyle ) {
				this.mobileMenuFullScreen();
			}
		},

		/**
		 * Mobile Menu
		 *
		 * @since 3.6.0
		 */
		mobileMenuSidr: function() {
			var self       = this,
				$toggleBtn = $( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a' );

			// Add dark overlay to content
			self.config.$body.append( '<div class="wpex-sidr-overlay wpex-hidden"></div>' );
			var $sidrOverlay = $( '.wpex-sidr-overlay' );

			// Add active class to toggle button
			$toggleBtn.click( function() {
				$( this ).toggleClass( 'wpex-active' );
			} );

			// Add sidr
			$toggleBtn.sidr( {
				name     : 'sidr-main',
				source   : wpexLocalize.sidrSource,
				side     : wpexLocalize.sidrSide,
				displace : wpexLocalize.sidrDisplace,
				speed    : parseInt( wpexLocalize.sidrSpeed ),
				renaming : true,
				bind     : 'click',

				// Callbacks
				onOpen: function() {

					// Add extra classname
					$( '#sidr-main' ).addClass( 'wpex-mobile-menu' );

					// Prevent body scroll
					if ( wpexLocalize.sidrBodyNoScroll ) {
						self.config.$body.addClass( 'wpex-noscroll' );
					}

					// FadeIn Overlay
					$sidrOverlay.fadeIn( wpexLocalize.sidrSpeed, function() {
						$sidrOverlay.addClass( 'wpex-custom-cursor' );
					} );

					// Close sidr when clicking on overlay
					$( '.wpex-sidr-overlay' ).on( 'click', function( event ) {
						$.sidr( 'close', 'sidr-main' );
						return false;
					} );

				},

				onClose: function() {

					// Remove active class
					$toggleBtn.removeClass( 'wpex-active' );

					// Remove body noscroll class
					if ( wpexLocalize.sidrBodyNoScroll ) {
						self.config.$body.removeClass( 'wpex-noscroll' );
					}
					
					// FadeOut overlay
					$sidrOverlay.removeClass( 'wpex-custom-cursor' ).fadeOut( wpexLocalize.sidrSpeed );
					
				},

				onCloseEnd: function() {

					// Remove active dropdowns
					$( '.sidr-class-menu-item-has-children.active' ).removeClass( 'active' ).find( 'ul' ).hide();

					// Re-trigger stretched rows to prevent issues if browser was resized while
					// sidr was open
					if ( $.fn.vc_rowBehaviour !== undefined ) {
						vc_rowBehaviour();
					}

				}

			} );

			// Cache main sidebar var
			var $sidrMain = $( '#sidr-main' );

			// Sidr dropdown toggles
			var $sidrMenu             = $( '.sidr-class-dropdown-menu', $sidrMain ),
				$sidrDropdownTargetEl = $( '.sidr-class-menu-item-has-children > a', $sidrMenu );

			// Add dropdown toggle (arrow)
			$( '.sidr-class-menu-item-has-children', $sidrMenu )
				.children( 'a' )
				.append( '<span class="sidr-class-dropdown-toggle"></span>' );

			// Add toggle click event
			$sidrDropdownTargetEl.on( 'click', function( event ) {

				var $parentEl = $( this ).parent( 'li' );

				if ( ! $parentEl.hasClass( 'active' ) ) {
					var $allParentLis = $parentEl.parents( 'li' );
					$( '.sidr-class-menu-item-has-children', $sidrMenu )
						.not( $allParentLis )
						.removeClass( 'active' )
						.children( 'ul' )
						.stop( true, true )
						.slideUp( 'fast' );
					$parentEl.addClass( 'active' ).children( 'ul' ).stop( true, true ).slideDown( 'fast' );
				} else {
					$parentEl.removeClass( 'active' );
					$parentEl.find( 'li' ).removeClass( 'active' ); // Remove active from sub-drops
					$parentEl.find( 'ul' ).stop( true, true ).slideUp( 'fast' );       // Hide all drops
				}

				return false;

			} );

			// Loop through parent items and add to dropdown if they have a link
			var $parents = $( 'li.sidr-class-menu-item-has-children > a', $sidrMenu );

			$parents.each( function() {

				var $this = $( this );

				if ( $this && $this.attr( 'href' ) && '#' != $this.attr( 'href' ) ) {
					var $parent = $this.parent( 'li' ),
						el      = $parent.clone();
					$this.removeAttr( 'data-ls_linkto' );
					$parent.removeClass( 'sidr-class-local-scroll' );
					el.removeClass( 'sidr-class-menu-item-has-children sidr-class-dropdown' );
					el.find( 'a' ).removeClass();
					el.find( 'ul, .sidr-class-dropdown-toggle' ).remove().end().prependTo( $this.next( 'ul' ) );
				}

			} );

			// Re-name font Icons to correct classnames
			$( "[class*='sidr-class-fa']", $sidrMain ).attr( 'class',
				function( i, c ) {
				c = c.replace( 'sidr-class-fa', 'fa' );
				c = c.replace( 'sidr-class-fa-', 'fa-' );
				return c;
			} );

			// Close sidr when clicking toggle
			$( '.sidr-class-wpex-close > a', $sidrMain ).on( 'click', function( e ) {
				e.preventDefault();
				$.sidr( 'close', 'sidr-main' );
			} );

			// Close on resize past mobile menu breakpoint
			self.config.$window.resize( function() {
				if ( self.config.viewportWidth >= self.config.mobileMenuBreakpoint ) {
					$.sidr( 'close', 'sidr-main' );
				}
			} );

			// Close sidr when clicking local scroll link
			$( 'li.sidr-class-local-scroll > a', $sidrMain ).click( function() {
				var $hash = this.hash;
				if ( $.inArray( $hash, self.config.localScrollSections ) > -1 ) {
					$.sidr( 'close', 'sidr-main' );
					self.scrollTo( $hash );
					return false;
				}
			} );

			// Remove mobile menu alternative if on page to prevent duplicate links
			if ( $( '#mobile-menu-alternative' ).length ) {
				$( '#mobile-menu-alternative' ).remove();
			}

		},

		/**
		 * Toggle Mobile Menu
		 *
		 * @since 3.6.0
		 */
		mobileMenuToggle: function() {

			var self                = this,
				$position           = wpexLocalize.mobileToggleMenuPosition,
				$classes            = 'mobile-toggle-nav wpex-mobile-menu wpex-clr wpex-togglep-'+ $position,
				$mobileMenuContents = '',
				$mobileSearch       = $( '#mobile-menu-search' ),
				$appendTo           = self.config.$siteHeader,
				$toggleBtn          = $( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a' );

			// Insert nav in fixed_top mobile menu
			if ( 'fixed_top' == self.config.mobileMenuToggleStyle ) {
				$appendTo = $( '#wpex-mobile-menu-fixed-top' );
				if ( $appendTo.length ) {
					$appendTo.append( '<nav class="'+ $classes +'" aria-label="Mobile menu"></nav>' );
				}
			}

			// Absolute position
			else if ( 'absolute' == $position ) {
				if ( 'navbar' == self.config.mobileMenuToggleStyle ) {
					$appendTo = $( '#wpex-mobile-menu-navbar' );
					if ( $appendTo.length ) {
						$appendTo.append( '<nav class="'+ $classes +'" aria-label="Mobile menu"></nav>' );
					}
				} else if ( $appendTo ) {
					$appendTo.append( '<nav class="'+ $classes +'" aria-label="Mobile menu"></nav>' );
				}
			}

			// Insert afterSelf
			else if ( 'afterself' == $position ) {

				$appendTo = $( '#wpex-mobile-menu-navbar' );
				
				$( '<nav class="'+ $classes +'" aria-label="Mobile menu"></nav>' ).insertAfter( $appendTo );

			// Normal toggle insert (static)
			} else {
				$( '<nav class="'+ $classes +'" aria-label="Mobile menu"></nav>' ).insertAfter( $appendTo );
			}

			// Store Nav in cache
			var $mobileToggleNav = $( '.mobile-toggle-nav' );

			// Grab all content from menu and add into mobile-toggle-nav element
			if ( $( '#mobile-menu-alternative' ).length ) {
				$mobileMenuContents = $( '#mobile-menu-alternative .dropdown-menu' ).html();
				$( '#mobile-menu-alternative' ).remove();
			} else {
				$mobileMenuContents = $( '.dropdown-menu', self.config.$siteNav ).html();
			}
			$mobileToggleNav.html( '<ul class="mobile-toggle-nav-ul">' + $mobileMenuContents + '</ul>' );

			// Remove all styles
			$( '.mobile-toggle-nav-ul, .mobile-toggle-nav-ul *' ).children().each( function() {
				$( this ).removeAttr( 'style' );
			} );

			// Add classes where needed
			$( '.mobile-toggle-nav-ul' ).addClass( 'container' );

			// Loop through parent items and add to dropdown if they have a link
			var parseDropParents = false;
			if ( ! parseDropParents ) {

				var $parents = $mobileToggleNav.find( 'li.menu-item-has-children > a' );

				$parents.each( function() {

					var $this = $( this );

					if ( $this && $this.attr( 'href' ) && '#' != $this.attr( 'href' ) ) {
						var $parent = $this.parent( 'li' ),
							el      = $parent.clone();
						$parent.removeClass( 'local-scroll' );
						$this.removeAttr( 'data-ls_linkto' );
						el.removeClass( 'menu-item-has-children' );
						el.find( 'ul, .wpex-open-submenu' ).remove().end().prependTo( $this.next( 'ul' ) );
					}

				} );

				parseDropParents = true;

			}

			// Add toggles
			var dropDownParents = $mobileToggleNav.find( '.menu-item-has-children' );

			dropDownParents.children( 'a' ).append( '<span class="wpex-open-submenu" aria-haspopup="true"></span>' );

			// Add toggle click event
			var $dropdownTargetEl = $dropdownTargetEl = $( '.menu-item-has-children > a', $mobileToggleNav );
			$dropdownTargetEl.on( 'click', function( event ) {

				var $parentEl = $( this ).parent( 'li' );

				if ( ! $parentEl.hasClass( 'active' ) ) {
					var $allParentLis = $parentEl.parents( 'li' );
					$( '.menu-item-has-children', $mobileToggleNav )
						.not( $allParentLis )
						.removeClass( 'active' )
						.children( 'ul' )
						.stop( true, true )
						.slideUp( 'fast' );
					$parentEl.addClass( 'active' ).children( 'ul' ).stop( true, true ).slideDown( 'fast' );
				} else {
					$parentEl.removeClass( 'active' );
					$parentEl.find( 'li' ).removeClass( 'active' ); // Remove active from sub-drops
					$parentEl.find( 'ul' ).stop( true, true ).slideUp( 'fast' );       // Hide all drops
				}

				return false;

			} );

			// On Show
			function openToggle( $button ) {
				if ( wpexLocalize.animateMobileToggle ) {
					$mobileToggleNav.stop( true, true ).slideDown( 'fast' ).addClass( 'visible' );
				} else {
					$mobileToggleNav.addClass( 'visible' );
				}
				$button.addClass( 'wpex-active' );
			}

			// On Close
			function closeToggle( $button ) {
				if ( wpexLocalize.animateMobileToggle ) {
					$mobileToggleNav.stop( true, true ).slideUp( 'fast' ).removeClass( 'visible' );
				} else {
					$mobileToggleNav.removeClass( 'visible' );
				}
				$mobileToggleNav.find( 'li.active > ul' ).stop( true, true ).slideUp( 'fast' );
				$mobileToggleNav.find( '.active' ).removeClass( 'active' );
				$button.removeClass( 'wpex-active' );
			}

			// Show/Hide
			$toggleBtn.on( 'click', function( e ) {
				if ( $mobileToggleNav.hasClass( 'visible' ) ) {
					closeToggle( $( this ) );
				} else {
					openToggle( $( this ) );
				}
				return false;
			} );

			// Close on resize
			self.config.$window.resize( function() {
				if ( self.config.viewportWidth >= self.config.mobileMenuBreakpoint && $mobileToggleNav.hasClass( 'visible' ) ) {
					closeToggle( $toggleBtn );
				}
			} );

			// Add search to toggle menu
			if ( $mobileSearch.length ) {
				$mobileToggleNav.append( '<div class="mobile-toggle-nav-search container"></div>' );
				$( '.mobile-toggle-nav-search' ).append( $mobileSearch );
			}

		},

		/**
		 * Overlay Mobile Menu
		 *
		 * @since 3.6.0
		 */
		mobileMenuFullScreen: function() {
			var self          = this,
				$style        = wpexLocalize.fullScreenMobileMenuStyle ? wpexLocalize.fullScreenMobileMenuStyle : false, // prevent undefined class
				$mobileSearch = $( '#mobile-menu-search' ),
				$menuHTML     = '';

			// Insert new nav
			self.config.$body.append( '<div class="full-screen-overlay-nav wpex-mobile-menu wpex-clr ' + $style + '"><span class="full-screen-overlay-nav-close">&times;</span><nav class="full-screen-overlay-nav-ul-wrapper"><ul class="full-screen-overlay-nav-ul"></ul></nav></div>' );

			var $navUL = $( '.full-screen-overlay-nav-ul' );

			// Grab all content from menu and add into mobile-toggle-nav element
			if ( $( '#mobile-menu-alternative' ).length ) {
				$menuHTML = $( '#mobile-menu-alternative .dropdown-menu' ).html();
				$( '#mobile-menu-alternative' ).remove();
			} else {
				$menuHTML = $( '#site-navigation .dropdown-menu' ).html();
			}
			$navUL.html( $menuHTML );

			// Cache element
			var $nav = $( '.full-screen-overlay-nav' );

			// Remove all styles
			$( '.full-screen-overlay-nav, .full-screen-overlay-nav *' ).children().each( function() {
				$( this ).removeAttr( 'style' );
			} );

			// Loop through parent items and add to dropdown if they have a link
			var parseDropParents = false;
			if ( ! parseDropParents ) {

				var $parents = $nav.find( 'li.menu-item-has-children > a' );

				$parents.each( function() {

					var $this = $( this );

					if ( $this && $this.attr( 'href' ) && '#' != $this.attr( 'href' ) ) {
						var $parent = $this.parent( 'li' ),
							el      = $parent.clone();
						$parent.removeClass( 'local-scroll' );
						$this.removeAttr( 'data-ls_linkto' );
						el.removeClass( 'menu-item-has-children' );
						el.find( 'ul' ).remove().end().prependTo( $this.next( 'ul' ) );
					}

				} );

				parseDropParents = true;

			}

			// Add toggle click event
			var $dropdownTargetEl = $nav.find( 'li.menu-item-has-children > a' );
			$dropdownTargetEl.on( 'click', function( event ) {

				var $parentEl = $( this ).parent( 'li' );

				if ( ! $parentEl.hasClass( 'wpex-active' ) ) {
					var $allParentLis = $parentEl.parents( 'li' );
					$nav.find( '.menu-item-has-children' )
						.not( $allParentLis )
						.removeClass( 'wpex-active' )
						.children( 'ul' )
						.stop( true, true )
						.slideUp( 'fast' );
					$parentEl.addClass( 'wpex-active' ).children( 'ul' ).stop( true, true ).slideDown( {
						duration: 'normal',
						easing: 'easeInQuad'
					} );
				} else {
					$parentEl.removeClass( 'wpex-active' );
					$parentEl.find( 'li' ).removeClass( 'wpex-active' ); // Remove active from sub-drops
					$parentEl.find( 'ul' ).stop( true, true ).slideUp( 'fast' ); // Hide all drops
				}

				// Return false
				return false;

			} );

			// Show
			$( '.mobile-menu-toggle' ).on( 'click', function() {
				$nav.addClass( 'visible' );
				self.config.$body.addClass( 'wpex-noscroll' );
				return false;
			} );

			// Hide overlay when clicking local scroll links
			$( '.local-scroll > a', $nav ).click( function() {
				var $hash = this.hash;
				if ( $.inArray( $hash, self.config.localScrollSections ) > -1 ) {
					$nav.removeClass( 'visible' );
					self.config.$body.removeClass( 'wpex-noscroll' );
					return false;
				}
			} );

			// Hide when clicking close button
			$( '.full-screen-overlay-nav-close' ).on( 'click', function() {
				$nav.removeClass( 'visible' );
				$nav.find( 'li.wpex-active > ul' ).stop( true, true ).slideUp( 'fast' );
				$nav.find( '.wpex-active' ).removeClass( 'wpex-active' );
				self.config.$body.removeClass( 'wpex-noscroll' );
				return false;
			} );

			// Add search to toggle menu
			if ( $mobileSearch.length ) {
				$navUL.append( $mobileSearch );
				$( '#mobile-menu-search' ).wrap( '<li class="wpex-search"></li>' );
			}

		},

		/**
		 * Prevent clickin on links
		 *
		 * @since 2.0.0
		 */
		navNoClick: function() {
			$( 'li.nav-no-click > a, li.sidr-class-nav-no-click > a' ).on( 'click', function() {
				return false;
			} );
		},

		/**
		 * Header Search
		 *
		 * @since 2.0.0
		 */
		menuSearch: function() {
			var self      = this;
			var $toggleEl = '';
			var $wrapEl   = $( '.header-searchform-wrap' );

			// Alter search placeholder & autocomplete
			if ( $wrapEl.length ) {
				if ( $wrapEl.data( 'placeholder' ) ) {
					$wrapEl.find( 'input[type="search"]' ).attr( 'placeholder', $wrapEl.data( 'placeholder' ) );
				}
				if ( $wrapEl.data( 'disable-autocomplete' ) ) {
					$wrapEl.find( 'input[type="search"]' ).attr( 'autocomplete', 'off' )
				}
			}

			/**** Menu Search > Dropdown ****/
			if ( 'drop_down' == wpexLocalize.menuSearchStyle ) {

				$toggleEl = $( 'a.search-dropdown-toggle, a.mobile-menu-search' );
				var $searchDropdownForm = $( '#searchform-dropdown' );

				$toggleEl.click( function( event ) {
					
					// Display search form
					$searchDropdownForm.toggleClass( 'show' );
				   
					// Active menu item
					$( this ).parent( 'li' ).toggleClass( 'active' );
				   
					// Focus
					var $transitionDuration = $searchDropdownForm.css( 'transition-duration' );
					$transitionDuration = $transitionDuration.replace( 's', '' ) * 1000;
					if ( $transitionDuration ) {
						setTimeout( function() {
							$searchDropdownForm.find( 'input[type="search"]' ).focus();
						}, $transitionDuration );
					}
					
					// Hide other things
					$( 'div#current-shop-items-dropdown' ).removeClass( 'show' );
					$( 'li.wcmenucart-toggle-dropdown' ).removeClass( 'active' );
					
					// Return false
					return false;

				} );

				// Close on doc click
				self.config.$document.on( 'click', function( event ) {
					if ( ! $( event.target ).closest( '#searchform-dropdown.show' ).length ) {
						$toggleEl.parent( 'li' ).removeClass( 'active' );
						$searchDropdownForm.removeClass( 'show' );
					}
				} );

			}

			/**** Menu Search > Overlay Modal ****/
			else if ( 'overlay' == wpexLocalize.menuSearchStyle ) {

				$toggleEl = $( 'a.search-overlay-toggle, a.mobile-menu-search, li.search-overlay-toggle > a' );
				var $overlayEl = $( '#wpex-searchform-overlay' );
				var $inner = $overlayEl.find( '.wpex-inner' );

				$toggleEl.on( 'click', function( event ) {
					$overlayEl.toggleClass( 'active' );
					$overlayEl.find( 'input[type="search"]' ).val( '' );
					if ( $overlayEl.hasClass( 'active' ) ) {
						var $overlayElTransitionDuration = $overlayEl.css( 'transition-duration' );
						$overlayElTransitionDuration = $overlayElTransitionDuration.replace( 's', '' ) * 1000;
						setTimeout( function() {
							$overlayEl.find( 'input[type="search"]' ).focus();
						}, $overlayElTransitionDuration );
					}
					return false;
				} );

				// Close searchforms
				$inner.click( function( event ) {
					event.stopPropagation();
				} );
				$overlayEl.click( function() {
					$overlayEl.removeClass( 'active' );
				} );

			}
			
			/**** Menu Search > Header Replace ****/
			else if ( 'header_replace' == wpexLocalize.menuSearchStyle ) {

				$toggleEl = $( 'a.search-header-replace-toggle, a.mobile-menu-search' );
				var $headerReplace = $( '#searchform-header-replace' );

				// Show
				$toggleEl.click( function( event ) {

					// Display search form
					$headerReplace.toggleClass( 'show' );

					// Focus
					var $transitionDuration = $headerReplace.css( 'transition-duration' );
					$transitionDuration = $transitionDuration.replace( 's', '' ) * 1000;
					if ( $transitionDuration ) {
						setTimeout( function() {
							$headerReplace.find( 'input[type="search"]' ).focus();
						}, $transitionDuration );
					}

					// Return false
					return false;
					
				} );

				// Close on click
				$( '#searchform-header-replace-close' ).click( function() {
					$headerReplace.removeClass( 'show' );
					return false;
				} );

				// Close on doc click
				self.config.$document.on( 'click', function( event ) {
					if ( ! $( event.target ).closest( $( '#searchform-header-replace.show' ) ).length ) {
						$headerReplace.removeClass( 'show' );
					}
				} );
			}

		},

		/**
		 * Header Cart
		 *
		 * @since 2.0.0
		 */
		headerCart: function() {

			if ( $( 'a.wcmenucart' ).hasClass( 'go-to-shop' ) ) {
				return;
			}

			var $toggle = $( '.toggle-cart-widget, li.toggle-header-cart > a' );
			if ( ! $toggle.length ) return;

			// Drop-down
			if ( 'drop_down' == wpexLocalize.wooCartStyle ) {

				var $dropdown = $( 'div#current-shop-items-dropdown' );
				if ( ! $dropdown.length ) return;

				// Display cart dropdown
				$toggle.click( function() {
					$( '#searchform-dropdown' ).removeClass( 'show' );
					$( 'a.search-dropdown-toggle' ).parent( 'li' ).removeClass( 'active' );
					$dropdown.toggleClass( 'show' );
					$( this ).toggleClass( 'active' );
					return false;
				} );

				// Hide cart dropdown
				$dropdown.click( function( event ) {
					event.stopPropagation();
				} );
				this.config.$document.click( function() {
					$dropdown.removeClass( 'show' );
					$toggle.removeClass( 'active' );
				} );

				/* Prevent body scroll on current shop dropdown - seems buggy...
				$( '#current-shop-items-dropdown' ).bind( 'mousewheel DOMMouseScroll', function ( e ) {
					var e0 = e.originalEvent,
						delta = e0.wheelDelta || -e0.detail;
					this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
					e.preventDefault();
				} );*/

			}

			// Modal
			else if ( 'overlay' == wpexLocalize.wooCartStyle ) {

				var $overlayEl = $( '#wpex-cart-overlay' );
				var $inner = $overlayEl.find( '.wpex-inner' );

				$toggle.on( 'click', function( event ) {
					$overlayEl.toggleClass( 'active' );
					return false;
				} );

				// Close searchforms
				$inner.click( function( event ) {
					event.stopPropagation();
				} );
				$overlayEl.click( function() {
					$overlayEl.removeClass( 'active' );
				} );

			}

		},

		/**
		 * Automatically add padding to row to offset header
		 *
		 * @since 3.6.0
		 */
		headerOverlayOffset: function() {
			var $offset_element = $( '.add-overlay-header-offset' );
			if ( $offset_element.length ) {
				var self = this;
				var $height = self.config.siteHeaderHeight;
				if ( ! $height ) return;
				var $offset = $( '<div class="overlay-header-offset-div" style="height:'+ $height +'px"></div>' );
				$offset_element.prepend( $offset );
				self.config.$window.resize( function() {
					$offset.css( 'height', self.config.siteHeaderHeight );
				} );
			}
		},

		/**
		 * Relocate the cart for specific header styles
		 *
		 * @todo remove if possible
		 * @since 2.0.0
		 */
		cartDropdownRelocate: function() {

			// Validate first
			if ( this.config.hasHeaderOverlay
				|| ! this.config.$siteHeader
				|| ! this.config.$siteHeader.hasClass( 'wpex-reposition-cart-search-drops' )
			) {
				return;
			}

			// Get last menu item
			var $lastMenuItem = $( '.dropdown-menu > li:nth-last-child(1)', this.config.$siteNav );
			if ( ! $lastMenuItem.length ) {
				return;
			}

			// Define vars
			var $shopDrop           = $( '#current-shop-items-dropdown' );
			var $lastMenuItemOffset = $lastMenuItem.position();
			var $leftPosition       = '';

			// Position Woo dropdown
			if ( $shopDrop.length ) {

				if ( wpexLocalize.isRTL ) {

					$leftPosition = $lastMenuItemOffset.left;

				} else {

					$leftPosition = $lastMenuItemOffset.left - $shopDrop.outerWidth() + $lastMenuItem.width();

				}

				 $shopDrop.css( {
					'right' : 'auto',
					'left'  : $leftPosition
				} );

			}

		},

		/**
		 * Hide post edit link
		 *
		 * @since 2.0.0
		 */
		hideEditLink: function() {
			$( 'a.hide-post-edit', $( '#content' ) ).click( function() {
				$( 'div.post-edit' ).hide();
				return false;
			} );
		},

		/**
		 * Custom menu widget accordion
		 *
		 * @since 4.5.4.2
		 */
		menuWidgetAccordion: function() {
			
			if ( ! wpexLocalize.menuWidgetAccordion ) {
				return;
			}

			var self = this;

			// Open toggle for active page
			$( '#sidebar .widget_nav_menu .current-menu-ancestor, .widget_nav_menu_accordion .widget_nav_menu .current-menu-ancestor', self.config.$siteMain ).addClass( 'active' ).children( 'ul' ).show();

			// Toggle items
			$( '#sidebar .widget_nav_menu, .widget_nav_menu_accordion  .widget_nav_menu', self.config.$siteMain ).each( function() {
				var $hasChildren = $( this ).find( '.menu-item-has-children' );
				$hasChildren.each( function() {
					$( this ).addClass( 'parent' );
					var $links = $( this ).children( 'a' );
					$links.on( 'click', function( event ) {
						var $linkParent = $( this ).parent( 'li' );
						var $allParents = $linkParent.parents( 'li' );
						if ( ! $linkParent.hasClass( 'active' ) ) {
							$hasChildren.not( $allParents ).removeClass( 'active' ).children( '.sub-menu' ).slideUp( 'fast' );
							$linkParent.addClass( 'active' ).children( '.sub-menu' ).stop( true, true ).slideDown( 'fast' );
						} else {
							$linkParent.removeClass( 'active' ).children( '.sub-menu' ).stop( true, true ).slideUp( 'fast' );
						}
						return false;
					} );
				} );
			} );

		},

		/**
		 * Header 5 - Inline Logo
		 *
		 * @since 2.0.0
		 */
		inlineHeaderLogo: function() {
			var self = this;

			// For header 5 only
			if ( 'five' != self.config.siteHeaderStyle ) {
				return;
			}

			// Define vars
			var $headerLogo        = $( '#site-header-inner > .header-five-logo', self.config.$siteHeader );
			var $headerNav         = $( '.navbar-style-five', self.config.$siteHeader );
			var $navLiCount        = $headerNav.children( '#site-navigation' ).children( 'ul' ).children( 'li' ).size();
			var $navBeforeMiddleLi = Math.round( $navLiCount / 2 ) - parseInt( wpexLocalize.headerFiveSplitOffset );

			// Insert Logo into Menu
			function onInit() {

				if ( self.config.viewportWidth > self.config.mobileMenuBreakpoint
					&& $headerLogo.length
					&& $headerNav.length
				) {
					$( '<li class="menu-item-logo"></li>' ).insertAfter( $headerNav.find( '#site-navigation > ul > li:nth( '+ $navBeforeMiddleLi +' )' ) );
					$headerLogo.appendTo( $headerNav.find( '.menu-item-logo' ) );
				}

				$headerLogo.addClass( 'display' );

			}

			// Move logo
			function onResize() {

				var $centeredLogo = $( '.menu-item-logo .header-five-logo' );

				if ( self.config.viewportWidth <= self.config.mobileMenuBreakpoint ) {
					if ( $centeredLogo.length ) {
						$centeredLogo.prependTo( $( '#site-header-inner' ) );
						$( '.menu-item-logo' ).remove();
					}
				} else if ( ! $centeredLogo.length ) {
					onInit(); // Insert logo to menu
				}
			}

			// On init
			onInit();

			// Move logo on resize
			self.config.$window.resize( function() {
				onResize();
			} );

		},

		/**
		 * Back to top link
		 *
		 * @since 2.0.0
		 */
		backTopLink: function() {
			var self           = this;
			var $scrollTopLink = $( 'a#site-scroll-top' );

			if ( $scrollTopLink.length ) {

				var $speed  = wpexLocalize.scrollTopSpeed ? parseInt( wpexLocalize.scrollTopSpeed ) : 1000;
				var $offset = wpexLocalize.scrollTopOffset ? parseInt( wpexLocalize.scrollTopOffset ) : 100;

				self.config.$window.scroll( function() {
					if ( $( this ).scrollTop() > $offset ) {
						$scrollTopLink.addClass( 'show' );
					} else {
						$scrollTopLink.removeClass( 'show' );
					}
				} );

				$scrollTopLink.on( 'click', function( event ) {
					$( 'html, body' ).stop( true, true ).animate( {
						scrollTop : 0
					}, $speed, self.config.localScrollEasing );
					return false;
				} );

			}

		},

		/**
		 * Smooth Comment Scroll
		 *
		 * @since 2.0.0
		 */
		smoothCommentScroll: function() {
			var self = this;
			$( '.single li.comment-scroll a' ).click( function( event ) {
				var $target = $( '#comments' );
				var $offset = $target.offset().top - self.config.localScrollOffset - 20;
				self.scrollTo( $target, $offset );
				return false;
			} );
		},

		/**
		 * Tooltips
		 *
		 * @since 2.0.0
		 */
		tipsyTooltips: function() {

			$( 'a.tooltip-left' ).tipsy( {
				fade    : true,
				gravity : 'e'
			} );

			$( 'a.tooltip-right' ).tipsy( {
				fade    : true,
				gravity : 'w'
			} );

			$( 'a.tooltip-up' ).tipsy( {
				fade    : true,
				gravity : 's'
			} );

			$( 'a.tooltip-down' ).tipsy( {
				fade    : true,
				gravity : 'n'
			} );

		},


		/**
		 * Responsive Text
		 * Inspired by FlowType.JS
		 *
		 * @since 3.2.0
		 */
		responsiveText: function() {
			var self = this;
			var $responsiveText = $( '.wpex-responsive-txt' );
			$responsiveText.each( function() {
				var $this      = $( this );
				var $thisWidth = $this.width();
				var $data      = $this.data();
				var $minFont   = self.parseData( $data.minFontSize, 13 );
				var $maxFont   = self.parseData( $data.maxFontSize, 40 );
				var $ratio     = self.parseData( $data.responsiveTextRatio, 10 );
				var $fontBase  = $thisWidth / $ratio;
				var $fontSize  = $fontBase > $maxFont ? $maxFont : $fontBase < $minFont ? $minFont : $fontBase;
				$this.css( 'font-size', $fontSize + 'px' );
			} );
		},

		/**
		 * Togglebar toggle
		 *
		 * @since 2.0.0
		 */
		toggleBar: function() {

			var self           = this;
			var $toggleBtn     = $( 'a.toggle-bar-btn, a.togglebar-toggle, .togglebar-toggle > a' );
			var $toggleBarWrap = $( '#toggle-bar-wrap' );

			if ( $toggleBtn.length && $toggleBarWrap.length ) {

				$toggleBtn.on( 'click', function( event ) {
					var $fa = $( '.toggle-bar-btn' ).find( '.fa' );
					if ( $fa.length ) {
						$fa.toggleClass( $toggleBtn.data( 'icon' ) );
						$fa.toggleClass( $toggleBtn.data( 'icon-hover' ) );
					}
					$toggleBarWrap.toggleClass( 'active-bar' );
					return false;
				} );

				// Close on doc click
				self.config.$document.on( 'click', function( event ) {
					if ( ! $( event.target ).closest( '#toggle-bar-wrap.active-bar' ).length ) {
						$toggleBarWrap.removeClass( 'active-bar' );
						var $fa = $toggleBtn.children( '.fa' );
						if ( $fa.length ) {
							$fa.removeClass( $toggleBtn.data( 'icon-hover' ) ).addClass( $toggleBtn.data( 'icon' ) );
						}
					}
				} );

			}

		},

		/**
		 * Skillbar
		 *
		 * @since 2.0.0
		 */
		skillbar: function( $context ) {
			if ( undefined === $.fn.appear ) {
				return;
			}
			$( '.vcex-skillbar', $context ).each( function() {
				var $this = $( this );
				$this.appear( function() {
					$this.find( '.vcex-skillbar-bar' ).animate( {
						width: $( this ).attr( 'data-percent' )
					}, 800 );
				} );
			}, {
				accX : 0,
				accY : 0
			} );
		},

		/**
		 * Milestones
		 *
		 * @since 2.0.0
		 */
		milestone: function( $context ) {

			if ( typeof CountUp !== 'function' || undefined === $.fn.appear ) {
				return;
			}

			$( '.vcex-countup', $context ).each( function() {

				var $this    = $( this ),
					data     = $this.data( 'options' ),
					startVal = data.startVal,
					endVal   = data.endVal,
					decimals = data.decimals,
					duration = data.duration;

				var options = {
					useEasing   : true,
					useGrouping : true,
					separator   : data.separator,
					decimal     : data.decimal,
					prefix      : '',
					suffix      : ''
				};

				var numAnim = new CountUp( this, startVal, endVal, decimals, duration, options );

				// No need to show on appear when in context
				if ( $context ) {
					numAnim.start();
					return;
				}

				$this.appear( function() {
					numAnim.start();
				} );

			} );
		},

		/**
		 * Before/After Image (twenty twenty)
		 *
		 * @since 4.3
		 */
		twentytwenty: function( $context ) {
			if ( undefined === $.fn.twentytwenty || undefined === $.fn.imagesLoaded ) {
				return;
			}
			$( '.vcex-image-ba', $context ).each( function() {
				var $this = $( this );
				$this.imagesLoaded( function() {
					$this.twentytwenty( $this.data( 'options' ) );
				} );
			} );
		},

		/**
		 * Countdown
		 *
		 * @since 2.0.0
		 */
		countdown: function( $context ) {
			if ( undefined === $.fn.countdown ) {
				return;
			}
			$( '.vcex-countdown', $context ).each( function() {

				var $this     = $( this ),
					endDate  = $this.data( 'countdown' ),
					days     = $this.data( 'days' ),
					hours    = $this.data( 'hours' ),
					minutes  = $this.data( 'minutes' ),
					seconds  = $this.data( 'seconds' ),
					timezone = $this.data( 'timezone' );
			   
				if ( timezone && typeof moment.tz !== 'undefined' && $.isFunction( moment.tz ) ) {
					endDate = moment.tz( endDate, timezone ).toDate();
				}
				
				if ( ! endDate ) {
					return;
				}

				$this.countdown( endDate, function( event ) {
					$this.html( event.strftime( '<div class="wpex-days"><span>%-D</span> <small>' + days + '</small></div> <div class="wpex-hours"><span>%-H</span> <small>' + hours + '</small></div class="wpex-months"> <div class="wpex-minutes"><span>%-M</span> <small>' + minutes + '</small></div> <div class="wpex-seconds"><span>%-S</span> <small>' + seconds + '</small></div>' ) );
				} );

			} );
		},

		/**
		 * Typed Text
		 *
		 * @since 2.0.0
		 */
		typedText: function( $context ) {
			if ( typeof Typed !== 'function' || undefined === $.fn.appear ) {
				return;
			}
			$( '.vcex-typed-text', $context ).each( function() {
				var $this     = $( this );
				var $settings = $this.data( 'settings' );
				$this.appear( function() {
					$settings.typeSpeed  = parseInt( $settings.typeSpeed );
					$settings.backDelay  = parseInt( $settings.backDelay );
					$settings.backSpeed  = parseInt( $settings.backSpeed );
					$settings.startDelay = parseInt( $settings.startDelay );
					$settings.strings    = $this.data( 'strings' );
					var typed = new Typed( this, $settings );
				} );
			} );
		},

		/**
		 * Advanced Parallax
		 *
		 * @since 2.0.0
		 */
		parallax: function( $context ) {
			$( '.wpex-parallax-bg', $context ).each( function() {
				var $this = $( this );
				$this.scrolly2().trigger( 'scroll' );
				$this.css( {
					'opacity' : 1
				} );
			} );
		},

		/**
		 * Local Scroll Offset
		 *
		 * @since 2.0.0
		 */
		parseLocalScrollOffset: function( instance ) {
			var self    = this;
			var $offset = 0;

			// Return custom offset
			if ( wpexLocalize.localScrollOffset ) {
				return wpexLocalize.localScrollOffset;
			}

			// Adds extra offset via filter
			if ( wpexLocalize.localScrollExtraOffset ) {
			   $offset = $offset + parseInt( wpexLocalize.localScrollExtraOffset );
			}

			// Add wp toolbar
			if ( $( '#wpadminbar' ).is( ':visible' ) ) {
				$offset = parseInt( $offset ) +  parseInt( $( '#wpadminbar' ).outerHeight() );
			}

			// Fixed Mobile menu
			if ( 'fixed_top' == self.config.mobileMenuToggleStyle ) {
				var $mmFixed = $( '#wpex-mobile-menu-fixed-top' );
				if ( $mmFixed.length && $mmFixed.is( ':visible' ) ) {
					$offset = parseInt( $offset ) + parseInt( $mmFixed.outerHeight() );
				}
			}

			// Add sticky topbar height offset
			if ( self.config.hasStickyTopBar ) {
				$offset = parseInt( $offset ) + parseInt( self.config.$stickyTopBar.outerHeight() );
			}

			// Fixed header
			if ( self.config.hasStickyHeader ) {

				// Return 0 for small screens if mobile fixed header is disabled
				if ( ! self.config.hasStickyMobileHeader && self.config.windowWidth <= wpexLocalize.stickyHeaderBreakPoint ) {
					$offset = parseInt( $offset ) + 0;
				}

				// Return header height
				else {

					// Shrink header
					if ( self.config.$siteHeader.hasClass( 'shrink-sticky-header' ) ) {
						if ( 'init' == instance || self.config.$siteHeader.is( ':visible' ) ) {
							$offset = parseInt( $offset ) + parseInt( wpexLocalize.shrinkHeaderHeight );
						}
					}

					// Standard header
					else {
						$offset = parseInt( $offset ) + parseInt( self.config.siteHeaderHeight );
					}

				}

			}

			// Fixed Nav
			if ( self.config.hasStickyNavbar ) {
				if ( self.config.viewportWidth >= wpexLocalize.stickyNavbarBreakPoint ) {
					$offset = parseInt( $offset ) + parseInt( self.config.$siteNavWrap.outerHeight() );
				}
			}

			// VCEX Navbar module
			var $vcexNavbarSticky = $( '.vcex-navbar-sticky' );
			if ( $vcexNavbarSticky.length ) {
				$offset = parseInt( $offset ) + parseInt( $vcexNavbarSticky.outerHeight() );
			}

			// Add 1 extra decimal to prevent cross browser rounding issues (mostly firefox)
			$offset = $offset ? $offset - 1 : 0;

			//console.log( $offset );

			// Return offset
			return $offset;

		},

		/**
		 * Scroll to function
		 *
		 * @since 2.0.0
		 */
		scrollTo: function( hash, offset, callback ) {

			// Hash is required
			if ( ! hash ) {
				return;
			}

			// Define important vars
			var self          = this;
			var $target       = null;
			var $page         = $( 'html, body' );
			var $isLsDataLink = false;

			// Check for target in data attributes
			var $lsTarget = $( '[data-ls_id="'+ hash +'"]' );

			if ( $lsTarget.length ) {
				$target       = $lsTarget;
				$isLsDataLink = true;
			}

			// Check for straight up element with ID
			else {
				if ( typeof hash == 'string' ) {
					$target = $( hash );
				} else {
					$target = hash;
				}
			}

			// Target check
			if ( $target.length ) {

				// LocalScroll vars
				var $lsSpeed  = self.config.localScrollSpeed ? parseInt( self.config.localScrollSpeed ) : 1000,
					$lsOffset = self.config.localScrollOffset,
					$lsEasing = self.config.localScrollEasing;

				// Sanitize offset
				offset = offset ? offset : $target.offset().top - $lsOffset;

				// Update hash
				if ( hash && $isLsDataLink && wpexLocalize.localScrollUpdateHash ) {
					window.location.hash = hash;
				}

				/* Remove hash on site top click
				if ( '#site_top' == hash && wpexLocalize.localScrollUpdateHash && window.location.hash ) {
					history.pushState( '', document.title, window.location.pathname);
				}*/

				// Mobile toggle Menu needs it's own code so it closes before the event fires
				// to make sure we end up in the right place
				var $mobileToggleNav = $( '.mobile-toggle-nav' );
				if ( $mobileToggleNav.hasClass( 'visible' ) ) {
					$( 'a.mobile-menu-toggle, li.mobile-menu-toggle > a' ).removeClass( 'wpex-active' );
					if ( wpexLocalize.animateMobileToggle ) {
						$mobileToggleNav.slideUp( 'fast', function() {
							$mobileToggleNav.removeClass( 'visible' );
							$page.stop( true, true ).animate( {
								scrollTop: $target.offset().top - $lsOffset
							}, $lsSpeed );
						} );
					} else {
						$mobileToggleNav.hide().removeClass( 'visible' );
						$page.stop( true, true ).animate( {
							scrollTop: $target.offset().top - $lsOffset
						}, $lsSpeed );
					}
				}

				// Scroll to target
				else {
					$page.stop( true, true ).animate( {
						scrollTop: offset
					}, $lsSpeed, $lsEasing );
				}

			}

		},

		/**
		 * Scroll to Hash
		 *
		 * @since 2.0.0
		 */
		scrollToHash: function( self ) {

			var hash    = location.hash;
			var $target = '';
			var $offset = '';

			// Hash needed
			if ( ! hash ) {
				return;
			}

			// Scroll to comments
			if ( '#view_comments' == hash || '#comments_reply' == hash ) {
				$target = $( '#comments' );
				$offset = $target.offset().top - self.config.localScrollOffset - 20;
				if ( $target.length ) {
					self.scrollTo( $target, $offset );
				}
				return;
			}

			// Scroll to specific comment, fix for sticky header
			if ( self.config.hasStickyHeader && hash.indexOf( 'comment-' ) != -1 ) {
				$target = $( hash );
				$offset = $target.offset().top - self.config.localScrollOffset - 20;
				self.scrollTo( $target, $offset );
				return;
			}


			// Scroll to hash for localscroll links
			if ( hash.indexOf( 'localscroll-' ) != -1 ) {
				self.scrollTo( hash.replace( 'localscroll-', '' ) );
				return;
			}

			// Check elements with data attributes
			if ( $( '[data-ls_id="'+ hash +'"]' ).length ) {
				self.scrollTo( hash );
				return;
			}

		},

		/**
		 * Local scroll links array
		 *
		 * @since 2.0.0
		 */
		localScrollSections: function() {
			var self = this;

			// Add local-scroll class to links in menu with localscroll- prefix (if on same page)
			// And add to $localScrollTargets
			// And add data-ls_linkto attr
			if ( self.config.$siteNav ) {

				var $navLinks    = $( 'a', this.config.$siteNav );
				var $location    = location;
				var $currentPage = $location.href;

				// Sanitize current page var
				$currentPage = $location.hash ? $currentPage.substr( 0, $currentPage.indexOf( '#' ) ) : $currentPage;

				// Loop through nav links
				$navLinks.each( function() {
					var $this = $( this );
					var $ref = $this.attr( 'href' );
						if ( $ref && $ref.indexOf( 'localscroll-' ) != -1 ) {
							$this.parent( 'li' ).addClass( 'local-scroll' );
							var $withoutHash = $ref.substr( 0, $ref.indexOf( '#' ) );
							if ( $withoutHash == $currentPage ) {
								var $hash = $ref.substring( $ref.indexOf( '#' ) + 1 );
								var $parseHash = $hash.replace( 'localscroll-', '' );
								$this.attr( 'data-ls_linkto', '#' + $parseHash );
							}
						}
				} );

			}

			// Define main vars
			var $array = [];
			var $links = $( self.config.$localScrollTargets );

			// Loop through links
			for ( var i=0; i < $links.length; i++ ) {

				// Add to array and save hash
				var $link    = $links[i];
				var $linkDom = $( $link );
				var $href    = $( $link ).attr( 'href' );
				var $hash    = $href ? '#' + $href.replace( /^.*?(#|$)/, '' ) : null;

				// Hash required
				if ( $hash && '#' != $hash ) {

					// Add custom data attribute to each
					if ( ! $linkDom.attr( 'data-ls_linkto' ) ) {
						$linkDom.attr( 'data-ls_linkto', $hash );
					}

					// Data attribute targets
					if ( $( '[data-ls_id="'+ $hash +'"]' ).length ) {
						if ( $.inArray( $hash, $array ) == -1 ) {
							$array.push( $hash );
						}
					}

					// Standard ID targets
					else if ( $( $hash ).length ) {
						if ( $.inArray( $hash, $array ) == -1 ) {
							$array.push( $hash );
						}
					}

				}

			}

			// Return array of local scroll links
			return $array;

		},

		/**
		 * Local Scroll link
		 *
		 * @since 2.0.0
		 */
		localScrollLinks: function() {
			var self = this;

			// Local Scroll - Menus
			$( self.config.$localScrollTargets ).on( 'click', function() {
				var $this = $( this );
				var $hash = $this.attr( 'data-ls_linkto' );
				$hash = $hash ? $hash : this.hash; // Fallback
				if ( $.inArray( $hash, self.config.localScrollSections ) > -1 ) {
					$this.parent().removeClass( 'sfHover' );
					self.scrollTo( $hash );
					return false;
				}
			} );

			// Local Scroll - Logo
			$( 'a.wpex-scroll-top, .wpex-scroll-top a' ).on( 'click', function() {
				self.scrollTo( '#site_top' );
				return false;
			} );

			// Local Scroll - Woocommerce Reviews
			$( 'a.woocommerce-review-link', $( 'body.single div.entry-summary' ) ).click( function() {
				var $target = $( '.woocommerce-tabs' );
				if ( $target.length ) {
					$( '.reviews_tab a' ).click();
					var $offset = $target.offset().top - self.config.localScrollOffset;
					self.scrollTo( $target, $offset );
				}
				return false;
			} );

		},

		/**
		 * Local Scroll Highlight on scroll
		 *
		 * @since 2.0.0
		 */
		localScrollHighlight: function() {

			// Return if disabled
			if ( ! wpexLocalize.localScrollHighlight ) {
				return;
			}

			// Define main vars
			var self = this,
				localScrollSections = self.config.localScrollSections;

			// Return if there aren't any local scroll items
			if ( ! localScrollSections.length ) {
				return;
			}

			// Define vars
			var $windowPos = this.config.$window.scrollTop(),
				$divPos,
				$divHeight,
				$higlight_link;

			// Highlight active items
			for ( var i=0; i < localScrollSections.length; i++ ) {

				// Get section
				var $section = localScrollSections[i];

				// Data attribute targets
				if ( $( '[data-ls_id="'+ $section +'"]' ).length ) {
					var $targetDiv = $( '[data-ls_id="'+ $section +'"]' );
					$divPos        = $targetDiv.offset().top - self.config.localScrollOffset - 1;
					$divHeight     = $targetDiv.outerHeight();
					$higlight_link = $( '[data-ls_linkto="'+ $section +'"]' );
				}

				// Standard element targets
				else if ( $( $section ).length ) {
					$divPos        = $( $section ).offset().top - self.config.localScrollOffset - 1;
					$divHeight     = $( $section ).outerHeight();
					$higlight_link = $( '[data-ls_linkto="'+ $section +'"]' );
				}

				// Higlight items
				if ( $windowPos >= $divPos && $windowPos < ( $divPos + $divHeight ) ) {
					$( '.local-scroll.menu-item' ).removeClass( 'current-menu-item' ); // prevent any sort of duplicate local scroll active links
					$higlight_link.addClass( 'active' );
					$higlight_link.parent( 'li' ).addClass( 'current-menu-item' );
				} else {
					$higlight_link.removeClass( 'active' );
					$higlight_link.parent( 'li' ).removeClass( 'current-menu-item' );
				}

			}

			/* @todo: Highlight last item if at bottom of page or last item clicked - needs major testing now.
			var $docHeight    = this.config.$document.height();
			var windowHeight = this.config.windowHeight;
			var $lastLink = localScrollSections[localScrollSections.length-1];
			if ( $windowPos + windowHeight == $docHeight ) {
				$( '.local-scroll.current-menu-item' ).removeClass( 'current-menu-item' );
				$( "li.local-scroll a[href='" + $lastLink + "']" ).parent( 'li' ).addClass( 'current-menu-item' );
			}*/

		},

		/**
		 * Equal heights function => Must run before isotope method
		 *
		 * @since 2.0.0
		 */
		equalHeights: function( $context ) {

			if ( $.fn.wpexEqualHeights !== undefined ) {

				// Add equal heights grid
				$( '.match-height-grid', $context ).wpexEqualHeights( {
					children: '.match-height-content'
				} );

				// Columns
				$( '.match-height-row', $context ).wpexEqualHeights( {
					children: '.match-height-content'
				} );

				// Feature Box
				$( '.vcex-feature-box-match-height', $context ).wpexEqualHeights( {
					children: '.vcex-match-height'
				} );

				// Blog entries
				$( '.blog-equal-heights', $context ).wpexEqualHeights( {
					children: '.blog-entry-inner'
				} );

				// Related entries
				$( '.related-posts', $context ).wpexEqualHeights( {
					children: '.related-post-content'
				} );

				// Row => @deprecated in v4.0
				$( '.wpex-vc-row-columns-match-height', $context ).wpexEqualHeights( {
					children: '.vc_column-inner'
				} );

				// Manual equal heights
				$( '.vc_row', $context ).wpexEqualHeights( {
					children: '.equal-height-column'
				} );
				$( '.vc_row', $context ).wpexEqualHeights( {
					children: '.equal-height-content'
				} );

			}

		},

		/**
		 * Footer Reveal Display on Load
		 *
		 * @since 2.0.0
		 */
		footerReveal: function() {
			var self = this;

			// Return if disabled
			if ( ! self.config.$hasFooterReveal ) {
				return;
			}

			// Footer reveal
			var $footerReveal = self.config.$footerReveal;

			function showHide() {

				// Disabled under 960
				if ( self.config.viewportWidth < 960 ) {
					if ( $footerReveal.hasClass( 'footer-reveal' ) ) {
						$footerReveal.toggleClass( 'footer-reveal footer-reveal-visible' );
						self.config.$siteWrap.css( 'margin-bottom', '' );
					}
					return;
				}

				var $hideFooter         = false,
					$footerRevealHeight = $footerReveal.outerHeight(),
					windowHeight       = self.config.windowHeight,
					$heightCheck        = 0;

				if ( $footerReveal.hasClass( 'footer-reveal' ) ) {
					$heightCheck = self.config.$siteWrap.outerHeight() + self.config.localScrollOffset;
				} else {
					$heightCheck = self.config.$siteWrap.outerHeight() + self.config.localScrollOffset - $footerRevealHeight;
				}

				// Check window height
				if ( ( windowHeight > $footerRevealHeight ) && ( $heightCheck  > windowHeight ) ) {
					$hideFooter = true;
				}

				// Footer Reveal
				if ( $hideFooter && $footerReveal.hasClass( 'footer-reveal-visible' ) ) {
					self.config.$siteWrap.css( {
						'margin-bottom': $footerRevealHeight
					} );
					$footerReveal.removeClass( 'footer-reveal-visible' );
					$footerReveal.addClass( 'footer-reveal' );
				}

				// Visible Footer
				if ( ! $hideFooter && $footerReveal.hasClass( 'footer-reveal' ) ) {
					self.config.$siteWrap.css( 'margin-bottom', '' );
					$footerReveal.removeClass( 'footer-reveal' );
					$footerReveal.removeClass( 'wpex-visible' );
					$footerReveal.addClass( 'footer-reveal-visible' );
				}

			}

			function reveal() {
				if ( $footerReveal.hasClass( 'footer-reveal' ) ) {
					if ( self.scrolledToBottom( self.config.$siteMain ) ) {
						$footerReveal.addClass( 'wpex-visible' );
					} else {
						$footerReveal.removeClass( 'wpex-visible' );
					}
				}
			}

			// Fire on init
			showHide();

			// Fire onscroll event
			self.config.$window.scroll( function() {
				reveal();
			} );

			// Fire onResize
			self.config.$window.resize( function() {
				if ( self.config.widthChanged || self.config.heightChanged ) {
					showHide();
				}
			} );

		},

		/**
		 * Set min height on main container to prevent issue with extra space below footer
		 *
		 * @since 3.1.1
		 */
		fixedFooter: function() {
			var self = this;

			// Checks
			if ( ! self.config.$siteMain || ! self.config.hasFixedFooter ) {
				return;
			}

			function run() {

				// Set main vars
				var $mainHeight = self.config.$siteMain.outerHeight();
				var $htmlHeight = $( 'html' ).height();

				// Generate min Height
				var $minHeight = $mainHeight + ( self.config.$window.height() - $htmlHeight );

				// Add min height
				self.config.$siteMain.css( 'min-height', $minHeight );

			}

			// Run on doc ready
			run();

			// Run on resize
			self.config.$window.resize( function() {
				if ( self.config.widthChanged || self.config.heightChanged ) {
					run();
				}
			} );

		},

		/**
		 * If title and breadcrumbs don't both fit in the header switch breadcrumb style
		 *
		 * @since 3.5.0
		 */
		titleBreadcrumbsFix: function() {
			var self = this;

			// Return if disabled
			if ( ! self.config.$body.hasClass( 'has-breadcrumbs' ) ) {
				return;
			}

			var $pageHeader = $( '.page-header' );
			var $crumbs = $( '.site-breadcrumbs.position-absolute', $pageHeader );
			if ( ! $crumbs.length || ! $crumbs.hasClass( 'has-js-fix' ) ) {
				return;
			}

			var $crumbsTrail = $( '.breadcrumb-trail', $crumbs );
			if ( ! $crumbsTrail.length ) {
				return;
			}

			var $headerInner = $( '.page-header-inner', $pageHeader );
			if ( ! $headerInner.length ) {
				return;
			}

			var $title = $( '.page-header-title > span', $headerInner );
			if ( ! $title.length ) {
				return;
			}

			function tweak_classes() {
				if ( ( $title.width() + $crumbsTrail.width() + 20 ) >= $headerInner.width() ) {
					if ( $crumbs.hasClass( 'position-absolute' ) ) {
						$crumbs.removeClass( 'position-absolute' );
						$crumbs.addClass( 'position-under-title' );
					}
				} else {
					$crumbs.removeClass( 'position-under-title' );
					$crumbs.addClass( 'position-absolute' );
				}
			}

			// Run on init
			tweak_classes();

			// Run on resize
			self.config.$window.resize( function() {
				tweak_classes();
			} );
			
		},

		/**
		 * Custom Selects
		 *
		 * @since 2.0.0
		 */
		customSelects: function( $context ) {

			$( wpexLocalize.customSelects, $context ).each( function() {
				var $this   = $( this );
				var elID    = $this.attr( 'id' );
				var elClass = elID ? ' wpex-' + elID : '';
				if ( $this.is( ':visible' ) ) {
					if ( $this.attr( 'multiple' ) ) {
						$this.wrap( '<div class="wpex-multiselect-wrap' + elClass + '"></div>' );
					} else {
						$this.wrap( '<div class="wpex-select-wrap' + elClass + '"></div>' );
					}
				}
			} );

			$( '.wpex-select-wrap', $context ).append( '<span class="fa fa-angle-down" aria-hidden="true"></span>' );

			if ( $.fn.select2 !== undefined ) {
				$( '#calc_shipping_country' ).select2();
			}

		},

		/**
		 * FadeIn Elements
		 *
		 * @since 2.0.0
		 */
		fadeIn: function() {
			$( '.fade-in-image, .wpex-show-on-load' ).addClass( 'no-opacity' );
		},

		/**
		 * OwlCarousel
		 *
		 * @since 2.0.0
		 */
		wpexOwlCarousel: function( $context ) {

			// Make sure scripts are loaded
			if ( undefined === $.fn.wpexOwlCarousel || undefined === $.fn.imagesLoaded ) {
				return;
			}

			var self = this;
			
			// Loop through carousels
			$( '.wpex-carousel', $context ).each( function() {

				var $this = $( this ),
					$data = $this.data();

				$this.imagesLoaded( function() {

					var owl = $this.wpexOwlCarousel( {
						animateIn          : false,
						animateOut         : false,
						lazyLoad           : false,
						smartSpeed         : $data.smartSpeed ? $data.smartSpeed : wpexLocalize.carouselSpeed,
						rtl                : wpexLocalize.isRTL ? true : false,
						dots               : $data.dots,
						nav                : $data.nav,
						items              : $data.items,
						slideBy            : $data.slideby,
						center             : $data.center,
						loop               : $data.loop,
						margin             : $data.margin,
						autoplay           : $data.autoplay,
						autoplayTimeout    : $data.autoplayTimeout,
						autoHeight         : $data.autoHeight,
						autoWidth          : $data.autoWidth,
						autoplayHoverPause : true,
						navText            : [ '<span class="fa fa-chevron-left"><span>', '<span class="fa fa-chevron-right"></span>' ],
						responsive         : {
							0: {
								items : $data.itemsMobilePortrait
							},
							480: {
								items : $data.itemsMobileLandscape
							},
							768: {
								items : $data.itemsTablet
							},
							960: {
								items : $data.items
							}
						},
					} );

				} );

			} );

		},

		/**
		 * SliderPro
		 *
		 * @since 2.0.0
		 */
		sliderPro: function( $context ) {
			var self = this;

			// Make sure functions are defined
			if ( undefined === $.fn.sliderPro ) {
				return;
			}

			// Loop through each slider
			$( '.wpex-slider', $context ).each( function() {

				// Declare vars
				var $slider = $( this );
				var $data   = $slider.data();
				var $slides = $slider.find( '.sp-slide' );

				// Lets show things that were hidden to prevent flash
				$slider.find( '.wpex-slider-slide, .wpex-slider-thumbnails.sp-thumbnails' ).css( {
					'opacity' : 1,
					'display' : 'block'
				} );

				// Main checks
				var $autoHeight              = self.parseData( $data.autoHeight, true );
				var $preloader               = $slider.prev( '.wpex-slider-preloaderimg' );
				var $height                  = ( $preloader.length && $autoHeight ) ? $preloader.outerHeight() : null;
				var $heightAnimationDuration = self.parseData( $data.heightAnimationDuration, 600 );
				var $loop                    = self.parseData( $data.loop, false );
				var $autoplay                = self.parseData( $data.autoPlay, true );

				// Get height based on tallest item if autoHeight is disabled
				if ( ! $autoHeight && $slides.length ) {
					var $tallest = self.getTallestEl( $slides );
					$height = $tallest.height();
				}

				// Run slider
				$slider.sliderPro( {
					
					//supportedAnimation      : 'JavaScript', //(CSS3 2D, CSS3 3D or JavaScript)
					aspectRatio             : -1,
					width                   : '100%',
					height                  : $height,
					responsive              : true,
					fade                    : self.parseData( $data.fade, 600 ),
					touchSwipe              : self.parseData( $data.touchSwipe, true ),
					fadeDuration            : self.parseData( $data.animationSpeed, 600 ),
					slideAnimationDuration  : self.parseData( $data.animationSpeed, 600 ),
					autoHeight              : $autoHeight,
					heightAnimationDuration : parseInt( $heightAnimationDuration ),
					arrows                  : self.parseData( $data.arrows, true ),
					fadeArrows              : self.parseData( $data.fadeArrows, true ),
					autoplay                : $autoplay,
					autoplayDelay           : self.parseData( $data.autoPlayDelay, 5000 ),
					buttons                 : self.parseData( $data.buttons, true ),
					shuffle                 : self.parseData( $data.shuffle, false ),
					orientation             : self.parseData( $data.direction, 'horizontal' ),
					loop                    : $loop,
					keyboard                : false,
					fullScreen              : self.parseData( $data.fullscreen, false ),
					slideDistance           : self.parseData( $data.slideDistance, 0 ),
					thumbnailsPosition      : 'bottom',
					thumbnailHeight         : self.parseData( $data.thumbnailHeight, 70 ),
					thumbnailWidth          : self.parseData( $data.thumbnailWidth, 70 ),
					thumbnailPointer        : self.parseData( $data.thumbnailPointer, false ),
					updateHash              : self.parseData( $data.updateHash, false ),
					thumbnailArrows         : false,
					fadeThumbnailArrows     : false,
					thumbnailTouchSwipe     : true,
					fadeCaption             : self.parseData( $data.fadeCaption, true ),
					captionFadeDuration     : 600,
					waitForLayers           : true,
					autoScaleLayers         : true,
					forceSize               : 'none',
					reachVideoAction        : self.parseData( $data.reachVideoAction, 'playVideo' ),
					leaveVideoAction        : self.parseData( $data.leaveVideoAction, 'pauseVideo' ),
					endVideoAction          : self.parseData( $data.leaveVideoAction, 'nextSlide' ),
					fadeOutPreviousSlide    : true, // If disabled testimonial/content slides are bad
					autoplayOnHover         : self.parseData( $data.autoplayOnHover, 'pause' ),
					init: function( event ) {
						$slider.prev( '.wpex-slider-preloaderimg' ).remove();
					},
					gotoSlide: function( event ) {
						if ( ! $loop && $autoplay && event.index === $slider.find( '.sp-slide' ).length - 1 ) {
							$slider.data( 'sliderPro' ).stopAutoplay();
						}
					}
				} );

			} );

			// WooCommerce: Prevent clicking on Woo entry slider
			$( '.woo-product-entry-slider' ).click( function() {
				return false;
			} );

			// Make sure functions are defined
			if ( undefined === $.fn.imagesLoaded ) {
				return;
			}

			// Show no carousel thumbs
			var $sliderProThumbsNC = $( '.wpex-slider-thumbnails.sp-nc-thumbnails', $context );
			$sliderProThumbsNC.each( function() {
				var $this = $( this );
				$this.imagesLoaded( function() {
					 $this.css( {
						'opacity' : 1,
						'display' : 'block'
					} );
				} );
			} );

		},

		/**
		 * Isotope Grids
		 *
		 * @since 2.0.0
		 */
		isotopeGrids: function( $context ) {

			// Make sure scripts are loaded
			if ( undefined === $.fn.imagesLoaded || undefined === $.fn.isotope ) {
				return;
			}

			// Loop through isotope grids
			$( '.vcex-isotope-grid' ).each( function() {

				// Isotope layout
				var $container = $( this );

				// Run only once images have been loaded
				$container.imagesLoaded( function() {

					// Check filter
					var activeItems;

					// Filter links
					var $filter = $container.prev( 'ul.vcex-filter-links' );
					if ( $filter.length ) {
						var $filterLinks = $filter.find( 'a' );
						activeItems = $container.data( 'filter' );
						if ( activeItems && ! $filter.find( '[data-filter="' + activeItems + '"]').length ) {
							activeItems = '';
						}
						$filterLinks.click( function() {
							$grid.isotope( {
								filter : $( this ).attr( 'data-filter' )
							} );
							$( this ).parents( 'ul' ).find( 'li' ).removeClass( 'active' );
							$( this ).parent( 'li' ).addClass( 'active' );
							return false;
						} );
					}

					// Crete the isotope layout
					var $grid = $container.isotope( {
						itemSelector       : '.vcex-isotope-entry',
						transformsEnabled  : true,
						isOriginLeft       : wpexLocalize.isRTL ? false : true,
						transitionDuration : $container.data( 'transition-duration' ) ? $container.data( 'transition-duration' ) + 's' : '0.4s',
						layoutMode         : $container.data( 'layout-mode' ) ? $container.data( 'layout-mode' ) : 'masonry',
						filter             : activeItems
					} );

				} );

			} );

		},

		/**
		 * Custom hovers using data attributes
		 *
		 * @since 4.5.4.2
		 */
		customHovers: function() {
			var headCSS = '';
			var cssObj  = {};

			$( '.wpex-hover-data' ).remove(); // prevent dups / front-end editor fix

			// Newer Total 4.5.4.2 method
			$( '[data-wpex-hover]' ).each( function( index, value ) {

				var $this       = $( this );
				var data        = $this.data( 'wpex-hover' );
				var uniqueClass = 'wpex-dhover-' + index;
				var hoverCSS    = '';
				var target      = '';

				if ( data.parent ) {
					$this.parents( data.parent ).addClass( uniqueClass + '-p' );
					$this.addClass( uniqueClass );
					target = '.' + uniqueClass + '-p:hover .' + uniqueClass;
				} else {
					$this.addClass( uniqueClass );
					target = '.' + uniqueClass + ':hover';
				}

				$.each( data, function( attribute, value ) {
					if ( 'target' == attribute ) {
						return true;
					}
					hoverCSS += attribute + ':' +  value + '!important;';
				} );

				if ( hoverCSS ) {
					if ( hoverCSS in cssObj ) {
						cssObj[hoverCSS] = cssObj[hoverCSS] + ',' + target
					} else {
						cssObj[hoverCSS] = target;
					}
				}

			} );

			if ( cssObj ) {

				$.each( cssObj, function( css, elements ) {

					headCSS += elements + '{' + css + '}';

				} );

			}

			if ( headCSS ) {
				this.config.$head.append( '<style class="wpex-hover-data">/** CUSTOM HOVERS **/' + headCSS + '</style>' );
			}
			
		},

		/**
		 * Responsive CSS
		 *
		 * @since 4.0
		 */
		responsiveCSS: function() {

			var headCSS   = '';
			var mediaObj  = {};
			var bkPoints  = {};

			$( '.wpex-vc-rcss' ).remove(); // Prevent duplicates when editing the VC

			// Get breakpoints
			bkPoints.d = '';
			bkPoints = $.extend( bkPoints, wpexLocalize.responsiveDataBreakpoints );

			// Loop through breakpoints to create mediaObj
			$.each( bkPoints, function( key ) {
				mediaObj[key] = ''; // Create empty array of media breakpoints
			} );

			// loop through all modules and add CSS to mediaObj
			$( '[data-wpex-rcss]' ).each( function( index, value ) {

				var $this       = $( this );
				var uniqueClass = 'wpex-rcss-' + index;
				var data        = $this.data( 'wpex-rcss' );

				$this.addClass( uniqueClass );

				$.each( data, function( key, val ) {

					var thisVal = val;
					var target  = key;

					$.each( bkPoints, function( key ) {

						if ( thisVal[key] ) {

							mediaObj[key] += '.' + uniqueClass + '{' + target + ':' + thisVal[key] + '!important;}';

						}

					} );

				} );

			} );

			$.each( mediaObj, function( key, val ) {

				if ( 'd' == key ) {
					headCSS += val;
				} else {
					if ( val ) {
						headCSS += '@media (max-width: ' + bkPoints[key] + ') { ' + val + ' }';
					}
				}

			} );

			if ( headCSS ) {

				headCSS = '<style class="wpex-vc-rcss">/** RESPONSIVE VC PARAMS **/' + headCSS + '</style>';

				this.config.$head.append( headCSS );

			}

		},

		/**
		 * VCEX Filter Nav
		 *
		 * @since 2.0.0
		 */
		vcexFilterNav: function( $context ) {

			// Make sure scripts are loaded
			if ( undefined === $.fn.imagesLoaded || undefined === $.fn.isotope ) {
				return;
			}

			// Filter Navs
			$( '.vcex-filter-nav', $context ).each( function() {

				var $nav        = $( this ),
					$filterGrid = $( '#' + $nav.data( 'filter-grid' ) ),
					$grid;

				if ( ! $filterGrid.hasClass( 'wpex-row' ) ) {
					$filterGrid = $filterGrid.find( '.wpex-row' );
				}

				if ( $filterGrid.length ) {

					// Remove isotope class
					$filterGrid.removeClass( 'vcex-isotope-grid' );

					// Run functions after images are loaded for grid
					$filterGrid.imagesLoaded( function() {

						// Create Isotope
						if ( ! $filterGrid.hasClass( 'vcex-navbar-filter-grid' ) ) {

							$filterGrid.addClass( 'vcex-navbar-filter-grid' );

							var activeItems = $nav.data( 'filter' );
							if ( activeItems && ! $nav.find( '[data-filter="' + activeItems + '"]').length ) {
								activeItems = '';
							}

							$grid = $filterGrid.isotope( {
								itemSelector       : '.col',
								transformsEnabled  : true,
								isOriginLeft       : wpexLocalize.isRTL ? false : true,
								transitionDuration : $nav.data( 'transition-duration' ) ? $nav.data( 'transition-duration' ) + 's' : '0.4s',
								layoutMode         : $nav.data( 'layout-mode' ) ? $nav.data( 'layout-mode' ) : 'masonry',
								filter             : activeItems
							} );

						} else {

							// Add isotope only, the filter grid already
							$grid = $filterGrid.isotope();

						}

						// Loop through filter links for filtering items
						var $filterLinks = $nav.find( 'a' );
						$filterLinks.click( function() {

							// Define link
							var $link = $( this );

							// Filter items
							$grid.isotope( {
								filter : $( this ).attr( 'data-filter' )
							} );

							// Remove all active class
							$filterLinks.removeClass( 'active' );

							// Add active class
							$link.addClass( 'active' );

							// Return false
							return false;

						} );

					} );

				}

			} );

		},

		/**
		 * Archive Masonry Grids
		 *
		 * @since 2.0.0
		 */
		archiveMasonryGrids: function() {

			// Make sure scripts are loaded
			if ( undefined === $.fn.imagesLoaded || undefined === $.fn.isotope ) {
				return;
			}

			// Define main vars
			var self      = this,
				$archives = $( '.blog-masonry-grid,div.wpex-row.portfolio-masonry,div.wpex-row.portfolio-no-margins,div.wpex-row.staff-masonry,div.wpex-row.staff-no-margins' );

			// Loop through archives
			$archives.each( function() {

				var $this = $( this );
				var $data = $this.data();

				// Load isotope after images loaded
				$this.imagesLoaded( function() {

					var $grid = $this.isotope( {
						itemSelector       : '.isotope-entry',
						transformsEnabled  : true,
						isOriginLeft       : wpexLocalize.isRTL ? false : true,
						transitionDuration : self.parseData( $data.transitionDuration, '0.0' ) + 's',
						layoutMode         : self.parseData( $data.layoutMode, 'masonry' )
					} );

				} );

			} );

		},

		/**
		 * Automatic Lightbox for images
		 *
		 * @version 4.5
		 */
		autoLightbox: function() {
			if ( ! wpexLocalize.iLightbox.auto ) {
				return;
			}
			var self     = this,
				imageExt = ['bmp', 'gif', 'jpeg', 'jpg', 'png', 'tiff', 'tif', 'jfif', 'jpe'];
			$( '.wpb_text_column a:has(img), body.no-composer .entry a:has(img)' ).each( function() {
				var $this = $( this );
				var href  = $this.attr( 'href' );
				var ext   = self.getUrlExtension( href );
				if ( href && imageExt.indexOf( ext ) !== -1 ) {
					if ( ! $this.parents( '.woocommerce-product-gallery' ).length ) {
						$this.addClass( 'wpex-lightbox' );
					}
				}
			} );
		},

		/**
		 * iLightbox
		 *
		 * @since 2.0.0
		 */
		iLightbox: function( $context ) {
			var self = this;

			// Store lightbox settings in object
			self.iLightboxSettings = wpexLocalize.iLightbox;

			// Sanitize data
			self.iLightboxSettings.show.speed              = parseInt( self.iLightboxSettings.show.speed );
			self.iLightboxSettings.hide.speed              = parseInt( self.iLightboxSettings.hide.speed );
			self.iLightboxSettings.effects.repositionSpeed = parseInt( self.iLightboxSettings.effects.repositionSpeed );
			self.iLightboxSettings.effects.switchSpeed     = parseInt( self.iLightboxSettings.effects.switchSpeed  );
			self.iLightboxSettings.effects.loadedFadeSpeed = parseInt( self.iLightboxSettings.effects.loadedFadeSpeed );
			self.iLightboxSettings.effects.fadeSpeed       = parseInt( self.iLightboxSettings.effects.fadeSpeed );

			// Lightbox Galleries
			// @todo change lightbox-group class to .wpex-lightbox-group in theme template parts
			$( '.lightbox-group, .wpex-lightbox-group', $context ).each( function() {

				// Get lightbox data
				var $this          = $( this );
				var $item          = $this.find( '.wpex-lightbox-group-item' );
				var $iLightboxData = $this.data( 'ilightbox' );

				// Destroy if lightbox has already been added and re-add
				// Prevents build-up in AJAX functions
				if ( $iLightboxData ) {
					$iLightboxData.destroy();
				}

				// Prevent conflicts (can't be a group item and a lightbox item)
				$item.removeClass( 'wpex-lightbox' );

				// Set item to it's child link if not a link itself
				if ( ! $item.is( 'a' ) ) {
					$item = $item.find( 'a' );
					$item.removeClass( 'wpex-lightbox' ); // prevent conflicts
				}

				// Start up lightbox
				var $ilightbox = $item.iLightBox( $.extend( true, {}, self.iLightboxSettings, {
					skin: self.parseData( $this.data( 'skin' ), wpexLocalize.iLightbox.skin ),
					path: self.parseData( $this.data( 'path' ), wpexLocalize.iLightbox.path ),
					infinite: self.parseData( $this.data( 'infinite' ), wpexLocalize.iLightbox.infinite ),
					show: {
						title: self.parseData( $this.data( 'show_title' ), wpexLocalize.iLightbox.show.title )
					},
					controls: {
						arrows: self.parseData( $this.data( 'arrows' ), wpexLocalize.iLightbox.controls.arrows ),
						thumbnail: self.parseData( $this.data( 'thumbnails' ), wpexLocalize.iLightbox.controls.thumbnail )
					},
				} ) );

				// Save lightbox instance
				$this.data( 'ilightbox', $ilightbox );

			} );

			// Lightbox Standard => SINGLE LIGHTBOX
			$( '.wpex-lightbox', $context ).each( function() {

				var $this = $( this );

				if ( ! $this.is( 'a' ) ) {
					$this = $this.find( 'a' );
				}

				if ( ! $this.hasClass( 'wpex-lightbox-group-item' ) ) {
				   
					var $ilightbox = $this.iLightBox( $.extend( true, {}, self.iLightboxSettings, {
						skin: self.parseData( $this.data( 'skin' ), wpexLocalize.iLightbox.skin ),
						show: {
							title: self.parseData( $this.data( 'show_title' ), wpexLocalize.iLightbox.show.title )
						},
						controls: {
							arrows: false,
							thumbnail: false,
							mousewheel: false
						}
					} ) );

					$this.data( 'ilightbox', $ilightbox );
				}

			} );

			// Lightbox Gallery with custom imgs
			$( '.wpex-lightbox-gallery', $context ).on( 'click', function( event ) {

				var $this       = $( this ),
					data        = $this.data( 'gallery' ),
					imagesArray = '';
				
				if ( ! data ) {
					return;
				}

				if ( typeof data == 'string' || data instanceof String ) {
			   
					imagesArray = data.split( ',' );

				} else {
					imagesArray = data;
				}

				// console.log ( imagesArray );
				//return false;

				$.iLightBox( imagesArray, $.extend( true, {}, self.iLightboxSettings, {
					skin: self.parseData( $this.data( 'skin' ), wpexLocalize.iLightbox.skin ),
					path: self.parseData( $this.data( 'path' ), wpexLocalize.iLightbox.path ),
					infinite: self.parseData( $this.data( 'skin' ), wpexLocalize.iLightbox.infinite ),
					controls: {
						arrows: self.parseData( $this.data( 'arrows' ), wpexLocalize.iLightbox.controls.arrows ),
						thumbnail: self.parseData( $this.data( 'thumbnails' ), wpexLocalize.iLightbox.controls.thumbnail )
					}
				} ) );

				return false;

			} );

			// Lightbox Videos => OLD SCHOOL STUFF, keep for old customers
			$( '.wpex-lightbox-video, .wpb_single_image.video-lightbox a, .wpex-lightbox-autodetect, .wpex-lightbox-autodetect a', $context ).each( function() {

				var $this = $( this ),
					$data = $this.data();

				$this.iLightBox( {
					smartRecognition : true,
					skin             : self.parseData( $data.skin, wpexLocalize.iLightbox.skin ),
					path             : 'horizontal',
					controls         : {
						fullscreen : wpexLocalize.iLightbox.controls.fullscreen
					},
					show             : {
						title : self.parseData( $data.show_title, wpexLocalize.iLightbox.show.title ),
						speed : parseInt( wpexLocalize.iLightbox.show.speed )
					},
					hide             : {
						speed : parseInt( wpexLocalize.iLightbox.hide.speed )
					},
					effects          : {
						reposition      : true,
						repositionSpeed : 200,
						switchSpeed     : 300,
						loadedFadeSpeed : wpexLocalize.iLightbox.effects.loadedFadeSpeed,
						fadeSpeed       : wpexLocalize.iLightbox.effects.fadeSpeed
					},
					overlay : wpexLocalize.iLightbox.overlay,
					social  : wpexLocalize.iLightbox.social
				} );
			} );

			// Custom Lightbox for Carousels
			$( '.wpex-carousel', $context ).on( 'click', '.wpex-carousel-lightbox-item', function( e ) {
				e.preventDefault();

				var $this          = $( this ),
					$parent        = $this.parents( '.wpex-carousel' ),
					$parentOwl     = $this.parents( '.owl-item' ),
					$owlItems      = $parent.find( '.owl-item' ),
					$data          = $this.data(),
					$imagesArray   = [];

				$owlItems.each( function() {
					if ( ! $( this ).hasClass( 'cloned' ) ) {
						var $image = $( this ).find( '.wpex-carousel-lightbox-item' );
						if ( $image.length > 0 ) {
							$imagesArray.push( {
								URL     : $image.attr( 'href' ),
								title   : $image.attr( 'data-title' ),
								caption : $image.attr( 'data-caption' )
							} );
						}
					}
				} );

				if ( $imagesArray.length > 0 ) {

					// Define where to start lightbox from
					var $startFrom = $this.data( 'count' ) - 1;
					$startFrom = $startFrom ? $startFrom : 0;

					$.iLightBox( $imagesArray, $.extend( true, {}, self.iLightboxSettings, {
						startFrom: parseInt( $startFrom ),
						skin: self.parseData( $data.skin, wpexLocalize.iLightbox.skin ),
						path: self.parseData( $data.path, wpexLocalize.iLightbox.path ),
						infinite: self.parseData( $data.skin, wpexLocalize.iLightbox.infinite ),
						show: {
							title: self.parseData( $data.show_title, wpexLocalize.iLightbox.show.title )
						},
						controls: {
							arrows: self.parseData( $data.arrows, wpexLocalize.iLightbox.controls.arrows ),
							thumbnail: self.parseData( $data.thumbnails, wpexLocalize.iLightbox.controls.thumbnail )
						}
					} ) );

				}

			} );

		},

		/**
		 * Overlay Hovers
		 *
		 * @since 2.0.0
		 */
		overlayHovers: function() {

			// Mobile Hovers if enabled
			if ( this.config.isMobile ) {

				// Remove overlays completely if mobile support is disabled
				$( '.overlay-parent.overlay-hh' ).each( function() {
					if ( ! $( this ).hasClass( 'overlay-ms' ) ) {
						$( this ).find( '.theme-overlay' ).remove();
					}
				} );

				// Prevent click on touchstart
				$( 'a.overlay-parent.overlay-ms.overlay-h, .overlay-parent.overlay-ms.overlay-h > a' ).on( 'touchstart', function( e ) {

					var $this = $( this );
					var $overlayParent = $this.hasClass( 'overlay-parent' ) ? $this : $this.parent( '.overlay-parent' );

					if ( $overlayParent.hasClass( 'wpex-touched' ) ) {
						return true;
					} else {
						$overlayParent.addClass( 'wpex-touched' );
						$( '.overlay-parent' ).not($overlayParent).removeClass( 'wpex-touched' );
						e.preventDefault();
						return false;
					}

				} );

				// Hide overlay when clicking outside
				this.config.$document.on( 'touchstart', function( e ) {
					if ( ! $( e.target ).closest( '.wpex-touched' ).length ) {
						$( '.wpex-touched' ).removeClass( 'wpex-touched' );
					}
				} );

			}

			// Title Push Up
			$( '.overlay-parent-title-push-up' ).each( function() {

				// Define vars
				var $this        = $( this ),
					$title       = $this.find( '.overlay-title-push-up' ),
					$child       = $this.find( 'a' ),
					$img         = $child.find( 'img' ),
					$titleHeight = $title.outerHeight();

				// Create overlay after image is loaded to prevent issues
				$this.imagesLoaded( function() {

					// Position title
					$title.css( {
						'bottom' : - $titleHeight
					} );

					// Add height to child
					$child.css( {
						'height' : $img.outerHeight()
					} );

					// Position image
					$img.css( {
						'position' : 'absolute',
						'top'      : '0',
						'left'     : '0',
						'width'    : 'auto',
						'height'   : 'auto'
					} );

					// Animate image on hover
					$this.hover( function() {
						$img.css( {
							'top' : -20
						} );
						$title.css( {
							'bottom' : 0
						} );
					}, function() {
						$img.css( {
							'top' : '0'
						} );
						$title.css( {
							'bottom' : - $titleHeight
						} );
					} );

				} );

			} );

		},

		/**
		 * Sticky Topbar
		 *
		 * @since 3.4.0
		 */
		stickyTopBar: function() {
			var self = this;

			// Return if disabled or not found
			if ( ! self.config.hasStickyTopBar || ! self.config.$stickyTopBar ) {
				return;
			}

			// Define vars
			var $isSticky      = false,
				$offset        = 0,
				$window        = self.config.$window,
				$stickyTopbar  = self.config.$stickyTopBar,
				$mobileSupport = self.config.hasStickyTopBarMobile,
				$brkPoint      = wpexLocalize.stickyTopBarBreakPoint,
				$mobileMenu    = $( '#wpex-mobile-menu-fixed-top' ),
				$stickyWrap    = $( '<div id="top-bar-wrap-sticky-wrapper" class="wpex-sticky-top-bar-holder not-sticky"></div>' );
			
			// Set sticky wrap to new wrapper
			$stickyTopbar.wrapAll( $stickyWrap );
			$stickyWrap = $( '#top-bar-wrap-sticky-wrapper' );

			// Get offset
			function getOffset() {
				$offset = 0; // Reset offset for resize
				if ( self.config.$wpAdminBar ) {
					$offset = $offset + self.config.$wpAdminBar.outerHeight();
				}
				if ( $mobileMenu.is( ':visible' ) ) {
					$offset = $offset + $mobileMenu.outerHeight();
				}
				return $offset;
			}

			// Stick the TopBar
			function setSticky() {

				// Already stuck
				if ( $isSticky ) {
					return;
				}
				
				// Add wrap class and toggle sticky class
				$stickyWrap
					.css( 'height', $stickyTopbar.outerHeight() )
					.removeClass( 'not-sticky' )
					.addClass( 'is-sticky' );

				// Add CSS to topbar
				$stickyTopbar.css( {
					'top'   : getOffset(),
					'width' : $stickyWrap.width()
				} );

				// Set sticky to true
				$isSticky = true;

			}

			// Unstick the TopBar
			function destroySticky() {

				if ( ! $isSticky ) {
					return;
				}

				// Remove sticky wrap height and toggle sticky class
				$stickyWrap
					.css( 'height', '' )
					.removeClass( 'is-sticky' )
					.addClass( 'not-sticky' );

				// Remove topbar css
				$stickyTopbar.css( {
					'width' : '',
					'top'   : '',
				} );

				// Set sticky to false
				$isSticky = false;

			}

			// On load check
			function initSetSticky() {

				// Disable on mobile devices
				if ( ! $mobileSupport && ( self.config.viewportWidth < $brkPoint ) ) {
					return;
				}

				// Set sticky based on original offset
				$offset = $stickyWrap.offset().top - getOffset();

				// Set or destroy sticky
				if ( self.config.windowTop > $offset ) {
					setSticky();
				}

			}

			// On scroll actions for sticky topbar
			function onScroll() {

				// Disable on mobile devices
				if ( ! $mobileSupport && ( self.config.viewportWidth < $brkPoint ) ) {
					return;
				}

				// Destroy sticky at top and prevent sticky at top (since its already at top)
				if ( 0 === self.config.windowTop ) {
					if ( $isSticky ) {
						destroySticky();
					}
					return;
				}

				// Get correct start position for sticky to start
				var $stickyWrapTop = $stickyWrap.offset().top;
				var $setStickyPos  = $stickyWrapTop - getOffset();

				// Set or destroy sticky based on offset
				if ( self.config.windowTop >= $setStickyPos ) {
					setSticky();
				} else {
					destroySticky();
				}

			}

			// On resize actions for sticky topbar
			function onResize() {

				// Check if header is disabled on mobile if not destroy on resize
				if ( ! $mobileSupport && ( self.config.viewportWidth < $brkPoint ) ) {
					destroySticky();
				} else {

					// Set correct width and top value
					if ( $isSticky ) {
						$stickyWrap.css( 'height', $stickyTopbar.outerHeight() );
						$stickyTopbar.css( {
							'top'   : getOffset(),
							'width' : $stickyWrap.width()
						} );
					} else {
						onScroll();
					}

				}

			}

			// Fire on init
			initSetSticky();

			// Fire onscroll event
			$window.scroll( function() {
				onScroll();
			} );

			// Fire onResize
			$window.resize( function() {
				onResize();
			} );

			// Fire resize on flip
			// Destroy and re-calculate
			$window.on( 'orientationchange' , function( e ) {
				destroySticky();
				initSetSticky();
			} );

		},

		/**
		 * Get correct offSet for the sticky header and sticky header menu.
		 *
		 * @since 3.4.0
		 */
		stickyOffset: function() {
			var self          = this;
			var $offset       = 0;
			var $mobileMenu   = $( '#wpex-mobile-menu-fixed-top' );
			var $stickyTopbar = self.config.$stickyTopBar;

			// Offset sticky topbar
			if ( $stickyTopbar && $stickyTopbar.is( ':visible' ) ) {
				if ( self.config.hasStickyTopBarMobile
					|| self.config.viewportWidth >= wpexLocalize.stickyTopBarBreakPoint
				) {
					$offset = $offset + $stickyTopbar.outerHeight();
				}
			}

			// Offset mobile menu
			if ( $mobileMenu.is( ':visible' ) ) {
				$offset = $offset + $mobileMenu.outerHeight();
			}

			// Offset adminbar
			if ( this.config.$wpAdminBar && this.config.$wpAdminBar.is( ':visible' ) ) {
				$offset = $offset + this.config.$wpAdminBar.outerHeight();
			}

			// Added offset via child theme
			if ( wpexLocalize.addStickyHeaderOffset ) {
				$offset = $offset + wpexLocalize.addStickyHeaderOffset;
			}

			// Return correct offset
			return $offset;

		},

		/**
		 * New Sticky Header
		 *
		 * @since 4.6.5
		 */
		stickyHeaderCustomStartPoint: function() {
			var $startPosition = wpexLocalize.stickyHeaderStartPosition;
			if ( $.isNumeric( $startPosition ) ) {
				$startPosition = $startPosition;
			} else if ( $( $startPosition ).length ) {
				$startPosition = $( $startPosition ).offset().top;
			} else {
				$startPosition = 0;
			}
			return $startPosition;
		},

		/**
		 * New Sticky Header
		 *
		 * @since 3.4.0
		 */
		stickyHeader: function() {
			var self           = this,
				$isSticky      = false,
				$isShrunk      = false,
				$isLogoSwapped = false;

			// Return if sticky is disabled
			if ( ! self.config.hasStickyHeader ) {
				return;
			}

			// Define header
			var $header        = self.config.$siteHeader;
			var $headerHeight  = self.config.siteHeaderHeight;
			var $headerBottom  = $header.offset().top + $header.outerHeight();

			// Add sticky wrap
			var $stickyWrap = $( '<div id="site-header-sticky-wrapper" class="wpex-sticky-header-holder not-sticky"></div>' );
			$header.wrapAll( $stickyWrap );
			$stickyWrap     = $( '#site-header-sticky-wrapper' ); // Cache newly added element as dom object

			// Define main vars for sticky function
			var $window        = self.config.$window;
			var $brkPoint      = wpexLocalize.stickyHeaderBreakPoint;
			var $mobileSupport = self.config.hasStickyMobileHeader;
			var $customStart   = self.stickyHeaderCustomStartPoint();

			// Custom sticky logo
			var $headerLogo    = self.config.$siteLogo;
			var $headerLogoSrc = self.config.siteLogoSrc;

			// Shrink support
			var maybeShrink = ( 'shrink' == self.stickyHeaderStyle || 'shrink_animated' == self.stickyHeaderStyle ) ? true : false;

			// Custom shrink logo
			var $stickyLogo = wpexLocalize.stickyheaderCustomLogo;
			if ( $stickyLogo
				&& wpexLocalize.stickyheaderCustomLogoRetina
				&& self.config.isRetina
			) {
				$stickyLogo = wpexLocalize.stickyheaderCustomLogoRetina;
			}

			// Load images to be used for the custom sticky logo
			if ( $stickyLogo ) {
				$( '<img src="' + $stickyLogo + '">' ).appendTo( 'body' ).css( 'display', 'none' );
			}

			// Check if we are on mobile size
			function pastBreakPoint() {
				return ( self.config.viewportWidth < $brkPoint ) ? true : false;
			}

			// Check if we are past the header
			function pastheader() {
				var bottomCheck = 0;
				if ( self.config.hasHeaderOverlay ) {
					bottomCheck = $headerBottom;
				} else {
					bottomCheck = $stickyWrap.offset().top + $stickyWrap.outerHeight();
				}
				if ( self.config.windowTop > $headerBottom ) {
					return true;
				}
				return false;
			}

			// Check start position
			function start_position() {
				var $startPosition = $customStart;
				$startPosition = $startPosition ? $startPosition : $stickyWrap.offset().top;
				return $startPosition - self.stickyOffset();
			}

			// Transform
			function transformPrepare() {
				if ( $isSticky ) {
					$header.addClass( 'transform-go' ); // prevent issues when scrolling
				}
				if ( 0 === self.config.windowTop ) {
					$header.removeClass( 'transform-prepare' );
				} else if ( pastheader() ) {
					$header.addClass( 'transform-prepare' );
				} else {
					$header.removeClass( 'transform-prepare' );
				}
			}

			// Swap logo
			function swapLogo() {

				if ( ! $stickyLogo || ! $headerLogo ) {
					return;
				}

				if ( $isLogoSwapped ) {

					$headerLogo.attr( 'src', $headerLogoSrc );
					self.config.siteLogoHeight = self.config.$siteLogo.height();

					$isLogoSwapped = false;

				} else {

					$headerLogo.attr( 'src', $stickyLogo );
					self.config.siteLogoHeight = self.config.$siteLogo.height();

					$isLogoSwapped = true;

				}

			}

			// Shrink/unshrink header
			function shrink() {

				var checks = maybeShrink;

				if ( pastBreakPoint() ) {
					if ( $mobileSupport && ( 'icon_buttons' == self.config.mobileMenuToggleStyle || 'fixed_top' == self.config.mobileMenuToggleStyle ) ) {
						checks = true
					} else {
						checks = false;
					}
				}

				if ( checks && pastheader() ) {

					if ( ! $isShrunk && $isSticky ) {
						$header.addClass( 'sticky-header-shrunk' );
						$isShrunk = true;
					}

				} else {

					$header.removeClass( 'sticky-header-shrunk' );
					$isShrunk = false;

				}

			}

			// Set sticky
			function setSticky() {

				// Already stuck
				if ( $isSticky ) {
					return;
				}

				// Custom Sticky logo
				swapLogo();

				// Add wrap class and toggle sticky class
				$stickyWrap
					.css( 'height', $headerHeight )
					.removeClass( 'not-sticky' )
					.addClass( 'is-sticky' );

				// Tweak header
				$header.removeClass( 'dyn-styles' ).css( {
					'top'       : self.stickyOffset(),
					'width'     : $stickyWrap.width()
				} );

				// Add transform go class
				if ( $header.hasClass( 'transform-prepare' ) ) {
					$header.addClass( 'transform-go' );
				}

				// Set sticky to true
				$isSticky = true;

			}

			// Destroy actions
			function destroyActions() {

				// Reset logo
				swapLogo();

				// Remove sticky wrap height and toggle sticky class
				$stickyWrap.removeClass( 'is-sticky' ).addClass( 'not-sticky' );

				// Do not remove height on sticky header for shrink header incase animation isn't done yet
				if ( ! $header.hasClass( 'shrink-sticky-header' ) ) {
					$stickyWrap.css( 'height', '' );
				}

				// Reset header
				$header.addClass( 'dyn-styles' ).css( {
					'width' : '',
					'top'   : ''
				} ).removeClass( 'transform-go' );

				// Set sticky to false
				$isSticky = false;

				// Make sure shrink header is removed
				$header.removeClass( 'sticky-header-shrunk' ); // Fixes some bugs with really fast scrolling
				$isShrunk = false;

			}

			// Destroy sticky
			function destroySticky() {

				// Already unstuck
				if ( ! $isSticky ) {
					return;
				}

				if ( $customStart ) {
					$header.removeClass( 'transform-go' );
					if ( $isShrunk ) {
						$header.removeClass( 'sticky-header-shrunk' );
						$isShrunk = false;
					}
				} else {
					$header.removeClass( 'transform-prepare' );
				}

				destroyActions();

			}

			// On load check
			function initResizeSetSticky() {

				if ( ! $mobileSupport && pastBreakPoint() ) {
					return;
				}

				//$header.addClass( 'transform-go' );

				if ( self.config.windowTop > start_position() && 0 !== self.config.windowTop ) {
					setSticky();
				}

				if ( maybeShrink ) {
					shrink();
				}

			}

			// On scroll function
			function onScroll() {

				// Disable on mobile devices
				if ( ! $mobileSupport && pastBreakPoint() ) {
					return;
				}

				// Animate scroll with custom start
				if ( $customStart ) {
					transformPrepare();
				}

				// Destroy sticky at top
				if ( 0 === self.config.windowTop ) {
					destroySticky();
					return;
				}

				// Set or destroy sticky
				if ( self.config.windowTop >= start_position() ) {
					setSticky();
				} else {
					destroySticky();
				}

				// Shrink
				if ( maybeShrink ) {
					shrink();
				}

			}

			// On resize function
			function onResize() {

				// Check if header is disabled on mobile if not destroy on resize
				if ( ! $mobileSupport && pastBreakPoint() ) {
					destroySticky();
					$header.removeClass( 'transform-prepare' ); // important!
				} else {

					// Update sticky
					if ( $isSticky ) {

						// Update wrapper height
						if ( ! $header.hasClass( 'shrink-sticky-header' ) ) {
							$stickyWrap.css( 'height', self.config.siteHeaderHeight );
						}

						// Update sticky width and top offset
						$header.css( {
							'top'   : self.stickyOffset(),
							'width' : $stickyWrap.width()
						} );

					}

					// Add sticky
					else {
						initResizeSetSticky();
					}

				}

				// Shrink
				if ( maybeShrink ) {
					shrink();
				}

			} // End onResize

			// Fire on init
			initResizeSetSticky();

			// Fire onscroll event
			$window.scroll( function() {
				if ( self.config.$hasScrolled ) {
					onScroll();
				}
			} );

			// Fire onResize
			$window.resize( function() {
				if ( self.config.widthChanged || self.config.heightChanged ) {
					onResize();
				}
			} );

			// Destroy and run onResize function on orientation change
			$window.on( 'orientationchange' , function() {
				destroySticky();
				initResizeSetSticky();
			} );

		},

		/**
		 * Sticky Header Menu
		 *
		 * @since 3.4.0
		 */
		stickyHeaderMenu: function() {
			var self = this;

			// Return if disabled
			if ( ! self.config.hasStickyNavbar ) {
				return;
			}

			// Main vars
			var $navWrap       = self.config.$siteNavWrap,
				$isSticky      = false,
				$window        = self.config.$window,
				elIndex        = $( $navWrap ).index(),
				//$mobileSupport = wpexLocalize.hasStickyNavbarMobile,
				$stickyWrap    = $( '<div id="site-navigation-sticky-wrapper" class="wpex-sticky-navigation-holder not-sticky"></div>' );
			
			// Define sticky wrap
			$navWrap.wrapAll( $stickyWrap );
			$stickyWrap = $( '#site-navigation-sticky-wrapper' );

			// Add offsets
			var $stickyWrapTop = $stickyWrap.offset().top,
				$stickyOffset  = self.stickyOffset(),
				$setStickyPos  = $stickyWrapTop - $stickyOffset;

			// Shrink header function
			function setSticky() {

				// Already sticky
				if ( $isSticky ) {
					return;
				}

				// Add wrap class and toggle sticky class
				$stickyWrap
					.css( 'height', self.config.$siteNavWrap.outerHeight() )
					.removeClass( 'not-sticky' )
					.addClass( 'is-sticky' );

				// Add CSS to topbar
				$navWrap.css( {
					'top'   : self.stickyOffset(),
					'width' : $stickyWrap.width()
				} );

				// Remove header dynamic styles
				self.config.$siteHeader.removeClass( 'dyn-styles' );
				
				// Update shrunk var
				$isSticky = true;

			}

			// Un-Shrink header function
			function destroySticky() {

				// Not shrunk
				if ( ! $isSticky ) {
					return;
				}

				// Remove sticky wrap height and toggle sticky class
				$stickyWrap
					.css( 'height', '' )
					.removeClass( 'is-sticky' )
					.addClass( 'not-sticky' );

				// Remove navbar width
				$navWrap.css( {
					'width' : '',
					'top'   : ''
				} );

				// Re-add dynamic header styles
				self.config.$siteHeader.addClass( 'dyn-styles' );

				// Update shrunk var
				$isSticky = false;

			}

			// On load check
			function initResizeSetSticky() {

				// Disable on mobile devices
				if ( self.config.viewportWidth <= wpexLocalize.stickyNavbarBreakPoint ) {
					return;
				}

				// Sticky menu
				if ( self.config.windowTop >= $setStickyPos && 0 !== self.config.windowTop ) {
					setSticky();
				} else {
					destroySticky();
				}

			}

			// Sticky check / enable-disable
			function onScroll() {

				// Disable on mobile devices
				if ( self.config.viewportWidth <= wpexLocalize.stickyNavbarBreakPoint ) {
					return;
				}

				// Destroy sticky at top and prevent sticky at top (since its already at top)
				if ( 0 === self.config.windowTop ) {
					if ( $isSticky ) {
						destroySticky();
					}
					return;
				}

				// Sticky menu
				if ( self.config.windowTop >= $setStickyPos ) {
					setSticky();
				} else {
					destroySticky();
				}

			}

			// On resize function
			function onResize() {

				// Check if sticky is disabled on mobile if not destroy on resize
				if ( self.config.viewportWidth <= wpexLocalize.stickyNavbarBreakPoint ) {
					destroySticky();
				}

				// Update width
				if ( $isSticky ) {
					$navWrap.css( 'width', $stickyWrap.width() );
				} else {
					initResizeSetSticky();
				}

			}

			// Fire on init
			initResizeSetSticky();

			// Fire onscroll event
			$window.scroll( function() {
				if ( self.config.$hasScrolled ) {
					onScroll();
				}
			} );

			// Fire onResize
			$window.resize( function() {
				onResize();
			} );

			// Fire resize on flip
			$window.on( 'orientationchange' , function() {
				destroySticky();
				initResizeSetSticky();
			} );

		},

		/**
		 * Sticky Visual Composer Navbar
		 *
		 * @since 3.3.2
		 */
		stickyVcexNavbar: function() {
			var self = this;
			var $nav = $( '.vcex-navbar-sticky' );

			if ( ! $nav.length ) {
				return;
			}

			$nav.each( function() {

				var $this           = $( this );
				var $isSticky       = false;
				var $window         = self.config.$window;
				var $stickyEndPoint = $this.data( 'sticky-endpoint' ) ? $( $this.data( 'sticky-endpoint' ) ) : '';

				// Add sticky wrap
				var $stickyWrap = $( '<div class="vcex-navbar-sticky-wrapper not-sticky"></div>' );
				$this.wrapAll( $stickyWrap );
				$stickyWrap = $this.parent( '.vcex-navbar-sticky-wrapper' );

				// Set sticky
				function setSticky( $offset ) {

					// Already sticky or hidden
					if ( $isSticky || ! $this.is( ':visible' ) ) {
						return;
					}

					// Set placeholder
					$stickyWrap
						.css( 'height', $this.outerHeight() )
						.removeClass( 'not-sticky' )
						.addClass( 'is-sticky' );

					// Position Fixed nav
					$this.css( {
						'top'   : $offset,
						'width' : $stickyWrap.width()
					} );
					
					// Update sticky var
					$isSticky = true;

				}

				// Check sticky offSet based on other sticky elements
				function getStickyOffset() {

					var offset              = 0;
					var $stickyTopbar       = $( '#top-bar-wrap-sticky-wrapper' );
					var $stickyHeader       = $( '#site-header-sticky-wrapper' );
					var $stickyHeaderNavbar = $( '#site-navigation-sticky-wrapper' );
					var $mobileMenu         = $( '#wpex-mobile-menu-fixed-top' );

					if ( $stickyTopbar.is( ':visible' ) ) {
						if ( self.config.hasStickyTopBarMobile
							|| self.config.viewportWidth >= wpexLocalize.stickyTopBarBreakPoint
						) {
							offset = offset + $stickyTopbar.outerHeight();
						}
					}

					if ( $stickyHeader.is( ':visible' ) ) {
						if ( self.config.hasStickyMobileHeader
							|| self.config.viewportWidth >= wpexLocalize.stickyHeaderBreakPoint
						) {
							if ( self.config.$siteHeader.hasClass( 'shrink-sticky-header' ) ) {
								offset = offset + parseInt( wpexLocalize.shrinkHeaderHeight );
							} else {
								offset = offset + $stickyHeader.outerHeight();
							}
						}
					}

					if ( self.config.hasStickyNavbar && $stickyHeaderNavbar.is( ':visible' ) ) {
						if ( self.config.hasStickyMobileHeader
							|| ( self.config.viewportWidth >= wpexLocalize.stickyNavbarBreakPoint )
						) {
							offset = offset + $stickyHeaderNavbar.outerHeight();
						}
					}

					if ( $mobileMenu.is( ':visible' ) ) {
						offset = offset + $mobileMenu.outerHeight();
					}

					if ( self.config.$wpAdminBar && self.config.$wpAdminBar.is( ':visible' ) ) {
						offset = offset + self.config.$wpAdminBar.outerHeight();
					}

					return offset;

				}

				// Un-Shrink header function
				function destroySticky() {

					// Not shrunk
					if ( ! $isSticky ) {
						return;
					}

					// Remove sticky wrap height and toggle sticky class
					$stickyWrap
						.css( 'height', '' )
						.removeClass( 'is-sticky' )
						.addClass( 'not-sticky' );

					// Remove navbar width
					$this.css( {
						'width' : '',
						'top'   : ''
					} );

					// Update shrunk var
					$isSticky = false;

				}

				// On scroll function
				function stickyCheck() {

					var stickyOffset = getStickyOffset();
					var stickyWrapTop = $stickyWrap.offset().top;
					var setStickyPos  = stickyWrapTop - stickyOffset;

					if ( self.config.windowTop > setStickyPos && 0 !== self.config.windowTop ) {
						setSticky( stickyOffset );
						if ( $stickyEndPoint.length && $stickyEndPoint.is( ':visible' ) ) {
							if ( self.config.windowTop > ( $stickyEndPoint.offset().top - stickyOffset - $this.outerHeight() ) ) {
								$stickyWrap.addClass( 'sticky-hidden' );
							} else {
								$stickyWrap.removeClass( 'sticky-hidden' );
							}
						}
					} else {
						destroySticky();
					}

				}

				// On resize function
				function onResize() {

					// Should it be sticky?
					stickyCheck();

					// Sticky fixes
					if ( $isSticky ) {
						
						// Destroy if hidden
						if ( ! $this.is( ':visible' ) ) {
							destroySticky();
						}
						
						// Set correct height on wrapper
						$stickyWrap.css( 'height', $this.outerHeight() );
						
						// Set correct width and offset value on sticky element
						$this.css( {
							'top'   : getStickyOffset(),
							'width' : $stickyWrap.width()
						} );

					}

					// Should it become sticky?
					else {
						stickyCheck();
					}

				}

				// Fire on init
				stickyCheck();

				// Fire onscroll event
				$window.scroll( function() {
					if ( self.config.$hasScrolled ) {
						stickyCheck();
					}
				} );

				// Fire onResize
				$window.resize( function() {
					onResize();
				} );

				// Fire resize on flip
				$window.on( 'orientationchange' , function( e ) {
					destroySticky();
					stickyCheck();
				} );

			} ); // End each
		},

		/**
		 * Infinite Scroll
		 *
		 * @since 3.5.0
		 */
		infiniteScrollInit: function() {

			var self = this;

			var $container = $( '#blog-entries' );

			$container.infinitescroll( wpexInfiniteScroll, function( newElements ) {

				var $newElems = $( newElements ).css( 'opacity', 0 );

				$newElems.imagesLoaded( function() {

					if ( $container.hasClass( 'blog-masonry-grid' ) ) {
						$container.isotope( 'appended', $newElems );
						$newElems.css( 'opacity', 0 );
					}

					if ( typeof retinajs !== 'undefined' && $.isFunction( retinajs ) ) {
						retinajs();
					}

					$newElems.animate( {
						opacity: 1
					} );

					$container.trigger( 'wpexinfiniteScrollLoaded', [$newElems] );

					self.sliderPro( $newElems );
					self.iLightbox( $newElems );

					if ( $.fn.wpexEqualHeights !== undefined ) {
						$( '.blog-equal-heights' ).wpexEqualHeights( {
							children : '.blog-entry-inner'
						} );
					}

					if ( typeof( $.fn.mediaelementplayer ) !== 'undefined' ) {
						$newElems.find( 'audio, video' ).mediaelementplayer();
					}

				} );

			} );

		},

		/**
		 * Load More pagination
		 *
		 * @since 4.4.1
		 */
		loadMore: function() {

			var self      = this;
			var $loadMore = $( '.wpex-load-more' );

			if ( ! $loadMore.length ) {
				return;
			}

			$loadMore.each( function() {

				var $button      = $( this );
				var $buttonInner = $button.find( '.theme-button-inner' );
				var loading      = false;
				var text         = wpexLocalize.loadMore.text;
				var ajaxUrl      = wpexLocalize.ajaxurl;
				var loadingText  = wpexLocalize.loadMore.loadingText;
				var failedText   = wpexLocalize.loadMore.failedText;
				var buttonData   = $button.data( 'loadmore' );
				var $grid        = $( buttonData.grid );
				var page         = 2;

				if ( 1 != buttonData.maxPages ) {
					$button.addClass( 'wpex-visible' );
				}

				var loadmoreData = buttonData;

				$button.on( 'click', function() {

					if ( ! loading ) {

						loading = true;

						$button.addClass( 'loading' );
						$buttonInner.text( loadingText );

						var data = {
							action   : 'wpex_ajax_load_more',
							nonce    : buttonData.nonce,
							page     : page,
							loadmore : loadmoreData
						};

						$.post( ajaxUrl, data, function( res ) {

							// Ajax request successful
							if ( res.success ) {

								//console.log( res.data );

								// Increase page
								page = page + 1;

								// Define vars
								var $newElements = $( res.data );
								$newElements.css( 'opacity', 0 ); // hide until images are loaded

								// Remove duplicate posts (sticky)
								$newElements.each( function() {
									var $this = $( this );
									if ( $this.hasClass( 'sticky' ) ) {
										$this.addClass( 'wpex-duplicate' );
									}
								} );

								$grid.append( $newElements ).imagesLoaded( function() {

									if ( $.fn.wpexEqualHeights !== undefined ) {
										$( '.blog-equal-heights' ).wpexEqualHeights( {
											children : '.blog-entry-inner'
										} );
									}
									
									if ( $grid.hasClass( 'blog-masonry-grid' ) ) {
										$grid.isotope( 'appended', $newElements );
										//$grid.isotope( 'appended', $( $newElements ) ).isotope( 'layout' );
									}

									self.iLightbox( $newElements );
									self.overlayHovers( $newElements );

									$grid.trigger( 'wpexLoadMoreAddedHidden', [$newElements] );

									$newElements.css( 'opacity', 1 );

									if ( typeof retinajs !== 'undefined' && $.isFunction( retinajs ) ) {
										retinajs();
									}

									self.sliderPro( $newElements );

									if ( typeof( $.fn.mediaelementplayer ) !== 'undefined' ) {
										$newElements.find( 'audio, video' ).mediaelementplayer();
									}

									$grid.trigger( 'wpexLoadMoreAddedVisible', [$newElements] );

									// Reset button
									$button.removeClass( 'loading' );
									$buttonInner.text( text );

									// Hide button
									if ( ( page - 1 ) == buttonData.maxPages ) {
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

		},

		/**
		 * Contact form 7 switch preloader for txt
		 *
		 * @since 3.6.0
		 */
		ctf7Preloader: function() {

			// Return if disabled
			if ( ! wpexLocalize.altercf7Prealoader ) {
				return;
			}

			// Forms
			var $forms = $( 'form.wpcf7-form' );

			// Loop through forms
			$forms.each( function() {

				var $this = $( this );

				// Find button
				var $button = $this.find( '.wpcf7-submit' );

				// Hide loader if button found
				if ( $button.length ) {

					// Hide preLoader
					$this.find( '.ajax-loader' ).remove();

					// Add font awesome spinner
					var $customLoader = $( '<span class="fa fa-refresh fa-spin wpex-wpcf7-loader"></span>' );
					$button.after( $customLoader );

					// Show new spinner on Send button click
					$button.on( 'click', function() {
						$customLoader.addClass( 'visible' );
					} );

					// Hide new spinner on result
					$( 'div.wpcf7' ).on( 'wpcf7:invalid wpcf7:spam wpcf7:mailsent wpcf7:mailfailed', function() {
						$customLoader.removeClass( 'visible' );
					} );

				}

			} );

		},

		/**
		 * Visual Composer Slider & Accordions
		 *
		 * @since 4.2.1
		 */
		vcTabsTogglesJS: function() {
			var self = this;

			// Only needed when VC is enabled
			if ( ! this.config.$body.hasClass( 'wpb-js-composer' ) ) {
				return;
			}

			function onShow() {
				var $this = $( this );

				// Sliders
				$this.find( '.wpex-slider' ).each( function() {
					$( this ).sliderPro( 'update' );
				} );

				// Grids
				$this.find( '.vcex-isotope-grid' ).each( function() {
					$( this ).isotope( 'layout' );
				} );

				// Milestones
				$this.find( '.vcex-milestone' ).each( function() {
					self.milestone( $( this ) );
				} );

			}

			// Re-trigger/update things when opening VC tabs
			$( '.vc_tta-tabs' ).on( 'show.vc.tab', onShow );

			// Re-trigger slider on tabs change
			$( '.vc_tta-accordion' ).on( 'show.vc.accordion', onShow );

			// Tab clicks custom checks - due to issues with show.vc.tab not triggering on click in v5.4.3
			// Front-end only (breaks back-end tabs and not needed there apparently)
			self.config.$document.on( 'click.vc.tabs.data-api', '[data-vc-tabs]', function( e ) {

				if ( self.config.$body.hasClass( 'vc_editor' ) ) {
					return;
				}
			   
				var tab = $( $( this ).attr( 'href' ) );
				
				if ( tab.length ) {

					// Sliders
					tab.find( '.wpex-slider' ).each( function() {
						$( this ).sliderPro( 'update' );
					} );

					// Grids
					tab.find( '.vcex-isotope-grid' ).each( function() {
						$( this ).isotope( 'layout' );
					} );

				}

			} );

		},

		/**
		 * Visual Composer Accessability fixes
		 *
		 * @since 4.5
		 */
		vcAccessability: function() {

			if ( ! this.config.vcActive ) {
				return;
			}
			
			// Add tab index to toggles and toggle on enter
			var $toggles = $( '.vc_toggle .vc_toggle_title' );
			$toggles.each( function( index ) {
				var $this = $( this );
				$this.attr( 'tabindex', 0 );
				$this.on( 'keydown', function( e ) {
					if ( 13 == e.which ) {
						$this.trigger( 'click' );
					}
				} );
			} );

			// Tabs
			var $tabContainers = $( '.vc_tta-container' );

			var tabClick = function( $thisTab, $allTabs, $tabPanels, i ) {
				$allTabs.attr( 'tabindex', -1 );
				$thisTab.attr( 'tabindex', 0 ).focus().click();
			}

			$tabContainers.each( function() {

				var $tabContainer = $( this ),
					$tabs         = $tabContainer.find( '.vc_tta-tab > a' ),
					$panels       = $tabContainer.find( '.vc_tta-panels' );

				$tabs.each( function( index ) {

					var $tab = $( this );

					if ( 0 == index ) {
						$tab.attr( 'tabindex', 0 );
					} else {
						$tab.attr( 'tabindex', -1 );
					}

					$tab.on( 'keydown', function( e ) {

						var $this        = $( this ),
							keyCode      = e.which,
							$nextTab     = $this.parent().next().is( 'li.vc_tta-tab' ) ? $this.parent().next().find( 'a' ) : false,
							$previousTab = $this.parent().prev().is( 'li.vc_tta-tab' ) ? $this.parent().prev().find( 'a' ) : false,
							$firstTab    = $this.parent().parent().find( 'li.vc_tta-tab:first' ).find( 'a' ),
							$lastTab     = $this.parent().parent().find( 'li.vc_tta-tab:last' ).find( 'a' );

						switch( keyCode ) {

						// Left/Up
						case 37 :
						case 38 :
							e.preventDefault();
							e.stopPropagation();
							if ( ! $previousTab) {
								tabClick( $lastTab, $tabs, $panels );
							} else {
								tabClick( $previousTab, $tabs, $panels );
							}
						break;

						// Right/Down
						case 39 :
						case 40 :
							e.preventDefault();
							e.stopPropagation();
							if ( ! $nextTab ) {
								tabClick( $firstTab, $tabs, $panels );
							} else {
								tabClick( $nextTab, $tabs, $panels );
							}
						break;

						// Home
						case 36 :
							e.preventDefault();
							e.stopPropagation();
							tabClick( $firstTab, $tabs, $panels );
							break;
						// End
						case 35 :
							e.preventDefault();
							e.stopPropagation();
							tabClick( $lastTab, $tabs, $panels );
						break;

						// Enter/Space
						case 13 :
						case 32 :
							e.preventDefault();
							e.stopPropagation();
						break;

						} // end switch

					} );

				} );

			} );

		},

		/**
		 * Removes duplicate VC elements added by Total (overlays, parallax)
		 *
		 * @since 4.0
		 */
		vcexRemoveiFrameDups: function( $context ) {

			var $this = $context;
			var $module = $this.children( ':first' );

			if ( ! $module.length ) {
				return;
			}

			// Overlays
			var $overlays = $module.find( '> .wpex-bg-overlay-wrap' );

			if ( $module.hasClass( 'wpex-has-overlay' ) ) {
				$overlays.not( ':first' ).remove();
			} else if ( $overlays.length ) {
				$overlays.remove();
			}

			// Self-hosted Videos
			var $videos = $module.find( '> .wpex-video-bg-wrap' );

			if ( $module.hasClass( 'wpex-has-video-bg' ) ) {
				$videos.not( ':first' ).remove();
			} else if ( $videos.length ) {
				$videos.remove();
			}

			// Parallax
			var $parallax = $module.find( '> .wpex-parallax-bg' );

			if ( $module.hasClass( 'wpex-parallax-bg-wrap' ) ) {
				$parallax.not( ':first' ).remove();
			} else if ( $parallax.length ) {
				$parallax.remove();
			}

			// Video Backgrounds
			// Deprecated? @todo Remove & test
			var $videoOverlays = $module.find( '> .wpex-video-bg-overlay' );
			if ( $videoOverlays.length ) {
				$videoOverlays.not( ':first' ).remove();
			}

		},

		/**
		 * Visual Composer Updates/Adding
		 *
		 * @since 3.6.0
		 */
		vcPageEditable: function() {
			var self     = this,
				$modelId = '';

			// Only needed in composer mode
			if ( ! self.config.$body.hasClass( 'compose-mode' ) ) {
				return;
			}

			// Store model ID when events change
			parent.vc.events.on( 'shortcodes:add shortcodes:update shortcodes:clone', function( model ) {
				$modelId = model.id;
			} );

			// Re-run functions on each VC reload
			self.config.$window.on( 'vc_reload', function() {
				self.equalHeights();
				self.sliderPro();
				self.wpexOwlCarousel();
				self.vcexFilterNav();
				self.customHovers();
				self.responsiveCSS();
				if ( $modelId ) {
					var $context = $( '[data-model-id=' + $modelId + ']' );
					self.isotopeGrids( $context );
					self.vcPageEditableFuncs( $context );
					self.vcexRemoveiFrameDups( $context );
				} else {
					self.isotopeGrids();
				}
			} );

		},

		/**
		 * Visual Composer Trigger JS
		 *
		 * @since 3.6.0
		 */
		vcPageEditableFuncs: function( $context ) {

			// Globals in context
			this.parallax( $context );
			this.responsiveText( $context );
			this.overlayHovers( $context );
			this.iLightbox( $context );

			// Module dependent
			if ( $context.hasClass( 'vc_vcex_skillbar' ) ) {
				this.skillbar( $context );
				return;
			}

			if ( $context.hasClass( 'vc_vc_wp_custommenu' ) ) {
				this.menuWidgetAccordion( $context );
				return;
			}

			if ( $context.hasClass( 'vc_vcex_form_shortcode' ) ) {
				this.customSelects( $context );
				return;
			}

			if ( $context.hasClass( 'vc_vcex_milestone' ) ) {
				this.milestone( $context );
				return;
			}

			if ( $context.hasClass( 'vc_vcex_image_ba' ) ) {
				this.twentytwenty( $context );
				return;
			}

			if ( $context.hasClass( 'vc_vcex_animated_text' ) ) {
				this.typedText( $context );
				return;
			}

			if ( $context.hasClass( 'vc_vcex_countdown' ) ) {
				this.countdown( $context );
				return;
			}
			
		},

		/**
		 * WooCommerce Gallery functions
		 *
		 * @since 4.1
		 */
		wooGallery: function() {

			if ( typeof wc_single_product_params === 'undefined' || ! wc_single_product_params.flexslider.directionNav ) {
				return;
			}

			var self = this;

			var $wooGallery = $( '.woocommerce-product-gallery--with-images' );

			if ( ! $wooGallery.length ) {
				return;
			}

			function setWooSliderArrows() {

				$wooGallery.each( function() {

					var $this      = $( this );
					var $nav       = $( this ).find( '.flex-direction-nav' );
					var $thumbsNav = $( this ).find( '.flex-control-thumbs' );

					if ( $nav.length && $thumbsNav.length ) {

						var thumbsNavHeight = $thumbsNav.outerHeight();
						var arrowHeight     = $nav.find( 'a' ).outerHeight();
						var arrowTopoffset  = - ( thumbsNavHeight + arrowHeight ) / 2;

						if ( arrowTopoffset ) {
							$this.find( '.flex-direction-nav a' ).css( 'margin-top', arrowTopoffset );
						}

					}

				} );

			}

			self.config.$window.on( 'load', function() {
				setWooSliderArrows();
			} );

			self.config.$window.resize( function() {
				if ( self.config.widthChanged || self.config.heightChanged ) {
					setWooSliderArrows();
				}
			} );

		},

		/**
		 * Parses data to check if a value is defined in the data attribute and if not returns the fallback
		 *
		 * @since 2.0.0
		 */
		parseData: function( val, fallback ) {
			return ( typeof val !== 'undefined' ) ? val : fallback;
		},

		/**
		 * Returns extension from URL
		 */
		getUrlExtension: function( url ) {
			var ext = url.split( '.' ).pop().toLowerCase();
			var extra = ext.indexOf( '?' ) !== -1 ? ext.split( '?' ).pop() : '';
			ext = ext.replace( extra, '' );
			return ext.replace( '?', '' );
		},

		/**
		 * Check if window has scrolled to bottom of element
		 */
		scrolledToBottom: function( elem ) {
			return this.config.windowTop >= elem.offset().top + elem.outerHeight() - window.innerHeight;
		},

		/**
		 * Check if an element is currently in the window view
		 */
		isElementInWindowView: function( elem ) {
			var docViewTop    = this.config.$window.scrollTop();
			var docViewBottom = docViewTop + this.config.windowHeight;
			var elemTop       = $(elem).offset().top;
			var elemBottom    = elemTop + $(elem).height();
			return ( ( elemBottom <= docViewBottom) && (elemTop >= docViewTop ) );
		},

		/**
		 * Return tallest element
		 */
		getTallestEl: function( el ) {
			var tallest;
			var first = 1;
			el.each( function() {
				var $this = $( this );
				if ( first == 1 ) {
					tallest = $this;
					first = 0;
				} else {
					if ( tallest.height() < $this.height()) {
						tallest = $this;
					}
				}
			} );
			return tallest;
		}

	}; // END totalTheme

	// Start things up
	wpex.init();

} ) ( jQuery );