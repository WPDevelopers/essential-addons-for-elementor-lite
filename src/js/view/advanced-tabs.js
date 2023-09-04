ea.hooks.addAction("init", "ea", () => {
	if (ea.elementStatusCheck('eaelAdvancedTabs')) {
		return false;
	}
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-adv-tabs.default",
		function ($scope, $) {
			const $currentTab = $scope.find('.eael-advance-tabs');
			let $customIdOffsetTab = $currentTab.data('custom-id-offset');
			if ( !$currentTab.attr( 'id' ) ) {
				return false;
			}
			const $currentTabId = '#' + $currentTab.attr('id').toString();
			let hashTag = window.location.hash.substr(1);
				hashTag = hashTag === 'safari' ? 'eael-safari' : hashTag;
			var hashLink = false;
			$($currentTabId + ' > .eael-tabs-nav ul li', $scope).each(function (index) {
				if (hashTag && $(this).attr("id") == hashTag) {
					$($currentTabId + ' .eael-tabs-nav > ul li', $scope)
					.removeClass("active")
					.addClass("inactive");
					
					$(this).removeClass("inactive").addClass("active");

					hashLink = true;
				} else {
					if ($(this).hasClass("active-default") && !hashLink) {
						$($currentTabId + ' .eael-tabs-nav > ul li', $scope)
						.removeClass("active")
						.addClass("inactive");
						$(this).removeClass("inactive").addClass('active');
					} else {
						if (index == 0) {
							if( $currentTab.hasClass('eael-tab-auto-active') ) {
								$(this).removeClass("inactive").addClass("active");
							}
						}
					}
				}
			});
			
			var hashContent = false;
			$($currentTabId + ' > .eael-tabs-content > div', $scope).each(function (index) {
				if (hashTag && $(this).attr("id") == hashTag + '-tab') {
					$($currentTabId + ' > .eael-tabs-content > div', $scope).removeClass("active");
					let nestedLink = $(this).closest('.eael-tabs-content').closest('.eael-tab-content-item');
					if (nestedLink.length) {
						let parentTab = nestedLink.closest('.eael-advance-tabs'),
							titleID = $("#"+nestedLink.attr("id")),
							contentID = titleID.data('title-link');
						parentTab.find(" > .eael-tabs-nav > ul > li").removeClass('active');
						parentTab.find(" > .eael-tabs-content > div").removeClass('active');
						titleID.addClass("active")
						$("#" + contentID).addClass("active")
					}
					$(this).removeClass("inactive").addClass("active");
					hashContent = true
				} else {
					if ($(this).hasClass("active-default") && !hashContent) {
						$($currentTabId + ' > .eael-tabs-content > div', $scope).removeClass("active");
						$(this).removeClass("inactive").addClass("active");
					} else {
						if (index == 0) {
							if( $currentTab.hasClass('eael-tab-auto-active') ) {
								$(this).removeClass("inactive").addClass("active");
							}
						}
					}
				}
			});

			$($currentTabId + ' > .eael-tabs-nav ul li', $scope).on("click", function (e) {
				e.preventDefault();
				
				var currentTabIndex = $(this).index();
				var tabsContainer = $(this).closest(".eael-advance-tabs");
				var tabsNav = $(tabsContainer)
				.children(".eael-tabs-nav")
				.children("ul")
				.children("li");
				var tabsContent = $(tabsContainer)
				.children(".eael-tabs-content")
				.children("div");

				if ($($currentTabId).hasClass('eael-tab-toggle')) {
					$(this).toggleClass('active inactive');
					$(tabsNav).not(this).removeClass("active active-default").addClass("inactive").attr('aria-selected', 'false').attr('aria-expanded', 'false');
					$(this).attr("aria-selected", 'true').attr("aria-expanded", 'true');

					$(tabsContent).not(':eq(' + currentTabIndex + ')').removeClass("active").addClass("inactive");
					$(tabsContent).eq(currentTabIndex).toggleClass('active inactive');
				} else {
					$(this).parent("li").addClass("active");
					$(tabsNav).removeClass("active active-default").addClass("inactive").attr('aria-selected', 'false').attr('aria-expanded', 'false');
					$(this).addClass("active").removeClass("inactive");
					$(this).attr("aria-selected", 'true').attr("aria-expanded", 'true');

					$(tabsContent).removeClass("active").addClass("inactive");
					$(tabsContent).eq(currentTabIndex).addClass("active").removeClass("inactive");
				}
				ea.hooks.doAction("ea-advanced-tabs-triggered", $(tabsContent).eq(currentTabIndex));
				
				$(tabsContent).each(function (index) {
					$(this).removeClass("active-default");
				});
				
				let $filterGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-filter-gallery-container"),
					$postGridGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-post-grid.eael-post-appender"),
					$twitterfeedGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-twitter-feed-masonry"),
					$instaGallery = tabsContent
					.eq(currentTabIndex)
					.find(".eael-instafeed"),
					$paGallery = tabsContent
					.eq(currentTabIndex)
					.find(".premium-gallery-container"),
					$evCalendar = $(".eael-event-calendar-cls", tabsContent);
				
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

			// If hashTag is not null then scroll to that hashTag smoothly
			if( typeof hashTag !== 'undefined' && hashTag && !ea.elementStatusCheck('eaelAdvancedTabScroll')){
				let $customIdOffsetValTab = $customIdOffsetTab ? parseFloat($customIdOffsetTab) : 0;
					$('html, body').animate({
						scrollTop: $("#"+hashTag).offset().top - $customIdOffsetValTab,
					}, 300);
			}

		}
	);
});