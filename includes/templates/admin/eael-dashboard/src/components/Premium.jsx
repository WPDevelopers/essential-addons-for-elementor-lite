import React from 'react';
import EnhanceExperience from "./EnhanceExperience.jsx";

function Premium() {
    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__premium-content-wrapper">
                    <EnhanceExperience/>
                    <div className="ea__pro-features flex justify-between items-center">
                        <div className="ea__features-content">
                            <h2>Explore Premiere Pro features</h2>
                            <p className="mb-6">Learn all about the tools and techniques you can use to edit videos,
                                animate titles,
                                add effects, mix sound, and more.
                            </p>
                            <a href="#">
                                <button className="primary-btn changelog-btn">
                                    View Changelog
                                    <i className="eaicon ea-link"></i>
                                </button>
                            </a>
                        </div>
                        <div className="features-img">
                            <img src={localize.eael_dashboard.reactPath + '/images/img-3.png'} alt="img"/>
                        </div>
                    </div>
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
                        <div className="ea__connect-others">
                            <div className="ea__others-icon eaicon-1">
                                <i className="eaicon ea-support"></i>
                            </div>
                            <h5>Automatic Updates & Priority Support</h5>
                            <p>LoremGet access to automatic updates & keep your website up-to-date with
                                constantly developing features. Having any trouble?
                            </p>
                            <a href="#">
                                <button className="underline">
                                    Learn More
                                </button>
                            </a>
                        </div>
                        <div className="ea__connect-others">
                            <div className="ea__others-icon eaicon-1">
                                <i className="eaicon ea-docs"></i>
                            </div>
                            <h5>Automatic Updates & Priority Support</h5>
                            <p>LoremGet access to automatic updates & keep your website up-to-date with
                                constantly developing features. Having any trouble?
                            </p>
                            <a href="#">
                                <button className="underline">
                                    Learn More
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default Premium;