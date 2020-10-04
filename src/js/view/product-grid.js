ea.hooks.addAction("init", "ea", () => {
    const productGrid = function ($scope, $) {
        const $wrap = $scope.find('#eael-product-grid');// cache wrapper
        const widgetId = $wrap.data('widget-id');
        const pageId = $wrap.data('page-id');
        const overlay = document.createElement("div");
        overlay.classList.add('wcpc-overlay');
        overlay.setAttribute('id', 'wcpc-overlay');
        const body = document.getElementsByTagName('body')[0];
        body.appendChild(overlay);
        const overlayNode = document.getElementById('wcpc-overlay');
        const modal = document.getElementsByClassName('eael-wcpc-modal')[0];
        const ajaxData = [
            {
                name: "action",
                value: 'eael-product-grid'
            },
            {
                name: "widget_id",
                value: widgetId
            },
            {
                name: "page_id",
                value: pageId
            }
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

        $(document).on('click', '.eael-wc-compare', function (e) {
            e.preventDefault();
            handleSuccess({'success': true})
            //sendData(ajaxData, handleSuccess, handleError);
        });

        $(document).on('click', '.close-modal', function (e) {
            modal.style.visibility = 'hidden';
            modal.style.opacity = '0';
            overlayNode.style.visibility = 'hidden';
            overlayNode.style.opacity = '0';
        });



        function handleSuccess(data) {
                console.log('logging data');
                console.log(data);
                const success = (data && data.success);

                if (success) {
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
