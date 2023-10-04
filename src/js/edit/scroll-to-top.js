eael.hooks.addAction("editMode.init", "ea", () => {
    elementor.settings.page.addChangeCallback(
		"eael_ext_scroll_to_top",
		function (newValue) {
			elementor.saver.update.apply().then(function () {
				elementor.reloadPreview();
			});
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_scroll_to_top_button_icon_image",
		function (newValue) {
			elementor.saver.update.apply().then(function () {
				elementor.reloadPreview();
			});
		}
	);
});
