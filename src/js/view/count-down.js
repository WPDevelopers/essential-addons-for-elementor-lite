var CountDown = function ($scope, $) {
	var $coundDown = $scope.find(".eael-countdown-wrapper").eq(0),
		$countdown_id = $coundDown.data("countdown-id") !== undefined ? $coundDown.data("countdown-id") : "",
		$expire_type = $coundDown.data("expire-type") !== undefined ? $coundDown.data("expire-type") : "",
		$expiry_text = $coundDown.data("expiry-text") !== undefined ? DOMPurify.sanitize($coundDown.data("expiry-text")) : "",
		$expiry_title = $coundDown.data("expiry-title") !== undefined ? DOMPurify.sanitize($coundDown.data("expiry-title")) : "",
		$redirect_url = $coundDown.data("redirect-url") !== undefined ? $coundDown.data("redirect-url") : "",
		$template = $coundDown.data("template") !== undefined ? $coundDown.data("template") : "",
		$countdown_type = $coundDown.data("countdown-type") !== undefined ? $coundDown.data("countdown-type") : "",
		$evergreen_time = $coundDown.data("evergreen-time") !== undefined ? $coundDown.data("evergreen-time") : "",
		$recurring = $coundDown.data("evergreen-recurring") !== undefined ? $coundDown.data("evergreen-recurring") : false,
		$recurring_stop_time = $coundDown.data("evergreen-recurring-stop") !== undefined ? $coundDown.data("evergreen-recurring-stop") : "";

	jQuery(document).ready(function ($) {
		"use strict";
		var countDown = $("#eael-countdown-" + $countdown_id),
			eael_countdown_options = {
				end: function () {
					if ($expire_type == "text") {
						countDown.html(
							'<div class="eael-countdown-finish-message"><h4 class="expiry-title">' +
							$expiry_title +
							"</h4>" +
							'<div class="eael-countdown-finish-text">' +
							$expiry_text +
							"</div></div>"
						);
					} else if ($expire_type === "url") {
						if (isEditMode) {
							countDown.html("Your Page will be redirected to given URL (only on Frontend).");
						} else {
							window.location.href = eael.sanitizeURL($redirect_url);
						}
					} else if ($expire_type === "template") {
						countDown.html($coundDown.find(".eael-countdown-expiry-template").html());
					} else {
						//do nothing!
					}
				},
			};

		if ($countdown_type === 'evergreen') {
			let $evergreen_interval = `eael_countdown_evergreen_interval_${$countdown_id}`,
				$evergreen_time_key = `eael_countdown_evergreen_time_${$countdown_id}`,
				$interval = localStorage.getItem($evergreen_interval),
				$date = localStorage.getItem($evergreen_time_key),
				HOUR_IN_MILISECONDS = 60 * 60 * 1000;

			if ($date === null || $interval === null || $interval != $evergreen_time) {
				$date = Date.now() + parseInt($evergreen_time) * 1000;
				localStorage.setItem($evergreen_interval, $evergreen_time.toString());
				localStorage.setItem($evergreen_time_key, $date.toString());
			}

			if ($recurring !== false) {
				$recurring_stop_time = new Date($recurring_stop_time);
				let $recurring_after = parseFloat($recurring) * HOUR_IN_MILISECONDS;

				if (parseInt($date) + $recurring_after < Date.now()) {
					$date = Date.now() + parseInt($evergreen_time) * 1000;
					localStorage.setItem($evergreen_time_key, $date.toString());
				}

				if ($recurring_stop_time.getTime() < $date) {
					$date = $recurring_stop_time.getTime();
				}
			}

			eael_countdown_options.date = new Date(parseInt($date));
		}

		countDown.eacountdown(eael_countdown_options);
	});
};
jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eael-countdown.default", CountDown);
});
