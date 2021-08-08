const QuickView = {
	scope: null,
	init: (scope) => {
		this.scope = scope;
	},
	popupMarkup: () => {
		return `<div style="display: none" class="eael-woocommerce-popup-view eael-product-popup eael-product-zoom-in woocommerce">
                    			<div class="eael-product-modal-bg"></div>
                    			<div class="eael-popup-details-render eael-woo-slider-popup"><div class="eael-preloader"></div></div>
               				 </div>`;
	},
	appandPopup: () => {
		$('body').prepend(this.popupMarkup);
	},
	test:() => {
		console.log("quick view");
	}
}
