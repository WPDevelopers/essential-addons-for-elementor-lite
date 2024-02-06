ea.hooks.addAction("init", "ea", () => {

	const wooProductGallery = function ($scope, $) {
		let showSecondaryImageOnHover = $scope.find(".products.eael-post-appender").data("show-secondary-image");
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
					terms_tag: $('.eael-cat-tab li a.active', $scope).data('tagid'),
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
						var $max_page = $('<div>' + response + '</div>').find('.eael-max-page').text();
						var load_more = $('.eael-load-more-button', $scope);
						if ($max_page && load_more.data('page') >= $max_page) {
							load_more.addClass('hide-load-more');
						}else {
							load_more.removeClass('hide-load-more');
						}
						load_more.data('max-page', $max_page);
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

		let dataSrc = dataSrcHover = srcset = srcsetHover = ''; 

		if( showSecondaryImageOnHover ){
			$(document).on("mouseover", ".eael-product-wrap", function () {
				dataSrc = $(this).data("src");
				dataSrcHover = $(this).data("src-hover");
				srcset = $(this).find('img').attr('srcset');
				
				if( dataSrcHover ){
					$(this).find('img').attr('srcset-hover', srcset);

					$(this).find('img').attr( 'src', dataSrcHover );
					$(this).find('img').attr('srcset', dataSrcHover );
				}
			  }).on( "mouseout", ".eael-product-wrap", function () {
				dataSrc = $(this).data("src");
				dataSrcHover = $(this).data("src-hover")
				srcsetHover = $(this).find('img').attr('srcset-hover');

				if( dataSrcHover ){
					$(this).find('img').attr( 'src', dataSrc );
					$(this).find('img').attr('srcset', srcsetHover );
					$(this).find('img').attr('srcset-hover', '' );
				}
			  });
		}

		/*
		Returning to the page using the browser's forward or back buttons 
		it will reload spatially [Safari] browser
		*/
		window.addEventListener( "pageshow", function( evt ) {
			if( evt.persisted ) {
				setTimeout( function() {
					window.location.reload();
				}, 10 );
			}
		}, false );
	};

    if (ea.elementStatusCheck('productGalleryLoad')) {
        return false;
    }

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-gallery.default",
		wooProductGallery
	);
});
