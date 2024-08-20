import { createHooks } from "@wordpress/hooks";

window.isEditMode = false;
window.eael = window.ea = {
	hooks: createHooks(),
	isEditMode: false,
	elementStatusCheck:function(name){
		if (window.eaElementList && name in window.eaElementList) {
			return true;
		} else {
			window.eaElementList = {...window.eaElementList, [name]: true}
		}
		return false;
	}
};

eael.hooks.addAction("widgets.reinit", "ea", ($content) => {
	let filterGallery = jQuery(".eael-filter-gallery-container", $content);
	let postGridGallery = jQuery(
		".eael-post-grid:not(.eael-post-carousel)",
		$content
	);
	let twitterfeedGallery = jQuery(".eael-twitter-feed-masonry", $content);
	let instaGallery = jQuery(".eael-instafeed", $content);
	let paGallery = jQuery(".premium-gallery-container", $content);
	let eventCalendar = jQuery(".eael-event-calendar-cls", $content);
	let testimonialSlider = jQuery(".eael-testimonial-slider", $content);
	let teamMemberCarousel = jQuery(".eael-tm-carousel", $content);
	let postCarousel = jQuery(
		".eael-post-carousel:not(.eael-post-grid)",
		$content
	);
	let logoCarousel = jQuery(".eael-logo-carousel", $content);
	let twitterCarousel = jQuery(".eael-twitter-feed-carousel", $content);

	if (filterGallery.length) {
		filterGallery.isotope("layout");
	}

	if (postGridGallery.length) {
		postGridGallery.isotope("layout");
	}

	if (twitterfeedGallery.length) {
		twitterfeedGallery.isotope("layout");
	}

	if (instaGallery.length) {
		instaGallery.isotope("layout");
	}

	if (paGallery.length) {
		paGallery.isotope("layout");
	}

	if (eventCalendar.length) {
		eael.hooks.doAction("eventCalendar.reinit");
	}

	if (testimonialSlider.length) {
		eael.hooks.doAction("testimonialSlider.reinit");
	}

	if (teamMemberCarousel.length) {
		eael.hooks.doAction("teamMemberCarousel.reinit");
	}

	if (postCarousel.length) {
		eael.hooks.doAction("postCarousel.reinit");
	}

	if (logoCarousel.length) {
		eael.hooks.doAction("logoCarousel.reinit");
	}

	if (twitterCarousel.length) {
		eael.hooks.doAction("twitterCarousel.reinit");
	}
});

let ea_swiper_slider_init_inside_template = (content) => {
	window.dispatchEvent(new Event('resize'));

	content = typeof content === 'object' ? content : jQuery(content);
	content.find('.swiper-wrapper').each(function () {
		let transform = jQuery(this).css('transform');
		jQuery(this).css('transform', transform);
	});
}

eael.hooks.addAction("ea-advanced-tabs-triggered", "ea", ea_swiper_slider_init_inside_template);
eael.hooks.addAction("ea-advanced-accordion-triggered", "ea", ea_swiper_slider_init_inside_template);

jQuery(window).on("elementor/frontend/init", function () {
	window.isEditMode = elementorFrontend.isEditMode();
	window.eael.isEditMode = elementorFrontend.isEditMode();

	// hooks
	eael.hooks.doAction("init");

	// init edit mode hook
	if (eael.isEditMode) {
		eael.hooks.doAction("editMode.init");
	}
});

(function ($) {
	eael.getToken = () => {
		if (localize.nonce && !eael.noncegenerated) {
			$.ajax({
				url: localize.ajaxurl,
				type: "post",
				data: {
					action: "eael_get_token",
				},
				success: function (response) {
					if (response.success) {
						localize.nonce = response.data.nonce
						eael.noncegenerated = true;
					}
				}
			});
		}
	}
	eael.sanitizeURL = function (url) {
		if (url.startsWith('/') || url.startsWith('#')) {
			return url;
		}

		try {
			const urlObject = new URL(url);

			// Check if the protocol is valid (allowing only 'http' and 'https')
			if (!['http:', 'https:', 'ftp:', 'ftps:', 'mailto:', 'news:', 'irc:', 'irc6:', 'ircs:', 'gopher:', 'nntp:', 'feed:', 'telnet:', 'mms:', 'rtsp:', 'sms:', 'svn:', 'tel:', 'fax:', 'xmpp:', 'webcal:', 'urn:'].includes(urlObject.protocol)) {
				throw new Error('Invalid protocol');
			}

			// If all checks pass, return the sanitized URL
			return urlObject.toString();
		} catch (error) {
			console.error('Error sanitizing URL:', error.message);
			return '#';
		}
	}

	//Add hashchange code form advanced-accordion
	let  isTriggerOnHashchange = true;
	window.addEventListener( 'hashchange', function () {
		if( !isTriggerOnHashchange ) {
			return;
		}
		let hashTag = window.location.hash.substr(1);
		hashTag = hashTag === 'safari' ? 'eael-safari' : hashTag;
		if ( hashTag !== 'undefined' && hashTag ) {
			jQuery( '#' + hashTag ).trigger( 'click' );
		}
	});

	$('a').on('click', function (e) {
		var hashURL = $(this).attr('href'),
			isStartWithHash;

		hashURL = hashURL === undefined ? '' : hashURL;
		isStartWithHash = hashURL.startsWith('#');

		if (!isStartWithHash) {
			hashURL = hashURL.replace(localize.page_permalink, '');
			isStartWithHash = hashURL.startsWith('#');
		}

		if( isStartWithHash ) {
			isTriggerOnHashchange = false;
			setTimeout( () => {
				isTriggerOnHashchange = true;
			}, 100 );
		}

		// we will try and catch the error but not show anything just do it if possible
		try {
			if( hashURL.startsWith( '#!' ) ){
				var replace_with_hash = hashURL.replace( '#!', '#' );
				$( replace_with_hash ).trigger( 'click' );
			} else {
				if (isStartWithHash && ($(hashURL).hasClass('eael-tab-item-trigger') || $(hashURL).hasClass('eael-accordion-header'))) {
					$(hashURL).trigger('click');
	
					if (typeof hashURL !== 'undefined' && hashURL) {
						let tabs = $(hashURL).closest('.eael-advance-tabs');
						if( tabs.length > 0 ){
							let idOffset = tab.data('custom-id-offset');
							idOffset = idOffset ? parseFloat(idOffset) : 0;
							$('html, body').animate({
								scrollTop: $(hashURL).offset().top - idOffset,
							}, 300);
						}
					}
				}
			}
		} catch (err) {
			// nothing to do
		}
	});

	$(document).on('click', '.e-n-tab-title', function () {
		window.dispatchEvent(new Event('resize'));
	});
})(jQuery);

(function ($) {
	$(document).on('click', '.theme-savoy .eael-product-popup .nm-qty-minus, .theme-savoy .eael-product-popup .nm-qty-plus', function(e) {
		// Get elements and values
		var $this		= $(this),
			$qty		= $this.closest('.quantity').find('.qty'),
			currentVal	= parseFloat($qty.val()),
			max			= parseFloat($qty.attr('max')),
			min			= parseFloat($qty.attr('min')),
			step		= $qty.attr('step');

		// Format values
		if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
		if (max === '' || max === 'NaN') max = '';
		if (min === '' || min === 'NaN') min = 0;
		if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;
	          
		// Change the value
		if ($this.hasClass('nm-qty-plus')) {
			if (max && (max == currentVal || currentVal > max)) {
				$qty.val(max);
			} else {
				$qty.val(currentVal + parseFloat(step));
			}
		} 
		else {
			if (min && (min == currentVal || currentVal < min)) {
				$qty.val(min);
			} else if (currentVal > 0) {
				$qty.val(currentVal - parseFloat(step));
			}
		}
	});
})(jQuery);

(function ($) {
	$.fn.isInViewport = function() {
		if ($(this).length < 1 ) return false;
		var elementTop = $(this).offset().top;
		var elementBottom = elementTop + $(this).outerHeight() / 2;
		var viewportTop = $(window).scrollTop();
		var viewportHalf = viewportTop + $(window).height() / 2;
		return elementBottom > viewportTop && elementTop < viewportHalf;
	};

	$(document).ready(function(){ 
		let resetPasswordParams = new URLSearchParams(location.search);
	
		if ( resetPasswordParams.has('popup-selector') && ( resetPasswordParams.has('eael-lostpassword') || resetPasswordParams.has('eael-resetpassword') ) ){
			let popupSelector = resetPasswordParams.get('popup-selector');
			if(popupSelector.length){
				popupSelector = popupSelector.replace(/_/g," ");
				setTimeout(function(){
					jQuery(popupSelector).trigger('click');
				}, 300);
			}
		}
	});
})(jQuery);
