function LicenseForm() {
    return (
        <>
            <div className="ea__license-key">
                <div className="license-key-items flex items-center">
                    <i className="ea-dash-icon  ea-key"></i>
                    <input className="input-api" type="text"
                           placeholder="Place Your License Key and Active"/>
                    <button className="primary-btn install-btn">Active License</button>
                </div>
            </div>
        </>
    );
}

export default LicenseForm;