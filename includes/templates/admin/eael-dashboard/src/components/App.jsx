import Header from "./Header.jsx";
import Menu from "./Menu.jsx";
import General from './General.jsx'
import Elements from './Elements.jsx'
import Extensions from './Extensions.jsx'
import Tools from './Tools.jsx'
import Integration from './Integration.jsx'
import Premium from './Premium.jsx'
import consumer from "../context";
import Modal from "./Modal.jsx";
import ModalRegenerateAssets from "./ModalRegenerateAssets.jsx";
import '../App.css'
import ModalGoPremium from "./ModalGoPremium.jsx";

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
                    {eaState.menu === 'General' ? <General/> : ''}
                    {eaState.menu === 'Elements' ? <Elements/> : ''}
                    {eaState.menu === 'Extensions' ? <Extensions/> : ''}
                    {eaState.menu === 'Tools' ? <Tools/> : ''}
                    {eaState.menu === 'Integration' ? <Integration/> : ''}
                    {eaState.menu === 'Go Premium' ? <Premium/> : ''}
                </section>
                {eaState.modal === 'open' && <Modal/>}
                {eaState.modalRegenerateAssets === 'open' && <ModalRegenerateAssets/>}
                {eaState.modalGoPremium === 'open' && <ModalGoPremium/>}
            </section>
        </>
    )
}

export default App
