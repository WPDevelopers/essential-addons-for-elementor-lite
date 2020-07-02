jQuery(window).on("elementor/frontend/init", function () {
    const EALoginRegister = function ($scope, $) {
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        const regLinkAction = $('#eael-lr-reg-toggle').data('action');
        const loginLinkAction = $('#eael-lr-login-toggle').data('action');

        if ('form' === regLinkAction) {
            $(document).on('click', '#eael-lr-reg-toggle', function (e) {
                e.preventDefault();
                $loginFormWrapper.hide();
                $regFormWrapper.fadeIn();
            });
        }
        if ('form' === loginLinkAction) {
            $(document).on('click', '#eael-lr-login-toggle', function (e) {
                e.preventDefault();
                $regFormWrapper.hide();
                $loginFormWrapper.fadeIn();
            });
        }
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister);
});

