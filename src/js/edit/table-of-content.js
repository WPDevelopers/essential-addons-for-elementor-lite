eael.hooks.addAction("editMode.init", "ea", () => {
	elementor.settings.page.addChangeCallback(
		"eael_ext_table_of_content",
		function (newValue) {
			elementor.settings.page.setSettings(
				"eael_ext_table_of_content",
				newValue
			);

			elementor.saver.update.apply().then(function () {
				elementor.reloadPreview();
			});
		}
	);

	elementor.settings.page.addChangeCallback("eael_ext_toc_position", function (
		newValue
	) {
		var selector = jQuery("#eael-toc");
		if (newValue === "right") {
			selector.addClass("eael-toc-right");
		} else {
			selector.removeClass("eael-toc-right");
			selector.addClass("eael-toc-left");
		}
	});

	elementor.settings.page.addChangeCallback(
		"eael_ext_table_of_content_list_style",
		function (newValue) {
			var list = jQuery(".eael-toc-list");
			list.removeClass("eael-toc-list-bar eael-toc-list-arrow");
			if (newValue !== "none") {
				list.addClass("eael-toc-list-" + newValue);
			}
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_toc_collapse_sub_heading",
		function (newValue) {
			var list = jQuery(".eael-toc-list");
			if (newValue === "yes") {
				list.addClass("eael-toc-collapse");
			} else {
				list.removeClass("eael-toc-collapse");
			}
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_table_of_content_header_icon",
		function (newValue) {
			var iconElement = $(".eael-toc-button i");
			iconElement.removeClass().addClass(newValue.value);
		}
	);

	elementor.settings.page.addChangeCallback("eael_ext_toc_list_icon", function (
		newValue
	) {
		var list = jQuery(".eael-toc-list");
		if (newValue === "number") {
			list.addClass("eael-toc-number").removeClass("eael-toc-bullet");
		} else {
			list.addClass("eael-toc-bullet").removeClass("eael-toc-number");
		}
	});

	elementor.settings.page.addChangeCallback("eael_ext_toc_word_wrap", function (
		newValue
	) {
		var list = jQuery(".eael-toc-list");
		if (newValue === "yes") {
			list.addClass("eael-toc-word-wrap");
		} else {
			list.removeClass("eael-toc-word-wrap");
		}
	});

	elementor.settings.page.addChangeCallback(
		"eael_ext_toc_close_button_text_style",
		function (newValue) {
			var toc = jQuery("#eael-toc");
			if (newValue === "bottom_to_top") {
				toc.addClass("eael-bottom-to-top");
			} else {
				toc.removeClass("eael-bottom-to-top");
			}
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_toc_box_shadow",
		function (newValue) {
			var toc = jQuery("#eael-toc");
			if (newValue === "yes") {
				toc.addClass("eael-box-shadow");
			} else {
				toc.removeClass("eael-box-shadow");
			}
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_toc_auto_collapse",
		function (newValue) {
			var toc = jQuery("#eael-toc");
			if (newValue === "yes") {
				toc.addClass("eael-toc-auto-collapse collapsed");
			} else {
				toc.removeClass("eael-toc-auto-collapse collapsed");
			}
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_toc_auto_highlight",
		function (newValue) {
			let tocList = jQuery("#eael-toc-list");
			if (newValue === "yes") {
				tocList.addClass("eael-toc-auto-highlight");
			} else {
				tocList.removeClass("eael-toc-auto-highlight");
			}
		}
	);

	elementor.settings.page.addChangeCallback(
		"eael_ext_toc_auto_highlight_single_item_only",
		function (newValue) {
			let tocList = jQuery("#eael-toc-list");
			if (newValue === "yes") {
				if(tocList.hasClass("eael-toc-auto-highlight")){
					tocList.addClass("eael-toc-highlight-single-item");
				}
			} else {
				tocList.removeClass("eael-toc-highlight-single-item");
			}
		}
	);

	elementor.settings.page.addChangeCallback("eael_ext_toc_title", function (
		newValue
	) {
		elementorFrontend.elements.$document.find(".eael-toc-title").text(newValue);
		elementorFrontend.elements.$document
		.find(".eael-toc-button span")
		.text(newValue);
	});
});
