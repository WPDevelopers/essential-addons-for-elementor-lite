import WhatsNew from './WhatsNew.jsx'
import TemplatelyPromo from "./TemplatelyPromo.jsx";
import CommunityBox from "./CommunityBox.jsx";
import SidebarBox from "./SidebarBox.jsx";
import LicenseSection from "./LicenseSection.jsx";
import ElementStatistics from "./ElementStatistics.jsx";
import VideoPromo from "./VideoPromo.jsx";
import consumer from "../context";

function General() {
    const isProActivated = localize.eael_dashboard.is_eapro_activate,
        {eaState} = consumer();

    return (
        <>
            <div className="ea__main-content-wrapper flex gap-4">
                <div className='ea__general-content--wrapper'>
                    {isProActivated ? <LicenseSection/> : <WhatsNew/>}
                    {eaState.isTemplatelyInstalled ? <VideoPromo/> : <TemplatelyPromo/>}
                    <div className="ea__connect-others-wrapper flex gap-4">
                        <CommunityBox index={0}/>
                        <CommunityBox index={1}/>
                        <CommunityBox index={2}/>
                    </div>
                </div>
                <div className="ea__sidebar-info">
                    <div className="ea__sidebar-sticky">
                        {isProActivated || <SidebarBox/>}
                        <ElementStatistics/>
                        <div>
                            <CommunityBox index={3}/>
                        </div>
                    </div>
                    <div></div>
                </div>
            </div>
        </>
    );
}

export default General;