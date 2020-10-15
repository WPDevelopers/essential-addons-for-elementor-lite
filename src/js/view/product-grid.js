ea.hooks.addAction("init", "ea", () => {
    const productGrid = function ($scope, $) {
        const $wrap = $scope.find('#eael-product-grid');// cache wrapper
        const widgetId = $wrap.data('widget-id');
        const pageId = $wrap.data('page-id');
        const nonce = $wrap.data('nonce');
        const overlay = document.createElement("div");
        overlay.classList.add('wcpc-overlay');
        overlay.setAttribute('id', 'wcpc-overlay');
        const body = document.getElementsByTagName('body')[0];
        body.appendChild(overlay);
        const overlayNode = document.getElementById('wcpc-overlay');
        const $doc = $(document);
        const modalTemplate = `
        <div class="eael-wcpc-modal">
            <i title="Close" class="close-modal far fa-times-circle"></i>
            <div class="modal__content" id="eael_modal_content">
            </div>
        </div>
        `;
        $(body).append(modalTemplate);
        const $modalContentWraper = $('#eael_modal_content');
        const modal = document.getElementsByClassName('eael-wcpc-modal')[0];
        const ajaxData = [
            {
                name: "action",
                value: 'eael_product_grid'
            },
            {
                name: "widget_id",
                value: widgetId
            },
            {
                name: "page_id",
                value: pageId
            },
            {
                name: "nonce",
                value: nonce
            },
        ];
        const sendData = function sendData(ajaxData, successCb, errorCb) {
            $.ajax({
                url: localize.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: ajaxData,
                success: successCb,
                error: errorCb
            });
        }

        $doc.on('click', '.eael-wc-compare', function (e) {
            e.preventDefault();
            ajaxData.push({
                name: 'product_id',
                value: e.target.dataset.productId
            });
            sendData(ajaxData, handleSuccess, handleError);
            //@TODO; show a loader while fetching the table

        });

        $doc.on('click', '.close-modal', function (e) {
            modal.style.visibility = 'hidden';
            modal.style.opacity = '0';
            overlayNode.style.visibility = 'hidden';
            overlayNode.style.opacity = '0';
        });

        $doc.on('click', '.eael-wc-remove', function (e){
            e.preventDefault();
            $(this).prop('disabled', true);// prevent additional ajax request
            const rmData = Array.from(ajaxData);
            rmData.push({
                name: 'product_id',
                value: e.target.dataset.productId
            });
            rmData.push({
                name: 'remove_product',
                value: 1
            });

            sendData(rmData, handleSuccess, handleError);
            //@TODO; show a loader while updating the table
        });



        function handleSuccess(data) {
                const success = (data && data.success);
                if (success) {
                    $modalContentWraper.html(data.data.compare_table)
                    modal.style.visibility = 'visible';
                    modal.style.opacity = '1';
                    overlayNode.style.visibility = 'visible';
                    overlayNode.style.opacity = '1';
                }

        }

        function handleError(xhr, err) {
            let errorHtml = `<p class="eael-form-msg invalid">${err.toString()} </p>`;
        }
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default", productGrid);
});
