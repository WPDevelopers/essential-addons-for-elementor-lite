eael.hooks.addAction("init", "ea", () => {
    if (eael.elementStatusCheck('eaelLoginRegister')) {
        return false;
    }

    const EALoginRegister = function ($scope, $) {

        const $wrap = $scope.find('.eael-login-registration-wrapper');// cache wrapper
        const widgetId = $wrap.data('widget-id');
        const isProAndAjaxEnabled = typeof $wrap.data('is-ajax') !== 'undefined' && $wrap.data('is-ajax') == 'yes';
        const loggedInLocation = $scope.find('[data-logged-in-location]').data('logged-in-location');
        const $loginFormWrapper = $scope.find("#eael-login-form-wrapper");
        const $lostpasswordFormWrapper = $scope.find("#eael-lostpassword-form-wrapper");
        const $resetpasswordFormWrapper = $scope.find("#eael-resetpassword-form-wrapper");
        const $regFormWrapper = $scope.find("#eael-register-form-wrapper");
        const $regLinkAction = $scope.find('#eael-lr-reg-toggle');
        const $loginLinkAction = $scope.find('#eael-lr-login-toggle');
        const $lostpasswordLinkAction = $scope.find('#eael-lr-lostpassword-toggle');
        const $lostpasswordLoginLinkAction = $scope.find('#eael-lr-login-toggle-lostpassword');
        const $passField = $loginFormWrapper.find('#eael-user-password');
        const $passFieldRegister = $regFormWrapper.find('#form-field-password');
        const $passConfirmFieldRegister = $regFormWrapper.find('#form-field-confirm_pass');
        const $pass1Field = $resetpasswordFormWrapper.find('#eael-pass1');
        const $pass2Field = $resetpasswordFormWrapper.find('#eael-pass2');
        const params = new URLSearchParams(location.search);

        // Initialize unified reCAPTCHA handler
        if (typeof window.EAELRecaptchaHandler !== 'undefined') {
            window.EAELRecaptchaHandler.create($scope);
        }

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

        function getCookie(cname) {
            const name = cname + "=",
                decodedCookie = decodeURIComponent(document.cookie),
                ca = decodedCookie.split(';');

            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == ' ') {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }

        function removeCookie(cname) {
            document.cookie = cname + "=;Max-Age=0;";
        }

        $(document).ready(function () {
            //Validation error message is not show when the Registration page is selected
            $( "[name='eael-login-submit']" ).on( 'click', function() {
                localStorage.setItem( 'eael-is-login-form', 'true' );
            } );
            var eael_get_login_status = localStorage.getItem( 'eael-is-login-form' );
            if( eael_get_login_status === 'true' ) {
                localStorage.removeItem( 'eael-is-login-form' );
                setTimeout(function() {
                    $( '#eael-lr-login-toggle' ).trigger( 'click' );
                },100);
            }

            //
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

            // reCAPTCHA handling is now managed by the unified EAELRecaptchaHandler

            const errormessage = getCookie('eael_login_error_' + widgetId);
            if (errormessage) {
                $('.eael-form-validation-container', $scope).html(`<p class="eael-form-msg invalid">${errormessage}</p>`);
                removeCookie('eael_login_error_' + widgetId);
            }

            //This register error message
            const registerErrorMessage = getCookie('eael_register_errors_' + widgetId);
            if ( registerErrorMessage ) {
                $('.eael-form-validation-container', $scope).html(`<div class="eael-form-msg invalid">${registerErrorMessage}</div>`);
                removeCookie('eael_register_errors_' + widgetId);
            }
        });

        // Old reCAPTCHA handling code removed - now handled by unified EAELRecaptchaHandler
    };
    elementorFrontend.hooks.addAction("frontend/element_ready/eael-login-register.default", EALoginRegister);
});
