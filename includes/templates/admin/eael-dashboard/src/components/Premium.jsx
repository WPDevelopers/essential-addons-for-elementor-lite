import React from 'react';
import EnhanceExperience from "./EnhanceExperience.jsx";
import ExploreProFeatures from "./ExploreProFeatures.jsx";
import CommunityBox from "./CommunityBox.jsx";

function Premium() {
    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__premium-content-wrapper">
                    <EnhanceExperience/>
                    <ExploreProFeatures/>
                    <div className="ea__slider-connect">
                        <div className="ea__connect-wrapper flex gap-4">
                            <div className="ea__premium-item">
                                <div className="ea__premimu-item-header flex gap-2 items-center">
                                    <img src={localize.eael_dashboard.reactPath + '/images/img-5.png'} alt="img"/>
                                </div>
                                <div className="ea__premium-item-footer">
                                    <h5>Protected Content </h5>
                                    <p className="mb-2">Restrict access to important data of your
                                        website by setting up user
                                        permissions
                                    </p>
                                    <a href="#">
                                        <button className="underline">View Demo</button>
                                    </a>
                                </div>
                            </div>
                            <div className="ea__premium-item">
                                <div className="ea__premimu-item-header flex gap-2 items-center">
                                    <img src={localize.eael_dashboard.reactPath + '/images/img-6.png'} alt="img"/>
                                </div>
                                <div className="ea__premium-item-footer">
                                    <h5>Smart Post List</h5>
                                    <p className="mb-2">Restrict access to important data of your
                                        website by setting up user
                                        permissions
                                    </p>
                                    <a href="#">
                                        <button className="underline">View Demo</button>
                                    </a>
                                </div>
                            </div>
                            <div className="ea__premium-item">
                                <div className="ea__premimu-item-header flex gap-2 items-center">
                                    <img src={localize.eael_dashboard.reactPath + '/images/img-5.png'} alt="img"/>
                                </div>
                                <div className="ea__premium-item-footer">
                                    <h5>Woo Product Slider</h5>
                                    <p className="mb-2">Restrict access to important data of your
                                        website by setting up user
                                        permissions
                                    </p>
                                    <a href="#">
                                        <button className="underline">View Demo</button>
                                    </a>
                                </div>
                            </div>
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