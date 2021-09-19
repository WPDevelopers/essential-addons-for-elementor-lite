ea.hooks.addAction("init", "ea", () => {
	const interactiveCircle = function ($scope, $) {

		var $circleWrap = $scope.find(".eael-circle-wrapper");
		var $eventType = "";

		if($circleWrap.hasClass('toggle-on-click')){
			$eventType = "click";
		} else {
			$eventType = "mouseenter";
		}

		console.log($eventType);

		var $tabLinks = $circleWrap.find(".eael-circle-btn");
		var $tabContents = $circleWrap.find(".eael-circle-btn-content");

		$tabLinks.first().addClass("active");
		$tabContents.first().addClass("active");

		$tabLinks.each(function (element) {
			$(this).on($eventType, handleEvent(element));
		});

		function handleEvent(element) {
			return function () {
				var $element = $(this);
				var $activeTab = $(this).hasClass("active");
				if ($activeTab == false) {
					$tabLinks.each(function (tabLink) {
						$(this).removeClass("active");
					});
					$element.addClass("active");
					$tabContents.each(function (tabContent) {
						$(this).removeClass("active");
						if ($(this).hasClass($element.attr("id"))) {
							$(this).addClass("active");
						}
					});
				}
			};
		}
	};

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-interactive-circle.default",
		interactiveCircle
	);
});
