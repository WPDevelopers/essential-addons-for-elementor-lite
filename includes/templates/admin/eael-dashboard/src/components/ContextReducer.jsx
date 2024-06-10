import {useReducer} from "react";
import {ContextProvider, initValue} from '../context'
import App from "./App.jsx";
import {eaAjax} from "../helper";

function ContextReducer() {

    const eaData = localize.eael_dashboard;

    const reducer = (state, {type, payload}) => {
        const licenseManagerConfig = typeof wpdeveloperLicenseManagerConfig === 'undefined' ? {} : wpdeveloperLicenseManagerConfig;
        let params, request, response, licenseStatus, licenseError, otpError, otp, otpEmail, errorMessage, hiddenLicenseKey,
            integrations,
            elements, modals;

        switch (type) {
            case 'ON_PROCESSING':
                return {...state, ...payload};
            case 'SET_MENU':
                return {...state, menu: payload};
            case 'ON_CHANGE_INTEGRATION':
                params = {
                    action: 'wpdeveloper_deactivate_plugin',
                    security: localize.nonce,
                    slug: eaData.integration_box.list[payload.key].slug,
                    basename: eaData.integration_box.list[payload.key].basename
                };

                if (payload.value) {
                    params.action = 'wpdeveloper_auto_active_even_not_installed';
                }

                request = eaAjax(params, true);
                request.onreadystatechange = () => {
                    response = JSON.parse(request.responseText);
                }

                integrations = {...state.integrations, [payload.key]: payload.value};
                return {...state, integrations};
            case 'ON_CHANGE_ELEMENT':
                elements = {...state.elements, [payload.key]: payload.value};
                return {...state, elements};
            case 'ON_CHANGE_ALL':
                if (payload.key === 'extensionAll') {
                    state.extensions.map((item) => {
                        if (state.proElements.includes(item)) {
                            return;
                        }

                        state.elements[item] = payload.value;
                    });
                } else if (payload.key === 'widgetAll') {
                    Object.keys(state.widgets).map((item) => {
                        state[item] = payload.value;
                        state.widgets[item].map((subitem) => {
                            if (state.proElements.includes(subitem)) {
                                return;
                            }

                            state.elements[subitem] = payload.value;
                        });
                    });
                } else if (payload.key === 'searchAll') {
                    Object.keys(state.search).map((item) => {
                        if (state.proElements.includes(item)) {
                            return;
                        }

                        state.elements[item] = payload.value;
                    });
                } else {
                    state.widgets[payload.key].map((item) => {
                        if (state.proElements.includes(item)) {
                            return;
                        }

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
                    otpError = true;
                    errorMessage = response.data.message;
                }

                return {...state, licenseStatus, hiddenLicenseKey, otpError, errorMessage, otp};
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
            case 'GO_PRO_MODAL':
                return {...state, modalGoPremium: 'open'}
            case 'OPEN_MODAL':
                return {...state, modal: 'open', modalID: payload.key, modalTitle: payload.title}
            case 'CLOSE_MODAL':
                return {...state, modal: 'close', modalGoPremium: 'close', modalRegenerateAssets: 'close'}
            case 'MODAL_ACCORDION':
                return {...state, modalAccordion: payload.key}
            case 'MODAL_ON_CHANGE':
                modals = {...state.modals, [payload.key]: payload.value};
                return {...state, modals};
            case 'SAVE_MODAL_DATA':
                params = {
                    action: 'save_settings_with_ajax',
                    security: localize.nonce,
                    ...payload
                };

                response = eaAjax(params);

                if (response?.success) {
                    return {...state, modal: 'close', modalRegenerateAssets: 'open'};
                }

                return {...state};
            case 'SAVE_ELEMENTS_DATA':
                params = {
                    action: 'save_settings_with_ajax',
                    security: localize.nonce,
                    elements: true
                };

                Object.keys(state.elements).map((item) => {
                    if (state.elements[item] === true) {
                        params[item] = true;
                    }
                });

                response = eaAjax(params);

                // if (response?.success) {
                //     return {...state, modal: 'close'};
                // }

                return {...state, modalRegenerateAssets: 'open'};
            case 'SAVE_TOOLS':
                params = {
                    action: 'save_settings_with_ajax',
                    security: localize.nonce,
                    [payload.key]: payload.value
                };

                response = eaAjax(params);

                return {...state, modalRegenerateAssets: 'open'};
            case 'REGENERATE_ASSETS':
                params = {
                    action: 'clear_cache_files_with_ajax',
                    security: localize.nonce
                };

                response = eaAjax(params);
                return {...state, modalRegenerateAssets: 'open'}
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
