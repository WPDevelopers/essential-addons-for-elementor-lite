(function ($) {
	"use strict";

	$(document).on("click", ".eael-load-more-button", function (e) {
		e.preventDefault();
		e.stopPropagation();
		e.stopImmediatePropagation();
		var $this = $(this),
			$LoaderSpan = $("span", $this),
			$text = $LoaderSpan.html(),
			$widget_id = $this.data("widget"),
			$page_id = $this.data("page-id"),
			$nonce = $this.data("nonce"),
			$scope = $(".elementor-element-" + $widget_id),
			$class = $this.data("class"),
			$args = $this.data("args"),
			$layout = $this.data("layout"),
			$template_info = $this.data('template'),
			$page = parseInt($this.data("page")) + 1;

		if (typeof $widget_id == "undefined" || typeof $args == "undefined") {
			return;
		}

		var obj = {};
		var $data = {
			action: "load_more",
			class: $class,
			args: $args,
			page: $page,
			page_id: $page_id,
			widget_id: $widget_id,
			nonce: $nonce,
			template_info: $template_info
		};
		String($args)
			.split("&")
			.forEach(function (item, index) {
				var arr = String(item).split("=");
				obj[arr[0]] = arr[1];
			});

		if (obj.orderby == "rand") {
			var $printed = $(".eael-grid-post");

			if ($printed.length) {
				var $ids = [];
				$printed.each(function (index, item) {
					var $id = $(item).data("id");
					$ids.push($id);
				});

				$data.post__not_in = $ids;
			}
		}

		$this.addClass("button--loading");
		$LoaderSpan.html(localize.i18n.loading);

		$.ajax({
			url: localize.ajaxurl,
			type: "post",
			data: $data,
			success: function (response) {
				var $content = $(response);

				if ($content.hasClass("no-posts-found") || $content.length === 0) {
					$this.remove();
				} else {
					if ($data.class == "Essential_Addons_Elementor\\Elements\\Product_Grid") {
						$(".eael-product-grid .products", $scope).append($content.filter('li'));
						const dynamicID = "eael-product-"+Date.now();
						
						if ($layout == "masonry") {
							$content.find('.woocommerce-product-gallery').addClass(dynamicID);
							$content.find('.woocommerce-product-gallery').addClass('eael-new-product');

							$(".woocommerce-product-gallery."+dynamicID,$scope).each(function () {
								$(this).wc_product_gallery();
							});

							$(".eael-product-grid .products", $scope).isotope("appended", $content).isotope("layout");
						}

					} else {
						$(".eael-post-appender", $scope).append($content);

						if ($layout == "masonry") {
							var $isotope = $(".eael-post-appender", $scope).isotope();
							$isotope.isotope("appended", $content).isotope("layout");

							$isotope.imagesLoaded().progress(function () {
								$isotope.isotope("layout");
							});
						}
					}

					$this.removeClass("button--loading");
					$LoaderSpan.html($text);

					$this.data("page", $page);
				}
			},
			error: function (response) {
				console.log(response);
			},
		});
	});
})(jQuery);
