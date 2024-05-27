import React from 'react';

function SidebarBox() {
    const eaData = localize.eael_dashboard.sidebar_box;

    return (
        <>
            <div className="ea__sidebar-content">
                <h5>{eaData.heading}</h5>
                <p>{eaData.content}</p>
                <div className="review-wrap">
                    <h6>{eaData.review.label}</h6>
                    <div className="flex items-center gap-1">
                        <i className="ea-dash-icon ea-star"></i>
                        <i className="ea-dash-icon ea-star"></i>
                        <i className="ea-dash-icon ea-star"></i>
                        <i className="ea-dash-icon ea-star"></i>
                        <i className="ea-dash-icon ea-star"></i>
                        <span className="reating-details">{eaData.review.score}</span>
                    </div>
                </div>
                <a href={eaData.button.url}>
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