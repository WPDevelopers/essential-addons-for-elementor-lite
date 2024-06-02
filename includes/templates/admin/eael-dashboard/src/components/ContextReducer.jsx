import {useEffect, useReducer} from "react";
import {ContextProvider} from '../context'
import App from "./App.jsx";
import {eaAjax} from "../helper";

function ContextReducer() {

    const eaData = localize.eael_dashboard,
        licenseData = typeof wpdeveloperLicenseData === 'undefined' ? {} : wpdeveloperLicenseData,
        initValue = {
            menu: 'General',
            integrations: {},
            extensions: [],
            widgets: {},
            elements: {},
            extensionAll: false,
            widgetAll: false,
            licenseStatus: licenseData?.license_status,
            hiddenLicenseKey: licenseData?.hidden_license_key
        }

    useEffect(() => {
        Object.keys(eaData.integration_box.list).map((item) => {
            initValue.integrations[item] = eaData.integration_box.list[item].status;
        });

        Object.keys(eaData.extensions.list).map((item) => {
            initValue.extensions.push(item);
            initValue.elements[item] = eaData.extensions.list[item].is_activate;
        });

        Object.keys(eaData.widgets).map((item) => {
            initValue.widgets[item] = [];
            Object.keys(eaData.widgets[item].elements).map((subitem) => {
                initValue.widgets[item].push(subitem);
                initValue.elements[subitem] = eaData.widgets[item].elements[subitem].is_activate;
            });
        });
    }, []);

    const reducer = (state, {type, payload}) => {
        const licenseManagerConfig = typeof wpdeveloperLicenseManagerConfig === 'undefined' ? {} : wpdeveloperLicenseManagerConfig;
        let params, response, licenseStatus, licenseError, otp, otpEmail, errorMessage, hiddenLicenseKey;

        switch (type) {
            case 'ON_PROCESSING':
                return {...state, ...payload};
            case 'SET_MENU':
                return {...state, menu: payload};
            case 'ON_CHANGE_INTEGRATION':
                const integrations = {...state.integrations, [payload.key]: payload.value};
                return {...state, integrations};
            case 'ON_CHANGE_ELEMENT':
                const elements = {...state.elements, [payload.key]: payload.value};
                return {...state, elements};
            case 'ON_CHANGE_ALL':
                if (payload.key === 'extensionAll') {
                    state.extensions.map((item) => {
                        state.elements[item] = payload.value;
                    });
                } else if (payload.key === 'widgetAll') {
                    Object.keys(state.widgets).map((item) => {
                        state[item] = payload.value;
                        state.widgets[item].map((subitem) => {
                            state.elements[subitem] = payload.value;
                        });
                    });
                } else if (payload.key === 'searchAll') {
                    Object.keys(state.search).map((item) => {
                        state.elements[item] = payload.value;
                    });
                } else {
                    state.widgets[payload.key].map((item) => {
                        state.elements[item] = payload.value;
                    });
                }
                return {...state, [payload.key]: payload.value};
            case 'ON_SEARCH':
                return {...state, search: payload.value};
            case 'OPEN_LICENSE_FORM':
                return {...state, licenseFormOpen: payload};
            case 'LICENSE_ACTIVATE':
                params = {
                    action: 'essential-addons-elementor/license/activate',
                    license_key: payload,
                    _nonce: licenseManagerConfig?.nonce
                };
                response = eaAjax(params);

                licenseError = false;
                otp = false;
                otpEmail = response.data.customer_email;

                if (response?.success) {
                    switch (response.data.license) {
                        case 'required_otp':
                            otp = true;
                            break;
                        case 'valid':
                            licenseStatus = response.data.license;
                            hiddenLicenseKey = response.data.license_key;
                            break;
                    }
                } else {
                    licenseError = true;
                    errorMessage = response.data.message;
                }

                return {
                    ...state,
                    otp,
                    licenseStatus,
                    hiddenLicenseKey,
                    licenseError,
                    otpEmail,
                    errorMessage,
                    licenseKey: payload
                };
            case 'OTP_VERIFY':
                params = {
                    action: 'essential-addons-elementor/license/submit-otp',
                    license: state.licenseKey,
                    otp: payload,
                    _nonce: licenseManagerConfig?.nonce
                };
                response = eaAjax(params);

                if (response?.success) {
                    otp = false;
                    licenseStatus = response.data.license;
                    hiddenLicenseKey = response.data.license_key;
                } else {
                    otp = true;
                    licenseError = true;
                    errorMessage = response.data.message;
                }

                return {...state, licenseStatus, hiddenLicenseKey, licenseError, errorMessage, otp};
            case 'LICENSE_DEACTIVATE':
                params = {
                    action: 'essential-addons-elementor/license/deactivate',
                    _nonce: licenseManagerConfig?.nonce
                };
                response = eaAjax(params);

                if (response?.success) {
                    licenseStatus = '';
                    hiddenLicenseKey = '';
                } else {
                    licenseError = true;
                    errorMessage = response.data.message;
                }

                return {...state, licenseStatus, hiddenLicenseKey, licenseError, errorMessage};
            case 'RESEND_OTP':
                params = {
                    action: 'essential-addons-elementor/license/resend-otp',
                    _nonce: licenseManagerConfig?.nonce,
                    license: state.licenseKey
                };
                response = eaAjax(params);

                if (response?.success) {
                    otp = true;
                } else {
                    licenseError = true;
                    errorMessage = response.data.message;
                }

                return {...state, otp, licenseError, errorMessage};
            case 'OPEN_MODAL':
                return {...state, modal: 'open', modalID: payload.key, modalTitle: payload.title}
            case 'CLOSE_MODAL':
                return {...state, modal: 'close'}
        }
    }

    const [eaState, eaDispatch] = useReducer(reducer, initValue);

    return (
        <ContextProvider value={{eaState, eaDispatch}}>
            <App/>
        </ContextProvider>
    )
}

export default ContextReducer
