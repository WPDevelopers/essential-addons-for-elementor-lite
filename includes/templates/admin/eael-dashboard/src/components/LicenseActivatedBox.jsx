function LicenseActivatedBox() {
    return (
        <>
            <div className="ea__active-license flex gap-4">
                <div className="ea__others-icon eaicon-active">
                    <i className="ea-dash-icon ea-lock"></i>
                </div>
                <div className="max-w-454">
                    <h4>Enjoy the Amazing Features & Support</h4>
                    <p>Congratulations! You've unlocked Essential Addons PRO. Now build your dream website effortlessly
                        with more advanced features. If you get stuck, take help from our amazing support team.</p>
                    <span className="activated-btn"><i className="ea-dash-icon  ea-check"></i>Activated</span>
                </div>
            </div>
        </>
    );
}

export default LicenseActivatedBox;