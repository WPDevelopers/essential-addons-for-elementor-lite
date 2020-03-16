(function($) {
	window.isEditMode = false;

	$(window).on("elementor/frontend/init", function() {
		window.isEditMode = elementorFrontend.isEditMode();

		if (isEditMode) {
			var interval = setInterval(function() {
				if (parent.document.querySelectorAll(".elementor-element--promotion").length > 0) {
					parent.document.querySelectorAll(".elementor-element--promotion").forEach(function(widget) {
						widget.addEventListener("click", function(e) {
							var dialog = parent.document.querySelector("#elementor-element--promotion__dialog");

							if (dialog.querySelector("#elementor-element--promotion__dialog__title").innerHTML.match("EA")) {
								dialog.querySelector(".dialog-buttons-action").innerHTML = "Go Pro";

								dialog.querySelector(".dialog-action").addEventListener("click", function(e) {
                                    return false
                                    e.preventDefault();
                                    
                                    console.log(e)
								});
							} else {
								dialog.querySelector(".dialog-buttons-action").innerHTML = "See it in Action";
							}
						});
					});

					clearTimeout(interval);
				}
			}, 500);
		}
	});
})(jQuery);
