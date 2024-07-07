import React from 'react';

function ExploreProFeatures() {
    const eaData = localize.eael_dashboard.explore_pro_features,
        assetPath = localize.eael_dashboard.reactPath;

    return (
        <>
            <div className="ea__pro-features flex justify-between items-center">
                <div className="ea__features-content">
                    <h2>{eaData.heading}</h2>
                    <p className="mb-7">{eaData.content}</p>
                    <div className="ea__feature-list-wrap mb-6">
                        {eaData.list.map((item, index) => {
                            return <div className="ea__feature-list-item flex gap-2 mb-4" key={index}>
                                <i className='ea-dash-icon ea-active'></i>
                                <p>{item}</p>
                            </div>;
                        })}
                    </div>
                    <a href={eaData.button.url} target="_blank">
                         <span className="primary-btn changelog-btn">
                           <i className="ea-dash-icon ea-link"></i>
                             {eaData.button.label}
                         </span>
                    </a>
                </div>
                <div className="features-widget-wrapper">
                    {eaData.icons.map((item, index) => {
                        return (<div className="features-widget-item" key={index}>
                            <a href={item.url} target="_blank">
                                <img src={assetPath + item.icon} alt="img"/>
                                <span className="eael-tooltip">{item.label}</span>
                            </a>
                        </div>);
                    })}
                </div>
            </div>
        </>
    );
}

export default ExploreProFeatures;