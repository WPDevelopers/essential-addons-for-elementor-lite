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

		if($circleWrap.hasClass('eael-interactive-circle-preset-1')){

			var $wrap = $circleWrap.find(".eael-circle-inner").width();
			var $i = $tabLinks.length;
			var $navwidth = $tabLinks.width();

			for(var a=1;a<=$i;a++){
				console.log($wrap);

			$tabLinks.eq(a).css({
				transform: 'rotate(calc((360deg / '+$i+') * '+a+')) translate(calc('+$wrap+'px / 2)) rotate(calc((360deg /' +
					' '+$i+')' +
					' * '+a+' * -1)) rotate(calc(180deg + ('+$i+' * 10deg)))',
			});

		}

		}

		// if($circleWrap.hasClass('eael-interactive-circle-preset-2')){
		//
		// 	// var radius = ($tabLinks.length * $tabLinks.width()) / 2;
		// 	// var degree = Math.PI / $tabLinks.length, angle = degree / 2;
		//
		// 	var i=$tabLinks.length,n=i-1,r=120;
		//
		// 	// console.log(radius);
		// 	// console.log(degree);
		// 	// console.log(angle);
		//
		// 	for(var a=0;a<i;a++){
		//
		// 		$tabLinks.eq(a).css({
		//
		// 			'transition-delay':""+(50*a)+"ms",
		// 			'-webkit-transition-delay':""+(50*a)+"ms",
		//
		// 			'left':(r*Math.cos(90/n*a*(Math.PI/180))),
		//
		// 			'top':(-r*Math.sin(90/n*a*(Math.PI/180)))
		//
		// 		});
		//
		// 	}
		//
		//
		// 	// $tabLinks.each(function() {
		// 	// 	var x = r*Math.cos(90/n*a*(Math.PI/180));
		// 	// 	var y = Math.round(radius * Math.sin(angle));
		// 	//
		// 	// 	$(this).css({
		// 	// 		left: x + 'px',
		// 	// 		top: y + 'px'
		// 	// 	});
		// 	//
		// 	// 	// if(window.console) {
		// 	// 	// 	console.log(x, y);
		// 	// 	// }
		// 	//
		// 	// 	angle += degree;
		// 	// });
		// }
	};

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-interactive-circle.default",
		interactiveCircle
	);
});
