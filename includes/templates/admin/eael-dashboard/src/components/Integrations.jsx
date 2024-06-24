import React from 'react';
import IntegrationBox from "./IntegrationBox.jsx";

function Integrations() {
    const eaData = localize.eael_dashboard.integration_box;

    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__integration-content-wrapper">
                    {Object.keys(eaData.list).map((item, index) => {
                        return <IntegrationBox index={item} key={index}/>;
                    })}
                </div>
            </div>
        </>
    );
}

export default Integrations;