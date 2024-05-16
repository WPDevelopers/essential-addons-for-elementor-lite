import React from 'react';

function EnhanceExperience() {
    return (
        <>
            <div className="ea__pro-elements-content">
                <div>
                    <h3>Enhance Your Elementor Experience By <br/> <b>Unlocking</b> <span
                        className="Advance-color">35+ Advanced PRO</span> <b>Elements</b></h3>
                </div>
                <div className="review-wrap-ex flex justify-between">
                    <div className="flex gap-2">
                        <i className="eaicon ea-link-2"></i>
                        <div>
                            <h6>Review from Real Users</h6>
                            <div className="icons flex items-center gap-1">
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <span className="reating-details">5/5</span>
                            </div>
                        </div>
                    </div>
                    <a href="#">
                        <button className="upgrade-button">
                            <i className="eaicon ea-crown-1"></i>
                            Upgrade to PRO
                        </button>
                    </a>
                </div>
            </div>
        </>
    );
}

export default EnhanceExperience;