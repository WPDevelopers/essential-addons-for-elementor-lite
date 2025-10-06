function LicenseSteps() {
    return (
        <>
            <div className="ea__license-step">
                <div className="ea__license-step-items flex">
                    <span className="step-count">1</span>
                    <p>Log in to <a href="https://store.wpdeveloper.com/" target="_blank" className="step-details-ex">your account</a> to get your license key.</p>
                </div>
                <div className="ea__license-step-items flex">
                    <span className="step-count">2</span>
                    <p>If you don't yet have a license key, get <a href="https://essential-addons.com/upgrade-ea-pro"
                        className="step-details-ex" target="_blank">Essential Addons Pro now</a>.</p>
                </div>
                <div className="ea__license-step-items flex">
                    <span className="step-count">3</span>
                    <p>Copy the license key from your account and paste it below.</p>
                </div>
                <div className="ea__license-step-items flex">
                    <span className="step-count">4</span>
                    <p>Click on the <span className="step-details-ex">"Activate License"</span> button.</p>
                </div>
            </div>
        </>
    );
}

export default LicenseSteps;