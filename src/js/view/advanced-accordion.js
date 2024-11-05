eael.hooks.addAction("init", "ea", () => {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-adv-accordion.default",
		function ($scope, $) {
			let hashTag = window.location.hash.substr(1);
				hashTag = hashTag === 'safari' ? 'eael-safari' : hashTag;
			let hashTagExists = false;
			var $advanceAccordion = $scope.find(".eael-adv-accordion"),
				$accordionHeader = $scope.find(".eael-accordion-header"),
				$accordionType = $advanceAccordion.data("accordion-type"),
				$accordionSpeed = $advanceAccordion.data("toogle-speed"),
				$customIdOffset = $advanceAccordion.data("custom-id-offset"),
				$scrollOnClick = $advanceAccordion.data("scroll-on-click"),
				$srollSpeed = $advanceAccordion.data("scroll-speed");

			// Open default actived tab
			if (hashTag || $scrollOnClick === 'yes') {
				$accordionHeader.each(function () {
					if ($scrollOnClick === 'yes') {
						$(this).attr('data-scroll', $(this).offset().top)
					}

					if (hashTag) {
						if ($(this).attr("id") == hashTag) {
							hashTagExists = true;

							$(this).addClass("show-this active");
							$(this).next().slideDown($accordionSpeed);
						}
					}
				});
			}

			if (hashTagExists === false) {
				$accordionHeader.each(function () {
					if ($(this).hasClass("active-default")) {
						$(this).addClass("show-this active");
						$(this).next().slideDown($accordionSpeed);
					}
				});
			}

			// Remove multiple click event for nested accordion
			$accordionHeader.unbind("click");

			$accordionHeader.click(function (e) {
				e.preventDefault();

				var $this = $(this);

				setTimeout(function(e) {
					$('.eael-accordion-header').removeClass('triggered');
				},70);

				if( $this.hasClass('triggered') ){
					return;
				}

				if ($accordionType === "accordion") {
					if ($this.hasClass("show-this")) {
						$this.removeClass("show-this active");
						$this.next().slideUp($accordionSpeed);
					} else {
						$this.parent().parent().find(".eael-accordion-header").removeClass("show-this active");
						$this.parent().parent().find(".eael-accordion-content").slideUp($accordionSpeed);
						$this.toggleClass("show-this active");
						$this.next().slideToggle($accordionSpeed);
					}
				} else {
					// For acccordion type 'toggle'
					if ($this.hasClass("show-this")) {
						$this.removeClass("show-this active");
						$this.next().slideUp($accordionSpeed);
					} else {
						$this.addClass("show-this active");
						$this.next().slideDown($accordionSpeed);
					}
				}

                if ($scrollOnClick === 'yes' && $this.hasClass("active")) {
                    let $customIdOffsetVal = $customIdOffset ? parseFloat($customIdOffset) : 0;
                    $('html, body').animate({
                        scrollTop: $(this).data('scroll') - $customIdOffsetVal,
                    }, $srollSpeed);
                }

                setTimeout(function () {
                    $this.addClass('triggered');
                    eael.hooks.doAction("widgets.reinit", $this.parent());
                    eael.hooks.doAction("ea-advanced-accordion-triggered", $this.next());
                }, 50);
			});

			$scope.on('keydown', '.eael-accordion-header', function (e) {
				if (e.which === 13 || e.which === 32) {
					$(this).trigger('click');
				}
			});
		}
	);
});