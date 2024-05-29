import {useRef} from "react";
import consumer from "../context";

function LicenseForm() {
    const licenseRef = useRef(),
        {eaState, eaDispatch} = consumer(),
        submitHandler = () => {
            eaDispatch({type: 'LICENSE_ACTIVATE', payload: licenseRef.current.value});
        };

    return (
        <>
            <div className="ea__license-key">
                <div className="license-key-items flex items-center">
                    <i className="ea-dash-icon  ea-key"></i>
                    <input ref={licenseRef} className="input-api" type="text"
                           placeholder="Place Your License Key and Active"/>
                    <button className="primary-btn install-btn" onClick={submitHandler}>Active License</button>
                </div>
            </div>
        </>
    );
}

export default LicenseForm;