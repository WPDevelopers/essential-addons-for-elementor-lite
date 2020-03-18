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
								dialog.querySelector(".dialog-buttons-action").style.display = "none";

								if (dialog.querySelector(".ea-dialog-buttons-action") === null) {
									var button = document.createElement("a");
									var buttonText = document.createTextNode("GO PRO");

									button.setAttribute("href", "https://essential-addons.com/elementor/#pricing");
									button.setAttribute("target", "_blank");
									button.classList.add(
										"dialog-button",
										"dialog-action",
										"dialog-buttons-action",
										"elementor-button",
										"elementor-button-success",
										"ea-dialog-buttons-action"
									);
									button.appendChild(buttonText);

									dialog.querySelector(".dialog-buttons-action").insertAdjacentHTML("afterend", button.outerHTML);
								} else {
									dialog.querySelector(".ea-dialog-buttons-action").style.display = "";
								}
							} else {
								dialog.querySelector(".dialog-buttons-action").style.display = "";

								if (dialog.querySelector(".ea-dialog-buttons-action") !== null) {
									dialog.querySelector(".ea-dialog-buttons-action").style.display = "none";
								}
							}
						});
					});

					clearTimeout(interval);
				}
			}, 500);
		}
	});
})(jQuery);
