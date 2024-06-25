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
import ModalRegenerateAssets from "./ModalRegenerateAssets.jsx";
import '../App.css'
import ModalGoPremium from "./ModalGoPremium.jsx";
import Toasts from "./Toasts.jsx";

function App() {
    const {eaState} = consumer();

    return (
        <>
            <section id="ea__dashboard--wrapper"
                     className={eaState.isDark ? 'ea__dashboard--wrapper ea-dark-mode' : 'ea__dashboard--wrapper'}>
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
                {eaState.modalRegenerateAssets === 'open' && <ModalRegenerateAssets/>}
                {eaState.modalGoPremium === 'open' && <ModalGoPremium/>}
                {eaState.toasts && <Toasts/>}
            </section>
        </>
    )
}

export default App
