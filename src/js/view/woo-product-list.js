ea.hooks.addAction("init", "ea", () => {
	const productList = function ($scope, $) {
		ea.hooks.doAction("quickViewAddMarkup",$scope,$);
		const $wrap = $scope.find("#eael-product-list"); // cache wrapper
		const widgetId = $wrap.data("widget-id");
		const pageId = $wrap.data("page-id");
		const nonce = $wrap.data("nonce");
		const overlay = document.createElement("div");
		overlay.classList.add("wcpc-overlay");
		overlay.setAttribute("id", "wcpc-overlay");
		const body = document.getElementsByTagName("body")[0];
		body.appendChild(overlay);
		const overlayNode = document.getElementById("wcpc-overlay");
		const $doc = $(document);
		let loader = false;
		let compareBtn = false;
		let hasCompareIcon = false;
		let compareBtnSpan = false;
		let requestType = false; // compare | remove
		let iconBeforeCompare = '<i class="fas fa-exchange-alt"></i>';
		let iconAfterCompare = '<i class="fas fa-check-circle"></i>';
		const modalTemplate = `
        <div class="eael-wcpc-modal">
            <i title="Close" class="close-modal far fa-times-circle"></i>
            <div class="modal__content" id="eael_modal_content">
            </div>
        </div>
        `;
		$(body).append(modalTemplate);
		const $modalContentWraper = $("#eael_modal_content");
		const modal = document.getElementsByClassName("eael-wcpc-modal")[0];
		const ajaxData = [
			{
				name: "action",
				value: "eael_woo_product_list",
			},
			{
				name: "widget_id",
				value: widgetId,
			},
			{
				name: "page_id",
				value: pageId,
			},
			{
				name: "nonce",
				value: nonce,
			},
		];
		const sendData = function sendData(
			ajaxData,
			successCb,
			errorCb,
			beforeCb,
			completeCb
		) {
			$.ajax({
				url: localize.ajaxurl,
				type: "POST",
				dataType: "json",
				data: ajaxData,
				beforeSend: beforeCb,
				success: successCb,
				error: errorCb,
				complete: completeCb,
			});
		};

        if ($wrap.hasClass('masonry')) {
            $doc.ajaxComplete(function () {
                $(window).trigger('resize');
            });
        }

		$doc.on("click", ".eael-wc-compare", function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			requestType = "compare";
			compareBtn = $(this);
			compareBtnSpan = compareBtn.find(".eael-wc-compare-text");
			if (!compareBtnSpan.length) {
				hasCompareIcon = compareBtn.hasClass("eael-wc-compare-icon");
			}
			if (!hasCompareIcon) {
				loader = compareBtn.find(".eael-wc-compare-loader");
				loader.show();
			}
			let product_id = compareBtn.data("product-id");
			let oldProductIds = localStorage.getItem('productIds');
			if (oldProductIds){
				oldProductIds = JSON.parse(oldProductIds);
				oldProductIds.push(product_id);
			}else {
				oldProductIds = [product_id]
			}

			ajaxData.push({
				name: "product_id",
				value: compareBtn.data("product-id"),
			});
			ajaxData.push({
				name: "product_ids",
				value: JSON.stringify(oldProductIds),
			});
			sendData(ajaxData, handleSuccess, handleError);
		});

		$doc.on("click", ".close-modal", function (e) {
			modal.style.visibility = "hidden";
			modal.style.opacity = "0";
			overlayNode.style.visibility = "hidden";
			overlayNode.style.opacity = "0";
		});

		$doc.on("click", ".eael-wc-remove", function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			let $rBtn = $(this);
			let productId = $rBtn.data("product-id");
			$rBtn.addClass("disable");
			$rBtn.prop("disabled", true); // prevent additional ajax request
			let oldProductIds = localStorage.getItem('productIds');
			if (oldProductIds){
				oldProductIds = JSON.parse(oldProductIds);
				oldProductIds.push(productId);
			}else {
				oldProductIds = [productId]
			}
			const rmData = Array.from(ajaxData);
			rmData.push({
				name: "product_id",
				value: productId,
			});
			rmData.push({
				name: "remove_product",
				value: 1,
			});
			rmData.push({
				name: "product_ids",
				value: JSON.stringify(oldProductIds),
			});

			requestType = "remove";
			let compareBtn = $('button[data-product-id="' + productId + '"]');
			compareBtnSpan = compareBtn.find(".eael-wc-compare-text");
			if (!compareBtnSpan.length) {
				hasCompareIcon = compareBtn.hasClass("eael-wc-compare-icon");
			}
			sendData(rmData, handleSuccess, handleError);
		});

		function handleSuccess(data) {
			const success = data && data.success;
			if (success) {
				$modalContentWraper.html(data.data.compare_table);
				modal.style.visibility = "visible";
				modal.style.opacity = "1";
				overlayNode.style.visibility = "visible";
				overlayNode.style.opacity = "1";
				localStorage.setItem('productIds', JSON.stringify(data.data.product_ids));

			}
			if (loader) {
				loader.hide();
			}

			if ("compare" === requestType) {
				if (compareBtnSpan && compareBtnSpan.length) {
					compareBtnSpan.text(localize.i18n.added);
				} else if (hasCompareIcon) {
					compareBtn.html(iconAfterCompare);
				}
			}
			if ("remove" === requestType) {
				if (compareBtnSpan && compareBtnSpan.length) {
					compareBtnSpan.text(localize.i18n.compare);
				} else if (hasCompareIcon) {
					compareBtn.html(iconBeforeCompare);
				}
			}
		}

		function handleError(xhr, err) {
			console.log(err.toString());
		}

		// pagination
		$(".eael-woo-pagination", $scope).on("click", "a", function (e) {
			e.preventDefault();

			var $this = $(this),
				navClass = $this.closest(".eael-woo-pagination"),
				nth = $this.data("pnumber"),
				lmt = navClass.data("plimit"),
				ajax_url = localize.ajaxurl,
				args = navClass.data("args"),
				widgetid = navClass.data("widgetid"),
				pageid = navClass.data("pageid"),
				widgetclass = ".elementor-element-" + widgetid,
				template_info = navClass.data("template");

			$.ajax({
				url: ajax_url,
				type: "post",
				data: {
					action: "woo_product_pagination_product",
					number: nth,
					limit: lmt,
					args: args,
					widget_id: widgetid,
					page_id: pageid,
					security: localize.nonce,
					templateInfo: template_info,
				},
				beforeSend: function () {
					$(widgetclass).addClass("eael-product-loader");
				},
				success: function (response) {
					$(widgetclass + " .eael-product-list .products").html(response);
					$(widgetclass + " .woocommerce-product-gallery").each(function () {
						$(this).wc_product_gallery();
					});

					$('html, body').animate({
						scrollTop: $(widgetclass + " .eael-product-list").offset().top - 50
					}, 500);

				},
				complete: function () {
					$(widgetclass).removeClass("eael-product-loader");
				},
			});

			$.ajax({
				url: ajax_url,
				type: "post",
				data: {
					action: "woo_product_pagination",
					number: nth,
					limit: lmt,
					args: args,
					widget_id: widgetid,
					page_id: pageid,
					security: localize.nonce,
					template_name: template_info.name,
				},

				success: function (response) {
					$(widgetclass + " .eael-product-list .eael-woo-pagination").html(
						response
					);

					$('html, body').animate({
						scrollTop: $(widgetclass + " .eael-product-list").offset().top - 50
					}, 500);
				},
			});
		});

		ea.hooks.doAction("quickViewPopupViewInit",$scope,$);

		if (isEditMode) {
			$(".eael-product-image-wrap .woocommerce-product-gallery").css(
				"opacity",
				"1"
			);
		}

		const eael_popup = $(document).find(".eael-woocommerce-popup-view");
		if ( eael_popup.length < 1 ) {
			eael_add_popup();
		}


		function eael_add_popup() {
			let markup = `<div style="display: none" class="eael-woocommerce-popup-view eael-product-popup eael-product-zoom-in woocommerce">
                    <div class="eael-product-modal-bg"></div>
                    <div class="eael-popup-details-render eael-woo-slider-popup"><div class="eael-preloader"></div></div>
                </div>`;
			$("body").append(markup);
		}

	};
	elementorFrontend.hooks.addAction(
		"frontend/element_ready/eicon-woocommerce.default",
		productList
	);
});
