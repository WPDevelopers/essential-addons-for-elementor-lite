import React from 'react';

function TemplatelyPromo() {
    const eaData = localize.eael_dashboard.templately_promo;

    return (
        <>
            <div className="ea__general-content-item templates flex justify-between items-center">
                <div className="templates-content">
                    <h2>{eaData.heading}</h2>
                    <div className="mb-6 flex flex-col gap-4">
                        {eaData.list.map((item, index) => {
                            return <div className="ea__content-details flex gap-2 items-center" key={index}>
                                <span className="check-icon eaicon ea-check"></span>
                                {item}
                            </div>;
                        })}
                    </div>
                    <a href={eaData.button.url}>
                        <button className="primary-btn install-btn">
                            <i className="eaicon ea-install"></i>
                            {eaData.button.label}
                        </button>
                    </a>
                </div>
                <div className="templates-img">
                    <img src={localize.eael_dashboard.reactPath + '/images/img-2.png'} alt="img"/>
                </div>
            </div>
        </>
    );
}

export default TemplatelyPromo;