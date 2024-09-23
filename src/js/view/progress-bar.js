var ProgressBar = function ($scope, $) {
	var $this = $(".eael-progressbar", $scope);
	var $layout = $this.data("layout");
	var $num = $this.data("count");
	var $duration = $this.data("duration");

	if ($num > 100) {
		$num = 100;
	}

	$this.one("inview", function () {
		if ($layout == "line") {
			$(".eael-progressbar-line-fill", $this).css({
				width: $num + "%",
			});
		} else if ($layout == "half_circle") {
			$(".eael-progressbar-circle-half", $this).css({
				transform: "rotate(" + $num * 1.8 + "deg)",
			});
		}

		eael.hooks.doAction("progressBar.initValue", $this, $layout, $num);

		$(".eael-progressbar-count", $this)
			.prop({
				counter: 0,
			})
			.animate(
				{
					counter: $num,
				},
				{
					duration: $duration,
					easing: "linear",
					step: function (counter) {
						if ($layout == "circle" || $layout == "circle_fill") {
							var rotate = counter * 3.6;

							$(".eael-progressbar-circle-half-left", $this).css({
								transform: "rotate(" + rotate + "deg)",
							});

							if (rotate > 180) {
								$(".eael-progressbar-circle-pie", $this).css({
									"-webkit-clip-path": "inset(0)",
									"clip-path": "inset(0)",
								});
								$(".eael-progressbar-circle-half-right", $this).css({
									visibility: "visible",
								});
							}
						}

						$(this).text(Math.ceil(counter));
					},
				}
			);
	});
};
jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eael-progress-bar.default", ProgressBar);
});
