( function( $ ) {
	'use strict';
	// Init jQuery Ui Tabs
	$( ".eael-settings-tabs" ).tabs();

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
	$('.eael-settings-tabs ul li a').click(function () {
		var tabUrl = $(this).attr( 'href' );
	  	window.location.hash = tabUrl;
	   	$('html, body').scrollTop(tabUrl);
	});

	// Save Button reacting on any changes
	var headerSaveBtn = $( '.eael-header-bar .eael-btn' );
	var footerSaveBtn = $( '.eael-save-btn-wrap .eael-btn' );
	$('.eael-checkbox input[type="checkbox"]').on( 'click', function() {
		headerSaveBtn.addClass( 'save-now' );
		footerSaveBtn.addClass( 'save-now' );
	} );

	// Saving Data With Ajax Request
	$( 'form#eael-settings' ).on( 'submit', function(e) {
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
