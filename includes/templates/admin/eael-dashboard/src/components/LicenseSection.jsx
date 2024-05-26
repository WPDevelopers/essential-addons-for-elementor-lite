function LicenseSection() {
    return (
        <>
            <div className="ea__general-content-item relative">
                <div className="ea__unlock-license flex gap-4">
                    <div className="ea__others-icon eaicon-1">
                        <i className="eaicon ea-lock"></i>
                    </div>
                    <div className="max-w-454">
                        <h4>Unlock With Your License Key</h4>
                        <p>Enter your license key here, to activate BetterDocs Pro, and get automatic updates
                            and premium support.</p>
                    </div>
                </div>
                <div className="ea__active-license flex gap-4">
                    <div className="ea__others-icon eaicon-active">
                        <i className="eaicon ea-lock"></i>
                    </div>
                    <div className="max-w-454">
                        <h4>Enjoy the pro features & Supports!</h4>
                        <p>You have already activated Essential Blocks Pro. You will able to update the plugin
                            right from your WP dashboard.</p>
                        <span className="activated-btn"><i className="eaicon-v-2 ea-check"></i>Activated</span>
                    </div>
                </div>
                <div className="ea__license-wrapper">
                    <div className="ea__license-content">
                        <h5>
                            How to get license key?
                        </h5>
                        <i className="eaicon ea-dropdown"></i>
                    </div>
                    <div className="ea__license-options-wrapper">
                        <div className="ea__license-step">
                            <div className="ea__license-step-items flex items-center">
                                <span className="step-count">1</span>
                                <p>Log in to <span className="step-details-ex">your account</span> to get your
                                    license key.</p>
                            </div>
                            <div className="ea__license-step-items flex items-center">
                                <span className="step-count">2</span>
                                <p>If you don't yet have a license key, get <span
                                    className="step-details-ex">BetterDocs Pro</span>
                                    now.</p>
                            </div>
                            <div className="ea__license-step-items flex items-center">
                                <span className="step-count">3</span>
                                <p>Copy the license key from your account and paste it below.</p>
                            </div>
                            <div className="ea__license-step-items flex items-center">
                                <span className="step-count">4</span>
                                <p>Click on <span className="step-details-ex">"Activate License"</span> button.</p>
                            </div>
                        </div>
                        <div className="ea__license-key">
                            <div className="license-key-items flex items-center">
                                <i className="eaicon-v-2 ea-key"></i>
                                <input className="input-api" type="text"
                                       placeholder="Place Your License Key and Active"/>
                                <button className="primary-btn install-btn">Active License</button>
                            </div>
                        </div>
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
                                <i className="eaicon-v-2 ea-info">
                                            <span className="tooltip-api">
                                                Check out this <span className="color-ex">guide</span> to verify your
                                                license key. If you need any
                                                assistance with retrieving your License Verification Key, please <span
                                                className="color-ex">contact support.</span>
                                            </span>
                                </i>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default LicenseSection;