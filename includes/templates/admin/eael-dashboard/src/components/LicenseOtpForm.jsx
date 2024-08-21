import consumer from "../context/index.js";
import {useRef} from "react";
import {eaAjax} from "../helper/index.js";

function LicenseOtpForm() {
    const otpRef = useRef(),
        {eaState, eaDispatch} = consumer(),
        licenseManagerConfig = typeof wpdeveloperLicenseManagerConfig === 'undefined' ? {} : wpdeveloperLicenseManagerConfig,
        submitHandler = () => {
            eaDispatch({type: 'BUTTON_LOADER', payload: 'otp'});
            const params = {
                action: 'essential-addons-elementor/license/submit-otp',
                license: eaState.licenseKey,
                otp: otpRef.current.value,
                _nonce: licenseManagerConfig?.nonce
            };

            const request = eaAjax(params, true);
            request.onreadystatechange = () => {
                const response = request.responseText ? JSON.parse(request.responseText) : {};
                let otp,
                    licenseStatus,
                    hiddenLicenseKey = eaState.hiddenLicenseKey,
                    otpError,
                    errorMessage;

                if (response?.success) {
                    otp = false;
                    licenseStatus = response.data.license;
                    hiddenLicenseKey = response.data.license_key;
                } else {
                    otp = true;
                    otpError = true;
                    errorMessage = response?.data?.message;
                }

                eaDispatch({
                    type: 'OTP_VERIFY',
                    payload: {licenseStatus, hiddenLicenseKey, otpError, errorMessage, otp}
                });
            }
        },
        clickHandler = () => {
            eaDispatch({type: 'BUTTON_LOADER', payload: 'resend'});
            const params = {
                action: 'essential-addons-elementor/license/resend-otp',
                _nonce: licenseManagerConfig?.nonce,
                license: eaState.licenseKey
            };

            const request = eaAjax(params, true);
            request.onreadystatechange = () => {
                const response = request.responseText ? JSON.parse(request.responseText) : {};
                let toastType, toastMessage;

                if (response?.success) {
                    toastType = 'success';
                    toastMessage = 'A verification code sent to your email';
                } else {
                    toastType = 'error';
                    toastMessage = response?.data?.message;
                }

                eaDispatch({
                    type: 'RESEND_OTP',
                    payload: {toastType, toastMessage}
                });
            }
        },
        isOtpError = eaState?.otpError === true,
        otpLabel = eaState.btnLoader === 'otp' ? 'Verifying...' : 'Verify',
        resendLabel = eaState.btnLoader === 'resend' ? 'Resending...' : 'Resend button';

    return (
        <>
            <div className={isOtpError ? 'ea__license-verify warning' : 'ea__license-verify'}>
                <p>License Verification code has been sent to this email <span>{eaState.otpEmail}</span>. Please check
                    your email for the code & insert it below</p>
                <div>
                    <div className="license-key-items flex items-center">
                        <input ref={otpRef} className="input-api verify" type="text"
                               placeholder="Enter Your Verification Code"/>
                        <button className="primary-btn verify-btn"
                                onClick={submitHandler}>{otpLabel} {eaState.btnLoader === 'otp' &&
                            <span className="eael_btn_loader"></span>}</button>
                    </div>
                    {isOtpError &&
                        <div className="invalid-text flex items-center">
                            <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                      d="M5.56222 2.33424C5.75217 1.98885 6.24847 1.98885 6.43842 2.33423L10.2472 9.25918C10.4304 9.59243 10.1894 10.0001 9.80907 10.0001H2.19159C1.81129 10.0001 1.57021 9.59243 1.75348 9.25918L5.56222 2.33424ZM7.31462 1.85232C6.74477 0.816152 5.25587 0.816157 4.686 1.85232L0.877266 8.77728C0.327442 9.77698 1.05069 11.0001 2.19159 11.0001H9.80907C10.95 11.0001 11.6732 9.77698 11.1234 8.77728L7.31462 1.85232ZM6.00032 4.00018C6.27647 4.00018 6.50032 4.22404 6.50032 4.50018V6.50018C6.50032 6.77633 6.27647 7.00018 6.00032 7.00018C5.72417 7.00018 5.50032 6.77633 5.50032 6.50018V4.50018C5.50032 4.22404 5.72417 4.00018 6.00032 4.00018ZM6.50032 8.50018C6.50032 8.77633 6.27647 9.00018 6.00032 9.00018C5.72417 9.00018 5.50032 8.77633 5.50032 8.50018C5.50032 8.22403 5.72417 8.00018 6.00032 8.00018C6.27647 8.00018 6.50032 8.22403 6.50032 8.50018Z"
                                      fill="#D92D20"/>
                            </svg>
                            <span>{eaState.errorMessage}</span>
                        </div>
                    }
                </div>
                <div className="resend-content">
                    Haven’t received email? Retry clicking on <span className="resend-text"
                                                                    onClick={clickHandler}>{resendLabel}</span>.
                    Please note that this verification code will expire after 15 minutes.
                    <span className="info-icon-wrap">
                        <i className="ea-dash-icon  ea-info">
                            <span className="tooltip-api">
                                Check out this <a target="_blank" className="color-ex"
                                                  href="https://essential-addons.com/docs/verify-essential-addons-pro-license-key/">guide</a> to verify your license key. If you need any assistance with retrieving your License Verification Key, please <a
                                href="https://wpdeveloper.com/support/" target="_blank" className="color-ex">contact support.</a>
                            </span>
                        </i>
                    </span>
                </div>
            </div>
        </>
    );
}

export default LicenseOtpForm;