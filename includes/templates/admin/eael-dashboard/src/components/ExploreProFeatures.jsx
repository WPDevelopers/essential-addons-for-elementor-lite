import React from 'react';

function ExploreProFeatures() {
    const eaData = localize.eael_dashboard.explore_pro_features;

    return (
        <>
            <div className="ea__pro-features flex justify-between items-center">
                <div className="ea__features-content">
                    <h2>{eaData.heading}</h2>
                    <p className="mb-6">{eaData.content}</p>
                    <a href={eaData.button.url}>
                        <button className="primary-btn changelog-btn">
                            {eaData.button.label}
                            <i className={ "ea-dash-icon " + eaData.button.icon}></i>
                        </button>
                    </a>
                </div>
                <div className="features-img">
                    <img src={localize.eael_dashboard.reactPath + eaData.image} alt="img"/>
                </div>
            </div>
        </>
    );
}

export default ExploreProFeatures;