( function( $ ) {
	'use strict';

	/**
	 * Eael Tabs
	 */
	$( '.eael-tabs li a' ).on( 'click', function(e) {
		e.preventDefault();
		$( '.eael-tabs li a' ).removeClass( 'active' );
		$(this).addClass( 'active' );
		var tab = $(this).attr( 'href' );
		$( '.eael-settings-tab' ).removeClass( 'active' );
		$( '.eael-settings-tabs' ).find( tab ).addClass( 'active' );
	});

	$( '.eael-get-pro' ).on( 'click', function() {
		swal({
	  		title: '<h2><span>Go</span> Premium',
	  		type: 'warning',
	  		html:
	    		'Purchase our <b><a href="https://wpdeveloper.net/in/upgrade-essential-addons-elementor" rel="nofollow">premium version</a></b> to unlock these pro components!',
	  		showCloseButton: true,
	  		showCancelButton: false,
	  		focusConfirm: true,
		});
	} );

	// Adding link id after the url
	// $('.eael-settings-tabs ul li a').click(function () {
	// 	var tabUrl = $(this).attr( 'href' );
	//   	window.location.hash = tabUrl;
	//    	$('html, body').scrollTop(tabUrl);
	// });

	// Save Button reacting on any changes
	var headerSaveBtn = $( '.eael-header-bar .eael-btn' );
	var footerSaveBtn = $( '.eael-save-btn-wrap .eael-btn' );
	$('.eael-checkbox input[type="checkbox"]').on( 'click', function() {
		headerSaveBtn.addClass( 'save-now' );
		footerSaveBtn.addClass( 'save-now' );
	} );

	// Saving Data With Ajax Request
	$( '.js-eael-settings-save' ).on( 'click', function(e) {
		e.preventDefault();

		$.ajax( {
			url: settings.ajaxurl,
			type: 'post',
			data: {
				action: 'save_settings_with_ajax',
				fields: $( 'form#eael-settings' ).serialize(),
			},
			success: function( response ) {
				swal(
				  'Settings Saved!',
				  'Click OK to continue',
				  'success'
				);
				headerSaveBtn.removeClass( 'save-now' );
				footerSaveBtn.removeClass( 'save-now' );
			},
			error: function() {
				swal(
				  'Oops...',
				  'Something went wrong!',
				  'error'
				);
			}
		} );

	} );

} )( jQuery );
