eael.hooks.addAction("init", "ea", () => {
    if (eael.elementStatusCheck('eaelLoginRegister')) {
        return false;
    }

    const EALoginRegister = function ($scope, $) {

        const $wrap = $scope.find('.eael-login-registration-wrapper');// cache wrapper
        const widgetId = $wrap.data('widget-id');
        const recaptchaSiteKey = $wrap.data('recaptcha-sitekey');
        const recaptchaSiteKeyV3 = $wrap.data('recaptcha-sitekey-v3');
        const isProAndAjaxEnabled = typeof $wrap.data('is-ajax') !== 'undefined' && $wrap.data('is-ajax') == 'yes';
        const loggedInLocation = $scope.find('[data-logged-in-location]').data('logged-in-location');
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const $lostpasswordFormWrapper = $scope.find("#eael-lostpassword-form-wrapper");
        const $resetpasswordFormWrapper = $scope.find("#eael-resetpassword-form-wrapper");
        const loginRcTheme = $loginFormWrapper.data('recaptcha-theme');
        const loginRcSize = $loginFormWrapper.data('recaptcha-size');
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        const regRcTheme = $regFormWrapper.data('recaptcha-theme');
        const regRcSize = $regFormWrapper.data('recaptcha-size');
        const loginRecaptchaVersion = $wrap.data('login-recaptcha-version');
        const registerRecaptchaVersion = $wrap.data('register-recaptcha-version');
        const $regLinkAction = $scope.find('#eael-lr-reg-toggle');
        const $loginLinkAction = $scope.find('#eael-lr-login-toggle');
        const $lostpasswordLinkAction = $scope.find('#eael-lr-lostpassword-toggle');
        const $lostpasswordLoginLinkAction = $scope.find('#eael-lr-login-toggle-lostpassword');
        const $passField = $loginFormWrapper.find('#eael-user-password');
        const $passFieldRegister = $regFormWrapper.find('#form-field-password');
        const $passConfirmFieldRegister = $regFormWrapper.find('#form-field-confirm_pass');
        const $pass1Field = $resetpasswordFormWrapper.find('#eael-pass1');
        const $pass2Field = $resetpasswordFormWrapper.find('#eael-pass2');
        const recaptchaAvailable = (typeof grecaptcha !== 'undefined' && grecaptcha !== null);
        const params = new URLSearchParams(location.search);

        let loginRecaptchaNode = document.getElementById('login-recaptcha-node-' + widgetId);
        let registerRecaptchaNode = document.getElementById('register-recaptcha-node-' + widgetId);
                
        if ( loggedInLocation !== undefined && loggedInLocation !== '' ) {
            location.replace(loggedInLocation);
        }
        if ('form' === $regLinkAction.data('action')) {
            $regLinkAction.on('click', function (e) {
                e.preventDefault();
                if (params.has('eael-lostpassword')){
                    params.delete('eael-lostpassword');
                }
                if (!params.has('eael-register')){
                    params.append('eael-register',1);
                }
                window.history.replaceState({}, '', `${location.pathname}?${params}`);
                $loginFormWrapper.hide();
                $lostpasswordFormWrapper.hide();
                $regFormWrapper.fadeIn();
            });
        }
        if ('form' === $loginLinkAction.data('action')) {
            $loginLinkAction.on('click', function (e) {
                if (params.has('eael-register')){
                    params.delete('eael-register');
                }else if (params.has('eael-lostpassword')){
                    params.delete('eael-lostpassword');
                }
                window.history.replaceState({}, '', `${location.pathname}?${params}`);
                e.preventDefault();
                $regFormWrapper.hide();
                $regFormWrapper.find('.eael-form-validation-container').html('');
                $lostpasswordFormWrapper.hide();                
                $loginFormWrapper.fadeIn();
            });
        }
        if ('form' === $lostpasswordLoginLinkAction.data('action')) {
            $lostpasswordLoginLinkAction.on('click', function (e) {
                if (params.has('eael-register')){
                    params.delete('eael-register');
                } else if (params.has('eael-lostpassword')){
                    params.delete('eael-lostpassword');
                }
                window.history.replaceState({}, '', `${location.pathname}?${params}`);
                e.preventDefault();
                $lostpasswordFormWrapper.hide();
                $regFormWrapper.hide();
                $loginFormWrapper.fadeIn();
            });
        }
        if ('form' === $lostpasswordLinkAction.data('action')) {
            $lostpasswordLinkAction.on('click', function (e) {
                e.preventDefault();
                if (!params.has('eael-lostpassword')){
                    params.append('eael-lostpassword',1);
                }
                window.history.replaceState({}, '', `${location.pathname}?${params}`);
                $lostpasswordFormWrapper.find('.eael-form-validation-container').html('');
                $lostpasswordFormWrapper.find(".eael-lr-form-group").css("display", 'bloock').removeClass('eael-d-none');
                $lostpasswordFormWrapper.find("#eael-lostpassword-submit").css("display", 'bloock').removeClass('eael-d-none');
                
                $regFormWrapper.hide();
                $loginFormWrapper.hide();
                $lostpasswordFormWrapper.fadeIn();
            });
        }

        // Password Visibility Toggle
        $(document).on('click', '#wp-hide-pw, #wp-hide-pw1, #wp-hide-pw2, #wp-hide-pw-register', function (e) {
            let $buttonId = $(this).attr('id');

            switch ($buttonId) {
                case 'wp-hide-pw1':
                    togglePasswordVisibility( $pass1Field );
                    togglePasswordVisibility( $pass2Field );
                    break;
                case 'wp-hide-pw2':
                    togglePasswordVisibility( $pass2Field );
                    break;
                case 'wp-hide-pw-register':
                    togglePasswordVisibility( $passFieldRegister );
                    if($passConfirmFieldRegister){
                        togglePasswordVisibility( $passConfirmFieldRegister );
                    }
                    break;
                default :
                    togglePasswordVisibility( $passField );
                    break;
            }
        });

        function togglePasswordVisibility( $selector ){
            let fieldType = $selector.attr('type') === 'text' ? 'password' : 'text';
            $selector.attr('type', fieldType);
            
            $icon = $selector.parent().find('span');
            if( fieldType === 'password' ){
                $icon.removeClass('dashicons-hidden').addClass('dashicons-visibility');
            }else {
                $icon.removeClass('dashicons-visibility').addClass('dashicons-hidden');
            }
        }

        $(document).ready(function () {
            let eaelGetTokenPromise = new Promise(function (eaelGetTokenResolve, eaelGetTokenReject) {
                eael.getToken();

                let interval = setInterval(function () {
                    if (eael.noncegenerated === true && typeof localize.nonce !== 'undefined') {
                        eaelGetTokenResolve(localize.nonce);
                        clearInterval(interval);
                    }
                }, 100);
            });

            eaelGetTokenPromise.then(function (updatedNonce) {
                $('#eael-login-nonce, #eael-register-nonce, #eael-lostpassword-nonce, #eael-resetpassword-nonce').val(updatedNonce);
            });

            if(!isProAndAjaxEnabled){
                let isRecaptchaVersion3 = false;
                isRecaptchaVersion3 = loginRecaptchaVersion === 'v3' || registerRecaptchaVersion === 'v3' ;
                
                if (recaptchaAvailable && isRecaptchaVersion3) {
                    grecaptcha.ready(function() {
                        grecaptcha.execute(recaptchaSiteKeyV3, { 
                            action: 'eael_login_register_form' 
                        }).then(function (token) {
                            if ($('form input[name="g-recaptcha-response"]', $scope).length === 0) {
                                $('form', $scope).append('<input type="hidden" name="g-recaptcha-response" value="' + token + '">');
                            } else {
                                $('form input[name="g-recaptcha-response"]', $scope).val(token);
                            }
                        });
                    });
                }
            }
        });

        // reCAPTCHA
        function onloadLRcb() {
            if(typeof grecaptcha.render !="function"){
                return false;
            }
            if (loginRecaptchaNode) {
                if( registerRecaptchaVersion !== 'v3' ){
                    try {
                        grecaptcha.render(loginRecaptchaNode, {
                            'sitekey': recaptchaSiteKey,
                            'theme': loginRcTheme,
                            'size': loginRcSize,
                        });
                    } catch ( error ){
                        // duplicate instance
                    }
                }
            }
            if (registerRecaptchaNode) {
                if( loginRecaptchaVersion !== 'v3' ){
                    try {
                        grecaptcha.render(registerRecaptchaNode, {
                            'sitekey': recaptchaSiteKey,
                            'theme': regRcTheme,
                            'size': regRcSize,
                        });
                    } catch ( error ) {
                        // duplicate instance
                    }
                }
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
