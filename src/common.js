import { createHooks } from "@wordpress/hooks";

window.ea = {
	hooks: createHooks(),
	isEditMode: false,
};

jQuery(window).on("elementor/frontend/init", function () {
	window.ea.isEditMode = elementorFrontend.isEditMode();

	// hooks
	ea.hooks.doAction("init", ea.isEditMode);
});
