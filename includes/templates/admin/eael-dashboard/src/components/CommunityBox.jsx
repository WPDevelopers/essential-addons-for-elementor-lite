import React from 'react';

function CommunityBox(props) {
    const eaData = localize.eael_dashboard.community_box[props.index];

    return (
        <>
            <div className="ea__connect-others">
                <div className={'ea__others-icon ' + eaData.icon_color}>
                    <i className={eaData.icon + ' ea-dash-icon'}></i>
                </div>
                <h5>{eaData.heading}</h5>
                <p className="mb-6" dangerouslySetInnerHTML={{__html: eaData.content}}></p>
                {eaData.button === undefined || (<a href={eaData.button.url} target="_blank">
                    <button>
                        <span className="underline">{eaData.button.label}</span>
                        <i className="ea-dash-icon ea-right-arrow"></i>
                    </button>
                </a>)}
            </div>
        </>
    );
}

export default CommunityBox;