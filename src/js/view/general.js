import { createHooks } from "@wordpress/hooks";

window.isEditMode = false;
window.ea = {
	hooks: createHooks(),
	isEditMode: false,
};

jQuery(window).on("elementor/frontend/init", function () {
	window.isEditMode = elementorFrontend.isEditMode();
	window.ea.isEditMode = elementorFrontend.isEditMode();

	// hooks
	ea.hooks.doAction("init", ea.isEditMode);

	// init edit mode hook
	if(ea.isEditMode) {
		ea.hooks.doAction("editMode.init");
	}
});