import {useEffect, useReducer} from "react";
import {ContextProvider} from '../context'
import App from "./App.jsx";

function ContextReducer() {

    const eaData = localize.eael_dashboard,
        initValue = {
            menu: 'General',
            integrations: {},
            extensions: {},
            extensionAll: false
        }

    useEffect(() => {
        Object.keys(eaData.integration_box.list).map((item, index) => {
            initValue.integrations[item] = eaData.integration_box.list[item].status;
        });
        Object.keys(eaData.extensions.list).map((item, index) => {
            initValue.extensions[item] = eaData.extensions.list[item].is_activate;
        });
    }, []);

    const reducer = (state, {type, payload}) => {
        switch (type) {
            case 'SET_MENU':
                return {...state, menu: payload};
                break;
            case 'ON_CHANGE_INTEGRATION':
                const integrations = {...state.integrations, [payload.key]: payload.value};
                return {...state, integrations};
            case 'ON_CHANGE_ELEMENT':
                const extensions = {...state.extensions, [payload.key]: payload.value};
                return {...state, extensions};
            case 'ON_CHANGE_ALL':
                return {...state, [payload.key]: payload.value};
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
