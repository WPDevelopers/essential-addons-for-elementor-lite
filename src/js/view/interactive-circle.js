ea.hooks.addAction("init", "ea", () => {
	const interactiveCircle = function ($scope, $) {

		function setTabActive(evt, tabItem) {
			var i, tabContent, tabLinks;
			tabContent = document.getElementsByClassName("ea-circle-info-content");
			tabLinks = document.getElementsByClassName("ea-circle-info-icon");
			for (i = 0; i < tabLinks.length; i++) {
				tabLinks[i].className = tabLinks[i].className.replace(" active", "");
				tabContent[i].className = tabContent[i].className.replace(
					" active",
					""
				);
			}
			document.getElementById(tabItem).className += " active";
			evt.currentTarget.className += " active";
		}

	};

	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eael-interactive-circle.default",
		interactiveCircle
	);
});
