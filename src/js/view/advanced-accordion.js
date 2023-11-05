ea.hooks.addAction("init", "ea", () => {
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
				$customIdOffset = $advanceAccordion.data("custom-id-offset");

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

				var $this = $(this);

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
				ea.hooks.doAction("widgets.reinit",$this.parent());
				ea.hooks.doAction("ea-advanced-accordion-triggered", $this.next());
			});

			$scope.on('keydown', '.eael-accordion-header', function (e) {
				if (e.which === 13 || e.which === 32) {
					$(this).trigger('click');
				}
			});

			// If hashTag is not null then scroll to that hashTag smoothly
			if( hashURL.startsWith( '#!' ) ){
				var replace_with_hash = hashURL.replace( '#!', '#' );
				$( replace_with_hash ).trigger( 'click' );
			} else {
				if( typeof hashTag !== 'undefined' && hashTag && !ea.elementStatusCheck('eaelAdvancedAccordionScroll') ){
					let $customIdOffsetVal = $customIdOffset ? parseFloat($customIdOffset) : 0;
					$('html, body').animate({
						scrollTop: $("#"+hashTag).offset().top - $customIdOffsetVal,
					}, 300);
				}
			}
		}
	);
});