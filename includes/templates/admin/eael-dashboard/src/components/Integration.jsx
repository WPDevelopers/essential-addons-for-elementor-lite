import React from 'react';
import IntegrationBox from "./IntegrationBox.jsx";

function Integration() {
    const eaData = localize.eael_dashboard.integration_box;

    return (
        <>
            <div className="ea__elements-nav-content">
                <div className="ea__integration-content-wrapper">
                    {eaData.list.map((item, index) => {
                        return <IntegrationBox index={index} key={index}/>;
                    })}
                </div>
            </div>
        </>
    );
}

export default Integration;