import React from 'react';
import WhatsNew from './WhatsNew.jsx'
import TemplatelyPromo from "./TemplatelyPromo.jsx";
import CommunityBox from "./CommunityBox.jsx";
import SidebarBox from "./SidebarBox.jsx";

function General() {
    return (
        <>
            <div className="ea__main-content-wrapper flex gap-4">
                <div>
                    <WhatsNew/>
                    <TemplatelyPromo/>
                    <div className="ea__connect-others-wrapper flex gap-4">
                        <CommunityBox index={0}/>
                        <CommunityBox index={1}/>
                    </div>
                </div>
                <div className="ea__sidebar-info">
                    <SidebarBox/>
                    <div>
                        <CommunityBox index={2}/>
                    </div>
                </div>
            </div>
        </>
    );
}

export default General;