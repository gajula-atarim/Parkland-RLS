/**
 * Parkland home page behaviour, class-driven.
 *
 * The home page is built from native Elementor widgets so the client can edit
 * it. Elementor free cannot emit custom attributes (Custom Attributes is a Pro
 * control), so none of the data-prl-* hooks the header script uses are
 * available here. Everything below keys off CSS classes instead, and toggles
 * `is-active` / `is-hidden` rather than the `hidden` attribute, because the
 * attribute would sit on markup Elementor owns and regenerates.
 *
 * Header behaviour (search switcher, mega menus, mobile nav) still lives in
 * prl-site.js and still uses data attributes - that markup is a UAE template,
 * not this page.
 */
( function () {
	'use strict';

	var reduced = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var all = function ( sel, root ) { return Array.prototype.slice.call( ( root || document ).querySelectorAll( sel ) ); };
	var one = function ( sel, root ) { return ( root || document ).querySelector( sel ); };

	/* A rotating group: panels switched by `is-hidden`, triggers by `is-active`. */
	function group( panelSel, triggerSel, interval ) {
		var panels   = all( panelSel );
		var triggers = all( triggerSel );
		if ( panels.length < 2 ) { return null; }
		var i = 0, timer = null, paused = false;

		function show( n ) {
			i = ( n + panels.length ) % panels.length;
			panels.forEach( function ( p, k ) { p.classList.toggle( 'is-hidden', k !== i ); } );
			triggers.forEach( function ( t, k ) {
				t.classList.toggle( 'is-active', k === i );
				t.setAttribute( 'aria-selected', String( k === i ) );
			} );
		}
		function start() {
			if ( reduced || timer || ! interval ) { return; }
			timer = setInterval( function () { if ( ! paused ) { show( i + 1 ); } }, interval );
		}

		triggers.forEach( function ( t, k ) {
			t.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				paused = true;
				show( k );
			} );
		} );
		show( 0 );
		start();
		return { show: function ( n ) { paused = true; show( n ); }, next: function () { paused = true; show( i + 1 ); },
		         prev: function () { paused = true; show( i - 1 ); }, pause: function () { paused = true; } };
	}

	/* ---- Hero: 4 slides, 7s.
	   The design places the dots inline after the CTA, and the CTA label differs
	   per slide, so every slide carries its own dot row. The index is therefore
	   the dot's position *within its own row*, not its position in the document -
	   reading it document-wide is what left the first build with no active dot
	   and dead arrows. */
	function initHero() {
		var hero = one( '.prl-hero' );
		if ( ! hero ) { return; }
		var slides = all( '.prl-hero__slide', hero );
		if ( slides.length < 2 ) { return; }
		var i = 0, timer = null, paused = false;

		function show( n ) {
			i = ( n + slides.length ) % slides.length;
			slides.forEach( function ( s, k ) { s.classList.toggle( 'is-hidden', k !== i ); } );
			/* Mark the i-th dot in every row, so whichever row is on screen reads right. */
			slides.forEach( function ( s ) {
				all( '.prl-hero__dot', s ).forEach( function ( d, k ) {
					d.classList.toggle( 'is-active', k === i );
					d.setAttribute( 'aria-current', String( k === i ) );
				} );
			} );
		}
		function start() {
			if ( reduced || timer ) { return; }
			timer = setInterval( function () { if ( ! paused ) { show( i + 1 ); } }, 7000 );
		}

		all( '.prl-hero__dot', hero ).forEach( function ( d ) {
			var k = Array.prototype.indexOf.call( d.parentElement.children, d );
			d.setAttribute( 'role', 'button' );
			d.setAttribute( 'tabindex', '0' );
			d.setAttribute( 'aria-label', 'Slide ' + ( k + 1 ) );
			d.addEventListener( 'click', function () { paused = true; show( k ); } );
		} );
		var prev = one( '.prl-hero__prev' ), next = one( '.prl-hero__next' );
		if ( prev ) {
			prev.setAttribute( 'role', 'button' );
			prev.setAttribute( 'tabindex', '0' );
			prev.setAttribute( 'aria-label', 'Previous slide' );
			prev.addEventListener( 'click', function () { paused = true; show( i - 1 ); } );
		}
		if ( next ) {
			next.setAttribute( 'role', 'button' );
			next.setAttribute( 'tabindex', '0' );
			next.setAttribute( 'aria-label', 'Next slide' );
			next.addEventListener( 'click', function () { paused = true; show( i + 1 ); } );
		}
		hero.addEventListener( 'mouseenter', function () { paused = true; } );
		show( 0 );
		start();
	}

	/* ---- Special Features: 3 panels, 6s, clicking a row pins it. */
	function initFeatures() {
		group( '.prl-sf__panel', '.prl-sf__item', 6000 );
	}

	/* ---- New & Notable: three shelves, no auto-advance in the design. */
	function initBooks() {
		group( '.prl-books__shelf', '.prl-books__tab', 0 );
	}

	/* ---- Library Stories: 3 quotes, 8s, avatars double as the dots. */
	function initQuotes() {
		var g = group( '.prl-quotes__slide', '.prl-quotes__face', 8000 );
		if ( ! g ) { return; }
		var prev = one( '.prl-quotes__arrow--prev' ), next = one( '.prl-quotes__arrow--next' );
		if ( prev ) { prev.addEventListener( 'click', function ( e ) { e.preventDefault(); g.prev(); } ); }
		if ( next ) { next.addEventListener( 'click', function ( e ) { e.preventDefault(); g.next(); } ); }
	}

	/* ---- Library selector: Browse A-Z toggle plus filter-as-you-type. */
	function initSelector() {
		var grid = one( '.prl-select__grid' );
		if ( ! grid ) { return; }
		var browse = one( '.prl-select__browse' );
		var items  = all( '.elementor-icon-list-item', grid );
		var input  = one( '.prl-select__search input[type="search"], .prl-select__search input' );

		function open( on ) {
			grid.classList.toggle( 'is-open', on );
			if ( browse ) { browse.setAttribute( 'aria-expanded', String( on ) ); }
		}
		if ( browse ) {
			browse.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				open( ! grid.classList.contains( 'is-open' ) );
			} );
		}
		if ( input ) {
			/* The Search widget has no placeholder field, so the design's prompt is
			   set here rather than left as WordPress's "Search ...". */
			input.placeholder = 'Type a library or town, e.g. Ponoka';
			input.setAttribute( 'aria-label', 'Find your library' );
			var form = input.closest( 'form' );
			if ( form ) { form.addEventListener( 'submit', function ( e ) { e.preventDefault(); } ); }
			input.addEventListener( 'input', function () {
				var q = input.value.trim().toLowerCase();
				if ( q ) { open( true ); }
				items.forEach( function ( li ) {
					var txt = ( li.textContent || '' ).trim().toLowerCase();
					li.classList.toggle( 'is-filtered', !! q && txt.indexOf( q ) === -1 );
				} );
			} );
			input.addEventListener( 'keydown', function ( e ) {
				if ( 'Enter' !== e.key ) { return; }
				e.preventDefault();
				var first = items.filter( function ( li ) { return ! li.classList.contains( 'is-filtered' ); } )[ 0 ];
				var a = first && first.querySelector( 'a' );
				if ( a ) { window.location.href = a.href; }
			} );
		}
	}

	/* ---- Events: audience chips and the All / Today toggle.
	   Rows carry prl-ev-aud-<index> and prl-ev-today-<0|1>; the chip index
	   lines up with the audience list the rows were built from. */
	function initEvents() {
		var sec = one( '.prl-events' );
		if ( ! sec ) { return; }
		var chips = all( '.prl-chip', sec ).filter( function ( c ) { return ! c.classList.contains( 'prl-chip--sm' ); } );
		var rows  = all( '.prl-events__row', sec );
		var tabs  = all( '.prl-events__tab', sec );
		var aud = 0, todayOnly = false;

		function apply() {
			var shown = 0;
			rows.forEach( function ( r ) {
				var okA = 0 === aud || r.classList.contains( 'prl-ev-aud-' + aud );
				var okT = ! todayOnly || r.classList.contains( 'prl-ev-today-1' );
				var on = okA && okT;
				r.classList.toggle( 'is-hidden', ! on );
				if ( on ) { shown++; }
			} );
			/* The design shows an empty state rather than a blank gap. */
			var empty = one( '.prl-events__empty', sec );
			if ( empty ) { empty.classList.toggle( 'is-hidden', shown > 0 ); }
		}
		chips.forEach( function ( c, k ) {
			c.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				aud = k;
				chips.forEach( function ( o ) { o.classList.toggle( 'is-active', o === c ); } );
				chips.forEach( function ( o ) { o.setAttribute( 'aria-pressed', String( o === c ) ); } );
				apply();
			} );
		} );
		tabs.forEach( function ( t, k ) {
			t.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				todayOnly = ( 1 === k );
				tabs.forEach( function ( o ) {
					o.classList.toggle( 'is-active', o === t );
					o.setAttribute( 'aria-selected', String( o === t ) );
				} );
				apply();
			} );
		} );
		apply();
	}

	/* ---- Donate: the amount buttons rewrite the call to action. */
	function initDonate() {
		var amounts = all( '.prl-donate__amt' );
		var cta = one( '.prl-donate__cta .elementor-button-text' ) || one( '.prl-donate__cta' );
		if ( ! amounts.length || ! cta ) { return; }
		amounts.forEach( function ( b ) {
			b.addEventListener( 'click', function ( e ) {
				e.preventDefault();
				var amt = ( b.textContent || '' ).trim();
				amounts.forEach( function ( o ) {
					o.classList.toggle( 'is-active', o === b );
					o.setAttribute( 'aria-pressed', String( o === b ) );
				} );
				cta.textContent = ( 'Other' === amt ? 'Choose an amount' : 'Donate ' + amt ) + ' →';
			} );
		} );
	}

	/* ---- Marquee: duplicate the row so prlMarquee loops seamlessly at -50%. */
	function initMarquee() {
		var list = one( '.prl-marquee__track .elementor-icon-list-items' );
		if ( ! list || list.getAttribute( 'data-prl-cloned' ) ) { return; }
		list.innerHTML += list.innerHTML;
		list.setAttribute( 'data-prl-cloned', '1' );
	}

	/* ---- Office hours: mark today's row.
	   The design highlights it server-side; the native rebuild has no template,
	   so it is set here from the visitor's own date. */
	function initHours() {
		var rows = all( '.prl-hours__row' );
		if ( rows.length !== 7 ) { return; }
		rows[ new Date().getDay() ].classList.add( 'is-today' );
	}

	function boot() {
		initHours();
		initHero();
		initFeatures();
		initBooks();
		initQuotes();
		initSelector();
		initEvents();
		initDonate();
		initMarquee();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', boot );
	} else {
		boot();
	}
}() );
