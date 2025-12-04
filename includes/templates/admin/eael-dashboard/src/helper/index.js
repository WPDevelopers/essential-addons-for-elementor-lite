const debouncer = (callback, delay) => {
        let timer
        return function () {
            clearTimeout(timer)
            timer = setTimeout(() => {
                callback();
            }, delay)
        }
    },
    encodeURI = (obj) => {
        let result = '',
            splitter = '';

        if (typeof obj === 'object') {
            Object.keys(obj).forEach(function (key) {
                result += splitter + key + '=' + encodeURIComponent(obj[key]);
                splitter = '&';
            });
        }
        return result;
    },
    eaXMLHttpRequest = (params, async = false) => {
        const request = new XMLHttpRequest();
        request.open('POST', localize.ajaxurl, async);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.send(encodeURI(params));

        if (async) {
            return request;
        }

        return JSON.parse(request.responseText);
    },
    eaFetchRequest = (params) => {
        const options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: encodeURI(params)
        };

        return fetch(localize.ajaxurl, options)
            .then(response => response.json())
            .then(data => data)
            .catch(error => console.error('Fetch Error:', error));
    },
    getData = (key = null, defaultValue = undefined) => {
        const data = localStorage.getItem('eael_dashboard') ? JSON.parse(localStorage.getItem('eael_dashboard')) : {};
        return key ? data?. [key] ?? defaultValue : data;
    },
    setData = (key, val) => {
        let data = getData();
        data[key] = val;
        localStorage.setItem('eael_dashboard', JSON.stringify(data));
    };

export function asyncDispatch({eaState, eaDispatch}, $type, $args = {}) {
    const eaData = localize.eael_dashboard;
    let $payload, params;

    switch ($type) {
        case 'SAVE_MODAL_DATA':
            params = {
                action: 'eael_save_settings_with_ajax',
                security: localize.nonce,
                ...$args
            };

            $payload = {
                toasts: true,
                toastType: 'error',
                toastMessage: eaData.i18n.toaster_error_msg,
                btnLoader: ''
            };
            eaAjaxFetch(params).then((response) => {
                if (response?.success) {
                    $payload = {
                        ...$payload,
                        modal: 'close',
                        toastType: 'success',
                        toastMessage: eaData.i18n.toaster_success_msg,
                    };
                }
                eaDispatch({type: $type, payload: $payload});
            });
            return;
        case 'SAVE_ELEMENTS_DATA':
            params = {
                action: 'eael_save_settings_with_ajax',
                security: localize.nonce,
                elements: true
            };

            $payload = {
                toastType: 'error',
                toastMessage: eaData.i18n.toaster_error_msg
            }

            Object.keys(eaState.elements).map((item) => {
                if (eaState.elements[item] === true) {
                    params[item] = true;
                }
            });

            eaAjaxFetch(params).then((response) => {
                if (response?.success) {
                    $payload = {
                        ...$payload,
                        toastType: 'success',
                        toastMessage: eaData.i18n.toaster_success_msg
                    }
                }

                eaDispatch({type: $type, payload: $payload});
            });
            return;
        case 'SAVE_TOOLS':
            params = {
                action: 'eael_save_settings_with_ajax',
                security: localize.nonce,
                [$args.key]: $args.value,
            };

            $payload = {
                toastType: 'error',
                toastMessage: eaData.i18n.toaster_error_msg
            }

            eaAjaxFetch(params).then((response) => {
                if (response?.success) {
                    $payload = {
                        ...$payload,
                        toastType: 'success',
                        toastMessage: eaData.i18n.toaster_success_msg
                    }
                }
                eaDispatch({type: $type, payload: $payload});
            });
            return;
        case 'REGENERATE_ASSETS':
            params = {
                action: 'clear_cache_files_with_ajax',
                security: localize.nonce
            };

            $payload = {
                toastType: 'error',
                toastMessage: eaData.i18n.toaster_error_msg
            }

            eaAjaxFetch(params).then((response) => {
                if (response === true) {
                    $payload = {
                        ...$payload,
                        toastType: 'success',
                        toastMessage: 'Assets Regenerated!'
                    }
                }
                eaDispatch({type: $type, payload: $payload});
            });
            return;
        case 'INSTALL_TEMPLATELY':
            params = {
                action: 'wpdeveloper_install_plugin',
                security: localize.nonce,
                slug: 'templately'
            };

            eaAjaxFetch(params).then(() => {
                eaDispatch({type: $type});
            });
    }
}

export const debounce = debouncer;
export const eaAjax = eaXMLHttpRequest;
export const eaAjaxFetch = eaFetchRequest;
export const getLsData = getData;
export const setLsData = setData;