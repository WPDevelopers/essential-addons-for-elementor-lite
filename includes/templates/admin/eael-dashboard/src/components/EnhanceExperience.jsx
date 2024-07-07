import React from 'react';

function EnhanceExperience() {
    const eaData = localize.eael_dashboard.enhance_experience;

    return (
        <>
            <div className="ea__pro-elements-content">
                <div>
                    <h3 dangerouslySetInnerHTML={{__html: eaData.heading}}></h3>
                </div>
                <div className="review-wrap flex items-center justify-between">
                    <div className="flex gap-2">
                        <i className="ea-dash-icon ea-star"></i>
                        <h6>3200+</h6>
                        <span className="reating-details">Five Star Reviews</span>
                    </div>
                    <a href={eaData.button.url} target="_blank">
                        <button className="upgrade-button">
                            <i className={"ea-dash-icon " + eaData.button.icon}></i>
                            {eaData.button.label}
                        </button>
                    </a>
                </div>
            </div>
        </>
    );
}

export default EnhanceExperience;