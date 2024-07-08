import React from 'react';

function SidebarBox() {
    const eaData = localize.eael_dashboard.sidebar_box;

    return (
        <>
            <div className="ea__sidebar-content">
                <h5>{eaData.heading}</h5>
                <p>{eaData.content}</p>
                <div className="review-wrap">
                    <div className="flex items-center gap-2">
                        <i className="ea-dash-icon ea-star"></i>
                        <h6>{eaData.review.count}</h6>
                    </div>
                    <span className="reating-details">{eaData.review.label}</span>
                </div>
                <a href={eaData.button.url} target="_blank">
                    <button className="upgrade-button">
                        <i className={'ea-dash-icon ' + eaData.button.icon}></i>
                        {eaData.button.label}
                    </button>
                </a>
            </div>
        </>
    );
}

export default SidebarBox;