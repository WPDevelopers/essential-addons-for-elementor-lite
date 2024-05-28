import consumer from "../context";
import LicenseSteps from "./LicenseSteps.jsx";
import LicenseForm from "./LicenseForm.jsx";
import LicenseOtpForm from "./LicenseOtpForm.jsx";

function LicenseSection() {
    const licenseData = typeof wpdeveloperLicenseData === 'undefined' ? {} : wpdeveloperLicenseData,
        {eaState, eaDispatch} = consumer(),
        isOpenForm = eaState?.licenseFormOpen === true,
        clickHandler = () => {
            eaDispatch({type: 'OPEN_LICENSE_FORM', payload: !isOpenForm});
        };

    return (
        <>
            <div className="ea__general-content-item relative">
                {licenseData?.license_status !== 'valid' ? <div className="ea__unlock-license flex gap-4">
                    <div className="ea__others-icon eaicon-1">
                        <i className="ea-dash-icon ea-lock"></i>
                    </div>
                    <div className="max-w-454">
                        <h4>Unlock With Your License Key</h4>
                        <p>Enter your license key here, to activate BetterDocs Pro, and get automatic updates
                            and premium support.</p>
                    </div>
                </div> : <div className="ea__active-license flex gap-4">
                    <div className="ea__others-icon eaicon-active">
                        <i className="ea-dash-icon ea-lock"></i>
                    </div>
                    <div className="max-w-454">
                        <h4>Enjoy the pro features & Supports!</h4>
                        <p>You have already activated Essential Blocks Pro. You will able to update the plugin
                            right from your WP dashboard.</p>
                        <span className="activated-btn"><i className="ea-dash-icon  ea-check"></i>Activated</span>
                    </div>
                </div>}
                <div className="ea__license-wrapper">
                    {licenseData?.license_status !== 'valid' &&
                        <div className="ea__license-content" onClick={clickHandler}>
                            <h5>
                                How to get license key?
                            </h5>
                            <i className="ea-dash-icon ea-dropdown"></i>
                        </div>}
                    {licenseData?.license_status === 'valid' &&
                        <div className="ea__license-options-wrapper">
                            <LicenseForm/>
                        </div>
                    }
                    {(licenseData?.license_status !== 'valid' && isOpenForm) && <>
                        <div className="ea__license-options-wrapper">
                            <LicenseSteps/>
                            <LicenseForm/>
                            <LicenseOtpForm/>
                        </div>
                    </>
                    }

                </div>
            </div>
        </>
    );
}

export default LicenseSection;