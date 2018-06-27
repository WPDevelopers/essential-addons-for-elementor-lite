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

	// Save Button reacting on any changes
	var headerSaveBtn = $( '.eael-header-bar .eael-btn' );
	var footerSaveBtn = $( '.eael-save-btn-wrap .eael-btn' );
	$('.eael-checkbox input[type="checkbox"]').on( 'click', function() {
		headerSaveBtn.addClass( 'save-now' );
		footerSaveBtn.addClass( 'save-now' );
		headerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
		footerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
	} );

	// Saving Data With Ajax Request
	$( '.js-eael-settings-save' ).on( 'click', function(e) {
		e.preventDefault();

		var _this = $(this);

		if( $(this).hasClass('save-now') ) {
			$.ajax( {
				url: js_eael_lite_settings.ajaxurl,
				type: 'post',
				data: {
					action: 'save_settings_with_ajax',
					fields: $( 'form#eael-settings' ).serialize(),
				},
				beforeSend: function() {
					_this.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Saving Data..');
				},
				success: function( response ) {
					setTimeout(function() {
						_this.html('Save Settings');
						swal(
						'Settings Saved!',
						'Click OK to continue',
						'success'
						);
						headerSaveBtn.removeClass( 'save-now' );
						footerSaveBtn.removeClass( 'save-now' );
					}, 2000);
				},
				error: function() {
					swal(
					  'Oops...',
					  'Something went wrong!',
					  'error'
					);
				}
			} );
		}else {
			$(this).attr('disabled', 'true').css('cursor', 'not-allowed');
		}

	} );

} )( jQuery );
