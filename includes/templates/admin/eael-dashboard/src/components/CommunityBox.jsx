import React from 'react';

function CommunityBox(props) {
    const eaData = localize.eael_dashboard.community_box[props.index];

    return (
        <>
            <div className="ea__connect-others">
                <div className={'ea__others-icon eaicon-' + (props.index + 1)}>
                    <i className={eaData.icon + 'eaicon'}></i>
                </div>
                <h5>{eaData.heading}</h5>
                <p className="mb-6">{eaData.content}</p>
                <a href={eaData.button.url}>
                    <button>
                        <span className="underline">{eaData.button.label}</span>
                        <i className="eaicon ea-right-arrow"></i>
                    </button>
                </a>
            </div>
        </>
    );
}

export default CommunityBox;