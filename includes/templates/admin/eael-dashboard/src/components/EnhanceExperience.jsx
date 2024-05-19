import React from 'react';

function EnhanceExperience() {
    const eaData = localize.eael_dashboard.enhance_experience;

    return (
        <>
            <div className="ea__pro-elements-content">
                <div>
                    <h3 dangerouslySetInnerHTML={{__html: eaData.heading}}></h3>
                </div>
                <div className="review-wrap-ex flex justify-between">
                    <div className="flex gap-2">
                        <i className="eaicon ea-link-2"></i>
                        <div>
                            <h6>{eaData.review.label}</h6>
                            <div className="icons flex items-center gap-1">
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <i className="eaicon ea-star"></i>
                                <span className="reating-details">{eaData.review.score}</span>
                            </div>
                        </div>
                    </div>
                    <a href={eaData.button.url}>
                        <button className="upgrade-button">
                            <i className={"eaicon " + eaData.button.icon}></i>
                            {eaData.button.label}
                        </button>
                    </a>
                </div>
            </div>
        </>
    );
}

export default EnhanceExperience;