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
    eaXMLHttpRequest = (params) => {
        const request = new XMLHttpRequest();
        request.open('POST', localize.ajaxurl, false);
        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
        request.send(encodeURI(params));
        return JSON.parse(request.responseText);
    };

export const debounce = debouncer;
export const eaAjax = eaXMLHttpRequest;