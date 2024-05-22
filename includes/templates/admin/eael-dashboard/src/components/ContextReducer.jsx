import {useEffect, useReducer} from "react";
import {ContextProvider} from '../context'
import App from "./App.jsx";

function ContextReducer() {

    const initValue = {};

    const reducer = (state, {type, payload}) => {
        switch (type) {
            case 'ON_CHANGE':
                return {...state, ...payload};
        }
    }

    const [eaState, eaDispatch] = useReducer(reducer, initValue);

    useEffect(() => {

    }, []);

    return (
        <ContextProvider value={{eaState, eaDispatch}}>
            <App/>
        </ContextProvider>
    )
}

export default ContextReducer
