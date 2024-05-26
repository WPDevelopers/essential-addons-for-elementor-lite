const debouncer = (callback, delay) => {
    let timer
    return function () {
        clearTimeout(timer)
        timer = setTimeout(() => {
            callback();
        }, delay)
    }
}

export const debounce = debouncer;