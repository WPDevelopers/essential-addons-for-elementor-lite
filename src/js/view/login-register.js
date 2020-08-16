ea.hooks.addAction("init", "ea", () => {
    const EALoginRegister = function ($scope, $) {

        const $wrap = $scope.find('.eael-login-registration-wrapper');// cache wrapper
        //const recaptchaEnabled = $wrap.data('is-recaptcha');
        const widgetId = $wrap.data('widget-id');
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        const $regLinkAction = $scope.find('#eael-lr-reg-toggle');
        const $loginLinkAction = $scope.find('#eael-lr-login-toggle');
        const $passField = $loginFormWrapper.find('#eael-user-password');

        if ('form' === $regLinkAction.data('action')) {
            $regLinkAction.on('click', function (e) {
                e.preventDefault();
                $loginFormWrapper.hide();
                $regFormWrapper.fadeIn();
            });
        }
        if ('form' === $loginLinkAction.data('action')) {
            $loginLinkAction.on('click', function (e) {
                e.preventDefault();
                $regFormWrapper.hide();
                $loginFormWrapper.fadeIn();
            });
        }

        // Password Visibility Toggle
        let pass_shown = false;
        $(document).on('click', '#wp-hide-pw', function (e) {
            let $icon = $(this).find('span');// cache
            if (pass_shown) {
                $passField.attr('type', 'password');
                $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
                pass_shown = false;
            } else {
                $passField.attr('type', 'text');
                $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
                pass_shown = true;
            }
        });


        // Recaptcha
        function onloadLRcb() {
            let loginRecaptchaNode = document.getElementById('login-recaptcha-node-' + widgetId);
            let registerRecaptchaNode = document.getElementById('register-recaptcha-node-' + widgetId);

            if (loginRecaptchaNode) {
                grecaptcha.render(loginRecaptchaNode, {
                    'sitekey': '6Le0KLsZAAAAAHjjwmR3xKsxzQ2toY35Z8ruhp3x',
                });
            }
            if (registerRecaptchaNode) {
                grecaptcha.render(registerRecaptchaNode, {
                    'sitekey': '6Le0KLsZAAAAAHjjwmR3xKsxzQ2toY35Z8ruhp3x',
                });
            }
        }


        $(window).load(function () {
            if (grecaptcha){
                onloadLRcb();
            }
        });
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister);
});

