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
        let loader = false;
        let compareIconSpan = false;
        let compareBtnSpan = false;
        let requestType = false; // compare | remove
        let iconBeforeCompare = '<i class="fas fa-balance-scale-right"></i>';
        //let iconAfterCompare = '<i class="fas fa-balance-scale"></i>';
        let iconAfterCompare = '<i class="fas fa-check-circle"></i>';
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
        const sendData = function sendData(ajaxData, successCb, errorCb, beforeCb, completeCb) {
            $.ajax({
                url: localize.ajaxurl,
                type: 'POST',
                dataType: 'json',
                data: ajaxData,
                beforeSend: beforeCb,
                success: successCb,
                error: errorCb,
                complete: completeCb,
            });
        }

        $doc.on('click', '.eael-wc-compare', function (e) {
            requestType = 'compare'
            let compareBtn = $(this);
            compareBtnSpan = compareBtn.find('.eael-wc-compare-text');
            if (!compareBtnSpan.length){
                compareIconSpan = compareBtn.find('.eael-wc-compare-icon');
            }
            if (!compareIconSpan || !compareIconSpan.length){
                loader = compareBtn.find('.eael-wc-compare-loader');
                loader.show();
            }

            ajaxData.push({
                name: 'product_id',
                value: compareBtn.data('product-id')
            });
            sendData(ajaxData, handleSuccess, handleError);

        });

        $doc.on('click', '.close-modal', function (e) {
            modal.style.visibility = 'hidden';
            modal.style.opacity = '0';
            overlayNode.style.visibility = 'hidden';
            overlayNode.style.opacity = '0';
        });
        $doc.on('click', '.eael-wc-remove', function (e) {
            e.preventDefault();
            let $rBtn = $(this);
            let productId = $rBtn.data('product-id');
            $rBtn.addClass('disable');
            $rBtn.prop('disabled', true);// prevent additional ajax request
            const rmData = Array.from(ajaxData);
            rmData.push({
                name: 'product_id',
                value: productId
            });
            rmData.push({
                name: 'remove_product',
                value: 1
            });
            requestType = 'remove';
            let compareBtn = $('button[data-product-id="' + productId + '"]');
            compareBtnSpan = compareBtn.find('.eael-wc-compare-text');
            if (!compareBtnSpan.length ) {
                compareIconSpan = compareBtn.find('.eael-wc-compare-icon');
            }
            sendData(rmData, handleSuccess, handleError);
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
            if (loader) {
                loader.hide();
            }

            if ('compare' === requestType) {
                if (compareBtnSpan && compareBtnSpan.length){
                    compareBtnSpan.text(localize.i18n.added);
                }else if(compareIconSpan && compareIconSpan.length){
                    compareIconSpan.html(iconAfterCompare)
                }
            }
            if ('remove' === requestType) {
                if (compareBtnSpan && compareBtnSpan.length){
                    compareBtnSpan.text(localize.i18n.compare);
                }else if(compareIconSpan && compareIconSpan.length){
                    compareIconSpan.html(iconBeforeCompare)
                }
            }

        }

        function handleError(xhr, err) {
            console.log(err.toString());
        }


    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eicon-woocommerce.default", productGrid);
});
