ea.hooks.addAction("init", "ea", () => {
	const wooProductGallery = function ($scope, $) {
		// category
		const $post_cat_wrap = $('.eael-cat-tab', $scope)
		$post_cat_wrap.on('click', 'a', function (e) {
			e.preventDefault();
			let $this = $(this);
			// tab class
			$('.eael-cat-tab li a', $scope).removeClass('active');
			$this.addClass('active');

			// collect props
			const $class = $post_cat_wrap.data('class'),
				$widget_id = $post_cat_wrap.data("widget"),
				$page_id = $post_cat_wrap.data("page-id"),
				$nonce = $post_cat_wrap.data("nonce"),
				$args = $post_cat_wrap.data('args'),
				$page = 1,
				$template_info = $post_cat_wrap.data('template'),
				$taxonomy = {
					taxonomy: 'product_cat',
					field: 'slug',
					terms: $('.eael-cat-tab li a.active', $scope).data('terms'),
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
				success: function (response) {
					var $content = $(response);
					// console.log($content);

					if ($content.hasClass('no-posts-found') || $content.length == 0) {
						// do nothing
					} else {
						$('.elementor-element-' + $widget_id + ' .eael-product-gallery .woocommerce' +
							' .eael-post-appender')
							.empty()
							.append($content);

					}
				},
				error: function (response) {
					console.log(response);
				}
			});
		});

	};


	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-woo-product-gallery.default",
		wooProductGallery
	);
});
