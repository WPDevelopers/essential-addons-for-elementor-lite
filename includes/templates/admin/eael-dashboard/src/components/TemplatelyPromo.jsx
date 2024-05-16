import React from 'react';

function TemplatelyPromo() {
    return (
        <>
            <div className="ea__general-content-item templates flex justify-between items-center">
                <div className="templates-content">
                    <h2>Unlock 5000+ Ready Templates</h2>
                    <div className="mb-6 flex flex-col gap-4">
                        <div className="ea__content-details flex gap-2 items-center">
                            <span className="check-icon eaicon ea-check"></span>
                            Stunning, Ready Website Templates
                        </div>
                        <div className="ea__content-details flex gap-2 items-center">
                            <span className="check-icon eaicon ea-check"></span>
                            Add Team Members & Collaborate
                        </div>
                        <div className="ea__content-details flex gap-2 items-center">
                            <span className="check-icon eaicon ea-check"></span>
                            Cloud With Templately WorkSpace
                        </div>
                    </div>
                    <a href="#">
                        <button className="primary-btn install-btn">
                            <i className="eaicon ea-install"></i>
                            Install templately
                        </button>
                    </a>
                </div>
                <div className="templates-img">
                    <img src={localize.eael_dashboard.reactPath + '/images/img-2.png'} alt="img"/>
                </div>
            </div>
        </>
    );
}

export default TemplatelyPromo;