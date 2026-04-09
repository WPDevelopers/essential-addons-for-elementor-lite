/**
 * EAEL URL Search — Elementor editor script
 *
 * Upgrades every `.eael-url-ajax-search` <select> (rendered by the EAEL_URL
 * control's content_template) into a Select2 AJAX dropdown.
 *
 * Flow:
 *   1. `content_template()` fires `eael_url_search_init` on document.body and
 *      a MutationObserver watches the panel for newly inserted wrappers.
 *   2. `initSearch()` calls Select2 with an AJAX data source that calls the
 *      `eael_lr_search_redirect_post` action (≥3 chars threshold).
 *   3. On selection the permalink (`e.params.data.id`) is written into the
 *      sibling `[data-setting="url"]` input and Elementor's `input` event is
 *      dispatched so the model is dirtied and saved on the next save.
 */
( function ( $ ) {
	'use strict';

	if ( typeof eaelURLSearch === 'undefined' ) {
		return;
	}

	// WeakSet so we never double-initialise a wrapper element.
	var _initialized = ( typeof WeakSet !== 'undefined' ) ? new WeakSet() : null;

	function isInitialized( el ) {
		return _initialized ? _initialized.has( el ) : $( el ).data( 'eaelUrlReady' );
	}

	function markInitialized( el ) {
		if ( _initialized ) {
			_initialized.add( el );
		} else {
			$( el ).data( 'eaelUrlReady', true );
		}
	}

	/**
	 * Boot Select2 on a single `.eael-url-search-wrap` element.
	 *
	 * @param {jQuery} $wrap  The `.eael-url-search-wrap` wrapper div.
	 */
	function initSearch( $wrap ) {
		var el = $wrap[ 0 ];
		if ( ! el || isInitialized( el ) ) {
			return;
		}
		markInitialized( el );

		// ── DOM refs ──────────────────────────────────────────────────────────
		var $select   = $wrap.find( '.eael-url-ajax-search' );
		// The URL text input lives in a sibling of .eael-url-search-wrap inside
		// the same .elementor-control-field wrapper.
		var $urlInput = $wrap.closest( '.elementor-control-field' )
		                     .find( '[data-setting="url"]' );

		if ( ! $select.length || ! $urlInput.length ) {
			return;
		}

		// ── post_types from data attribute ────────────────────────────────────
		var postTypes;
		try {
			postTypes = JSON.parse( $wrap.attr( 'data-post-types' ) || '[]' );
		} catch ( e ) {
			postTypes = [ 'page', 'post', 'product' ];
		}
		if ( ! postTypes.length ) {
			postTypes = [ 'page', 'post', 'product' ];
		}

		// ── Pre-populate with the already-saved URL ───────────────────────────
		var currentUrl = $urlInput.val();
		if ( currentUrl ) {
			$select.append(
				new Option( currentUrl, currentUrl, true, true )
			);
		}

		// ── Determine best dropdownParent ─────────────────────────────────────
		// Elementor's panel scrolls inside its own container; anchoring the
		// dropdown there prevents overflow clipping.
		var $panelContent = $wrap.closest( '.elementor-panel-content-wrapper' );
		var dropdownParent = $panelContent.length ? $panelContent : $( 'body' );

		// ── Initialise Select2 ────────────────────────────────────────────────
		$select.select2( {
			dropdownParent:     dropdownParent,
			minimumInputLength: parseInt( eaelURLSearch.minChars, 10 ) || 3,
			placeholder:        eaelURLSearch.placeholder,
			allowClear:         true,
			language: {
				inputTooShort: function () {
					return eaelURLSearch.placeholder;
				},
				noResults: function () {
					return 'No results found';
				},
			},
			ajax: {
				url:      eaelURLSearch.ajaxUrl,
				type:     'POST',
				dataType: 'json',
				delay:    350,
				data: function ( params ) {
					return {
						action:     'eael_lr_search_redirect_post',
						nonce:      eaelURLSearch.nonce,
						search:     params.term,
						post_types: postTypes,
					};
				},
				processResults: function ( data ) {
					// Handler returns a flat [ {id, text}, … ] array.
					return { results: Array.isArray( data ) ? data : [] };
				},
				cache: true,
			},
		} );

		// ── Sync Select2 → URL input ──────────────────────────────────────────
		$select.on( 'select2:select', function ( e ) {
			var url = e.params.data.id; // AJAX handler sets id = permalink
			$urlInput.val( url ).trigger( 'input' );
			updateHint( $wrap, url );
		} );

		$select.on( 'select2:clear', function () {
			$urlInput.val( '' ).trigger( 'input' );
			updateHint( $wrap, '' );
		} );

		// ── Keep hint in sync if the admin types in the URL input directly ────
		$urlInput.on( 'input.eaelurl', function () {
			updateHint( $wrap, $( this ).val() );
		} );

		// Show the hint for whatever value is already there.
		updateHint( $wrap, currentUrl );
	}

	/**
	 * Update the informational hint below the <select>.
	 *
	 * @param {jQuery} $wrap
	 * @param {string} url
	 */
	function updateHint( $wrap, url ) {
		var $hint = $wrap.find( '.eael-url-search-hint' );
		if ( ! $hint.length ) {
			return;
		}
		if ( url ) {
			$hint.html(
				'<strong>Selected:</strong> ' +
				'<a href="' + escapeHtml( url ) + '" target="_blank" rel="noopener noreferrer" ' +
				'style="word-break:break-all;">' + escapeHtml( url ) + '</a>'
			);
		} else {
			$hint.text( 'Type \u2265 3 characters to search posts, pages and products \u2014 or type a URL directly below.' );
		}
	}

	/** Minimal HTML escape helper (avoids a jQuery round-trip). */
	function escapeHtml( str ) {
		return String( str )
			.replace( /&/g, '&amp;' )
			.replace( /</g, '&lt;' )
			.replace( />/g, '&gt;' )
			.replace( /"/g, '&quot;' );
	}

	// ── Trigger-based initialisation (from content_template body trigger) ─────
	$( document.body ).on( 'eael_url_search_init', function () {
		// Slight delay so the template has finished rendering its DOM.
		setTimeout( function () {
			$( '.eael-url-search-wrap' ).each( function () {
				initSearch( $( this ) );
			} );
		}, 80 );
	} );

	// ── MutationObserver: catch lazy-rendered / section-toggled instances ─────
	var _observer = new MutationObserver( function ( mutations ) {
		mutations.forEach( function ( mutation ) {
			mutation.addedNodes.forEach( function ( node ) {
				if ( node.nodeType !== 1 /* ELEMENT_NODE */ ) {
					return;
				}
				var $node = $( node );
				if ( $node.hasClass( 'eael-url-search-wrap' ) ) {
					initSearch( $node );
				}
				$node.find( '.eael-url-search-wrap' ).each( function () {
					initSearch( $( this ) );
				} );
			} );
		} );
	} );

	/**
	 * Attach the observer to the Elementor panel once it exists.
	 * The panel is a persistent DOM element so we only need to observe it once.
	 */
	function attachObserver() {
		var panelEl = document.getElementById( 'elementor-panel' );
		if ( panelEl ) {
			_observer.observe( panelEl, { childList: true, subtree: true } );
		}
	}

	// Elementor may already be ready, or we may be loading before it is.
	if ( typeof elementor !== 'undefined' && elementor.initialized ) {
		attachObserver();
	} else {
		$( window ).on( 'elementor:init', attachObserver );
		// Belt-and-suspenders: try after a short delay.
		setTimeout( attachObserver, 1500 );
	}

} )( jQuery );
