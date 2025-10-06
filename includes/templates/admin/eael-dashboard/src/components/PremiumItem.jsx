import React from 'react';

function PremiumItem(props) {
    const eaData = localize.eael_dashboard.premium_items.list[props.index];

    return (
        <>
            <div className="ea__premium-item">
                <div className="ea__premimu-item-header flex gap-2 items-center">
                    <img src={localize.eael_dashboard.reactPath + eaData.image} alt="img"/>
                </div>
                <div className="ea__premium-item-footer">
                    <h5>{eaData.heading}</h5>
                    <p className="mb-2">{eaData.content}</p>
                    <a href={eaData.button.url} target="_blank">
                        <button className="underline">{eaData.button.label}</button>
                    </a>
                </div>
            </div>
        </>
    );
}

export default PremiumItem;