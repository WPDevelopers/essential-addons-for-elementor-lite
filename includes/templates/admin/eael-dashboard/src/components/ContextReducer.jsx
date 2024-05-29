import {useEffect, useReducer} from "react";
import {ContextProvider} from '../context'
import App from "./App.jsx";
import {eaAjax} from "../helper";

function ContextReducer() {

    const eaData = localize.eael_dashboard,
        initValue = {
            menu: 'General',
            integrations: {},
            extensions: [],
            widgets: {},
            elements: {},
            extensionAll: false,
            widgetAll: false
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
                const licenseManagerConfig = typeof wpdeveloperLicenseManagerConfig === 'undefined' ? {} : wpdeveloperLicenseManagerConfig,
                    params = {
                        action: 'essential-addons-elementor/license/activate',
                        license_key: payload,
                        _nonce: licenseManagerConfig?.nonce
                    },
                    response = eaAjax(params);

                let licenseError = false,
                    otp = false,
                    otpEmail = '';

                if (response?.success) {
                    otp = response.data.license === 'required_otp';
                    otpEmail = response.data.customer_email;
                } else {
                    licenseError = true;
                }

                return {...state, otp, licenseError, otpEmail};
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
