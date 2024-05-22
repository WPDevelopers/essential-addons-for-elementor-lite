import {useEffect, useReducer} from "react";
import {ContextProvider} from '../context'
import App from "./App.jsx";

function ContextReducer() {

    const initValue = {
        menu: 'General'
    }

    const reducer = (state, {type, payload}) => {
        switch (type) {
            case 'SET_MENU':
                return {...state, menu: payload};
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
