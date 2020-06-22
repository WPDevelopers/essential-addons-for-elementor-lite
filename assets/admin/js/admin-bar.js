(function ($) {
	"use strict";

	$(document).on("click", ".ea-tools-clear-cache-single", function (e) {
		e.preventDefault();

		if (typeof localize != "undefined" && localize) {
			var posts = $("#eael-loaded-templates").html(),
				text = $(this).find(".ab-item");

			$.ajax({
				url: localize.ajaxurl,
				type: "post",
				data: {
					action: "clear_cache_files_with_ajax",
					security: localize.nonce,
					posts: posts,
				},
				beforeSend: function () {
					text.text("Generating...");
				},
				success: function (response) {
					setTimeout(function () {
						text.text("Regenerate Page Assets");
						window.location.reload();
					}, 1000);
				},
				error: function () {
					console.log("Something went wrong!");
				},
			});
		} else {
			console.log("This page has no widget from EA");
		}
	});

	$(document).on("click", ".ea-tools-clear-cache", function (e) {
		e.preventDefault();

		if (typeof localize != "undefined" && localize) {
			var text = $(this).find(".ab-item");

			$.ajax({
				url: localize.ajaxurl,
				type: "post",
				data: {
					action: "clear_cache_files_with_ajax",
					security: localize.nonce,
				},
				beforeSend: function () {
					text.text("Generating...");
				},
				success: function (response) {
					setTimeout(function () {
						text.text("Regenerate All Assets");
						window.location.reload();
					}, 1000);
				},
				error: function () {
					console.log("Something went wrong!");
				},
			});
		} else {
			console.log(
				"This page has no widget from EA, Regenerate Assets from Dashboard"
			);
		}
	});
})(jQuery);
