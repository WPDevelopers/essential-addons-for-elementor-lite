function LicenseActivatedBox() {
    return (
        <>
            <div className="ea__active-license flex gap-4">
                <div className="ea__others-icon eaicon-active">
                    <i className="ea-dash-icon ea-lock"></i>
                </div>
                <div className="max-w-454">
                    <h4>Enjoy the pro features & Supports!</h4>
                    <p>You have already activated Essential Blocks Pro. You will able to update the plugin
                        right from your WP dashboard.</p>
                    <span className="activated-btn"><i className="ea-dash-icon  ea-check"></i>Activated</span>
                </div>
            </div>
        </>
    );
}

export default LicenseActivatedBox;