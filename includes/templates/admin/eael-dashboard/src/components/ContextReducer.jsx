import {useReducer} from "react";
import {ContextProvider, initValue} from '../context'
import App from "./App.jsx";
import {eaAjax, setLsData} from "../helper";

function ContextReducer() {

    const eaData = localize.eael_dashboard;

    const reducer = (state, {type, payload}) => {
        let params, response, licenseStatus, licenseError, otpError, otp, otpEmail, errorMessage,
            hiddenLicenseKey, integrations, elements, modals, toastMessage, toastType, search404;

        switch (type) {
            case 'SET_MENU':
                return {...state, menu: payload};
            case 'SET_OFFSET_TOP':
                return {...state, scrollOffset: payload}
            case 'INTEGRATION_LOADER':
                return {...state, [payload]: true};
            case 'ON_CHANGE_INTEGRATION':
                integrations = {...state.integrations, [payload.key]: payload.value};

                return {...state, integrations, [payload.key]: false};
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
                search404 = Object.keys(payload.value).length === 0;
                return {...state, search: payload.value, search404};
            case 'OPEN_LICENSE_FORM':
                return {...state, licenseFormOpen: payload};
            case 'LICENSE_ACTIVATE':
                licenseError = payload.licenseError;
                otp = payload.otp;
                otpEmail = payload.otpEmail;
                licenseStatus = payload.licenseStatus;
                hiddenLicenseKey = payload.hiddenLicenseKey;
                errorMessage = payload.errorMessage;

                return {
                    ...state,
                    otp,
                    licenseStatus,
                    hiddenLicenseKey,
                    licenseError,
                    otpEmail,
                    errorMessage,
                    btnLoader: '',
                    licenseKey: payload.licenseKey
                };
            case 'OTP_VERIFY':
                licenseStatus = payload.licenseStatus;
                hiddenLicenseKey = payload.hiddenLicenseKey;
                otpError = payload.otpError;
                errorMessage = payload.errorMessage;
                otp = payload.otp;

                return {...state, licenseStatus, hiddenLicenseKey, otpError, errorMessage, otp, btnLoader: ''};
            case 'LICENSE_DEACTIVATE':
                licenseStatus = payload.licenseStatus;
                hiddenLicenseKey = payload.hiddenLicenseKey;
                licenseError = payload.licenseError;
                errorMessage = payload.errorMessage;

                return {...state, licenseStatus, hiddenLicenseKey, licenseError, errorMessage, btnLoader: ''};
            case 'RESEND_OTP':
                toastType = payload.toastType;
                toastMessage = payload.toastMessage;

                return {...state, otp: true, toasts: true, toastType, toastMessage, btnLoader: ''};
            case 'GO_PRO_MODAL':
                return {...state, modalGoPremium: 'open'}
            case 'BUTTON_LOADER':
                return {...state, btnLoader: payload, licenseError: '', otpError: ''}
            case 'OPEN_MODAL':
                return {...state, modal: 'open', modalID: payload.key, modalTitle: payload.title}
            case 'CLOSE_MODAL':
                return {...state, modal: 'close', modalGoPremium: 'close'}
            case 'CLOSE_TOAST':
                return {...state, toasts: false}
            case 'MODAL_ACCORDION':
                return {...state, modalAccordion: payload.key}
            case 'MODAL_ON_CHANGE':
                modals = {...state.modals, [payload.key]: payload.value};
                return {...state, modals};
            case 'SAVE_MODAL_DATA':
                return {...state, ...payload};
            case 'SAVE_ELEMENTS_DATA':
                return {...state, toasts: true, btnLoader: '', ...payload};
            case 'SAVE_TOOLS':
                return {...state, toasts: true, btnLoader: '', ...payload};
            case 'REGENERATE_ASSETS':
                return {...state, toasts: true, ...payload}
            case 'ELEMENTS_CAT':
                return {...state, elementsActivateCatIndex: payload}
            case 'LIGHT_DARK_TOGGLE':
                setLsData('isDark', !state.isDark);
                return {...state, isDark: !state.isDark}
            case 'INSTALL_TEMPLATELY':
                return {...state, isTemplatelyInstalled: true, btnLoader: ''}
            case 'CLOSE_ADMIN_PROMOTION':
                params = {
                    action: 'eael_admin_promotion',
                    security: localize.nonce
                };

                eaAjax(params, true);

                return {...state, optinPromo: false}
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
