function LicenseUnlockBox() {
    return (
        <>
            <div className="ea__unlock-license flex gap-4">
                <div className="ea__others-icon eaicon-unlock">
                    <i className="ea-dash-icon ea-lock"></i>
                </div>
                <div className="max-w-454">
                    <h4>Activate License</h4>
                    <p>Enter your license key here, to activate Essential Addons Pro and get automated updates and
                        premium support. Follow the steps below or get help from the <a
                            href="https://essential-addons.com/docs/verify-essential-addons-pro-license-key/"
                            target="_blank">Validation Guide</a> to activate the key.</p>
                </div>
            </div>
        </>
    );
}

export default LicenseUnlockBox;