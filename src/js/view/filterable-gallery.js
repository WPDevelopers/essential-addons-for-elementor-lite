jQuery(window).on("elementor/frontend/init", function () {
	var filterableGalleryHandler = function ($scope, $) {
		var filterControls = $scope.find(".fg-layout-3-filter-controls").eq(0),
			filterTrigger  = $scope.find("#fg-filter-trigger"),
			form          = $scope.find(".fg-layout-3-search-box"),
			input         = $scope.find("#fg-search-box-input"),
			searchRegex,
			buttonFilter,
			timer,
			fg_mfp_counter_text = localize.eael_translate_text.fg_mfp_counter_text;
			fg_mfp_counter_text = fg_mfp_counter_text ? '%curr% '+fg_mfp_counter_text+' %total%' : '%curr% of %total%';

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
				fg_items = $gallery_items   = $gallery.data("gallery-items"),
				$layout_mode     = $settings.grid_style === "masonry" ? "masonry" : "fitRows",
				$gallery_enabled = ($settings.gallery_enabled === "yes"),
				$images_per_page = $gallery.data("images-per-page"),
				$init_show_setting     = $gallery.data("init-show");
				fg_items.splice(0, $init_show_setting)
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
				delegate: ".eael-magnific-link.active",
				type: "image",
				gallery: {
					enabled: $gallery_enabled,
					tCounter: fg_mfp_counter_text,
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
				let initData = $(".eael-filter-gallery-container .eael-filterable-gallery-item-wrap"+buttonFilter,$scope).length;
				let $tspan = $scope.find("#fg-filter-trigger > span");
				if ($tspan.length) {
					$tspan.text($this.text());
				}
				const firstInit = parseInt($this.data('first-init'));
				
				if(!firstInit){
					$this.data('first-init', 1);
					let item_found = initData;
					let index_list = $items =  [];

					if (item_found < $images_per_page) {
						for (const [index, item] of fg_items.entries()) {
							if (buttonFilter !== '' && buttonFilter !== '*') {
								let element = $($(item)[0]);
								if (element.is(buttonFilter)) {
									++item_found;
									$items.push($(item)[0]);
									index_list.push(index);
								}
							}

							if (item_found >= $images_per_page) {
								break;
							}
						}
					}
					
					if(index_list.length>0){
						fg_items = fg_items.filter(function (item, index){
							return !index_list.includes(index);
						});
					}
				}
				
				const LoadMoreShow = $(this).data("load-more-status"),
					 loadMore = $(".eael-gallery-load-more",$scope);
				
				//hide load more button if selected control have no item to show
				if(LoadMoreShow || fg_items.length < 1){
					loadMore.hide()
				}else{
					loadMore.show()
				}
				
				$this.siblings().removeClass("active");
				$this.addClass("active");
				if (!firstInit && $items.length > 0) {
					$isotope_gallery.isotope();
					$gallery.append($items);
					$isotope_gallery.isotope('appended', $items);
					$isotope_gallery.imagesLoaded().progress(function () {
						$isotope_gallery.isotope("layout");
					});
					
				} else {
					$isotope_gallery.isotope();
				}

				if($this.hasClass('all-control')){
					//All items are active
					$('.eael-filterable-gallery-item-wrap .eael-magnific-link-clone').removeClass('active').addClass('active');
				}else {
					$('.eael-filterable-gallery-item-wrap .eael-magnific-link-clone').removeClass('active');
					$(buttonFilter + ' .eael-magnific-link').addClass('active');
				}
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
					// $init_show       = $(".eael-filter-gallery-container", $scope).children(".eael-filterable-gallery-item-wrap").length,
					// $total_items     = $gallery.data("total-gallery-items"),
					$nomore_text     = $gallery.data("nomore-item-text"),
					filter_enable 	= $(".eael-filter-gallery-control",$scope).length,
					$items           = [];
				var filter_name      = $(".eael-filter-gallery-control li.active", $scope).data('filter');
				if(filterControls.length>0){
					filter_name = $(".fg-layout-3-filter-controls li.active", $scope).data('filter');
				}

				if (filter_name === undefined) {
					filter_name = '*';
				}

				let item_found = 0;
				let index_list = []
				for (const [index, item] of fg_items.entries()){

					let element = $($(item)[0]);
					if (element.is(filter_name)) {
						++item_found;
						$items.push($(item)[0]);
						index_list.push(index);
					}
					if (filter_name !== '' && filter_name !== '*' && (fg_items.length-1)===index) {
							$(".eael-filter-gallery-control li.active", $scope).data('load-more-status',1)
							$this.hide();
					}

					if (item_found === $images_per_page) {
						break;
					}
				}

				if(index_list.length>0){
					fg_items = fg_items.filter(function (item, index){
						return !index_list.includes(index);
					});
				}

				if (fg_items.length<1) {
					$this.html('<div class="no-more-items-text">' + $nomore_text + "</div>");
					setTimeout(function () {
						$this.fadeOut("slow");
					}, 600);
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

			$( document ).ready(function() {
				if( window.location.hash ) {
					jQuery('#' + window.location.hash.substring(1) ).trigger('click');
				}
			});
		}
	};

	if (ea.elementStatusCheck('eaelFilterableGallery')) {
		return false;
	}

	elementorFrontend.hooks.addAction("frontend/element_ready/eael-filterable-gallery.default", filterableGalleryHandler);
});
