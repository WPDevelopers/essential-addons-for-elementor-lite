/**
 * ThinkRank — Gutenberg "Configure SEO" document panel.
 *
 * A subtle, attributed, dismissible entry in the block editor sidebar that
 * lets users add ThinkRank (AI SEO) to the page they're editing. Written with
 * the wp.* editor globals so it needs NO build step. Enqueued only when
 * ThinkRank is not active and the user hasn't dismissed the promo.
 */
( function ( wp, data ) {
	if ( ! wp || ! wp.plugins || ! wp.element || ! data ) {
		return;
	}

	var editPost = wp.editPost || {};
	var Panel    = editPost.PluginDocumentSettingPanel;
	if ( ! Panel ) {
		return; // Older editor without the document-panel slot — skip quietly.
	}

	var el         = wp.element.createElement;
	var useState   = wp.element.useState;
	var Button     = wp.components.Button;
	var __         = ( wp.i18n && wp.i18n.__ ) ? wp.i18n.__ : function ( s ) { return s; };
	var registerPlugin = wp.plugins.registerPlugin;

	function post( action ) {
		var body = new URLSearchParams();
		body.append( 'action', action );
		body.append( 'security', data.nonce );
		if ( 'wpdeveloper_install_plugin' === action ) { body.append( 'slug', data.slug ); }
		return window.fetch( data.ajaxurl, {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
			body: body.toString()
		} ).then( function ( r ) { return r.json(); } );
	}

	function Body() {
		var s = useState( { status: 'idle', hidden: false } );
		var state = s[0], set = s[1];

		if ( state.hidden ) { return null; }

		function install() {
			set( { status: 'installing', hidden: false } );
			post( 'wpdeveloper_install_plugin' ).then( function ( res ) {
				if ( res && res.success ) {
					set( { status: 'done', hidden: false } );
					window.setTimeout( function () { window.location.href = data.openUrl; }, 800 );
				} else {
					set( { status: 'error', hidden: false } );
				}
			} ).catch( function () { set( { status: 'error', hidden: false } ); } );
		}

		function dismiss() {
			post( 'eael_thinkrank_dismiss' );
			set( { status: state.status, hidden: true } );
		}

		var label = __( 'Enable SEO tool', 'essential-addons-for-elementor-lite' );
		if ( 'installing' === state.status ) { label = __( 'Installing…', 'essential-addons-for-elementor-lite' ); }
		if ( 'done' === state.status ) { label = __( 'Opening ThinkRank…', 'essential-addons-for-elementor-lite' ); }

		return el( 'div', { className: 'eael-tr-gb' },
			el( 'p', { className: 'eael-tr-gb__desc' },
				__( 'Add AI SEO analysis to this page with ThinkRank — titles, meta, schema & readability. Free.', 'essential-addons-for-elementor-lite' )
			),
			'error' === state.status ? el( 'p', { className: 'eael-tr-gb__err' },
				__( 'Could not install automatically. Try Plugins → Add New.', 'essential-addons-for-elementor-lite' )
			) : null,
			el( Button, {
				variant: 'primary',
				className: 'eael-tr-gb__cta',
				disabled: 'installing' === state.status || 'done' === state.status,
				onClick: install
			}, label ),
			el( Button, { variant: 'link', className: 'eael-tr-gb__later', onClick: dismiss },
				__( 'Don’t show again', 'essential-addons-for-elementor-lite' )
			)
		);
	}

	registerPlugin( 'eael-thinkrank-seo', {
		render: function () {
			return el( Panel, {
				name: 'eael-thinkrank-seo-panel',
				title: __( 'Configure SEO', 'essential-addons-for-elementor-lite' ),
				className: 'eael-tr-gb-panel'
			}, el( Body ) );
		},
		icon: null
	} );
} )( window.wp, window.eaelThinkRank );
