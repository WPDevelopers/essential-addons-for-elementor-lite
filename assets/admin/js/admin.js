( function ( $ ) {
	"use strict";
	/**
	 * Eael Tabs
	 */
	$( ".eael-main__tab li.tab__list a" ).on( "click", function ( e ) {
		e.preventDefault();
		$( ".eael-main__tab li.tab__list" ).removeClass( "active" );
		$( this ).parent().addClass( "active" );
		var tab = $( this ).attr( "href" );
		$( ".eael-admin-setting-tab" ).removeClass( "active" );
		$( ".eael-admin-setting-tabs" ).find( tab ).addClass( "active" );
	} );
	
	totalElements();
	
	function totalElements() {
		var totalElements  = parseInt( $( '.eael-widget-item' ).length ),
		    activeElements = parseInt( $( '.eael-widget-item:checked' ).length ),
		    unusedElements = totalElements - activeElements;
		
		$( "#eael-total-elements" ).text( totalElements )
		$( "#eael-used-elements" ).text( activeElements )
		$( "#eael-unused-elements" ).text( unusedElements )
	}
	
	var eaelPopupBox = $( "#eael-admn-setting-popup" );
	
	$( ".switch__box.disabled" ).on( "click", function () {
		eaelPopupBox.show();
		$( "#eael-pro-popup" ).show();
	} );
	
	$( document ).on( 'click', '#eael-googl-map-setting', function ( event ) {
		event.preventDefault();
		eaelPopupBox.show();
		$( "#eael-google-map-popup" ).show();
	} )
	
	$( document ).on( 'click', '#eael-mailchimp-setting', function ( event ) {
		event.preventDefault();
		eaelPopupBox.show();
		$( "#eael-mailchimp-popup" ).show();
	} )
	
	$( document ).on( 'click', '#eael-login-register-setting', function ( event ) {
		event.preventDefault();
		eaelPopupBox.show();
		$( "#eael-login-register-popup" ).show();
	} )
	
	$( document ).on( 'click', '#eael-post-duplicator-setting', function ( event ) {
		event.preventDefault();
		eaelPopupBox.show();
		$( "#eael-post-duplicator-popup" ).show();
	} )
	
	$( document ).on( "click", ".eael-save-trigger", function ( event ) {
		event.preventDefault();
		saveButton
		.addClass( "save-now" )
		.removeAttr( "disabled" )
		.css( "cursor", "pointer" );
	} )
	
	//close popup
	$( document ).on( "click", ".eael-admin-popup-close", function ( event ) {
		event.preventDefault();
		eaelPopupBox.hide();
		$( ".modal__content__popup" ).hide();
	} )
	
	// Save Button reacting on any changes
	var saveButton = $( ".js-eael-settings-save" );
	
	$( ".eael-widget-item:enabled" ).on( "click", function ( e ) {
		totalElements();
		saveButton
		.addClass( "save-now" )
		.removeAttr( "disabled" )
		.css( "cursor", "pointer" );
	} );
	
	// Saving Data With Ajax Request
	$( ".js-eael-settings-save" ).on( "click", function ( event ) {
		event.preventDefault();
		
		var _this = $( this );
		
		if ( $( this ).hasClass( "save-now" ) ) {
			$.ajax( {
				        url: localize.ajaxurl,
				        type: "post",
				        data: {
					        action: "save_settings_with_ajax",
					        security: localize.nonce,
					        fields: $( "form#eael-settings" ).serialize(),
				        },
				        beforeSend: function () {
					        _this.html(
						        '<svg id="eael-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span>Saving Data..</span>'
					        );
				        },
				        success: function ( response ) {
					        setTimeout( function () {
						        _this.html( "Save Settings" );
						        Swal.fire( {
							                   timer: 2000,
							                   showConfirmButton: false,
							                   imageUrl: localize.settings_save,
						                   } )
						        saveButton.removeClass( "save-now" );
					        }, 500 );
				        },
				        error: function () {
					        Swal.fire( {
						                   type: "error",
						                   title: "Oops...",
						                   text: "Something went wrong!",
					                   } );
				        },
			        } );
		} else {
			$( this ).attr( "disabled", "true" ).css( "cursor", "not-allowed" );
		}
	} );
	
	// Regenerate Assets
	$( "#eael-regenerate-files" ).on( "click", function ( e ) {
		e.preventDefault();
		var _this = $( this );
		
		$.ajax( {
			        url: localize.ajaxurl,
			        type: "post",
			        data: {
				        action: "clear_cache_files_with_ajax",
				        security: localize.nonce,
			        },
			        beforeSend: function () {
				        _this.html(
					        '<svg id="eael-spinner" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48"><circle cx="24" cy="4" r="4" fill="#fff"/><circle cx="12.19" cy="7.86" r="3.7" fill="#fffbf2"/><circle cx="5.02" cy="17.68" r="3.4" fill="#fef7e4"/><circle cx="5.02" cy="30.32" r="3.1" fill="#fef3d7"/><circle cx="12.19" cy="40.14" r="2.8" fill="#feefc9"/><circle cx="24" cy="44" r="2.5" fill="#feebbc"/><circle cx="35.81" cy="40.14" r="2.2" fill="#fde7af"/><circle cx="42.98" cy="30.32" r="1.9" fill="#fde3a1"/><circle cx="42.98" cy="17.68" r="1.6" fill="#fddf94"/><circle cx="35.81" cy="7.86" r="1.3" fill="#fcdb86"/></svg><span>Generating...</span>'
				        );
			        },
			        success: function ( response ) {
				        setTimeout( function () {
					        _this.html( "Regenerate Assets" );
					
					        Swal.fire( {
						                   type: "success",
						                   title: "Assets Regenerated!",
						                   showConfirmButton: false,
						                   timer: 2000,
					                   } );
				        }, 1000 );
			        },
			        error: function () {
				        Swal.fire( {
					                   type: "error",
					                   title: "Ops!",
					                   footer: "Something went wrong!",
					                   showConfirmButton: false,
					                   timer: 2000,
				                   } );
			        },
		        } );
	} );
	
	$( document ).on( 'click', '.eael-element-global-switch', function ( e ) {
		var status = $( this ).prop( "checked" );
		$( ".eael-widget-item:enabled" ).each( function () {
			$( this ).prop( "checked", status ).change();
		} );
		totalElements();
		saveButton
		.addClass( "save-now" )
		.removeAttr( "disabled" )
		.css( "cursor", "pointer" );
	} );
	
	$( document ).on( 'click', function ( event ) {
		var selector = $( event.target ).closest( ".eael-modal" );
		
	} );
	
	// Popup
	$( document ).on( "click", ".eael-admin-settings-popup", function ( e ) {
		e.preventDefault();
		
		var title          = $( this ).data( "title" );
		var placeholder    = $( this ).data( "placeholder" );
		var type           = $( this ).data( "option" ) || "text";
		var options        = $( this ).data( "options" ) || {};
		var prepareOptions = {};
		var target         = $( this ).data( "target" );
		var val            = $( target ).val();
		var docSelector    = $( this ).data( "doc" );
		var docMarkup      = docSelector
			? $( docSelector ).clone().css( "display", "block" )
			: false;
		
		if ( Object.keys( options ).length > 0 ) {
			prepareOptions["all"] = "All";
			
			for ( var index in options ) {
				prepareOptions[index] = options[index].toUpperCase();
			}
		}
		
		Swal.fire( {
			           title: title,
			           input: type,
			           inputPlaceholder: placeholder,
			           inputValue: val,
			           inputOptions: prepareOptions,
			           footer: docMarkup,
			           preConfirm: function ( res ) {
				           $( target ).val( res );
				
				           saveButton
				           .addClass( "save-now" )
				           .removeAttr( "disabled" )
				           .css( "cursor", "pointer" );
			           },
		           } );
	} );
	
	$( "#eael-js-print-method" ).on( "change", function ( evt ) {
		var printMethod = $( this ).val();
		saveButton
		.addClass( "save-now" )
		.removeAttr( "disabled" )
		.css( "cursor", "pointer" );
		
		if ( printMethod === "internal" ) {
			$( ".eael-external-printjs" ).hide();
			$( ".eael-internal-printjs" ).show();
		} else {
			$( ".eael-external-printjs" ).show();
			$( ".eael-internal-printjs" ).hide();
		}
	} );
	
	/**
	 * Open a popup for typeform auth2 authentication
	 */
	$( "#eael-typeform-get-access" ).on( "click", function ( e ) {
		e.preventDefault();
		var link = $( this ).data( "link" );
		if ( link != "" ) {
			window.open(
				link,
				"mywindowtitle",
				"width=500,height=500,left=500,top=200"
			);
		}
	} );
	
	// install/activate plugin
	$( document ).on( "click", ".wpdeveloper-plugin-installer", function ( ev ) {
		ev.preventDefault();
		
		var button   = $( this );
		var action   = $( this ).data( "action" );
		var slug     = $( this ).data( "slug" );
		var basename = $( this ).data( "basename" );
		
		if ( $.active && typeof action != "undefined" && action != 'completed' ) {
			button.text( "Waiting..." ).attr( "disabled", true );
			
			setInterval( function () {
				if ( !$.active ) {
					button.attr( "disabled", false ).trigger( "click" );
				}
			}, 1000 );
		}
		
		if ( action == "install" && !$.active ) {
			button.text( "Installing..." ).attr( "disabled", true );
			
			$.ajax( {
				        url: localize.ajaxurl,
				        type: "POST",
				        data: {
					        action: "wpdeveloper_install_plugin",
					        security: localize.nonce,
					        slug: slug,
				        },
				        success: function ( response ) {
					        if ( response.success ) {
						        button.attr( "disabled", true );
						        button.text( "Activated" );
						        button.data( "action", 'completed' );
						        $( "body" ).trigger( 'eael_after_active_plugin', { plugin: slug } );
					        } else {
						        button.attr( "disabled", false );
						        button.text( "Install" );
					        }
				        },
				        error: function ( err ) {
					        console.log( err.responseJSON );
				        },
			        } );
		} else if ( action == "activate" && !$.active ) {
			button.text( "Activating..." ).attr( "disabled", true );
			
			$.ajax( {
				        url: localize.ajaxurl,
				        type: "POST",
				        data: {
					        action: "wpdeveloper_activate_plugin",
					        security: localize.nonce,
					        basename: basename,
				        },
				        success: function ( response ) {
					        if ( response.success ) {
						        button.text( "Activated" );
						        button.data( "action", null );
						        $( "body" ).trigger( 'eael_after_active_plugin', { plugin: basename } );
					        } else {
						        button.text( "Activate" );
					        }
					
					        button.attr( "disabled", false );
				        },
				        error: function ( err ) {
					        console.log( err.responseJSON );
				        },
			        } );
		}
	} );
	
	$( document ).on( 'click', '.eael-setup-wizard-save', function ( e ) {
		var $this = $( this );
		$this.attr( 'disabled', 'disabled' );
		$.ajax( {
			        url: localize.ajaxurl,
			        type: "POST",
			        data: {
				        action: "save_setup_wizard_data",
				        security: localize.nonce,
				        fields: $( "form.eael-setup-wizard-form" ).serialize()
			        },
			
			        success: function ( response ) {
				        if ( response.success ) {
					        Swal.fire( {
						                   timer: 3000,
						                   showConfirmButton: false,
						                   imageUrl: localize.success_image,
					                   } ).then( ( result ) => {
						        window.location = response.data.redirect_url;
					        } );
				        } else {
					        $this.attr( 'disabled', 'disabled' );
					        Swal.fire( {
						                   type: "error",
						                   title: 'Error',
						                   text: 'error',
					                   } );
				        }
			        },
			        error: function ( err ) {
				        $this.attr( 'disabled', 'disabled' );
				        Swal.fire( {
					                   type: "error",
					                   title: 'Error',
					                   text: 'error',
				                   } );
			        },
		        } );
	} );
	
	$( document ).on( 'change', '.eael_preferences', function ( e ) {
		var $this       = $( this ),
		    preferences = $this.val();
		
		var elements = $( ".eael-quick-setup-post-grid .eael-quick-setup-toggler input[type=checkbox]" );
		if ( elements.length > 0 ) {
			if ( preferences == 'custom' ) {
				elements.prop( 'checked', true )
			} else {
				elements.prop( 'checked', false )
				elements.each( function ( i, item ) {
					if ( preferences == 'advance' && $( item ).data( 'preferences' ) != '' ) {
						$( item ).prop( 'checked', true )
					} else if ( $( item ).data( 'preferences' ) == preferences ) {
						$( item ).prop( 'checked', true )
					}
				} )
			}
		}
	} );
	
	eaelRenderTab();
	
	function eaelRenderTab( step = 0 ) {
		
		var contents    = document.getElementsByClassName( "setup-content" ),
		    prev        = document.getElementById( "eael-prev" ),
		    nextElement = document.getElementById( "eael-next" ),
		    saveElement = document.getElementById( "eael-save" );
		
		if ( contents.length < 1 ) {
			return;
		}
		
		contents[step].style.display = "block";
		prev.style.display           = ( step == 0 ) ? "none" : "inline";
		if ( step == ( contents.length - 1 ) ) {
			saveElement.style.display = "inline";
			nextElement.style.display = "none";
		} else {
			nextElement.style.display = "inline";
			saveElement.style.display = "none";
		}
		eaelStepIndicator( step )
	}
	
	function eaelStepIndicator( stepNumber ) {
		var steps     = document.getElementsByClassName( "eael-quick-setup-step" ),
		    container = document.getElementsByClassName( "eael-quick-setup-wizard" );
		container[0].setAttribute( 'data-step', stepNumber );
		
		for ( var i = 0; i < steps.length; i++ ) {
			steps[i].className = steps[i].className.replace( " active", "" );
		}
		
		steps[stepNumber].className += " active";
	}
	
	$( document ).on( 'click', '#eael-next,#eael-prev', function ( e ) {
		var container  = document.getElementsByClassName( "eael-quick-setup-wizard" ),
		    StepNumber = parseInt( container[0].getAttribute( 'data-step' ) ),
		    contents   = document.getElementsByClassName( "setup-content" );
		
		contents[StepNumber].style.display = "none";
		StepNumber                         = ( e.target.id == 'eael-prev' ) ? StepNumber - 1 : StepNumber + 1;
		if ( e.target.id == 'eael-next' && StepNumber == 2 ) {
			$.ajax( {
				        url: localize.ajaxurl,
				        type: "POST",
				        data: {
					        action: "save_eael_elements_data",
					        security: localize.nonce,
					        fields: $( "form.eael-setup-wizard-form" ).serialize()
				        }
			        } );
		}
		if ( StepNumber >= contents.length ) {
			return false;
		}
		eaelRenderTab( StepNumber );
	} );
	
	$( '.btn-collect' ).on( 'click', function () {
		$( ".eael-whatwecollecttext" ).toggle();
	} );
	
	
	$( document ).on( 'eael_after_active_plugin', function ( event, obj ) {
		if ( obj.plugin == 'templately/templately.php' || obj.plugin == 'templately' ) {
			if ( $( ".eael-settings-tabs" ).length > 0 ) {
				location.reload();
			}
		}
	} )
	
	$( window ).on( 'load', function () {
		var params = new URLSearchParams( location.search );
		if ( params.has( 'typeform_tk' ) ) {
			var elements_tab = document.querySelector( "ul.eael-tabs li a.eael-elements-tab" );
			params.delete( 'typeform_tk' );
			params.delete( 'pr_code' );
			window.history.replaceState( {}, '', `${location.pathname}?${params}` );
			
			if ( elements_tab ) {
				elements_tab.click();
			}
			
			if ( typeof Swal == 'function' ) {
				Swal.fire(
					{
						timer: 3000,
						showConfirmButton: false,
						type: 'success',
						title: 'TypeForm Token Added',
					}
				)
			}
		}
	} );
	
} )( jQuery );
