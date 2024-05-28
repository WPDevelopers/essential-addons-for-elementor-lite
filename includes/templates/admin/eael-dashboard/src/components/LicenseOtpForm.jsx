function LicenseOtpForm() {
    return (
        <>
            <div className="ea__license-verify">
                <p>Licence Verification Code has been sent to this
                    <span>emo***@wpdeveloper.com</span> mail. Please check your
                    email. copy the code
                    and insert it bellow:
                </p>
                <div className="license-key-items flex items-center">
                    <input className="input-api" type="text"
                           placeholder="Enter Your Verification Code"/>
                    <button className="primary-btn verify-btn">Verify</button>
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