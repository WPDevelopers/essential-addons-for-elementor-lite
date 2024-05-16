import React from 'react';
import WhatsNew from './WhatsNew.jsx'
import TemplatelyPromo from "./TemplatelyPromo.jsx";

function General() {
    return (
        <>
            <div className="ea__main-content-wrapper flex gap-4">
                <div>
                    <WhatsNew/>
                    <TemplatelyPromo/>
                    <div className="ea__connect-others-wrapper flex gap-4">
                        <div className="ea__connect-others">
                            <div className="ea__others-icon eaicon-1">
                                <i className="eaicon ea-github"></i>
                            </div>
                            <h5>GitHub & Support</h5>
                            <p className="mb-6">Encountering a problem? Seek assistance through live chat or by
                                submitting.
                            </p>
                            <a href="#">
                                <button>
                                    <span className="underline">Create Ticket</span>
                                    <i className="eaicon ea-right-arrow"></i>
                                </button>
                            </a>
                        </div>
                        <div className="ea__connect-others">
                            <div className="ea__others-icon eaicon-2">
                                <i className="eaicon ea-community"></i>
                            </div>
                            <h5>Join Community</h5>
                            <p className="mb-6">Encountering a problem? Seek assistance through live chat or by
                                submitting.
                            </p>
                            <a href="#">
                                <button>
                                    <span className="underline">Join with us</span>
                                    <i className="eaicon ea-right-arrow"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
                <div className="ea__sidebar-info">
                    <div className="ea__sidebar-content">
                        <h5>Unlimited Features</h5>
                        <p>Supercharge your content schedule and</p>
                        <div className="review-wrap">
                            <h6>Review from Real Users</h6>
                            <div className="flex items-center gap-1">
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <span className="reating-details">5/5</span>
                            </div>
                        </div>
                        <a href="#">
                            <button className="upgrade-button">
                                <i className="eaicon ea-crown-1"></i>
                                Upgrade to PRO
                            </button>
                        </a>
                    </div>

                    <div>
                        <div className="ea__connect-others">
                            <div className="ea__others-icon eaicon-3">
                                <i className="eaicon ea-docs"></i>
                            </div>
                            <h5>View knowledgebase</h5>
                            <p className="mb-6">Get started by spending some time with the documentation</p>
                            <a href="#">
                                <button>
                                    <span className="underline">View Docs</span>
                                    <i className="eaicon ea-right-arrow"></i>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}

export default General;