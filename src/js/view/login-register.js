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
        const lostpasswordRcTheme = $lostpasswordFormWrapper.data('recaptcha-theme');
        const lostpasswordRcSize = $lostpasswordFormWrapper.data('recaptcha-size');
        const loginRecaptchaVersion = $wrap.data('login-recaptcha-version');
        const registerRecaptchaVersion = $wrap.data('register-recaptcha-version');
        const lostpasswordRecaptchaVersion = $wrap.data('lostpassword-recaptcha-version');
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
        let lostpasswordRecaptchaNode = document.getElementById('lostpassword-recaptcha-node-' + widgetId);
                
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

            if(!isProAndAjaxEnabled){
                let isRecaptchaVersion3 = false;
                isRecaptchaVersion3 = loginRecaptchaVersion === 'v3' || registerRecaptchaVersion === 'v3' || lostpasswordRecaptchaVersion === 'v3';

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

        // reCAPTCHA
        function onloadLRcb() {
            if(typeof grecaptcha.render !="function"){
                return false;
            }
            if (loginRecaptchaNode) {
                if( ( registerRecaptchaVersion !== 'v3' ) && ( lostpasswordRecaptchaVersion !== 'v3' ) ){
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
                if( ( loginRecaptchaVersion !== 'v3' ) && ( lostpasswordRecaptchaVersion !== 'v3' ) ){
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
            if (lostpasswordRecaptchaNode) {
                if( ( loginRecaptchaVersion !== 'v3' ) && ( registerRecaptchaVersion !== 'v3' ) ){
                    try {
                        grecaptcha.render(lostpasswordRecaptchaNode, {
                            'sitekey': recaptchaSiteKey,
                            'theme': lostpasswordRcTheme,
                            'size': lostpasswordRcSize,
                        });
                    } catch ( error ) {
                        // duplicate instance
                    }
                }
            }
        }

        // ============================================================
        // Email OTP Verification
        // ============================================================
        const $loginOtp    = $loginFormWrapper.find('.eael-lr-otp-wrapper');
        const $registerOtp = $regFormWrapper.find('.eael-lr-otp-wrapper');

        // The Pro plugin renders an animated character (`.eael-animated-character-wrapper`) above
        // the login form via the `eael/login-register/before-login-form` action. When the OTP UI
        // takes over the form area we need to hide that character too, otherwise it floats over
        // an empty space above the OTP input. We resolve it relative to the OTP wrapper so multiple
        // widgets on the same page are correctly scoped.
        function eaelOtpHideCharacter($otpEl) {
            const $formContainer = $otpEl.closest('.lr-form-wrapper');
            const $character = $formContainer.length
                ? $formContainer.find('.eael-animated-character-wrapper')
                : $otpEl.closest('section').find('.eael-animated-character-wrapper');
            if ($character.length) {
                $character.hide();
            }
        }

        function eaelOtpShow($otpEl, $formEl, response) {
            if (!$otpEl || !$otpEl.length) { return; }
            if (response && response.otp_token) {
                $otpEl.find('.eael-lr-otp-token').val(response.otp_token);
            }
            if (response && response.otp_cooldown) {
                $otpEl.attr('data-cooldown', response.otp_cooldown);
            }
            $otpEl.removeClass('eael-d-none');
            if ($formEl && $formEl.length) {
                $formEl.hide();
            }
            eaelOtpHideCharacter($otpEl);
            eaelOtpStartCooldown($otpEl);
            const $msg = $otpEl.find('.eael-lr-otp-message');
            if (response && response.message) {
                $msg.removeClass('invalid').addClass('valid').text(response.message);
            } else {
                $msg.removeClass('invalid valid').text('');
            }
            $otpEl.find('.eael-lr-otp-input').val('').trigger('focus');
        }

        function eaelOtpStartCooldown($otpEl, overrideRemaining) {
            const fullCooldown = parseInt($otpEl.attr('data-cooldown'), 10) || 60;
            const $resend      = $otpEl.find('.eael-lr-otp-resend');
            const $cdText      = $otpEl.find('.eael-lr-otp-cooldown-text');
            let remaining      = (typeof overrideRemaining === 'number' && !isNaN(overrideRemaining))
                ? overrideRemaining
                : fullCooldown;

            // Bust any previous countdown so resend → restart works without piling up intervals.
            const prevInterval = $otpEl.data('cooldown-interval');
            if (prevInterval) {
                clearInterval(prevInterval);
            }

            if (remaining <= 0) {
                $resend.removeClass('eael-lr-otp-disabled').removeAttr('aria-disabled');
                $cdText.text('');
                return;
            }

            $resend.addClass('eael-lr-otp-disabled').attr('aria-disabled', 'true');
            $cdText.text(' (' + remaining + 's)');

            const interval = setInterval(function () {
                remaining--;
                if (remaining <= 0) {
                    clearInterval(interval);
                    $otpEl.removeData('cooldown-interval');
                    $resend.removeClass('eael-lr-otp-disabled').removeAttr('aria-disabled');
                    $cdText.text('');
                    return;
                }
                $cdText.text(' (' + remaining + 's)');
            }, 1000);
            $otpEl.data('cooldown-interval', interval);
        }

        function eaelOtpVerify($otpEl) {
            const token = $otpEl.find('.eael-lr-otp-token').val();
            const code  = $otpEl.find('.eael-lr-otp-input').val();
            const $msg  = $otpEl.find('.eael-lr-otp-message');
            const $btn  = $otpEl.find('.eael-lr-otp-verify-btn');

            if (!token) {
                $msg.removeClass('valid').addClass('invalid').text('Verification session expired. Please start over.');
                return;
            }
            if (!/^\d{6}$/.test(code)) {
                $msg.removeClass('valid').addClass('invalid').text('Please enter the 6-digit code.');
                return;
            }

            $btn.prop('disabled', true);
            $.ajax({
                url: $otpEl.data('ajax-url'),
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'eael_lr_verify_otp',
                    _eael_otp_nonce: $otpEl.data('otp-nonce'),
                    otp_token: token,
                    otp_code: code
                }
            }).done(function (response) {
                if (response && response.success) {
                    // 1) Show the success message in the OTP message slot, in a clearly successful state.
                    // 2) Disable the input/resend so the user can't keep poking at a verified session.
                    // 3) Hold for ~4s so the user actually reads the message.
                    // 4) Then either redirect (if the server provided a URL) or reload to land them on
                    //    the post-success state of the page.
                    $msg.removeClass('invalid').addClass('valid')
                        .text(response.data.message || 'Verified successfully.');
                    $otpEl.find('.eael-lr-otp-input').prop('disabled', true);
                    $otpEl.find('.eael-lr-otp-resend').addClass('eael-lr-otp-disabled').attr('aria-disabled', 'true');
                    $btn.prop('disabled', true);

                    setTimeout(function () {
                        if (response.data.redirect_to) {
                            window.location.href = response.data.redirect_to;
                        } else {
                            window.location.reload();
                        }
                    }, 4000);
                } else {
                    const m = (response && response.data && response.data.message) ? response.data.message : 'Verification failed.';
                    $msg.removeClass('valid').addClass('invalid').text(m);
                    $btn.prop('disabled', false);
                }
            }).fail(function () {
                $msg.removeClass('valid').addClass('invalid').text('Network error. Please try again.');
                $btn.prop('disabled', false);
            });
        }

        function eaelOtpResend($otpEl) {
            const token = $otpEl.find('.eael-lr-otp-token').val();
            const $msg  = $otpEl.find('.eael-lr-otp-message');
            const $resend = $otpEl.find('.eael-lr-otp-resend');

            if ($resend.hasClass('eael-lr-otp-disabled')) { return; }
            if (!token) {
                $msg.removeClass('valid').addClass('invalid').text('Verification session expired. Please start over.');
                return;
            }

            $.ajax({
                url: $otpEl.data('ajax-url'),
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'eael_lr_send_otp',
                    _eael_otp_nonce: $otpEl.data('otp-nonce'),
                    otp_token: token
                }
            }).done(function (response) {
                if (response && response.success) {
                    if (response.data.cooldown) {
                        $otpEl.attr('data-cooldown', response.data.cooldown);
                    }
                    $msg.removeClass('invalid').addClass('valid').text(response.data.message || 'A new code has been sent.');
                    eaelOtpStartCooldown($otpEl);
                } else {
                    const m = (response && response.data && response.data.message) ? response.data.message : 'Could not resend code.';
                    $msg.removeClass('valid').addClass('invalid').text(m);
                }
            }).fail(function () {
                $msg.removeClass('valid').addClass('invalid').text('Network error. Please try again.');
            });
        }

        // Bind verify and resend handlers.
        $scope.find('.eael-lr-otp-wrapper').each(function () {
            const $otpEl     = $(this);
            const isPreview  = $otpEl.hasClass('eael-lr-otp-editor-preview');
            const hasToken   = !!$otpEl.find('.eael-lr-otp-token').val();
            const isAutoShow = !$otpEl.hasClass('eael-d-none') && hasToken;

            // If the OTP UI was rendered visible (cookie-based reload after non-AJAX submission)
            // OR an editor-only preview is active, surface the wrapper and ensure the parent
            // section isn't being suppressed by the form's default-hide class.
            if (isAutoShow || isPreview) {
                $otpEl.removeClass('eael-d-none');
                $otpEl.closest('section').removeClass('eael-lr-d-none');

                // Hide the corresponding form so only the OTP UI is visible.
                if ($otpEl.data('flow') === 'login') {
                    $loginFormWrapper.find('form#eael-login-form').hide();
                    $loginFormWrapper.removeClass('eael-lr-d-none');
                } else if ($otpEl.data('flow') === 'register') {
                    $regFormWrapper.find('form#eael-register-form').hide();
                    $regFormWrapper.removeClass('eael-lr-d-none');
                }

                // Also hide the Pro animated character if it's present.
                eaelOtpHideCharacter($otpEl);

                if (isAutoShow) {
                    // One-shot cookie — drop it now so a refresh after success doesn't replay.
                    const widgetId   = $otpEl.data('widget-id');
                    const cookiePath = (typeof eaelLR !== 'undefined' && eaelLR.cookiePath) ? eaelLR.cookiePath : '/';
                    document.cookie = 'eael_lr_otp_token_' + widgetId + '=; Max-Age=0; path=' + cookiePath;

                    // Resume the cooldown from where it left off across page reloads.
                    // PHP computes the remaining seconds from the OTP transient (`last_sent` + `cooldown`)
                    // and ships it via data-remaining-cooldown. If 0, the resend link is immediately enabled.
                    const remaining = parseInt($otpEl.attr('data-remaining-cooldown'), 10);
                    eaelOtpStartCooldown($otpEl, isNaN(remaining) ? undefined : remaining);
                }
            }

            $otpEl.on('click', '.eael-lr-otp-verify-btn', function (e) {
                e.preventDefault();
                eaelOtpVerify($otpEl);
            });
            $otpEl.on('keydown', '.eael-lr-otp-input', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    eaelOtpVerify($otpEl);
                }
            });
            $otpEl.on('click', '.eael-lr-otp-resend', function (e) {
                e.preventDefault();
                eaelOtpResend($otpEl);
            });
        });

        // Global AJAX listener: catches any login/register submission (Pro AJAX path or otherwise)
        // whose response carries `otp_required: true`, regardless of which JS layer fired the request.
        // Scoped via .eaelOtp namespace and rebound per-widget so multiple widgets on a page each get
        // routed to their own OTP wrapper.
        $(document).off('ajaxSuccess.eaelOtp_' + widgetId).on('ajaxSuccess.eaelOtp_' + widgetId, function (event, xhr, settings) {
            let response = xhr.responseJSON;
            if (typeof response === 'undefined') {
                try { response = JSON.parse(xhr.responseText); } catch (e) { return; }
            }
            if (!response || !response.success || !response.data || !response.data.otp_required) {
                return;
            }

            // Identify which form fired the request by sniffing the serialized POST body.
            // We deliberately key off the per-form nonce fields (`eael-login-nonce` /
            // `eael-register-nonce`) rather than the submit-button names — jQuery's $form.serialize()
            // does NOT include <input type="submit"> values, so the previous sniff was always false
            // for the Pro AJAX path. The nonce fields are guaranteed hidden inputs and always serialize.
            const body = (typeof settings.data === 'string') ? settings.data : '';
            const isRegister = body.indexOf('eael-register-nonce') !== -1;
            const isLogin    = body.indexOf('eael-login-nonce') !== -1 && !isRegister;

            // Make sure the response is for *this* widget instance (not another on the same page).
            if (body.indexOf('widget_id=' + encodeURIComponent(widgetId)) === -1
                && body.indexOf('widget_id=' + widgetId) === -1) {
                return;
            }

            if (isRegister && $registerOtp.length) {
                eaelOtpShow($registerOtp, $regFormWrapper.find('form#eael-register-form'), response.data);
            } else if (isLogin && $loginOtp.length) {
                eaelOtpShow($loginOtp, $loginFormWrapper.find('form#eael-login-form'), response.data);
            }
        });

        // ============================================================
        // Prevent Pro AJAX handler from reloading when OTP is required
        // ============================================================
        // Pro's eaelAjaxCall() treats any { success: true } response as "done" and schedules
        // location.reload() after 1 s for login forms, regardless of content.
        // When OTP is required the PHP returns wp_send_json_success({ otp_required: true }),
        // so Pro's timer fires and wipes the OTP form off the screen.
        //
        // We intercept via $.ajaxPrefilter — scoped to this widget's login/register
        // submissions — and skip Pro's success callback when otp_required is set.
        // Our ajaxSuccess listener above handles the OTP display, so nothing is lost.
        $.ajaxPrefilter(function (filterOptions) {
            const reqData = typeof filterOptions.data === 'string' ? filterOptions.data : '';
            const isThisWidget = reqData.indexOf('widget_id=' + encodeURIComponent(widgetId)) !== -1
                              || reqData.indexOf('widget_id=' + widgetId) !== -1;
            const isLoginOrRegister = reqData.indexOf('eael-login-nonce') !== -1
                                   || reqData.indexOf('eael-register-nonce') !== -1;
            if (!isThisWidget || !isLoginOrRegister || !filterOptions.success) { return; }

            const origSuccess = filterOptions.success;
            filterOptions.success = function (data) {
                if (data && data.success && data.data && data.data.otp_required) {
                    // OTP response: bail out of Pro's handler so no reload is scheduled.
                    // The ajaxSuccess listener scoped to this widget will show the OTP form.
                    return;
                }
                origSuccess.apply(this, arguments);
            };
        });

        // Also expose the legacy hook for any custom integrations that explicitly fire it.
        if (typeof eael !== 'undefined' && eael.hooks && eael.hooks.addAction) {
            eael.hooks.addAction('eael/lr/ajax-response', 'ea_' + widgetId, function (response, $form) {
                if (!response || !response.success || !response.data || !response.data.otp_required) {
                    return;
                }
                if ($form && $form.attr('id') === 'eael-login-form') {
                    eaelOtpShow($loginOtp, $loginFormWrapper.find('form#eael-login-form'), response.data);
                } else if ($form && $form.attr('id') === 'eael-register-form') {
                    eaelOtpShow($registerOtp, $regFormWrapper.find('form#eael-register-form'), response.data);
                }
            });
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

    function renderTurnstile($scope) {
        if ($scope.find('.eael-login-registration-wrapper').length) {
            let $turnstiles = $scope.find('.cf-turnstile');
            
            if ($turnstiles.length && typeof turnstile !== 'undefined') {
                $turnstiles.each(function() {
                    turnstile.render(this);
                });
            }
        }
    }
    jQuery(document).on('elementor/popup/show', function (event, id, instance) {
        renderTurnstile(instance.$element);
    });
    eael.hooks.addAction("ea-lightbox-triggered", "ea", function($selector) {
        let $scope = jQuery($selector);
        $scope.find('.cf-turnstile').html('');
        renderTurnstile($scope);
    });
});
