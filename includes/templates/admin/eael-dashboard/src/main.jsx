import React from 'react'
import ReactDOM from 'react-dom/client'
import {HashRouter} from 'react-router-dom'
import ContextReducer from "./components/ContextReducer.jsx";

ReactDOM.createRoot(document.getElementById('eael-dashboard')).render(
    <React.StrictMode>
        <HashRouter>
            <ContextReducer/>
        </HashRouter>
    </React.StrictMode>,
)
