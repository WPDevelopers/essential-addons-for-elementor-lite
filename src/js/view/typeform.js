var TypeFormHandler = function ($scope, $) {
	if (typeformEmbed) {
		var typeForm = $scope.find(".eael-typeform"),
			id = typeForm.attr("id"),
			data = typeForm.data('typeform');
		if (typeof id != 'undefined' && typeof data !=='undefined') {
			var el = document.getElementById(id);
			if(data.url){
				typeformEmbed.makeWidget(el, data.url, {
					hideFooter: data.hideFooter,
					hideHeaders: data.hideHeaders,
					opacity: data.opacity
				});
			}
		}
	}
};

jQuery(window).on("elementor/frontend/init", function() {
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-typeform.default",
		TypeFormHandler
	);
});
