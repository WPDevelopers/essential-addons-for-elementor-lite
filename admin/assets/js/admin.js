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
	var FBbtnURL = $('#eaelFBbtn').attr('href');
	var headerSaveBtn = $( '.eael-header-bar .eael-btn' );
	var footerSaveBtn = $( '.eael-save-btn-wrap .eael-btn' );
	$('.eael-checkbox input[type="checkbox"]').on( 'click', function( e ) {
		if( e.currentTarget.id == 'eael-fb-feed-own-app' ) {
			if( e.currentTarget.checked ) {
				$( '.eael-fb-own-app-settings' ).addClass( 'eael-active' );
				$('#eaelFBbtn').removeClass('eael-active');
				$('#eaelFBbtnOwnApp').addClass('eael-active');
			} else {
				$( '.eael-fb-own-app-settings' ).removeClass( 'eael-active' );
				$('#eaelFBbtn').addClass('eael-active');
				$('#eaelFBbtnOwnApp').removeClass('eael-active');
			}
			$('.eael-fb-feed-btn').addClass( 'save-now' );
			$('.eael-fb-feed-btn').removeAttr('disabled').css('cursor', 'pointer');
			return;
		}
		headerSaveBtn.addClass( 'save-now' );
		footerSaveBtn.addClass( 'save-now' );
		headerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
		footerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
	} );

	// $( '#eael-fb-feed-app-id' ).on('blur', function( e ){
	// 	if( e.currentTarget.value != '' ) {
	// 		var url = 'https://www.facebook.com/dialog/oauth?scope=manage_pages&client_id='+ e.currentTarget.value +'&redirect_uri=https://fb.essential-addons.com/callback.php&state=' + window.location.href;
	// 		$('#eaelFBbtn').attr( 'href', url );
	// 	} else {
	// 		$('#eaelFBbtn').attr( 'href', FBbtnURL );
	// 	}
	// });

	$('#eaelFBbtnOwnApp').on('click', function( e ){
		// e.preventDefault();
		// var eaelAppID = $('#eael-fb-feed-app-id').val(),
		// 	eaelAppSecret = $('#eael-fb-feed-app-secret').val(),
		// 	_this = $( this ),
		// 	_url = _this.attr('href');

		// var url = 'https://www.facebook.com/dialog/oauth?scope=manage_pages&client_id='+ eaelAppID +'&redirect_uri=https://fb.essential-addons.com/callback.php&state=' + window.location.href;
			
		// 	_url = url + '&appid='+eaelAppID+'&appsecret='+eaelAppSecret+'&eae=v2.7.2'

		// _this.attr( 'href', _url );

		// console.log( _url );

		// $.ajax( {
		// 	url: _url,
		// 	type: 'get',
		// 	beforeSend: function() {
		// 		_this.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Saving Data..');
		// 	},
		// 	success: function( response ) {
		// 		setTimeout(function() {
		// 			_this.html('Save Settings');
		// 			swal(
		// 				'Settings Saved!',
		// 				'Click OK to continue',
		// 				'success'
		// 			);
		// 		}, 2000);
		// 		// window.location.replace( path[0] );
		// 	},
		// 	error: function() {
		// 		swal(
		// 			'Oops...',
		// 			'Something went wrong!',
		// 			'error'
		// 		);
		// 	}
		// } );

	});

	var path = window.location.href.split('&');
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
					window.location.replace( path[0] );
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
	// Saving Data With Ajax Request for facebook feed
	$( '#eael-fb-feed-save-settings' ).on( 'click', function(e) {
		e.preventDefault();

		var _this = $(this);

		if( $(this).hasClass('save-now') ) {
			$.ajax( {
				url: js_eael_lite_settings.ajaxurl,
				type: 'post',
				data: {
					action: 'save_facebook_feed_settings',
					fields: $( 'form#eael-settings #social-networks input' ).serialize(),
				},
				beforeSend: function() {
					_this.html('<i class="fa fa-spinner fa-spin"></i>&nbsp;Saving Feed Data..');
				},
				success: function( response ) {
					setTimeout(function() {
						_this.html('Save Facebook Feed Setting');
						swal(
							'Settings Saved!',
							'Click OK to continue',
							'success'
						);
						$('.eael-fb-feed-btn').removeClass( 'save-now' );
					}, 2000);
					window.location.replace( path[0] );
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

	if( path[1] ) {
		var acc = path[1].split('=');
		if( acc[0] == 'access_token' ) {
			headerSaveBtn.addClass( 'save-now' );
			footerSaveBtn.addClass( 'save-now' );
			headerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
			footerSaveBtn.removeAttr('disabled').css('cursor', 'pointer');
		}
	}

	// if( $('#eael-fb-feed-access-token').val() != '' ) {
	// 	var access_token = $('#eael-fb-feed-access-token').val();
	// 	var url = 'https://graph.facebook.com/me?fields=id,name,accounts.limit(10){name,picture{url},access_token}&access_token=' + access_token;
	// 	var pages = '';
	// 	$.get(url, function( res ){
	// 		res.accounts.data.forEach(function( elem ){
	// 			pages += '<option data-access_token="' + elem.access_token + '" data-page_id="' + elem.id + '" value="' + elem.id + '">' + elem.name + '</option>';
	// 		});
	// 		$(pages).appendTo('#eael-fb-feed-select-page');
	// 	}, 'json');
	// }

	if( $('.page-list-with-access').length > 0 ) {
		$('#eael-fb-feed-select-page').on('change', function(e){
			e.preventDefault();
			$('.eael-fb-feed-btn').addClass( 'save-now' );
			$('.eael-fb-feed-btn').removeAttr('disabled').css('cursor', 'pointer');

			var eaelPageAccessToken = $(this).data('access_token'),
				eaelFBPageId = $(this).data('page_id');
				$('#eael-fb-feed-page-id').val( e.currentTarget.value );
				$('#eael-fb-feed-page-access-token').val( e.currentTarget.selectedOptions[0].dataset.access_token );
		});
	}

	

} )( jQuery );
