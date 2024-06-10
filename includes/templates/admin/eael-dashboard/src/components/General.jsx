import WhatsNew from './WhatsNew.jsx'
import TemplatelyPromo from "./TemplatelyPromo.jsx";
import CommunityBox from "./CommunityBox.jsx";
import SidebarBox from "./SidebarBox.jsx";
import LicenseSection from "./LicenseSection.jsx";
import ElementStatistics from "./ElementStatistics.jsx";
import VideoPromo from "./VideoPromo.jsx";

function General() {
    const isProActivated = localize.eael_dashboard.is_eapro_activate,
        isTemplatelyActivate = localize.eael_dashboard.is_templately_activate;

    return (
        <>
            <div className="ea__main-content-wrapper flex gap-4">
                <div>
                    {isProActivated ? <LicenseSection/> : <WhatsNew/>}
                    {isTemplatelyActivate ? <VideoPromo/> : <TemplatelyPromo/>}
                    <div className="ea__connect-others-wrapper flex gap-4">
                        <CommunityBox index={0}/>
                        <CommunityBox index={1}/>
                    </div>
                </div>
                <div className="ea__sidebar-info">
                    {isProActivated || <SidebarBox/>}
                    <ElementStatistics/>
                    <div>
                        <CommunityBox index={2}/>
                    </div>
                </div>
            </div>
        </>
    );
}

export default General;