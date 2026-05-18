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
import {useLocation} from "react-router-dom";
import '../App.css'

function App() {
    const {eaState, eaDispatch} = consumer(),
    wrapperRef = useRef(),
    location = useLocation();

    useEffect(() => {
        eaState.isDark ? document.body.classList.add('eael_dash_dark_mode') : document.body.classList.remove('eael_dash_dark_mode');
        eaDispatch({type: 'SET_OFFSET_TOP', payload: wrapperRef.current.offsetTop});
    }, [eaState.isDark]);

    // Deep link: sync hash route → menu/modal state (handles page load, back/forward)
    useEffect(() => {
        const validTabs = ['general', 'elements', 'extensions', 'tools', 'integrations', 'go-premium'];
        const modalMatch = location.pathname.match(/^\/(elements|extensions)\/(.+)$/);
        const tabMatch = location.pathname.match(/^\/([^/]+)$/);

        if (modalMatch) {
            const [, type, slug] = modalMatch;
            let found = null;
            if (type === 'elements') {
                const widgets = localize.eael_dashboard.widgets;
                for (const cat of Object.keys(widgets)) {
                    if (widgets[cat].elements && widgets[cat].elements[slug]) {
                        found = {data: widgets[cat].elements[slug]};
                        break;
                    }
                }
            } else {
                const ext = localize.eael_dashboard.extensions.list;
                if (ext[slug]) {
                    found = {data: ext[slug]};
                }
            }
            if (found && found.data.setting?.id) {
                eaDispatch({type: 'SET_MENU', payload: type});
                eaDispatch({type: 'OPEN_MODAL', payload: {key: found.data.setting.id, title: found.data.title}});
            }
        } else if (tabMatch && validTabs.includes(tabMatch[1])) {
            eaDispatch({type: 'SET_MENU', payload: tabMatch[1]});
            eaDispatch({type: 'CLOSE_MODAL'});
        } else if (location.pathname === '/') {
            eaDispatch({type: 'CLOSE_MODAL'});
        }
    }, [location]);

    // Auto-open Business Reviews modal after OAuth callback or actions
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const shouldOpenModal = urlParams.get('eael_business_profile_success') === '1' ||
                                urlParams.get('eael_business_profile_locations_refreshed') === '1' ||
                                urlParams.get('eael_business_profile_disconnected') === '1' ||
                                urlParams.get('eael_business_profile_error') === '1';

        if (shouldOpenModal && localize.eael_dashboard.modal.businessReviewsSetting) {
            // Get the first accordion key (Business Profile API)
            const firstAccordionKey = Object.keys(localize.eael_dashboard.modal.businessReviewsSetting.accordion)[0];

            // Open the modal
            eaDispatch({
                type: 'OPEN_MODAL',
                payload: {
                    key: 'businessReviewsSetting',
                    title: 'Business Reviews'
                }
            });

            // Set the accordion to Business Profile API
            if (firstAccordionKey) {
                eaDispatch({
                    type: 'MODAL_ACCORDION',
                    payload: { key: firstAccordionKey }
                });
            }

            // Clean up URL parameters
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
