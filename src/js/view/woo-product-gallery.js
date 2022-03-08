ea.hooks.addAction("init", "ea", () => {
	const wooProductGallery = function ($scope, $) {
		// category
		ea.hooks.doAction("quickViewAddMarkup",$scope,$);
		const $post_cat_wrap = $('.eael-cat-tab', $scope)

		$('.eael-cat-tab li:first a', $scope).addClass('active');

		$post_cat_wrap.on('click', 'a', function (e) {
			
			e.preventDefault();
			let $this = $(this);
			if($this.hasClass('active')){
				return false;
			}
			// tab class
			$('.eael-cat-tab li a', $scope).removeClass('active');
			$this.addClass('active');

			localStorage.setItem('eael-cat-tab', 'true');
			// collect props
			const $class = $post_cat_wrap.data('class'),
				$widget_id = $post_cat_wrap.data("widget"),
				$page_id = $post_cat_wrap.data("page-id"),
				$nonce = $post_cat_wrap.data("nonce"),
				$args = $post_cat_wrap.data('args'),
				$layout = $post_cat_wrap.data('layout'),
				$widget_class = ".elementor-element-" + $widget_id,
				$page = 1,
				$template_info = $post_cat_wrap.data('template'),
				$taxonomy = {
					taxonomy: $('.eael-cat-tab li a.active', $scope).data('taxonomy'),
					field: 'term_id',
					terms: $('.eael-cat-tab li a.active', $scope).data('id'),
				};

			// ajax
			$.ajax({
				url: localize.ajaxurl,
				type: 'POST',
				data: {
					action: 'eael_product_gallery',
					class: $class,
					args: $args,
					taxonomy: $taxonomy,
					template_info: $template_info,
					page: $page,
					page_id: $page_id,
					widget_id: $widget_id,
					nonce: $nonce
				},
				beforeSend: function () {
					$($widget_class + ' .woocommerce').addClass("eael-product-loader");
				},
				success: function (response) {
					var $content = $(response);
					if ($content.hasClass('no-posts-found') || $content.length == 0) {
						$('.elementor-element-' + $widget_id + ' .eael-product-gallery .woocommerce' +
							  ' .eael-post-appender')
						.empty()
						.append(`<h2 class="eael-product-not-found">No Product Found</h2>`);
						$('.eael-load-more-button', $scope).addClass('hide-load-more');
					} else {

						$('.elementor-element-' + $widget_id + ' .eael-product-gallery .woocommerce' +
							' .eael-post-appender')
							.empty()
							.append($content);

						$('.eael-load-more-button', $scope).removeClass('hide-load-more');

						if ($layout === 'masonry') {
							var $products = $('.eael-product-gallery .products', $scope);

							$products.isotope('destroy');

							// init isotope
							var $isotope_products = $products.isotope({
								itemSelector: "li.product",
								layoutMode: $layout,
								percentPosition: true
							});

							$isotope_products.imagesLoaded().progress( function() {
								$isotope_products.isotope('layout');
							})
							
						}
					}
				},
				complete: function () {
					$($widget_class + ' .woocommerce').removeClass("eael-product-loader");
				},
				error: function (response) {
					console.log(response);
				}
			});
		});
		
		ea.hooks.doAction("quickViewPopupViewInit",$scope,$);
		
		if (isEditMode) {
			$(".eael-product-image-wrap .woocommerce-product-gallery").css(
				"opacity",
				"1"
			);
		}

		$(document).on('click', '.theme-savoy .nm-qty-minus, .theme-savoy .nm-qty-plus', function(e) {
			// Get elements and values
			var $this		= $(this),
				$qty		= $this.closest('.quantity').find('.qty'),
				currentVal	= parseFloat($qty.val()),
				max			= parseFloat($qty.attr('max')),
				min			= parseFloat($qty.attr('min')),
				step		= $qty.attr('step');

			// Format values
			if (!currentVal || currentVal === '' || currentVal === 'NaN') currentVal = 0;
			if (max === '' || max === 'NaN') max = '';
			if (min === '' || min === 'NaN') min = 0;
			if (step === 'any' || step === '' || step === undefined || parseFloat(step) === 'NaN') step = 1;
            
			// Change the value
			if ($this.hasClass('nm-qty-plus')) {
				if (max && (max == currentVal || currentVal > max)) {
					$qty.val(max);
				} else {
					$qty.val(currentVal + parseFloat(step));
				}
			} 
			else {
				if (min && (min == currentVal || currentVal < min)) {
					$qty.val(min);
				} else if (currentVal > 0) {
					$qty.val(currentVal - parseFloat(step));
				}
			}
		});
	};

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-gallery.default",
		wooProductGallery
	);
});
