import Header from "./Header.jsx";
import Menu from "./Menu.jsx";
import General from './General.jsx'
import Elements from './Elements.jsx'
import Extensions from './Extensions.jsx'
import Tools from './Tools.jsx'
import Integrations from './Integrations.jsx'
import Premium from './Premium.jsx'
import consumer from "../context";
import Modal from "./Modal.jsx";
import ModalGoPremium from "./ModalGoPremium.jsx";
import Toasts from "./Toasts.jsx";
import Optin from "./Optin.jsx";
import {useEffect, useRef} from "react";
import '../App.css'

function App() {
    const {eaState, eaDispatch} = consumer(),
    wrapperRef = useRef();

    useEffect(() => {
        eaState.isDark ? document.body.classList.add('eael_dash_dark_mode') : document.body.classList.remove('eael_dash_dark_mode');
        eaDispatch({type: 'SET_OFFSET_TOP', payload: wrapperRef.current.offsetTop});
    }, [eaState.isDark]);

    return (
        <>
            {eaState.optinPromo && <Optin/>}
            <section id="ea__dashboard--wrapper" className="ea__dashboard--wrapper" ref={wrapperRef}>
                <Header/>
                <section
                    className={eaState.menu === 'Elements' ? 'ea__section-wrapper ea__main-wrapper flex' : 'ea__section-wrapper ea__main-wrapper flex gap-4'}>
                    <Menu/>
                    {eaState.menu === 'general' ? <General/> : ''}
                    {eaState.menu === 'elements' ? <Elements/> : ''}
                    {eaState.menu === 'extensions' ? <Extensions/> : ''}
                    {eaState.menu === 'tools' ? <Tools/> : ''}
                    {eaState.menu === 'integrations' ? <Integrations/> : ''}
                    {eaState.menu === 'go-premium' ? <Premium/> : ''}
                </section>
                {eaState.modal === 'open' && <Modal/>}
                {eaState.modalGoPremium === 'open' && <ModalGoPremium/>}
                {eaState.toasts && <Toasts/>}
            </section>
        </>
    )
}

export default App
