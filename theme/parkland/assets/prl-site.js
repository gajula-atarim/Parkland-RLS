/**
 * Parkland front-end behaviour.
 *
 * Covers the PRL Home v3 interactions that have no free-Elementor widget:
 * the catalogue/website search switch, mobile nav, carousels, tab groups
 * and the marquee. Every sub-site uses this same file; per-library values
 * (CTX number, PAC URL) come from data attributes, never from code.
 */
( function () {
	'use strict';

	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var each = function ( list, fn ) { Array.prototype.forEach.call( list, fn ); };

	/* Catalogue routes to this library's PAC profile; Website searches only
	   this sub-site. Both read from data attributes so one script serves 42. */
	function initSearch( root ) {
		var modeBtn = root.querySelector( '.prl-search__mode' );
		var menu    = root.querySelector( '.prl-search__menu' );
		var current = root.querySelector( '.prl-search__current' );
		var input   = root.querySelector( '.prl-search__input' );
		var submit  = root.querySelector( '.prl-search__submit' );
		if ( ! modeBtn || ! menu || ! input ) { return; }

		var mode = root.getAttribute( 'data-prl-default-mode' ) || 'catalogue';

		function close() { menu.hidden = true; modeBtn.setAttribute( 'aria-expanded', 'false' ); }
		function open()  { menu.hidden = false; modeBtn.setAttribute( 'aria-expanded', 'true' ); }

		modeBtn.addEventListener( 'click', function ( e ) {
			e.preventDefault();
			if ( menu.hidden ) { open(); } else { close(); }
		} );

		each( menu.querySelectorAll( '.prl-search__opt' ), function ( opt ) {
			opt.addEventListener( 'click', function () {
				mode = opt.getAttribute( 'data-mode' );
				if ( current ) { current.textContent = opt.getAttribute( 'data-label' ); }
				each( menu.querySelectorAll( '.prl-search__opt' ), function ( o ) {
					o.setAttribute( 'aria-selected', String( o === opt ) );
				} );
				var ph = opt.getAttribute( 'data-placeholder' );
				if ( ph ) { input.placeholder = ph; }
				close();
				input.focus();
			} );
		} );

		document.addEventListener( 'click', function ( e ) {
			if ( ! root.contains( e.target ) ) { close(); }
		} );
		root.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) { close(); modeBtn.focus(); }
		} );

		function go() {
			var q = ( input.value || '' ).trim();
			if ( ! q ) { input.focus(); return; }
			var url;
			if ( 'catalogue' === mode ) {
				var pac = root.getAttribute( 'data-prl-pac' );
				var ctx = root.getAttribute( 'data-prl-ctx' );
				if ( ! pac || ! ctx ) { return; }
				url = pac + '?ctx=' + encodeURIComponent( ctx ) + '&q=' + encodeURIComponent( q ) + '&by=KW';
			} else {
				url = ( root.getAttribute( 'data-prl-site' ) || '/' ) + '?s=' + encodeURIComponent( q );
			}
			window.location.href = url;
		}

		if ( submit ) { submit.addEventListener( 'click', go ); }
		input.addEventListener( 'keydown', function ( e ) {
			if ( 'Enter' === e.key ) { e.preventDefault(); go(); }
		} );
	}

	/* Mobile nav. Authored by us; the design zip has no mobile layout. */
	function initNav() {
		/* The toggle sits in the masthead and the list in the nav bar, so these
		   are looked up document-wide rather than within one container. */
		var toggle = document.querySelector( '.prl-nav__toggle' );
		var list   = document.querySelector( '.prl-nav__list' );
		if ( toggle && toggle.getAttribute( 'data-prl-bound' ) ) { return; }
		if ( toggle ) { toggle.setAttribute( 'data-prl-bound', '1' ); }
		if ( ! toggle || ! list ) { return; }
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.addEventListener( 'click', function () {
			var open = list.classList.toggle( 'is-open' );
			toggle.setAttribute( 'aria-expanded', String( open ) );
			toggle.setAttribute( 'aria-label', open ? 'Close menu' : 'Open menu' );
		} );
	}

	/* Slide carousel. Timings come from the design: hero 7s, features 6s,
	   shelf 4.5s, testimonials 8s - set per instance via data-prl-interval. */
	function initCarousel( root ) {
		var slides = root.querySelectorAll( '[data-prl-slide]' );
		if ( slides.length < 2 ) { return; }
		var dots  = root.querySelectorAll( '[data-prl-dot]' );
		var prev  = root.querySelector( '[data-prl-prev]' );
		var next  = root.querySelector( '[data-prl-next]' );
		var delay = parseInt( root.getAttribute( 'data-prl-interval' ), 10 ) || 7000;
		var i = 0, timer = null;

		function show( n ) {
			i = ( n + slides.length ) % slides.length;
			each( slides, function ( s, k ) {
				s.hidden = k !== i;
				s.setAttribute( 'aria-hidden', String( k !== i ) );
			} );
			each( dots, function ( d, k ) { d.setAttribute( 'aria-current', String( k === i ) ); } );
		}
		function start() {
			if ( reduced || timer ) { return; }
			timer = setInterval( function () { show( i + 1 ); }, delay );
		}
		function stop() { clearInterval( timer ); timer = null; }

		if ( prev ) { prev.addEventListener( 'click', function () { stop(); show( i - 1 ); } ); }
		if ( next ) { next.addEventListener( 'click', function () { stop(); show( i + 1 ); } ); }
		each( dots, function ( d, k ) { d.addEventListener( 'click', function () { stop(); show( k ); } ); } );

		root.addEventListener( 'mouseenter', stop );
		root.addEventListener( 'mouseleave', start );
		root.addEventListener( 'focusin', stop );

		show( 0 );
		start();
	}

	/* Tab / accordion groups: Special Features, New & Notable, event chips. */
	function initPanels( root ) {
		var tabs   = root.querySelectorAll( '[data-prl-tab]' );
		var panels = root.querySelectorAll( '[data-prl-panel]' );
		if ( ! tabs.length ) { return; }

		function select( key ) {
			each( tabs, function ( t ) {
				var on = t.getAttribute( 'data-prl-tab' ) === key;
				t.setAttribute( 'aria-selected', String( on ) );
				t.classList.toggle( 'is-active', on );
			} );
			each( panels, function ( p ) {
				p.hidden = p.getAttribute( 'data-prl-panel' ) !== key;
			} );
		}

		each( tabs, function ( t ) {
			t.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				select( t.getAttribute( 'data-prl-tab' ) );
			} );
		} );

		select( tabs[ 0 ].getAttribute( 'data-prl-tab' ) );
	}

	/* Duplicate the marquee track so prlMarquee loops seamlessly at -50%. */
	function initMarquee( root ) {
		var track = root.querySelector( '.prl-marquee__track' );
		if ( ! track || track.getAttribute( 'data-prl-cloned' ) ) { return; }
		track.innerHTML += track.innerHTML;
		track.setAttribute( 'data-prl-cloned', '1' );
	}

	function boot() {
		each( document.querySelectorAll( '.prl-search' ), initSearch );
		initNav();
		each( document.querySelectorAll( '[data-prl-carousel]' ), initCarousel );
		each( document.querySelectorAll( '[data-prl-panels]' ), initPanels );
		each( document.querySelectorAll( '.prl-marquee' ), initMarquee );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}

	window.addEventListener( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', boot );
		}
	} );
}() );

/**
 * Mega menus. Opens on hover on pointer devices and on click everywhere,
 * so it works for touch and keyboard as well as mouse.
 */
( function () {
	'use strict';
	function initMega() {
		var bar = document.querySelector( '.prl-navbar' );
		if ( ! bar || bar.getAttribute( 'data-prl-mega-bound' ) ) { return; }
		bar.setAttribute( 'data-prl-mega-bound', '1' );

		var buttons = bar.querySelectorAll( '[data-prl-menu]' );
		var panels  = bar.querySelectorAll( '[data-prl-mega]' );
		var hoverOK = window.matchMedia( '(hover:hover) and (min-width:1025px)' );
		var closeTimer = null;

		function closeAll() {
			Array.prototype.forEach.call( panels, function ( p ) { p.hidden = true; } );
			Array.prototype.forEach.call( buttons, function ( b ) { b.setAttribute( 'aria-expanded', 'false' ); } );
		}
		function open( key ) {
			clearTimeout( closeTimer );
			Array.prototype.forEach.call( panels, function ( p ) {
				p.hidden = p.getAttribute( 'data-prl-mega' ) !== key;
			} );
			Array.prototype.forEach.call( buttons, function ( b ) {
				b.setAttribute( 'aria-expanded', String( b.getAttribute( 'data-prl-menu' ) === key ) );
			} );
		}

		Array.prototype.forEach.call( buttons, function ( b ) {
			var key = b.getAttribute( 'data-prl-menu' );
			b.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				if ( 'true' === b.getAttribute( 'aria-expanded' ) ) { closeAll(); } else { open( key ); }
			} );
			b.addEventListener( 'mouseenter', function () {
				if ( hoverOK.matches ) { open( key ); }
			} );
		} );

		bar.addEventListener( 'mouseleave', function () {
			if ( hoverOK.matches ) { closeTimer = setTimeout( closeAll, 180 ); }
		} );
		bar.addEventListener( 'mouseenter', function () { clearTimeout( closeTimer ); } );
		document.addEventListener( 'click', function ( e ) {
			if ( ! bar.contains( e.target ) ) { closeAll(); }
		} );
		document.addEventListener( 'keydown', function ( e ) {
			if ( 'Escape' === e.key ) { closeAll(); }
		} );

		/* Filter the library grid as you type. */
		Array.prototype.forEach.call( bar.querySelectorAll( '.prl-mega__filter' ), function ( input ) {
			var grid = input.closest( '.prl-mega__libs' ).querySelector( '.prl-mega__libs-grid' );
			input.addEventListener( 'input', function () {
				var q = input.value.trim().toLowerCase();
				Array.prototype.forEach.call( grid.querySelectorAll( '[data-prl-lib]' ), function ( a ) {
					a.hidden = q && a.getAttribute( 'data-prl-lib' ).indexOf( q ) === -1;
				} );
			} );
		} );
	}
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initMega );
	} else { initMega(); }
	window.addEventListener( 'elementor/frontend/init', function () {
		if ( window.elementorFrontend && window.elementorFrontend.hooks ) {
			window.elementorFrontend.hooks.addAction( 'frontend/element_ready/global', initMega );
		}
	} );
}() );

/** Home page library selector: filter as you type, Browse A-Z toggle. */
( function () {
	'use strict';
	function initSelector() {
		var root = document.querySelector( '.prl-select__card' );
		if ( ! root || root.getAttribute( 'data-prl-bound' ) ) { return; }
		root.setAttribute( 'data-prl-bound', '1' );
		var input  = root.querySelector( '.prl-select__input' );
		var browse = root.querySelector( '.prl-select__browse' );
		var grid   = root.querySelector( '.prl-select__grid' );
		if ( ! grid ) { return; }
		var links = grid.querySelectorAll( '[data-prl-lib]' );

		function show( on ) {
			grid.hidden = ! on;
			if ( browse ) { browse.setAttribute( 'aria-expanded', String( on ) ); }
		}
		if ( browse ) {
			browse.addEventListener( 'click', function () { show( grid.hidden ); } );
		}
		if ( input ) {
			input.addEventListener( 'input', function () {
				var q = input.value.trim().toLowerCase();
				if ( q ) { show( true ); }
				Array.prototype.forEach.call( links, function ( a ) {
					a.hidden = q && a.getAttribute( 'data-prl-lib' ).indexOf( q ) === -1;
				} );
			} );
			input.addEventListener( 'keydown', function ( e ) {
				if ( 'Enter' !== e.key ) { return; }
				e.preventDefault();
				var first = Array.prototype.filter.call( links, function ( a ) { return ! a.hidden; } )[ 0 ];
				if ( first ) { window.location.href = first.href; }
			} );
		}
	}
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initSelector );
	} else { initSelector(); }
}() );

/** Events audience chips + View All / Today, and the donate amount picker. */
( function () {
	'use strict';
	function initEvents() {
		var sec = document.querySelector( '.prl-events' );
		if ( sec && ! sec.getAttribute( 'data-prl-bound' ) ) {
			sec.setAttribute( 'data-prl-bound', '1' );
			var chips = sec.querySelectorAll( '[data-prl-aud]' );
			var rows  = sec.querySelectorAll( '[data-prl-ev-aud]' );
			var tabs  = sec.querySelectorAll( '.prl-events__toggle button' );
			var aud = 'All', only = false;
			function apply() {
				Array.prototype.forEach.call( rows, function ( row ) {
					var okA = 'All' === aud || row.getAttribute( 'data-prl-ev-aud' ) === aud;
					var okT = ! only || '1' === row.getAttribute( 'data-prl-ev-today' );
					row.hidden = ! ( okA && okT );
				} );
			}
			Array.prototype.forEach.call( chips, function ( c ) {
				c.addEventListener( 'click', function () {
					aud = c.getAttribute( 'data-prl-aud' );
					Array.prototype.forEach.call( chips, function ( o ) {
						o.classList.toggle( 'is-active', o === c );
						o.setAttribute( 'aria-pressed', String( o === c ) );
					} );
					apply();
				} );
			} );
			Array.prototype.forEach.call( tabs, function ( t ) {
				t.addEventListener( 'click', function () {
					only = 'today' === t.getAttribute( 'data-prl-tab' );
					Array.prototype.forEach.call( tabs, function ( o ) {
						o.setAttribute( 'aria-selected', String( o === t ) );
					} );
					apply();
				} );
			} );
		}
		var don = document.querySelector( '.prl-donate__amounts' );
		if ( don && ! don.getAttribute( 'data-prl-bound' ) ) {
			don.setAttribute( 'data-prl-bound', '1' );
			var cta = document.querySelector( '[data-prl-donate-cta]' );
			Array.prototype.forEach.call( don.querySelectorAll( '[data-prl-amt]' ), function ( b ) {
				b.addEventListener( 'click', function () {
					var amt = b.getAttribute( 'data-prl-amt' );
					Array.prototype.forEach.call( don.querySelectorAll( '[data-prl-amt]' ), function ( o ) {
						o.classList.toggle( 'is-active', o === b );
						o.setAttribute( 'aria-pressed', String( o === b ) );
					} );
					if ( cta ) { cta.textContent = ( 'Other' === amt ? 'Choose an amount' : 'Donate ' + amt ) + ' \u2192'; }
				} );
			} );
		}
		/* Special Features advances on its own every 6s, matching the design. */
		var sf = document.querySelector( '[data-prl-auto]' );
		if ( sf && ! sf.getAttribute( 'data-prl-auto-bound' ) ) {
			sf.setAttribute( 'data-prl-auto-bound', '1' );
			var every = parseInt( sf.getAttribute( 'data-prl-auto' ), 10 ) || 6000;
			var sfTabs = sf.querySelectorAll( '[data-prl-tab]' );
			var paused = false, k = 0;
			sf.addEventListener( 'mouseenter', function () { paused = true; } );
			sf.addEventListener( 'click', function () { paused = true; } );
			if ( ! window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
				setInterval( function () {
					if ( paused || ! sfTabs.length ) { return; }
					k = ( k + 1 ) % sfTabs.length;
					sfTabs[ k ].click();
				}, every );
			}
		}
	}
	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', initEvents );
	} else { initEvents(); }
}() );
