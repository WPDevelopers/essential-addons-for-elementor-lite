ea.hooks.addAction("editMode.init", "ea", () => {
	elementor.settings.page.addChangeCallback(
		"eael_ext_reading_progress",
		function (newValue) {
			elementor.saver.update.apply().then(function () {
				elementor.reloadPreview();
			});
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_reading_progress_position",
		function (newValue) {
			elementor.settings.page.setSettings(
				"eael_ext_reading_progress_position",
				newValue
			);

			jQuery(".eael-reading-progress")
				.removeClass("eael-reading-progress-top eael-reading-progress-bottom")
				.addClass("eael-reading-progress-" + newValue);
		}
	);
});
