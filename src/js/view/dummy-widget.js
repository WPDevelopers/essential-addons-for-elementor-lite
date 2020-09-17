ea.hooks.addAction("init", "ea", () => {
    const dummyWidget = function ($scope, $) {
        console.log('dummy widget loaded');
    }
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-dummy-widget.default", dummyWidget);
});
