import { createHooks } from "@wordpress/hooks";

window.isEditMode = false;
window.ea = {
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

function EAELsetScreenSize() {
	jQuery.ajax({
		url: localize.ajaxurl,
		type: "post",
		data: {
			action: "eael_set_screen_width",
			screen_width: window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth
		}
	});
}

EAELsetScreenSize();
let debunce_time = false;
window.addEventListener('resize', function () {
	clearTimeout(debunce_time);
	debunce_time = setTimeout(EAELsetScreenSize, 250);
});

ea.hooks.addAction("widgets.reinit", "ea", ($content) => {
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
		ea.hooks.doAction("eventCalendar.reinit");
	}

	if (testimonialSlider.length) {
		ea.hooks.doAction("testimonialSlider.reinit");
	}

	if (teamMemberCarousel.length) {
		ea.hooks.doAction("teamMemberCarousel.reinit");
	}

	if (postCarousel.length) {
		ea.hooks.doAction("postCarousel.reinit");
	}

	if (logoCarousel.length) {
		ea.hooks.doAction("logoCarousel.reinit");
	}

	if (twitterCarousel.length) {
		ea.hooks.doAction("twitterCarousel.reinit");
	}
});

jQuery(window).on("elementor/frontend/init", function () {
	window.isEditMode = elementorFrontend.isEditMode();
	window.ea.isEditMode = elementorFrontend.isEditMode();

	// hooks
	ea.hooks.doAction("init");

	// init edit mode hook
	if (ea.isEditMode) {
		ea.hooks.doAction("editMode.init");
	}
});

(function ($) {
	ea.getToken = () => {
		if (localize.nonce && !ea.noncegenerated) {
			$.ajax({
				url: localize.ajaxurl,
				type: "post",
				data: {
					action: "eael_get_token",
				},
				success: function (response) {
					if (response.success) {
						localize.nonce = response.data.nonce
						ea.noncegenerated = true;
					}
				}
			});
		}
	}

	$('a').on('click', function (e) {
		var hashURL = $(this).attr('href'),
			isStartWithHash;

		hashURL = hashURL === undefined ? '' : hashURL;
		isStartWithHash = hashURL.startsWith('#');

		if (!isStartWithHash) {
			hashURL = hashURL.replace(localize.page_permalink, '');
			isStartWithHash = hashURL.startsWith('#');
		}

		// we will try and catch the error but not show anything just do it if possible
		try {
			if (isStartWithHash && ($(hashURL).hasClass('eael-tab-item-trigger') || $(hashURL).hasClass('eael-accordion-header'))) {
				$(hashURL).trigger('click');

				if (typeof hashURL !== 'undefined' && hashURL) {
					let idOffset = $(hashURL).closest('.eael-advance-tabs').data('custom-id-offset');
					idOffset = idOffset ? parseFloat(idOffset) : 0;
					$('html, body').animate({
						scrollTop: $(hashURL).offset().top - idOffset,
					}, 300);
				}
			}
		} catch (err) {
			// nothing to do
		}
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
