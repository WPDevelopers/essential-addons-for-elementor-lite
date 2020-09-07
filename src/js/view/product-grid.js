var ProductGrid = function ($scope, $) {

	// pagination

	$('.eael-woo-pagination', $scope).on('click', 'a', function(e) {
		e.preventDefault();

		var $this = $(this),
			nth  = $this.data('pnumber'),
			lmt  = $this.data('plimit'),
			ajax_url = localize.ajaxurl,
			args = $this.data('args'),
			settings = $this.data('settings'),
			widgetid = $this.data('widgetid'),
			widgetclass = ".elementor-element-"+widgetid;


		$.ajax({
			url		:ajax_url,
			type	:'post',
			data	:{
				action:'woo_product_pagination_product',
				number:nth,
				limit:lmt,
				args:args,
				settings:settings
			},
			// beforeSend	: function(){
			// 	$(widgetclass+" .eael-product-grid .products").html("<li style='text-align:center;'>Loading please " +
			// 		"wait...!</li>");
			// },
			success :function(response){
				// console.log(response);
				$(widgetclass+" .eael-product-grid .products").html(response);
				$(widgetclass+" .woocommerce-product-gallery").each(function () {
					$(this).wc_product_gallery();
				});

			}
		});

		$.ajax({
			url		:ajax_url,
			type	:'post',
			data	:{
				action:'woo_product_pagination',
				number:nth,
				limit:lmt,
				args:args,
				settings:settings
			},
			// beforeSend	: function(){
			// 	$(widgetclass+" .eael-product-grid .products").html("<li style='text-align:center;'>Loading please " +
			// 		"wait...!</li>");
			// },
			success :function(response){
				$(widgetclass+" .eael-product-grid .eael-woo-pagination").html(response);
			}
		});


	});

};

jQuery(window).on("elementor/frontend/init", function () {
	elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default", ProductGrid);
});
