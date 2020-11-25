ea.hooks.addAction("init", "ea", () => {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-adv-accordion.default",
		function ($scope, $) {
			let hashTag = window.location.hash.substr(1);
			let hashTagExists = false;
			var $advanceAccordion = $scope.find(".eael-adv-accordion"),
				$accordionHeader = $scope.find(".eael-accordion-header"),
				$accordionType = $advanceAccordion.data("accordion-type"),
				$accordionSpeed = $advanceAccordion.data("toogle-speed");

			// Open default actived tab
			if (hashTag) {
				$accordionHeader.each(function () {
					if ($(this).attr("id") == hashTag) {
						hashTagExists = true;

						$(this).addClass("show active");
						$(this).next().slideDown($accordionSpeed);
					}
				});
			}

			if (hashTagExists === false) {
				$accordionHeader.each(function () {
					if ($(this).hasClass("active-default")) {
						$(this).addClass("show active");
						$(this).next().slideDown($accordionSpeed);
					}
				});
			}

			// Remove multiple click event for nested accordion
			$accordionHeader.unbind("click");

			$accordionHeader.click(function (e) {
				e.preventDefault();

				let $this = $(this);
				let $content = $this.parent();
				let $filterGallery = $(".eael-filter-gallery-container", $content),
					$postGridGallery = $(".eael-post-grid.eael-post-appender", $content),
					$twitterfeedGallery = $(".eael-twitter-feed-masonry", $content),
					$instaGallery = $(".eael-instafeed", $content),
					$paGallery = $(".premium-gallery-container", $content),
					$evCalendar = $(".eael-event-calendar-cls", $content);

				if ($accordionType === "accordion") {
					if ($this.hasClass("show")) {
						$this.removeClass("show active");
						$this.next().slideUp($accordionSpeed);
					} else {
						$this
							.parent()
							.parent()
							.find(".eael-accordion-header")
							.removeClass("show active");
						$this
							.parent()
							.parent()
							.find(".eael-accordion-content")
							.slideUp($accordionSpeed);
						$this.toggleClass("show active");
						$this.next().slideToggle($accordionSpeed);
					}
				} else {
					// For acccordion type 'toggle'
					if ($this.hasClass("show")) {
						$this.removeClass("show active");
						$this.next().slideUp($accordionSpeed);
					} else {
						$this.addClass("show active");
						$this.next().slideDown($accordionSpeed);
					}
				}

				// added: compatibility for other js instance
				if ($postGridGallery.length) {
					$postGridGallery.isotope("layout");
				}

				if ($twitterfeedGallery.length) {
					$twitterfeedGallery.isotope("layout");
				}

				if ($filterGallery.length) {
					$filterGallery.isotope("layout");
				}

				if ($instaGallery.length) {
					$instaGallery.isotope("layout");
				}

				if ($paGallery.length) {
					$paGallery.each(function (index, item) {
						$(item).isotope("layout");
					});
				}

				if ($evCalendar.length) {
					ea.hooks.doAction("eventCalendar.reinit");
				}
			});
		}
	);
});
