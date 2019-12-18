var dataTable = function($scope, $) {
	var $_this = $scope.find(".eael-data-table-wrap"),
		$id = $_this.data("table_id");

	if (typeof enableProSorter !== "undefined" && $.isFunction(enableProSorter)) {
		$(document).ready(function() {
			enableProSorter(jQuery, $_this);
		});
	}

	var responsive = $_this.data("custom_responsive");
	if (true == responsive) {
		var $th = $scope.find(".eael-data-table").find("th");
		var $tbody = $scope.find(".eael-data-table").find("tbody");

		$tbody.find("tr").each(function(i, item) {
			$(item)
				.find("td .td-content-wrapper")
				.each(function(index, item) {
					$(this).prepend('<div class="th-mobile-screen">' + $th.eq(index).html() + "</div>");
				});
		});
	}
};

var Data_Table_Click_Handler = function(panel, model, view) {
	if (event.target.dataset.event == "ea:table:export") {
		// export
		var table = view.el.querySelector("#eael-data-table-" + model.attributes.id);
		var rows = table.querySelectorAll("table tr");
		var csv = [];

		// generate csv
		for (var i = 0; i < rows.length; i++) {
			var row = [];
			var cols = rows[i].querySelectorAll("th, td");

			for (var j = 0; j < cols.length; j++) {
				row.push(JSON.stringify(cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim()));
			}

			csv.push(row.join(","));
		}

		// download
		var csv_file = new Blob([csv.join("\n")], { type: "text/csv" });
		var download_link = parent.document.createElement("a");

		download_link.classList.add("eael-data-table-download-" + model.attributes.id);
		download_link.download = "eael-data-table-" + model.attributes.id + ".csv";
		download_link.href = window.URL.createObjectURL(csv_file);
		download_link.style.display = "none";
		parent.document.body.appendChild(download_link);
		download_link.click();

		parent.document.querySelector(".eael-data-table-download-" + model.attributes.id).remove();
	}
};

var data_table_panel = function(panel, model, view) {
	var handler = Data_Table_Click_Handler.bind(this, panel, model, view);

	panel.el.addEventListener("click", handler);

	panel.currentPageView.on("destroy", function() {
		panel.el.removeEventListener("click", handler);
	});
};

jQuery(window).on("elementor/frontend/init", function() {
	// export table
	if (isEditMode) {
		elementor.hooks.addAction("panel/open_editor/widget/eael-data-table", data_table_panel);
	}

	elementorFrontend.hooks.addAction("frontend/element_ready/eael-data-table.default", dataTable);
});
