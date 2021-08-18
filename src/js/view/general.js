import { createHooks } from "@wordpress/hooks";

window.isEditMode = false;
window.ea = {
	hooks: createHooks(),
	isEditMode: false,
};

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
