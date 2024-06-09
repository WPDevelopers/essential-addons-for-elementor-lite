import {useRef} from "react";
import consumer from "../context";

function LicenseForm() {
    const licenseRef = useRef(),
        {eaState, eaDispatch} = consumer(),
        submitHandler = () => {
            eaDispatch({type: 'ON_PROCESSING', payload: {licenseBtn: true}});

            if (eaState.licenseStatus !== 'valid') {
                eaDispatch({type: 'LICENSE_ACTIVATE', payload: licenseRef.current.value});
            } else {
                eaDispatch({type: 'LICENSE_DEACTIVATE'});
            }
        },
        disabled = eaState.otp === true || eaState.licenseStatus === 'valid';

    return (
        <>
            <div className="ea__license-key ea__invalid-license">
                <div className="license-key-items flex items-center">
                    <i className="ea-dash-icon  ea-key"></i>
                    <input ref={licenseRef} disabled={disabled} className="input-api" type="text"
                           placeholder={eaState.hiddenLicenseKey || "Place Your License Key and Active"}/>
                    <button
                        className={eaState.licenseStatus === 'valid' ? 'primary-btn install-btn deactivated' : 'primary-btn install-btn'}
                        onClick={submitHandler}
                        disabled={eaState.otp === true}>{eaState.licenseStatus === 'valid' ? "Deactivate" : "Active License"}
                    </button>
                </div>
                <div className="invalid-text flex items-center">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.56222 2.33424C5.75217 1.98885 6.24847 1.98885 6.43842 2.33423L10.2472 9.25918C10.4304 9.59243 10.1894 10.0001 9.80907 10.0001H2.19159C1.81129 10.0001 1.57021 9.59243 1.75348 9.25918L5.56222 2.33424ZM7.31462 1.85232C6.74477 0.816152 5.25587 0.816157 4.686 1.85232L0.877266 8.77728C0.327442 9.77698 1.05069 11.0001 2.19159 11.0001H9.80907C10.95 11.0001 11.6732 9.77698 11.1234 8.77728L7.31462 1.85232ZM6.00032 4.00018C6.27647 4.00018 6.50032 4.22404 6.50032 4.50018V6.50018C6.50032 6.77633 6.27647 7.00018 6.00032 7.00018C5.72417 7.00018 5.50032 6.77633 5.50032 6.50018V4.50018C5.50032 4.22404 5.72417 4.00018 6.00032 4.00018ZM6.50032 8.50018C6.50032 8.77633 6.27647 9.00018 6.00032 9.00018C5.72417 9.00018 5.50032 8.77633 5.50032 8.50018C5.50032 8.22403 5.72417 8.00018 6.00032 8.00018C6.27647 8.00018 6.50032 8.22403 6.50032 8.50018Z" fill="#D92D20"/>
                    </svg>
                    <span>Invalid license.</span>
                </div>
            </div>
        </>
    );
}

export default LicenseForm;