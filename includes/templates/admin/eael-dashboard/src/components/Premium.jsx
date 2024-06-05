import React from 'react';
import EnhanceExperience from "./EnhanceExperience.jsx";
import ExploreProFeatures from "./ExploreProFeatures.jsx";
import CommunityBox from "./CommunityBox.jsx";
import PremiumItem from "./PremiumItem.jsx";

function Premium() {
    const eaData = localize.eael_dashboard.premium_items;

    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__premium-content-wrapper">
                    <EnhanceExperience/>
                    <ExploreProFeatures/>
                    <div className="ea__slider-connect">
                        <div className="ea__connect-wrapper flex gap-4">
                            {eaData.list.map((item, index) => {
                                return <PremiumItem index={index} key={index}/>
                            })}
                        </div>
                    </div>
                    <div className="ea__connect-others-wrapper flex gap-4">
                        <CommunityBox index={3}/>
                        <CommunityBox index={4}/>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Premium;