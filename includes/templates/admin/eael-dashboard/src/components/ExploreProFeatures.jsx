import React from 'react';

function ExploreProFeatures() {
    return (
        <>
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
        </>
    );
}

export default ExploreProFeatures;