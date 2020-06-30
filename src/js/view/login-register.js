jQuery(window).on("elementor/frontend/init", function () {
    const EALoginRegister = function ($scope, $) {
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        $(document).on('click', "#eael-lr-reg-toggle", function (e) {
            // alert('got clicked on reg btn ');
            e.preventDefault();
            $loginFormWrapper.hide();
            $regFormWrapper.fadeIn();
        });
        $(document).on('click', "#eael-lr-login-toggle", function (e) {
            // alert('got clicked on login btn ');
            e.preventDefault();
            $regFormWrapper.hide();
            $loginFormWrapper.fadeIn();
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister);
});

