function LicenseActivatedBox() {
    return (
        <>
            <div className="ea__active-license flex gap-4">
                <div className="ea__others-icon eaicon-active">
                    <i className="ea-dash-icon ea-lock"></i>
                </div>
                <div className="max-w-454">
                    <h4>You have Unlocked Essential Addons Pro</h4>
                    <p>Congratulations! Now build your dream website effortlessly with more advanced features & priority support.</p>
                    <span className="activated-btn"><i className="ea-dash-icon  ea-check"></i>Activated</span>
                </div>
            </div>
        </>
    );
}

export default LicenseActivatedBox;