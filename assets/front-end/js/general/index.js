(function($) {
	window.isEditMode = false;

	$(window).on("elementor/frontend/init", function() {
		window.isEditMode = elementorFrontend.isEditMode();

		if (isEditMode) {
			parent.document.addEventListener("mousedown", function(e) {
				var widgets = parent.document.querySelectorAll(".elementor-element--promotion");

				if (widgets.length > 0) {
					for (var i = 0; i < widgets.length; i++) {
						if (widgets[i].contains(e.target)) {
							var dialog = parent.document.querySelector("#elementor-element--promotion__dialog");
							var icon = widgets[i].querySelector(".icon > i");

							if (icon.classList.toString().indexOf("eaicon") >= 0) {
								dialog.querySelector(".dialog-buttons-action").style.display = "none";

								if (dialog.querySelector(".ea-dialog-buttons-action") === null) {
									var button = document.createElement("a");
									var buttonText = document.createTextNode("Upgrade Essential Addons");

									button.setAttribute("href", "https://wpdeveloper.net/upgrade/ea-pro");
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

							// stop loop
							break;
						}
					}
				}
			});
		}
	});
})(jQuery);
