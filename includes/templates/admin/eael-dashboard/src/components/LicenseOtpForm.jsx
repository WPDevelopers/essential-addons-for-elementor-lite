import consumer from "../context/index.js";
import {useRef} from "react";

function LicenseOtpForm() {
    const otpRef = useRef(),
        {eaState, eaDispatch} = consumer(),
        submitHandler = () => {
            // eaDispatch({type: 'ON_PROCESSING', payload: {licenseBtn: true}});
            eaDispatch({type: 'OTP_VERIFY', payload: otpRef.current.value});
        };

    return (
        <>
            <div className="ea__license-verify">
                <p>Licence Verification Code has been sent to this <span>{eaState.otpEmail}</span> mail. Please check
                    your email. copy the code and insert it bellow:
                </p>
                <div className="license-key-items flex items-center">
                    <input ref={otpRef} className="input-api" type="text" placeholder="Enter Your Verification Code"/>
                    <button className="primary-btn verify-btn" onClick={submitHandler}>Verify</button>
                </div>
                <p className="resend-content">
                    Haven’t receive email. Code has been sent to this mail.
                    Please <br/>
                    <span className="resend-text">resend button</span>
                    your email. copy the code and insert it meanutes.
                    <i className="ea-dash-icon  ea-info">
                    <span className="tooltip-api">
                        Check out this <span className="color-ex">guide</span> to verify your
                        license key. If you need any
                        assistance with retrieving your License Verification Key, please <span
                        className="color-ex">contact support.</span>
                    </span>
                    </i>
                </p>
            </div>
        </>
    );
}

export default LicenseOtpForm;