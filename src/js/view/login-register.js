ea.hooks.addAction("init", "ea", () => {
    const EALoginRegister = function ($scope, $) {

        const $wrap = $scope.find('.eael-login-registration-wrapper');// cache wrapper
        const widgetId = $wrap.data('widget-id');
        const recaptchaSiteKey = $wrap.data('recaptcha-sitekey');
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const loginRcTheme = $loginFormWrapper.data('recaptcha-theme');
        const loginRcSize = $loginFormWrapper.data('recaptcha-size');
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        const regRcTheme = $regFormWrapper.data('recaptcha-theme');
        const regRcSize = $regFormWrapper.data('recaptcha-size');
        const $regLinkAction = $scope.find('#eael-lr-reg-toggle');
        const $loginLinkAction = $scope.find('#eael-lr-login-toggle');
        const $passField = $loginFormWrapper.find('#eael-user-password');
        const recaptchaAvailable = (typeof grecaptcha !== 'undefined' && grecaptcha !== null);
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


        // reCAPTCHA
        function onloadLRcb() {
            let loginRecaptchaNode = document.getElementById('login-recaptcha-node-' + widgetId);
            let registerRecaptchaNode = document.getElementById('register-recaptcha-node-' + widgetId);
            console.log()
            if(typeof grecaptcha.render !="function"){
                return false;
            }
            if (loginRecaptchaNode) {
                grecaptcha.render(loginRecaptchaNode, {
                    'sitekey': recaptchaSiteKey,
                    'theme': loginRcTheme,
                    'size': loginRcSize,
                });
            }
            if (registerRecaptchaNode) {
                grecaptcha.render(registerRecaptchaNode, {
                    'sitekey': recaptchaSiteKey,
                    'theme': regRcTheme,
                    'size': regRcSize,
                });
            }
        }

        if (recaptchaAvailable && isEditMode){
            // on elementor editor, window load event already fired, so run recaptcha
            onloadLRcb();
        }else{
            // on frontend, load even is yet to fire, so wait and fire recaptcha
            let navData = window.performance.getEntriesByType("navigation");
            if (navData.length > 0 && navData[0].loadEventEnd > 0) {
                if (recaptchaAvailable) {
                    onloadLRcb();
                }
            } else {
                $(window).on('load', function () {
                    if (recaptchaAvailable) {
                        onloadLRcb();
                    }
                });
            }
        }
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister);
});
