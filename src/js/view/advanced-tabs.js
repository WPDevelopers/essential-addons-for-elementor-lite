ea.hooks.addAction("init", "ea", () => {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-adv-tabs.default",
		function ($scope, $) {
			let hashTag = window.location.hash.substr(1);
			let hashTagExists = false;

			if (hashTag) {
				$(".eael-tabs-nav ul li", $scope).each(function (index) {
					if ($(this).attr("id") == hashTag) {
						hashTagExists = true;

						$(this).removeClass("inactive").addClass("active");
					}
				});

				$(".eael-tabs-content div", $scope).each(function (index) {
					if ($(this).attr("id") == hashTag) {
						$(this).removeClass("inactive").addClass("active");
					}
				});
			}

			if (hashTagExists === false) {
				$(".eael-tabs-nav ul li", $scope).each(function (index) {
					if ($(this).hasClass("active-default")) {
						$(".eael-tabs-nav > ul li", $scope)
							.removeClass("active")
							.addClass("inactive");
						$(this).removeClass("inactive").addClass("active");
					} else {
						if (index == 0) {
							$(this).removeClass("inactive").addClass("active");
						}
					}
				});

				$(".eael-tabs-content div", $scope).each(function (index) {
					if ($(this).hasClass("active-default")) {
						$(".eael-tabs-content > div", $scope)
							.removeClass("active")
							.addClass("inactive");
						$(this).removeClass("inactive").addClass("active");
					} else {
						if (index == 0) {
							$(this).removeClass("inactive").addClass("active");
						}
					}
				});
			}

			$(".eael-tabs-nav ul li", $scope).on("click", function (e) {
				e.preventDefault();

				let currentTabIndex = $(this).index();
				let tabsContent = $(".eael-tabs-content", $scope).children("div");

				// handle tab class
				$(this)
					.siblings()
					.removeClass("active active-default")
					.addClass("inactive");
				$(this).addClass("active").removeClass("inactive");

				// handle tab content class
				$(tabsContent)
					.removeClass("active active-default")
					.addClass("inactive");
				$(tabsContent)
					.eq(currentTabIndex)
					.addClass("active")
					.removeClass("inactive");

				// fire hooks for inner contents
				ea.hooks.doAction("widgets.reinit", $(tabsContent).eq(currentTabIndex));
			});
		}
	);
});
