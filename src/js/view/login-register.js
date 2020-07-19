jQuery(window).on("elementor/frontend/init", function () {
    const EALoginRegister = function ($scope, $) {
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        const regLinkAction = $('#eael-lr-reg-toggle').data('action');
        const loginLinkAction = $('#eael-lr-login-toggle').data('action');
        const $passField = $loginFormWrapper.find('#eael-user-password');

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

        // Password Visibility Toggle
        let pass_shown = false;
        $(document).on('click', '#wp-hide-pw', function (e) {
            let $icon = $(this).find('span');// cache
            if (pass_shown){
                $passField.attr('type', 'password');
                $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                pass_shown = false;
            }else{
                $passField.attr('type', 'text');
                $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                pass_shown = true;
            }
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister);
});

