import React from 'react';

function SidebarBox() {
    return (
        <>
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
        </>
    );
}

export default SidebarBox;