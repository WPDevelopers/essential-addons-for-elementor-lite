jQuery(window).on("elementor/frontend/init", function () {
	var filterableGalleryHandler = function ($scope, $) {
		var filterControls = $scope.find(".fg-layout-3-filter-controls").eq(0),
			filterTrigger  = $scope.find("#fg-filter-trigger"),
			form           = $scope.find(".fg-layout-3-search-box"),
			input          = $scope.find("#fg-search-box-input"),
			searchRegex,
			buttonFilter,
			timer;

		if (form.length) {
			form.on("submit", function (e) {
				e.preventDefault();
			});
		}

		filterTrigger.on("click", function () {
			filterControls.toggleClass("open-filters");
		});
		filterTrigger.on("blur",function () {
			filterControls.removeClass("open-filters");
		});

		if (!isEditMode) {
			var $gallery         = $(".eael-filter-gallery-container", $scope),
				$settings        = $gallery.data("settings"),
				$gallery_items   = $gallery.data("gallery-items"),
				$layout_mode     = $settings.grid_style === "masonry" ? "masonry" : "fitRows",
				$gallery_enabled = ($settings.gallery_enabled === "yes");

			// init isotope
			let gwrap = $(".eael-filter-gallery-wrapper");
			var layoutMode       = gwrap.data("layout-mode");
			var mfpCaption       = gwrap.data("mfp_caption");
			var $isotope_gallery = $gallery.isotope({
				itemSelector: ".eael-filterable-gallery-item-wrap",
				layoutMode: $layout_mode,
				percentPosition: true,
				stagger: 30,
				transitionDuration: $settings.duration + "ms",
				filter: function () {
					var $this   = $(this);
					var $result = searchRegex ? $this.text().match(searchRegex) : true;
					if (buttonFilter === undefined) {
						if (layoutMode !== "layout_3") {
							buttonFilter = $scope
								.find(".eael-filter-gallery-control ul li")
								.first()
								.data("filter");
						} else {
							buttonFilter = $scope
								.find(".fg-layout-3-filter-controls li")
								.first()
								.data("filter");
						}
					}
					var buttonResult = buttonFilter ? $this.is(buttonFilter) : true;
					return $result && buttonResult;
				}
			});

			// Popup
			$($scope).magnificPopup({
				delegate: ".eael-magnific-link",
				type: "image",
				gallery: {
					enabled: $gallery_enabled
				},
				image: {
					titleSrc: function (item) {
						if (mfpCaption === "yes") {
							return item.el.parents('.gallery-item-caption-over').find('.fg-item-title').html() || item.el.parents('.gallery-item-caption-wrap').find('.fg-item-title').html() || item.el.parents('.eael-filterable-gallery-item-wrap').find('.fg-item-title').html();
						}
					}
				}
			});

			// filter
			$scope.on("click", ".control", function () {
				var $this    = $(this);
				buttonFilter = $(this).attr("data-filter");
				let $tspan = $scope.find("#fg-filter-trigger > span");
				if ($tspan.length) {
					$tspan.text($this.text());
				}

				$this.siblings().removeClass("active");
				$this.addClass("active");
				$isotope_gallery.isotope();
			});

			//quick search
			input.on("input", function () {
				var $this = $(this);

				clearTimeout(timer);
				timer = setTimeout(function () {
					searchRegex = new RegExp($this.val(), "gi");
					$isotope_gallery.isotope();
				}, 600);
			});

			// layout gal, while images are loading
			$isotope_gallery.imagesLoaded().progress(function () {
				$isotope_gallery.isotope("layout");
			});

			// layout gal, on click tabs
			$isotope_gallery.on("arrangeComplete", function () {
				$isotope_gallery.isotope("layout");
			});

			// layout gal, after window loaded
			$(window).on("load", function () {
				$isotope_gallery.isotope("layout");
			});

			// Load more button
			$scope.on("click", ".eael-gallery-load-more", function (e) {
				e.preventDefault();
				var $this            = $(this),
					$init_show       = $(".eael-filter-gallery-container", $scope).children(".eael-filterable-gallery-item-wrap").length,
					$total_items     = $gallery.data("total-gallery-items"),
					$images_per_page = $gallery.data("images-per-page"),
					$nomore_text     = $gallery.data("nomore-item-text"),
					filter_enable = $(".eael-filter-gallery-control",$scope).length,
					$items           = [];
				var filter_name      = $(".eael-filter-gallery-control li.active", $scope).data('filter');
				if(filterControls.length>0){
					filter_name = $(".fg-layout-3-filter-controls li.active", $scope).data('filter');
				}

				if ($init_show === $total_items) {
					$this.html('<div class="no-more-items-text">' + $nomore_text + "</div>");
					setTimeout(function () {
						$this.fadeOut("slow");
					}, 600);
				}

				// new items html
				var i          = $init_show;
				var item_found = 0;
				while (i < ($init_show + $images_per_page) && item_found<$images_per_page) {
					if (filter_name !== '' && filter_name !== '*' && filter_enable) {
						console.log($gallery_items)
						for (let j in $gallery_items){
							const index = parseInt(j)+i;
							console.log("loop "+index)
							console.log("j "+j)
							console.log("i "+i)
							let element = $($($gallery_items[index])[0]);
							if (element.is(filter_name)) {
								++item_found;
								$items.push($($gallery_items[index])[0]);
								delete $gallery_items[index];
								if (item_found === $images_per_page) {
									break;
								}
							}
						}
						// for (let j = i; j < $gallery_items.length; j++) {
						// 	let element = $($($gallery_items[j])[0]);
						// 	if (element.is(filter_name)) {
						// 		++item_found;
						// 		$items.push($($gallery_items[j])[0]);
						// 		delete $gallery_items[j];
						// 		if (item_found === $images_per_page) {
						// 			break;
						// 		}
						// 	}
						// }
						//break when cross $images_per_page or no image found
						break;
					} else {
						++item_found;
						console.log("outer "+i)
						$items.push($($gallery_items[i])[0]);
						delete $gallery_items[i];
					}
					i++;
				}

				// append items
				$gallery.append($items);
				$isotope_gallery.isotope("appended", $items);
				$isotope_gallery.imagesLoaded().progress(function () {
					$isotope_gallery.isotope("layout");
				});
			});

			// Fix issue on Safari: hide filter menu
			$(document).on('mouseup', function(e){
				if(!filterTrigger.is(e.target) && filterTrigger.has(e.target).length === 0){
					filterControls.removeClass("open-filters");
				}
			});
		}
	};

	elementorFrontend.hooks.addAction("frontend/element_ready/eael-filterable-gallery.default", filterableGalleryHandler);
});
