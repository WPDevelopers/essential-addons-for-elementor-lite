import {useEffect, useReducer} from "react";
import {ContextProvider} from '../context'
import App from "./App.jsx";

function ContextReducer() {

    const eaData = localize.eael_dashboard,
        initValue = {
            menu: 'General',
            integrations: {}
        }

    useEffect(() => {
        Object.keys(eaData.integration_box.list).map((item, index) => {
            initValue.integrations[item] = eaData.integration_box.list[item].status;
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
