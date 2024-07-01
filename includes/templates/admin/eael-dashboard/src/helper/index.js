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
    getData = (key = null, defaultValue = undefined) => {
        const data = localStorage.getItem('eael_dashboard') ? JSON.parse(localStorage.getItem('eael_dashboard')) : {};
        return key ? data?. [key] ?? defaultValue : data;
    },
    setData = (key, val) => {
        let data = getData();
        data[key] = val;
        localStorage.setItem('eael_dashboard', JSON.stringify(data));
    };

export const debounce = debouncer;
export const eaAjax = eaXMLHttpRequest;
export const getLsData = getData;
export const setLsData = setData;