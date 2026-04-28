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

    // Re-open the matching settings modal when an OAuth callback adds a status flag.
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);

        const flagMap = [
            {
                flags: [
                    'eael_business_profile_success',
                    'eael_business_profile_locations_refreshed',
                    'eael_business_profile_disconnected',
                    'eael_business_profile_error',
                ],
                modalID: 'businessReviewsSetting',
                title: 'Business Reviews',
            },
            {
                flags: [
                    'eael_pinterest_success',
                    'eael_pinterest_disconnected',
                    'eael_pinterest_error',
                ],
                modalID: 'pinterestFeedSetting',
                title: 'Pinterest Feed',
            },
        ];

        const match = flagMap.find(entry =>
            entry.flags.some(flag => urlParams.get(flag) === '1')
        );

        if (match && localize.eael_dashboard.modal[match.modalID]) {
            const accordion = localize.eael_dashboard.modal[match.modalID].accordion || {};
            const firstAccordionKey = Object.keys(accordion)[0];

            eaDispatch({
                type: 'OPEN_MODAL',
                payload: { key: match.modalID, title: match.title },
            });

            if (firstAccordionKey) {
                eaDispatch({
                    type: 'MODAL_ACCORDION',
                    payload: { key: firstAccordionKey },
                });
            }

            // Drop the flag so a reload doesn't reopen the modal with a stale message.
            const newUrl = window.location.pathname + '?page=eael-settings';
            window.history.replaceState({}, '', newUrl);
        }
    }, []);

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
