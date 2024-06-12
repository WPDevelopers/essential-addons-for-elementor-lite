import consumer from "../context";
import LicenseSteps from "./LicenseSteps.jsx";
import LicenseForm from "./LicenseForm.jsx";
import LicenseOtpForm from "./LicenseOtpForm.jsx";
import LicenseUnlockBox from "./LicenseUnlockBox.jsx";
import LicenseActivatedBox from "./LicenseActivatedBox.jsx";

function LicenseSection() {
    const {eaState, eaDispatch} = consumer(),
        isOpenForm = eaState?.licenseFormOpen === true,
        clickHandler = () => {
            eaDispatch({type: 'OPEN_LICENSE_FORM', payload: !isOpenForm});
        };

    return (
        <>
            <div className="ea__general-content-item license-unlock relative">
                {eaState.licenseStatus !== 'valid' ? <LicenseUnlockBox/> : <LicenseActivatedBox/>}
                <div className="ea__license-wrapper">
                    {eaState.licenseStatus !== 'valid' &&
                        <div className="ea__license-content" onClick={clickHandler}>
                            <h5>
                                How to get license key?
                            </h5>
                            <i className={isOpenForm ? 'ea-dash-icon ea-dropdown rotate-180' : 'ea-dash-icon ea-dropdown'}></i>
                        </div>
                    }

                    {eaState.licenseStatus === 'valid' &&
                        <div className="ea__license-options-wrapper">
                            <LicenseForm/>
                        </div>
                    }

                    {(eaState.licenseStatus !== 'valid' && isOpenForm) &&
                        <div className="ea__license-options-wrapper">
                            <LicenseSteps/>
                            <LicenseForm/>
                            {eaState?.otp === true && <LicenseOtpForm/>}
                        </div>
                    }
                </div>
            </div>
        </>
    );
}

export default LicenseSection;