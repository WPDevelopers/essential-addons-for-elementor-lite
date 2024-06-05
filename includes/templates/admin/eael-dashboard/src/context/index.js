import {createContext, useContext} from "react";

const context = createContext();
const consumer = () => {
    return useContext(context);
}

export const ContextProvider = context.Provider;

export default consumer