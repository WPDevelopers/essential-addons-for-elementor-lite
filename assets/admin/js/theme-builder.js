/**
 * Essential Addons — Theme Builder list table.
 *
 * Quick Edit, Bulk Edit and the delete confirmation, i.e. the behaviour that has
 * to live next to WordPress's own list-table markup. The "Add New Template" and
 * "Display Conditions" modals are a React app —
 * see includes/templates/admin/theme-builder/.
 *
 * @since 6.7.3
 */
( function ( $ ) {
	'use strict';

	var config = window.eaelThemeBuilder || {};
	var i18n = config.i18n || {};
	var priorityRange = config.priority || { min: 1, max: 100, default: 10 };

	/* ------------------------------------------------------------------
	 * Helpers
	 * --------------------------------------------------------------- */

	function request( action, data ) {
		return $.ajax( {
			url: config.ajaxUrl,
			type: 'POST',
			dataType: 'json',
			data: $.extend( { action: action, nonce: config.nonce }, data )
		} );
	}

	function setBusy( $form, busy, label ) {
		var $button = $form.find( '.eael-tb-submit' );

		$button.prop( 'disabled', busy );

		if ( busy ) {
			$button.data( 'label', $button.text() ).text( label || i18n.saving );
		} else if ( $button.data( 'label' ) ) {
			$button.text( $button.data( 'label' ) );
		}
	}

	/* ------------------------------------------------------------------
	 * Events
	 * --------------------------------------------------------------- */

	$( function () {
		var $document = $( document );

		$document.on( 'click', '.eael-tb-confirm', function ( event ) {
			if ( ! window.confirm( i18n.confirmDelete ) ) {
				event.preventDefault();
			}
		} );

		/* --------------------------------------------------------------
		 * Quick Edit
		 * ----------------------------------------------------------- */

		$document.on( 'click', '.eael-tb-quick-edit', function ( event ) {
			event.preventDefault();
			openQuickEdit( $( this ) );
		} );

		$document.on( 'click', '.eael-tb-quick-edit-cancel', function ( event ) {
			event.preventDefault();
			closeQuickEdit( $( this ).closest( '.eael-tb-quick-edit-row' ) );
		} );

		$document.on( 'keyup', '.eael-tb-quick-edit-row', function ( event ) {
			if ( 27 === event.keyCode ) {
				closeQuickEdit( $( this ) );
			}
		} );

		$document.on( 'click', '.eael-tb-quick-edit-save', function ( event ) {
			event.preventDefault();
			saveQuickEdit( $( this ).closest( '.eael-tb-quick-edit-form' ) );
		} );

		// The row lives inside the list table's own <form>, so Enter would submit
		// that one and reload the screen.
		$document.on( 'keydown', '.eael-tb-quick-edit-row input', function ( event ) {
			if ( 13 === event.keyCode ) {
				event.preventDefault();
				saveQuickEdit( $( this ).closest( '.eael-tb-quick-edit-form' ) );
			}
		} );

		/* --------------------------------------------------------------
		 * Bulk Edit
		 * ----------------------------------------------------------- */

		// Every other bulk action is a normal form submit handled in PHP. This one
		// opens a panel instead, so the submit has to be stopped before it starts.
		$document.on( 'click', '#doaction, #doaction2', function ( event ) {
			var which = 'doaction2' === this.id ? 'bottom' : 'top';

			if ( 'edit' !== $( '#bulk-action-selector-' + which ).val() ) {
				return;
			}

			event.preventDefault();
			openBulkEdit();
		} );

		$document.on( 'click', '.eael-tb-bulk-edit-cancel', function ( event ) {
			event.preventDefault();
			closeBulkEdit();
		} );

		$document.on( 'keyup', '.eael-tb-bulk-edit-row', function ( event ) {
			if ( 27 === event.keyCode ) {
				closeBulkEdit();
			}
		} );

		$document.on( 'click', '.eael-tb-bulk-edit-save', function ( event ) {
			event.preventDefault();
			saveBulkEdit( $( this ).closest( '.eael-tb-bulk-edit-form' ) );
		} );

		$document.on( 'keydown', '.eael-tb-bulk-edit-row input', function ( event ) {
			if ( 13 === event.keyCode ) {
				event.preventDefault();
				saveBulkEdit( $( this ).closest( '.eael-tb-bulk-edit-form' ) );
			}
		} );

		// Dropping a template from the list also unticks its row, so the panel and
		// the checkboxes cannot disagree about what is about to be edited.
		$document.on( 'click', '.eael-tb-bulk-remove', function ( event ) {
			event.preventDefault();

			var $chip = $( this ).closest( '.eael-tb-bulk-title' );
			var id = parseInt( $chip.data( 'template-id' ), 10 );

			$( '.wp-list-table' ).find( 'input[name="template_ids[]"][value="' + id + '"]' ).prop( 'checked', false );
			$chip.remove();

			if ( ! $( '.eael-tb-bulk-title' ).length ) {
				closeBulkEdit();
			}
		} );
	} );

	function buildOptions( pairs, selected ) {
		return pairs
			.map( function ( pair ) {
				return (
					'<option value="' + escapeHtml( pair[ 0 ] ) + '"' +
					( String( pair[ 0 ] ) === String( selected ) ? ' selected' : '' ) +
					'>' + escapeHtml( pair[ 1 ] ) + '</option>'
				);
			} )
			.join( '' );
	}

	/**
	 * Split a `YYYY-MM-DD HH:MM:SS` post date into the five date inputs.
	 */
	function splitDate( value ) {
		var match = /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/.exec( String( value || '' ) );

		if ( ! match ) {
			var now = new Date();
			return {
				aa: String( now.getFullYear() ),
				mm: ( '0' + ( now.getMonth() + 1 ) ).slice( -2 ),
				jj: ( '0' + now.getDate() ).slice( -2 ),
				hh: ( '0' + now.getHours() ).slice( -2 ),
				mn: ( '0' + now.getMinutes() ).slice( -2 ),
			};
		}

		return { aa: match[ 1 ], mm: match[ 2 ], jj: match[ 3 ], hh: match[ 4 ], mn: match[ 5 ] };
	}

	function textField( name, value, extra ) {
		return (
			'<span class="input-text-wrap">' +
			'<input type="text" name="' + name + '" value="' + escapeHtml( value ) + '" ' + ( extra || '' ) + ' />' +
			'</span>'
		);
	}

	/**
	 * Swap a table row for an inline editor built from its stashed data.
	 *
	 * Uses core's `inline-edit-*` markup and class names so the panel inherits
	 * WordPress's own list-table styling instead of re-inventing it.
	 */
	function openQuickEdit( $button ) {
		closeAllQuickEdits();

		var id = parseInt( $button.data( 'template-id' ), 10 );
		var $row = $button.closest( 'tr' );
		var $data = $( '#eael-tb-inline_' + id );
		var colspan = $row.children( 'td, th' ).length;

		var current = {
			title: $data.attr( 'data-title' ),
			slug: $data.attr( 'data-slug' ),
			date: $data.attr( 'data-date' ),
			password: $data.attr( 'data-password' ),
			private: 'yes' === $data.attr( 'data-private' ),
			pageTemplate: $data.attr( 'data-page-template' ) || 'default',
			status: $data.attr( 'data-status' ),
			type: $data.attr( 'data-type' ),
			priority: $data.attr( 'data-priority' ),
			active: 'no' !== $data.attr( 'data-active' ),
		};

		var date = splitDate( current.date );

		var monthOptions = ( config.months || [] )
			.map( function ( month ) {
				return (
					'<option value="' + month.value + '"' +
					( month.value === date.mm ? ' selected' : '' ) +
					'>' + escapeHtml( month.label ) + '</option>'
				);
			} )
			.join( '' );

		var typeOptions = ( config.types || [] ).map( function ( type ) {
			return [ type.slug, type.label ];
		} );

		var templateOptions = Object.keys( config.pageTemplates || {} ).map( function ( key ) {
			return [ key, config.pageTemplates[ key ] ];
		} );

		// A private template reports post_status "private"; core shows it as
		// Published with the Private box ticked.
		var statusValue = 'private' === current.status ? 'publish' : current.status;

		// Deliberately a <div>, not a <form>: this row is injected inside the list
		// table's own <form>, and a nested form is invalid HTML — the browser
		// submits it natively instead of letting the handler intercept.
		var html =
			// `quick-edit-row-post` is what core's list-tables.css keys the 40%/39%
			// two-column split on — without it both fieldsets stay full width.
			'<tr class="inline-edit-row inline-edit-row-post quick-edit-row quick-edit-row-post inline-edit-post eael-tb-inline-row eael-tb-quick-edit-row" data-template-id="' + id + '">' +
				'<td colspan="' + colspan + '" class="colspanchange">' +
					// `inline-edit-wrapper` is what supplies core's padding —
					// `tr.inline-edit-row td` itself is padding: 0.
					'<div class="inline-edit-wrapper eael-tb-quick-edit-form" role="region" aria-labelledby="eael-tb-quick-edit-legend-' + id + '">' +

						'<fieldset class="inline-edit-col-left">' +
							'<legend class="inline-edit-legend" id="eael-tb-quick-edit-legend-' + id + '">' + escapeHtml( i18n.quickEdit ) + '</legend>' +
							'<div class="inline-edit-col">' +
								'<label>' +
									'<span class="title">' + escapeHtml( i18n.titleLabel ) + '</span>' +
									textField( 'title', current.title, 'class="ptitle"' ) +
								'</label>' +
								'<label>' +
									'<span class="title">' + escapeHtml( i18n.slugLabel ) + '</span>' +
									textField( 'slug', current.slug ) +
								'</label>' +
								'<fieldset class="inline-edit-date">' +
									'<legend><span class="title">' + escapeHtml( i18n.dateLabel ) + '</span></legend>' +
									'<div class="timestamp-wrap">' +
										'<label><span class="screen-reader-text">' + escapeHtml( i18n.monthLabel ) + '</span>' +
											'<select name="mm">' + monthOptions + '</select>' +
										'</label>' +
										'<label><span class="screen-reader-text">' + escapeHtml( i18n.dayLabel ) + '</span>' +
											'<input type="text" name="jj" value="' + date.jj + '" size="2" maxlength="2" autocomplete="off" />' +
										'</label>, ' +
										'<label><span class="screen-reader-text">' + escapeHtml( i18n.yearLabel ) + '</span>' +
											'<input type="text" name="aa" value="' + date.aa + '" size="4" maxlength="4" autocomplete="off" />' +
										'</label> ' + escapeHtml( i18n.atLabel ) + ' ' +
										'<label><span class="screen-reader-text">' + escapeHtml( i18n.hourLabel ) + '</span>' +
											'<input type="text" name="hh" value="' + date.hh + '" size="2" maxlength="2" autocomplete="off" />' +
										'</label>:' +
										'<label><span class="screen-reader-text">' + escapeHtml( i18n.minuteLabel ) + '</span>' +
											'<input type="text" name="mn" value="' + date.mn + '" size="2" maxlength="2" autocomplete="off" />' +
										'</label>' +
									'</div>' +
								'</fieldset>' +
								'<div class="inline-edit-group wp-clearfix">' +
									'<label class="alignleft">' +
										'<span class="title">' + escapeHtml( i18n.passwordLabel ) + '</span>' +
										'<span class="input-text-wrap">' +
											'<input type="text" name="password" class="inline-edit-password-input" value="' + escapeHtml( current.password ) + '" />' +
										'</span>' +
									'</label>' +
									'<span class="alignleft inline-edit-or">' + i18n.orLabel + '</span>' +
									'<label class="alignleft inline-edit-private">' +
										'<input type="checkbox" name="private"' + ( current.private ? ' checked' : '' ) + ' />' +
										'<span class="checkbox-title">' + escapeHtml( i18n.privateLabel ) + '</span>' +
									'</label>' +
								'</div>' +
							'</div>' +
						'</fieldset>' +

						'<fieldset class="inline-edit-col-right">' +
							'<div class="inline-edit-col">' +
								'<label class="alignleft">' +
									'<span class="title">' + escapeHtml( i18n.templateLabel ) + '</span>' +
									'<select name="page_template">' + buildOptions( templateOptions, current.pageTemplate ) + '</select>' +
								'</label>' +
								'<label class="inline-edit-status alignleft">' +
									'<span class="title">' + escapeHtml( i18n.statusLabel ) + '</span>' +
									'<select name="status">' + buildOptions( [
										[ 'publish', i18n.statusPublish ],
										[ 'pending', i18n.statusPending ],
										[ 'draft', i18n.statusDraft ],
									], statusValue ) + '</select>' +
								'</label>' +
								'<label class="alignleft">' +
									'<span class="title">' + escapeHtml( i18n.typeLabel ) + '</span>' +
									'<select name="type">' + buildOptions( typeOptions, current.type ) + '</select>' +
								'</label>' +
								'<label class="alignleft" title="' + escapeHtml( i18n.priorityHelp ) + '">' +
									'<span class="title">' + escapeHtml( i18n.priorityLabel ) + '</span>' +
									'<input type="number" name="priority" class="eael-tb-priority-input" inputmode="numeric" step="1"' +
										' min="' + priorityRange.min + '" max="' + priorityRange.max + '"' +
										' value="' + parseInt( current.priority, 10 ) + '" />' +
								'</label>' +
								'<div class="inline-edit-group wp-clearfix">' +
									'<label class="alignleft eael-tb-active-label" title="' + escapeHtml( i18n.activeHelp ) + '">' +
										'<input type="checkbox" name="active"' + ( current.active ? ' checked' : '' ) + ' />' +
										'<span class="checkbox-title">' + escapeHtml( i18n.activeLabel ) + '</span>' +
									'</label>' +
								'</div>' +
							'</div>' +
						'</fieldset>' +

						'<div class="submit inline-edit-save">' +
							'<button type="button" class="button button-primary eael-tb-submit eael-tb-quick-edit-save">' + escapeHtml( i18n.update ) + '</button> ' +
							'<button type="button" class="button eael-tb-quick-edit-cancel">' + escapeHtml( i18n.cancel ) + '</button>' +
							'<span class="notice notice-error notice-alt inline hidden eael-tb-quick-edit-notice">' +
								'<p class="error eael-tb-quick-edit-error" role="alert"></p>' +
							'</span>' +
							'<br class="clear" />' +
						'</div>' +

					'</div>' +
				'</td>' +
			'</tr>';

		$row.addClass( 'eael-tb-row-hidden' ).hide().after( html );
		$button.attr( 'aria-expanded', 'true' );
		$row.next( '.eael-tb-quick-edit-row' ).find( 'input[name="title"]' ).trigger( 'focus' );
	}

	function closeQuickEdit( $editRow ) {
		var $row = $editRow.prev( 'tr' );

		$row.removeClass( 'eael-tb-row-hidden' ).show();
		$row.find( '.eael-tb-quick-edit' ).attr( 'aria-expanded', 'false' );
		$editRow.remove();
	}

	function closeAllQuickEdits() {
		$( '.eael-tb-quick-edit-row' ).each( function () {
			closeQuickEdit( $( this ) );
		} );
	}

	/**
	 * Repaint one row from a server payload and re-stash its inline data.
	 *
	 * Shared by Quick Edit and Bulk Edit — both get the same payload back, so a
	 * row updated either way ends up in the same state, including the stash Quick
	 * Edit reads the next time it is opened.
	 */
	function repaintRow( $row, data ) {
		if ( ! $row.length || ! data ) {
			return;
		}

		$row.find( '.row-title' ).text( data.title );
		$row.find( '.eael-tb-post-states' ).html( data.states );
		$row.find( '.template_type' ).text( data.type_label );
		$row.find( '.date' ).html( data.date_html );

		var $stash = $( '#eael-tb-inline_' + data.id );

		Object.keys( data.inline ).forEach( function ( key ) {
			$stash.attr( 'data-' + key, data.inline[ key ] );
		} );

		$stash.removeData();

		$row.addClass( 'eael-tb-row-updated' );
		window.setTimeout( function () {
			$row.removeClass( 'eael-tb-row-updated' );
		}, 1200 );
	}

	/* ------------------------------------------------------------------
	 * Bulk Edit
	 * --------------------------------------------------------------- */

	function rowFor( id ) {
		return $( '.wp-list-table' ).find( 'input[name="template_ids[]"][value="' + id + '"]' ).closest( 'tr' );
	}

	/**
	 * Open the bulk editor above the table for every ticked row.
	 *
	 * Every field carries a "no change" value and starts on it, so the panel can
	 * be used to set one thing without flattening everything else the selected
	 * templates disagree on — the same contract core's bulk edit offers.
	 */
	function openBulkEdit() {
		closeAllQuickEdits();
		closeBulkEdit();

		var $table = $( '.wp-list-table' );
		var selected = $table
			.find( 'tbody input[name="template_ids[]"]:checked' )
			.map( function () {
				var $row = $( this ).closest( 'tr' );

				return {
					id: parseInt( this.value, 10 ),
					title: $.trim( $row.find( '.row-title' ).text() ),
				};
			} )
			.get();

		if ( ! selected.length ) {
			window.alert( i18n.bulkNoSelection );

			return;
		}

		var colspan = $table.find( 'thead tr' ).first().children( 'td, th' ).length;

		var noChange = [ [ '', i18n.noChange ] ];

		var typeOptions = noChange.concat(
			( config.types || [] ).map( function ( type ) {
				return [ type.slug, type.label ];
			} )
		);

		var statusOptions = noChange.concat( [
			[ 'publish', i18n.statusPublish ],
			[ 'pending', i18n.statusPending ],
			[ 'draft', i18n.statusDraft ],
		] );

		var activeOptions = noChange.concat( [
			[ 'yes', i18n.activeLabel ],
			[ 'no', i18n.inactiveLabel ],
		] );

		var chips = selected
			.map( function ( item ) {
				return (
					'<div class="eael-tb-bulk-title" data-template-id="' + item.id + '">' +
						'<button type="button" class="button-link eael-tb-bulk-remove" aria-label="' + escapeHtml( i18n.removeFromBulk ) + '">' +
							'<span aria-hidden="true">&times;</span>' +
						'</button>' +
						'<span class="eael-tb-bulk-title-text">' + escapeHtml( item.title ) + '</span>' +
					'</div>'
				);
			} )
			.join( '' );

		// A <div>, not a <form> — same reason as Quick Edit: this row is injected
		// inside the list table's own <form>, and a nested form is invalid HTML.
		var html =
			'<tr class="inline-edit-row inline-edit-row-post bulk-edit-row bulk-edit-row-post inline-edit-post eael-tb-inline-row eael-tb-bulk-edit-row">' +
				'<td colspan="' + colspan + '" class="colspanchange">' +
					'<div class="inline-edit-wrapper eael-tb-bulk-edit-form" role="region" aria-labelledby="eael-tb-bulk-edit-legend">' +

						'<fieldset class="inline-edit-col-left">' +
							'<legend class="inline-edit-legend" id="eael-tb-bulk-edit-legend">' + escapeHtml( i18n.bulkEdit ) + '</legend>' +
							'<div class="inline-edit-col">' +
								'<div class="eael-tb-bulk-titles">' + chips + '</div>' +
							'</div>' +
						'</fieldset>' +

						'<fieldset class="inline-edit-col-right">' +
							'<div class="inline-edit-col">' +
								'<label class="alignleft">' +
									'<span class="title">' + escapeHtml( i18n.typeLabel ) + '</span>' +
									'<select name="type">' + buildOptions( typeOptions, '' ) + '</select>' +
								'</label>' +
								'<label class="inline-edit-status alignleft">' +
									'<span class="title">' + escapeHtml( i18n.statusLabel ) + '</span>' +
									'<select name="status">' + buildOptions( statusOptions, '' ) + '</select>' +
								'</label>' +
								'<label class="alignleft" title="' + escapeHtml( i18n.priorityHelp ) + '">' +
									'<span class="title">' + escapeHtml( i18n.priorityLabel ) + '</span>' +
									'<input type="number" name="priority" class="eael-tb-priority-input" inputmode="numeric" step="1"' +
										' min="' + priorityRange.min + '" max="' + priorityRange.max + '"' +
										// The field is ~5.5em wide, so the full "no change" label
									// would be clipped to nonsense. An empty box already means
									// no change here; the dash just makes that look deliberate.
									' value="" placeholder="&mdash;" />' +
								'</label>' +
								'<label class="alignleft" title="' + escapeHtml( i18n.activeHelp ) + '">' +
									'<span class="title">' + escapeHtml( i18n.activeLabel ) + '</span>' +
									'<select name="active">' + buildOptions( activeOptions, '' ) + '</select>' +
								'</label>' +
							'</div>' +
						'</fieldset>' +

						'<div class="submit inline-edit-save">' +
							'<button type="button" class="button button-primary eael-tb-submit eael-tb-bulk-edit-save">' + escapeHtml( i18n.update ) + '</button> ' +
							'<button type="button" class="button eael-tb-bulk-edit-cancel">' + escapeHtml( i18n.cancel ) + '</button>' +
							'<span class="notice notice-error notice-alt inline hidden eael-tb-quick-edit-notice">' +
								'<p class="error eael-tb-quick-edit-error" role="alert"></p>' +
							'</span>' +
							'<br class="clear" />' +
						'</div>' +

					'</div>' +
				'</td>' +
			'</tr>';

		$table.find( 'tbody' ).first().prepend( html );
		$( '.eael-tb-bulk-edit-row' ).find( 'select[name="type"]' ).trigger( 'focus' );
	}

	function closeBulkEdit() {
		$( '.eael-tb-bulk-edit-row' ).remove();
	}

	function saveBulkEdit( $form ) {
		var $notice = $form.find( '.eael-tb-quick-edit-notice' );
		var $error = $form.find( '.eael-tb-quick-edit-error' );
		var $priority = $form.find( '[name="priority"]' );

		function fail( message ) {
			$error.text( message || i18n.genericError );
			$notice.removeClass( 'hidden' );
		}

		$notice.addClass( 'hidden' );
		$error.text( '' );

		var ids = $form
			.find( '.eael-tb-bulk-title' )
			.map( function () {
				return parseInt( $( this ).data( 'template-id' ), 10 );
			} )
			.get();

		var type = $form.find( '[name="type"]' ).val();
		var status = $form.find( '[name="status"]' ).val();
		var active = $form.find( '[name="active"]' ).val();
		var priority = $.trim( String( $priority.val() || '' ) );

		if ( ! ids.length ) {
			fail( i18n.bulkNoSelection );

			return;
		}

		if ( ! type && ! status && ! active && '' === priority ) {
			fail( i18n.bulkNoChange );

			return;
		}

		// Empty means "leave it alone"; anything else has to be in range, exactly
		// as in Quick Edit.
		if ( '' !== priority && ! isValidPriority( priority ) ) {
			fail( i18n.priorityRange );
			$priority.trigger( 'focus' );

			return;
		}

		setBusy( $form, true );

		request( 'eael_theme_builder_bulk_edit', {
			template_ids: ids,
			type: type,
			status: status,
			priority: priority,
			active: active,
		} )
			.done( function ( response ) {
				if ( ! response || ! response.success ) {
					fail( response && response.data ? response.data.message : '' );
					return;
				}

				( response.data.updated || [] ).forEach( function ( row ) {
					repaintRow( rowFor( row.id ), row );
				} );

				var skipped = parseInt( response.data.skipped, 10 ) || 0;

				closeBulkEdit();

				$( '.wp-list-table' ).find( 'input[name="template_ids[]"], #cb-select-all-1, #cb-select-all-2' ).prop( 'checked', false );
				$( '#bulk-action-selector-top, #bulk-action-selector-bottom' ).val( '-1' );

				if ( skipped ) {
					window.alert( i18n.bulkSkipped.replace( '%s', skipped ) );
				}
			} )
			.fail( function () {
				fail( i18n.genericError );
			} )
			.always( function () {
				setBusy( $form, false );
			} );
	}

	/**
	 * Whether a Priority value is a whole number inside the allowed range.
	 *
	 * Mirrors `Ajax::is_valid_priority()`. Out-of-range values used to be clamped
	 * on save with no feedback, so a typed 500 came back as 100 with nothing to
	 * explain it — Title and Date in the same panel both reject bad input, and
	 * Priority now does too.
	 */
	function isValidPriority( value ) {
		var raw = $.trim( String( value === undefined || value === null ? '' : value ) );

		if ( '' === raw ) {
			return false;
		}

		var number = Number( raw );

		if ( ! isFinite( number ) || number !== Math.floor( number ) ) {
			return false;
		}

		return number >= priorityRange.min && number <= priorityRange.max;
	}

	function saveQuickEdit( $form ) {
		var $editRow = $form.closest( '.eael-tb-quick-edit-row' );
		var $row = $editRow.prev( 'tr' );
		var id = parseInt( $editRow.data( 'template-id' ), 10 );
		var $notice = $form.find( '.eael-tb-quick-edit-notice' );
		var $error = $form.find( '.eael-tb-quick-edit-error' );
		var $priority = $form.find( '[name="priority"]' );

		function fail( message ) {
			$error.text( message || i18n.genericError );
			$notice.removeClass( 'hidden' );
		}

		$notice.addClass( 'hidden' );
		$error.text( '' );

		if ( ! isValidPriority( $priority.val() ) ) {
			fail( i18n.priorityRange );
			$priority.trigger( 'focus' );

			return;
		}

		setBusy( $form, true );

		request( 'eael_theme_builder_quick_edit', {
			template_id: id,
			title: $form.find( '[name="title"]' ).val(),
			slug: $form.find( '[name="slug"]' ).val(),
			password: $form.find( '[name="password"]' ).val(),
			private: $form.find( '[name="private"]' ).is( ':checked' ) ? 'yes' : 'no',
			page_template: $form.find( '[name="page_template"]' ).val(),
			mm: $form.find( '[name="mm"]' ).val(),
			jj: $form.find( '[name="jj"]' ).val(),
			aa: $form.find( '[name="aa"]' ).val(),
			hh: $form.find( '[name="hh"]' ).val(),
			mn: $form.find( '[name="mn"]' ).val(),
			type: $form.find( '[name="type"]' ).val(),
			status: $form.find( '[name="status"]' ).val(),
			priority: $priority.val(),
			active: $form.find( '[name="active"]' ).is( ':checked' ) ? 'yes' : 'no',
		} )
			.done( function ( response ) {
				if ( ! response || ! response.success ) {
					fail( response && response.data ? response.data.message : '' );
					return;
				}

				closeQuickEdit( $editRow );
				repaintRow( $row, response.data );
			} )
			.fail( function () {
				fail( i18n.genericError );
			} )
			.always( function () {
				setBusy( $form, false );
			} );
	}

	function escapeHtml( value ) {
		return $( '<div/>' ).text( null === value || undefined === value ? '' : value ).html();
	}
} )( jQuery );
